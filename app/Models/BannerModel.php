<?php

namespace App\Models;

class BannerModel extends BaseAppModel
{
    protected $table          = 'banner';
    protected $primaryKey     = 'id_banner';
    protected $useAutoIncrement = true;
    protected $allowedFields  = ['judul', 'url', 'gambar', 'tgl_posting'];
}

