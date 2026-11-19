<?php

namespace App\Controllers\Admin;

use App\Models\UsersModulModel;
use App\Services\AuthService;
use App\Services\CaptchaService;
use Config\Services;
use Throwable;

class AuthController extends BaseAdminController
{
    public function index()
    {
        $session     = session();
        $authService = new AuthService();

        if ($this->isPostSubmit()) {
            if (! $this->securityAudit->checkRateLimit('admin_login', 5, 900, $this->request)) {
                $session->setFlashdata('message', '<div class="alert alert-danger"><center>Terlalu banyak percobaan login. Tunggu 15 menit.</center></div>');

                return redirect()->to(base_url('administrator/index'));
            }

            $captchaInput = (string) ($this->request->getPost('security_code') ?? '');
            if (! (new CaptchaService())->verify($captchaInput, 'mycaptcha', true)) {
                $session->setFlashdata('message', '<div class="alert alert-danger"><center>Security Code salah!</center></div>');

                return redirect()->to(base_url('administrator/index'));
            }

            $username = trim((string) ($this->request->getPost('a') ?? ''));
            $password = (string) ($this->request->getPost('b') ?? '');
            $user     = $authService->attemptLogin($username, $password);

            if ($user !== null) {
                return redirect()->to(base_url('administrator/home'));
            }

            $this->securityAudit->logEvent('ADMIN_LOGIN_FAILED', 'Failed admin login for user: ' . $username, $this->request);
            $session->setFlashdata('message', '<div class="alert alert-danger"><center>Username dan Password Salah!!</center></div>');

            return redirect()->to(base_url('administrator/index'));
        }

        if ($this->isLoggedIn()) {
            return redirect()->to(base_url('administrator/home'));
        }

        $cap = (new CaptchaService())->create([
            'img_width'   => 320,
            'img_height'  => 33,
            'word_length' => 5,
        ]);
        $session->set('mycaptcha', $cap['word']);

        return view('administrator/view_login', [
            'title' => 'Users &rsaquo; Log In',
            'image' => $cap['image'],
        ]);
    }

    public function refresh_captcha()
    {
        $session = session();
        $cap     = (new CaptchaService())->create([
            'img_width'   => 320,
            'img_height'  => 33,
            'word_length' => 5,
        ]);
        $session->set('mycaptcha', $cap['word']);

        return $this->response->setJSON([
            'status' => 'ok',
            'image'  => $cap['image'],
            'token'  => csrf_hash(),
        ]);
    }

    public function reset_password(string $tokenParam = '')
    {
        $session     = session();
        $authService = new AuthService();

        if ($this->isPostSubmit()) {
            $token = trim((string) ($this->request->getPost('reset_token') ?? ''));
            if (! $authService->isValidPasswordResetToken($token)) {
                $session->setFlashdata('message', '<div class="alert alert-danger"><center>Token reset password tidak valid atau telah kedaluwarsa.</center></div>');

                return redirect()->to(base_url('administrator'));
            }

            $passA = (string) ($this->request->getPost('a') ?? '');
            $passB = (string) ($this->request->getPost('b') ?? '');
            if (strlen($passA) < 6 || strlen($passA) > 100 || ! hash_equals($passA, $passB)) {
                return view('administrator/view_reset', [
                    'title'       => 'Password Tidak Valid',
                    'error_msg'   => 'Password minimal 6 karakter dan kedua input harus sama.',
                    'reset_token' => $token,
                ]);
            }

            if (! $authService->consumePasswordResetToken($token, $passA)) {
                $session->setFlashdata('message', '<div class="alert alert-danger"><center>Token reset password gagal diproses.</center></div>');

                return redirect()->to(base_url('administrator'));
            }

            $session->regenerate(true);
            $session->setFlashdata('message', '<div class="alert alert-success"><center>Password berhasil diperbarui. Silakan login kembali.</center></div>');

            return redirect()->to(base_url('administrator'));
        }

        $token = trim($tokenParam !== '' ? $tokenParam : uri_segment(3));
        if (! $authService->isValidPasswordResetToken($token)) {
            $session->setFlashdata('message', '<div class="alert alert-danger"><center>Tautan reset password tidak valid atau sudah kedaluwarsa.</center></div>');

            return redirect()->to(base_url('administrator'));
        }

        return view('administrator/view_reset', [
            'title'       => 'Reset Password',
            'reset_token' => $token,
        ]);
    }

