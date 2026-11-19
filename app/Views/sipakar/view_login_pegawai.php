<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= esc($description ?? 'Login ASN SIPAKAR Siantar Barat') ?>">
    <title><?= esc($title ?? 'Login ASN – SIPAKAR') ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/logo.png') ?>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --sipakar-green:       #1a5c38;
            --sipakar-green-mid:   #217a4b;
            --sipakar-green-light: #2ecc71;
            --sipakar-gold:        #f39c12;
            --sipakar-dark:        #0f2419;
            --sipakar-bg:          #f0f9f4;
            --radius-xl:           18px;
            --radius-lg:           12px;
            --shadow-soft:         0 8px 40px rgba(26, 92, 56, .15);
            --shadow-hover:        0 16px 56px rgba(26, 92, 56, .22);
            --transition:          .25s cubic-bezier(.4,0,.2,1);
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--sipakar-bg);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* ── Background hero ── */
        .login-hero {
            position: fixed;
            inset: 0;
            background:
                linear-gradient(145deg, rgba(15,36,25,.92) 0%, rgba(26,92,56,.82) 60%, rgba(46,204,113,.18) 100%),
                url('<?= base_url('assets/images/bg_sipakar.jpg') ?>') center/cover no-repeat;
            z-index: 0;
        }
        .login-hero::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse 80% 60% at 50% 40%, rgba(255,255,255,.04), transparent);
        }

        /* ── Animated dots pattern ── */
        .dots-bg {
            position: fixed;
            inset: 0;
            z-index: 1;
            background-image: radial-gradient(circle, rgba(255,255,255,.07) 1px, transparent 1px);
            background-size: 36px 36px;
            pointer-events: none;
        }

        /* ── Main layout ── */
        .login-wrapper {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        .login-card {
            width: 100%;
            max-width: 460px;
            background: rgba(255,255,255,.97);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow-soft);
            overflow: hidden;
            animation: slideUp .45s var(--transition) both;
        }
        @keyframes slideUp {
            from { opacity:0; transform: translateY(28px); }
            to   { opacity:1; transform: translateY(0); }
        }

        /* ── Card header ── */
        .card-header-sipakar {
            background: linear-gradient(135deg, var(--sipakar-dark) 0%, var(--sipakar-green) 100%);
            padding: 32px 36px 28px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .card-header-sipakar::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: rgba(255,255,255,.05);
        }
        .header-logo {
            width: 68px;
            height: 68px;
            border-radius: 50%;
            border: 3px solid rgba(255,255,255,.25);
            object-fit: contain;
            background: #fff;
            padding: 4px;
            margin-bottom: 14px;
            box-shadow: 0 4px 16px rgba(0,0,0,.2);
        }
        .header-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: .5px;
            margin: 0 0 4px;
        }
        .header-subtitle {
            font-size: .82rem;
            color: rgba(255,255,255,.7);
            margin: 0;
        }
        .badge-sipakar {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(243,156,18,.18);
            border: 1px solid rgba(243,156,18,.35);
            color: var(--sipakar-gold);
            font-size: .72rem;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 20px;
            margin-top: 10px;
            letter-spacing: .4px;
        }

        /* ── Card body ── */
        .card-body-sipakar {
            padding: 32px 36px 28px;
        }

        /* ── Alerts ── */
        .alert-sipakar {
            border-radius: var(--radius-lg);
            font-size: .875rem;
            border: none;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 16px;
            margin-bottom: 20px;
            animation: fadeIn .3s ease both;
        }
        @keyframes fadeIn { from { opacity:0; transform: translateY(-6px); } to { opacity:1; transform: translateY(0); } }
        .alert-sipakar.danger  { background: #fef2f2; color: #b91c1c; border-left: 4px solid #ef4444; }
        .alert-sipakar.success { background: #f0fdf4; color: #15803d; border-left: 4px solid #22c55e; }
        .alert-sipakar.info    { background: #eff6ff; color: #1d4ed8; border-left: 4px solid #3b82f6; }

        /* ── Form controls ── */
        .form-label-sipakar {
            font-size: .82rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
            letter-spacing: .2px;
        }
        .input-group-sipakar {
            position: relative;
            margin-bottom: 18px;
        }
        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--sipakar-green-mid);
            font-size: 1rem;
            z-index: 3;
            pointer-events: none;
        }
        .form-control-sipakar {
            width: 100%;
            padding: 11px 42px 11px 40px;
            border: 1.5px solid #d1d5db;
            border-radius: var(--radius-lg);
            font-size: .92rem;
            color: #111827;
            background: #f9fafb;
            transition: border-color var(--transition), box-shadow var(--transition), background var(--transition);
            outline: none;
        }
        .form-control-sipakar:focus {
            border-color: var(--sipakar-green-mid);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(33,122,75,.12);
        }
        .form-control-sipakar.is-error {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239,68,68,.1);
        }
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            font-size: 1rem;
            padding: 2px 4px;
            z-index: 3;
            transition: color var(--transition);
        }
        .toggle-password:hover { color: var(--sipakar-green-mid); }

        /* ── NIP hint ── */
        .nip-info {
            font-size: .75rem;
            color: #6b7280;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* ── Submit button ── */
        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--sipakar-green) 0%, var(--sipakar-green-mid) 100%);
            color: #fff;
            border: none;
            border-radius: var(--radius-lg);
            font-size: .95rem;
            font-weight: 700;
            letter-spacing: .3px;
            cursor: pointer;
            transition: transform var(--transition), box-shadow var(--transition), opacity var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 8px;
        }
        .btn-login:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(26,92,56,.35);
        }
        .btn-login:active:not(:disabled) { transform: translateY(0); }
        .btn-login:disabled { opacity: .65; cursor: not-allowed; }
        .btn-login .spinner-border { width: 1rem; height: 1rem; border-width: 2px; }

        /* ── Back link ── */
        .back-link {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 20px;
            font-size: .82rem;
            color: #6b7280;
            text-decoration: none;
            transition: color var(--transition);
        }
        .back-link:hover { color: var(--sipakar-green); }

        /* ── Footer strip ── */
        .card-footer-sipakar {
            background: #f8faf8;
            border-top: 1px solid #e5e7eb;
            padding: 14px 36px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
        .footer-info {
            font-size: .72rem;
            color: #9ca3af;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .footer-portal-link {
            font-size: .72rem;
            color: var(--sipakar-green-mid);
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
            transition: color var(--transition);
        }
        .footer-portal-link:hover { color: var(--sipakar-green); }

        /* ── Responsive ── */
        @media (max-width: 480px) {
            .card-header-sipakar,
            .card-body-sipakar,
            .card-footer-sipakar { padding-left: 24px; padding-right: 24px; }
            .login-card { max-width: 100%; border-radius: var(--radius-lg); }
        }
    </style>
</head>
<body>

<!-- Background -->
<div class="login-hero" aria-hidden="true"></div>
<div class="dots-bg" aria-hidden="true"></div>

<!-- Main Login -->
<div class="login-wrapper">
    <div class="login-card" role="main">

        <!-- Header -->
        <div class="card-header-sipakar">
            <img src="<?= base_url('assets/images/logo.png') ?>"
                 alt="Logo Kecamatan Siantar Barat"
                 class="header-logo"
                 onerror="this.style.display='none'">
            <h1 class="header-title">Portal ASN SIPAKAR</h1>
            <p class="header-subtitle">Kecamatan Siantar Barat – Kota Pematangsiantar</p>
            <div class="badge-sipakar">
                <i class="bi bi-shield-lock-fill"></i>
                Sistem Pelayanan Administrasi Kepegawaian
            </div>
        </div>

        <!-- Body -->
        <div class="card-body-sipakar">

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('sipakar_error')): ?>
                <div class="alert-sipakar danger" role="alert">
                    <i class="bi bi-exclamation-triangle-fill mt-1 flex-shrink-0"></i>
                    <div><?= esc(session()->getFlashdata('sipakar_error')) ?></div>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('sipakar_success')): ?>
                <div class="alert-sipakar success" role="alert">
                    <i class="bi bi-check-circle-fill mt-1 flex-shrink-0"></i>
                    <div><?= esc(session()->getFlashdata('sipakar_success')) ?></div>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('sipakar_info')): ?>
                <div class="alert-sipakar info" role="alert">
                    <i class="bi bi-info-circle-fill mt-1 flex-shrink-0"></i>
                    <div><?= esc(session()->getFlashdata('sipakar_info')) ?></div>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form id="loginForm" method="post" action="<?= base_url('sipakar/login') ?>" novalidate autocomplete="off">
                <?= csrf_field() ?>

                <!-- NIP -->
                <div>
                    <label class="form-label-sipakar" for="nip">
                        <i class="bi bi-person-badge me-1 text-success"></i>NIP (Nomor Induk Pegawai)
                    </label>
                    <div class="input-group-sipakar">
                        <i class="bi bi-credit-card-2-front input-icon"></i>
                        <input
                            type="text"
                            id="nip"
                            name="nip"
                            class="form-control-sipakar"
                            placeholder="Contoh: 197112252006041008"
                            inputmode="numeric"
                            pattern="[0-9]{15,25}"
                            maxlength="25"
                            autocomplete="username"
                            value="<?= esc(service('request')->getPost('nip') ?? '') ?>"
                            required>
                    </div>
                    <div class="nip-info">
                        <i class="bi bi-info-circle"></i>
                        Masukkan NIP Anda (15–25 digit angka tanpa spasi)
                    </div>
                </div>

                <!-- Password -->
                <div style="margin-top:16px;">
                    <label class="form-label-sipakar" for="password">
                        <i class="bi bi-lock me-1 text-success"></i>Password
                    </label>
                    <div class="input-group-sipakar">
                        <i class="bi bi-lock-fill input-icon"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control-sipakar"
                            placeholder="Masukkan password Anda"
                            autocomplete="current-password"
                            required>
                        <button type="button" class="toggle-password" id="togglePwd" aria-label="Tampilkan password">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                    <div class="nip-info">
                        <i class="bi bi-info-circle"></i>
                        Password default: 6 digit terakhir NIP Anda
                    </div>
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-login" id="loginBtn">
                    <span id="btnText"><i class="bi bi-box-arrow-in-right"></i> Masuk ke SIPAKAR</span>
                    <span id="btnSpinner" class="d-none">
                        <span class="spinner-border" role="status" aria-hidden="true"></span>
                        Memproses...
                    </span>
                </button>
            </form>

            <!-- Back link -->
            <a href="<?= base_url('sipakar') ?>" class="back-link">
                <i class="bi bi-arrow-left-circle"></i>
                Kembali ke halaman SIPAKAR
            </a>
        </div>

        <!-- Footer -->
        <div class="card-footer-sipakar">
            <span class="footer-info">
                <i class="bi bi-shield-check text-success"></i>
                Koneksi aman & terenkripsi
            </span>
            <a href="<?= base_url() ?>" class="footer-portal-link">
                <i class="bi bi-house-door"></i>
                Portal Utama
            </a>
        </div>

    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    'use strict';

    // ── Toggle password visibility ──
    const toggleBtn = document.getElementById('togglePwd');
    const pwdInput  = document.getElementById('password');
    const eyeIcon   = document.getElementById('eyeIcon');

    if (toggleBtn && pwdInput) {
        toggleBtn.addEventListener('click', function () {
            const isHidden = pwdInput.type === 'password';
            pwdInput.type   = isHidden ? 'text' : 'password';
            eyeIcon.className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
        });
    }

    // ── NIP: strip non-digits on input ──
    const nipInput = document.getElementById('nip');
    if (nipInput) {
        nipInput.addEventListener('input', function () {
            const pos   = this.selectionStart;
            const clean = this.value.replace(/\D/g, '');
            if (this.value !== clean) {
                this.value = clean;
                this.setSelectionRange(pos - 1, pos - 1);
            }
        });
    }

    // ── Loading state on submit ──
    const form       = document.getElementById('loginForm');
    const loginBtn   = document.getElementById('loginBtn');
    const btnText    = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');

    if (form) {
        form.addEventListener('submit', function (e) {
            const nipVal = nipInput ? nipInput.value.trim() : '';
            const pwdVal = pwdInput ? pwdInput.value : '';

            if (nipVal.length < 15) {
                e.preventDefault();
                nipInput.classList.add('is-error');
                nipInput.focus();
                return;
            }
            if (pwdVal.length === 0) {
                e.preventDefault();
                pwdInput.classList.add('is-error');
                pwdInput.focus();
                return;
            }

            if (loginBtn) {
                loginBtn.disabled = true;
                btnText.classList.add('d-none');
                btnSpinner.classList.remove('d-none');
            }
        });
    }

    // ── Clear error styling on focus ──
    document.querySelectorAll('.form-control-sipakar').forEach(function (el) {
        el.addEventListener('focus', function () {
            this.classList.remove('is-error');
        });
    });

    // ── Auto-dismiss alerts after 6s ──
    document.querySelectorAll('.alert-sipakar').forEach(function (el) {
        setTimeout(function () {
            el.style.transition = 'opacity .4s ease';
            el.style.opacity    = '0';
            setTimeout(function () { el.remove(); }, 450);
        }, 6000);
    });
})();
</script>
</body>
</html>
