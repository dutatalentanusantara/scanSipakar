<?php

namespace App\Models;

class KategoriModel extends BaseAppModel
{
    protected $table          = 'kategori';
    protected $primaryKey     = 'id_kategori';
    protected $useAutoIncrement = true;
    protected $allowedFields  = [
        'nama_kategori',
        'username',
        'kategori_seo',
        'aktif',
        'sidebar',
        'gambar_utama',
    ];

    /**
     * @return list<array<string, mixed>>
     */
    public function getPopularCategories(int $limit = 10): array
    {
        $limit = max(1, min(100, $limit));
        $sql   = "SELECT c.*, COALESCE(b.jum_dibaca, 0) AS jum_dibaca
                  FROM kategori AS c
                  LEFT JOIN (
                      SELECT id_kategori, SUM(dibaca) AS jum_dibaca
                      FROM berita
                      GROUP BY id_kategori
                  ) AS b ON c.id_kategori = b.id_kategori
                  WHERE c.aktif = 'Y'
                  ORDER BY b.jum_dibaca DESC
                  LIMIT ?";

        return $this->db->query($sql, [$limit])->getResultArray();
    }
}

