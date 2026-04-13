<?php 
/**
 * @author Hieu "Jin" Phan Trung
 * * Template: Product category - Header
 */
$currentObj = get_queried_object();
$shopPageID = wc_get_page_id( 'shop' );
if( is_a( $currentObj, 'WP_Post_Type' ) ) {
  $headerData = get_field('header', $shopPageID);
  $title = $headerData['title'] ?? get_the_title( $shopPageID );
  $description = $headerData['description'] ?? '';
  $bgImgID = $headerData['bg_img_id'] ?? '';
} else {
  $title = $currentObj->name;
  $description = $currentObj->description;
  $bgImgID = get_field('bg_img_id', $currentObj);
}
$shortDesc = wp_trim_words( $description, 100, '...' );
?>
<header class="product-cat-header">
  <div class="product-cat-header__bg">
    <?php if( !empty( $bgImgID )) {
      echo wp_get_attachment_image( $bgImgID, 'full', false, ['class' => 'product-cat-header__bg-img'] );
    } ?>
  </div>
  <div class="section__inner">
    <h1 class="product-cat-header__title"><?= esc_html( $title ) ?></h1>
    <?php if( $description ) : ?>
      <div class="product-cat-header__description">
        <div class="product-cat-header__description-content"><?= wp_kses_post( $shortDesc ) ?></div>
        <a href="javascript:void(0);" class="product-cat-header__description-toggle" popovertarget="product-cat-description"><?= __('Xem thêm', 'gpw') ?></a>
      </div>
      <div popover="auto" id="product-cat-description" class="product-cat-popover">
        <header class="product-cat-popover__header">
          <button type="button" class="product-cat-popover__close-btn">
            <span class="material-symbols-outlined">close</span>
          </button>
          <h2 class="product-cat-popover__title"><?= esc_html( $title ) ?></h2>
        </header>
        <main class="product-cat-popover__main"><?= wp_kses_post( $description ) ?></main>
      </div>
    <?php endif; ?>
  </div>
</header>
<?php