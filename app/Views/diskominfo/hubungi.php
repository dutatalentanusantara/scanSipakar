<style>
.contact-container {
    background: #ffffff;
    border-radius: 16px;
    padding: 28px;
    box-shadow: 0 4px 25px rgba(0,0,0,0.06);
    margin-top: 15px;
    border: 1px solid #eef2f6;
}

/* ==================== MAP SECTION STYLING ==================== */
.map-master-box {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 28px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

.map-control-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 12px 18px;
    flex-wrap: wrap;
    gap: 10px;
}

.map-tab-group {
    display: inline-flex;
    background: #e2e8f0;
    padding: 3px;
    border-radius: 10px;
    gap: 4px;
}

.map-tab-btn {
    border: none;
    background: transparent;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 700;
    color: #475569;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.map-tab-btn.active {
    background: #ffffff;
    color: #0284c7;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

.map-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 20px;
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #a7f3d0;
}

.map-status-badge.offline-mode {
    background: #fef3c7;
    color: #92400e;
    border-color: #fde68a;
}

.contact-map-wrap {
    width: 100%;
    height: 400px;
    position: relative;
    background: #f1f5f9;
    overflow: hidden;
}

.contact-map-wrap iframe {
    width: 100%;
    height: 100%;
    border: 0;
    display: block;
}

/* Offline Vector Map Styling */
.offline-vector-map {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    user-select: none;
    background: #f8fafc;
}

.offline-vector-map svg {
    width: 100%;
    height: 100%;
    display: block;
}

/* Pulsing GPS Pin */
@keyframes pinPulse {
    0% { transform: scale(0.95); opacity: 0.9; }
    50% { transform: scale(1.35); opacity: 0.3; }
    100% { transform: scale(0.95); opacity: 0.9; }
}

.gps-pulse-ring {
    animation: pinPulse 2s infinite ease-in-out;
    transform-origin: center;
}

/* Action Bar below map */
.map-actions-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 18px;
    background: #ffffff;
    border-top: 1px solid #e2e8f0;
    flex-wrap: wrap;
    gap: 10px;
}

