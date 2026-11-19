<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class AlbumEntity extends Entity
{
    protected $casts = [
        'id_album'   => 'integer',
        'hits_album' => 'integer',
    ];
}

