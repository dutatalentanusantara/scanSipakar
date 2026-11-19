<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak | Kecamatan Siantar Barat</title>
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
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f3d2e 100%);
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
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.25);
        }
        .error-code {
            display: inline-block;
            font-size: 64px;
            font-weight: 800;
            line-height: 1;
            background: linear-gradient(135deg, #b91c1c, #ea580c);
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
        .action-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 11px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-primary-action {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(5, 150, 105, 0.3);
        }
        .btn-secondary-action {
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
        }
        .btn-secondary-action:hover {
            background: #e2e8f0;
            color: #0f172a;
        }
        .brand-footer {
            margin-top: 24px;
            font-size: 12px;
            color: #64748b;
        }
        .debug-notice {
            margin-top: 18px;
            padding: 10px 14px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            font-size: 12px;
            color: #991b1b;
            text-align: left;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <main class="error-card">
        <div class="error-code">403</div>
        <h1>Akses Ditolak / Sesi Kedaluwarsa</h1>
        <p>
            Tindakan yang Anda minta tidak diizinkan atau sesi keamanan CSRF formulir telah kedaluwarsa. Silakan muat ulang halaman formulir dan coba kembali.
        </p>

        <?php if (ENVIRONMENT !== 'production' && !empty($message)): ?>
            <div class="debug-notice">
                <strong>Detail:</strong> <?= esc((string) $message); ?>
            </div>
            <div style="height: 16px;"></div>
        <?php endif; ?>

        <div class="action-buttons">
            <a class="btn-action btn-primary-action" href="<?= function_exists('base_url') ? esc(base_url('administrator')) : '/administrator'; ?>">
                Login Administrator
            </a>
            <a class="btn-action btn-secondary-action" href="javascript:history.back()">
                Kembali
            </a>
            <a class="btn-action btn-secondary-action" href="<?= function_exists('base_url') ? esc(base_url()) : '/'; ?>">
                Beranda Portal
            </a>
        </div>
        <div class="brand-footer">Pemerintah Kecamatan Siantar Barat &bull; Kota Pematangsiantar</div>
    </main>
</body>
</html>

