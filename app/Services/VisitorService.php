<?php

namespace App\Services;

use App\Models\StatistikModel;

class VisitorService
{
    private StatistikModel $stats;
    private PortalCacheService $cache;

    public function __construct(?StatistikModel $stats = null, ?PortalCacheService $cache = null)
    {
        $this->stats = $stats ?? new StatistikModel();
        $this->cache = $cache ?? new PortalCacheService();
    }

    public function recordVisit(string $ipAddress): void
    {
        $this->stats->recordHit($ipAddress);
    }

    /**
     * @return array{today: int, yesterday: int, this_month: int, this_year: int, total: int, total_hits: int, online: int}
     */
    public function getMetrics(): array
    {
        return $this->cache->remember('portal_visitor_stats', 60, function (): array {
            $summary = $this->stats->getSummary();

            return [
                'today'      => $summary['today'],
                'yesterday'  => $summary['yesterday'],
                'this_month' => $summary['this_month'],
                'this_year'  => $summary['this_year'],
                'total'      => $summary['total'],
                'total_hits' => $this->stats->getTotalHits(),
                'online'     => $this->stats->getOnlineCount(300),
            ];
        });
    }
}

