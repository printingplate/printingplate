<?php

// ==============================================================
// Salts, for security
// Set in env file, usually by the printingplate cli tool
// ==============================================================
define('AUTH_KEY',          PP_AUTH_KEY);
define('SECURE_AUTH_KEY',   PP_SECURE_AUTH_KEY);
define('LOGGED_IN_KEY',     PP_LOGGED_IN_KEY);
define('NONCE_KEY',         PP_NONCE_KEY);
define('AUTH_SALT',         PP_AUTH_SALT);
define('SECURE_AUTH_SALT',  PP_SECURE_AUTH_SALT);
define('LOGGED_IN_SALT',    PP_LOGGED_IN_SALT);
define('NONCE_SALT',        PP_NONCE_SALT);
