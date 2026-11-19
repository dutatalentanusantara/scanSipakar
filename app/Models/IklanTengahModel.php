<?php

namespace App\Models;

class IklanTengahModel extends BaseAppModel
{
    protected $table          = 'iklantengah';
    protected $primaryKey     = 'id_iklantengah';
    protected $useAutoIncrement = true;
    protected $allowedFields  = ['judul', 'username', 'url', 'source', 'gambar', 'tgl_posting', 'posisi'];
}

