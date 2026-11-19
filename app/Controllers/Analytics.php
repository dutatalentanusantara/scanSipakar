<?php

namespace App\Controllers;

/**
 * Legacy analytics route stub safely rendering visitor statistics without missing library crashes.
 */
class Analytics extends BasePortalController
{
    public function index()
    {
        $metrics = (new \App\Services\VisitorService())->getMetrics();

        return $this->response->setJSON([
            'status'  => 'ok',
            'metrics' => $metrics,
        ]);
    }

    public function visitor_stats()
    {
        return $this->response->setJSON((new \App\Services\VisitorService())->getMetrics());
    }
}
