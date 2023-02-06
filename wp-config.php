<?php
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
define( 'DB_NAME', 'u452192538_multi' );

/** Database username */
define( 'DB_USER', 'u452192538_multi' );

/** Database password */
define( 'DB_PASSWORD', '51D3r41!du57' );

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
define( 'AUTH_KEY',         'igmCADiOi1,+g~)Udx_][*e4`m8X:lgWJagHk^@K^f>Tr%lSR+?&7Vzs2m|h /h-' );
define( 'SECURE_AUTH_KEY',  'PN=tF]Zo(O{fC|y:DC(BQqtJ@ZZyjeCmt.p-UN5.CrvvSK?r4T3iI~7i y*lN]VQ' );
define( 'LOGGED_IN_KEY',    '`B,!ea}hkv`+zGlw7pR(eUZko07F!~:iccMfs3.qik^UK-|b[)Br/B<fV?jVc4z~' );
define( 'NONCE_KEY',        '~oE/lp],n{?gj6A0f}~0xbOVR2,t{g=3m>*kzfgV<OQClf85H`(;{=+yv0U%&rpV' );
define( 'AUTH_SALT',        'M_G!_y0x]>9}W6@e_YI[nK1=^iwuF-{t3qWP*fX@GdY^h?pRQQ gfRmu:s};Te2D' );
define( 'SECURE_AUTH_SALT', '7~?C]iL)IT:X{D?C6Fx$.7ZMuml=Gl /F=QzlGsn=[~QOrm1?4#,ER]JtLdA~HXx' );
define( 'LOGGED_IN_SALT',   'Jj~XO:/}G.]?9ts$%IOrqhqv3JIypKxBY.L`3$(Z<A(t i/dY?p.SW6x4L/d5:l^' );
define( 'NONCE_SALT',       '{lw`rv!w=N;<=A1Y[P51:X>Dns3n l/77KH=y0rOyn&UMVwO)Ih$(2v@CQ-%6gy9' );

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

define( 'MULTISITE', true );
define( 'SUBDOMAIN_INSTALL', true );
define( 'DOMAIN_CURRENT_SITE', 'multilnx.com' );
define( 'PATH_CURRENT_SITE', '/' );
define( 'SITE_ID_CURRENT_SITE', 1 );
define( 'BLOG_ID_CURRENT_SITE', 1 );
define( 'SUNRISE', true);

/* That's all, stop editing! Happy publishing. */

/* Multisite */
define( 'WP_ALLOW_MULTISITE', true );

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
