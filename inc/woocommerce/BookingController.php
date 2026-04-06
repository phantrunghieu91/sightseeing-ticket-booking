<?php 
/**
 * @author Hieu "Jin" Phan Trung
 * * Woocommerce Include: Booking controller
 */
namespace gpweb\inc\woocommerce;
class BookingController {
  private static $instance = null;
  private string $action = 'gpw_add_to_cart';
  public static function getInstance() {
    if( self::$instance === null ) {
      self::$instance = new BookingController();
    }
    return self::$instance;
  }
  public function register() {
    add_action("wp_ajax_{$this->action}", [$this, 'handleAddToCart']);
    add_action("wp_ajax_nopriv_{$this->action}", [$this, 'handleAddToCart']);
  } 
  public function handleAddToCart() {
    if( !check_ajax_referer( "{$this->action}_nonce", 'nonce', false ) ) {
      wp_send_json_error( ['message' => __('Có lỗi xảy ra trong quá trình xác thực, vui lòng thử lại sau!', 'gpw')] );
      wp_die();
    }
    $productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $bookingDate = isset($_POST['booking-date']) ? $_POST['booking-date'] : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    if( ! $productId ) {
      wp_send_json_error( ['message' => __('Sản phẩm không tồn tại!', 'gpw')] );
      wp_die();
    }
    $product = wc_get_product( $productId );
    if( ! $product ) {
      wp_send_json_error( ['message' => __('Không tìm thấy sản phẩm!', 'gpw')] );
      wp_die();
    }
    $newBookingDate = \DateTime::createFromFormat('Y-m-d', $bookingDate);
    if( ! $newBookingDate ) {
      wp_send_json_error( ['message' => __('Ngày đặt không hợp lệ!', 'gpw'), 'date' => $bookingDate] );
      wp_die();
    }
    if( !empty(WC()->cart->get_cart()) ) {
      WC()->cart->empty_cart();
    }
    $additionData = [
      'booking_date' => $newBookingDate->format('Y-m-d'),
    ];
    $cartKey = WC()->cart->add_to_cart( $productId, $quantity, 0, [], $additionData );
    if( ! $cartKey ) {
      wp_send_json_error( ['message' => __('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng, vui lòng thử lại sau!', 'gpw')] );
      wp_die();
    }
    wp_send_json_success( ['message' => __('Thêm sản phẩm vào giỏ hàng thành công!', 'gpw')] );
    wp_die();
  }
  public function getAction() {
    return $this->action;
  }
}