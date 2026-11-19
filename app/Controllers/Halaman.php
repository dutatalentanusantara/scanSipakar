<?php

namespace App\Controllers;

use App\Models\HalamanModel;

class Halaman extends BasePortalController
{
    public function detail(?string $slug = null)
    {
        if (! $this->securityAudit->checkRateLimit('halaman_view', 100, 3600, $this->request)) {
            $this->securityAudit->logEvent('HALAMAN_RATE_LIMIT', 'Too many page views', $this->request);

            return $this->response->setStatusCode(429)->setBody('Terlalu banyak permintaan. Silakan tunggu sebentar.');
        }

        if ($this->securityAudit->isSuspiciousBot($this->request)) {
            $this->securityAudit->logEvent('HALAMAN_SUSPICIOUS_BOT', 'Suspicious bot detected', $this->request);

            return $this->response->setStatusCode(403)->setBody('Access denied');
        }

        if ($slug === 'struktu-organisasi') {
            $slug = 'struktur-organisasi';
        }

        $judulSeo = $this->securityAudit->validateSlug($slug);
        if ($judulSeo === null) {
            return redirect()->to(base_url('main'));
        }

        $halamanModel = new HalamanModel();
        $row          = $halamanModel->findBySlug($judulSeo);
        if ($row === null || empty($row['id_halaman']) || empty($row['judul'])) {
            return redirect()->to(base_url('main'));
        }

        $idHalaman = $this->securityAudit->validateId($row['id_halaman']);
        if ($idHalaman > 0 && $this->securityAudit->shouldIncrementViewCounter('halaman', $idHalaman)) {
            $nextViews = min(2147483647, max(0, (int) ($row['dibaca'] ?? 0)) + 1);
            $halamanModel->update($idHalaman, ['dibaca' => $nextViews]);
        }

        $excerpt = cetak_meta((string) ($row['isi_halaman'] ?? ''), 0, 200);
        $data    = [
            'title'       => cetak((string) $row['judul']),
            'description' => $excerpt !== '' ? $excerpt : description(),
            'keywords'    => cetak(str_replace(' ', ', ', (string) $row['judul'])),
            'rows'        => $row,
        ];

        return $this->renderPortal('detailhalaman', $data);
    }
}
