<?php

namespace App\Controllers\Admin;

class MediaController extends BaseAdminController
{
    /**
     * Unified configuration map for advertisement slots.
     *
     * @var array<string, array<string, mixed>>
     */
    private const AD_MODULES = [
        'iklanatas' => [
            'table'     => 'iklanatas',
            'pk'        => 'id_iklanatas',
            'dir'       => 'asset/foto_iklanatas/',
            'view'      => 'administrator/mod_iklanatas/view_iklanatas',
            'route'     => 'administrator/iklanatas',
            'hasPosisi' => false,
        ],
        'iklanhome' => [
            'table'     => 'iklantengah',
            'pk'        => 'id_iklantengah',
            'dir'       => 'asset/foto_iklantengah/',
            'view'      => 'administrator/mod_iklanhome/view_iklanhome',
            'route'     => 'administrator/iklanhome',
            'hasPosisi' => true,
        ],
        'iklansidebar' => [
            'table'     => 'pasangiklan',
            'pk'        => 'id_pasangiklan',
            'dir'       => 'asset/foto_pasangiklan/',
            'view'      => 'administrator/mod_iklansidebar/view_iklansidebar',
            'route'     => 'administrator/iklansidebar',
            'hasPosisi' => false,
        ],
    ];

    // ==================== ALBUM ====================

    public function album()
    {
        if ($redirect = $this->ensureModule('album')) {
            return $redirect;
        }

        return $this->renderAdmin('administrator/mod_album/view_album', [
            'record' => $this->scopedList('album', 'id_album', 'DESC'),
        ]);
    }

    public function tambah_album()
    {
        if ($redirect = $this->ensureModule('album')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            $judul    = (string) $this->request->getPost('a');
            $filename = $this->uploadOptional('c', 'asset/img_album/', ['gif', 'jpg', 'jpeg', 'png', 'webp'], 3000);
            $data     = [
                'jdl_album'   => $judul,
                'album_seo'   => seo_title($judul),
                'keterangan'  => (string) $this->request->getPost('b'),
                'aktif'       => 'Y',
                'hits_album'  => 0,
                'tgl_posting' => date('Y-m-d'),
                'jam'         => date('H:i:s'),
                'hari'        => hari_ini((int) date('w')),
                'username'    => $this->currentUsername(),
            ];
            if ($filename !== null) {
                $data['gbr_album'] = $filename;
            }
            $this->portalModel->insert('album', $data);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/album'));
        }

        return $this->renderAdmin('administrator/mod_album/view_album_tambah');
    }

