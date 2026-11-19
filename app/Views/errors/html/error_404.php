<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | Kecamatan Siantar Barat</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #f1f5f9 0%, #e0f2fe 100%);
            color: #0f172a;
        }
        .error-card {
            max-width: 560px;
            width: 100%;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 40px 32px;
            text-align: center;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.08);
        }
        .error-code {
            display: inline-block;
            font-size: 64px;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, #1e3a8a, #0284c7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0 0 12px 0;
        }
        h1 {
            font-size: 22px;
            font-weight: 700;
            margin: 0 0 12px 0;
            color: #1e293b;
        }
        p {
            font-size: 15px;
            line-height: 1.6;
            color: #475569;
            margin: 0 0 24px 0;
        }
        .btn-home {
            display: inline-block;
            background: linear-gradient(135deg, #1e3a8a, #0284c7);
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            padding: 12px 24px;
            border-radius: 10px;
            box-shadow: 0 6px 16px rgba(2, 132, 199, 0.25);
        }
        .brand-footer {
            margin-top: 24px;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <main class="error-card">
        <div class="error-code">404</div>
        <h1>Halaman Tidak Ditemukan</h1>
        <p>
            <?php if (ENVIRONMENT !== 'production' && !empty($message)): ?>
                <?= nl2br(esc((string) $message)) ?>
            <?php else: ?>
                Maaf, halaman atau berkas yang Anda cari tidak tersedia atau telah dipindahkan di Portal Resmi Pemerintah Kecamatan Siantar Barat.
            <?php endif; ?>
        </p>
        <a class="btn-home" href="<?= function_exists('base_url') ? esc(base_url()) : '/' ?>">Kembali ke Beranda</a>
        <div class="brand-footer">Pemerintah Kecamatan Siantar Barat &bull; Kota Pematangsiantar</div>
    </main>
</body>
</html>
