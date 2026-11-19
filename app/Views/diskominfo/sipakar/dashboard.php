<div class="wrapper">
    <div class="mainwrap">
        <div class="sectionrow">
            
            <!-- Unified Header Nav -->
            <?php echo view(template() . '/sipakar/nav_header'); ?>

            <!-- Title & Quick Filter -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; flex-wrap: wrap; gap: 14px;">
                <div>
                    <h2 style="font-size: 22px; font-weight: 800; color: #0f2b5c; margin: 0 0 4px 0;">
                        <i class="fas fa-chart-line text-primary"></i> Dashboard Pengendalian Mutu SIPAKAR
                    </h2>
                    <p style="font-size: 13.5px; color: #64748b; margin: 0;">
                        Pemantauan real-time integritas administrasi, kecepatan tindak lanjut, dan mitigasi kendala berkas ASN.
                    </p>
                </div>

                <div style="display: flex; gap: 10px; align-items: center;">
                    <form method="GET" action="<?= base_url('sipakar/dashboard'); ?>" style="display: flex; gap: 8px;">
                        <select name="tahun" class="form-control" onchange="this.form.submit()" style="border-radius: 10px; font-size: 13.5px; padding: 7px 14px;">
                            <?php for($y = date('Y'); $y >= 2024; $y--): ?>
                                <option value="<?= $y; ?>" <?= ($tahun == $y) ? 'selected' : ''; ?>>Tahun <?= $y; ?></option>
                            <?php endfor; ?>
                        </select>
                    </form>
                    <a href="<?= base_url('sipakar/laporan?tahun=' . $tahun); ?>" class="btn btn-outline-primary" style="border-radius: 10px; font-size: 13px; font-weight: 600;">
                        <i class="fas fa-file-export mr-1"></i> Cetak Laporan
                    </a>
                </div>
            </div>

            <!-- KPI Cards Grid (Pengendalian Mutu) -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 18px; margin-bottom: 28px;">
                
                <div style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; padding: 20px; box-shadow: 0 4px 14px rgba(0,0,0,0.03);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Pengajuan</span>
                        <span style="background: #e0f2fe; color: #0284c7; width: 34px; height: 34px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center;"><i class="fas fa-folder"></i></span>
                    </div>
                    <div style="font-size: 28px; font-weight: 800; color: #0f2b5c;"><?= number_format($stats['total'] ?? 0); ?></div>
                    <div style="font-size: 12px; color: #64748b; margin-top: 4px;">
                        <span style="color: #0284c7; font-weight: 700;"><?= $stats['kp'] ?? 0; ?> KP</span> &bull; <span style="color: #10b981; font-weight: 700;"><?= $stats['kgb'] ?? 0; ?> KGB</span>
                    </div>
                </div>

                <div style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; padding: 20px; box-shadow: 0 4px 14px rgba(0,0,0,0.03);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Selesai (SK Terbit)</span>
                        <span style="background: #dcfce7; color: #16a34a; width: 34px; height: 34px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center;"><i class="fas fa-check-circle"></i></span>
                    </div>
                    <div style="font-size: 28px; font-weight: 800; color: #16a34a;"><?= number_format($stats['selesai'] ?? 0); ?></div>
                    <div style="font-size: 12px; color: #16a34a; margin-top: 4px; font-weight: 600;">
                        Tingkat Sukses: <?= $stats['persen_selesai'] ?? 0; ?>%
                    </div>
                </div>

                <div style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; padding: 20px; box-shadow: 0 4px 14px rgba(0,0,0,0.03);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Dalam Proses Validasi</span>
                        <span style="background: #fef3c7; color: #d97706; width: 34px; height: 34px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center;"><i class="fas fa-spinner"></i></span>
                    </div>
                    <div style="font-size: 28px; font-weight: 800; color: #d97706;"><?= number_format($stats['dalam_proses'] ?? 0); ?></div>
                    <div style="font-size: 12px; color: #64748b; margin-top: 4px;">
                        <?= $stats['diajukan'] ?? 0; ?> Masuk &bull; <?= $stats['verifikasi'] ?? 0; ?> Periksa
                    </div>
                </div>

                <div style="background: white; border-radius: 16px; border: 1px solid #fecaca; padding: 20px; box-shadow: 0 4px 14px rgba(0,0,0,0.03);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                        <span style="font-size: 12px; font-weight: 700; color: #b91c1c; text-transform: uppercase;">Butuh Perbaikan</span>
                        <span style="background: #fee2e2; color: #b91c1c; width: 34px; height: 34px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center;"><i class="fas fa-exclamation-triangle"></i></span>
                    </div>
                    <div style="font-size: 28px; font-weight: 800; color: #b91c1c;"><?= number_format($stats['perbaikan'] ?? 0); ?></div>
                    <div style="font-size: 12px; color: #b91c1c; margin-top: 4px; font-weight: 600;">
                        Menunggu Revisi ASN
                    </div>
                </div>

            </div>

            <!-- Visualisasi Grafik: Tren Bulanan & Distribusi Layanan -->
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 28px;">
                
                <!-- Grafik Tren Pengajuan Bulanan -->
                <div class="sipakar-card" style="margin-bottom: 0;">
                    <h4 style="font-size: 16px; font-weight: 800; color: #0f2b5c; margin: 0 0 16px 0;">
                        <i class="fas fa-chart-bar text-primary mr-1"></i> Tren Pengajuan Kenaikan Pangkat & KGB Tahun <?= $tahun; ?>
                    </h4>
                    <div style="height: 280px; position: relative;">
                        <canvas id="chartBulanan"></canvas>
                    </div>
                </div>

                <!-- Grafik Distribusi & Proporsi Layanan -->
                <div class="sipakar-card" style="margin-bottom: 0;">
                    <h4 style="font-size: 16px; font-weight: 800; color: #0f2b5c; margin: 0 0 16px 0;">
                        <i class="fas fa-chart-pie text-primary mr-1"></i> Proporsi Jenis Layanan
                    </h4>
                    <div style="height: 220px; position: relative;">
                        <canvas id="chartProporsi"></canvas>
                    </div>
                    <div style="display: flex; justify-content: space-around; margin-top: 14px; text-align: center; font-size: 13px;">
                        <div>
                            <span style="display: inline-block; width: 10px; height: 10px; background: #0284c7; border-radius: 50%;"></span>
                            <strong>KP: <?= $stats['kp'] ?? 0; ?> usulan</strong>
                        </div>
                        <div>
                            <span style="display: inline-block; width: 10px; height: 10px; background: #10b981; border-radius: 50%;"></span>
                            <strong>KGB: <?= $stats['kgb'] ?? 0; ?> usulan</strong>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Tabel Manajemen Berkas & Tindak Lanjut Operator -->
            <div class="sipakar-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; flex-wrap: wrap; gap: 12px;">
                    <div>
                        <h3 style="font-size: 17px; font-weight: 800; color: #0f2b5c; margin: 0;">
                            <i class="fas fa-tasks text-primary mr-1"></i> Daftar Usulan ASN & Tindak Lanjut Operator
                        </h3>
                        <p style="font-size: 12.5px; color: #64748b; margin: 2px 0 0 0;">Lakukan verifikasi berkas, persetujuan internal, dan penerbitan SK</p>
                    </div>

                    <!-- Filter Form -->
                    <form action="<?= base_url('sipakar/dashboard'); ?>" method="GET" style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <input type="hidden" name="tahun" value="<?= $tahun; ?>">
                        <select name="status" class="form-control" style="font-size: 12.5px; border-radius: 8px; padding: 6px 12px;">
                            <option value="">Semua Status</option>
                            <option value="Diajukan" <?= (($filter['status'] ?? '') == 'Diajukan') ? 'selected' : ''; ?>>Diajukan</option>
                            <option value="Verifikasi Berkas" <?= (($filter['status'] ?? '') == 'Verifikasi Berkas') ? 'selected' : ''; ?>>Verifikasi Berkas</option>
                            <option value="Perlu Perbaikan" <?= (($filter['status'] ?? '') == 'Perlu Perbaikan') ? 'selected' : ''; ?>>Perlu Perbaikan</option>
                            <option value="Disetujui Internal" <?= (($filter['status'] ?? '') == 'Disetujui Internal') ? 'selected' : ''; ?>>Disetujui Internal</option>
                            <option value="Diteruskan ke BKPSDM" <?= (($filter['status'] ?? '') == 'Diteruskan ke BKPSDM') ? 'selected' : ''; ?>>Diteruskan ke BKPSDM</option>
                            <option value="Selesai" <?= (($filter['status'] ?? '') == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                        </select>

                        <input type="text" name="q" value="<?= htmlspecialchars($filter['q'] ?? ''); ?>" placeholder="Cari NIP / Nama..." class="form-control" style="font-size: 12.5px; border-radius: 8px; padding: 6px 12px; width: 170px;">
                        
                        <button type="submit" class="btn btn-sm btn-primary" style="border-radius: 8px;">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>

                <div class="table-responsive" style="border: 1px solid #e2e8f0; border-radius: 12px;">
                    <table class="table table-hover" style="margin-bottom: 0; font-size: 13px;">
                        <thead style="background: #f8fafc; color: #0f2b5c; font-weight: 700;">
                            <tr>
                                <th style="padding: 12px;">No</th>
                                <th style="padding: 12px;">No. Tiket</th>
                                <th style="padding: 12px;">Pegawai ASN</th>
                                <th style="padding: 12px;">Unit Kerja</th>
                                <th style="padding: 12px;">Layanan</th>
                                <th style="padding: 12px;">Tgl Masuk</th>
                                <th style="padding: 12px;">Status Mutu</th>
                                <th style="padding: 12px; text-align: center;">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pengajuan_list)): 
                                $no = 1;
                                foreach ($pengajuan_list as $p):
                                    $st = $p['status'];
                                    $b_cls = 'badge-diajukan';
                                    if ($st == 'Verifikasi Berkas') $b_cls = 'badge-verifikasi';
                                    if ($st == 'Perlu Perbaikan') $b_cls = 'badge-perbaikan';
                                    if ($st == 'Disetujui Internal') $b_cls = 'badge-disetujui';
                                    if ($st == 'Diteruskan ke BKPSDM') $b_cls = 'badge-bkpsdm';
                                    if ($st == 'Selesai') $b_cls = 'badge-selesai';
                                    if ($st == 'Ditolak') $b_cls = 'badge-ditolak';
                            ?>
                                <tr>
                                    <td style="padding: 12px;"><?= $no++; ?></td>
                                    <td style="padding: 12px;">
                                        <strong style="color: #0284c7;"><?= htmlspecialchars($p['nomor_tiket']); ?></strong>
                                    </td>
                                    <td style="padding: 12px;">
                                        <div style="font-weight: 700; color: #1e293b;"><?= htmlspecialchars($p['nama_lengkap']); ?></div>
                                        <div style="font-size: 11.5px; color: #64748b;">NIP: <?= htmlspecialchars($p['nip']); ?></div>
                                    </td>
                                    <td style="padding: 12px;">
                                        <div><?= htmlspecialchars($p['unit_kerja']); ?></div>
                                        <div style="font-size: 11px; color: #94a3b8;"><?= htmlspecialchars($p['jabatan']); ?></div>
                                    </td>
                                    <td style="padding: 12px;">
                                        <span class="badge <?= ($p['jenis_layanan'] == 'KP') ? 'badge-primary' : 'badge-success'; ?>" style="font-size: 11px;">
                                            <?= $p['jenis_layanan']; ?>
                                        </span>
                                    </td>
                                    <td style="padding: 12px; font-size: 12px; color: #64748b;">
                                        <?= date('d/m/Y H:i', strtotime($p['tgl_pengajuan'])); ?>
                                    </td>
                                    <td style="padding: 12px;">
                                        <span class="badge-status <?= $b_cls; ?>" style="font-size: 11.5px;">
                                            <?= htmlspecialchars($st); ?>
                                        </span>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <a href="<?= base_url('sipakar/verifikasi/' . $p['id_pengajuan']); ?>" class="btn btn-sm btn-primary" style="border-radius: 8px; font-weight: 600; padding: 4px 12px; font-size: 12px; background: #0284c7;">
                                            <i class="fas fa-check-double mr-1"></i> Periksa Berkas
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 30px; color: #64748b;">
                                        Tidak ada data pengajuan yang sesuai filter.
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

