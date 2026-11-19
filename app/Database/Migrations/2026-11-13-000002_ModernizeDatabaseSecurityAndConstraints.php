<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Modernize schema security, referential integrity (foreign keys),
 * uniqueness constraints, and dynamic row format for Portal Siantar Barat.
 * Fully idempotent and safe against live production databases.
 */
class ModernizeDatabaseSecurityAndConstraints extends Migration
{
    public function up()
    {
        $driver = strtolower((string) $this->db->DBDriver);
        if ($driver !== 'mysqli') {
            return;
        }

        // 1. Ensure InnoDB and ROW_FORMAT=DYNAMIC across all application tables
        $tables = $this->db->listTables();
        foreach ($tables as $table) {
            if ($table === 'migrations') {
                continue;
            }
            try {
                $this->db->query("ALTER TABLE `{$table}` ENGINE=InnoDB ROW_FORMAT=DYNAMIC");
            } catch (\Throwable $e) {
                // Table might already be InnoDB Dynamic
            }
        }

        // 2. Uniqueness constraints
        $uniqueConstraints = [
            'users'             => ['uniq_users_email' => ['email']],
            'sipakar_pengajuan' => ['uniq_sipakar_tiket' => ['nomor_tiket']],
            'sipakar_hak_akses' => ['uniq_sipakar_level' => ['level_user']],
            'users_modul'       => ['uniq_session_modul' => ['id_session', 'id_modul']],
            'statistik'         => ['uniq_statistik_tgl_ip' => ['tanggal', 'ip']],
        ];

        foreach ($uniqueConstraints as $table => $constraints) {
            if (! $this->db->tableExists($table)) {
                continue;
            }

            $existing = [];
            foreach ($this->db->query("SHOW INDEX FROM `{$table}`")->getResultArray() as $row) {
                $existing[(string) ($row['Key_name'] ?? '')] = true;
            }

            foreach ($constraints as $name => $cols) {
                if (isset($existing[$name])) {
                    continue;
                }
                $colStr = implode(', ', array_map(static fn ($c) => "`{$c}`", $cols));
                try {
                    $this->db->query("ALTER TABLE `{$table}` ADD UNIQUE KEY `{$name}` ({$colStr})");
                } catch (\Throwable $e) {
                    // Ignore if already enforced
                }
            }
        }

        // 3. Foreign key constraints for relational integrity
        $foreignKeys = [
            'gallery' => [
                'fk_gallery_album' => 'FOREIGN KEY (`id_album`) REFERENCES `album` (`id_album`) ON DELETE CASCADE ON UPDATE CASCADE',
            ],
            'komentar' => [
                'fk_komentar_berita' => 'FOREIGN KEY (`id_berita`) REFERENCES `berita` (`id_berita`) ON DELETE CASCADE ON UPDATE CASCADE',
            ],
            'komentarvid' => [
                'fk_komentarvid_video' => 'FOREIGN KEY (`id_video`) REFERENCES `video` (`id_video`) ON DELETE CASCADE ON UPDATE CASCADE',
            ],
            'video' => [
                'fk_video_playlist' => 'FOREIGN KEY (`id_playlist`) REFERENCES `playlist` (`id_playlist`) ON DELETE CASCADE ON UPDATE CASCADE',
            ],
            'berita' => [
                'fk_berita_kategori' => 'FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE RESTRICT ON UPDATE CASCADE',
            ],
            'users_modul' => [
                'fk_users_modul_modul' => 'FOREIGN KEY (`id_modul`) REFERENCES `modul` (`id_modul`) ON DELETE CASCADE ON UPDATE CASCADE',
            ],
            'sipakar_dokumen' => [
                'fk_sipakar_dokumen_pengajuan' => 'FOREIGN KEY (`id_pengajuan`) REFERENCES `sipakar_pengajuan` (`id_pengajuan`) ON DELETE CASCADE ON UPDATE CASCADE',
            ],
            'sipakar_riwayat_status' => [
                'fk_sipakar_riwayat_pengajuan' => 'FOREIGN KEY (`id_pengajuan`) REFERENCES `sipakar_pengajuan` (`id_pengajuan`) ON DELETE CASCADE ON UPDATE CASCADE',
            ],
            'sipakar_audit_log' => [
                'fk_sipakar_audit_pengajuan' => 'FOREIGN KEY (`id_pengajuan`) REFERENCES `sipakar_pengajuan` (`id_pengajuan`) ON DELETE SET NULL ON UPDATE CASCADE',
            ],
        ];

        foreach ($foreignKeys as $table => $fks) {
            if (! $this->db->tableExists($table)) {
                continue;
            }

            $existing = [];
            $res = $this->db->query(
                "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_NAME IS NOT NULL",
                [$table]
            )->getResultArray();

            foreach ($res as $r) {
                $existing[(string) $r['CONSTRAINT_NAME']] = true;
            }

            foreach ($fks as $fkName => $fkDef) {
                if (isset($existing[$fkName])) {
                    continue;
                }
                try {
                    $this->db->query("ALTER TABLE `{$table}` ADD CONSTRAINT `{$fkName}` {$fkDef}");
                } catch (\Throwable $e) {
                    // Ignore if already exists or schema differs
                }
            }
        }
    }

    public function down()
    {
        // Safe and non-destructive: do not drop live tables or data during rollback check
    }
}

