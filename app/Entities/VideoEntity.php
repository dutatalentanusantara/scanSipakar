<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class VideoEntity extends Entity
{
    protected $casts = [
        'id_video'    => 'integer',
        'id_playlist' => 'integer',
        'dilihat'     => 'integer',
    ];
}

