<?php

namespace App\Services;

/**
 * Pure GD CAPTCHA generator compatible with legacy CI3 captcha params and session checks.
 */
class CaptchaService
{
    /**
     * @param array<string, mixed> $params
     * @return array{word: string, time: int, filename: string, image: string}
     */
    public function create(array $params = []): array
    {
        $width      = max(80, (int) ($params['img_width'] ?? 150));
        $height     = max(24, (int) ($params['img_height'] ?? 29));
        $wordLen    = max(4, min(8, (int) ($params['word_length'] ?? 5)));
        $expiration = max(60, (int) ($params['expiration'] ?? 7200));
        $pool       = (string) ($params['pool'] ?? '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ');

        $imgPath = rtrim((string) ($params['img_path'] ?? portal_fcpath('captcha/')), '/\\') . '/';
        $imgUrl  = rtrim((string) ($params['img_url'] ?? base_url('captcha/')), '/') . '/';

        if (! is_dir($imgPath)) {
            @mkdir($imgPath, 0755, true);
        }

        // Prune expired captcha images
        $now = time();
        foreach (glob($imgPath . '*.{png,jpg}', GLOB_BRACE) ?: [] as $existingFile) {
            if (($now - (int) @filemtime($existingFile)) > $expiration) {
                @unlink($existingFile);
            }
        }

        $word = '';
        $max  = strlen($pool) - 1;
        for ($i = 0; $i < $wordLen; $i++) {
            $word .= $pool[random_int(0, $max)];
        }

        $filename = $now . '_' . bin2hex(random_bytes(4)) . '.png';
        $fullPath = $imgPath . $filename;

        if (function_exists('imagecreatetruecolor')) {
            $im     = imagecreatetruecolor($width, $height);
            $bg     = imagecolorallocate($im, 255, 255, 255);
            $border = imagecolorallocate($im, 203, 213, 225);
            $grid   = imagecolorallocate($im, 226, 232, 240);
            $text   = imagecolorallocate($im, 15, 43, 92);

            imagefilledrectangle($im, 0, 0, $width, $height, $bg);
            imagerectangle($im, 0, 0, $width - 1, $height - 1, $border);

            for ($i = 0; $i < 6; $i++) {
                imageline($im, random_int(0, $width), random_int(0, $height), random_int(0, $width), random_int(0, $height), $grid);
            }

            $font    = 5;
            $charW   = imagefontwidth($font);
            $charH   = imagefontheight($font);
            $stepX   = (int) floor(($width - 16) / $wordLen);
            $startX  = 8;
            $baseY   = (int) max(2, floor(($height - $charH) / 2));

            for ($i = 0; $i < $wordLen; $i++) {
                $cx = $startX + ($i * max($charW + 2, $stepX));
                $cy = max(1, min($height - $charH - 1, $baseY + random_int(-3, 3)));
                imagestring($im, $font, $cx, $cy, $word[$i], $text);
            }

            imagepng($im, $fullPath);
            imagedestroy($im);
        } else {
            return [
                'word'     => $word,
                'time'     => $now,
                'filename' => '',
                'image'    => '<span class="badge bg-secondary" style="letter-spacing:3px;font-size:16px;padding:6px 12px;">' . esc($word) . '</span>',
            ];
        }

        $imgTag = '<img src="' . htmlspecialchars($imgUrl . $filename, ENT_QUOTES, 'UTF-8')
            . '" width="' . $width . '" height="' . $height . '" style="border:0;" alt="CAPTCHA" />';

        return [
            'word'     => $word,
            'time'     => $now,
            'filename' => $filename,
            'image'    => $imgTag,
        ];
    }

    public function verify(?string $submitted, string $sessionKey = 'mycaptcha', bool $clearOnMatch = false): bool
    {
        $expected = (string) (session()->get($sessionKey) ?? '');
        $input    = trim((string) $submitted);
        if ($expected === '' || $input === '' || $expected === 'fallback') {
            return false;
        }

        $ok = hash_equals(strtolower($expected), strtolower($input));
        if ($ok && $clearOnMatch) {
            session()->remove($sessionKey);
        }

        return $ok;
    }
}
