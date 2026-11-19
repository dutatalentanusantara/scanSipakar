<?php

namespace App\Controllers;

use App\Models\TblCommentModel;
use App\Services\CaptchaService;

class Konsultasi extends BasePortalController
{
    public function index($segment3 = null, $segment4 = null)
    {
        $perPage = 10;
        $jumlah  = (int) $this->portalModel->db()->table('tbl_comment')->where('reply', 0)->countAllResults();
        $dariRaw = $segment4 !== null ? $segment4 : $segment3;
        $dari    = $this->validatePaginationOffset($dariRaw);

        if ($jumlah > 0 && $dari >= $jumlah) {
            return redirect()->to(base_url('konsultasi'));
        }

        $usr = null;
        if (session()->get('username')) {
            $usr = $this->portalModel->findOne('users', ['username' => (string) session()->get('username')]);
        }

        $cap = (new CaptchaService())->create([
            'img_width'   => 180,
            'img_height'  => 42,
            'word_length' => 5,
        ]);
        session()->set('mycaptcha', $cap['word']);

        $comments = $this->portalModel->viewWhereOrderingLimit('tbl_comment', ['reply' => 0], 'id_komentar', 'DESC', $dari, $perPage);
        $data     = [
            'title'           => 'Forum Konsultasi',
            'description'     => description(),
            'keywords'        => keywords(),
            'usr'             => $usr,
            'image'           => $cap['image'],
            'konsultasi'      => $comments,
            'bottom'          => $comments,
            'pagination_html' => portal_pagination(base_url('konsultasi/index'), $jumlah, $perPage, $dari),
        ];

        return $this->renderPortal('konsultasi', $data);
    }

    public function reply()
    {
        $session = session();
        if (! $this->request->is('post') || $this->request->getPost('submit') === null) {
            return redirect()->to(base_url('konsultasi'));
        }

        $replyId      = (int) ($this->request->getPost('a') ?? 0);
        $redirectBack = $replyId > 0 ? base_url('konsultasi#comment-' . $replyId) : base_url('konsultasi');

        $captchaInput = (string) ($this->request->getPost('secutity_code')
            ?? $this->request->getPost('security_code')
            ?? $this->request->getPost('captcha')
            ?? '');

        if (! (new CaptchaService())->verify($captchaInput, 'mycaptcha', true)) {
            $session->setFlashdata('message', '<div class="alert alert-danger"><center>Kode keamanan (CAPTCHA) salah atau kosong!</center></div>');

            return redirect()->to($redirectBack);
        }

        $nama  = trim((string) $this->request->getPost('b'));
        $email = trim((string) $this->request->getPost('c'));
        $pesan = trim((string) $this->request->getPost('d'));

        if ($nama === '' || $email === '' || $pesan === '') {
            $session->setFlashdata('message', '<div class="alert alert-danger"><center>Semua field harus diisi!</center></div>');

            return redirect()->to(base_url('konsultasi'));
        }

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $session->setFlashdata('message', '<div class="alert alert-danger"><center>Format email tidak valid!</center></div>');

            return redirect()->to(base_url('konsultasi'));
        }

        if ($replyId < 0 || $replyId > 999999999) {
            $session->setFlashdata('message', '<div class="alert alert-danger"><center>ID balasan tidak valid!</center></div>');

            return redirect()->to(base_url('konsultasi'));
        }

        $model = new TblCommentModel();
        $model->insert([
            'reply'            => $replyId,
            'nama_lengkap'     => cetak($nama),
            'alamat_email'     => $email,
            'isi_pesan'        => cetak($pesan),
            'tanggal_komentar' => date('Y-m-d'),
            'jam_komentar'     => date('H:i:s'),
            'aktif'            => 'Y',
        ]);
        $newId = (int) $model->getInsertID();

        $session->setFlashdata('message', '<div class="alert alert-success"><center>Komentar berhasil dikirim!</center></div>');

        return redirect()->to(base_url('konsultasi#comment-' . $newId));
    }

    public function delete($id = null)
    {
        if (! session()->get('username') || (string) session()->get('level') !== 'admin') {
            session()->setFlashdata('message', '<div class="alert alert-danger"><center>Akses ditolak!</center></div>');

            return redirect()->to(base_url('konsultasi'));
        }

        $idKomentar = $this->securityAudit->validateId($id);
        if ($idKomentar <= 0) {
            session()->setFlashdata('message', '<div class="alert alert-danger"><center>ID komentar tidak valid!</center></div>');

            return redirect()->to(base_url('konsultasi'));
        }

        $model = new TblCommentModel();
        if (! $model->find($idKomentar)) {
            session()->setFlashdata('message', '<div class="alert alert-danger"><center>Komentar tidak ditemukan!</center></div>');

            return redirect()->to(base_url('konsultasi'));
        }

        $model->delete($idKomentar);
        session()->setFlashdata('message', '<div class="alert alert-success"><center>Komentar berhasil dihapus!</center></div>');

        return redirect()->to(base_url('konsultasi'));
    }
}
