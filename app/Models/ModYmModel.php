<?php

namespace App\Models;

class ModYmModel extends BaseAppModel
{
    protected $table          = 'mod_ym';
    protected $primaryKey     = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields  = ['nama', 'username', 'ym_icon'];
}

