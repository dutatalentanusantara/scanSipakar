<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Main');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

// =========================================================================
// PUBLIC PORTAL ROUTES
// =========================================================================
$routes->match(['get', 'post'], '/', 'Main::index');
$routes->match(['get', 'post'], 'main', 'Main::index');
$routes->match(['get', 'post'], 'main/index', 'Main::index');
$routes->get('rss.xml', 'Feed::index');
$routes->get('feed', 'Feed::index');

// Authentication
$routes->match(['get', 'post'], 'login', 'Login::index');
$routes->match(['get', 'post'], 'login/index', 'Login::index');
$routes->get('login/refresh_captcha', 'Login::refresh_captcha');
$routes->get('login/logout', 'Login::logout');

// Agenda
$routes->get('agenda', 'Agenda::index');
$routes->get('agenda/index', 'Agenda::index');
$routes->get('agenda/index/(:num)', 'Agenda::index/$1');
$routes->get('agenda/detail/(:segment)', 'Agenda::detail/$1');

// Albums / Gallery Aliases
$routes->get('albums', 'Albums::index');
$routes->get('albums/index', 'Albums::index');
$routes->get('albums/index/(:num)', 'Albums::index/$1');
$routes->get('albums/detail/(:segment)', 'Albums::detail/$1');
$routes->get('albums/detail/(:segment)/(:num)', 'Albums::detail/$1/$2');

$routes->get('gallery', 'Albums::index');
$routes->get('gallery/index/(:num)', 'Albums::index/$1');
$routes->get('gallery/detail/(:segment)', 'Albums::detail/$1');
$routes->get('gallery/detail/(:segment)/(:num)', 'Albums::detail/$1/$2');

$routes->get('galeri', 'Albums::index');
$routes->get('galeri/index/(:num)', 'Albums::index/$1');
$routes->get('galeri/detail/(:segment)', 'Albums::detail/$1');
$routes->get('galeri/detail/(:segment)/(:num)', 'Albums::detail/$1/$2');

// Analytics
$routes->get('analytics', 'Analytics::index');
$routes->get('analytics/index', 'Analytics::index');
$routes->get('analytics/visitor_stats', 'Analytics::visitor_stats');

// Berita / Articles / Indeks
$routes->match(['get', 'post'], 'berita', 'Berita::index');
$routes->match(['get', 'post'], 'berita/index', 'Berita::index');
$routes->match(['get', 'post'], 'berita/index/(:num)', 'Berita::index/$1');
$routes->get('berita/detail/(:segment)', 'Berita::detail/$1');
$routes->match(['get', 'post'], 'berita/kirim_komentar', 'Berita::kirim_komentar');
$routes->match(['get', 'post'], 'berita/indeks_berita', 'Berita::indeks_berita');

// Download / Dokumen Aliases
$routes->get('download', 'Download::index');
$routes->get('download/index', 'Download::index');
$routes->get('download/file/(:any)', 'Download::file/$1');
$routes->get('download/preview/(:any)', 'Download::preview/$1');
$routes->get('dokumen', 'Download::index');
$routes->get('dokumen/index', 'Download::index');
$routes->get('dokumen/file/(:any)', 'Download::file/$1');
$routes->get('dokumen/preview/(:any)', 'Download::preview/$1');

// Halaman Statis
$routes->get('halaman/detail/(:segment)', 'Halaman::detail/$1');
$routes->get('halaman/(:segment)', 'Halaman::detail/$1');

// Hubungi Kami & Kontak Alias
$routes->get('hubungi', 'Hubungi::index');
$routes->get('hubungi/index', 'Hubungi::index');
$routes->match(['get', 'post'], 'hubungi/kirim', 'Hubungi::kirim');
$routes->get('kontak', 'Hubungi::index');
$routes->match(['get', 'post'], 'kontak/kirim', 'Hubungi::kirim');

