<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class AgendaEntity extends Entity
{
    protected $casts = [
        'id_agenda' => 'integer',
        'dibaca'    => 'integer',
    ];
}

