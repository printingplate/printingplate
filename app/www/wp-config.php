<?php
// =========================================
// Load Composer autoload for extra packages
// =========================================
require_once( dirname( dirname( dirname( __FILE__ ) ) ) . '/vendor/autoload.php' );

if( class_exists( 'Dotenv\Dotenv' ) ) {
  $dotenv = new Dotenv\Dotenv( dirname( dirname( dirname( __FILE__ ) ) ) );
  $dotenv->load();
}
else {
  die( "Please include vlucas/phpdotenv" );
}

// ===================================================
// Load database info and local development parameters
// ===================================================
if ( file_exists( dirname( __FILE__ ) . '/wp-local-config.php' ) ) {
  include( dirname( __FILE__ ) . '/wp-local-config.php' );
}
elseif( isset( $_SERVER['SUPER_ENV'] ) ) {

  define( 'DB_NAME', $_SERVER['SUPER_DBNAME'] );
  define( 'DB_USER', $_SERVER['SUPER_DBUSER'] );
  define( 'DB_PASSWORD', $_SERVER['SUPER_DBPASS'] );
  define( 'DB_HOST', $_SERVER['SUPER_DBHOST'] );

  define( 'WP_DEBUG', $_SERVER['SUPER_DEBUG'] );
  define( 'SAVEQUERIES', $_SERVER['SUPER_SAVEQUERIES'] );

  define( 'WP_HOME', $_SERVER['SUPER_HOME'] );
  define( 'WP_SITEURL', $_SERVER['SUPER_SITEURL'] ); // Please don't forget the /wp at the end!

}
else {

  // Environment definition is mandatory
  die( "Env config not found." );
}

// ========================
// Custom Content Directory
// ========================
define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/content' );
define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/content' );


// ================================================
// You almost certainly do not want to change these
// ================================================
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

// ==============================================================
// Salts, for security
// Grab these from: https://api.wordpress.org/secret-key/1.1/salt
// ==============================================================
define('AUTH_KEY',         '%q~lW|f0?80w/)[Aa`]>X!Z!?]!#dHWb .4W)hGS3SdA_1AU4BZ_4x+Wo.gp+{r0');
define('SECURE_AUTH_KEY',  '@h|^yZCaOrdVKmf+3~+[v`vAQk8eE$Hfx/1-8kkGkaeX~;dXv$%umh[e=vU,`,E3');
define('LOGGED_IN_KEY',    '>vTTFE6>s VGb(&:jg#y^_`vuyD]M*wR&Efnj;hUCm|-,6PLL6cYdem,l5dP6:/Z');
define('NONCE_KEY',        'P5bXwkO4{OhYLQ.>aH4Cap70,Ft%{r,hb9E|SfyHGCas->e7+|KnY*}zWz-5;4*N');
define('AUTH_SALT',        'al?^^-*Tk`3X00)3qDFg@F/#Z{#=^dztMh6|J(ow<W!:(s#QO_[7a:11.&1pnvL`');
define('SECURE_AUTH_SALT', '_mZ<im yY@;d`CGd=tHfI<U=<b?rk>Z fNvu8S2/gI~giS@,g3_~M#(2bPr^*!kH');
define('LOGGED_IN_SALT',   '1_lj^B40[vEfXL;QJh2/!L+h(%YngKg} fPpTrUknRVc=X=Ka:l5){-z|MD}/trp');
define('NONCE_SALT',       '&X$Uq/U.wGMW-#0[qXg]@rl*25@!2}GTVs4O? ow?NK[|61fb.qX=9Hc}0l?WMd&');

// ==============================================================
// Table prefix
// Change this if you have multiple installs in the same database
// ==============================================================
$table_prefix  = 'super_';

// ===========
// Miscellaneous settings
// =========== 
 
// ===========
// Don't use automatic updates
// ===========
define( 'WP_AUTO_UPDATE_CORE', false );
define( 'AUTOMATIC_UPDATER_DISABLED', true );

// We won't be needing the theme editor
define( 'DISALLOW_FILE_EDIT', $_SERVER['SUPER_DISALLOW_FILE_EDIT'] );

// ===================
// Bootstrap WordPress
// ===================
if ( !defined( 'ABSPATH' ) )
  define( 'ABSPATH', dirname( __FILE__ ) . '/wp/' );
require_once( ABSPATH . 'wp-settings.php' );
