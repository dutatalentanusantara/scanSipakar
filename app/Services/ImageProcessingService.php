<?php

namespace App\Services;

/**
 * Portal template rendering helper replacement for CI3 Template library and GD image helper.
 */
class ImageProcessingService
{
    /**
     * Render a layout template wrapping an inner content view (compatible with CI3 Template::load).
     *
     * @param array<string, mixed> $viewData
     */
    public function renderTemplate(string $templateView, string $contentView, array $viewData = []): string
    {
        $inner = view($contentView, $viewData);
        $viewData['contents'] = $inner;

        return view($templateView, $viewData);
    }

    public function formatSize(string $path): string
    {
        $bytes = is_file($path) ? (int) filesize($path) : 0;
        if ($bytes <= 0) {
            return '0 B';
        }
        $units = ['B', 'KB', 'MB', 'GB'];
        $pow   = (int) floor(log((float) $bytes, 1024));
        $pow   = min($pow, count($units) - 1);

        return sprintf('%d %s', (int) round($bytes / (1024 ** $pow)), $units[$pow]);
    }
}
