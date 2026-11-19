<?php
$pertanyaan = portal_model()->findOne('poling', ['aktif' => 'Y', 'status' => 'Pertanyaan']);
$judulPertanyaan = (string) ($pertanyaan['pilihan'] ?? 'Bagaimana penilaian Anda terhadap pelayanan informasi publik Portal Kecamatan Siantar Barat?');
$opsiPolling = is_array($polling ?? null) ? $polling : [];
$totalSuara = max(0, (int) ($rows ?? 0));
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
    margin-bottom: 20px;
    line-height: 1.5;
}
.polling-option {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    margin-bottom: 12px;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: #f8fafc;
}
.polling-option:hover {
    border-color: #0284c7;
    background: #eff6ff;
}
.polling-option input[type="radio"] {
    width: 18px;
    height: 18px;
    accent-color: #0284c7;
    cursor: pointer;
}
.polling-option-label {
    font-size: 15px;
    font-weight: 600;
    color: #1e293b;
    flex: 1;
}
.polling-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 22px;
    align-items: center;
}
.btn-polling-submit {
    background: linear-gradient(135deg, #0284c7, #1d4ed8);
    color: #ffffff;
    border: none;
    border-radius: 10px;
    padding: 11px 24px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
}
.btn-polling-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 11px 18px;
    border-radius: 10px;
    border: 1px solid #cbd5e1;
    color: #334155;
    font-weight: 600;
    text-decoration: none;
    background: #ffffff;
}
.btn-polling-link:hover {
    background: #f1f5f9;
}
</style>

<div class="wrapper" id="features">
    <div class="mainwrap">
        <div class="sectionrow">
            <?php echo view(template() . '/widget/berita-popular'); ?>

            <div class="featsection">
                <div class="polling-header-bar">
                    <h1 style="font-size: 22px; font-weight: 800; color: #fff; margin: 0 0 6px 0;">
                        <i class="fas fa-poll mr-2"></i> Jajak Pendapat Masyarakat
                    </h1>
                    <p style="font-size: 14px; color: rgba(255,255,255,0.88); margin: 0;">
                        Partisipasi Anda membantu peningkatan kualitas pelayanan publik Pemerintah Kecamatan Siantar Barat.
                    </p>
                </div>

                <div class="polling-card">
                    <div class="polling-question"><?= esc($judulPertanyaan); ?></div>

                    <?php if (!empty($opsiPolling)): ?>
                        <form action="<?= base_url('polling/hasil'); ?>" method="POST">
                            <?= csrf_field(); ?>
                            <?php foreach ($opsiPolling as $idx => $opt): ?>
                                <?php $optId = (int) ($opt['id_poling'] ?? 0); ?>
                                <label class="polling-option" for="pilihan_<?= $optId; ?>">
                                    <input type="radio" id="pilihan_<?= $optId; ?>" name="pilihan" value="<?= $optId; ?>" <?= $idx === 0 ? 'required' : ''; ?>>
                                    <span class="polling-option-label"><?= esc((string) ($opt['pilihan'] ?? '')); ?></span>
                                </label>
                            <?php endforeach; ?>

                            <div class="polling-actions">
                                <button type="submit" class="btn-polling-submit">
                                    <i class="fas fa-paper-plane mr-1"></i> Kirim Pilihan
                                </button>
                                <a href="<?= base_url('polling/hasil'); ?>" class="btn-polling-link">
                                    <i class="fas fa-chart-bar"></i> Lihat Hasil Sementara (<?= number_format($totalSuara); ?> suara)
                                </a>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">Belum ada jajak pendapat aktif saat ini.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
