<style>
/* Modern Styling for Detail Halaman Statis */
.halaman-content-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 18px;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
    margin-bottom: 30px;
}

.halaman-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #64748b;
    margin-bottom: 16px;
}

.halaman-breadcrumb a {
    color: #0284c7;
    text-decoration: none;
}

.halaman-breadcrumb a:hover {
    text-decoration: underline;
}

.halaman-title {
    font-size: 26px;
    font-weight: 800;
    color: #1e293b;
    line-height: 1.35;
    margin: 0 0 16px 0;
    padding-bottom: 16px;
    border-bottom: 2px solid #f1f5f9;
}

.halaman-meta {
    display: flex;
    align-items: center;
    gap: 18px;
    color: #94a3b8;
    font-size: 13px;
    margin-bottom: 24px;
}

.halaman-meta span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.halaman-banner {
    width: 100%;
    max-height: 420px;
    object-fit: cover;
    border-radius: 14px;
    margin-bottom: 26px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
}

.halaman-body {
    font-size: 15.5px;
    line-height: 1.85;
    color: #334155;
}

.halaman-body p {
    margin-bottom: 18px;
}

.halaman-body img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    margin: 15px 0;
}
</style>

<div class="wrapper">
    <div class="mainwrap">
        <div class="sectionrow">
            <div class="minidashboard">
                <div class="minidashboard__body" style="display: flex; flex-wrap: wrap; gap: 20px;">
                    
                    <!-- Kolom Kiri: Widget Menu Navigasi & Populer -->
                    <?php echo view(template() . '/widget/berita-popular'); ?>

                    <!-- Kolom Kanan: Isi Halaman Statis -->
                    <div class="news3col__col" style="flex: 2; order: 1; min-width: 300px; width: 100%;">
                        <article class="halaman-content-card">
                            
                            <!-- Breadcrumb -->
                            <div class="halaman-breadcrumb">
                                <a href="<?= base_url(); ?>"><i class="fas fa-home"></i> Beranda</a>
                                <span>/</span>
                                <span>Halaman</span>
                                <span>/</span>
                                <span style="color: #334155; font-weight: 500;"><?= htmlspecialchars($rows['judul'] ?? ''); ?></span>
                            </div>

                            <!-- Judul Halaman -->
                            <h1 class="halaman-title"><?= htmlspecialchars($rows['judul'] ?? ''); ?></h1>

                            <!-- Meta info -->
                            <div class="halaman-meta">
                                <span><i class="far fa-calendar-alt"></i> <?= tgl_indo($rows['tgl_posting'] ?? date('Y-m-d')); ?></span>
                                <span><i class="far fa-user"></i> <?= htmlspecialchars($rows['nama_lengkap'] ?? 'Pemerintah Kecamatan Siantar Barat'); ?></span>
                                <span><i class="far fa-eye"></i> <?= number_format($rows['dibaca'] ?? 0); ?> kali dibaca</span>
                            </div>

                            <div style="margin: -10px 0 22px;">
                                <?php
                                    echo view(template() . '/widget/share_buttons', array(
                                        'share_url' => current_url(),
                                        'share_title' => $rows['judul'] ?? '',
                                        'share_show_label' => true,
                                    ));
                                ?>
                            </div>

                            <!-- Gambar Header Halaman jika ada -->
                            <?php 
                            $gambar = $rows['gambar'] ?? '';
                            $img_path = portal_fcpath('asset/foto_statis/' . $gambar);
                            if (!empty($gambar) && file_exists($img_path)): 
                            ?>
                                <img src="<?= base_url('asset/foto_statis/' . $gambar); ?>" alt="<?= htmlspecialchars($rows['judul'] ?? ''); ?>" class="halaman-banner" onerror="this.style.display='none'">
                            <?php endif; ?>

                            <!-- Konten Halaman Statis -->
                            <div class="halaman-body">
                                <?= $rows['isi_halaman'] ?? ''; ?>
                            </div>

                        </article>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
