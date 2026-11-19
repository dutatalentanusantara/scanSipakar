<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class KategoriEntity extends Entity
{
    protected $casts = [
        'id_kategori' => 'integer',
    ];
}

