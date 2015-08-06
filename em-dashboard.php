<?php
/**
 * Plugin Name: EM Dashboard
 * Plugin URI: https://wordpress.org/plugins/em-dashboard/
 * Description: Redesigned wp-admin screen with easy mode activation to go back to basics.
 * Version: 1.0.0
 * Author: Sybre Waaijer
 * Author URI: https://cyberwire.nl/
 * Text Domain: emdashboard
 */

/**
 * Add the following line to your wp-config.php to load the space-grey styles:
 *
 * define( 'USE_EM_DASHBOARD_THEME', true);
 */

/**
 * CDN Cache buster.
 * Not many caching use CDN in dashboard. What a shame.
 *
 * @since 1.0.0
 */
if ( !defined( 'EM_PLUGIN_VER' ) )
	define ( 'EM_PLUGIN_VER', '1.0.0' );

/**
 * Le plugin map absolute.
 * Used for calling browser files.
 * Needs refining, do not redefine unless you're absolutely sure what you're doing.
 *
 * @since 1.0.0
 */
if ( !defined( 'EM_DASHBOARD_BASEDIR' ) )
	define ( 'EM_DASHBOARD_BASEDIR', basename( dirname(__FILE__) ) );

/**
 * Le plugin map relative.
 * Used for calling php files.
 * Needs refining, do not redefine unless you're absolutely sure what you're doing.
 *
 * @since 1.0.0
 */
if ( !defined( 'EM_DASHBOARD_LOCALDIR' ) )
	define ( 'EM_DASHBOARD_LOCALDIR', dirname(__FILE__) );

/**
 * Plugin locale 'emdashboard'
 *
 * File located in plugin folder emdashboard/language/
 *
 * @since 1.0.0
 *
 * @uses EM_DASHBOARD_BASEDIR
 * @return void
 */
function em_dashboard_locale() {
	load_plugin_textdomain( 'emdashboard', false, EM_DASHBOARD_BASEDIR . '/language/');
}
add_action('plugins_loaded', 'em_dashboard_locale');

/**
 * Load plugin files
 *
 * @since 1.0.0
 *
 * @uses EM_DASHBOARD_DIR
 */
require_once( EM_DASHBOARD_LOCALDIR . '/load.class.php' );
