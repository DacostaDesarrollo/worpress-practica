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
define('DB_NAME', 'wordpress_db');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '12345');

/** MySQL hostname */
define('DB_HOST', '67.207.90.189');

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
define('AUTH_KEY',         'qsxuPfs&lFFs/ =K[Oz]ab9!I<[bG-GLZG8_1rkv_~d2^]]$X5d=Ly+O=;rC7*&)');
define('SECURE_AUTH_KEY',  'Z%l2>_fP5FaN)M~p5Q]TAZ}nqhV9pywCS!VM]}JJ>TNBCDV1>:L7o=&g[qd=3L/e');
define('LOGGED_IN_KEY',    '<?9*gR4h]qKWTw2vCw;gHm$[HePDVYygvx6.,`F&kp_uGli%^lK~X)bmOS;ukQwh');
define('NONCE_KEY',        'E.Me)Uvxn9;uU~MS@DC`J5,~v-/g@WRZByd3@I6&NvW_b](NSO^no6|,mjIs~KIx');
define('AUTH_SALT',        'jsxp#^&1uzUTA|}^KM9EF}a2xxJ8TpY ;%QUA!rkiAk$wK~5.9z>5R/|`>9%)Bm#');
define('SECURE_AUTH_SALT', 'cx3Q%mOHK{:) !#_S)s:afCQ5gzM6^wYgv[HG:%ukN%dbnBu/}r-yNy}R%*@JB6h');
define('LOGGED_IN_SALT',   '&oYlnB%xpyS>,GWw])d 8dky.<Z[Ijg|G7a+!&o {*1FF!5.X#$EQx$PLOgTHWVJ');
define('NONCE_SALT',       'rWT0,M E]6(vI0X&y!-7f@pn4/Tnk+,dNv7N~uHz Lkt*E<@!p4qw+<7zbI:H`fC');

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
//define( 'WP_HOME', 'http://67.207.90.189/worpress-practica' );
//define( 'WP_SITEURL', 'http://67.207.90.189/worpress-practica' );


if ( defined( 'RELOCATE' ) AND RELOCATE ) {
  // Move flag is set
  if ( isset( $_SERVER['PATH_INFO'] ) AND ($_SERVER['PATH_INFO'] != $_SERVER['PHP_SELF']) )
        $_SERVER['PHP_SELF'] = str_replace( $_SERVER['PATH_INFO'], "", $_SERVER['PHP_SELF'] );
    $url = dirname( set_url_scheme( 'http://'. $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] ) );
    if ( $url != get_option( 'siteurl' ) )
        update_option( 'siteurl', $url );
}

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

// ** FTP CONFIGURACIÓN PARA AUTO-FTP ** //
define('FTP_HOST', 'localhost');
define('FTP_USER', 'NombredeUsuarioFTP');
define('FTP_PASS', 'ContraseñaFTP');
define("FS_METHOD","direct");