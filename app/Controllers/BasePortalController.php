<?php

namespace App\Controllers;

use App\Models\LegacyPortalModel;
use App\Services\ImageProcessingService;
use App\Services\PortalCacheService;
use App\Services\PortalContextService;
use App\Services\SecurityAuditService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Shared base controller providing Clean Architecture services, layout rendering,
 * security guardrails, and safe input utilities to all portal controllers.
 */
abstract class BasePortalController extends BaseController
{
    protected LegacyPortalModel $portalModel;
    protected PortalContextService $portalContext;
    protected PortalCacheService $portalCache;
    protected SecurityAuditService $securityAudit;
    protected ImageProcessingService $viewRenderer;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->portalModel   = new LegacyPortalModel();
        $this->portalCache   = new PortalCacheService();
        $this->portalContext = new PortalContextService($this->portalCache, $this->portalModel);
        $this->securityAudit = new SecurityAuditService();
        $this->viewRenderer  = new ImageProcessingService();
    }

    /**
     * Render inner view wrapped inside active portal template layout.
     *
     * @param array<string, mixed> $data
     */
    protected function renderPortal(string $contentView, array $data = [], ?string $templateFolder = null): string
    {
        $folder = $templateFolder ?: $this->portalContext->getActiveTemplate();
        $folder = preg_replace('/[^a-zA-Z0-9\-_]/', '', $folder) ?: 'diskominfo';

        return $this->viewRenderer->renderTemplate($folder . '/template', $folder . '/' . ltrim($contentView, '/'), $data);
    }

    /**
     * Render admin template layout wrapping module view.
     *
     * @param array<string, mixed> $data
     */
    protected function renderAdmin(string $viewPath, array $data = []): string
    {
        return $this->viewRenderer->renderTemplate('administrator/template', $viewPath, $data);
    }

    protected function safeText(?string $value): string
    {
        return htmlspecialchars(trim((string) $value), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Sanitize plain text input without HTML-entity encoding so DB storage, search, and CSV exports stay uncorrupted.
     */
    protected function cleanText(?string $value): string
    {
        $stripped = strip_tags((string) $value);

        return trim((string) preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $stripped));
    }

    protected function validatePaginationOffset($rawOffset, int $maxOffset = 50000): int
    {
        if ($rawOffset === null || $rawOffset === '' || ! is_numeric($rawOffset)) {
            return 0;
        }
        $val = (int) $rawOffset;

        return ($val >= 0 && $val <= $maxOffset) ? $val : 0;
    }
}
