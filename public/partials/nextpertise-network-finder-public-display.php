<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://www.fiverr.com/mrabro
 * @since      1.0.0
 *
 * @package    Nextpertise_Network_Finder
 * @subpackage Nextpertise_Network_Finder/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
$container_color = isset($dataSaved['container_color']) ? $dataSaved['container_color'] : "";
$button_color = isset($dataSaved['button_color']) ? $dataSaved['button_color'] : "";
$font_color = isset($dataSaved['font_color']) ? $dataSaved['font_color'] : "";
?>
<div class="container qupra_search_container" style="background-color: <?php echo $container_color;?>">
	<form class="search_provider">
		<div class="row form-row">
			<div class="col-md-3 col-sm-4">
				<input type="text" class="form-control" name="zip_code" id="zip_code" placeholder="Enter zipcode" required>
			</div>
			<div class="col-md-3 col-sm-4">
				<input type="text" class="form-control" name="house_nr" id="house_nr" placeholder="Enter House Number" required>
			</div>
			<div class="col-md-3 col-sm-4">
				<input type="text" class="form-control" name="house_ext" id="house_ext" placeholder="Enter House Extension">
			</div>
			<div class="col-md-3 col-sm-12">
				<button class="btn btn-primary btn-block" id="qupra_search" style="background-color: <?php echo $button_color;?>; color:<?php echo $font_color;?>;">Search</button>
			</div>
		</div>
		<div class="row mt-5">
			<div class="col search_results">
				
			</div>
		</div>
	</form>
</div>
