<?php 
/**
 * @author Hieu "Jin" Phan Trung
 * * Template: Single product - Product details section
 */
dump(get_fields(get_the_ID()));
?>
<section class="product-details">
  <div class="section__inner">
    <main class="product-details__main">

      <?php get_template_part( 'gpw-templates/woocommerce/single-product/product-details/highlights-block' ); ?>

      <?php get_template_part( 'gpw-templates/woocommerce/single-product/product-details/description-block' ) ?>

    </main>
    <aside class="product-details__sidebar"></aside>
  </div>
</section>
<?php 
// ! Cleanup variables
unset($highlights);