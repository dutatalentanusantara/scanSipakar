<?php

namespace App\Controllers;

use App\Models\HubungiModel;
use App\Services\CaptchaService;

class Hubungi extends BasePortalController
{
    public function index()
    {
        $cap = (new CaptchaService())->create([
            'img_width'   => 180,
            'img_height'  => 42,
            'word_length' => 5,
        ]);
        session()->set('mycaptcha', $cap['word']);

        $iden   = $this->portalContext->getIdentitas();
        $alamat = $this->portalModel->findOne('mod_alamat', ['id_alamat' => 1]);

        $data = [
            'title'       => 'Hubungi Kami',
            'description' => description(),
            'keywords'    => keywords(),
            'image'       => $cap['image'],
            'iden'        => $iden,
            'rows'        => $alamat ?? ['alamat' => ''],
        ];

        return $this->renderPortal('hubungi', $data);
    }

    public function kirim()
    {
        if (! $this->request->is('post')) {
            return redirect()->to(base_url('hubungi'));
        }

        $session = session();
        $captcha = (string) ($this->request->getPost('secutity_code')
            ?? $this->request->getPost('security_code')
            ?? $this->request->getPost('captcha')
            ?? '');

        if (! (new CaptchaService())->verify($captcha, 'mycaptcha', true)) {
            $session->setFlashdata('message', '<div class="alert alert-danger"><center>Kode keamanan (CAPTCHA) salah atau kosong!</center></div>');

            return redirect()->to(base_url('hubungi'));
        }

        $nama   = htmlspecialchars(trim((string) $this->request->getPost('a')), ENT_QUOTES, 'UTF-8');
        $email  = trim((string) $this->request->getPost('b'));
        $subjek = htmlspecialchars(trim((string) ($this->request->getPost('subjek') ?: 'Pesan Pengunjung Website Siantar Barat')), ENT_QUOTES, 'UTF-8');
        $pesan  = htmlspecialchars(trim((string) $this->request->getPost('c')), ENT_QUOTES, 'UTF-8');

        if ($nama === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL) || $pesan === '') {
            $session->setFlashdata('message', '<div class="alert alert-danger"><center>Pastikan nama, email valid, dan isi pesan terisi lengkap!</center></div>');

            return redirect()->to(base_url('hubungi'));
        }

        (new HubungiModel())->insert([
            'nama'    => $nama,
            'email'   => $email,
            'subjek'  => $subjek,
            'pesan'   => $pesan,
            'tanggal' => date('Y-m-d'),
            'jam'     => date('H:i:s'),
            'dibaca'  => 'N',
        ]);

        $session->setFlashdata('message', '<div class="alert alert-success"><center>Pesan Anda berhasil terkirim! Terima kasih atas masukan Anda.</center></div>');

        return redirect()->to(base_url('hubungi'));
    }
}
