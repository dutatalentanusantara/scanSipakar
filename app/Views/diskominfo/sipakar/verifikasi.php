<div class="wrapper">
    <div class="mainwrap">
        <div class="sectionrow">
            
            <!-- Unified Header Nav -->
            <?php echo view(template() . '/sipakar/nav_header'); ?>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div>
                    <a href="<?= base_url('sipakar/dashboard'); ?>" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px;">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
                    </a>
                </div>
                <div>
                    <span style="font-size: 13px; color: #64748b;">No. Tiket:</span>
                    <strong style="color: #0284c7; font-size: 16px; margin-left: 4px;"><?= htmlspecialchars($row['nomor_tiket']); ?></strong>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 24px;">
                
                <!-- Kolom Kiri: Rincian Berkas & Dokumen Persyaratan -->
                <div>
                    <!-- Kartu Rincian ASN -->
                    <div class="sipakar-card">
                        <div class="sipakar-card-title">
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; background: #e0f2fe; color: #0284c7;">
                                <i class="fas fa-user-tie"></i>
                            </span>
                            <span>Informasi Pemohon & Usulan</span>
                        </div>

                        <table style="width: 100%; font-size: 13.5px; line-height: 2.1;">
                            <tr><td style="width: 36%; color: #64748b;">Nama Lengkap</td><td>: <strong><?= htmlspecialchars($row['nama_lengkap']); ?></strong></td></tr>
                            <tr><td style="color: #64748b;">NIP</td><td>: <?= htmlspecialchars($row['nip']); ?></td></tr>
                            <tr><td style="color: #64748b;">Jabatan</td><td>: <?= htmlspecialchars($row['jabatan']); ?></td></tr>
                            <tr><td style="color: #64748b;">Unit Kerja</td><td>: <?= htmlspecialchars($row['unit_kerja']); ?></td></tr>
                            <tr><td style="color: #64748b;">Pangkat/Golongan</td><td>: <?= htmlspecialchars($row['pangkat_golongan']); ?></td></tr>
                            <tr><td style="color: #64748b;">Kontak / WA</td><td>: <?= htmlspecialchars($row['no_hp']); ?> (<?= htmlspecialchars($row['email']); ?>)</td></tr>
                            <tr>
                                <td style="color: #64748b;">Jenis Layanan</td>
                                <td>: <strong class="text-primary"><?= ($row['jenis_layanan'] == 'KP') ? 'Kenaikan Pangkat (KP)' : 'Kenaikan Gaji Berkala (KGB)'; ?></strong></td>
                            </tr>
                            <tr><td style="color: #64748b;">Sub-Kategori</td><td>: <?= htmlspecialchars($row['sub_jenis']); ?></td></tr>
                            <tr><td style="color: #64748b;">TMT Terakhir</td><td>: <?= !empty($row['tmt_terakhir']) ? date('d M Y', strtotime($row['tmt_terakhir'])) : '-'; ?></td></tr>
                            <tr><td style="color: #64748b;">TMT Usulan</td><td>: <strong style="color: #047857;"><?= !empty($row['tmt_usulan']) ? date('d M Y', strtotime($row['tmt_usulan'])) : '-'; ?></strong></td></tr>
                            <?php if ($row['jenis_layanan'] == 'KGB' && (!empty($row['gaji_pokok_lama']) || !empty($row['gaji_pokok_baru']))): ?>
                                <tr>
                                    <td style="color: #64748b;">Gaji Pokok</td>
                                    <td>: Rp <?= number_format((float) $row['gaji_pokok_lama']); ?> &rarr; <strong style="color: #16a34a;">Rp <?= number_format((float) $row['gaji_pokok_baru']); ?></strong></td>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <td style="color: #64748b;">Tanggal Masuk</td>
                                <td>: <?= date('d M Y, H:i', strtotime($row['tgl_pengajuan'])); ?> WIB</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Kartu Berkas Persyaratan Digital -->
                    <div class="sipakar-card">
                        <div class="sipakar-card-title">
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; background: #ecfdf5; color: #10b981;">
                                <i class="fas fa-folder-open"></i>
                            </span>
                            <span>Berkas Persyaratan yang Diunggah</span>
                        </div>

                        <?php if (!empty($dokumen)): ?>
                            <div style="display: flex; flex-direction: column; gap: 12px;">
                                <?php foreach ($dokumen as $d): ?>
                                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; display: flex; justify-content: space-between; align-items: center;">
                                        <div>
                                            <div style="font-weight: 700; font-size: 13.5px; color: #0f2b5c;">
                                                <i class="fas fa-file-pdf text-danger mr-1"></i> <?= htmlspecialchars($d['jenis_dokumen']); ?>
                                            </div>
                                            <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                                                File: <?= htmlspecialchars($d['nama_file_asli']); ?> &bull; <?= $d['ukuran_kb']; ?> KB &bull; Diunggah: <?= date('d/m/Y H:i', strtotime($d['uploaded_at'])); ?>
                                            </div>
                                        </div>
                                        <div>
                                            <a href="<?= base_url('asset/sipakar/' . $d['nama_file_simpan']); ?>" target="_blank" class="btn btn-sm btn-primary" style="border-radius: 8px; font-size: 12px; font-weight: 600;">
                                                <i class="fas fa-external-link-alt mr-1"></i> Buka Berkas
                                            </a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p style="color: #64748b; font-size: 13px;">Tidak ada dokumen yang diunggah.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Kolom Kanan: Form Verifikasi & Tindak Lanjut Operator -->
                <div>
                    <div class="sipakar-card">
                        <div class="sipakar-card-title">
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; background: #fef3c7; color: #d97706;">
                                <i class="fas fa-clipboard-check"></i>
                            </span>
                            <span>Validasi Administrasi & Tindak Lanjut</span>
                        </div>

                        <!-- Status Saat Ini -->
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 16px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 13px; color: #64748b;">Status Saat Ini:</span>
                            <span class="badge-status badge-<?= strtolower(str_replace(' ', '', $row['status'])); ?>">
                                <?= htmlspecialchars($row['status']); ?>
                            </span>
                        </div>

                        <!-- Form Update Status -->
                        <form action="<?= base_url('sipakar/verifikasi/' . $row['id_pengajuan']); ?>" method="POST" enctype="multipart/form-data">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="submit_verifikasi" value="1">

                            <div style="margin-bottom: 16px;">
                                <label style="font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">
                                    Tindakan / Perubahan Status <span style="color: #ef4444;">*</span>
                                </label>
                                <select name="status_baru" id="status_baru" class="form-control" required style="border-radius: 10px; font-size: 13.5px; padding: 10px 14px;" onchange="toggleSelesaiFields()">
                                    <option value="Verifikasi Berkas" <?= ($row['status'] == 'Verifikasi Berkas') ? 'selected' : ''; ?>>Verifikasi Berkas (Sedang Diperiksa)</option>
                                    <option value="Perlu Perbaikan" <?= ($row['status'] == 'Perlu Perbaikan') ? 'selected' : ''; ?>>Perlu Perbaikan (Kembalikan ke ASN)</option>
                                    <option value="Disetujui Internal" <?= ($row['status'] == 'Disetujui Internal') ? 'selected' : ''; ?>>Disetujui Internal (Kasubbag Kepegawaian)</option>
                                    <option value="Diteruskan ke BKPSDM" <?= ($row['status'] == 'Diteruskan ke BKPSDM') ? 'selected' : ''; ?>>Diteruskan ke BKPSDM (Usulan Kolektif)</option>
                                    <option value="Selesai" <?= ($row['status'] == 'Selesai') ? 'selected' : ''; ?>>Selesai (SK Resmi Terbit & Disahkan)</option>
                                    <option value="Ditolak" <?= ($row['status'] == 'Ditolak') ? 'selected' : ''; ?>>Ditolak (Tidak Memenuhi Syarat)</option>
                                </select>
                            </div>

                            <div style="margin-bottom: 16px;">
                                <label style="font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">
                                    Nama Operator / Verifikator
                                </label>
                                <input type="text" name="operator_petugas" class="form-control" value="<?= htmlspecialchars($row['operator_petugas'] ?: 'Rina Sartika, A.Md (Operator Kepegawaian)'); ?>" style="border-radius: 10px; font-size: 13px; padding: 8px 12px;">
                            </div>

                            <div style="margin-bottom: 16px;">
                                <label style="font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">
                                    Catatan Operator / Keterangan Status
                                </label>
                                <textarea name="catatan_operator" rows="3" class="form-control" placeholder="Contoh: Berkas lengkap dan memenuhi syarat administratif..." style="border-radius: 10px; font-size: 13px; padding: 10px;"><?= htmlspecialchars($row['catatan_operator'] ?? ''); ?></textarea>
                            </div>

                            <!-- Field khusus jika Perlu Perbaikan -->
                            <div id="field_perbaikan" style="display: <?= ($row['status'] == 'Perlu Perbaikan') ? 'block' : 'none'; ?>; margin-bottom: 16px; background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 14px;">
                                <label style="font-size: 13px; font-weight: 700; color: #991b1b; margin-bottom: 6px; display: block;">
                                    Rincian Berkas yang Harus Diperbaiki oleh ASN:
                                </label>
                                <textarea name="catatan_perbaikan" rows="2" class="form-control" placeholder="Tuliskan dokumen apa yang kurang atau harus diperbaiki..." style="border-radius: 8px; font-size: 13px;"><?= htmlspecialchars($row['catatan_perbaikan'] ?? ''); ?></textarea>
                            </div>

                            <!-- Fields khusus jika Selesai -->
                            <div id="field_selesai" style="display: <?= ($row['status'] == 'Selesai') ? 'block' : 'none'; ?>; margin-bottom: 16px; background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 14px;">
                                <h5 style="font-size: 13.5px; font-weight: 700; color: #166534; margin: 0 0 10px 0;">
                                    Penerbitan Dokumen SK Resmi:
                                </h5>
                                
                                <div style="margin-bottom: 10px;">
                                    <label style="font-size: 12px; font-weight: 600; color: #166534; display: block;">Nomor SK Resmi:</label>
                                    <input type="text" name="nomor_sk_resmi" class="form-control" value="<?= htmlspecialchars($row['nomor_sk_resmi'] ?? ''); ?>" placeholder="Contoh: 822.2/105/SB/2026" style="border-radius: 8px; font-size: 13px;">
                                </div>

                                <div style="margin-bottom: 10px;">
                                    <label style="font-size: 12px; font-weight: 600; color: #166534; display: block;">Tanggal Penetapan SK:</label>
                                    <input type="date" name="tgl_sk_resmi" class="form-control" value="<?= $row['tgl_sk_resmi'] ?? date('Y-m-d'); ?>" style="border-radius: 8px; font-size: 13px;">
                                </div>

                                <div>
                                    <label style="font-size: 12px; font-weight: 600; color: #166534; display: block;">Unggah Dokumen SK Resmi (PDF):</label>
                                    <input type="file" name="file_sk_resmi" accept=".pdf" class="form-control-file" style="font-size: 12.5px;">
                                    <?php if (!empty($row['file_hasil_resmi'])): ?>
                                        <div style="font-size: 11.5px; color: #16a34a; margin-top: 4px;">
                                            <i class="fas fa-check"></i> File aktif: <?= $row['file_hasil_resmi']; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block" style="border-radius: 10px; font-weight: 700; padding: 12px; background: #0284c7; border-color: #0284c7;">
                                <i class="fas fa-save mr-1"></i> Simpan Hasil Verifikasi
                            </button>
                        </form>
                    </div>

                    <!-- Riwayat Audit Status -->
                    <div class="sipakar-card" style="padding: 18px;">
                        <h4 style="font-size: 14.5px; font-weight: 700; color: #0f2b5c; margin: 0 0 12px 0;">
                            <i class="fas fa-history text-primary mr-1"></i> Audit Trail Perubahan
                        </h4>
                        <ul style="list-style: none; padding: 0; margin: 0; font-size: 12.5px;">
                            <?php foreach ($riwayat as $r): ?>
                                <li style="border-left: 2px solid #0284c7; padding-left: 10px; margin-bottom: 10px;">
                                    <strong><?= htmlspecialchars($r['status_baru']); ?></strong>
                                    <div style="color: #64748b;"><?= date('d/m/Y H:i', strtotime($r['waktu'])); ?> &bull; <?= htmlspecialchars($r['diubah_oleh']); ?></div>
                                    <div style="color: #475569;"><?= htmlspecialchars($r['keterangan']); ?></div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
function toggleSelesaiFields() {
    var val = document.getElementById('status_baru').value;
    document.getElementById('field_perbaikan').style.display = (val === 'Perlu Perbaikan') ? 'block' : 'none';
    document.getElementById('field_selesai').style.display = (val === 'Selesai') ? 'block' : 'none';
}
</script>
