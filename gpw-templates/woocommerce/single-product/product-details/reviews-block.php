<?php 
/**
 * @author Hieu "Jin" Phan Trung
 * * Template: Single product - product details - reviews block
 */
if( !comments_open(  ) ) {
  do_action( 'qm/debug', 'Comments are closed for this product.' );
  return;
}
$sectionID = isset($args['section_id']) ? $args['section_id'] : 'gpw-reviews';
?>
<div class="product-details__reviews" id="<?= esc_attr( $sectionID ) ?>">
  <?php comments_template() ?>
</div>