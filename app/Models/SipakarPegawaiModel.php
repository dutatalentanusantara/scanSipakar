<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model for SIPAKAR employee (ASN) authentication data.
 * Table: sipakar_pegawai
 * Primary key: nip (VARCHAR 25)
 */
class SipakarPegawaiModel extends Model
{
    protected $table            = 'sipakar_pegawai';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'nip', 'nama', 'jabatan', 'pangkat_golongan', 'unit_kerja',
        'jenis_asn', 'password', 'aktif', 'login_attempts', 'locked_until',
        'last_login', 'last_ip', 'must_change_password',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nip'  => 'required|min_length[15]|max_length[25]',
        'nama' => 'required|max_length[255]',
    ];

    // =========================================================================
    // QUERY METHODS
    // =========================================================================

    /**
     * Find employee by NIP.
     */
    public function findByNip(string $nip): ?array
    {
        return $this->where('nip', $nip)->first();
    }

    /**
     * Verify login credential.
     * Returns employee array on success, null on failure.
     * Implements brute-force protection.
     */
    public function verifyLogin(string $nip, string $password): ?array
    {
        $pegawai = $this->findByNip($nip);

        if ($pegawai === null) {
            return null;
        }

        // Account disabled
        if ((int) $pegawai['aktif'] !== 1) {
            return null;
        }

        // Check lockout
        if (! empty($pegawai['locked_until'])) {
            $lockedUntil = strtotime($pegawai['locked_until']);
            if ($lockedUntil !== false && $lockedUntil > time()) {
                return null; // Still locked
            }
            // Unlock expired — reset
            $this->resetAttempts($nip);
        }

        // Verify password
        if (! password_verify($password, $pegawai['password'])) {
            $this->incrementAttempts($nip, (int) $pegawai['login_attempts']);
            return null;
        }

        // Success — reset attempts
        $this->resetAttempts($nip);

        return $pegawai;
    }

    /**
     * Update last login metadata.
     */
    public function recordLogin(int $id, string $ip): void
    {
        $this->update($id, [
            'last_login'     => date('Y-m-d H:i:s'),
            'last_ip'        => $ip,
            'login_attempts' => 0,
            'locked_until'   => null,
        ]);
    }

    /**
     * Increment failed login attempts; lock after 5 failures.
     */
    private function incrementAttempts(string $nip, int $current): void
    {
        $newCount  = $current + 1;
        $lockUntil = null;

        if ($newCount >= 5) {
            // Lock for 15 minutes
            $lockUntil = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        }

        $this->where('nip', $nip)->set([
            'login_attempts' => $newCount,
            'locked_until'   => $lockUntil,
        ])->update();
    }

    /**
     * Reset login attempts and clear lockout.
     */
    public function resetAttempts(string $nip): void
    {
        $this->where('nip', $nip)->set([
            'login_attempts' => 0,
            'locked_until'   => null,
        ])->update();
    }

    /**
     * Update password with bcrypt hash.
     */
    public function updatePassword(int $id, string $newPassword): bool
    {
        return $this->update($id, [
            'password'             => password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]),
            'must_change_password' => 0,
        ]);
    }

    /**
     * Get lockout remaining seconds for a NIP.
     */
    public function getLockoutRemaining(string $nip): int
    {
        $pegawai = $this->findByNip($nip);
        if ($pegawai === null || empty($pegawai['locked_until'])) {
            return 0;
        }
        $remaining = strtotime($pegawai['locked_until']) - time();
        return max(0, (int) $remaining);
    }

    /**
     * Check if NIP is currently locked.
     */
    public function isLocked(string $nip): bool
    {
        return $this->getLockoutRemaining($nip) > 0;
    }
}
