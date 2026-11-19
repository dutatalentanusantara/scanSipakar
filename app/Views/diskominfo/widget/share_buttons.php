<?php
$default_uri = uri_string();
$fallback_url = $default_uri !== '' ? base_url($default_uri) : base_url();

$resolved_url = isset($share_url) && trim((string) $share_url) !== ''
    ? trim((string) $share_url)
    : $fallback_url;

$resolved_title = isset($share_title) && trim((string) $share_title) !== ''
    ? trim(strip_tags((string) $share_title))
    : (isset($title) && trim((string) $title) !== '' ? trim(strip_tags((string) $title)) : 'Kecamatan Siantar Barat');

$wa_payload = $resolved_title !== '' ? ($resolved_title . "\n" . $resolved_url) : $resolved_url;

$esc_url = htmlspecialchars($resolved_url, ENT_QUOTES, 'UTF-8');
$esc_title = htmlspecialchars($resolved_title, ENT_QUOTES, 'UTF-8');

$fb_href = 'https://www.facebook.com/sharer/sharer.php?u=' . rawurlencode($resolved_url);
$tw_href = 'https://twitter.com/intent/tweet?url=' . rawurlencode($resolved_url) . '&text=' . rawurlencode($resolved_title);
$wa_href = 'https://wa.me/?text=' . rawurlencode($wa_payload);
$tg_href = 'https://t.me/share/url?url=' . rawurlencode($resolved_url) . '&text=' . rawurlencode($resolved_title);
$show_label = !empty($share_show_label);
?>
<div class="xsosmedlist global-share-bar" data-share-url="<?php echo $esc_url; ?>" data-share-title="<?php echo $esc_title; ?>">
  <?php if ($show_label): ?>
    <span class="global-share-bar__label">Bagikan:</span>
  <?php endif; ?>
  <ul>
    <li>
      <a href="<?php echo htmlspecialchars($fb_href, ENT_QUOTES, 'UTF-8'); ?>"
         class="xsm-fb"
         target="_blank"
         rel="noopener noreferrer"
         data-share-network="facebook"
         data-url="<?php echo $esc_url; ?>"
         data-title="<?php echo $esc_title; ?>"
         title="Bagikan ke Facebook"
         aria-label="Bagikan ke Facebook">
        <i class="fab fa-facebook-f" aria-hidden="true"></i>
      </a>
    </li>
    <li>
      <a href="<?php echo htmlspecialchars($tw_href, ENT_QUOTES, 'UTF-8'); ?>"
         class="xsm-tw"
         target="_blank"
         rel="noopener noreferrer"
         data-share-network="twitter"
         data-url="<?php echo $esc_url; ?>"
         data-title="<?php echo $esc_title; ?>"
         title="Bagikan ke X / Twitter"
         aria-label="Bagikan ke X / Twitter">
        <i class="fab fa-twitter" aria-hidden="true"></i>
      </a>
    </li>
    <li>
      <a href="<?php echo htmlspecialchars($wa_href, ENT_QUOTES, 'UTF-8'); ?>"
         class="xsm-ig xsm-wa"
         target="_blank"
         rel="noopener noreferrer"
         data-share-network="whatsapp"
         data-url="<?php echo $esc_url; ?>"
         data-title="<?php echo $esc_title; ?>"
         title="Bagikan ke WhatsApp"
         aria-label="Bagikan ke WhatsApp">
        <i class="fab fa-whatsapp" aria-hidden="true"></i>
      </a>
    </li>
    <li>
      <a href="<?php echo htmlspecialchars($tg_href, ENT_QUOTES, 'UTF-8'); ?>"
         class="xsm-tg"
         target="_blank"
         rel="noopener noreferrer"
         data-share-network="telegram"
         data-url="<?php echo $esc_url; ?>"
         data-title="<?php echo $esc_title; ?>"
         title="Bagikan ke Telegram"
         aria-label="Bagikan ke Telegram">
        <i class="fab fa-telegram-plane" aria-hidden="true"></i>
      </a>
    </li>
    <li>
      <button type="button"
              class="xsm-copy"
              data-share-copy="1"
              data-url="<?php echo $esc_url; ?>"
              data-title="<?php echo $esc_title; ?>"
              title="Salin tautan"
              aria-label="Salin tautan">
        <i class="fas fa-link" aria-hidden="true"></i>
      </button>
    </li>
  </ul>
</div>
