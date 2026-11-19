<?php

namespace App\Controllers;

use App\Models\AlbumModel;

class Albums extends BasePortalController
{
    public function index($segment3 = null, $segment4 = null)
    {
        if (! $this->securityAudit->checkRateLimit('albums_index', 200, 3600, $this->request)) {
            return $this->response->setStatusCode(429)->setBody('Terlalu banyak permintaan.');
        }

        $perPage = 12;
        $jumlah  = (int) $this->portalModel->db()->table('album')->where('aktif', 'Y')->countAllResults();
        $dariRaw = $segment4 !== null ? $segment4 : $segment3;
        $dari    = $this->validatePaginationOffset($dariRaw, 5000);

        if ($jumlah > 0 && $dari >= $jumlah) {
            return redirect()->to(base_url('albums'));
        }

        $albums = $this->portalModel->viewWhereOrderingLimit('album', ['aktif' => 'Y'], 'id_album', 'DESC', $dari, $perPage);

        $data = [
            'title'           => 'Albums',
            'description'     => description(),
            'keywords'        => keywords(),
            'album'           => $albums,
            'pagination_html' => portal_pagination(base_url('albums/index'), $jumlah, $perPage, $dari),
        ];

        return $this->renderPortal('album', $data);
    }

    public function detail(?string $slug = null, $segment4 = null)
    {
        if (! $this->securityAudit->checkRateLimit('albums_detail', 50, 3600, $this->request)) {
            return $this->response->setStatusCode(429)->setBody('Terlalu banyak permintaan.');
        }

        $albumSeo = $this->securityAudit->validateSlug($slug);
        if ($albumSeo === null) {
            return redirect()->to(base_url('albums'));
        }

        $rows = $this->portalModel->viewJoinOne('album', 'users', 'username', ['album_seo' => $albumSeo, 'aktif' => 'Y'], 'id_album', 'DESC', 0, 1);
        if ($rows === []) {
            return redirect()->to(base_url('albums'));
        }

        $row     = $rows[0];
        $albumId = $this->securityAudit->validateId($row['id_album'] ?? 0);
        if ($albumId <= 0 || empty($row['jdl_album'])) {
            return redirect()->to(base_url('albums'));
        }

        $perPage = 5;
        $jumlah  = (int) $this->portalModel->db()->table('gallery')->where('id_album', $albumId)->countAllResults();
        $dari    = $this->validatePaginationOffset($segment4, 5000);
        if ($jumlah > 0 && $dari >= $jumlah) {
            return redirect()->to(base_url('albums/detail/' . $albumSeo));
        }

        $gallery = $this->portalModel->viewWhereOrderingLimit('gallery', ['id_album' => $albumId], 'id_gallery', 'DESC', $dari, $perPage);

        if ($this->securityAudit->shouldIncrementViewCounter('album', $albumId)) {
            $nextHits = min(2147483647, max(0, (int) ($row['hits_album'] ?? 0)) + 1);
            (new AlbumModel())->update($albumId, ['hits_album' => $nextHits]);
        }

        $albumDesc = ! empty($row['keterangan']) ? cetak_meta((string) $row['keterangan'], 0, 200) : '';
        $data      = [
            'title'           => $this->safeText((string) $row['jdl_album']),
            'description'     => $albumDesc !== '' ? $albumDesc : description(),
            'keywords'        => keywords(),
            'rows'            => $row,
            'detailalbum'     => $gallery,
            'pagination_html' => portal_pagination(base_url('albums/detail/' . $albumSeo), $jumlah, $perPage, $dari),
        ];

        return $this->renderPortal('detailalbum', $data);
    }
}
