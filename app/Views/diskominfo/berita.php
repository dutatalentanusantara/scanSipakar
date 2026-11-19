<style>
/* ==================== MODERN BERITA STYLES ==================== */
.berita-hub {
    margin-top: 15px;
}

/* Header & Filter Bar */
.berita-filter-card {
    background: linear-gradient(135deg, #1e3a8a 0%, #0284c7 100%);
    border-radius: 18px;
    padding: 28px;
    color: #ffffff;
    box-shadow: 0 10px 30px rgba(2, 132, 199, 0.22);
    margin-bottom: 25px;
    position: relative;
    overflow: hidden;
}

.berita-filter-title {
    font-size: 22px;
    font-weight: 800;
    margin: 0 0 6px 0;
    color: #ffffff;
}

.berita-filter-desc {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.85);
    margin: 0 0 18px 0;
}

.berita-search-form {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.berita-search-input {
    flex: 1;
    padding: 12px 18px;
    border-radius: 10px;
    border: none;
    font-size: 14px;
    background: #ffffff;
    color: #1e293b;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.berita-search-input:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(255,255,255,0.4);
}

.berita-search-btn {
    background: #0ea5e9;
    color: #ffffff;
    border: none;
    padding: 12px 22px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.25s ease;
}

.berita-search-btn:hover {
    background: #0284c7;
    color: #ffffff;
}

/* Category Pills */
.berita-category-pills {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
}

.cat-pill {
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff !important;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    backdrop-filter: blur(5px);
    transition: all 0.25s ease;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.cat-pill:hover, .cat-pill.active {
    background: #ffffff;
    color: #0369a1 !important;
    text-decoration: none;
    transform: translateY(-1px);
}

/* News List Card */
.berita-card-horizontal {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    gap: 22px;
    align-items: center;
}

.berita-card-horizontal:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    border-color: #cbd5e1;
}

.berita-card-thumb {
    width: 260px;
    height: 175px;
    border-radius: 12px;
    overflow: hidden;
    flex-shrink: 0;
    position: relative;
    background: #f1f5f9;
}

.berita-card-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.berita-card-horizontal:hover .berita-card-thumb img {
    transform: scale(1.06);
}

.berita-card-content {
    flex: 1;
    min-width: 0;
}

.berita-card-category {
    display: inline-block;
    background: #eff6ff;
    color: #1d4ed8;
    padding: 4px 12px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 8px;
}

.berita-card-title {
    margin: 0 0 10px 0;
    font-size: 18px;
    font-weight: 700;
    line-height: 1.4;
}

.berita-card-title a {
    color: #0f172a;
    text-decoration: none;
    transition: color 0.25s ease;
}

.berita-card-title a:hover {
    color: #0284c7;
}

.berita-card-meta {
    display: flex;
    gap: 14px;
    align-items: center;
    flex-wrap: wrap;
    font-size: 12px;
    color: #64748b;
    margin-bottom: 12px;
}

.berita-card-meta span {
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.berita-card-excerpt {
    font-size: 14px;
    color: #475569;
    line-height: 1.6;
    margin: 0 0 14px 0;
}

.berita-card-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #0284c7;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
}

.berita-card-link:hover {
    color: #0369a1;
    transform: translateX(4px);
    text-decoration: none;
}

/* Pagination Modern */
.berita-pagination {
    margin: 30px 0 15px 0;
    display: flex;
    justify-content: center;
    gap: 6px;
}

.berita-pagination a, .berita-pagination strong {
    padding: 9px 16px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    border: 1px solid #e2e8f0;
}

.berita-pagination a {
    background: #ffffff;
    color: #334155;
}

.berita-pagination a:hover {
    background: #0284c7;
    color: #ffffff;
    border-color: #0284c7;
}

.berita-pagination strong {
    background: #0284c7;
    color: #ffffff;
    border-color: #0284c7;
    box-shadow: 0 4px 10px rgba(2, 132, 199, 0.3);
}

/* Empty State */
.berita-empty-state {
    text-align: center;
    padding: 60px 20px;
    background: #ffffff;
    border-radius: 16px;
    border: 1px dashed #cbd5e1;
}

.berita-empty-state i {
    font-size: 48px;
    color: #94a3b8;
    margin-bottom: 15px;
}

.berita-empty-state h4 {
    font-size: 18px;
    color: #334155;
    margin-bottom: 6px;
}

.berita-empty-state p {
    color: #64748b;
    margin: 0;
}

@media (max-width: 768px) {
    .berita-card-horizontal {
        flex-direction: column;
        align-items: stretch;
    }
    .berita-card-thumb {
        width: 100%;
        height: 200px;
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
                    
                    <!-- Kolom Kanan: Daftar Berita & Kabar Terkini -->
                    <div class="news3col__col" style="flex: 2; order: 1; width: 100%">
                        <div class="leader">
                            <div class="leader__label">
                                <div class="xlabel"><span>Kabar & Berita Kecamatan</span></div>
                            </div>
                            
                            <div class="berita-hub">
                                <!-- Banner Header & Form Pencarian -->
                                <div class="berita-filter-card">
                                    <h3 class="berita-filter-title">
                                        <i class="fas fa-newspaper mr-2"></i> Kabar & Informasi Siantar Barat
                                    </h3>
                                    <p class="berita-filter-desc">
                                        Menyajikan rilis berita resmi, liputan kegiatan kemasyarakatan, inovasi pelayanan, dan agenda pembangunan di Kecamatan Siantar Barat.
                                    </p>
                                    
                                    <form action="<?= base_url('berita'); ?>" method="POST" class="berita-search-form">
<?= csrf_field(); ?>
                                        <input type="text" name="kata" class="berita-search-input" placeholder="Ketik kata kunci judul berita atau topik..." value="<?= htmlspecialchars((string) (service('request')->getPost('kata') ?? '')); ?>">
                                        <button type="submit" class="berita-search-btn">
                                            <i class="fas fa-search"></i> Cari
                                        </button>
                                    </form>

                                    <!-- Kategori Filter -->
                                    <div class="berita-category-pills">
                                        <a href="<?= base_url('berita'); ?>" class="cat-pill <?= empty(service('request')->getPost('kata')) ? 'active' : ''; ?>">
                                            <i class="fas fa-th-large mr-1"></i> Semua Berita
                                        </a>
                                        <?php
                                        $categories_query = portal_model()->viewWhereOrdering('kategori', array('aktif' => 'Y'), 'id_kategori', 'ASC');
                                        if (!empty($categories_query)):
                                            foreach ($categories_query as $cat):
                                        ?>
                                            <a href="<?= base_url('kategori/detail/' . $cat['kategori_seo']); ?>" class="cat-pill">
                                                <?= $cat['nama_kategori']; ?>
                                            </a>
                                        <?php 
                                            endforeach;
                                        endif; 
                                        ?>
                                    </div>
                                </div>

                                <!-- List Berita -->
                                <div class="berita-list-container">
                                    <?php
                                    $list_items = !empty($berita) ? $berita : (!empty($agenda) ? $agenda : null);
                                    if (!empty($list_items)):
                                        foreach ($list_items as $r):
                                            $is_berita = isset($r['judul']);
                                            $judul_asli = $is_berita ? ($r['judul'] ?? '') : ($r['tema'] ?? '');
                                            $judull = strlen($judul_asli) > 85 ? substr($judul_asli, 0, 85) . '...' : $judul_asli;
                                            $link_detail = $is_berita ? base_url() . ($r['judul_seo'] ?? '') : base_url() . 'agenda/detail/' . ($r['tema_seo'] ?? '');
                                            
                                            $tgl_posting = tgl_indo($r['tanggal'] ?? ($r['tgl_posting'] ?? date('Y-m-d')));
                                            $jam_posting = isset($r['jam']) ? date('H:i', strtotime($r['jam'])) . ' WIB' : '';
                                            $baca = (int)($r['dibaca'] ?? 0);
                                            
                                            // Excerpt pintar
                                            $isi_raw = strip_tags($r['isi_berita'] ?? ($r['isi_agenda'] ?? ''));
                                            $isi_sub = substr($isi_raw, 0, 180);
                                            $last_sp = strrpos($isi_sub, " ");
                                            $isi = ($last_sp !== false) ? substr($isi_sub, 0, $last_sp) . '...' : $isi_sub . '...';

                                            // Gambar artikel
                                            $folder_img = $is_berita ? 'foto_berita' : 'foto_agenda';
                                            $gbr = $r['gambar'] ?? '';
                                            if (!empty($gbr) && file_exists(portal_fcpath('asset/' . $folder_img . '/' . $gbr))) {
                                                $foto = base_url() . 'asset/' . $folder_img . '/' . $gbr;
                                            } else {
                                                $foto = base_url() . 'asset/foto_berita/no-image.jpg';
                                            }

                                            $author = !empty($r['nama_lengkap']) ? $r['nama_lengkap'] : 'Admin Kecamatan';
                                            $kategori_name = !empty($r['nama_kategori']) ? $r['nama_kategori'] : ($is_berita ? 'Siantar Barat' : 'Agenda');
                                    ?>
                                        <article class="berita-card-horizontal">
                                            <div class="berita-card-thumb">
                                                <a href="<?= $link_detail; ?>">
                                                    <img src="<?= $foto; ?>" alt="<?= htmlspecialchars($judul_asli); ?>" loading="lazy" onerror="this.src='<?= base_url('asset/foto_berita/no-image.jpg'); ?>'">
                                                </a>
                                            </div>

                                            <div class="berita-card-content">
                                                <span class="berita-card-category">
                                                    <i class="fas fa-tag mr-1"></i> <?= $kategori_name; ?>
                                                </span>

                                                <h3 class="berita-card-title">
                                                    <a href="<?= $link_detail; ?>"><?= htmlspecialchars($judull); ?></a>
                                                </h3>

                                                <div class="berita-card-meta">
                                                    <span><i class="far fa-calendar-alt text-primary"></i> <?= $tgl_posting; ?></span>
                                                    <?php if ($jam_posting): ?>
                                                        <span><i class="far fa-clock text-info"></i> <?= $jam_posting; ?></span>
                                                    <?php endif; ?>
                                                    <span><i class="far fa-user text-secondary"></i> <?= $author; ?></span>
                                                    <span><i class="far fa-eye text-success"></i> <?= number_format($baca); ?> views</span>
                                                </div>

                                                <p class="berita-card-excerpt">
                                                    <?= htmlspecialchars($isi); ?>
                                                </p>

                                                <a href="<?= $link_detail; ?>" class="berita-card-link">
                                                    Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </article>
                                    <?php
                                        endforeach;
                                    else:
                                    ?>
                                        <div class="berita-empty-state">
                                            <i class="far fa-newspaper"></i>
                                            <h4>Belum Ada Berita</h4>
                                            <p>Saat ini belum ada artikel berita yang diterbitkan atau hasil pencarian tidak ditemukan.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Pagination Navigasi Halaman -->
                                <div class="berita-pagination">
                                    <?php echo pagination_links(); ?>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
