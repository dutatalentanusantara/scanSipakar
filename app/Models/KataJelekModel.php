<?php

namespace App\Models;

class KataJelekModel extends BaseAppModel
{
    protected $table          = 'katajelek';
    protected $primaryKey     = 'id_jelek';
    protected $useAutoIncrement = true;
    protected $allowedFields  = ['kata', 'username', 'ganti'];
}