// Kategori & Tag
$routes->get('kategori/detail/(:segment)', 'Kategori::detail/$1');
$routes->get('kategori/detail/(:segment)/(:num)', 'Kategori::detail/$1/$2');
$routes->get('tag/detail/(:segment)', 'Tag::detail/$1');
$routes->get('tag/detail/(:segment)/(:num)', 'Tag::detail/$1/$2');

// Konsultasi & Forum Aliases
$routes->match(['get', 'post'], 'konsultasi', 'Konsultasi::index');
$routes->match(['get', 'post'], 'konsultasi/index', 'Konsultasi::index');
$routes->match(['get', 'post'], 'konsultasi/index/(:num)', 'Konsultasi::index/$1');
$routes->match(['get', 'post'], 'konsultasi/reply', 'Konsultasi::reply');
$routes->get('konsultasi/delete/(:num)', 'Konsultasi::delete/$1');

$routes->match(['get', 'post'], 'forum', 'Konsultasi::index');
$routes->match(['get', 'post'], 'forum/index/(:num)', 'Konsultasi::index/$1');
$routes->match(['get', 'post'], 'forum/reply', 'Konsultasi::reply');

// Playlist / Video Aliases
$routes->get('playlist', 'Playlist::index');
$routes->get('playlist/index', 'Playlist::index');
$routes->get('playlist/index/(:num)', 'Playlist::index/$1');
$routes->get('playlist/detail/(:segment)', 'Playlist::detail/$1');
$routes->get('playlist/detail/(:segment)/(:num)', 'Playlist::detail/$1/$2');
$routes->get('playlist/watch/(:segment)', 'Playlist::watch/$1');
$routes->match(['get', 'post'], 'playlist/kirim_komentar', 'Playlist::kirim_komentar');

$routes->get('video', 'Playlist::index');
$routes->get('video/index/(:num)', 'Playlist::index/$1');
$routes->get('video/detail/(:segment)', 'Playlist::watch/$1');
$routes->get('video/watch/(:segment)', 'Playlist::watch/$1');

// Polling
$routes->match(['get', 'post'], 'polling', 'Polling::index');
$routes->match(['get', 'post'], 'polling/index', 'Polling::index');
$routes->match(['get', 'post'], 'polling/hasil', 'Polling::hasil');

// Pelayanan Publik & Aliases
$routes->get('pelayanan/unduh-ahli-waris', 'Pelayanan::unduhAhliWaris');
$routes->get('pelayanan/unduh-penyerahan-waris', 'Pelayanan::unduhPenyerahanWaris');
$routes->get('pelayanan/unduh-surat-kuasa', 'Pelayanan::unduhSuratKuasa');
$routes->get('pelayanan/unduh-surat-hibah', 'Pelayanan::unduhSuratHibah');
$routes->get('pelayanan/unduh-surat-pindah', 'Pelayanan::unduhSuratPindah');
$routes->get('pelayanan/unduh-dispensasi-nikah', 'Pelayanan::unduhDispensasiNikah');
$routes->get('pelayanan', 'Pelayanan::index');
$routes->get('pelayanan/index', 'Pelayanan::index');
$routes->get('layanan', 'Pelayanan::index');
$routes->get('administrasi-warga', 'Pelayanan::index');
$routes->get('layanan-warga', 'Pelayanan::index');

// Profil Kecamatan
$routes->get('profil', 'Profil::index');
$routes->get('profil/index', 'Profil::index');
$routes->get('profil/file/(:any)', 'Profil::file/$1');

// Direct News controller routes
$routes->get('news/(:segment)', 'News::index/$1');
$routes->get('news/(:segment)/(:any)', 'News::index/$1/$2');

// =========================================================================
// SIPAKAR ROUTES (Public + Role-Protected)
// =========================================================================
$routes->get('sipakar', 'Sipakar::index');
$routes->get('sipakar/index', 'Sipakar::index');

// ── SIPAKAR Employee (Pegawai ASN) Auth ────────────────────────────────────
$routes->match(['get', 'post'], 'sipakar/login', 'SipakarLogin::index');
$routes->get('sipakar/logout', 'SipakarLogin::logout');
$routes->match(['get', 'post'], 'sipakar/ganti_password', 'SipakarLogin::ganti_password');

