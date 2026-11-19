<?php

namespace App\Models;

class TagVideoModel extends BaseAppModel
{
    protected $table          = 'tagvid';
    protected $primaryKey     = 'id_tag';
    protected $useAutoIncrement = true;
    protected $allowedFields  = [
        'nama_tag',
        'username',
        'tag_seo',
        'count',
    ];
}

