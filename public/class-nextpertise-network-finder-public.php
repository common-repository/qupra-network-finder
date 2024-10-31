<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.fiverr.com/mrabro
 * @since      1.0.0
 *
 * @package    Nextpertise_Network_Finder
 * @subpackage Nextpertise_Network_Finder/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Nextpertise_Network_Finder
 * @subpackage Nextpertise_Network_Finder/public
 * @author     Rafi Abro <mrabro8@gmail.com >
 */
class Nextpertise_Network_Finder_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Nextpertise_Network_Finder_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nextpertise_Network_Finder_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nextpertise-network-finder-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Nextpertise_Network_Finder_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Nextpertise_Network_Finder_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/nextpertise-network-finder-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, "admin", array("ajax"=>admin_url( 'admin-ajax.php' ), "base_url" => get_site_url()));

		wp_enqueue_script( $this->plugin_name."mra_form_underscore",  site_url().'/wp-includes/js/underscore.min.js', array('jquery'), $this->version);
		wp_enqueue_script( $this->plugin_name."mra_form_wp_utils",  site_url().'/wp-includes/js/wp-util.min.js', array('jquery'), $this->version);
		wp_enqueue_script( $this->plugin_name."mra_form_templatify",  plugins_url("js/templetify.js", __FILE__), array('jquery', $this->plugin_name."mra_form_underscore", $this->plugin_name."mra_form_wp_utils"), $this->version);
		$client = get_option(OPTION_PREFIX."qupra_api_user");
		$js_file_path = plugins_url("js/search-result.js", __FILE__);
		if ( $client && isset($client->api_v) && $client->api_v == "v3" ){
			$js_file_path = plugins_url("js/search-result-v3.js", __FILE__);
		}
		wp_enqueue_script( $this->plugin_name."mra_template",  $js_file_path, array('jquery',$this->plugin_name."mra_form_templatify", $this->plugin_name."mra_form_wp_utils", $this->plugin_name."mra_form_underscore"), $this->version);
		if ( $client && isset($client->api_v) && $client->api_v == "v3" ){
			$pricings = get_option(OPTION_PREFIX."network_pricings");
			$v3_settings = ['price_calculation', 'pricing_margin'];
			$dataSaved = array();
			foreach ($v3_settings as $setting) {
				$dataSaved[$setting] = get_option(OPTION_PREFIX.$setting);
			}
			$dataSaved['manual'] = json_encode($pricings);
			wp_localize_script( $this->plugin_name."mra_template", "pricings", $dataSaved);
		}
	}

}
