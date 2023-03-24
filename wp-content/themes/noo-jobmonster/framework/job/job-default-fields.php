<?php

if( !function_exists( 'jm_get_job_default_fields' ) ) :
	function jm_get_job_default_fields() {
		$default_fields = array(
				'_cover_image' => array(
					'name' => '_cover_image',
					'label' => __('Cover Image', 'noo'),
					'is_default' => true,
					'type' => 'single_image',
					'value'      => __( 'Recommend size: 1400x600px', 'noo' ),
					'allowed_type' => array(
						'single_image' => __( 'Single Image', 'noo' ),
					),
					'required' => false
				),
				'job_category' => array(
					'name' => 'job_category',
					'label' => __('Job Category', 'noo'),
					'is_default' => true,
                    'is_tax' => true,
					'type' => 'multiple_select',
					'allowed_type' => array(
						'select'			=> __('Select', 'noo'),
						'multiple_select'	=> __( 'Multiple Select', 'noo' ),
					),
					'required' => true
				),
				'job_type' => array(
					'name' => 'job_type',
					'label' => __('Job Type', 'noo'),
					'is_default' => true,
                    'is_tax' => true,
					'type' => 'select',
					'allowed_type' => array(
						'select'			=> __('Select', 'noo'),
						'multiple_select'	=> __( 'Multiple Select', 'noo' ),
						'radio'				=> __( 'Radio', 'noo' ),
						'checkbox'			=> __( 'Checkbox', 'noo' )
					),
					'required' => true
				),
				'job_location' => array(
					'name' => 'job_location',
					'label' => __('Job Location', 'noo'),
					'is_default' => true,
                    'is_tax' => true,
					'type' => 'multi_location_input',
					'allowed_type' => array(
						'multi_location_input'	=> __('Multiple Location with Input', 'noo'),
						'multi_location'		=> __('Multiple Location', 'noo'),
						'single_location_input'	=> __('Single Location with Input', 'noo'),
						'single_location'		=> __('Single Location', 'noo'),
					),
					'required' => true
				),
				'_full_address' => array(
					'name' => '_full_address',
					'label' => __('Address', 'noo'),
					'is_default' => true,
					'type' => 'text',
					'desc' => __('Enter Streets, Region, Locality, Country. eg: 1600 Chestnut Street, Philadelphia, PA, USA ', 'noo'),
					'allowed_type' => array(
						'text'			=> __('Text', 'noo'),
						'location_picker'			=> __('Map Picker', 'noo'),
					),
					'required' => false
				),
				'_postalcode' => array(
					'name' => '_postalcode',
					'label' => __('Postal Code', 'noo'),
					'is_default' => true,
					'type' => 'text',
					'allowed_type' => array(
						'text'			=> __('Text', 'noo'),
						'number'	    => __('Number', 'noo'),
					),
					'required' => false
				),
				'job_tag' => array(
					'name' => 'job_tag',
					'label' => __('Job Tag', 'noo'),
					'is_default' => true,
                    'is_tax' => true,
					'type' => 'multiple_select',
					'allowed_type' => array(
						'select'			    => __('Select', 'noo'),
						'multiple_select'	    => __( 'Multiple Select', 'noo' ),
						'radio'				    => __( 'Radio', 'noo' ),
						'checkbox'			    => __( 'Checkbox', 'noo' ),
                        'multi_job_tag_input'   => __('Multiple Job_Tag with Input','noo'),
					),
					'is_disabled' => 'yes',
					'required' => false
				),
				'_salary' => array(
					'name' => '_salary',
					'label' => __('Salary Offer', 'noo'),
					'is_default' => true,
					'type' => 'text',
					'allowed_type' => array(
						'text'			=> __('Text', 'noo'),
						'number'	    => __('Number', 'noo'),
						'select'			    => __('Select', 'noo'),
						'multiple_select'	    => __( 'Multiple Select', 'noo' ),
					),
					'required' => false
				),
				'_closing' => array(
					'name' => '_closing',
					'label' => __('Closing Date', 'noo'),
					'desc' => __('Set the Apply for Job expiry date. If left blank, will automatically get the Job\'s Expiration Date.', 'noo'),
					'is_default' => true,
					'type' => 'datepicker',
					'allowed_type' => array(
						'datepicker'			=> __('Date Picker', 'noo')
					),
					'required' => false
				),
			);

		return apply_filters( 'jm_job_default_fields', $default_fields );
	}
