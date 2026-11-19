<?php

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\CaptchaService;

class Login extends BasePortalController
{
    public function index()
    {
        $session     = session();
        $authService = new AuthService();

        if ($this->request->is('post') && ($this->request->getPost('submit') !== null || $this->request->getPost('a') !== null)) {
            if (! $this->securityAudit->checkRateLimit('login_attempt', 5, 900, $this->request)) {
                $session->setFlashdata('message', '<div class="alert alert-danger"><center>Terlalu banyak percobaan login. Silakan tunggu 15 menit.</center></div>');

                return redirect()->to(base_url('login'));
            }

            $securityCode = (string) ($this->request->getPost('security_code') ?? '');
            if (! (new CaptchaService())->verify($securityCode, 'mycaptcha', true)) {
                $session->setFlashdata('message', '<div class="alert alert-danger"><center>Security Code salah!</center></div>');

                return redirect()->to(base_url('login'));
            }

            $username = trim((string) ($this->request->getPost('a') ?? ''));
            $password = (string) ($this->request->getPost('b') ?? '');

            if (! preg_match('/^[a-zA-Z0-9_\-.]{3,50}$/', $username)) {
                $session->setFlashdata('message', '<div class="alert alert-danger"><center>Format username tidak valid!</center></div>');

                return redirect()->to(base_url('login'));
            }

            if (strlen($password) < 4 || strlen($password) > 100) {
                $session->setFlashdata('message', '<div class="alert alert-danger"><center>Password harus 4-100 karakter!</center></div>');

                return redirect()->to(base_url('login'));
            }

            $user = $authService->attemptLogin($username, $password);
            if ($user !== null) {
                return redirect()->to(base_url('administrator/home'));
            }

            $this->securityAudit->logEvent('LOGIN_FAILED', 'Failed login attempt for user: ' . $username, $this->request);
            $session->setFlashdata('message', '<div class="alert alert-danger"><center>Username dan Password Salah!!</center></div>');

            return redirect()->to(base_url('login'));
        }

        if ($authService->validateLiveSession() !== null) {
            return redirect()->to(base_url('administrator/home'));
        }

        $cap = (new CaptchaService())->create([
            'img_width'   => 320,
            'img_height'  => 33,
            'word_length' => 5,
        ]);
        $session->set('mycaptcha', $cap['word']);

        return view('administrator/view_login', [
            'title' => 'Administrator &rsaquo; Log In',
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

    public function logout()
    {
        session()->destroy();

        return redirect()->to(base_url('main'));
    }
}
