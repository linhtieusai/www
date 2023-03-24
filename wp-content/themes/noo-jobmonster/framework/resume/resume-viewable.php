<?php
if( !function_exists('jm_viewable_resume_enabled') ) :
	function jm_viewable_resume_enabled() {
		$max_viewable_resumes = jm_get_resume_setting('max_viewable_resumes', 1);

		return apply_filters( 'jm_viewable_resume_enabled', $max_viewable_resumes >= 0 );
	};
endif;

if( !function_exists('jm_can_view_resume') ) :
	function jm_can_view_resume( $resume_id = null, $is_loop = false ) {
		if( $is_loop ) {
			$can_view_resume = jm_can_view_resumes_list();
		} else {
			$can_view_resume = jm_can_view_single_resume( $resume_id );
		}

		return apply_filters( 'jm_can_view_resume', $can_view_resume, $resume_id, $is_loop );
	};
endif;

if( !function_exists('jm_can_view_resumes_list') ) :
	function jm_can_view_resumes_list() {
		// Administrator can view all resumes
		if( 'administrator' == Noo_Member::get_user_role(get_current_user_id()) )
			return true;
		
		$can_view_resume = false;
		
		$can_view_resume_setting = jm_get_action_control('view_and_search_resume');
		switch( $can_view_resume_setting ) {
			case 'public':
				$can_view_resume = true;
				break;
			case 'user':
				$can_view_resume = Noo_Member::is_logged_in();
				break;
			case 'employer':
				$can_view_resume = Noo_Member::is_employer();
				break;
			case 'package': // @TODO: move to specific file for resume viewable controlled by job package
				if( Noo_Member::is_employer() ) {
					$package = jm_get_package_info();
					$can_view_resume = ( isset( $package['can_view_resume'] ) && $package['can_view_resume'] === '1' ) && !jm_is_resume_view_expired();
				}
				break;
		}

		return apply_filters( 'jm_can_view_resumes_list', $can_view_resume );
	};
endif;

if( !function_exists('jm_can_view_single_resume') ) :
	function jm_can_view_single_resume( $resume_id = null ) {
		if( empty( $resume_id ) ) 
			return false;

		// Resume's author can view his/her resume
		$candidate_id = get_post_field( 'post_author', $resume_id );
		if( $candidate_id == get_current_user_id() ) {
			return true;
		}

		$can_view_resume = false;
        $blocked_company = true;
        $enable_block_company = jm_get_action_control('enable_block_company');
        if($enable_block_company=='enable'){
            $list_company_block = !empty( $candidate_id) ? get_user_meta( $candidate_id,'block_company',true) : array();
            $company_block = (!empty($list_company_block)) ? $list_company_block : array();
            $employer_id = array(jm_get_employer_company(get_current_user_id()));
            $blocked_company = !in_array($employer_id,$company_block);
        }
		// Administrator can view all resumes
		if( 'administrator' == Noo_Member::get_user_role(get_current_user_id()) ) {
			$can_view_resume = true;
		} elseif( isset($_GET['application_id'] ) && !empty($_GET['application_id']) ) {
			// Employers can view resumes from their applications

			$job_id = get_post_field( 'post_parent', $_GET['application_id'] );
			$company_id = noo_get_post_meta($job_id,'_company_id');
			$employer_id = get_post_field('post_author',$company_id);
			if( $employer_id == get_current_user_id() ) {
				$attachement_resume_id = noo_get_post_meta( $_GET['application_id'], '_resume', '' );
				$can_view_resume = $resume_id == $attachement_resume_id;
				$blocked_company = true;

			}
		}
		if( !$can_view_resume ) {
			$viewable = !jm_viewable_resume_enabled() || ( 'yes' == noo_get_post_meta( $resume_id, '_viewable', '' ) );

			$can_view_resume_setting = jm_get_action_control('view_resume');
			switch( $can_view_resume_setting ) {
				case 'public':
					$can_view_resume = $viewable;
					break;
				case 'user':
					$can_view_resume = Noo_Member::is_logged_in();
					break;
				case 'employer':
					$can_view_resume = $viewable && Noo_Member::is_employer() && $blocked_company;
					break;
				case 'package': 
				    // @TODO: move to specific file for resume viewable controlled by job package
					if( Noo_Member::is_employer() ) {
						$package = jm_get_package_info();
						$can_view_resume = ( isset( $package['can_view_resume'] ) && $package['can_view_resume'] === '1' ) && !jm_is_resume_view_expired() && ( jm_get_resume_view_remain() != 0 ) && $blocked_company;
					}
					break;
			}
		}
		return apply_filters( 'jm_can_view_single_resume', $can_view_resume, $resume_id );
	};
endif;
if( !function_exists('jm_get_cannot_view_resume_message') ) :
	function jm_get_cannot_view_resume_message( $resume_id = 0 ) {
		$title = '';
		$link = '';

		$viewable = !empty( $resume_id ) ? 'yes' == noo_get_post_meta( $resume_id, '_viewable' ) : true;
		if( !$viewable ) {
			$title = __('This resume is private.','noo');
		} else {
			$can_view_resume_setting = jm_get_action_control('view_resume');
			switch( $can_view_resume_setting ) {
				case 'public':
					$title = __( 'There\'s an unknown error. Please retry or contact Administrator.<br />', 'noo' );
					break;
				case 'user':
					$title = __('Only logged in users can view resumes.<br />','noo');
					if( !Noo_Member::is_logged_in() ) {
						$link = Noo_Member::get_login_url();
						$link = '<a href="' . esc_url( $link ) . '" class="btn btn-primary member-login-link">' . __( 'Login', 'noo' ) . '</a>';
					}
					break;
				case 'employer':
					$title = __('Only employers can view resumes Or Your Company has been blocked by Candidate.<br />','noo');
					if( !Noo_Member::is_logged_in() ) {
						$link = Noo_Member::get_login_url();
						$link = '<a href="' . esc_url( $link ) . '" class="btn btn-primary member-login-link">' . __( 'Login as Employer', 'noo' ) . '</a>';
					} elseif( !Noo_Member::is_employer() ) {
						$link = Noo_Member::get_logout_url();
						$link = '<a href="' . esc_url( $link ) . '" class="btn btn-primary">' . __( 'Logout', 'noo' ) . '</a>';
					}
					break;
				case 'package':
					$title = __('Only employers with package can view resumes Or Your Company has been blocked by Candidate.<br />','noo');
					$link = Noo_Member::get_endpoint_url('manage-plan');

					if( !Noo_Member::is_logged_in() ) {
						$link = Noo_Member::get_login_url();
						$link = '<a href="' . esc_url( $link ) . '" class="btn btn-primary  member-login-link">' . __( 'Login as Employer', 'noo' ) . '</a>';
					} elseif( !Noo_Member::is_employer() ) {
						$link = Noo_Member::get_logout_url();
						$link = '<a href="' . esc_url( $link ) . '" class="btn btn-primary">' . __( 'Logout', 'noo' ) . '</a>';
					} else {
						$title = __('Your membership doesn\'t allow you to view the resumes.<br />','noo');
						$link = Noo_Member::get_endpoint_url('manage-plan');
						$link = '<a href="' . esc_url( $link ) . '" class="btn btn-primary">' . __( 'Click here to upgrade your Membership.', 'noo' ) . '</a>';
					}
					break;
			}
		}
		$params = apply_filters( 'jm_cannot_view_resume_message', compact( 'title', 'link' ), $resume_id );
		extract($params);

		$title = empty( $title ) ? __('You don\'t have permission to view resumes.','noo') : $title;

		return array( $title, $link );
	}
endif;