endif;

if( !function_exists( 'jm_get_job_taxonomies' ) ) :
	function jm_get_job_taxonomies() {
		return apply_filters( 'jm_job_taxonomies', array( 'job_category', 'job_location', 'job_type', 'job_tag' ) );
	}
endif;

if( !function_exists( 'jm_job_tax_field_params' ) ) :
	function jm_job_tax_field_params( $args = array(), $job_id = 0 )  {
		extract($args);

		if( in_array( $field['name'], jm_get_job_taxonomies() ) ) {
			$field_id = str_replace('job_', '', $field['name']);
			$field_value = array();
			$terms = get_terms( $field['name'], array( 'hide_empty' => 0 ) );
			foreach ($terms as $term) {
				if( is_taxonomy_hierarchical( $field['name'] ) ) {
					$field_value[] = $term->term_id . '|' . $term->name;
				} else {
					$field_value[] = $term->slug . '|' . $term->name;
				}
			}

			$field['value'] = $field_value;
			$field['no_translate'] = true;

			$value = array();
			if( !empty( $job_id ) ) {
				if( is_taxonomy_hierarchical( $field['name'] ) ) {
					$value = wp_get_object_terms( $job_id, $field['name'], array( 'fields' => 'ids' ) );
				} else {
					$value = wp_get_object_terms( $job_id, $field['name'], array( 'fields' => 'slugs' ) );
				}
			}

			if( empty( $field['type'] ) || $field['type'] == 'text' ) {
				$default_fields = jm_get_job_default_fields();
				$field['type'] = $default_fields[$field['name']]['type'];
			}
		}

		return compact( 'field', 'field_id', 'value' );
	}
	
	add_filter( 'jm_job_render_form_field_params', 'jm_job_tax_field_params', 10, 2 );
endif;

if( !function_exists( 'jm_job_tax_search_field_params' ) ) :
	function jm_job_tax_search_field_params( $args = array(), $job_id = 0 )  {
		extract($args);
		$hide_empty_tax = jm_get_job_setting( 'hide_empty_tax','');
		if( in_array( $field['name'], jm_get_job_taxonomies() ) ) {
			$field_id = str_replace('job_', '', $field['name']);
			$field_value = array();
			$terms = get_terms( $field['name'], array( 'hide_empty' => (int)$hide_empty_tax) );
			foreach ($terms as $term) {
				$field_value[] = $term->slug . '|' . $term->name;
			}
			$field['value'] = $field_value;
			$field['no_translate'] = true;
			if( isset( $_GET[$field_id] ) && !empty( $_GET[$field_id] ) ) {
				$value = $_GET[$field_id];
			} else {
				if( is_tax( $field['name'] ) ) {
					global $wp_query;
					$term_id = $wp_query->get_queried_object_id();
					$term = get_term( $term_id, $field['name'] );
					$value = !empty( $term ) && !is_wp_error( $term ) ? $term->slug : '';
				}
			}

			if( empty( $field['type'] ) || $field['type'] == 'text' ) {
				$default_fields = jm_get_job_default_fields();
				$field['type'] = $default_fields[$field['name']]['type'];
			}
		}

		return compact( 'field', 'field_id', 'value' );
	}
	
	add_filter( 'jm_job_render_search_field_params', 'jm_job_tax_search_field_params' );
endif;

if( !function_exists( 'jm_job_get_tax_value' ) ) :
	function jm_job_get_tax_value( $job_id = 0, $field_id = 'job_category' )  {
		if( empty( $job_id ) ) return array();

		$value = array();
		$terms = get_the_terms( $job_id, $field_id );
		if( !empty( $terms ) && !is_wp_error( $terms ) ) {
			foreach ($terms as $term) {
				$value[] = '<a href="' . get_term_link($term->term_id,$field_id) . '"><em>' . $term->name . '</em></a>';
			}
		}

		return $value;
	}
endif;