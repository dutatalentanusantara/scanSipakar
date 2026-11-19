<?php

namespace App\Models;

class IdentitasModel extends BaseAppModel
{
    protected $table          = 'identitas';
    protected $primaryKey     = 'id_identitas';
    protected $useAutoIncrement = true;
    protected $allowedFields  = [
        'nama_website',
        'email',
        'url',
        'facebook',
        'rekening',
        'no_telp',
        'meta_deskripsi',
        'meta_keyword',
        'favicon',
        'maps',
    ];

    /**
     * @return array<string, mixed>
     */
    public function getCurrent(): array
    {
        $row = $this->orderBy('id_identitas', 'DESC')->limit(1)->first();

        return is_array($row) ? $row : [];
    }
}

