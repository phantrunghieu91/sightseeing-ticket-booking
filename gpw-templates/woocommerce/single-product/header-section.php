<?php
/**
 * @author Hieu "Jin" Phan Trung
 * * Template: Single Product - Header
 */
$address = get_field('address', get_the_ID());
?>
<header class="product-header">
  <div class="section__inner">
    <h1 class="single-product__title">
      <?= get_the_title() ?>
    </h1>
    <?php if (isset($address['text']) && !empty($address['text'])): ?>
      <div class="single-product__address">
        <span><?= esc_html($address['text']) ?></span>
        <?php if (isset($address['google_map_link']) && !empty($address['google_map_link'])): ?>
          <a href="<?= esc_url($address['google_map_link']) ?>" target="_blank">
            <?php esc_html_e('Xem trên bản đồ', 'gpw') ?>
          </a>
        <?php endif; ?>
      </div>
    <?php endif; ?>
  </div>
</header>
<?php
// ! Cleanup variables
unset($address);