// ── Protected pengajuan routes (require sipakar employee login) ─────────────
$routes->group('sipakar', ['filter' => 'sipakarPegawaiAuth'], static function (RouteCollection $routes) {
    $routes->match(['get', 'post'], 'pengajuan', 'Sipakar::pengajuan');
    $routes->post('upload_perbaikan', 'Sipakar::upload_perbaikan');
});

// ── Public SIPAKAR routes ───────────────────────────────────────────────────
$routes->get('sipakar/sukses/(:segment)', 'Sipakar::sukses/$1');
$routes->match(['get', 'post'], 'sipakar/tracking', 'Sipakar::tracking');
$routes->get('sipakar/arsip', 'Sipakar::arsip');
$routes->get('sipakar/unduh_dokumen/(:any)', 'Sipakar::unduh_dokumen/$1');

$routes->get('layanan-kepegawaian', 'Sipakar::index');
$routes->match(['get', 'post'], 'layanan-kepegawaian/pengajuan', 'Sipakar::pengajuan');
$routes->match(['get', 'post'], 'layanan-kepegawaian/tracking', 'Sipakar::tracking');
$routes->get('layanan-kepegawaian/arsip', 'Sipakar::arsip');

$routes->group('sipakar', ['filter' => 'sipakarauth'], static function (RouteCollection $routes) {
    $routes->get('dashboard', 'Sipakar::dashboard');
    $routes->match(['get', 'post'], 'verifikasi/(:num)', 'Sipakar::verifikasi/$1');
    $routes->get('riwayat', 'Sipakar::riwayat');
    $routes->get('evaluasi', 'Sipakar::evaluasi');
    $routes->get('laporan', 'Sipakar::laporan');
});

$routes->group('sipakar', ['filter' => 'sipakarauth:admin'], static function (RouteCollection $routes) {
    $routes->get('audit_log', 'Sipakar::audit_log');
    $routes->match(['get', 'post'], 'hak_akses', 'Sipakar::hak_akses');
});

// =========================================================================
// ADMINISTRATOR CMS ROUTES (Modular Admin Controllers + Session Filter)
// =========================================================================
$routes->match(['get', 'post'], 'administrator', 'Admin\AuthController::index');
$routes->match(['get', 'post'], 'administrator/index', 'Admin\AuthController::index');
$routes->get('administrator/refresh_captcha', 'Admin\AuthController::refresh_captcha');
$routes->match(['get', 'post'], 'administrator/reset_password', 'Admin\AuthController::reset_password');
$routes->match(['get', 'post'], 'administrator/reset_password/(:any)', 'Admin\AuthController::reset_password/$1');
$routes->match(['get', 'post'], 'administrator/lupapassword', 'Admin\AuthController::lupapassword');
$routes->get('administrator/logout', 'Admin\AuthController::logout');

