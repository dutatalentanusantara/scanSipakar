<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class IdentitasEntity extends Entity
{
    protected $casts = [
        'id_identitas' => 'integer',
    ];
}

