<?php

namespace App\Services;

use CodeIgniter\HTTP\Files\UploadedFile;

/**
 * Unified secure file upload handler replacing duplicated CI3 $this->upload->do_upload blocks.
 */
class UploadService
{
    private const IMAGE_EXTENSIONS = ['gif', 'jpg', 'jpeg', 'png', 'webp'];

    private const FORBIDDEN_EXTENSIONS = [
        'php', 'phtml', 'php3', 'php4', 'php5', 'phar', 'shtml', 'html', 'htm',
        'svg', 'svgz', 'sh', 'bat', 'cmd', 'exe', 'dll', 'js', 'jsp', 'asp',
        'aspx', 'cgi', 'pl', 'py', 'htaccess', 'ini',
    ];

    private const DANGEROUS_MIME_PREFIXES = [
        'text/html',
        'text/x-php',
        'application/x-php',
        'application/x-httpd-php',
        'image/svg',
        'application/javascript',
        'text/javascript',
        'application/x-sh',
        'application/x-msdownload',
    ];

    /**
     * Upload a file safely into a portal subpath relative to portal root (e.g. 'asset/foto_berita/').
     *
     * @param list<string> $allowedExtensions
     * @return array{ok: bool, filename: string, original_name: string, size_kb: int, mime_type: string, error: string}
     */
    public function upload(
        ?UploadedFile $file,
        string $relativeTargetDir,
        array $allowedExtensions = ['gif', 'jpg', 'jpeg', 'png', 'webp'],
        int $maxKb = 5120,
        bool $randomName = true
    ): array {
        $fail = static fn (string $message, string $orig = '', int $size = 0): array => [
            'ok'            => false,
            'filename'      => '',
            'original_name' => $orig,
            'size_kb'       => $size,
            'mime_type'     => '',
            'error'         => $message,
        ];

        $normalizedTarget = str_replace(["\0", '\\'], ['', '/'], trim($relativeTargetDir));
        if ($normalizedTarget === '' || str_contains($normalizedTarget, '..')) {
            return $fail('Direktori target unggahan tidak valid');
        }

        if ($file === null || ! $file->isValid() || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return $fail('No file uploaded');
        }

        if ($file->hasMoved()) {
            return $fail('File already moved');
        }

        $origName = (string) $file->getClientName();
        $sizeKb   = (int) ceil($file->getSize() / 1024);
        if ($sizeKb > $maxKb) {
            return $fail("Ukuran file melebihi batas {$maxKb} KB", $origName, $sizeKb);
        }

        $clientExt = strtolower((string) $file->getClientExtension());
        if ($clientExt === '' || in_array($clientExt, self::FORBIDDEN_EXTENSIONS, true)) {
            return $fail('Format file tidak diizinkan', $origName, $sizeKb);
        }

        $normalizedAllowed = array_values(array_unique(array_map('strtolower', $allowedExtensions)));
        if (in_array('jpg', $normalizedAllowed, true) && ! in_array('jpeg', $normalizedAllowed, true)) {
            $normalizedAllowed[] = 'jpeg';
        } elseif (in_array('jpeg', $normalizedAllowed, true) && ! in_array('jpg', $normalizedAllowed, true)) {
            $normalizedAllowed[] = 'jpg';
        }

        if (! in_array($clientExt, $normalizedAllowed, true)) {
            return $fail('Format file tidak diizinkan', $origName, $sizeKb);
        }

        $guessedExt = strtolower((string) ($file->guessExtension() ?: ''));
        $mimeType   = strtolower((string) ($file->getMimeType() ?: 'application/octet-stream'));

        if ($guessedExt !== '' && in_array($guessedExt, self::FORBIDDEN_EXTENSIONS, true)) {
            return $fail('Konten file terdeteksi berformat terlarang', $origName, $sizeKb);
        }

        foreach (self::DANGEROUS_MIME_PREFIXES as $badMime) {
            if (str_starts_with($mimeType, $badMime)) {
                return $fail('Tipe MIME file tidak diizinkan', $origName, $sizeKb);
            }
        }

        if (! $this->isMimeAndExtensionConsistent($clientExt, $guessedExt, $mimeType, $normalizedAllowed, (string) $file->getTempName())) {
            return $fail('Isi file tidak sesuai dengan ekstensi yang diizinkan', $origName, $sizeKb);
        }

        $targetDir = rtrim(portal_fcpath($normalizedTarget), '/\\') . '/';
        if (! is_dir($targetDir)) {
            @mkdir($targetDir, 0755, true);
        }

        $newName = $randomName ? $file->getRandomName() : $this->sanitizeFilename($origName);

        if (is_file($targetDir . $newName)) {
            $base    = pathinfo($newName, PATHINFO_FILENAME);
            $fileExt = pathinfo($newName, PATHINFO_EXTENSION);
            $newName = $base . '_' . substr(bin2hex(random_bytes(3)), 0, 6) . ($fileExt !== '' ? '.' . $fileExt : '');
        }

        $file->move($targetDir, $newName);

        return [
            'ok'            => true,
            'filename'      => $newName,
            'original_name' => $origName,
            'size_kb'       => $sizeKb,
            'mime_type'     => $mimeType,
            'error'         => '',
        ];
    }

