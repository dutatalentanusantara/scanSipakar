<style>
/* Modern SIPAKAR Layout, Form Controls & Responsive System */
.sipakar-form-grid-2 {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}
.sipakar-form-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
}
@media (max-width: 768px) {
    .sipakar-form-grid-2,
    .sipakar-form-grid-3 {
        grid-template-columns: 1fr;
    }
}
.sipakar-form-col {
    min-width: 0;
    width: 100%;
}
.sipakar-label {
    font-size: 13px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 6px;
    display: block;
    line-height: 1.4;
}
.sipakar-input,
.sipakar-select {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    height: 44px !important;
    box-sizing: border-box !important;
    border-radius: 10px !important;
    font-size: 14px !important;
    padding: 10px 14px !important;
    border: 1.5px solid #cbd5e1 !important;
    background-color: #ffffff !important;
    color: #1e293b !important;
    outline: none !important;
    transition: border-color .2s ease, box-shadow .2s ease !important;
    display: block !important;
}
.sipakar-select {
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    cursor: pointer;
}
.sipakar-input:focus,
.sipakar-select:focus {
    border-color: #0284c7 !important;
    box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15) !important;
}
.sipakar-input[readonly] {
    background-color: #f1f5f9 !important;
    cursor: not-allowed !important;
    color: #475569 !important;
    font-weight: 600 !important;
}
.sipakar-hint {
    color: #64748b;
    font-size: 11.5px;
    margin-top: 4px;
    display: block;
    line-height: 1.4;
}
</style>

