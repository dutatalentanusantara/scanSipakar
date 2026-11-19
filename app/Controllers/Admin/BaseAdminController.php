<?php

namespace App\Controllers\Admin;

use App\Controllers\BasePortalController;
use App\Models\UsersModulModel;
use App\Services\AuthService;
use App\Services\UploadService;
use CodeIgniter\HTTP\RedirectResponse;

/**
 * Shared foundation for modular Administrator CMS controllers.
 * Enforces session authentication, module RBAC, ownership scoping,
 * secure uploads, and cache invalidation.
 */
abstract class BaseAdminController extends BasePortalController
{
    protected function isLoggedIn(): bool
    {
        return (new AuthService())->validateLiveSession() !== null;
    }

    protected function isAdmin(): bool
    {
        return (string) (session()->get('level') ?? '') === 'admin';
    }

    protected function currentUsername(): string
    {
        return (string) (session()->get('username') ?? '');
    }

    /**
     * Strongly-typed redirect helper guaranteeing RedirectResponse object.
     */
    protected function redirectTo(string $uri): RedirectResponse
    {
        return redirect()->to($uri);
    }

    protected function ensureLoggedIn(): ?RedirectResponse
    {
        if (! $this->isLoggedIn()) {
            return $this->redirectTo(base_url('administrator'));
        }

        return null;
    }

    protected function ensureAdmin(): ?RedirectResponse
    {
        if ($redirect = $this->ensureLoggedIn()) {
            return $redirect;
        }
        if (! $this->isAdmin()) {
            return $this->redirectTo(base_url('administrator/home'));
        }

        return null;
    }

    protected function ensureModule(string $moduleLink): ?RedirectResponse
    {
        if ($redirect = $this->ensureLoggedIn()) {
            return $redirect;
        }
        if ($this->isAdmin()) {
            return null;
        }

        $idSession = (string) (session()->get('id_session') ?? '');
        if (! (new UsersModulModel())->hasAccess($idSession, $moduleLink)) {
            return $this->redirectTo(base_url('administrator/home'));
        }

        return null;
    }

    /**
     * Build ownership-aware WHERE filter (admins access all records; contributors only their own).
     *
     * @return array<string, mixed>
     */
    protected function ownerWhere(string $idColumn, $idValue, string $userColumn = 'username'): array
    {
        $where = [$idColumn => $idValue];
        if (! $this->isAdmin()) {
            $where[$userColumn] = $this->currentUsername();
        }

        return $where;
    }

    /**
     * Fetch ordered records scoped by admin vs author ownership.
     *
     * @return list<array<string, mixed>>
     */
    protected function scopedList(string $table, string $orderColumn, string $direction = 'DESC', string $userColumn = 'username'): array
    {
        if ($this->isAdmin()) {
            return $this->portalModel->viewOrdering($table, $orderColumn, $direction);
        }

        return $this->portalModel->viewWhereOrdering($table, [$userColumn => $this->currentUsername()], $orderColumn, $direction);
    }

    protected function isPostSubmit(): bool
    {
        return $this->request->is('post') && ($this->request->getPost('submit') !== null || $this->request->getPost('a') !== null || $this->request->getPost('id') !== null);
    }

    /**
     * @param list<string> $allowedExt
     */
    protected function uploadOptional(
        string $fieldName,
        string $targetDir,
        array $allowedExt = ['gif', 'jpg', 'jpeg', 'png'],
        int $maxKb = 3000
    ): ?string {
        $result = (new UploadService())->upload(
            $this->request->getFile($fieldName),
            $targetDir,
            $allowedExt,
            $maxKb,
            true
        );

        return $result['ok'] ? $result['filename'] : null;
    }

    protected function invalidateAll(): void
    {
        $this->portalCache->invalidatePortalCaches();
    }

    protected function resolveId($paramId = null): int
    {
        if ($paramId !== null && is_numeric($paramId)) {
            return (int) $paramId;
        }

        return (int) uri_segment(3, '0');
    }
}
