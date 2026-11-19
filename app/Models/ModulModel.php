<?php

namespace App\Models;

class ModulModel extends BaseAppModel
{
    protected $table          = 'modul';
    protected $primaryKey     = 'id_modul';
    protected $useAutoIncrement = true;
    protected $allowedFields  = [
        'nama_modul',
        'username',
        'link',
        'static_content',
        'gambar',
        'publish',
        'status',
        'aktif',
        'urutan',
        'link_seo',
    ];
}

