<?php

namespace App\Models;

class DownloadModel extends BaseAppModel
{
    protected $table          = 'download';
    protected $primaryKey     = 'id_download';
    protected $useAutoIncrement = true;
    protected $allowedFields  = [
        'judul',
        'nama_file',
        'tgl_posting',
        'hits',
    ];

    public function incrementHits(int $idDownload, int $currentHits = 0): bool
    {
        return (bool) $this->db->table('download')
            ->where('id_download', $idDownload)
            ->set('hits', 'LEAST(2147483647, hits + 1)', false)
            ->update();
    }
}

