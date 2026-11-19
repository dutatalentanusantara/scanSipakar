<?php

namespace App\Controllers\Admin;

class KontenController extends BaseAdminController
{
    public function halamanbaru()
    {
        if ($redirect = $this->ensureModule('halamanbaru')) {
            return $redirect;
        }

        return $this->renderAdmin('administrator/mod_halaman/view_halaman', [
            'record' => $this->scopedList('halamanstatis', 'id_halaman', 'DESC'),
        ]);
    }

    public function tambah_halamanbaru()
    {
        if ($redirect = $this->ensureModule('halamanbaru')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            $judul    = trim((string) $this->request->getPost('a'));
            $filename = $this->uploadOptional('c', 'asset/foto_statis/', ['gif', 'jpg', 'jpeg', 'png', 'webp'], 3000);
            $data     = [
                'judul'       => $judul,
                'judul_seo'   => seo_title($judul),
                'isi_halaman' => (string) $this->request->getPost('b'),
                'tgl_posting' => date('Y-m-d'),
                'username'    => $this->currentUsername(),
                'dibaca'      => 0,
                'jam'         => date('H:i:s'),
                'hari'        => hari_ini((int) date('w')),
            ];
            if ($filename !== null) {
                $data['gambar'] = $filename;
            }

            $this->portalModel->insert('halamanstatis', $data);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/halamanbaru'));
        }

        return $this->renderAdmin('administrator/mod_halaman/view_halaman_tambah');
    }

