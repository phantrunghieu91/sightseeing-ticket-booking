<?php 
/**
 * @author Hieu "Jin" Phan Trung
 * * Template: Single product - More information popup
 */
global $product;
$moreInfo = get_field('more_information', get_The_ID() );
$tags = get_the_terms( get_the_ID(), 'product_tag' );
if( isset( $moreInfo['general_terms'] ) && !empty( $moreInfo['general_terms'] ) ) {
  $generalTerms = [];
  foreach( $moreInfo['general_terms'] as $idx => $term ) {
    $generalTerms[] = [
      'title' => $term['title'],
      'content' => wp_kses_post( $term['content'] ),
    ];
  }
}
if( isset( $moreInfo['user_manual'] ) && !empty( $moreInfo['user_manual'] ) ) {
  $userManual = [];
  foreach( $moreInfo['user_manual'] as $idx => $manual ) {
    $userManual[] = [
      'title' => $manual['title'],
      'content' => wp_kses_post( $manual['content'] ),
    ];
  }
}
?>
<div class="product-details__more-info more-info-popover" popover="auto" id="more-info-popover">
  <header class="more-info-popover__header">
    <strong class="more-info-popover__title"><?= __('Thông tin gói dịch vụ', 'gpw') ?></strong>
    <button type="button" class="more-info-popover__close-btn" popovertargetaction="hide" popovertarget="more-info-popover">
      <span class="material-symbols-outlined">close</span>
    </button>
  </header>
  <main class="more-info-popover__main">
    <h2 class="more-info-popover__product-title"><?= $product->get_title() ?></h2>
    <?php if( !empty( $tags ) ) : ?>
    <ul class="more-info-popover__tags">
      <?php foreach( $tags as $tag ) : 
        $iconID = get_field('icon', $tag );
      ?>
        <li class="more-info-popover__tag">
          <?php if( $iconID ) { echo wp_get_attachment_image( $iconID, 'thumbnail', false, [ 'class' => 'more-info-popover__tag-icon']); } ?>
          <span class="more-info-popover__tag-name"><?= esc_html( $tag->name ) ?></span>
          <?php if( !empty($tag->description) ): ?>
            <div class="more-info-popover__tag-description"><?= wp_kses_post( $tag->description ); ?></div>
          <?php endif ?>
        </li>
      <?php endforeach; ?>
    </ul>
    <?php endif ?>
    <?php if( isset( $moreInfo['product_info'] ) && !empty( $moreInfo['product_info'] ) ) : ?>
      <div class="more-info-popover__info">
        <h3 class="section__title"><?= __('Thông tin sản phẩm', 'gpw') ?></h3>
        <div class="more-info-popover__info-content"><?= wp_kses_post( $moreInfo['product_info'] ) ?></div>
      </div>
    <?php endif ?>
    <?php if( isset( $generalTerms ) && !empty( $generalTerms ) ) : ?>

      <div class="more-info-popover__general-terms">
        <h3 class="section__title"><?= __('Điều khoản chung', 'gpw') ?></h3>
        <?php get_template_part( 'gpw-templates/global/accordion-template', null, [ 'items' => $generalTerms, 'has_icon' => true ] ) ?>
      </div>

    <?php endif ?>
    <?php if( isset( $userManual ) && !empty( $userManual ) ) : ?>

      <div class="more-info-popover__user-manual">
        <h3 class="section__title"><?= __('Hướng dẫn sử dụng', 'gpw') ?></h3>
        <?php get_template_part( 'gpw-templates/global/accordion-template', null, [ 'items' => $userManual, 'has_icon' => true ] ) ?>
      </div>
    <?php endif ?>

  </main>
</div>