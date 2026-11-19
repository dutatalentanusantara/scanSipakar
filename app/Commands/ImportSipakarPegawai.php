<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ImportSipakarPegawai extends BaseCommand
{
    protected $group       = 'SIPAKAR';
    protected $name        = 'sipakar:import';
    protected $description = 'Import data pegawai ASN dari file CSV ke database SIPAKAR';
    protected $usage       = 'sipakar:import [options]';
    protected $options     = [
        '--dry-run' => 'Simulasikan import tanpa menulis ke database',
        '--reset'   => 'Kosongkan tabel sipakar_pegawai sebelum import',
        '--file'    => 'Path file CSV (default: Data PNS dan PPPK FIX.csv di root)',
    ];

    public function run(array $params)
    {
        $db = \Config\Database::connect();

        $dryRun  = CLI::getOption('dry-run') !== null;
        $reset   = CLI::getOption('reset') !== null;
        $fileOpt = CLI::getOption('file');

        $csvFile = $fileOpt ?? FCPATH . 'Data PNS dan PPPK FIX.csv';

        if (! file_exists($csvFile)) {
            $fallback = 'C:/Users/Boi Meningkat Manalu/Downloads/Data PNS dan PPPK FIX.csv';
            if (file_exists($fallback)) {
                $csvFile = $fallback;
            } else {
                CLI::error("File CSV tidak ditemukan di: {$csvFile}");
                return;
            }
        }

        CLI::write("=== SIPAKAR Pegawai Importer ===", 'green');
        CLI::write("File : {$csvFile}", 'yellow');
        CLI::write("Mode : " . ($dryRun ? 'DRY-RUN' : ($reset ? 'RESET + IMPORT' : 'UPSERT')), 'cyan');
        CLI::newLine();

        if ($reset && ! $dryRun) {
            $db->table('sipakar_pegawai')->truncate();
            CLI::write("Tabel sipakar_pegawai berhasil dikosongkan.", 'light_red');
        }

        $handle = fopen($csvFile, 'r');
        if (! $handle) {
            CLI::error("Gagal membuka file CSV.");
            return;
        }

        $headerFound = false;
        $dataRows    = [];

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            if (empty(array_filter($row))) {
                continue;
            }

            if (! $headerFound) {
                if (isset($row[1]) && strtoupper(trim($row[1])) === 'NIP') {
                    $headerFound = true;
                }
                continue;
            }

            $nip     = preg_replace('/\D/', '', trim($row[1] ?? ''));
            $nama    = trim($row[2] ?? '');
            $jabatan = trim($row[3] ?? '');
            $pangkat = trim($row[4] ?? '');

            if ($nip === '' || strlen($nip) < 15 || $nama === '') {
                continue;
            }

            $jenisAsn = $this->detectJenisAsn($pangkat);
            $defaultPassword = substr($nip, -6);

            $dataRows[] = [
                'nip'              => $nip,
                'nama'             => $nama,
                'jabatan'          => $jabatan,
                'pangkat_golongan' => $pangkat,
                'unit_kerja'       => $this->detectUnitKerja($jabatan),
                'jenis_asn'        => $jenisAsn,
                'password'         => password_hash($defaultPassword, PASSWORD_BCRYPT, ['cost' => 12]),
                'raw_pwd'          => $defaultPassword,
            ];
        }
        fclose($handle);

        CLI::write("Ditemukan " . count($dataRows) . " data ASN valid.\n", 'light_green');

        $inserted = 0;
        $updated  = 0;
        $now      = date('Y-m-d H:i:s');

        foreach ($dataRows as $i => $row) {
            $existing = $db->table('sipakar_pegawai')->where('nip', $row['nip'])->get()->getRowArray();

            if ($dryRun) {
                $status = $existing ? '[UPDATE]' : '[INSERT]';
                CLI::write(sprintf("%2d. [%-18s] %-35s (%s) %s", $i + 1, $row['nip'], mb_strimwidth($row['nama'], 0, 34, '...'), $row['pangkat_golongan'], $status));
                continue;
            }

            if ($existing) {
                $db->table('sipakar_pegawai')->where('nip', $row['nip'])->update([
                    'nama'             => $row['nama'],
                    'jabatan'          => $row['jabatan'],
                    'pangkat_golongan' => $row['pangkat_golongan'],
                    'unit_kerja'       => $row['unit_kerja'] ?: $existing['unit_kerja'],
                    'jenis_asn'        => $row['jenis_asn'],
                    'updated_at'       => $now,
                ]);
                $updated++;
                $status = '[UPDATED]';
            } else {
                $db->table('sipakar_pegawai')->insert([
                    'nip'                  => $row['nip'],
                    'nama'                 => $row['nama'],
                    'jabatan'              => $row['jabatan'],
                    'pangkat_golongan'     => $row['pangkat_golongan'],
                    'unit_kerja'           => $row['unit_kerja'],
                    'jenis_asn'            => $row['jenis_asn'],
                    'password'             => $row['password'],
                    'aktif'                => 1,
                    'login_attempts'       => 0,
                    'locked_until'         => null,
                    'last_login'           => null,
                    'must_change_password' => 1,
                    'created_at'           => $now,
                    'updated_at'           => $now,
                ]);
                $inserted++;
                $status = '[INSERTED]';
            }

            CLI::write(sprintf("%2d. [%-18s] %-35s (%s) %s", $i + 1, $row['nip'], mb_strimwidth($row['nama'], 0, 34, '...'), $row['pangkat_golongan'], $status));
        }

        CLI::newLine();
        if ($dryRun) {
            CLI::write("DRY-RUN selesai. Tidak ada data yang diubah.", 'yellow');
        } else {
            CLI::write("Import selesai!", 'green');
            CLI::write("Total Baru   : {$inserted}", 'green');
            CLI::write("Total Update : {$updated}", 'cyan');
            CLI::write("Password Default: 6 digit terakhir NIP masing-masing ASN.", 'light_yellow');
        }
    }

    private function detectJenisAsn(string $pangkat): string
    {
        $pangkat = trim(strtoupper($pangkat));
        if (preg_match('/^(I{1,3}|IV|V|VI|VII|VIII|IX|X|XI|XII)$/', $pangkat)) {
            return 'PPPK';
        }
        return 'PNS';
    }

    private function detectUnitKerja(string $jabatan): string
    {
        $j = strtolower($jabatan);
        if (str_contains($j, 'dwi kora') || str_contains($j, 'dwikora')) {
            return 'Kelurahan Dwikora';
        }
        if (str_contains($j, 'proklamasi')) {
            return 'Kelurahan Proklamasi';
        }
        if (str_contains($j, 'teladan')) {
            return 'Kelurahan Teladan';
        }
        if (str_contains($j, 'banjar')) {
            return 'Kelurahan Banjar';
        }
        if (str_contains($j, 'bantan')) {
            return 'Kelurahan Bantan';
        }
        if (str_contains($j, 'simarito')) {
            return 'Kelurahan Simarito';
        }
        if (str_contains($j, 'sipinggol')) {
            return 'Kelurahan Sipinggol-pinggol';
        }
        if (str_contains($j, 'timbang galung')) {
            return 'Kelurahan Timbang Galung';
        }
        return 'Kantor Kecamatan Siantar Barat';
    }
}
