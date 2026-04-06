<?php 
/**
 * @author Hieu "Jin" Phan Trung
 * * Template: Single product - product details - Product form
 */
$formAction = 'gpw_add_to_cart';
$sectionID = isset($args['section_id']) ? $args['section_id'] : 'gpw-services';
global $product;
if( ! $product ) return;
$price = $product->get_price();
$tags = get_the_terms( get_the_ID(), 'product_tag' );

?>
<div class="product-form" id="<?= esc_attr( $sectionID ) ?>">
  <h3 class="section__title section__title--dot-front"><?= __('Các gói dịch vụ', 'gpw') ?></h3>
  <div class="product-form__wrapper">
    <?php if( !empty( $tags ) && !is_wp_error( $tags ) ) : ?>
      <ul class="product-form__tags">
        <?php foreach( $tags as $tag ) : ?>
          <li class="product-form__tag"><?= esc_html( $tag->name ) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
    <div class="product-form__price-wrapper">
      <?php if( $price ) {
        echo $product->get_price_html();
      } else {
        echo '<span class="product-form__price">' . __('Liên hệ để biết giá', 'gpw') . '</span>';
      } ?>
    </div>
    <form method="POST" class="product-form__form">
      <input type="hidden" name="product_id" value="<?= esc_attr( $product->get_id() ) ?>">
      <div class="product-form__control-wrapper">
        <label for="booking-date"><?= __('Chọn ngày', 'gpw') ?></label>
        <input type="date" name="booking-date" id="booking-date" required class="product-form__control product-form__control--date">
      </div>
      <div class="product-form__control-wrapper">
        <label for="quantity"><?= __('Số lượng', 'gpw') ?></label>
        <button type="button" class="product-form__quantity-btn product-form__quantity-btn--decrease">
          <span class="material-symbols-outlined">remove</span>
        </button>
        <input type="number" name="quantity" id="quantity" value="1" min="1" required class="product-form__control product-form__control--quantity">
        <button type="button" class="product-form__quantity-btn product-form__quantity-btn--increase">
          <span class="material-symbols-outlined">add</span>
        </button>
      </div>
      <?php get_template_part( 'gpw-templates/global/gpw-button', null, [ 'label' => __('Chọn', 'gpw'), 'tag' => 'button', 'type' => 'submit' ] ) ?>
    </form>
  </div>
</div>