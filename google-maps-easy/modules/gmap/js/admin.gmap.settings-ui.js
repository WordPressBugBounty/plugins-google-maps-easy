/**
 * Map editor: the options of the Map tab are split into groups shown as tabs, the rows of a group sit in cards, a
 * search finds any option and the checkboxes look like switches. The Markers, Figures, Heatmap and Path Router forms
 * get one card each.
 *
 * Only the presentation changes: every row (<tr>) is moved whole into a card table inside the same <form>, so no field
 * is renamed, rebuilt or taken out of its form, and the page scripts (which find rows with closest('tr')), the saved
 * values and the PRO handlers keep working as before. The same model as the Photo Gallery editor
 * (grid-gallery.galleries.settings-ui.js) and the Ultimate Maps editor.
 */
(function ($) {
  'use strict';

  var STORAGE_KEY = 'gmpMapSettingsGroup';

  // A row belongs to the card of the first map_opts[...] key found in it. Rows that are not listed go to "More".
  var GROUPS = [
    {
      key: 'basics',
      icon: 'fa-map-o',
      label: 'Basics',
      cards: [{ key: 'map', icon: 'fa-globe', title: 'Map', text: 'Name, size and the Google Cloud Map ID.', fields: ['title', 'width', 'height', 'mapId'] }],
    },
    {
      key: 'view',
      icon: 'fa-crosshairs',
      label: 'Center & Zoom',
      cards: [
        { key: 'center', icon: 'fa-map-pin', title: 'Map center', text: 'Type an address or coordinates, or drag the map in the preview.', fields: ['map_center'] },
        { key: 'zoom', icon: 'fa-search-plus', title: 'Zoom', text: 'The first zoom of the map, on desktops and phones.', fields: ['zoom_type'] },
        { key: 'interaction', icon: 'fa-hand-pointer-o', title: 'Interaction', text: 'How visitors can move and zoom the map.', fields: ['draggable'] },
      ],
    },
    {
      key: 'controls',
      icon: 'fa-sliders',
      label: 'Controls',
      cards: [
        { key: 'controls', icon: 'fa-gamepad', title: 'Map controls', text: 'The buttons Google draws on the map and where they sit.', fields: ['type_control', 'zoom_control', 'street_view_control', 'enable_full_screen_btn'] },
        { key: 'custom', icon: 'fa-th', title: 'Custom controls', text: 'Your own set of buttons over the map: zoom, reset, search, filters.', fields: ['enable_custom_map_controls'] },
      ],
    },
    {
      key: 'look',
      icon: 'fa-paint-brush',
      label: 'Look',
      cards: [{ key: 'theme', icon: 'fa-tint', title: 'Theme & style', text: 'Map type, color stylization and what the map hides.', fields: ['map_type', 'map_stylization', 'hide_poi', 'hide_countries'] }],
    },
    {
      key: 'markers',
      icon: 'fa-map-marker',
      label: 'Markers',
      cards: [
        { key: 'markers', icon: 'fa-map-marker', title: 'Markers', text: 'Clusters, tooltips and how the map follows the markers.', fields: ['marker_clasterer', 'hide_marker_tooltip', 'center_on_cur_marker_infownd', 'center_on_cur_user_pos'] },
        { key: 'list', icon: 'fa-list-ul', title: 'Markers list', text: 'A list or slider of the markers next to the map.', fields: ['markers_list_type'] },
        { key: 'filters', icon: 'fa-filter', title: 'Category filter', text: 'The filter of marker categories over the map.', fields: ['marker_filter_color', 'marker_filter_button_title', 'marker_filter_show_all_parents'] },
        { key: 'visitors', icon: 'fa-users', title: 'Visitors', text: 'Markers added by visitors and the search of places.', fields: ['frontend_add_markers', 'places'] },
      ],
    },
    {
      key: 'layers',
      icon: 'fa-clone',
      label: 'Layers',
      cards: [
        { key: 'google', icon: 'fa-road', title: 'Google layers', text: 'Live traffic, public transport and bicycle routes.', fields: ['enable_trafic_layer', 'enable_transit_layer', 'enable_bicycling_layer'] },
        { key: 'kml', icon: 'fa-file-code-o', title: 'KML layers', text: 'Zones, routes and points from your KML files.', fields: ['enable_kml_filter', 'kml_file_url'] },
      ],
    },
    {
      key: 'info',
      icon: 'fa-commenting-o',
      label: 'Info window',
      cards: [
        {
          key: 'infownd',
          icon: 'fa-commenting-o',
          title: 'Info window',
          text: 'The window that opens on a marker: its look, size and fonts.',
          fields: ['marker_infownd_type', 'marker_infownd_width_units', 'marker_infownd_width', 'marker_infownd_height_units', 'marker_infownd_height', 'marker_title_color', 'marker_infownd_bg_color', 'marker_title_size', 'marker_desc_size'],
        },
        { key: 'buttons', icon: 'fa-hand-o-up', title: 'Info window buttons', text: 'Directions and printing from the window of a marker.', fields: ['enable_directions_btn', 'enable_infownd_print_btn'] },
      ],
    },
  ];
  var MORE = { key: 'more', icon: 'fa-ellipsis-h', label: 'More', cards: [{ key: 'more', icon: 'fa-ellipsis-h', title: 'More options', text: '', fields: [] }] };

  function texts() {
    return window.gmpMapUi && typeof window.gmpMapUi === 'object' ? window.gmpMapUi : {};
  }
  function t(text) {
    var all = texts();
    return all[text] ? all[text] : text;
  }
  function readStore() {
    try {
      return window.localStorage.getItem(STORAGE_KEY);
    } catch (e) {
      return null;
    }
  }
  function writeStore(value) {
    try {
      window.localStorage.setItem(STORAGE_KEY, value);
    } catch (e) {}
  }

  /** The option keys of a row: map_opts[KEY]... of its fields, in order */
  function rowKeys(row) {
    var keys = [];
    Array.prototype.forEach.call(row.querySelectorAll('[name]'), function (field) {
      var match = /^map_opts\[([^\]]+)\]/.exec(field.getAttribute('name') || '');
      if (match && keys.indexOf(match[1]) === -1) {
        keys.push(match[1]);
      }
    });
    return keys;
  }

  function findCard(keys) {
    for (var i = 0; i < keys.length; i++) {
      for (var g = 0; g < GROUPS.length; g++) {
        for (var c = 0; c < GROUPS[g].cards.length; c++) {
          if (GROUPS[g].cards[c].fields.indexOf(keys[i]) !== -1) {
            return { group: GROUPS[g], card: GROUPS[g].cards[c] };
          }
        }
      }
    }
    return { group: MORE, card: MORE.cards[0] };
  }

  /** A card frame with its head and an empty form table; the rows are moved into its tbody */
  function makeCard(card) {
    var box = document.createElement('section'),
      head = document.createElement('div'),
      icon = document.createElement('span'),
      title = document.createElement('div'),
      h3 = document.createElement('h3'),
      table = document.createElement('table'),
      tbody = document.createElement('tbody');
    box.className = 'gmp-mc';
    box.setAttribute('data-gmp-card', card.key);
    head.className = 'gmp-mc-head';
    icon.className = 'gmp-mc-icon';
    icon.innerHTML = '<i class="fa ' + card.icon + '" aria-hidden="true"></i>';
    h3.textContent = t(card.title);
    title.appendChild(h3);
    if (card.text) {
      var p = document.createElement('p');
      p.textContent = t(card.text);
      title.appendChild(p);
    }
    head.appendChild(icon);
    head.appendChild(title);
    table.className = 'form-table gmp-mc-table';
    table.appendChild(tbody);
    box.appendChild(head);
    box.appendChild(table);
    return { box: box, tbody: tbody };
  }

  /** The checkboxes of the cards become switches; the input stays the same element */
  function switchify(scope) {
    Array.prototype.forEach.call(scope.querySelectorAll('input[type="checkbox"]'), function (box) {
      if (!box.closest('.ui-jqgrid') && !box.closest('.gmpProOptMiniLabel')) {
        box.classList.add('gmp-switch');
      }
    });
  }

  // A card whose rows the page has all hidden (options of another mode) is not drawn as an empty frame
  function shown(element, stop) {
    for (var node = element; node && node !== stop; node = node.parentElement) {
      if (node.hidden || window.getComputedStyle(node).display === 'none') {
        return false;
      }
    }
    return true;
  }

  function initMapForm() {
    var form = document.getElementById('gmpMapForm');
    if (!form || form.classList.contains('gmp-mapui')) {
      return;
    }
    var rows = [];
    Array.prototype.forEach.call(form.querySelectorAll('table.form-table'), function (table) {
      // Only the outer tables of the form: the nested ones (Draggable, Double click) travel with their row
      if (table.parentElement.closest('table')) {
        return;
      }
      var body = table.tBodies[0] || table;
      Array.prototype.forEach.call(body.children, function (row) {
        if (row.tagName === 'TR') {
          rows.push(row);
        }
      });
    });
    if (!rows.length) {
      return;
    }

    var cards = {},
      order = [],
      used = {};
    rows.forEach(function (row) {
      var keys = rowKeys(row);
      if (!keys.length && !row.querySelector('input, select, textarea, button')) {
        // A heading row of the old layout ("Info Window:"): the card head says it now
        row.classList.add('gmp-mc-oldhead');
        row.hidden = true;
      }
      var place = keys.length ? findCard(keys) : null;
      if (!place) {
        // The heading row goes with the next card; a row without options of its own stays in "More"
        place = row.hidden ? findCard(rowKeys(row.nextElementSibling || row)) : { group: MORE, card: MORE.cards[0] };
      }
      var id = place.group.key + '/' + place.card.key;
      if (!cards[id]) {
        cards[id] = { group: place.group, card: place.card, rows: [] };
        order.push(id);
      }
      cards[id].rows.push(row);
      used[place.group.key] = place.group;
    });

    // Cards in the order of the groups and cards above, the rows in their own order
    var groups = GROUPS.concat([MORE]).filter(function (group) {
      return used[group.key];
    });
    var holder = document.createElement('div');
    holder.className = 'gmp-mc-list';
    var sections = [];
    groups.forEach(function (group) {
      group.cards.forEach(function (card) {
        var id = group.key + '/' + card.key;
        if (!cards[id]) {
          return;
        }
        var made = makeCard(card);
        made.box.setAttribute('data-gmp-group', group.key);
        cards[id].rows.forEach(function (row) {
          made.tbody.appendChild(row);
        });
        holder.appendChild(made.box);
        sections.push(made.box);
      });
    });

    // The emptied old tables stay in the form, hidden (nothing is removed)
    Array.prototype.forEach.call(form.querySelectorAll('table.form-table:not(.gmp-mc-table)'), function (table) {
      if (!table.parentElement.closest('table') && !table.querySelector('tr')) {
        table.classList.add('gmp-mc-emptied');
      }
    });

    var nav = document.createElement('div');
    nav.className = 'gmp-mapui-nav';
    var list = document.createElement('div');
    list.className = 'gmp-mapui-groups';
    list.setAttribute('role', 'tablist');
    list.setAttribute('aria-label', t('Settings groups'));
    groups.forEach(function (group) {
      var button = document.createElement('button');
      button.type = 'button';
      button.className = 'gmp-mapui-group';
      button.setAttribute('role', 'tab');
      button.setAttribute('data-gmp-group', group.key);
      button.innerHTML = '<i class="fa fa-fw ' + group.icon + '" aria-hidden="true"></i><span></span>';
      button.querySelector('span').textContent = t(group.label);
      list.appendChild(button);
    });
    var search = document.createElement('label');
    search.className = 'gmp-mapui-search';
    search.innerHTML = '<i class="fa fa-search" aria-hidden="true"></i><input type="search" autocomplete="off">';
    var input = search.querySelector('input');
    // Not a field of the map: without a name it is never sent with the form
    input.placeholder = t('Search settings');
    input.setAttribute('aria-label', t('Search settings'));
    nav.appendChild(list);
    nav.appendChild(search);
    // A scrolling strip (narrow column): fade the side where more groups are hidden
    function markStripEnds() {
      var max = list.scrollWidth - list.clientWidth;
      list.classList.toggle('is-more-left', max > 1 && list.scrollLeft > 1);
      list.classList.toggle('is-more-right', max > 1 && list.scrollLeft < max - 1);
    }
    list.addEventListener('scroll', markStripEnds, { passive: true });
    window.addEventListener('resize', markStripEnds);

    var empty = document.createElement('div');
    empty.className = 'gmp-mapui-empty';
    empty.hidden = true;
    empty.textContent = t('No settings match your search.');

    form.insertBefore(nav, form.firstChild);
    form.insertBefore(empty, nav.nextSibling);
    form.insertBefore(holder, empty.nextSibling);
    form.classList.add('gmp-mapui');
    switchify(holder);

    function activate(key, remember) {
      if (!used[key]) {
        key = groups[0].key;
      }
      form.setAttribute('data-gmp-active', key);
      Array.prototype.forEach.call(list.children, function (button) {
        var on = button.getAttribute('data-gmp-group') === key;
        button.classList.toggle('is-active', on);
        button.setAttribute('aria-selected', on ? 'true' : 'false');
        button.tabIndex = on ? 0 : -1;
        // On a narrow column the groups are one scrolling strip: keep the open group in view
        if (on && list.scrollWidth > list.clientWidth) {
          var left = button.offsetLeft - list.offsetLeft;
          if (left < list.scrollLeft || left + button.offsetWidth > list.scrollLeft + list.clientWidth) {
            list.scrollLeft = Math.max(0, left - 56);
          }
        }
      });
      markStripEnds();
      sections.forEach(function (section) {
        section.classList.toggle('gmp-mc-off', section.getAttribute('data-gmp-group') !== key);
      });
      if (remember) {
        writeStore(key);
      }
      markEmpty();
    }

    function markEmpty() {
      sections.forEach(function (section) {
        var rowsIn = section.querySelectorAll('.gmp-mc-table > tbody > tr'),
          visible = Array.prototype.some.call(rowsIn, function (row) {
            return !row.hidden && row.style.display !== 'none' && !row.classList.contains('gmp-row-miss');
          });
        section.classList.toggle('gmp-mc-empty', rowsIn.length > 0 && !visible);
      });
    }
    var emptyTimer = null;
    function markEmptySoon() {
      window.clearTimeout(emptyTimer);
      emptyTimer = window.setTimeout(markEmpty, 80);
    }
    if (window.MutationObserver) {
      new MutationObserver(markEmptySoon).observe(holder, { attributes: true, attributeFilter: ['style', 'hidden'], subtree: true });
    }

    // Search through the labels of every group; rows the page itself hides stay hidden
    function filter(query) {
      query = query.trim().toLowerCase();
      form.classList.toggle('is-searching', query !== '');
      var found = 0;
      sections.forEach(function (section) {
        var hits = 0;
        Array.prototype.forEach.call(section.querySelectorAll('.gmp-mc-table > tbody > tr'), function (row) {
          var label = Array.prototype.map
            .call(row.querySelectorAll('th, label'), function (th) {
              return th.textContent;
            })
            .join(' ')
            .toLowerCase();
          var match = !query || label.indexOf(query) !== -1;
          row.classList.toggle('gmp-row-miss', !match);
          if (match && query && !row.hidden && row.style.display !== 'none') {
            hits++;
          }
        });
        section.classList.toggle('gmp-mc-miss', query !== '' && hits === 0);
        found += hits;
      });
      empty.hidden = !query || found > 0;
      markEmpty();
    }

    list.addEventListener('click', function (event) {
      var button = event.target.closest('.gmp-mapui-group');
      if (!button) {
        return;
      }
      input.value = '';
      filter('');
      activate(button.getAttribute('data-gmp-group'), true);
    });
    // Arrow keys move between the groups, as in a tab list
    list.addEventListener('keydown', function (event) {
      var buttons = Array.prototype.slice.call(list.children),
        index = buttons.indexOf(document.activeElement),
        step = { ArrowRight: 1, ArrowDown: 1, ArrowLeft: -1, ArrowUp: -1 }[event.key];
      if (index === -1 || !step) {
        return;
      }
      event.preventDefault();
      var next = buttons[(index + step + buttons.length) % buttons.length];
      next.focus();
      next.click();
    });

    var timer = null;
    input.addEventListener('input', function () {
      window.clearTimeout(timer);
      timer = window.setTimeout(function () {
        filter(input.value);
      }, 120);
    });
    input.addEventListener('keydown', function (event) {
      // Enter in the search must not submit the map form
      if (event.key === 'Enter') {
        event.preventDefault();
      }
      if (event.key === 'Escape') {
        input.value = '';
        filter('');
      }
    });
    document.addEventListener('keydown', function (event) {
      var tag = event.target && event.target.tagName;
      if (event.key === '/' && tag !== 'INPUT' && tag !== 'TEXTAREA' && tag !== 'SELECT' && !event.target.isContentEditable && form.offsetParent !== null) {
        event.preventDefault();
        input.focus();
      }
    });
    form.addEventListener('change', markEmptySoon);

    activate(readStore() || groups[0].key, false);
  }

  /**
   * The other forms of the editor: the form itself becomes the card, with a head and switches. Nothing is moved: the
   * Marker and Figure forms hold a TinyMCE editor (wp_editor), whose iframe would reload and break if its row moved.
   */
  function cardForm(formId, card) {
    var form = document.getElementById(formId);
    if (!form || form.classList.contains('gmp-mapui-card')) {
      return;
    }
    var table = form.querySelector('table.form-table');
    if (!table || table.parentElement.closest('table')) {
      return;
    }
    var made = makeCard(card);
    var head = made.box.querySelector('.gmp-mc-head');
    form.insertBefore(head, form.firstChild);
    table.classList.add('gmp-mc-table');
    form.classList.add('gmp-mc', 'gmp-mapui-card');
    form.setAttribute('data-gmp-card', card.key);
    switchify(form);
  }

  function init() {
    var tabs = document.getElementById('gmpMapPropertiesTabs');
    if (!tabs) {
      return;
    }
    tabs.classList.add('gmp-mapui-tabs');
    initMapForm();
    cardForm('gmpMarkerForm', { key: 'marker', icon: 'fa-map-marker', title: 'Marker', text: 'Name, place, icon and what opens on a click.' });
    cardForm('gmpShapeForm', { key: 'figure', icon: 'fa-cubes', title: 'Figure', text: 'A polygon, polyline or circle drawn on the map.' });
    cardForm('gmpHeatmapForm', { key: 'heatmap', icon: 'fa-fire', title: 'Heatmap layer', text: 'Points that show where things happen most.' });
    cardForm('gmpRouterForm', { key: 'router', icon: 'fa-random', title: 'Path router', text: 'A route between the points of the map.' });
    // The PRO plaques and the PRO card of the free version (admin.upsell.js) follow the cards
    document.dispatchEvent(new CustomEvent('gmpMapUiReady'));
  }

  $(function () {
    // After the page's own ready handlers: the tabs, the color pickers and the PRO handlers are set up by then
    window.setTimeout(init, 0);
  });
})(jQuery);
