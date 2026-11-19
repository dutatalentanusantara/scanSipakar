<?php

namespace App\Models;

class UserModel extends BaseAppModel
{
    protected $table            = 'users';
    protected $primaryKey       = 'username';
    protected $useAutoIncrement = false;
    protected $allowedFields    = [
        'username',
        'password',
        'nama_lengkap',
        'email',
        'no_telp',
        'foto',
        'level',
        'blokir',
        'id_session',
    ];

    public static function hashLegacyPassword(string $plain): string
    {
        return hash('sha512', md5($plain));
    }

    public static function hashModernPassword(string $plain): string
    {
        return password_hash($plain, PASSWORD_DEFAULT);
    }

    public static function generateSessionToken(): string
    {
        return bin2hex(random_bytes(24));
    }

    /**
     * Fetch active (non-blocked) user record by username.
     *
     * @return array<string, mixed>|null
     */
    public function findActiveByUsername(string $username): ?array
    {
        $user = $this->where('username', $username)
            ->where('blokir', 'N')
            ->first();

        return is_array($user) ? $user : null;
    }

    /**
     * Authenticate active (non-blocked) user supporting legacy SHA512(MD5) and modern password_hash().
     * Transparently upgrades verified legacy/outdated hashes to PASSWORD_DEFAULT.
     *
     * @return array<string, mixed>|null
     */
    public function authenticate(string $username, string $plainPassword): ?array
    {
        $user = $this->findActiveByUsername($username);

        if ($user === null || empty($user['password'])) {
            return null;
        }

        $stored        = (string) $user['password'];
        $isModernHash  = str_starts_with($stored, '$2') || str_starts_with($stored, '$argon2');
        $authenticated = $isModernHash
            ? password_verify($plainPassword, $stored)
            : hash_equals($stored, self::hashLegacyPassword($plainPassword));

        if (! $authenticated) {
            return null;
        }

        $updates = [];
        if (! $isModernHash || password_needs_rehash($stored, PASSWORD_DEFAULT)) {
            $newHash             = self::hashModernPassword($plainPassword);
            $updates['password'] = $newHash;
            $user['password']    = $newHash;
        }

        if (empty($user['id_session'])) {
            $newSession            = self::generateSessionToken();
            $updates['id_session'] = $newSession;
            $user['id_session']    = $newSession;
        }

        if ($updates !== []) {
            $this->db->table($this->table)
                ->where($this->primaryKey, (string) $user['username'])
                ->update($updates);
        }

        return $user;
    }
}

