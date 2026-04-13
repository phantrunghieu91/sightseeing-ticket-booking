<?php
/**
 * @author Hieu "Jin" Phan Trung
 * * Controller to override some hooks from Flatsome and WooCommerce
 */
namespace gpweb\inc\woocommerce;
class OverrideFlatsomeAndWoocommerceHooks
{
  private static $instance = null;
  public static function getInstance()
  {
    if (self::$instance === null) {
      self::$instance = new self();
    }
    return self::$instance;
  }
  public function register()
  {
    add_action('wp', [$this, 'removeFlatsomeHeaderInShopAndProductCategoryPage']);
    add_filter( 'woocommerce_get_price_html', [ $this, 'changePriceDisplay' ], 10, 2 );
    add_action( 'woocommerce_shop_loop_item_title', [ $this, 'displayTagsInProductLoop' ], 40, 1 );
  }
  public function changePriceDisplay( $price, $product ) {
    if( $price === '' ) {
      return __('Liên hệ', 'gpw');
    }
    return sprintf('<span class="price-prefix">%s</span> %s', __('Từ', 'gpw'), $price );
  }
  public function removeFlatsomeHeaderInShopAndProductCategoryPage()
  {
    remove_action('flatsome_after_header', 'flatsome_category_header');
    remove_action('flatsome_after_header', 'flatsome_product_header');
  }
  public function displayTagsInProductLoop( $product ) {
    $tags = get_the_terms( $product->get_id(), 'product_tag' );
    if( empty($tags) || is_wp_error($tags) ) {
      return;
    }
    echo '<ul class="product-tags">';
    foreach( $tags as $idx => $tag ) {
      if( $idx >= 2 ) {
        break;
      }
      echo sprintf('<li>%s</li>', $tag->name);
    }
    if( count($tags) > 2 ) {
      echo sprintf('<li class="product-tags__more">%d+</li>', count($tags) - 2);
    }
    echo '</ul>';
  }
}