<?php 
get_header(); 
do_action('noo_resume_archive_before_content');
?>
<div class="container-wrap">
    <div class="main-content container-boxed max offset">
        <div class="row">
            <div class="<?php noo_main_class(); ?>" role="main">
                <?php
                
                do_action('noo_resume_archive_content');
                
                $can_view_resume_list = jm_can_view_resumes_list();
                
                if($can_view_resume_list){
                    if ( noo_get_option( 'noo_resume_archive_featured', false ) && is_post_type_archive( 'noo_resume' ) && ! is_search() ) {
                        echo do_shortcode('[noo_resume show="featured" title="' . __( 'Featured Resumes', 'noo' ) . '" no_content="none" show_pagination="yes"]');
                    }
                	
                    jm_resume_loop();
                	
                }else{
                    list($title, $link) = jm_cannot_view_list_resume();
                    ?>
                    <article class="resume">
                        <h3><?php echo $title; ?></h3>
                        <?php if( !empty( $link ) ) echo $link; ?>
                    </article>
                    <?php
                }
                ?>
            </div> <!-- /.main -->
            <?php get_sidebar(); ?>
        </div><!--/.row-->
    </div><!--/.container-boxed-->
</div><!--/.container-wrap-->

<?php 
do_action('noo_resume_archive_after_content');

get_footer(); 
?>
