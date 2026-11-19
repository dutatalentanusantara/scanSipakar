<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Idempotent baseline schema & performance index verification for Portal Siantar Barat.
 * Safe against existing populated `siantarbarat` database (never drops or truncates live tables).
 */
class VerifySchemaAndIndexes extends Migration
{
    public function up()
    {
        $desiredIndexes = [
            'berita'            => [
                'idx_berita_status_id'      => ['status', 'id_berita'],
                'idx_berita_kategori_stat'  => ['id_kategori', 'status'],
                'idx_berita_judul_seo'      => ['judul_seo'],
            ],
            'halamanstatis'     => [
                'idx_halaman_judul_seo'     => ['judul_seo'],
            ],
            'kategori'          => [
                'idx_kategori_seo_aktif'    => ['kategori_seo', 'aktif'],
            ],
            'tag'               => [
                'idx_tag_seo'               => ['tag_seo'],
            ],
            'agenda'            => [
                'idx_agenda_tema_seo'       => ['tema_seo'],
            ],
            'album'             => [
                'idx_album_seo_aktif'       => ['album_seo', 'aktif'],
            ],
            'gallery'           => [
                'idx_gallery_album'         => ['id_album'],
            ],
            'playlist'          => [
                'idx_playlist_seo_aktif'    => ['playlist_seo', 'aktif'],
            ],
            'video'             => [
                'idx_video_seo'             => ['video_seo'],
                'idx_video_playlist'        => ['id_playlist'],
            ],
            'menu'              => [
                'idx_menu_parent_pos'       => ['id_parent', 'position', 'aktif', 'urutan'],
            ],
            'statistik'         => [
                'idx_statistik_tgl_ip'      => ['tanggal', 'ip'],
            ],
            'komentar'          => [
                'idx_komentar_berita_aktif' => ['id_berita', 'aktif'],
            ],
            'sipakar_pengajuan' => [
                'idx_sipakar_tiket'         => ['nomor_tiket'],
                'idx_sipakar_nip_status'    => ['nip', 'status'],
            ],
        ];

        $driver = strtolower((string) $this->db->DBDriver);
        if ($driver !== 'mysqli') {
            return;
        }

        foreach ($desiredIndexes as $table => $indexes) {
            if (! $this->db->tableExists($table)) {
                continue;
            }

            $existingIndexes = [];
            foreach ($this->db->query("SHOW INDEX FROM `{$table}`")->getResultArray() as $row) {
                $existingIndexes[(string) ($row['Key_name'] ?? '')] = true;
            }

            $columnMeta = [];
            foreach ($this->db->query("SHOW COLUMNS FROM `{$table}`")->getResultArray() as $col) {
                $columnMeta[(string) ($col['Field'] ?? '')] = strtolower((string) ($col['Type'] ?? ''));
            }

            foreach ($indexes as $indexName => $columns) {
                if (isset($existingIndexes[$indexName])) {
                    continue;
                }

                $colExprs = [];
                $valid    = true;
                foreach ($columns as $col) {
                    if (! isset($columnMeta[$col])) {
                        $valid = false;
                        break;
                    }
                    $type = $columnMeta[$col];
                    if (str_contains($type, 'text') || str_contains($type, 'blob')) {
                        $colExprs[] = "`{$col}`(191)";
                    } elseif (preg_match('/varchar\((\d+)\)/', $type, $m) && (int) $m[1] > 191) {
                        $colExprs[] = "`{$col}`(191)";
                    } else {
                        $colExprs[] = "`{$col}`";
                    }
                }

                if ($valid && $colExprs !== []) {
                    $sql = sprintf(
                        'ALTER TABLE `%s` ADD INDEX `%s` (%s)',
                        $table,
                        $indexName,
                        implode(', ', $colExprs)
                    );
                    $this->db->query($sql);
                }
            }
        }
    }

    public function down()
    {
        // Intentionally non-destructive: preserve live tables and indexes during rollback checks.
    }
}

