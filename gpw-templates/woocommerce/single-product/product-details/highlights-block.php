<?php
/**
 * @author Hieu "Jin" Phan Trung
 * * Template: Single product - product details highlights block
 */
$highlights = get_field('highlights', get_the_ID());
if( !isset($highlights) || empty($highlights) ) {
  do_action( 'qm/debug', 'No highlights found for product details highlights block template' );
  return; 
}
?>
<div class="product-details__highlights">
  <?= wp_kses_post($highlights) ?>
</div>