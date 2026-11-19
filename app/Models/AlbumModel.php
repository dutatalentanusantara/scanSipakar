<?php

namespace App\Models;

class AlbumModel extends BaseAppModel
{
    protected $table          = 'album';
    protected $primaryKey     = 'id_album';
    protected $useAutoIncrement = true;
    protected $allowedFields  = [
        'jdl_album',
        'album_seo',
        'keterangan',
        'gbr_album',
        'aktif',
        'hits_album',
        'tgl_posting',
        'jam',
        'hari',
        'username',
    ];
}