<!-- Chart.js CDN for Visualization -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Chart Bulanan (Bar / Line)
    var ctxBulanan = document.getElementById('chartBulanan').getContext('2d');
    new Chart(ctxBulanan, {
        type: 'bar',
        data: {
            labels: <?= json_encode($trends['labels']); ?>,
            datasets: [
                {
                    label: 'Kenaikan Pangkat (KP)',
                    data: <?= json_encode($trends['kp']); ?>,
                    backgroundColor: 'rgba(2, 132, 199, 0.75)',
                    borderColor: '#0284c7',
                    borderWidth: 1,
                    borderRadius: 6
                },
                {
                    label: 'Kenaikan Gaji Berkala (KGB)',
                    data: <?= json_encode($trends['kgb']); ?>,
                    backgroundColor: 'rgba(16, 185, 129, 0.75)',
                    borderColor: '#10b981',
                    borderWidth: 1,
                    borderRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });

    // 2. Chart Proporsi (Doughnut)
    var ctxProporsi = document.getElementById('chartProporsi').getContext('2d');
    new Chart(ctxProporsi, {
        type: 'doughnut',
        data: {
            labels: ['Kenaikan Pangkat', 'Kenaikan Gaji Berkala'],
            datasets: [{
                data: [<?= (int)($stats['kp'] ?? 0); ?>, <?= (int)($stats['kgb'] ?? 0); ?>],
                backgroundColor: ['#0284c7', '#10b981'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>

