<?php 
/**
 * @author Hieu "Jin" Phan Trung
 * * Shortcode: Header contact button
 * * Usage: [header_contact_btn]
*/
namespace gpweb\shortcodes;
class HeaderContactButton extends BaseShortcode {
  protected function getData() {
    $response = wp_remote_get('https://disanhoian.com/wp-json/gpw/v1/company_info', [
      'headers' => [
        'x-api-key' => 'GPW_KEY_23_TCV'
      ]
    ]);
    return json_decode( wp_remote_retrieve_body( $response ), true );
  }
  public function shortcodeCallback(array $atts, $content = null){
    $data = $this->getData();
    if( empty( $data ) || !isset( $data['phone'] )) {
      return;
    }
    $phone = $data['phone'];
    $link = sprintf('tel:%s', preg_replace('/\s+/', '', $phone) );
    return sprintf('<a href="%s" class="header__contact">
      <span class="material-symbols-outlined header__contact-icon">call</span>
      <span class="header__contact-phone">%s</span>
      <span class="header__contact-text">%s</span>
    </a>', esc_url($link), esc_html($phone), __('Cần hỗ trợ?', 'gpw') );
  }
}