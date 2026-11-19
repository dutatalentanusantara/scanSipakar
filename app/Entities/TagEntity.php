<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class TagEntity extends Entity
{
    protected $casts = [
        'id_tag' => 'integer',
        'count'  => 'integer',
    ];
}

