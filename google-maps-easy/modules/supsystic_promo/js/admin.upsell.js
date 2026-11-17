/**
 * PRO feature windows of the free version (views/tpl/upsellWindows.php), the same as in Photo Gallery and Data Tables.
 * - Any element with data-gmp-upsell="<feature>" opens the window of that feature; without a window it keeps its own
 *   behaviour (a link to the PRO page). The listener runs in the capture phase.
 * - The old "PRO option" labels (.gmpProOptMiniLabel) become PRO plaques with Read more. The label and its link stay
 *   in the row, so the old scripts that read the link (gmpInitMainPromoPopup) still find it.
 * - The old "available in PRO" popup (#gmpOptInProWnd) gives way to the window of the feature.
 */
(function ($) {
  'use strict';

  var dialog = null,
    body = null,
    aliases = {};

  function texts() {
    return window.gmpUpsellTexts || {};
  }

  function setup() {
    if (dialog) {
      return true;
    }
    dialog = document.querySelector('[data-gmp-up]');
    body = dialog ? dialog.querySelector('[data-gmp-up-body]') : null;
    if (!dialog || !body || typeof dialog.showModal !== 'function') {
      dialog = null;
      return false;
    }
    try {
      aliases = JSON.parse(dialog.getAttribute('data-gmp-up-aliases') || '{}') || {};
    } catch (e) {
      aliases = {};
    }
    dialog.addEventListener('click', function (event) {
      // A click on the dimmed backdrop lands on the dialog itself
      if (event.target === dialog || event.target.closest('[data-gmp-up-close]')) {
        dialog.close();
      }
    });
    return true;
  }

  /** The window of a key: its own, an old utm key, or a family of old keys (marker_list_...) */
  function resolve(key) {
    if (!key || !setup()) {
      return '';
    }
    if (document.getElementById('gmp-up-' + key)) {
      return key;
    }
    if (aliases[key]) {
      return aliases[key];
    }
    for (var alias in aliases) {
      if (alias.charAt(alias.length - 1) === '_' && key.indexOf(alias) === 0) {
        return aliases[alias];
      }
    }
    return '';
  }

  /** The utm_medium of a PRO link */
  function keyOfLink(href) {
    var match = /[?&]utm_medium=([^&#]+)/.exec(href || '');
    return match ? decodeURIComponent(match[1]) : '';
  }

  /**
   * @param {string} key The feature or an old key
   * @return {boolean} Whether the window opened
   */
  function open(key) {
    var feature = resolve(key),
      template = feature ? document.getElementById('gmp-up-' + feature) : null;
    if (!template) {
      return false;
    }
    body.textContent = '';
    body.appendChild(template.content.cloneNode(true));
    if (!dialog.open) {
      dialog.showModal();
    }
    body.scrollTop = 0;
    var cta = body.querySelector('.gmp-up-cta');
    if (cta) {
      cta.focus({ preventScroll: true });
    }
    return true;
  }

  document.addEventListener(
    'click',
    function (event) {
      var trigger = event.target.closest ? event.target.closest('[data-gmp-upsell]') : null;
      if (trigger && open(trigger.getAttribute('data-gmp-upsell'))) {
        event.preventDefault();
        event.stopImmediatePropagation();
      }
    },
    true
  );

  /** The chip (short name) of a feature, from its window */
  function chipOf(feature) {
    var template = document.getElementById('gmp-up-' + feature);
    return template ? template.getAttribute('data-chip') || '' : '';
  }

  // The old "PRO option" labels as PRO plaques; one plaque per feature in a run of rows without fields of their own
  function plaques(scope) {
    var lastRowKey = null;
    Array.prototype.forEach.call((scope || document).querySelectorAll('.gmpProOptMiniLabel'), function (label) {
      if (label.classList.contains('gmp-pro-note')) {
        return;
      }
      var link = label.querySelector('a[href]');
      var feature = resolve(keyOfLink(link ? link.getAttribute('href') : ''));
      if (!link || !feature) {
        return;
      }
      var row = label.closest('tr');
      if (row) {
        var fieldless = !row.querySelector('td input:not([type="hidden"]), td select, td textarea'),
          inLabel = !!label.closest('th');
        if (fieldless && inLabel && lastRowKey === feature && row.previousElementSibling && row.previousElementSibling.querySelector('.gmp-pro-note')) {
          // "Period From" / "Period To" of the free version: the plaque of "Display period" above is enough
          label.classList.add('gmp-pro-note', 'gmp-pro-note-dup');
          label.hidden = true;
          return;
        }
        lastRowKey = feature;
        // Under the field of the row, not in the label column
        var cell = row.querySelector(':scope > td:last-child');
        var br = label.previousElementSibling;
        if (br && br.tagName === 'BR') {
          br.parentNode.removeChild(br);
        }
        if (cell && !cell.contains(label) && !label.closest('table table')) {
          cell.appendChild(label);
        }
        var locked = row.querySelector('.gmpProOpt');
        if (locked) {
          row.classList.add('gmp-pro-locked');
        }
      }
      label.classList.add('gmp-pro-note');
      label.style.paddingLeft = '';
      var title = chipOf(feature);
      link.className = 'gmp-pro-note-more';
      link.setAttribute('data-gmp-upsell', feature);
      link.setAttribute('rel', 'noopener');
      link.innerHTML = '';
      link.appendChild(document.createTextNode((texts().readMore || 'Read more') + ' '));
      var arrow = document.createElement('i');
      arrow.className = 'fa fa-arrow-right';
      arrow.setAttribute('aria-hidden', 'true');
      link.appendChild(arrow);
      var icon = document.createElement('span');
      icon.className = 'gmp-pro-note-icon';
      icon.innerHTML = '<i class="fa fa-diamond" aria-hidden="true"></i>';
      var text = document.createElement('span');
      text.className = 'gmp-pro-note-text';
      text.innerHTML = '<b class="gmp-pro-note-tag">PRO</b>';
      if (title) {
        text.appendChild(document.createTextNode(title));
      }
      label.insertBefore(icon, link);
      label.insertBefore(text, link);
    });
  }

  // The old popup of a PRO field opens the window of its feature instead (its link is set just before it opens)
  $(document).on('dialogopen', '#gmpOptInProWnd', function () {
    var $wnd = $(this),
      href = $wnd.find('a[href]').first().attr('href');
    if (open(keyOfLink(href))) {
      setTimeout(function () {
        $wnd.dialog('close');
      }, 0);
    }
  });

  // The PRO tabs of the map editor (Figures, Heatmap, Path Router): the picture of the feature from its window, and
  // Read more opens that window. The tab passes through wp_kses, so this is filled here.
  function promoTabs() {
    Array.prototype.forEach.call(document.querySelectorAll('.gmp-up-tab'), function (tab) {
      var more = tab.querySelector('.gmp-up-tab-more'),
        cta = tab.querySelector('.gmp-up-cta'),
        feature = resolve(keyOfLink((more || cta || {}).getAttribute ? (more || cta).getAttribute('href') : ''));
      if (!feature) {
        return;
      }
      if (more) {
        more.setAttribute('data-gmp-upsell', feature);
      }
      var slot = tab.querySelector('.gmp-up-tab-demo'),
        template = document.getElementById('gmp-up-' + feature),
        picture = template ? template.content.querySelector('.gmp-up-map') : null;
      if (slot && picture && !slot.firstChild) {
        slot.appendChild(picture.cloneNode(true));
      }
    });
  }

  // Other links to the PRO page of a feature (the "Available in PRO" buttons of the markers list layouts): its window
  function proLinks() {
    Array.prototype.forEach.call(document.querySelectorAll('a[href*="utm_medium="]:not([data-gmp-upsell])'), function (link) {
      if (link.closest('[data-gmp-up]')) {
        return;
      }
      var feature = resolve(keyOfLink(link.getAttribute('href')));
      if (feature) {
        link.setAttribute('data-gmp-upsell', feature);
      }
    });
  }

  /**
   * The PRO card of the map editor (like pro-card.twig of the Photo Gallery editors): every PRO feature of the map as a
   * button that opens its window, and Get PRO. It stands under the cards of every group of the Map tab.
   */
  function proCard() {
    var list = document.querySelector('#gmpMapForm.gmp-mapui .gmp-mc-list');
    if (!list || !setup() || document.querySelector('.gmp-up-card')) {
      return;
    }
    var texts = {};
    try {
      texts = JSON.parse(dialog.getAttribute('data-gmp-up-card') || '{}') || {};
    } catch (e) {
      texts = {};
    }
    var card = document.createElement('section'),
      head = document.createElement('div'),
      eyebrow = document.createElement('span'),
      h3 = document.createElement('h3'),
      p = document.createElement('p'),
      grid = document.createElement('div'),
      foot = document.createElement('div'),
      cta = document.createElement('a');
    card.className = 'gmp-up-card';
    head.className = 'gmp-up-card-head';
    eyebrow.className = 'gmp-up-eyebrow';
    eyebrow.innerHTML = '<i class="fa fa-diamond" aria-hidden="true"></i>';
    eyebrow.appendChild(document.createTextNode(texts.eyebrow || 'PRO'));
    h3.textContent = texts.title || '';
    p.textContent = texts.text || '';
    head.appendChild(eyebrow);
    head.appendChild(h3);
    head.appendChild(p);
    grid.className = 'gmp-up-card-grid';
    Array.prototype.forEach.call(document.querySelectorAll('template[id^="gmp-up-"]'), function (template) {
      // Access roles live in Settings, the CSV import on its own page
      if (template.getAttribute('data-group') === 'site' || template.getAttribute('data-group') === 'markers' && template.id === 'gmp-up-csv') {
        return;
      }
      var button = document.createElement('button'),
        icon = document.createElement('i');
      button.type = 'button';
      button.setAttribute('data-gmp-upsell', template.id.replace('gmp-up-', ''));
      // The card shows the PRO features of the group in view (CSS by the active group of the form)
      button.setAttribute('data-gmp-up-group', template.getAttribute('data-group') || '');
      icon.className = 'fa ' + (template.getAttribute('data-icon') || 'fa-diamond');
      icon.setAttribute('aria-hidden', 'true');
      button.appendChild(icon);
      button.appendChild(document.createTextNode(template.getAttribute('data-chip') || ''));
      grid.appendChild(button);
    });
    foot.className = 'gmp-up-card-foot';
    cta.className = 'gmp-up-cta';
    cta.href = texts.url || '#';
    cta.target = '_blank';
    cta.rel = 'noopener';
    cta.innerHTML = '<span></span><i class="fa fa-arrow-right" aria-hidden="true"></i>';
    cta.querySelector('span').textContent = texts.cta || 'Get PRO';
    foot.appendChild(cta);
    card.appendChild(head);
    card.appendChild(grid);
    card.appendChild(foot);
    list.parentNode.insertBefore(card, list.nextSibling);
  }

  // One plaque per feature in a card: the other rows of the same feature (the three control positions, the three
  // Google layers) keep their label hidden, so the card does not repeat itself
  function dedupe() {
    Array.prototype.forEach.call(document.querySelectorAll('.gmp-mc'), function (card) {
      var seen = {};
      Array.prototype.forEach.call(card.querySelectorAll('.gmp-pro-note'), function (note) {
        var link = note.querySelector('[data-gmp-upsell]'),
          key = link ? link.getAttribute('data-gmp-upsell') : '';
        if (!key || note.hidden) {
          return;
        }
        if (seen[key]) {
          note.hidden = true;
          note.classList.add('gmp-pro-note-dup');
        }
        seen[key] = true;
      });
    });
  }

  // "Get 300+ Themes with PRO" beside Map Stylization: the window of the map themes
  function themesLink() {
    var select = document.querySelector('select[name="map_opts[map_stylization]"]'),
      row = select ? select.closest('tr') : null,
      link = row ? row.querySelector('a.sup-standard-link') : null;
    if (link && resolve('map_themes')) {
      link.setAttribute('data-gmp-upsell', 'map_themes');
      link.classList.add('gmp-themes-link');
    }
  }

  $(function () {
    promoTabs();
    themesLink();
    proLinks();
    // After the editor layout (admin.gmap.settings-ui.js) has put the rows into their cards
    setTimeout(function () {
      plaques(document);
      proCard();
    }, 30);
    // The map editor puts its rows into cards a little later (admin.gmap.settings-ui.js)
    document.addEventListener('gmpMapUiReady', function () {
      plaques(document);
      dedupe();
      proCard();
    });
  });

  window.GmpUpsell = { open: open, plaques: plaques };
})(jQuery);
