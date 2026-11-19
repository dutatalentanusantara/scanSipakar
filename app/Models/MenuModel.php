<?php

namespace App\Models;

class MenuModel extends BaseAppModel
{
    protected $table          = 'menu';
    protected $primaryKey     = 'id_menu';
    protected $useAutoIncrement = true;
    protected $allowedFields  = [
        'id_parent',
        'nama_menu',
        'link',
        'aktif',
        'position',
        'urutan',
    ];

    /**
     * @return list<array<string, mixed>>
     */
    public function getBottomTopLevel(): array
    {
        return $this->where('id_parent', 0)
            ->where('position', 'Bottom')
            ->where('aktif', 'Ya')
            ->orderBy('urutan', 'ASC')
            ->findAll();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getTopMenus(): array
    {
        return $this->where('position', 'Top')
            ->orderBy('urutan', 'ASC')
            ->findAll();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getChildren(int $parentId): array
    {
        return $this->where('id_parent', max(0, $parentId))
            ->where('aktif', 'Ya')
            ->orderBy('urutan', 'ASC')
            ->findAll();
    }
}

