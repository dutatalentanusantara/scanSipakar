<style>
/* ==================== MODERN DOWNLOAD HUB STYLES ==================== */
.download-hub {
    margin-top: 15px;
}

/* Header & Search Bar */
.download-header-card {
    background: linear-gradient(135deg, #1e3a8a 0%, #0284c7 100%);
    border-radius: 18px;
    padding: 30px;
    color: #ffffff;
    box-shadow: 0 10px 30px rgba(2, 132, 199, 0.25);
    margin-bottom: 25px;
    position: relative;
    overflow: hidden;
}

.download-header-card::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
    border-radius: 50%;
}

.download-header-title {
    font-size: 24px;
    font-weight: 800;
    margin: 0 0 8px 0;
    color: #ffffff;
}

.download-header-subtitle {
    font-size: 14px;
    color: rgba(255, 255, 255, 0.85);
    margin: 0 0 20px 0;
    line-height: 1.5;
}

/* Search input */
.download-search-wrap {
    position: relative;
    max-width: 100%;
}

.download-search-input {
    width: 100%;
    padding: 14px 20px 14px 45px;
    border-radius: 12px;
    border: none;
    font-size: 15px;
    background: #ffffff;
    color: #1e293b;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    box-sizing: border-box;
    transition: all 0.3s ease;
}

.download-search-input:focus {
    outline: none;
    box-shadow: 0 0 0 4px rgba(255,255,255,0.4);
}

.download-search-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    font-size: 16px;
}

/* Stats Badges */
.download-stats-row {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    margin-bottom: 25px;
}

.download-stat-pill {
    flex: 1;
    min-width: 140px;
    background: #ffffff;
    padding: 16px 20px;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    display: flex;
    align-items: center;
    gap: 12px;
}

