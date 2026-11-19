<?php

namespace App\Controllers;

use App\Models\BeritaModel;

class Tag extends BasePortalController
{
    public function detail(?string $slug = null, $segment4 = null)
    {
        $tagSeo = $this->securityAudit->validateSlug($slug);
        if ($tagSeo === null) {
            return redirect()->to(base_url('main'));
        }

        $row = $this->portalModel->findOne('tag', ['tag_seo' => $tagSeo]);
        if ($row === null || empty($row['nama_tag']) || empty($row['tag_seo'])) {
            return redirect()->to(base_url('main'));
        }

        $beritaModel = new BeritaModel();
        $jumlah      = $beritaModel->countByTagSlug((string) $row['tag_seo']);
        $perPage     = 10;
        $dari        = $this->validatePaginationOffset($segment4);

        if ($jumlah > 0 && $dari >= $jumlah) {
            return redirect()->to(base_url('tag/detail/' . $tagSeo));
        }

        $data = [
            'title'           => 'Berita Tag ' . $this->safeText((string) $row['nama_tag']),
            'description'     => description(),
            'keywords'        => keywords(),
            'rows'            => $row,
            'beritatag'       => $beritaModel->findByTagSlug((string) $row['tag_seo'], $perPage, $dari),
            'pagination_html' => portal_pagination(base_url('tag/detail/' . $tagSeo), $jumlah, $perPage, $dari),
        ];

        return $this->renderPortal('detailtag', $data);
    }
}
