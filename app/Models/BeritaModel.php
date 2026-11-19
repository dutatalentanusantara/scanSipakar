<?php

namespace App\Models;

class BeritaModel extends BaseAppModel
{
    protected $table          = 'berita';
    protected $primaryKey     = 'id_berita';
    protected $useAutoIncrement = true;
    protected $allowedFields  = [
        'id_kategori',
        'username',
        'judul',
        'sub_judul',
        'judul_seo',
        'isi_berita',
        'gambar',
        'keterangan_gambar',
        'youtube',
        'hari',
        'tanggal',
        'jam',
        'dibaca',
        'headline',
        'aktif',
        'utama',
        'tag',
        'status',
    ];

    /**
     * @return list<array<string, mixed>>
     */
    public function getPublishedJoined(int $limit = 10, int $offset = 0, ?int $categoryId = null): array
    {
        $builder = $this->db->table('berita')
            ->select('berita.*, users.nama_lengkap, kategori.nama_kategori, kategori.kategori_seo')
            ->join('users', 'berita.username = users.username', 'left')
            ->join('kategori', 'berita.id_kategori = kategori.id_kategori', 'left')
            ->where('berita.status', 'Y');

        if ($categoryId !== null) {
            $builder->where('berita.id_kategori', $categoryId);
        }

        return $builder->orderBy('berita.id_berita', 'DESC')
            ->limit(max(1, $limit), max(0, $offset))
            ->get()
            ->getResultArray();
    }

    public function countPublished(?int $categoryId = null): int
    {
        $builder = $this->db->table('berita')->where('status', 'Y');
        if ($categoryId !== null) {
            $builder->where('id_kategori', $categoryId);
        }

        return (int) $builder->countAllResults();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findBySlug(string $slug): ?array
    {
        return $this->db->table('berita')
            ->select('berita.*, users.nama_lengkap, kategori.nama_kategori, kategori.kategori_seo')
            ->join('users', 'berita.username = users.username', 'left')
            ->join('kategori', 'berita.id_kategori = kategori.id_kategori', 'left')
            ->where('berita.judul_seo', $slug)
            ->orderBy('berita.id_berita', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function findByTagSlug(string $tagSlug, int $limit = 10, int $offset = 0): array
    {
        return $this->db->table('berita')
            ->select('berita.*, users.nama_lengkap, kategori.nama_kategori, kategori.kategori_seo')
            ->join('users', 'berita.username = users.username', 'left')
            ->join('kategori', 'berita.id_kategori = kategori.id_kategori', 'left')
            ->where('berita.status', 'Y')
            ->like('berita.tag', $tagSlug)
            ->orderBy('berita.id_berita', 'DESC')
            ->limit(max(1, $limit), max(0, $offset))
            ->get()
            ->getResultArray();
    }

    public function countByTagSlug(string $tagSlug): int
    {
        return (int) $this->db->table('berita')
            ->where('status', 'Y')
            ->like('tag', $tagSlug)
            ->countAllResults();
    }

    public function incrementViews(int $idBerita, int $currentViews): bool
    {
        $next = min(2147483647, max(0, $currentViews) + 1);

        return (bool) $this->db->table('berita')
            ->where('id_berita', $idBerita)
            ->update(['dibaca' => $next]);
    }
}

