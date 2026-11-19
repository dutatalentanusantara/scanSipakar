<?php

namespace App\Models;

class HubungiModel extends BaseAppModel
{
    protected $table          = 'hubungi';
    protected $primaryKey     = 'id_hubungi';
    protected $useAutoIncrement = true;
    protected $allowedFields  = ['nama', 'email', 'subjek', 'pesan', 'tanggal', 'jam', 'dibaca'];
}

