<?php

use App\Models\LegacyPortalModel;
use App\Models\UsersModulModel;
use App\Services\PortalContextService;

if (! function_exists('portal_context')) {
    function portal_context(): PortalContextService
    {
        static $ctx = null;
        if ($ctx === null) {
            $ctx = new PortalContextService();
        }

        return $ctx;
    }
}

if (! function_exists('portal_model')) {
    function portal_model(): LegacyPortalModel
    {
        static $model = null;
        if ($model === null) {
            $model = new LegacyPortalModel();
        }

        return $model;
    }
}

if (! function_exists('portal_fcpath')) {
    /**
     * Resolve filesystem base path for public assets (supports both staged testing and cutover root).
     */
    function portal_fcpath(string $relative = ''): string
    {
        $base    = str_replace('\\', '/', FCPATH);
        $staging = dirname(dirname(rtrim($base, '/'))) . '/';
        $root    = (str_contains($base, '/_ci4_staging/public') && is_dir($staging . 'asset'))
            ? $staging
            : $base;

        return $root . ltrim(str_replace('\\', '/', $relative), '/');
    }
}

if (! function_exists('portal_resolve_contained_file')) {
    /**
     * Resolve an existing file strictly contained within $relativeBaseDir (preventing traversal & sibling-prefix bypass).
     */
    function portal_resolve_contained_file(string $relativeBaseDir, string $fileOrRelativePath, bool $allowSubdir = false): ?string
    {
        $baseDir = realpath(portal_fcpath($relativeBaseDir));
        if ($baseDir === false) {
            return null;
        }

        $clean = trim(str_replace(["\0", '\\'], ['', '/'], $fileOrRelativePath), '/');
        if ($clean === '' || $clean === '.' || $clean === '..' || str_contains($clean, '..')) {
            return null;
        }

        if (! $allowSubdir) {
            $clean = basename($clean);
        }

        $candidate = $allowSubdir
            ? portal_fcpath($clean)
            : ($baseDir . DIRECTORY_SEPARATOR . $clean);

        $target = realpath($candidate);
        if ($target === false || ! is_file($target)) {
            return null;
        }

        $normalizedBase = rtrim($baseDir, '/\\') . DIRECTORY_SEPARATOR;
        $matches        = DIRECTORY_SEPARATOR === '\\'
            ? str_starts_with(strtolower($target), strtolower($normalizedBase))
            : str_starts_with($target, $normalizedBase);

        return $matches ? $target : null;
    }
}

if (! function_exists('portal_session')) {
    function portal_session(?string $key = null)
    {
        $s = session();

        return $key === null ? $s : $s->get($key);
    }
}

if (! function_exists('uri_segment')) {
    function uri_segment(int $segment, string $default = ''): string
    {
        $uri = service('uri');
        if ($segment <= $uri->getTotalSegments()) {
            return (string) $uri->getSegment($segment);
        }

        return $default;
    }
}

if (! function_exists('template')) {
    function template(): string
    {
        return portal_context()->getActiveTemplate();
    }
}

if (! function_exists('background')) {
    function background(): string
    {
        return portal_context()->getBackground();
    }
}

if (! function_exists('title')) {
    function title(): string
    {
        $id = portal_context()->getIdentitas();

        return ! empty($id['nama_website']) ? (string) $id['nama_website'] : 'Kecamatan Siantar Barat';
    }
}

if (! function_exists('description')) {
    function description(): string
    {
        $id = portal_context()->getIdentitas();

        return ! empty($id['meta_deskripsi']) ? (string) $id['meta_deskripsi'] : 'Website Resmi Kecamatan Siantar Barat';
    }
}

if (! function_exists('keywords')) {
    function keywords(): string
    {
        $id = portal_context()->getIdentitas();

        return ! empty($id['meta_keyword']) ? (string) $id['meta_keyword'] : 'siantar barat';
    }
}

