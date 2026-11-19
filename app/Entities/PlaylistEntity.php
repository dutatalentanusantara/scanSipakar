<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class PlaylistEntity extends Entity
{
    protected $casts = [
        'id_playlist' => 'integer',
    ];
}

