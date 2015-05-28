<?php
/*
This is a sample wp-local-config.php file
In it, you *must* include the four main database defines

You may include other settings here that you only want enabled on your local development checkouts
*/

define( 'DB_NAME', 'local-db-name' );
define( 'DB_USER', 'local-db-user' );
define( 'DB_PASSWORD', 'local-db-password' );
define( 'DB_HOST', 'localhost' );

define( 'WP_DEBUG', 1 );
define( 'SAVEQUERIES', 1 );

# Using the loc. subdomain turns out to be very useful in an autocomplete world.
define('WP_HOME', 'http://loc.superinteractive.com');
define('WP_SITEURL', 'http://loc.superinteractive.com/wp'); // Please don't forget the /wp at the end!