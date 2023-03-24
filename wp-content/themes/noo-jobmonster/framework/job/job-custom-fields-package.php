<?php
if( !function_exists('jm_is_enabled_job_package_cf') ) :
	function jm_is_enabled_job_package_cf() {
		$enabled = (bool) jm_get_job_custom_fields_option('job_package', '0');

		return jm_is_woo_job_posting() && $enabled;
	}
endif;
if(!function_exists('jm_is_enable_job_package_resume_cf')):
    function jm_is_enable_job_package_resume_cf(){
        $enabled = (bool) jm_get_resume_custom_fields_option('add_job_package', '0');
        return $enabled;
    }
endif;
if( !function_exists('jm_get_job_package_cf') ) :
	function jm_get_job_package_cf( $package_id = null ) {
		if( ! jm_is_enabled_job_package_cf() ) {
			return array();
		}

		if( !empty( $package_id ) ) {
			$package_cf = get_post_meta($package_id, '_job_package_cf', true);
			$package_cf = !is_array( $package_cf ) ? array( $package_cf ) : $package_cf;
			if( empty( $package_cf ) || ( 1 ==count( $package_cf ) && empty( $package_cf[0] ) ) ) {
				return array();
			} elseif( in_array( 'all', $package_cf ) ) {
				return jm_get_job_custom_fields_option('job_package_fields', array());
			} elseif( is_array( $package_cf ) ) {
				return $package_cf;
			}

			return array();
		}

		return jm_get_job_custom_fields_option('job_package_fields', array());
	}
endif;
if(!function_exists('jm_get_job_package_resume_cf')):
    function jm_get_job_package_resume_cf($package_id = null){
        if(!jm_is_enable_job_package_resume_cf()){
            return array();
        }
        if(!empty($package_id)){
            $package_resume_cf = get_post_meta($package_id,'_job_package_resume_cf',true);
            $package_resume_cf = !is_array($package_resume_cf) ? array($package_resume_cf) : $package_resume_cf;
            if(empty($package_resume_cf)|| (1 == count($package_resume_cf)&& empty($package_resume_cf[0]))){
                return array();
            }elseif (in_array('all',$package_resume_cf)){
               return jm_get_resume_custom_fields_option('job_package_resume_fields', array());
            }elseif (is_array($package_resume_cf)){
                return $package_resume_cf;
            }
            return array();
        }
        return jm_get_resume_custom_fields_option('job_package_resume_fields', array());
    }
endif;

if( !function_exists('jm_job_package_cf_data') ) :
	function jm_job_package_cf_data() {
		// if( ! jm_is_enabled_job_package_cf() ) {
		// 	return;
		// }

		global $post;

		$job_package_cfs = jm_get_job_custom_fields_option('job_package_fields', array());

		if( !empty( $job_package_cfs ) ) {
			$job_custom_fields = jm_get_job_custom_fields();
			$selected_fields = array( '' => __('None', 'noo'), 'all' => __('All fields', 'noo') );
			foreach ($job_custom_fields as $field) {
				if( in_array( $field['name'], $job_package_cfs ) ) {
					$field_label = ( isset( $field['label_translated'] ) && !empty( $field['label_translated'] ) ) ? $field['label_translated'] : $field['label'];
					$selected_fields[$field['name']] = $field_label;
				}
			}

            noo_wc_wp_select_multiple(
                array(
                    'id' => '_job_package_cf',
                    'label' => __('Job Custom Fields', 'noo'),
                    'description' => __('Choose fields that come with this package', 'noo'),
                    'options' => $selected_fields,
                    'desc_tip' => true,
                )
            );
		}
        $job_package_resume_cf = jm_get_resume_custom_fields_option('job_package_resume_fields', array());
		if(!empty($job_package_resume_cf)){
		    $resume_custom_field = jm_get_resume_custom_fields();
		    $selected_resume_fields = array('' => __('None','noo'),'all'=> __('All Fields','noo'));
		    foreach ( $resume_custom_field as $field){
		            if(in_array($field['name'],$job_package_resume_cf)){
                        $field_label = (isset($field['label_translated']) && !empty($field['label_translated'])) ? $field['label_translated'] : $field['label'];
                        $selected_resume_fields[$field['name']] = $field_label;
                    }

            }
            noo_wc_wp_select_multiple(
                array(
                    'id' => '_job_package_resume_cf',
                    'label' => __('Resume Custom Fields', 'noo'),
                    'description' => __('Choose fields that come with this package', 'noo'),
                    'options' => $selected_resume_fields,
                    'desc_tip' => true,
                )
            );
        }
	}

	add_action( 'noo_job_package_data', 'jm_job_package_cf_data' );
