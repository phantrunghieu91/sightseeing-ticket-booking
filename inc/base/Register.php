<?php
/**
 * @author Hieu "Jin" Phan Trung
 ** The Register class handles the registration of scripts, styles, and shortcodes for the theme.
 */

namespace gpweb\inc\base;

class Register extends BaseController {
  /**
   ** An array of shortcodes.
   * @var array
   */
  protected $shortcodes;
  protected $module_scripts = [];
  /**
   * Registers the necessary actions and filters.
   */
  public function register() {
    add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    add_action('wp_enqueue_scripts', [$this, 'setTypeForModuleScripts']);
    // Add AOS init script in the header
    add_action('wp_footer', function() {
      echo '<script> AOS.init(); </script>';
    });
    $this->setShortcodes();
    add_action('init', [$this, 'registerShortcodes']);
  }

  /**
   * Sets the shortcodes.
   */
  protected function setShortcodes() {
    $this->shortcodes = [];
  }
  
  /**
   * Enqueues the necessary scripts and styles.
   */
  public function enqueue() {
    $this->enqueueScript('aos', null);
    $this->enqueueStyle('aos', null);

    $this->enqueueStyle('theme-init', time());
    $this->enqueueStyle('google-symbols', null, 'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200');

    $this->enqueueStyle('gpw-footer', time());

    // * Enqueue swiper for page that needs it
    if( is_front_page() ) {
      $this->enqueueScript('swiper');
      $this->enqueueStyle('swiper');
    }

    if( is_singular( 'product' ) ) {
      $this->enqueueScript('gpw-product-single-page', time());
      $this->enqueueStyle('gpw-product-single-page', time());
    }
  }
  public function setTypeForModuleScripts() {
    if( empty( $this->module_scripts ) ) {
      return;
    }
    add_filter('script_loader_tag', function( $tag, $handle, $src ) {
      if( in_array( $handle, $this->module_scripts ) ) {
        $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
      }
      return $tag;
    }, 10, 3);
  }
  /**
   ** Enqueue single script 
   */
  protected function enqueueScript(string $script_name, ?string $version = null, bool $is_module = false, string $url = '', array $dependencies = [], bool $in_footer = true)
  {
    if( $is_module ) {
      $this->module_scripts[] = $script_name;
    }
    $url = $url === '' ? "{$this->theme_url}/assets/js/{$script_name}.min.js" : $url;
    wp_enqueue_script($script_name, $url, $dependencies, $version, $in_footer);
  }

  /**
   ** Enqueue single style
   */
  public function enqueueStyle(string $style_name, ?string $version = null, string $url = '', array $dependencies = [], string $media = 'all') {
    $url = $url === '' ? "{$this->theme_url}/assets/css/{$style_name}.min.css" : $url;
    wp_enqueue_style($style_name, $url, $dependencies, $version, $media);
  }

  /**
   * * Registers the shortcodes.
   * * Loops through the shortcodes array and registers each shortcode.
   */
  public function registerShortcodes() {
    foreach( $this->shortcodes as $shortcode ) {
      add_shortcode($shortcode->getShortcodeName(), [$shortcode, 'shortcodeCallback']);
    }
  }
}