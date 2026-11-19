<?php

namespace App\Models;

/**
 * Domain model and query repository for SIPAKAR (Sistem Informasi Pelayanan Kenaikan Pangkat & Gaji Berkala).
 */
class SipakarModel extends BaseAppModel
{
    protected $table          = 'sipakar_pengajuan';
    protected $primaryKey     = 'id_pengajuan';
    protected $useAutoIncrement = true;
    protected $allowedFields  = [
        'nomor_tiket',
        'nip',
        'nama_lengkap',
        'gelar_depan',
        'gelar_belakang',
        'jabatan',
        'unit_kerja',
        'pangkat_golongan',
        'email',
        'no_hp',
        'jenis_layanan',
        'sub_jenis',
        'periode_tahun',
        'tmt_terakhir',
        'tmt_usulan',
        'gaji_pokok_lama',
        'gaji_pokok_baru',
        'status',
        'catatan_operator',
        'catatan_perbaikan',
        'nomor_sk_resmi',
        'tgl_sk_resmi',
        'file_hasil_resmi',
        'operator_petugas',
        'pejabat_penyetuju',
        'tgl_pengajuan',
        'tgl_verifikasi',
        'tgl_selesai',
        'ip_address',
        'user_agent',
    ];

    public function generateTicket(): string
    {
        $prefix = 'SPK-' . date('Ym') . '-';
        $row    = $this->db->table('sipakar_pengajuan')
            ->selectMax('id_pengajuan', 'max_id')
            ->get()
            ->getRowArray();
        $next   = (int) ($row['max_id'] ?? 0) + 1;

        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT) . '-' . strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
    }

    /**
     * @param array<string, mixed> $data
     */
    public function insertPengajuan(array $data): int
    {
        $this->db->table('sipakar_pengajuan')->insert($data);

        return (int) $this->db->insertID();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function insertDokumen(array $data): int
    {
        $this->db->table('sipakar_dokumen')->insert($data);

        return (int) $this->db->insertID();
    }

    public function logRiwayat(
        int $idPengajuan,
        ?string $statusSebelumnya,
        string $statusBaru,
        string $keterangan,
        string $diubahOleh = 'Sistem'
    ): void {
        $this->db->table('sipakar_riwayat_status')->insert([
            'id_pengajuan'      => $idPengajuan,
            'status_sebelumnya' => $statusSebelumnya,
            'status_baru'       => $statusBaru,
            'keterangan'        => $keterangan,
            'diubah_oleh'       => $diubahOleh,
            'waktu'             => date('Y-m-d H:i:s'),
        ]);
    }

    public function logAudit(
        ?int $idPengajuan,
        ?string $nip,
        string $aktor,
        string $aktivitas,
        string $detail,
        string $ipAddress = '',
        string $userAgent = ''
    ): void {
        $this->db->table('sipakar_audit_log')->insert([
            'id_pengajuan' => $idPengajuan,
            'nip'          => $nip,
            'aktor'        => $aktor !== '' ? $aktor : 'Pengguna',
            'aktivitas'    => $aktivitas,
            'detail'       => $detail,
            'ip_address'   => $ipAddress !== '' ? $ipAddress : '127.0.0.1',
            'user_agent'   => $userAgent,
            'waktu'        => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getPengajuanById(int $id): ?array
    {
        return $this->db->table('sipakar_pengajuan')->where('id_pengajuan', $id)->get()->getRowArray();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getPengajuanByTicket(string $ticket): ?array
    {
        return $this->db->table('sipakar_pengajuan')->where('nomor_tiket', $ticket)->get()->getRowArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getPengajuanByNip(string $nip): array
    {
        return $this->db->table('sipakar_pengajuan')
            ->where('nip', $nip)
            ->orderBy('tgl_pengajuan', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getDokumenByPengajuan(int $idPengajuan): array
    {
        return $this->db->table('sipakar_dokumen')
            ->where('id_pengajuan', $idPengajuan)
            ->orderBy('id_dokumen', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getRiwayatByPengajuan(int $idPengajuan): array
    {
        return $this->db->table('sipakar_riwayat_status')
            ->where('id_pengajuan', $idPengajuan)
            ->orderBy('waktu', 'DESC')
            ->orderBy('id_riwayat', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function updateStatus(
        int $idPengajuan,
        string $statusBaru,
        string $catatanOperator = '',
        string $catatanPerbaikan = '',
        ?string $nomorSk = null,
        ?string $tglSk = null,
        ?string $fileHasil = null,
        string $operator = 'Operator Kepegawaian'
    ): bool {
        $existing = $this->getPengajuanById($idPengajuan);
        if ($existing === null) {
            return false;
        }

        $prev   = (string) ($existing['status'] ?? 'Diajukan');
        $update = [
            'status'           => $statusBaru,
            'operator_petugas' => $operator,
        ];
        if ($catatanOperator !== '') {
            $update['catatan_operator'] = $catatanOperator;
        }
        if ($catatanPerbaikan !== '') {
            $update['catatan_perbaikan'] = $catatanPerbaikan;
        }
        if (in_array($statusBaru, ['Verifikasi Berkas', 'Disetujui Internal', 'Diteruskan ke BKPSDM'], true) && empty($existing['tgl_verifikasi'])) {
            $update['tgl_verifikasi'] = date('Y-m-d H:i:s');
        }
        if ($nomorSk !== null && $nomorSk !== '') {
            $update['nomor_sk_resmi'] = $nomorSk;
        }
        if ($tglSk !== null && $tglSk !== '') {
            $update['tgl_sk_resmi'] = $tglSk;
        }
        if ($fileHasil !== null && $fileHasil !== '') {
            $update['file_hasil_resmi'] = $fileHasil;
        }
        if ($statusBaru === 'Selesai') {
            $update['tgl_selesai']       = date('Y-m-d H:i:s');
            $update['pejabat_penyetuju'] = $operator;
        }

        $this->db->table('sipakar_pengajuan')->where('id_pengajuan', $idPengajuan)->update($update);
        $ket = $catatanOperator !== '' ? $catatanOperator : ("Pembaruan status dari {$prev} ke {$statusBaru}");
        if ($catatanPerbaikan !== '') {
            $ket .= ' | Catatan perbaikan: ' . $catatanPerbaikan;
        }
        $this->logRiwayat($idPengajuan, $prev, $statusBaru, $ket, $operator);

        return true;
    }

    /**
     * @return array<string, int|float>
     */
    public function getDashboardStats(?int $year = null): array
    {
        $year ??= (int) date('Y');
        $sql  = "SELECT
                    COUNT(*) AS total,
                    SUM(CASE WHEN jenis_layanan = 'KP' THEN 1 ELSE 0 END) AS kp,
                    SUM(CASE WHEN jenis_layanan = 'KGB' THEN 1 ELSE 0 END) AS kgb,
                    SUM(CASE WHEN status = 'Diajukan' THEN 1 ELSE 0 END) AS diajukan,
                    SUM(CASE WHEN status = 'Verifikasi Berkas' THEN 1 ELSE 0 END) AS verifikasi,
                    SUM(CASE WHEN status = 'Perlu Perbaikan' THEN 1 ELSE 0 END) AS perbaikan,
                    SUM(CASE WHEN status IN ('Disetujui Internal','Diteruskan ke BKPSDM') THEN 1 ELSE 0 END) AS disetujui,
                    SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) AS selesai,
                    SUM(CASE WHEN status = 'Ditolak' THEN 1 ELSE 0 END) AS ditolak
                 FROM sipakar_pengajuan
                 WHERE periode_tahun = ?";
        $row = $this->db->query($sql, [$year])->getRowArray() ?? [];

        $total   = (int) ($row['total'] ?? 0);
        $selesai = (int) ($row['selesai'] ?? 0);
        $dalam   = (int) ($row['diajukan'] ?? 0) + (int) ($row['verifikasi'] ?? 0) + (int) ($row['disetujui'] ?? 0);

        return [
            'total'          => $total,
            'kp'             => (int) ($row['kp'] ?? 0),
            'kgb'            => (int) ($row['kgb'] ?? 0),
            'diajukan'       => (int) ($row['diajukan'] ?? 0),
            'verifikasi'     => (int) ($row['verifikasi'] ?? 0),
            'perbaikan'      => (int) ($row['perbaikan'] ?? 0),
            'disetujui'      => (int) ($row['disetujui'] ?? 0),
            'dalam_proses'   => $dalam,
            'selesai'        => $selesai,
            'ditolak'        => (int) ($row['ditolak'] ?? 0),
            'persen_selesai' => $total > 0 ? round(($selesai / $total) * 100, 1) : 0,
        ];
    }

    /**
     * @return array{labels: list<string>, kp: list<int>, kgb: list<int>}
     */
    public function getMonthlyTrends(?int $year = null): array
    {
        $year ??= (int) date('Y');
        $rows = $this->db->query(
            "SELECT MONTH(tgl_pengajuan) AS bln,
                    SUM(CASE WHEN jenis_layanan = 'KP' THEN 1 ELSE 0 END) AS kp,
                    SUM(CASE WHEN jenis_layanan = 'KGB' THEN 1 ELSE 0 END) AS kgb
             FROM sipakar_pengajuan
             WHERE periode_tahun = ?
             GROUP BY MONTH(tgl_pengajuan)",
            [$year]
        )->getResultArray();

        $byMonth = [];
        foreach ($rows as $r) {
            $byMonth[(int) $r['bln']] = ['kp' => (int) $r['kp'], 'kgb' => (int) $r['kgb']];
        }

        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $kp     = [];
        $kgb    = [];
        for ($m = 1; $m <= 12; $m++) {
            $kp[]  = $byMonth[$m]['kp'] ?? 0;
            $kgb[] = $byMonth[$m]['kgb'] ?? 0;
        }

        return ['labels' => $labels, 'kp' => $kp, 'kgb' => $kgb];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getUnitKerjaStats(?int $year = null): array
    {
        $year ??= (int) date('Y');

        return $this->db->query(
            "SELECT unit_kerja,
                    COUNT(*) AS total,
                    SUM(CASE WHEN jenis_layanan = 'KP' THEN 1 ELSE 0 END) AS kp,
                    SUM(CASE WHEN jenis_layanan = 'KGB' THEN 1 ELSE 0 END) AS kgb,
                    SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) AS selesai
             FROM sipakar_pengajuan
             WHERE periode_tahun = ?
             GROUP BY unit_kerja
             ORDER BY total DESC",
            [$year]
        )->getResultArray();
    }

    /**
     * @param array<string, mixed> $filter
     * @return list<array<string, mixed>>
     */
    public function getPengajuanFiltered(array $filter = [], int $limit = 20, int $offset = 0): array
    {
        $builder = $this->db->table('sipakar_pengajuan');
        $this->applyPengajuanFilter($builder, $filter);
        $builder->orderBy('tgl_pengajuan', 'DESC');
        if ($limit > 0) {
            $builder->limit($limit, max(0, $offset));
        }

        return $builder->get()->getResultArray();
    }

    /**
     * @param array<string, mixed> $filter
     */
    public function countPengajuanFiltered(array $filter = []): int
    {
        $builder = $this->db->table('sipakar_pengajuan');
        $this->applyPengajuanFilter($builder, $filter);

        return (int) $builder->countAllResults();
    }

    /**
     * @param array<string, mixed> $filter
     */
    private function applyPengajuanFilter($builder, array $filter): void
    {
        if (! empty($filter['tahun'])) {
            $builder->where('periode_tahun', (int) $filter['tahun']);
        }
        if (! empty($filter['status'])) {
            $builder->where('status', (string) $filter['status']);
        }
        $jenis = $filter['jenis_layanan'] ?? $filter['jenis'] ?? '';
        if ($jenis !== '') {
            $builder->where('jenis_layanan', (string) $jenis);
        }
        $unit = $filter['unit_kerja'] ?? $filter['unit'] ?? '';
        if ($unit !== '') {
            $builder->where('unit_kerja', (string) $unit);
        }
        if (! empty($filter['q'])) {
            $q = (string) $filter['q'];
            $builder->groupStart()
                ->like('nomor_tiket', $q)
                ->orLike('nip', $q)
                ->orLike('nama_lengkap', $q)
                ->orLike('unit_kerja', $q)
                ->groupEnd();
        }
    }

    /**
     * @param array<string, mixed> $filter
     * @return list<array<string, mixed>>
     */
    public function getArsipList(array $filter = []): array
    {
        $builder = $this->db->table('sipakar_pengajuan')->where('status', 'Selesai');
        if (! empty($filter['tahun'])) {
            $builder->where('periode_tahun', (int) $filter['tahun']);
        }
        if (! empty($filter['jenis_layanan'])) {
            $builder->where('jenis_layanan', (string) $filter['jenis_layanan']);
        }
        if (! empty($filter['q'])) {
            $q = (string) $filter['q'];
            $builder->groupStart()
                ->like('nip', $q)
                ->orLike('nama_lengkap', $q)
                ->orLike('nomor_sk_resmi', $q)
                ->orLike('nomor_tiket', $q)
                ->groupEnd();
        }

        return $builder->orderBy('tgl_selesai', 'DESC')->orderBy('id_pengajuan', 'DESC')->get()->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getAuditLogs(int $limit = 100): array
    {
        return $this->db->table('sipakar_audit_log')
            ->orderBy('waktu', 'DESC')
            ->limit(max(1, min(500, $limit)))
            ->get()
            ->getResultArray();
    }

    /**
     * @param array<string, mixed> $filter
     * @return list<array<string, mixed>>
     */
    public function getRiwayatPelayananAll(array $filter = [], int $limit = 50): array
    {
        $builder = $this->db->table('sipakar_riwayat_status r')
            ->select('r.*, p.nomor_tiket, p.nip, p.nama_lengkap, p.unit_kerja, p.jenis_layanan')
            ->join('sipakar_pengajuan p', 'r.id_pengajuan = p.id_pengajuan', 'inner');

        if (! empty($filter['jenis'])) {
            $builder->where('p.jenis_layanan', (string) $filter['jenis']);
        }
        if (! empty($filter['q'])) {
            $q = (string) $filter['q'];
            $builder->groupStart()
                ->like('p.nomor_tiket', $q)
                ->orLike('p.nip', $q)
                ->orLike('p.nama_lengkap', $q)
                ->orLike('r.keterangan', $q)
                ->groupEnd();
        }

        return $builder->orderBy('r.waktu', 'DESC')
            ->limit(max(1, min(300, $limit)))
            ->get()
            ->getResultArray();
    }

    /**
     * @return array<string, mixed>
     */
    public function getEvaluasiKinerja(?int $tahun = null): array
    {
        $tahun ??= (int) date('Y');
        $stats = $this->getDashboardStats($tahun);

        $sla = $this->db->query(
            "SELECT
                COALESCE(ROUND(AVG(DATEDIFF(tgl_selesai, tgl_pengajuan)), 1), 0) AS avg_days,
                COALESCE(MIN(DATEDIFF(tgl_selesai, tgl_pengajuan)), 0) AS min_days,
                COALESCE(MAX(DATEDIFF(tgl_selesai, tgl_pengajuan)), 0) AS max_days
             FROM sipakar_pengajuan
             WHERE status = 'Selesai' AND tgl_selesai IS NOT NULL AND periode_tahun = ?",
            [$tahun]
        )->getRowArray() ?? [];

        $operatorPerf = $this->db->query(
            "SELECT COALESCE(NULLIF(operator_petugas, ''), 'Sistem / Operator') AS operator_petugas,
                    COUNT(*) AS jml,
                    SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) AS tuntas,
                    SUM(CASE WHEN status = 'Perlu Perbaikan' THEN 1 ELSE 0 END) AS revisi
             FROM sipakar_pengajuan
             WHERE periode_tahun = ?
             GROUP BY COALESCE(NULLIF(operator_petugas, ''), 'Sistem / Operator')
             ORDER BY jml DESC",
            [$tahun]
        )->getResultArray();

        return [
            'avg_days'      => (float) ($sla['avg_days'] ?? 0),
            'min_days'      => (int) ($sla['min_days'] ?? 0),
            'max_days'      => (int) ($sla['max_days'] ?? 0),
            'stats'         => $stats,
            'operator_perf' => $operatorPerf,
            'unit_stats'    => $this->getUnitKerjaStats($tahun),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getRoles(): array
    {
        return $this->db->table('sipakar_hak_akses')->orderBy('id_akses', 'ASC')->get()->getResultArray();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRoleByLevel(string $level): ?array
    {
        return $this->db->table('sipakar_hak_akses')->where('level_user', $level)->get()->getRowArray();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updateRole(string $level, array $data): bool
    {
        return (bool) $this->db->table('sipakar_hak_akses')->where('level_user', $level)->update($data);
    }
}
