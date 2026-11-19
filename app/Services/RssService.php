<?php

namespace App\Services;

use App\Models\BeritaModel;

/**
 * Generates and sanitizes RSS 2.0 site feeds and reads external official feeds securely.
 */
class RssService
{
    public function regenerateFeed(): bool
    {
        $beritaModel = new BeritaModel();
        $items       = $beritaModel->where('status', 'Y')
            ->orderBy('id_berita', 'DESC')
            ->limit(20)
            ->findAll();

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<rss version="2.0">' . "\n";
        $xml .= "  <channel>\n";
        $xml .= '    <title>' . htmlspecialchars(title(), ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</title>\n";
        $xml .= '    <link>' . htmlspecialchars(base_url(), ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</link>\n";
        $xml .= '    <description>' . htmlspecialchars(description(), ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</description>\n";
        $xml .= "    <language>id-ID</language>\n";

        foreach ($items as $row) {
            $link = base_url($row['judul_seo'] ?? '');
            $desc = strip_tags((string) ($row['isi_berita'] ?? ''));
            if (mb_strlen($desc) > 250) {
                $desc = mb_substr($desc, 0, 250) . '...';
            }
            $xml .= "    <item>\n";
            $xml .= '      <title>' . htmlspecialchars((string) ($row['judul'] ?? ''), ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</title>\n";
            $xml .= '      <link>' . htmlspecialchars($link, ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</link>\n";
            $xml .= '      <guid isPermaLink="true">' . htmlspecialchars($link, ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</guid>\n";
            $xml .= '      <description>' . htmlspecialchars($desc, ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</description>\n";
            $xml .= '      <pubDate>' . date(DATE_RSS, strtotime((string) ($row['tanggal'] ?? 'now') . ' ' . ($row['jam'] ?? '00:00:00'))) . "</pubDate>\n";
            $xml .= "    </item>\n";
        }

        $xml .= "  </channel>\n";
        $xml .= "</rss>\n";

        $target = FCPATH . 'rss.xml';

        return @file_put_contents($target, $xml) !== false;
    }

    /**
     * Parse external RSS safely (LIBXML_NONET disables XXE network fetches).
     *
     * @return list<string>
     */
    public function parseExternalFeed(string $feedUrl): array
    {
        $feedUrl = trim($feedUrl);
        if ($feedUrl === '' || ! preg_match('#^https?://#i', $feedUrl)) {
            return [];
        }

        $prev = libxml_use_internal_errors(true);
        $ctx  = stream_context_create([
            'http' => [
                'method'          => 'GET',
                'timeout'         => 4,
                'follow_location' => 1,
                'max_redirects'   => 3,
                'user_agent'      => 'SiantarBaratPortal/2.0',
            ],
            'ssl' => [
                'verify_peer'      => true,
                'verify_peer_name' => true,
            ],
        ]);

        $rawXml = @file_get_contents($feedUrl, false, $ctx);
        $rss    = ($rawXml !== false && $rawXml !== '')
            ? @simplexml_load_string($rawXml, 'SimpleXMLElement', LIBXML_NONET | LIBXML_NOCDATA)
            : false;

        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        if ($rss === false || ! isset($rss->channel->item)) {
            return [];
        }

        $items = [];
        foreach ($rss->channel->item as $item) {
            $title = htmlspecialchars((string) $item->title, ENT_QUOTES, 'UTF-8');
            $raw   = trim((string) $item->link);
            $link  = preg_match('#^https?://#i', $raw) ? htmlspecialchars($raw, ENT_QUOTES, 'UTF-8') : '#';
            $view  = htmlspecialchars((string) $item->view, ENT_QUOTES, 'UTF-8');
            $desc  = htmlspecialchars(strip_tags((string) $item->description), ENT_QUOTES, 'UTF-8');
            $foto  = htmlspecialchars(trim((string) $item->foto), ENT_QUOTES, 'UTF-8');

            $items[] = '<li style="padding: 0px 0;" class="item">'
                . '<div class="product-img"><img class="img-circle img-thumbnail" style="height:50px; width:50px" src="' . $foto . '" alt=""></div>'
                . '<div class="product-info"><a target="_blank" rel="noopener noreferrer" href="' . $link . '" class="product-title">' . $title
                . '<span class="label label-success pull-right">' . $view . ' Kali</span></a>'
                . '<span class="product-description">' . $desc . '</span></div></li>';
        }

        return $items;
    }

    public function displayWidget(string $feedUrl, int $numRows, string $head): string
    {
        $items = array_slice($this->parseExternalFeed($feedUrl), 0, max(0, $numRows));
        $html  = '<div class="card"><div class="card-header with-border"><h3 class="card-title">'
            . htmlspecialchars($head, ENT_QUOTES, 'UTF-8')
            . '</h3></div><div class="card-body"><ul class="products-list product-list-in-card">';
        $html .= implode('', $items);
        $html .= '</ul></div><div class="card-footer text-center">'
            . '<a class="btn btn-default btn-sm btn-block uppercase" target="_blank" rel="noopener noreferrer" href="https://pematangsiantar.go.id/">View All Topic</a><br>'
            . '</div></div>';

        return $html;
    }
}

