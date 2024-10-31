<?php

class Nextpertise_API extends WP_REST_Controller{

	protected $namespace;
	protected $rest_base;
	// protected $base_url = "http://localhost:1337/"; //Development
	protected $base_url = "https://api.quprawholesale.com/"; //Production
	protected $lookup = "provider/lookup";
	protected $sendMail = "sendMail";
	protected $verify = "verify";
	protected $getNetworkPricing = "get/network/pricing";


	public function __construct() {
		$this->namespace = 'mra/v1';
		$this->rest_base = 'lookup';
	}

	public function register_routes() {

		register_rest_route( $this->namespace, '/' . $this->rest_base, array(

			array(
				'methods'             => WP_REST_Server::ALLMETHODS,
				'callback'            => array( $this, 'lookup_provider' ),
				 'permission_callback' => array( $this, 'api_permissions_check' ),
			),
			'schema' => null,

		) );

		register_rest_route( $this->namespace, '/' . $this->verify, array(

			array(
				'methods'             => WP_REST_Server::ALLMETHODS,
				'callback'            => array( $this, 'verify_client' ),
			),
			'schema' => null,

		) );

		register_rest_route( $this->namespace, '/' . $this->sendMail, array(

			array(
				'methods'             => WP_REST_Server::ALLMETHODS,
				'callback'            => array( $this, 'sendMail' ),
			),
			'schema' => null,

		) );

	}

	public function sendMail($request){
		$response = array("status" => false, "msg" => "Something went wrong");

		// Insert into db;
		global $wpdb;
		$table_name = $wpdb->prefix . QP_TABLE;
		$data = array(
			"firstname" => $_REQUEST['Voornaam'],
			"lastname" => $_REQUEST['Achternaam'],
			"email" => $_REQUEST['email'],
			"phone" => $_REQUEST['Telefoonnummer'],
			"company_name" => $_REQUEST['Bedrijfsnaam'],
			"request_type" => $_REQUEST['ptype'],
			"request_carrier" => $_REQUEST['carrier'],
			"request_company" => $_REQUEST['company'],
			"request_provider" => $_REQUEST['provider'],
			"request_maxDownload" => $_REQUEST['download'],
			"request_maxUpload" => $_REQUEST['upload'],
			"request_area" => $_REQUEST['area'],
			"request_distance" => $_REQUEST['distance'],
			"request_address" => $_REQUEST['address'],
			"request_date" => date('Y-m-d H:i:s'),
			"action_type" => $_REQUEST['action_type'],
		);
		$d = $wpdb->insert($table_name, $data);

		$fromEmail = get_option(OPTION_PREFIX."email");
		$msg = get_option(OPTION_PREFIX."email_template");
		$message = $this->replace_shortcode(array("firstname" => $_REQUEST['Voornaam'], "lastname" => $_REQUEST['Achternaam'], "email" => $_REQUEST['email']), $msg);
		$response['msg'] = $message;
		$email		 = "";
		$subject	 = "Offerte Aanvragen";
		$header		 = "Content-type: text/html; charset=iso-8859-1\r\n";
		$header		.= "From: " . $fromEmail . "\r\n";
		$MailResult = wp_mail($_REQUEST['email'], $subject, $message, $header);
		$response['mailUser'] = $MailResult;
			
		if($fromEmail){
			$message = "<p>Nieuwe offerte aanvraag via de postcodechecker: </p>";
			$message .= "<p><strong>Contactgegevens</strong><br>";
			$message .= "Bedrijfsnaam: ".$_REQUEST['Bedrijfsnaam']."<br>";
			$message .= "Contactpersoon: ".$_REQUEST['Voornaam']." ".$_REQUEST['Achternaam']."<br>";
			$message .= "Telefoonnummer: ".$_REQUEST['Telefoonnummer']."<br>";
			$message .= "Email: ".$_REQUEST['email']."</p>";
			$message .= "<p><strong>Gekozen product</strong><br>";
			$message .= $_REQUEST['ptype']." ".$_REQUEST['carrier']."<br>";
			$message .= "Upload Speed: ".$_REQUEST['upload']."<br>";
			$message .= "Download Speed: ".$_REQUEST['download']."</p>";
			$message .= "Action: ".$_REQUEST['action_type']."</p>";
			$message .= "<p><strong>Adresgegevens</strong><br>".$_REQUEST['address'];
			$MailResult = wp_mail($fromEmail, $subject, $message, $header);
			$response['mailAdmin'] = $MailResult;
		}

		$response['status'] = true;
		$response['msg'] = 'success';
		wp_send_json($response);

	}

