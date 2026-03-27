<?php
defined('ABSPATH') || exit;

// Placeholder image id constant
define('PLACEHOLDER_IMAGE_ID', 10);
define('GPW_TEXT_DOMAIN', 'gpw');

// Turn off auto gen <p> of contact form 7
add_filter('wpcf7_autop_or_not', function() {
  return false;
});

// Load autoload
if(file_exists(__DIR__ . '/vendor/autoload.php')) {
  require_once __DIR__ . '/vendor/autoload.php';
}

// Register services
if(class_exists('gpweb\\inc\\ThemeInit')) {
  gpweb\inc\ThemeInit::register_services();
}