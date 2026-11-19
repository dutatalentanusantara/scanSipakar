<?php

namespace App\Models;

class PolingModel extends BaseAppModel
{
    protected $table          = 'poling';
    protected $primaryKey     = 'id_poling';
    protected $useAutoIncrement = true;
    protected $allowedFields  = ['pilihan', 'status', 'username', 'rating', 'aktif'];
}