if (! function_exists('favicon')) {
    function favicon(): string
    {
        $id = portal_context()->getIdentitas();

        return ! empty($id['favicon']) ? (string) $id['favicon'] : 'favicon.ico';
    }
}

if (! function_exists('cek_session_akses')) {
    function cek_session_akses(string $link, $id): bool
    {
        $session = session();
        if ((string) $session->get('level') === 'admin') {
            return true;
        }
        $allowed = (new UsersModulModel())->hasAccess((string) $id, $link);
        if (! $allowed) {
            return false;
        }

        return true;
    }
}

if (! function_exists('cek_session_admin')) {
    function cek_session_admin(): bool
    {
        return (string) session()->get('level') === 'admin';
    }
}

if (! function_exists('portal_pagination')) {
    /**
     * Build CI3-compatible Bootstrap pagination HTML from base URL, total rows, per page, and current offset.
     */
    function portal_pagination(string $baseUrl, int $totalRows, int $perPage, int $currentOffset): string
    {
        if ($totalRows <= $perPage || $perPage <= 0) {
            return '';
        }

        $totalPages  = (int) ceil($totalRows / $perPage);
        $currentPage = (int) floor(max(0, $currentOffset) / $perPage) + 1;
        $baseUrl     = rtrim($baseUrl, '/');

        $html = '<nav aria-label="Pagination"><ul class="pagination justify-content-center">';
        if ($currentPage > 1) {
            $prevOffset = ($currentPage - 2) * $perPage;
            $prevUrl    = $prevOffset > 0 ? $baseUrl . '/' . $prevOffset : $baseUrl;
            $html      .= '<li class="page-item"><a class="page-link" href="' . htmlspecialchars($prevUrl, ENT_QUOTES, 'UTF-8') . '">&laquo; Previous</a></li>';
        }

        $start = max(1, $currentPage - 3);
        $end   = min($totalPages, $currentPage + 3);
        for ($p = $start; $p <= $end; $p++) {
            $off = ($p - 1) * $perPage;
            $url = $off > 0 ? $baseUrl . '/' . $off : $baseUrl;
            if ($p === $currentPage) {
                $html .= '<li class="page-item active"><a class="page-link" href="#">' . $p . '</a></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link" href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">' . $p . '</a></li>';
            }
        }

        if ($currentPage < $totalPages) {
            $nextOffset = $currentPage * $perPage;
            $nextUrl    = $baseUrl . '/' . $nextOffset;
            $html      .= '<li class="page-item"><a class="page-link" href="' . htmlspecialchars($nextUrl, ENT_QUOTES, 'UTF-8') . '">Next &raquo;</a></li>';
        }
        $html .= '</ul></nav>';

        return $html;
    }
}

if (! function_exists('pagination_links')) {
    function pagination_links(): string
    {
        return (string) (service('renderer')->getData()['pagination_html'] ?? '');
    }
}

if (! function_exists('autolink')) {
    function autolink($str): string
    {
        $str = (string) $str;
        $str = (string) preg_replace('/(\s)((f|ht)tps?:\/\/[a-z0-9~#%@\&:=?+\/\.,_-]+[a-z0-9~#%@\&=?+\/_.;-]+)/i', '$1<a href="$2" target="_blank" rel="noopener noreferrer">$2</a>', $str);
        $str = (string) preg_replace('/(\s)(www\.[a-z0-9~#%@\&:=?+\/\.,_-]+[a-z0-9~#%@\&=?+\/_.;-]+)/i', '$1<a href="https://$2" target="_blank" rel="noopener noreferrer">$2</a>', $str);
        $str = (string) preg_replace('/(\s)([_\.0-9a-z-]+@(?:[0-9a-z][0-9a-z-]+\.)+[a-z]{2,6})/i', '$1<a href="mailto:$2">$2</a>', $str);
        $str = (string) preg_replace('/^((f|ht)tps?:\/\/[a-z0-9~#%@\&:=?+\/\.,_-]+[a-z0-9~#%@\&=?+\/_.;-]+)/i', '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>', $str);
        $str = (string) preg_replace('/^(www\.[a-z0-9~#%@\&:=?+\/\.,_-]+[a-z0-9~#%@\&=?+\/_.;-]+)/i', '<a href="https://$1" target="_blank" rel="noopener noreferrer">$1</a>', $str);
        $str = (string) preg_replace('/^([_\.0-9a-z-]+@(?:[0-9a-z][0-9a-z-]+\.)+[a-z]{2,6})/i', '<a href="mailto:$1">$1</a>', $str);

        return $str;
    }
}

