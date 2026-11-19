<?php

namespace App\Models;

class LogoModel extends BaseAppModel
{
    protected $table          = 'logo';
    protected $primaryKey     = 'id_logo';
    protected $useAutoIncrement = true;
    protected $allowedFields  = ['gambar'];
}

