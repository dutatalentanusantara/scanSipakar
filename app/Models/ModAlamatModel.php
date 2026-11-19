<?php

namespace App\Models;

class ModAlamatModel extends BaseAppModel
{
    protected $table          = 'mod_alamat';
    protected $primaryKey     = 'id_alamat';
    protected $useAutoIncrement = true;
    protected $allowedFields  = ['alamat'];
}

