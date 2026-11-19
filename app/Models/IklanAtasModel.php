<?php

namespace App\Models;

class IklanAtasModel extends BaseAppModel
{
    protected $table          = 'iklanatas';
    protected $primaryKey     = 'id_iklanatas';
    protected $useAutoIncrement = true;
    protected $allowedFields  = ['judul', 'username', 'url', 'source', 'gambar', 'tgl_posting'];
}

