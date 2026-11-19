<?php

namespace App\Services;

use App\Models\IdentitasModel;
use App\Models\LogoModel;

/**
 * Resolves verified Open Graph & Twitter Card image metadata with landscape fallback guarantees.
 */
class OpenGraphService
{
    public function encodeAssetUrl(string $basePath, string $filename): string
    {
        $filename = trim(str_replace('\\', '/', $filename), '/');
        if ($filename === '') {
            return '';
        }
        $segments = explode('/', $filename);
        $encoded  = array_map(static fn ($seg) => rawurlencode($seg), $segments);

        return rtrim($basePath, '/') . '/' . implode('/', $encoded);
    }

    /**
     * Resolve Open Graph image URL and dimensions for any portal route.
     *
     * @return array{url: string, type: string, width: string, height: string, alt: string}
     */
    public function resolveOgImageMeta(string $rawGambar = '', string $title = '', string $context = ''): array
    {
        $fcpath   = portal_fcpath();
        $siteUrl  = rtrim((string) config('App')->baseURL, '/') . '/';
        $ident    = (new IdentitasModel())->getCurrent();
        $siteName = trim((string) ($ident['nama_website'] ?? 'Kecamatan Siantar Barat - Pemerintah Kota Pematangsiantar'));
        $raw      = trim($rawGambar);

        if ($raw !== '' && preg_match('#^https?://#i', $raw)) {
            return [
                'url'    => $raw,
                'type'   => $this->guessMime($raw),
                'width'  => '1200',
                'height' => '630',
                'alt'    => $title !== '' ? $title : $siteName,
            ];
        }

        $candidateDirs = [];
        if ($context === 'halaman') {
            $candidateDirs = ['asset/foto_statis/', 'asset/foto_berita/', 'asset/img_album/', 'asset/foto_banner/', 'asset/images/'];
        } elseif ($context === 'agenda') {
            $candidateDirs = ['asset/foto_agenda/', 'asset/foto_berita/', 'asset/images/'];
        } elseif ($context === 'album') {
            $candidateDirs = ['asset/img_album/', 'asset/img_galeri/', 'asset/foto_berita/', 'asset/images/'];
        } elseif ($context === 'video') {
            $candidateDirs = ['asset/img_video/', 'asset/img_playlist/', 'asset/foto_berita/', 'asset/images/'];
        } else {
            $candidateDirs = ['asset/foto_berita/', 'asset/foto_statis/', 'asset/foto_agenda/', 'asset/img_album/', 'asset/img_playlist/', 'asset/img_video/', 'asset/images/'];
        }

        $cleanRel = ltrim(str_replace('\\', '/', $raw), '/');
        if ($cleanRel !== '' && is_file($fcpath . $cleanRel)) {
            $meta = $this->inspectLocalImage($fcpath . $cleanRel);
            if ($meta !== null) {
                return [
                    'url'    => $this->encodeAssetUrl($siteUrl, $cleanRel),
                    'type'   => $meta['type'],
                    'width'  => $meta['width'],
                    'height' => $meta['height'],
                    'alt'    => $title !== '' ? $title : $siteName,
                ];
            }
        }

        if ($cleanRel !== '') {
            foreach ($candidateDirs as $dir) {
                $full = $fcpath . $dir . $cleanRel;
                if (is_file($full)) {
                    $meta = $this->inspectLocalImage($full);
                    if ($meta !== null) {
                        return [
                            'url'    => $this->encodeAssetUrl($siteUrl . $dir, $cleanRel),
                            'type'   => $meta['type'],
                            'width'  => $meta['width'],
                            'height' => $meta['height'],
                            'alt'    => $title !== '' ? $title : $siteName,
                        ];
                    }
                }
            }
        }

        $logoRow      = (new LogoModel())->orderBy('id_logo', 'DESC')->first();
        $logoFile     = ! empty($logoRow['gambar']) ? (string) $logoRow['gambar'] : '';
        $fallbackList = ['asset/images/og-default-1200x630.jpg', 'asset/images/og-default.jpg', 'asset/images/og-banner.jpg', 'asset/images/header_siantar_barat_modern.png'];
        if ($logoFile !== '') {
            $fallbackList[] = 'asset/logo/' . $logoFile;
        }
        $fallbackList[] = 'asset/images/logo.png';

        foreach ($fallbackList as $relPath) {
            if (is_file($fcpath . $relPath)) {
                $meta = $this->inspectLocalImage($fcpath . $relPath);
                if ($meta !== null) {
                    return [
                        'url'    => $this->encodeAssetUrl($siteUrl, $relPath),
                        'type'   => $meta['type'],
                        'width'  => $meta['width'],
                        'height' => $meta['height'],
                        'alt'    => $title !== '' ? $title : $siteName,
                    ];
                }
            }
        }

        return [
            'url'    => $siteUrl . 'favicon.ico',
            'type'   => 'image/x-icon',
            'width'  => '512',
            'height' => '512',
            'alt'    => $title !== '' ? $title : $siteName,
        ];
    }

    /**
     * @return array{type: string, width: string, height: string}|null
     */
    private function inspectLocalImage(string $fullPath): ?array
    {
        $info = @getimagesize($fullPath);
        if ($info === false || empty($info[0]) || empty($info[1])) {
            return null;
        }

        return [
            'type'   => (string) ($info['mime'] ?? $this->guessMime($fullPath)),
            'width'  => (string) $info[0],
            'height' => (string) $info[1],
        ];
    }

    private function guessMime(string $path): string
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return match ($ext) {
            'png'  => 'image/png',
            'webp' => 'image/webp',
            'gif'  => 'image/gif',
            'ico'  => 'image/x-icon',
            default => 'image/jpeg',
        };
    }
}
