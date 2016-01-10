<?php

// ===========
// Load environment settings from .env filee
// ===========
if( class_exists( 'Dotenv\Dotenv' ) ) {
  $dotenv = new Dotenv\Dotenv( dirname( dirname( __FILE__ ) ) );
  $dotenv->load();
}
else {
  die( "PrintingPlate needs vlucas/phpdotenv." );
}

// ========================
// Custom Content Directory
// ========================
define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/app' );
define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/app' );


// ===========
// Don't use automatic updates
// ===========
define( 'WP_AUTO_UPDATE_CORE', $_ENV['PP_AUTO_UPDATE_CORE'] );
define( 'AUTOMATIC_UPDATER_DISABLED', $_ENV['PP_AUTOMATIC_UPDATER_DISABLED'] );


// ========================
// Shut down theme editor
// ========================
define( 'DISALLOW_FILE_EDIT', $_ENV['PP_DISALLOW_FILE_EDIT'] );


// ================================================
// You almost certainly do not want to change these
// ================================================
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );
