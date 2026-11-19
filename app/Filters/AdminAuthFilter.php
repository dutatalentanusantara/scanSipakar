<?php

namespace App\Filters;

use App\Models\UsersModulModel;
use App\Services\AuthService;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Middleware verifying administrator/operator session and optional module RBAC access.
 */
class AdminAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $user = (new AuthService())->validateLiveSession();
        if ($user === null) {
            return redirect()->to(base_url('administrator'));
        }

        $level     = (string) ($user['level'] ?? '');
        $idSession = (string) ($user['id_session'] ?? '');

        if (! empty($arguments[0])) {
            $moduleLink = (string) $arguments[0];
            if ($level !== 'admin') {
                $allowed = (new UsersModulModel())->hasAccess($idSession, $moduleLink);
                if (! $allowed) {
                    return redirect()->to(base_url('administrator/home'));
                }
            }
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
