<?php

namespace App\Controllers;

use App\Models\BeritaModel;

class Kategori extends BasePortalController
{
    public function detail(?string $slug = null, $segment4 = null)
    {
        if (! $this->securityAudit->checkRateLimit('kategori_view', 200, 3600, $this->request)) {
            return $this->response->setStatusCode(429)->setBody('Terlalu banyak permintaan. Silakan tunggu sebentar.');
        }

        $kategoriSeo = $this->securityAudit->validateSlug($slug);
        if ($kategoriSeo === null) {
            return redirect()->to(base_url('main'));
        }

        $row = $this->portalModel->findOne('kategori', ['kategori_seo' => $kategoriSeo]);
        if ($row === null || empty($row['id_kategori']) || empty($row['nama_kategori'])) {
            return redirect()->to(base_url('main'));
        }

        $catId       = $this->securityAudit->validateId($row['id_kategori']);
        $perPage     = 10;
        $beritaModel = new BeritaModel();
        $jumlah      = $beritaModel->countPublished($catId);
        $dari        = $this->validatePaginationOffset($segment4);

        if ($jumlah > 0 && $dari >= $jumlah) {
            return redirect()->to(base_url('kategori/detail/' . $kategoriSeo));
        }

        $data = [
            'title'           => 'Berita Kategori ' . $this->safeText((string) $row['nama_kategori']),
            'description'     => description(),
            'keywords'        => keywords(),
            'rows'            => $row,
            'beritakategori'  => $beritaModel->getPublishedJoined($perPage, $dari, $catId),
            'pagination_html' => portal_pagination(base_url('kategori/detail/' . $kategoriSeo), $jumlah, $perPage, $dari),
        ];

        return $this->renderPortal('detailkategori', $data);
    }
}
