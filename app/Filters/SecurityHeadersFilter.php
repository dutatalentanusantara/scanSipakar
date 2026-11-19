<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Global HTTP security headers middleware applying CSP, Permissions-Policy,
 * framing protection, MIME sniffing prevention, and conditional HSTS on production HTTPS.
 */
class SecurityHeadersFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        if ($request instanceof \CodeIgniter\HTTP\CLIRequest || (is_cli() && ENVIRONMENT !== 'testing')) {
            return $response;
        }

        if (function_exists('header_remove')) {
            @header_remove('X-Powered-By');
        }
        $response->removeHeader('X-Powered-By');

        $host = strtolower((string) ($request->getServer('HTTP_HOST') ?? ''));
        $isLocal = ($host === ''
            || str_contains($host, 'localhost')
            || str_contains($host, '.test')
            || str_contains($host, '127.0.0.1'));

        $https = strtolower((string) ($request->getServer('HTTPS') ?? ''));
        $xfp   = strtolower((string) ($request->getServer('HTTP_X_FORWARDED_PROTO') ?? ''));
        $isHttps = ($https !== '' && $https !== 'off') || $xfp === 'https';

        $response->setHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->setHeader('X-Content-Type-Options', 'nosniff');
        $response->setHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->setHeader('X-Permitted-Cross-Domain-Policies', 'none');
        $response->setHeader('Cross-Origin-Opener-Policy', 'same-origin-allow-popups');

        $permissionsPolicy = [
            'accelerometer=()',
            'autoplay=(self)',
            'camera=()',
            'display-capture=()',
            'encrypted-media=()',
            'fullscreen=(self "https://www.youtube.com" "https://www.youtube-nocookie.com" "https://maps.google.com" "https://www.google.com")',
            'geolocation=()',
            'gyroscope=()',
            'magnetometer=()',
            'microphone=()',
            'midi=()',
            'payment=()',
            'picture-in-picture=(self)',
            'usb=()',
        ];
        $response->setHeader('Permissions-Policy', implode(', ', $permissionsPolicy));

        $cspDirectives = [
            "default-src 'self'",
            "base-uri 'self'",
            "form-action 'self' https://api.whatsapp.com https://wa.me",
            "frame-ancestors 'self'",
            "object-src 'none'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.googletagmanager.com https://www.google-analytics.com https://widget.kominfo.go.id https://cdn.jsdelivr.net https://code.highcharts.com https://code.jquery.com https://ajax.googleapis.com https://connect.facebook.net https://cdnjs.cloudflare.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://maxcdn.bootstrapcdn.com https://code.ionicframework.com https://cdn.jsdelivr.net https://widget.kominfo.go.id https://cdnjs.cloudflare.com",
            "font-src 'self' data: https://fonts.gstatic.com https://maxcdn.bootstrapcdn.com https://code.ionicframework.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com",
            "img-src 'self' data: blob: https:",
            "frame-src 'self' https://www.youtube.com https://www.youtube-nocookie.com https://maps.google.com https://www.google.com https://widget.kominfo.go.id https://cctv.pematangsiantar.go.id",
            "connect-src 'self' https://www.google-analytics.com https://analytics.google.com https://region1.google-analytics.com https://www.googletagmanager.com https://widget.kominfo.go.id https://api.openweathermap.org https://cdn.jsdelivr.net",
            "media-src 'self' https://www.youtube.com https://www.youtube-nocookie.com",
            "worker-src 'self' blob:",
        ];

        if (! $isLocal && $isHttps) {
            $cspDirectives[] = 'upgrade-insecure-requests';
            $response->setHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        if (! $response->hasHeader('Content-Security-Policy')) {
            $response->setHeader('Content-Security-Policy', implode('; ', $cspDirectives));
        }

        return $response;
    }
}
