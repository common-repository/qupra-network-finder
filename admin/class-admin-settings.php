<?php

/**
 * Admin Settings
 */
class mra_admin_settings{

	public function admin_menu_addition(){
		add_menu_page( 
	      'Qupra Tool', 
	      'Qupra Network Availabillity', 
	      'edit_posts', 
	      PLUGIN_SLUG, 
	      array($this,'qupra_menu_content'), 
	      'dashicons-media-spreadsheet',
	      2
	     );

		add_submenu_page( PLUGIN_SLUG, "inzendingen", "inzendingen", "read", PLUGIN_SLUG."_listing", array($this, "listings"), 1 );

		global $submenu;
	    $submenu[PLUGIN_SLUG][0][0] = 'Settings';
	}


	public function listings(){
		$listing_entries = new listing_data();
        $listing_entries->prepare_items();
        ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                <h2>Submissions</h2>
                <?php $listing_entries->display(); ?>
            </div>
        <?php
	}

	public function qupra_menu_content(){
		$settings = array('next_api_key', 'company_name', 'email', 'container_color', 'font_color', 'button_color', 'button_border_color', 'email_template','container_border_color');
		if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == PLUGIN_SLUG && isset($_REQUEST['nextpertise_settings']) ) {
			echo '<div class="is-dismissible notice"><p>Settings saved successfully.</p></div>';

			foreach ($settings as $setting) {
				if(isset($_REQUEST[$setting]) && $_REQUEST[$setting] != ""){
					update_option(OPTION_PREFIX.$setting, $_REQUEST[$setting]);
				}				
			}
		}
		$dataSaved = array();
		foreach ($settings as $setting) {
			$dataSaved[$setting] = get_option(OPTION_PREFIX.$setting);
		}
		$client = get_option(OPTION_PREFIX."qupra_api_user");

