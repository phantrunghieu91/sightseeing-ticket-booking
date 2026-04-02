<?php
/**
 * @author Hieu "Jin" Phan Trung
 * * Template: Single product - product details highlights block
 */
$highlights = get_field('highlights', get_the_ID());
if (!isset($highlights) || empty($highlights)) {
  do_action('qm/debug', 'No highlights found for product details highlights block template');
  return;
}
$sectionID = isset($args['section_id']) ? $args['section_id'] : 'gpw-summary';
?>
<div class="product-details__highlights" id="<?= esc_attr( $sectionID) ?>">
  <div class="product-details__highlights-content"><?= wp_kses_post($highlights) ?></div>
  <a href="javascript:void(0);" class="product-details__highlights-toggle">
    <span><?php _e('Xem thêm', 'gpw') ?></span>
    <span class="material-symbols-outlined">chevron_right</span>
  </a>
</div>
<?php 
// ! Cleanup variables
unset($highlights, $sectionID);