<?php

namespace App\Filters;

use App\Services\AuthService;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Middleware enforcing login and SIPAKAR RBAC role permissions on protected endpoints.
 */
class SipakarAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $user    = (new AuthService())->validateLiveSession();
        $level   = (string) ($user['level'] ?? '');

        if ($user === null || ! in_array($level, ['admin', 'user'], true)) {
            $session->setFlashdata(
                'error_msg',
                'Akses Terbatas: Anda wajib login sebagai Operator atau Administrator untuk mengakses menu pengelolaan SIPAKAR.'
            );

            return redirect()->to(base_url('login'));
        }

        if (! empty($arguments[0]) && $arguments[0] === 'admin' && $level !== 'admin') {
            $session->setFlashdata(
                'error_msg',
                "Akses Ditolak: Hak akses Anda ({$level}) tidak memiliki otorisasi untuk membuka fitur ini."
            );

            return redirect()->to(base_url('sipakar/dashboard'));
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