		if(isset($dataSaved['next_api_key']) && $client && isset($client->token) && $dataSaved['next_api_key'] != $client->token){
			$client = false;
		}
		$verified = false;
		if(isset($dataSaved['next_api_key']) && $client && isset($client->token) && $dataSaved['next_api_key'] == $client->token){
			$verified = true;
			update_option(OPTION_PREFIX.'api_verified', 'yes');
		} else {
			update_option(OPTION_PREFIX.'api_verified', 'no');
		}
		if ($client && isset($client->api_v) && $client->api_v == "v3" && (isset( $_REQUEST['page'] ) && $_REQUEST['page'] == PLUGIN_SLUG && isset($_REQUEST['nextpertise_settings']))){
			$display_price = false;
			if(isset($_REQUEST['display_pricings'])){
				$display_price = true;
			}
			update_option(OPTION_PREFIX.'display_pricings', $display_price);
			$v3_settings = ['price_calculation', 'pricing_margin'];
			foreach ($v3_settings as $setting) {
				if(isset($_REQUEST[$setting]) && $_REQUEST[$setting] != ""){
					update_option(OPTION_PREFIX.$setting, $_REQUEST[$setting]);
				}				
			}
			if ( isset($_REQUEST['pricings']) ){
				update_option(OPTION_PREFIX."network_pricings", $_REQUEST['pricings']);
			}
		}
		if ( $client && isset($client->api_v) && $client->api_v == "v3" ){
			$dataSaved['display_pricings'] = get_option(OPTION_PREFIX.'display_pricings');
			$v3_settings = ['price_calculation', 'pricing_margin'];
			foreach ($v3_settings as $setting) {
				$dataSaved[$setting] = get_option(OPTION_PREFIX.$setting);
			}
			$pricings = get_option(OPTION_PREFIX."network_pricings");
		}
		?>
		<div class="wrap">
			<h1>Qupra Zipcode tool</h1>
			<p>Voor het gebruik van de postcodetool een Qupra API key benodigd. Ga naar <a href="quprawholesale.nl/availabillity">quprawholesale.nl/availabillity</a> en registreer je voor een gratis API key. Bent u klant van Qupra? Dan wordt deze key aangeleverd door uw accountmanager.</p>
			<form method="post">
				<table class="form-table">
					<tr>
						<th>
							<label for="API">QUPRA API KEY</label>
						</th>
						<td>
							<input type="password" name="next_api_key" placeholder="Enter API Key" value="<?php echo isset($dataSaved['next_api_key']) ? $dataSaved['next_api_key'] : ''; ?>" id="next_api_key">
							<input type="hidden" name="nextpertise_settings" value="saving">
							<?php if($verified): ?>
								<span class="description" id="api_status" style='color:green'>Verified</span>
							<?php else: ?>	
								<span class="description" id="api_status">Sla eerst de wijzigingen op door op de knop opslaan te drukken, druk daarna op de valideer API key knop.</span><button class="default" id="verifyAPI">Valideer API Key</button>
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<th>
							<label for="company">Your Company Name</label>
						</th>
						<td>
							<input type="text" name="company_name" placeholder="Enter your company name" value="<?php echo isset($dataSaved['company_name']) ? $dataSaved['company_name'] : ''; ?>">
							<span class="description">Uw bedrijfsnaam wordt getoond in de postcode resultaten waarmee de postcodetool de uitstraling van uw bedrijf krijgt.”</span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="email">Email</label>
						</th>
						<td>
							<input type="email" name="email" placeholder="Enter your email.." value="<?php echo isset($dataSaved['email']) ? $dataSaved['email'] : ''; ?>">
							<span class="description">Als een website bezoeker een offerte aanvraagt worden de details naar dit email adres verzonden.</span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="color">Container color</label>
						</th>
						<td>
							<input type="color" name="container_color" value="<?php echo isset($dataSaved['container_color']) ? $dataSaved['container_color'] : ''; ?>">
							<span class="description">De kleur die wordt gebruikt voor de achtergrond van de zoekbalk.</span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="color">Container border color</label>
						</th>
						<td>
							<input type="color" name="container_border_color" value="<?php echo isset($dataSaved['container_border_color']) ? $dataSaved['container_border_color'] : ''; ?>">
							<span class="description">De kleur die wordt gebruikt voor de achtergrond van de zoekbalk.</span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="color">Font color</label>
						</th>
						<td>
							<input type="color" name="font_color" value="<?php echo isset($dataSaved['font_color']) ? $dataSaved['font_color'] : ''; ?>">
							<span class="description">De kleur die wordt gebruikt voor de tekst op de zoekbalk.</span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="color">Button color</label>
						</th>
						<td>
							<input type="color" name="button_color" value="<?php echo isset($dataSaved['button_color']) ? $dataSaved['button_color'] : ''; ?>">
							<span class="description">De kleur die wordt gebruikt voor de knop op de zoekbalk.</span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="color">Button border color</label>
						</th>
						<td>
							<input type="color" name="button_border_color" value="<?php echo isset($dataSaved['button_border_color']) ? $dataSaved['button_border_color'] : ''; ?>">
							<span class="description">De kleur die wordt gebruikt voor de knop op de zoekbalk.</span>
						</td>
					</tr>
					<tr>
						<th>
							<label for="email-template">Email template</label>
						</th>
						<td>
							<textarea name="email_template" id="email_template" cols="60" rows="7"><?php echo isset($dataSaved['email_template']) ? $dataSaved['email_template'] : '';?></textarea><br>
							<span class="description">U kunt gebruik maken van de variabelen {firstname}, {lastname} en {email} in de template. Deze worden vervangen door de ingevoerde waarden.</span>
						</td>
					</tr>
					<tr>
						<th>Shortcode</th>
						<td>
							[qupra_search]
							<span class="description">Dit is de shortcode welke u plaatst op de pagina waar u de postcodetool wilt laten zien</span>
						</td>
					</tr>
				</table>
				
