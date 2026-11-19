<?php

namespace App\Models;

class StatistikModel extends BaseAppModel
{
    protected $table          = 'statistik';
    protected $primaryKey     = 'id_statistik';
    protected $useAutoIncrement = true;
    protected $allowedFields  = [
        'ip',
        'tanggal',
        'hits',
        'online',
    ];

    public function recordHit(string $ipAddress): void
    {
        $ip      = filter_var($ipAddress, FILTER_VALIDATE_IP) ? $ipAddress : '0.0.0.0';
        $tanggal = date('Y-m-d');
        $waktu   = time();

        $existing = $this->db->table('statistik')
            ->select('id_statistik, hits')
            ->where('ip', $ip)
            ->where('tanggal', $tanggal)
            ->limit(1)
            ->get()
            ->getRowArray();

        if ($existing === null) {
            $this->db->table('statistik')->insert([
                'ip'      => $ip,
                'tanggal' => $tanggal,
                'hits'    => 1,
                'online'  => $waktu,
            ]);
        } else {
            $this->db->table('statistik')
                ->where('id_statistik', (int) $existing['id_statistik'])
                ->set('hits', 'hits + 1', false)
                ->set('online', $waktu)
                ->update();
        }
    }

    /**
     * Single-query visitor summary metrics.
     *
     * @return array{today: int, yesterday: int, this_month: int, this_year: int, total: int}
     */
    public function getSummary(): array
    {
        $today     = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $thisMonth = date('Y-m-01');
        $thisYear  = date('Y-01-01');

        $sql = 'SELECT
                    SUM(CASE WHEN tanggal = ? THEN 1 ELSE 0 END) AS today,
                    SUM(CASE WHEN tanggal = ? THEN 1 ELSE 0 END) AS yesterday,
                    SUM(CASE WHEN tanggal >= ? THEN 1 ELSE 0 END) AS this_month,
                    SUM(CASE WHEN tanggal >= ? THEN 1 ELSE 0 END) AS this_year,
                    COUNT(*) AS total
                FROM statistik';
        $row = $this->db->query($sql, [$today, $yesterday, $thisMonth, $thisYear])->getRowArray();

        return [
            'today'      => (int) ($row['today'] ?? 0),
            'yesterday'  => (int) ($row['yesterday'] ?? 0),
            'this_month' => (int) ($row['this_month'] ?? 0),
            'this_year'  => (int) ($row['this_year'] ?? 0),
            'total'      => (int) ($row['total'] ?? 0),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getDailyChart(int $limit = 10): array
    {
        return $this->db->table('statistik')
            ->select('COUNT(*) AS jumlah, tanggal')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'DESC')
            ->limit(max(1, min(90, $limit)))
            ->get()
            ->getResultArray();
    }

    public function getTotalHits(): int
    {
        $row = $this->db->table('statistik')
            ->selectSum('hits', 'total_hits')
            ->get()
            ->getRowArray();

        return (int) ($row['total_hits'] ?? 0);
    }

    public function getOnlineCount(int $windowSeconds = 300): int
    {
        $cutoff = time() - max(60, $windowSeconds);

        return (int) $this->db->table('statistik')
            ->where('online >', $cutoff)
            ->countAllResults();
    }
}