.stat-pill-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.stat-icon-blue { background: #eff6ff; color: #2563eb; }
.stat-icon-green { background: #f0fdf4; color: #16a34a; }
.stat-icon-purple { background: #faf5ff; color: #9333ea; }

.stat-pill-content h4 {
    margin: 0;
    font-size: 18px;
    font-weight: 800;
    color: #0f172a;
}

.stat-pill-content span {
    font-size: 12px;
    color: #64748b;
    font-weight: 500;
}

/* Document Item Card */
.doc-item-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 22px;
    margin-bottom: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
}

.doc-item-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    border-color: #cbd5e1;
}

.doc-item-left {
    display: flex;
    align-items: flex-start;
    gap: 18px;
    flex: 1;
}

.doc-type-badge {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-weight: 800;
    font-size: 11px;
    text-transform: uppercase;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}

.badge-pdf {
    background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
    color: #ffffff;
}

.badge-doc {
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: #ffffff;
}

.badge-gdrive {
    background: linear-gradient(135deg, #0284c7 0%, #047857 100%);
    color: #ffffff;
}

.badge-file {
    background: linear-gradient(135deg, #64748b 0%, #334155 100%);
    color: #ffffff;
}

.doc-type-badge i {
    font-size: 18px;
    margin-bottom: 2px;
}

.doc-item-info h4 {
    margin: 0 0 8px 0;
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.45;
}

.doc-item-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: center;
    font-size: 13px;
    color: #64748b;
}

.doc-meta-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #f1f5f9;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.doc-item-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

.btn-doc-preview {
    background: #f8fafc;
    color: #0284c7;
    border: 1.5px solid #e0f2fe;
    padding: 10px 18px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.25s ease;
}

.btn-doc-preview:hover {
    background: #e0f2fe;
    color: #0369a1;
    text-decoration: none;
}

.btn-doc-download {
    background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
    color: #ffffff !important;
    border: none;
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
    transition: all 0.25s ease;
}

.btn-doc-download:hover {
    background: linear-gradient(135deg, #0369a1 0%, #075985 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(2, 132, 199, 0.35);
    text-decoration: none;
}

/* Empty State */
.doc-empty-state {
    text-align: center;
    padding: 50px 20px;
    background: #ffffff;
    border-radius: 16px;
    border: 1px dashed #cbd5e1;
}

.doc-empty-state i {
    font-size: 48px;
    color: #94a3b8;
    margin-bottom: 15px;
}

.doc-empty-state h4 {
    font-size: 18px;
    color: #334155;
    margin-bottom: 6px;
}

.doc-empty-state p {
    color: #64748b;
    margin: 0;
}

@media (max-width: 768px) {
    .doc-item-card {
        flex-direction: column;
        align-items: stretch;
    }
    .doc-item-actions {
        justify-content: flex-end;
        border-top: 1px solid #f1f5f9;
        padding-top: 12px;
        margin-top: 8px;
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
                    
                    <!-- Kolom Kanan: Pusat Unduhan Dokumen Publik -->
                    <div class="news3col__col" style="flex: 2; order: 1; width: 100%">
                        <div class="leader">
                            <div class="leader__label">
                                <div class="xlabel"><span>Pusat Unduhan</span></div>
                            </div>
                            
                            <div class="download-hub">
                                <!-- Banner Header & Pencarian Cepat -->
                                <div class="download-header-card">
                                    <h3 class="download-header-title">
                                        <i class="fas fa-file-invoice mr-2"></i> Pusat Dokumen & Informasi Publik
                                    </h3>
                                    <p class="download-header-subtitle">
                                        Akses keterbukaan informasi publik Pemerintah Kecamatan Siantar Barat. Temukan dokumen perencanaan, anggaran, laporan tahunan, dan regulasi resmi di sini.
                                    </p>
                                    
                                    <div class="download-search-wrap">
                                        <i class="fas fa-search download-search-icon"></i>
                                        <input type="text" id="docSearchInput" class="download-search-input" placeholder="Ketik kata kunci untuk menyaring dokumen..." value="<?= htmlspecialchars($search_keyword ?? '', ENT_QUOTES, 'UTF-8'); ?>" onkeyup="filterDocuments()">
                                    </div>
                                </div>

                                <?php
                                $total_unduhan = 0;
                                if (!empty($documents)) {
                                    foreach ($documents as $d) {
                                        $total_unduhan += $d['hits'];
                                    }
                                }
                                ?>

                                <!-- Statistik Ringkas -->
                                <div class="download-stats-row">
                                    <div class="download-stat-pill">
                                        <div class="stat-pill-icon stat-icon-blue"><i class="fas fa-folder-open"></i></div>
                                        <div class="stat-pill-content">
                                            <h4><?= count($documents ?? []); ?> Berkas</h4>
                                            <span>Dokumen Publik</span>
                                        </div>
                                    </div>
                                    <div class="download-stat-pill">
                                        <div class="stat-pill-icon stat-icon-green"><i class="fas fa-file-pdf"></i></div>
                                        <div class="stat-pill-content">
                                            <h4>Format PDF</h4>
                                            <span>Standar Arsip Resmi</span>
                                        </div>
                                    </div>
                                    <div class="download-stat-pill">
                                        <div class="stat-pill-icon stat-icon-purple"><i class="fas fa-cloud-download-alt"></i></div>
                                        <div class="stat-pill-content">
                                            <h4><?= number_format($total_unduhan); ?> Kali</h4>
                                            <span>Total Diunduh Warga</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pesan Notifikasi Flash Data jika ada -->
                                <?php if (session()->getFlashdata('error')): ?>
                                    <div class="alert alert-danger" style="border-radius: 12px; margin-bottom: 20px;">
                                        <i class="fas fa-exclamation-triangle mr-2"></i> <?= session()->getFlashdata('error'); ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Daftar Dokumen Publik -->
                                <div id="documentList">
                                    <?php if (!empty($documents)): ?>
                                        <?php foreach ($documents as $doc): ?>
                                            <div class="doc-item-card" data-title="<?= strtolower($doc['judul']); ?>" data-file="<?= strtolower($doc['nama_file']); ?>">
                                                <div class="doc-item-left">
                                                    <div class="doc-type-badge <?= $doc['is_pdf'] ? 'badge-pdf' : ($doc['extension'] === 'gdrive' ? 'badge-gdrive' : 'badge-file'); ?>">
                                                        <i class="<?= $doc['is_pdf'] ? 'fas fa-file-pdf' : ($doc['extension'] === 'gdrive' ? 'fab fa-google-drive' : 'fas fa-file-alt'); ?>"></i>
                                                        <span><?= $doc['extension'] === 'gdrive' ? 'GDRIVE' : strtoupper($doc['extension']); ?></span>
                                                    </div>
                                                    <div class="doc-item-info">
                                                        <h4><?= $doc['judul']; ?></h4>
                                                        <div class="doc-item-meta">
                                                            <span class="doc-meta-badge">
                                                                <i class="far fa-calendar-alt text-primary"></i> <?= date('d M Y', strtotime($doc['tgl_posting'])); ?>
                                                            </span>
                                                            <span class="doc-meta-badge">
                                                                <i class="fas fa-hdd text-info"></i> <?= $doc['filesize']; ?>
                                                            </span>
                                                            <span class="doc-meta-badge">
                                                                <i class="fas fa-arrow-circle-down text-success"></i> <?= number_format($doc['hits']); ?>x Diunduh
                                                            </span>
                                                            <span class="doc-meta-badge" style="background: #ecfdf5; color: #047857;">
                                                                <i class="fas fa-shield-alt text-success"></i> Terverifikasi
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="doc-item-actions">
                                                    <?php if ($doc['is_pdf']): ?>
                                                        <a href="<?= base_url('download/preview/' . $doc['id_download']); ?>" target="_blank" rel="noopener noreferrer" class="btn-doc-preview" title="Pratinjau langsung di browser">
                                                            <i class="fas fa-eye"></i> Pratinjau
                                                        </a>
                                                    <?php endif; ?>
                                                    
                                                    <a href="<?= base_url('download/file/' . $doc['id_download']); ?>" <?= !empty($doc['is_external']) ? 'target="_blank" rel="noopener noreferrer"' : ''; ?> class="btn-doc-download" title="Unduh berkas resmi">
                                                        <i class="fas fa-download"></i> Unduh
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="doc-empty-state">
                                            <i class="fas fa-folder-open"></i>
                                            <h4>Belum Ada Dokumen</h4>
                                            <p>Saat ini belum ada berkas dokumen publik yang diterbitkan.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Pesan Tidak Ditemukan saat Filter JS -->
                                <div id="noMatchMessage" class="doc-empty-state" style="display: none;">
                                    <i class="fas fa-search"></i>
                                    <h4>Dokumen Tidak Ditemukan</h4>
                                    <p>Tidak ada dokumen yang cocok dengan kata kunci pencarian Anda.</p>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
function filterDocuments() {
    var input = document.getElementById("docSearchInput");
    var filter = input.value.toLowerCase().trim();
    var list = document.getElementById("documentList");
    var cards = list.getElementsByClassName("doc-item-card");
    var matches = 0;

    for (var i = 0; i < cards.length; i++) {
        var title = cards[i].getAttribute("data-title") || "";
        var file = cards[i].getAttribute("data-file") || "";

        if (title.indexOf(filter) > -1 || file.indexOf(filter) > -1) {
            cards[i].style.display = "";
            matches++;
        } else {
            cards[i].style.display = "none";
        }
    }

    var noMatch = document.getElementById("noMatchMessage");
    if (noMatch) {
        if (matches === 0 && cards.length > 0) {
            noMatch.style.display = "block";
        } else {
            noMatch.style.display = "none";
        }
    }
}
</script>
