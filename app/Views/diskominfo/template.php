<!DOCTYPE html>
<html lang="id" prefix="og: https://ogp.me/ns#">
<head>
    <?php
    $meta_esc = function ($val) {
        return htmlspecialchars((string) $val, ENT_QUOTES, 'UTF-8');
    };
    $site_name = function_exists('title') ? clean_meta_text(title()) : 'Pemerintah Kecamatan Siantar Barat';
    $default_desc = function_exists('description')
        ? clean_meta_text(description(), 220)
        : 'Portal Resmi Pemerintah Kecamatan Siantar Barat - Layanan Publik, Berita Terkini, dan Sistem Informasi Administrasi ASN SIPAKAR';
    $default_kw = function_exists('keywords') ? clean_meta_text(keywords(), 250) : 'siantar barat, pematangsiantar, kecamatan, layanan publik';

    $seg1 = strtolower((string) uri_segment(1));
    $seg2 = strtolower((string) uri_segment(2));
    $seg3 = (string) uri_segment(3);
    $uri_clean = uri_string();

    $raw_title = !empty($title) ? clean_meta_text($title) : $site_name;
    $is_home = ($seg1 === '' || $seg1 === 'main');
    $doc_title = $is_home ? ($site_name . ' - Kota Pematangsiantar') : ($raw_title . ' | ' . $site_name);
    $og_title = $is_home ? ($site_name . ' - Website Resmi Kota Pematangsiantar') : $raw_title;
    $og_description = !empty($description) ? clean_meta_text($description, 220) : $default_desc;
    if ($og_description === '') {
        $og_description = $default_desc;
    }
    $meta_keywords = !empty($keywords) ? clean_meta_text($keywords, 250) : $default_kw;
    if ($meta_keywords === '') {
        $meta_keywords = $default_kw;
    }

    $og_type = 'website';
    $canonical_url = $uri_clean !== '' ? base_url($uri_clean) : base_url();
    $img_candidate = 'asset/images/og-default.jpg';
    $img_fallback = 'asset/images/og-default.jpg';
    $og_image_meta = null;
    $article_meta = array();

    $og_berita = null;
    if ($seg1 === 'berita' && $seg2 === 'detail' && $seg3 !== '') {
        $og_rows = portal_model()->viewJoinTwo('berita', 'users', 'kategori', 'username', 'id_kategori', array('judul_seo' => $seg3), 'id_berita', 'DESC', 0, 1);
        $og_berita = $og_rows[0] ?? null;
    } elseif ($seg1 !== '' && !empty($rows) && is_array($rows) && !empty($rows['id_berita']) && !empty($rows['judul_seo'])) {
        $og_berita = $rows;
    }

    if (!empty($og_berita)) {
        $og_type = 'article';
        if (!empty($og_berita['judul_seo'])) {
            $canonical_url = base_url($og_berita['judul_seo']);
        }
        $img_candidate = !empty($og_berita['gambar']) ? ('asset/foto_berita/' . $og_berita['gambar']) : 'asset/foto_berita/no-image.jpg';
        $img_fallback = 'asset/foto_berita/no-image.jpg';
        if (!empty($og_berita['tanggal'])) {
            $pub_time = trim($og_berita['tanggal'] . ' ' . (!empty($og_berita['jam']) ? $og_berita['jam'] : '08:00:00'));
            $ts = @strtotime($pub_time);
            if ($ts) {
                $article_meta['published_time'] = date('c', $ts);
            }
        }
        if (!empty($og_berita['nama_kategori'])) {
            $article_meta['section'] = clean_meta_text($og_berita['nama_kategori']);
        }
        if (!empty($og_berita['nama_lengkap'])) {
            $article_meta['author'] = clean_meta_text($og_berita['nama_lengkap']);
        }
    } elseif ($seg1 === 'halaman' && $seg2 === 'detail' && !empty($rows) && is_array($rows) && !empty($rows['id_halaman'])) {
        $og_type = 'article';
        if (!empty($rows['judul_seo'])) {
            $canonical_url = base_url('halaman/detail/' . $rows['judul_seo']);
        }
        $img_candidate = !empty($rows['gambar']) ? ('asset/foto_statis/' . $rows['gambar']) : 'asset/images/og-default.jpg';
    } elseif ($seg1 === 'agenda' && $seg2 === 'detail' && !empty($rows) && is_array($rows) && !empty($rows['id_agenda'])) {
        $og_type = 'article';
        if (!empty($rows['tema_seo'])) {
            $canonical_url = base_url('agenda/detail/' . $rows['tema_seo']);
        }
        $img_candidate = !empty($rows['gambar']) ? ('asset/foto_agenda/' . $rows['gambar']) : 'asset/images/og-default.jpg';
    } elseif ($seg1 === 'albums' && $seg2 === 'detail' && !empty($rows) && is_array($rows) && !empty($rows['id_album'])) {
        $og_type = 'article';
        if (!empty($rows['album_seo'])) {
            $canonical_url = base_url('albums/detail/' . $rows['album_seo']);
        }
        $img_candidate = !empty($rows['gbr_album']) ? ('asset/img_album/' . $rows['gbr_album']) : 'asset/images/og-default.jpg';
    } elseif ($seg1 === 'playlist' && $seg2 === 'watch' && !empty($rows) && is_array($rows) && !empty($rows['id_video'])) {
        $og_type = 'video.other';
        if (!empty($rows['video_seo'])) {
            $canonical_url = base_url('playlist/watch/' . $rows['video_seo']);
        }
        if (!empty($rows['gbr_video']) && is_file(portal_fcpath('asset/img_video/' . $rows['gbr_video']))) {
            $img_candidate = 'asset/img_video/' . $rows['gbr_video'];
        } else {
            $yt_id = function_exists('extract_youtube_id') ? extract_youtube_id($rows['youtube'] ?? '') : '';
            if ($yt_id !== '') {
                $og_image_meta = array(
                    'url' => 'https://img.youtube.com/vi/' . rawurlencode($yt_id) . '/hqdefault.jpg',
                    'width' => 480,
                    'height' => 360,
                    'mime' => 'image/jpeg'
                );
            }
        }
    } elseif ($seg1 === 'playlist' && $seg2 === 'detail' && !empty($rows) && is_array($rows) && !empty($rows['id_playlist'])) {
        $og_type = 'website';
        if (!empty($rows['playlist_seo'])) {
            $canonical_url = base_url('playlist/detail/' . $rows['playlist_seo']);
        }
        $img_candidate = !empty($rows['gbr_playlist']) ? ('asset/img_playlist/' . $rows['gbr_playlist']) : 'asset/images/og-default.jpg';
    }

    if ($og_image_meta === null) {
        $og_image_meta = resolve_og_image_meta($img_candidate, $og_title);
    }

    $og_image_url = $og_image_meta['url'] ?? base_url('asset/images/og-default.jpg');
    $og_image_w = (int) ($og_image_meta['width'] ?? 1200);
    $og_image_h = (int) ($og_image_meta['height'] ?? 630);
    $og_image_mime = (string) ($og_image_meta['mime'] ?? $og_image_meta['type'] ?? 'image/jpeg');
    $is_https_url = (stripos($og_image_url, 'https://') === 0);

    $fav_ver = is_file(portal_fcpath('favicon.ico')) ? ('?v=' . filemtime(portal_fcpath('favicon.ico'))) : '';
    $apple_ver = is_file(portal_fcpath('asset/images/apple-touch-icon.png')) ? ('?v=' . filemtime(portal_fcpath('asset/images/apple-touch-icon.png'))) : '';
    $fav32_ver = is_file(portal_fcpath('asset/images/favicon-32x32.png')) ? ('?v=' . filemtime(portal_fcpath('asset/images/favicon-32x32.png'))) : '';
    $fav192_ver = is_file(portal_fcpath('asset/images/favicon-192x192.png')) ? ('?v=' . filemtime(portal_fcpath('asset/images/favicon-192x192.png'))) : '';
    ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $meta_esc($doc_title); ?></title>

    <!-- Meta Tags -->
    <meta name="description" content="<?= $meta_esc($og_description); ?>">
    <meta name="keywords" content="<?= $meta_esc($meta_keywords); ?>">
    <meta name="author" content="Kecamatan Siantar Barat Pematangsiantar">
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Language" content="id-ID">
    <meta name="distribution" content="Global">
    <meta name="rating" content="General">
    <link rel="canonical" href="<?= $meta_esc($canonical_url); ?>">

    <!-- Favicon & App Icons -->
    <link rel="icon" type="image/x-icon" href="<?= $meta_esc(base_url('favicon.ico') . $fav_ver); ?>">
    <link rel="shortcut icon" type="image/x-icon" href="<?= $meta_esc(encode_asset_url('asset/images/' . favicon()) . $fav_ver); ?>">
    <?php if (is_file(portal_fcpath('asset/images/favicon-32x32.png'))): ?>
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $meta_esc(base_url('asset/images/favicon-32x32.png') . $fav32_ver); ?>">
    <?php endif; ?>
    <?php if (is_file(portal_fcpath('asset/images/favicon-192x192.png'))): ?>
    <link rel="icon" type="image/png" sizes="192x192" href="<?= $meta_esc(base_url('asset/images/favicon-192x192.png') . $fav192_ver); ?>">
    <?php endif; ?>
    <?php if (is_file(portal_fcpath('asset/images/apple-touch-icon.png'))): ?>
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $meta_esc(base_url('asset/images/apple-touch-icon.png') . $apple_ver); ?>">
    <?php endif; ?>

    <!-- Open Graph Meta Tags -->
    <meta property="og:locale" content="id_ID">
    <meta property="og:site_name" content="<?= $meta_esc($site_name); ?>">
    <meta property="og:type" content="<?= $meta_esc($og_type); ?>">
    <meta property="og:title" content="<?= $meta_esc($og_title); ?>">
    <meta property="og:description" content="<?= $meta_esc($og_description); ?>">
    <meta property="og:url" content="<?= $meta_esc($canonical_url); ?>">
    <meta property="og:image" content="<?= $meta_esc($og_image_url); ?>">
    <?php if ($is_https_url): ?>
    <meta property="og:image:secure_url" content="<?= $meta_esc($og_image_url); ?>">
    <?php endif; ?>
    <?php if ($og_image_mime !== ''): ?>
    <meta property="og:image:type" content="<?= $meta_esc($og_image_mime); ?>">
    <?php endif; ?>
    <?php if ($og_image_w > 0 && $og_image_h > 0): ?>
    <meta property="og:image:width" content="<?= $og_image_w; ?>">
    <meta property="og:image:height" content="<?= $og_image_h; ?>">
    <?php endif; ?>
    <meta property="og:image:alt" content="<?= $meta_esc($og_title); ?>">
    <?php if (!empty($article_meta['published_time'])): ?>
    <meta property="article:published_time" content="<?= $meta_esc($article_meta['published_time']); ?>">
    <?php endif; ?>
    <?php if (!empty($article_meta['section'])): ?>
    <meta property="article:section" content="<?= $meta_esc($article_meta['section']); ?>">
    <?php endif; ?>
    <?php if (!empty($article_meta['author'])): ?>
    <meta property="article:author" content="<?= $meta_esc($article_meta['author']); ?>">
    <?php endif; ?>

    <!-- Twitter / X Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $meta_esc($og_title); ?>">
    <meta name="twitter:description" content="<?= $meta_esc($og_description); ?>">
    <meta name="twitter:image" content="<?= $meta_esc($og_image_url); ?>">
    <meta name="twitter:image:alt" content="<?= $meta_esc($og_title); ?>">
    
    <!-- CSS -->
    <link href="<?= base_url(); ?>template/<?= template(); ?>/assets/css/datatable.css" rel="stylesheet">
    <link href="<?= base_url(); ?>template/<?= template(); ?>/assets/css/app.css?v=<?= file_exists(portal_fcpath('template/diskominfo/assets/css/app.css')) ? filemtime(portal_fcpath('template/diskominfo/assets/css/app.css')) : time(); ?>" rel="stylesheet">
    <link href="<?= base_url(); ?>template/<?= template(); ?>/assets/css/add.css?v=<?= file_exists(portal_fcpath('template/diskominfo/assets/css/add.css')) ? filemtime(portal_fcpath('template/diskominfo/assets/css/add.css')) : time(); ?>" rel="stylesheet">
    <link href="<?= base_url(); ?>template/<?= template(); ?>/assets/plugins/slick/css/slick.css" rel="stylesheet">
    <link href="<?= base_url(); ?>template/<?= template(); ?>/assets/plugins/slick/css/slick-theme.css" rel="stylesheet">

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-215056084-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-215056084-1');
    </script>
    
    <!-- Custom JavaScript -->
    <script>
        function updateClock() {
            var currentDate = new Date();
            var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            var currentDay = days[currentDate.getDay()];
            var hours = (currentDate.getHours() < 10) ? '0' + currentDate.getHours() : currentDate.getHours();
            var minutes = (currentDate.getMinutes() < 10) ? '0' + currentDate.getMinutes() : currentDate.getMinutes();
            var seconds = (currentDate.getSeconds() < 10) ? '0' + currentDate.getSeconds() : currentDate.getSeconds();

            var jam = document.getElementById('dynamic-day');
            if (jam) {
                jam.innerHTML = currentDay + ', ' + currentDate.getDate() + ' ' + getMonthName(currentDate.getMonth()) + ' ' + currentDate.getFullYear() + ' <br> ' + hours + ':' + minutes + ':' + seconds + ' WIB';
            }
        }

        function getMonthName(month) {
            var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            return months[month];
        }

        setInterval(updateClock, 1000);
        updateClock();
    </script>
    
    <style>
        @media only screen and (max-width: 768px) {
            .minidashboard__body {
                flex-direction: column;
            }
            .news3col__col {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <h1 style="display: none">Website Resmi Kecamatan Siantar Barat Pematangsiantar</h1>

    <div class="bdgcontent">
        <div class="floatmenu">
            <div class="floatmenu__wrap"></div>
        </div>

        <div class="sticky-trigger"></div>
        <div class="mainmenu-spacer"></div>

        <!-- Content -->
        <?php
            echo view(template() . '/header');
            echo $contents;
            echo view(template() . '/footer');
        ?>
    </div>

    <!-- Back to top -->
    <a id="back-to-top" href="#" class="back-to-top" role="button">
        <span class="fa fa-arrow-up" aria-hidden="true"></span>
    </a>
    <!-- End Back to top -->

    <!-- JavaScript Files -->
    <script src="<?= base_url(); ?>template/<?= template(); ?>/assets/js/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.dropdown-toggle').on('click', function(e) {
                if ($(this).closest('.menu__utama').length) {
                    return;
                }
                var $subMenu = $(this).next('.dropdown-menu');
                if (!$subMenu.length) {
                    return;
                }
                e.preventDefault();
                if (!$subMenu.hasClass('show')) {
                    $(this).parents('.dropdown-menu').first().find('.show').removeClass('show');
                }
                $subMenu.toggleClass('show');

                $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function() {
                    $('.dropdown-submenu .show').removeClass('show');
                });
            });
        });
    </script>
    <script src="<?= base_url(); ?>template/<?= template(); ?>/assets/js/datatable.js"></script>
    <script src="<?= base_url(); ?>template/<?= template(); ?>/assets/plugins/slick/js/slick.min.js"></script>
    <script src="<?= base_url(); ?>template/<?= template(); ?>/assets/js/main.js?v=<?= file_exists(portal_fcpath('template/diskominfo/assets/js/main.js')) ? filemtime(portal_fcpath('template/diskominfo/assets/js/main.js')) : time(); ?>"></script>
    <script src="<?= base_url(); ?>template/<?= template(); ?>/assets/js/custom.js?v=<?= file_exists(portal_fcpath('template/diskominfo/assets/js/custom.js')) ? filemtime(portal_fcpath('template/diskominfo/assets/js/custom.js')) : time(); ?>"></script>
    <script src="https://widget.kominfo.go.id/gpr-widget-kominfo.min.js"></script>
</body>
</html>
