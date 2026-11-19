<?php

namespace App\Controllers;

use App\Models\PolingModel;

class Polling extends BasePortalController
{
    public function index()
    {
        $data = [
            'title'       => 'Hasil Poling',
            'description' => description(),
            'keywords'    => keywords(),
            'rows'        => $this->portalModel->pollingSum(),
            'polling'     => $this->portalModel->viewWhere('poling', ['aktif' => 'Y', 'status' => 'Jawaban']),
        ];

        return $this->renderPortal('polling_lihat', $data);
    }

    public function hasil()
    {
        if (! $this->securityAudit->checkRateLimit('polling_vote', 10, 600, $this->request)) {
            return redirect()->to(base_url('polling'));
        }

        if ($this->request->is('post') && $this->request->getPost('pilihan') !== null) {
            $idPoling = $this->securityAudit->validateId($this->request->getPost('pilihan'));
            if ($idPoling > 0 && ! session()->get('voted_polling')) {
                $row = $this->portalModel->findOne('poling', ['id_poling' => $idPoling, 'aktif' => 'Y', 'status' => 'Jawaban']);
                if ($row !== null) {
                    $this->portalModel->db()->table('poling')
                        ->set('rating', 'rating + 1', false)
                        ->where('id_poling', $idPoling)
                        ->update();
                    session()->set('voted_polling', $idPoling);
                }
            }
        }

        $data = [
            'title'       => 'Hasil Poling',
            'description' => description(),
            'keywords'    => keywords(),
            'rows'        => $this->portalModel->pollingSum(),
            'polling'     => $this->portalModel->viewWhere('poling', ['aktif' => 'Y', 'status' => 'Jawaban']),
        ];

        return $this->renderPortal('polling_hasil', $data);
    }
}
