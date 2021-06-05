<?php
define('WP_AUTO_UPDATE_CORE', 'minor');// This setting is required to make sure that WordPress updates can be properly managed in WordPress Toolkit. Remove this line if this WordPress website is not managed by WordPress Toolkit anymore.
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
define( 'DB_NAME', 'pontopraiacom_praia' );

/** MySQL database username */
define( 'DB_USER', 'pontopraiacom_praia' );

/** MySQL database password */
define( 'DB_PASSWORD', 'pontopraiacom_praia' );

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
define( 'AUTH_KEY',         'nantwunuqgk3vxj240olwdqvqan5rlp3xtltvzz6olnjxjykwa6ujlcambwijny5' );
define( 'SECURE_AUTH_KEY',  't3tsabj0uaztx1niyvl8pk11cfrj0bywumckrjurm6vdgxf9tx8aczf6eit0g79w' );
define( 'LOGGED_IN_KEY',    'objenjgqrjvlxlcfrep6getqqsjtbmwopbkvbyedged4t6vwvoctoygjdoyyfwhd' );
define( 'NONCE_KEY',        'd3azvoewwbv4cgrvhhige7ckiuewusuyfkavvwuj1x3et1liioqy3oh3w3ybju0h' );
define( 'AUTH_SALT',        'r2bo2hxlvavltaxkptrhdkazuq2boncyt7rprka0pvznlnitjc8dedahjpxlenmf' );
define( 'SECURE_AUTH_SALT', 'rjhtxuixgnxiuqxxwlrends4otb2jbvyn6mgaco1rmpjeixd3ridxx3sitze7lxa' );
define( 'LOGGED_IN_SALT',   'mov6q4kml2d0vd2pbxjmywp9aspnjqlkr0f9ogzg21jglukmgxcuwyuewtbnq3zf' );
define( 'NONCE_SALT',       'z1gt0ozvjqdc85dmnyclwqwtnpfotszxy9sv9c1sbqys493afhyjovjn5cb6x95p' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp9r_';

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
