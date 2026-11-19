<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class HalamanEntity extends Entity
{
    protected $casts = [
        'id_halaman' => 'integer',
        'dibaca'     => 'integer',
    ];
}

