<div class="wrapper">
    <div class="mainwrap">
        <div class="sectionrow">
            
            <!-- Unified Header Nav -->
            <?php echo view(template() . '/sipakar/nav_header'); ?>

            <div class="sipakar-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; flex-wrap: wrap; gap: 14px;">
                    <div>
                        <div class="sipakar-card-title" style="margin-bottom: 4px;">
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 10px; background: #e0f2fe; color: #0284c7;">
                                <i class="fas fa-stream"></i>
                            </span>
                            <span>Riwayat Pelayanan ASN</span>
                        </div>
                        <p style="font-size: 13px; color: #64748b; margin: 0;">Rekam jejak kronologis proses pengajuan, pemeriksaan, dan pemutakhiran status kepegawaian ASN</p>
                    </div>

                    <!-- Protected Role Indicator -->
                    <div style="background: #f8fafc; border: 1px solid #cbd5e1; padding: 6px 14px; border-radius: 8px; font-size: 12px; color: #475569;">
                        <i class="fas fa-lock text-primary mr-1"></i> Terotentikasi: Operator & Administrator
                    </div>
                </div>

                <!-- Form Filter Pencarian Riwayat -->
                <form action="<?= base_url('sipakar/riwayat'); ?>" method="GET" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)) 120px; gap: 12px; align-items: flex-end;">
                        <div>
                            <label style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Kata Kunci Pencarian</label>
                            <input type="text" name="q" value="<?= htmlspecialchars($filter['q'] ?? ''); ?>" placeholder="No. Tiket / NIP / Nama ASN / Keterangan..." class="form-control" style="border-radius: 8px; font-size: 13px;">
                        </div>

                        <div>
                            <label style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Filter Jenis Layanan</label>
                            <select name="jenis" class="form-control" style="border-radius: 8px; font-size: 13px;">
                                <option value="">Semua Layanan</option>
                                <option value="KP" <?= (($filter['jenis'] ?? '') == 'KP') ? 'selected' : ''; ?>>Kenaikan Pangkat (KP)</option>
                                <option value="KGB" <?= (($filter['jenis'] ?? '') == 'KGB') ? 'selected' : ''; ?>>Kenaikan Gaji Berkala (KGB)</option>
                            </select>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary btn-block" style="border-radius: 8px; font-weight: 700; font-size: 13px; background: #0284c7;">
                                <i class="fas fa-search mr-1"></i> Cari Riwayat
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Tabel Riwayat Kronologis -->
                <div class="table-responsive" style="border: 1px solid #e2e8f0; border-radius: 12px;">
                    <table class="table table-hover" style="font-size: 13px; margin-bottom: 0;">
                        <thead style="background: #f1f5f9; color: #0f2b5c; font-weight: 700;">
                            <tr>
                                <th style="padding: 12px;">Waktu Transaksi</th>
                                <th style="padding: 12px;">No. Tiket</th>
                                <th style="padding: 12px;">Pegawai ASN</th>
                                <th style="padding: 12px;">Layanan</th>
                                <th style="padding: 12px;">Perubahan Status</th>
                                <th style="padding: 12px;">Keterangan & Catatan</th>
                                <th style="padding: 12px;">Diubah Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($riwayat_list)): 
                                foreach ($riwayat_list as $rw):
                                    $st = $rw['status_baru'];
                                    $b_cls = 'badge-diajukan';
                                    if ($st == 'Verifikasi Berkas') $b_cls = 'badge-verifikasi';
                                    if ($st == 'Perlu Perbaikan') $b_cls = 'badge-perbaikan';
                                    if ($st == 'Disetujui Internal') $b_cls = 'badge-disetujui';
                                    if ($st == 'Diteruskan ke BKPSDM') $b_cls = 'badge-bkpsdm';
                                    if ($st == 'Selesai') $b_cls = 'badge-selesai';
                                    if ($st == 'Ditolak') $b_cls = 'badge-ditolak';
                            ?>
                                <tr>
                                    <td style="padding: 12px; font-size: 12px; color: #64748b; white-space: nowrap;">
                                        <i class="far fa-clock mr-1"></i> <?= date('d/m/Y H:i:s', strtotime($rw['waktu'])); ?>
                                    </td>
                                    <td style="padding: 12px;">
                                        <a href="<?= base_url('sipakar/tracking?q=' . urlencode($rw['nomor_tiket'])); ?>" style="font-weight: 700; color: #0284c7; text-decoration: none;">
                                            <?= htmlspecialchars($rw['nomor_tiket']); ?>
                                        </a>
                                    </td>
                                    <td style="padding: 12px;">
                                        <strong style="color: #1e293b;"><?= htmlspecialchars($rw['nama_lengkap']); ?></strong>
                                        <div style="font-size: 11px; color: #64748b;">NIP: <?= htmlspecialchars($rw['nip']); ?></div>
                                        <div style="font-size: 10.5px; color: #94a3b8;"><?= htmlspecialchars($rw['unit_kerja']); ?></div>
                                    </td>
                                    <td style="padding: 12px;">
                                        <span class="badge <?= ($rw['jenis_layanan'] == 'KP') ? 'badge-primary' : 'badge-success'; ?>" style="font-size: 11px;">
                                            <?= $rw['jenis_layanan']; ?>
                                        </span>
                                    </td>
                                    <td style="padding: 12px;">
                                        <span class="badge-status <?= $b_cls; ?>" style="font-size: 11px;">
                                            <?= htmlspecialchars($st); ?>
                                        </span>
                                    </td>
                                    <td style="padding: 12px; color: #334155; max-width: 280px;">
                                        <?= htmlspecialchars($rw['keterangan']); ?>
                                    </td>
                                    <td style="padding: 12px; font-weight: 600; color: #475569; font-size: 12px;">
                                        <?= htmlspecialchars($rw['diubah_oleh']); ?>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 30px; color: #64748b;">
                                        Tidak ada catatan riwayat pelayanan yang sesuai filter.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
</div>

