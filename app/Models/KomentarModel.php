<?php

namespace App\Models;

class KomentarModel extends BaseAppModel
{
    protected $table          = 'komentar';
    protected $primaryKey     = 'id_komentar';
    protected $useAutoIncrement = true;
    protected $allowedFields  = [
        'id_berita',
        'nama_komentar',
        'url',
        'isi_komentar',
        'tgl',
        'jam_komentar',
        'aktif',
        'email',
    ];
}

