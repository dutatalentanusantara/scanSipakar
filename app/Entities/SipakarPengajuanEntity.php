<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class SipakarPengajuanEntity extends Entity
{
    protected $casts = [
        'id_pengajuan'    => 'integer',
        'gaji_pokok_lama' => 'integer',
        'gaji_pokok_baru' => 'integer',
    ];
}

