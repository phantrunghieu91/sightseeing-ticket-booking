<?php 
/**
 * @author Hieu "Jin" Phan Trung
 * * Handles display additional data in Cart and Checkout page
 */
namespace gpweb\inc\woocommerce;
class CartAndCheckoutController {
  private static $instance = null;
  public static function getInstance() {
    if( self::$instance === null ) {
      self::$instance = new CartAndCheckoutController();
    }
    return self::$instance;
  }
  public function register() {
    add_filter( 'woocommerce_get_item_data', [$this, 'displayAdditionalDataInCartAndCheckoutPage'], 10, 2 );
    add_action('woocommerce_checkout_create_order_line_item', [$this,'addAdditionalDataToOrder'], 10, 4);
    add_action('woocommerce_order_item_meta_start', [$this, 'displayAdditionalDataInOrder'], 10, 4);
    add_filter('woocommerce_order_item_get_formatted_meta_data', [$this, 'removeOrderItemMetaOnThankYouPage'], 10, 2);
  }
  public function displayAdditionalDataInCartAndCheckoutPage( $itemData, $cartItem ) {
    if( !isset( $cartItem['booking_date'] ) || empty( $cartItem['booking_date'] ) ) {
      return $itemData;
    }
    $bookingDate = \DateTime::createFromFormat('Y-m-d', $cartItem['booking_date']);
    if( ! $bookingDate ) {
      return $itemData;
    }
    $itemData[] = [
      'key' => __('Ngày đặt', 'gpw'),
      'value' => $bookingDate->format('d/m/Y'),
    ];
    return $itemData;
  }
  public function addAdditionalDataToOrder( $item, $cartItemKey, $values, $order ) {
    if( isset( $values['booking_date'] ) && !empty( $values['booking_date'] ) ) {
      $item->add_meta_data( 'booking_date', $values['booking_date'] );
    }
  }
  public function displayAdditionalDataInOrder( $itemId, $item, $order, $plainText ) {
    $bookingDate = $item->get_meta( 'booking_date' );
    if( ! $bookingDate ) {
      return;
    }
    $bookingDateObj = \DateTime::createFromFormat('Y-m-d', $bookingDate);
    if( ! $bookingDateObj ) {
      return;
    }
    echo sprintf( '<p><strong>%s:</strong> %s</p>', __('Ngày đặt', 'gpw'), $bookingDateObj->format('d/m/Y') );
  }
  public function removeOrderItemMetaOnThankYouPage( $formattedMeta, $item ) {
    if( is_wc_endpoint_url( 'order-received' ) ) {
      foreach( $formattedMeta as $key => $meta ) {
        if( $meta->key === 'booking_date' ) {
          unset( $formattedMeta[$key] );
        }
      }
    }
    return $formattedMeta;
  }
}