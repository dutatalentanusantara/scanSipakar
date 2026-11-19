<div class="wrapper">
    <div class="mainwrap">
        <div class="sectionrow">
            
            <!-- Unified Header Nav -->
            <?php echo view(template() . '/sipakar/nav_header'); ?>

            <div class="sipakar-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; flex-wrap: wrap; gap: 14px;">
                    <div>
                        <div class="sipakar-card-title" style="margin-bottom: 4px;">
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 10px; background: #fee2e2; color: #dc2626;">
                                <i class="fas fa-user-shield"></i>
                            </span>
                            <span>Audit Log & Keamanan Sistem SIPAKAR</span>
                        </div>
                        <p style="font-size: 13px; color: #64748b; margin: 0;">Pencatatan rekam jejak aktivitas, otentikasi, integritas data, dan kontrol akses</p>
                    </div>

                    <!-- Security Badge -->
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 8px 16px; border-radius: 10px; font-size: 12.5px; font-weight: 700;">
                        <i class="fas fa-lock mr-1"></i> Proteksi Enkripsi & Anti-Tamper Aktif
                    </div>
                </div>

                <!-- Prinsip Keamanan & Hak Akses -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 24px;">
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                        <div style="font-weight: 700; font-size: 13.5px; color: #0f2b5c; margin-bottom: 4px;">
                            <i class="fas fa-users-cog text-primary mr-1"></i> Kontrol Akses Berjenjang
                        </div>
                        <p style="font-size: 12px; color: #64748b; margin: 0;">Pemisahan kewenangan antara ASN Pemohon, Operator Verifikasi, dan Pejabat Penyetuju (Camat / Kasubbag).</p>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                        <div style="font-weight: 700; font-size: 13.5px; color: #0f2b5c; margin-bottom: 4px;">
                            <i class="fas fa-file-contract text-primary mr-1"></i> Integritas Dokumen Digital
                        </div>
                        <p style="font-size: 12px; color: #64748b; margin: 0;">Setiap dokumen hasil resmi memiliki nomor registrasi unik dan tersimpan dalam direktori terproteksi.</p>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
                        <div style="font-weight: 700; font-size: 13.5px; color: #0f2b5c; margin-bottom: 4px;">
                            <i class="fas fa-database text-primary mr-1"></i> Pencadangan Otomatis
                        </div>
                        <p style="font-size: 12px; color: #64748b; margin: 0;">Database SQL dan berkas arsip tersinkronisasi berkala dalam repositori cadangan sistem.</p>
                    </div>
                </div>

                <!-- Tabel Audit Trail -->
                <div class="table-responsive" style="border: 1px solid #e2e8f0; border-radius: 12px;">
                    <table class="table table-hover" style="font-size: 13px; margin-bottom: 0;">
                        <thead style="background: #f8fafc; color: #0f2b5c; font-weight: 700;">
                            <tr>
                                <th style="padding: 12px;">Waktu Log</th>
                                <th style="padding: 12px;">Aktor / Pelaku</th>
                                <th style="padding: 12px;">Aktivitas</th>
                                <th style="padding: 12px;">Detail & Keterangan</th>
                                <th style="padding: 12px;">Alamat IP</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($logs)): 
                                foreach ($logs as $l):
                            ?>
                                <tr>
                                    <td style="padding: 12px; font-size: 12px; color: #64748b; white-space: nowrap;">
                                        <i class="far fa-clock mr-1"></i> <?= date('d/m/Y H:i:s', strtotime($l['waktu'])); ?>
                                    </td>
                                    <td style="padding: 12px;">
                                        <strong><?= htmlspecialchars($l['aktor']); ?></strong>
                                        <?php if (!empty($l['nip'])): ?>
                                            <div style="font-size: 11px; color: #64748b;">NIP: <?= htmlspecialchars($l['nip']); ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 12px;">
                                        <span class="badge badge-light" style="font-size: 11.5px; border: 1px solid #cbd5e1; font-family: monospace;">
                                            <?= htmlspecialchars($l['aktivitas']); ?>
                                        </span>
                                    </td>
                                    <td style="padding: 12px; color: #334155;">
                                        <?= htmlspecialchars($l['detail']); ?>
                                    </td>
                                    <td style="padding: 12px; font-family: monospace; font-size: 11.5px; color: #64748b;">
                                        <?= htmlspecialchars($l['ip_address'] ?: '127.0.0.1'); ?>
                                    </td>
                                </tr>
                            <?php endforeach; else: ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; padding: 30px; color: #64748b;">
                                        Belum ada catatan aktivitas dalam log audit.
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

