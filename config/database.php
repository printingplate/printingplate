<?php

// ===================================================
// Load database info and local development parameters
// ===================================================
define( 'DB_NAME', $_ENV['PP_DBNAME'] );
define( 'DB_USER', $_ENV['PP_DBUSER'] );
define( 'DB_PASSWORD', $_ENV['PP_DBPASS'] );
define( 'DB_HOST', $_ENV['PP_DBHOST'] );

define( 'WP_DEBUG', $_ENV['PP_DEBUG'] );
define( 'SAVEQUERIES', $_ENV['PP_SAVEQUERIES'] );

define( 'WP_HOME', $_ENV['PP_HOME'] );
define( 'WP_SITEURL', $_ENV['PP_SITEURL'] ); // Please don't forget the /wp at the end!

// ==============================================================
// Table prefix
// Change this if you have multiple installs in the same database
// ==============================================================
$table_prefix  = env('PP_DBPREFIX', 'pp_');
