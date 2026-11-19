<?php

namespace App\Controllers\Admin;

use Throwable;

class SettingsController extends BaseAdminController
{
    public function identitaswebsite()
    {
        if ($redirect = $this->ensureModule('identitaswebsite')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            $data = [
                'nama_website'   => trim((string) $this->request->getPost('a')),
                'email'          => trim((string) $this->request->getPost('b')),
                'url'            => trim((string) $this->request->getPost('c')),
                'facebook'       => (string) $this->request->getPost('d'),
                'rekening'       => trim((string) $this->request->getPost('e')),
                'no_telp'        => trim((string) $this->request->getPost('f')),
                'meta_deskripsi' => (string) $this->request->getPost('g'),
                'meta_keyword'   => trim((string) $this->request->getPost('h')),
                'maps'           => (string) $this->request->getPost('i'),
            ];

            $favicon = $this->uploadOptional('j', 'asset/images/', ['gif', 'jpg', 'jpeg', 'png', 'ico'], 500);
            if ($favicon !== null) {
                $data['favicon'] = $favicon;
            }

            $id = (int) ($this->request->getPost('id') ?: 1);
            $this->portalModel->update('identitas', $data, ['id_identitas' => $id]);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/identitaswebsite'));
        }

        $record = $this->portalModel->findOne('identitas', ['id_identitas' => 1]) ?? [];

        return $this->renderAdmin('administrator/mod_identitas/view_identitas', ['record' => $record]);
    }

    public function logowebsite()
    {
        if ($redirect = $this->ensureModule('logowebsite')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            $filename = $this->uploadOptional('logo', 'asset/logo/', ['gif', 'jpg', 'jpeg', 'png'], 2000);
            if ($filename !== null) {
                $id = (int) ($this->request->getPost('id') ?: 1);
                $this->portalModel->update('logo', ['gambar' => $filename], ['id_logo' => $id]);
                $this->invalidateAll();
            }

            return redirect()->to(base_url('administrator/logowebsite'));
        }

        return $this->renderAdmin('administrator/mod_logowebsite/view_logowebsite', [
            'record' => $this->portalModel->view('logo'),
        ]);
    }

    public function templatewebsite()
    {
        if ($redirect = $this->ensureModule('templatewebsite')) {
            return $redirect;
        }

        return $this->renderAdmin('administrator/mod_template/view_template', [
            'record' => $this->scopedList('templates', 'id_templates', 'DESC'),
        ]);
    }

    public function tambah_templatewebsite()
    {
        if ($redirect = $this->ensureModule('templatewebsite')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            $folder = preg_replace('/[^a-zA-Z0-9\-_]/', '', trim((string) $this->request->getPost('c'))) ?: 'diskominfo';
            $this->portalModel->insert('templates', [
                'judul'    => trim((string) $this->request->getPost('a')),
                'username' => $this->currentUsername(),
                'pembuat'  => (string) $this->request->getPost('b'),
                'folder'   => $folder,
                'aktif'    => 'N',
            ]);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/templatewebsite'));
        }

        return $this->renderAdmin('administrator/mod_template/view_template_tambah');
    }

