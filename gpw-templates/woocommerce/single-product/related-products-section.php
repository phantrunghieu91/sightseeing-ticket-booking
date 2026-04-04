<?php 
/**
 * @author Hieu "Jin" Phan Trung
 * * Template: Single product - Related products section
 */
$relatedProducts = wc_get_related_products(get_the_ID(), 8);
if( empty($relatedProducts) ) {
  do_action('qm/debug', 'No related products found for product ID: ' . get_the_ID());
  return;
}
$slideItems = [];
foreach( $relatedProducts as $post ) {
  setup_postdata($post);
  ob_start();
  wc_get_template_part('content', 'product');
  $slideItems[] = ob_get_clean();
}
wp_reset_postdata();
?>
<section class="related-products">
  <div class="section__inner">
    <h3 class="section__title section__title--dot-front"><?= __('Bạn có thể sẽ thích', 'gpw') ?></h3>
    <div class="related-products__carousel">
      <?php get_template_part( 'gpw-templates/global/swiper-template', null, [ 'slide_items' => $slideItems, 'has_nav' => true, 'has_pagination' => true ]) ?>
    </div>
  </div>
</section>
<?php 
// ! Cleanup variables
unset($relatedProducts, $slideItems);