<?php
/**
 * @author Hieu "Jin" Phan Trung
 * * Template: Single Product - Header
 */
global $product;
$address = get_field('address', get_the_ID());
$displayData = get_field('display_data', get_the_ID());
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
      <?php if( isset($displayData['review_stars']) && !empty($displayData['review_stars']) ) : ?>
        <li class="product-header__meta-item product-header__meta-item--review-stars">
          <strong><?= esc_html( $displayData['review_stars'] ) ?></strong>
          <span>/</span>
          <span>5</span>
        </li>
      <?php endif; ?>
      <?php if( isset($displayData['review_count']) && !empty($displayData['review_count']) ) : ?>
        <li class="product-header__meta-item product-header__meta-item--review-count">
          <span><?= esc_html( $displayData['review_count'] ) ?> <?php _e('đánh giá', 'gpw') ?></span>
        </li>
      <?php endif; ?>
      <?php if( isset($displayData['ordered_number']) && !empty($displayData['ordered_number']) ) : ?>
        <li class="product-header__meta-item product-header__meta-item--ordered-number">
          <span><?= esc_html( $displayData['ordered_number'] ) ?> <?php _e('đã đặt', 'gpw') ?></span>
        </li>
      <?php endif; ?>
    </ul>
  </div>
</header>
<?php
// ! Cleanup variables
unset($address, $displayData);