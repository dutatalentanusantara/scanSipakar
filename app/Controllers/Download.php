<?php

namespace App\Controllers;

use App\Models\DownloadModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Download extends BasePortalController
{
    public function index()
    {
        $rawQ    = (string) ($this->request->getGet('q') ?? '');
        $keyword = trim((string) preg_replace('/[^a-zA-Z0-9\s\-_]/', '', $rawQ));

        $model = new DownloadModel();
        if ($keyword !== '') {
            $model->groupStart()
                ->like('judul', $keyword)
                ->orLike('nama_file', $keyword)
                ->groupEnd();
        }
        $rows = $model->orderBy('id_download', 'DESC')->findAll();

        $documents    = [];
        $totalUnduhan = 0;

        foreach ($rows as $doc) {
            $rawFile    = (string) ($doc['nama_file'] ?? '');
            $isExternal = str_starts_with($rawFile, 'http://') || str_starts_with($rawFile, 'https://');
            if ($isExternal) {
                $filename   = $rawFile;
                $fileExists = true;
                $filesize   = 'Cloud (Drive)';
                $ext        = 'gdrive';
            } else {
                $filename   = basename($rawFile);
                $filepath   = portal_resolve_contained_file('asset/files/', $filename);
                $fileExists = ($filepath !== null);
                $filesize   = $this->formatSize($fileExists ? (int) filesize($filepath) : 0);
                $ext        = strtolower((string) pathinfo($filename, PATHINFO_EXTENSION));
            }
            $hits       = (int) ($doc['hits'] ?? 0);
            $totalUnduhan += $hits;

            $documents[] = [
                'id_download' => (int) $doc['id_download'],
                'judul'       => htmlspecialchars((string) ($doc['judul'] ?? ''), ENT_QUOTES, 'UTF-8'),
                'nama_file'   => $filename,
                'tgl_posting' => (string) ($doc['tgl_posting'] ?? ''),
                'hits'        => $hits,
                'file_exists' => $fileExists,
                'filesize'    => $filesize,
                'extension'   => $ext !== '' ? $ext : 'file',
                'is_pdf'      => ($ext === 'pdf'),
                'is_external' => $isExternal,
            ];
        }

        return $this->renderPortal('download', [
            'title'          => 'Pusat Unduhan Dokumen Publik',
            'description'    => description(),
            'keywords'       => keywords(),
            'documents'      => $documents,
            'total_docs'     => count($documents),
            'total_unduhan'  => $totalUnduhan,
            'search_keyword' => $keyword,
        ]);
    }

    public function file($identifier = null)
    {
        if (! $this->securityAudit->checkRateLimit('download_rate', 40, 60, $this->request)) {
            return $this->response->setStatusCode(429)
                ->setBody('Terlalu banyak permintaan download. Silakan coba lagi beberapa saat lagi.');
        }

        $identifier = ($identifier !== null && $identifier !== '') ? (string) $identifier : uri_segment(3);
        if ($identifier === '') {
            return redirect()->to(base_url('download'));
        }

        $row = $this->findDownloadRecord($identifier);
        if ($row === null) {
            session()->setFlashdata('error', 'Dokumen yang diminta tidak ditemukan di database.');

            return redirect()->to(base_url('download'));
        }

        $rawFilename = (string) ($row['nama_file'] ?? '');
        if (str_starts_with($rawFilename, 'http://') || str_starts_with($rawFilename, 'https://')) {
            (new DownloadModel())->incrementHits((int) $row['id_download'], (int) ($row['hits'] ?? 0));

            return redirect()->to($rawFilename);
        }

        $safeFilename = basename($rawFilename);
        $filepath     = portal_resolve_contained_file('asset/files/', $safeFilename);

        if ($filepath === null) {
            session()->setFlashdata('error', 'File fisik dokumen sedang tidak tersedia di server.');

            return redirect()->to(base_url('download'));
        }

        (new DownloadModel())->incrementHits((int) $row['id_download'], (int) ($row['hits'] ?? 0));

        return $this->response->download($filepath, null)
            ->setFileName(str_replace('"', '', $safeFilename))
            ->setHeader('X-Content-Type-Options', 'nosniff');
    }

    public function preview($identifier = null)
    {
        $identifier = ($identifier !== null && $identifier !== '') ? (string) $identifier : uri_segment(3);
        if ($identifier === '') {
            return redirect()->to(base_url('download'));
        }

        $row = $this->findDownloadRecord($identifier);
        if ($row === null) {
            throw PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan.');
        }

        $rawFilename = (string) ($row['nama_file'] ?? '');
        if (str_starts_with($rawFilename, 'http://') || str_starts_with($rawFilename, 'https://')) {
            (new DownloadModel())->incrementHits((int) $row['id_download'], (int) ($row['hits'] ?? 0));

            return redirect()->to($rawFilename);
        }

        $safeFilename = basename($rawFilename);
        $filepath     = portal_resolve_contained_file('asset/files/', $safeFilename);

        if ($filepath === null) {
            throw PageNotFoundException::forPageNotFound('Berkas tidak ditemukan di server.');
        }

        $ext = strtolower((string) pathinfo($safeFilename, PATHINFO_EXTENSION));
        if ($ext !== 'pdf') {
            return redirect()->to(base_url('download/file/' . (int) $row['id_download']));
        }

        (new DownloadModel())->incrementHits((int) $row['id_download'], (int) ($row['hits'] ?? 0));

        return $this->response->download($filepath, null)
            ->setFileName(str_replace('"', '', $safeFilename))
            ->inline()
            ->setContentType('application/pdf', '')
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setHeader('Content-Security-Policy', "default-src 'self'; object-src 'self'; style-src 'unsafe-inline'; frame-ancestors 'self'")
            ->setHeader('Cache-Control', 'private, max-age=3600');
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findDownloadRecord(string $identifier): ?array
    {
        $model = new DownloadModel();
        if (is_numeric($identifier)) {
            return $model->where('id_download', (int) $identifier)->first();
        }

        $cleanId = basename(urldecode($identifier));

        return $model->where('nama_file', $cleanId)->first();
    }

    private function formatSize(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        }
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }
        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        }
        if ($bytes > 1) {
            return $bytes . ' bytes';
        }
        if ($bytes === 1) {
            return '1 byte';
        }

        return '0 bytes';
    }
}
