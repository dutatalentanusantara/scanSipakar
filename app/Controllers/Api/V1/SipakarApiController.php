<?php

namespace App\Controllers\Api\V1;

use App\Models\SipakarModel;
use App\Services\PortalCacheService;
use CodeIgniter\RESTful\ResourceController;

class SipakarApiController extends ResourceController
{
    protected $format = 'json';

    private SipakarModel $sipakar;
    private PortalCacheService $cache;

    public function __construct()
    {
        $this->sipakar = new SipakarModel();
        $this->cache   = new PortalCacheService();
    }

    public function stats()
    {
        $tahun = (int) ($this->request->getGet('tahun') ?: date('Y'));
        if ($tahun < 2000 || $tahun > 2100) {
            return $this->failValidationErrors('Parameter tahun tidak valid.');
        }

        $stats = $this->cache->remember('api_sipakar_stats_' . $tahun, 120, function () use ($tahun): array {
            return [
                'summary'    => $this->sipakar->getDashboardStats($tahun),
                'trends'     => $this->sipakar->getMonthlyTrends($tahun),
                'unit_kerja' => $this->sipakar->getUnitKerjaStats($tahun),
            ];
        });

        return $this->respond([
            'status' => 'ok',
            'tahun'  => $tahun,
            'data'   => $stats,
        ]);
    }

    public function track(string $ticket = '')
    {
        $ticket = trim($ticket);
        if ($ticket === '' || ! preg_match('/^[a-zA-Z0-9\-_]{4,40}$/', $ticket)) {
            return $this->failValidationErrors('Format nomor tiket tidak valid.');
        }

        $row = $this->sipakar->getPengajuanByTicket($ticket);
        if ($row === null) {
            return $this->failNotFound('Pengajuan dengan nomor tiket tersebut tidak ditemukan.');
        }

        $riwayat = $this->sipakar->getRiwayatByPengajuan((int) $row['id_pengajuan']);
        $safeHistory = array_map(static function (array $item): array {
            return [
                'status_sebelumnya' => $item['status_sebelumnya'] ?? null,
                'status_baru'       => (string) ($item['status_baru'] ?? ''),
                'keterangan'        => (string) ($item['keterangan'] ?? ''),
                'waktu'             => (string) ($item['waktu'] ?? ''),
            ];
        }, $riwayat);

        return $this->respond([
            'status' => 'ok',
            'data'   => [
                'nomor_tiket'   => (string) ($row['nomor_tiket'] ?? ''),
                'nama_lengkap'  => (string) ($row['nama_lengkap'] ?? ''),
                'unit_kerja'    => (string) ($row['unit_kerja'] ?? ''),
                'jenis_layanan' => (string) ($row['jenis_layanan'] ?? ''),
                'sub_jenis'     => (string) ($row['sub_jenis'] ?? ''),
                'status'        => (string) ($row['status'] ?? ''),
                'tgl_pengajuan' => (string) ($row['tgl_pengajuan'] ?? ''),
                'tgl_selesai'   => $row['tgl_selesai'] ?? null,
                'riwayat'       => $safeHistory,
            ],
        ]);
    }
}
