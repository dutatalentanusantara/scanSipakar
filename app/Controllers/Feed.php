<?php

namespace App\Controllers;

use App\Services\RssService;

class Feed extends BasePortalController
{
    public function index()
    {
        (new RssService())->regenerateFeed();

        $xmlPath = portal_fcpath('rss.xml');
        $xml     = is_file($xmlPath) ? (string) @file_get_contents($xmlPath) : '';

        return $this->response
            ->setStatusCode(200)
            ->setHeader('Content-Type', 'application/rss+xml; charset=UTF-8')
            ->setBody($xml);
    }
}
