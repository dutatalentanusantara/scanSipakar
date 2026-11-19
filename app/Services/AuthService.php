<?php

namespace App\Services;

use App\Models\UserModel;
use Config\Database;
use Config\Services;

/**
 * Centralized authentication, live session validation, and single-use password reset service.
 */
class AuthService
{
    private const RESET_CACHE_PREFIX = 'pwd_reset_';
    private const RESET_TTL_SECONDS  = 1800;

    /**
     * Request-local memoization cache for live session validation.
     *
     * @var array<string, array<string, mixed>|null>
     */
    private static array $liveUserCache = [];

    private UserModel $users;

    public function __construct(?UserModel $users = null)
    {
        $this->users = $users ?? new UserModel();
    }

    public static function resetLiveSessionCache(): void
    {
        self::$liveUserCache = [];
    }

    /**
     * Authenticate credentials and initialize an isolated admin/operator session.
     *
     * @return array<string, mixed>|null
     */
    public function attemptLogin(string $username, string $plainPassword): ?array
    {
        $user = $this->users->authenticate($username, $plainPassword);
        if ($user === null) {
            return null;
        }

        $this->establishSession($user);

        return $user;
    }

    /**
     * Regenerate session ID completely and store normalized user session state.
     *
     * @param array<string, mixed> $user
     */
    public function establishSession(array $user): void
    {
        $session = session();
        $session->regenerate(true);
        $session->set('upload_image_file_manager', true);
        $session->set([
            'username'   => (string) ($user['username'] ?? ''),
            'level'      => (string) ($user['level'] ?? ''),
            'id_session' => (string) ($user['id_session'] ?? ''),
        ]);

        self::$liveUserCache[(string) ($user['username'] ?? '')] = $user;
    }

    /**
     * Verify current session against live database user status (blokir, existence, role, session token).
     *
     * @return array<string, mixed>|null
     */
    public function validateLiveSession(): ?array
    {
        $session  = session();
        $username = trim((string) ($session->get('username') ?? ''));
        $level    = trim((string) ($session->get('level') ?? ''));

        if ($username === '' || $level === '') {
            return null;
        }

        if (! array_key_exists($username, self::$liveUserCache)) {
            self::$liveUserCache[$username] = $this->users->findActiveByUsername($username);
        }

        $user = self::$liveUserCache[$username];
        if ($user === null || ($user['blokir'] ?? 'Y') !== 'N') {
            $this->clearAuthSession();

            return null;
        }

        $sessionToken = (string) ($session->get('id_session') ?? '');
        $dbToken      = (string) ($user['id_session'] ?? '');
        if ($sessionToken !== '' && $dbToken !== '' && ! hash_equals($dbToken, $sessionToken)) {
            $this->clearAuthSession();

            return null;
        }

        $dbLevel = (string) ($user['level'] ?? '');
        if ($dbLevel !== '' && $dbLevel !== $level) {
            $session->set('level', $dbLevel);
        }

        return $user;
    }

    /**
     * Issue a short-lived, cryptographically random single-use password reset token.
     *
     * @return array{user: array<string, mixed>, token: string}|null
     */
    public function createPasswordResetToken(string $email): ?array
    {
        $cleanEmail = trim($email);
        if ($cleanEmail === '' || ! filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        $user = $this->users
            ->where('email', $cleanEmail)
            ->where('blokir', 'N')
            ->first();

        if (! is_array($user) || empty($user['username'])) {
            return null;
        }

        $rawToken  = bin2hex(random_bytes(32));
        $cacheKey  = self::RESET_CACHE_PREFIX . hash('sha256', $rawToken);
        Services::cache()->save($cacheKey, [
            'username' => (string) $user['username'],
            'issued'   => time(),
        ], self::RESET_TTL_SECONDS);

        return [
            'user'  => $user,
            'token' => $rawToken,
        ];
    }

    public function isValidPasswordResetToken(string $rawToken): bool
    {
        $token = trim($rawToken);
        if (! preg_match('/^[a-f0-9]{64}$/i', $token)) {
            return false;
        }

        $payload = Services::cache()->get(self::RESET_CACHE_PREFIX . hash('sha256', $token));

        return is_array($payload) && ! empty($payload['username']);
    }

    /**
     * Consume a valid reset token once, update to a modern password hash, and rotate id_session.
     */
    public function consumePasswordResetToken(string $rawToken, string $newPlainPassword): bool
    {
        $token = trim($rawToken);
        if (! preg_match('/^[a-f0-9]{64}$/i', $token)) {
            return false;
        }

        $cache    = Services::cache();
        $cacheKey = self::RESET_CACHE_PREFIX . hash('sha256', $token);
        $payload  = $cache->get($cacheKey);
        if (! is_array($payload) || empty($payload['username'])) {
            return false;
        }

        // Delete immediately so tokens cannot be replayed.
        $cache->delete($cacheKey);

        $username = (string) $payload['username'];
        $user     = $this->users->findActiveByUsername($username);
        if ($user === null) {
            return false;
        }

        $oldSession = (string) ($user['id_session'] ?? '');
        $newSession = UserModel::generateSessionToken();
        $newHash    = UserModel::hashModernPassword($newPlainPassword);

        $db = Database::connect('default');
        $db->transStart();
        $db->table('users')
            ->where('username', $username)
            ->update([
                'password'   => $newHash,
                'id_session' => $newSession,
            ]);

        if ($oldSession !== '') {
            $db->table('users_modul')
                ->where('id_session', $oldSession)
                ->update(['id_session' => $newSession]);
        }
        $db->transComplete();

        unset(self::$liveUserCache[$username]);

        return $db->transStatus();
    }

    public function clearAuthSession(): void
    {
        $session  = session();
        $username = (string) ($session->get('username') ?? '');
        if ($username !== '') {
            unset(self::$liveUserCache[$username]);
        }

        $session->remove(['username', 'level', 'id_session', 'upload_image_file_manager']);
    }
}

