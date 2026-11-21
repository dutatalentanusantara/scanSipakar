<div class="wrapper">
    <div class="mainwrap">
        <div class="sectionrow">
            
            <!-- Unified Header Nav -->
            <?php echo view(template() . '/sipakar/nav_header'); ?>

            <div class="sipakar-card">
                <div class="sipakar-card-title">
                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; border-radius: 10px; background: #e0f2fe; color: #0284c7;">
                        <i class="fas fa-search"></i>
                    </span>
                    <div>
                        <span>Pelacakan Status Pengajuan (Tracking Permohonan)</span>
                        <div style="font-size: 13px; font-weight: normal; color: #64748b;">Pantau tahapan pemeriksaan dan tindak lanjut usulan ASN secara transparan</div>
                    </div>
                </div>

                <!-- Form Pencarian -->
                <form action="<?= base_url('sipakar/tracking'); ?>" method="GET" style="margin-bottom: 26px;">
                    <div style="display: flex; gap: 12px; max-width: 640px;">
                        <input type="text" name="q" class="form-control" value="<?= htmlspecialchars($keyword ?? ''); ?>" placeholder="Masukkan Nomor Tiket (cth: SPK-202611-001) atau NIP..." required style="border-radius: 12px; font-size: 14.5px; padding: 12px 18px; border: 2px solid #cbd5e1;">
                        <button type="submit" class="btn btn-primary" style="padding: 12px 24px; border-radius: 12px; font-weight: 700; background: #0284c7; border-color: #0284c7; white-space: nowrap;">
                            <i class="fas fa-search mr-1"></i> Cari Berkas
                        </button>
                    </div>
                </form>

                <?php if (!empty($keyword) && !$result): ?>
                    <div style="text-align: center; padding: 40px 20px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 14px;">
                        <i class="fas fa-search-minus fa-3x" style="color: #94a3b8; margin-bottom: 12px;"></i>
                        <h4 style="font-size: 16px; font-weight: 700; color: #334155; margin: 0 0 6px 0;">Data Pengajuan Tidak Ditemukan</h4>
                        <p style="font-size: 13px; color: #64748b; margin: 0;">Pastikan Nomor Tiket atau 18 digit NIP yang Anda masukkan sudah benar dan terdaftar di sistem SIPAKAR.</p>
                    </div>
                <?php endif; ?>

                <?php if ($result): 
                    $st = $result['status'];
                    $steps = ['Diajukan', 'Verifikasi Berkas', 'Disetujui Internal', 'Diteruskan ke BKPSDM', 'Selesai'];
                    if ($result['jenis_layanan'] == 'KGB') {
                        $steps = ['Diajukan', 'Verifikasi Berkas', 'Disetujui Internal', 'Penetapan SK Camat', 'Selesai'];
                    }

                    $current_step_idx = 1;
                    if ($st == 'Verifikasi Berkas') $current_step_idx = 2;
                    if ($st == 'Perlu Perbaikan') $current_step_idx = 2;
                    if ($st == 'Disetujui Internal') $current_step_idx = 3;
                    if ($st == 'Diteruskan ke BKPSDM') $current_step_idx = 4;
                    if ($st == 'Selesai') $current_step_idx = 5;
                ?>
                    <!-- Timeline Progres Interaktif -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; margin-bottom: 24px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                            <div>
                                <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Nomor Tiket</span>
                                <h3 style="margin: 0; font-size: 20px; font-weight: 800; color: #0284c7;"><?= htmlspecialchars($result['nomor_tiket']); ?></h3>
                            </div>
                            <div style="text-align: right;">
                                <?php
                                $badge_cls = 'badge-diajukan';
                                if ($st == 'Verifikasi Berkas') $badge_cls = 'badge-verifikasi';
                                if ($st == 'Perlu Perbaikan') $badge_cls = 'badge-perbaikan';
                                if ($st == 'Disetujui Internal') $badge_cls = 'badge-disetujui';
                                if ($st == 'Diteruskan ke BKPSDM') $badge_cls = 'badge-bkpsdm';
                                if ($st == 'Selesai') $badge_cls = 'badge-selesai';
                                if ($st == 'Ditolak') $badge_cls = 'badge-ditolak';
                                ?>
                                <span class="badge-status <?= $badge_cls; ?>" style="font-size: 14px; padding: 8px 16px;">
                                    <i class="fas fa-circle" style="font-size: 9px;"></i> Status: <?= htmlspecialchars($st); ?>
                                </span>
                            </div>
                        </div>

                        <!-- Progress Bar Visual -->
                        <div style="display: flex; justify-content: space-between; position: relative; margin: 30px 10px 10px 10px;">
                            <div style="position: absolute; top: 16px; left: 0; width: 100%; height: 4px; background: #e2e8f0; z-index: 1;"></div>
                            <?php 
                            $pct = (($current_step_idx - 1) / (count($steps) - 1)) * 100;
                            ?>
                            <div style="position: absolute; top: 16px; left: 0; width: <?= $pct; ?>%; height: 4px; background: <?= ($st == 'Perlu Perbaikan') ? '#ef4444' : '#0284c7'; ?>; z-index: 2; transition: width 0.5s ease;"></div>

                            <?php foreach ($steps as $idx => $sname): 
                                $step_num = $idx + 1;
                                $is_active = ($step_num <= $current_step_idx);
                                $is_current = ($step_num == $current_step_idx);
                                $step_color = $is_active ? '#0284c7' : '#94a3b8';
                                if ($is_current && $st == 'Perlu Perbaikan') $step_color = '#ef4444';
                                if ($is_current && $st == 'Selesai') $step_color = '#10b981';
                            ?>
                                <div style="position: relative; z-index: 3; text-align: center; width: 80px;">
                                    <div style="width: 36px; height: 36px; border-radius: 50%; background: <?= $is_active ? $step_color : '#ffffff'; ?>; color: <?= $is_active ? '#ffffff' : '#64748b'; ?>; border: 3px solid <?= $step_color; ?>; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; margin-bottom: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                                        <?php if ($is_active && $step_num < $current_step_idx): ?>
                                            <i class="fas fa-check"></i>
                                        <?php else: ?>
                                            <?= $step_num; ?>
                                        <?php endif; ?>
                                    </div>
                                    <div style="font-size: 11px; font-weight: <?= $is_current ? '700' : '500'; ?>; color: <?= $is_current ? '#0f2b5c' : '#64748b'; ?>; line-height: 1.2;">
                                        <?= $sname; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Alert Perlu Perbaikan & Form Upload Revisi -->
                    <?php if ($st == 'Perlu Perbaikan'): ?>
                        <div style="background: #fef2f2; border: 2px solid #fecaca; border-radius: 14px; padding: 22px; margin-bottom: 24px;">
                            <div style="display: flex; gap: 14px; align-items: flex-start;">
                                <div style="font-size: 26px; color: #ef4444;"><i class="fas fa-exclamation-circle"></i></div>
                                <div style="flex: 1;">
                                    <h4 style="margin: 0 0 6px 0; font-size: 16px; font-weight: 700; color: #991b1b;">
                                        Berkas Usulan Membutuhkan Perbaikan!
                                    </h4>
                                    <p style="margin: 0 0 14px 0; font-size: 13.5px; color: #7f1d1d;">
                                        <strong>Catatan Operator:</strong> <?= htmlspecialchars($result['catatan_perbaikan'] ?: $result['catatan_operator'] ?: 'Harap lengkapi dan perbaiki dokumen usulan Anda.'); ?>
                                    </p>

                                    <!-- Form Upload Revisi -->
                                    <form action="<?= base_url('sipakar/upload_perbaikan'); ?>" method="POST" enctype="multipart/form-data" style="background: white; border: 1px solid #fca5a5; border-radius: 10px; padding: 16px;">
                                        <?= csrf_field(); ?>
                                        <input type="hidden" name="ticket" value="<?= htmlspecialchars($result['nomor_tiket']); ?>">
                                        <label style="font-size: 13px; font-weight: 700; color: #1e293b; margin-bottom: 6px; display: block;">
                                            Unggah Dokumen Perbaikan (Wajib PDF, maks. 2 MB):
                                        </label>
                                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                            <input type="file" name="file_perbaikan" class="form-control-file" accept=".pdf,application/pdf" required style="font-size: 13px; flex: 1;" onchange="if(this.files[0] && (this.files[0].size > 2097152 || !this.files[0].name.toLowerCase().endsWith('.pdf'))){ alert('Dokumen perbaikan wajib berformat .pdf dan ukuran maksimal 2 MB!'); this.value=''; }">
                                            <button type="submit" class="btn btn-danger" style="border-radius: 8px; font-weight: 700; padding: 8px 20px; font-size: 13px;">
                                                <i class="fas fa-upload mr-1"></i> Unggah Revisi Berkas
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Hasil Layanan Resmi (Jika Sudah Selesai) -->
                    <?php if ($st == 'Selesai'): ?>
                        <div style="background: #ecfdf5; border: 2px solid #a7f3d0; border-radius: 14px; padding: 22px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
                            <div>
                                <span style="font-size: 11.5px; font-weight: 700; color: #047857; text-transform: uppercase;">Dokumen Hasil Resmi Telah Terbit</span>
                                <h3 style="margin: 2px 0 6px 0; font-size: 18px; font-weight: 800; color: #065f46;">
                                    Nomor SK: <?= htmlspecialchars($result['nomor_sk_resmi'] ?: 'Dalam Arsip'); ?>
                                </h3>
                                <div style="font-size: 13px; color: #065f46;">
                                    Tanggal Penetapan: <?= !empty($result['tgl_sk_resmi']) ? date('d M Y', strtotime($result['tgl_sk_resmi'])) : date('d M Y'); ?>
                                </div>
                            </div>
                            <?php if (!empty($result['file_hasil_resmi'])): ?>
                                <a href="<?= base_url('sipakar/unduh_dokumen/' . $result['file_hasil_resmi']); ?>" class="btn btn-success" style="padding: 10px 22px; border-radius: 10px; font-weight: 700; background: #10b981; border-color: #10b981; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                                    <i class="fas fa-download mr-1"></i> Unduh Dokumen Resmi SK
                                </a>
                            <?php else: ?>
                                <span style="font-size: 13px; color: #047857; font-weight: 600;">
                                    <i class="fas fa-check-double"></i> Dokumen fisik dapat diambil di Kantor Kecamatan
                                </span>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Detail Data Pengajuan & Dokumen -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                        
                        <!-- Rincian Pegawai -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px;">
                            <h4 style="font-size: 15px; font-weight: 700; color: #0f2b5c; margin: 0 0 14px 0; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                                <i class="fas fa-id-card text-primary mr-1"></i> Rincian Identitas Pemohon
                            </h4>
                            <table style="width: 100%; font-size: 13.5px; line-height: 2;">
                                <tr><td style="width: 40%; color: #64748b;">Nama Lengkap</td><td>: <strong><?= htmlspecialchars($result['nama_lengkap']); ?></strong></td></tr>
                                <tr><td style="color: #64748b;">NIP</td><td>: <?= htmlspecialchars($result['nip']); ?></td></tr>
                                <tr><td style="color: #64748b;">Jabatan</td><td>: <?= htmlspecialchars($result['jabatan']); ?></td></tr>
                                <tr><td style="color: #64748b;">Unit Kerja</td><td>: <?= htmlspecialchars($result['unit_kerja']); ?></td></tr>
                                <tr><td style="color: #64748b;">Pangkat/Golongan</td><td>: <?= htmlspecialchars($result['pangkat_golongan']); ?></td></tr>
                                <tr><td style="color: #64748b;">Layanan</td><td>: <span class="badge badge-info"><?= $result['jenis_layanan']; ?></span> - <?= htmlspecialchars($result['sub_jenis']); ?></td></tr>
                                <tr><td style="color: #64748b;">TMT Usulan</td><td>: <?= !empty($result['tmt_usulan']) ? date('d M Y', strtotime($result['tmt_usulan'])) : '-'; ?></td></tr>
                            </table>
                        </div>

                        <!-- Riwayat & Catatan Operator -->
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px;">
                            <h4 style="font-size: 15px; font-weight: 700; color: #0f2b5c; margin: 0 0 14px 0; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px;">
                                <i class="fas fa-history text-primary mr-1"></i> Riwayat Perubahan Status
                            </h4>
                            
                            <?php if (!empty($riwayat)): ?>
                                <ul style="list-style: none; padding-left: 0; margin: 0; font-size: 13px; line-height: 1.6;">
                                    <?php foreach ($riwayat as $rw): ?>
                                        <li style="margin-bottom: 12px; padding-left: 14px; border-left: 3px solid #0284c7;">
                                            <div style="font-weight: 700; color: #1e293b;">
                                                <?= htmlspecialchars($rw['status_baru']); ?> 
                                                <small style="font-weight: normal; color: #94a3b8;">(<?= date('d M Y, H:i', strtotime($rw['waktu'])); ?>)</small>
                                            </div>
                                            <div style="color: #475569; font-size: 12.5px;"><?= htmlspecialchars($rw['keterangan']); ?></div>
                                            <div style="color: #64748b; font-size: 11.5px;">Oleh: <?= htmlspecialchars($rw['diubah_oleh']); ?></div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <p style="color: #64748b; font-size: 13px;">Belum ada riwayat perubahan status.</p>
                            <?php endif; ?>
                        </div>

                    </div>

                    <!-- Dokumen Persyaratan Terunggah -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px;">
                        <h4 style="font-size: 15px; font-weight: 700; color: #0f2b5c; margin: 0 0 14px 0;">
                            <i class="fas fa-paperclip text-primary mr-1"></i> Berkas Digital yang Telah Diunggah
                        </h4>
                        
                        <?php if (!empty($dokumen)): ?>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 12px;">
                                <?php foreach ($dokumen as $dk): ?>
                                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px; display: flex; justify-content: space-between; align-items: center;">
                                        <div>
                                            <div style="font-weight: 700; font-size: 13px; color: #1e293b;"><?= htmlspecialchars($dk['jenis_dokumen']); ?></div>
                                            <div style="font-size: 11.5px; color: #64748b;"><?= htmlspecialchars($dk['nama_file_asli']); ?> (<?= $dk['ukuran_kb']; ?> KB)</div>
                                        </div>
                                        <a href="<?= base_url('asset/sipakar/' . $dk['nama_file_simpan']); ?>" target="_blank" class="btn btn-sm btn-outline-primary" style="border-radius: 8px; font-size: 12px;">
                                            <i class="fas fa-eye"></i> Lihat
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p style="color: #64748b; font-size: 13px; margin: 0;">Tidak ada berkas yang dilampirkan.</p>
                        <?php endif; ?>
                    </div>

                <?php endif; ?>

            </div>

        </div>
    </div>
</div>
