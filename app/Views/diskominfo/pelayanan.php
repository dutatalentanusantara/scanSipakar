<?php
/**
 * View: Pelayanan Administrasi Warga & Kelurahan
 *
 * @var array $layanan_list
 * @var array $kelurahan_list
 * @var array $ahli_waris_download
 * @var array $penyerahan_waris_download
 * @var array $surat_kuasa_download
 * @var array $surat_hibah_download
 * @var array $surat_pindah_download
 * @var array $dispensasi_nikah_download
 * @var array $jam_pelayanan
 */
helper('portal');
?>
<style>
/* ==================== MODERN PELAYANAN WARGA HUB STYLES ==================== */
.pelayanan-hub {
    margin-top: 15px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Header Banner - Green Theme matching user request */
.pelayanan-header-card {
    background: linear-gradient(135deg, #065f46 0%, #047857 50%, #0d9488 100%);
    border-radius: 20px;
    padding: 35px 30px;
    color: #ffffff;
    box-shadow: 0 12px 35px rgba(6, 95, 70, 0.25);
    margin-bottom: 25px;
    position: relative;
    overflow: hidden;
}

.pelayanan-header-card::before {
    content: '';
    position: absolute;
    top: -40%;
    right: -15%;
    width: 320px;
    height: 320px;
    background: radial-gradient(circle, rgba(255,255,255,0.18) 0%, rgba(255,255,255,0) 70%);
    border-radius: 50%;
    pointer-events: none;
}

.pelayanan-breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: rgba(255, 255, 255, 0.85);
    margin-bottom: 15px;
}

.pelayanan-breadcrumb a {
    color: #ffffff;
    text-decoration: none;
    font-weight: 500;
}

.pelayanan-breadcrumb a:hover {
    text-decoration: underline;
}

.pelayanan-header-title {
    font-size: 28px;
    font-weight: 800;
    margin: 0 0 10px 0;
    color: #ffffff;
    letter-spacing: -0.5px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.pelayanan-header-subtitle {
    font-size: 14.5px;
    color: rgba(255, 255, 255, 0.9);
    margin: 0 0 25px 0;
    line-height: 1.6;
    max-width: 850px;
}

/* Search input */
.pelayanan-search-wrap {
    position: relative;
    max-width: 100%;
}

.pelayanan-search-input {
    width: 100%;
    padding: 15px 20px 15px 50px;
    border-radius: 14px;
    border: none;
    font-size: 15px;
    background: #ffffff;
    color: #1e293b;
    box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    box-sizing: border-box;
    transition: all 0.3s ease;
}

.pelayanan-search-input:focus {
    outline: none;
    box-shadow: 0 0 0 4px rgba(52, 211, 153, 0.45);
}

.pelayanan-search-icon {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #059669;
    font-size: 18px;
}

/* Highlights Badges */
.pelayanan-highlights {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 20px;
}

.pelayanan-badge-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.25);
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 12.5px;
    font-weight: 600;
    color: #ffffff;
}

/* Filter Nav Tabs */
.pelayanan-tabs-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 25px;
    background: #ffffff;
    padding: 12px 16px;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03);
}

.pelayanan-tab-btn {
    border: none;
    background: #f1f5f9;
    color: #475569;
    padding: 8px 16px;
    border-radius: 10px;
    font-size: 13.5px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.pelayanan-tab-btn:hover {
    background: #e2e8f0;
    color: #1e293b;
}

.pelayanan-tab-btn.active {
    background: #059669;
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
}

/* Service Cards Grid */
.pelayanan-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 35px;
}

@media (max-width: 768px) {
    .pelayanan-grid {
        grid-template-columns: 1fr;
    }
}

.pelayanan-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    position: relative;
    overflow: hidden;
}

.pelayanan-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 5px;
    height: 100%;
    background: var(--card-accent, #059669);
    transition: width 0.3s ease;
}

.pelayanan-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
    border-color: #cbd5e1;
}

.pelayanan-card-header {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 15px;
}

.pelayanan-card-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: var(--card-bg, #ecfdf5);
    color: var(--card-accent, #059669);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.pelayanan-card-title-wrap {
    flex-grow: 1;
}

.pelayanan-card-tag {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--card-accent, #059669);
    background: var(--card-bg, #ecfdf5);
    padding: 2px 8px;
    border-radius: 6px;
    margin-bottom: 5px;
}

.pelayanan-card-title {
    font-size: 17px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    line-height: 1.35;
}

.pelayanan-card-desc {
    font-size: 13.5px;
    color: #64748b;
    line-height: 1.55;
    margin-bottom: 16px;
}

.pelayanan-info-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    background: #f8fafc;
    padding: 10px 14px;
    border-radius: 10px;
    border: 1px solid #f1f5f9;
    margin-bottom: 16px;
    font-size: 12.5px;
}

.pelayanan-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #475569;
}

.pelayanan-meta-item strong {
    color: #1e293b;
}

/* Requirements Preview */
.pelayanan-syarat-box {
    margin-top: auto;
    border-top: 1px solid #f1f5f9;
    padding-top: 14px;
}

.pelayanan-syarat-title {
    font-size: 13px;
    font-weight: 700;
    color: #334155;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.pelayanan-syarat-list {
    margin: 0 0 16px 0;
    padding-left: 20px;
    font-size: 12.5px;
    color: #475569;
    line-height: 1.6;
}

.pelayanan-syarat-list li {
    margin-bottom: 4px;
}

.btn-detail-layanan {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 10px 16px;
    background: #f1f5f9;
    color: #059669;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.25s ease;
    cursor: pointer;
}

.btn-detail-layanan:hover {
    background: #059669;
    color: #ffffff;
    border-color: #059669;
}

/* 5 Steps Procedure Flowchart */
.procedure-flow-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    padding: 28px;
    box-shadow: 0 4px 18px rgba(0,0,0,0.03);
    margin-bottom: 35px;
}

.procedure-flow-title {
    font-size: 20px;
    font-weight: 800;
    color: #1e293b;
    margin: 0 0 6px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.procedure-flow-subtitle {
    font-size: 13.5px;
    color: #64748b;
    margin: 0 0 24px 0;
}

.procedure-steps-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 14px;
    position: relative;
}

