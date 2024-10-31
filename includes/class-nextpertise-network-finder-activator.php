<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.fiverr.com/mrabro
 * @since      1.0.0
 *
 * @package    Nextpertise_Network_Finder
 * @subpackage Nextpertise_Network_Finder/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Nextpertise_Network_Finder
 * @subpackage Nextpertise_Network_Finder/includes
 * @author     Rafi Abro <mrabro8@gmail.com >
 */
class Nextpertise_Network_Finder_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		global $wpdb;
		if (!isset($wpdb))
			$wpdb = $GLOBALS['wpdb'];

		$table_name = $wpdb->prefix . QP_TABLE;
		$sql = "DROP TABLE IF EXISTS $table_name";
		$wpdb->query($sql);
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		`id` 			int(11) 	UNSIGNED 	NOT NULL 	AUTO_INCREMENT 		UNIQUE,
		`firstname`		varchar(40) NOT NULL,
		`lastname` 		varchar(40) NOT NULL,
		`email`			varchar(40),
		`phone`  		varchar(40),
		`company_name`	varchar(40),
		`request_type`	varchar(40),
		`request_carrier` varchar(40),
		`request_company` varchar(40),
		`request_provider` varchar(40),
		`request_maxDownload` varchar(40),
		`request_maxUpload` varchar(40),
		`request_area` 	varchar(40),
		`request_distance` varchar(40),
		`request_address` varchar(200),
		`request_date` datetime,
		PRIMARY KEY (`id`))";
		dbDelta( $sql );

		// Alteration
		$column_name = 'action_type';
		$row = $wpdb->get_results("SELECT action_type FROM INFORMATION_SCHEMA.COLUMNS
			WHERE table_name = $table_name AND column_name = $column_name"  );
		if(empty($row)){
			$wpdb->query("ALTER TABLE $table_name ADD $column_name varchar(250)");
		}

	}

}