    public function edit_halamanbaru($id = null)
    {
        if ($redirect = $this->ensureModule('halamanbaru')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        if ($this->isPostSubmit()) {
            $postId   = (int) $this->request->getPost('id');
            $judul    = trim((string) $this->request->getPost('a'));
            $filename = $this->uploadOptional('c', 'asset/foto_statis/', ['gif', 'jpg', 'jpeg', 'png', 'webp'], 3000);
            $data     = [
                'judul'       => $judul,
                'judul_seo'   => seo_title($judul),
                'isi_halaman' => (string) $this->request->getPost('b'),
            ];
            if ($filename !== null) {
                $data['gambar'] = $filename;
            }

            $this->portalModel->update('halamanstatis', $data, $this->ownerWhere('id_halaman', $postId));
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/halamanbaru'));
        }

        $row = $this->portalModel->findOne('halamanstatis', $this->ownerWhere('id_halaman', $targetId)) ?? [];

        return $this->renderAdmin('administrator/mod_halaman/view_halaman_edit', ['rows' => $row]);
    }

    public function delete_halamanbaru($id = null)
    {
        if ($redirect = $this->ensureModule('halamanbaru')) {
            return $redirect;
        }

        $this->portalModel->delete('halamanstatis', $this->ownerWhere('id_halaman', $this->resolveId($id)));
        $this->invalidateAll();

        return redirect()->to(base_url('administrator/halamanbaru'));
    }

    public function agenda()
    {
        if ($redirect = $this->ensureModule('agenda')) {
            return $redirect;
        }

        return $this->renderAdmin('administrator/mod_agenda/view_agenda', [
            'record' => $this->scopedList('agenda', 'id_agenda', 'DESC'),
        ]);
    }

    public function tambah_agenda()
    {
        if ($redirect = $this->ensureModule('agenda')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            [$mulai, $selesai] = $this->parseDateRange((string) $this->request->getPost('f'));
            $tema              = trim((string) $this->request->getPost('a'));
            $filename          = $this->uploadOptional('c', 'asset/foto_agenda/', ['gif', 'jpg', 'jpeg', 'png', 'webp'], 3000);

            $data = [
                'tema'        => $tema,
                'tema_seo'    => seo_title($tema),
                'isi_agenda'  => (string) $this->request->getPost('b'),
                'tempat'      => trim((string) $this->request->getPost('d')),
                'pengirim'    => trim((string) $this->request->getPost('g')),
                'tgl_mulai'   => $mulai,
                'tgl_selesai' => $selesai,
                'tgl_posting' => date('Y-m-d'),
                'jam'         => trim((string) $this->request->getPost('e')),
                'dibaca'      => 0,
                'username'    => $this->currentUsername(),
            ];
            if ($filename !== null) {
                $data['gambar'] = $filename;
            }

            $this->portalModel->insert('agenda', $data);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/agenda'));
        }

        return $this->renderAdmin('administrator/mod_agenda/view_agenda_tambah');
    }

    public function edit_agenda($id = null)
    {
        if ($redirect = $this->ensureModule('agenda')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        if ($this->isPostSubmit()) {
            $postId            = (int) $this->request->getPost('id');
            [$mulai, $selesai] = $this->parseDateRange((string) $this->request->getPost('f'));
            $tema              = trim((string) $this->request->getPost('a'));
            $filename          = $this->uploadOptional('c', 'asset/foto_agenda/', ['gif', 'jpg', 'jpeg', 'png', 'webp'], 3000);

            $data = [
                'tema'        => $tema,
                'tema_seo'    => seo_title($tema),
                'isi_agenda'  => (string) $this->request->getPost('b'),
                'tempat'      => trim((string) $this->request->getPost('d')),
                'pengirim'    => trim((string) $this->request->getPost('g')),
                'tgl_mulai'   => $mulai,
                'tgl_selesai' => $selesai,
                'jam'         => trim((string) $this->request->getPost('e')),
            ];
            if ($filename !== null) {
                $data['gambar'] = $filename;
            }

            $this->portalModel->update('agenda', $data, $this->ownerWhere('id_agenda', $postId));
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/agenda'));
        }

        $row = $this->portalModel->findOne('agenda', $this->ownerWhere('id_agenda', $targetId)) ?? [];

        return $this->renderAdmin('administrator/mod_agenda/view_agenda_edit', ['rows' => $row]);
    }

    public function delete_agenda($id = null)
    {
        if ($redirect = $this->ensureModule('agenda')) {
            return $redirect;
        }

        $this->portalModel->delete('agenda', $this->ownerWhere('id_agenda', $this->resolveId($id)));
        $this->invalidateAll();

        return redirect()->to(base_url('administrator/agenda'));
    }

    public function sekilasinfo()
    {
        if ($redirect = $this->ensureModule('sekilasinfo')) {
            return $redirect;
        }

        return $this->renderAdmin('administrator/mod_sekilasinfo/view_sekilasinfo', [
            'record' => $this->portalModel->viewOrdering('sekilasinfo', 'id_sekilas', 'DESC'),
        ]);
    }

    public function tambah_sekilasinfo()
    {
        if ($redirect = $this->ensureModule('sekilasinfo')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            $filename = $this->uploadOptional('b', 'asset/foto_info/', ['gif', 'jpg', 'jpeg', 'png', 'webp'], 2500);
            $data     = [
                'info'        => (string) $this->request->getPost('a'),
                'tgl_posting' => date('Y-m-d'),
                'aktif'       => 'Y',
            ];
            if ($filename !== null) {
                $data['gambar'] = $filename;
            }

            $this->portalModel->insert('sekilasinfo', $data);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/sekilasinfo'));
        }

        return $this->renderAdmin('administrator/mod_sekilasinfo/view_sekilasinfo_tambah');
    }

    public function edit_sekilasinfo($id = null)
    {
        if ($redirect = $this->ensureModule('sekilasinfo')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        if ($this->isPostSubmit()) {
            $postId   = (int) $this->request->getPost('id');
            $filename = $this->uploadOptional('b', 'asset/foto_info/', ['gif', 'jpg', 'jpeg', 'png', 'webp'], 2500);
            $data     = [
                'info'  => (string) $this->request->getPost('a'),
                'aktif' => (string) $this->request->getPost('f'),
            ];
            if ($filename !== null) {
                $data['gambar'] = $filename;
            }

            $this->portalModel->update('sekilasinfo', $data, ['id_sekilas' => $postId]);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/sekilasinfo'));
        }

        $row = $this->portalModel->findOne('sekilasinfo', ['id_sekilas' => $targetId]) ?? [];

        return $this->renderAdmin('administrator/mod_sekilasinfo/view_sekilasinfo_edit', ['rows' => $row]);
    }

    public function delete_sekilasinfo($id = null)
    {
        if ($redirect = $this->ensureModule('sekilasinfo')) {
            return $redirect;
        }

        $this->portalModel->delete('sekilasinfo', ['id_sekilas' => $this->resolveId($id)]);
        $this->invalidateAll();

        return redirect()->to(base_url('administrator/sekilasinfo'));
    }

    public function download()
    {
        if ($redirect = $this->ensureModule('download')) {
            return $redirect;
        }

        return $this->renderAdmin('administrator/mod_download/view_download', [
            'record' => $this->portalModel->viewOrdering('download', 'id_download', 'DESC'),
        ]);
    }

    public function tambah_download()
    {
        if ($redirect = $this->ensureModule('download')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            $allowed  = ['gif', 'jpg', 'jpeg', 'png', 'zip', 'rar', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt'];
            $filename = $this->uploadOptional('b', 'asset/files/', $allowed, 25000);
            $data     = [
                'judul'       => trim((string) $this->request->getPost('a')),
                'tgl_posting' => date('Y-m-d'),
                'hits'        => 0,
            ];
            if ($filename !== null) {
                $data['nama_file'] = $filename;
            }

            $this->portalModel->insert('download', $data);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/download'));
        }

        return $this->renderAdmin('administrator/mod_download/view_download_tambah');
    }

    public function edit_download($id = null)
    {
        if ($redirect = $this->ensureModule('download')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        if ($this->isPostSubmit()) {
            $postId   = (int) $this->request->getPost('id');
            $allowed  = ['gif', 'jpg', 'jpeg', 'png', 'zip', 'rar', 'pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx', 'txt'];
            $filename = $this->uploadOptional('b', 'asset/files/', $allowed, 25000);
            $data     = [
                'judul' => trim((string) $this->request->getPost('a')),
            ];
            if ($filename !== null) {
                $data['nama_file'] = $filename;
            }

            $this->portalModel->update('download', $data, ['id_download' => $postId]);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/download'));
        }

        $row = $this->portalModel->findOne('download', ['id_download' => $targetId]) ?? [];

        return $this->renderAdmin('administrator/mod_download/view_download_edit', ['rows' => $row]);
    }

    public function delete_download($id = null)
    {
        if ($redirect = $this->ensureModule('download')) {
            return $redirect;
        }

        $this->portalModel->delete('download', ['id_download' => $this->resolveId($id)]);
        $this->invalidateAll();

        return redirect()->to(base_url('administrator/download'));
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function parseDateRange(string $range): array
    {
        $parts = explode(' - ', trim($range));
        $start = explode('/', trim($parts[0] ?? ''));
        $end   = explode('/', trim($parts[1] ?? ($parts[0] ?? '')));

        $mulai   = count($start) === 3 ? ($start[2] . '-' . $start[0] . '-' . $start[1]) : date('Y-m-d');
        $selesai = count($end) === 3 ? ($end[2] . '-' . $end[0] . '-' . $end[1]) : $mulai;

        return [$mulai, $selesai];
    }
}
