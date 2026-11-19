<?php

namespace App\Controllers\Admin;

use Config\Services;
use Throwable;

class InteraksiController extends BaseAdminController
{
    public function komentarberita()
    {
        if ($redirect = $this->ensureModule('komentarberita')) {
            return $redirect;
        }

        return $this->renderAdmin('administrator/mod_komentar/view_komentar', [
            'record' => $this->portalModel->viewOrdering('komentar', 'id_komentar', 'DESC'),
        ]);
    }

    public function edit_komentarberita($id = null)
    {
        if ($redirect = $this->ensureModule('komentarberita')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        if ($this->isPostSubmit()) {
            $postId = (int) $this->request->getPost('id');
            $this->portalModel->update('komentar', [
                'nama_komentar' => (string) $this->request->getPost('a'),
                'url'           => (string) $this->request->getPost('b'),
                'isi_komentar'  => (string) $this->request->getPost('c'),
                'aktif'         => (string) $this->request->getPost('d'),
                'email'         => (string) $this->request->getPost('e'),
            ], ['id_komentar' => $postId]);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/komentarberita'));
        }

        return $this->renderAdmin('administrator/mod_komentar/view_komentar_edit', [
            'rows' => $this->portalModel->findOne('komentar', ['id_komentar' => $targetId]) ?? [],
        ]);
    }

    public function delete_komentarberita($id = null)
    {
        if ($redirect = $this->ensureModule('komentarberita')) {
            return $redirect;
        }

        $this->portalModel->delete('komentar', ['id_komentar' => $this->resolveId($id)]);
        $this->invalidateAll();

        return redirect()->to(base_url('administrator/komentarberita'));
    }

    public function komentarvideo()
    {
        if ($redirect = $this->ensureModule('komentarvideo')) {
            return $redirect;
        }

        return $this->renderAdmin('administrator/mod_komentarvideo/view_komentar', [
            'record' => $this->portalModel->viewJoinAll('komentarvid', 'video', 'id_video', 'id_komentar', 'DESC'),
        ]);
    }

    public function edit_komentarvideo($id = null)
    {
        if ($redirect = $this->ensureModule('komentarvideo')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        if ($this->isPostSubmit()) {
            $postId = (int) $this->request->getPost('id');
            $this->portalModel->update('komentarvid', [
                'nama_komentar' => (string) $this->request->getPost('a'),
                'url'           => (string) $this->request->getPost('b'),
                'isi_komentar'  => (string) $this->request->getPost('c'),
                'aktif'         => (string) $this->request->getPost('d'),
            ], ['id_komentar' => $postId]);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/komentarvideo'));
        }

        return $this->renderAdmin('administrator/mod_komentarvideo/view_komentar_edit', [
            'rows' => $this->portalModel->findOne('komentarvid', ['id_komentar' => $targetId]) ?? [],
        ]);
    }

    public function delete_komentarvideo($id = null)
    {
        if ($redirect = $this->ensureModule('komentarvideo')) {
            return $redirect;
        }

        $this->portalModel->delete('komentarvid', ['id_komentar' => $this->resolveId($id)]);
        $this->invalidateAll();

        return redirect()->to(base_url('administrator/komentarvideo'));
    }

    public function sensorkomentar()
    {
        if ($redirect = $this->ensureModule('sensorkomentar')) {
            return $redirect;
        }

        return $this->renderAdmin('administrator/mod_sensorkomentar/view_sensorkomentar', [
            'record' => $this->scopedList('katajelek', 'id_jelek', 'DESC'),
        ]);
    }

    public function tambah_sensorkomentar()
    {
        if ($redirect = $this->ensureModule('sensorkomentar')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            $this->portalModel->insert('katajelek', [
                'kata'     => (string) $this->request->getPost('a'),
                'username' => $this->currentUsername(),
                'ganti'    => (string) $this->request->getPost('b'),
            ]);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/sensorkomentar'));
        }

        return $this->renderAdmin('administrator/mod_sensorkomentar/view_sensorkomentar_tambah');
    }

    public function edit_sensorkomentar($id = null)
    {
        if ($redirect = $this->ensureModule('sensorkomentar')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        if ($this->isPostSubmit()) {
            $postId = (int) $this->request->getPost('id');
            $this->portalModel->update('katajelek', [
                'kata'     => (string) $this->request->getPost('a'),
                'username' => $this->currentUsername(),
                'ganti'    => (string) $this->request->getPost('b'),
            ], $this->ownerWhere('id_jelek', $postId));
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/sensorkomentar'));
        }

        return $this->renderAdmin('administrator/mod_sensorkomentar/view_sensorkomentar_edit', [
            'rows' => $this->portalModel->findOne('katajelek', $this->ownerWhere('id_jelek', $targetId)) ?? [],
        ]);
    }

    public function delete_sensorkomentar($id = null)
    {
        if ($redirect = $this->ensureModule('sensorkomentar')) {
            return $redirect;
        }

        $this->portalModel->delete('katajelek', $this->ownerWhere('id_jelek', $this->resolveId($id)));
        $this->invalidateAll();

        return redirect()->to(base_url('administrator/sensorkomentar'));
    }

    public function jajakpendapat()
    {
        if ($redirect = $this->ensureModule('jajakpendapat')) {
            return $redirect;
        }

        return $this->renderAdmin('administrator/mod_jajakpendapat/view_jajakpendapat', [
            'record' => $this->scopedList('poling', 'id_poling', 'DESC'),
        ]);
    }

    public function tambah_jajakpendapat()
    {
        if ($redirect = $this->ensureModule('jajakpendapat')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            $this->portalModel->insert('poling', [
                'pilihan'  => (string) $this->request->getPost('a'),
                'status'   => (string) $this->request->getPost('b'),
                'username' => $this->currentUsername(),
                'rating'   => 0,
                'aktif'    => (string) $this->request->getPost('c'),
            ]);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/jajakpendapat'));
        }

        return $this->renderAdmin('administrator/mod_jajakpendapat/view_jajakpendapat_tambah');
    }

    public function edit_jajakpendapat($id = null)
    {
        if ($redirect = $this->ensureModule('jajakpendapat')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        if ($this->isPostSubmit()) {
            $postId = (int) $this->request->getPost('id');
            $this->portalModel->update('poling', [
                'pilihan' => (string) $this->request->getPost('a'),
                'status'  => (string) $this->request->getPost('b'),
                'aktif'   => (string) $this->request->getPost('c'),
            ], $this->ownerWhere('id_poling', $postId));
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/jajakpendapat'));
        }

        return $this->renderAdmin('administrator/mod_jajakpendapat/view_jajakpendapat_edit', [
            'rows' => $this->portalModel->findOne('poling', $this->ownerWhere('id_poling', $targetId)) ?? [],
        ]);
    }

    public function delete_jajakpendapat($id = null)
    {
        if ($redirect = $this->ensureModule('jajakpendapat')) {
            return $redirect;
        }

        $this->portalModel->delete('poling', $this->ownerWhere('id_poling', $this->resolveId($id)));
        $this->invalidateAll();

        return redirect()->to(base_url('administrator/jajakpendapat'));
    }

    public function ym()
    {
        if ($redirect = $this->ensureModule('ym')) {
            return $redirect;
        }

        return $this->renderAdmin('administrator/mod_ym/view_ym', [
            'record' => $this->portalModel->viewOrdering('mod_ym', 'id', 'DESC'),
        ]);
    }

    public function tambah_ym()
    {
        if ($redirect = $this->ensureModule('ym')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            $this->portalModel->insert('mod_ym', [
                'nama'     => trim((string) $this->request->getPost('a')),
                'username' => seo_title((string) $this->request->getPost('b')),
                'ym_icon'  => (string) $this->request->getPost('c'),
            ]);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/ym'));
        }

        return $this->renderAdmin('administrator/mod_ym/view_ym_tambah');
    }

    public function edit_ym($id = null)
    {
        if ($redirect = $this->ensureModule('ym')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        if ($this->isPostSubmit()) {
            $postId = (int) $this->request->getPost('id');
            $this->portalModel->update('mod_ym', [
                'nama'     => trim((string) $this->request->getPost('a')),
                'username' => seo_title((string) $this->request->getPost('b')),
                'ym_icon'  => (string) $this->request->getPost('c'),
            ], ['id' => $postId]);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/ym'));
        }

        return $this->renderAdmin('administrator/mod_ym/view_ym_edit', [
            'rows' => $this->portalModel->findOne('mod_ym', ['id' => $targetId]) ?? [],
        ]);
    }

    public function delete_ym($id = null)
    {
        if ($redirect = $this->ensureModule('ym')) {
            return $redirect;
        }

        $this->portalModel->delete('mod_ym', ['id' => $this->resolveId($id)]);
        $this->invalidateAll();

        return redirect()->to(base_url('administrator/ym'));
    }

    public function pesanmasuk()
    {
        if ($redirect = $this->ensureModule('pesanmasuk')) {
            return $redirect;
        }

        return $this->renderAdmin('administrator/mod_pesanmasuk/view_pesanmasuk', [
            'record' => $this->portalModel->viewOrdering('hubungi', 'id_hubungi', 'DESC'),
        ]);
    }

    public function detail_pesanmasuk($id = null)
    {
        if ($redirect = $this->ensureModule('pesanmasuk')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        $this->portalModel->update('hubungi', ['dibaca' => 'Y'], ['id_hubungi' => $targetId]);

        if ($this->isPostSubmit()) {
            $email   = trim((string) $this->request->getPost('b'));
            $subject = trim((string) $this->request->getPost('c'));
            $message = (string) $this->request->getPost('isi') . ' <br><hr><br> ' . (string) $this->request->getPost('d');

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                try {
                    $iden   = $this->portalContext->getIdentitas();
                    $mailer = Services::email();
                    $mailer->setFrom((string) ($iden['email'] ?? 'diskominfo@mail.pematangsiantar.go.id'), (string) ($iden['nama_website'] ?? 'Kecamatan Siantar Barat'));
                    $mailer->setTo($email);
                    $mailer->setSubject($subject);
                    $mailer->setMessage($message);
                    $mailer->setMailType('html');
                    $mailer->send();
                } catch (Throwable $e) {
                    log_message('warning', 'Reply email send failed: ' . $e->getMessage());
                }
            }
        }

        return $this->renderAdmin('administrator/mod_pesanmasuk/view_pesanmasuk_detail', [
            'rows' => $this->portalModel->findOne('hubungi', ['id_hubungi' => $targetId]) ?? [],
        ]);
    }

    public function delete_pesanmasuk($id = null)
    {
        if ($redirect = $this->ensureModule('pesanmasuk')) {
            return $redirect;
        }

        $this->portalModel->delete('hubungi', ['id_hubungi' => $this->resolveId($id)]);
        $this->invalidateAll();

        return redirect()->to(base_url('administrator/pesanmasuk'));
    }
}
