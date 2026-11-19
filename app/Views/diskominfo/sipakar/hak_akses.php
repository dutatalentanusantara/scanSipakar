<div class="wrapper">
    <div class="mainwrap">
        <div class="sectionrow">
            
            <!-- Unified Header Nav -->
            <?php echo view(template() . '/sipakar/nav_header'); ?>

            <div class="sipakar-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; flex-wrap: wrap; gap: 14px;">
                    <div>
                        <div class="sipakar-card-title" style="margin-bottom: 4px;">
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 10px; background: #e0e7ff; color: #4338ca;">
                                <i class="fas fa-user-lock"></i>
                            </span>
                            <span>Pengaturan Hak Akses (RBAC) SIPAKAR</span>
                        </div>
                        <p style="font-size: 13px; color: #64748b; margin: 0;">Manajemen hierarki otorisasi, pembatasan hak akses berbasis role, dan proteksi server-side</p>
                    </div>

                    <div style="background: #eef2ff; border: 1px solid #c7d2fe; color: #3730a3; padding: 8px 16px; border-radius: 10px; font-size: 12.5px; font-weight: 700;">
                        <i class="fas fa-shield-virus mr-1"></i> Backend-Enforced Security Active
                    </div>
                </div>

                <!-- Banner Informasi Keamanan Server -->
                <div style="background: #f8fafc; border-left: 4px solid #0284c7; border-radius: 10px; padding: 16px 20px; margin-bottom: 26px;">
                    <div style="display: flex; gap: 14px; align-items: flex-start;">
                        <div style="color: #0284c7; font-size: 24px; line-height: 1;">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <div style="font-weight: 700; font-size: 14px; color: #0f2b5c; margin-bottom: 4px;">
                                Prinsip Keamanan & Otorisasi Ketat Sisi Server (Backend)
                            </div>
                            <p style="font-size: 12.5px; color: #475569; margin: 0; line-height: 1.6;">
                                Seluruh hak akses fitur SIPAKAR diverifikasi langsung oleh <strong>PHP Server Controller</strong> menggunakan metode <code>require_login()</code> dan <code>require_role()</code>. Manipulasi elemen visual atau pengubahan parameter pada sisi browser / frontend <em>tidak akan dapat menembus proteksi server</em>. Setiap percobaan akses tanpa izin atau pelampauan wewenang akan otomatis diblokir, dialihkan, dan dicatat ke dalam <strong>Audit Log Keamanan</strong> dengan metadata IP Address dan username.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Matriks Role-Based Access Control -->
                <div style="margin-bottom: 28px;">
                    <h3 style="font-size: 16px; font-weight: 800; color: #0f2b5c; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-table text-primary"></i> Matriks Hak Akses Pengguna
                    </h3>
                    
                    <div class="table-responsive" style="border: 1px solid #e2e8f0; border-radius: 12px;">
                        <table class="table table-hover table-bordered" style="font-size: 12.5px; margin-bottom: 0;">
                            <thead style="background: #0f2b5c; color: #ffffff; text-align: center;">
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle; padding: 12px; width: 180px; text-align: left;">Role & Level User</th>
                                    <th colspan="4" style="background: #1e3a8a; padding: 8px;">Layanan Publik ASN</th>
                                    <th colspan="4" style="background: #0369a1; padding: 8px;">Operasional & Verifikasi</th>
                                    <th colspan="2" style="background: #047857; padding: 8px;">Pengendalian Mutu</th>
                                    <th colspan="2" style="background: #b91c1c; padding: 8px;">Keamanan & RBAC</th>
                                    <th rowspan="2" style="vertical-align: middle; padding: 12px; width: 90px;">Aksi</th>
                                </tr>
                                <tr style="background: #f1f5f9; color: #334155; font-size: 11px;">
                                    <th title="Informasi Layanan" style="padding: 6px;">Info</th>
                                    <th title="Pengajuan Online" style="padding: 6px;">Ajukan</th>
                                    <th title="Tracking Pengajuan" style="padding: 6px;">Track</th>
                                    <th title="Arsip Dokumen" style="padding: 6px;">Arsip</th>
                                    <th title="Riwayat Pelayanan" style="padding: 6px;">Riwayat</th>
                                    <th title="Dashboard Monitoring" style="padding: 6px;">Dash</th>
                                    <th title="Verifikasi Berkas" style="padding: 6px;">Verif</th>
                                    <th title="Persetujuan SK Akhir" style="padding: 6px;">Setuju</th>
                                    <th title="Evaluasi Kinerja & SLA" style="padding: 6px;">Eval</th>
                                    <th title="Laporan & Rekapitulasi" style="padding: 6px;">Lapor</th>
                                    <th title="Audit Log Keamanan" style="padding: 6px;">Audit</th>
                                    <th title="Pengaturan Hak Akses" style="padding: 6px;">RBAC</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($roles)): 
                                    foreach ($roles as $r):
                                ?>
                                    <tr>
                                        <td style="padding: 12px; vertical-align: middle;">
                                            <div style="font-weight: 700; color: #0f2b5c;"><?= htmlspecialchars($r['nama_role']); ?></div>
                                            <div style="font-size: 11px; color: #64748b;">
                                                Level: <span class="badge badge-secondary" style="font-family: monospace; font-size: 10px;"><?= htmlspecialchars($r['level_user']); ?></span>
                                            </div>
                                        </td>
                                        
                                        <!-- Layanan Publik ASN -->
                                        <td style="text-align: center; vertical-align: middle;">
                                            <?= $r['akses_informasi'] ? '<i class="fas fa-check-circle text-success" style="font-size: 15px;"></i>' : '<i class="fas fa-times-circle text-muted" style="opacity: 0.3;"></i>'; ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <?= $r['akses_pengajuan'] ? '<i class="fas fa-check-circle text-success" style="font-size: 15px;"></i>' : '<i class="fas fa-times-circle text-muted" style="opacity: 0.3;"></i>'; ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <?= $r['akses_tracking'] ? '<i class="fas fa-check-circle text-success" style="font-size: 15px;"></i>' : '<i class="fas fa-times-circle text-muted" style="opacity: 0.3;"></i>'; ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <?= $r['akses_arsip'] ? '<i class="fas fa-check-circle text-success" style="font-size: 15px;"></i>' : '<i class="fas fa-times-circle text-muted" style="opacity: 0.3;"></i>'; ?>
                                        </td>

                                        <!-- Operasional & Verifikasi -->
                                        <td style="text-align: center; vertical-align: middle;">
                                            <?= $r['akses_riwayat'] ? '<i class="fas fa-check-circle text-success" style="font-size: 15px;"></i>' : '<i class="fas fa-times-circle text-muted" style="opacity: 0.3;"></i>'; ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <?= $r['akses_dashboard'] ? '<i class="fas fa-check-circle text-success" style="font-size: 15px;"></i>' : '<i class="fas fa-times-circle text-muted" style="opacity: 0.3;"></i>'; ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <?= $r['akses_verifikasi'] ? '<i class="fas fa-check-circle text-success" style="font-size: 15px;"></i>' : '<i class="fas fa-times-circle text-muted" style="opacity: 0.3;"></i>'; ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <?= $r['akses_persetujuan'] ? '<i class="fas fa-check-circle text-success" style="font-size: 15px;"></i>' : '<i class="fas fa-times-circle text-muted" style="opacity: 0.3;"></i>'; ?>
                                        </td>

                                        <!-- Pengendalian Mutu -->
                                        <td style="text-align: center; vertical-align: middle;">
                                            <?= $r['akses_evaluasi'] ? '<i class="fas fa-check-circle text-success" style="font-size: 15px;"></i>' : '<i class="fas fa-times-circle text-muted" style="opacity: 0.3;"></i>'; ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <?= $r['akses_laporan'] ? '<i class="fas fa-check-circle text-success" style="font-size: 15px;"></i>' : '<i class="fas fa-times-circle text-muted" style="opacity: 0.3;"></i>'; ?>
                                        </td>

                                        <!-- Keamanan & RBAC -->
                                        <td style="text-align: center; vertical-align: middle;">
                                            <?= $r['akses_audit_log'] ? '<i class="fas fa-check-circle text-danger" style="font-size: 15px;"></i>' : '<i class="fas fa-times-circle text-muted" style="opacity: 0.3;"></i>'; ?>
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            <?= $r['akses_hak_akses'] ? '<i class="fas fa-check-circle text-danger" style="font-size: 15px;"></i>' : '<i class="fas fa-times-circle text-muted" style="opacity: 0.3;"></i>'; ?>
                                        </td>

                                        <td style="text-align: center; vertical-align: middle;">
                                            <button type="button" class="btn btn-sm btn-outline-primary" style="font-size: 11px; padding: 4px 8px; border-radius: 6px;" onclick='editRole(<?= json_encode($r); ?>)'>
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Form Modifikasi Kewenangan Role (Modal / Card Interaktif) -->
                <div id="roleEditCard" style="display: none; background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 14px; padding: 22px; margin-top: 24px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                        <h4 style="font-size: 16px; font-weight: 800; color: #0f2b5c; margin: 0;">
                            <i class="fas fa-user-edit text-primary mr-1"></i> Edit Konfigurasi Kewenangan: <span id="labelRoleName" style="color: #0284c7;"></span>
                        </h4>
                        <button type="button" class="btn btn-sm btn-light" onclick="closeEditRole()" style="border-radius: 8px;">
                            <i class="fas fa-times"></i> Tutup
                        </button>
                    </div>

                    <form action="<?= base_url('sipakar/hak_akses'); ?>" method="post">
                        <!-- CSRF Token Protection -->
                        <?= csrf_field(); ?>
                        <input type="hidden" name="level_user" id="input_level_user" value="">

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label style="font-weight: 700; font-size: 12.5px; color: #334155;">Nama Role</label>
                                <input type="text" name="nama_role" id="input_nama_role" class="form-control" style="border-radius: 8px; font-size: 13px;" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-weight: 700; font-size: 12.5px; color: #334155;">Deskripsi Role</label>
                                <input type="text" name="deskripsi" id="input_deskripsi" class="form-control" style="border-radius: 8px; font-size: 13px;">
                            </div>
                        </div>

                        <div style="font-weight: 700; font-size: 13px; color: #0f2b5c; margin-top: 10px; margin-bottom: 12px;">
                            Centang Fitur & Modul yang Diizinkan:
                        </div>

                        <div class="row" style="margin-bottom: 18px;">
                            <!-- Kolom 1 -->
                            <div class="col-md-4">
                                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; margin-bottom: 12px;">
                                    <div style="font-weight: 700; font-size: 12.5px; color: #1e3a8a; margin-bottom: 8px;">
                                        <i class="fas fa-globe mr-1"></i> Layanan Publik ASN
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="check_akses_informasi" name="akses_informasi" value="1">
                                        <label class="custom-control-label" for="check_akses_informasi" style="font-size: 12px;">Informasi Layanan</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="check_akses_pengajuan" name="akses_pengajuan" value="1">
                                        <label class="custom-control-label" for="check_akses_pengajuan" style="font-size: 12px;">Pengajuan Online</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="check_akses_tracking" name="akses_tracking" value="1">
                                        <label class="custom-control-label" for="check_akses_tracking" style="font-size: 12px;">Tracking Pengajuan</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="check_akses_arsip" name="akses_arsip" value="1">
                                        <label class="custom-control-label" for="check_akses_arsip" style="font-size: 12px;">Arsip Dokumen Resmi</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom 2 -->
                            <div class="col-md-4">
                                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; margin-bottom: 12px;">
                                    <div style="font-weight: 700; font-size: 12.5px; color: #0369a1; margin-bottom: 8px;">
                                        <i class="fas fa-cogs mr-1"></i> Operasional & Verifikasi
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="check_akses_riwayat" name="akses_riwayat" value="1">
                                        <label class="custom-control-label" for="check_akses_riwayat" style="font-size: 12px;">Riwayat Pelayanan ASN</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="check_akses_dashboard" name="akses_dashboard" value="1">
                                        <label class="custom-control-label" for="check_akses_dashboard" style="font-size: 12px;">Dashboard Monitoring</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="check_akses_verifikasi" name="akses_verifikasi" value="1">
                                        <label class="custom-control-label" for="check_akses_verifikasi" style="font-size: 12px;">Verifikasi Berkas Operator</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="check_akses_persetujuan" name="akses_persetujuan" value="1">
                                        <label class="custom-control-label" for="check_akses_persetujuan" style="font-size: 12px;">Persetujuan Akhir SK (Camat)</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom 3 -->
                            <div class="col-md-4">
                                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; margin-bottom: 12px;">
                                    <div style="font-weight: 700; font-size: 12.5px; color: #047857; margin-bottom: 8px;">
                                        <i class="fas fa-chart-bar mr-1"></i> Pengendalian & Keamanan
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="check_akses_evaluasi" name="akses_evaluasi" value="1">
                                        <label class="custom-control-label" for="check_akses_evaluasi" style="font-size: 12px;">Evaluasi Kinerja & SLA</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="check_akses_laporan" name="akses_laporan" value="1">
                                        <label class="custom-control-label" for="check_akses_laporan" style="font-size: 12px;">Laporan & Rekapitulasi</label>
                                    </div>
                                    <div class="custom-control custom-checkbox mb-2">
                                        <input type="checkbox" class="custom-control-input" id="check_akses_audit_log" name="akses_audit_log" value="1">
                                        <label class="custom-control-label text-danger" for="check_akses_audit_log" style="font-size: 12px; font-weight: 700;">Audit Log Keamanan</label>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="check_akses_hak_akses" name="akses_hak_akses" value="1">
                                        <label class="custom-control-label text-danger" for="check_akses_hak_akses" style="font-size: 12px; font-weight: 700;">Pengaturan Hak Akses (RBAC)</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="text-align: right; border-top: 1px solid #e2e8f0; padding-top: 14px;">
                            <button type="button" class="btn btn-secondary mr-2" onclick="closeEditRole()" style="border-radius: 8px; font-size: 13px;">Batal</button>
                            <button type="submit" name="submit_hak_akses" value="1" class="btn btn-primary" style="border-radius: 8px; font-size: 13px; font-weight: 700; background: #0284c7; border-color: #0284c7;">
                                <i class="fas fa-save mr-1"></i> Simpan Konfigurasi Role
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
function editRole(role) {
    document.getElementById('roleEditCard').style.display = 'block';
    document.getElementById('labelRoleName').innerText = role.nama_role + ' (' + role.level_user + ')';
    document.getElementById('input_level_user').value = role.level_user;
    document.getElementById('input_nama_role').value = role.nama_role;
    document.getElementById('input_deskripsi').value = role.deskripsi || '';

    // Checkboxes
    var fields = [
        'akses_informasi', 'akses_pengajuan', 'akses_tracking', 'akses_arsip',
        'akses_riwayat', 'akses_dashboard', 'akses_verifikasi', 'akses_persetujuan',
        'akses_evaluasi', 'akses_laporan', 'akses_audit_log', 'akses_hak_akses'
    ];

    fields.forEach(function(field) {
        var el = document.getElementById('check_' + field);
        if (el) {
            el.checked = (parseInt(role[field]) === 1);
        }
    });

    // Scroll smoothly to form
    document.getElementById('roleEditCard').scrollIntoView({ behavior: 'smooth' });
}

function closeEditRole() {
    document.getElementById('roleEditCard').style.display = 'none';
}
</script>

