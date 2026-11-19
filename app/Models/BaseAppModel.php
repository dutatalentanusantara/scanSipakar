<?php

namespace App\Models;

use CodeIgniter\Model;
use InvalidArgumentException;

/**
 * Shared CI4 ORM base model enforcing table allowlists, safe ordering identifiers,
 * and consistent array results across Portal Kecamatan Siantar Barat.
 */
abstract class BaseAppModel extends Model
{
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $useTimestamps    = false;
    protected $protectFields    = true;

    /**
     * Complete allowlist of database tables in siantarbarat schema.
     *
     * @var list<string>
     */
    public const ALLOWED_TABLES = [
        'agenda',
        'album',
        'background',
        'banner',
        'berita',
        'download',
        'gallery',
        'halamanstatis',
        'hubungi',
        'identitas',
        'iklanatas',
        'iklantengah',
        'katajelek',
        'kategori',
        'komentar',
        'komentarvid',
        'logo',
        'menu',
        'mod_alamat',
        'mod_ym',
        'modul',
        'pasangiklan',
        'playlist',
        'poling',
        'sekilasinfo',
        'sipakar_audit_log',
        'sipakar_dokumen',
        'sipakar_hak_akses',
        'sipakar_pengajuan',
        'sipakar_riwayat_status',
        'statistik',
        'tag',
        'tagvid',
        'tbl_comment',
        'templates',
        'users',
        'users_modul',
        'video',
    ];

    public static function validateIdentifier(string $identifier): string
    {
        if (! preg_match('/^[a-zA-Z_][a-zA-Z0-9_.]*$/', $identifier)) {
            throw new InvalidArgumentException('Invalid SQL identifier: ' . $identifier);
        }

        return $identifier;
    }

    public static function validateTableName(string $table): string
    {
        if (! in_array($table, self::ALLOWED_TABLES, true)) {
            throw new InvalidArgumentException('Disallowed table name: ' . $table);
        }

        return $table;
    }

    public static function validateDirection(string $direction): string
    {
        $upper = strtoupper(trim($direction));

        return in_array($upper, ['ASC', 'DESC'], true) ? $upper : 'ASC';
    }
}

