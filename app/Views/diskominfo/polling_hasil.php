<?php
$pertanyaan = portal_model()->findOne('poling', ['aktif' => 'Y', 'status' => 'Pertanyaan']);
$judulPertanyaan = (string) ($pertanyaan['pilihan'] ?? 'Hasil Jajak Pendapat Portal Kecamatan Siantar Barat');
$opsiPolling = is_array($polling ?? null) ? $polling : [];
$totalSuara = max(0, (int) ($rows ?? 0));
$isVotedPost = service('request')->is('post') && service('request')->getPost('pilihan') !== null;
?>
<style>
.polling-header-bar {
    background: linear-gradient(135deg, #1e3a8a 0%, #0284c7 100%);
    border-radius: 18px;
    padding: 28px 24px;
    color: #ffffff;
    box-shadow: 0 8px 24px rgba(2, 132, 199, 0.22);
    margin-bottom: 24px;
}
.polling-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 28px;
    box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
}
.polling-question {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 8px;
}
.polling-meta {
    font-size: 13px;
    color: #64748b;
    margin-bottom: 22px;
}
.polling-result-item {
    margin-bottom: 18px;
}
.polling-result-top {
    display: flex;
    justify-content: space-between;
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 6px;
}
.polling-bar-track {
    width: 100%;
    height: 12px;
    background: #e2e8f0;
    border-radius: 999px;
    overflow: hidden;
}
.polling-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #0284c7, #2563eb);
    border-radius: 999px;
    transition: width 0.4s ease;
}
</style>

<div class="wrapper" id="features">
    <div class="mainwrap">
        <div class="sectionrow">
            <?php echo view(template() . '/widget/berita-popular'); ?>

            <div class="featsection">
                <div class="polling-header-bar">
                    <h1 style="font-size: 22px; font-weight: 800; color: #fff; margin: 0 0 6px 0;">
                        <i class="fas fa-chart-pie mr-2"></i> Hasil Jajak Pendapat
                    </h1>
                    <p style="font-size: 14px; color: rgba(255,255,255,0.88); margin: 0;">
                        Rekapitulasi suara partisipasi masyarakat Portal Kecamatan Siantar Barat.
                    </p>
                </div>

                <div class="polling-card">
                    <?php if ($isVotedPost): ?>
                        <div class="alert alert-success" style="border-radius: 10px;">
                            <i class="fas fa-check-circle mr-1"></i> Terima kasih! Suara Anda telah dicatat.
                        </div>
                    <?php endif; ?>

                    <div class="polling-question"><?= esc($judulPertanyaan); ?></div>
                    <div class="polling-meta">Total partisipasi: <strong><?= number_format($totalSuara); ?></strong> suara</div>

                    <?php if (!empty($opsiPolling)): ?>
                        <?php foreach ($opsiPolling as $opt): ?>
                            <?php
                            $rating = max(0, (int) ($opt['rating'] ?? 0));
                            $persen = $totalSuara > 0 ? round(($rating / $totalSuara) * 100, 1) : 0;
                            ?>
                            <div class="polling-result-item">
                                <div class="polling-result-top">
                                    <span><?= esc((string) ($opt['pilihan'] ?? '')); ?></span>
                                    <span><?= number_format($rating); ?> suara (<?= number_format($persen, 1); ?>%)</span>
                                </div>
                                <div class="polling-bar-track">
                                    <div class="polling-bar-fill" style="width: <?= $persen; ?>%;"></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">Belum ada data jajak pendapat tersedia.</div>
                    <?php endif; ?>

                    <div style="margin-top: 24px;">
                        <a href="<?= base_url('polling'); ?>" style="display:inline-flex;align-items:center;gap:6px;padding:10px 18px;border-radius:10px;background:#0284c7;color:#fff;font-weight:600;text-decoration:none;">
                            <i class="fas fa-arrow-left"></i> Kembali ke Form Polling
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
