<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Ganti Password – SIPAKAR') ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('assets/images/logo.png') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { font-family:'Inter',sans-serif; background:#f0f9f4; min-height:100vh; display:flex; align-items:center; justify-content:center; }
        .change-card { max-width:440px; width:100%; background:#fff; border-radius:16px; box-shadow:0 8px 40px rgba(26,92,56,.15); overflow:hidden; }
        .change-header { background:linear-gradient(135deg,#0f2419,#1a5c38); padding:28px 32px; text-align:center; color:#fff; }
        .change-header h1 { font-size:1.2rem; font-weight:700; margin:0 0 4px; }
        .change-header p  { font-size:.8rem; color:rgba(255,255,255,.65); margin:0; }
        .change-body { padding:28px 32px; }
        .alert-warn { background:#fffbeb; border-left:4px solid #f59e0b; color:#92400e; border-radius:10px; padding:12px 14px; font-size:.85rem; margin-bottom:20px; }
        .form-label { font-size:.82rem; font-weight:600; color:#374151; }
        .input-wrap { position:relative; }
        .input-icon { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#217a4b; }
        .fc { width:100%; padding:10px 40px; border:1.5px solid #d1d5db; border-radius:10px; font-size:.9rem; background:#f9fafb; outline:none; transition:.2s; }
        .fc:focus { border-color:#217a4b; background:#fff; box-shadow:0 0 0 3px rgba(33,122,75,.1); }
        .toggle-eye { position:absolute; right:10px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#9ca3af; }
        .strength-bar { height:4px; border-radius:4px; margin-top:6px; transition:.3s; background:#e5e7eb; }
        .strength-bar .fill { height:100%; border-radius:4px; transition:.3s; }
        .btn-change { width:100%; padding:12px; background:linear-gradient(135deg,#1a5c38,#217a4b); color:#fff; border:none; border-radius:10px; font-weight:700; cursor:pointer; margin-top:8px; display:flex; align-items:center; justify-content:center; gap:8px; transition:.2s; }
        .btn-change:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(26,92,56,.3); }
    </style>
</head>
<body>
<div class="change-card">
    <div class="change-header">
        <img src="<?= base_url('assets/images/logo.png') ?>" alt="Logo" style="width:52px;height:52px;border-radius:50%;background:#fff;padding:3px;margin-bottom:10px;" onerror="this.style.display='none'">
        <h1><i class="bi bi-shield-lock me-2"></i>Ganti Password</h1>
        <p>Halo, <?= esc($pegawai['nama'] ?? '') ?>. Silakan buat password baru Anda.</p>
    </div>
    <div class="change-body">
        <?php if (session()->getFlashdata('sipakar_error')): ?>
            <div class="alert-warn"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= esc(session()->getFlashdata('sipakar_error')) ?></div>
        <?php endif; ?>

        <div class="alert-warn">
            <i class="bi bi-info-circle-fill me-1"></i>
            Ini adalah login pertama Anda. Wajib ganti password sebelum melanjutkan pengajuan.
        </div>

        <form method="post" action="<?= base_url('sipakar/ganti_password') ?>" id="cpForm">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label">Password Lama</label>
                <div class="input-wrap">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password_lama" class="fc" required placeholder="Password saat ini">
                    <button type="button" class="toggle-eye" onclick="togglePwd('password_lama', this)"><i class="bi bi-eye"></i></button>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password Baru <span class="text-muted" style="font-weight:400">(min. 8 karakter)</span></label>
                <div class="input-wrap">
                    <i class="bi bi-lock-fill input-icon"></i>
                    <input type="password" name="password_baru" id="newPwd" class="fc" required placeholder="Password baru" oninput="checkStrength(this)">
                    <button type="button" class="toggle-eye" onclick="togglePwd('newPwd', this)"><i class="bi bi-eye"></i></button>
                </div>
                <div class="strength-bar"><div class="fill" id="strengthFill" style="width:0%;background:#ef4444"></div></div>
                <div style="font-size:.72rem;color:#6b7280;margin-top:3px;" id="strengthLabel"></div>
            </div>

            <div class="mb-3">
                <label class="form-label">Konfirmasi Password Baru</label>
                <div class="input-wrap">
                    <i class="bi bi-check2-circle input-icon"></i>
                    <input type="password" name="password_konfirmasi" id="confPwd" class="fc" required placeholder="Ulangi password baru">
                </div>
                <div style="font-size:.75rem;margin-top:4px;" id="matchLabel"></div>
            </div>

            <button type="submit" class="btn-change">
                <i class="bi bi-check-circle-fill"></i> Simpan Password Baru
            </button>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePwd(id, btn) {
    const el = document.getElementById(id) || btn.closest('.input-wrap').querySelector('input');
    el.type = el.type === 'password' ? 'text' : 'password';
    btn.querySelector('i').className = el.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
function checkStrength(input) {
    const v = input.value, fill = document.getElementById('strengthFill'), lbl = document.getElementById('strengthLabel');
    let score = 0;
    if (v.length >= 8) score++;
    if (/[A-Z]/.test(v)) score++;
    if (/[0-9]/.test(v)) score++;
    if (/[^A-Za-z0-9]/.test(v)) score++;
    const levels = [{w:'0%',c:'#ef4444',t:''},{w:'30%',c:'#f97316',t:'Lemah'},{w:'55%',c:'#eab308',t:'Cukup'},{w:'80%',c:'#22c55e',t:'Kuat'},{w:'100%',c:'#15803d',t:'Sangat Kuat'}];
    const l = levels[score];
    fill.style.width = l.w; fill.style.background = l.c;
    lbl.style.color = l.c; lbl.textContent = l.t;
    checkMatch();
}
function checkMatch() {
    const lbl = document.getElementById('matchLabel');
    const nv = document.getElementById('newPwd')?.value;
    const cv = document.getElementById('confPwd')?.value;
    if (!cv) { lbl.textContent=''; return; }
    if (nv === cv) { lbl.style.color='#15803d'; lbl.textContent='✓ Password cocok'; }
    else { lbl.style.color='#ef4444'; lbl.textContent='✗ Password tidak cocok'; }
}
document.getElementById('confPwd')?.addEventListener('input', checkMatch);
</script>
</body>
</html>
