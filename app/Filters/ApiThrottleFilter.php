<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

/**
 * Rate limiter middleware for REST API endpoints (60 requests/minute per IP).
 */
class ApiThrottleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $throttler = Services::throttler();
        $key       = 'api_v1_' . md5($request->getIPAddress());

        if ($throttler->check($key, 60, MINUTE) === false) {
            return Services::response()
                ->setStatusCode(429)
                ->setJSON([
                    'status'  => 'error',
                    'code'    => 429,
                    'message' => 'Too Many Requests. Rate limit exceeded.',
                ]);
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
