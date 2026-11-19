<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class UserEntity extends Entity
{
    /**
     * Never expose password hash or session token in serialized JSON.
     *
     * @return array<string, mixed>
     */
    public function toSafeArray(): array
    {
        return [
            'username'     => (string) ($this->attributes['username'] ?? ''),
            'nama_lengkap' => (string) ($this->attributes['nama_lengkap'] ?? ''),
            'email'        => (string) ($this->attributes['email'] ?? ''),
            'no_telp'      => (string) ($this->attributes['no_telp'] ?? ''),
            'foto'         => (string) ($this->attributes['foto'] ?? ''),
            'level'        => (string) ($this->attributes['level'] ?? ''),
            'blokir'       => (string) ($this->attributes['blokir'] ?? 'N'),
        ];
    }
}

