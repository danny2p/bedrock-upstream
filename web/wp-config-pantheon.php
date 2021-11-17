<?php
/**
 * Set root path
 */
use Roots\WPConfig\Config;

/**
 * Pantheon platform settings.
 *
 * IMPORTANT NOTE:
 * Do not modify this file. This file is maintained by Pantheon.
 *
 * Site-specific modifications belong in wp-config.php, not this file. This
 * file may change in future releases and modifications would cause conflicts
 * when attempting to apply upstream updates.
 */

// ** MySQL settings - included in the Pantheon Environment ** //
/** The name of the database for WordPress */
Config::define('DB_NAME', $_ENV['DB_NAME']);

/** MySQL database username */
Config::define('DB_USER', $_ENV['DB_USER']);

/** MySQL database password */
Config::define('DB_PASSWORD', $_ENV['DB_PASSWORD']);

/** MySQL hostname; on Pantheon this includes a specific port number. */
Config::define('DB_HOST', $_ENV['DB_HOST'] . ':' . $_ENV['DB_PORT']);

/** Database Charset to use in creating database tables. */
Config::define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
Config::define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Changing these will force all users to have to log in again.
 * Pantheon sets these values for you. If you want to shuffle them you must
 * contact support: https://pantheon.io/docs/getting-support
 *
 * @since 2.6.0
 */
Config::define('AUTH_KEY', $_ENV['AUTH_KEY']);
Config::define('SECURE_AUTH_KEY', $_ENV['SECURE_AUTH_KEY']);
Config::define('LOGGED_IN_KEY', $_ENV['LOGGED_IN_KEY']);
Config::define('NONCE_KEY', $_ENV['NONCE_KEY']);
Config::define('AUTH_SALT', $_ENV['AUTH_SALT']);
Config::define('SECURE_AUTH_SALT', $_ENV['SECURE_AUTH_SALT']);
Config::define('LOGGED_IN_SALT', $_ENV['LOGGED_IN_SALT']);
Config::define('NONCE_SALT', $_ENV['NONCE_SALT']);
/**#@-*/

/** A couple extra tweaks to help things run well on Pantheon. **/
if ( isset( $_SERVER['HTTP_HOST'] ) ) {
    // HTTP is still the default scheme for now.
    $scheme = 'http';
    // If we have detected that the end use is HTTPS, make sure we pass that
    // through here, so <img> tags and the like don't generate mixed-mode
    // content warnings.
    if ( isset( $_SERVER['HTTP_USER_AGENT_HTTPS'] ) && $_SERVER['HTTP_USER_AGENT_HTTPS'] == 'ON' ) {
        $scheme = 'https';
    }
    Config::define( 'WP_HOME', $scheme . '://' . $_SERVER['HTTP_HOST'] );
    Config::define( 'WP_SITEURL', $scheme . '://' . $_SERVER['HTTP_HOST'] . '/wp' );

}

// Don't show deprecations; useful under PHP 5.5
error_reporting(E_ALL ^ E_DEPRECATED);
/** Define appropriate location for default tmp directory on Pantheon */
define('WP_TEMP_DIR', $_SERVER['HOME'] .'/tmp');

// FS writes aren't permitted in test or live, so we should let WordPress know to
// disable relevant UI.
if (in_array($_ENV['PANTHEON_ENVIRONMENT'], array( 'test', 'live' ) ) ) {
    if ( ! defined('DISALLOW_FILE_MODS') ) {
        Config::define( 'DISALLOW_FILE_MODS', true );
    }
    if ( ! defined('DISALLOW_FILE_EDIT') ) {
        Config::define( 'DISALLOW_FILE_EDIT', true );
    }	
}

/**
 * Set WP_ENVIRONMENT_TYPE according to the Pantheon Environment
 */
if (getenv('WP_ENVIRONMENT_TYPE') === false) {
    switch ($_ENV['PANTHEON_ENVIRONMENT']) {
        case 'live':
            putenv('WP_ENVIRONMENT_TYPE=production');
            putenv('WP_ENV=production');
            break;
        case 'test':
            putenv('WP_ENVIRONMENT_TYPE=staging');
            putenv('WP_ENV=staging');
            break;
        default:
            putenv('WP_ENVIRONMENT_TYPE=development');
            putenv('WP_ENV=development');
            break;
    }
}

/**
 * Custom Settings
 */
Config::define('AUTOMATIC_UPDATER_DISABLED', true);
Config::define('DISABLE_WP_CRON', getenv('DISABLE_WP_CRON') ?: false);
// Disable the plugin and theme file editor in the admin
Config::define('DISALLOW_FILE_EDIT', true);
// Disable plugin and theme updates and installation from the admin
Config::define('DISALLOW_FILE_MODS', true);
// Limit the number of post revisions that Wordpress stores (true (default WP): store every revision)
Config::define('WP_POST_REVISIONS', getenv('WP_POST_REVISIONS') ?: true);

/**
 * URLs
 */
Config::define('WP_HOME', getenv('WP_HOME'));
Config::define('WP_SITEURL', getenv('WP_SITEURL'));


/**
 * Allow WordPress to detect HTTPS when used behind a reverse proxy or a load balancer
 * See https://codex.wordpress.org/Function_Reference/is_ssl#Notes
 */
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}
/**
 * Force SSL
 */
if ( ! defined('FORCE_SSL_ADMIN') ) {
    define( 'FORCE_SSL_ADMIN', true );
}
