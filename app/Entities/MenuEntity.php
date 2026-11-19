<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class MenuEntity extends Entity
{
    protected $casts = [
        'id_menu'   => 'integer',
        'id_parent' => 'integer',
        'urutan'    => 'integer',
    ];
}

