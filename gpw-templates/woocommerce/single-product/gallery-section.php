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
<section class="gpw-gallery">
  <div class="section__inner">
    <div class="swiper">
      <div class="swiper-wrapper">
        <?php foreach( array_slice($image_ids, 0, 7) as $idx => $image_id ) : 
          $full_size_url = wp_get_attachment_image_url( $image_id, 'full' );  
        ?>

          <a href="<?= esc_url( $full_size_url) ?>" class="swiper-slide" data-fancybox="hotel-gallery">
            <?= wp_get_attachment_image( $image_id, 'medium', false, [ 'class' => 'gpw-gallery__image', 'alt' => 'Hotel image' ]) ?>
          </a>

        <?php endforeach ?>
        <?php if( count( $image_ids ) > 7 ) : ?>
          <div class="gpw-gallery__total-display">
            <span>+<?= esc_html( count( $image_ids )) ?></span>
            <span class="material-symbols-outlined">image</span>
          </div>
        <?php endif; ?>
      </div>
      <a class="gpw-nav-btn gpw-nav-btn__prev" role="button" aria-label="Previous slide">
        <span class="material-symbols-outlined">chevron_left</span>
      </a>
      <a class="gpw-nav-btn gpw-nav-btn__next" role="button" aria-label="Next slide">
        <span class="material-symbols-outlined">chevron_right</span>
      </a>
      <div class="gpw-pagination"></div>
    </div>
    <?php if( count($image_ids) > 7 ) : ?>
      <div class="gpw-gallery__remain-imgs">
        <?php foreach( array_slice($image_ids, 7) as $img_id ) {
          $full_size_url = wp_get_attachment_image_url( $img_id, 'full' );  
          echo sprintf('<a href="%s" data-fancybox="hotel-gallery">%s</a>',
          esc_url( $full_size_url ),
          wp_get_attachment_image( $img_id, 'medium', false, [ 'class' => 'gpw-gallery__remain-imgs--image', 'alt' => 'Hotel image' ] )
          );
        } ?>
      </div>
    <?php endif; ?>
  </div>
</section>
<?php 
// ! Cleanup variables
unset( $thumbnail_id, $image_ids );