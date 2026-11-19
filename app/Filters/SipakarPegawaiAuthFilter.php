<?php

namespace App\Filters;

use App\Services\SipakarPegawaiAuthService;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Filter: enforce SIPAKAR employee (pegawai ASN) authentication.
 * Protects /sipakar/pengajuan and /sipakar/upload_perbaikan.
 * Completely separate from SipakarAuthFilter (admin/operator RBAC).
 */
class SipakarPegawaiAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $auth = new SipakarPegawaiAuthService();

        if (! $auth->isLoggedIn()) {
            session()->setFlashdata(
                'sipakar_error',
                'Silakan login terlebih dahulu menggunakan NIP dan password ASN Anda untuk mengakses halaman pengajuan.'
            );

            // Preserve intended URL
            session()->set('sipakar_intended_url', (string) current_url());

            return redirect()->to(base_url('sipakar/login'));
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
