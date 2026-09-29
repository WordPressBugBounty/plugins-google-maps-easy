<?php if (!empty($this->maps)) : foreach ($this->maps as $map) :
  $id = (int) $map['id'];
  $editUrl = $this->getModule()->getEditMapLink($id);
  $shortcode = '[google_map_easy id="' . $id . '"]';
  $phpCode = '<?php echo do_shortcode(\'' . $shortcode . '\'); ?>';
  $markerCount = (int) $map['marker_count'];
  $markerTitles = !empty($map['marker_preview']) ? implode(', ', $map['marker_preview']) : '';
?>
<tr id="gmp-map-<?php echo $id; ?>">
  <td class="gmp-col-check"><input type="checkbox" id="gmp-check-<?php echo $id; ?>" class="gmp-row-checkbox gmp-native-check" aria-label="<?php echo esc_attr(sprintf(__('Select map: %s', GMP_LANG_CODE), $map['title'])); ?>"></td>
  <td class="gmp-col-id"><?php echo $id; ?></td>
  <td class="gmp-col-title"><a href="<?php echo esc_url($editUrl); ?>" title="<?php echo esc_attr(sprintf(__('Edit map: %s', GMP_LANG_CODE), $map['title'])); ?>"><?php echo esc_html($map['title']); ?></a></td>
  <td class="gmp-col-markers"><span class="gmp-marker-count"><?php echo $markerCount; ?></span><?php if ($markerTitles !== '') : ?><span class="gmp-marker-preview" title="<?php echo esc_attr($markerTitles); ?>"><?php echo esc_html($markerTitles); ?></span><?php endif; ?></td>
  <td class="gmp-col-created"><?php echo esc_html($map['created_label'] ?: '—'); ?></td>
  <td class="gmp-col-code"><input type="text" readonly class="gmp-copy-code" value="<?php echo esc_attr($shortcode); ?>" aria-label="<?php esc_attr_e('Shortcode', GMP_LANG_CODE); ?>"></td>
  <td class="gmp-col-code"><input type="text" readonly class="gmp-copy-code" value="<?php echo esc_attr($phpCode); ?>" aria-label="<?php esc_attr_e('PHP code', GMP_LANG_CODE); ?>"></td>
  <td class="gmp-col-actions">
    <a class="button gmp-row-action" href="<?php echo esc_url($editUrl); ?>"><i class="fa fa-pencil" aria-hidden="true"></i> <?php esc_html_e('Edit', GMP_LANG_CODE); ?></a>
    <button type="button" class="button gmp-row-action gmp-clone-map" id="gmp-clone-<?php echo $id; ?>" title="<?php esc_attr_e('Clone', GMP_LANG_CODE); ?>" aria-label="<?php echo esc_attr(sprintf(__('Clone map: %s', GMP_LANG_CODE), $map['title'])); ?>"><i class="fa fa-clone" aria-hidden="true"></i></button>
    <button type="button" class="button gmp-row-action gmp-delete-map" id="gmp-delete-<?php echo $id; ?>" title="<?php esc_attr_e('Delete', GMP_LANG_CODE); ?>" aria-label="<?php echo esc_attr(sprintf(__('Delete map: %s', GMP_LANG_CODE), $map['title'])); ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
  </td>
</tr>
<?php endforeach; else : ?>
<tr class="gmp-empty-row"><td colspan="8"><i class="fa fa-map-marker" aria-hidden="true"></i><strong><?php esc_html_e('No maps found', GMP_LANG_CODE); ?></strong><span><?php esc_html_e('Try another search or create a new map.', GMP_LANG_CODE); ?></span></td></tr>
<?php endif; ?>
