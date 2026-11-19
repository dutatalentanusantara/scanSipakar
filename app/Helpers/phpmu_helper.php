<?php

use App\Services\OpenGraphService;

if (! function_exists('cetak')) {
    function cetak($str): string
    {
        return strip_tags(htmlentities((string) $str, ENT_QUOTES, 'UTF-8'));
    }
}

if (! function_exists('clean_meta_text')) {
    function clean_meta_text($str): string
    {
        $str = (string) $str;
        $str = preg_replace('/<(script|style)\b[^>]*>.*?<\/\1>/is', ' ', $str);
        $str = str_ireplace(
            ['</p>', '</div>', '</h1', '</h2', '</h3', '</h4', '</li>', '<br>', '<br/>', '<br />', '&nbsp;', "\r", "\n", "\t"],
            ' ',
            (string) $str
        );
        $str = strip_tags($str);
        $str = html_entity_decode($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $str = (string) preg_replace('/\s+/u', ' ', $str);

        return trim($str);
    }
}

if (! function_exists('cetak_meta')) {
    function cetak_meta($str, $mulai, $selesai): string
    {
        $clean = clean_meta_text($str);
        if ($clean === '') {
            return '';
        }
        $limit = max(1, (int) $selesai);
        if (mb_strlen($clean, 'UTF-8') <= $limit && (int) $mulai === 0) {
            return $clean;
        }
        $slice = mb_substr($clean, (int) $mulai, $limit, 'UTF-8');
        if (mb_strlen($clean, 'UTF-8') > ((int) $mulai + $limit)) {
            $lastSpace = mb_strrpos($slice, ' ', 0, 'UTF-8');
            if ($lastSpace !== false && $lastSpace > (int) floor($limit * 0.6)) {
                $slice = mb_substr($slice, 0, $lastSpace, 'UTF-8');
            }
            $slice = rtrim($slice, ".,;:- \t\n\r") . '...';
        }

        return $slice;
    }
}

if (! function_exists('sensor')) {
    function sensor($teks): string
    {
        $teks  = (string) $teks;
        $words = portal_model()->view('katajelek');
        foreach ($words as $w) {
            if (! empty($w['kata'])) {
                $teks = str_ireplace((string) $w['kata'], (string) ($w['ganti'] ?? '***'), $teks);
            }
        }

        return $teks;
    }
}

if (! function_exists('getSearchTermToBold')) {
    function getSearchTermToBold($text, $words)
    {
        $text  = (string) $text;
        $words = trim((string) $words);
        if ($words === '') {
            return $text;
        }
        preg_match_all('~[A-Za-z0-9_]+~', $words, $m);
        if (empty($m[0])) {
            return $text;
        }
        $re = '~(' . implode('|', array_map(static fn ($w) => preg_quote($w, '~'), $m[0])) . ')~i';

        return preg_replace($re, '<b style="color:red">$0</b>', $text);
    }
}

if (! function_exists('tgl_indo')) {
    function tgl_indo($tgl): string
    {
        $tgl     = (string) $tgl;
        $tanggal = substr($tgl, 8, 2);
        $bulan   = getBulan((int) substr($tgl, 5, 2));
        $tahun   = substr($tgl, 0, 4);

        return $tanggal . ' ' . $bulan . ' ' . $tahun;
    }
}

if (! function_exists('tgl_simpan')) {
    function tgl_simpan($tgl): string
    {
        $p = explode('/', (string) $tgl);

        return count($p) === 3 ? "{$p[2]}-{$p[1]}-{$p[0]}" : (string) $tgl;
    }
}

if (! function_exists('tgl_view')) {
    function tgl_view($tgl): string
    {
        $p = explode('-', (string) $tgl);

        return count($p) === 3 ? "{$p[2]}/{$p[1]}/{$p[0]}" : (string) $tgl;
    }
}

if (! function_exists('tgl_grafik')) {
    function tgl_grafik($tgl): string
    {
        $tgl     = (string) $tgl;
        $tanggal = substr($tgl, 8, 2);
        $bulan   = getBulan((int) substr($tgl, 5, 2));

        return $tanggal . '_' . $bulan;
    }
}

if (! function_exists('generateRandomString')) {
    function generateRandomString(int $length = 10): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $max        = strlen($characters) - 1;
        $out        = '';
        for ($i = 0; $i < $length; $i++) {
            $out .= $characters[random_int(0, $max)];
        }

        return $out;
    }
}

if (! function_exists('seo_title')) {
    function seo_title($s): string
    {
        $d = ['-', '/', '\\', ',', '.', '#', ':', ';', "'", '"', '[', ']', '{', '}', ')', '(', '|', '`', '~', '!', '@', '%', '$', '^', '&', '*', '=', '?', '+', '–'];
        $s = str_replace($d, '', (string) $s);

        return strtolower(str_replace(' ', '-', $s));
    }
}

if (! function_exists('hari_ini')) {
    function hari_ini($w): string
    {
        $seminggu = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return $seminggu[(int) $w % 7] ?? 'Senin';
    }
}

