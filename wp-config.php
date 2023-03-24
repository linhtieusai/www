<?php

 // Added by WP Rocket
	
ini_set('display_errors','Off');
ini_set('error_reporting', E_ALL );
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);


define('WP_LANG', "vi");
// define( 'WP_DEBUG', true );
// define( 'WP_DEBUG_DISPLAY', true );
// define( 'WP_DEBUG_LOG', true );
// define( 'SCRIPT_DEBUG', true );
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'taqnfuuk_caunoitot' );

/** Database username */
define( 'DB_USER', 'taqnfuuk_caunoitot' );

/** Database password */
define( 'DB_PASSWORD', 'Chxhcndtgpt93@@' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'dYoRLauddXyssAkBS5 {i{>3;QsVv4C6VoJ{~8Do$M=YnJd_lt&l_py(70`p-BJQ' );
define( 'SECURE_AUTH_KEY',  'i*_b@y=_{x0NxB,@99zB{f6.e|ILjW P?l%dwm}dWfT81bs((wHj7^0S[BgZy=9C' );
define( 'LOGGED_IN_KEY',    'S5iMYI}gXIsza1|;x8{kP.69u)eo0^y|WCs2vf(`KYFD?-dZt3sn3fibYRp1#$~,' );
define( 'NONCE_KEY',        'gT@6vjI#7fDHg`:ooW?8[A`ED-D`IzPWS>(9OHxyzza=e}5[3jWuo#Hm|`[-<Vz#' );
define( 'AUTH_SALT',        'YX<2+<~p)H#)DUY,~Su^}MHRG23ZRwB||@T_t&kzU$scX}@RqMxdyigV~[3(,y?]' );
define( 'SECURE_AUTH_SALT', 'g%;WA;y+qOM1(Q4S X8O4*TVq70T%P .(Pp,?*NN@oowXd617Y?O:mxbDtqdO*.<' );
define( 'LOGGED_IN_SALT',   '&=~<OV1KH%8cb#LOBde0@F}VmB0w^(l39m]fK*MIbn{ZtC#?0 =U+G0BQ;~T6z/Y' );
define( 'NONCE_SALT',       'S#vP=k^8=%~:?gyRO3=]M;ye+ijDndMN2FnQ_u(&^Fvi8@=!h29.cG# 1,;kgZqw' );
define('DISALLOW_FILE_EDIT', true);
define('DISALLOW_FILE_MODS', true);
/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