@media (max-width: 991px) {
    .procedure-steps-grid {
        grid-template-columns: 1fr;
    }
}

.step-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 18px 14px;
    text-align: center;
    position: relative;
    transition: transform 0.25s ease;
}

.step-card:hover {
    transform: translateY(-3px);
    border-color: #059669;
}

.step-number {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #059669;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    font-weight: 800;
    margin: 0 auto 12px auto;
}

.step-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 6px 0;
}

.step-desc {
    font-size: 12px;
    color: #64748b;
    line-height: 1.45;
    margin: 0;
}

/* Kelurahan Directory Section */
.kelurahan-section {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    padding: 28px;
    box-shadow: 0 4px 18px rgba(0,0,0,0.03);
    margin-bottom: 35px;
}

.kelurahan-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-top: 20px;
}

@media (max-width: 991px) {
    .kelurahan-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .kelurahan-grid {
        grid-template-columns: 1fr;
    }
}

.kelurahan-card {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px;
    transition: all 0.25s ease;
}

.kelurahan-card:hover {
    background: #ffffff;
    border-color: #059669;
    box-shadow: 0 6px 16px rgba(5, 150, 105, 0.1);
    transform: translateY(-2px);
}

.kelurahan-name {
    font-size: 15px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.kelurahan-name i {
    color: #059669;
}

.kelurahan-addr {
    font-size: 12px;
    color: #64748b;
    margin-bottom: 8px;
    line-height: 1.4;
}

.kelurahan-contact {
    font-size: 12px;
    color: #0284c7;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Maklumat Card */
.maklumat-box {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border-radius: 18px;
    padding: 26px;
    color: #ffffff;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
    margin-bottom: 25px;
}

.maklumat-box h3 {
    margin: 0 0 12px 0;
    font-size: 18px;
    font-weight: 800;
    color: #f8fafc;
    display: flex;
    align-items: center;
    gap: 10px;
}

.maklumat-box p {
    font-size: 13.5px;
    color: #cbd5e1;
    line-height: 1.6;
    margin-bottom: 16px;
}

.maklumat-points {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
}

@media (max-width: 768px) {
    .maklumat-points {
        grid-template-columns: 1fr;
    }
}

.maklumat-point-item {
    background: rgba(255, 255, 255, 0.06);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    padding: 12px 14px;
}

.maklumat-point-item strong {
    display: block;
    color: #34d399;
    font-size: 13.5px;
    margin-bottom: 4px;
}

.maklumat-point-item span {
    font-size: 12px;
    color: #94a3b8;
}

/* ==================== PERSYARATAN KELAS WARIS SECTION ==================== */
.kelas-waris-section {
    background: linear-gradient(145deg, #064e3b 0%, #065f46 60%, #047857 100%);
    border-radius: 18px;
    padding: 26px 22px;
    color: #ffffff;
    box-shadow: 0 10px 30px rgba(6, 78, 59, 0.22);
    margin-bottom: 25px;
    border: 1px solid rgba(255, 255, 255, 0.18);
    position: relative;
    overflow: hidden;
    width: 100%;
    box-sizing: border-box;
}

.kelas-waris-section::after {
    content: '';
    position: absolute;
    bottom: -30%;
    right: -10%;
    width: 250px;
    height: 250px;
    background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0) 70%);
    border-radius: 50%;
    pointer-events: none;
}

.kelas-waris-title {
    font-size: 21px;
    font-weight: 800;
    color: #ffffff;
    margin: 0 0 6px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.kelas-waris-subtitle {
    font-size: 13.5px;
    color: rgba(255, 255, 255, 0.9);
    margin: 0 0 18px 0;
    line-height: 1.5;
}

.kelas-waris-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 16px;
    width: 100%;
    box-sizing: border-box;
}

@media (max-width: 860px) {
    .kelas-waris-grid {
        grid-template-columns: 1fr;
    }
}

.waris-item-card {
    background: rgba(255, 255, 255, 0.98);
    color: #1e293b;
    border-radius: 14px;
    padding: 18px 18px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    border-left: 5px solid #059669;
    min-width: 0;
    box-sizing: border-box;
    width: 100%;
}

.waris-item-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}

.waris-item-card.card-var-b {
    border-left-color: #0284c7;
}

.waris-item-card.card-var-c {
    border-left-color: #7c3aed;
}

.waris-item-card.card-var-d {
    border-left-color: #b45309;
}

.waris-item-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #ecfdf5;
    color: #047857;
    font-size: 11.5px;
    font-weight: 800;
    padding: 3px 10px;
    border-radius: 20px;
    margin-bottom: 8px;
    width: fit-content;
}

.waris-item-badge.badge-var-b {
    background: #f0f9ff;
    color: #0284c7;
}

.waris-item-badge.badge-var-c {
    background: #f5f3ff;
    color: #7c3aed;
}

.waris-item-badge.badge-var-d {
    background: #fffbeb;
    color: #b45309;
}

.waris-item-title {
    font-size: 15.5px;
    font-weight: 800;
    color: #0f172a;
    margin: 0 0 10px 0;
    display: flex;
    align-items: center;
    gap: 8px;
    line-height: 1.4;
    word-break: break-word;
    overflow-wrap: break-word;
}

.waris-item-syarat {
    font-size: 13px;
    color: #334155;
    line-height: 1.55;
    margin: 0 0 16px 0;
    padding-left: 18px;
    flex: 1;
}

.waris-item-syarat li {
    margin-bottom: 5px;
}

/* ==================== WARIS GDRIVE DOWNLOAD BOX ==================== */
.waris-gdrive-download-box {
    margin-top: auto;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border: 1.5px solid #cbd5e1;
    border-radius: 12px;
    padding: 12px 14px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
    transition: all 0.25s ease;
    box-sizing: border-box;
    width: 100%;
}

.waris-gdrive-download-box:hover {
    border-color: #0284c7;
    box-shadow: 0 4px 14px rgba(2, 132, 199, 0.12);
}

.waris-gdrive-left {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    min-width: 0;
}

.waris-gdrive-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #e0f2fe;
    color: #0284c7;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
    transition: transform 0.25s ease;
}

.waris-gdrive-download-box:hover .waris-gdrive-icon {
    transform: scale(1.06);
}

