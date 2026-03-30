<?php 
/**
 * @author Hieu "Jin" Phan Trung
 * * Template: Single product - Product details section
 */
dump(get_fields(get_the_ID()));
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