$routes->group('administrator', ['filter' => 'adminauth'], static function (RouteCollection $routes) {
    $routes->get('home', 'Admin\AuthController::home');

    // Settings
    $routes->match(['get', 'post'], 'identitaswebsite', 'Admin\SettingsController::identitaswebsite');
    $routes->match(['get', 'post'], 'logowebsite', 'Admin\SettingsController::logowebsite');
    $routes->get('templatewebsite', 'Admin\SettingsController::templatewebsite');
    $routes->match(['get', 'post'], 'tambah_templatewebsite', 'Admin\SettingsController::tambah_templatewebsite');
    $routes->match(['get', 'post'], 'edit_templatewebsite/(:num)', 'Admin\SettingsController::edit_templatewebsite/$1');
    $routes->match(['get', 'post'], 'edit_templatewebsite', 'Admin\SettingsController::edit_templatewebsite');
    $routes->get('aktif_templatewebsite/(:num)/(:segment)', 'Admin\SettingsController::aktif_templatewebsite/$1/$2');
    $routes->get('delete_templatewebsite/(:num)', 'Admin\SettingsController::delete_templatewebsite/$1');
    $routes->match(['get', 'post'], 'background', 'Admin\SettingsController::background');
    $routes->match(['get', 'post'], 'alamat', 'Admin\SettingsController::alamat');
    $routes->get('upgrade', 'Admin\SettingsController::upgrade');

    // Menu
    $routes->get('menuwebsite', 'Admin\MenuController::menuwebsite');
    $routes->get('menuwebsite1', 'Admin\MenuController::menuwebsite');
    $routes->post('save_menuwebsite', 'Admin\MenuController::save_menuwebsite');
    $routes->post('save', 'Admin\MenuController::save');
    $routes->match(['get', 'post'], 'posisi_menuwebsite', 'Admin\MenuController::posisi_menuwebsite');
    $routes->match(['get', 'post'], 'posisi_menuwebsite/(:num)', 'Admin\MenuController::posisi_menuwebsite/$1');
    $routes->post('delete_menuwebsite', 'Admin\MenuController::delete_menuwebsite');

    // Berita, Kategori, Tag, Editor Media
    $routes->get('listberita', 'Admin\BeritaController::listberita');
    $routes->match(['get', 'post'], 'tambah_listberita', 'Admin\BeritaController::tambah_listberita');
    $routes->match(['get', 'post'], 'edit_listberita/(:num)', 'Admin\BeritaController::edit_listberita/$1');
    $routes->match(['get', 'post'], 'edit_listberita', 'Admin\BeritaController::edit_listberita');
    $routes->get('publish_listberita/(:num)/(:segment)', 'Admin\BeritaController::publish_listberita/$1/$2');
    $routes->get('delete_listberita/(:num)', 'Admin\BeritaController::delete_listberita/$1');
    $routes->post('upload_image', 'Admin\BeritaController::upload_image');
    $routes->post('delete_image', 'Admin\BeritaController::delete_image');

    $routes->get('kategoriberita', 'Admin\BeritaController::kategoriberita');
    $routes->match(['get', 'post'], 'tambah_kategoriberita', 'Admin\BeritaController::tambah_kategoriberita');
    $routes->match(['get', 'post'], 'edit_kategoriberita/(:num)', 'Admin\BeritaController::edit_kategoriberita/$1');
    $routes->match(['get', 'post'], 'edit_kategoriberita', 'Admin\BeritaController::edit_kategoriberita');
    $routes->get('delete_kategoriberita/(:num)', 'Admin\BeritaController::delete_kategoriberita/$1');

    $routes->get('tagberita', 'Admin\BeritaController::tagberita');
    $routes->match(['get', 'post'], 'tambah_tagberita', 'Admin\BeritaController::tambah_tagberita');
    $routes->match(['get', 'post'], 'edit_tagberita/(:num)', 'Admin\BeritaController::edit_tagberita/$1');
    $routes->match(['get', 'post'], 'edit_tagberita', 'Admin\BeritaController::edit_tagberita');
    $routes->get('delete_tagberita/(:num)', 'Admin\BeritaController::delete_tagberita/$1');

    // Konten: Halaman, Agenda, Sekilas Info, Download
    $routes->get('halamanbaru', 'Admin\KontenController::halamanbaru');
    $routes->match(['get', 'post'], 'tambah_halamanbaru', 'Admin\KontenController::tambah_halamanbaru');
    $routes->match(['get', 'post'], 'edit_halamanbaru/(:num)', 'Admin\KontenController::edit_halamanbaru/$1');
    $routes->match(['get', 'post'], 'edit_halamanbaru', 'Admin\KontenController::edit_halamanbaru');
    $routes->get('delete_halamanbaru/(:num)', 'Admin\KontenController::delete_halamanbaru/$1');

    $routes->get('agenda', 'Admin\KontenController::agenda');
    $routes->match(['get', 'post'], 'tambah_agenda', 'Admin\KontenController::tambah_agenda');
    $routes->match(['get', 'post'], 'edit_agenda/(:num)', 'Admin\KontenController::edit_agenda/$1');
    $routes->match(['get', 'post'], 'edit_agenda', 'Admin\KontenController::edit_agenda');
    $routes->get('delete_agenda/(:num)', 'Admin\KontenController::delete_agenda/$1');

    $routes->get('sekilasinfo', 'Admin\KontenController::sekilasinfo');
    $routes->match(['get', 'post'], 'tambah_sekilasinfo', 'Admin\KontenController::tambah_sekilasinfo');
    $routes->match(['get', 'post'], 'edit_sekilasinfo/(:num)', 'Admin\KontenController::edit_sekilasinfo/$1');
    $routes->match(['get', 'post'], 'edit_sekilasinfo', 'Admin\KontenController::edit_sekilasinfo');
    $routes->get('delete_sekilasinfo/(:num)', 'Admin\KontenController::delete_sekilasinfo/$1');

    $routes->get('download', 'Admin\KontenController::download');
    $routes->match(['get', 'post'], 'tambah_download', 'Admin\KontenController::tambah_download');
    $routes->match(['get', 'post'], 'edit_download/(:num)', 'Admin\KontenController::edit_download/$1');
    $routes->match(['get', 'post'], 'edit_download', 'Admin\KontenController::edit_download');
    $routes->get('delete_download/(:num)', 'Admin\KontenController::delete_download/$1');

    // Media: Album, Gallery, Video, Playlist, TagVideo, Iklan, Banner
    $routes->get('album', 'Admin\MediaController::album');
    $routes->match(['get', 'post'], 'tambah_album', 'Admin\MediaController::tambah_album');
    $routes->match(['get', 'post'], 'edit_album/(:num)', 'Admin\MediaController::edit_album/$1');
    $routes->match(['get', 'post'], 'edit_album', 'Admin\MediaController::edit_album');
    $routes->get('delete_album/(:num)', 'Admin\MediaController::delete_album/$1');

    $routes->get('gallery', 'Admin\MediaController::gallery');
    $routes->match(['get', 'post'], 'tambah_gallery', 'Admin\MediaController::tambah_gallery');
    $routes->match(['get', 'post'], 'edit_gallery/(:num)', 'Admin\MediaController::edit_gallery/$1');
    $routes->match(['get', 'post'], 'edit_gallery', 'Admin\MediaController::edit_gallery');
    $routes->get('delete_gallery/(:num)', 'Admin\MediaController::delete_gallery/$1');

    $routes->get('video', 'Admin\MediaController::video');
    $routes->match(['get', 'post'], 'tambah_video', 'Admin\MediaController::tambah_video');
    $routes->match(['get', 'post'], 'edit_video/(:num)', 'Admin\MediaController::edit_video/$1');
    $routes->match(['get', 'post'], 'edit_video', 'Admin\MediaController::edit_video');
    $routes->get('delete_video/(:num)', 'Admin\MediaController::delete_video/$1');

    $routes->get('playlist', 'Admin\MediaController::playlist');
    $routes->match(['get', 'post'], 'tambah_playlist', 'Admin\MediaController::tambah_playlist');
    $routes->match(['get', 'post'], 'edit_playlist/(:num)', 'Admin\MediaController::edit_playlist/$1');
    $routes->match(['get', 'post'], 'edit_playlist', 'Admin\MediaController::edit_playlist');
    $routes->get('delete_playlist/(:num)', 'Admin\MediaController::delete_playlist/$1');

    $routes->get('tagvideo', 'Admin\MediaController::tagvideo');
    $routes->match(['get', 'post'], 'tambah_tagvideo', 'Admin\MediaController::tambah_tagvideo');
    $routes->match(['get', 'post'], 'edit_tagvideo/(:num)', 'Admin\MediaController::edit_tagvideo/$1');
    $routes->match(['get', 'post'], 'edit_tagvideo', 'Admin\MediaController::edit_tagvideo');
    $routes->get('delete_tagvideo/(:num)', 'Admin\MediaController::delete_tagvideo/$1');

    $routes->get('iklanatas', 'Admin\MediaController::iklanatas');
    $routes->match(['get', 'post'], 'tambah_iklanatas', 'Admin\MediaController::tambah_iklanatas');
    $routes->match(['get', 'post'], 'edit_iklanatas/(:num)', 'Admin\MediaController::edit_iklanatas/$1');
    $routes->match(['get', 'post'], 'edit_iklanatas', 'Admin\MediaController::edit_iklanatas');
    $routes->get('delete_iklanatas/(:num)', 'Admin\MediaController::delete_iklanatas/$1');

    $routes->get('iklanhome', 'Admin\MediaController::iklanhome');
    $routes->match(['get', 'post'], 'tambah_iklanhome', 'Admin\MediaController::tambah_iklanhome');
    $routes->match(['get', 'post'], 'edit_iklanhome/(:num)', 'Admin\MediaController::edit_iklanhome/$1');
    $routes->match(['get', 'post'], 'edit_iklanhome', 'Admin\MediaController::edit_iklanhome');
    $routes->get('delete_iklanhome/(:num)', 'Admin\MediaController::delete_iklanhome/$1');

    $routes->get('iklansidebar', 'Admin\MediaController::iklansidebar');
    $routes->match(['get', 'post'], 'tambah_iklansidebar', 'Admin\MediaController::tambah_iklansidebar');
    $routes->match(['get', 'post'], 'edit_iklansidebar/(:num)', 'Admin\MediaController::edit_iklansidebar/$1');
    $routes->match(['get', 'post'], 'edit_iklansidebar', 'Admin\MediaController::edit_iklansidebar');
    $routes->get('delete_iklansidebar/(:num)', 'Admin\MediaController::delete_iklansidebar/$1');

    $routes->get('banner', 'Admin\MediaController::banner');
    $routes->match(['get', 'post'], 'tambah_banner', 'Admin\MediaController::tambah_banner');
    $routes->match(['get', 'post'], 'edit_banner/(:num)', 'Admin\MediaController::edit_banner/$1');
    $routes->match(['get', 'post'], 'edit_banner', 'Admin\MediaController::edit_banner');
    $routes->get('delete_banner/(:num)', 'Admin\MediaController::delete_banner/$1');

    // Interaksi: Komentar, Sensor, Polling, YM, Pesan Masuk
    $routes->get('komentarberita', 'Admin\InteraksiController::komentarberita');
    $routes->match(['get', 'post'], 'edit_komentarberita/(:num)', 'Admin\InteraksiController::edit_komentarberita/$1');
    $routes->match(['get', 'post'], 'edit_komentarberita', 'Admin\InteraksiController::edit_komentarberita');
    $routes->get('delete_komentarberita/(:num)', 'Admin\InteraksiController::delete_komentarberita/$1');

    $routes->get('komentarvideo', 'Admin\InteraksiController::komentarvideo');
    $routes->match(['get', 'post'], 'edit_komentarvideo/(:num)', 'Admin\InteraksiController::edit_komentarvideo/$1');
    $routes->match(['get', 'post'], 'edit_komentarvideo', 'Admin\InteraksiController::edit_komentarvideo');
    $routes->get('delete_komentarvideo/(:num)', 'Admin\InteraksiController::delete_komentarvideo/$1');

    $routes->get('sensorkomentar', 'Admin\InteraksiController::sensorkomentar');
    $routes->match(['get', 'post'], 'tambah_sensorkomentar', 'Admin\InteraksiController::tambah_sensorkomentar');
    $routes->match(['get', 'post'], 'edit_sensorkomentar/(:num)', 'Admin\InteraksiController::edit_sensorkomentar/$1');
    $routes->match(['get', 'post'], 'edit_sensorkomentar', 'Admin\InteraksiController::edit_sensorkomentar');
    $routes->get('delete_sensorkomentar/(:num)', 'Admin\InteraksiController::delete_sensorkomentar/$1');

    $routes->get('jajakpendapat', 'Admin\InteraksiController::jajakpendapat');
    $routes->match(['get', 'post'], 'tambah_jajakpendapat', 'Admin\InteraksiController::tambah_jajakpendapat');
    $routes->match(['get', 'post'], 'edit_jajakpendapat/(:num)', 'Admin\InteraksiController::edit_jajakpendapat/$1');
    $routes->match(['get', 'post'], 'edit_jajakpendapat', 'Admin\InteraksiController::edit_jajakpendapat');
    $routes->get('delete_jajakpendapat/(:num)', 'Admin\InteraksiController::delete_jajakpendapat/$1');

    $routes->get('ym', 'Admin\InteraksiController::ym');
    $routes->match(['get', 'post'], 'tambah_ym', 'Admin\InteraksiController::tambah_ym');
    $routes->match(['get', 'post'], 'edit_ym/(:num)', 'Admin\InteraksiController::edit_ym/$1');
    $routes->match(['get', 'post'], 'edit_ym', 'Admin\InteraksiController::edit_ym');
    $routes->get('delete_ym/(:num)', 'Admin\InteraksiController::delete_ym/$1');

    $routes->get('pesanmasuk', 'Admin\InteraksiController::pesanmasuk');
    $routes->match(['get', 'post'], 'detail_pesanmasuk/(:num)', 'Admin\InteraksiController::detail_pesanmasuk/$1');
    $routes->get('delete_pesanmasuk/(:num)', 'Admin\InteraksiController::delete_pesanmasuk/$1');

    // Users & Modul RBAC
    $routes->get('manajemenuser', 'Admin\UserModulController::manajemenuser');
    $routes->match(['get', 'post'], 'tambah_manajemenuser', 'Admin\UserModulController::tambah_manajemenuser');
    $routes->match(['get', 'post'], 'edit_manajemenuser/(:segment)', 'Admin\UserModulController::edit_manajemenuser/$1');
    $routes->match(['get', 'post'], 'edit_manajemenuser', 'Admin\UserModulController::edit_manajemenuser');
    $routes->get('delete_manajemenuser/(:segment)', 'Admin\UserModulController::delete_manajemenuser/$1');
    $routes->get('delete_akses/(:num)/(:segment)', 'Admin\UserModulController::delete_akses/$1/$2');

    $routes->get('manajemenmodul', 'Admin\UserModulController::manajemenmodul');
    $routes->match(['get', 'post'], 'tambah_manajemenmodul', 'Admin\UserModulController::tambah_manajemenmodul');
    $routes->match(['get', 'post'], 'edit_manajemenmodul/(:num)', 'Admin\UserModulController::edit_manajemenmodul/$1');
    $routes->match(['get', 'post'], 'edit_manajemenmodul', 'Admin\UserModulController::edit_manajemenmodul');
    $routes->get('delete_manajemenmodul/(:num)', 'Admin\UserModulController::delete_manajemenmodul/$1');
});

