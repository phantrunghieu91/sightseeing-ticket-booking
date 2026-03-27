<?php
/**
 * @package GiapPhapWeb_Theme
 * * Template render swiper slide
 */
$slideItems = $args['slide_items'] ?? [];
$hasNav = $args['has_nav'] ?? false;
$hasPagination = $args['has_pagination'] ?? false;
$hasScrollbar = $args['has_scrollbar'] ?? false;
if( !isset($slideItems) || empty($slideItems)) {
  return;
}
?>
<div class="swiper">
  <div class="swiper-wrapper">
    <?php foreach($slideItems as $slideItem) : ?>
      <div class="swiper-slide">
        <?= $slideItem ?>
      </div>
    <?php endforeach; ?>
  </div>
  <?php if($hasNav) : ?>
    <a class="gpw-nav-btn gpw-nav-btn__prev" role="button" aria-label="Previous slide">
      <span class="material-symbols-outlined">chevron_left</span>
    </a>
    <a class="gpw-nav-btn gpw-nav-btn__next" role="button" aria-label="Next slide">
      <span class="material-symbols-outlined">chevron_right</span>
    </a>
  <?php endif; ?>
  <?php if($hasPagination) : ?>
    <div class="gpw-pagination"></div>
  <?php endif; ?>
  <?php if($hasScrollbar) : ?>
    <div class="swiper-scrollbar"></div>
  <?php endif; ?>
</div>