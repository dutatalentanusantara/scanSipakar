<?php

namespace App\Controllers;

use App\Models\KomentarVideoModel;
use App\Models\VideoModel;
use App\Services\CaptchaService;

class Playlist extends BasePortalController
{
    public function index($segment3 = null, $segment4 = null)
    {
        $perPage = 12;
        $jumlah  = (int) $this->portalModel->db()->table('playlist')->countAllResults();
        $dariRaw = $segment4 !== null ? $segment4 : $segment3;
        $dari    = $this->validatePaginationOffset($dariRaw);

        if ($jumlah > 0 && $dari >= $jumlah) {
            return redirect()->to(base_url('playlist'));
        }

        $data = [
            'title'           => 'Playlist',
            'description'     => description(),
            'keywords'        => keywords(),
            'playlist'        => $this->portalModel->viewWhereOrderingLimit('playlist', ['aktif' => 'Y'], 'id_playlist', 'DESC', $dari, $perPage),
            'pagination_html' => portal_pagination(base_url('playlist/index'), $jumlah, $perPage, $dari),
        ];

        return $this->renderPortal('playlist', $data);
    }

    public function detail(?string $slug = null, $segment4 = null)
    {
        $playlistSeo = $this->securityAudit->validateSlug($slug);
        if ($playlistSeo === null) {
            return redirect()->to(base_url('main'));
        }

        $row = $this->portalModel->findOne('playlist', ['playlist_seo' => $playlistSeo]);
        if ($row === null || empty($row['id_playlist']) || empty($row['jdl_playlist'])) {
            return redirect()->to(base_url('main'));
        }

        $playlistId = $this->securityAudit->validateId($row['id_playlist']);
        $perPage    = 12;
        $jumlah     = (int) $this->portalModel->db()->table('video')->where('id_playlist', $playlistId)->countAllResults();
        $dari       = $this->validatePaginationOffset($segment4);
        if ($jumlah > 0 && $dari >= $jumlah) {
            return redirect()->to(base_url('playlist/detail/' . $playlistSeo));
        }

        $plDesc = ! empty($row['keterangan'])
            ? cetak_meta((string) $row['keterangan'], 0, 200)
            : 'Galeri Video Playlist ' . trim(strip_tags((string) $row['jdl_playlist'])) . ' - Kecamatan Siantar Barat';

        $data = [
            'title'           => 'Playlist ' . $this->safeText((string) $row['jdl_playlist']),
            'description'     => $plDesc,
            'keywords'        => keywords(),
            'rows'            => $row,
            'detailplaylist'  => $this->portalModel->viewJoinTwo('video', 'users', 'playlist', 'username', 'id_playlist', ['video.id_playlist' => $playlistId], 'id_video', 'DESC', $dari, $perPage),
            'pagination_html' => portal_pagination(base_url('playlist/detail/' . $playlistSeo), $jumlah, $perPage, $dari),
        ];

        return $this->renderPortal('detailplaylist', $data);
    }

    public function watch(?string $slug = null)
    {
        $videoSeo = $this->securityAudit->validateSlug($slug);
        if ($videoSeo === null) {
            return redirect()->to(base_url('main'));
        }

        $videoModel = new VideoModel();
        $row        = $videoModel->findBySlug($videoSeo);
        if ($row === null || empty($row['id_video']) || empty($row['jdl_video'])) {
            return redirect()->to(base_url('main'));
        }

        $videoId = $this->securityAudit->validateId($row['id_video']);
        if ($videoId > 0 && $this->securityAudit->shouldIncrementViewCounter('video', $videoId)) {
            $nextViews = min(2147483647, max(0, (int) ($row['dilihat'] ?? 0)) + 1);
            $videoModel->update($videoId, ['dilihat' => $nextViews]);
        }

        $cap = (new CaptchaService())->create([
            'img_width'  => 150,
            'img_height' => 29,
            'word_length' => 5,
        ]);
        session()->set('mycaptcha', $cap['word']);

        $vidExcerpt = cetak_meta((string) ($row['keterangan'] ?? ''), 0, 200);
        $data       = [
            'title'       => cetak((string) $row['jdl_video']),
            'description' => $vidExcerpt !== '' ? $vidExcerpt : description(),
            'keywords'    => cetak((string) ($row['tagvid'] ?? '')),
            'rows'        => $row,
            'image'       => $cap['image'],
        ];

        return $this->renderPortal('play', $data);
    }

    public function kirim_komentar()
    {
        if (! $this->request->is('post')) {
            return redirect()->to(base_url('main'));
        }

        $session = session();
        if (! $this->securityAudit->checkRateLimit('playlist_comment', 3, 300, $this->request)) {
            $session->setFlashdata('message', '<div class="alert alert-danger"><center>Terlalu banyak komentar. Silakan tunggu beberapa menit.</center></div>');

            return redirect()->to(base_url('main'));
        }

        $videoId = $this->securityAudit->validateId($this->request->getPost('a'));
        if ($videoId <= 0) {
            return redirect()->to(base_url('main'));
        }

        $video = (new VideoModel())->find($videoId);
        if (! is_array($video)) {
            return redirect()->to(base_url('main'));
        }

        $target       = base_url('playlist/watch/' . ($video['video_seo'] ?? '') . '#listcomment');
        $securityCode = (string) ($this->request->getPost('secutity_code') ?? $this->request->getPost('security_code') ?? '');

        if (! (new CaptchaService())->verify($securityCode, 'mycaptcha', true)) {
            $session->setFlashdata('message', '<div class="alert alert-danger"><center>Security Code yang anda masukkan salah!</center></div>');

            return redirect()->to($target);
        }

        $nama = trim((string) $this->request->getPost('b'));
        $url  = trim((string) $this->request->getPost('c'));
        $isi  = trim((string) $this->request->getPost('d'));

        if ($nama === '' || $isi === '') {
            $session->setFlashdata('message', '<div class="alert alert-danger"><center>Nama dan isi komentar harus diisi!</center></div>');

            return redirect()->to($target);
        }

        if (strlen($nama) > 100 || strlen($isi) > 1000) {
            $session->setFlashdata('message', '<div class="alert alert-danger"><center>Input terlalu panjang!</center></div>');

            return redirect()->to($target);
        }

        if ($url !== '' && ! filter_var($url, FILTER_VALIDATE_URL)) {
            $url = '';
        }

        (new KomentarVideoModel())->insert([
            'id_video'     => $videoId,
            'nama_komentar' => cetak($nama),
            'url'          => cetak($url),
            'isi_komentar' => cetak($isi),
            'tgl_komentar' => date('Y-m-d'),
            'aktif'        => 'N',
        ]);

        $session->setFlashdata('message', '<div class="alert alert-success"><center>Komentar anda akan tampil setelah kami setujui!</center></div>');

        return redirect()->to($target);
    }
}