    public function lupapassword()
    {
        $session = session();
        if (! $this->request->is('post') || $this->request->getPost('lupa') === null) {
            return redirect()->to(base_url('administrator'));
        }

        if (! $this->securityAudit->checkRateLimit('password_reset', 5, 900, $this->request)) {
            $session->setFlashdata('message', '<div class="alert alert-danger"><center>Terlalu banyak permintaan reset password. Coba lagi nanti.</center></div>');

            return redirect()->to(base_url('administrator'));
        }

        $email  = trim(strip_tags((string) ($this->request->getPost('email') ?? '')));
        $issued = (new AuthService())->createPasswordResetToken($email);

        if ($issued !== null) {
            $usr      = $issued['user'];
            $iden     = $this->portalContext->getIdentitas();
            $resetUrl = base_url('administrator/reset_password/' . $issued['token']);
            $namaUser = esc((string) ($usr['nama_lengkap'] ?? ''));
            $siteUrl  = esc((string) ($iden['url'] ?? base_url()));
            $siteMail = esc((string) ($iden['email'] ?? ''));
            $siteTelp = esc((string) ($iden['no_telp'] ?? ''));

            $message = "<html><body><table style='margin-left:25px'><tr><td>"
                . "Halo {$namaUser},<br>"
                . "Seseorang baru saja meminta untuk mengatur ulang kata sandi Anda di <span style='color:red'>{$siteUrl}</span>.<br>"
                . "Klik atau salin URL dibawah ini ke address bar browser Anda (berlaku 30 menit, 1 kali pakai):<br>"
                . "<a href='{$resetUrl}'>{$resetUrl}</a><br><br>"
                . "Jika Anda tidak meminta kata sandi baru, segera beri tahu kami.<br>"
                . "Email: {$siteMail}, No Telp: {$siteTelp}"
                . '</td></tr></table></body></html>';

            try {
                $mailer = Services::email();
                $mailer->setFrom((string) ($iden['email'] ?? 'noreply@siantarbarat.local'), (string) ($iden['nama_website'] ?? 'Admin Portal'));
                $mailer->setTo((string) $usr['email']);
                $mailer->setSubject('Reset Password Admin Portal');
                $mailer->setMessage($message);
                $mailer->setMailType('html');
                $mailer->send();
            } catch (Throwable $e) {
                log_message('warning', 'Password reset email failed: ' . $e->getMessage());
            }
        }

        // Non-enumerating notice prevents account/email discovery.
        $session->setFlashdata('message', '<div class="alert alert-info"><center>Jika email terdaftar dan aktif, tautan reset password telah dikirim.</center></div>');

        return redirect()->to(base_url('administrator'));
    }

    public function home()
    {
        if ($redirect = $this->ensureLoggedIn()) {
            return $redirect;
        }

        if ($this->isAdmin()) {
            return $this->renderAdmin('administrator/include/view_home_admin');
        }

        $username  = $this->currentUsername();
        $idSession = (string) (session()->get('id_session') ?? '');
        $users     = $this->portalModel->findOne('users', ['username' => $username]) ?? [];
        $modul     = (new UsersModulModel())->getAssignedModules($idSession);

        return $this->renderAdmin('administrator/include/view_home_users', [
            'users'    => $users,
            'modul'    => $modul,
            'hakakses' => $modul,
        ]);
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to(base_url('main'));
    }
}
