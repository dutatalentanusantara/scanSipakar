<?php

namespace App\Controllers;

class Pelayanan extends BasePortalController
{
    public function index()
    {
        $layananList = [
            [
                'id'             => 'pernyataan-ahli-waris',
                'kategori'       => 'ahli-waris',
                'kategori_label' => 'Kelas Waris (A)',
                'icon'           => 'fas fa-file-signature',
                'warna'          => '#047857',
                'judul'          => 'Surat Pernyataan Ahli Waris',
                'deskripsi'      => 'Penetapan dan pengesahan resmi Surat Pernyataan Ahli Waris (Persyaratan Kelas Waris A) bagi keluarga pewaris yang sah di Kecamatan Siantar Barat.',
                'waktu'          => '30 Menit',
                'biaya'          => 'GRATIS (Rp 0)',
                'syarat'         => [
                    'Fotokopi KTP dan KK seluruh ahli waris',
                    'Surat/akta kematian',
                    'Surat nikah/akta perkawinan',
                    'Surat cerai (jika ada)',
                    'Surat kematian dari RS/dokter',
                    'Akta kelahiran ahli waris di bawah 17 tahun',
                    'Wajib melampirkan foto ahli waris saat penandatanganan surat (bersama saksi)',
                    'Fotokopi KTP saksi',
                    'SPPT PBB lunas tahun terakhir',
                ],
                'alur' => 'Pengantar Kelurahan & verifikasi silsilah keluarga -> Pemeriksaan dokumen di loket Siap1Pintu Kecamatan -> Penandatanganan berkas bersama para saksi -> Registrasi & pengesahan Camat.',
            ],
            [
                'id'             => 'penyerahan-hak-waris',
                'kategori'       => 'ahli-waris',
                'kategori_label' => 'Kelas Waris (B)',
                'icon'           => 'fas fa-handshake',
                'warna'          => '#0284c7',
                'judul'          => 'Penyerahan Hak Waris',
                'deskripsi'      => 'Pengurusan legalisasi dan administrasi Penyerahan Hak Waris (Persyaratan Kelas Waris B) atas kepemilikan tanah, bangunan, maupun aset keluarga.',
                'waktu'          => '30 Menit',
                'biaya'          => 'GRATIS (Rp 0)',
                'syarat'         => [
                    'Berkas ahli waris',
                    'Fotokopi KTP dan KK',
                    'Bukti kepemilikan tanah (SHM, AJB, surat jual beli, hibah, atau dokumen sah lainnya)',
                    'SPPT PBB lunas',
                ],
                'alur' => 'Validasi berkas ahli waris di Kelurahan -> Verifikasi bukti kepemilikan tanah/objek waris di Kecamatan -> Penandatanganan kesepakatan para ahli waris -> Legalisasi resmi Kecamatan.',
            ],
            [
                'id'             => 'surat-kuasa-waris',
                'kategori'       => 'ahli-waris',
                'kategori_label' => 'Kelas Waris (C)',
                'icon'           => 'fas fa-file-contract',
                'warna'          => '#7c3aed',
                'judul'          => 'Surat Kuasa Ahli Waris',
                'deskripsi'      => 'Pengesahan Surat Kuasa Ahli Waris (Persyaratan Kelas Waris C) untuk penunjukan kuasa pengurusan rekening bank, tabungan, aset, atau hak waris.',
                'waktu'          => '30 Menit',
                'biaya'          => 'GRATIS (Rp 0)',
                'syarat'         => [
                    'Berkas ahli waris',
                    'Fotokopi buku rekening bank',
                    'Bukti objek yang dikuasakan',
                    'Fotokopi KTP pemberi dan penerima kuasa',
                ],
                'alur' => 'Penyusunan format surat kuasa di Kelurahan -> Validasi batas wewenang objek kuasa di loket Siap1Pintu Kecamatan -> Penandatanganan para pihak bermeterai -> Pengesahan registrasi Kecamatan.',
            ],
            [
                'id'             => 'hibah-tanah-bangunan',
                'kategori'       => 'ahli-waris',
                'kategori_label' => 'Kelas Waris (D)',
                'icon'           => 'fas fa-home',
                'warna'          => '#b45309',
                'judul'          => 'Surat Hibah Tanah dan Bangunan',
                'deskripsi'      => 'Rekomendasi dan pengesahan Surat Hibah Tanah dan Bangunan (Persyaratan Kelas Waris D) dari pemberi hibah kepada penerima hibah.',
                'waktu'          => '30 Menit',
                'biaya'          => 'GRATIS (Rp 0)',
                'syarat'         => [
                    'Berkas ahli waris',
                    'KTP dan KK',
                    'Surat nikah',
                    'Persetujuan pasangan jika harta bersama',
                    'Sertifikat asli dan fotokopi surat tanah',
                    'SPPT PBB lunas',
                    'Pernyataan tanah tidak bersengketa atau dijaminkan',
                ],
                'alur' => 'Pemeriksaan lokasi dan batas tanah di Kelurahan -> Verifikasi dokumen yuridis bebas sengketa di Kecamatan -> Penandatanganan surat hibah & persetujuan pasangan -> Pengesahan Camat.',
            ],
            [
                'id'             => 'surat-pindah',
                'kategori'       => 'kependudukan',
                'kategori_label' => 'Kependudukan',
                'icon'           => 'fas fa-truck-moving',
                'warna'          => '#8b5cf6',
                'judul'          => 'Surat Keterangan Pindah Datang (SKPWNI)',
                'deskripsi'      => 'Pengurusan perpindahan domisili kependudukan antar-kelurahan, antar-kecamatan, atau ke luar Kota Pematangsiantar.',
                'waktu'          => '30 Menit',
                'biaya'          => 'GRATIS (Rp 0)',
                'syarat'         => [
                    'Kartu Keluarga (KK) dan KTP-el asli pemohon',
                    'Surat Pengantar Pindah dari Kelurahan asal',
                    'Alamat tujuan lengkap dan jelas (RT/RW, Kelurahan, Kecamatan, Kota)',
                    'Pas foto ukuran 3x4 (2 lembar)',
                ],
                'alur' => 'Pemeriksaan status domisili kelurahan -> Penerbitan format SKPWNI di Kecamatan -> Pengesahan Camat/Kasi Pelayanan.',
            ],
            [
                'id'             => 'nikah',
                'kategori'       => 'sosial',
                'kategori_label' => 'Sosial & Rekomendasi',
                'icon'           => 'fas fa-ring',
                'warna'          => '#db2777',
                'judul'          => 'Rekomendasi / Dispensasi Nikah (N1-N4)',
                'deskripsi'      => 'Penerbitan surat rekomendasi pernikahan ke Kantor Urusan Agama (KUA) atau Dinas Kependudukan dan Catatan Sipil.',
                'waktu'          => '30 Menit',
                'biaya'          => 'GRATIS (Rp 0)',
                'syarat'         => [
                    'Surat Keterangan untuk Nikah (Model N1 s/d N4) dari Kelurahan',
                    'Fotokopi KTP-el calon pengantin & orang tua/wali',
                    'Fotokopi Kartu Keluarga & Akta Kelahiran calon pengantin',
                    'Pas foto berdampingan latar biru ukuran 2x3 & 4x6 (masing-masing 3 lembar)',
                    'Akta Cerai / Kematian pasangan terdahulu (bagi janda/duda)',
                ],
                'alur' => 'Pemberkasan formulir N1-N4 di kelurahan -> Register pencatatan di seksi Kesos Kecamatan -> Penandatanganan rekomendasi nikah.',
            ],
            [
                'id'             => 'legalisir',
                'kategori'       => 'surat-keterangan',
                'kategori_label' => 'Surat Keterangan',
                'icon'           => 'fas fa-stamp',
                'warna'          => '#475569',
                'judul'          => 'Legalisir Dokumen & Surat Resmi',
                'deskripsi'      => 'Pengesahan dan stempel basah legalisir salinan surat atau dokumen yang dikeluarkan oleh pihak Kecamatan Siantar Barat.',
                'waktu'          => '30 Menit',
                'biaya'          => 'GRATIS (Rp 0)',
                'syarat'         => [
                    'Membawa dokumen asli untuk diverifikasi petugas',
                    'Salinan / fotokopi dokumen maksimal 5 lembar per pengajuan',
                    'Identitas pemohon (KTP-el asli)',
                ],
                'alur' => 'Pengecekan kesesuaian fisik asli dengan salinan -> Pembubuhan stempel legalisir -> Penandatanganan pejabat berwenang.',
            ],
        ];

        $kelurahanList = [
            [
                'no'      => 1,
                'nama'    => 'Kelurahan Banjar',
                'alamat'  => 'Jl. Bola Kaki No. 11',
                'lurah'   => 'Kepala Kelurahan Banjar',
                'telepon' => '(0622) 21082',
                'wilayah' => 'Kawasan padat penduduk dan niaga',
                'link'    => '#banjar',
            ],
            [
                'no'      => 2,
                'nama'    => 'Kelurahan Bantan',
                'alamat'  => 'Jl. Seram No. 31',
                'lurah'   => 'Kepala Kelurahan Bantan',
                'telepon' => '(0622) 21081',
                'wilayah' => 'Pusat pemerintahan dan permukiman warga',
                'link'    => '#bantan',
            ],
            [
                'no'      => 3,
                'nama'    => 'Kelurahan Dwikora',
                'alamat'  => 'Jl. Bandung No. 2',
                'lurah'   => 'Kepala Kelurahan Dwikora',
                'telepon' => '(0622) 21083',
                'wilayah' => 'Sentra perdagangan, pasar, dan perkantoran',
                'link'    => '#dwikora',
            ],
            [
                'no'      => 4,
                'nama'    => 'Kelurahan Proklamasi',
                'alamat'  => 'Jl. W.R. Supratman No. 2',
                'lurah'   => 'Kepala Kelurahan Proklamasi',
                'telepon' => '(0622) 21084',
                'wilayah' => 'Kawasan pendidikan, rumah ibadah, dan cagar budaya',
                'link'    => '#proklamasi',
            ],
            [
                'no'      => 5,
                'nama'    => 'Kelurahan Simarito',
                'alamat'  => 'Jl. Melati No. 59',
                'lurah'   => 'Kepala Kelurahan Simarito',
                'telepon' => '(0622) 21085',
                'wilayah' => 'Kawasan perumahan dan sentra UMKM kuliner',
                'link'    => '#simarito',
            ],
            [
                'no'      => 6,
                'nama'    => 'Kelurahan Sipinggol-pinggol',
                'alamat'  => 'Jl. Enggang',
                'lurah'   => 'Kepala Kelurahan Sipinggol-pinggol',
                'telepon' => '(0622) 21086',
                'wilayah' => 'Lokasi kompleks Kantor Camat Siantar Barat',
                'link'    => '#sipinggol',
            ],
            [
                'no'      => 7,
                'nama'    => 'Kelurahan Teladan',
                'alamat'  => 'Jl. Tarutung',
                'lurah'   => 'Kepala Kelurahan Teladan',
                'telepon' => '(0622) 21087',
                'wilayah' => 'Permukiman asri dan fasilitas olahraga',
                'link'    => '#teladan',
            ],
            [
                'no'      => 8,
                'nama'    => 'Kelurahan Timbang Galung',
                'alamat'  => 'Jl. Menambin',
                'lurah'   => 'Kepala Kelurahan Timbang Galung',
                'telepon' => '(0622) 21088',
                'wilayah' => 'Kawasan perumahan hijau dan taman kota Adam Malik',
                'link'    => '#timbanggalung',
            ],
        ];

        $downloadModel = new \App\Models\DownloadModel();
        $ahliWarisDoc  = $downloadModel->where('id_download', 8)
            ->orWhere('nama_file LIKE', '%1IThAOn45xjfhLOPXgTg2lHQn_qix964s%')
            ->orWhere('judul LIKE', '%Surat Pernyataan Ahli Waris%')
            ->first();

        if ($ahliWarisDoc === null) {
            $ahliWarisDoc = [
                'id_download' => 8,
                'judul'       => 'Contoh Surat Pernyataan Ahli Waris',
                'nama_file'   => 'https://drive.google.com/drive/folders/1IThAOn45xjfhLOPXgTg2lHQn_qix964s?usp=sharing',
                'hits'        => 0,
            ];
        }

        $penyerahanWarisDoc = $downloadModel->where('id_download', 9)
            ->orWhere('nama_file LIKE', '%1vPWAgGLelGxMGUmbgVitkGumtVfE3eAk%')
            ->orWhere('judul LIKE', '%Penyerahan Hak Waris%')
            ->first();

        if ($penyerahanWarisDoc === null) {
            $penyerahanWarisDoc = [
                'id_download' => 9,
                'judul'       => 'Contoh Surat Penyerahan Hak Waris',
                'nama_file'   => 'https://drive.google.com/drive/folders/1vPWAgGLelGxMGUmbgVitkGumtVfE3eAk?usp=sharing',
                'hits'        => 0,
            ];
        }

        $suratKuasaDoc = $downloadModel->where('id_download', 10)
            ->orWhere('nama_file LIKE', '%1VOoMpKH1FIhCsP83iHOcCsXdI2SclX5B%')
            ->orWhere('judul LIKE', '%Surat Kuasa%')
            ->first();

        if ($suratKuasaDoc === null) {
            $suratKuasaDoc = [
                'id_download' => 10,
                'judul'       => 'Contoh Surat Kuasa Ahli Waris',
                'nama_file'   => 'https://drive.google.com/drive/folders/1VOoMpKH1FIhCsP83iHOcCsXdI2SclX5B?usp=sharing',
                'hits'        => 0,
            ];
        }

        $suratHibahDoc = $downloadModel->where('id_download', 11)
            ->orWhere('nama_file LIKE', '%1MeXXnVbud8GF4n2wm9D_HiZAvlkBarcm%')
            ->orWhere('judul LIKE', '%Surat Hibah%')
            ->first();

        if ($suratHibahDoc === null) {
            $suratHibahDoc = [
                'id_download' => 11,
                'judul'       => 'Contoh Surat Hibah Tanah dan Bangunan',
                'nama_file'   => 'https://drive.google.com/drive/folders/1MeXXnVbud8GF4n2wm9D_HiZAvlkBarcm?usp=sharing',
                'hits'        => 0,
            ];
        }

        $suratPindahDoc = $downloadModel->where('id_download', 12)
            ->orWhere('nama_file LIKE', '%1pQDNp4nnDx4iUIRWMhRQd4wdkOSGG_E8%')
            ->orWhere('judul LIKE', '%Surat Keterangan Pindah%')
            ->first();

        if ($suratPindahDoc === null) {
            $suratPindahDoc = [
                'id_download' => 12,
                'judul'       => 'Contoh Surat Keterangan Pindah',
                'nama_file'   => 'https://drive.google.com/drive/folders/1pQDNp4nnDx4iUIRWMhRQd4wdkOSGG_E8?usp=sharing',
                'hits'        => 0,
            ];
        }

        $dispensasiNikahDoc = $downloadModel->where('id_download', 13)
            ->orWhere('nama_file LIKE', '%1NuRs_ROLcmlVEXdh-aOhEd92DV2eF1Eu%')
            ->orWhere('judul LIKE', '%Dispensasi Nikah%')
            ->first();

        if ($dispensasiNikahDoc === null) {
            $dispensasiNikahDoc = [
                'id_download' => 13,
                'judul'       => 'Contoh Surat Dispensasi Nikah',
                'nama_file'   => 'https://drive.google.com/drive/folders/1NuRs_ROLcmlVEXdh-aOhEd92DV2eF1Eu?usp=sharing',
                'hits'        => 0,
            ];
        }

        return $this->renderPortal('pelayanan', [
            'title'                      => 'Standar Pelayanan, Administrasi Warga & Ahli Waris - Kecamatan Siantar Barat',
            'description'                => 'Panduan lengkap persyaratan kelas waris (Surat Pernyataan Ahli Waris, Penyerahan Hak Waris, Surat Kuasa, Hibah Tanah dan Bangunan), surat keterangan pindah (SKPWNI), dispensasi nikah, legalisir, dan pelayanan kantor kelurahan se-Kecamatan Siantar Barat.',
            'keywords'                   => 'pelayanan siantar barat, ahli waris, kelas waris, surat pernyataan ahli waris, penyerahan hak waris, surat kuasa ahli waris, surat hibah tanah dan bangunan, surat keterangan pindah, skpwni, dispensasi nikah, rekomendasi nikah, legalisir, administrasi warga, kelurahan siantar barat, siap1pintu pematangsiantar',
            'layanan_list'               => $layananList,
            'kelurahan_list'             => $kelurahanList,
            'ahli_waris_download'        => $ahliWarisDoc,
            'penyerahan_waris_download'  => $penyerahanWarisDoc,
            'surat_kuasa_download'       => $suratKuasaDoc,
            'surat_hibah_download'       => $suratHibahDoc,
            'surat_pindah_download'      => $suratPindahDoc,
            'dispensasi_nikah_download'  => $dispensasiNikahDoc,
            'jam_pelayanan'              => [
                'senin_kamis'  => '08:00 - 16:00 WIB (Istirahat 12:00 - 13:30 WIB)',
                'jumat'        => '08:00 - 16:00 WIB (Istirahat 11:30 - 13:30 WIB)',
                'sabtu_minggu' => 'Libur / Tutup',
            ],
        ]);
    }

