<?php
/**
 * style-1.php
 *
 * @author  : NooTheme
 * @since   : 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<div<?php echo( $class ); ?>>
    <div class="noo-job-category-wrap <?php echo esc_attr( $style ); ?>">
        <div class="noo-job-category is-flex <?php echo esc_attr( $mobile_class . ' ' .$tablet_class.' '.$desktop_class) ;?>">
            <?php

// error_log(print_r($list_job_location, true));

            $i = 0;
            if ( $list_job_location == 'all' or $list_job_location == '' ) {
                $locations    = get_terms( 'job_location', array(
                    'orderby'    => 'term_id',
                    'order'      => 'ASC',
                    'hide_empty' => ('true' == $hide_empty) ? false : true,
                ) );
            } else {
                $locations =  get_terms( 'job_location', array(
                    'orderby'    => 'term_id',
                    'order'      => 'ASC',
                    'hide_empty' => ('true' == $hide_empty) ? false : true,
                    'include' => $list_job_location
                ) );
            }
            
            foreach ( $locations as $key => $loc ) :
                if ( $i >= $limit_location )
                    break;
                $loc_name = $loc->name;
                $job_count = $loc->count;
                $loc_link = get_term_link( $loc );
                $icon_markers   = get_term_meta( $loc->term_id, 'icon_type', true );
                if ( empty( $icon_markers ) ) {
                    $icon_markers = 'fa-home';
                }
                ?>
                <div class="category-item noo-grid-item ">
                    <a href="<?php echo esc_url( $loc_link ); ?>">
                            <span class="icon">
                                <i class="fa <?php echo esc_attr( $icon_markers ) ?>"></i>
                            </span>
                        <span class="title">
                                <?php echo esc_html( $loc_name ); ?>
                            </span>
                        <?php if ( 'true' == $show_job_count ) : ?>
                            <span class="job-count">
                                    (<?php echo sprintf( _n( '%s Job', '%s Jobs', $job_count, 'noo' ), $job_count ); ?>)
                                </span>
                        <?php endif; ?>
                    </a>
                </div>
                <?php $i++; endforeach;
            ?>
        </div>
    </div>
</div>