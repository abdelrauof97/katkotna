<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'wordpress' );

/** Database password */
define( 'DB_PASSWORD', 'c2dca9ef87cf5187ee96eb177133dbc1511b2987d8f82de4' );

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
define( 'AUTH_KEY',         'J|3dgU.DfFBvng3LQ&>;-34@2%MS(!5!DY&vsU;4HN:D~!N8OnBXT8zuPx~,g2<;' );
define( 'SECURE_AUTH_KEY',  '*rOdKVvyUbXa86QLm8cSm_V97*75l}FN/:&@_EN!IhulTjKIZndEfj,vNI`i^,TQ' );
define( 'LOGGED_IN_KEY',    'f 3|Kh$xk||c9.dtBPZtY78Vpf=@?btBmI&=w+T~j!^7/UdUSF9LBHC-  H|9^Pf' );
define( 'NONCE_KEY',        'j=2,C4PMt0iI]f!;z|>GilMQ dAKNm%A0Co8ay*P@SL]arTt#*>d&R6GT*$^P$qX' );
define( 'AUTH_SALT',        'B7&-eF@9QGtX]u=-1%#zxOzbqob6R`xY2_xoRVm `5R&D0}-*34V36({bMOs*+QC' );
define( 'SECURE_AUTH_SALT', 'iphoVPyA4#^f-bIav1sMww~~N21r5-XQ[;W&b% Y!`NWJy`=u-2LZZLLhmguJ:[`' );
define( 'LOGGED_IN_SALT',   'QDP=Ll`kB}~cY;Jjr16y+Ljx*$w?-^|XleeD^r_$j><9+#cidL%tWFZuFnn8v6Pv' );
define( 'NONCE_SALT',       '`bsr^(U}oSM5Ui.i9VB9NYsF3mF2P?4s`3DS(zZHC7QHG5w%1K?T1&WVKRo(^8~y' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
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