    public function unduhAhliWaris()
    {
        $downloadModel = new \App\Models\DownloadModel();
        $doc           = $downloadModel->where('id_download', 8)
            ->orWhere('nama_file LIKE', '%1IThAOn45xjfhLOPXgTg2lHQn_qix964s%')
            ->orWhere('judul LIKE', '%Surat Pernyataan Ahli Waris%')
            ->first();

        $targetUrl = 'https://drive.google.com/drive/folders/1IThAOn45xjfhLOPXgTg2lHQn_qix964s?usp=sharing';

        if ($doc !== null) {
            $downloadModel->incrementHits((int) $doc['id_download'], (int) ($doc['hits'] ?? 0));
            $targetUrl   = ! empty($doc['nama_file']) ? (string) $doc['nama_file'] : $targetUrl;
            $updatedHits = (int) ($doc['hits'] ?? 0) + 1;
        } else {
            $updatedHits = 1;
        }

        if ($this->request->isAJAX() || $this->request->getGet('ajax') === '1') {
            return $this->response->setJSON([
                'status' => 'success',
                'hits'   => $updatedHits,
                'url'    => $targetUrl,
            ]);
        }

        return redirect()->to($targetUrl);
    }

    public function unduhPenyerahanWaris()
    {
        $downloadModel = new \App\Models\DownloadModel();
        $doc           = $downloadModel->where('id_download', 9)
            ->orWhere('nama_file LIKE', '%1vPWAgGLelGxMGUmbgVitkGumtVfE3eAk%')
            ->orWhere('judul LIKE', '%Penyerahan Hak Waris%')
            ->first();

        $targetUrl = 'https://drive.google.com/drive/folders/1vPWAgGLelGxMGUmbgVitkGumtVfE3eAk?usp=sharing';

        if ($doc !== null) {
            $downloadModel->incrementHits((int) $doc['id_download'], (int) ($doc['hits'] ?? 0));
            $targetUrl   = ! empty($doc['nama_file']) ? (string) $doc['nama_file'] : $targetUrl;
            $updatedHits = (int) ($doc['hits'] ?? 0) + 1;
        } else {
            $updatedHits = 1;
        }

        if ($this->request->isAJAX() || $this->request->getGet('ajax') === '1') {
            return $this->response->setJSON([
                'status' => 'success',
                'hits'   => $updatedHits,
                'url'    => $targetUrl,
            ]);
        }

        return redirect()->to($targetUrl);
    }