    public function edit_album($id = null)
    {
        if ($redirect = $this->ensureModule('album')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        if ($this->isPostSubmit()) {
            $postId   = (int) $this->request->getPost('id');
            $judul    = (string) $this->request->getPost('a');
            $filename = $this->uploadOptional('c', 'asset/img_album/', ['gif', 'jpg', 'jpeg', 'png', 'webp'], 3000);
            $data     = [
                'jdl_album'  => $judul,
                'album_seo'  => seo_title($judul),
                'keterangan' => (string) $this->request->getPost('b'),
                'aktif'      => (string) $this->request->getPost('d'),
            ];
            if ($filename !== null) {
                $data['gbr_album'] = $filename;
            }
            $this->portalModel->update('album', $data, $this->ownerWhere('id_album', $postId));
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/album'));
        }

        $row = $this->portalModel->findOne('album', $this->ownerWhere('id_album', $targetId)) ?? [];

        return $this->renderAdmin('administrator/mod_album/view_album_edit', ['rows' => $row]);
    }

    public function delete_album($id = null)
    {
        if ($redirect = $this->ensureModule('album')) {
            return $redirect;
        }

        $this->portalModel->delete('album', $this->ownerWhere('id_album', $this->resolveId($id)));
        $this->invalidateAll();

        return redirect()->to(base_url('administrator/album'));
    }

    // ==================== GALLERY ====================

    public function gallery()
    {
        if ($redirect = $this->ensureModule('gallery')) {
            return $redirect;
        }

        $record = $this->isAdmin()
            ? $this->portalModel->viewJoinAll('gallery', 'album', 'id_album', 'id_gallery', 'DESC')
            : $this->portalModel->viewJoinWhere('gallery', 'album', 'id_album', ['gallery.username' => $this->currentUsername()], 'id_gallery', 'DESC');

        return $this->renderAdmin('administrator/mod_gallery/view_gallery', ['record' => $record]);
    }

    public function tambah_gallery()
    {
        if ($redirect = $this->ensureModule('gallery')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            $judul    = (string) $this->request->getPost('b');
            $filename = $this->uploadOptional('d', 'asset/img_galeri/', ['gif', 'jpg', 'jpeg', 'png', 'webp'], 3000);
            $data     = [
                'id_album'    => (string) $this->request->getPost('a'),
                'username'    => $this->currentUsername(),
                'jdl_gallery' => $judul,
                'gallery_seo' => seo_title($judul),
                'keterangan'  => (string) $this->request->getPost('c'),
            ];
            if ($filename !== null) {
                $data['gbr_gallery'] = $filename;
            }
            $this->portalModel->insert('gallery', $data);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/gallery'));
        }

        return $this->renderAdmin('administrator/mod_gallery/view_gallery_tambah', [
            'record' => $this->portalModel->viewOrdering('album', 'id_album', 'DESC'),
        ]);
    }

    public function edit_gallery($id = null)
    {
        if ($redirect = $this->ensureModule('gallery')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        if ($this->isPostSubmit()) {
            $postId   = (int) $this->request->getPost('id');
            $judul    = (string) $this->request->getPost('b');
            $filename = $this->uploadOptional('d', 'asset/img_galeri/', ['gif', 'jpg', 'jpeg', 'png', 'webp'], 3000);
            $data     = [
                'id_album'    => (string) $this->request->getPost('a'),
                'username'    => $this->currentUsername(),
                'jdl_gallery' => $judul,
                'gallery_seo' => seo_title($judul),
                'keterangan'  => (string) $this->request->getPost('c'),
            ];
            if ($filename !== null) {
                $data['gbr_gallery'] = $filename;
            }
            $this->portalModel->update('gallery', $data, $this->ownerWhere('id_gallery', $postId));
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/gallery'));
        }

        return $this->renderAdmin('administrator/mod_gallery/view_gallery_edit', [
            'rows'   => $this->portalModel->findOne('gallery', $this->ownerWhere('id_gallery', $targetId)) ?? [],
            'record' => $this->portalModel->viewOrdering('album', 'id_album', 'DESC'),
        ]);
    }

    public function delete_gallery($id = null)
    {
        if ($redirect = $this->ensureModule('gallery')) {
            return $redirect;
        }

        $this->portalModel->delete('gallery', $this->ownerWhere('id_gallery', $this->resolveId($id)));
        $this->invalidateAll();

        return redirect()->to(base_url('administrator/gallery'));
    }

    // ==================== VIDEO & PLAYLIST ====================

    public function video()
    {
        if ($redirect = $this->ensureModule('video')) {
            return $redirect;
        }

        $record = $this->isAdmin()
            ? $this->portalModel->viewJoinAll('video', 'playlist', 'id_playlist', 'id_video', 'DESC')
            : $this->portalModel->viewJoinWhere('video', 'playlist', 'id_playlist', ['video.username' => $this->currentUsername()], 'id_video', 'DESC');

        return $this->renderAdmin('administrator/mod_video/view_video', ['record' => $record]);
    }

    public function tambah_video()
    {
        if ($redirect = $this->ensureModule('video')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            $judul    = (string) $this->request->getPost('b');
            $filename = $this->uploadOptional('d', 'asset/img_video/', ['gif', 'jpg', 'jpeg', 'png', 'webp'], 3000);
            $tags     = $this->request->getPost('f');
            $tagStr   = is_array($tags) ? implode(',', $tags) : trim((string) ($tags ?? ''));
            $data     = [
                'id_playlist' => (string) $this->request->getPost('a'),
                'username'    => $this->currentUsername(),
                'jdl_video'   => $judul,
                'video_seo'   => seo_title($judul),
                'keterangan'  => (string) $this->request->getPost('c'),
                'video'       => '',
                'youtube'     => (string) $this->request->getPost('e'),
                'dilihat'     => 0,
                'hari'        => hari_ini((int) date('w')),
                'tanggal'     => date('Y-m-d'),
                'jam'         => date('H:i:s'),
                'tagvid'      => $tagStr,
            ];
            if ($filename !== null) {
                $data['gbr_video'] = $filename;
            }
            $this->portalModel->insert('video', $data);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/video'));
        }

        return $this->renderAdmin('administrator/mod_video/view_video_tambah', [
            'record' => $this->portalModel->viewOrdering('playlist', 'id_playlist', 'DESC'),
            'tag'    => $this->portalModel->viewOrdering('tagvid', 'id_tag', 'DESC'),
        ]);
    }

    public function edit_video($id = null)
    {
        if ($redirect = $this->ensureModule('video')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        if ($this->isPostSubmit()) {
            $postId   = (int) $this->request->getPost('id');
            $judul    = (string) $this->request->getPost('b');
            $filename = $this->uploadOptional('d', 'asset/img_video/', ['gif', 'jpg', 'jpeg', 'png', 'webp'], 3000);
            $tags     = $this->request->getPost('f');
            $tagStr   = is_array($tags) ? implode(',', $tags) : trim((string) ($tags ?? ''));
            $data     = [
                'id_playlist' => (string) $this->request->getPost('a'),
                'username'    => $this->currentUsername(),
                'jdl_video'   => $judul,
                'video_seo'   => seo_title($judul),
                'keterangan'  => (string) $this->request->getPost('c'),
                'video'       => '',
                'youtube'     => (string) $this->request->getPost('e'),
                'tagvid'      => $tagStr,
            ];
            if ($filename !== null) {
                $data['gbr_video'] = $filename;
            }
            $this->portalModel->update('video', $data, $this->ownerWhere('id_video', $postId));
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/video'));
        }

        return $this->renderAdmin('administrator/mod_video/view_video_edit', [
            'rows'   => $this->portalModel->findOne('video', $this->ownerWhere('id_video', $targetId)) ?? [],
            'record' => $this->portalModel->viewOrdering('playlist', 'id_playlist', 'DESC'),
            'tag'    => $this->portalModel->viewOrdering('tagvid', 'id_tag', 'DESC'),
        ]);
    }

    public function delete_video($id = null)
    {
        if ($redirect = $this->ensureModule('video')) {
            return $redirect;
        }

        $this->portalModel->delete('video', $this->ownerWhere('id_video', $this->resolveId($id)));
        $this->invalidateAll();

        return redirect()->to(base_url('administrator/video'));
    }

    public function playlist()
    {
        if ($redirect = $this->ensureModule('playlist')) {
            return $redirect;
        }

        return $this->renderAdmin('administrator/mod_playlist/view_playlist', [
            'record' => $this->portalModel->viewOrdering('playlist', 'id_playlist', 'DESC'),
        ]);
    }

    public function tambah_playlist()
    {
        if ($redirect = $this->ensureModule('playlist')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            $judul    = (string) $this->request->getPost('a');
            $filename = $this->uploadOptional('b', 'asset/img_playlist/', ['gif', 'jpg', 'jpeg', 'png', 'webp'], 3000);
            $data     = [
                'jdl_playlist' => $judul,
                'username'     => $this->currentUsername(),
                'playlist_seo' => seo_title($judul),
                'aktif'        => 'Y',
            ];
            if ($filename !== null) {
                $data['gbr_playlist'] = $filename;
            }
            $this->portalModel->insert('playlist', $data);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/playlist'));
        }

        return $this->renderAdmin('administrator/mod_playlist/view_playlist_tambah');
    }

    public function edit_playlist($id = null)
    {
        if ($redirect = $this->ensureModule('playlist')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        if ($this->isPostSubmit()) {
            $postId   = (int) $this->request->getPost('id');
            $judul    = (string) $this->request->getPost('a');
            $filename = $this->uploadOptional('b', 'asset/img_playlist/', ['gif', 'jpg', 'jpeg', 'png', 'webp'], 3000);
            $data     = [
                'jdl_playlist' => $judul,
                'username'     => $this->currentUsername(),
                'playlist_seo' => seo_title($judul),
                'aktif'        => (string) $this->request->getPost('c'),
            ];
            if ($filename !== null) {
                $data['gbr_playlist'] = $filename;
            }
            $this->portalModel->update('playlist', $data, ['id_playlist' => $postId]);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/playlist'));
        }

        return $this->renderAdmin('administrator/mod_playlist/view_playlist_edit', [
            'rows' => $this->portalModel->findOne('playlist', ['id_playlist' => $targetId]) ?? [],
        ]);
    }

    public function delete_playlist($id = null)
    {
        if ($redirect = $this->ensureModule('playlist')) {
            return $redirect;
        }

        $this->portalModel->delete('playlist', ['id_playlist' => $this->resolveId($id)]);
        $this->invalidateAll();

        return redirect()->to(base_url('administrator/playlist'));
    }

    public function tagvideo()
    {
        if ($redirect = $this->ensureModule('tagvideo')) {
            return $redirect;
        }

        return $this->renderAdmin('administrator/mod_tagvideo/view_tag', [
            'record' => $this->scopedList('tagvid', 'id_tag', 'DESC'),
        ]);
    }

    public function tambah_tagvideo()
    {
        if ($redirect = $this->ensureModule('tagvideo')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            $nama = trim((string) $this->request->getPost('a'));
            $this->portalModel->insert('tagvid', [
                'nama_tag' => $nama,
                'username' => $this->currentUsername(),
                'tag_seo'  => seo_title($nama),
                'count'    => 0,
            ]);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/tagvideo'));
        }

        return $this->renderAdmin('administrator/mod_tagvideo/view_tag_tambah');
    }

    public function edit_tagvideo($id = null)
    {
        if ($redirect = $this->ensureModule('tagvideo')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        if ($this->isPostSubmit()) {
            $postId = (int) $this->request->getPost('id');
            $nama   = trim((string) $this->request->getPost('a'));
            $this->portalModel->update('tagvid', [
                'nama_tag' => $nama,
                'username' => $this->currentUsername(),
                'tag_seo'  => seo_title($nama),
            ], $this->ownerWhere('id_tag', $postId));
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/tagvideo'));
        }

        return $this->renderAdmin('administrator/mod_tagvideo/view_tag_edit', [
            'rows' => $this->portalModel->findOne('tagvid', $this->ownerWhere('id_tag', $targetId)) ?? [],
        ]);
    }

    public function delete_tagvideo($id = null)
    {
        if ($redirect = $this->ensureModule('tagvideo')) {
            return $redirect;
        }

        $this->portalModel->delete('tagvid', $this->ownerWhere('id_tag', $this->resolveId($id)));
        $this->invalidateAll();

        return redirect()->to(base_url('administrator/tagvideo'));
    }

    // ==================== ADVERTISEMENTS & BANNERS ====================

    public function iklanatas()
    {
        return $this->adList('iklanatas');
    }

    public function tambah_iklanatas()
    {
        return $this->adCreate('iklanatas');
    }

    public function edit_iklanatas($id = null)
    {
        return $this->adEdit('iklanatas', $id);
    }

    public function delete_iklanatas($id = null)
    {
        return $this->adDelete('iklanatas', $id);
    }

    public function iklanhome()
    {
        return $this->adList('iklanhome');
    }

    public function tambah_iklanhome()
    {
        return $this->adCreate('iklanhome');
    }

    public function edit_iklanhome($id = null)
    {
        return $this->adEdit('iklanhome', $id);
    }

    public function delete_iklanhome($id = null)
    {
        return $this->adDelete('iklanhome', $id);
    }

    public function iklansidebar()
    {
        return $this->adList('iklansidebar');
    }

    public function tambah_iklansidebar()
    {
        return $this->adCreate('iklansidebar');
    }

    public function edit_iklansidebar($id = null)
    {
        return $this->adEdit('iklansidebar', $id);
    }

    public function delete_iklansidebar($id = null)
    {
        return $this->adDelete('iklansidebar', $id);
    }

    public function banner()
    {
        if ($redirect = $this->ensureModule('banner')) {
            return $redirect;
        }

        return $this->renderAdmin('administrator/mod_banner/view_banner', [
            'record' => $this->portalModel->viewOrdering('banner', 'id_banner', 'DESC'),
        ]);
    }

    public function tambah_banner()
    {
        if ($redirect = $this->ensureModule('banner')) {
            return $redirect;
        }

        if ($this->isPostSubmit()) {
            $this->portalModel->insert('banner', [
                'judul'       => trim((string) $this->request->getPost('a')),
                'url'         => (string) $this->request->getPost('b'),
                'tgl_posting' => date('Y-m-d'),
            ]);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/banner'));
        }

        return $this->renderAdmin('administrator/mod_banner/view_banner_tambah');
    }

    public function edit_banner($id = null)
    {
        if ($redirect = $this->ensureModule('banner')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id);
        if ($this->isPostSubmit()) {
            $postId = (int) $this->request->getPost('id');
            $this->portalModel->update('banner', [
                'judul'       => trim((string) $this->request->getPost('a')),
                'url'         => (string) $this->request->getPost('b'),
                'tgl_posting' => date('Y-m-d'),
            ], ['id_banner' => $postId]);
            $this->invalidateAll();

            return redirect()->to(base_url('administrator/banner'));
        }

        return $this->renderAdmin('administrator/mod_banner/view_banner_edit', [
            'rows' => $this->portalModel->findOne('banner', ['id_banner' => $targetId]) ?? [],
        ]);
    }

    public function delete_banner($id = null)
    {
        if ($redirect = $this->ensureModule('banner')) {
            return $redirect;
        }

        $this->portalModel->delete('banner', ['id_banner' => $this->resolveId($id)]);
        $this->invalidateAll();

        return redirect()->to(base_url('administrator/banner'));
    }

    private function adList(string $module)
    {
        if ($redirect = $this->ensureModule($module)) {
            return $redirect;
        }

        $cfg = self::AD_MODULES[$module];

        return $this->renderAdmin($cfg['view'], [
            'record' => $this->scopedList($cfg['table'], $cfg['pk'], 'DESC'),
        ]);
    }

    private function adCreate(string $module)
    {
        if ($redirect = $this->ensureModule($module)) {
            return $redirect;
        }

        $cfg = self::AD_MODULES[$module];
        if ($this->isPostSubmit()) {
            $filename = $this->uploadOptional('c', $cfg['dir'], ['gif', 'jpg', 'jpeg', 'png', 'webp', 'swf'], 3000);
            $data     = [
                'judul'       => trim((string) $this->request->getPost('a')),
                'username'    => $this->currentUsername(),
                'url'         => (string) $this->request->getPost('b'),
                'source'      => (string) $this->request->getPost('source'),
                'tgl_posting' => date('Y-m-d'),
            ];
            if ($cfg['hasPosisi']) {
                $data['posisi'] = (string) ($this->request->getPost('posisi') ?? 'home');
            }
            if ($filename !== null) {
                $data['gambar'] = $filename;
            }
            $this->portalModel->insert($cfg['table'], $data);
            $this->invalidateAll();

            return redirect()->to(base_url($cfg['route']));
        }

        return $this->renderAdmin($cfg['view'] . '_tambah');
    }

    private function adEdit(string $module, $id = null)
    {
        if ($redirect = $this->ensureModule($module)) {
            return $redirect;
        }

        $cfg      = self::AD_MODULES[$module];
        $targetId = $this->resolveId($id);
        if ($this->isPostSubmit()) {
            $postId   = (int) $this->request->getPost('id');
            $filename = $this->uploadOptional('c', $cfg['dir'], ['gif', 'jpg', 'jpeg', 'png', 'webp', 'swf'], 3000);
            $data     = [
                'judul'       => trim((string) $this->request->getPost('a')),
                'username'    => $this->currentUsername(),
                'url'         => (string) $this->request->getPost('b'),
                'source'      => (string) $this->request->getPost('source'),
                'tgl_posting' => date('Y-m-d'),
            ];
            if ($cfg['hasPosisi']) {
                $data['posisi'] = (string) ($this->request->getPost('posisi') ?? 'home');
            }
            if ($filename !== null) {
                $data['gambar'] = $filename;
            }
            $this->portalModel->update($cfg['table'], $data, $this->ownerWhere($cfg['pk'], $postId));
            $this->invalidateAll();

            return redirect()->to(base_url($cfg['route']));
        }

        return $this->renderAdmin($cfg['view'] . '_edit', [
            'rows' => $this->portalModel->findOne($cfg['table'], $this->ownerWhere($cfg['pk'], $targetId)) ?? [],
        ]);
    }

    private function adDelete(string $module, $id = null)
    {
        if ($redirect = $this->ensureModule($module)) {
            return $redirect;
        }

        $cfg = self::AD_MODULES[$module];
        $this->portalModel->delete($cfg['table'], $this->ownerWhere($cfg['pk'], $this->resolveId($id)));
        $this->invalidateAll();

        return redirect()->to(base_url($cfg['route']));
    }
}
