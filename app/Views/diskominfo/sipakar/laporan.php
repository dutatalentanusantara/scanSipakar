<div class="wrapper">
    <div class="mainwrap">
        <div class="sectionrow">
            
            <!-- Unified Header Nav -->
            <?php echo view(template() . '/sipakar/nav_header'); ?>

            <div class="sipakar-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; flex-wrap: wrap; gap: 14px;">
                    <div>
                        <div class="sipakar-card-title" style="margin-bottom: 4px;">
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 10px; background: #f3e8ff; color: #7e22ce;">
                                <i class="fas fa-file-invoice"></i>
                            </span>
                            <span>Evaluasi & Rekapitulasi Pelayanan Kepegawaian</span>
                        </div>
                        <p style="font-size: 13px; color: #64748b; margin: 0;">Laporan berkala kinerja, ketepatan waktu tindak lanjut, dan evaluasi pimpinan</p>
                    </div>

                    <!-- Export Actions -->
                    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                        <a href="<?= base_url('sipakar/laporan?tahun=' . $tahun . '&export=excel'); ?>" class="btn btn-success" style="border-radius: 10px; font-weight: 700; font-size: 13px; background: #10b981; border-color: #10b981;">
                            <i class="fas fa-file-excel mr-1"></i> Ekspor Excel / CSV
                        </a>
                        <a href="<?= base_url('sipakar/laporan?tahun=' . $tahun . '&export=print'); ?>" target="_blank" class="btn btn-primary" style="border-radius: 10px; font-weight: 700; font-size: 13px; background: #0284c7; border-color: #0284c7;">
                            <i class="fas fa-print mr-1"></i> Cetak Laporan Resmi (PDF)
                        </a>
                    </div>
                </div>

                <!-- Filter Laporan -->
                <form action="<?= base_url('sipakar/laporan'); ?>" method="GET" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 24px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) 120px; gap: 12px; align-items: flex-end;">
                        <div>
                            <label style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Tahun Periode</label>
                            <select name="tahun" class="form-control" style="border-radius: 8px; font-size: 13px;">
                                <?php for($y = date('Y'); $y >= 2024; $y--): ?>
                                    <option value="<?= $y; ?>" <?= ($tahun == $y) ? 'selected' : ''; ?>><?= $y; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div>
                            <label style="font-size: 12px; font-weight: 700; color: #475569; margin-bottom: 4px; display: block;">Unit Kerja</label>
                            <select name="unit" class="form-control" style="border-radius: 8px; font-size: 13px;">
                                <option value="">Semua Unit Kerja</option>
                                <option value="Kantor Kecamatan Siantar Barat">Kantor Kecamatan Siantar Barat</option>
                                <option value="Kelurahan Bantan">Kelurahan Bantan</option>
                                <option value="Kelurahan Sipinggol-pinggol">Kelurahan Sipinggol-pinggol</option>
                                <option value="Kelurahan Dwikora">Kelurahan Dwikora</option>
                                <option value="Kelurahan Teladan">Kelurahan Teladan</option>
                                <option value="Kelurahan Proklamasi">Kelurahan Proklamasi</option>
                                <option value="Kelurahan Simarito">Kelurahan Simarito</option>
                            </select>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary btn-block" style="border-radius: 8px; font-weight: 700; font-size: 13px; background: #0284c7;">
                                <i class="fas fa-sync-alt mr-1"></i> Tampilkan
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Ringkasan Eksekutif untuk Pimpinan -->
                <div style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border: 1px solid #cbd5e1; border-radius: 14px; padding: 20px; margin-bottom: 24px;">
                    <h4 style="font-size: 15px; font-weight: 800; color: #0f2b5c; margin: 0 0 10px 0;">
                        <i class="fas fa-chart-pie text-primary mr-1"></i> Evaluasi Efektivitas Pelayanan (Executive Summary Tahun <?= $tahun; ?>)
                    </h4>
                    <p style="font-size: 13px; color: #475569; line-height: 1.6; margin: 0 0 14px 0;">
                        Tingkat penyelesaian usulan administrasi kepegawaian mencapai <strong><?= $stats['persen_selesai'] ?? 0; ?>%</strong> dari total <strong><?= $stats['total'] ?? 0; ?> usulan</strong> yang masuk. Sebanyak <strong><?= $stats['selesai'] ?? 0; ?> permohonan</strong> telah tuntas dengan penerbitan SK resmi, sementara <strong><?= $stats['dalam_proses'] ?? 0; ?> permohonan</strong> masih dalam alur verifikasi internal dan penerusan ke BKPSDM.
                    </p>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 12px; text-align: center;">
                        <div style="background: white; border-radius: 10px; padding: 12px;">
                            <div style="font-size: 20px; font-weight: 800; color: #0284c7;"><?= $stats['kp'] ?? 0; ?></div>
                            <div style="font-size: 11.5px; color: #64748b;">Usulan Kenaikan Pangkat</div>
                        </div>
                        <div style="background: white; border-radius: 10px; padding: 12px;">
                            <div style="font-size: 20px; font-weight: 800; color: #10b981;"><?= $stats['kgb'] ?? 0; ?></div>
                            <div style="font-size: 11.5px; color: #64748b;">Usulan Gaji Berkala</div>
                        </div>
                        <div style="background: white; border-radius: 10px; padding: 12px;">
                            <div style="font-size: 20px; font-weight: 800; color: #16a34a;"><?= $stats['selesai'] ?? 0; ?></div>
                            <div style="font-size: 11.5px; color: #64748b;">Tuntas (SK Terbit)</div>
                        </div>
                        <div style="background: white; border-radius: 10px; padding: 12px;">
                            <div style="font-size: 20px; font-weight: 800; color: #ef4444;"><?= $stats['perbaikan'] ?? 0; ?></div>
                            <div style="font-size: 11.5px; color: #64748b;">Perlu Perbaikan</div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Rekapitulasi Data -->
                <div class="table-responsive" style="border: 1px solid #e2e8f0; border-radius: 12px;">
                    <table class="table table-striped table-hover" style="font-size: 13px; margin-bottom: 0;">
                        <thead style="background: #0f2b5c; color: white;">
                            <tr>
                                <th style="padding: 12px;">No</th>
                                <th style="padding: 12px;">No. Tiket</th>
                                <th style="padding: 12px;">NIP & Nama Pegawai</th>
                                <th style="padding: 12px;">Unit Kerja</th>
                                <th style="padding: 12px;">Layanan & Sub-Jenis</th>
                                <th style="padding: 12px;">TMT Usulan</th>
                                <th style="padding: 12px;">Status</th>
                                <th style="padding: 12px;">No. SK Resmi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($rows)): 
                                $no = 1;
                                foreach ($rows as $r):
                            ?>
                                <tr>
                                    <td style="padding: 12px;"><?= $no++; ?></td>
                                    <td style="padding: 12px; font-weight: 700; color: #0284c7;"><?= htmlspecialchars($r['nomor_tiket']); ?></td>
                                    <td style="padding: 12px;">
                                        <strong><?= htmlspecialchars($r['nama_lengkap']); ?></strong>
                                        <div style="font-size: 11.5px; color: #64748b;">NIP: <?= htmlspecialchars($r['nip']); ?></div>
                                    </td>
                                    <td style="padding: 12px;"><?= htmlspecialchars($r['unit_kerja']); ?></td>
                                    <td style="padding: 12px;">
                                        <span class="badge <?= ($r['jenis_layanan'] == 'KP') ? 'badge-primary' : 'badge-success'; ?>" style="font-size: 11px;">
                                            <?= $r['jenis_layanan']; ?>
                                        </span>
                                        <div style="font-size: 11.5px; color: #64748b;"><?= htmlspecialchars($r['sub_jenis']); ?></div>
                                    </td>
                                    <td style="padding: 12px;"><?= !empty($r['tmt_usulan']) ? date('d/m/Y', strtotime($r['tmt_usulan'])) : '-'; ?></td>
                                    <td style="padding: 12px;">
                                        <span class="badge badge-light" style="font-size: 11.5px; border: 1px solid #cbd5e1;">
                                            <?= htmlspecialchars($r['status']); ?>
                                        </span>
                                    </td>
                                    <td style="padding: 12px; font-weight: 600; color: #0f2b5c;">
                                        <?= htmlspecialchars($r['nomor_sk_resmi'] ?: '-'); ?>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 30px; color: #64748b;">
                                        Tidak ada data yang tersedia untuk periode ini.
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

