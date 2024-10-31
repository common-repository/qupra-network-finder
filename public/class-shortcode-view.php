<?php

/**
 * shortcode
 */
class mra_shortcode_rendering{
	public function __construct(){
		// Add shortcodes here
		add_shortcode('qupra_search', array($this, 'qupra_search_view'));

	}

	public function qupra_search_view(){
		// wp_enqueue_style("mra_qupra_bootstrap", 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css', array(), NEXTPERTISE_NETWORK_FINDER_VERSION);
		// wp_enqueue_script("mra_qupra_bootstrap_jquery", 'https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js', array(), NEXTPERTISE_NETWORK_FINDER_VERSION);
		// wp_enqueue_script("mra_qupra_bootstrap_popper", 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js', array('mra_qupra_bootstrap_jquery'), NEXTPERTISE_NETWORK_FINDER_VERSION);
		// wp_enqueue_script("mra_qupra_bootstrap_js", 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', array('mra_qupra_bootstrap_jquery'), NEXTPERTISE_NETWORK_FINDER_VERSION);
		$settings = array('next_api_key', 'company_name', 'email', 'container_color', 'font_color', 'button_color','button_border_color', 'container_border_color');
		$client = get_option(OPTION_PREFIX."qupra_api_user");
		$dataSaved = array();
		foreach ($settings as $setting) {
			$dataSaved[$setting] = get_option(OPTION_PREFIX.$setting);
		}
		$container_color = isset($dataSaved['container_color']) ? $dataSaved['container_color'] : "";
		$button_color = isset($dataSaved['button_color']) ? $dataSaved['button_color'] : "";
		$font_color = isset($dataSaved['font_color']) ? $dataSaved['font_color'] : "";
		$company_name = isset($dataSaved['company_name']) ? $dataSaved['company_name'] : "";
		$button_border_color = isset($dataSaved['button_border_color']) ? $dataSaved['button_border_color'] : "";
		$container_border_color = isset($dataSaved['container_border_color']) ? $dataSaved['container_border_color'] : "";
		ob_start();
		if ( $client && isset($client->api_v) && $client->api_v == "v3" ){
			$pricings = get_option(OPTION_PREFIX."network_pricings");
			$displayPrice = get_option(OPTION_PREFIX.'display_pricings');
			include "partials/search-result-template-v3.php";
		} else {
			// include "partials/nextpertise-network-finder-public-display.php";
			include "partials/search-result-template.php";
		}
		$data = ob_get_clean();

		return $data;
	}

}

//init
$shortcodes = new mra_shortcode_rendering();