	public function lookup_provider($request){
		$response = array("status" => false, "msg" => "Something went wrong");
		$api_verification = get_option(OPTION_PREFIX.'api_verified');
		error_log(print_r($api_verification,true));
		if ($api_verification && $api_verification == "yes") {
			$houseExt = "NULL";
			if(isset($_REQUEST['house_ext']) && $_REQUEST['house_ext'] != ''){
				$houseExt = $_REQUEST['house_ext'];
			}
			$data = array(
				"HouseNr" => $_REQUEST['house_nr'],
				"HouseNrExt" => $houseExt,
				"Zipcode" => $_REQUEST['zip_code'],
				);
			$result = $this->CallAPI("POST",$this->base_url.$this->lookup, $data);
			$result = json_decode($result);
			$response['result'] = $result;	
			if(isset($result->status) && $result->status == false){
				wp_send_json($response);
			}
			if(!$result){
				wp_send_json($response);	
			}
			$response['status'] = true;
			$response['msg'] = 'success';

		} else {
			$response['msg'] = "Your API is not verified yet, Please verify your API first.";
		}
		wp_send_json($response);
	}
	public function api_permissions_check( $request ) {
		if(!isset($_REQUEST['zip_code'])){
			return new WP_Error( 'rest_block', esc_html__( 'Zip Code is required' ), array( 'status' => 402 ) );
		}
		if(!isset($_REQUEST['house_nr'])){
			return new WP_Error( 'rest_block', esc_html__( 'House number is required' ), array( 'status' => 402 ) );
		}
		return true;
	}

	public function verify_client( $request ){
		$response = array("status" => false, "msg" => "Something went wrong");
		$url = $this->base_url.$this->lookup;
		$result = $this->CallAPI("POST",$url, array("verify"=>1));
		$result = json_decode($result);
		if(isset($result->status) && $result->status == false){
			$response['msg'] = "Verification Failed";
			wp_send_json($response);	
		}
		if(!isset($result->status)){
			$response['msg'] = "Verification Failed";
			wp_send_json($response);	
		}
		update_option(OPTION_PREFIX."qupra_api_user", $result);
		if(isset($result->api_v) && $result->api_v == "v3"){
			$pricingUrl = $this->base_url.$this->getNetworkPricing;
			$result = $this->CallAPI("GET",$pricingUrl);
			$result = json_decode($result);
			update_option(OPTION_PREFIX."qupra_network_pricings", $result);
		}
		$response['status'] = true;
		$response['msg'] = 'API Verified';
		wp_send_json($response);
	}

	function CallAPI($method, $url, $data = []){
	    $curl = curl_init();
	    switch ($method){
	        case "POST":
	            curl_setopt($curl, CURLOPT_POST, 1);

	            if ($data)
	                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	            break;
	        case "PUT":
	            curl_setopt($curl, CURLOPT_PUT, 1);
	            break;
	        default:
	            if ($data)
	                $url = sprintf("%s?%s", $url, http_build_query($data));
	    }

	    // Optional Authentication:
	    $getAPI = get_option(OPTION_PREFIX."next_api_key");
	    $authorization = "Authorization: Bearer ".$getAPI;
	    curl_setopt($curl, CURLOPT_HTTPHEADER, array($authorization ));
	    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	    // curl_setopt($curl, CURLOPT_USERPWD, "username:password");

	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

	    $result = curl_exec($curl);

	    curl_close($curl);

	    return $result;
	}



	public function replace_shortcode( $args, $body ) {

		$tags =  array(
			'email' 	=> "",
			'firstname' 	=> "",
			'lastname' => "",
		);
		$tags = array_merge( $tags, $args );

		extract( $tags );

		$tags 	= array( '{email}',
						'{firstname}',
						'{lastname}',
						);

		$values  = array(   $email, 
							$firstname ,
							$lastname,
		);
	
		$message = str_replace($tags, $values, $body);	
		
		$message = nl2br($message);
		$message = htmlspecialchars_decode($message,ENT_QUOTES);

		return $message;
	}

}

function register_next_api_controller() {
	$controller = new Nextpertise_API();
	$controller->register_routes();
}

add_action( 'rest_api_init', 'register_next_api_controller' );