    /**
     * @param list<string> $allowedExtensions
     */
    private function isMimeAndExtensionConsistent(
        string $clientExt,
        string $guessedExt,
        string $mimeType,
        array $allowedExtensions,
        string $tempPath
    ): bool {
        if (in_array($clientExt, self::IMAGE_EXTENSIONS, true)) {
            // First inspect binary image headers with getimagesize if file is present
            if ($tempPath !== '' && is_file($tempPath)) {
                $info = @getimagesize($tempPath);
                if ($info !== false && ! empty($info[0]) && ! empty($info[1])) {
                    $imgType = $info[2] ?? 0;
                    $validImageTypes = [
                        IMAGETYPE_GIF  => ['gif'],
                        IMAGETYPE_JPEG => ['jpg', 'jpeg'],
                        IMAGETYPE_PNG  => ['png'],
                    ];
                    if (defined('IMAGETYPE_WEBP')) {
                        $validImageTypes[IMAGETYPE_WEBP] = ['webp'];
                    }
                    if (defined('IMAGETYPE_ICO')) {
                        $validImageTypes[IMAGETYPE_ICO] = ['ico'];
                    }

                    if (isset($validImageTypes[$imgType])) {
                        $detectedExts = $validImageTypes[$imgType];
                        $hasMatch = ! empty(array_intersect($detectedExts, $allowedExtensions));
                        if ($hasMatch) {
                            return true;
                        }
                    }
                }
            }

            // Fallback: check guessed extension consistency
            $canonicalGuessed = $guessedExt === 'jpeg' ? 'jpg' : $guessedExt;
            $canonicalClient  = $clientExt === 'jpeg' ? 'jpg' : $clientExt;
            if ($canonicalGuessed !== '' && ($canonicalGuessed === $canonicalClient || in_array($canonicalGuessed, $allowedExtensions, true))) {
                return true;
            }

            return false;
        }

        if ($clientExt === 'pdf') {
            return $guessedExt === 'pdf' && str_contains($mimeType, 'pdf');
        }

        if ($clientExt === 'zip') {
            return $guessedExt === 'zip';
        }

        // OOXML office formats (.docx, .xlsx, .pptx) are ZIP containers at the magic-byte level.
        if (in_array($clientExt, ['docx', 'xlsx', 'pptx'], true)) {
            return in_array($guessedExt, [$clientExt, 'zip'], true);
        }

        if ($guessedExt === '') {
            return true;
        }

        return in_array($guessedExt, $allowedExtensions, true) || str_starts_with($mimeType, 'application/') || str_starts_with($mimeType, 'text/plain');
    }

    private function sanitizeFilename(string $filename): string
    {
        $ext  = pathinfo($filename, PATHINFO_EXTENSION);
        $base = pathinfo($filename, PATHINFO_FILENAME);
        $base = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $base) ?: 'file';

        return substr($base, 0, 120) . ($ext !== '' ? '.' . preg_replace('/[^a-zA-Z0-9]/', '', $ext) : '');
    }
}
