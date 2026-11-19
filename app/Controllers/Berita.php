<?php

namespace App\Controllers;

use App\Controllers\Concerns\RendersBeritaDetail;
use App\Models\BeritaModel;
use App\Models\KomentarModel;
use App\Services\CaptchaService;

class Berita extends BasePortalController
{
    use RendersBeritaDetail;
    public function index($segment3 = null, $segment4 = null)
    {
        $perPage = 10;
        $kataRaw = $this->request->getPost('kata') ?? $this->request->getGet('kata');
        $beritaModel = new BeritaModel();

        if ($kataRaw !== null && trim((string) $kataRaw) !== '') {
            $kata = mb_substr(trim(strip_tags((string) $kataRaw)), 0, 100);
            $list = $this->portalModel->cariBerita($kata);

            $data = [
                'title'           => 'Hasil Pencarian keyword - ' . cetak($kata),
                'description'     => description(),
                'keywords'        => keywords(),
                'berita'          => $list,
                'pagination_html' => '',
                'kata'            => $kata,
            ];

            return $this->renderPortal('berita', $data);
        }

        $jumlah = $beritaModel->countPublished();
        $dariRaw = $segment4 !== null ? $segment4 : $segment3;
        $dari    = $this->validatePaginationOffset($dariRaw);
        if ($jumlah > 0 && $dari >= $jumlah) {
            return redirect()->to(base_url('berita'));
        }

        $list = $beritaModel->getPublishedJoined($perPage, $dari);
        $data = [
            'title'           => 'Semua Berita',
            'description'     => description(),
            'keywords'        => keywords(),
            'berita'          => $list,
            'pagination_html' => portal_pagination(base_url('berita/index'), $jumlah, $perPage, $dari),
        ];

        return $this->renderPortal('berita', $data);
    }

    public function detail(?string $slug = null)
    {
        return $this->renderBeritaDetail($slug, 'berita');
    }

    public function kirim_komentar()
    {
        if (! $this->request->is('post')) {
            return redirect()->to(base_url('main'));
        }

        $session = session();
        if (! $this->securityAudit->checkRateLimit('berita_comment', 3, 300, $this->request)) {
            $session->setFlashdata('message', '<div class="alert alert-danger"><center>Terlalu banyak komentar. Silakan tunggu beberapa menit.</center></div>');

            return redirect()->to(base_url('main'));
        }

        $idBerita = $this->securityAudit->validateId($this->request->getPost('a'));
        if ($idBerita <= 0) {
            return redirect()->to(base_url('main'));
        }

        $berita = (new BeritaModel())->find($idBerita);
        if (! is_array($berita)) {
            return redirect()->to(base_url('main'));
        }

        $slug   = (string) ($berita['judul_seo'] ?? '');
        $target = base_url('berita/detail/' . $slug . '#listcomment');

        $securityCode = (string) ($this->request->getPost('secutity_code') ?? $this->request->getPost('security_code') ?? '');
        $captchaSvc   = new CaptchaService();
        if (! $captchaSvc->verify($securityCode, 'mycaptcha', true)) {
            $session->setFlashdata('message', '<div class="alert alert-danger"><center>Security Code yang anda masukkan salah!</center></div>');

            return redirect()->to($target);
        }

        $nama  = trim((string) $this->request->getPost('b'));
        $url   = trim((string) $this->request->getPost('c'));
        $isi   = trim((string) $this->request->getPost('d'));
        $email = trim((string) $this->request->getPost('e'));

        if ($nama === '' || $email === '' || $isi === '') {
            $session->setFlashdata('message', '<div class="alert alert-danger"><center>Nama, email dan isi komentar harus diisi!</center></div>');

            return redirect()->to($target);
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $session->setFlashdata('message', '<div class="alert alert-danger"><center>Format email tidak valid!</center></div>');

            return redirect()->to($target);
        }

        if (strlen($nama) > 100 || strlen($isi) > 1000) {
            $session->setFlashdata('message', '<div class="alert alert-danger"><center>Input terlalu panjang!</center></div>');

            return redirect()->to($target);
        }

        if ($url !== '' && ! filter_var($url, FILTER_VALIDATE_URL)) {
            $url = '';
        }

        (new KomentarModel())->insert([
            'id_berita'     => $idBerita,
            'nama_komentar' => cetak($nama),
            'url'           => cetak($url),
            'isi_komentar'  => cetak($isi),
            'tgl'           => date('Y-m-d'),
            'jam_komentar'  => date('H:i:s'),
            'aktif'         => 'N',
            'email'         => $email,
        ]);

        $session->setFlashdata('message', '<div class="alert alert-success"><center>Komentar anda akan tampil setelah kami setujui!</center></div>');

        return redirect()->to($target);
    }

    public function indeks_berita()
    {
        $tahun   = preg_replace('/[^0-9]/', '', (string) ($this->request->getPost('tahun') ?? date('Y')));
        $bulan   = preg_replace('/[^0-9]/', '', (string) ($this->request->getPost('bulan') ?? date('m')));
        $tanggal = preg_replace('/[^0-9]/', '', (string) ($this->request->getPost('tanggal') ?? date('d')));

        $bulan   = str_pad($bulan !== '' ? $bulan : date('m'), 2, '0', STR_PAD_LEFT);
        $tanggal = str_pad($tanggal !== '' ? $tanggal : date('d'), 2, '0', STR_PAD_LEFT);
        if (strlen($tahun) !== 4) {
            $tahun = date('Y');
        }

        $filterDate = "{$tahun}-{$bulan}-{$tanggal}";

        $data = [
            'title'       => 'Indeks Berita',
            'description' => description(),
            'keywords'    => keywords(),
            'record'      => $this->portalModel->view('kategori'),
            'hari_ini'    => $filterDate,
            'hitung'      => $this->portalModel->viewWhere('berita', ['tanggal' => $filterDate, 'status' => 'Y']),
        ];

        return $this->renderPortal('indeks_berita', $data);
    }
}
