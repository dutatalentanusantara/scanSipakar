<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class BeritaEntity extends Entity
{
    protected $datamap = [];

    protected $dates = [];

    protected $casts = [
        'id_berita'   => 'integer',
        'id_kategori' => 'integer',
        'dibaca'      => 'integer',
    ];

    public function isPublished(): bool
    {
        return ($this->attributes['status'] ?? 'N') === 'Y';
    }

    public function getExcerpt(int $limit = 160): string
    {
        $raw = trim((string) preg_replace('/\s+/', ' ', strip_tags((string) ($this->attributes['isi_berita'] ?? ''))));
        if ($raw === '' || mb_strlen($raw) <= $limit) {
            return $raw;
        }
        $cut = mb_substr($raw, 0, $limit);
        $sp  = mb_strrpos($cut, ' ');

        return ($sp !== false ? mb_substr($cut, 0, $sp) : $cut) . '...';
    }
}

