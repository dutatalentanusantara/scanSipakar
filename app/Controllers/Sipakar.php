<?php

namespace App\Controllers;

use App\Models\SipakarModel;
use App\Services\SipakarService;
use App\Services\SipakarPegawaiAuthService;
use App\Services\UploadService;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Sipakar extends BasePortalController
{
    private SipakarModel $sipakar;
    private SipakarService $sipakarService;
    private SipakarPegawaiAuthService $pegawaiAuth;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->sipakarService = new SipakarService();
        $this->sipakar        = $this->sipakarService->model();
        $this->pegawaiAuth    = new SipakarPegawaiAuthService();
    }

    // ==================== FITUR PUBLIK (ASN) ====================

    public function index()
    {
        $year = (int) date('Y');

        return $this->renderPortal('sipakar/index', [
            'title'          => 'SIPAKAR – Sistem Pelayanan Administrasi Kenaikan Pangkat & Kenaikan Gaji Berkala',
            'description'    => 'Pusat informasi dan pelayanan administrasi digital kenaikan pangkat dan kenaikan gaji berkala ASN Kecamatan Siantar Barat.',
            'keywords'       => 'SIPAKAR, Kenaikan Pangkat, Kenaikan Gaji Berkala, ASN, Siantar Barat',
            'stats'          => $this->sipakar->getDashboardStats($year),
            'recent_selesai' => $this->sipakar->getArsipList(['tahun' => $year]),
            'current_method' => 'index',
        ]);
    }

    public function pengajuan()
    {
        if ($this->request->is('post') && $this->request->getPost('submit_pengajuan') !== null) {
            return $this->prosesPengajuan();
        }

        // Pre-fill form from logged-in employee session
        $pegawaiSession = $this->pegawaiAuth->getSession();

        return $this->renderPortal('sipakar/pengajuan', [
            'title'          => 'Formulir Pengajuan Online SIPAKAR – Kecamatan Siantar Barat',
            'description'    => 'Formulir pengajuan online usulan Kenaikan Pangkat (KP) dan Kenaikan Gaji Berkala (KGB) ASN.',
            'keywords'       => 'Pengajuan SIPAKAR, Formulir KP, Formulir KGB, ASN Siantar Barat',
            'current_method' => 'pengajuan',
            'jenis_get'      => (string) ($this->request->getGet('jenis') ?? ''),
            // Auto-fill from employee session
            'prefill_nip'       => $pegawaiSession['nip'] ?? '',
            'prefill_nama'      => $pegawaiSession['nama'] ?? '',
            'prefill_jabatan'   => $pegawaiSession['jabatan'] ?? '',
            'prefill_pangkat'   => $pegawaiSession['pangkat_golongan'] ?? '',
            'prefill_unit'      => $pegawaiSession['unit_kerja'] ?? '',
            'pegawai_logged_in' => $pegawaiSession !== null,
            'pegawai_session'   => $pegawaiSession,
        ]);
    }

    private function prosesPengajuan()
    {
        $nip             = (string) preg_replace('/[^0-9]/', '', (string) ($this->request->getPost('nip') ?? ''));
        $namaLengkap     = $this->cleanText($this->request->getPost('nama_lengkap'));
        $jabatan         = $this->cleanText($this->request->getPost('jabatan'));
        $unitKerja       = $this->cleanText($this->request->getPost('unit_kerja'));
        $pangkatGolongan = $this->cleanText($this->request->getPost('pangkat_golongan'));
        $emailRaw        = trim((string) ($this->request->getPost('email') ?? ''));
        $email           = filter_var($emailRaw, FILTER_VALIDATE_EMAIL) ? $emailRaw : '';
        $noHp            = (string) preg_replace('/[^0-9\+\-\s]/', '', (string) ($this->request->getPost('no_hp') ?? ''));
        $jenisPost       = (string) ($this->request->getPost('jenis_layanan') ?? 'KGB');
        $jenisLayanan    = in_array($jenisPost, ['KP', 'KGB'], true) ? $jenisPost : 'KGB';
        $subJenis        = $this->cleanText($this->request->getPost('sub_jenis'));
        $tmtTerakhir     = $this->cleanText($this->request->getPost('tmt_terakhir'));
        $tmtUsulan       = $this->cleanText($this->request->getPost('tmt_usulan'));

        if ($nip === '' || strlen($nip) < 18 || $namaLengkap === '' || $jabatan === '' || $unitKerja === '' || $noHp === '') {
            session()->setFlashdata('error_msg', 'Validasi Gagal: NIP wajib 18 digit dan seluruh data bertanda bintang (*) harus diisi secara valid.');

            return redirect()->to(base_url('sipakar/pengajuan'));
        }

        $ticket      = $this->sipakar->generateTicket();
        $idPengajuan = $this->sipakar->insertPengajuan([
            'nomor_tiket'      => $ticket,
            'nip'              => $nip,
            'nama_lengkap'     => $namaLengkap,
            'jabatan'          => $jabatan,
            'unit_kerja'       => $unitKerja,
            'pangkat_golongan' => $pangkatGolongan,
            'email'            => $email,
            'no_hp'            => $noHp,
            'jenis_layanan'    => $jenisLayanan,
            'sub_jenis'        => $subJenis,
            'periode_tahun'    => (int) date('Y'),
            'tmt_terakhir'     => $tmtTerakhir !== '' ? $tmtTerakhir : null,
            'tmt_usulan'       => $tmtUsulan !== '' ? $tmtUsulan : null,
            'gaji_pokok_lama'  => 0.00,
            'gaji_pokok_baru'  => 0.00,
            'status'           => 'Diajukan',
            'tgl_pengajuan'    => date('Y-m-d H:i:s'),
            'ip_address'       => $this->request->getIPAddress(),
            'user_agent'       => substr((string) $this->request->getUserAgent(), 0, 250),
        ]);

        $docKeys = [
            'dokumen_sk_terakhir'     => 'SK Pangkat / KGB Terakhir',
            'dokumen_sk_pangkat'      => 'SK Pangkat',
            'dokumen_kgb_terakhir'    => 'KGB Terakhir',
            'dokumen_skp_1th'         => 'SKP 1 Tahun Terakhir',
            'dokumen_skp_2th'         => 'SKP 2 Tahun Terakhir',
            'dokumen_sk_jabatan'      => 'SK Jabatan Struktural / Pelaksana',
            'dokumen_riwayat_jabatan' => 'Daftar Riwayat Jabatan',
            'dokumen_sertifikat_ukom' => 'Sertifikat Ujian Kompetensi',
            'dokumen_pendukung'       => 'Dokumen Pendukung Lainnya',
            'dokumen_pendukung_kgb'   => 'Dokumen Pendukung Lainnya',
            // Fallback backward compatibility for legacy field names
            'dokumen_skp'             => 'Penilaian Kinerja / SKP 2 Tahun Terakhir',
            'dokumen_pengantar'       => 'Surat Pengantar dari Kepala Unit Kerja',
        ];

        $uploader = new UploadService();
        foreach ($docKeys as $inputName => $labelDoc) {
            $fileObj = $this->request->getFile($inputName);
            if ($fileObj === null || ! $fileObj->isValid()) {
                continue;
            }
            $res = $uploader->upload($fileObj, 'asset/sipakar/', ['pdf'], 2048, true);
            if ($res['ok']) {
                $this->sipakar->insertDokumen([
                    'id_pengajuan'     => $idPengajuan,
                    'jenis_dokumen'    => $labelDoc,
                    'nama_file_asli'   => $res['original_name'],
                    'nama_file_simpan' => $res['filename'],
                    'ukuran_kb'        => $res['size_kb'],
                    'mime_type'        => $res['mime_type'],
                    'status_validasi'  => 'Menunggu',
                    'uploaded_at'      => date('Y-m-d H:i:s'),
                ]);
            }
        }

        session()->setFlashdata('success_ticket', $ticket);

        return redirect()->to(base_url('sipakar/sukses/' . $ticket));
    }

    public function sukses(string $ticket = '')
    {
        $ticket = $this->cleanText($ticket);
        $row    = $this->sipakar->getPengajuanByTicket($ticket);
        if ($row === null) {
            return redirect()->to(base_url('sipakar'));
        }

        return $this->renderPortal('sipakar/sukses', [
            'row'            => $row,
            'dokumen'        => $this->sipakar->getDokumenByPengajuan((int) $row['id_pengajuan']),
            'title'          => 'Bukti Pendaftaran Pengajuan SIPAKAR - ' . $ticket,
            'description'    => 'Tanda terima pendaftaran resmi SIPAKAR Kecamatan Siantar Barat.',
            'keywords'       => 'Bukti Pendaftaran, Resi SIPAKAR, Kecamatan Siantar Barat',
            'current_method' => 'pengajuan',
        ]);
    }

    public function tracking()
    {
        $keyword = $this->cleanText((string) ($this->request->getGet('q') ?? $this->request->getPost('q') ?? ''));
        $result  = null;
        $riwayat = [];
        $dokumen = [];
        $allNip  = [];

        if ($keyword !== '') {
            if (stripos($keyword, 'SPK-') !== false) {
                $result = $this->sipakar->getPengajuanByTicket($keyword);
            } else {
                $allNip = $this->sipakar->getPengajuanByNip($keyword);
                if ($allNip !== []) {
                    $result = $allNip[0];
                }
            }

            if ($result !== null) {
                $idPengajuan = (int) $result['id_pengajuan'];
                $riwayat     = $this->sipakar->getRiwayatByPengajuan($idPengajuan);
                $dokumen     = $this->sipakar->getDokumenByPengajuan($idPengajuan);
                $this->sipakar->logAudit(
                    $idPengajuan,
                    (string) ($result['nip'] ?? ''),
                    'Pengguna Portal',
                    'TRACKING_SEARCH',
                    "Mencari status permohonan dengan keyword: {$keyword}",
                    $this->request->getIPAddress(),
                    substr((string) $this->request->getUserAgent(), 0, 250)
                );
            }
        }

        return $this->renderPortal('sipakar/tracking', [
            'keyword'        => $keyword,
            'result'         => $result,
            'all_by_nip'     => $allNip,
            'riwayat'        => $riwayat,
            'dokumen'        => $dokumen,
            'title'          => 'Tracking Pengajuan SIPAKAR – Kecamatan Siantar Barat',
            'description'    => 'Lacak status permohonan Kenaikan Pangkat dan Kenaikan Gaji Berkala secara transparan dan real-time.',
            'keywords'       => 'Tracking SIPAKAR, Cek Status Berkas, ASN Siantar Barat',
            'current_method' => 'tracking',
        ]);
    }

    public function upload_perbaikan()
    {
        $ticket    = $this->cleanText($this->request->getPost('ticket'));
        $pengajuan = $this->sipakar->getPengajuanByTicket($ticket);

        if ($pengajuan === null || ($pengajuan['status'] ?? '') !== 'Perlu Perbaikan') {
            session()->setFlashdata('error_msg', 'Pengajuan tidak ditemukan atau tidak dalam status perbaikan.');

            return redirect()->to(base_url('sipakar/tracking?q=' . urlencode($ticket)));
        }

        $res = (new UploadService())->upload(
            $this->request->getFile('file_perbaikan'),
            'asset/sipakar/',
            ['pdf'],
            2048,
            true
        );

        if ($res['ok']) {
            $idPengajuan = (int) $pengajuan['id_pengajuan'];
            $this->sipakar->insertDokumen([
                'id_pengajuan'     => $idPengajuan,
                'jenis_dokumen'    => 'Dokumen Perbaikan / Revisi Berkas',
                'nama_file_asli'   => $res['original_name'],
                'nama_file_simpan' => $res['filename'],
                'ukuran_kb'        => $res['size_kb'],
                'mime_type'        => $res['mime_type'],
                'status_validasi'  => 'Menunggu',
                'uploaded_at'      => date('Y-m-d H:i:s'),
            ]);

            $this->sipakar->updateStatus(
                $idPengajuan,
                'Verifikasi Berkas',
                'Pemohon telah mengunggah dokumen perbaikan. Berkas siap diverifikasi ulang.',
                '',
                null,
                null,
                null,
                'Pemohon (ASN)'
            );

            session()->setFlashdata('success_msg', 'Dokumen perbaikan berhasil diunggah! Status permohonan kini kembali ke Verifikasi Berkas.');

            return redirect()->to(base_url('sipakar/tracking?q=' . urlencode($ticket)));
        }

        session()->setFlashdata('error_msg', 'Gagal mengunggah file. Pastikan format file PDF/JPG/PNG maksimal 6 MB.');

        return redirect()->to(base_url('sipakar/tracking?q=' . urlencode($ticket)));
    }

    public function arsip()
    {
        $filter = [
            'q'             => $this->cleanText($this->request->getGet('q')),
            'jenis_layanan' => $this->cleanText($this->request->getGet('jenis')),
            'tahun'         => $this->cleanText($this->request->getGet('tahun')) ?: date('Y'),
        ];

        return $this->renderPortal('sipakar/arsip', [
            'arsip'          => $this->sipakar->getArsipList($filter),
            'filter'         => $filter,
            'title'          => 'Arsip Dokumen & Hasil Layanan Resmi SIPAKAR',
            'description'    => 'Penyimpanan dan unduhan resmi dokumen SK Kenaikan Pangkat dan SK Kenaikan Gaji Berkala ASN Kecamatan Siantar Barat.',
            'keywords'       => 'Arsip Dokumen SIPAKAR, Unduh SK KP, Unduh SK KGB, ASN Siantar Barat',
            'current_method' => 'arsip',
        ]);
    }

    public function unduh_dokumen(string $filename = '')
    {
        $filename = basename($filename);
        $filepath = portal_resolve_contained_file('asset/sipakar/', $filename);

        if ($filepath === null) {
            throw PageNotFoundException::forPageNotFound('Dokumen tidak ditemukan.');
        }

        $this->sipakar->logAudit(
            null,
            null,
            'Pengguna Portal',
            'DOWNLOAD_DOKUMEN',
            "Mengunduh berkas: {$filename}",
            $this->request->getIPAddress(),
            substr((string) $this->request->getUserAgent(), 0, 250)
        );

        return $this->response->download($filepath, null)
            ->setFileName($filename)
            ->setHeader('X-Content-Type-Options', 'nosniff');
    }

    // ==================== FITUR TERPROTEKSI (BACKEND AUTH & RBAC) ====================

    public function dashboard()
    {
        if ($redirect = $this->guardLogin()) {
            return $redirect;
        }

        $tahun  = (int) ($this->request->getGet('tahun') ?: date('Y'));
        $filter = [
            'status'        => $this->cleanText($this->request->getGet('status')),
            'jenis_layanan' => $this->cleanText($this->request->getGet('jenis')),
            'tahun'         => $tahun,
            'q'             => $this->cleanText($this->request->getGet('q')),
        ];

        return $this->renderPortal('sipakar/dashboard', [
            'tahun'          => $tahun,
            'stats'          => $this->sipakar->getDashboardStats($tahun),
            'trends'         => $this->sipakar->getMonthlyTrends($tahun),
            'unit_kerja'     => $this->sipakar->getUnitKerjaStats($tahun),
            'filter'         => $filter,
            'pengajuan_list' => $this->sipakar->getPengajuanFiltered($filter, 50, 0),
            'title'          => 'Dashboard Monitoring Pengendalian Mutu SIPAKAR',
            'description'    => 'Dashboard monitoring real-time, statistik berkas lengkap/tidak lengkap, dan rekapitulasi status.',
            'keywords'       => 'Dashboard Monitoring SIPAKAR, Pengendalian Mutu, ASN Siantar Barat',
            'current_method' => 'dashboard',
        ]);
    }

    public function verifikasi($id = 0)
    {
        if ($redirect = $this->guardLogin()) {
            return $redirect;
        }

        $id  = (int) $id;
        $row = $this->sipakar->getPengajuanById($id);
        if ($row === null) {
            return redirect()->to(base_url('sipakar/dashboard'));
        }

        if ($this->request->is('post') && $this->request->getPost('submit_verifikasi') !== null) {
            $statusBaru       = $this->cleanText($this->request->getPost('status_baru'));
            $catatanOperator  = $this->cleanText($this->request->getPost('catatan_operator'));
            $catatanPerbaikan = $this->cleanText($this->request->getPost('catatan_perbaikan'));
            $nomorSk          = $this->cleanText($this->request->getPost('nomor_sk_resmi'));
            $tglSk            = $this->cleanText($this->request->getPost('tgl_sk_resmi'));
            $petugas          = $this->cleanText($this->request->getPost('operator_petugas')) ?: (string) (session()->get('username') ?? 'Operator');

            if ($statusBaru === 'Selesai' && session()->get('level') !== 'admin') {
                session()->setFlashdata('error_msg', 'Validasi Gagal: Persetujuan penetapan SK selesai hanya berhak dilakukan oleh Administrator / Pejabat Berwenang.');

                return redirect()->to(base_url('sipakar/verifikasi/' . $id));
            }

            $fileHasilResmi = (string) ($row['file_hasil_resmi'] ?? '');
            $uploadRes      = (new UploadService())->upload($this->request->getFile('file_sk_resmi'), 'asset/sipakar/', ['pdf'], 10240, true);
            if ($uploadRes['ok']) {
                $fileHasilResmi = $uploadRes['filename'];
            }

            $this->sipakar->updateStatus(
                $id,
                $statusBaru,
                $catatanOperator,
                $catatanPerbaikan,
                $nomorSk,
                $tglSk,
                $fileHasilResmi,
                $petugas
            );

            session()->setFlashdata('success_msg', "Status permohonan berhasil diperbarui menjadi: {$statusBaru}");

            return redirect()->to(base_url('sipakar/verifikasi/' . $id));
        }

        return $this->renderPortal('sipakar/verifikasi', [
            'row'            => $row,
            'dokumen'        => $this->sipakar->getDokumenByPengajuan($id),
            'riwayat'        => $this->sipakar->getRiwayatByPengajuan($id),
            'title'          => 'Pemeriksaan & Verifikasi Berkas: ' . ($row['nomor_tiket'] ?? ''),
            'description'    => 'Pemeriksaan administrasi kepegawaian dan validasi berkas usulan KP/KGB.',
            'keywords'       => 'Verifikasi Berkas SIPAKAR, Validasi Administrasi, Siantar Barat',
            'current_method' => 'verifikasi',
        ]);
    }

    public function riwayat()
    {
        if ($redirect = $this->guardLogin()) {
            return $redirect;
        }

        $filter = [
            'q'     => $this->cleanText($this->request->getGet('q')),
            'nip'   => $this->cleanText($this->request->getGet('nip')),
            'jenis' => $this->cleanText($this->request->getGet('jenis')),
        ];

        return $this->renderPortal('sipakar/riwayat', [
            'filter'         => $filter,
            'riwayat_list'   => $this->sipakar->getRiwayatPelayananAll($filter, 100),
            'title'          => 'Riwayat Pelayanan ASN – Rekam Jejak Pelayanan Kepegawaian',
            'description'    => 'Kronologi riwayat pengajuan, pemeriksaan, perubahan status, dan penyelesaian berkas ASN.',
            'keywords'       => 'Riwayat Pelayanan ASN, Kronologi SIPAKAR, Kecamatan Siantar Barat',
            'current_method' => 'riwayat',
        ]);
    }

    public function evaluasi()
    {
        if ($redirect = $this->guardRole(['admin', 'user'], 'Evaluasi Kinerja')) {
            return $redirect;
        }

        $tahun = (int) ($this->request->getGet('tahun') ?: date('Y'));

        return $this->renderPortal('sipakar/evaluasi', [
            'tahun'          => $tahun,
            'evaluasi'       => $this->sipakar->getEvaluasiKinerja($tahun),
            'title'          => 'Evaluasi Kinerja Pelayanan SIPAKAR',
            'description'    => 'Evaluasi efektivitas pelayanan, ketepatan tindak lanjut SLA, dan performa operator.',
            'keywords'       => 'Evaluasi Kinerja SIPAKAR, SLA Layanan, Analisis Mutu ASN',
            'current_method' => 'evaluasi',
        ]);
    }

    public function laporan()
    {
        if ($redirect = $this->guardLogin()) {
            return $redirect;
        }

        $tahun  = (int) ($this->request->getGet('tahun') ?: date('Y'));
        $unit   = $this->cleanText($this->request->getGet('unit'));
        $format = $this->cleanText($this->request->getGet('export'));

        $rows = $this->sipakar->getPengajuanFiltered([
            'tahun'      => $tahun,
            'unit_kerja' => $unit,
        ], 0, 0);

        $data = [
            'rows'           => $rows,
            'tahun'          => $tahun,
            'stats'          => $this->sipakar->getDashboardStats($tahun),
            'unit_kerja'     => $this->sipakar->getUnitKerjaStats($tahun),
            'current_method' => 'laporan',
        ];

        if ($format === 'excel' || $format === 'csv') {
            $csv      = $this->sipakarService->buildCsvExport($rows, $tahun);
            $filename = "Rekapitulasi_SIPAKAR_{$tahun}_" . date('Ymd_His') . '.csv';

            return $this->response
                ->setStatusCode(200)
                ->setHeader('Content-Type', 'text/csv; charset=utf-8')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->setBody($csv);
        }

        if ($format === 'print') {
            return view(template() . '/sipakar/laporan_print', $data);
        }

        $data['title']       = 'Laporan dan Rekapitulasi Kinerja Layanan SIPAKAR';
        $data['description'] = 'Rekapitulasi berkala usulan kepegawaian ASN, ekspor PDF/Excel, dan laporan resmi.';
        $data['keywords']    = 'Laporan dan Rekapitulasi SIPAKAR, Rekapitulasi KP KGB, Siantar Barat';

        return $this->renderPortal('sipakar/laporan', $data);
    }

    public function audit_log()
    {
        if ($redirect = $this->guardRole(['admin'], 'Audit Log & Keamanan')) {
            return $redirect;
        }

        return $this->renderPortal('sipakar/audit_log', [
            'logs'           => $this->sipakar->getAuditLogs(150),
            'title'          => 'Audit Log & Rekam Jejak Keamanan Sistem SIPAKAR',
            'description'    => 'Pencatatan seluruh aktivitas pengguna, otentikasi, verifikasi, dan audit trail keamanan.',
            'keywords'       => 'Audit Log SIPAKAR, Keamanan Sistem, Jejak Aktivitas Server',
            'current_method' => 'audit_log',
        ]);
    }

    public function hak_akses()
    {
        if ($redirect = $this->guardRole(['admin'], 'Pengaturan Hak Akses')) {
            return $redirect;
        }

        if ($this->request->is('post') && $this->request->getPost('submit_hak_akses') !== null) {
            $levelUser = $this->cleanText($this->request->getPost('level_user'));
            $username  = (string) (session()->get('username') ?? 'admin');
            $roleData  = [
                'nama_role'         => $this->cleanText($this->request->getPost('nama_role')),
                'deskripsi'         => $this->cleanText($this->request->getPost('deskripsi')),
                'akses_informasi'   => $this->request->getPost('akses_informasi') ? 1 : 0,
                'akses_pengajuan'   => $this->request->getPost('akses_pengajuan') ? 1 : 0,
                'akses_tracking'    => $this->request->getPost('akses_tracking') ? 1 : 0,
                'akses_arsip'       => $this->request->getPost('akses_arsip') ? 1 : 0,
                'akses_riwayat'     => $this->request->getPost('akses_riwayat') ? 1 : 0,
                'akses_dashboard'   => $this->request->getPost('akses_dashboard') ? 1 : 0,
                'akses_verifikasi'  => $this->request->getPost('akses_verifikasi') ? 1 : 0,
                'akses_persetujuan' => $this->request->getPost('akses_persetujuan') ? 1 : 0,
                'akses_evaluasi'    => $this->request->getPost('akses_evaluasi') ? 1 : 0,
                'akses_laporan'     => $this->request->getPost('akses_laporan') ? 1 : 0,
                'akses_audit_log'   => $this->request->getPost('akses_audit_log') ? 1 : 0,
                'akses_hak_akses'   => $this->request->getPost('akses_hak_akses') ? 1 : 0,
                'updated_by'        => $username,
            ];

            $this->sipakar->updateRole($levelUser, $roleData);
            $this->sipakar->logAudit(
                null,
                null,
                $username,
                'UPDATE_HAK_AKSES',
                "Memperbarui matriks hak akses untuk role: {$levelUser}",
                $this->request->getIPAddress(),
                substr((string) $this->request->getUserAgent(), 0, 250)
            );

            session()->setFlashdata('success_msg', "Pengaturan hak akses role [{$levelUser}] berhasil diperbarui.");

            return redirect()->to(base_url('sipakar/hak_akses'));
        }

        return $this->renderPortal('sipakar/hak_akses', [
            'roles'          => $this->sipakar->getRoles(),
            'title'          => 'Pengaturan Hak Akses (Role-Based Access Control) SIPAKAR',
            'description'    => 'Manajemen kewenangan dan pembatasan akses pengguna berdasarkan jabatan dan role sistem.',
            'keywords'       => 'Pengaturan Hak Akses, RBAC SIPAKAR, Otorisasi Server',
            'current_method' => 'hak_akses',
        ]);
    }

    private function guardLogin()
    {
        $session = session();
        $user    = (new \App\Services\AuthService())->validateLiveSession();
        $level   = (string) ($user['level'] ?? '');

        if ($user === null || ! in_array($level, ['admin', 'user'], true)) {
            $this->sipakar->logAudit(
                null,
                null,
                'Tamu Tak Dikenal',
                'UNAUTHORIZED_ACCESS_ATTEMPT',
                'Mencoba mengakses fitur terproteksi (' . current_url() . ') tanpa autentikasi sesi.',
                $this->request->getIPAddress(),
                substr((string) $this->request->getUserAgent(), 0, 250)
            );
            $session->setFlashdata('error_msg', 'Akses Terbatas: Anda wajib login sebagai Operator atau Administrator untuk mengakses menu pengelolaan SIPAKAR.');

            return redirect()->to(base_url('login'));
        }

        return null;
    }

    /**
     * @param list<string> $allowedLevels
     */
    private function guardRole(array $allowedLevels, string $featureName)
    {
        if ($redirect = $this->guardLogin()) {
            return $redirect;
        }

        $level    = (string) (session()->get('level') ?? '');
        $username = (string) (session()->get('username') ?? '');
        if (! in_array($level, $allowedLevels, true)) {
            $this->sipakar->logAudit(
                null,
                null,
                $username,
                'FORBIDDEN_ROLE_ATTEMPT',
                "Pengguna {$username} (role: {$level}) ditolak mengakses fitur: {$featureName} (" . current_url() . ')',
                $this->request->getIPAddress(),
                substr((string) $this->request->getUserAgent(), 0, 250)
            );
            session()->setFlashdata('error_msg', "Akses Ditolak: Hak akses Anda ({$level}) tidak memiliki otorisasi untuk membuka fitur {$featureName}.");

            return redirect()->to(base_url('sipakar/dashboard'));
        }

        return null;
    }
}
