<?php

namespace App\Models;

class TagModel extends BaseAppModel
{
    protected $table          = 'tag';
    protected $primaryKey     = 'id_tag';
    protected $useAutoIncrement = true;
    protected $allowedFields  = [
        'nama_tag',
        'username',
        'tag_seo',
        'count',
    ];
}

