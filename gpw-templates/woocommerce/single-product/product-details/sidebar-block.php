<?php 
/**
 * @author Hieu "Jin" Phan Trung
 * * Template: Single product - product details sidebar
 */
global $product;
$price = $product->get_price();
$moveToSectionID = isset($args['move_to']) ? $args['move_to'] : '';
?>
<div class="product-sidebar__price">
  <?= $product->get_price_html() ?>
  
  <?php if( !empty( $moveToSectionID )) {
    get_template_part( 'gpw-templates/global/gpw-button', null, [ 'style' => 'primary', 'url' => "#{$moveToSectionID}", 'label' => __('Chọn các gói dịch vụ', 'gpw')]);
  } ?>
</div>