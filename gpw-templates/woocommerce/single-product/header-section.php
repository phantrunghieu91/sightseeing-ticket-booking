<?php
/**
 * @author Hieu "Jin" Phan Trung
 * * Template: Single Product - Header
 */
global $product;
$address = get_field('address', get_the_ID());
$displayData = get_field('display_data', get_the_ID());
$reviewCount = $product->get_review_count();
?>
<header class="product-header">
  <div class="section__inner">
    <h1 class="product-header__title">
      <?= get_the_title() ?>
    </h1>
    <?php if (isset($address['text']) && !empty($address['text'])): ?>
      <div class="product-header__address">
        <span><?= esc_html($address['text']) ?></span>
      </div>
    <?php endif; ?>
    <ul class="product-header__meta-list">
      <?php if( isset($displayData['review_point']) && !empty($displayData['review_point']) ) : ?>
        <li class="product-header__meta-item">
          <span><?= esc_html( $displayData['review_point'] ) ?>/10</span>
        </li>
      <?php endif; ?>
      <?php if( $reviewCount > 0 ) : ?>
        <li class="product-header__meta-item">
          <span><?= esc_html( $reviewCount ) ?> <?php _e('reviews', 'gpw') ?></span>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</header>
<?php
// ! Cleanup variables
unset($address, $displayData);