<?php

namespace App\Controllers\Concerns;

use App\Models\BeritaModel;
use App\Services\CaptchaService;

/**
 * Shared article-detail resolution and comment captcha setup for News and Berita controllers.
 */
trait RendersBeritaDetail
{
    protected function renderBeritaDetail(?string $slug, string $counterScope = 'news')
    {
        $cleanSlug = $this->securityAudit->validateSlug($slug);
        if ($cleanSlug === null) {
            return redirect()->to(base_url('main'));
        }

        $beritaModel = new BeritaModel();
        $row         = $beritaModel->findBySlug($cleanSlug);
        if ($row === null || empty($row['id_berita']) || empty($row['judul'])) {
            return redirect()->to(base_url('main'));
        }

        $idBerita = $this->securityAudit->validateId($row['id_berita']);
        if ($idBerita > 0 && $this->securityAudit->shouldIncrementViewCounter($counterScope, $idBerita)) {
            $beritaModel->incrementViews($idBerita, (int) ($row['dibaca'] ?? 0));
        }

        $cap = (new CaptchaService())->create([
            'img_width'   => 150,
            'img_height'  => 29,
            'word_length' => 5,
        ]);
        session()->set('mycaptcha', $cap['word']);

        $excerpt = cetak_meta((string) ($row['isi_berita'] ?? ''), 0, 200);
        $data    = [
            'title'       => cetak((string) $row['judul']),
            'description' => $excerpt !== '' ? $excerpt : description(),
            'keywords'    => cetak((string) ($row['tag'] ?? '')),
            'rows'        => $row,
            'image'       => $cap['image'],
        ];

        return $this->renderPortal('detailberita', $data);
    }
}

