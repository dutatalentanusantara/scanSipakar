<?php

namespace App\Models;

class VideoModel extends BaseAppModel
{
    protected $table          = 'video';
    protected $primaryKey     = 'id_video';
    protected $useAutoIncrement = true;
    protected $allowedFields  = [
        'id_playlist',
        'username',
        'jdl_video',
        'video_seo',
        'keterangan',
        'gbr_video',
        'video',
        'youtube',
        'dilihat',
        'hari',
        'tanggal',
        'jam',
        'tagvid',
    ];

    /**
     * @return array<string, mixed>|null
     */
    public function findBySlug(string $slug): ?array
    {
        return $this->db->table('video')
            ->select('video.*, users.nama_lengkap, playlist.jdl_playlist, playlist.playlist_seo')
            ->join('users', 'video.username = users.username', 'left')
            ->join('playlist', 'video.id_playlist = playlist.id_playlist', 'left')
            ->where('video.video_seo', $slug)
            ->orderBy('video.id_video', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();
    }
}

