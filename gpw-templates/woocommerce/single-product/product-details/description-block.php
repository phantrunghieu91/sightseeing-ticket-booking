<?php 
/**
 * @author Hieu "Jin" Phan Trung
 * * Template: Single product - Product detail - Product description block
 */
global $product;
if( ! $product || !$product->get_description() ) {
  return;
}
?>
<div class="product-details__description">
  <h3 class="section__title section__title--dot-front"><?= __('Về dịch vụ này', 'gpw') ?></h3>
  <div class="product-details__description-content">
    <?= the_content() ?>
  </div>
</div>