<?php
/**
 * @package GiapPhapWeb_Theme
 * @author Hieu "Jin" Phan Trung
 * * Utilities Class
 */
namespace gpweb\inc\base;

use NumberFormatter;
class Utilities
{
  public static function getUrl($linkData)
  {
    $type = $linkData['type'];

    if($type === 'none') {
      return false;
    }
    
    $data = $linkData[$type];
    $url = '';

    switch ($type) {
      case 'custom':
        $url = $data;
        break;
      case 'post':
      case 'page':
      case 'product':
        $url = get_permalink($data);
        break;
      case 'category':
      case 'product_cat':
        $url = get_term_link($data, 'product_cat');
        break;
    }
    if( $url instanceof \WP_Error ) {
      $url = '';
    }
    $return = !empty($url) && $url != '' ? esc_url($url) : ($linkData['label'] != '' ? 'javascript:void(0);' : false);
    return $return;
  }
  public static function renderResponseImage( $images, $class = '' ) {
    if( empty( $images['desktop'] ) ) {
      return '';
    }
    $desktopSrc = wp_get_attachment_image_url( $images['desktop'], 'full' );
    $mobileSrc = wp_get_attachment_image_url( $images['mobile'], 'full' );

    return sprintf('<picture class="gpw-response-image %s">
      <source media="(min-width:850px)" srcset="%s">
      %s
      <img src="%s" decoding="async" />
    </picture>',
      esc_attr( $class ),
      esc_url( $desktopSrc ),
      $mobileSrc ? sprintf('<source media="(max-width:849px)" srcset="%s">', esc_url( $mobileSrc )) : '',
      esc_url( $desktopSrc )
    );
  }
  public static function currencyDisplay( $amount, $locate = 'en_US' ) {
    if( !is_numeric( $amount ) ) {
      return false;
    }
    $fmt = new NumberFormatter( $locate, NumberFormatter::CURRENCY );
    $amount = floatval( $amount );
    return $fmt->formatCurrency( $amount, 'USD' );
  }
  public static function renderYoutubeEmbed( $youtubeLink, $autoplay = false, $class = '' ) {
    if( empty( $youtubeLink ) ) {
      return '';
    }
    // Split the URL to get the video ID
    $youtubeLinkParts = parse_url( $youtubeLink );
    if( !isset( $youtubeLinkParts['host'] )) {
      return '';
    }
    $youtubeVideoID = match( $youtubeLinkParts['host'] ) {
      'youtu.be' => ltrim( $youtubeLinkParts['path'], '/' ),
      'www.youtube.com', 'youtube.com' => isset( $youtubeLinkParts['query'] )
      ? (function($query) {
          parse_str($query, $params);
          return $params['v'] ?? '';
        })($youtubeLinkParts['query'])
      : '',
      default => ''
    };
    if( empty( $youtubeVideoID ) ) {
      return '';
    }
    $embedUrl = "https://www.youtube.com/embed/{$youtubeVideoID}";
    echo sprintf('<div class="youtube-embed%s">
      <iframe src="%s" title="YouTube video player" frameborder="0" allow="accelerometer;%s clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
    </div>', $class == '' ? '' : " $class", esc_url( $embedUrl . ($autoplay ? "?autoplay=1&mute=1&controls=0&loop=1&playlist={$youtubeVideoID}" : '') ), $autoplay ? ' autoplay;' : '' );
  }
  public static function renderVideoBlock( $videoData, $class = '', $autoplay = true ) {
    if( (!is_array($videoData) && empty($videoData)) || (is_array($videoData) && empty($videoData['id'])) ) {
      return '';
    }
    $videoId = is_array($videoData) ? $videoData['ID'] : $videoData;
    $videoSrc = is_array($videoData) ? $videoData['url'] : wp_get_attachment_url( $videoId );
    $videoType = is_array($videoData) ? $videoData['mime_type'] : get_post_mime_type( $videoId );
    
    if( empty( $videoSrc ) ) {
      return '';
    }
    echo sprintf(
      '<video class="gpw-video-block %s" muted playsinline%s>
          <source src="%s" type="%s">
          Your browser does not support the video tag.
        </video>', 
      esc_attr( $class ),
      $autoplay ? ' autoplay' : '',
      esc_url( $videoSrc ),
      esc_attr( $videoType )
    );
  }
}