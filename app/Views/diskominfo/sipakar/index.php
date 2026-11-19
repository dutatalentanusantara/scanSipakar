<div class="wrapper">
    <div class="mainwrap">
        <div class="sectionrow">
            
            <!-- Unified Header Nav -->
            <?php echo view(template() . '/sipakar/nav_header'); ?>

            <!-- Quick Action Cards -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 28px;">
                <div style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: white; border-radius: 18px; padding: 24px; box-shadow: 0 8px 24px rgba(2, 132, 199, 0.22); display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="font-size: 32px; margin-bottom: 12px;"><i class="fas fa-file-signature"></i></div>
                        <h3 style="color: white; font-size: 19px; font-weight: 800; margin: 0 0 8px 0;">Pengajuan Layanan Online</h3>
                        <p style="font-size: 13.5px; opacity: 0.9; margin: 0 0 18px 0; line-height: 1.5;">Daftarkan usulan Kenaikan Pangkat atau Kenaikan Gaji Berkala secara digital tanpa perlu antre fisik.</p>
                    </div>
                    <a href="<?= base_url('sipakar/pengajuan'); ?>" style="display: inline-block; background: white; color: #0284c7; padding: 10px 18px; border-radius: 10px; font-weight: 700; font-size: 14px; text-decoration: none; text-align: center; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                        Mulai Pengajuan <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div style="background: linear-gradient(135deg, #059669 0%, #047857 100%); color: white; border-radius: 18px; padding: 24px; box-shadow: 0 8px 24px rgba(5, 150, 105, 0.22); display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="font-size: 32px; margin-bottom: 12px;"><i class="fas fa-search-location"></i></div>
                        <h3 style="color: white; font-size: 19px; font-weight: 800; margin: 0 0 8px 0;">Lacak Status Pengajuan</h3>
                        <p style="font-size: 13.5px; opacity: 0.9; margin: 0 0 18px 0; line-height: 1.5;">Pantau posisi berkas usulan secara transparan mulai dari verifikasi hingga penerbitan SK resmi.</p>
                    </div>
                    <a href="<?= base_url('sipakar/tracking'); ?>" style="display: inline-block; background: white; color: #059669; padding: 10px 18px; border-radius: 10px; font-weight: 700; font-size: 14px; text-decoration: none; text-align: center; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                        Cek Nomor Resi/NIP <i class="fas fa-search ml-1"></i>
                    </a>
                </div>

                <div style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); color: white; border-radius: 18px; padding: 24px; box-shadow: 0 8px 24px rgba(79, 70, 229, 0.22); display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <div style="font-size: 32px; margin-bottom: 12px;"><i class="fas fa-folder-open"></i></div>
                        <h3 style="color: white; font-size: 19px; font-weight: 800; margin: 0 0 8px 0;">Hasil Layanan & Pengarsipan</h3>
                        <p style="font-size: 13.5px; opacity: 0.9; margin: 0 0 18px 0; line-height: 1.5;">Akses dan unduh berkas hasil resmi SK KGB dan SK Kenaikan Pangkat yang telah disahkan.</p>
                    </div>
                    <a href="<?= base_url('sipakar/arsip'); ?>" style="display: inline-block; background: white; color: #4f46e5; padding: 10px 18px; border-radius: 10px; font-weight: 700; font-size: 14px; text-decoration: none; text-align: center; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                        Buka Arsip Digital <i class="fas fa-archive ml-1"></i>
                    </a>
                </div>
            </div>

            <!-- KPI Mini Summary -->
            <div class="sipakar-card" style="padding: 20px 24px; margin-bottom: 28px;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; text-align: center;">
                    <div style="border-right: 1px solid #e2e8f0; padding-right: 10px;">
                        <span style="font-size: 26px; font-weight: 800; color: #0284c7;"><?= number_format($stats['total'] ?? 0); ?></span>
                        <div style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; margin-top: 2px;">Total Pengajuan</div>
                    </div>
                    <div style="border-right: 1px solid #e2e8f0; padding-right: 10px;">
                        <span style="font-size: 26px; font-weight: 800; color: #10b981;"><?= number_format($stats['selesai'] ?? 0); ?></span>
                        <div style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; margin-top: 2px;">SK Terbit (Selesai)</div>
                    </div>
                    <div style="border-right: 1px solid #e2e8f0; padding-right: 10px;">
                        <span style="font-size: 26px; font-weight: 800; color: #f59e0b;"><?= number_format($stats['dalam_proses'] ?? 0); ?></span>
                        <div style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; margin-top: 2px;">Dalam Proses</div>
                    </div>
                    <div style="border-right: 1px solid #e2e8f0; padding-right: 10px;">
                        <span style="font-size: 26px; font-weight: 800; color: #ef4444;"><?= number_format($stats['perbaikan'] ?? 0); ?></span>
                        <div style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; margin-top: 2px;">Perlu Perbaikan</div>
                    </div>
                    <div>
                        <span style="font-size: 26px; font-weight: 800; color: #8b5cf6;"><?= $stats['persen_selesai'] ?? 0; ?>%</span>
                        <div style="font-size: 12px; font-weight: 600; color: #64748b; text-transform: uppercase; margin-top: 2px;">Ketepatan Layanan</div>
                    </div>
                </div>
            </div>

            <!-- Informasi Layanan: Persyaratan & Jadwal (KP & KGB) -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px;">
                
                <!-- Card Kenaikan Pangkat -->
                <div class="sipakar-card">
                    <div class="sipakar-card-title">
                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 10px; background: #e0f2fe; color: #0284c7;">
                            <i class="fas fa-medal"></i>
                        </span>
                        <div>
                            <span>Kenaikan Pangkat (KP) ASN</span>
                            <div style="font-size: 12.5px; font-weight: normal; color: #64748b;">Reguler, Pilihan Struktural, Fungsional & Penyesuaian Ijazah</div>
                        </div>
                    </div>

                    <div style="background: #f8fafc; border-left: 4px solid #0284c7; padding: 12px 16px; border-radius: 0 8px 8px 0; margin-bottom: 18px;">
                        <strong style="color: #0f2b5c; font-size: 13.5px;"><i class="far fa-calendar-alt"></i> Periode & Jadwal Usulan:</strong>
                        <p style="margin: 4px 0 0 0; font-size: 13px; color: #475569;">
                            Sesuai ketentuan BKN, kenaikan pangkat dilaksanakan 6 periode dalam setahun (Februari, April, Juni, Agustus, Oktober, dan Desember). Batas pengunggahan berkas di SIPAKAR maksimal 2 bulan sebelum TMT KP.
                        </p>
                    </div>

                    <h4 style="font-size: 14px; font-weight: 700; color: #1e293b; margin: 0 0 10px 0;">Dokumen Persyaratan:</h4>
                    <ul style="padding-left: 18px; margin: 0 0 18px 0; font-size: 13.5px; color: #334155; line-height: 1.8;">
                        <li><strong>SK Pangkat / KGB Terakhir</strong> <span style="color:#ef4444;">*</span></li>
                        <li><strong>SKP 1 Tahun Terakhir</strong> <span style="color:#ef4444;">*</span></li>
                        <li><strong>SKP 2 Tahun Terakhir</strong> <span style="color:#ef4444;">*</span></li>
                        <li><strong>SK Jabatan Struktural / Pelaksana</strong> <span style="color:#ef4444;">*</span></li>
                        <li>Daftar Riwayat Jabatan <em style="color:#64748b;">(opsional)</em></li>
                        <li>Sertifikat Ujian Kompetensi <em style="color:#64748b;">(opsional)</em></li>
                        <li>Dokumen Pendukung Lainnya <em style="color:#64748b;">(opsional)</em></li>
                    </ul>

                    <a href="<?= base_url('sipakar/pengajuan?jenis=KP'); ?>" class="btn btn-outline-primary btn-block" style="border-radius: 10px; font-weight: 600;">
                        Ajukan Kenaikan Pangkat <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                </div>

                <!-- Card Kenaikan Gaji Berkala -->
                <div class="sipakar-card">
                    <div class="sipakar-card-title">
                        <span style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 10px; background: #ecfdf5; color: #10b981;">
                            <i class="fas fa-money-check-alt"></i>
                        </span>
                        <div>
                            <span>Kenaikan Gaji Berkala (KGB)</span>
                            <div style="font-size: 12.5px; font-weight: normal; color: #64748b;">Hak Normatif Setiap 2 Tahun Sekali</div>
                        </div>
                    </div>

                    <div style="background: #f8fafc; border-left: 4px solid #10b981; padding: 12px 16px; border-radius: 0 8px 8px 0; margin-bottom: 18px;">
                        <strong style="color: #0f2b5c; font-size: 13.5px;"><i class="far fa-calendar-alt"></i> Periode & Ketentuan:</strong>
                        <p style="margin: 4px 0 0 0; font-size: 13px; color: #475569;">
                            Diberikan kepada PNS yang telah mencapai masa kerja golongan setiap 2 tahun sekali dan memiliki penilaian kinerja bernilai baik. Usulan diajukan 1 bulan sebelum jatuh tempo TMT KGB.
                        </p>
                    </div>

                    <h4 style="font-size: 14px; font-weight: 700; color: #1e293b; margin: 0 0 10px 0;">Dokumen Persyaratan:</h4>
                    <ul style="padding-left: 18px; margin: 0 0 18px 0; font-size: 13.5px; color: #334155; line-height: 1.8;">
                        <li><strong>SK Pangkat / KGB Terakhir</strong> <span style="color:#ef4444;">*</span></li>
                        <li><strong>SKP 1 Tahun Terakhir</strong> <span style="color:#ef4444;">*</span></li>
                        <li><strong>SKP 2 Tahun Terakhir</strong> <span style="color:#ef4444;">*</span></li>
                        <li><strong>SK Jabatan Struktural / Pelaksana</strong> <span style="color:#ef4444;">*</span></li>
                        <li>Daftar Riwayat Jabatan <em style="color:#64748b;">(opsional)</em></li>
                        <li>Sertifikat Ujian Kompetensi <em style="color:#64748b;">(opsional)</em></li>
                        <li>Dokumen Pendukung Lainnya <em style="color:#64748b;">(opsional)</em></li>
                    </ul>

                    <a href="<?= base_url('sipakar/pengajuan?jenis=KGB'); ?>" class="btn btn-outline-success btn-block" style="border-radius: 10px; font-weight: 600;">
                        Ajukan Kenaikan Gaji Berkala <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                </div>

            </div>

            <!-- Alur 9 Tahapan Proses Bisnis SIPAKAR -->
            <div class="sipakar-card">
                <div class="sipakar-card-title">
                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border-radius: 10px; background: #fef3c7; color: #d97706;">
                        <i class="fas fa-project-diagram"></i>
                    </span>
                    <div>
                        <span>Siklus 9 Tahapan Proses Bisnis SIPAKAR</span>
                        <div style="font-size: 12.5px; font-weight: normal; color: #64748b;">Standar Operasional Prosedur Pelayanan Digital Kecamatan Siantar Barat</div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; margin-top: 14px;">
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px;">
                        <span style="display: inline-block; background: #0284c7; color: white; border-radius: 50%; width: 24px; height: 24px; font-size: 12px; line-height: 24px; text-align: center; font-weight: 800;">1</span>
                        <h5 style="font-size: 13.5px; font-weight: 700; margin: 8px 0 4px 0; color: #0f2b5c;">Sosialisasi & Jadwal</h5>
                        <p style="font-size: 12px; color: #64748b; margin: 0; line-height: 1.4;">Penyampaian informasi persyaratan dan tenggat waktu usulan KP/KGB.</p>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px;">
                        <span style="display: inline-block; background: #0284c7; color: white; border-radius: 50%; width: 24px; height: 24px; font-size: 12px; line-height: 24px; text-align: center; font-weight: 800;">2</span>
                        <h5 style="font-size: 13.5px; font-weight: 700; margin: 8px 0 4px 0; color: #0f2b5c;">Pengajuan Online</h5>
                        <p style="font-size: 12px; color: #64748b; margin: 0; line-height: 1.4;">Pengisian form identitas dan upload dokumen digital oleh ASN.</p>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px;">
                        <span style="display: inline-block; background: #0284c7; color: white; border-radius: 50%; width: 24px; height: 24px; font-size: 12px; line-height: 24px; text-align: center; font-weight: 800;">3</span>
                        <h5 style="font-size: 13.5px; font-weight: 700; margin: 8px 0 4px 0; color: #0f2b5c;">Resi Tiket Registrasi</h5>
                        <p style="font-size: 12px; color: #64748b; margin: 0; line-height: 1.4;">Penerbitan kode tiket unik (SPK) untuk pelacakan permohonan.</p>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px;">
                        <span style="display: inline-block; background: #0284c7; color: white; border-radius: 50%; width: 24px; height: 24px; font-size: 12px; line-height: 24px; text-align: center; font-weight: 800;">4</span>
                        <h5 style="font-size: 13.5px; font-weight: 700; margin: 8px 0 4px 0; color: #0f2b5c;">Pemeriksaan Berkas</h5>
                        <p style="font-size: 12px; color: #64748b; margin: 0; line-height: 1.4;">Verifikasi kelengkapan dan validasi administrasi oleh operator.</p>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px;">
                        <span style="display: inline-block; background: #0284c7; color: white; border-radius: 50%; width: 24px; height: 24px; font-size: 12px; line-height: 24px; text-align: center; font-weight: 800;">5</span>
                        <h5 style="font-size: 13.5px; font-weight: 700; margin: 8px 0 4px 0; color: #0f2b5c;">Tindak Lanjut / Revisi</h5>
                        <p style="font-size: 12px; color: #64748b; margin: 0; line-height: 1.4;">Perbaikan mandiri jika ada berkas yang kurang atau tidak sah.</p>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px;">
                        <span style="display: inline-block; background: #0284c7; color: white; border-radius: 50%; width: 24px; height: 24px; font-size: 12px; line-height: 24px; text-align: center; font-weight: 800;">6</span>
                        <h5 style="font-size: 13.5px; font-weight: 700; margin: 8px 0 4px 0; color: #0f2b5c;">Persetujuan Internal</h5>
                        <p style="font-size: 12px; color: #64748b; margin: 0; line-height: 1.4;">Validasi Kasubbag Kepegawaian dan paraf pejabat berwenang.</p>
                    </div>

                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px;">
                        <span style="display: inline-block; background: #0284c7; color: white; border-radius: 50%; width: 24px; height: 24px; font-size: 12px; line-height: 24px; text-align: center; font-weight: 800;">7</span>
                        <h5 style="font-size: 13.5px; font-weight: 700; margin: 8px 0 4px 0; color: #0f2b5c;">Penerusan ke BKPSDM</h5>
                        <p style="font-size: 12px; color: #64748b; margin: 0; line-height: 1.4;">Pengiriman berkas usulan kolektif KP ke Badan Kepegawaian Daerah.</p>
                    </div>

                    <div style="background: #dcfce7; border: 1px solid #bbf7d0; border-radius: 12px; padding: 14px;">
                        <span style="display: inline-block; background: #16a34a; color: white; border-radius: 50%; width: 24px; height: 24px; font-size: 12px; line-height: 24px; text-align: center; font-weight: 800;">8</span>
                        <h5 style="font-size: 13.5px; font-weight: 700; margin: 8px 0 4px 0; color: #166534;">Hasil Layanan & Arsip</h5>
                        <p style="font-size: 12px; color: #166534; margin: 0; line-height: 1.4;">Penerbitan SK resmi, notifikasi pemohon, dan pengarsipan digital.</p>
                    </div>

                    <div style="background: #ede9fe; border: 1px solid #ddd6fe; border-radius: 12px; padding: 14px;">
                        <span style="display: inline-block; background: #7c3aed; color: white; border-radius: 50%; width: 24px; height: 24px; font-size: 12px; line-height: 24px; text-align: center; font-weight: 800;">9</span>
                        <h5 style="font-size: 13.5px; font-weight: 700; margin: 8px 0 4px 0; color: #5b21b6;">Evaluasi & Audit Mutu</h5>
                        <p style="font-size: 12px; color: #5b21b6; margin: 0; line-height: 1.4;">Pelaporan berkala untuk pimpinan dan pemeliharaan integritas sistem.</p>
                    </div>
                </div>

                <div style="margin-top: 18px; padding: 12px 16px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 10px; font-size: 12.5px; color: #92400e; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-info-circle fa-lg"></i>
                    <span><strong>Ketentuan Penting:</strong> SIPAKAR berfungsi sebagai sistem informasi, pengajuan, pencatatan, pemantauan, dan pengarsipan. Pemeriksaan, persetujuan, serta penetapan keputusan resmi SK Kenaikan Pangkat maupun Kenaikan Gaji Berkala tetap menjadi kewenangan pejabat atau instansi yang berwenang (BKPSDM Kota Pematangsiantar / Pemerintah Kecamatan).</span>
                </div>
            </div>

        </div>
    </div>
</div>

