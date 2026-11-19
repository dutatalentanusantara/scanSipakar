<?php

namespace App\Services;

use App\Models\MenuModel;

/**
 * Nestable JSON menu tree builder and persistence service for CMS navigation management.
 */
class AdminMenuService
{
    public function parseMenuJsonArray($raw): array
    {
        $data = json_decode((string) $raw, true);
        if (! is_array($data)) {
            return [];
        }

        $flattened = [];
        $flatten   = static function (array $items, int $parentId) use (&$flatten, &$flattened): void {
            foreach ($items as $item) {
                if (! is_array($item)) {
                    continue;
                }
                $id = isset($item['id']) ? (int) $item['id'] : 0;
                if ($id > 0) {
                    $flattened[] = [
                        'id'       => $id,
                        'parentID' => $parentId,
                    ];
                }
                if (! empty($item['children']) && is_array($item['children'])) {
                    $flatten($item['children'], $id > 0 ? $id : $parentId);
                }
            }
        };

        $flatten($data, 0);

        return $flattened;
    }

    public function buildAdminMenuHtml(): string
    {
        $items = (new MenuModel())->orderBy('urutan', 'ASC')->findAll();
        $ref   = [];
        $list  = [];

        foreach ($items as $row) {
            $id    = (int) $row['id_menu'];
            $pid   = (int) ($row['id_parent'] ?? 0);
            $node  = [
                'id_menu'   => $id,
                'nama_menu' => (string) ($row['nama_menu'] ?? ''),
                'link'      => (string) ($row['link'] ?? ''),
                'aktif'     => (string) ($row['aktif'] ?? 'Ya'),
                'position'  => (string) ($row['position'] ?? 'Bottom'),
                'child'     => [],
            ];
            $ref[$id] = &$node;
            if ($pid === 0) {
                $list[$id] = &$node;
            } else {
                $ref[$pid]['child'][$id] = &$node;
            }
            unset($node);
        }

        return $this->renderItems($list);
    }

    /**
     * @param array<int, array<string, mixed>> $items
     */
    private function renderItems(array $items, string $class = 'dd-list'): string
    {
        $html = '<ol class="' . $class . '" id="menu-id">';
        foreach ($items as $value) {
            $id      = (int) $value['id_menu'];
            $label   = esc((string) $value['nama_menu']);
            $link    = esc((string) $value['link']);
            $pos     = esc((string) $value['position']);
            $posUrl  = base_url('administrator/posisi_menuwebsite/' . $id);
            $html   .= '<li class="dd-item dd3-item" data-id="' . $id . '">'
                . '<div class="dd-handle dd3-handle"></div>'
                . '<div class="dd3-content"><span id="label_show' . $id . '">' . $label . '</span>'
                . '<span class="span-right">/<span id="link_show' . $id . '">' . $link . '</span> &nbsp;&nbsp; '
                . '<a href="' . $posUrl . '"><span title="' . $pos . '" style="font-size:10px; padding:0 5px" class="btn btn-xs btn-default">' . $pos . '</span></a> &nbsp; '
                . '<a class="edit-button" id="' . $id . '" label="' . $label . '" link="' . $link . '"><i class="fa fa-pencil"></i></a> &nbsp; '
                . '<a class="del-button" id="' . $id . '"><i class="fa fa-trash"></i></a></span></div>';

            if (! empty($value['child'])) {
                $html .= $this->renderItems($value['child'], 'child');
            }
            $html .= '</li>';
        }
        $html .= '</ol>';

        return $html;
    }
}
