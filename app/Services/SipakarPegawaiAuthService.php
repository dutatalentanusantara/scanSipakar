<?php

namespace App\Services;

use App\Models\SipakarPegawaiModel;

/**
 * Service for SIPAKAR Employee Authentication.
 * Manages a completely separate session namespace from admin/user auth.
 *
 * Session key: sipakar_pegawai  (array with sub-keys)
 * Does NOT touch: username, level, id_session (admin keys)
 */
class SipakarPegawaiAuthService
{
    private const SESSION_KEY = 'sipakar_pegawai';

    private SipakarPegawaiModel $model;

    public function __construct()
    {
        $this->model = new SipakarPegawaiModel();
    }

    // =========================================================================
    // LOGIN / LOGOUT
    // =========================================================================

    /**
     * Attempt login. Returns employee data array on success, null on failure.
     * Also populates flash 'sipakar_error' with reason on failure.
     *
     * @param string $nip      NIP (stripped to digits)
     * @param string $password Plain-text password
     * @param string $ip       Client IP for rate limiting
     */
    public function attemptLogin(string $nip, string $password, string $ip = ''): ?array
    {
        $nip = preg_replace('/\D/', '', $nip);

        if ($nip === '' || strlen($nip) < 15) {
            session()->setFlashdata('sipakar_error', 'NIP tidak valid. Masukkan NIP 15–25 digit.');
            return null;
        }

        // Check lockout first
        $remaining = $this->model->getLockoutRemaining($nip);
        if ($remaining > 0) {
            $minutes = ceil($remaining / 60);
            session()->setFlashdata(
                'sipakar_error',
                "Akun dikunci sementara. Coba lagi dalam {$minutes} menit karena terlalu banyak percobaan gagal."
            );
            return null;
        }

        $pegawai = $this->model->verifyLogin($nip, $password);

        if ($pegawai === null) {
            // Check if locked after this attempt
            $remaining2 = $this->model->getLockoutRemaining($nip);
            if ($remaining2 > 0) {
                session()->setFlashdata(
                    'sipakar_error',
                    'Akun dikunci 15 menit karena 5x percobaan login gagal.'
                );
            } else {
                session()->setFlashdata('sipakar_error', 'NIP atau Password tidak sesuai. Periksa kembali data Anda.');
            }
            return null;
        }

        // Record the login
        $this->model->recordLogin((int) $pegawai['id'], $ip);

        // Build session payload (do NOT store password hash)
        $sessionData = [
            'id'                   => (int) $pegawai['id'],
            'nip'                  => $pegawai['nip'],
            'nama'                 => $pegawai['nama'],
            'jabatan'              => $pegawai['jabatan'],
            'pangkat_golongan'     => $pegawai['pangkat_golongan'],
            'unit_kerja'           => $pegawai['unit_kerja'],
            'jenis_asn'            => $pegawai['jenis_asn'],
            'must_change_password' => (int) $pegawai['must_change_password'],
            'login_at'             => time(),
            'ip'                   => $ip,
        ];

        $this->setSession($sessionData);

        return $sessionData;
    }

    /**
     * Destroy sipakar employee session (logout).
     * Does NOT affect admin session.
     */
    public function logout(): void
    {
        session()->remove(self::SESSION_KEY);
    }

    // =========================================================================
    // SESSION HELPERS
    // =========================================================================

    /**
     * Write employee session data.
     */
    public function setSession(array $data): void
    {
        session()->set(self::SESSION_KEY, $data);
    }

    /**
     * Get current employee session, or null if not logged in.
     */
    public function getSession(): ?array
    {
        $data = session()->get(self::SESSION_KEY);
        return is_array($data) && ! empty($data['nip']) ? $data : null;
    }

    /**
     * Check if a valid employee session exists.
     */
    public function isLoggedIn(): bool
    {
        return $this->getSession() !== null;
    }

    /**
     * Get specific field from session.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $session = $this->getSession();
        return $session[$key] ?? $default;
    }
}
