<?php 
/**
 * @author Hieu "Jin" Phan Trung
 * * Template: Single product - Gallery section
 */
global $product;
if( !$product || is_a( $product, 'WP_Error' ) ) {
  do_action( 'qm/debug', 'No product found for gallery section template' );
  return; 
}
$thumbnail_id = $product->get_image_id();
$image_ids = $thumbnail_id ? array_merge( [ intval($thumbnail_id) ], $product->get_gallery_image_ids() ) : [];
?>
<section class="product-gallery">
  <div class="section__inner">
    <div class="swiper">
      <div class="swiper-wrapper">
        <?php foreach( array_slice($image_ids, 0, 7) as $idx => $image_id ) : 
          $full_size_url = wp_get_attachment_image_url( $image_id, 'full' );  
        ?>

          <a href="<?= esc_url( $full_size_url) ?>" class="swiper-slide" data-fancybox="hotel-gallery">
            <?= wp_get_attachment_image( $image_id, 'medium', false, [ 'class' => 'product-gallery__image', 'alt' => 'Hotel image' ]) ?>
          </a>

        <?php endforeach ?>
        <?php if( count( $image_ids ) > 7 ) : ?>
          <div class="product-gallery__total-display">
            <span>+<?= esc_html( count( $image_ids )) ?></span>
            <span class="material-symbols-outlined">image</span>
          </div>
        <?php endif; ?>
      </div>
      <a href="javascript:void(0);" class="carousel-btn carousel-btn__prev" aria-label="Carousel previous slide" role="button">
        <span class="material-symbols-outlined">chevron_left</span>
      </a>
      <a href="javascript:void(0);" class="carousel-btn carousel-btn__next" aria-label="Carousel next slide" role="button">
        <span class="material-symbols-outlined">chevron_right</span>
      </a>
      <div class="swiper-pagination"></div>
    </div>
    <?php if( count($image_ids) > 7 ) : ?>
      <div class="product-gallery__remain-imgs">
        <?php foreach( array_slice($image_ids, 7) as $img_id ) {
          $full_size_url = wp_get_attachment_image_url( $img_id, 'full' );  
          echo sprintf('<a href="%s" data-fancybox="hotel-gallery">%s</a>',
          esc_url( $full_size_url ),
          wp_get_attachment_image( $img_id, 'medium', false, [ 'class' => 'product-gallery__remain-imgs--image', 'alt' => 'Hotel image' ] )
          );
        } ?>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php 
// ! Cleanup variables
unset( $thumbnail_id, $image_ids );