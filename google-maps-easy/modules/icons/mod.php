<?php
class iconsGmp extends moduleGmp {
	public function init(){
		parent::init();
		add_filter('upload_mimes', array($this, 'addMimeTypes'));
		add_filter('wp_handle_upload_prefilter', array($this, 'simple_svg_sanitize_file'));
		//$this->getModel()->checkDefIcons();
		/*if(frameGmp::_()->isAdminPlugPage()){
			$gmpExistsIcons = $this->getModel()->getIcons();
			frameGmp::_()->addJSVar('iconOpts', 'gmpExistsIcons', $gmpExistsIcons);
			frameGmp::_()->addScript('iconOpts', $this->getModPath() .'js/iconOpts.js');
		}*/
	}
	public function addMimeTypes($mimes) {
		$mimes['svg'] = 'image/svg+xml';
		return $mimes;
	}
	// Sanitize SVG files upon upload.
	public function simple_svg_sanitize_file( $file ) {
		if ( $file['type'] === 'image/svg+xml' ) {
			$svg = file_get_contents( $file['tmp_name'] );
			$clean_svg = $this->simple_svg_sanitize( $svg );

			if ( $clean_svg ) {
				file_put_contents( $file['tmp_name'], $clean_svg );
			} else {
				$file['error'] = 'Unable to sanitize SVG file.';
			}
		}
		return $file;
	}
	// Basic SVG sanitization function.
	public function simple_svg_sanitize( $svg ) {
		// List of allowed elements.
		$allowed_elements = array(
			'svg', 'g', 'path', 'rect', 'circle', 'ellipse', 'line', 'polyline', 'polygon', 'text',
			'tspan', 'tref', 'textPath', 'altGlyph', 'glyphRef', 'altGlyphDef', 'altGlyphItem',
			'glyph', 'missing-glyph', 'desc', 'title', 'use', 'symbol', 'defs', 'clipPath',
			'filter', 'pattern', 'mask', 'image', 'switch', 'style', 'view'
		);

		// List of allowed attributes.
		$allowed_attributes = array(
			'id', 'xml:base', 'xml:lang', 'xml:space', 'height', 'width', 'x', 'y', 'version',
			'preserveAspectRatio', 'viewBox', 'transform', 'style', 'class', 'd', 'pathLength',
			'xlink:href', 'xlink:title', 'xlink:show', 'xlink:actuate', 'xlink:type', 'xlink:role',
			'xlink:arcrole', 'xlink:label', 'xlink:from', 'xlink:to', 'xlink:title',
			'fill', 'stroke', 'stroke-width', 'stroke-linecap', 'stroke-linejoin', 'stroke-miterlimit',
			'stroke-dasharray', 'stroke-dashoffset', 'opacity', 'fill-opacity', 'stroke-opacity',
			'font-family', 'font-size', 'font-weight', 'font-style', 'text-anchor', 'pointer-events',
			'clip-rule', 'fill-rule', 'marker', 'marker-start', 'marker-mid', 'marker-end', 'mask',
			'pattern', 'filter', 'style', 'clip-path', 'filter', 'stop-color', 'stop-opacity',
			'offset', 'result', 'color', 'direction', 'overflow', 'enable-background',
			'writing-mode', 'word-spacing', 'glyph-orientation-horizontal', 'glyph-orientation-vertical',
			'kerning', 'letter-spacing', 'alignment-baseline', 'baseline-shift', 'dominant-baseline',
			'text-decoration', 'unicode-bidi', 'color-interpolation', 'color-interpolation-filters',
			'color-profile', 'color-rendering', 'flood-color', 'flood-opacity', 'lighting-color',
			'marker-height', 'marker-units', 'marker-width', 'maskContentUnits', 'maskUnits', 
			'patternContentUnits', 'patternTransform', 'patternUnits', 'spreadMethod', 'stop-color', 
			'stop-opacity', 'transform', 'vector-effect', 'viewBox', 'points'
		);

		// Load the SVG into a DOMDocument.
		$dom = new DOMDocument();
		libxml_use_internal_errors( true );
		$dom->loadXML( $svg, LIBXML_NOENT | LIBXML_DTDLOAD | LIBXML_DTDATTR );
		libxml_clear_errors();

		// Remove disallowed elements and attributes.
		$elements = $dom->getElementsByTagName('*');
		for ( $i = $elements->length - 1; $i >= 0; $i-- ) {
			$element = $elements->item( $i );
			if ( ! in_array( $element->nodeName, $allowed_elements ) ) {
				$element->parentNode->removeChild( $element );
			} else {
				// Remove disallowed attributes.
				foreach ( iterator_to_array( $element->attributes ) as $attribute ) {
					if ( ! in_array( $attribute->nodeName, $allowed_attributes ) ) {
						$element->removeAttribute( $attribute->nodeName );
					}
				}
			}
		}

		// Save the sanitized SVG.
		return $dom->saveXML();
	}
}
