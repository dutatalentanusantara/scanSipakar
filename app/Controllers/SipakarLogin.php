<?php

namespace App\Controllers;

use App\Services\SipakarPegawaiAuthService;
use App\Models\SipakarPegawaiModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * SipakarLogin Controller
 * Handles employee (ASN) login/logout for SIPAKAR /pengajuan access.
 * Completely separate from admin/user authentication.
 */
class SipakarLogin extends Controller
{
    protected SipakarPegawaiAuthService $auth;
    protected SipakarPegawaiModel $pegawaiModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->auth         = new SipakarPegawaiAuthService();
        $this->pegawaiModel = new SipakarPegawaiModel();
    }

    // =========================================================================
    // LOGIN FORM + HANDLER
    // =========================================================================

    /**
     * GET  /sipakar/login  — show login form
     * POST /sipakar/login  — process login
     */
    public function index(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        // Already logged in? Redirect to pengajuan
        if ($this->auth->isLoggedIn()) {
            $intended = session()->get('sipakar_intended_url') ?? base_url('sipakar/pengajuan');
            session()->remove('sipakar_intended_url');
            return redirect()->to($intended);
        }

        if ($this->request->is('post')) {
            return $this->handleLogin();
        }

        return $this->renderLoginForm();
    }

    /**
     * Process POST login.
     */
    private function handleLogin(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        $nip      = (string) ($this->request->getPost('nip') ?? '');
        $password = (string) ($this->request->getPost('password') ?? '');
        $ip       = $this->request->getIPAddress();

        // Basic input guard
        if (empty($nip) || empty($password)) {
            session()->setFlashdata('sipakar_error', 'NIP dan Password wajib diisi.');
            return redirect()->to(base_url('sipakar/login'));
        }

        $pegawai = $this->auth->attemptLogin($nip, $password, $ip);

        if ($pegawai === null) {
            // Error flash already set by service
            return redirect()->to(base_url('sipakar/login'));
        }

        // Redirect to intended or pengajuan
        $intended = session()->get('sipakar_intended_url') ?? base_url('sipakar/pengajuan');
        session()->remove('sipakar_intended_url');

        session()->setFlashdata(
            'sipakar_success',
            'Selamat datang, ' . esc($pegawai['nama']) . '! Anda berhasil masuk.'
        );

        return redirect()->to($intended);
    }

    /**
     * Render login form view.
     */
    private function renderLoginForm(): string
    {
        $data = [
            'title'       => 'Login ASN – SIPAKAR Kecamatan Siantar Barat',
            'description' => 'Login pegawai ASN untuk mengakses pengajuan SIPAKAR',
        ];

        return view('sipakar/view_login_pegawai', $data);
    }

    // =========================================================================
    // LOGOUT
    // =========================================================================

    /**
     * GET /sipakar/logout
     */
    public function logout(): \CodeIgniter\HTTP\RedirectResponse
    {
        $nama = $this->auth->get('nama', '');
        $this->auth->logout();

        session()->setFlashdata(
            'sipakar_info',
            ($nama ? esc($nama) . ' telah' : 'Anda telah') . ' berhasil keluar dari SIPAKAR.'
        );

        return redirect()->to(base_url('sipakar/login'));
    }

    // =========================================================================
    // CHANGE PASSWORD (first-login enforcement)
    // =========================================================================

    /**
     * GET/POST /sipakar/ganti_password
     */
    public function ganti_password(): string|\CodeIgniter\HTTP\RedirectResponse
    {
        if (! $this->auth->isLoggedIn()) {
            return redirect()->to(base_url('sipakar/login'));
        }

        if ($this->request->is('post')) {
            return $this->handleChangePassword();
        }

        return view('sipakar/view_ganti_password', [
            'title'   => 'Ganti Password – SIPAKAR',
            'pegawai' => $this->auth->getSession(),
        ]);
    }

    private function handleChangePassword(): \CodeIgniter\HTTP\RedirectResponse
    {
        $pegawai     = $this->auth->getSession();
        $oldPassword = (string) ($this->request->getPost('password_lama') ?? '');
        $newPassword = (string) ($this->request->getPost('password_baru') ?? '');
        $confPassword = (string) ($this->request->getPost('password_konfirmasi') ?? '');

        if (strlen($newPassword) < 8) {
            session()->setFlashdata('sipakar_error', 'Password baru minimal 8 karakter.');
            return redirect()->to(base_url('sipakar/ganti_password'));
        }

        if ($newPassword !== $confPassword) {
            session()->setFlashdata('sipakar_error', 'Konfirmasi password tidak cocok.');
            return redirect()->to(base_url('sipakar/ganti_password'));
        }

        // Verify old password
        $record = $this->pegawaiModel->findByNip($pegawai['nip']);
        if ($record === null || ! password_verify($oldPassword, $record['password'])) {
            session()->setFlashdata('sipakar_error', 'Password lama tidak sesuai.');
            return redirect()->to(base_url('sipakar/ganti_password'));
        }

        $this->pegawaiModel->updatePassword((int) $pegawai['id'], $newPassword);

        // Update session flag
        $sessionData                        = $pegawai;
        $sessionData['must_change_password'] = 0;
        $this->auth->setSession($sessionData);

        session()->setFlashdata('sipakar_success', 'Password berhasil diperbarui. Silakan lanjutkan pengajuan Anda.');
        return redirect()->to(base_url('sipakar/pengajuan'));
    }
}
