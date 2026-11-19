<?php

namespace App\Models;

class TblCommentModel extends BaseAppModel
{
    protected $table          = 'tbl_comment';
    protected $primaryKey     = 'id_komentar';
    protected $useAutoIncrement = true;
    protected $allowedFields  = ['reply', 'nama_lengkap', 'alamat_email', 'isi_pesan', 'tanggal_komentar', 'jam_komentar', 'aktif'];
}

