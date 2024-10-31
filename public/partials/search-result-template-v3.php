<div id="mra-search-result"></div>
<script type="text/template" id="tmpl-mra-search-result">
	<div class="qupra_search_container qupra_container" style="background-color: <?php echo $container_color;?>; border: 1px solid <?php echo $container_border_color;?>">
		<form class="search_provider">
			<div class="row form-row" style="display:flex; align-items:center;">
				<div class="col-md-3 col-sm-12">
					<h6 style="margin:0;font-size:16px">Doe de postcodecheck</h6>
					<p style="font-size:14px;">We hebben je postcode + huisnummer nodig om te zien wat er op jouw adres beschikbaar is.</p>
				</div>
				<div class="col-md-2 col-sm-4">
					<input type="text" class="form-control" name="zip_code" id="zip_code" placeholder="Postcode" required>
				</div>
				<div class="col-md-2 col-sm-4">
					<input type="text" class="form-control" name="house_nr" id="house_nr" placeholder="Huisnummer" required>
				</div>
				<div class="col-md-2 col-sm-4">
					<input type="text" class="form-control" name="house_ext" id="house_ext" placeholder="Toevoeging">
				</div>
				<div class="col-md-3 col-sm-12">
					<button class="btn btn-primary btn-block" id="qupra_search" style="background-color: <?php echo $button_color;?>; color:<?php echo $font_color;?>; border-color: <?php echo $button_border_color;?>;padding:10px 20px;">controleer beschikbaarheid</button>
				</div>
			</div>
		</form>
	</div>
	<div class="qupra_search_container">
		<div class="new-postcode-result postcode-result">
			<# if(data.loader){ #>
				<div class="loader"></div>
				<div style="text-align:center;margin-top:20px;">Wij controleren de beschikbaarheid op uw adres</div>
			<# } #>
			<# if(data.error){ #>
				<# if(data.errorMsg){ #>
						<div style="text-align:center;margin-top:20px;color:#ad0909ee;font-weight:bold;">{{data.errorMsg}}</div>
					<# } else { #>
						<div style="text-align:center;margin-top:20px;color:#ad0909ee;font-weight:bold;">Er kan op dit adres kan op dit moment geen beschikbaarheid worden opgehaald, controleer of de ingevoerde gegevens juist zijn</div>
				<# } #>
			<# } #>
			<# if(data.addr){ #>
				<div class="network-address">{{data.addr}}</div>
			<# } #>
			<# jQuery.each(data.providers, function(index, provider){ #>
			   <div class="postcode-result-box result-item">
			      <div class="postcode-left-part">
			         <h3 class="zipcheck-title"><?php echo $company_name;?> {{provider.Type}}</h3>
			         <p>via <span class="zipcheck-provider">{{provider.Provider}}</span></p>
			      </div>
			      <div class="postcode-offer-part">
                    <?php if($displayPrice): ?>
                        <# if(provider.pricing) { #>
			      	    <button class="blue-btn order-offer-btn zipcheck-order-btn" style="height:auto;" data-ptype="{{provider.Type}}" data-company="<?php echo $company_name;?>" data-provider="{{provider.Provider}}" data-download="{{provider.MaxDownload}}" data-upload="{{provider.MaxUpload}}" data-area="{{provider.Area}}" data-distance="{{provider.Distance}}" data-address="{{data.addr}}" data-carrier="{{provider.Carrier}}">Bestellen</button> <br/>
                        <# } #>
                    <?php endif; ?>
			      	<button class="blue-btn zipcheck-offer-btn order-offer-btn" style="height:auto;" data-ptype="{{provider.Type}}" data-company="<?php echo $company_name;?>" data-provider="{{provider.Provider}}" data-download="{{provider.MaxDownload}}" data-upload="{{provider.MaxUpload}}" data-area="{{provider.Area}}" data-distance="{{provider.Distance}}" data-address="{{data.addr}}" data-carrier="{{provider.Carrier}}">Offerte aanvragen</button>
			      </div>
			      <div class="postcode-right-part">
						<div class="line-one-part">
							<p class="area-distance">{{provider.Area}}. {{provider.Distance}}</p>
							<div class="speed-part">
								<h3><img src="<?php echo MRA_N_PLUGIN_PATH.'/public/imgs/arrow-down.svg';?>" alt="down"><span class="zipcheck-maxdownload">{{provider.MaxDownload}}</span></h3>
								<h3><img src="<?php echo MRA_N_PLUGIN_PATH.'/public/imgs/arrow-up.svg';?>" alt="down"><span class="zipcheck-maxupload">{{provider.MaxUpload}}</span></h3>
							</div>
						</div>
						<div class="line-two-part">
							<?php if($displayPrice): ?>
							<# if(provider.pricing) { #>
								<div class="manual-pricings" data-display="<?php echo $displayPrice; ?>">
									<# jQuery.each(provider.pricing, function(i, price){ #>
										<div class="manual-price price-option-{{i}} manual-price-{{index}}" data-price="{{price.id}}">
											<span data-period="monthly"> € {{data.manualpricings['network_'+price.id+'_monthly_sell']}} Per maand</span> <br/>
											<span data-period="oneoff"> € {{data.manualpricings['network_'+price.id+'_oneoff_sell']}} Eenmalig</span>
										</div>
									<# }); #>
								</div>
								<div class="pricing-part">
									<label for="sneh">snelheid: </label>
									<select name="provider-pricings" data-index="{{index}}" id="provider-pricings-{{index}}" class="provider-pricings">
										<# jQuery.each(provider.pricing, function(i, price){ #>
											<option value="{{price.id}}">{{price.option}}</option>
										<# }); #>
									</select>
								</div>
							<# } else { #>
								<div class="no-pricing-show">
									Prijs op aanvraag
								</div>
							<# } #>
							<?php endif ?>
						</div>
			      </div>
			   </div>
			<# }); #>
		</div>
	</div>
	<div id="qupraModal" class="qupra_modal">
	  <!-- qupra_modal content -->
	  <div class="qupra_modal-content">
	    <span class="qupra_close">&times;</span>
	    <form id="requestForm">
	      	<div class="row">
	      		<div class="col-md-12">
	      			<input type="text" class="form-control formInputs" name="Voornaam" placeholder="Voornaam">
	      		</div>
	      		<div class="col-md-12">
	      			<input type="text" class="form-control formInputs" name="Achternaam" placeholder="Achternaam">
	      		</div>
	      		<div class="col-md-12">
	      			<input type="text" class="form-control formInputs" name="Bedrijfsnaam" placeholder="Bedrijfsnaam">
	      		</div>
	      		<div class="col-md-12">
	      			<input type="text" class="form-control formInputs" name="Telefoonnummer" placeholder="Telefoonnummer ">
	      		</div>
	      		<div class="col-md-12">
	      			<input type="text" class="form-control formInputs" name="email" placeholder="Email">
	      			<input type="hidden" name="company" id="form_company">
	      			<input type="hidden" name="provider" id="form_provider">
	      			<input type="hidden" name="ptype" id="form_ptype">
	      			<input type="hidden" name="download" id="form_download">
	      			<input type="hidden" name="upload" id="form_upload">
	      			<input type="hidden" name="area" id="form_area">
	      			<input type="hidden" name="distance" id="form_distance">
	      			<input type="hidden" name="address" id="form_address">
	      			<input type="hidden" name="carrier" id="form_carrier">
                    <input type="hidden" name="action_type" id="form_type">
	      		</div>
	      		<div class="col-md-12">
	      			<button class="btn btn-block btn-primary" id="request_quote_submit" style="background-color: <?php echo $button_color;?>; color:<?php echo $font_color;?>; border-color: <?php echo $button_border_color;?>;padding:10px 20px;">Offerte Aanvragen</button>
	      		</div>
	      	</div>
	      </form>
	  </div>
	</div>
