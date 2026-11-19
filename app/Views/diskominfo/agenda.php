<style>
/* ==================== MODERN AGENDA STYLES ==================== */
.agenda-hub {
    margin-top: 15px;
}

.agenda-header-card {
    background: linear-gradient(135deg, #1e3a8a 0%, #0284c7 100%);
    border-radius: 18px;
    padding: 28px;
    color: #ffffff;
    box-shadow: 0 10px 30px rgba(2, 132, 199, 0.22);
    margin-bottom: 25px;
}

.agenda-header-title {
    font-size: 22px;
    font-weight: 800;
    margin: 0 0 6px 0;
    color: #ffffff;
}

.agenda-header-desc {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.85);
    margin: 0;
}

.agenda-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 22px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    gap: 22px;
    align-items: center;
}

.agenda-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    border-color: #cbd5e1;
}

.agenda-thumb {
    width: 240px;
    height: 165px;
    border-radius: 12px;
    overflow: hidden;
    flex-shrink: 0;
    background: #f1f5f9;
}

.agenda-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.agenda-card:hover .agenda-thumb img {
    transform: scale(1.06);
}

.agenda-content {
    flex: 1;
    min-width: 0;
}

.agenda-badge-date {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #eff6ff;
    color: #1d4ed8;
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 10px;
}

.agenda-title {
    margin: 0 0 10px 0;
    font-size: 18px;
    font-weight: 700;
    line-height: 1.4;
}

.agenda-title a {
    color: #0f172a;
    text-decoration: none;
    transition: color 0.25s ease;
}

.agenda-title a:hover {
    color: #0284c7;
}

.agenda-meta {
    display: flex;
    gap: 16px;
    align-items: center;
    flex-wrap: wrap;
    font-size: 13px;
    color: #64748b;
    margin-bottom: 12px;
}

.agenda-meta span {
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.agenda-excerpt {
    font-size: 14px;
    color: #475569;
    line-height: 1.6;
    margin: 0 0 14px 0;
}

.agenda-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #0284c7;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.agenda-link:hover {
    color: #0369a1;
    transform: translateX(4px);
    text-decoration: none;
}

@media (max-width: 768px) {
    .agenda-card {
        flex-direction: column;
        align-items: stretch;
    }
    .agenda-thumb {
        width: 100%;
        height: 190px;
    }
}
</style>

<div class="wrapper">
    <div class="mainwrap">
        <div class="sectionrow">
            <div class="minidashboard">
                <div class="minidashboard__body" style="display: flex; flex-wrap: wrap">
                    
                    <!-- Kolom Kiri: Widget Navigasi & Statistik -->
                    <?php echo view(template() . '/widget/berita-popular'); ?>
                    
                    <!-- Kolom Kanan: Daftar Agenda Kegiatan -->
                    <div class="news3col__col" style="flex: 2; order: 1; width: 100%">
                        <div class="leader">
                            <div class="leader__label">
                                <div class="xlabel"><span>Agenda Kegiatan</span></div>
                            </div>
                            
                            <div class="agenda-hub">
                                <div class="agenda-header-card">
                                    <h3 class="agenda-header-title">
                                        <i class="fas fa-calendar-alt mr-2"></i> Jadwal & Agenda Kecamatan Siantar Barat
                                    </h3>
                                    <p class="agenda-header-desc">
                                        Informasi jadwal rapat koordinasi, program pelayanan keliling, sosialisasi kemasyarakatan, dan kegiatan penting di wilayah Kecamatan Siantar Barat.
                                    </p>
                                </div>

                                <div class="agenda-list">
                                    <?php
                                    if (!empty($agenda)):
                                        foreach ($agenda as $r):
                                            $tgl_posting = tgl_indo($r['tgl_posting']);
                                            $tgl_mulai   = tgl_indo($r['tgl_mulai']);
                                            $tgl_selesai = tgl_indo($r['tgl_selesai']);
                                            $baca        = (int)($r['dibaca'] ?? 0);
                                            $judul_asli  = $r['tema'];
                                            $link_detail = base_url('agenda/detail/' . $r['tema_seo']);

                                            $isi_agenda = strip_tags($r['isi_agenda']);
                                            $isi = strlen($isi_agenda) > 170 ? substr($isi_agenda, 0, 170) . '...' : $isi_agenda;

                                            $gbr = $r['gambar'] ?? '';
                                            if (!empty($gbr) && file_exists(portal_fcpath('asset/foto_agenda/' . $gbr))) {
                                                $foto = base_url('asset/foto_agenda/' . $gbr);
                                            } else {
                                                $foto = base_url('asset/foto_agenda/no-image.jpg');
                                            }

                                            $author = !empty($r['nama_lengkap']) ? $r['nama_lengkap'] : 'Admin';
                                            $tempat = !empty($r['tempat']) ? $r['tempat'] : 'Kantor Camat Siantar Barat';
                                            $jam    = !empty($r['jam']) ? $r['jam'] : '09:00 WIB';
                                    ?>
                                        <article class="agenda-card">
                                            <div class="agenda-thumb">
                                                <a href="<?= $link_detail; ?>">
                                                    <img src="<?= $foto; ?>" alt="<?= htmlspecialchars($judul_asli); ?>" loading="lazy" onerror="this.src='<?= base_url('asset/foto_agenda/no-image.jpg'); ?>'">
                                                </a>
                                            </div>

                                            <div class="agenda-content">
                                                <div class="agenda-badge-date">
                                                    <i class="fas fa-calendar-check text-primary"></i> <?= $tgl_mulai; ?><?= ($tgl_selesai != $tgl_mulai) ? ' s/d ' . $tgl_selesai : ''; ?>
                                                </div>

                                                <h3 class="agenda-title">
                                                    <a href="<?= $link_detail; ?>"><?= htmlspecialchars($judul_asli); ?></a>
                                                </h3>

                                                <div class="agenda-meta">
                                                    <span><i class="fas fa-map-marker-alt text-danger"></i> <?= htmlspecialchars($tempat); ?></span>
                                                    <span><i class="far fa-clock text-info"></i> <?= htmlspecialchars($jam); ?></span>
                                                    <span><i class="far fa-user text-secondary"></i> <?= htmlspecialchars($r['pengirim'] ?? $author); ?></span>
                                                </div>

                                                <p class="agenda-excerpt">
                                                    <?= htmlspecialchars($isi); ?>
                                                </p>

                                                <a href="<?= $link_detail; ?>" class="agenda-link">
                                                    Lihat Detail Agenda <i class="fas fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </article>
                                    <?php
                                        endforeach;
                                    else:
                                    ?>
                                        <div class="alert alert-info text-center" style="border-radius: 12px; padding: 40px;">
                                            <i class="fas fa-calendar-times" style="font-size: 36px; margin-bottom: 15px; display: block;"></i>
                                            Belum ada agenda kegiatan yang dijadwalkan saat ini.
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div style="margin-top: 25px; text-align: center;">
                                    <?= pagination_links(); ?>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
