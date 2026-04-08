<?php 
/**
 * @author Hieu "Jin" Phan Trung
 * * Template: Single product - Product details section
 */
$navItems = [
  [
    'id' => 'gpw-summary',
    'label' => __('Tổng quan', 'gpw'),
    'template_name' => 'highlights-block',
  ],
  [
    'id' => 'gpw-services',
    'label' => __('Các gói dịch vụ', 'gpw'),
    'template_name' => 'product-form-block',
  ],
  [
    'id' => 'gpw-description',
    'label' => __('Về dịch vụ này', 'gpw'),
    'template_name' => 'description-block',
  ],
  [
    'id' => 'gpw-reviews',
    'label' => __('Đánh giá', 'gpw'),
    'template_name' => 'reviews-block',
  ]
]
?>
<section class="product-details">
  <div class="section__inner">
    <nav class="product-details__nav product-details__nav--hidden" aria-label="<?= __('Điều hướng chi tiết sản phẩm', 'gpw') ?>">
      <ul class="product-details__nav-list">

      <?php foreach( $navItems as $idx => $navItem ) : ?>

        <li class="product-details__nav-item<?= $idx === 0 ? ' product-details__nav-item--active' : '' ?>" aria-controls="<?= esc_attr( $navItem['id'] ) ?>"><?= esc_html( $navItem['label'] ) ?></li>

      <?php endforeach ?>

      </ul>
    </nav>
    <main class="product-details__main">

      <?php foreach ( $navItems as $navItem ) {
        get_template_part( "gpw-templates/woocommerce/single-product/product-details/{$navItem['template_name']}", null, ['section_id' => $navItem['id']] );
      } ?>

    </main>
    <aside class="product-details__sidebar">

      <?php get_template_part( 'gpw-templates/woocommerce/single-product/product-details/sidebar-block', null, [ 'move_to' => $navItems[1]['id'] ] ) ?>

    </aside>
  </div>
</section>
<?php 
// ! Cleanup variables
unset($navItems);