endif;

if( !function_exists('jm_job_package_cf_save_data') ) :
	function jm_job_package_cf_save_data($post_id) {
		// Save meta
		if( isset( $_POST[ '_job_package_cf' ] ) ) {
			update_post_meta( $post_id, '_job_package_cf', $_POST[ '_job_package_cf' ] );
		}
		if(isset($_POST['_job_package_resume_cf'])){
		    update_post_meta($post_id,'_job_package_resume_cf',$_POST['_job_package_resume_cf']);
        }
	}

	add_action( 'noo_job_package_save_data', 'jm_job_package_cf_save_data' );
endif;

if( !function_exists('jm_job_package_cf_job_form') ) :
	function jm_job_package_cf_job_form( $custom_fields ) {
		if( !jm_is_enabled_job_package_cf() ) {
			return $custom_fields;
		}

		// Only work on the member page or job posting page
		if( !jm_is_job_posting_page() && ( get_the_ID() != Noo_Member::get_member_page_id() ) ) {
			return $custom_fields;
		}

		$all_job_package_cfs = jm_get_job_custom_fields_option('job_package_fields', array());
		$package = jm_get_package_info();
		$package_id = isset( $package['product_id'] ) ? absint( $package['product_id'] ) : 0;

		if( empty( $package_id ) && jm_is_job_posting_page() && isset( $_REQUEST['package_id'] ) ) {
			$package_id = absint( $_REQUEST['package_id'] );
		}

		$package_cfs = !empty( $package_id ) ? jm_get_job_package_cf( $package_id ) : array();

		$remove_fields = array_diff($all_job_package_cfs, $package_cfs);
		if( !empty( $remove_fields ) ) {
			foreach ($custom_fields as $index => $field) {
				if( in_array( $field['name'], $remove_fields ) ) {
					unset( $custom_fields[$index] );
				}
			}
		}

		return $custom_fields;
	}

	add_filter( 'jm_job_custom_fields', 'jm_job_package_cf_job_form' );
endif;

if( !function_exists('jm_job_package_cf_features') ) :
	function jm_job_package_cf_features( $product ) {
		if( ! jm_is_enabled_job_package_cf() ) {
			return;
		}
		$all_job_package_cfs = jm_get_job_custom_fields_option('job_package_fields', array());
		$package_cfs = jm_get_job_package_cf( $product->get_id() );

		foreach ( $all_job_package_cfs as $field_name ) :
			$field = jm_get_job_field( $field_name );
			if( empty( $field ) ) continue;

			$field_label = ( isset( $field['label_translated'] ) && !empty( $field['label_translated'] ) ) ? $field['label_translated'] : $field['label'];
			if( in_array( $field_name, $package_cfs ) ) : ?>
				<li class="noo-li-icon"><i class="fa fa-check-circle"></i> <?php echo sprintf( __('Post job with %s field','noo'), $field_label );?></li>
			<?php elseif(noo_package_show_no_support_feature()) : ?>
				<li class="noo-li-icon noo-li-not-good"><i class="far fa-times-circle not-good"></i> <?php echo sprintf( __('Post job with %s field','noo'), $field_label );?></li>
			<?php endif; ?>
		<?php endforeach;
	}

	add_action( 'jm_job_package_features_list', 'jm_job_package_cf_features' );
endif;
if(!function_exists('jm_job_package_resume_cf_features_view')):
    function jm_job_package_resume_cf_features_view($product){
        if(!jm_is_enable_job_package_resume_cf()){
            return;
        }
        $all_job_package_resume_cf = jm_get_resume_custom_fields_option('job_package_resume_fields', array());
        $package_resume_cf = jm_get_job_package_resume_cf($product->get_id());
        foreach ($all_job_package_resume_cf as $field_name):
            $field = jm_get_resume_field($field_name);
            if(empty($field)){
                continue;
            }
            $field_label = (isset($field['label_translated']) && !empty($field['label_translated']))? $field['label_translated'] : $field['label'];
            if(in_array($field_name,$package_resume_cf)):?>
                <li class="noo-li-icon"><i class="fa fa-check-circle"></i><?php echo sprintf(__('View %s Field in Resume Details','noo'),$field_label); ?></li>
           <?php elseif(noo_package_show_no_support_feature()) : ?>
                <li class="noo-li-icon noo-li-not-good"><i class="far fa-times-circle not-good"></i><?php echo sprintf(__('View %s Field in Resume Details','noo'),$field_label); ?></li>
            <?php endif; ?>
            <?php
        endforeach;
    }
    add_action( 'jm_job_package_features_list', 'jm_job_package_resume_cf_features_view' );
endif;

