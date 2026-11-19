<?php

namespace App\Controllers\Admin;

use App\Services\RssService;
use App\Services\UploadService;

class BeritaController extends BaseAdminController
{
    public function listberita()
    {
        if ($redirect = $this->ensureModule('listberita')) {
            return $redirect;
        }

        (new RssService())->regenerateFeed();

        return $this->renderAdmin('administrator/mod_berita/view_berita', [
            'record' => $this->scopedList('berita', 'id_berita', 'DESC'),
            'iden'   => $this->portalContext->getIdentitas(),
        ]);
    }

    public function tambah_listberita()
    {
        if ($redirect = $this->ensureModule('listberita')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            $filename = $this->uploadOptional('k', 'asset/foto_berita/', ['gif', 'jpg', 'jpeg', 'png', 'webp'], 3000);
            $status   = session()->get('level') === 'kontributor' ? 'N' : 'Y';
            $tags     = $this->request->getPost('j');
            $tagStr   = is_array($tags) ? implode(',', $tags) : trim((string) ($tags ?? ''));
            $waktu    = explode(' ', trim((string) ($this->request->getPost('waktu') ?? '')));
            $tanggal  = ($waktu[0] ?? '') !== '' ? $waktu[0] : date('Y-m-d');
            $jam      = ($waktu[1] ?? '') !== '' ? $waktu[1] : date('H:i:s');
            $judul    = trim((string) $this->request->getPost('b'));

            $data = [
                'id_kategori'       => trim((string) $this->request->getPost('a')),
                'username'          => $this->currentUsername(),
                'judul'             => $judul,
                'sub_judul'         => trim((string) $this->request->getPost('c')),
                'youtube'           => trim((string) $this->request->getPost('d')),
                'judul_seo'         => seo_title($judul),
                'headline'          => trim((string) $this->request->getPost('e')),
                'aktif'             => trim((string) $this->request->getPost('f')),
                'utama'             => trim((string) $this->request->getPost('g')),
                'isi_berita'        => (string) $this->request->getPost('h'),
                'keterangan_gambar' => (string) $this->request->getPost('i'),
                'hari'              => hari_ini((int) date('w')),
                'tanggal'           => $tanggal,
                'jam'               => $jam,
                'dibaca'            => 0,
                'tag'               => $tagStr,
                'status'            => $status,
            ];
            if ($filename !== null) {
                $data['gambar'] = $filename;
            }

            $this->portalModel->insert('berita', $data);
            (new RssService())->regenerateFeed();
            $this->invalidateAll();

            return $this->redirectTo(base_url('administrator/listberita'));
        }

        return $this->renderAdmin('administrator/mod_berita/view_berita_tambah', [
            'tag'    => $this->portalModel->viewOrdering('tag', 'id_tag', 'DESC'),
            'record' => $this->portalModel->viewOrdering('kategori', 'id_kategori', 'DESC'),
        ]);
    }