// =========================================================================
// VERSIONED REST API v1 ROUTES (Rate-limited)
// =========================================================================
$routes->group('api/v1', ['filter' => 'apithrottle'], static function (RouteCollection $routes) {
    $routes->get('health', 'Api\V1\HealthController::index');
    $routes->get('identitas', 'Api\V1\ContentController::identitas');
    $routes->get('berita', 'Api\V1\ContentController::berita');
    $routes->get('berita/(:segment)', 'Api\V1\ContentController::beritaDetail/$1');
    $routes->get('agenda', 'Api\V1\ContentController::agenda');
    $routes->get('kategori', 'Api\V1\ContentController::kategori');
    $routes->get('download', 'Api\V1\ContentController::download');
    $routes->get('halaman/(:segment)', 'Api\V1\ContentController::halaman/$1');
    $routes->get('sipakar/stats', 'Api\V1\SipakarApiController::stats');
    $routes->get('sipakar/track/(:segment)', 'Api\V1\SipakarApiController::track/$1');
});

// =========================================================================
// FINAL CATCH-ALL NEWS SLUG ROUTE (preserves /slug-berita SEO URLs)
// =========================================================================
$routes->get('(:segment)', 'News::index/$1');
$routes->get('(:segment)/(:any)', 'News::index/$1/$2');
