<?php

namespace App\Services;

use CodeIgniter\Cache\CacheInterface;
use Config\Services;

/**
 * Centralized application cache manager with tag-style prefix invalidation.
 */
class PortalCacheService
{
    private CacheInterface $cache;

    private const TRACKED_KEYS = [
        'portal_active_template',
        'portal_identitas',
        'portal_background',
        'portal_logo',
        'portal_alamat',
        'portal_top_menu',
        'portal_bottom_menu_tree',
        'portal_popular_categories',
        'portal_visitor_stats',
        'portal_home_headline',
        'portal_home_utama',
        'portal_home_berita_list',
        'portal_sipakar_stats_' . '2026',
    ];

    public function __construct(?CacheInterface $cache = null)
    {
        $this->cache = $cache ?? Services::cache();
    }

    /**
     * Retrieve a cached entry or compute and store it cleanly.
     *
     * @template T
     * @param callable(): T $callback
     * @return T
     */
    public function remember(string $key, int $ttl, callable $callback)
    {
        $safeKey = $this->sanitizeKey($key);
        $hit     = $this->cache->get($safeKey);
        if ($hit !== null) {
            return $hit;
        }

        $value = $callback();
        if ($value !== null) {
            $this->cache->save($safeKey, $value, max(10, $ttl));
        }

        return $value;
    }

    public function forget(string $key): void
    {
        $this->cache->delete($this->sanitizeKey($key));
    }

    public function invalidatePortalCaches(): void
    {
        foreach (self::TRACKED_KEYS as $key) {
            $this->cache->delete($this->sanitizeKey($key));
        }
        $this->cache->delete('portal_sipakar_stats_' . date('Y'));
    }

    private function sanitizeKey(string $key): string
    {
        return preg_replace('/[^a-zA-Z0-9_.\-]/', '_', $key) ?: 'portal_item';
    }
}

