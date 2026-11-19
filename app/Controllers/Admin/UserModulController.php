<?php

namespace App\Controllers\Admin;

use App\Models\UserModel;
use App\Models\UsersModulModel;

class UserModulController extends BaseAdminController
{
    public function manajemenuser()
    {
        if ($redirect = $this->ensureModule('manajemenuser')) {
            return $redirect;
        }

        return $this->renderAdmin('administrator/mod_users/view_users', [
            'record' => $this->portalModel->viewOrdering('users', 'username', 'DESC'),
        ]);
    }

    public function tambah_manajemenuser()
    {
        if ($redirect = $this->ensureModule('manajemenuser')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            $username  = trim((string) $this->request->getPost('a'));
            $plainPass = (string) $this->request->getPost('b');
            if ($username === '' || $this->portalModel->findOne('users', ['username' => $username]) !== null) {
                return redirect()->to(base_url('administrator/manajemenuser'));
            }

            $filename  = $this->uploadOptional('f', 'asset/foto_user/', ['gif', 'jpg', 'jpeg', 'png', 'webp'], 1000);
            $idSession = UserModel::generateSessionToken();
            $levelRaw  = trim((string) $this->request->getPost('g'));
            $level     = in_array($levelRaw, ['admin', 'user', 'kontributor'], true) ? $levelRaw : 'user';

            $data = [
                'username'     => $username,
                'password'     => UserModel::hashModernPassword($plainPass),
                'nama_lengkap' => trim((string) $this->request->getPost('c')),
                'email'        => trim((string) $this->request->getPost('d')),
                'no_telp'      => trim((string) $this->request->getPost('e')),
                'level'        => $level,
                'blokir'       => 'N',
                'id_session'   => $idSession,
            ];
            if ($filename !== null) {
                $data['foto'] = $filename;
            }
            $this->portalModel->insert('users', $data);

            $moduls = (array) $this->request->getPost('modul');
            foreach ($moduls as $idModul) {
                if ((int) $idModul > 0) {
                    $this->portalModel->insert('users_modul', [
                        'id_session' => $idSession,
                        'id_modul'   => (int) $idModul,
                    ]);
                }
            }

            return redirect()->to(base_url('administrator/edit_manajemenuser/' . rawurlencode($username)));
        }

        return $this->renderAdmin('administrator/mod_users/view_users_tambah', [
            'record' => $this->portalModel->viewWhereOrdering('modul', ['publish' => 'Y', 'status' => 'user'], 'id_modul', 'DESC'),
        ]);
    }

    public function edit_manajemenuser(string $usernameParam = '')
    {
        if ($redirect = $this->ensureLoggedIn()) {
            return $redirect;
        }

        $targetUser = $usernameParam !== '' ? urldecode($usernameParam) : uri_segment(3);
        if ($this->isPostSubmit()) {
            $postUser = trim((string) ($this->request->getPost('id') ?: $this->request->getPost('a')));
            if (! $this->isAdmin() && $postUser !== $this->currentUsername()) {
                return redirect()->to(base_url('administrator/edit_manajemenuser/' . rawurlencode($this->currentUsername())));
            }

            $existing = $this->portalModel->findOne('users', ['username' => $postUser]);
            if ($existing === null) {
                return redirect()->to(base_url('administrator/home'));
            }

            $filename = $this->uploadOptional('f', 'asset/foto_user/', ['gif', 'jpg', 'jpeg', 'png', 'webp'], 1000);
            $newPass  = (string) ($this->request->getPost('b') ?? '');
            $blokir   = $this->isAdmin()
                ? (in_array(trim((string) ($this->request->getPost('h') ?? '')), ['Y', 'N'], true) ? trim((string) $this->request->getPost('h')) : (string) ($existing['blokir'] ?? 'N'))
                : (string) ($existing['blokir'] ?? 'N');

            $nextUsername = $postUser;
            if ($this->isAdmin()) {
                $reqUser = trim((string) ($this->request->getPost('a') ?: $postUser));
                if ($reqUser !== '' && ($reqUser === $postUser || $this->portalModel->findOne('users', ['username' => $reqUser]) === null)) {
                    $nextUsername = $reqUser;
                }
            }

            $data = [
                'username'     => $nextUsername,
                'nama_lengkap' => trim((string) $this->request->getPost('c')),
                'email'        => trim((string) $this->request->getPost('d')),
                'no_telp'      => trim((string) $this->request->getPost('e')),
                'blokir'       => $blokir,
            ];

            $activeSessionId = (string) ($existing['id_session'] ?? '');
            if ($newPass !== '') {
                $data['password'] = UserModel::hashModernPassword($newPass);
                $newSessionId     = UserModel::generateSessionToken();
                $data['id_session'] = $newSessionId;
                if ($activeSessionId !== '') {
                    $this->portalModel->update('users_modul', ['id_session' => $newSessionId], ['id_session' => $activeSessionId]);
                }
                $activeSessionId = $newSessionId;
            }
            if ($filename !== null) {
                $data['foto'] = $filename;
            }

            $this->portalModel->update('users', $data, ['username' => $postUser]);
            \App\Services\AuthService::resetLiveSessionCache();

            if ($postUser === $this->currentUsername()) {
                session()->set('username', $nextUsername);
                if ($activeSessionId !== '') {
                    session()->set('id_session', $activeSessionId);
                }
            }

            if ($this->isAdmin()) {
                $ids    = $activeSessionId !== '' ? $activeSessionId : (string) ($this->request->getPost('ids') ?? '');
                $moduls = (array) $this->request->getPost('modul');
                foreach ($moduls as $idModul) {
                    if ((int) $idModul > 0 && $ids !== '') {
                        $this->portalModel->insert('users_modul', [
                            'id_session' => $ids,
                            'id_modul'   => (int) $idModul,
                        ]);
                    }
                }
            }

            return redirect()->to(base_url('administrator/edit_manajemenuser/' . rawurlencode($nextUsername)));
        }

        if ($this->currentUsername() !== $targetUser && ! $this->isAdmin()) {
            return redirect()->to(base_url('administrator/edit_manajemenuser/' . rawurlencode($this->currentUsername())));
        }

        $row = $this->portalModel->findOne('users', ['username' => $targetUser]) ?? [];
        $idSess = (string) ($row['id_session'] ?? '');

        return $this->renderAdmin('administrator/mod_users/view_users_edit', [
            'rows'   => $row,
            'record' => $this->portalModel->viewWhereOrdering('modul', ['publish' => 'Y', 'status' => 'user'], 'id_modul', 'DESC'),
            'akses'  => $idSess !== '' ? (new UsersModulModel())->getAssignedModules($idSess) : [],
        ]);
    }

