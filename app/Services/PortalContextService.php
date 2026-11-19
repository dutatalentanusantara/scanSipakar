<?php

namespace App\Services;

use App\Models\BackgroundModel;
use App\Models\IdentitasModel;
use App\Models\LegacyPortalModel;
use App\Models\LogoModel;
use App\Models\MenuModel;
use App\Models\ModAlamatModel;
use App\Models\TemplatesModel;

/**
 * Provides cached global portal configuration, theme resolution, and menu trees.
 */
class PortalContextService
{
    private PortalCacheService $cache;
    private LegacyPortalModel $portal;

    /** @var array<string, mixed>|null */
    private ?array $identitasMemo = null;

    private ?string $templateMemo = null;

    public function __construct(?PortalCacheService $cache = null, ?LegacyPortalModel $portal = null)
    {
        $this->cache  = $cache ?? new PortalCacheService();
        $this->portal = $portal ?? new LegacyPortalModel();
    }

    public function getActiveTemplate(): string
    {
        if ($this->templateMemo !== null) {
            return $this->templateMemo;
        }

        $folder = $this->cache->remember('portal_active_template', 600, static function (): string {
            $model = new TemplatesModel();
            $row   = $model->where('aktif', 'Y')->first();
            $raw   = ! empty($row['folder']) ? (string) $row['folder'] : 'diskominfo';
            $clean = preg_replace('/[^a-zA-Z0-9_-]/', '', $raw) ?: 'diskominfo';
            if ($clean === 'phpmu-standar' || ! is_dir(APPPATH . 'Views/' . $clean)) {
                return is_dir(APPPATH . 'Views/diskominfo') ? 'diskominfo' : $clean;
            }

            return $clean;
        });

        $this->templateMemo = $folder ?: 'diskominfo';

        return $this->templateMemo;
    }

    /**
     * @return array<string, mixed>
     */
    public function getIdentitas(): array
    {
        if ($this->identitasMemo !== null) {
            return $this->identitasMemo;
        }

        $this->identitasMemo = $this->cache->remember('portal_identitas', 300, static function (): array {
            $model = new IdentitasModel();

            return $model->getCurrent();
        });

        return $this->identitasMemo;
    }

    public function getBackground(): string
    {
        return (string) $this->cache->remember('portal_background', 600, static function (): string {
            $row = (new BackgroundModel())->orderBy('id_background', 'DESC')->first();

            return ! empty($row['gambar']) ? (string) $row['gambar'] : 'default';
        });
    }

    public function getLogo(): string
    {
        return (string) $this->cache->remember('portal_logo', 600, static function (): string {
            $row = (new LogoModel())->orderBy('id_logo', 'DESC')->first();

            return ! empty($row['gambar']) ? (string) $row['gambar'] : 'logo.png';
        });
    }

    public function getAlamat(): string
    {
        return (string) $this->cache->remember('portal_alamat', 600, static function (): string {
            $row = (new ModAlamatModel())->orderBy('id_alamat', 'DESC')->first();

            return ! empty($row['alamat']) ? (string) $row['alamat'] : '';
        });
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function getTopMenus(): array
    {
        return $this->cache->remember('portal_top_menu', 300, static function (): array {
            return (new MenuModel())->getTopMenus();
        });
    }

    /**
     * Returns bottom navigation parents each enriched with 'children' list.
     *
     * @return list<array<string, mixed>>
     */
    public function getBottomMenuTree(): array
    {
        return $this->cache->remember('portal_bottom_menu_tree', 300, static function (): array {
            $menuModel = new MenuModel();
            $all       = $menuModel->where('position', 'Bottom')->where('aktif', 'Ya')->orderBy('urutan', 'ASC')->findAll();
            $byParent  = [];
            foreach ($all as $item) {
                $pid              = (int) ($item['id_parent'] ?? 0);
                $byParent[$pid][] = $item;
            }

            $build = static function (int $parentId) use (&$build, $byParent): array {
                $nodes = [];
                foreach ($byParent[$parentId] ?? [] as $node) {
                    $id               = (int) $node['id_menu'];
                    $node['children'] = $build($id);
                    $nodes[]          = $node;
                }

                return $nodes;
            };

            return $build(0);
        });
    }

    public function portal(): LegacyPortalModel
    {
        return $this->portal;
    }
}

