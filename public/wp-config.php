<?php

// =========================================
// Load Composer autoload for extra packages
// =========================================
require_once( dirname( dirname( __FILE__ ) ) . '/bootstrap/autoload.php' );

// ===================================================
// Load app settings
// ===================================================
require_once( dirname( dirname( __FILE__ ) ) . '/config/app.php' );


// ===================================================
// Load database info and local development parameters
// ===================================================
if( ! isset( $_ENV['PP_ENV'] ) ) {
  die( "Env config not found or PP_ENV not set." );
}


// ===================================================
// Load database info and local development parameters
// ===================================================
require_once( dirname( dirname( __FILE__ ) ) . '/config/database.php' );


// ===================================================
// Load database info and local development parameters
// ===================================================
require_once( dirname( dirname( __FILE__ ) ) . '/config/misc.php' );


// ===================
// Bootstrap WordPress
// ===================
if ( !defined( 'ABSPATH' ) ) {
  define( 'ABSPATH', dirname( __FILE__ ) . '/wp/' );
}

require_once( ABSPATH . 'wp-settings.php' );