    public function delete_manajemenuser(string $usernameParam = '')
    {
        if ($redirect = $this->ensureModule('manajemenuser')) {
            return $redirect;
        }

        $targetUser = $usernameParam !== '' ? urldecode($usernameParam) : uri_segment(3);
        if ($targetUser !== '' && $targetUser !== $this->currentUsername()) {
            $existing = $this->portalModel->findOne('users', ['username' => $targetUser]);
            if ($existing !== null) {
                $oldSess = (string) ($existing['id_session'] ?? '');
                if ($oldSess !== '') {
                    $this->portalModel->delete('users_modul', ['id_session' => $oldSess]);
                }
                $this->portalModel->delete('users', ['username' => $targetUser]);
                \App\Services\AuthService::resetLiveSessionCache();
            }
        }

        return redirect()->to(base_url('administrator/manajemenuser'));
    }

    public function delete_akses($idUmod = null, string $usernameParam = '')
    {
        if ($redirect = $this->ensureAdmin()) {
            return $redirect;
        }

        $id   = $this->resolveId($idUmod);
        $user = $usernameParam !== '' ? urldecode($usernameParam) : uri_segment(4);
        $this->portalModel->delete('users_modul', ['id_umod' => $id]);

        return redirect()->to(base_url('administrator/edit_manajemenuser/' . rawurlencode($user)));
    }

    public function manajemenmodul()
    {
        if ($redirect = $this->ensureModule('manajemenmodul')) {
            return $redirect;
        }

        return $this->renderAdmin('administrator/mod_modul/view_modul', [
            'record' => $this->scopedList('modul', 'id_modul', 'DESC'),
        ]);
    }

    public function tambah_manajemenmodul()
    {
        if ($redirect = $this->ensureModule('manajemenmodul')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            $this->portalModel->insert('modul', [
                'nama_modul'     => trim((string) $this->request->getPost('a')),
                'username'       => $this->currentUsername(),
                'link'           => trim((string) $this->request->getPost('b')),
                'static_content' => '',
                'gambar'         => '',
                'publish'        => trim((string) $this->request->getPost('c')),
                'status'         => trim((string) $this->request->getPost('e')),
                'aktif'          => trim((string) $this->request->getPost('d')),
                'urutan'         => 0,
                'link_seo'       => '',
            ]);

            return redirect()->to(base_url('administrator/manajemenmodul'));
        }

        return $this->renderAdmin('administrator/mod_modul/view_modul_tambah');
    }

    public function edit_manajemenmodul($id = null)
    {
        if ($redirect = $this->ensureModule('manajemenmodul')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        if ($this->isPostSubmit()) {
            $postId = (int) $this->request->getPost('id');
            $this->portalModel->update('modul', [
                'nama_modul'     => trim((string) $this->request->getPost('a')),
                'username'       => $this->currentUsername(),
                'link'           => trim((string) $this->request->getPost('b')),
                'static_content' => '',
                'gambar'         => '',
                'publish'        => trim((string) $this->request->getPost('c')),
                'status'         => trim((string) $this->request->getPost('e')),
                'aktif'          => trim((string) $this->request->getPost('d')),
                'urutan'         => 0,
                'link_seo'       => '',
            ], $this->ownerWhere('id_modul', $postId));

            return redirect()->to(base_url('administrator/manajemenmodul'));
        }

        return $this->renderAdmin('administrator/mod_modul/view_modul_edit', [
            'rows' => $this->portalModel->findOne('modul', $this->ownerWhere('id_modul', $targetId)) ?? [],
        ]);
    }

    public function delete_manajemenmodul($id = null)
    {
        if ($redirect = $this->ensureModule('manajemenmodul')) {
            return $redirect;
        }

        $this->portalModel->delete('modul', $this->ownerWhere('id_modul', $this->resolveId($id)));

        return redirect()->to(base_url('administrator/manajemenmodul'));
    }
}