.map-action-buttons {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-map-action {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 14px;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 600;
    text-decoration: none !important;
    transition: all 0.2s ease;
    cursor: pointer;
    border: 1px solid transparent;
}

.btn-share-google {
    background: #0284c7;
    color: #ffffff !important;
    box-shadow: 0 2px 8px rgba(2, 132, 199, 0.25);
}
.btn-share-google:hover {
    background: #0369a1;
    transform: translateY(-1px);
}

.btn-directions {
    background: #f1f5f9;
    color: #334155 !important;
    border-color: #cbd5e1;
}
.btn-directions:hover {
    background: #e2e8f0;
    color: #0f172a !important;
}

.btn-copy-coord {
    background: #f8fafc;
    color: #475569;
    border-color: #cbd5e1;
}
.btn-copy-coord:hover {
    background: #e0f2fe;
    color: #0284c7;
    border-color: #7dd3fc;
}

/* ==================== CONTACT INFO CARD ==================== */
.contact-info-card {
    background: linear-gradient(135deg, #f8fafc 0%, #edf2f7 100%);
    border-radius: 14px;
    padding: 24px;
    border-left: 5px solid #0284c7;
    margin-bottom: 25px;
}
.contact-info-item {
    display: flex;
    align-items: flex-start;
    margin-bottom: 16px;
}
.contact-info-item:last-child {
    margin-bottom: 0;
}
.contact-info-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    background: #e0f2fe;
    color: #0284c7;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    margin-right: 14px;
    flex-shrink: 0;
}
.contact-info-text h5 {
    margin: 0 0 3px 0;
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
}
.contact-info-text p {
    margin: 0;
    font-size: 14px;
    color: #475569;
    line-height: 1.5;
}

/* ==================== CONTACT FORM ==================== */
.contact-form-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 25px;
    border: 1px solid #e2e8f0;
}
.contact-form-title {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
}
.contact-form-title i {
    color: #0284c7;
    margin-right: 10px;
}
.form-group-contact {
    margin-bottom: 18px;
}
.form-group-contact label {
    display: block;
    font-weight: 600;
    font-size: 13px;
    color: #334155;
    margin-bottom: 6px;
}
.form-group-contact .form-control {
    width: 100%;
    padding: 11px 15px;
    font-size: 14px;
    border-radius: 10px;
    border: 1.5px solid #cbd5e1;
    background: #f8fafc;
    color: #1e293b;
    transition: all 0.25s ease;
    box-sizing: border-box;
}
.form-group-contact .form-control:focus {
    background: #ffffff;
    border-color: #0284c7;
    box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
    outline: none;
}
.captcha-box-row {
    display: flex;
    align-items: center;
    gap: 15px;
    flex-wrap: wrap;
}
.captcha-badge {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    background: #ffffff;
    padding: 3px;
    display: inline-block;
}
.captcha-badge img {
    display: block;
    height: 38px;
    border-radius: 8px;
}
.btn-submit-contact {
    background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
    color: #ffffff;
    border: none;
    padding: 13px 32px;
    font-size: 15px;
    font-weight: 600;
    border-radius: 10px;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(2, 132, 199, 0.3);
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-submit-contact:hover {
    background: linear-gradient(135deg, #0369a1 0%, #075985 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(2, 132, 199, 0.4);
    color: #ffffff;
}

/* Toast Notification */
#coordToast {
    display: none;
    position: fixed;
    bottom: 24px;
    right: 24px;
    background: #0f172a;
    color: #ffffff;
    padding: 12px 20px;
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    box-shadow: 0 6px 20px rgba(0,0,0,0.25);
    z-index: 99999;
}
</style>

<div class="wrapper">
    <div class="mainwrap">
        <div class="sectionrow">
            <div class="minidashboard">
                <div class="minidashboard__body" style="display: flex; flex-wrap: wrap">
                    
                    <!-- Kolom Kiri: Widget Navigasi & Statistik -->
                    <?php echo view(template() . '/widget/berita-popular'); ?>
                    
                    <!-- Kolom Kanan: Konten Hubungi Kami -->
                    <div class="news3col__col" style="flex: 2; order: 1; width: 100%">
                        <div class="leader">
                            <div class="leader__label">
                                <div class="xlabel"><span>Hubungi Kami</span></div>
                            </div>
                            
                            <div class="contact-container">

                                <!-- Master Box Peta: Offline & Online Google Maps -->
                                <div class="map-master-box">
                                    <!-- Toolbar Kontrol Peta -->
                                    <div class="map-control-bar">
                                        <div class="map-tab-group">
                                            <button type="button" class="map-tab-btn active" id="tabOffline" onclick="switchMapMode('offline')">
                                                <i class="fas fa-map"></i> Peta Offline (Lokal)
                                            </button>
                                            <button type="button" class="map-tab-btn" id="tabOnline" onclick="switchMapMode('online')">
                                                <i class="fas fa-globe-asia"></i> Google Maps (Online)
                                            </button>
                                        </div>

                                        <div>
                                            <span class="map-status-badge" id="mapStatusBadge">
                                                <i class="fas fa-check-circle"></i> Peta Offline Siap Digunakan
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Wadah Peta -->
                                    <div class="contact-map-wrap" id="mapDisplayArea">
                                        
                                        <!-- 1. PETA VEKTOR OFFLINE (Bisa Diakses 100% Tanpa Internet) -->
                                        <div class="offline-vector-map" id="offlineMapContainer">
                                            <svg viewBox="0 0 900 450" xmlns="http://www.w3.org/2000/svg" style="background: #f1f5f9;">
                                                <defs>
                                                    <!-- Pola Grid Peta -->
                                                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                                                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#e2e8f0" stroke-width="0.8"/>
                                                    </pattern>
                                                    <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
                                                        <feDropShadow dx="0" dy="4" stdDeviation="4" flood-opacity="0.18"/>
                                                    </filter>
                                                </defs>

                                                <!-- Grid Background -->
                                                <rect width="900" height="450" fill="#f8fafc"/>
                                                <rect width="900" height="450" fill="url(#grid)"/>

                                                <!-- Area Hijau / Taman / Lapangan -->
                                                <!-- Lapangan Merdeka (Taman Bunga) -->
                                                <rect x="620" y="160" width="130" height="110" rx="14" fill="#dcfce7" stroke="#86efac" stroke-width="1.5"/>
                                                <text x="685" y="220" font-family="Arial, sans-serif" font-size="11" font-weight="700" fill="#15803d" text-anchor="middle">Lapangan Merdeka</text>
                                                <text x="685" y="235" font-family="Arial, sans-serif" font-size="9" fill="#166534" text-anchor="middle">(Taman Bunga Siantar)</text>

                                                <!-- Ruang Terbuka Hijau Sipinggol-Pinggol -->
                                                <rect x="180" y="60" width="160" height="90" rx="12" fill="#e2f7ec" stroke="#bbf7d0" stroke-width="1.2"/>
                                                <text x="260" y="110" font-family="Arial, sans-serif" font-size="10" font-weight="600" fill="#166534" text-anchor="middle">Kawasan Sipinggol-Pinggol</text>

                                                <!-- Sungai Bah Bolon (Aliran Biru) -->
                                                <path d="M 0,380 C 220,360 400,430 600,370 C 750,330 820,380 900,350" fill="none" stroke="#bae6fd" stroke-width="26" stroke-linecap="round"/>
                                                <path d="M 0,380 C 220,360 400,430 600,370 C 750,330 820,380 900,350" fill="none" stroke="#7dd3fc" stroke-width="6" stroke-dasharray="8,6"/>
                                                <text x="740" y="380" font-family="Arial, sans-serif" font-size="10" font-style="italic" fill="#0369a1" transform="rotate(-10 740,380)">Sungai Bah Bolon</text>

                                                <!-- JARINGAN JALAN UTAMA -->
                                                <!-- Jl. Merdeka -->
                                                <line x1="50" y1="180" x2="880" y2="180" stroke="#cbd5e1" stroke-width="18" stroke-linecap="round"/>
                                                <line x1="50" y1="180" x2="880" y2="180" stroke="#ffffff" stroke-width="14" stroke-linecap="round"/>
                                                <text x="790" y="174" font-family="Arial, sans-serif" font-size="11" font-weight="700" fill="#64748b">Jl. Merdeka</text>

                                                <!-- Jl. Sutomo -->
                                                <line x1="50" y1="280" x2="880" y2="280" stroke="#cbd5e1" stroke-width="18" stroke-linecap="round"/>
                                                <line x1="50" y1="280" x2="880" y2="280" stroke="#ffffff" stroke-width="14" stroke-linecap="round"/>
                                                <text x="790" y="274" font-family="Arial, sans-serif" font-size="11" font-weight="700" fill="#64748b">Jl. Sutomo</text>

                                                <!-- Jl. Adam Malik -->
                                                <line x1="580" y1="40" x2="580" y2="420" stroke="#cbd5e1" stroke-width="16" stroke-linecap="round"/>
                                                <line x1="580" y1="40" x2="580" y2="420" stroke="#ffffff" stroke-width="12" stroke-linecap="round"/>
                                                <text x="592" y="90" font-family="Arial, sans-serif" font-size="10" font-weight="700" fill="#64748b">Jl. Adam Malik</text>

                                                <!-- Jl. Kartini -->
                                                <line x1="380" y1="40" x2="380" y2="420" stroke="#cbd5e1" stroke-width="16" stroke-linecap="round"/>
                                                <line x1="380" y1="40" x2="380" y2="420" stroke="#ffffff" stroke-width="12" stroke-linecap="round"/>
                                                <text x="392" y="90" font-family="Arial, sans-serif" font-size="10" font-weight="700" fill="#64748b">Jl. Kartini</text>

                                                <!-- Jl. Diponegoro -->
                                                <line x1="770" y1="40" x2="770" y2="420" stroke="#cbd5e1" stroke-width="14" stroke-linecap="round"/>
                                                <line x1="770" y1="40" x2="770" y2="420" stroke="#ffffff" stroke-width="10" stroke-linecap="round"/>
                                                <text x="782" y="90" font-family="Arial, sans-serif" font-size="10" font-weight="700" fill="#64748b">Jl. Diponegoro</text>

                                                <!-- JL. BANGAU (Jalan Lokasi Kantor Camat Siantar Barat - Sorotan Khusus) -->
                                                <line x1="160" y1="280" x2="420" y2="130" stroke="#93c5fd" stroke-width="18" stroke-linecap="round"/>
                                                <line x1="160" y1="280" x2="420" y2="130" stroke="#0284c7" stroke-width="12" stroke-linecap="round"/>
                                                <text x="245" y="195" font-family="Arial, sans-serif" font-size="11" font-weight="800" fill="#ffffff" transform="rotate(-30 245,195)">JL. BANGAU</text>

                                                <!-- Landmark: Kantor Walikota Pematangsiantar -->
                                                <g transform="translate(685, 95)" filter="url(#shadow)">
                                                    <rect x="-60" y="-18" width="120" height="34" rx="8" fill="#ffffff" stroke="#94a3b8" stroke-width="1"/>
                                                    <text x="0" y="3" font-family="Arial, sans-serif" font-size="10" font-weight="700" fill="#334155" text-anchor="middle">🏛️ Balai Kota Siantar</text>
                                                </g>

                                                <!-- Landmark: Pasar Horas -->
                                                <g transform="translate(770, 310)" filter="url(#shadow)">
                                                    <rect x="-50" y="-15" width="100" height="30" rx="8" fill="#ffffff" stroke="#94a3b8" stroke-width="1"/>
                                                    <text x="0" y="4" font-family="Arial, sans-serif" font-size="10" font-weight="700" fill="#334155" text-anchor="middle">🏪 Pasar Horas</text>
                                                </g>

                                                <!-- TITIK LOKASI UTAMA: KANTOR CAMAT SIANTAR BARAT -->
                                                <!-- Pulsing Ring Efek GPS -->
                                                <circle cx="280" cy="210" r="38" fill="rgba(2, 132, 199, 0.22)" class="gps-pulse-ring"/>
                                                <circle cx="280" cy="210" r="24" fill="rgba(2, 132, 199, 0.35)"/>
                                                <circle cx="280" cy="210" r="10" fill="#0284c7" stroke="#ffffff" stroke-width="3"/>

                                                <!-- Pin Marker Ikon -->
                                                <g transform="translate(280, 210)" filter="url(#shadow)">
                                                    <!-- Pin Shape -->
                                                    <path d="M 0,0 C -14,-22 -18,-35 0,-52 C 18,-35 14,-22 0,0 Z" fill="#dc2626" stroke="#ffffff" stroke-width="2"/>
                                                    <circle cx="0" cy="-35" r="7" fill="#ffffff"/>
                                                    <circle cx="0" cy="-35" r="3.5" fill="#dc2626"/>
                                                </g>

                                                <!-- Floating Card Tooltip Kantor Camat -->
                                                <g transform="translate(140, 110)" filter="url(#shadow)">
                                                    <rect x="0" y="0" width="280" height="85" rx="12" fill="#0f2b5c" stroke="#38bdf8" stroke-width="2"/>
                                                    <!-- Segitiga pointer -->
                                                    <polygon points="140,85 130,96 150,85" fill="#0f2b5c"/>
                                                    
                                                    <text x="18" y="24" font-family="Arial, sans-serif" font-size="13" font-weight="800" fill="#ffffff">🏢 KANTOR CAMAT SIANTAR BARAT</text>
                                                    <text x="18" y="42" font-family="Arial, sans-serif" font-size="11" fill="#cbd5e1">Jl. Bangau No.3, Sipinggol-Pinggol</text>
                                                    <text x="18" y="58" font-family="Arial, sans-serif" font-size="10.5" fill="#93c5fd">Kec. Siantar Barat, Kota Pematang Siantar</text>
                                                    
                                                    <rect x="18" y="64" width="170" height="15" rx="4" fill="rgba(255,255,255,0.15)"/>
                                                    <text x="24" y="75" font-family="Courier, monospace" font-size="9.5" font-weight="700" fill="#facc15">📍 2.971510° N, 99.056024° E</text>
                                                </g>

                                                <!-- Kompas / Arah Mata Angin -->
                                                <g transform="translate(845, 55)">
                                                    <circle cx="0" cy="0" r="22" fill="#ffffff" stroke="#cbd5e1" stroke-width="1.5" filter="url(#shadow)"/>
                                                    <polygon points="0,-16 6,-2 0,0" fill="#dc2626"/>
                                                    <polygon points="0,16 6,2 0,0" fill="#64748b"/>
                                                    <polygon points="0,-16 -6,-2 0,0" fill="#b91c1c"/>
                                                    <polygon points="0,16 -6,2 0,0" fill="#94a3b8"/>
                                                    <text x="0" y="-18" font-family="Arial, sans-serif" font-size="10" font-weight="800" fill="#dc2626" text-anchor="middle">U</text>
                                                    <text x="0" y="27" font-family="Arial, sans-serif" font-size="8" font-weight="700" fill="#64748b" text-anchor="middle">S</text>
                                                </g>

                                                <!-- Watermark Info Peta Offline -->
                                                <g transform="translate(18, 430)">
                                                    <rect x="0" y="-16" width="280" height="22" rx="6" fill="rgba(255,255,255,0.85)" stroke="#e2e8f0"/>
                                                    <text x="8" y="-2" font-family="Arial, sans-serif" font-size="10" font-weight="600" fill="#475569">
                                                        🗺️ Peta Navigasi Vektor Offline Siantar Barat
                                                    </text>
                                                </g>
                                            </svg>
                                        </div>

                                        <!-- 2. GOOGLE MAPS EMBED ONLINE -->
                                        <div id="onlineMapContainer" style="display: none; width: 100%; height: 100%;">
                                            <iframe 
                                                id="googleMapsIframe"
                                                src="<?php echo $maps_embed_url ?? $iden['maps'] ?? 'https://maps.google.com/maps?q=Kantor+Camat+Siantar+Barat,+Pematangsiantar&t=&z=16&ie=UTF8&iwloc=&output=embed'; ?>" 
                                                allowfullscreen="" 
                                                loading="lazy" 
                                                referrerpolicy="no-referrer-when-downgrade"
                                                title="Google Maps Kantor Camat Siantar Barat">
                                            </iframe>
                                        </div>

                                    </div>

                                    <!-- Action Bar: Tautan Share Google Maps Resmi & Utilitas -->
                                    <div class="map-actions-bar">
                                        <div style="font-size: 12.5px; color: #475569;">
                                            <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                                            <strong>Titik Lokasi:</strong> Jl. Bangau No.3 (2.971510, 99.056024)
                                        </div>

                                        <div class="map-action-buttons">
                                            <!-- Tombol Share Google Maps Link Resmi -->
                                            <a href="<?php echo $maps_share_link ?? 'https://share.google/LQuiIL9dS6RBLUf2i'; ?>" target="_blank" rel="noopener noreferrer" class="btn-map-action btn-share-google" title="Buka tautan lokasi resmi di Google Maps">
                                                <i class="fas fa-external-link-alt"></i> Buka Google Maps
                                            </a>

                                            <!-- Tombol Petunjuk Arah / Rute -->
                                            <a href="<?php echo $maps_share_link ?? 'https://share.google/LQuiIL9dS6RBLUf2i'; ?>" target="_blank" rel="noopener noreferrer" class="btn-map-action btn-directions" title="Navigasi rute perjalanan ke Kantor Camat">
                                                <i class="fas fa-directions text-primary"></i> Petunjuk Arah
                                            </a>

                                            <!-- Tombol Salin Koordinat -->
                                            <button type="button" class="btn-map-action btn-copy-coord" onclick="salinKoordinat('2.971510, 99.056024')">
                                                <i class="fas fa-copy"></i> <span id="labelCopy">Salin Koordinat</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informasi Kantor -->
                                <div class="contact-info-card">
                                    <h4 style="margin: 0 0 16px 0; font-size: 17px; font-weight: 700; color: #0f172a;">
                                        <i class="fas fa-landmark text-primary mr-2"></i> Kantor Camat Siantar Barat Kota Pematangsiantar
                                    </h4>
                                    
                                    <div class="contact-info-item">
                                        <div class="contact-info-icon"><i class="fas fa-map-marker-alt"></i></div>
                                        <div class="contact-info-text">
                                            <h5>Alamat Kantor</h5>
                                            <p>Jl. Bangau No.3, Sipinggol-Pinggol, Kec. Siantar Barat, Kota Pematang Siantar, Sumatera Utara 21114</p>
                                        </div>
                                    </div>

                                    <div class="contact-info-item">
                                        <div class="contact-info-icon"><i class="fas fa-phone-alt"></i></div>
                                        <div class="contact-info-text">
                                            <h5>Telepon Layanan</h5>
                                            <p>(0622) 123456</p>
                                        </div>
                                    </div>

                                    <div class="contact-info-item">
                                        <div class="contact-info-icon"><i class="fas fa-envelope"></i></div>
                                        <div class="contact-info-text">
                                            <h5>Email Resmi</h5>
                                            <p>diskominfo@pematangsiantar.go.id</p>
                                        </div>
                                    </div>

                                    <div class="contact-info-item">
                                        <div class="contact-info-icon"><i class="fas fa-clock"></i></div>
                                        <div class="contact-info-text">
                                            <h5>Jam Operasional Pelayanan</h5>
                                            <p>Senin - Jumat: 08.00 - 16.00 WIB (Sabtu, Minggu & Hari Libur Nasional Tutup)</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Formulir Aspirasi & Pesan -->
                                <div class="contact-form-card">
                                    <div class="contact-form-title">
                                        <i class="fas fa-envelope-open-text"></i> Kirim Pesan / Aspirasi Masyarakat
                                    </div>

                                    <form action="<?php echo base_url('hubungi/kirim'); ?>" method="POST" id="form_komentar">
                                        <!-- CSRF Token Form Protection -->
                                        <?= csrf_field(); ?>

                                        <div class="form-group-contact">
                                            <label for="contact_nama">Nama Lengkap <span style="color: #ef4444;">*</span></label>
                                            <input type="text" id="contact_nama" name="a" class="form-control" placeholder="Contoh: Budi Santoso" required />
                                        </div>

                                        <div class="form-group-contact">
                                            <label for="contact_email">Alamat Email <span style="color: #ef4444;">*</span></label>
                                            <input type="email" id="contact_email" name="b" class="form-control" placeholder="nama@email.com" required />
                                        </div>

                                        <div class="form-group-contact">
                                            <label for="contact_subjek">Subjek / Topik</label>
                                            <input type="text" id="contact_subjek" name="subjek" class="form-control" placeholder="Contoh: Layanan Kependudukan, Sanitasi Lingkungan, dll" />
                                        </div>

                                        <div class="form-group-contact">
                                            <label for="contact_pesan">Isi Pesan / Pertanyaan <span style="color: #ef4444;">*</span></label>
                                            <textarea id="contact_pesan" name="c" class="form-control" rows="5" placeholder="Tuliskan pesan, pertanyaan, atau aspirasi Anda dengan jelas..." required></textarea>
                                        </div>

                                        <div class="form-group-contact">
                                            <label>Kode Keamanan (CAPTCHA) <span style="color: #ef4444;">*</span></label>
                                            <div class="captcha-box-row">
                                                <div class="captcha-badge">
                                                    <?php echo $image ?? ''; ?>
                                                </div>
                                                <div style="flex: 1; min-width: 180px;">
                                                    <input type="text" name="secutity_code" maxlength="6" class="form-control" placeholder="Ketik kode di sebelah kiri" required />
                                                </div>
                                            </div>
                                        </div>

                                        <div style="margin-top: 25px;">
                                            <button type="submit" name="submit" class="btn-submit-contact">
                                                <i class="fas fa-paper-plane"></i> Kirim Pesan Sekarang
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="coordToast"><i class="fas fa-check-circle text-success mr-1"></i> Koordinat lokasi berhasil disalin ke clipboard!</div>

<script>
// Switcher Map Mode: Offline Vector vs Online Google Maps
function switchMapMode(mode) {
    var offlineMap = document.getElementById('offlineMapContainer');
    var onlineMap = document.getElementById('onlineMapContainer');
    var tabOffline = document.getElementById('tabOffline');
    var tabOnline = document.getElementById('tabOnline');
    var badge = document.getElementById('mapStatusBadge');

    if (mode === 'online') {
        offlineMap.style.display = 'none';
        onlineMap.style.display = 'block';
        tabOffline.classList.remove('active');
        tabOnline.classList.add('active');
        badge.className = 'map-status-badge';
        badge.innerHTML = '<i class="fas fa-globe"></i> Mode Google Maps Aktif';
    } else {
        onlineMap.style.display = 'none';
        offlineMap.style.display = 'flex';
        tabOnline.classList.remove('active');
        tabOffline.classList.add('active');
        badge.className = 'map-status-badge';
        badge.innerHTML = '<i class="fas fa-check-circle"></i> Peta Offline Siap Digunakan';
    }
}

// Salin Koordinat GPS ke Clipboard
function salinKoordinat(text) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
            showToast();
        });
    } else {
        var input = document.createElement('textarea');
        input.value = text;
        document.body.appendChild(input);
        input.select();
        document.execCommand('copy');
        document.body.removeChild(input);
        showToast();
    }
}

function showToast() {
    var toast = document.getElementById('coordToast');
    var label = document.getElementById('labelCopy');
    label.innerText = 'Tersalin!';
    toast.style.display = 'block';
    setTimeout(function() {
        toast.style.display = 'none';
        label.innerText = 'Salin Koordinat';
    }, 2800);
}

// Auto-deteksi jika browser sedang offline
window.addEventListener('offline', function() {
    switchMapMode('offline');
    var badge = document.getElementById('mapStatusBadge');
    badge.className = 'map-status-badge offline-mode';
    badge.innerHTML = '<i class="fas fa-plane"></i> Terdeteksi Offline - Peta Lokal Aktif';
});

window.addEventListener('online', function() {
    var badge = document.getElementById('mapStatusBadge');
    badge.className = 'map-status-badge';
    badge.innerHTML = '<i class="fas fa-wifi"></i> Online - Peta Siap Digunakan';
});
</script>
