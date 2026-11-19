<?php

namespace App\Models;

class GalleryModel extends BaseAppModel
{
    protected $table          = 'gallery';
    protected $primaryKey     = 'id_gallery';
    protected $useAutoIncrement = true;
    protected $allowedFields  = [
        'id_album',
        'username',
        'jdl_gallery',
        'gallery_seo',
        'keterangan',
        'gbr_gallery',
    ];
}