<div class="wrapper">
    <div class="mainwrap">
        <div class="sectionrow">
            
            <!-- Unified Header Nav -->
            <?php echo view(template() . '/sipakar/nav_header'); ?>

            <div class="sipakar-card">
                <div class="sipakar-card-title">
                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; border-radius: 10px; background: #e0f2fe; color: #0284c7;">
                        <i class="fas fa-file-invoice"></i>
                    </span>
                    <div>
                        <span>Formulir Pengajuan Usulan Layanan Kepegawaian</span>
                        <div style="font-size: 13px; font-weight: normal; color: #64748b;">Lengkapi data diri dan unggah berkas persyaratan sesuai ketentuan</div>
                    </div>
                </div>

                <form action="<?= base_url('sipakar/pengajuan'); ?>" method="POST" enctype="multipart/form-data" id="formSipakar">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="submit_pengajuan" value="1">

                    <?php if (! empty($pegawai_session)): ?>
                    <!-- Employee Session Status Card -->
                    <div style="background: linear-gradient(135deg, #0f2419 0%, #1a5c38 100%); color: #fff; border-radius: 14px; padding: 20px 24px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px; box-shadow: 0 4px 20px rgba(26,92,56,0.18);">
                        <div style="display: flex; align-items: center; gap: 16px;">
                            <div style="width: 52px; height: 52px; border-radius: 12px; background: rgba(255,255,255,0.15); display: flex; align-items: center; justify-content: center; font-size: 24px; color: #4ade80;">
                                <i class="fas fa-id-badge"></i>
                            </div>
                            <div>
                                <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                                    <span style="font-size: 16px; font-weight: 700; color: #fff;"><?= esc($pegawai_session['nama']) ?></span>
                                    <span style="background: rgba(74, 222, 128, 0.2); border: 1px solid rgba(74, 222, 128, 0.4); color: #4ade80; font-size: 11px; font-weight: 700; padding: 2px 8px; border-radius: 12px;">
                                        <i class="fas fa-check-circle me-1"></i>ASN Terverifikasi
                                    </span>
                                </div>
                                <div style="font-size: 13px; color: rgba(255,255,255,0.85); margin-top: 4px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                    <span><i class="fas fa-fingerprint me-1"></i>NIP: <strong><?= esc($pegawai_session['nip']) ?></strong></span>
                                    <span>•</span>
                                    <span><i class="fas fa-briefcase me-1"></i><?= esc($pegawai_session['jabatan'] ?: 'Pegawai') ?></span>
                                    <?php if (! empty($pegawai_session['pangkat_golongan'])): ?>
                                        <span>•</span>
                                        <span style="background: #f59e0b; color: #1e293b; font-size: 11px; font-weight: 700; padding: 1px 7px; border-radius: 6px;">
                                            Gol. <?= esc($pegawai_session['pangkat_golongan']) ?> (<?= esc($pegawai_session['jenis_asn']) ?>)
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <a href="<?= base_url('sipakar/ganti_password') ?>" class="btn btn-sm btn-outline-light" style="border-radius: 8px; font-size: 12px; font-weight: 600; padding: 6px 12px;">
                                <i class="fas fa-key me-1"></i> Ganti Password
                            </a>
                            <a href="<?= base_url('sipakar/logout') ?>" class="btn btn-sm" style="background: rgba(239, 68, 68, 0.25); border: 1px solid rgba(239, 68, 68, 0.5); color: #fca5a5; border-radius: 8px; font-size: 12px; font-weight: 600; padding: 6px 12px;" onclick="return confirm('Apakah Anda yakin ingin keluar dari akun SIPAKAR?');">
                                <i class="fas fa-sign-out-alt me-1"></i> Keluar
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Section 1: Identitas Pegawai ASN -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 22px; margin-bottom: 24px;">
                        <h4 style="font-size: 15.5px; font-weight: 700; color: #0f2b5c; margin: 0 0 18px 0; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-user-check text-primary"></i> 1. Identitas Pegawai ASN
                        </h4>

                        <!-- Row 1: NIP & Nama Lengkap -->
                        <div class="sipakar-form-grid-2" style="margin-bottom: 16px;">
                            <div class="sipakar-form-col">
                                <label class="sipakar-label" for="nip">
                                    NIP (Nomor Induk Pegawai) <span style="color: #ef4444;">*</span>
                                </label>
                                <input type="text" id="nip" name="nip" class="sipakar-input" placeholder="Contoh: 198507152010011012" maxlength="25" value="<?= esc($prefill_nip ?? '') ?>" <?= ! empty($prefill_nip) ? 'readonly' : '' ?> required>
                                <small class="sipakar-hint">Terisi otomatis dari akun pegawai ASN terverifikasi.</small>
                            </div>

                            <div class="sipakar-form-col">
                                <label class="sipakar-label" for="nama_lengkap">
                                    Nama Lengkap Beserta Gelar <span style="color: #ef4444;">*</span>
                                </label>
                                <input type="text" id="nama_lengkap" name="nama_lengkap" class="sipakar-input" placeholder="Contoh: Bambang Hidayat, S.STP, M.Si" value="<?= esc($prefill_nama ?? '') ?>" required>
                                <small class="sipakar-hint">Nama lengkap resmi sesuai SK kepegawaian.</small>
                            </div>
                        </div>

                        <!-- Row 2: Jabatan & Unit Kerja -->
                        <div class="sipakar-form-grid-2" style="margin-bottom: 16px;">
                            <div class="sipakar-form-col">
                                <label class="sipakar-label" for="jabatan">
                                    Jabatan Saat Ini <span style="color: #ef4444;">*</span>
                                </label>
                                <input type="text" id="jabatan" name="jabatan" class="sipakar-input" placeholder="Contoh: Sekretaris Camat / Kasi Pelayanan" value="<?= esc($prefill_jabatan ?? '') ?>" required>
                                <small class="sipakar-hint">Jabatan definitif atau pelaksana yang sedang diemban.</small>
                            </div>

                            <div class="sipakar-form-col">
                                <label class="sipakar-label" for="unit_kerja">
                                    Unit Kerja / Satuan Organisasi <span style="color: #ef4444;">*</span>
                                </label>
                                <?php
                                $unitSelected = $prefill_unit ?? '';
                                $unitList = [
                                    'Kantor Kecamatan Siantar Barat',
                                    'Kelurahan Bantan',
                                    'Kelurahan Banjar',
                                    'Kelurahan Dwikora',
                                    'Kelurahan Proklamasi',
                                    'Kelurahan Simarito',
                                    'Kelurahan Sipinggol-pinggol',
                                    'Kelurahan Teladan',
                                    'Kelurahan Timbang Galung',
                                ];
                                ?>
                                <select id="unit_kerja" name="unit_kerja" class="sipakar-select" required>
                                    <option value="">-- Pilih Unit Kerja --</option>
                                    <?php foreach ($unitList as $u): ?>
                                        <option value="<?= esc($u) ?>" <?= ($unitSelected === $u) ? 'selected' : '' ?>><?= esc($u) ?></option>
                                    <?php endforeach; ?>
                                    <?php if (! empty($unitSelected) && ! in_array($unitSelected, $unitList, true)): ?>
                                        <option value="<?= esc($unitSelected) ?>" selected><?= esc($unitSelected) ?></option>
                                    <?php endif; ?>
                                </select>
                                <small class="sipakar-hint">Instansi penempatan kerja ASN di lingkungan Kecamatan Siantar Barat.</small>
                            </div>
                        </div>

                        <!-- Row 3: Pangkat/Golongan, No HP & Email -->
                        <div class="sipakar-form-grid-3">
                            <div class="sipakar-form-col">
                                <label class="sipakar-label" for="pangkat_golongan">
                                    Pangkat / Golongan Ruang Saat Ini <span style="color: #ef4444;">*</span>
                                </label>
                                <?php
                                $pangkatPrefill = $prefill_pangkat ?? '';
                                $golonganPns = [
                                    'Juru Muda (I/a)', 'Juru Muda Tk. I (I/b)', 'Juru (I/c)', 'Juru Tk. I (I/d)',
                                    'Pengatur Muda (II/a)', 'Pengatur Muda Tk. I (II/b)', 'Pengatur (II/c)', 'Pengatur Tk. I (II/d)',
                                    'Penata Muda (III/a)', 'Penata Muda Tk. I (III/b)', 'Penata (III/c)', 'Penata Tk. I (III/d)',
                                    'Pembina (IV/a)', 'Pembina Tk. I (IV/b)', 'Pembina Utama Muda (IV/c)', 'Pembina Utama Madya (IV/d)', 'Pembina Utama (IV/e)'
                                ];
                                $golonganPppk = [
                                    'Golongan V', 'Golongan VII', 'Golongan IX', 'Golongan X', 'Golongan XI'
                                ];
                                $matchedPangkat = false;
                                ?>
                                <select id="pangkat_golongan" name="pangkat_golongan" class="sipakar-select" required>
                                    <option value="">-- Pilih Pangkat/Golongan --</option>
                                    <optgroup label="PNS (Pegawai Negeri Sipil)">
                                        <?php foreach ($golonganPns as $g): 
                                            $isSel = (! empty($pangkatPrefill) && (stripos($g, '(' . $pangkatPrefill . ')') !== false || stripos($g, $pangkatPrefill) !== false));
                                            if ($isSel) $matchedPangkat = true;
                                        ?>
                                            <option value="<?= esc($g) ?>" <?= $isSel ? 'selected' : '' ?>><?= esc($g) ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <optgroup label="PPPK (Pegawai Pemerintah dengan Perjanjian Kerja)">
                                        <?php foreach ($golonganPppk as $g): 
                                            $romanOnly = str_replace('Golongan ', '', $g);
                                            $isSel = (! empty($pangkatPrefill) && (strcasecmp($pangkatPrefill, $romanOnly) === 0 || strcasecmp($pangkatPrefill, $g) === 0));
                                            if ($isSel) $matchedPangkat = true;
                                        ?>
                                            <option value="<?= esc($g) ?>" <?= $isSel ? 'selected' : '' ?>><?= esc($g) ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                    <?php if (! empty($pangkatPrefill) && ! $matchedPangkat): ?>
                                        <option value="<?= esc($pangkatPrefill) ?>" selected><?= esc($pangkatPrefill) ?></option>
                                    <?php endif; ?>
                                </select>
                            </div>

                            <div class="sipakar-form-col">
                                <label class="sipakar-label" for="no_hp">
                                    Nomor WhatsApp / HP Aktif <span style="color: #ef4444;">*</span>
                                </label>
                                <input type="text" id="no_hp" name="no_hp" class="sipakar-input" placeholder="Contoh: 081260451234" required>
                                <small class="sipakar-hint">Untuk pemberitahuan status verifikasi berkas.</small>
                            </div>

                            <div class="sipakar-form-col">
                                <label class="sipakar-label" for="email">
                                    Alamat Email Aktif <span style="color: #ef4444;">*</span>
                                </label>
                                <input type="email" id="email" name="email" class="sipakar-input" placeholder="Contoh: asn@pematangsiantar.go.id" required>
                                <small class="sipakar-hint">Untuk pengiriman salinan tanda terima berkas.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Jenis Layanan & Rincian Usulan -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 22px; margin-bottom: 24px;">
                        <h4 style="font-size: 15.5px; font-weight: 700; color: #0f2b5c; margin: 0 0 18px 0; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-layer-group text-primary"></i> 2. Jenis Layanan & Data Usulan
                        </h4>

                        <!-- Baris 1: Jenis Layanan & Kategori Usulan -->
                        <div class="sipakar-form-grid-2" style="margin-bottom: 16px;">
                            <div class="sipakar-form-col">
                                <label class="sipakar-label" for="jenis_layanan">
                                    Jenis Layanan Kepegawaian <span style="color: #ef4444;">*</span>
                                </label>
                                <?php $jenis_get = service('request')->getGet('jenis'); ?>
                                <select name="jenis_layanan" id="jenis_layanan" class="sipakar-select" required onchange="updateSubJenis()">
                                    <option value="KGB" <?= ($jenis_get == 'KGB') ? 'selected' : ''; ?>>Kenaikan Gaji Berkala (KGB)</option>
                                    <option value="KP" <?= ($jenis_get == 'KP') ? 'selected' : ''; ?>>Kenaikan Pangkat (KP)</option>
                                </select>
                                <small class="sipakar-hint">Pilih jenis usulan administrasi kepegawaian yang diajukan.</small>
                            </div>

                            <div class="sipakar-form-col">
                                <label class="sipakar-label" for="sub_jenis">
                                    Kategori / Sub-Jenis Usulan <span style="color: #ef4444;">*</span>
                                </label>
                                <select name="sub_jenis" id="sub_jenis" class="sipakar-select" required>
                                    <!-- Populated via JavaScript -->
                                </select>
                                <small class="sipakar-hint">Sesuaikan dengan kriteria jalur usulan pangkat atau berkala.</small>
                            </div>
                        </div>

                        <!-- Baris 2: TMT Terakhir & TMT Usulan Baru -->
                        <div class="sipakar-form-grid-2">
                            <div class="sipakar-form-col">
                                <label class="sipakar-label" for="tmt_terakhir">
                                    TMT (Terhitung Mulai Tanggal) Terakhir <span style="color: #ef4444;">*</span>
                                </label>
                                <input type="date" id="tmt_terakhir" name="tmt_terakhir" class="sipakar-input" required>
                                <small class="sipakar-hint">TMT pada SK Pangkat atau SK KGB yang sedang berlaku saat ini.</small>
                            </div>

                            <div class="sipakar-form-col">
                                <label class="sipakar-label" for="tmt_usulan">
                                    TMT Usulan Baru <span style="color: #ef4444;">*</span>
                                </label>
                                <input type="date" id="tmt_usulan" name="tmt_usulan" class="sipakar-input" required>
                                <small class="sipakar-hint">TMT hak kenaikan pangkat atau kenaikan gaji berkala yang baru.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Upload Dokumen Persyaratan Digital -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 22px; margin-bottom: 24px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; margin-bottom: 8px;">
                            <h4 style="font-size: 15.5px; font-weight: 700; color: #0f2b5c; margin: 0; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-file-upload text-primary"></i> 3. Unggah Berkas Persyaratan Digital
                            </h4>
                            <span style="font-size: 12px; color: #64748b; background: #e2e8f0; padding: 3px 10px; border-radius: 20px; font-weight: 600;">
                                Total: 7 Berkas Persyaratan (4 Wajib, 3 Opsional)
                            </span>
                        </div>
                        <p style="font-size: 13px; color: #64748b; margin: 0 0 18px 0;">
                            Format berkas yang didukung: <strong>PDF, JPG, PNG</strong>. Ukuran maksimum masing-masing berkas adalah <strong>6 MB</strong>. Pastikan pindaian (scan) jelas dan terbaca.
                        </p>

                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(290px, 1fr)); gap: 18px;">
                            
                            <!-- 1. SK Pangkat / KGB Terakhir -->
                            <div style="background: white; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 16px; transition: border-color .2s; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                    <label style="font-size: 13.5px; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-file-contract text-danger"></i> 1. SK Pangkat / KGB Terakhir <span style="color: #ef4444;">*</span>
                                    </label>
                                    <span class="badge" style="background: #fee2e2; color: #b91c1c; font-size: 10.5px; font-weight: 700;">Wajib</span>
                                </div>
                                <input type="file" name="dokumen_sk_terakhir" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png" required style="font-size: 13px; width: 100%; padding: 6px 0;">
                                <small style="display: block; color: #64748b; margin-top: 4px; font-size: 11.5px;">Salinan sah SK Pangkat atau SK KGB terakhir.</small>
                            </div>

                            <!-- 2. SKP 1 Tahun Terakhir -->
                            <div style="background: white; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 16px; transition: border-color .2s; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                    <label style="font-size: 13.5px; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-file-alt text-primary"></i> 2. SKP 1 Tahun Terakhir <span style="color: #ef4444;">*</span>
                                    </label>
                                    <span class="badge" style="background: #fee2e2; color: #b91c1c; font-size: 10.5px; font-weight: 700;">Wajib</span>
                                </div>
                                <input type="file" name="dokumen_skp_1th" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png" required style="font-size: 13px; width: 100%; padding: 6px 0;">
                                <small style="display: block; color: #64748b; margin-top: 4px; font-size: 11.5px;">Sasaran Kinerja Pegawai (SKP) / Penilaian Prestasi Kerja 1 tahun terakhir.</small>
                            </div>

                            <!-- 3. SKP 2 Tahun Terakhir -->
                            <div style="background: white; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 16px; transition: border-color .2s; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                    <label style="font-size: 13.5px; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-file-invoice text-info"></i> 3. SKP 2 Tahun Terakhir <span style="color: #ef4444;">*</span>
                                    </label>
                                    <span class="badge" style="background: #fee2e2; color: #b91c1c; font-size: 10.5px; font-weight: 700;">Wajib</span>
                                </div>
                                <input type="file" name="dokumen_skp_2th" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png" required style="font-size: 13px; width: 100%; padding: 6px 0;">
                                <small style="display: block; color: #64748b; margin-top: 4px; font-size: 11.5px;">Sasaran Kinerja Pegawai (SKP) / Penilaian Prestasi Kerja 2 tahun terakhir.</small>
                            </div>

                            <!-- 4. SK Jabatan Struktural / Pelaksana -->
                            <div style="background: white; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 16px; transition: border-color .2s; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                    <label style="font-size: 13.5px; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-file-signature text-warning"></i> 4. SK Jabatan Struktural / Pelaksana <span style="color: #ef4444;">*</span>
                                    </label>
                                    <span class="badge" style="background: #fee2e2; color: #b91c1c; font-size: 10.5px; font-weight: 700;">Wajib</span>
                                </div>
                                <input type="file" name="dokumen_sk_jabatan" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png" required style="font-size: 13px; width: 100%; padding: 6px 0;">
                                <small style="display: block; color: #64748b; margin-top: 4px; font-size: 11.5px;">Surat Keputusan (SK) Jabatan Struktural atau Pelaksana saat ini.</small>
                            </div>

                            <!-- 5. Daftar Riwayat Jabatan -->
                            <div style="background: white; border: 1.5px dashed #cbd5e1; border-radius: 12px; padding: 16px; transition: border-color .2s;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                    <label style="font-size: 13.5px; font-weight: 700; color: #334155; margin: 0; display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-history text-secondary"></i> 5. Daftar Riwayat Jabatan
                                    </label>
                                    <span class="badge" style="background: #f1f5f9; color: #64748b; font-size: 10.5px;">Opsional</span>
                                </div>
                                <input type="file" name="dokumen_riwayat_jabatan" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png" style="font-size: 13px; width: 100%; padding: 6px 0;">
                                <small style="display: block; color: #64748b; margin-top: 4px; font-size: 11.5px;">Daftar riwayat jabatan / riwayat pekerjaan kepegawaian ASN.</small>
                            </div>

                            <!-- 6. Sertifikat Ujian Kompetensi -->
                            <div style="background: white; border: 1.5px dashed #cbd5e1; border-radius: 12px; padding: 16px; transition: border-color .2s;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                    <label style="font-size: 13.5px; font-weight: 700; color: #334155; margin: 0; display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-certificate text-success"></i> 6. Sertifikat Ujian Kompetensi
                                    </label>
                                    <span class="badge" style="background: #f1f5f9; color: #64748b; font-size: 10.5px;">Opsional</span>
                                </div>
                                <input type="file" name="dokumen_sertifikat_ukom" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png" style="font-size: 13px; width: 100%; padding: 6px 0;">
                                <small style="display: block; color: #64748b; margin-top: 4px; font-size: 11.5px;">Sertifikat Ujian Dinas / Ujian Kompetensi (Ukom) / Penyesuaian Ijazah.</small>
                            </div>

                            <!-- 7. Dokumen Pendukung Lainnya -->
                            <div style="background: white; border: 1.5px dashed #cbd5e1; border-radius: 12px; padding: 16px; transition: border-color .2s;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                                    <label style="font-size: 13.5px; font-weight: 700; color: #334155; margin: 0; display: flex; align-items: center; gap: 6px;">
                                        <i class="fas fa-paperclip text-muted"></i> 7. Dokumen Pendukung Lainnya
                                    </label>
                                    <span class="badge" style="background: #f1f5f9; color: #64748b; font-size: 10.5px;">Opsional</span>
                                </div>
                                <input type="file" name="dokumen_pendukung" class="form-control-file" accept=".pdf,.jpg,.jpeg,.png" style="font-size: 13px; width: 100%; padding: 6px 0;">
                                <small style="display: block; color: #64748b; margin-top: 4px; font-size: 11.5px;">Ijazah legalisir, Transkrip Nilai, Karpeg/KPE, atau dokumen pendukung sah lainnya.</small>
                            </div>

                        </div>
                    </div>

                    <!-- Section 4: Pernyataan & Tombol Aksi -->
                    <div style="background: #fffbeb; border: 1px solid #fef3c7; border-radius: 12px; padding: 16px 20px; margin-bottom: 24px;">
                        <label style="font-size: 13px; color: #92400e; display: flex; align-items: flex-start; gap: 10px; cursor: pointer; margin: 0;">
                            <input type="checkbox" required style="margin-top: 3px; transform: scale(1.2);">
                            <span>Saya menyatakan dengan sesungguhnya bahwa seluruh data dan dokumen yang saya unggah adalah benar, sah, dan dapat dipertanggungjawabkan sesuai peraturan perundang-undangan aparatur sipil negara.</span>
                        </label>
                    </div>

                    <div style="display: flex; gap: 12px; justify-content: flex-end;">
                        <a href="<?= base_url('sipakar'); ?>" class="btn btn-light" style="padding: 10px 22px; border-radius: 10px; font-weight: 600; border: 1px solid #cbd5e1;">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary" style="padding: 10px 28px; border-radius: 10px; font-weight: 700; background: #0284c7; border-color: #0284c7; box-shadow: 0 4px 12px rgba(2, 132, 199, 0.3);">
                            <i class="fas fa-paper-plane mr-1"></i> Kirim Permohonan SIPAKAR
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
</div>

<script>
function updateSubJenis() {
    var jenis = document.getElementById('jenis_layanan').value;
    var sub = document.getElementById('sub_jenis');
    sub.innerHTML = '';

    if (jenis === 'KP') {
        var options = [
            'Kenaikan Pangkat Reguler (Periode April/Oktober)',
            'Kenaikan Pangkat Pilihan Jabatan Struktural',
            'Kenaikan Pangkat Pilihan Jabatan Fungsional',
            'Kenaikan Pangkat Penyesuaian Ijazah (PI)',
            'Kenaikan Pangkat Anumerta / Pengabdian'
        ];
    } else {
        var options = [
            'Kenaikan Gaji Berkala 2 Tahunan (Reguler)',
            'Kenaikan Gaji Berkala Pertama CPNS ke PNS',
            'Penyesuaian Kenaikan Gaji Berkala Susulan'
        ];
    }

    options.forEach(function(opt) {
        var el = document.createElement('option');
        el.value = opt;
        el.textContent = opt;
        sub.appendChild(el);
    });
}
document.addEventListener('DOMContentLoaded', updateSubJenis);
</script>