				<?php if ($client && isset($client->api_v) && $client->api_v == "v3"){ ?>
					<hr>
					<table class="form-table">
						<tr>
							<th>
								<label for="display_prices">Toon prijzen</label><br/>
								<span class="description small-description">*Hiermee kunt u er voor kiezen om verkoopprijzen prijzen weer te geven op uw website.</span>
							</th>
							<td>
								<label class="switch">
									<input type="checkbox" name="display_pricings" value="true" <?php echo $dataSaved['display_pricings'] ? "checked" : ""; ?>>
									<span class="slider round"></span>
								</label>
							</td>
						</tr>
						<tr>
							<th>
								<label for="price_calc">Type prijsberekening</label><br/>
								<span class="description small-description">*Kies voor een vast percentage bovenop de inkoopprijs of stel zelf verkoopprijzen in.</span>
							</th>
							<td>
								<select name="price_calculation" id="price_calculation">
									<option value="non">Select Method</option>
									<option value="percentage" <?php echo isset($dataSaved['price_calculation']) && $dataSaved['price_calculation'] == "percentage" ? "selected" : ""; ?>>Percentage op inkoop</option>
									<option value="fixed" <?php echo isset($dataSaved['price_calculation']) && $dataSaved['price_calculation'] == "fixed" ? "selected" : ""; ?>>Vaste prijzen</option>
									
								</select>
							</td>
						</tr>
						<tr>
							<th>
								<label for="price-margin">Marge (bij percentage)</label>
							</th>
							<td>
								<input type="number" name="pricing_margin" value="<?php echo isset($dataSaved['pricing_margin']) ? $dataSaved['pricing_margin'] : ''; ?>">%
							</td>
						</tr>
					</table>
					<?php $networkPricings = get_option(OPTION_PREFIX."qupra_network_pricings");
						if ($networkPricings): ?>
							<h3>Prijsoverzicht <button class="reload_pricings" id="reload_pricings">Inkoopprijzen opnieuw ophalen</button></h3>
							<table class="network_pricing_table">
								<tr>
									<td>ID</td>
									<td>Provider</td>
									<td>Type</td>
									<td>Carrier</td>
									<td>MaxUpload</td>
									<td>MaxDownload</td>
									<td>Maandelijkse inkoopprijs</td>
									<td>Eenmalige inkoopprijs</td>
									<td>Maandelijkse verkoopprijs</td>
									<td>Eenmalige verkoopprijs</td>
									<td>Prijs niet tonen</td>
								</tr>
								<?php 
									foreach($networkPricings as $network) {
										$networkMonthlyPricings_sell = $network->MonthlyPricing;
										$networkMonthlyPricings_buy = $network->MonthlyPricing;
										$networkOneOffPricings_sell = $network->OneOffPricing;
										$networkOneOffPricings_buy = $network->OneOffPricing;
										if ( isset($pricings['network_'.$network->id.'_monthly_sell']) && $pricings['network_'.$network->id.'_monthly_sell'] != "" ){
											$networkMonthlyPricings_sell = $pricings['network_'.$network->id.'_monthly_sell'];
										}
										if ( isset($pricings['network_'.$network->id.'_oneoff_sell']) && $pricings['network_'.$network->id.'_oneoff_sell'] != "" ){
											$networkOneOffPricings_sell = $pricings['network_'.$network->id.'_oneoff_sell'];
										}
										?>
										<tr>
										<td> <?php echo isset($network->id) ? $network->id : "" ; ?></td>
										<td> <?php echo isset($network->Provider) ? $network->Provider : "" ; ?></td>
										<td> <?php echo isset($network->Type) ? $network->Type : "" ; ?></td>
										<td> <?php echo isset($network->Carrier) ? $network->Carrier : "" ; ?></td>
										<td> <?php echo isset($network->MaxUpload) ? $network->MaxUpload : "" ; ?></td>
										<td> <?php echo isset($network->MaxDownload) ? $network->MaxDownload : "" ; ?></td>
										<td> €<input type="text" disabled name="pricings[network_<?php echo $network->id; ?>_monthly_buy]" value="<?php echo $networkMonthlyPricings_buy ; ?>" ></td>
										<td> €<input type="text" disabled name="pricings[network_<?php echo $network->id; ?>_oneoff_buy]" value="<?php echo $networkOneOffPricings_buy ; ?>"></td>
										<td> €<input type="text" name="pricings[network_<?php echo $network->id; ?>_monthly_sell]" value="<?php echo $networkMonthlyPricings_sell ; ?>" ></td>
										<td> €<input type="text" name="pricings[network_<?php echo $network->id; ?>_oneoff_sell]" value="<?php echo $networkOneOffPricings_sell ; ?>"></td>
										<td> <input type="checkbox" name="pricings[network_<?php echo $network->id; ?>_hideprice_check]" value="yes" <?php echo isset($pricings['network_'.$network->id.'_hideprice_check']) ? 'checked' : '';?>></td>
										</tr>
										<?php
									}
								?>
							</table>
					<?php endif;
					} ?>
				<?php echo submit_button(); ?>
			</form>
		</div>

