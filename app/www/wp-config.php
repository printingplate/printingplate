<?php
// ===================================================
// Load database info and local development parameters
// ===================================================
if ( file_exists( dirname( __FILE__ ) . '/wp-local-config.php' ) ) {
  define( 'WP_ENV', 'local' );
  include( dirname( __FILE__ ) . '/wp-local-config.php' );
}
elseif ( file_exists( dirname( __FILE__ ) . '/../../env_production' ) ) {

    // Production Environment
    define( 'WP_ENV', 'production' );
    define( 'WP_DEBUG', false );
    define( 'WP_CACHE', true );

    define( 'DB_NAME', 'superdb' );
    define( 'DB_USER', 'superdb' );
    define( 'DB_PASSWORD', 'db_password' );
    define( 'DB_HOST', 'localhost' );

    define( 'WP_HOME', 'http://www.superinteractive.com');
    define( 'WP_SITEURL', 'http://www.superinteractive.com/wp'); // Please don't forget the /wp at the end!

    ini_set('display_errors', 'Off');
    ini_set('display_startup_errors', 'Off');
    error_reporting(0);

}
elseif ( file_exists( dirname( __FILE__ ) . '/../../env_staging' ) ) {

    // Production Environment
    define( 'WP_ENV', 'staging' );
    define( 'WP_DEBUG', false );
    define( 'WP_CACHE', false );

    define( 'DB_NAME', 'superstagingdb' );
    define( 'DB_USER', 'superstagingdb' );
    define( 'DB_PASSWORD', 'db_password' );
    define( 'DB_HOST', 'localhost' );

    define( 'WP_HOME', 'http://staging.superinteractive.com');
    define( 'WP_SITEURL', 'http://staging.superinteractive.com/wp'); // Please don't forget the /wp at the end!

}
else {

    // Environment definition is mandatory
    die("Environment not defined. Please create a wp-local-config.php file or check the settings in wp-config.php");

}

// ========================
// Custom Content Directory
// ========================
define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/content' );
define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/content' );


// =========================================
// Load Composer autoload for extra packages
// =========================================
require_once( dirname( dirname( dirname( __FILE__ ) ) ) . '/vendor/autoload.php' );

// ================================================
// You almost certainly do not want to change these
// ================================================
define( 'DB_CHARSET', 'utf8' );
define( 'DB_COLLATE', '' );

// ==============================================================
// Salts, for security
// Grab these from: https://api.wordpress.org/secret-key/1.1/salt
// ==============================================================
define('AUTH_KEY',         '3L+QJg77L$O6(~-[dgU%3Sf8Hq4@9HYagxlO5z{%{~P.wU<ie, i_~[`F=F#{1Tj');
define('SECURE_AUTH_KEY',  'd|W_+qj*@^k;adsA%zLg8?$+N_ .VIVTBBDI|S_sZb66`IQwTaPzQ6sG=.Hc7Wv0');
define('LOGGED_IN_KEY',    'jQiRtc(XwFdW*{I-C0>He*5?5x$pGO<NiNNTx@Nb<[<z>D_J0YQRS8/RYJ05OC,+');
define('NONCE_KEY',        '|j177(yY@lA]W(F(^;8qhg.dPpCh2#Q[~rtB8{X-O;K%8we$uK1tm0L%I9,|dHZ8');
define('AUTH_SALT',        '2`d#^(@in_t1yV8[kHS7V6h76<CS3-1H;+qXhny+poBHs;,nP|[E8-{t95>*o*zg');
define('SECURE_AUTH_SALT', 'hgO=D@lz~b@+@Af|06NNlWa^/E$=Q<+ATzlF9k:L?>X&Ec=4-295B{pk9-VczGDV');
define('LOGGED_IN_SALT',   'R 29DP#As`v]~dW|XW=XgjH>>| x|F1KCVWSVJL/*_|#V{c-d-p-)CTL.i?|p9-U');
define('NONCE_SALT',       'X<1z.EgT~ne``_IminLx4}1]%<wxx}9M^s(dCUc[z_=y35tyKt0qZMw@Z*IJ1ire');

// ==============================================================
// Table prefix
// Change this if you have multiple installs in the same database
// ==============================================================
$table_prefix  = 'super_';

// ===========
// Miscellaneous settings
// ===========

// We won't be needing the theme editor
# define( 'DISALLOW_FILE_EDIT', true );

// Don't use automatic updates
# define( 'WP_AUTO_UPDATE_CORE', false );
# define( 'AUTOMATIC_UPDATER_DISABLED', true );

// W3TC will otherwise keep nagging because we have changing paths
# define( 'DONOTVERIFY_WP_LOADER', true );



// ===================
// Bootstrap WordPress
// ===================
if ( !defined( 'ABSPATH' ) )
  define( 'ABSPATH', dirname( __FILE__ ) . '/wp/' );
require_once( ABSPATH . 'wp-settings.php' );