</script>

<!-- <div class="modal fade" id="requestFormModel" role="dialog">
    <div class="modal-dialog">
    
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" style="position: absolute;right: 10px;">&times;</button>
          <h4 class="modal-title">Request form</h4>
        </div>
        <div class="modal-body">
          <form id="requestForm">
          	<div class="row">
          		<div class="col-md-12">
          			<input type="text" class="form-control formInputs" name="firstname" placeholder="Voornaam  ">
          		</div>
          		<div class="col-md-12">
          			<input type="text" class="form-control formInputs" name="lastname" placeholder="Achternaam">
          		</div>
          		<div class="col-md-12">
          			<input type="text" class="form-control formInputs" name="company_name" placeholder="Bedrijfsnaam">
          		</div>
          		<div class="col-md-12">
          			<input type="text" class="form-control formInputs" name="phone_number" placeholder="Telefoonnummer ">
          		</div>
          		<div class="col-md-12">
          			<input type="text" class="form-control formInputs" name="email" placeholder="Email">
          			<input type="hidden" name="company" id="form_company">
          			<input type="hidden" name="provider" id="form_provider">
          			<input type="hidden" name="ptype" id="form_ptype">
          			<input type="hidden" name="download" id="form_download">
          			<input type="hidden" name="upload" id="form_upload">
          		</div>
          		<div class="col-md-12">
          			<button class="btn btn-block btn-primary">Offerte Aanvragen</button>
          		</div>
          	</div>
          </form>
        </div>
      </div>
      
    </div>
  </div> -->