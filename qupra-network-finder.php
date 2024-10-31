<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.fiverr.com/mrabro
 * @since             3.1.0
 * @package           Nextpertise_Network_Finder
 *
 * @wordpress-plugin
 * Plugin Name:       Qupra Network Finder
 * Plugin URI:        https://www.fiverr.com/mrabro
 * Description:       Plugin is to serve to locate the network providers via zipcode
 * Version:           3.1.1
 * Author:            Qupra
 * Author URI:        https://qupra.nl/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nextpertise-network-finder
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'NEXTPERTISE_NETWORK_FINDER_VERSION', '3.1.1' );
define('MRA_N_PLUGIN_DIR'		, dirname( __FILE__ ) );
define('MRA_N_PLUGIN_PATH'		, plugin_dir_url(__FILE__) );
define('TEXT_DOMAIN'		, 'mra-nextpertise' );
define('PLUGIN_SLUG' 		, 'qupra_tools');
define('OPTION_PREFIX' 		, 'nextpertise_tool_data_');
define('QP_TABLE' 		, 'qupra_request_quote_records');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-nextpertise-network-finder-activator.php
 */
function activate_nextpertise_network_finder() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nextpertise-network-finder-activator.php';
	Nextpertise_Network_Finder_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-nextpertise-network-finder-deactivator.php
 */
function deactivate_nextpertise_network_finder() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nextpertise-network-finder-deactivator.php';
	Nextpertise_Network_Finder_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_nextpertise_network_finder' );
register_deactivation_hook( __FILE__, 'deactivate_nextpertise_network_finder' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-nextpertise-network-finder.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    3.0.9
 */
function run_nextpertise_network_finder() {

	$plugin = new Nextpertise_Network_Finder();
	$plugin->run();

}
run_nextpertise_network_finder();
