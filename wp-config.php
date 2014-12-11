<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'nashville');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'vagrant');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'xK eR?l:pCox@xpQh(.&x<q,}XewJK{>LuKJX#x#^l0Iw)!(arr7H1`(s>f61Gh-');
define('SECURE_AUTH_KEY',  'F1?O0iNGax1$oO2,-_ZL(X7sk10)IvC@t-iwlft|bhr/+YTJ)wcaU2k|B z.v#6z');
define('LOGGED_IN_KEY',    ' Dn>wTd4y_cFze},xrh8H.H|dmaS(|-($U%FqF4YiCq1<$);w6 3Eg_OEm;x(Rz{');
define('NONCE_KEY',        'nQ$PZ.~H=S:V0{r;>DVH1+5vjQm12i=-)d_n~cS+gLK+Z=o2zxdY_ESy-Ts5yZX*');
define('AUTH_SALT',        'gr)%>?XQ?),4eb<n#gHPtIBR_XUQ)g$o5YS54O}@BQU:[T g8>*g<--p/I6(wfw=');
define('SECURE_AUTH_SALT', '6}OCKDt M&J0uI%`49|<yA3<9#.@4_bp}@@7gZ^,ZT#!}bPlytK>A/|?UW73&|zk');
define('LOGGED_IN_SALT',   '@6Q;2M+T/m@-ee86B0V%PZ87Q6f^d63ggM6AtF`P(+WE9?1BT-F>IKhGkY1)+O5Z');
define('NONCE_SALT',       'o{AvK|T4<py[69o)V5X&7[YOOV4T#5NX`qW=Bw$]2|tpu#ZeB$ @oDS!.U2t}e0~');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
    define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
