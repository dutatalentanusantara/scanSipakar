(function (window, document) {
  'use strict';

  function getElAttr(el, attr) {
    return el ? (el.getAttribute(attr) || '') : '';
  }

  function resolveShareData(explicitUrl, explicitTitle) {
    var legacyNode = document.getElementById('share-url');
    var canonicalNode = document.querySelector('link[rel="canonical"]');

    var url = explicitUrl ||
      getElAttr(legacyNode, 'data-url') ||
      getElAttr(canonicalNode, 'href') ||
      (window.location ? window.location.href.split('#')[0] : '');

    var h1Node = document.querySelector('h1');
    var title = explicitTitle ||
      getElAttr(legacyNode, 'data-title') ||
      getElAttr(legacyNode, 'data-text') ||
      (h1Node ? h1Node.textContent : '') ||
      document.title ||
      '';

    return {
      url: String(url || '').trim(),
      title: String(title || '').replace(/\s+/g, ' ').trim(),
      img: getElAttr(legacyNode, 'data-img')
    };
  }

  function openSharePopup(targetUrl) {
    var width = Math.min(620, Math.max(360, window.innerWidth ? window.innerWidth - 40 : 620));
    var height = Math.min(540, Math.max(420, window.innerHeight ? window.innerHeight - 60 : 540));
    var left = typeof window.screenLeft === 'number' ? window.screenLeft : (window.screenX || 0);
    var top = typeof window.screenTop === 'number' ? window.screenTop : (window.screenY || 0);
    var popupLeft = left + Math.max(0, Math.round(((window.outerWidth || 800) - width) / 2));
    var popupTop = top + Math.max(0, Math.round(((window.outerHeight || 600) - height) / 2));
    var features = 'width=' + width + ',height=' + height + ',left=' + popupLeft + ',top=' + popupTop + ',noopener,noreferrer';

    try {
      var popup = window.open(targetUrl, 'siantarbarat_share', features);
      if (popup) {
        if (typeof popup.focus === 'function') {
          popup.focus();
        }
        return false;
      }
    } catch (err) {
      return true;
    }
    return true;
  }

  function buildShareUrl(network, explicitUrl, explicitTitle) {
    var data = resolveShareData(explicitUrl, explicitTitle);
    var encodedUrl = encodeURIComponent(data.url);
    var encodedTitle = encodeURIComponent(data.title);

    switch (network) {
      case 'facebook':
        return 'https://www.facebook.com/sharer/sharer.php?u=' + encodedUrl;
      case 'twitter':
        return 'https://twitter.com/intent/tweet?url=' + encodedUrl + (data.title ? '&text=' + encodedTitle : '');
      case 'whatsapp':
        var waText = data.title ? (data.title + '\n' + data.url) : data.url;
        return 'https://wa.me/?text=' + encodeURIComponent(waText);
      case 'telegram':
        return 'https://t.me/share/url?url=' + encodedUrl + (data.title ? '&text=' + encodedTitle : '');
      default:
        return '';
    }
  }

  function shareNetwork(network, explicitUrl, explicitTitle) {
    var targetUrl = buildShareUrl(network, explicitUrl, explicitTitle);
    if (!targetUrl) {
      return true;
    }
    return openSharePopup(targetUrl);
  }

  function showShareToast(message) {
    if (!document.body) {
      return;
    }
    var toast = document.getElementById('global-share-toast');
    if (!toast) {
      toast = document.createElement('div');
      toast.id = 'global-share-toast';
      toast.className = 'global-share-toast';
      toast.setAttribute('role', 'status');
      toast.setAttribute('aria-live', 'polite');
      document.body.appendChild(toast);
    }
    toast.textContent = message;
    toast.classList.add('is-visible');
    if (toast._hideTimer) {
      clearTimeout(toast._hideTimer);
    }
    toast._hideTimer = setTimeout(function () {
      toast.classList.remove('is-visible');
    }, 2200);
  }

  function fallbackCopyText(text) {
    var textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.setAttribute('readonly', '');
    textarea.style.position = 'fixed';
    textarea.style.top = '-9999px';
    textarea.style.left = '-9999px';
    document.body.appendChild(textarea);
    textarea.select();
    textarea.setSelectionRange(0, text.length);
    var copied = false;
    try {
      copied = document.execCommand('copy');
    } catch (err) {
      copied = false;
    }
    document.body.removeChild(textarea);
    return copied;
  }

  function markButtonCopied(buttonEl) {
    if (!buttonEl) {
      return;
    }
    buttonEl.classList.add('is-copied');
    var icon = buttonEl.querySelector('i');
    if (icon) {
      icon.classList.remove('fa-link');
      icon.classList.add('fa-check');
    }
    if (buttonEl._copyTimer) {
      clearTimeout(buttonEl._copyTimer);
    }
    buttonEl._copyTimer = setTimeout(function () {
      buttonEl.classList.remove('is-copied');
      if (icon) {
        icon.classList.remove('fa-check');
        icon.classList.add('fa-link');
      }
    }, 2000);
  }

  function copyShareLink(buttonEl, explicitUrl) {
    var data = resolveShareData(explicitUrl);
    if (!data.url) {
      return false;
    }

    var onSuccess = function () {
      markButtonCopied(buttonEl);
      showShareToast('Tautan berhasil disalin!');
    };

    if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
      navigator.clipboard.writeText(data.url).then(onSuccess, function () {
        if (fallbackCopyText(data.url)) {
          onSuccess();
        } else {
          showShareToast('Gagal menyalin tautan.');
        }
      });
    } else if (fallbackCopyText(data.url)) {
      onSuccess();
    } else {
      showShareToast('Salin tautan secara manual dari bilah alamat.');
    }
    return false;
  }

  window.sharelinkfacebook = function (url, title) {
    return shareNetwork('facebook', url, title);
  };

  window.sharelinktwitter = function (url, title) {
    return shareNetwork('twitter', url, title);
  };

  window.sharelinkwhatsapp = function (url, title) {
    return shareNetwork('whatsapp', url, title);
  };

  window.sharelinktelegram = function (url, title) {
    return shareNetwork('telegram', url, title);
  };

  window.copyShareLink = copyShareLink;

  window.fb_share = function () {
    return shareNetwork('facebook');
  };

  window.twitter_share = function () {
    return shareNetwork('twitter');
  };

  window.pinterest_share = function () {
    var data = resolveShareData();
    var target = 'https://www.pinterest.com/pin/create/button/?url=' + encodeURIComponent(data.url) +
      '&description=' + encodeURIComponent(data.title) +
      (data.img ? '&media=' + encodeURIComponent(data.img) : '');
    return openSharePopup(target);
  };

  window.linked_share = function () {
    var data = resolveShareData();
    var target = 'https://www.linkedin.com/shareArticle?mini=true&url=' + encodeURIComponent(data.url) +
      '&title=' + encodeURIComponent(data.title);
    return openSharePopup(target);
  };

  window.line_share = function () {
    var data = resolveShareData();
    window.open('https://social-plugins.line.me/lineit/share?url=' + encodeURIComponent(data.url), '_blank', 'noopener,noreferrer');
    return false;
  };

  document.addEventListener('click', function (event) {
    var copyBtn = event.target.closest('.global-share-bar [data-share-copy]');
    if (copyBtn) {
      event.preventDefault();
      copyShareLink(copyBtn, copyBtn.getAttribute('data-url'));
      return;
    }

    var shareLink = event.target.closest('.global-share-bar [data-share-network]');
    if (shareLink) {
      var network = shareLink.getAttribute('data-share-network');
      var handled = shareNetwork(network, shareLink.getAttribute('data-url'), shareLink.getAttribute('data-title'));
      if (handled === false) {
        event.preventDefault();
      }
    }
  });
})(window, document);
