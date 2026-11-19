<?php

namespace App\Models;

class PlaylistModel extends BaseAppModel
{
    protected $table          = 'playlist';
    protected $primaryKey     = 'id_playlist';
    protected $useAutoIncrement = true;
    protected $allowedFields  = [
        'jdl_playlist',
        'username',
        'playlist_seo',
        'gbr_playlist',
        'aktif',
    ];
}