		<script>
			jQuery("#verifyAPI").on("click", function(e){
				e.preventDefault();
				jQuery.ajax({
					url: "<?php echo get_site_url();?>/?rest_route=/mra/v1/verify",
					type: 'post',
					data: {verify:"api"},
					success: function(response) {
						console.log(response);
						if(response.status){
							jQuery("#api_status").html(response.msg).css("color","green");
						} else {
							jQuery("#api_status").html(response.msg).css("color","red");
						}
					}
				});
			});
			jQuery("#reload_pricings").on("click", function(e){
				e.preventDefault();
				jQuery.ajax({
					url: "<?php echo get_site_url();?>/?rest_route=/mra/v1/verify",
					type: 'post',
					data: {verify:"api"},
					success: function(response) {
						location.reload();
					}
				});
			});
		</script>
		<?php
	}
	
}


// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class listing_data extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );

        $perPage = 20;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'id'          => 'ID',
            'user'       => 'User',
            'email' => 'Email',
            'phone'        => 'Phone',
            'company'    => 'Company',
            'provider'      => 'Provider',
            'download'      => 'Max Download',
            'upload'      => 'Max Upload',
            'distance'      => 'Distance',
            'address'      => 'Address',
            'request_date'      => 'Date',
            'action_type'      => 'Action',
        );

        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array(
        	'id' => array('id', false),
        	'request_date' => array('request_date', false),
        	'email' => array('email', false),
        );
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        $data = array();

        global $wpdb;
		$table_name = $wpdb->prefix . QP_TABLE;
		$sql = "SELECT * FROM $table_name";
		if(isset($_GET['orderby'])){
			$sql .= " order by ".$_GET['orderby'];
		}
		if(isset($_GET['order'])){
			$sql .= " ".$_GET['order'];
		}
		$result = $wpdb->get_results($sql);
		$record = array();
		foreach ($result as $key => $value) {
			$record['id'] = $value->id;
			$record['user'] = $value->firstname.' '.$value->lastname;
			$record['email'] = $value->email;
			$record['phone'] = $value->phone;
			$record['company'] = $value->company_name;
			$record['provider'] = $value->request_type.' '.$value->request_provider;
			$record['download'] = $value->request_maxDownload;
			$record['upload'] = $value->request_maxUpload;
			$record['distance'] = $value->request_area.' '.$value->request_distance;
			$record['address'] = $value->request_address;
			$record['request_date'] = $value->request_date;
			$record['action_type'] = $value->action_type;

			$data[] = $record;
		}
        // $data[] = array(
        //             'id'          => 1,
        //             'user'       => 'The Shawshank Redemption',
        //             'email' => 'Two imprisoned.',
        //             'phone'        => '1994',
        //             'company'    => 'Frank Darabont',
        //             'provider'      => '9.3',
        //             'download'      => '9.3',
        //             'upload'      => '9.3',
        //             'date'      => '9.3',
        //             );

        
        return $data;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        // switch( $column_name ) {
        //     case 'id':
        //     case 'user':
        //     case 'email':
        //     case 'year':
        //     case 'director':
        //     case 'rating':
                return $item[ $column_name ];

        //     default:
        //         return print_r( $item, true ) ;
        // }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'id';
        $order = 'asc';

        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }


        $result = strcmp( $a[$orderby], $b[$orderby] );

        if($order === 'asc')
        {
            return $result;
        }

        return -$result;
    }
}