<?php

namespace App\Controllers\Api\V1;

use App\Models\BeritaModel;
use App\Models\DownloadModel;
use App\Models\HalamanModel;
use App\Models\KategoriModel;
use App\Models\LegacyPortalModel;
use App\Services\PortalCacheService;
use App\Services\PortalContextService;
use CodeIgniter\RESTful\ResourceController;

class ContentController extends ResourceController
{
    protected $format = 'json';

    private PortalCacheService $portalCache;
    private PortalContextService $portalContext;
    private LegacyPortalModel $portalModel;

    public function __construct()
    {
        $this->portalCache   = new PortalCacheService();
        $this->portalModel   = new LegacyPortalModel();
        $this->portalContext = new PortalContextService($this->portalCache, $this->portalModel);
    }

    public function identitas()
    {
        $iden = $this->portalContext->getIdentitas();

        return $this->respond([
            'status' => 'ok',
            'data'   => [
                'nama_website'   => (string) ($iden['nama_website'] ?? ''),
                'email'          => (string) ($iden['email'] ?? ''),
                'url'            => (string) ($iden['url'] ?? base_url()),
                'no_telp'        => (string) ($iden['no_telp'] ?? ''),
                'meta_deskripsi' => (string) ($iden['meta_deskripsi'] ?? ''),
                'logo'           => $this->portalContext->getLogo(),
                'alamat'         => $this->portalContext->getAlamat(),
                'template'       => $this->portalContext->getActiveTemplate(),
            ],
        ]);
    }

    public function berita()
    {
        $page     = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage  = max(1, min(50, (int) ($this->request->getGet('per_page') ?? 10)));
        $offset   = ($page - 1) * $perPage;
        $catParam = trim((string) ($this->request->getGet('kategori') ?? ''));

        $categoryId = null;
        if ($catParam !== '') {
            if (is_numeric($catParam)) {
                $categoryId = (int) $catParam;
            } else {
                $cat = (new KategoriModel())->where('kategori_seo', $catParam)->first();
                if ($cat === null) {
                    return $this->respond([
                        'status' => 'ok',
                        'data'   => [],
                        'meta'   => ['page' => $page, 'per_page' => $perPage, 'total' => 0, 'total_pages' => 0],
                    ]);
                }
                $categoryId = (int) $cat['id_kategori'];
            }
        }

        $model = new BeritaModel();
        $total = $model->countPublished($categoryId);
        $rows  = $model->getPublishedJoined($perPage, $offset, $categoryId);

        $data = array_map(static function (array $row): array {
            $plain = trim(strip_tags((string) ($row['isi_berita'] ?? '')));

            return [
                'id_berita'     => (int) $row['id_berita'],
                'judul'         => (string) ($row['judul'] ?? ''),
                'judul_seo'     => (string) ($row['judul_seo'] ?? ''),
                'url'           => base_url((string) ($row['judul_seo'] ?? '')),
                'ringkasan'     => mb_strlen($plain) > 220 ? mb_substr($plain, 0, 220) . '...' : $plain,
                'gambar'        => ! empty($row['gambar']) ? base_url('asset/foto_berita/' . $row['gambar']) : null,
                'kategori'      => (string) ($row['nama_kategori'] ?? ''),
                'kategori_seo'  => (string) ($row['kategori_seo'] ?? ''),
                'penulis'       => (string) ($row['nama_lengkap'] ?? ($row['username'] ?? '')),
                'tanggal'       => (string) ($row['tanggal'] ?? ''),
                'jam'           => (string) ($row['jam'] ?? ''),
                'dibaca'        => (int) ($row['dibaca'] ?? 0),
            ];
        }, $rows);

        return $this->respond([
            'status' => 'ok',
            'data'   => $data,
            'meta'   => [
                'page'        => $page,
                'per_page'    => $perPage,
                'total'       => $total,
                'total_pages' => (int) ceil($total / $perPage),
            ],
        ]);
    }

