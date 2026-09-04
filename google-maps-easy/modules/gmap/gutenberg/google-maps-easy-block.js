(function (blocks, element, components, i18n, serverSideRender) {
  var el = element.createElement;
  var __ = i18n.__;
  var ServerSideRender = serverSideRender && serverSideRender.default ? serverSideRender.default : serverSideRender;
  var mapsData = window.gmpGutenbergMaps || {};
  var mapsOptions = mapsData.maps || [{ label: __('Select a map', 'google-maps-easy'), value: '' }];

  blocks.registerBlockType('supsystic/google-maps-easy', {
    title: __('Easy Google Maps by Supsystic', 'google-maps-easy'),
    icon: 'location-alt',
    category: 'widgets',
    keywords: [
      __('map', 'google-maps-easy'),
      __('google maps', 'google-maps-easy'),
      __('supsystic', 'google-maps-easy'),
    ],
    attributes: {
      map_id: {
        type: 'string',
        default: mapsData.defaultMapId || '',
      },
      width: {
        type: 'string',
        default: '',
      },
      height: {
        type: 'number',
      },
      align: {
        type: 'string',
        default: '',
      },
    },
    edit: function (props) {
      var attrs = props.attributes;
      var selectedMapId = attrs.map_id || '';
      var heightValue = typeof attrs.height === 'undefined' ? '' : attrs.height;
      var preview = selectedMapId && ServerSideRender
        ? el(ServerSideRender, {
            block: 'supsystic/google-maps-easy',
            attributes: attrs,
          })
        : selectedMapId
          ? el('p', {}, __('Map preview will be available after saving or refreshing the editor.', 'google-maps-easy'))
          : el('p', {}, __('Select an Easy Google Maps map to display it here.', 'google-maps-easy'));

      return el(
        'div',
        { className: props.className },
        el(
          components.PanelBody,
          { title: __('Map Settings', 'google-maps-easy'), initialOpen: true },
          el(components.SelectControl, {
            label: __('Select Map', 'google-maps-easy'),
            value: selectedMapId,
            options: mapsOptions,
            onChange: function (value) {
              props.setAttributes({ map_id: value });
            },
          }),
          el(components.TextControl, {
            label: __('Width', 'google-maps-easy'),
            value: attrs.width || '',
            placeholder: '100%',
            onChange: function (value) {
              props.setAttributes({ width: value });
            },
          }),
          el(components.TextControl, {
            label: __('Height', 'google-maps-easy'),
            type: 'number',
            min: 50,
            step: 10,
            value: heightValue,
            onChange: function (value) {
              var parsedValue = parseInt(value, 10);
              props.setAttributes({ height: value === '' || isNaN(parsedValue) ? undefined : parsedValue });
            },
          }),
          el(components.SelectControl, {
            label: __('Alignment', 'google-maps-easy'),
            value: attrs.align || '',
            options: [
              { label: __('Default', 'google-maps-easy'), value: '' },
              { label: __('Left', 'google-maps-easy'), value: 'left' },
              { label: __('Right', 'google-maps-easy'), value: 'right' },
              { label: __('None', 'google-maps-easy'), value: 'none' },
            ],
            onChange: function (value) {
              props.setAttributes({ align: value });
            },
          })
        ),
        preview
      );
    },
    save: function () {
      return null;
    },
  });
})(window.wp.blocks, window.wp.element, window.wp.components, window.wp.i18n, window.wp.serverSideRender);