    public function unduhSuratKuasa()
    {
        $downloadModel = new \App\Models\DownloadModel();
        $doc           = $downloadModel->where('id_download', 10)
            ->orWhere('nama_file LIKE', '%1VOoMpKH1FIhCsP83iHOcCsXdI2SclX5B%')
            ->orWhere('judul LIKE', '%Surat Kuasa%')
            ->first();

        $targetUrl = 'https://drive.google.com/drive/folders/1VOoMpKH1FIhCsP83iHOcCsXdI2SclX5B?usp=sharing';

        if ($doc !== null) {
            $downloadModel->incrementHits((int) $doc['id_download'], (int) ($doc['hits'] ?? 0));
            $targetUrl   = ! empty($doc['nama_file']) ? (string) $doc['nama_file'] : $targetUrl;
            $updatedHits = (int) ($doc['hits'] ?? 0) + 1;
        } else {
            $updatedHits = 1;
        }

        if ($this->request->isAJAX() || $this->request->getGet('ajax') === '1') {
            return $this->response->setJSON([
                'status' => 'success',
                'hits'   => $updatedHits,
                'url'    => $targetUrl,
            ]);
        }

        return redirect()->to($targetUrl);
    }

    public function unduhSuratHibah()
    {
        $downloadModel = new \App\Models\DownloadModel();
        $doc           = $downloadModel->where('id_download', 11)
            ->orWhere('nama_file LIKE', '%1MeXXnVbud8GF4n2wm9D_HiZAvlkBarcm%')
            ->orWhere('judul LIKE', '%Surat Hibah%')
            ->first();

        $targetUrl = 'https://drive.google.com/drive/folders/1MeXXnVbud8GF4n2wm9D_HiZAvlkBarcm?usp=sharing';

        if ($doc !== null) {
            $downloadModel->incrementHits((int) $doc['id_download'], (int) ($doc['hits'] ?? 0));
            $targetUrl   = ! empty($doc['nama_file']) ? (string) $doc['nama_file'] : $targetUrl;
            $updatedHits = (int) ($doc['hits'] ?? 0) + 1;
        } else {
            $updatedHits = 1;
        }

        if ($this->request->isAJAX() || $this->request->getGet('ajax') === '1') {
            return $this->response->setJSON([
                'status' => 'success',
                'hits'   => $updatedHits,
                'url'    => $targetUrl,
            ]);
        }

        return redirect()->to($targetUrl);
    }

