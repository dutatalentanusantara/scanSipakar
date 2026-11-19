<?php

namespace App\Models;

class HalamanModel extends BaseAppModel
{
    protected $table          = 'halamanstatis';
    protected $primaryKey     = 'id_halaman';
    protected $useAutoIncrement = true;
    protected $allowedFields  = [
        'judul',
        'judul_seo',
        'isi_halaman',
        'gambar',
        'tgl_posting',
        'username',
        'dibaca',
        'jam',
        'hari',
    ];

    /**
     * @return array<string, mixed>|null
     */
    public function findBySlug(string $slug): ?array
    {
        return $this->db->table('halamanstatis')
            ->select('halamanstatis.*, users.nama_lengkap')
            ->join('users', 'halamanstatis.username = users.username', 'left')
            ->where('halamanstatis.judul_seo', $slug)
            ->orderBy('halamanstatis.id_halaman', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();
    }
}

