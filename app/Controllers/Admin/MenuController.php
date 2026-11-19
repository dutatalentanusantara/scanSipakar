<?php

namespace App\Controllers\Admin;

use App\Services\AdminMenuService;

class MenuController extends BaseAdminController
{
    public function menuwebsite()
    {
        if ($redirect = $this->ensureModule('menuwebsite')) {
            return $redirect;
        }

        $records = $this->portalModel->db()
            ->table('menu')
            ->orderBy('position', 'ASC')
            ->orderBy('urutan', 'ASC')
            ->get()
            ->getResultArray();

        return $this->renderAdmin('administrator/mod_menu/view_menu', [
            'record'   => $records,
            'halaman'  => $this->portalModel->viewOrdering('halamanstatis', 'id_halaman', 'DESC'),
            'kategori' => $this->portalModel->viewOrdering('kategori', 'id_kategori', 'DESC'),
        ]);
    }

    public function save_menuwebsite()
    {
        if ($redirect = $this->ensureModule('menuwebsite')) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Unauthorized']);
        }

        $rawLink  = (string) ($this->request->getPost('link') ?? '');
        $page     = (string) ($this->request->getPost('page') ?? '');
        $kategori = (string) ($this->request->getPost('kategori') ?? '');
        $link     = $rawLink . $page . $kategori;
        $label    = trim((string) ($this->request->getPost('label') ?? ''));
        $menuId   = (int) ($this->request->getPost('id') ?? 0);
        $db       = $this->portalModel->db();

        if ($menuId > 0) {
            $db->table('menu')->where('id_menu', $menuId)->update([
                'nama_menu' => $label,
                'link'      => $link,
            ]);

            $payload = [
                'type'       => 'edit',
                'label'      => esc($label),
                'link'       => esc($link),
                'page'       => esc($page),
                'kategori'   => esc($kategori),
                'id'         => $menuId,
                'csrf_token' => csrf_token(),
                'csrf_hash'  => csrf_hash(),
            ];
        } else {
            $maxRow = $db->table('menu')->selectMax('urutan', 'max_urutan')->get()->getRowArray();
            $urutan = (int) ($maxRow['max_urutan'] ?? 0) + 1;

            $db->table('menu')->insert([
                'id_parent' => 0,
                'nama_menu' => $label,
                'link'      => $link,
                'aktif'     => 'Ya',
                'position'  => 'Bottom',
                'urutan'    => $urutan,
            ]);
            $id         = (int) $db->insertID();
            $escLabel   = esc($label);
            $escLink    = esc($link);
            $escRawLink = esc($rawLink);
            $posUrl     = base_url('administrator/posisi_menuwebsite/' . $id);

            $menuHtml = '<li class="dd-item dd3-item" data-id="' . $id . '" >'
                . '<div class="dd-handle dd3-handle Bottom"></div>'
                . '<div class="dd3-content"><span id="label_show' . $id . '">' . $escLabel . '</span>'
                . '<span class="span-right">/<span id="link_show' . $id . '">' . $escLink . '</span> &nbsp;&nbsp; '
                . '<a href="' . $posUrl . '" style="cursor:pointer"><i class="fa fa-chevron-circle-up text-success"></i></a> &nbsp; '
                . '<a class="edit-button" id="' . $id . '" label="' . $escLabel . '" link="' . $escRawLink . '" ><i class="fa fa-pencil"></i></a> &nbsp; '
                . '<a class="del-button" id="' . $id . '"><i class="fa fa-trash"></i></a></span></div></li>';

            $payload = [
                'type'       => 'add',
                'id'         => $id,
                'menu'       => $menuHtml,
                'csrf_token' => csrf_token(),
                'csrf_hash'  => csrf_hash(),
            ];
        }

        $this->invalidateAll();

        return $this->response->setJSON($payload);
    }

    public function save()
    {
        if ($redirect = $this->ensureModule('menuwebsite')) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Unauthorized']);
        }

        $rows = (new AdminMenuService())->parseMenuJsonArray($this->request->getPost('data'));
        $db   = $this->portalModel->db();

        $db->transStart();
        $i = 0;
        foreach ($rows as $row) {
            $i++;
            $db->table('menu')->where('id_menu', (int) $row['id'])->update([
                'id_parent' => (int) $row['parentID'],
                'urutan'    => $i,
            ]);
        }
        $db->transComplete();
        $this->invalidateAll();

        return $this->response->setJSON([
            'ok'         => true,
            'csrf_token' => csrf_token(),
            'csrf_hash'  => csrf_hash(),
        ]);
    }

    public function posisi_menuwebsite($id = null)
    {
        if ($redirect = $this->ensureModule('menuwebsite')) {
            return $redirect;
        }

        $targetId = $this->resolveId($id ?: $this->request->getPost('id'));
        $existing = $this->portalModel->findOne('menu', ['id_menu' => $targetId]);
        if ($existing !== null) {
            $posisi = ($existing['position'] ?? '') === 'Top' ? 'Bottom' : 'Top';
            $this->portalModel->update('menu', ['position' => $posisi], ['id_menu' => $targetId]);
            $this->invalidateAll();
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'ok'         => true,
                'csrf_token' => csrf_token(),
                'csrf_hash'  => csrf_hash(),
            ]);
        }

        return redirect()->to(base_url('administrator/menuwebsite'));
    }

    public function delete_menuwebsite()
    {
        if ($redirect = $this->ensureModule('menuwebsite')) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Unauthorized']);
        }

        $id = (int) ($this->request->getPost('id') ?? 0);
        if ($id > 0) {
            $this->portalModel->delete('menu', ['id_menu' => $id]);
            $this->portalModel->delete('menu', ['id_parent' => $id]);
            $this->invalidateAll();
        }

        return $this->response->setJSON([
            'ok'         => true,
            'csrf_token' => csrf_token(),
            'csrf_hash'  => csrf_hash(),
        ]);
    }
}
