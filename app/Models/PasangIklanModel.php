<?php

namespace App\Models;

class PasangIklanModel extends BaseAppModel
{
    protected $table          = 'pasangiklan';
    protected $primaryKey     = 'id_pasangiklan';
    protected $useAutoIncrement = true;
    protected $allowedFields  = ['judul', 'username', 'url', 'source', 'gambar', 'tgl_posting'];
}

