<?php

namespace App\Controllers;

use App\Models\AgendaModel;

class Agenda extends BasePortalController
{
    public function index($segment3 = null, $segment4 = null)
    {
        $perPage = 15;
        $jumlah  = $this->portalModel->db()->table('agenda')->countAllResults();
        $dariRaw = $segment4 !== null ? $segment4 : $segment3;
        $dari    = $this->validatePaginationOffset($dariRaw, 10000);

        if ($jumlah > 0 && $dari >= $jumlah) {
            return redirect()->to(base_url('agenda'));
        }

        $rows = $this->portalModel->viewJoin('agenda', 'users', 'username', 'id_agenda', 'DESC', $dari, $perPage);
        $seg3 = $this->securityAudit->cleanSegment((string) $segment3);
        $base = base_url('agenda/index' . ($seg3 !== '' && ! is_numeric($seg3) ? '/' . $seg3 : ''));

        $data = [
            'title'           => 'Agenda',
            'description'     => description(),
            'keywords'        => keywords(),
            'agenda'          => $rows,
            'pagination_html' => portal_pagination($base, $jumlah, $perPage, $dari),
        ];

        return $this->renderPortal('agenda', $data);
    }

    public function detail(?string $slug = null)
    {
        if (! $this->securityAudit->checkRateLimit('agenda_view', 100, 3600, $this->request)) {
            return $this->response->setStatusCode(429)->setBody('Terlalu banyak permintaan. Silakan tunggu sebentar.');
        }

        $temaSeo = $this->securityAudit->validateSlug($slug);
        if ($temaSeo === null) {
            return redirect()->to(base_url('main'));
        }

        $agendaModel = new AgendaModel();
        $row         = $agendaModel->findBySlug($temaSeo);
        if ($row === null || empty($row['id_agenda']) || empty($row['tema'])) {
            return redirect()->to(base_url('main'));
        }

        $idAgenda = $this->securityAudit->validateId($row['id_agenda']);
        if ($idAgenda > 0 && $this->securityAudit->shouldIncrementViewCounter('agenda', $idAgenda)) {
            $nextViews = min(2147483647, max(0, (int) ($row['dibaca'] ?? 0)) + 1);
            $agendaModel->update($idAgenda, ['dibaca' => $nextViews]);
        }

        $excerpt = cetak_meta((string) ($row['isi_agenda'] ?? ''), 0, 200);
        $data    = [
            'title'       => cetak((string) $row['tema']),
            'description' => $excerpt !== '' ? $excerpt : description(),
            'keywords'    => cetak(str_replace(' ', ', ', (string) $row['tema'])),
            'rows'        => $row,
        ];

        return $this->renderPortal('detailagenda', $data);
    }
}
