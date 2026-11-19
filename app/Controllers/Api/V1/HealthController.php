<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;
use Config\Database;
use Throwable;

class HealthController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        $dbStatus = 'disconnected';
        $httpCode = 200;

        try {
            $db  = Database::connect();
            $row = $db->query('SELECT 1 AS ok')->getRowArray();
            if (($row['ok'] ?? 0) == 1) {
                $dbStatus = 'connected';
            }
        } catch (Throwable $e) {
            $dbStatus = 'error: ' . $e->getMessage();
            $httpCode = 503;
        }

        return $this->respond([
            'status'    => $httpCode === 200 ? 'ok' : 'degraded',
            'service'   => 'Portal Resmi Kecamatan Siantar Barat API',
            'version'   => 'v1',
            'framework' => 'CodeIgniter ' . \CodeIgniter\CodeIgniter::CI_VERSION,
            'php'       => PHP_VERSION,
            'database'  => $dbStatus,
            'timestamp' => date(DATE_ATOM),
        ], $httpCode);
    }
}
