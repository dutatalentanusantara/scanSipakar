<?php
$current_method = service('router')->methodName();
$sess_user      = session()->get('username');
$sess_level     = session()->get('level');
$sess_pegawai   = session()->get('sipakar_pegawai');
?>
<style>
/* ==================== SIPAKAR UNIFIED STYLING ==================== */
.sipakar-hero {
    background: linear-gradient(135deg, #0f2b5c 0%, #1e3a8a 50%, #0284c7 100%);
    border-radius: 20px;
    padding: 30px 28px;
    color: #ffffff;
    box-shadow: 0 12px 35px rgba(15, 43, 92, 0.22);
    margin-bottom: 22px;
    position: relative;
    overflow: hidden;
}
.sipakar-hero::after {
    content: '';
    position: absolute;
    right: -30px;
    bottom: -30px;
    width: 220px;
    height: 220px;
    background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0) 70%);
    border-radius: 50%;
    pointer-events: none;
}
.sipakar-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.18);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.28);
    padding: 5px 14px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: #f8fafc;
    margin-bottom: 12px;
}
.sipakar-hero h1 {
    font-size: 25px;
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 8px 0;
    line-height: 1.3;
}
.sipakar-hero p {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.9);
    margin: 0;
    max-width: 820px;
    line-height: 1.6;
}

/* User Auth Bar */
.sipakar-auth-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px 16px;
    margin-bottom: 14px;
    font-size: 13px;
    flex-wrap: wrap;
    gap: 10px;
}

/* Secondary Navigation Bar */
.sipakar-subnav {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 8px 10px;
    margin-bottom: 24px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.03);
}
.sipakar-nav-item {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 8px 14px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    text-decoration: none !important;
    transition: all 0.2s ease;
}
.sipakar-nav-item:hover {
    background: #f1f5f9;
    color: #0284c7;
}
.sipakar-nav-item.active {
    background: #0284c7;
    color: #ffffff !important;
    box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
}
.sipakar-nav-divider {
    width: 1px;
    height: 28px;
    background: #e2e8f0;
    margin: 4px 4px;
}