.waris-gdrive-info {
    min-width: 0;
    flex: 1;
}

.waris-gdrive-label {
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #64748b;
    margin-bottom: 2px;
}

.waris-gdrive-name {
    font-size: 13px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.35;
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.waris-gdrive-meta {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

.waris-meta-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    color: #334155;
}

.waris-gdrive-actions {
    width: 100%;
}

.btn-waris-download {
    background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
    color: #ffffff !important;
    border: none;
    padding: 8px 14px;
    border-radius: 9px;
    font-size: 12.5px;
    font-weight: 700;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    box-shadow: 0 3px 10px rgba(2, 132, 199, 0.25);
    transition: all 0.25s ease;
    white-space: nowrap;
    cursor: pointer;
    width: 100%;
    box-sizing: border-box;
}

.btn-waris-download:hover {
    background: linear-gradient(135deg, #0369a1 0%, #075985 100%);
    transform: translateY(-1px);
    box-shadow: 0 5px 14px rgba(2, 132, 199, 0.35);
    text-decoration: none;
}

@media (max-width: 600px) {
    .waris-gdrive-download-box {
        padding: 10px 12px;
    }
}
</style>

<div class="wrapper pelayanan-hub">
    <div class="mainwrap">
        <div class="sectionrow">
            <div class="minidashboard">
                <div class="minidashboard__body" style="display: flex; flex-wrap: wrap; gap: 20px;">

                    <!-- ==================== KOLOM KANAN (CONTENT UTAMA) ==================== -->
                    <div class="news3col__col" style="flex: 2; order: 1; min-width: 0; max-width: 100%; box-sizing: border-box;">
                        
                        <!-- Header Banner -->
                        <div class="pelayanan-header-card">
                            <div class="pelayanan-breadcrumb">
                                <a href="<?= base_url(); ?>"><i class="fas fa-home"></i> Beranda</a>
                                <span>/</span>
                                <span>Layanan Publik</span>
                                <span>/</span>
                                <span>Administrasi Warga & Kelurahan</span>
                            </div>

                            <h1 class="pelayanan-header-title">
                                <i class="fas fa-landmark"></i> Pelayanan Administrasi Warga
                            </h1>
                            <p class="pelayanan-header-subtitle">
                                Portal resmi standar operasional prosedur (SOP) pelayanan publik terpadu Siap1Pintu Kecamatan Siantar Barat. Panduan persyaratan, alur penerbitan dokumen kependudukan, surat keterangan, serta informasi 8 kantor kelurahan.
                            </p>

                            <!-- Live Search Input -->
                            <div class="pelayanan-search-wrap">
                                <i class="fas fa-search pelayanan-search-icon"></i>
                                <input type="text" 
                                       id="searchLayanan" 
                                       class="pelayanan-search-input" 
                                       placeholder="Ketik jenis layanan (contoh: Ahli Waris, Surat Kuasa, Hibah, Surat Pindah, Dispensasi Nikah, Legalisir)..." 
                                       autocomplete="off" />
                            </div>

                            <!-- Highlights Badges -->
                            <div class="pelayanan-highlights">
                                <span class="pelayanan-badge-pill">
                                    <i class="fas fa-check-circle text-warning"></i> 100% GRATIS (Tanpa Pungli)
                                </span>
                                <span class="pelayanan-badge-pill">
                                    <i class="fas fa-file-signature"></i> Layanan Kelas Waris Resmi
                                </span>
                                <span class="pelayanan-badge-pill">
                                    <i class="fas fa-shield-alt"></i> Standar Resmi Siap1Pintu
                                </span>
                                <span class="pelayanan-badge-pill">
                                    <i class="fas fa-map-marker-alt"></i> 8 Kelurahan Terintegrasi
                                </span>
                            </div>
                        </div>

                        <!-- ==================== PERSYARATAN KELAS WARIS (SPECIAL HIGHLIGHT) ==================== -->
                        <div class="kelas-waris-section" id="kelas-waris">
                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;">
                                <div>
                                    <h2 class="kelas-waris-title">
                                        <i class="fas fa-balance-scale text-warning"></i> PERSYARATAN KELAS WARIS
                                    </h2>
                                    <p class="kelas-waris-subtitle">
                                        Standar panduan berkas resmi permohonan administrasi kewarisan, penyerahan hak waris, surat kuasa, dan hibah tanah/bangunan di Kecamatan Siantar Barat.
                                    </p>
                                </div>
                                <button type="button" class="btn btn-sm" onclick="konsultasiLayanan('Konsultasi Persyaratan Kelas Waris')" style="background: #ffffff; color: #047857; font-weight: 700; border-radius: 8px; padding: 8px 16px; border: none; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.15);">
                                    <i class="fab fa-whatsapp"></i> Konsultasi Kelas Waris
                                </button>
                            </div>

                            <div class="kelas-waris-grid">
                                <!-- A. Surat Pernyataan Ahli Waris -->
                                <div class="waris-item-card">
                                    <span class="waris-item-badge">
                                        <i class="fas fa-stamp"></i> Kelas Waris - Bagian A
                                    </span>
                                    <h3 class="waris-item-title">
                                        <i class="fas fa-file-signature text-success"></i> A. Surat Pernyataan Ahli Waris
                                    </h3>
                                    <ul class="waris-item-syarat">
                                        <li>Fotokopi KTP dan KK seluruh ahli waris</li>
                                        <li>Surat/akta kematian</li>
                                        <li>Surat nikah/akta perkawinan</li>
                                        <li>Surat cerai (jika ada)</li>
                                        <li>Surat kematian dari RS/dokter</li>
                                        <li>Akta kelahiran ahli waris di bawah 17 tahun</li>
                                        <li><strong>Wajib melampirkan foto ahli waris saat penandatanganan surat</strong></li>
                                        <li>Fotokopi KTP saksi</li>
                                        <li>SPPT PBB lunas tahun terakhir</li>
                                    </ul>

                                    <div style="background: #ecfdf5; border-left: 3px solid #059669; border-radius: 6px; padding: 7px 10px; margin-bottom: 12px; font-size: 11.5px; color: #065f46; line-height: 1.45;">
                                        <i class="fas fa-camera mr-1"></i> <strong>Keterangan:</strong> Layanan surat ahli waris wajib ada terlampir foto ahli waris saat penandatanganan surat.
                                    </div>

                                    <!-- Link Google Drive Berkas Unduhan Ahli Waris -->
                                    <div class="waris-gdrive-download-box">
                                        <div class="waris-gdrive-left">
                                            <div class="waris-gdrive-icon" style="background: #ecfdf5; color: #047857;">
                                                <i class="fab fa-google-drive"></i>
                                            </div>
                                            <div class="waris-gdrive-info">
                                                <div class="waris-gdrive-label">Berkas Formulir Persyaratan</div>
                                                <div class="waris-gdrive-name">Format Berkas Ahli Waris (Google Drive)</div>
                                                <div class="waris-gdrive-meta">
                                                    <span class="waris-meta-badge" title="Statistik total unduhan dokumen">
                                                        <i class="fas fa-arrow-circle-down text-success"></i> 
                                                        <span id="warisDownloadHitsCount"><?= number_format((int) ($ahli_waris_download['hits'] ?? 0)); ?></span>x Diunduh
                                                    </span>
                                                    <span class="waris-meta-badge" style="background: #ecfdf5; color: #047857;">
                                                        <i class="fas fa-cloud"></i> Folder Resmi
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="waris-gdrive-actions">
                                            <a href="<?= base_url('pelayanan/unduh-ahli-waris'); ?>" target="_blank" rel="noopener noreferrer" class="btn-waris-download" onclick="trackWarisDownload(event)" style="background: linear-gradient(135deg, #059669 0%, #047857 100%);" title="Unduh berkas persyaratan resmi di Google Drive">
                                                <i class="fas fa-download"></i> Unduh Berkas Ahli Waris
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- B. Penyerahan Hak Waris -->
                                <div class="waris-item-card card-var-b">
                                    <span class="waris-item-badge badge-var-b">
                                        <i class="fas fa-handshake"></i> Kelas Waris - Bagian B
                                    </span>
                                    <h3 class="waris-item-title">
                                        <i class="fas fa-handshake text-info"></i> B. Penyerahan Hak Waris
                                    </h3>
                                    <ul class="waris-item-syarat">
                                        <li>Berkas ahli waris</li>
                                        <li>Fotokopi KTP dan KK</li>
                                        <li>Bukti kepemilikan tanah (SHM, AJB, surat jual beli, hibah, atau dokumen sah lainnya)</li>
                                        <li>SPPT PBB lunas</li>
                                    </ul>

                                    <!-- Link Google Drive Berkas Unduhan Penyerahan Hak Waris -->
                                    <div class="waris-gdrive-download-box">
                                        <div class="waris-gdrive-left">
                                            <div class="waris-gdrive-icon" style="background: #e0f2fe; color: #0284c7;">
                                                <i class="fab fa-google-drive"></i>
                                            </div>
                                            <div class="waris-gdrive-info">
                                                <div class="waris-gdrive-label">Berkas Formulir Persyaratan</div>
                                                <div class="waris-gdrive-name">Format Penyerahan Hak Waris (Google Drive)</div>
                                                <div class="waris-gdrive-meta">
                                                    <span class="waris-meta-badge" title="Statistik total unduhan dokumen">
                                                        <i class="fas fa-arrow-circle-down text-success"></i> 
                                                        <span id="penyerahanDownloadHitsCount"><?= number_format((int) ($penyerahan_waris_download['hits'] ?? 0)); ?></span>x Diunduh
                                                    </span>
                                                    <span class="waris-meta-badge" style="background: #e0f2fe; color: #0284c7;">
                                                        <i class="fas fa-cloud"></i> Folder Resmi
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="waris-gdrive-actions">
                                            <a href="<?= base_url('pelayanan/unduh-penyerahan-waris'); ?>" target="_blank" rel="noopener noreferrer" class="btn-waris-download" onclick="trackPenyerahanDownload(event)" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);" title="Unduh berkas persyaratan resmi di Google Drive">
                                                <i class="fas fa-download"></i> Unduh Berkas Penyerahan Waris
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- C. Surat Kuasa -->
                                <div class="waris-item-card card-var-c">
                                    <span class="waris-item-badge badge-var-c">
                                        <i class="fas fa-file-contract"></i> Kelas Waris - Bagian C
                                    </span>
                                    <h3 class="waris-item-title">
                                        <i class="fas fa-file-contract" style="color: #7c3aed;"></i> C. Surat Kuasa
                                    </h3>
                                    <ul class="waris-item-syarat">
                                        <li>Berkas ahli waris</li>
                                        <li>Fotokopi buku rekening bank</li>
                                        <li>Bukti objek yang dikuasakan</li>
                                        <li>Fotokopi KTP pemberi dan penerima kuasa</li>
                                    </ul>

                                    <!-- Link Google Drive Berkas Unduhan Surat Kuasa -->
                                    <div class="waris-gdrive-download-box">
                                        <div class="waris-gdrive-left">
                                            <div class="waris-gdrive-icon" style="background: #f5f3ff; color: #7c3aed;">
                                                <i class="fab fa-google-drive"></i>
                                            </div>
                                            <div class="waris-gdrive-info">
                                                <div class="waris-gdrive-label">Berkas Formulir Persyaratan</div>
                                                <div class="waris-gdrive-name">Format Surat Kuasa (Google Drive)</div>
                                                <div class="waris-gdrive-meta">
                                                    <span class="waris-meta-badge" title="Statistik total unduhan dokumen">
                                                        <i class="fas fa-arrow-circle-down text-success"></i> 
                                                        <span id="suratKuasaDownloadHitsCount"><?= number_format((int) ($surat_kuasa_download['hits'] ?? 0)); ?></span>x Diunduh
                                                    </span>
                                                    <span class="waris-meta-badge" style="background: #f5f3ff; color: #7c3aed;">
                                                        <i class="fas fa-cloud"></i> Folder Resmi
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="waris-gdrive-actions">
                                            <a href="<?= base_url('pelayanan/unduh-surat-kuasa'); ?>" target="_blank" rel="noopener noreferrer" class="btn-waris-download" onclick="trackSuratKuasaDownload(event)" style="background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);" title="Unduh berkas persyaratan resmi di Google Drive">
                                                <i class="fas fa-download"></i> Unduh Berkas Surat Kuasa
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- D. Surat Hibah Tanah dan Bangunan -->
                                <div class="waris-item-card card-var-d">
                                    <span class="waris-item-badge badge-var-d">
                                        <i class="fas fa-home"></i> Kelas Waris - Bagian D
                                    </span>
                                    <h3 class="waris-item-title">
                                        <i class="fas fa-home" style="color: #b45309;"></i> D. Surat Hibah Tanah dan Bangunan
                                    </h3>
                                    <ul class="waris-item-syarat">
                                        <li>Berkas ahli waris</li>
                                        <li>KTP, KK, surat nikah</li>
                                        <li>Persetujuan pasangan jika harta bersama</li>
                                        <li>Sertifikat asli dan fotokopi surat tanah</li>
                                        <li>SPPT PBB lunas</li>
                                        <li>Pernyataan tanah tidak bersengketa atau dijaminkan</li>
                                    </ul>

                                    <!-- Link Google Drive Berkas Unduhan Surat Hibah -->
                                    <div class="waris-gdrive-download-box">
                                        <div class="waris-gdrive-left">
                                            <div class="waris-gdrive-icon" style="background: #fffbeb; color: #b45309;">
                                                <i class="fab fa-google-drive"></i>
                                            </div>
                                            <div class="waris-gdrive-info">
                                                <div class="waris-gdrive-label">Berkas Formulir Persyaratan</div>
                                                <div class="waris-gdrive-name">Format Berkas Surat Hibah (Google Drive)</div>
                                                <div class="waris-gdrive-meta">
                                                    <span class="waris-meta-badge" title="Statistik total unduhan dokumen">
                                                        <i class="fas fa-arrow-circle-down text-success"></i> 
                                                        <span id="suratHibahDownloadHitsCount"><?= number_format((int) ($surat_hibah_download['hits'] ?? 0)); ?></span>x Diunduh
                                                    </span>
                                                    <span class="waris-meta-badge" style="background: #fffbeb; color: #b45309;">
                                                        <i class="fas fa-cloud"></i> Folder Resmi
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="waris-gdrive-actions">
                                            <a href="<?= base_url('pelayanan/unduh-surat-hibah'); ?>" target="_blank" rel="noopener noreferrer" class="btn-waris-download" onclick="trackSuratHibahDownload(event)" style="background: linear-gradient(135deg, #b45309 0%, #92400e 100%);" title="Unduh berkas persyaratan resmi di Google Drive">
                                                <i class="fas fa-download"></i> Unduh Berkas Surat Hibah
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Tabs -->
                        <div class="pelayanan-tabs-bar">
                            <button class="pelayanan-tab-btn active" data-filter="all">
                                <i class="fas fa-th-large"></i> Semua Layanan (<?= count($layanan_list ?? []); ?>)
                            </button>
                            <button class="pelayanan-tab-btn" data-filter="ahli-waris">
                                <i class="fas fa-file-signature"></i> Ahli Waris (Kelas Waris)
                            </button>
                            <button class="pelayanan-tab-btn" data-filter="kependudukan">
                                <i class="fas fa-truck-moving"></i> Kependudukan (Pindah)
                            </button>
                            <button class="pelayanan-tab-btn" data-filter="sosial">
                                <i class="fas fa-hand-holding-heart"></i> Sosial & Nikah
                            </button>
                            <button class="pelayanan-tab-btn" data-filter="surat-keterangan">
                                <i class="fas fa-stamp"></i> Surat Keterangan & Legalisir
                            </button>
                            <a href="#kelurahan" class="pelayanan-tab-btn" style="text-decoration: none;">
                                <i class="fas fa-building"></i> 8 Kelurahan
                            </a>
                        </div>

                        <!-- Service Cards Grid -->
                        <div class="pelayanan-grid" id="layananGrid">
                            <?php foreach (($layanan_list ?? []) as $layanan): 
                                $card_accent = $layanan['warna'] ?? '#047857';
                                $card_bg = $card_accent . '15'; // 15% opacity hex
                            ?>
                            <div class="pelayanan-card" 
                                 data-category="<?= $layanan['kategori']; ?>"
                                 data-title="<?= strtolower($layanan['judul']); ?>"
                                 data-desc="<?= strtolower($layanan['deskripsi']); ?>"
                                 data-syarat="<?= strtolower(implode(' ', $layanan['syarat'])); ?>"
                                 style="--card-accent: <?= $card_accent; ?>; --card-bg: <?= $card_bg; ?>;">
                                
                                <div class="pelayanan-card-header">
                                    <div class="pelayanan-card-icon">
                                        <i class="<?= $layanan['icon']; ?>"></i>
                                    </div>
                                    <div class="pelayanan-card-title-wrap">
                                        <span class="pelayanan-card-tag"><?= $layanan['kategori_label']; ?></span>
                                        <h3 class="pelayanan-card-title"><?= $layanan['judul']; ?></h3>
                                    </div>
                                </div>

                                <p class="pelayanan-card-desc"><?= $layanan['deskripsi']; ?></p>

                                <div class="pelayanan-info-meta">
                                    <div class="pelayanan-meta-item">
                                        <i class="far fa-clock text-primary"></i>
                                        <span>Waktu: <strong><?= $layanan['waktu']; ?></strong></span>
                                    </div>
                                    <div class="pelayanan-meta-item">
                                        <i class="fas fa-tag text-success"></i>
                                        <span>Biaya: <strong><?= $layanan['biaya']; ?></strong></span>
                                    </div>
                                </div>

                                <div class="pelayanan-syarat-box">
                                    <div class="pelayanan-syarat-title">
                                        <i class="fas fa-clipboard-check text-success"></i> Persyaratan Dokumen:
                                    </div>
                                    <ul class="pelayanan-syarat-list">
                                        <?php foreach ($layanan['syarat'] as $syarat): ?>
                                            <li><?= $syarat; ?></li>
                                        <?php endforeach; ?>
                                    </ul>

                                    <div style="background: #f1f5f9; border-radius: 8px; padding: 10px 12px; margin-bottom: 14px; font-size: 12px; color: #475569; line-height: 1.5;">
                                        <strong style="color: #0f172a;"><i class="fas fa-route text-primary mr-1"></i> Alur:</strong> <?= $layanan['alur']; ?>
                                    </div>

                                    <?php if ($layanan['id'] === 'pernyataan-ahli-waris'): ?>
                                    <div style="background: #ecfdf5; border-left: 3px solid #059669; border-radius: 6px; padding: 8px 12px; margin-bottom: 12px; font-size: 12px; color: #065f46; line-height: 1.45;">
                                        <i class="fas fa-camera text-success mr-1"></i> <strong>Keterangan:</strong> Layanan surat ahli waris wajib ada terlampir foto ahli waris saat penandatanganan surat.
                                    </div>
                                    <div style="margin-bottom: 10px;">
                                        <a href="<?= base_url('pelayanan/unduh-ahli-waris'); ?>" target="_blank" rel="noopener noreferrer" class="btn-waris-download" onclick="trackWarisDownload(event)" style="width: 100%; justify-content: center; box-sizing: border-box;" title="Unduh berkas persyaratan resmi di Google Drive">
                                            <i class="fab fa-google-drive"></i> Unduh Berkas Ahli Waris &bull; <span class="waris-hits-sync"><?= number_format((int) ($ahli_waris_download['hits'] ?? 0)); ?></span>x Diunduh
                                        </a>
                                    </div>
                                    <?php elseif ($layanan['id'] === 'penyerahan-hak-waris'): ?>
                                    <div style="margin-bottom: 10px;">
                                        <a href="<?= base_url('pelayanan/unduh-penyerahan-waris'); ?>" target="_blank" rel="noopener noreferrer" class="btn-waris-download" onclick="trackPenyerahanDownload(event)" style="width: 100%; justify-content: center; box-sizing: border-box;" title="Unduh berkas persyaratan resmi di Google Drive">
                                            <i class="fab fa-google-drive"></i> Unduh Berkas Penyerahan Waris &bull; <span class="penyerahan-hits-sync"><?= number_format((int) ($penyerahan_waris_download['hits'] ?? 0)); ?></span>x Diunduh
                                        </a>
                                    </div>
                                    <?php elseif ($layanan['id'] === 'surat-kuasa-waris'): ?>
                                    <div style="margin-bottom: 10px;">
                                        <a href="<?= base_url('pelayanan/unduh-surat-kuasa'); ?>" target="_blank" rel="noopener noreferrer" class="btn-waris-download" onclick="trackSuratKuasaDownload(event)" style="width: 100%; justify-content: center; box-sizing: border-box; background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);" title="Unduh berkas persyaratan resmi di Google Drive">
                                            <i class="fab fa-google-drive"></i> Unduh Berkas Surat Kuasa &bull; <span class="surat-kuasa-hits-sync"><?= number_format((int) ($surat_kuasa_download['hits'] ?? 0)); ?></span>x Diunduh
                                        </a>
                                    </div>
                                    <?php elseif ($layanan['id'] === 'hibah-tanah-bangunan'): ?>
                                    <div style="margin-bottom: 10px;">
                                        <a href="<?= base_url('pelayanan/unduh-surat-hibah'); ?>" target="_blank" rel="noopener noreferrer" class="btn-waris-download" onclick="trackSuratHibahDownload(event)" style="width: 100%; justify-content: center; box-sizing: border-box; background: linear-gradient(135deg, #b45309 0%, #92400e 100%);" title="Unduh berkas persyaratan resmi di Google Drive">
                                            <i class="fab fa-google-drive"></i> Unduh Berkas Surat Hibah &bull; <span class="surat-hibah-hits-sync"><?= number_format((int) ($surat_hibah_download['hits'] ?? 0)); ?></span>x Diunduh
                                        </a>
                                    </div>
                                    <?php elseif ($layanan['id'] === 'surat-pindah'): ?>
                                    <div style="margin-bottom: 10px;">
                                        <a href="<?= base_url('pelayanan/unduh-surat-pindah'); ?>" target="_blank" rel="noopener noreferrer" class="btn-waris-download" onclick="trackSuratPindahDownload(event)" style="width: 100%; justify-content: center; box-sizing: border-box; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);" title="Unduh berkas persyaratan resmi di Google Drive">
                                            <i class="fab fa-google-drive"></i> Unduh Berkas Surat Pindah &bull; <span class="surat-pindah-hits-sync"><?= number_format((int) ($surat_pindah_download['hits'] ?? 0)); ?></span>x Diunduh
                                        </a>
                                    </div>
                                    <?php elseif ($layanan['id'] === 'nikah'): ?>
                                    <div style="margin-bottom: 10px;">
                                        <a href="<?= base_url('pelayanan/unduh-dispensasi-nikah'); ?>" target="_blank" rel="noopener noreferrer" class="btn-waris-download" onclick="trackDispensasiNikahDownload(event)" style="width: 100%; justify-content: center; box-sizing: border-box; background: linear-gradient(135deg, #db2777 0%, #be185d 100%);" title="Unduh berkas persyaratan resmi di Google Drive">
                                            <i class="fab fa-google-drive"></i> Unduh Berkas Dispensasi Nikah &bull; <span class="dispensasi-nikah-hits-sync"><?= number_format((int) ($dispensasi_nikah_download['hits'] ?? 0)); ?></span>x Diunduh
                                        </a>
                                    </div>
                                    <?php endif; ?>

                                    <button type="button" class="btn-detail-layanan" onclick="konsultasiLayanan('<?= addslashes($layanan['judul']); ?>')">
                                        <i class="fab fa-whatsapp"></i> Admin Siap 1 Pintu
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- No Results State -->
                        <div id="noLayananResults" style="display: none; background: #ffffff; border-radius: 16px; border: 1px dashed #cbd5e1; padding: 40px; text-align: center; margin-bottom: 30px;">
                            <i class="fas fa-search" style="font-size: 40px; color: #94a3b8; margin-bottom: 12px;"></i>
                            <h4 style="color: #1e293b; margin: 0 0 6px 0;">Layanan Tidak Ditemukan</h4>
                            <p style="color: #64748b; font-size: 13.5px; margin: 0;">Silakan periksa kata kunci Anda atau hubungi loket pelayanan kantor Camat Siantar Barat.</p>
                        </div>

                        <!-- 5 Steps SOP Flowchart -->
                        <div class="procedure-flow-card">
                            <h3 class="procedure-flow-title">
                                <i class="fas fa-project-diagram text-success"></i> Standar Alur Pelayanan Terpadu (SOP Siap1Pintu)
                            </h3>
                            <p class="procedure-flow-subtitle">
                                5 tahapan mudah pengurusan berkas administrasi warga di Kantor Camat Siantar Barat
                            </p>

                            <div class="procedure-steps-grid">
                                <div class="step-card">
                                    <div class="step-number">1</div>
                                    <h4 class="step-title">Surat Pengantar</h4>
                                    <p class="step-desc">Minta surat pengantar dari RT/RW dan kantor kelurahan setempat.</p>
                                </div>
                                <div class="step-card">
                                    <div class="step-number">2</div>
                                    <h4 class="step-title">Loket Siap1Pintu</h4>
                                    <p class="step-desc">Serahkan berkas ke loket pelayanan terpadu Kantor Camat Siantar Barat.</p>
                                </div>
                                <div class="step-card">
                                    <div class="step-number">3</div>
                                    <h4 class="step-title">Verifikasi Berkas</h4>
                                    <p class="step-desc">Petugas memeriksa kelengkapan dan keabsahan berkas (10-15 menit).</p>
                                </div>
                                <div class="step-card">
                                    <div class="step-number">4</div>
                                    <h4 class="step-title">Penandatanganan</h4>
                                    <p class="step-desc">Dokumen diverifikasi & ditandatangani Camat atau Sekcam.</p>
                                </div>
                                <div class="step-card">
                                    <div class="step-number">5</div>
                                    <h4 class="step-title">Dokumen Selesai</h4>
                                    <p class="step-desc">Dokumen diserahkan kepada pemohon secara resmi dan 100% GRATIS.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Directory of 8 Urban Villages (Kelurahan) -->
                        <div class="kelurahan-section" id="kelurahan">
                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                                <div>
                                    <h3 style="font-size: 20px; font-weight: 800; color: #1e293b; margin: 0 0 4px 0; display: flex; align-items: center; gap: 8px;">
                                        <i class="fas fa-sitemap text-primary"></i> 8 Kantor Kelurahan Se-Kecamatan Siantar Barat
                                    </h3>
                                    <p style="font-size: 13.5px; color: #64748b; margin: 0;">
                                        Titik awal pengurusan surat pengantar sebelum diteruskan ke Kantor Camat
                                    </p>
                                </div>
                                <span style="background: #e0f2fe; color: #0284c7; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;">
                                    Siantar Barat Terpadu
                                </span>
                            </div>

                            <div class="kelurahan-grid">
                                <?php foreach (($kelurahan_list ?? []) as $index => $kel): ?>
                                <div class="kelurahan-card">
                                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px;">
                                        <h4 class="kelurahan-name" style="margin: 0;">
                                            <i class="fas fa-building"></i> <?= $kel['nama']; ?>
                                        </h4>
                                        <span style="background: #e2e8f0; color: #334155; font-size: 11px; font-weight: 700; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;" title="Kelurahan ke-<?= $kel['no'] ?? ($index + 1); ?>">
                                            <?= $kel['no'] ?? ($index + 1); ?>
                                        </span>
                                    </div>
                                    <div class="kelurahan-addr">
                                        <i class="fas fa-map-marker-alt text-danger mr-1"></i> <?= $kel['alamat']; ?>
                                    </div>
                                    <div style="font-size: 11px; color: #94a3b8; margin-top: 6px;">
                                        <?= $kel['wilayah']; ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Maklumat Pelayanan & Bebas Pungli -->
                        <div class="maklumat-box">
                            <h3><i class="fas fa-award text-warning"></i> Maklumat Pelayanan Publik Kecamatan Siantar Barat</h3>
                            <p>
                                "Dengan ini kami menyatakan sanggup menyelenggarakan pelayanan administrasi terpadu kecamatan sesuai standar pelayanan yang telah ditetapkan, dan apabila tidak menepati janji ini, kami siap menerima sanksi sesuai dengan peraturan perundang-undangan yang berlaku."
                            </p>
                            <div class="maklumat-points">
                                <div class="maklumat-point-item">
                                    <strong><i class="fas fa-hand-holding-usd mr-1"></i> Nol Rupiah (Bebas Pungli)</strong>
                                    <span>Seluruh layanan administrasi kecamatan & kelurahan tidak dipungut biaya apapun.</span>
                                </div>
                                <div class="maklumat-point-item">
                                    <strong><i class="fas fa-user-shield mr-1"></i> Kepastian Waktu</strong>
                                    <span>Pelayanan diselesaikan tepat waktu sesuai Standar Pelayanan Minimal (SOP).</span>
                                </div>
                                <div class="maklumat-point-item">
                                    <strong><i class="fas fa-headset mr-1"></i> Kanal Pengaduan 24 Jam</strong>
                                    <span>Layanan pengaduan masyarakat aktif via telepon, email, dan portal SP4N LAPOR.</span>
                                </div>
                            </div>
                        </div>

                    </div> <!-- /.news3col__col utama -->

                    <!-- ==================== KOLOM KIRI (SIDEBAR MENU & POPULER) ==================== -->
                    <?php echo view(template() . '/widget/berita-popular'); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // 1. Filter Kategori Tab
    const tabButtons = document.querySelectorAll('.pelayanan-tab-btn[data-filter]');
    const cards = document.querySelectorAll('.pelayanan-card');
    const noResults = document.getElementById('noLayananResults');
    const searchInput = document.getElementById('searchLayanan');

    function filterCards() {
        const activeTab = document.querySelector('.pelayanan-tab-btn.active');
        const filterVal = activeTab ? activeTab.getAttribute('data-filter') : 'all';
        const searchVal = searchInput ? searchInput.value.toLowerCase().trim() : '';

        let visibleCount = 0;

        cards.forEach(card => {
            const cardCategory = card.getAttribute('data-category');
            const cardTitle = card.getAttribute('data-title') || '';
            const cardDesc = card.getAttribute('data-desc') || '';
            const cardSyarat = card.getAttribute('data-syarat') || '';

            const matchesCategory = (filterVal === 'all' || cardCategory === filterVal);
            const matchesSearch = !searchVal || cardTitle.includes(searchVal) || cardDesc.includes(searchVal) || cardSyarat.includes(searchVal);

            if (matchesCategory && matchesSearch) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        if (noResults) {
            noResults.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }

    tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            tabButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            filterCards();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            filterCards();
        });
    }

    // Check URL hash on load (e.g. #kelas-waris)
    if (window.location.hash === '#kelas-waris' || window.location.hash === '#waris') {
        const warisBtn = document.querySelector('.pelayanan-tab-btn[data-filter="ahli-waris"]');
        if (warisBtn) {
            tabButtons.forEach(b => b.classList.remove('active'));
            warisBtn.classList.add('active');
            filterCards();
            const el = document.getElementById('kelas-waris');
            if (el) {
                el.scrollIntoView({ behavior: 'smooth' });
            }
        }
    }
});

