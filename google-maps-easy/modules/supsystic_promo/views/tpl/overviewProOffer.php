<?php
/**
 * Free vs PRO comparison and PRO pricing from the live Easy Google Maps page on supsystic.com
 * (https://supsystic.com/plugins/google-maps-plugin/, sections gmpMtxSection and gmpPrsSection, 2026-11-15), shown on
 * the Overview while PRO is not active. Without the download links; the Buy links are absolute. The plugin pages pass
 * their content through wp_kses, which takes out SVG, so this is printed in the footer (supsystic_promoGmp::
 * printProOffer) and js/admin.design.js moves it into #gmp-pro-offer-slot. Styles: css/admin.pro-offer.css, script:
 * js/admin.pro-offer.js. When the prices on the site change, rebuild it from the live page.
 */
?>
<div class="gmp-pro-offer" hidden data-gmp-move="#gmp-pro-offer-slot" data-gmp-move-mode="replace">
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false"><defs><symbol id="gmpIconStar" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></symbol><symbol id="gmpIconYes" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></symbol><symbol id="gmpIconNo" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" d="M5 12h14"/></symbol></defs></svg>
<section class="gmpMtxSection" id="gmp-compare">
    <div class="gmpMtxTop">
      <div class="gmpMtxSectionInner">
        <div class="gmpSectionLabel">Free vs Pro</div>
        <h2 class="gmpMtxSectionTitle">Everything in the Box &mdash; and Exactly What Pro Adds</h2>
        <div class="gmpMtxSectionSubtitle">51 capabilities, compared line by line and checked against the plugin itself.</div>
        <div class="gmpMtxScore">
          <div class="gmpMtxPlan">
            <div class="gmpMtxPlanTop">
              <div class="gmpMtxRing">
                <svg viewBox="0 0 92 92" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <circle class="gmpMtxRingTrack" cx="46" cy="46" r="40"></circle>
                  <circle class="gmpMtxRingFill" cx="46" cy="46" r="40"></circle>
                </svg>
                <div class="gmpMtxRingLabel">
                  <span class="gmpMtxRingNum" data-count="26">26</span>
                  <span class="gmpMtxRingOf">of 51</span>
                </div>
              </div>
              <div>
                <div class="gmpMtxPlanName">Free</div>
                <div class="gmpMtxPlanPrice">$0 <span>forever</span></div>
              </div>
            </div>
            <div class="gmpMtxPlanText">Unlimited maps and markers, custom icons, rich marker popups, categories with a filter, clustering, CSV import and export, a Gutenberg block and an Elementor widget &mdash; a complete map for a business site. Nothing expires.</div>
          </div>
          <div class="gmpMtxPlan gmpMtxPlanPro">
            <div class="gmpMtxPlanTop">
              <div class="gmpMtxRing">
                <svg viewBox="0 0 92 92" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <circle class="gmpMtxRingTrack" cx="46" cy="46" r="40"></circle>
                  <circle class="gmpMtxRingFill" cx="46" cy="46" r="40"></circle>
                </svg>
                <div class="gmpMtxRingLabel">
                  <span class="gmpMtxRingNum" data-count="51">51</span>
                  <span class="gmpMtxRingOf">of 51</span>
                </div>
              </div>
              <div>
                <div class="gmpMtxPlanName">Pro</div>
                <div class="gmpMtxPlanPrice">from $49 <span>per year</span></div>
                <div class="gmpMtxPlanPriceAlt"><span>or</span><b>$159</b><span>lifetime</span></div>
              </div>
            </div>
            <div class="gmpMtxPlanText">Directions and routes, 300+ themes, KML, heatmap, shapes, traffic layers, marker sliders and lists, front-end editing and custom controls &mdash; the full toolkit, on every license tier.</div>
            <a href="#gmp-pricing" class="gmpBtnPrimary">
              View License Options
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="gmpMtxBottom">
      <div class="gmpMtxSectionInner">
        <div class="gmpMtxTableWrap">
          <div class="gmpMtxControls">
            <div class="gmpMtxControlsTitle">Full feature comparison</div>
            <div class="gmpMtxFilters" role="group" aria-label="Filter the comparison">
              <button type="button" class="gmpMtxFilter gmpMtxFilterActive" data-gmp-tier-filter="all">All 51</button>
              <button type="button" class="gmpMtxFilter" data-gmp-tier-filter="free">In Free (26)</button>
              <button type="button" class="gmpMtxFilter" data-gmp-tier-filter="pro">Pro only (25)</button>
            </div>
          </div>
          <div class="gmpMtxTable">
            <div class="gmpMtxRow gmpMtxHeadRow">
              <div class="gmpMtxCell gmpMtxCellFeature">Capability</div>
              <div class="gmpMtxCell gmpMtxHeadPlan">
                <strong>Free</strong>
                <span class="gmpMtxHeadPlanSub">wordpress.org</span>
              </div>
              <div class="gmpMtxCell gmpMtxHeadPlan gmpMtxHeadPro">
                <strong>Pro</strong>
                <span class="gmpMtxHeadPlanSub">from $49/yr</span>
                <span class="gmpMtxHeadPlanAlt">or $159 lifetime</span>
              </div>
            </div>

            <div class="gmpMtxGroup" data-gmp-group>
              <div class="gmpMtxGroupName">
                <span class="gmpMtxGroupIcon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg></span>
                Maps &amp; markers
              </div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Unlimited maps</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Unlimited markers per map</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Marker description with text, photos, video, links, phone and e-mail</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Custom marker icons &mdash; built-in library or your own image</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Marker categories with a front-end filter</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Marker clustering</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Linked markers &mdash; open any URL on click</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Address search: type an address, get the pin</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Show a marker only between two dates</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Visitors add markers from the front end</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Front-end map and marker editing</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Center the map on the visitor's location</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>

            <div class="gmpMtxGroup" data-gmp-group>
              <div class="gmpMtxGroupName">
                <span class="gmpMtxGroupIcon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 3.75l6 4.5-6 4.5v-9zm9 0l6 4.5-6 4.5v-9zM4.5 15.75h15M4.5 19.5h15"/></svg></span>
                Marker lists &amp; sliders
              </div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Horizontal and vertical photo sliders under or beside the map</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Description sliders (photo + text cards)</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Exposition slider</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Table view: searchable, sortable list of locations</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>

            <div class="gmpMtxGroup" data-gmp-group>
              <div class="gmpMtxGroupName">
                <span class="gmpMtxGroupIcon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/></svg></span>
                Look &amp; controls
              </div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Map type: roadmap, satellite, hybrid, terrain</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Size, zoom, min/max zoom, scroll-wheel and drag settings</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Responsive map with its own zoom level on mobile</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Map themes</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxVal gmpMtxValMuted">Starter set</span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxVal">300+ themes</span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Custom positions for map, zoom and street-view controls</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Fullscreen button, print button, hidden points of interest</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Hide country names and labels</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Places toolbar &mdash; visitors search points of interest on the map</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Static map image (Maps Static API) for lightweight pages</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>

            <div class="gmpMtxGroup" data-gmp-group>
              <div class="gmpMtxGroupName">
                <span class="gmpMtxGroupIcon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3"/></svg></span>
                Layers &amp; shapes
              </div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">KML layers from a file or URL</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">KML layers filter with sub-layers</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Import KML points as markers</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Traffic, transit and bicycling layers</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Heatmap layer</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Figures: polygons, polylines, circles with fill and description</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Path router &mdash; draw a route that follows the roads</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>

            <div class="gmpMtxGroup" data-gmp-group>
              <div class="gmpMtxGroupName">
                <span class="gmpMtxGroupIcon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg></span>
                Routes &amp; directions
              </div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Get Direction button on every marker</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Route drawn on your map with turn-by-turn steps</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Alternate routes</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Start from the visitor's current position</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>

            <div class="gmpMtxGroup" data-gmp-group>
              <div class="gmpMtxGroupName">
                <span class="gmpMtxGroupIcon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg></span>
                Data in &amp; out
              </div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">CSV import of maps and markers</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">CSV export</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Move maps between sites (export / import)</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>

            <div class="gmpMtxGroup" data-gmp-group>
              <div class="gmpMtxGroupName">
                <span class="gmpMtxGroupIcon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75L22.5 12l-5.25 5.25m-10.5 0L1.5 12l5.25-5.25m7.5-3l-4.5 16.5"/></svg></span>
                Publishing &amp; integration
              </div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Shortcode and PHP snippet</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Gutenberg block</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Elementor widget</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Sidebar widget</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Works with WPBakery, Divi, Beaver Builder and any builder via shortcode</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Your own Google Maps API key, with a filter to rewrite it</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Map locale: language plus a region code &mdash; US, CA, GB, DE&hellip; &mdash; so labels, spelling and biasing match your country</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Google Maps China endpoint (maps.google.cn)</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Translations &mdash; 15 languages</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="pro">
              <div class="gmpMtxCell gmpMtxCellFeature">Social share buttons on the map</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxNo"><svg><use href="#gmpIconNo"/></svg></span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxYes"><svg><use href="#gmpIconYes"/></svg></span></div>
            </div>

            <div class="gmpMtxGroup" data-gmp-group>
              <div class="gmpMtxGroupName">
                <span class="gmpMtxGroupIcon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg></span>
                Support &amp; updates
              </div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Support</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxVal gmpMtxValMuted">Forum</span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxVal">Priority</span></div>
            </div>
            <div class="gmpMtxRow" data-gmp-tier="free">
              <div class="gmpMtxCell gmpMtxCellFeature">Updates and guarantee</div>
              <div class="gmpMtxCell gmpMtxCellFree"><span class="gmpMtxVal gmpMtxValMuted">Free updates</span></div>
              <div class="gmpMtxCell gmpMtxCellPro"><span class="gmpMtxVal">Pro updates + 30-day refund</span></div>
            </div>

            <div class="gmpMtxEmpty" hidden>No rows match this filter.</div>
            <div class="gmpMtxRow gmpMtxFootRow">
              <div class="gmpMtxCell gmpMtxCellFeature">Start free, upgrade when a Pro row is the one you need.</div>
              <div class="gmpMtxCell gmpMtxCellFree">
                </div>
              <div class="gmpMtxCell gmpMtxCellPro">
                <a href="#gmp-pricing" class="gmpBtnPrimary">Get Pro &rarr;</a>
              </div>
            </div>
          </div>
        </div>
        <div class="gmpMtxNote">
          Every Pro license unlocks <b>all 51 capabilities</b> &mdash; tiers differ only in how many sites they cover.<br>Pro is a superset of Free: nothing you build on the free version has to be rebuilt after upgrading.
        </div>
      </div>
    </div>
  </section>

  <!-- ══════════ PRICING ══════════ -->
  <section class="gmpPrsSection" id="gmp-pricing">
    <div class="gmpPrsInner">
      
    <div class="supPricing" id="sptPricing1">

  <div class="sptHead">
    <div class="sptEyebrow">Licensing</div>
    <h2 class="sptTitle">Choose your licence and get started</h2>
    <p class="sptSubtitle">Flexible options for individuals, businesses and agencies.</p>
  </div>

  <div class="sptTabs" role="tablist" aria-label="Licence">
        <button type="button" class="sptTab is-active" role="tab"
      id="sptPricing1-tab-single"
      aria-controls="sptPricing1-panel-single"
      aria-selected="true"
      data-licence="single">
      <span class="sptTabIcon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/></svg></span>
      <span>
        <span class="sptTabName">Personal</span>
        <span class="sptTabSites">1 site</span>
      </span>
    </button>
        <button type="button" class="sptTab" role="tab"
      id="sptPricing1-tab-dev"
      aria-controls="sptPricing1-panel-dev"
      aria-selected="false"
      data-licence="dev">
      <span class="sptTabIcon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="3.2"/><circle cx="4.8" cy="10" r="2.3"/><circle cx="19.2" cy="10" r="2.3"/><path d="M6.5 20c0-3.3 2.5-5.5 5.5-5.5s5.5 2.2 5.5 5.5"/><path d="M1.2 19c0-2.4 1.6-4 3.8-4"/><path d="M22.8 19c0-2.4-1.6-4-3.8-4"/></svg></span>
      <span>
        <span class="sptTabName">Multi-site</span>
        <span class="sptTabSites">Up to 5 sites</span>
      </span>
    </button>
        <button type="button" class="sptTab" role="tab"
      id="sptPricing1-tab-enterprise"
      aria-controls="sptPricing1-panel-enterprise"
      aria-selected="false"
      data-licence="enterprise">
      <span class="sptTabIcon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3.5" y="3" width="10.5" height="18" rx="1"/><path d="M14 9h5.5a1 1 0 0 1 1 1v11H14"/><path d="M6.5 7h1.5M10 7h1.5M6.5 11h1.5M10 11h1.5M6.5 15h1.5M10 15h1.5M16.5 13h1.5M16.5 17h1.5"/><path d="M1.5 21h21"/></svg></span>
      <span>
        <span class="sptTabName">Agency</span>
        <span class="sptTabSites">Unlimited sites</span>
      </span>
    </button>
      </div>
  <p class="sptHint">Choose your licence first, then your term.</p>

    <div class="sptPanel" role="tabpanel" data-licence="single"
    id="sptPricing1-panel-single"
    aria-labelledby="sptPricing1-tab-single">
    <div class="sptGrid">

      <div class="sptCard sptTermYear">
        <div class="sptCardHead">1 Year</div>
        <div class="sptPrice">
                    <div class="sptAmountRow">
            <span class="sptAmount"><span class="sptCur">$</span>49</span>
            <span class="sptPer">/ year</span>
          </div>
                  </div>
        <div class="sptFeatures" role="list">
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>1 Site License</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>All Pro Features</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Premium Support</div>
        </div>
        <div class="sptCta">
          <a class="sptBtn" href="https://supsystic.com/?mod=user&amp;addQty=1&amp;goto=checkout&amp;lang=en&amp;action=addToCart&amp;pid=185">Buy Now</a>
          <div class="sptGuarantee">30-Day Money Back Guarantee</div>
        </div>
      </div>

      <div class="sptCard sptTermThree">
        <div class="sptCardHead">3 Years</div>
        <div class="sptPrice">
                    <div class="sptAmountRow">
            <span class="sptAmount"><span class="sptCur">$</span>109</span>
            <span class="sptPer">/ 3 years</span>
          </div>
                  </div>
        <div class="sptFeatures" role="list">
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>1 Site License</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>All Pro Features</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Premium Support</div>
        </div>
        <div class="sptCta">
          <a class="sptBtn" href="https://supsystic.com/?mod=user&amp;addQty=1&amp;goto=checkout&amp;lang=en&amp;action=addToCart&amp;pid=22724">Buy Now</a>
          <div class="sptGuarantee">30-Day Money Back Guarantee</div>
        </div>
      </div>

            <div class="sptCard sptTermLife">
        <div class="sptCardHead">Lifetime</div>
        <div class="sptPrice">
          <div class="sptAmountRow">
            <span class="sptAmount"><span class="sptCur">$</span>159</span>
          </div>
          <div class="sptNote">Pay once, use forever</div>
        </div>
        <div class="sptFeatures" role="list">
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>1 Site License</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>All Pro Features</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Premium Support</div>
        </div>
        <div class="sptCta">
          <a class="sptBtn" href="https://supsystic.com/?mod=user&amp;addQty=1&amp;goto=checkout&amp;lang=en&amp;action=addToCart&amp;pid=24164">Buy Now</a>
          <div class="sptGuarantee">30-Day Money Back Guarantee</div>
        </div>
      </div>
      
    </div>
  </div>
    <div class="sptPanel" role="tabpanel" data-licence="dev"
    id="sptPricing1-panel-dev"
    aria-labelledby="sptPricing1-tab-dev" hidden>
    <div class="sptGrid">

      <div class="sptCard sptTermYear">
        <div class="sptCardHead">1 Year</div>
        <div class="sptPrice">
          <span class="sptBadge"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M2 12.6V4a2 2 0 0 1 2-2h8.6a2 2 0 0 1 1.4.6l8.4 8.4a2 2 0 0 1 0 2.8l-8.6 8.6a2 2 0 0 1-2.8 0L2.6 14A2 2 0 0 1 2 12.6zM7.5 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/></svg>Introductory price</span>          <div class="sptAmountRow">
            <span class="sptAmount"><span class="sptCur">$</span>99</span>
            <span class="sptPer">/ year</span>
          </div>
                  </div>
        <div class="sptFeatures" role="list">
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Up to 5 Sites</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>All Pro Features</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Premium Support</div>
        </div>
        <div class="sptCta">
          <a class="sptBtn" href="https://supsystic.com/?mod=user&amp;addQty=1&amp;goto=checkout&amp;lang=en&amp;action=addToCart&amp;pid=1649">Buy Now</a>
          <div class="sptGuarantee">30-Day Money Back Guarantee</div>
        </div>
      </div>

      <div class="sptCard sptTermThree">
        <div class="sptCardHead">3 Years</div>
        <div class="sptPrice">
          <span class="sptBadge"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 2h10v2h4v3a5 5 0 0 1-5 5h-.4A5 5 0 0 1 13 14.9V18h3v2.5H8V18h3v-3.1A5 5 0 0 1 8.4 12H8a5 5 0 0 1-5-5V4h4V2zM5 6v1a3 3 0 0 0 2 2.8V6H5zm12 0v3.8A3 3 0 0 0 19 7V6h-2z"/></svg>Best long-term value</span>          <div class="sptAmountRow">
            <span class="sptAmount"><span class="sptCur">$</span>239</span>
            <span class="sptPer">/ 3 years</span>
          </div>
          <div class="sptNote">That's only $79/year</div>        </div>
        <div class="sptFeatures" role="list">
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Up to 5 Sites</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>All Pro Features</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Premium Support</div>
        </div>
        <div class="sptCta">
          <a class="sptBtn" href="https://supsystic.com/?mod=user&amp;addQty=1&amp;goto=checkout&amp;lang=en&amp;action=addToCart&amp;pid=22725">Buy Now</a>
          <div class="sptGuarantee">30-Day Money Back Guarantee</div>
        </div>
      </div>

            <div class="sptCard sptTermLife">
        <div class="sptCardHead">Lifetime</div>
        <div class="sptPrice">
          <div class="sptAmountRow">
            <span class="sptAmount"><span class="sptCur">$</span>299</span>
          </div>
          <div class="sptNote">Pay once, use forever</div>
        </div>
        <div class="sptFeatures" role="list">
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Up to 5 Sites</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>All Pro Features</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Premium Support</div>
        </div>
        <div class="sptCta">
          <a class="sptBtn" href="https://supsystic.com/?mod=user&amp;addQty=1&amp;goto=checkout&amp;lang=en&amp;action=addToCart&amp;pid=24165">Buy Now</a>
          <div class="sptGuarantee">30-Day Money Back Guarantee</div>
        </div>
      </div>
      
    </div>
  </div>
    <div class="sptPanel" role="tabpanel" data-licence="enterprise"
    id="sptPricing1-panel-enterprise"
    aria-labelledby="sptPricing1-tab-enterprise" hidden>
    <div class="sptGrid">

      <div class="sptCard sptTermYear">
        <div class="sptCardHead">1 Year</div>
        <div class="sptPrice">
          <span class="sptBadge"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M2 12.6V4a2 2 0 0 1 2-2h8.6a2 2 0 0 1 1.4.6l8.4 8.4a2 2 0 0 1 0 2.8l-8.6 8.6a2 2 0 0 1-2.8 0L2.6 14A2 2 0 0 1 2 12.6zM7.5 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/></svg>Introductory price</span>          <div class="sptAmountRow">
            <span class="sptAmount"><span class="sptCur">$</span>199</span>
            <span class="sptPer">/ year</span>
          </div>
                  </div>
        <div class="sptFeatures" role="list">
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Unlimited Sites</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>All Pro Features</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Premium Support</div>
        </div>
        <div class="sptCta">
          <a class="sptBtn" href="https://supsystic.com/?mod=user&amp;addQty=1&amp;goto=checkout&amp;lang=en&amp;action=addToCart&amp;pid=1651">Buy Now</a>
          <div class="sptGuarantee">30-Day Money Back Guarantee</div>
        </div>
      </div>

      <div class="sptCard sptTermThree">
        <div class="sptCardHead">3 Years</div>
        <div class="sptPrice">
          <span class="sptBadge"><svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 2h10v2h4v3a5 5 0 0 1-5 5h-.4A5 5 0 0 1 13 14.9V18h3v2.5H8V18h3v-3.1A5 5 0 0 1 8.4 12H8a5 5 0 0 1-5-5V4h4V2zM5 6v1a3 3 0 0 0 2 2.8V6H5zm12 0v3.8A3 3 0 0 0 19 7V6h-2z"/></svg>Best long-term value</span>          <div class="sptAmountRow">
            <span class="sptAmount"><span class="sptCur">$</span>349</span>
            <span class="sptPer">/ 3 years</span>
          </div>
          <div class="sptNote">That's only $116/year</div>        </div>
        <div class="sptFeatures" role="list">
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Unlimited Sites</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>All Pro Features</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Premium Support</div>
        </div>
        <div class="sptCta">
          <a class="sptBtn" href="https://supsystic.com/?mod=user&amp;addQty=1&amp;goto=checkout&amp;lang=en&amp;action=addToCart&amp;pid=22726">Buy Now</a>
          <div class="sptGuarantee">30-Day Money Back Guarantee</div>
        </div>
      </div>

            <div class="sptCard sptTermLife">
        <div class="sptCardHead">Lifetime</div>
        <div class="sptPrice">
          <div class="sptAmountRow">
            <span class="sptAmount"><span class="sptCur">$</span>449</span>
          </div>
          <div class="sptNote">Pay once, use forever</div>
        </div>
        <div class="sptFeatures" role="list">
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Unlimited Sites</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>All Pro Features</div>
          <div class="sptFeature" role="listitem"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 12.5l5 5L20 6.5"/></svg>Premium Support</div>
        </div>
        <div class="sptCta">
          <a class="sptBtn" href="https://supsystic.com/?mod=user&amp;addQty=1&amp;goto=checkout&amp;lang=en&amp;action=addToCart&amp;pid=24166">Buy Now</a>
          <div class="sptGuarantee">30-Day Money Back Guarantee</div>
        </div>
      </div>
      
    </div>
  </div>
  
  </div>

        <div class="gmpPrsNote">Every tier includes all Pro features, updates and priority support for the license term, and the 30-day money-back guarantee. The free version never expires.</div>
    </div>
  </section>

  <!-- ══════════ FAQ ══════════ -->
</div>
