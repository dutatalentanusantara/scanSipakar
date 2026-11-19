<?php

namespace App\Controllers;

class Main extends BasePortalController
{
    public function index()
    {
        if (! $this->securityAudit->checkRateLimit('main_access', 500, 3600, $this->request)) {
            return $this->response->setStatusCode(429)->setBody('Terlalu banyak permintaan. Silakan tunggu sebentar.');
        }

        if ($this->securityAudit->isSuspiciousBot($this->request)) {
            $this->securityAudit->logEvent('MAIN_SUSPICIOUS_BOT', 'Blocked scanner user agent', $this->request);

            return $this->response->setStatusCode(403)->setBody('Access denied');
        }

        try {
            (new \App\Services\VisitorService())->recordVisit((string) $this->request->getIPAddress());
        } catch (\Throwable) {
        }

        $data = [
            'title'       => title(),
            'description' => description(),
            'keywords'    => keywords(),
        ];

        return $this->renderPortal('content', $data);
    }
}
