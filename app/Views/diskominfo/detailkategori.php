<!-- Halaman Kategori Berita Modern -->
<style>
.kategori-header-bar {
    background: linear-gradient(135deg, #1e3a8a 0%, #0284c7 100%);
    border-radius: 18px;
    padding: 28px 24px;
    color: #ffffff;
    box-shadow: 0 8px 24px rgba(2, 132, 199, 0.22);
    margin-bottom: 25px;
}
.kategori-header-title {
    font-size: 22px;
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 6px 0;
}
.kategori-header-desc {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.88);
    margin: 0;
}
.kategori-nav-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 25px;
    padding: 0;
    list-style: none;
}
.kategori-tab-pill {
    display: inline-block;
    padding: 9px 18px;
    background: #f1f5f9;
    color: #0284c7;
    font-size: 14px;
    font-weight: 600;
    border-radius: 20px;
    text-decoration: none;
    transition: all 0.25s ease;
    border: 1px solid #e2e8f0;
}
.kategori-tab-pill:hover,
.kategori-tab-pill.active {
    background: #0284c7;
    color: #ffffff;
    border-color: #0284c7;
    box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
}
.kategori-empty-box {
    background: #ffffff;
    border: 1px dashed #cbd5e1;
    border-radius: 16px;
    padding: 40px 20px;
    text-align: center;
    color: #64748b;
    margin: 20px 0;
}
</style>

<div class="wrapper" id="features">
    <div class="mainwrap">
        <div class="sectionrow">
            <div class="featsection">
                
                <!-- Header Kategori -->
                <div class="kategori-header-bar">
                    <h1 class="kategori-header-title">
                        <i class="fas fa-newspaper mr-2"></i> Berita Kategori: <?= htmlspecialchars($rows['nama_kategori'] ?? ''); ?>
                    </h1>
                    <p class="kategori-header-desc">
                        Menampilkan informasi, arsip kegiatan, dan kabar berita terkini seputar <?= htmlspecialchars($rows['nama_kategori'] ?? ''); ?> Kecamatan Siantar Barat.
                    </p>
                </div>

                <!-- Tab Navigasi Kategori Cepat -->
                <?php 
                $current_seo = $rows['kategori_seo'] ?? '';
                ?>
                <ul class="kategori-nav-tabs">
                    <li><a href="<?= base_url('kategori/detail/berita-terbaru'); ?>" class="kategori-tab-pill <?= ($current_seo === 'berita-terbaru') ? 'active' : ''; ?>">Berita Terbaru</a></li>
                    <li><a href="<?= base_url('kategori/detail/berita-unggulan'); ?>" class="kategori-tab-pill <?= ($current_seo === 'berita-unggulan') ? 'active' : ''; ?>">Berita Unggulan</a></li>
                    <li><a href="<?= base_url('kategori/detail/pengumuman'); ?>" class="kategori-tab-pill <?= ($current_seo === 'pengumuman') ? 'active' : ''; ?>">Pengumuman</a></li>
                    <li><a href="<?= base_url('berita/indeks_berita'); ?>" class="kategori-tab-pill">Index Berita</a></li>
                </ul>

                <!-- Grid Berita -->
                <div class="news3col">
                    <?php if (!empty($beritakategori)): ?>
                        <div class="news3col__row">
                            <?php foreach ($beritakategori as $r): 
                                $foto_slide = (!empty($r['gambar']) && file_exists(portal_fcpath('asset/foto_berita/' . $r['gambar']))) ? $r['gambar'] : 'no-image.jpg';
                                $detail_url = base_url('berita/detail/' . $r['judul_seo']);
                            ?>
                                <div class="news3col__col">
                                    <a href="<?= $detail_url; ?>" class="news3col__img">
                                        <img src="<?= base_url('asset/foto_berita/' . $foto_slide); ?>" alt="<?= htmlspecialchars($r['judul'] ?? ''); ?>" onerror="this.src='<?= base_url('asset/foto_berita/no-image.jpg'); ?>'">
                                    </a>
                                    <p class="news3col__summary"><?= htmlspecialchars($r['judul'] ?? ''); ?></p>
                                    <a href="<?= $detail_url; ?>" class="linkmore" tabindex="0"><span>Lihat Detail</span></a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="kategori-empty-box">
                            <i class="fas fa-folder-open fa-3x" style="color: #94a3b8; margin-bottom: 12px;"></i>
                            <h4 style="margin: 0 0 6px 0; color: #334155;">Belum Ada Berita</h4>
                            <p style="margin: 0;">Belum ada artikel berita yang dipublikasikan dalam kategori ini saat ini.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Pagination -->
                <div style="margin-top: 25px;">
                    <?= pagination_links(); ?>
                </div>

            </div>
        </div>
    </div>
</div>