/* Card General */
.sipakar-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 26px;
    box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
    margin-bottom: 24px;
}
.sipakar-card-title {
    font-size: 19px;
    font-weight: 800;
    color: #0f2b5c;
    margin: 0 0 18px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Alert Boxes */
.alert-sipakar-success {
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    color: #065f46;
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.alert-sipakar-error {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #991b1b;
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Status Badges */
.badge-status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    line-height: 1;
}
.badge-diajukan { background: #e0f2fe; color: #0369a1; }
.badge-verifikasi { background: #fef3c7; color: #b45309; }
.badge-perbaikan { background: #fee2e2; color: #b91c1c; }
.badge-disetujui { background: #e0e7ff; color: #4338ca; }
.badge-bkpsdm { background: #f3e8ff; color: #7e22ce; }
.badge-selesai { background: #dcfce7; color: #15803d; }
.badge-ditolak { background: #f3f4f6; color: #4b5563; }

@media (max-width: 768px) {
    .sipakar-hero h1 { font-size: 20px; }
    .sipakar-subnav { overflow-x: auto; white-space: nowrap; flex-wrap: nowrap; }
}
</style>

<div class="sipakar-hero">
    <div class="sipakar-badge">
        <i class="fas fa-shield-alt"></i> Layanan Kepegawaian ASN
    </div>
    <h1>SIPAKAR – Pelayanan Administrasi Kenaikan Pangkat & Kenaikan Gaji Berkala</h1>
    <p>
        Pusat informasi dan pelayanan administrasi digital terintegrasi ASN Kecamatan Siantar Barat. Seluruh 9 tahapan proses bisnis, validasi administrasi, pengarsipan, hingga evaluasi mutu terintegrasi dengan proteksi keamanan server.
    </p>
</div>

<!-- Status Autentikasi Pengguna & Role Sesi -->
<div class="sipakar-auth-bar">
    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
        <?php if (!empty($sess_pegawai)): ?>
            <span style="color: #15803d; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
                <i class="fas fa-id-badge text-success"></i> Pegawai ASN: <?= htmlspecialchars($sess_pegawai['nama']); ?>
            </span>
            <span class="badge" style="background: #16a34a; color: #fff; font-size: 11px;">
                NIP: <?= htmlspecialchars($sess_pegawai['nip']); ?>
            </span>
        <?php elseif (!empty($sess_user)): ?>
            <span style="color: #059669; font-weight: 700;">
                <i class="fas fa-user-check"></i> Sesi Aktif: <?= htmlspecialchars($sess_user); ?>
            </span>
            <span class="badge badge-info ml-1" style="font-size: 11px; text-transform: uppercase;">
                Role: <?= htmlspecialchars($sess_level); ?>
            </span>
            <span style="color: #64748b; margin-left: 8px;">(Otorisasi Backend Terpenuhi)</span>
        <?php else: ?>
            <span style="color: #64748b;">
                <i class="fas fa-user-lock"></i> Pengajuan SIPAKAR memerlukan login akun pegawai ASN (Database NIP).
            </span>
        <?php endif; ?>
    </div>

    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
        <?php if (!empty($sess_pegawai)): ?>
            <a href="<?= base_url('sipakar/pengajuan'); ?>" class="btn btn-sm btn-success" style="border-radius: 8px; font-weight: 600; padding: 4px 12px; font-size: 12px;">
                <i class="fas fa-file-invoice mr-1"></i> Formulir Pengajuan
            </a>
            <a href="<?= base_url('sipakar/logout'); ?>" class="btn btn-sm btn-outline-danger" style="border-radius: 8px; font-weight: 600; padding: 4px 12px; font-size: 12px;" onclick="return confirm('Apakah Anda yakin ingin keluar dari akun pegawai SIPAKAR?');">
                <i class="fas fa-sign-out-alt mr-1"></i> Keluar ASN
            </a>
        <?php else: ?>
            <a href="<?= base_url('sipakar/login'); ?>" class="btn btn-sm btn-success" style="border-radius: 8px; font-weight: 600; padding: 4px 14px; font-size: 12px; background: #16a34a; border-color: #16a34a;">
                <i class="fas fa-id-badge mr-1"></i> Login Pegawai (ASN)
            </a>
        <?php endif; ?>

        <?php if (!empty($sess_user)): ?>
            <a href="<?= base_url('administrator/logout'); ?>" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px; font-weight: 600; padding: 4px 12px; font-size: 12px;">
                <i class="fas fa-sign-out-alt mr-1"></i> Keluar Pengelola
            </a>
        <?php else: ?>
            <a href="<?= base_url('login'); ?>" class="btn btn-sm btn-outline-primary" style="border-radius: 8px; font-weight: 600; padding: 4px 12px; font-size: 12px; color: #0284c7; border-color: #0284c7;">
                <i class="fas fa-user-shield mr-1"></i> Pengelola
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Navigasi Menu SIPAKAR (Seluruh 9 Tahapan Proses Bisnis) -->
<nav class="sipakar-subnav">
    <!-- Layanan Publik ASN -->
    <a href="<?= base_url('sipakar'); ?>" class="sipakar-nav-item <?= ($current_method == 'index') ? 'active' : ''; ?>">
        <i class="fas fa-info-circle"></i> Informasi Layanan
    </a>
    <a href="<?= base_url('sipakar/pengajuan'); ?>" class="sipakar-nav-item <?= ($current_method == 'pengajuan') ? 'active' : ''; ?>">
        <i class="fas fa-paper-plane"></i> Pengajuan Online
    </a>
    <a href="<?= base_url('sipakar/tracking'); ?>" class="sipakar-nav-item <?= ($current_method == 'tracking') ? 'active' : ''; ?>">
        <i class="fas fa-search"></i> Tracking Pengajuan
    </a>
    <a href="<?= base_url('sipakar/arsip'); ?>" class="sipakar-nav-item <?= ($current_method == 'arsip') ? 'active' : ''; ?>">
        <i class="fas fa-archive"></i> Arsip Dokumen
    </a>

    <div class="sipakar-nav-divider"></div>

    <!-- Modul Terproteksi (Login Required) -->
    <a href="<?= base_url('sipakar/riwayat'); ?>" class="sipakar-nav-item <?= ($current_method == 'riwayat') ? 'active' : ''; ?>" title="Memerlukan Login Pengelola">
        <i class="fas fa-stream"></i> Riwayat Pelayanan ASN
    </a>
    <a href="<?= base_url('sipakar/dashboard'); ?>" class="sipakar-nav-item <?= in_array($current_method, ['dashboard', 'verifikasi']) ? 'active' : ''; ?>" title="Memerlukan Login Pengelola">
        <i class="fas fa-tachometer-alt"></i> Dashboard Monitoring
    </a>
    <a href="<?= base_url('sipakar/evaluasi'); ?>" class="sipakar-nav-item <?= ($current_method == 'evaluasi') ? 'active' : ''; ?>" title="Memerlukan Login Pengelola">
        <i class="fas fa-chart-line"></i> Evaluasi Kinerja
    </a>
    <a href="<?= base_url('sipakar/laporan'); ?>" class="sipakar-nav-item <?= ($current_method == 'laporan') ? 'active' : ''; ?>" title="Memerlukan Login Pengelola">
        <i class="fas fa-file-invoice"></i> Laporan dan Rekapitulasi
    </a>
    <?php if ($current_method !== 'pengajuan' && !str_contains(uri_string(), 'sipakar/pengajuan') && !empty($sess_level) && $sess_level === 'admin'): ?>
        <a href="<?= base_url('sipakar/audit_log'); ?>" class="sipakar-nav-item <?= ($current_method == 'audit_log') ? 'active' : ''; ?>" title="Khusus Administrator">
            <i class="fas fa-shield-alt"></i> Audit Log
        </a>
        <a href="<?= base_url('sipakar/hak_akses'); ?>" class="sipakar-nav-item <?= ($current_method == 'hak_akses') ? 'active' : ''; ?>" title="Khusus Administrator">
            <i class="fas fa-user-lock"></i> Pengaturan Hak Akses
        </a>
    <?php endif; ?>
</nav>

<?php if (session()->getFlashdata('success_msg')): ?>
    <div class="alert-sipakar-success">
        <i class="fas fa-check-circle fa-lg"></i>
        <span><?= session()->getFlashdata('success_msg'); ?></span>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error_msg')): ?>
    <div class="alert-sipakar-error">
        <i class="fas fa-exclamation-triangle fa-lg"></i>
        <span><?= session()->getFlashdata('error_msg'); ?></span>
    </div>
<?php endif; ?>
