<?php

namespace App\Services;

use App\Models\SipakarModel;

/**
 * Business workflow service for SIPAKAR ASN services and CSV/Excel exports.
 */
class SipakarService
{
    private SipakarModel $model;
    private UploadService $upload;

    public function __construct(?SipakarModel $model = null, ?UploadService $upload = null)
    {
        $this->model  = $model ?? new SipakarModel();
        $this->upload = $upload ?? new UploadService();
    }

    public function model(): SipakarModel
    {
        return $this->model;
    }

    public function buildCsvExport(array $rows, int $tahun): string
    {
        $fp = fopen('php://temp', 'r+');
        fwrite($fp, "\xEF\xBB\xBF");
        fputcsv($fp, [
            'No',
            'Nomor Tiket',
            'NIP ASN',
            'Nama Lengkap',
            'Pangkat / Golongan',
            'Jabatan',
            'Unit Kerja / Kelurahan',
            'Jenis Layanan',
            'Sub Kategori',
            'Tanggal Pengajuan',
            'Status Berkas',
            'Nomor SK Resmi',
            'Tanggal SK',
            'Tanggal Selesai',
            'Operator Petugas',
        ]);

        $no = 1;
        foreach ($rows as $r) {
            fputcsv($fp, [
                $no++,
                $this->escapeCsvCell((string) ($r['nomor_tiket'] ?? '')),
                "'" . preg_replace('/[^0-9]/', '', (string) ($r['nip'] ?? '')),
                $this->escapeCsvCell(trim((string) ($r['gelar_depan'] ?? '') . ' ' . (string) ($r['nama_lengkap'] ?? '') . ' ' . (string) ($r['gelar_belakang'] ?? ''))),
                $this->escapeCsvCell((string) ($r['pangkat_golongan'] ?? '')),
                $this->escapeCsvCell((string) ($r['jabatan'] ?? '')),
                $this->escapeCsvCell((string) ($r['unit_kerja'] ?? '')),
                $this->escapeCsvCell((string) ($r['jenis_layanan'] ?? '')),
                $this->escapeCsvCell((string) ($r['sub_jenis'] ?? '')),
                $this->escapeCsvCell((string) ($r['tgl_pengajuan'] ?? '')),
                $this->escapeCsvCell((string) ($r['status'] ?? '')),
                $this->escapeCsvCell((string) ($r['nomor_sk_resmi'] ?? '')),
                $this->escapeCsvCell((string) ($r['tgl_sk_resmi'] ?? '')),
                $this->escapeCsvCell((string) ($r['tgl_selesai'] ?? '')),
                $this->escapeCsvCell((string) ($r['operator_petugas'] ?? '')),
            ]);
        }

        rewind($fp);
        $csv = (string) stream_get_contents($fp);
        fclose($fp);

        return $csv;
    }

    /**
     * Prevent spreadsheet formula injection (=, +, -, @, tab, CR) on CSV export cells.
     */
    public function escapeCsvCell(string $value): string
    {
        $clean = trim(str_replace(["\r\n", "\r", "\n"], ' ', $value));
        if ($clean === '') {
            return '';
        }

        if (preg_match('/^[=+\-@\t\r]/', $clean)) {
            return "'" . $clean;
        }

        return $clean;
    }
}
