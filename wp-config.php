<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'joco_cups_db' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'md9TB1BZqtI.2?3foD6xs0-/)P6 gqVo)^b?pk/y19H7w=>Zw804(W;SxDwR_o0x' );
define( 'SECURE_AUTH_KEY',  ')`Iq5~^PSYs}/9z@vPt%,52<fN#wPe?I=(9{XXmgT,fwAA]>8zdg/+KXT }Os.h}' );
define( 'LOGGED_IN_KEY',    'i;$YN[q9l[l0 ov**C:0b&|%2aCeFa rCp9ImU35.]iA/P{8(Pl3Sso_qDd0-v k' );
define( 'NONCE_KEY',        'K.u,c5dO:6~eC9<-Wk6&wJ.u|O@mB3,-KYaP/1frwDDjd5.Y4A!GN]HjLAuA%%+G' );
define( 'AUTH_SALT',        'o(IlrNA/vTnS)*#xRMH9l8sC~.&M&|m8vJW$r5)T|({FV$Y <n%;vFE2UR(y!DU7' );
define( 'SECURE_AUTH_SALT', 'm PL7^5w3xKoMAg:YH&0GJZ/oEmI5(H @AYw-K{~24`/j|f-K4UzxY9>GpRAfq=l' );
define( 'LOGGED_IN_SALT',   '-egd4QGCpQKx)-_*j33G9{r|J.5]D`g3GeEVI08n@1B,:E@`f-X-6ZG_:y4[!SQG' );
define( 'NONCE_SALT',       '$;Hb_e7+S.{6kt-B79?.Ad.jaCL|ZX%>/2_;uN4L_~Km*uK)y+iY`7)&m8]ixDca' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
