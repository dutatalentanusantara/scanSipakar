<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kinerja Layanan SIPAKAR - Kecamatan Siantar Barat</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 20px 40px;
        }
        .kop-surat {
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .kop-logo {
            position: absolute;
            left: 10px;
            top: 5px;
            width: 75px;
            height: auto;
        }
        .kop-text {
            text-align: center;
            width: 100%;
        }
        .kop-text h3 {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .kop-text h2 {
            margin: 2px 0;
            font-size: 16pt;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .kop-text p {
            margin: 0;
            font-size: 10pt;
            font-style: italic;
        }
        .judul-laporan {
            text-align: center;
            margin-bottom: 20px;
        }
        .judul-laporan h4 {
            margin: 0;
            font-size: 13pt;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .judul-laporan p {
            margin: 4px 0 0 0;
            font-size: 11pt;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin-bottom: 30px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
        }
        table.data-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .ttd-block {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
            page-break-inside: avoid;
        }
        .ttd-box {
            width: 250px;
            text-align: center;
            font-size: 11pt;
        }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

<div class="no-print" style="background: #0284c7; color: white; padding: 12px 20px; margin-bottom: 20px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
    <div><strong>Pratinjau Cetak Dokumen Resmi SIPAKAR</strong> (Siap dicetak atau disimpan sebagai PDF)</div>
    <div>
        <button onclick="window.print()" style="background: white; color: #0284c7; border: none; padding: 6px 16px; border-radius: 6px; font-weight: bold; cursor: pointer;">
            Cetak Dokumen
        </button>
        <button onclick="window.close()" style="background: transparent; color: white; border: 1px solid white; padding: 6px 14px; border-radius: 6px; margin-left: 8px; cursor: pointer;">
            Tutup
        </button>
    </div>
</div>

<!-- KOP SURAT -->
<div class="kop-surat">
    <img src="<?= base_url('asset/logo/logo.png'); ?>" class="kop-logo" alt="Logo Pemko" onerror="this.style.display='none'">
    <div class="kop-text">
        <h3>PEMERINTAH KOTA PEMATANGSIANTAR</h3>
        <h2>KECAMATAN SIANTAR BARAT</h2>
        <p>Jl. Bangau No.3, Sipinggol-Pinggol, Kec. Siantar Barat, Kota Pematangsiantar, Sumatera Utara 21114</p>
        <p>Website: siantarbarat.pematangsiantar.go.id &bull; Email: siantarbarat@pematangsiantar.go.id</p>
    </div>
</div>

<!-- JUDUL LAPORAN -->
<div class="judul-laporan">
    <h4>LAPORAN REKAPITULASI PELAYANAN SIPAKAR</h4>
    <p>Sistem Administrasi Kenaikan Pangkat & Kenaikan Gaji Berkala ASN Tahun <?= $tahun; ?></p>
</div>

<!-- TABEL REKAPITULASI -->
<table class="data-table">
    <thead>
        <tr>
            <th style="width: 30px;">No</th>
            <th style="width: 100px;">No. Tiket</th>
            <th>NIP / Nama Pegawai</th>
            <th>Unit Kerja / Jabatan</th>
            <th style="width: 70px;">Layanan</th>
            <th style="width: 80px;">TMT Usulan</th>
            <th style="width: 100px;">Status Mutu</th>
            <th>No. SK Resmi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($rows)): 
            $no = 1;
            foreach ($rows as $r):
        ?>
            <tr>
                <td style="text-align: center;"><?= $no++; ?></td>
                <td><?= htmlspecialchars($r['nomor_tiket']); ?></td>
                <td>
                    <strong><?= htmlspecialchars($r['nama_lengkap']); ?></strong><br>
                    NIP. <?= htmlspecialchars($r['nip']); ?>
                </td>
                <td>
                    <?= htmlspecialchars($r['unit_kerja']); ?><br>
                    <small><?= htmlspecialchars($r['jabatan']); ?></small>
                </td>
                <td style="text-align: center;"><strong><?= $r['jenis_layanan']; ?></strong></td>
                <td style="text-align: center;"><?= !empty($r['tmt_usulan']) ? date('d/m/Y', strtotime($r['tmt_usulan'])) : '-'; ?></td>
                <td style="text-align: center;"><?= htmlspecialchars($r['status']); ?></td>
                <td><?= htmlspecialchars($r['nomor_sk_resmi'] ?: '-'); ?></td>
            </tr>
        <?php endforeach; else: ?>
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px;">Tidak ada data permohonan.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<!-- RINGKASAN REKAPITULASI -->
<div style="font-size: 11pt; margin-bottom: 25px; line-height: 1.6;">
    <strong>Ringkasan Kinerja Pelayanan:</strong>
    <ul>
        <li>Total Usulan Terdaftar: <?= $stats['total'] ?? 0; ?> berkas (KP: <?= $stats['kp'] ?? 0; ?>, KGB: <?= $stats['kgb'] ?? 0; ?>)</li>
        <li>Telah Selesai (SK Terbit): <?= $stats['selesai'] ?? 0; ?> berkas (<?= $stats['persen_selesai'] ?? 0; ?>%)</li>
        <li>Dalam Proses Administrasi: <?= $stats['dalam_proses'] ?? 0; ?> berkas</li>
        <li>Membutuhkan Perbaikan ASN: <?= $stats['perbaikan'] ?? 0; ?> berkas</li>
    </ul>
</div>

<!-- TANDA TANGAN PEJABAT -->
<div class="ttd-block">
    <div class="ttd-box">
        Mengetahui,<br>
        <strong>CAMAT SIANTAR BARAT</strong>
        <br><br><br><br>
        <strong>( .................................................... )</strong><br>
        NIP. ...................................................
    </div>

    <div class="ttd-box">
        Pematangsiantar, <?= tgl_indo(date('Y-m-d')); ?><br>
        Kasubbag Umum & Kepegawaian
        <br><br><br><br>
        <strong>( .................................................... )</strong><br>
        NIP. ...................................................
    </div>
</div>

</body>
</html>

