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
define( 'DB_PASSWORD', '803dd3a1db5a4aee7a9152a1b88bac16d030228c38e5f235' );

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
define( 'AUTH_KEY',         'DP/dypr6p(8U2E`$@ZvklBuig:!a;G[.q$*;qB+#@.Wg]|y{=UTRQK6BaU1h|0Gd' );
define( 'SECURE_AUTH_KEY',  'hJh!S@@*FF|y ;BA%p`AI0vrD==bh3GVV)n8P.gXsy5x4(E{f+$Re3$n<Ij*mFeA' );
define( 'LOGGED_IN_KEY',    'Mb/skN(R~ahYC,=J536EIt qaO?&2xuuVt6!0P8~HZ`m[P[/0?l_{yun9/M|^lFR' );
define( 'NONCE_KEY',        '4TwtIbZQl~Pq`(-%K+?7ka9!j#A]g`WFdelh/>RKKFT&m+/S}+d1)wux*jGW,SS@' );
define( 'AUTH_SALT',        'lHsO*2p?t2e>x>AVN;:C%l2na-%NufdFHCfro?@m~c@W08UN599yb K=a9}|KCS1' );
define( 'SECURE_AUTH_SALT', 'Z|K[Z];pW[1*zfKWCgZ*d+?0wEdcY$7}e$OA/Q5vzYL)p9|ejWdP-^B!-/oh4lAD' );
define( 'LOGGED_IN_SALT',   'RtqfZ:J},9VVb6;,Lmb4jZP}=?W5$.|JW{gJksIKXXXZX{}hk{|z[tpH+M|e)9S<' );
define( 'NONCE_SALT',       'jd5XMQyU~XebY0/441wg]IjaVz<(`wryma{)B]@Yny;q(rmW(g:R.z68bK4Y&_!C' );

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