    public function edit_listberita($id = null)
    {
        if ($redirect = $this->ensureModule('listberita')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        if ($this->isPostSubmit()) {
            $postId   = (int) $this->request->getPost('id');
            $existing = $this->portalModel->findOne('berita', $this->ownerWhere('id_berita', $postId));
            if ($existing === null) {
                return $this->redirectTo(base_url('administrator/listberita'));
            }

            $filename = $this->uploadOptional('k', 'asset/foto_berita/', ['gif', 'jpg', 'jpeg', 'png', 'webp'], 3000);
            $status   = session()->get('level') === 'kontributor' ? 'N' : 'Y';
            $tags     = $this->request->getPost('j');
            $tagStr   = is_array($tags) ? implode(',', $tags) : trim((string) ($tags ?? ''));
            $waktu    = explode(' ', trim((string) ($this->request->getPost('waktu') ?? '')));
            $tanggal  = ($waktu[0] ?? '') !== '' ? $waktu[0] : (string) ($existing['tanggal'] ?? date('Y-m-d'));
            $jam      = ($waktu[1] ?? '') !== '' ? $waktu[1] : (string) ($existing['jam'] ?? date('H:i:s'));
            $judul    = trim((string) $this->request->getPost('b'));

            $data = [
                'id_kategori'       => trim((string) $this->request->getPost('a')),
                'username'          => $this->currentUsername(),
                'judul'             => $judul,
                'sub_judul'         => trim((string) $this->request->getPost('c')),
                'youtube'           => trim((string) $this->request->getPost('d')),
                'judul_seo'         => seo_title($judul),
                'headline'          => trim((string) $this->request->getPost('e')),
                'aktif'             => trim((string) $this->request->getPost('f')),
                'utama'             => trim((string) $this->request->getPost('g')),
                'isi_berita'        => (string) $this->request->getPost('h'),
                'keterangan_gambar' => (string) $this->request->getPost('i'),
                'tanggal'           => $tanggal,
                'jam'               => $jam,
                'dibaca'            => (int) ($existing['dibaca'] ?? 0),
                'tag'               => $tagStr,
                'status'            => $status,
            ];
            if ($filename !== null) {
                $data['gambar'] = $filename;
            }

            $this->portalModel->update('berita', $data, $this->ownerWhere('id_berita', $postId));
            (new RssService())->regenerateFeed();
            $this->invalidateAll();

            return $this->redirectTo(base_url('administrator/listberita'));
        }

        $row = $this->portalModel->findOne('berita', $this->ownerWhere('id_berita', $targetId)) ?? [];

        return $this->renderAdmin('administrator/mod_berita/view_berita_edit', [
            'rows'   => $row,
            'tag'    => $this->portalModel->viewOrdering('tag', 'id_tag', 'DESC'),
            'record' => $this->portalModel->viewOrdering('kategori', 'id_kategori', 'DESC'),
        ]);
    }

    public function publish_listberita($id = null, string $status = '')
    {
        if ($redirect = $this->ensureAdmin()) {
            return $redirect;
        }

        $targetId  = $this->resolveId($id);
        $segStatus = $status !== '' ? $status : uri_segment(4);
        $newStatus = $segStatus === 'Y' ? 'N' : 'Y';

        $this->portalModel->update('berita', ['status' => $newStatus], ['id_berita' => $targetId]);
        (new RssService())->regenerateFeed();
        $this->invalidateAll();

        return $this->redirectTo(base_url('administrator/listberita'));
    }

    public function delete_listberita($id = null)
    {
        if ($redirect = $this->ensureModule('listberita')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        $this->portalModel->delete('berita', $this->ownerWhere('id_berita', $targetId));
        (new RssService())->regenerateFeed();
        $this->invalidateAll();

        return $this->redirectTo(base_url('administrator/listberita'));
    }

    public function upload_image()
    {
        if ($redirect = $this->ensureLoggedIn()) {
            return $this->response->setStatusCode(403)->setBody('Unauthorized');
        }

        $res = (new UploadService())->upload(
            $this->request->getFile('image'),
            'asset/images/',
            ['gif', 'jpg', 'jpeg', 'png', 'webp'],
            3000,
            true
        );

        if (! $res['ok']) {
            return $this->response->setStatusCode(400)->setHeader('X-CSRF-TOKEN', csrf_hash())->setBody($res['error']);
        }

        return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setBody(base_url('asset/images/' . $res['filename']));
    }

    public function delete_image()
    {
        if ($redirect = $this->ensureLoggedIn()) {
            return $this->response->setStatusCode(403)->setBody('Unauthorized');
        }

        $src    = trim((string) ($this->request->getPost('src') ?? ''));
        $rel    = ltrim(str_replace([base_url(), '\\'], ['', '/'], $src), '/');
        $target = $rel !== '' ? portal_resolve_contained_file('asset/images/', $rel, true) : null;

        if ($target !== null) {
            if (@unlink($target)) {
                return $this->response->setHeader('X-CSRF-TOKEN', csrf_hash())->setBody('File Delete Successfully');
            }
        }

        return $this->response->setStatusCode(404)->setHeader('X-CSRF-TOKEN', csrf_hash())->setBody('File not found');
    }

    public function kategoriberita()
    {
        if ($redirect = $this->ensureModule('kategoriberita')) {
            return $redirect;
        }

        return $this->renderAdmin('administrator/mod_kategori/view_kategori', [
            'record' => $this->scopedList('kategori', 'id_kategori', 'DESC'),
        ]);
    }

    public function tambah_kategoriberita()
    {
        if ($redirect = $this->ensureModule('kategoriberita')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            $nama     = trim((string) $this->request->getPost('a'));
            $filename = $this->uploadOptional('d', 'asset/foto_berita/', ['gif', 'jpg', 'jpeg', 'png', 'webp'], 5000);
            $data     = [
                'nama_kategori' => $nama,
                'username'      => $this->currentUsername(),
                'kategori_seo'  => seo_title($nama),
                'aktif'         => trim((string) $this->request->getPost('b')),
                'sidebar'       => trim((string) $this->request->getPost('c')),
                'gambar_utama'  => $filename ?? '',
            ];
            $this->portalModel->insert('kategori', $data);
            $this->invalidateAll();

            return $this->redirectTo(base_url('administrator/kategoriberita'));
        }

        return $this->renderAdmin('administrator/mod_kategori/view_kategori_tambah');
    }

    public function edit_kategoriberita($id = null)
    {
        if ($redirect = $this->ensureModule('kategoriberita')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        if ($this->isPostSubmit()) {
            $postId   = (int) $this->request->getPost('id');
            $nama     = trim((string) $this->request->getPost('a'));
            $filename = $this->uploadOptional('d', 'asset/foto_berita/', ['gif', 'jpg', 'jpeg', 'png', 'webp'], 5000);
            $data     = [
                'nama_kategori' => $nama,
                'username'      => $this->currentUsername(),
                'kategori_seo'  => seo_title($nama),
                'aktif'         => trim((string) $this->request->getPost('b')),
                'sidebar'       => trim((string) $this->request->getPost('c')),
            ];
            if ($filename !== null) {
                $data['gambar_utama'] = $filename;
            }
            $this->portalModel->update('kategori', $data, $this->ownerWhere('id_kategori', $postId));
            $this->invalidateAll();

            return $this->redirectTo(base_url('administrator/kategoriberita'));
        }

        $row = $this->portalModel->findOne('kategori', $this->ownerWhere('id_kategori', $targetId)) ?? [];

        return $this->renderAdmin('administrator/mod_kategori/view_kategori_edit', ['rows' => $row]);
    }

    public function delete_kategoriberita($id = null)
    {
        if ($redirect = $this->ensureModule('kategoriberita')) {
            return $redirect;
        }

        $this->portalModel->delete('kategori', $this->ownerWhere('id_kategori', $this->resolveId($id)));
        $this->invalidateAll();

        return $this->redirectTo(base_url('administrator/kategoriberita'));
    }

    public function tagberita()
    {
        if ($redirect = $this->ensureModule('tagberita')) {
            return $redirect;
        }

        return $this->renderAdmin('administrator/mod_tag/view_tag', [
            'record' => $this->scopedList('tag', 'id_tag', 'DESC'),
        ]);
    }

    public function tambah_tagberita()
    {
        if ($redirect = $this->ensureModule('tagberita')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            $nama = trim((string) $this->request->getPost('a'));
            $this->portalModel->insert('tag', [
                'nama_tag' => $nama,
                'username' => $this->currentUsername(),
                'tag_seo'  => seo_title($nama),
                'count'    => 0,
            ]);
            $this->invalidateAll();

            return $this->redirectTo(base_url('administrator/tagberita'));
        }

        return $this->renderAdmin('administrator/mod_tag/view_tag_tambah');
    }

    public function edit_tagberita($id = null)
    {
        if ($redirect = $this->ensureModule('tagberita')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        if ($this->isPostSubmit()) {
            $postId = (int) $this->request->getPost('id');
            $nama   = trim((string) $this->request->getPost('a'));
            $this->portalModel->update('tag', [
                'nama_tag' => $nama,
                'username' => $this->currentUsername(),
                'tag_seo'  => seo_title($nama),
            ], $this->ownerWhere('id_tag', $postId));
            $this->invalidateAll();

            return $this->redirectTo(base_url('administrator/tagberita'));
        }

        $row = $this->portalModel->findOne('tag', $this->ownerWhere('id_tag', $targetId)) ?? [];

        return $this->renderAdmin('administrator/mod_tag/view_tag_edit', ['rows' => $row]);
    }

    public function delete_tagberita($id = null)
    {
        if ($redirect = $this->ensureModule('tagberita')) {
            return $redirect;
        }

        $this->portalModel->delete('tag', $this->ownerWhere('id_tag', $this->resolveId($id)));
        $this->invalidateAll();

        return $this->redirectTo(base_url('administrator/tagberita'));
    }
}
