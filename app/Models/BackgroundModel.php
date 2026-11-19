<?php

namespace App\Models;

class BackgroundModel extends BaseAppModel
{
    protected $table          = 'background';
    protected $primaryKey     = 'id_background';
    protected $useAutoIncrement = true;
    protected $allowedFields  = ['gambar'];
}

