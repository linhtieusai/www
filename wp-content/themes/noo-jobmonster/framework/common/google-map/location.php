<?php

if( !function_exists( 'jm_get_location_setting' ) ) :
	function jm_get_location_setting($id = null ,$default = null){
		return jm_get_setting('jm_location', $id, $default);
	}
endif;

if( !function_exists( 'jm_location_admin_init' ) ) :
	function jm_location_admin_init(){
		register_setting('jm_location','jm_location');
		add_action('noo_job_setting_location', 'jm_location_settings_form');
	}
	
	add_filter('admin_init', 'jm_location_admin_init' );
endif;

if( !function_exists( 'jm_location_settings_tabs' ) ) :
	function jm_location_settings_tabs( $tabs = array() ) {
		$location_tab = array( 'location' => __('Location','noo') );
		return array_merge($tabs, $location_tab);
	}
	
	add_filter('noo_job_settings_tabs_array', 'jm_location_settings_tabs', 11 );
endif;

if( !function_exists( 'jm_location_settings_form' ) ) :
	function jm_location_settings_form(){
		wp_enqueue_style('vendor-chosen-css');
		wp_enqueue_script('vendor-chosen-js');
		?>
		<?php settings_fields('jm_location'); ?>
		<?php
			// setting value
			$location_mode = jm_get_location_setting( 'location_mode', 'taxonomy' );
			$allow_user_input = jm_get_location_setting( 'allow_user_input', 1 );
			$enable_auto_complete = jm_get_location_setting( 'enable_auto_complete', 1 );
			$country_restriction = jm_get_location_setting( 'country_restriction', '' );
			$location_type = jm_get_location_setting( 'location_type', '(regions)' );
			$map_type = jm_get_location_setting('map_type','google','none');
			$unit_type = jm_get_location_setting('unit_type','kilometer');
		?>
		<h3><?php echo __('Map Location Settings','noo')?></h3>
		<table class="form-table" cellpadding="0">
			<tbody>
				<tr>
					<th>
						<?php _e('Maps Type','noo')?>
					</th>
					<td>
						<fieldset class="box-map-type">
							<label>
								<input type="radio" <?php checked( $map_type, 'none' ); ?> id="jm-item-none" name="jm_location[map_type]" value="none"><?php _e('None', 'noo'); ?>
							</label><br/>
							<label>
								<input type="radio" <?php checked( $map_type, 'google' ); ?> id="jm-item-google" name="jm_location[map_type]" value="google"><?php _e('Google Map', 'noo'); ?>
							</label><br/>
							<label>
								<input type="radio" <?php checked( $map_type, 'bing' ); ?> id="jm-item-bing" name="jm_location[map_type]" value="bing"><?php _e('Bing Map', 'noo'); ?>
							</label><br/>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>
        <table class="form-table" cellpadding="0">
            <tbody>
            <tr>
                <th>
                    <?php esc_html_e('Unit of length of linear measure','noo') ?>
                </th>
                <td>
                    <fieldset class="box-map-type">
                    <label>
                        <input type="radio" <?php checked( $unit_type, 'km' ); ?> id="jm-item-kilometer" name="jm_location[unit_type]" value="km"><?php _e('Kilometer', 'noo'); ?>
                    </label><br/>
                    <label>
                        <input type="radio" <?php checked( $unit_type, 'mi' ); ?> id="jm-item-mile" name="jm_location[unit_type]" value="mi"><?php _e('Miles', 'noo'); ?>
                    </label><br/>
                    </fieldset>
                </td>
            </tr>
            </tbody>
        </table>

		<table class="form-table map_type type_google" cellspacing="0">
			<tbody>
				<!-- <tr>
					<th>
						<?php //_e('Location Mode','noo')?>
					</th>
					<td>
						<fieldset>
							<label><input type="radio" <?php //checked( $location_mode, 'taxonomy' ); ?> name="jm_location[location_mode]" value="taxonomy"><?php //_e('Taxonomy list ( easy to manage )', 'noo'); ?></label><br/>
							<label><input type="radio" <?php //checked( $location_mode, 'google' ); ?> name="jm_location[location_mode]" value="google"><?php //_e('Real Google Map address', 'noo'); ?></label><br/>
						</fieldset>
					</td>
				</tr> -->
				<tr>
					<th>
						<?php esc_html_e('Google Maps API Key','noo')?>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php echo jm_get_location_setting('google_api','')?>" name="jm_location[google_api]">
						<p>
							<?php echo __('<b>Google</b> requires that you register an API Key to display <b>Maps</b> on from your website.', 'noo' ); ?><br/>
							<?php echo __('To know how to create this application,', 'noo'); ?> <a href="javascript:void(0)" onClick="jQuery('#google-map-help').toggle();return false;"><?php _e('click here and follow the steps.', 'noo'); ?></a>
						</p>
						<div id="google-map-help" class="noo-setting-help" style="display: none; max-width: 1200px;" >
							<hr/>
							<br/>
							<?php echo __('To register a new <b> Google Map API Key</b>, follow the steps', 'noo'); ?>:
							<br/>
							<?php $setupsteps = 0; ?>
							<p><b><?php echo ++$setupsteps; ?></b>. <?php _e( 'Go to', 'noo'); ?>&nbsp;<a href="https://console.cloud.google.com/google/maps-apis/overview" target ="_blank">
								<?php echo __('Google Cloud Platform Console.', 'noo'); ?></a>. <?php echo __('Login to your Google account if needed', 'noo'); ?>.</p>
							<p><b><?php echo ++$setupsteps; ?></b>. <?php _e('Create or select a project.', 'noo') ?>.</p>
							<p><b><?php echo ++$setupsteps; ?></b>. <?php _e('Click <b>Continue</b> to enable the API and any related services.', 'noo') ?>.</p>
							<p><b><?php echo ++$setupsteps; ?></b>. <?php _e('On the <b>Credentials</b> page, get an <b>API key</b>.<br />
									Note: If you have an existing unrestricted API key, or a key with browser restrictions, you may use that key.', 'noo') ?></p> 
							<p><b><?php echo ++$setupsteps; ?></b>. <?php _e('From the dialog displaying the API key, select <b>Restrict key</b> to set a browser restriction on the API key.', 'noo') ?>.</p>
							<p><b><?php echo ++$setupsteps; ?></b>. <?php _e('In the <b>Key restriction</b> section, select <b>HTTP referrers (web sites)</b>, then follow the on-screen instructions to set referrers.', 'noo') ?>.</p> 

							<p><b><?php echo ++$setupsteps; ?></b>. <?php _e('(Optional) Enable billing. See <a href="https://developers.google.com/maps/documentation/javascript/usage-and-billing" target="_blank">Usage Limits</a> for more information.', 'noo') ?>.</p> 

							<p>
								<b><?php _e("And that's it!", 'noo') ?></b> 
								<br />
								<?php echo __( 'For more reference, you can see: ', 'noo' ); ?><a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"><?php echo __('Official Document', 'noo') ?></a>, <a href="http://googlegeodevelopers.blogspot.com.au/2016/06/building-for-scale-updates-to-google.html" target="_blank"><?php echo __('Google Blog', 'noo') ?></a>
							</p> 
							<div style="margin-bottom:12px;" class="noo-thumb-wrapper">
								<a href="https://i.imgur.com/k4GIeu3.png" target="_blank"><img src="https://i.imgur.com/k4GIeu3.png"></a>
								<a href="https://i.imgur.com/kSIqByU.png" target="_blank"><img src="https://i.imgur.com/kSIqByU.png"></a>
							</div>
							<br/>
							<hr/>
						</div>
					</td>
				</tr>
				<tr>
					<th>
						<?php esc_html_e('Starting Point Latitude','noo')?>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php echo jm_get_location_setting('latitude_google','')?>" name="jm_location[latitude_google]">
					</td>
				</tr>
				<tr>
					<th>
						<?php esc_html_e('Starting Point Longitude','noo')?>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php echo jm_get_location_setting('longitude_google','')?>" name="jm_location[longitude_google]">
					</td>
				</tr>
				<tr>
					<th>
						<?php esc_html_e('Default Zoom Level','noo')?>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php echo jm_get_location_setting('zoom_google','')?>" name="jm_location[zoom_google]">
					</td>
				</tr>
				<tr>
					<th>
						<?php _e('Enable Google Auto-Complete','noo')?>
					</th>
					<td>
						<input type="hidden" name="jm_location[enable_auto_complete]" value="0">
						<label><input type="checkbox" <?php checked( $enable_auto_complete, true ); ?> name="jm_location[enable_auto_complete]" value="1"><?php _e('Using Auto-Complete from Google Map for your location input', 'noo'); ?></label>
					</td>
				</tr>
				<tr>
					<th>
						<?php _e('Country Restriction','noo')?>
						<p><small><?php _e('Select your country will limit all suggestions to your local locations. Leave it blank to use all the locations around the world.', 'noo'); ?></small></p>
					</th>
					<td>
						<select name="jm_location[country_restriction]" data-placeholder="Select your country" class="jm-setting-chosen">
							<option value=""></option>
							<?php $country_list = _get_country_ISO_code(); ?>
							<?php if( !empty( $country_list ) ) : ?>
								<?php foreach ($country_list as $country ) : ?>
									<option value="<?php echo $country->Code; ?>" <?php selected( $country->Code, $country_restriction ); ?>><?php echo $country->Name; ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
						
					</td>
				</tr>
				<tr>
					<th>
						<?php _e('Location Type','noo')?>
					</th>
					<td>
						<fieldset>
							<label><input type="radio" <?php checked( $location_type, '(regions)' ); ?> name="jm_location[location_type]" value="(regions)"><?php _e('Administrative Regions', 'noo'); ?></label><br/>
							<label><input type="radio" <?php checked( $location_type, '(cities)' ); ?> name="jm_location[location_type]" value="(cities)"><?php _e('Cities', 'noo'); ?></label><br/>
							<label><input type="radio" <?php checked( $location_type, 'establishment' ); ?> name="jm_location[location_type]" value="establishment"><?php _e('Establishment ( Business location )', 'noo'); ?></label><br/>
							<label><input type="radio" <?php checked( $location_type, 'geocode' ); ?> name="jm_location[location_type]" value="geocode"><?php _e('Full address', 'noo'); ?></label><br/>
						</fieldset>
						<p><small><?php _e('Select the location type that matches your business.', 'noo'); ?></small></p>
					</td>
				</tr>
				<script>
					jQuery(document).ready( function($) {
						// Font functions
						$( 'select.jm-setting-chosen' ).chosen({
							allow_single_deselect: true,
							width: '240px'
						});

						$('input[name="jm_location[enable_auto_complete]"]').change(function(event) {
							var $input = $( this );
							if ( $input.is( ":checked" ) ) {
								$('.enable_auto_complete-child').show();
							} else {
								$('.enable_auto_complete-child').hide();
							}
						});

						$('input[name="jm_location[enable_auto_complete]"]').change();
					});
				</script>
				<?php do_action( 'jm_setting_location_fields' ); ?>
			</tbody>
		</table>
		<table class="form-table map_type type_bing" cellspacing="0">
			<tbody>
				<tr>
					<th>
						<?php esc_html_e('Bing Maps API Key','noo')?>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php echo jm_get_location_setting('bing_api','')?>" name="jm_location[bing_api]">
						</td>
				</tr>
				<tr>
					<th>
						<?php esc_html_e('Starting Point Latitude','noo')?>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php echo jm_get_location_setting('latitude','')?>" name="jm_location[latitude]">
					</td>
				</tr>
				<tr>
					<th>
						<?php esc_html_e('Starting Point Longitude','noo')?>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php echo jm_get_location_setting('longitude','')?>" name="jm_location[longitude]">
					</td>
				</tr>
				<tr>
					<th>
						<?php esc_html_e('Default Zoom Level','noo')?>
					</th>
					<td>
						<input type="text" class="regular-text" value="<?php echo jm_get_location_setting('zoom','')?>" name="jm_location[zoom]">
					</td>
				</tr>
			</tbody>
		</table>
		<script>
			(function( $ ){
				'use strict';
				$(document).ready(function($){

					var JM_Box_Map_Type_Input = $('input[name="jm_location[map_type]"]');

						var map_type = JM_Box_Map_Type_Input.val();
						$('.map_type').hide();
						$('.map_type.type_'+map_type).show();

						JM_Box_Map_Type_Input.change(function() {
						    var map_type = $(this).val();
						    $('.map_type').hide();
							$('.map_type.type_'+map_type).show();
						});
						
						var checked = $( "input:checked" ).val();
						$('.map_type').hide();
						$('.map_type.type_'+checked).show();
				});

			})(jQuery);
		</script>
		<?php 
	}
endif;

function _get_country_ISO_code() {
	$dataFile = dirname( __FILE__ ) . '/data.json';
	$content = json_decode( file_get_contents( $dataFile ) );

	$coutries = array();
	if ( !empty( $content ) ) {
		$coutries = $content;
	}

	return apply_filters( 'jm_location_country_list', $coutries );
}