// Google Drive Ahli Waris Download Tracker
function trackWarisDownload(e) {
    const hitElements = document.querySelectorAll('#warisDownloadHitsCount, .waris-hits-sync');
    let currentCount = parseInt(hitElements[0]?.innerText?.replace(/,/g, '') || '0', 10);
    currentCount++;
    hitElements.forEach(el => {
        el.innerText = currentCount.toLocaleString('id-ID');
    });

    try {
        fetch('<?= base_url('pelayanan/unduh-ahli-waris?ajax=1'); ?>', {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(res => res.json()).then(data => {
            if (data && data.hits) {
                hitElements.forEach(el => {
                    el.innerText = parseInt(data.hits, 10).toLocaleString('id-ID');
                });
            }
        }).catch(() => {});
    } catch (err) {}
}

// Google Drive Penyerahan Hak Waris Download Tracker
function trackPenyerahanDownload(e) {
    const hitElements = document.querySelectorAll('#penyerahanDownloadHitsCount, .penyerahan-hits-sync');
    let currentCount = parseInt(hitElements[0]?.innerText?.replace(/,/g, '') || '0', 10);
    currentCount++;
    hitElements.forEach(el => {
        el.innerText = currentCount.toLocaleString('id-ID');
    });

    try {
        fetch('<?= base_url('pelayanan/unduh-penyerahan-waris?ajax=1'); ?>', {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(res => res.json()).then(data => {
            if (data && data.hits) {
                hitElements.forEach(el => {
                    el.innerText = parseInt(data.hits, 10).toLocaleString('id-ID');
                });
            }
        }).catch(() => {});
    } catch (err) {}
}

// Google Drive Surat Kuasa Download Tracker
function trackSuratKuasaDownload(e) {
    const hitElements = document.querySelectorAll('#suratKuasaDownloadHitsCount, .surat-kuasa-hits-sync');
    let currentCount = parseInt(hitElements[0]?.innerText?.replace(/,/g, '') || '0', 10);
    currentCount++;
    hitElements.forEach(el => {
        el.innerText = currentCount.toLocaleString('id-ID');
    });

    try {
        fetch('<?= base_url('pelayanan/unduh-surat-kuasa?ajax=1'); ?>', {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(res => res.json()).then(data => {
            if (data && data.hits) {
                hitElements.forEach(el => {
                    el.innerText = parseInt(data.hits, 10).toLocaleString('id-ID');
                });
            }
        }).catch(() => {});
    } catch (err) {}
}

// Google Drive Surat Hibah Tanah dan Bangunan Download Tracker
function trackSuratHibahDownload(e) {
    const hitElements = document.querySelectorAll('#suratHibahDownloadHitsCount, .surat-hibah-hits-sync');
    let currentCount = parseInt(hitElements[0]?.innerText?.replace(/,/g, '') || '0', 10);
    currentCount++;
    hitElements.forEach(el => {
        el.innerText = currentCount.toLocaleString('id-ID');
    });

    try {
        fetch('<?= base_url('pelayanan/unduh-surat-hibah?ajax=1'); ?>', {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(res => res.json()).then(data => {
            if (data && data.hits) {
                hitElements.forEach(el => {
                    el.innerText = parseInt(data.hits, 10).toLocaleString('id-ID');
                });
            }
        }).catch(() => {});
    } catch (err) {}
}

// Google Drive Surat Keterangan Pindah Download Tracker
function trackSuratPindahDownload(e) {
    const hitElements = document.querySelectorAll('.surat-pindah-hits-sync');
    let currentCount = parseInt(hitElements[0]?.innerText?.replace(/,/g, '') || '0', 10);
    currentCount++;
    hitElements.forEach(el => {
        el.innerText = currentCount.toLocaleString('id-ID');
    });

    try {
        fetch('<?= base_url('pelayanan/unduh-surat-pindah?ajax=1'); ?>', {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(res => res.json()).then(data => {
            if (data && data.hits) {
                hitElements.forEach(el => {
                    el.innerText = parseInt(data.hits, 10).toLocaleString('id-ID');
                });
            }
        }).catch(() => {});
    } catch (err) {}
}

// Google Drive Surat Dispensasi Nikah Download Tracker
function trackDispensasiNikahDownload(e) {
    const hitElements = document.querySelectorAll('.dispensasi-nikah-hits-sync');
    let currentCount = parseInt(hitElements[0]?.innerText?.replace(/,/g, '') || '0', 10);
    currentCount++;
    hitElements.forEach(el => {
        el.innerText = currentCount.toLocaleString('id-ID');
    });

    try {
        fetch('<?= base_url('pelayanan/unduh-dispensasi-nikah?ajax=1'); ?>', {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(res => res.json()).then(data => {
            if (data && data.hits) {
                hitElements.forEach(el => {
                    el.innerText = parseInt(data.hits, 10).toLocaleString('id-ID');
                });
            }
        }).catch(() => {});
    } catch (err) {}
}

// WhatsApp Quick Consultation
function konsultasiLayanan(namaLayanan) {
    const text = encodeURIComponent(`Halo Admin Siap 1 Pintu Kecamatan Siantar Barat, saya ingin berkonsultasi mengenai persyaratan: *${namaLayanan}*. Mohon informasinya.`);
    window.open(`https://api.whatsapp.com/send?phone=6285195563739&text=${text}`, '_blank');
}
</script>

