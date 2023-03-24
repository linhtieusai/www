<?php do_action('noo_member_manage_plan_before'); ?>
<?php
	$package = array();
	$package_page_id = '';
    $user_id =  get_current_user_id();
	if( Noo_Member::is_employer() ) {
		$package =  jm_get_package_info();
		$package_page_id = Noo_Job_Package::get_setting( 'package_page_id' );
        $order_status = get_user_meta($user_id,'_order_status',true);
	} elseif( Noo_Member::is_candidate() ) {
		$package = jm_get_resume_posting_info();
		$package_page_id = Noo_Resume_Package::get_setting( 'resume_package_page_id' );
        $order_status = get_user_meta($user_id,'_order_resume_status',true);
	}
?>

<?php do_action('before_manage_plan', $package); ?>
<div class="member-plan">
	<?php if(empty($package)) : ?>
        <?php if($order_status =='pending'): ?>
            <p class="no-plan-package text-center"><?php _e('Your package pending payment','noo') ?></p>
            <div class="member-plan-choose">
                    <a class="btn btn-primary " href="#"> <?php _e('Package Pending Payment','noo') ?></a>
            </div>
         <?php else: ?>
            <p class="no-plan-package text-center"><?php _e('You have no active packages','noo') ?></p>
            <div class="member-plan-choose">
                <a class="btn btn-lg btn-primary" href="<?php echo esc_url(get_permalink( $package_page_id ))?>"><?php _e('Choose a Package','noo')?></a>
            </div>
        <?php endif; ?>
	<?php else : ?>
		<div class="row">
			<?php
			$attachments = '';
			if( isset( $package['product_id'] ) && !empty( $package['product_id'] ) ) :?>
				<div class="col-xs-6"><strong><?php _e('Plan','noo')?></strong></div>
				<div class="col-xs-6"><?php echo esc_html(get_the_title(absint($package['product_id']))) ?></div>
				<?php 
					$product        = wc_get_product($package['product_id']);
	            	$attachments    = $product ? $product->get_downloads() : array();
	            ?>
			<?php endif;?>
			
			<?php if( Noo_Member::is_employer() ) : ?>
				<?php
				$is_unlimited = $package['job_limit'] >= 99999999;
				$job_limit_text = $is_unlimited ? __('Unlimited', 'noo') : sprintf( _n( '%d job', '%d jobs', $package['job_limit'], 'noo' ), number_format_i18n( $package['job_limit'] ) );
				$job_added = jm_get_job_posting_added();
				?>
				<?php if( $is_unlimited || $package['job_limit'] > 0 ) : ?>
					<div class="col-xs-6"><strong><?php _e('Job Limit','noo')?></strong></div>
					<div class="col-xs-6"><?php echo $job_limit_text; ?></div>
					<div class="col-xs-6"><strong><?php _e('Job Added','noo')?></strong></div>
					<div class="col-xs-6"><?php echo $job_added > 0 ? sprintf( _n( '%d job', '%d jobs', $job_added, 'noo' ), number_format_i18n( $job_added ) ) : __( '0 job', 'noo'); ?></div>
					<div class="col-xs-6"><strong><?php _e('Job Duration','noo')?></strong></div>
					<div class="col-xs-6"><?php echo sprintf( _n( '%s day', '%s days', $package['job_duration'], 'noo' ), number_format_i18n( $package['job_duration'] ) ); ?></div>
                    <?php if(isset($package['job_refresh']) && !empty(($package['job_refresh']))): ?>
                        <div class="col-xs-6"><strong><?php _e('Job Refresh','noo')?></strong></div>
                        <?php if($package['job_refresh'] >= 99999999){?>
                        	<div class="col-xs-6"><?php echo esc_html__('Unlimited','noo');?></div>
                        <?php } else{?>
	                        	<div class="col-xs-6"><?php echo sprintf( _n( '%d time', '%d times', $package['job_refresh'], 'noo' ), number_format_i18n( $package['job_refresh'] ) ); ?>
	                        		
	                        	</div>
                        <?php }?>	
                    <?php endif; ?>
				<?php endif; ?>
				<?php if( isset( $package['job_featured'] ) && !empty( $package['job_featured'] ) ) : ?>
					<div class="col-xs-6"><strong><?php _e('Featured Job limit','noo')?></strong></div>
					<?php if($package['job_featured'] >= 99999999){?>
						<div class="col-xs-6"><?php echo esc_html__('Unlimited','noo');?></div>
					<?php }else{
						$feature_job_remain = jm_get_feature_job_remain();?>
						<div class="col-xs-6"><?php echo sprintf( _n( '%d job', '%d jobs', $package['job_featured'], 'noo' ),( $package['job_featured']) ); ?>
							<?php if( $feature_job_remain < $package['job_featured'] ) echo '&nbsp;' . sprintf( __('( %d remains )', 'noo'), $feature_job_remain ); ?>
						</div>
					<?php }?>
				<?php endif; ?>
				<?php if( isset( $package['company_featured'] ) && $package['company_featured'] ) : ?>
					<div class="col-xs-6"><strong><?php _e('Featured Company','noo')?></strong></div>
					<div class="col-xs-6"><?php _e('Yes','noo'); ?></div>
				<?php endif; ?>
                <?php if(isset( $package['download_resume_limit']) && $package['download_resume_limit']): ?>
                    <div class="col-xs-6"><strong><?php _e('Resume Download Limit','noo') ?></strong></div>
					<?php if($package['download_resume_limit'] >= 99999999){?>
						<div class="col-xs-6"><?php echo esc_html__('Unlimited','noo');?></div>
					<?php } else{
                    	$download_cv_remain = jm_get_download_cv_remain();					
					?>
                    	<div class="col-xs-6"><?php echo sprintf (_n('%d time to download','%d times to download',$package['download_resume_limit'],'noo'),$package['download_resume_limit']); ?>

	                        <?php if( $download_cv_remain < $package['download_resume_limit'] ) echo '&nbsp;' . sprintf( __('( %d remains )', 'noo'), $download_cv_remain ); ?>
	                    </div>
	                <?php }?>
                <?php endif; ?>
                <?php if (is_array($attachments) && !empty($attachments)) : ?>
                    <div class="col-xs-6"><strong><?php esc_html_e('Attachments','noo')?></strong></div>
                    <div class="col-xs-6">
                        <?php 
                        foreach($attachments as $value) {?>
                           <a class="attach-item" href="<?php echo esc_url($value['file'])?>" ><i class="fa fa-paperclip" aria-hidden="true"></i> <?php echo esc_html($value['name'])?></a>
                        <?php }?>
                    </div>
                <?php endif; ?>
			<?php else : ?>
				<?php
				$is_unlimited = $package['resume_limit'] >= 99999999;
				$resume_limit_text = $is_unlimited ? __('Unlimited', 'noo') : sprintf( _n( '%d resume', '%d resumes', $package['resume_limit'], 'noo' ), number_format_i18n( $package['resume_limit'] ) );
				$resume_added = jm_get_resume_posting_added();
				$resume_featured_remain = jm_get_feature_resume_remain();
				$remain_refresh_resume = noo_get_resume_refresh_remain();

				
				?>
				<?php if( $is_unlimited || $package['resume_limit'] > 0 ) : ?>
					<div class="col-xs-6"><strong><?php _e('Resume Limit','noo')?></strong></div>
					<div class="col-xs-6"><?php echo $resume_limit_text; ?></div>
					<div class="col-xs-6"><strong><?php _e('Resume Added','noo')?></strong></div>
					<div class="col-xs-6"><?php echo $resume_added > 0 ? sprintf( _n( '%s resume', '%s resumes', $resume_added, 'noo'), $resume_added ) : __( '0 resume', 'noo'); ?></div>
				<?php endif; ?>
                <?php if(isset($package['resume_featured']) && $package['resume_featured']): ?>
                    <div class="col-xs-6"><strong><?php _e('Resume Featured','noo') ?></strong></div>
                    <div class="col-xs-6"><?php echo sprintf(_n('%d resume','%d resumes',$package['resume_featured'],'noo'),$package['resume_featured']) ?><?php if($resume_featured_remain < $package['resume_featured']) echo '&nbsp;'.sprintf( __('(%d remains)','noo'),$resume_featured_remain); ?></div>
                <?php endif; ?>
                <?php if(isset($package['resume_refresh']) && $package['resume_refresh']): ?>
                    <div class="col-xs-6"><strong><?php _e('Resume Refresh','noo') ?></strong></div>
                    <div class="col-xs-6"><?php echo sprintf(_n('%d resume','%d resumes',$package['resume_refresh'],'noo'),$package['resume_refresh']) ?><?php if($remain_refresh_resume < $package['resume_refresh']) echo '&nbsp;'.sprintf( __('(%d remains)','noo'),$remain_refresh_resume); ?></div>
                <?php endif; ?>
			<?php endif; ?>
				<?php if (is_array($attachments) && !empty($attachments)) : ?>
                    <div class="col-xs-6"><strong><?php esc_html_e('Attachments','noo')?></strong></div>
                    <div class="col-xs-6">
                        <?php 
                        foreach($attachments as $value) {?>
                           <a class="attach-item" href="<?php echo esc_url($value['file'])?>" ><i class="fa fa-paperclip" aria-hidden="true"></i> <?php echo esc_html($value['name'])?></a>
                        <?php }?>
                    </div>
                <?php endif; ?>
			<?php do_action('jm_manage_plan_features_list', $package ); ?>
			<?php if( isset( $package['created'] ) && !empty( $package['created'] ) ):?>
				<div class="col-xs-6"><strong><?php _e('Date Activated','noo')?></strong></div>
				<div class="col-xs-6"><?php echo mysql2date(get_option('date_format'), $package['created']) ?></div>
			<?php elseif( isset( $package['counter_reset'] ) && !empty( $package['counter_reset'] ) ) :?>
				<div class="col-xs-12 text-center"><?php echo sprintf( _n( 'Your counter will be reset every %d month', 'Your counter will be reset every %d months', $package['counter_reset'], 'noo'), absint( $package['counter_reset'] ) );?></div>
			<?php endif;?>
			<?php if( isset( $package['expired'] ) && !empty( $package['expired'] ) ):?>
				<div class="col-xs-6"><strong><?php _e('Expires On','noo')?></strong></div>
				<div class="col-xs-6"><?php echo date_i18n( get_option('date_format'), $package['expired'] ); ?></div>
			<?php endif;?>
		</div>
		<?php if(jm_is_woo_job_posting()) : ?>
			<div class="member-plan-choose">
                <?php if($order_status=='pending'): ?>
                    <a class="btn btn-primary " href="#"> <?php _e('New Package Pending Payment','noo') ?></a>
                    <br/>
                <?php endif; ?>
                <br/>
				<a class="btn btn-lg btn-primary" href="<?php echo esc_url(get_permalink( $package_page_id ) )?>"><?php _e('Upgrade Package','noo')?></a>
				<br/>
				<p><em><?php echo __('Note: you will lose your current Package if you Upgrade', 'noo' ); ?></em></p>
				<hr/>
				<a href="<?php echo get_permalink(wc_get_page_id( 'myaccount' ))?>" class="btn btn-default"><?php _e('Order history', 'noo'); ?></a>
			</div>
		<?php endif;?>
	<?php endif; ?>
</div>
<?php do_action('after_manage_plan', $package); ?>