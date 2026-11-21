<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * CI4 Database Seeder for Portal Kecamatan Siantar Barat.
 * Ensures initial baseline administrative accounts and required settings are present.
 */
class InitialDatabaseSeeder extends Seeder
{
    public function run()
    {
        $db = $this->db;

        // 1. Provision initial administrator account only if explicitly configured via environment
        // and no administrator account currently exists in the system.
        $existingAdmin = $db->table('users')
            ->groupStart()
                ->where('username', 'admin')
                ->orWhere('level', 'admin')
            ->groupEnd()
            ->get()
            ->getRowArray();

        if ($existingAdmin === null) {
            // Retrieve configuration safely from environment variables (SIP-02 Compliance)
            $initialPassword = env('INITIAL_ADMIN_PASSWORD');

            // Do NOT create an account with default/hardcoded credentials if INITIAL_ADMIN_PASSWORD is not set
            if (! empty($initialPassword) && is_string($initialPassword)) {
                $initialUsername = (string) (env('INITIAL_ADMIN_USERNAME') ?: 'admin');
                $initialName     = (string) (env('INITIAL_ADMIN_NAME') ?: 'Administrator Siantar Barat');
                $initialEmail    = (string) (env('INITIAL_ADMIN_EMAIL') ?: 'admin@siantarbarat.pematangsiantar.go.id');
                $initialPhone    = (string) (env('INITIAL_ADMIN_PHONE') ?: '081234567890');

                // Hash password dynamically at runtime using PHP native PASSWORD_DEFAULT (compatible with PHP 8)
                $passwordHash = password_hash($initialPassword, PASSWORD_DEFAULT);

                $db->table('users')->insert([
                    'username'     => $initialUsername,
                    'password'     => $passwordHash,
                    'nama_lengkap' => $initialName,
                    'email'        => $initialEmail,
                    'no_telp'      => $initialPhone,
                    'foto'         => '',
                    'level'        => 'admin',
                    'blokir'       => 'N',
                    'id_session'   => bin2hex(random_bytes(16)),
                ]);
            }
        }

        // 2. Ensure default website identity exists
        $identitas = $db->table('identitas')->where('id_identitas', 1)->get()->getRowArray();
        if ($identitas === null) {
            $db->table('identitas')->insert([
                'id_identitas'   => 1,
                'nama_website'   => 'Pemerintah Kecamatan Siantar Barat',
                'email'          => 'diskominfo@pematangsiantar.go.id',
                'url'            => 'http://localhost/siantarbarat/',
                'facebook'       => 'https://facebook.com/siantarbarat,https://twitter.com/siantarbarat,https://instagram.com/siantarbarat,https://youtube.com/@siantarbarat',
                'rekening'       => '-',
                'no_telp'        => '(0622) 123456',
                'meta_deskripsi' => 'Website Resmi Pemerintah Kecamatan Siantar Barat Kota Pematangsiantar',
                'meta_keyword'   => 'siantar barat, pematangsiantar, kecamatan, berita, layanan',
                'favicon'        => 'favicon.ico',
                'maps'           => '<iframe src="https://maps.google.com/maps?q=Kantor+Camat+Siantar+Barat,+Pematangsiantar&t=&z=16&ie=UTF8&iwloc=&output=embed" width="100%" height="380" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
            ]);
        }

        // 3. Ensure SIPAKAR default access levels
        $levels = [
            [
                'nama_role'        => 'Administrator & Pimpinan Camat',
                'level_user'       => 'admin',
                'deskripsi'        => 'Kewenangan penuh manajemen, persetujuan penetapan SK, evaluasi kinerja, audit log keamanan, dan kontrol hak akses sistem.',
                'akses_informasi'  => 1,
                'akses_pengajuan'  => 1,
                'akses_tracking'   => 1,
                'akses_arsip'      => 1,
                'akses_riwayat'    => 1,
                'akses_dashboard'  => 1,
                'akses_verifikasi' => 1,
                'akses_persetujuan'=> 1,
                'akses_evaluasi'   => 1,
                'akses_laporan'    => 1,
                'akses_audit_log'  => 1,
                'akses_hak_akses'  => 1,
                'updated_by'       => 'admin',
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'nama_role'        => 'Operator & Verifikator Berkas',
                'level_user'       => 'user',
                'deskripsi'        => 'Pengelola administrasi berkas KGB/KP (Verifikasi & Laporan)',
                'akses_informasi'  => 1,
                'akses_pengajuan'  => 1,
                'akses_tracking'   => 1,
                'akses_arsip'      => 1,
                'akses_riwayat'    => 1,
                'akses_dashboard'  => 1,
                'akses_verifikasi' => 1,
                'akses_persetujuan'=> 0,
                'akses_evaluasi'   => 1,
                'akses_laporan'    => 1,
                'akses_audit_log'  => 0,
                'akses_hak_akses'  => 0,
                'updated_by'       => 'admin',
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
            [
                'nama_role'        => 'Pegawai ASN Pemohon',
                'level_user'       => 'asn',
                'deskripsi'        => 'Akses publik untuk informasi syarat SOP, pendaftaran usulan online, pelacakan status tiket, dan unduh SK resmi.',
                'akses_informasi'  => 1,
                'akses_pengajuan'  => 1,
                'akses_tracking'   => 1,
                'akses_arsip'      => 1,
                'akses_riwayat'    => 0,
                'akses_dashboard'  => 0,
                'akses_verifikasi' => 0,
                'akses_persetujuan'=> 0,
                'akses_evaluasi'   => 0,
                'akses_laporan'    => 0,
                'akses_audit_log'  => 0,
                'akses_hak_akses'  => 0,
                'updated_by'       => 'admin',
                'updated_at'       => date('Y-m-d H:i:s'),
            ],
        ];

        foreach ($levels as $lvl) {
            $existing = $db->table('sipakar_hak_akses')->where('level_user', $lvl['level_user'])->countAllResults();
            if ($existing === 0) {
                $db->table('sipakar_hak_akses')->insert($lvl);
            }
        }
    }
}
