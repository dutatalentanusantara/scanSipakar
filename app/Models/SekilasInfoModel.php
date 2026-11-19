<?php

namespace App\Models;

class SekilasInfoModel extends BaseAppModel
{
    protected $table          = 'sekilasinfo';
    protected $primaryKey     = 'id_sekilas';
    protected $useAutoIncrement = true;
    protected $allowedFields  = ['info', 'tgl_posting', 'gambar', 'aktif'];
}

