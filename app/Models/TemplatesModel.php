<?php

namespace App\Models;

class TemplatesModel extends BaseAppModel
{
    protected $table          = 'templates';
    protected $primaryKey     = 'id_templates';
    protected $useAutoIncrement = true;
    protected $allowedFields  = ['judul', 'username', 'pembuat', 'folder', 'aktif'];
}

