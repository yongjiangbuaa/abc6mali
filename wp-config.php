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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', '6mali');

/** MySQL database username */
define('DB_USER', 'mali');

/** MySQL database password */
define('DB_PASSWORD', '7pWE0N7kkCMr');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'D6K6i>m*`}vI- R746#M~PQ#SeCO~.N%^B?^zj@_ONXHN8xdKO1r?> G2/Drss*8');
define('SECURE_AUTH_KEY',  'ltM0s9r|#;VFEnE|q$r<6OB!1kpG$^e!3j(}]1>G#1MPiW2?;p?,w6$FBn &!DOn');
define('LOGGED_IN_KEY',    'Dr!|F2:wL@AaF|a~.)Ifb A@Ed`,/7%c$m#FX%4FN(p%gU<* Hz~2nDBtyqL{H#G');
define('NONCE_KEY',        '&F_@csMWCxIh^{}G}@J+VOu78*qJxQ+v7vss<rMW2.%0B,Sj Kxu|%!}W1GsSo =');
define('AUTH_SALT',        '9n*JFPe/v@F=HuQ~Sz0-sG^8}sqL(<9w}}%LQDeS8spIvW3VAui6.3A4{7DZ=Eh(');
define('SECURE_AUTH_SALT', 'e/VbH$N[W),A5~|eTQ1h*{U3sE2(F52llkGd6j+B7 )NnQ@<GjwX@C:B6lGuM+ym');
define('LOGGED_IN_SALT',   'N/z%#jSOo2zWs6(ms|:Fjv@?wan9rQ78!gG)$ s>!8j]vByTT*EW/iGtAV=W@~R:');
define('NONCE_SALT',       '<$z%vQ}FDhLu+g-U`p?kD f{>&)n}?U(.X[De!wxNhNe{HiT`K;]eyg10peH)D&w');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
