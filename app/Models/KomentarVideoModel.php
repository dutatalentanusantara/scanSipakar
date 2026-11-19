<?php

namespace App\Models;

class KomentarVideoModel extends BaseAppModel
{
    protected $table          = 'komentarvid';
    protected $primaryKey     = 'id_komentar';
    protected $useAutoIncrement = true;
    protected $allowedFields  = ['id_video', 'nama_komentar', 'url', 'isi_komentar', 'aktif', 'tgl_komentar'];
}

