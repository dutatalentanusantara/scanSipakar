<div class="wrapper">
    <div class="mainwrap">
        <div class="sectionrow">
            
            <!-- Unified Header Nav -->
            <?php echo view(template() . '/sipakar/nav_header'); ?>

            <div class="sipakar-card" style="text-align: center; padding: 40px 24px;">
                <div style="width: 76px; height: 76px; background: #ecfdf5; color: #10b981; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 38px; margin-bottom: 20px; box-shadow: 0 4px 16px rgba(16, 185, 129, 0.2);">
                    <i class="fas fa-check"></i>
                </div>

                <h2 style="font-size: 24px; font-weight: 800; color: #0f2b5c; margin: 0 0 8px 0;">Pengajuan Berhasil Didaftarkan!</h2>
                <p style="font-size: 14.5px; color: #64748b; margin: 0 0 24px 0; max-width: 600px; margin-left: auto; margin-right: auto;">
                    Permohonan usulan kepegawaian Anda telah berhasil tercatat dalam sistem SIPAKAR Kecamatan Siantar Barat. Simpan Nomor Tiket di bawah ini untuk memantau status pemeriksaan berkas.
                </p>

                <!-- Ticket Box -->
                <div style="background: #f8fafc; border: 2px dashed #0284c7; border-radius: 16px; padding: 24px; max-width: 580px; margin: 0 auto 30px auto; text-align: left;">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 14px; margin-bottom: 16px;">
                        <div>
                            <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Nomor Tiket Registrasi</span>
                            <div style="font-size: 22px; font-weight: 800; color: #0284c7; letter-spacing: 1px;">
                                <?= htmlspecialchars($row['nomor_tiket'] ?? ''); ?>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <span class="badge-status badge-diajukan">
                                <i class="fas fa-clock"></i> <?= htmlspecialchars($row['status'] ?? 'Diajukan'); ?>
                            </span>
                        </div>
                    </div>

                    <table style="width: 100%; font-size: 13.5px; color: #334155; line-height: 2;">
                        <tr>
                            <td style="width: 38%; color: #64748b;">Nama Pegawai</td>
                            <td style="font-weight: 700;">: <?= htmlspecialchars($row['nama_lengkap'] ?? ''); ?></td>
                        </tr>
                        <tr>
                            <td style="color: #64748b;">NIP</td>
                            <td>: <?= htmlspecialchars($row['nip'] ?? ''); ?></td>
                        </tr>
                        <tr>
                            <td style="color: #64748b;">Unit Kerja</td>
                            <td>: <?= htmlspecialchars($row['unit_kerja'] ?? ''); ?></td>
                        </tr>
                        <tr>
                            <td style="color: #64748b;">Layanan</td>
                            <td>: <strong class="text-primary"><?= ($row['jenis_layanan'] == 'KP') ? 'Kenaikan Pangkat (KP)' : 'Kenaikan Gaji Berkala (KGB)'; ?></strong></td>
                        </tr>
                        <tr>
                            <td style="color: #64748b;">Kategori Usulan</td>
                            <td>: <?= htmlspecialchars($row['sub_jenis'] ?? ''); ?></td>
                        </tr>
                        <tr>
                            <td style="color: #64748b;">Waktu Pengajuan</td>
                            <td>: <?= date('d M Y, H:i', strtotime($row['tgl_pengajuan'])); ?> WIB</td>
                        </tr>
                        <tr>
                            <td style="color: #64748b;">Jumlah Berkas Unggah</td>
                            <td>: <strong><?= count($dokumen); ?> Berkas Digital</strong></td>
                        </tr>
                    </table>
                </div>

                <!-- Action Buttons -->
                <div style="display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;">
                    <a href="<?= base_url('sipakar/tracking?q=' . urlencode($row['nomor_tiket'])); ?>" class="btn btn-primary" style="padding: 10px 24px; border-radius: 10px; font-weight: 700; background: #0284c7; border-color: #0284c7;">
                        <i class="fas fa-search mr-1"></i> Lacak Status Permohonan
                    </a>
                    <button onclick="window.print()" class="btn btn-outline-secondary" style="padding: 10px 24px; border-radius: 10px; font-weight: 600;">
                        <i class="fas fa-print mr-1"></i> Cetak Bukti Pendaftaran
                    </button>
                    <a href="<?= base_url('sipakar'); ?>" class="btn btn-light" style="padding: 10px 20px; border-radius: 10px; font-weight: 600; border: 1px solid #cbd5e1;">
                        Kembali ke Beranda SIPAKAR
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>