    public function unduhSuratPindah()
    {
        $downloadModel = new \App\Models\DownloadModel();
        $doc           = $downloadModel->where('id_download', 12)
            ->orWhere('nama_file LIKE', '%1pQDNp4nnDx4iUIRWMhRQd4wdkOSGG_E8%')
            ->orWhere('judul LIKE', '%Surat Keterangan Pindah%')
            ->first();

        $targetUrl = 'https://drive.google.com/drive/folders/1pQDNp4nnDx4iUIRWMhRQd4wdkOSGG_E8?usp=sharing';

        if ($doc !== null) {
            $downloadModel->incrementHits((int) $doc['id_download'], (int) ($doc['hits'] ?? 0));
            $targetUrl   = ! empty($doc['nama_file']) ? (string) $doc['nama_file'] : $targetUrl;
            $updatedHits = (int) ($doc['hits'] ?? 0) + 1;
        } else {
            $updatedHits = 1;
        }

        if ($this->request->isAJAX() || $this->request->getGet('ajax') === '1') {
            return $this->response->setJSON([
                'status' => 'success',
                'hits'   => $updatedHits,
                'url'    => $targetUrl,
            ]);
        }

        return redirect()->to($targetUrl);
    }

    public function unduhDispensasiNikah()
    {
        $downloadModel = new \App\Models\DownloadModel();
        $doc           = $downloadModel->where('id_download', 13)
            ->orWhere('nama_file LIKE', '%1NuRs_ROLcmlVEXdh-aOhEd92DV2eF1Eu%')
            ->orWhere('judul LIKE', '%Dispensasi Nikah%')
            ->first();

        $targetUrl = 'https://drive.google.com/drive/folders/1NuRs_ROLcmlVEXdh-aOhEd92DV2eF1Eu?usp=sharing';

        if ($doc !== null) {
            $downloadModel->incrementHits((int) $doc['id_download'], (int) ($doc['hits'] ?? 0));
            $targetUrl   = ! empty($doc['nama_file']) ? (string) $doc['nama_file'] : $targetUrl;
            $updatedHits = (int) ($doc['hits'] ?? 0) + 1;
        } else {
            $updatedHits = 1;
        }

        if ($this->request->isAJAX() || $this->request->getGet('ajax') === '1') {
            return $this->response->setJSON([
                'status' => 'success',
                'hits'   => $updatedHits,
                'url'    => $targetUrl,
            ]);
        }

        return redirect()->to($targetUrl);
    }
}
