<?php

namespace App\Models;

class AgendaModel extends BaseAppModel
{
    protected $table          = 'agenda';
    protected $primaryKey     = 'id_agenda';
    protected $useAutoIncrement = true;
    protected $allowedFields  = [
        'tema',
        'tema_seo',
        'isi_agenda',
        'tempat',
        'pengirim',
        'gambar',
        'tgl_mulai',
        'tgl_selesai',
        'tgl_posting',
        'jam',
        'dibaca',
        'username',
    ];

    /**
     * @return array<string, mixed>|null
     */
    public function findBySlug(string $slug): ?array
    {
        return $this->db->table('agenda')
            ->select('agenda.*, users.nama_lengkap')
            ->join('users', 'agenda.username = users.username', 'left')
            ->where('agenda.tema_seo', $slug)
            ->orderBy('agenda.id_agenda', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();
    }
}

