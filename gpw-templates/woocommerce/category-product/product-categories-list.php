<?php 
/**
 * @author Hieu "Jin" Phan Trung
 * * Template: Product category page - Categories list
 */
$shopPageID = wc_get_page_id( 'shop' );
$chosenCategories = get_field('category_to_display', $shopPageID );
if( empty( $chosenCategories ) ) {
  do_action('qm/debug', 'No categories selected to display in product category page, please select at least one category in Shop page settings!');
  return;
}
foreach( $chosenCategories as $chooseCatData ) :
  $catID = $chooseCatData['choose_category'] ?? 0;
  if( empty( $catID ) ) {
    continue;
  }
  $cat = get_term( $chooseCatData['choose_category'], 'product_cat' );
  $productCount = $cat->count;
  $catLink = get_term_link( $catID );
  $title = $chooseCatData['title'] ?? $cat->name;
  $products = get_posts([
    'post_type' => 'product',
    'post_status' => 'publish',
    'posts_per_page' => 10,
    'tax_query' => [
      [
        'taxonomy' => 'product_cat',
        'field' => 'term_id',
        'terms' => $catID,
      ]
    ],
  ]);
  if( empty( $products ) ) {
    continue;
  }
  $slideItems = [];
  foreach( $products as $post ) {
    setup_postdata( $post );
    ob_start();
    wc_get_template_part( 'content', 'product' );
    $slideItems[] = ob_get_clean();
  }
  wp_reset_postdata();
?>
<section class="gpw-prd-cat <?= esc_attr( $cat->slug ) ?>">
  <div class="section__inner">
    <header class="gpw-prd-cat__header">
      <h2 class="section__title"><?= esc_html( $title ) ?></h2>
      <a href="<?= esc_url( $catLink ) ?>" class="gpw-prd-cat__view-all"><?= sprintf( __('Xem tất cả (%d)', 'gpw'), $productCount ) ?></a>
    </header>
    <main class="gpw-prd-cat__carousel">
      <?php get_template_part( 'gpw-templates/global/swiper-template', null, [ 'slide_items' => $slideItems, 'has_nav' => 'true' ]) ?>
    </main>
  </div>
</section>
<?php endforeach;