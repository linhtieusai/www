<?php 
get_header(); 

do_action('noo_job_archive_before_content');
?>
<div class="container-wrap">
    <div class="main-content container-boxed max offset">
        <div class="row">
            <div class="<?php noo_main_class(); ?>" role="main">
            <div class="noo-heading-search">
                <form id="noo-heading-search-form" method="get" action="<?php echo esc_url( apply_filters('noo_heading_search_form_action_url', get_post_type_archive_link( 'noo_job' )) ); ?>">
                    <div class="row">
                        <div class="col-sm-4">
                            <label class="noo-form-label">
                                <?php echo esc_html__( 'Search Job Now:', 'noo' ); ?>
                            </label>
                            <input type="text" name="s" class="noo-form-control" value="<?php echo get_search_query(); ?>" placeholder="<?php echo esc_html__( 'Enter keywords...', 'noo' ); ?>">
                        </div>
                            <div class="col-sm-3">
                                <?php jm_job_advanced_search_field( 'job_location', $disable_multiple );?>
                            </div>
                          
                        <?php do_action('noo_heading_job_search'); ?>
                        <?php if($show_address):
                            $map_type = jm_get_location_setting('map_type','');
                            $address = isset($_GET['_full_address']) ? $_GET['_full_address'] :'';
                            ?>
                            <div class="col-sm-3 mb15">
                                <div class="form-group">
                                    <?php if ($map_type == 'google'):
                                       wp_enqueue_script('location-picker');           
                                    ?>
                                    <div class="noo-location-picker-field-wrap">
                                        <label for="noo-mb-location-address" class="control-label">
                                            <?php esc_html_e('Location', 'noo'); ?>
                                        </label>
                                        <input id="noo-mb-location-address" class="noo-mb-location-address noo-form-control" type="text" name="_full_address" value="<?php esc_attr_e($address);?>" placeholder="<?php echo esc_html__('Enter an exact address.', 'jobica') ?>" />
                                        <input type="hidden" class="noo-mb-lat" name="_full_address_lat">
                                        <input type="hidden" class="noo-mb-lon" name="_full_address_lon">
                                        <div class="noo-mb-job-location" id="_full_address" data-lat data-lon style="height: 300px;">
                                        </div>
                                    </div>
                                    <?php elseif ($map_type == 'bing'): 
                                        wp_enqueue_script('bing-map');?>
                                        <div class="noo-location-picker-field-wrap">
                                            <div class="heading_map_type">
                                                <label for="noo-mb-location-address" class="control-label">
                                                    <?php esc_html_e('Location', 'noo'); ?>
                                                </label>
                                                <input id="noo-mb-location-address-heading" class="noo-mb-location-address noo-form-control" type="text" name="_full_address" value="<?php echo esc_html($address); ?>" placeholder="<?php echo esc_html__('Enter an exact address.', 'jobica') ?>"/>
                                            </div>
                                        <input type="hidden" class="noo-mb-lat" name="_full_address_lat" id="noo-mb-lat">
                                        <input type="hidden" class="noo-mb-lon" name="_full_address_lon" id="noo-mb-lon">
                                        <div class="noo-mb-job" data-id="_full_address">
                                            <div id="_full_address"></div>
                                        </div>
                                    </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        <?php endif;?>
                        <div class="col-sm-2">
                            <label>&nbsp;</label>
                            <button style="display: block;" type="submit" class="btn btn-primary noo-btn-search"><?php echo esc_html__( 'Search', 'noo' ); ?></button>
                        </div>
                    </div>
                    <!-- <input type="hidden" name="post_type" value="noo_job"> -->
                </form>
                <?php if ( ! empty( $heading_text ) ): ?>
                    <div class="noo-search-html">
                        <?php echo $heading_text; ?>
                    </div>
                <?php endif; ?>
            </div>
          
                <?php                
                do_action('noo_job_archive_content');
                
                if ( noo_get_option( 'noo_jobs_featured', false ) && is_post_type_archive( 'noo_job' ) && ! is_search() ) {
                    echo do_shortcode( '[noo_jobs show=featured posts_per_page=' . noo_get_option( 'noo_jobs_featured_num', 4 ) . ' title="' . __( 'Featured Jobs', 'noo' ) . '" no_content="none" show_pagination="yes" choice_paginate="nextajax" css_class=" featured-jobs"]' );
                }
                
                jm_job_loop( array(
                    'paginate'      => noo_get_option( 'noo_jobs_list_pagination_style', '' ),
                    'title'         => '',
                    'display_style' => noo_job_list_display_type()
                ) );
                ?>
            </div> <!-- /.main -->
            <?php get_sidebar(); ?>
        </div><!--/.row-->
    </div><!--/.container-boxed-->
</div><!--/.container-wrap-->

<?php 
do_action('noo_job_archive_after_content');

get_footer(); 
?>
