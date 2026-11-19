<?php

namespace App\Controllers;

use App\Models\DownloadModel;

class Profil extends BasePortalController
{
    public function index($segment3 = null, $segment4 = null)
    {
        $perPage = 20;
        $jumlah  = (int) $this->portalModel->db()->table('download')->countAllResults();
        $dariRaw = $segment4 !== null ? $segment4 : $segment3;
        $dari    = $this->validatePaginationOffset($dariRaw);

        if ($jumlah > 0 && $dari >= $jumlah) {
            return redirect()->to(base_url('profil'));
        }

        $list         = $this->portalModel->viewOrderingLimit('download', 'id_download', 'DESC', $dari, $perPage);
        $totalUnduhan = (int) ($this->portalModel->db()->table('download')->selectSum('hits')->get()->getRowArray()['hits'] ?? 0);
        $documents    = array_map(static function (array $row): array {
            $filename  = (string) ($row['nama_file'] ?? '');
            $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            return [
                'id_download' => $row['id_download'] ?? null,
                'judul'       => $row['judul'] ?? '',
                'nama_file'   => $filename,
                'tgl_posting' => $row['tgl_posting'] ?? '',
                'hits'        => (int) ($row['hits'] ?? 0),
                'extension'   => $extension,
                'is_pdf'      => $extension === 'pdf',
            ];
        }, $list);

        $data = [
            'title'           => 'Profil & Dokumen Publik',
            'description'     => description(),
            'keywords'        => keywords(),
            'download'        => $list,
            'downloads'       => $list,
            'documents'       => $documents,
            'total_docs'      => $jumlah,
            'total_unduhan'   => $totalUnduhan,
            'search_keyword'  => '',
            'pagination_html' => portal_pagination(base_url('profil/index'), $jumlah, $perPage, $dari),
        ];

        return $this->renderPortal('download', $data);
    }

    public function file(?string $filename = null)
    {
        if (! $this->securityAudit->checkRateLimit('profil_download', 30, 3600, $this->request)) {
            return redirect()->to(base_url('profil'));
        }

        $cleanName = $filename !== null ? basename(rawurldecode($filename)) : '';
        if ($cleanName === '' || ! preg_match('/^[a-zA-Z0-9._\-\s()]+$/', $cleanName)) {
            return redirect()->to(base_url('profil'));
        }

        $row = $this->portalModel->findOne('download', ['nama_file' => $cleanName]);
        if ($row === null) {
            return redirect()->to(base_url('profil'));
        }

        $fullPath = portal_resolve_contained_file('asset/files/', (string) ($row['nama_file'] ?? ''));
        if ($fullPath === null) {
            return redirect()->to(base_url('profil'));
        }

        (new DownloadModel())->incrementHits((int) $row['id_download'], (int) ($row['hits'] ?? 0));

        return $this->response->download($fullPath, null);
    }
}
