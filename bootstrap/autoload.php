<?php

// =========================================
// Load Composer autoload for extra packages
// =========================================
require_once( dirname( dirname( __FILE__ ) ) . '/vendor/autoload.php' );

if ( ! function_exists('env') ) {
  
  function env($key, $default = null) {
      
      $value = getenv($key);

      if((bool)$value == false) {
        return $default;
      }

      switch( strtolower( $value ) ) {
          case 'true':
          case '(true)':
              return true;

          case 'false':
          case '(false)':
              return false;

          case 'empty':
          case '(empty)':
              return '';

          case 'null':
          case '(null)':
              return;
      }

      return $value;
  }
}
