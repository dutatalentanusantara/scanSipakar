<?php

namespace App\Models;

class UsersModulModel extends BaseAppModel
{
    protected $table          = 'users_modul';
    protected $primaryKey     = 'id_umod';
    protected $useAutoIncrement = true;
    protected $allowedFields  = [
        'id_session',
        'id_modul',
    ];

    public function hasAccess(string $idSession, string $moduleLink): bool
    {
        $row = $this->db->table('modul')
            ->select('modul.id_modul')
            ->join('users_modul', 'modul.id_modul = users_modul.id_modul')
            ->where('users_modul.id_session', $idSession)
            ->where('modul.link', $moduleLink)
            ->limit(1)
            ->get()
            ->getRowArray();

        return $row !== null;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getAssignedModules(string $idSession): array
    {
        return $this->db->table('users_modul')
            ->select('users_modul.*, modul.nama_modul, modul.link, modul.publish, modul.status')
            ->join('modul', 'users_modul.id_modul = modul.id_modul')
            ->where('users_modul.id_session', $idSession)
            ->orderBy('users_modul.id_umod', 'DESC')
            ->get()
            ->getResultArray();
    }
}