    public function beritaDetail(string $identifier = '')
    {
        $identifier = trim($identifier);
        if ($identifier === '') {
            return $this->failValidationErrors('Article identifier is required.');
        }

        $model = new BeritaModel();
        if (is_numeric($identifier)) {
            $row = $model->db->table('berita')
                ->select('berita.*, users.nama_lengkap, kategori.nama_kategori, kategori.kategori_seo')
                ->join('users', 'berita.username = users.username', 'left')
                ->join('kategori', 'berita.id_kategori = kategori.id_kategori', 'left')
                ->where('berita.id_berita', (int) $identifier)
                ->where('berita.status', 'Y')
                ->limit(1)
                ->get()
                ->getRowArray();
        } else {
            $row = $model->findBySlug($identifier);
            if ($row !== null && ($row['status'] ?? 'N') !== 'Y') {
                $row = null;
            }
        }

        if ($row === null) {
            return $this->failNotFound('Berita tidak ditemukan.');
        }

        return $this->respond([
            'status' => 'ok',
            'data'   => [
                'id_berita'         => (int) $row['id_berita'],
                'judul'             => (string) ($row['judul'] ?? ''),
                'sub_judul'         => (string) ($row['sub_judul'] ?? ''),
                'judul_seo'         => (string) ($row['judul_seo'] ?? ''),
                'url'               => base_url((string) ($row['judul_seo'] ?? '')),
                'isi_berita'        => (string) ($row['isi_berita'] ?? ''),
                'gambar'            => ! empty($row['gambar']) ? base_url('asset/foto_berita/' . $row['gambar']) : null,
                'keterangan_gambar' => (string) ($row['keterangan_gambar'] ?? ''),
                'kategori'          => (string) ($row['nama_kategori'] ?? ''),
                'penulis'           => (string) ($row['nama_lengkap'] ?? ''),
                'tanggal'           => (string) ($row['tanggal'] ?? ''),
                'jam'               => (string) ($row['jam'] ?? ''),
                'dibaca'            => (int) ($row['dibaca'] ?? 0),
                'tag'               => (string) ($row['tag'] ?? ''),
            ],
        ]);
    }

    public function agenda()
    {
        $page    = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = max(1, min(50, (int) ($this->request->getGet('per_page') ?? 12)));
        $offset  = ($page - 1) * $perPage;
        $total   = (int) $this->portalModel->db()->table('agenda')->countAllResults();
        $rows    = $this->portalModel->db()->table('agenda')
            ->select('agenda.id_agenda, agenda.tema, agenda.tema_seo, agenda.isi_agenda, agenda.tempat, agenda.jam, agenda.tgl_mulai, agenda.tgl_selesai, agenda.tgl_posting, agenda.pengirim, agenda.gambar, agenda.dibaca, users.nama_lengkap AS penulis')
            ->join('users', 'agenda.username = users.username', 'left')
            ->orderBy('agenda.id_agenda', 'DESC')
            ->limit($perPage, $offset)
            ->get()
            ->getResultArray();

        $data = array_map(static function (array $row): array {
            return [
                'id_agenda'   => (int) ($row['id_agenda'] ?? 0),
                'tema'        => (string) ($row['tema'] ?? ''),
                'tema_seo'    => (string) ($row['tema_seo'] ?? ''),
                'url'         => ! empty($row['tema_seo']) ? base_url('agenda/detail/' . $row['tema_seo']) : base_url('agenda'),
                'isi_agenda'  => (string) ($row['isi_agenda'] ?? ''),
                'tempat'      => (string) ($row['tempat'] ?? ''),
                'jam'         => (string) ($row['jam'] ?? ''),
                'tgl_mulai'   => (string) ($row['tgl_mulai'] ?? ''),
                'tgl_selesai' => (string) ($row['tgl_selesai'] ?? ''),
                'tgl_posting' => (string) ($row['tgl_posting'] ?? ''),
                'pengirim'    => (string) ($row['pengirim'] ?? ''),
                'gambar'      => ! empty($row['gambar']) ? base_url('asset/foto_agenda/' . $row['gambar']) : null,
                'dibaca'      => (int) ($row['dibaca'] ?? 0),
                'penulis'     => (string) ($row['penulis'] ?? ''),
            ];
        }, $rows);

        return $this->respond([
            'status' => 'ok',
            'data'   => $data,
            'meta'   => [
                'page'        => $page,
                'per_page'    => $perPage,
                'total'       => $total,
                'total_pages' => (int) ceil($total / $perPage),
            ],
        ]);
    }

    public function kategori()
    {
        $rows = (new KategoriModel())->where('aktif', 'Y')->orderBy('id_kategori', 'DESC')->findAll();

        return $this->respond([
            'status' => 'ok',
            'data'   => $rows,
        ]);
    }

    public function download()
    {
        $rows = (new DownloadModel())->orderBy('id_download', 'DESC')->findAll();
        $data = array_map(static function (array $doc): array {
            return [
                'id_download' => (int) $doc['id_download'],
                'judul'       => (string) ($doc['judul'] ?? ''),
                'nama_file'   => basename((string) ($doc['nama_file'] ?? '')),
                'tgl_posting' => (string) ($doc['tgl_posting'] ?? ''),
                'hits'        => (int) ($doc['hits'] ?? 0),
                'download_url'=> base_url('download/file/' . (int) $doc['id_download']),
            ];
        }, $rows);

        return $this->respond([
            'status' => 'ok',
            'data'   => $data,
        ]);
    }

    public function halaman(string $slug = '')
    {
        $slug = trim($slug);
        if ($slug === '') {
            return $this->failValidationErrors('Halaman slug is required.');
        }

        $row = (new HalamanModel())->findBySlug($slug);
        if ($row === null) {
            return $this->failNotFound('Halaman tidak ditemukan.');
        }

        return $this->respond([
            'status' => 'ok',
            'data'   => $row,
        ]);
    }
}
