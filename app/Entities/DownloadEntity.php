<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class DownloadEntity extends Entity
{
    protected $casts = [
        'id_download' => 'integer',
        'hits'        => 'integer',
    ];
}