if (! function_exists('getBulan')) {
    function getBulan($bln): string
    {
        return match ((int) $bln) {
            1       => 'Jan',
            2       => 'Feb',
            3       => 'Mar',
            4       => 'Apr',
            5       => 'Mei',
            6       => 'Jun',
            7       => 'Jul',
            8       => 'Agu',
            9       => 'Sep',
            10      => 'Okt',
            11      => 'Nov',
            12      => 'Des',
            default => '',
        };
    }
}

if (! function_exists('cek_terakhir')) {
    function cek_terakhir($datetime, $full = false): string
    {
        $today = time();
        $created = strtotime((string) $datetime);
        if (! $created) {
            return '';
        }
        $diff    = abs($today - $created);
        $years   = (int) floor($diff / (365 * 60 * 60 * 24));
        $months  = (int) floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days    = (int) floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));
        $hours   = (int) floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24) / (60 * 60));
        $minutes = (int) floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24 - $hours * 60 * 60) / 60);
        $seconds = (int) ($diff % 60);

        if ($years > 0) {
            return $years . ' Tahun';
        }
        if ($months > 0) {
            return $months . ' Bulan';
        }
        if ($days > 0) {
            return $days . ' Hari';
        }
        if ($hours > 0) {
            return $hours . ' Jam';
        }
        if ($minutes > 0) {
            return $minutes . ' Menit';
        }

        return $seconds . ' Detik';
    }
}

if (! function_exists('safe_output')) {
    function safe_output($str, $allow_html = false): string
    {
        $str = (string) $str;
        if ($allow_html) {
            return strip_tags($str, '<p><br><strong><em><u><ul><ol><li><a><img><h1><h2><h3><h4><h5><h6><blockquote>');
        }

        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}

if (! function_exists('format_view_count_enhanced')) {
    function format_view_count_enhanced($count): string
    {
        $count = (int) $count;
        if ($count >= 1000000) {
            return round($count / 1000000, 1) . 'M';
        }
        if ($count >= 1000) {
            return round($count / 1000, 1) . 'K';
        }

        return number_format($count);
    }
}

if (! function_exists('smart_excerpt_enhanced')) {
    function smart_excerpt_enhanced($text, $limit = 160): string
    {
        $text = strip_tags((string) $text);
        if (strlen($text) <= $limit) {
            return $text;
        }
        $excerpt   = substr($text, 0, $limit);
        $lastSpace = strrpos($excerpt, ' ');
        if ($lastSpace !== false) {
            $excerpt = substr($excerpt, 0, $lastSpace);
        }

        return $excerpt . '...';
    }
}

if (! function_exists('time_ago_enhanced')) {
    function time_ago_enhanced($datetime): string
    {
        $diff = time() - strtotime((string) $datetime);
        if ($diff < 60) {
            return 'baru saja';
        }
        if ($diff < 3600) {
            return floor($diff / 60) . ' menit lalu';
        }
        if ($diff < 86400) {
            return floor($diff / 3600) . ' jam lalu';
        }
        if ($diff < 2592000) {
            return floor($diff / 86400) . ' hari lalu';
        }
        if ($diff < 31104000) {
            return floor($diff / 2592000) . ' bulan lalu';
        }

        return floor($diff / 31104000) . ' tahun lalu';
    }
}

if (! function_exists('get_category_icon')) {
    function get_category_icon($category_name): string
    {
        $icons = [
            'pemerintahan' => 'fa-university',
            'pembangunan'  => 'fa-building',
            'sosial'       => 'fa-users',
            'ekonomi'      => 'fa-chart-line',
            'pendidikan'   => 'fa-graduation-cap',
            'kesehatan'    => 'fa-heartbeat',
            'lingkungan'   => 'fa-leaf',
            'budaya'       => 'fa-theater-masks',
            'olahraga'     => 'fa-futbol',
            'teknologi'    => 'fa-laptop',
            'keamanan'     => 'fa-shield-alt',
            'hukum'        => 'fa-balance-scale',
            'pariwisata'   => 'fa-camera',
            'berita'       => 'fa-newspaper',
            'pengumuman'   => 'fa-bullhorn',
            'agenda'       => 'fa-calendar-alt',
        ];
        $lower = strtolower(trim((string) $category_name));
        foreach ($icons as $key => $icon) {
            if (str_contains($lower, $key)) {
                return $icon;
            }
        }

        return 'fa-folder';
    }
}

if (! function_exists('encode_asset_url')) {
    function encode_asset_url($base_path, $filename = null): string
    {
        $og = new OpenGraphService();
        if ($filename === null) {
            $raw = trim((string) $base_path);
            if ($raw === '' || preg_match('#^https?://#i', $raw)) {
                return $raw;
            }

            return $og->encodeAssetUrl((string) base_url(), $raw);
        }

        return $og->encodeAssetUrl((string) $base_path, (string) $filename);
    }
}

if (! function_exists('extract_youtube_id')) {
    function extract_youtube_id($url): string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return '';
        }
        if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|v\/|shorts\/)|youtu\.be\/)([A-Za-z0-9_-]{6,})/', $url, $m)) {
            return $m[1];
        }

        return '';
    }
}

if (! function_exists('resolve_og_image_meta')) {
    function resolve_og_image_meta($raw_gambar = '', $title = '', $context = ''): array
    {
        return (new OpenGraphService())->resolveOgImageMeta((string) $raw_gambar, (string) $title, (string) $context);
    }
}
