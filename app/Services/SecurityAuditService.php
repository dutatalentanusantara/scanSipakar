<?php

namespace App\Services;

use CodeIgniter\HTTP\IncomingRequest;
use Config\Services;

/**
 * Shared security guardrails: rate limiting, structured security audit logging,
 * SEO slug/ID validation, and suspicious automated scanner detection.
 */
class SecurityAuditService
{
    public function checkRateLimit(string $scope, int $maxRequests, int $windowSeconds, ?IncomingRequest $request = null): bool
    {
        $request ??= Services::request();
        $ip      = $request instanceof IncomingRequest ? $request->getIPAddress() : '127.0.0.1';
        $cache   = Services::cache();
        $key     = 'ratelimit_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $scope) . '_' . md5($ip);
        $now     = time();
        $record  = $cache->get($key);

        if (is_array($record) && isset($record['count'], $record['started_at'])) {
            if (($now - (int) $record['started_at']) <= $windowSeconds) {
                if ((int) $record['count'] >= $maxRequests) {
                    $this->logEvent($scope . '_RATE_LIMIT', "IP {$ip} exceeded {$maxRequests} requests in {$windowSeconds}s", $request);

                    return false;
                }
                $record['count'] = (int) $record['count'] + 1;
            } else {
                $record = ['count' => 1, 'started_at' => $now];
            }
        } else {
            $record = ['count' => 1, 'started_at' => $now];
        }

        $cache->save($key, $record, max(60, $windowSeconds));

        return true;
    }

    /**
     * Anti-abuse view counter guard (one increment per session + per IP every 10 minutes).
     */
    public function shouldIncrementViewCounter(string $entityType, int $entityId, int $cooldownSeconds = 600): bool
    {
        if ($entityId <= 0) {
            return false;
        }
        $session    = session();
        $sessionKey = $entityType . '_viewed_' . $entityId;
        if ($session->get($sessionKey)) {
            return false;
        }

        $request = Services::request();
        $ip      = $request instanceof IncomingRequest ? $request->getIPAddress() : '127.0.0.1';
        $cache   = Services::cache();
        $ipKey   = 'viewcd_' . $entityType . '_' . $entityId . '_' . md5($ip);
        if ($cache->get($ipKey) !== null) {
            return false;
        }

        $session->set($sessionKey, time());
        $cache->save($ipKey, 1, $cooldownSeconds);

        return true;
    }

    public function validateId($rawId): int
    {
        $id = (int) $rawId;

        return ($id > 0 && $id <= 999999999) ? $id : 0;
    }

    public function validateSlug(?string $slug, int $maxLength = 200): ?string
    {
        if ($slug === null || $slug === '') {
            return null;
        }
        $slug = trim($slug);
        if ($slug === '' || strlen($slug) > $maxLength) {
            return null;
        }
        if (! preg_match('/^[a-zA-Z0-9\-_]+$/', $slug)) {
            return null;
        }

        return $slug;
    }

    public function cleanSegment(?string $segment): string
    {
        if ($segment === null || $segment === '') {
            return '';
        }

        return (string) preg_replace('/[^a-zA-Z0-9\-_]/', '', $segment);
    }

    public function isSuspiciousBot(?IncomingRequest $request = null): bool
    {
        $request ??= Services::request();
        $ua = $request instanceof IncomingRequest ? strtolower((string) $request->getUserAgent()->getAgentString()) : '';
        if ($ua === '') {
            return false;
        }

        foreach (['sqlmap', 'nikto', 'nessus', 'w3af', 'acunetix', 'netsparker', 'havij', 'pangolin', 'jsql'] as $sig) {
            if (str_contains($ua, $sig)) {
                return true;
            }
        }

        return false;
    }

    public function logEvent(string $event, string $details = '', ?IncomingRequest $request = null): void
    {
        $request ??= Services::request();
        $payload = [
            'timestamp'  => date('Y-m-d H:i:s'),
            'event'      => $event,
            'ip'         => $request instanceof IncomingRequest ? $request->getIPAddress() : 'cli',
            'user_agent' => $request instanceof IncomingRequest ? (string) $request->getUserAgent()->getAgentString() : 'cli',
            'details'    => $details,
            'uri'        => current_url(),
        ];

        log_message('warning', 'Security Event [' . $event . ']: ' . json_encode($payload));
    }
}

