<div class="wrapper">
    <div class="mainwrap">
        <div class="sectionrow">
            
            <!-- Unified Header Nav -->
            <?php echo view(template() . '/sipakar/nav_header'); ?>

            <div class="sipakar-card">
                <div class="sipakar-card-title">
                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; border-radius: 10px; background: #e0e7ff; color: #4338ca;">
                        <i class="fas fa-archive"></i>
                    </span>
                    <div>
                        <span>Hasil Layanan dan Pengarsipan Digital Resmi</span>
                        <div style="font-size: 13px; font-weight: normal; color: #64748b;">Pengelompokan arsip SK KGB dan SK KP berdasarkan NIP, nama ASN, jenis layanan, dan tahun</div>
                    </div>
                </div>

                <!-- Filter & Pencarian Arsip -->
                <form action="<?= base_url('sipakar/arsip'); ?>" method="GET" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; margin-bottom: 24px;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)) 110px; gap: 12px; align-items: flex-end;">
                        <div>
                            <label style="font-size: 12.5px; font-weight: 700; color: #475569; margin-bottom: 5px; display: block;">Cari NIP / Nama / No SK</label>
                            <input type="text" name="q" value="<?= htmlspecialchars($filter['q'] ?? ''); ?>" class="form-control" placeholder="Ketik kata kunci pencarian..." style="border-radius: 10px; font-size: 13.5px; padding: 9px 12px;">
                        </div>

                        <div>
                            <label style="font-size: 12.5px; font-weight: 700; color: #475569; margin-bottom: 5px; display: block;">Jenis Layanan</label>
                            <select name="jenis" class="form-control" style="border-radius: 10px; font-size: 13.5px; padding: 9px 12px;">
                                <option value="">Semua Layanan</option>
                                <option value="KP" <?= (($filter['jenis_layanan'] ?? '') == 'KP') ? 'selected' : ''; ?>>Kenaikan Pangkat (KP)</option>
                                <option value="KGB" <?= (($filter['jenis_layanan'] ?? '') == 'KGB') ? 'selected' : ''; ?>>Kenaikan Gaji Berkala (KGB)</option>
                            </select>
                        </div>

                        <div>
                            <label style="font-size: 12.5px; font-weight: 700; color: #475569; margin-bottom: 5px; display: block;">Tahun Penetapan</label>
                            <select name="tahun" class="form-control" style="border-radius: 10px; font-size: 13.5px; padding: 9px 12px;">
                                <?php for($y = date('Y'); $y >= 2022; $y--): ?>
                                    <option value="<?= $y; ?>" <?= (($filter['tahun'] ?? date('Y')) == $y) ? 'selected' : ''; ?>><?= $y; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div>
                            <button type="submit" class="btn btn-primary btn-block" style="border-radius: 10px; font-weight: 700; padding: 9px 14px; background: #0284c7; border-color: #0284c7;">
                                <i class="fas fa-filter mr-1"></i> Filter
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Tabel Arsip Resmi -->
                <div class="table-responsive" style="border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
                    <table class="table table-hover" style="margin-bottom: 0; font-size: 13.5px;">
                        <thead style="background: #f1f5f9; color: #0f2b5c; font-weight: 700;">
                            <tr>
                                <th style="padding: 14px;">No</th>
                                <th style="padding: 14px;">Identitas ASN & NIP</th>
                                <th style="padding: 14px;">Unit Kerja / Jabatan</th>
                                <th style="padding: 14px;">Layanan & Kategori</th>
                                <th style="padding: 14px;">Nomor & Tgl SK Resmi</th>
                                <th style="padding: 14px;">Tgl Terima & Selesai</th>
                                <th style="padding: 14px; text-align: center;">Aksi Unduh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($arsip)): 
                                $no = 1;
                                foreach ($arsip as $a):
                            ?>
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 14px; font-weight: 600; color: #64748b;"><?= $no++; ?></td>
                                    <td style="padding: 14px;">
                                        <strong style="color: #0f2b5c;"><?= htmlspecialchars($a['nama_lengkap']); ?></strong>
                                        <div style="font-size: 12px; color: #64748b;">NIP: <?= htmlspecialchars($a['nip']); ?></div>
                                        <div style="font-size: 11px; color: #94a3b8;"><?= htmlspecialchars($a['pangkat_golongan']); ?></div>
                                    </td>
                                    <td style="padding: 14px;">
                                        <div style="font-weight: 600; color: #334155;"><?= htmlspecialchars($a['unit_kerja']); ?></div>
                                        <div style="font-size: 12px; color: #64748b;"><?= htmlspecialchars($a['jabatan']); ?></div>
                                    </td>
                                    <td style="padding: 14px;">
                                        <span class="badge <?= ($a['jenis_layanan'] == 'KP') ? 'badge-primary' : 'badge-success'; ?>" style="font-size: 11px;">
                                            <?= $a['jenis_layanan']; ?>
                                        </span>
                                        <div style="font-size: 12px; color: #475569; margin-top: 4px;"><?= htmlspecialchars($a['sub_jenis']); ?></div>
                                    </td>
                                    <td style="padding: 14px;">
                                        <strong style="color: #0369a1; font-size: 13px;">
                                            <?= htmlspecialchars($a['nomor_sk_resmi'] ?: 'SK Kolektif Resmi'); ?>
                                        </strong>
                                        <div style="font-size: 12px; color: #64748b;">
                                            <i class="far fa-calendar-alt"></i> <?= !empty($a['tgl_sk_resmi']) ? date('d M Y', strtotime($a['tgl_sk_resmi'])) : date('d M Y'); ?>
                                        </div>
                                    </td>
                                    <td style="padding: 14px; font-size: 12px; color: #475569;">
                                        <div><span style="color: #64748b;">Masuk:</span> <?= date('d/m/Y', strtotime($a['tgl_pengajuan'])); ?></div>
                                        <div><span style="color: #10b981;">Selesai:</span> <?= !empty($a['tgl_selesai']) ? date('d/m/Y', strtotime($a['tgl_selesai'])) : '-'; ?></div>
                                    </td>
                                    <td style="padding: 14px; text-align: center;">
                                        <?php if (!empty($a['file_hasil_resmi'])): ?>
                                            <a href="<?= base_url('sipakar/unduh_dokumen/' . $a['file_hasil_resmi']); ?>" class="btn btn-sm btn-success" style="border-radius: 8px; font-weight: 600; padding: 6px 14px;">
                                                <i class="fas fa-file-pdf mr-1"></i> Unduh SK
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= base_url('sipakar/tracking?q=' . urlencode($a['nomor_tiket'])); ?>" class="btn btn-sm btn-outline-info" style="border-radius: 8px; font-size: 12px;">
                                                <i class="fas fa-info-circle"></i> Rincian
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 40px; color: #64748b;">
                                        <i class="fas fa-folder-open fa-3x" style="color: #cbd5e1; margin-bottom: 12px;"></i>
                                        <div style="font-weight: 600;">Belum Ada Arsip Dokumen Hasil Layanan</div>
                                        <p style="font-size: 12.5px; margin: 4px 0 0 0;">Arsip SK resmi akan muncul secara otomatis setelah berkas dinyatakan selesai dan diterbitkan.</p>
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

