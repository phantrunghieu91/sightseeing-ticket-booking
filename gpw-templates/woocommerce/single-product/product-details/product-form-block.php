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
  <?php if( !empty( $tags ) && !is_wp_error( $tags ) ) : ?>
    <ul class="product-form__tags">
      <?php foreach( $tags as $tag ) : ?>
        <li class="product-form__tag"><?= esc_html( $tag->name ) ?></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
  <form method="POST" class="product-form__form">
    <input type="hidden" name="action" value="<?= esc_attr($formAction) ?>">
    <?php wp_nonce_field( $formAction, $formAction . '_nonce' ) ?>
  </form>
</div>