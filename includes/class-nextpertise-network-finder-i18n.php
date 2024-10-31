<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.fiverr.com/mrabro
 * @since      1.0.0
 *
 * @package    Nextpertise_Network_Finder
 * @subpackage Nextpertise_Network_Finder/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Nextpertise_Network_Finder
 * @subpackage Nextpertise_Network_Finder/includes
 * @author     Rafi Abro <mrabro8@gmail.com >
 */
class Nextpertise_Network_Finder_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'nextpertise-network-finder',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
