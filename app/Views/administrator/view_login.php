<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <?php
    $identitas = portal_context()->getIdentitas();
    $namaWeb   = ! empty($identitas['nama_website']) ? (string) $identitas['nama_website'] : 'Kecamatan Siantar Barat';
    $logoImg   = ! empty($identitas['logo']) && file_exists(portal_fcpath('asset/logo/' . $identitas['logo'])) 
        ? base_url('asset/logo/' . $identitas['logo']) 
        : base_url('asset/logo/LOGO1.png');
  ?>
  <title><?= esc($title ?? 'Administrator Log In'); ?> &mdash; <?= esc($namaWeb); ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/x-icon" href="<?= base_url('asset/images/' . (! empty($identitas['favicon']) ? $identitas['favicon'] : 'favicon.ico')); ?>">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('asset/admin_mu/plugins/fontawesome-free/css/all.min.css'); ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('asset/admin_mu/dist/css/adminlte.min.css'); ?>">
  <!-- Google Font: Inter & Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body.login-page {
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f3d2e 100%);
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .login-box {
      width: 420px;
      max-width: 92%;
      margin: 20px auto;
    }
    .card-login {
      border: 0;
      border-radius: 16px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35), 0 1px 3px rgba(0, 0, 0, 0.2);
      background: #ffffff;
      overflow: hidden;
    }
    .card-login .card-body {
      padding: 30px 28px 24px;
    }
    .login-brand {
      text-align: center;
      margin-bottom: 22px;
    }
    .login-brand img {
      max-height: 55px;
      max-width: 220px;
      object-fit: contain;
      margin-bottom: 10px;
    }
    .login-brand-title {
      font-size: 17px;
      font-weight: 700;
      color: #0f172a;
      line-height: 1.3;
    }
    .login-brand-sub {
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 1.2px;
      text-transform: uppercase;
      color: #059669;
      margin-top: 3px;
    }
    .form-control {
      border-radius: 10px;
      border-color: #cbd5e1;
      font-size: 14px;
      height: 42px;
    }
    .form-control:focus {
      border-color: #059669;
      box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15);
    }
    .input-group-text {
      border-radius: 0 10px 10px 0;
      border-color: #cbd5e1;
      background-color: #f8fafc;
      color: #64748b;
    }
    .input-group > .form-control {
      border-radius: 10px 0 0 10px;
    }
    .btn-login-submit {
      background: linear-gradient(135deg, #059669 0%, #047857 100%);
      border: none;
      color: #ffffff;
      font-weight: 600;
      border-radius: 10px;
      height: 42px;
      font-size: 14px;
      transition: all 0.2s ease;
    }
    .btn-login-submit:hover {
      background: linear-gradient(135deg, #047857 0%, #065f46 100%);
      color: #ffffff;
      box-shadow: 0 4px 12px rgba(5, 150, 105, 0.35);
    }
    .captcha-box-wrapper {
      background: #f8fafc;
      border: 1px solid #cbd5e1;
      border-radius: 10px;
      padding: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 40px;
      line-height: 0;
      overflow: hidden;
    }
    .captcha-box-wrapper img {
      max-width: 100%;
      height: 33px;
      display: block;
      border-radius: 6px;
    }
    .btn-refresh-captcha {
      border-radius: 10px;
      height: 42px;
      width: 44px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-color: #cbd5e1;
      color: #475569;
      background: #f8fafc;
    }
    .btn-refresh-captcha:hover {
      background: #e2e8f0;
      color: #0f172a;
    }
  </style>
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-login">
    <div class="card-body login-card-body">
      <div class="login-brand">
        <a href="<?= base_url(); ?>" title="Buka Beranda">
          <img src="<?= $logoImg; ?>" alt="Logo <?= esc($namaWeb); ?>">
        </a>
        <div class="login-brand-title"><?= esc($namaWeb); ?></div>
        <div class="login-brand-sub">Portal Administrator CMS</div>
      </div>

      <?php 
          $msg = session()->getFlashdata('message');
          $err = session()->getFlashdata('error') ?? session()->getFlashdata('errors');
          if (! empty($msg)) {
              echo $msg;
          }
          if (! empty($err)) {
              $errText = is_array($err) ? implode('<br>', array_map('esc', $err)) : esc((string) $err);
              if (stripos($errText, 'not allowed') !== false || stripos($errText, 'action you requested') !== false) {
                  $errText = 'Sesi formulir telah kedaluwarsa atau token keamanan tidak valid. Silakan coba login kembali.';
              }
              echo '<div class="alert alert-warning text-center" role="alert" style="border-radius:10px;font-size:13px;padding:10px 14px;"><i class="fas fa-shield-alt mr-1"></i> ' . $errText . '</div>';
          }

          $targetAction = uri_segment(1, 'administrator') . '/index';
          $formTag = form_open($targetAction, ['id' => 'login-form', 'autocomplete' => 'on']);
          echo $formTag;
          if (! str_contains($formTag, 'name="' . csrf_token() . '"') && ! str_contains($formTag, 'name=\'' . csrf_token() . '\'')) {
              echo csrf_field();
          }
      ?>
        <div class="form-group mb-3">
          <label style="font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 4px;">Username</label>
          <div class="input-group">
            <input type="text" class="form-control" name="a" id="username" placeholder="Masukkan username" required autofocus autocomplete="username">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
        </div>

        <div class="form-group mb-3">
          <label style="font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 4px;">Password</label>
          <div class="input-group">
            <input type="password" class="form-control" name="b" id="password" placeholder="Masukkan password" required autocomplete="current-password">
            <div class="input-group-append">
              <button type="button" class="btn input-group-text btn-toggle-password" id="btn-toggle-password" style="cursor:pointer;" title="Tampilkan/Sembunyikan Password">
                <span class="fas fa-eye-slash" id="toggle-eye"></span>
              </button>
            </div>
          </div>
        </div>

        <div class="form-group mb-3">
          <label style="font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 4px;">Kode Keamanan (CAPTCHA)</label>
          <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 8px;">
            <div id="captcha-container" class="captcha-box-wrapper" style="flex: 1;">
              <?= $image; ?>
            </div>
            <button type="button" id="btn-refresh-captcha" class="btn btn-outline-secondary btn-refresh-captcha" title="Ganti Kode Keamanan">
              <i class="fas fa-sync-alt" id="icon-refresh"></i>
            </button>
          </div>
          <div class="input-group">
            <input type="text" class="form-control" name="security_code" id="security_code" placeholder="Ketik 5 karakter kode di atas" required autocomplete="off">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-shield-alt"></span>
              </div>
            </div>
          </div>
        </div>

        <div class="row align-items-center mb-3">
          <div class="col-7">
            <a data-dismiss="modal" aria-hidden="true" data-toggle="modal" href="#lupapass" data-target="#lupapass" style="font-size: 13px; color: #0284c7; font-weight: 500; text-decoration: none;">
              <i class="fas fa-key mr-1"></i> Lupa password?
            </a>
          </div>
          <div class="col-5">
            <button type="submit" name="submit" id="btn-submit" class="btn btn-login-submit btn-block">
              <span id="btn-text"><i class="fas fa-sign-in-alt mr-1"></i> Sign In</span>
            </button>
          </div>
        </div>
      </form>

      <div class="text-center pt-3" style="border-top: 1px solid #f1f5f9;">
        <a href="<?= base_url(); ?>" style="font-size: 12px; color: #64748b; text-decoration: none;">
          <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda Portal
        </a>
      </div>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?= base_url('asset/admin_mu/plugins/jquery/jquery.min.js'); ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('asset/admin_mu/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('asset/admin_mu/dist/js/adminlte.min.js'); ?>"></script>

<script>
$(function () {
  // Toggle password visibility
  $('#btn-toggle-password').on('click', function (e) {
    e.preventDefault();
    var input = $('#password');
    var eye = $('#toggle-eye');
    if (input.attr('type') === 'password') {
      input.attr('type', 'text');
      eye.removeClass('fa-eye-slash').addClass('fa-eye');
    } else {
      input.attr('type', 'password');
      eye.removeClass('fa-eye').addClass('fa-eye-slash');
    }
  });

  // AJAX Captcha refresh
  $('#btn-refresh-captcha').on('click', function (e) {
    e.preventDefault();
    var btn = $(this);
    var icon = $('#icon-refresh');
    icon.addClass('fa-spin');
    btn.prop('disabled', true);

    var seg1 = '<?= uri_segment(1, 'administrator'); ?>';
    var refreshUrl = '<?= base_url(); ?>' + (seg1 === 'login' ? 'login' : 'administrator') + '/refresh_captcha';

    $.ajax({
      url: refreshUrl,
      type: 'GET',
      dataType: 'json',
      cache: false,
      success: function (data) {
        if (data && data.image) {
          $('#captcha-container').html(data.image);
        }
        if (data && data.token) {
          $('input[name="<?= csrf_token(); ?>"]').val(data.token);
        }
      },
      complete: function () {
        icon.removeClass('fa-spin');
        btn.prop('disabled', false);
      }
    });
  });

  // Double-submit prevention
  var formSubmitted = false;
  $('#login-form').on('submit', function () {
    if (formSubmitted) {
      return false;
    }
    formSubmitted = true;
    $('#btn-submit').prop('disabled', true);
    $('#btn-text').html('<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...');
    return true;
  });

  // Restore button state if user returns via bfcache (back/forward)
  window.addEventListener('pageshow', function (event) {
    formSubmitted = false;
    $('#btn-submit').prop('disabled', false);
    $('#btn-text').html('<i class="fas fa-sign-in-alt mr-1"></i> Sign In');
  });
});
</script>

<div class="modal fade" id="lupapass">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius: 14px; overflow: hidden;">
      <div class="modal-header" style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
        <h5 class="modal-title" style="font-weight: 700; color: #0f172a;"><i class="fas fa-key text-primary mr-2"></i>Lupa Password?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php 
        $attributes = ['class' => 'form-horizontal'];
        echo form_open('administrator/lupapassword', $attributes); 
      ?>
      <div class="modal-body" style="padding: 24px;">
        <p style="font-size: 13px; color: #64748b; margin-bottom: 16px;">
          Masukkan alamat email yang terdaftar pada akun Anda. Tautan untuk mengatur ulang kata sandi akan dikirim ke email tersebut.
        </p>
        <div class="form-group">
          <label style="font-size: 13px; font-weight: 600; color: #334155;">Alamat Email</label>
          <div class="input-group">
            <input style="text-transform:lowercase;" placeholder="tulis.email.anda@domain.com" type="email" class="required form-control" name="email" required>
            <div class="input-group-append">
              <div class="input-group-text"><i class="fas fa-envelope"></i></div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
        <button type="submit" name="lupa" class="btn btn-primary" style="font-weight: 600; border-radius: 8px;">Kirim Tautan Reset</button>
        <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px;">Tutup</button>
      </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