if( !function_exists('jm_employer_manage_plan_cf_features') ) :
	function jm_employer_manage_plan_cf_features( $package ) {
		if( !Noo_Member::is_employer() || !jm_is_enabled_job_package_cf() || !isset( $package['product_id'] ) ) {
			return;
		}
		$all_job_package_cfs = jm_get_job_custom_fields_option('job_package_fields', array());
		$package_cfs = jm_get_job_package_cf( $package['product_id'] );

		foreach ( $all_job_package_cfs as $field_name ) :
			$field = jm_get_job_field( $field_name );
			if( empty( $field ) ) continue;

			$field_label = ( isset( $field['label_translated'] ) && !empty( $field['label_translated'] ) ) ? $field['label_translated'] : $field['label'];
			$icon = in_array( $field_name, $package_cfs ) ? 'fa-check-circle' : 'fa-times-circle-o not-good';
			?>
			<div class="col-xs-6"><strong><?php echo sprintf( __('Post with %s','noo'), $field_label ); ?></strong></div>
			<div class="col-xs-6"><?php echo in_array( $field_name, $package_cfs ) ? __('Yes','noo') : __('No', 'noo');?></div>
		<?php endforeach;
	}

	add_action( 'jm_manage_plan_features_list', 'jm_employer_manage_plan_cf_features' );
endif;
if(!function_exists('jm_employer_manager_plan_resume_cf_features_view')):
    function jm_employer_manager_plan_resume_cf_features_view($package){
        if(!Noo_Member::is_employer() || !jm_is_enable_job_package_resume_cf() || !isset($package['product_id'])){
            return;
        }
        $all_job_package_resume_cf = jm_get_resume_custom_fields_option('job_package_resume_fields', array());
        $package_resume_cf = jm_get_job_package_resume_cf($package['product_id']);
        foreach ($all_job_package_resume_cf as $field_name):

            $field = jm_get_all_resume_field_for_job_package($field_name);

            if(empty($field)){
                continue;
            }
            $field_label = (isset($field['label_translated']) && !empty($field['label_translated']))? $field['label_translated'] : $field['label'];
            ?>
                <div class="col-xs-6"><strong><?php echo sprintf(__('Visible %s Resume Details','noo'),$field_label) ?></strong></div>
                <div class="col-xs-6"><?php echo in_array($field_name,$package_resume_cf)? __('Yes','noo'): __('No','noo');?></div>
         <?php endforeach;
    }
    add_action( 'jm_manage_plan_features_list', 'jm_employer_manager_plan_resume_cf_features_view' );
endif;

if( !function_exists('jm_job_package_cf_options') ) :
	function jm_job_package_cf_options() {
		$job_package = jm_get_job_custom_fields_option('job_package', '0');
		$job_package_fields = jm_get_job_custom_fields_option('job_package_fields', array());
		?>
		<table class="form-table" cellspacing="0">
			<tbody>
				<tr>
					<th>
						<?php _e('Integrate Custom Field with Job Package','noo') ?>
					</th>
					<td>
						<input type="hidden" name="noo_job_custom_field[__options__][job_package]" value="0" />
						<input type="checkbox" name="noo_job_custom_field[__options__][job_package]" value="1" <?php checked( $job_package ); ?> id="job-package-cf-enabled"/><br/>
						<em><?php echo __('Enable this function and you can decide which fields employer can use with each Job Package', 'noo'); ?></em>
					</td>
				</tr>
				<tr class="job-package-cf">
					<th>
						<?php _e('Fields to add to Job Package','noo'); ?>
					</th>
					<td>
						<?php $custom_fields = jm_get_job_custom_fields(); ?>
						<select class="noo-admin-chosen" name="noo_job_custom_field[__options__][job_package_fields][]" multiple="multiple" style="width: 500px;max-width: 100%;">
							<?php foreach ($custom_fields as $key => $field) : ?>
								<option <?php selected( in_array( $field['name'], $job_package_fields ), true ); ?> value="<?php echo $field['name']; ?>"><?php echo $field['label']; ?></option>
							<?php endforeach; ?>
						</select>
						<br/><em><?php echo __('Fields selected here will required buying specific Job Package. Please continue edit on Job Package Products.', 'noo'); ?></em>
					</td>
				</tr>
			</tbody>
		</table>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$('#job-package-cf-enabled').change(function(event) {
					if( $(this).is(':checked') ) {
						$('.job-package-cf').show();
					} else {
						$('.job-package-cf').hide();
					}
				});

				$('#job-package-cf-enabled').change();
			});
		</script>
		<?php
	}

	add_action( 'jm_job_custom_fields_setting_options', 'jm_job_package_cf_options' );
endif;

