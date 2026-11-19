<div class="wrapper">
    <div class="mainwrap">
        <div class="sectionrow">
            
            <!-- Unified Header Nav -->
            <?php echo view(template() . '/sipakar/nav_header'); ?>

            <div class="sipakar-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; flex-wrap: wrap; gap: 14px;">
                    <div>
                        <div class="sipakar-card-title" style="margin-bottom: 4px;">
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 10px; background: #ede9fe; color: #7c3aed;">
                                <i class="fas fa-chart-line"></i>
                            </span>
                            <span>Evaluasi Kinerja Pelayanan Administrasi Kepegawaian</span>
                        </div>
                        <p style="font-size: 13px; color: #64748b; margin: 0;">Pengukuran standar pelayanan minimal (SPM), ketepatan waktu SOP, dan produktivitas operator</p>
                    </div>

                    <form method="GET" action="<?= base_url('sipakar/evaluasi'); ?>" style="display: flex; gap: 8px;">
                        <select name="tahun" class="form-control" onchange="this.form.submit()" style="border-radius: 10px; font-size: 13px; padding: 7px 14px;">
                            <?php for($y = date('Y'); $y >= 2024; $y--): ?>
                                <option value="<?= $y; ?>" <?= ($tahun == $y) ? 'selected' : ''; ?>>Tahun <?= $y; ?></option>
                            <?php endfor; ?>
                        </select>
                    </form>
                </div>

                <!-- KPI Evaluasi SLA -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 18px; margin-bottom: 26px;">
                    
                    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                        <div style="font-size: 11.5px; font-weight: 700; color: #64748b; text-transform: uppercase;">Kecepatan Rata-rata (SLA)</div>
                        <div style="font-size: 26px; font-weight: 800; color: #0284c7; margin-top: 4px;"><?= $evaluasi['avg_days']; ?> Hari</div>
                        <div style="font-size: 12px; color: #10b981; margin-top: 2px;">
                            <i class="fas fa-check-circle"></i> Sesuai Standar SOP (&le; 5 Hari)
                        </div>
                    </div>

                    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                        <div style="font-size: 11.5px; font-weight: 700; color: #64748b; text-transform: uppercase;">Ketuntasan Penetapan SK</div>
                        <div style="font-size: 26px; font-weight: 800; color: #10b981; margin-top: 4px;"><?= $evaluasi['stats']['persen_selesai']; ?>%</div>
                        <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                            <?= $evaluasi['stats']['selesai']; ?> dari <?= $evaluasi['stats']['total']; ?> Usulan Selesai
                        </div>
                    </div>

                    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                        <div style="font-size: 11.5px; font-weight: 700; color: #64748b; text-transform: uppercase;">Rasio Perbaikan Berkas</div>
                        <?php 
                        $pct_revisi = ($evaluasi['stats']['total'] > 0) ? round(($evaluasi['stats']['perbaikan'] / $evaluasi['stats']['total']) * 100, 1) : 0;
                        ?>
                        <div style="font-size: 26px; font-weight: 800; color: #ef4444; margin-top: 4px;"><?= $pct_revisi; ?>%</div>
                        <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                            <?= $evaluasi['stats']['perbaikan']; ?> Usulan Memerlukan Revisi
                        </div>
                    </div>

                    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
                        <div style="font-size: 11.5px; font-weight: 700; color: #64748b; text-transform: uppercase;">Waktu Proses Tercepat</div>
                        <div style="font-size: 26px; font-weight: 800; color: #8b5cf6; margin-top: 4px;"><?= $evaluasi['min_days']; ?> Hari Kerja</div>
                        <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                            Maksimum Penyelesaian: <?= $evaluasi['max_days']; ?> Hari
                        </div>
                    </div>

                </div>

                <!-- Kinerja Operator & Verifikator -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px; margin-bottom: 24px;">
                    <h4 style="font-size: 15px; font-weight: 800; color: #0f2b5c; margin: 0 0 14px 0;">
                        <i class="fas fa-user-check text-primary mr-1"></i> Rekapitulasi Kinerja Petugas Operator & Verifikator
                    </h4>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" style="font-size: 13px; margin-bottom: 0;">
                            <thead style="background: #f8fafc; color: #1e293b;">
                                <tr>
                                    <th>Petugas Operator</th>
                                    <th style="text-align: center;">Total Berkas Ditangani</th>
                                    <th style="text-align: center;">Tuntas Ditetapkan</th>
                                    <th style="text-align: center;">Permintaan Revisi</th>
                                    <th style="text-align: center;">Persentase Ketuntasan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($evaluasi['operator_perf'])): 
                                    foreach ($evaluasi['operator_perf'] as $op):
                                        $persen_op = ($op['jml'] > 0) ? round(($op['tuntas'] / $op['jml']) * 100, 1) : 0;
                                ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($op['operator_petugas']); ?></strong></td>
                                        <td style="text-align: center; font-weight: 700;"><?= $op['jml']; ?> Berkas</td>
                                        <td style="text-align: center; color: #16a34a; font-weight: 700;"><?= $op['tuntas']; ?></td>
                                        <td style="text-align: center; color: #ef4444; font-weight: 700;"><?= $op['revisi']; ?></td>
                                        <td style="text-align: center;">
                                            <div class="progress" style="height: 16px; border-radius: 10px;">
                                                <div class="progress-bar bg-success" style="width: <?= $persen_op; ?>%; font-size: 10px; line-height: 16px;">
                                                    <?= $persen_op; ?>%
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr><td colspan="5" style="text-align: center; padding: 15px;">Belum ada data operator tercatat.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Kinerja Distribusi Wilayah 6 Kelurahan -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 20px;">
                    <h4 style="font-size: 15px; font-weight: 800; color: #0f2b5c; margin: 0 0 14px 0;">
                        <i class="fas fa-building text-primary mr-1"></i> Sebaran Pelayanan Unit Kerja / Kelurahan
                    </h4>
                    
                    <div class="table-responsive">
                        <table class="table table-hover" style="font-size: 13px; margin-bottom: 0;">
                            <thead style="background: #f8fafc; color: #1e293b;">
                                <tr>
                                    <th>Unit Kerja / Instansi</th>
                                    <th style="text-align: center;">Total Usulan</th>
                                    <th style="text-align: center;">Kenaikan Pangkat (KP)</th>
                                    <th style="text-align: center;">Gaji Berkala (KGB)</th>
                                    <th style="text-align: center;">Selesai (SK Terbit)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($evaluasi['unit_stats'])): 
                                    foreach ($evaluasi['unit_stats'] as $us):
                                ?>
                                    <tr>
                                        <td><strong><?= htmlspecialchars($us['unit_kerja']); ?></strong></td>
                                        <td style="text-align: center; font-weight: 700;"><?= $us['total']; ?></td>
                                        <td style="text-align: center; color: #0284c7;"><?= $us['kp']; ?></td>
                                        <td style="text-align: center; color: #10b981;"><?= $us['kgb']; ?></td>
                                        <td style="text-align: center; font-weight: 700; color: #16a34a;"><?= $us['selesai']; ?></td>
                                    </tr>
                                <?php endforeach; else: ?>
                                    <tr><td colspan="5" style="text-align: center; padding: 15px;">Belum ada usulan per unit kerja.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

