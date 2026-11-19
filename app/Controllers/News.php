<?php

namespace App\Controllers;

use App\Controllers\Concerns\RendersBeritaDetail;

/**
 * Handles root-level news SEO slugs (/{judul_seo}).
 */
class News extends BasePortalController
{
    use RendersBeritaDetail;

    public function index(?string $slug = null)
    {
        if (! $this->securityAudit->checkRateLimit('news_rate', 300, 3600, $this->request)) {
            return $this->response->setStatusCode(429)->setBody('Too many requests');
        }

        return $this->renderBeritaDetail($slug, 'news');
    }
}