if (! function_exists('anti_injection')) {
    function anti_injection($data): string
    {
        return stripslashes(strip_tags(htmlspecialchars((string) $data, ENT_QUOTES, 'UTF-8')));
    }
}

if (! function_exists('greeting')) {
    function greeting(): void
    {
        $h = (int) date('G');
        if ($h <= 11) {
            echo 'Selamat pagi!';
        } elseif ($h <= 14) {
            echo 'Selamat siang!';
        } elseif ($h <= 17) {
            echo 'Selamat sore!';
        } else {
            echo 'Selamat malam!';
        }
    }
}

if (! function_exists('format_rupiah')) {
    function format_rupiah($angka): string
    {
        return number_format((float) $angka, 0, ',', '.');
    }
}

if (! function_exists('tgl_indoo')) {
    function tgl_indoo($tgl): string
    {
        $tgl   = (string) $tgl;
        $bulan = function_exists('getBulan') ? getBulan((int) substr($tgl, 5, 2)) : substr($tgl, 5, 2);

        return substr($tgl, 8, 2) . '/' . $bulan;
    }
}

if (! function_exists('phpmu')) {
    function phpmu($s): string
    {
        $d = ['&amp;', 'amp;', 'nbsp;', '&nbsp;', '-', '/', '\\', ',', '.', '#', ':', ';', "'", '"', '[', ']', '{', '}', ')', '(', '|', '`', '~', '!', '@', '%', '$', '^', '&', '*', '=', '?', '+', '–'];
        $s = str_replace($d, '', (string) $s);

        return strtolower(str_replace(' ', ' ', $s));
    }
}

if (! function_exists('getOSs')) {
    function getOSs(): string
    {
        $ua = (string) ($_SERVER['HTTP_USER_AGENT'] ?? '');
        $map = [
            '/windows nt 10/i'     => 'Windows 10',
            '/windows nt 6.3/i'    => 'Windows 8.1',
            '/windows nt 6.2/i'    => 'Windows 8',
            '/windows nt 6.1/i'    => 'Windows 7',
            '/macintosh|mac os x/i' => 'Mac OS X',
            '/linux/i'             => 'Linux',
            '/ubuntu/i'            => 'Ubuntu',
            '/iphone/i'            => 'iPhone',
            '/ipad/i'              => 'iPad',
            '/android/i'           => 'Android',
        ];
        foreach ($map as $rx => $label) {
            if ($ua !== '' && preg_match($rx, $ua)) {
                return $label;
            }
        }

        return 'Unknown';
    }
}

if (! function_exists('getBrowser')) {
    function getBrowser(): string
    {
        $ua = (string) ($_SERVER['HTTP_USER_AGENT'] ?? '');
        $map = [
            '/msie/i'    => 'Explorer',
            '/firefox/i' => 'Firefox',
            '/safari/i'  => 'Safari',
            '/chrome/i'  => 'Chrome',
            '/opera/i'   => 'Opera',
            '/mobile/i'  => 'Handheld',
        ];
        foreach ($map as $rx => $label) {
            if ($ua !== '' && preg_match($rx, $ua)) {
                return $label;
            }
        }

        return 'Unknown';
    }
}
