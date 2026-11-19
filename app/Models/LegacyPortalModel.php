<?php

namespace App\Models;

use CodeIgniter\Database\BaseConnection;
use Config\Database;

/**
 * Parameterized CI4 Query Builder gateway replacing legacy Model_utama, Model_app, and Model_menu.
 * Always returns plain associative arrays/integers (never CI3 result objects) so results are safe for views & cache.
 */
class LegacyPortalModel
{
    protected BaseConnection $db;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?? Database::connect();
    }

    public function db(): BaseConnection
    {
        return $this->db;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function view(string $table): array
    {
        $table = BaseAppModel::validateTableName($table);

        return $this->db->table($table)->get()->getResultArray();
    }

    /**
     * @param array<string, mixed> $where
     * @return list<array<string, mixed>>
     */
    public function viewWhere(string $table, array $where): array
    {
        $table = BaseAppModel::validateTableName($table);

        return $this->db->table($table)->where($where)->get()->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function viewOrdering(string $table, string $order, string $ordering = 'ASC'): array
    {
        $table    = BaseAppModel::validateTableName($table);
        $order    = BaseAppModel::validateIdentifier($order);
        $ordering = BaseAppModel::validateDirection($ordering);

        return $this->db->table($table)->orderBy($order, $ordering)->get()->getResultArray();
    }

    /**
     * @param array<string, mixed> $where
     * @return list<array<string, mixed>>
     */
    public function viewWhereOrdering(string $table, array $where, string $order, string $ordering = 'ASC'): array
    {
        $table    = BaseAppModel::validateTableName($table);
        $order    = BaseAppModel::validateIdentifier($order);
        $ordering = BaseAppModel::validateDirection($ordering);

        return $this->db->table($table)->where($where)->orderBy($order, $ordering)->get()->getResultArray();
    }

    /**
     * Legacy CI3 signature: ($table, $order, $ordering, $offset, $limit).
     *
     * @return list<array<string, mixed>>
     */
    public function viewOrderingLimit(string $table, string $order, string $ordering, int $offset, int $limit): array
    {
        $table    = BaseAppModel::validateTableName($table);
        $order    = BaseAppModel::validateIdentifier($order);
        $ordering = BaseAppModel::validateDirection($ordering);
        $offset   = max(0, $offset);
        $limit    = max(1, $limit);

        return $this->db->table($table)->orderBy($order, $ordering)->limit($limit, $offset)->get()->getResultArray();
    }

    /**
     * Legacy CI3 signature: ($table, $where, $order, $ordering, $offset, $limit).
     *
     * @param array<string, mixed> $where
     * @return list<array<string, mixed>>
     */
    public function viewWhereOrderingLimit(string $table, array $where, string $order, string $ordering, int $offset, int $limit): array
    {
        $table    = BaseAppModel::validateTableName($table);
        $order    = BaseAppModel::validateIdentifier($order);
        $ordering = BaseAppModel::validateDirection($ordering);
        $offset   = max(0, $offset);
        $limit    = max(1, $limit);

        return $this->db->table($table)->where($where)->orderBy($order, $ordering)->limit($limit, $offset)->get()->getResultArray();
    }

    /**
     * @param array<string, mixed> $where
     * @return list<array<string, mixed>>
     */
    public function viewSingle(string $table, array $where, string $order, string $ordering = 'ASC'): array
    {
        $table    = BaseAppModel::validateTableName($table);
        $order    = BaseAppModel::validateIdentifier($order);
        $ordering = BaseAppModel::validateDirection($ordering);

        return $this->db->table($table)->where($where)->orderBy($order, $ordering)->get()->getResultArray();
    }

    /**
     * @param array<string, mixed> $where
     * @return array<string, mixed>|null
     */
    public function findOne(string $table, array $where, ?string $order = null, string $ordering = 'DESC'): ?array
    {
        $table   = BaseAppModel::validateTableName($table);
        $builder = $this->db->table($table)->where($where);
        if ($order !== null && $order !== '') {
            $builder->orderBy(BaseAppModel::validateIdentifier($order), BaseAppModel::validateDirection($ordering));
        }

        return $builder->limit(1)->get()->getRowArray();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function insert(string $table, array $data): bool
    {
        $table = BaseAppModel::validateTableName($table);

        return (bool) $this->db->table($table)->insert($data);
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, mixed> $where
     */
    public function update(string $table, array $data, array $where): bool
    {
        $table = BaseAppModel::validateTableName($table);

        return (bool) $this->db->table($table)->where($where)->update($data);
    }

    /**
     * @param array<string, mixed> $where
     */
    public function delete(string $table, array $where): bool
    {
        $table = BaseAppModel::validateTableName($table);

        return (bool) $this->db->table($table)->where($where)->delete();
    }

    private function joinSelectClause(string $table1, string $table2, ?string $table3 = null): string
    {
        $tables = array_filter([$table2, $table3]);
        if (! in_array('users', $tables, true)) {
            return '*';
        }

        $parts = ["{$table1}.*"];
        foreach ($tables as $tbl) {
            if ($tbl === 'users') {
                $parts[] = 'users.nama_lengkap';
                $parts[] = 'users.foto';
            } else {
                $parts[] = "{$tbl}.*";
            }
        }

        return implode(', ', $parts);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function viewJoin(string $table1, string $table2, string $field, string $order, string $ordering, int $offset, int $limit): array
    {
        $table1   = BaseAppModel::validateTableName($table1);
        $table2   = BaseAppModel::validateTableName($table2);
        $field    = BaseAppModel::validateIdentifier($field);
        $order    = BaseAppModel::validateIdentifier($order);
        $ordering = BaseAppModel::validateDirection($ordering);

        return $this->db->table($table1)
            ->select($this->joinSelectClause($table1, $table2))
            ->join($table2, "{$table1}.{$field} = {$table2}.{$field}")
            ->orderBy($order, $ordering)
            ->limit(max(1, $limit), max(0, $offset))
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function viewJoinAll(string $table1, string $table2, string $field, string $order, string $ordering): array
    {
        $table1   = BaseAppModel::validateTableName($table1);
        $table2   = BaseAppModel::validateTableName($table2);
        $field    = BaseAppModel::validateIdentifier($field);
        $order    = BaseAppModel::validateIdentifier($order);
        $ordering = BaseAppModel::validateDirection($ordering);

        return $this->db->table($table1)
            ->select($this->joinSelectClause($table1, $table2))
            ->join($table2, "{$table1}.{$field} = {$table2}.{$field}")
            ->orderBy($order, $ordering)
            ->get()
            ->getResultArray();
    }

    /**
     * @param array<string, mixed> $where
     * @return list<array<string, mixed>>
     */
    public function viewJoinWhere(string $table1, string $table2, string $field, array $where, string $order, string $ordering): array
    {
        $table1   = BaseAppModel::validateTableName($table1);
        $table2   = BaseAppModel::validateTableName($table2);
        $field    = BaseAppModel::validateIdentifier($field);
        $order    = BaseAppModel::validateIdentifier($order);
        $ordering = BaseAppModel::validateDirection($ordering);

        return $this->db->table($table1)
            ->select($this->joinSelectClause($table1, $table2))
            ->join($table2, "{$table1}.{$field} = {$table2}.{$field}")
            ->where($where)
            ->orderBy($order, $ordering)
            ->get()
            ->getResultArray();
    }

    /**
     * @param array<string, mixed> $where
     * @return list<array<string, mixed>>
     */
    public function viewJoinOne(string $table1, string $table2, string $field, array $where, string $order, string $ordering, int $offset, int $limit): array
    {
        $table1   = BaseAppModel::validateTableName($table1);
        $table2   = BaseAppModel::validateTableName($table2);
        $field    = BaseAppModel::validateIdentifier($field);
        $order    = BaseAppModel::validateIdentifier($order);
        $ordering = BaseAppModel::validateDirection($ordering);

        $qualWhere = $this->qualifyWhere($table1, $where);

        return $this->db->table($table1)
            ->select($this->joinSelectClause($table1, $table2))
            ->join($table2, "{$table1}.{$field} = {$table2}.{$field}", 'left')
            ->where($qualWhere)
            ->orderBy("{$table1}.{$order}", $ordering)
            ->limit(max(1, $limit), max(0, $offset))
            ->get()
            ->getResultArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function viewJoinn(string $table1, string $table2, string $table3, string $field1, string $field2, string $order, string $ordering, int $offset, int $limit): array
    {
        $table1   = BaseAppModel::validateTableName($table1);
        $table2   = BaseAppModel::validateTableName($table2);
        $table3   = BaseAppModel::validateTableName($table3);
        $field1   = BaseAppModel::validateIdentifier($field1);
        $field2   = BaseAppModel::validateIdentifier($field2);
        $order    = BaseAppModel::validateIdentifier($order);
        $ordering = BaseAppModel::validateDirection($ordering);

        return $this->db->table($table1)
            ->select($this->joinSelectClause($table1, $table2, $table3))
            ->join($table2, "{$table1}.{$field1} = {$table2}.{$field1}", 'left')
            ->join($table3, "{$table1}.{$field2} = {$table3}.{$field2}", 'left')
            ->orderBy("{$table1}.{$order}", $ordering)
            ->limit(max(1, $limit), max(0, $offset))
            ->get()
            ->getResultArray();
    }

    /**
     * @param array<string, mixed> $where
     * @return list<array<string, mixed>>
     */
    public function viewJoinTwo(string $table1, string $table2, string $table3, string $field1, string $field2, array $where, string $order, string $ordering, int $offset, int $limit): array
    {
        $table1   = BaseAppModel::validateTableName($table1);
        $table2   = BaseAppModel::validateTableName($table2);
        $table3   = BaseAppModel::validateTableName($table3);
        $field1   = BaseAppModel::validateIdentifier($field1);
        $field2   = BaseAppModel::validateIdentifier($field2);
        $order    = BaseAppModel::validateIdentifier($order);
        $ordering = BaseAppModel::validateDirection($ordering);

        $qualWhere = $this->qualifyWhere($table1, $where);

        return $this->db->table($table1)
            ->select($this->joinSelectClause($table1, $table2, $table3))
            ->join($table2, "{$table1}.{$field1} = {$table2}.{$field1}", 'left')
            ->join($table3, "{$table1}.{$field2} = {$table3}.{$field2}", 'left')
            ->where($qualWhere)
            ->orderBy("{$table1}.{$order}", $ordering)
            ->limit(max(1, $limit), max(0, $offset))
            ->get()
            ->getResultArray();
    }

    /**
     * Qualify unqualified column keys with primary table name to avoid ambiguity in JOINs.
     *
     * @param array<string, mixed> $where
     * @return array<string, mixed>
     */
    private function qualifyWhere(string $primaryTable, array $where): array
    {
        $out = [];
        foreach ($where as $k => $v) {
            if (is_string($k) && ! str_contains($k, '.')) {
                $out["{$primaryTable}.{$k}"] = $v;
            } else {
                $out[$k] = $v;
            }
        }

        return $out;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function cariBerita(string $kata): array
    {
        $kata = mb_substr(trim(strip_tags($kata)), 0, 100);
        if ($kata === '') {
            return [];
        }
        $words = array_values(array_filter(array_slice(explode(' ', $kata), 0, 10), static fn ($w) => $w !== ''));

        $builder = $this->db->table('berita a')
            ->select('a.*, b.nama_lengkap, c.nama_kategori, c.kategori_seo')
            ->join('users b', 'a.username = b.username', 'left')
            ->join('kategori c', 'a.id_kategori = c.id_kategori', 'left')
            ->where('a.status', 'Y');

        if ($words !== []) {
            $builder->groupStart();
            foreach ($words as $idx => $word) {
                if ($idx === 0) {
                    $builder->like('a.judul', $word);
                } else {
                    $builder->orLike('a.judul', $word);
                }
            }
            $builder->groupEnd();
        }

        return $builder->orderBy('a.id_berita', 'DESC')->limit(15)->get()->getResultArray();
    }

    public function pollingSum(): int
    {
        $row = $this->db->table('poling')
            ->selectSum('rating', 'jml_vote')
            ->where('aktif', 'Y')
            ->get()
            ->getRowArray();

        return (int) ($row['jml_vote'] ?? 0);
    }

    public function umenuAkses(string $link, string $idSession): int
    {
        $row = $this->db->table('modul')
            ->select('modul.id_modul')
            ->join('users_modul', 'modul.id_modul = users_modul.id_modul')
            ->where('users_modul.id_session', $idSession)
            ->where('modul.link', $link)
            ->limit(1)
            ->get()
            ->getRowArray();

        return $row ? 1 : 0;
    }

    /**
     * Visitor count grouped by date for the admin dashboard chart (last 10 days).
     *
     * @return list<array<string, mixed>>
     */
    public function grafikKunjungan(): array
    {
        return $this->db->table('statistik')
            ->select('COUNT(*) AS jumlah, tanggal', false)
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();
    }
}