    public function edit_templatewebsite($id = null)
    {
        if ($redirect = $this->ensureModule('templatewebsite')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        if ($this->isPostSubmit()) {
            $postId = (int) $this->request->getPost('id');
            $folder = preg_replace('/[^a-zA-Z0-9\-_]/', '', trim((string) $this->request->getPost('c'))) ?: 'diskominfo';
            $this->portalModel->update('templates', [
                'judul'    => trim((string) $this->request->getPost('a')),
                'username' => $this->currentUsername(),
                'pembuat'  => (string) $this->request->getPost('b'),
                'folder'   => $folder,
            ], $this->ownerWhere('id_templates', $postId));
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/templatewebsite'));
        }

        $row = $this->portalModel->findOne('templates', $this->ownerWhere('id_templates', $targetId)) ?? [];

        return $this->renderAdmin('administrator/mod_template/view_template_edit', ['rows' => $row]);
    }

    public function aktif_templatewebsite($id = null, string $status = '')
    {
        if ($redirect = $this->ensureModule('templatewebsite')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        $target   = $this->portalModel->findOne('templates', ['id_templates' => $targetId]);
        if ($target === null) {
            return redirect()->to(base_url('administrator/templatewebsite'));
        }

        $seg4  = $status !== '' ? $status : uri_segment(4);
        $aktif = $seg4 === 'Y' ? 'N' : 'Y';
        $db    = $this->portalModel->db();

        if ($aktif === 'Y') {
            $db->table('templates')->where('id_templates', $targetId)->update(['aktif' => 'Y']);
            $db->table('templates')->where('id_templates !=', $targetId)->update(['aktif' => 'N']);
        } else {
            // Keep at least one active template so portal rendering never loses its layout.
            $otherActive = $db->table('templates')->where('id_templates !=', $targetId)->where('aktif', 'Y')->countAllResults();
            if ($otherActive > 0) {
                $db->table('templates')->where('id_templates', $targetId)->update(['aktif' => 'N']);
            }
        }
        $this->invalidateAll();

        return redirect()->to(base_url('administrator/templatewebsite'));
    }

    public function delete_templatewebsite($id = null)
    {
        if ($redirect = $this->ensureModule('templatewebsite')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        $this->portalModel->delete('templates', $this->ownerWhere('id_templates', $targetId));
        $this->invalidateAll();

        return redirect()->to(base_url('administrator/templatewebsite'));
    }

    public function background()
    {
        if ($redirect = $this->ensureModule('background')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            $this->portalModel->update('background', [
                'gambar' => (string) $this->request->getPost('a'),
            ], ['id_background' => 1]);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/background'));
        }

        return $this->renderAdmin('administrator/mod_background/view_background', [
            'rows' => $this->portalModel->findOne('background', ['id_background' => 1]) ?? [],
        ]);
    }

    public function alamat()
    {
        if ($redirect = $this->ensureModule('alamat')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            $this->portalModel->update('mod_alamat', [
                'alamat' => (string) $this->request->getPost('a'),
            ], ['id_alamat' => 1]);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/alamat'));
        }

        return $this->renderAdmin('administrator/mod_alamat/view_alamat', [
            'rows' => $this->portalModel->findOne('mod_alamat', ['id_alamat' => 1]) ?? [],
        ]);
    }

    public function upgrade()
    {
        if ($redirect = $this->ensureModule('upgrade')) {
            return $redirect;
        }

        $db      = $this->portalModel->db();
        $columns = [
            ['iklanatas', 'source', 'ALTER TABLE `iklanatas` ADD COLUMN `source` TEXT NOT NULL AFTER `url`'],
            ['iklantengah', 'source', 'ALTER TABLE `iklantengah` ADD COLUMN `source` TEXT NOT NULL AFTER `url`'],
            ['iklantengah', 'posisi', "ALTER TABLE `iklantengah` ADD COLUMN `posisi` VARCHAR(50) NOT NULL DEFAULT 'home' AFTER `tgl_posting`"],
            ['kategori', 'gambar_utama', 'ALTER TABLE `kategori` ADD COLUMN `gambar_utama` TEXT NOT NULL AFTER `sidebar`'],
            ['pasangiklan', 'source', 'ALTER TABLE `pasangiklan` ADD COLUMN `source` TEXT NOT NULL AFTER `url`'],
        ];

        foreach ($columns as [$table, $column, $sql]) {
            try {
                if (! $db->fieldExists($column, $table)) {
                    $db->query($sql);
                }
            } catch (Throwable $e) {
                log_message('warning', "Upgrade schema skip {$table}.{$column}: " . $e->getMessage());
            }
        }

        session()->setFlashdata('message', '<div class="alert alert-success"><center>Dashboard Swarakalibata Berhasil diperbaharui, Terima kasih..</center></div>');

        return redirect()->to(base_url('administrator/home'));
    }
}
