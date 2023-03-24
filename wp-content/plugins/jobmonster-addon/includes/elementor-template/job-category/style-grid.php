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
<div <?php echo( $class ); ?>>
    <div class="noo-job-category-wrap <?php echo esc_attr( $style ); ?>">
        <div class="noo-job-category is-flex <?php echo esc_attr( $mobile_class . ' ' .$tablet_class.' '.$desktop_class) ;?>">
            <?php
            $i = 0;
            if ( $list_job_category == 'all' or $list_job_category == '' ) {
                $categories    = get_terms( 'job_category', array(
                    'orderby'    => 'NAME',
                    'order'      => 'ASC',
                    'hide_empty' => ('true' == $hide_empty) ? false : true,
                ) );
            } else {

                $categories    = get_terms( 'job_category', array(
                    'orderby'    => 'NAME',
                    'order'      => 'ASC',
                    'hide_empty' => ('true' == $hide_empty) ? false : true,
                    'include' => $list_job_category
                ) );
            }
            foreach ( $categories as $key => $cat ) :
                if ( $i >= $limit_category )
                    break;
                $cate_name = $cat->name;
                $job_count = $cat->count;
                $cate_link = get_term_link( $cat );
                $cate_link = apply_filters('noo_job_widget_shortcode_category_link', $cate_link, $cat);
                $icon_markers   = get_term_meta( $cat->term_id, 'icon_type', true );
                if ( empty( $icon_markers ) ) {
                    $icon_markers = 'fa-home';
                }
                ?>
                <div class="noo-grid-item ">
                    <div class="category-item">
                        <a href="<?php echo esc_url( $cate_link ); ?>">
                                <span class="icon">
                                    <i class="fa <?php echo esc_attr( $icon_markers ) ?>"></i>
                                </span>
                            <span class="title">
                                    <?php echo esc_html( $cate_name ); ?>
                                </span>
                            <?php if ( 'true' == $show_job_count ) : ?>
                                <span class="job-count">
                                        (<?php echo sprintf( _n( '%s Job', '%s Jobs', $job_count, 'noo' ), $job_count ); ?>)
                                    </span>
                            <?php endif; ?>
                        </a>
                    </div>
                </div>
                <?php $i++; endforeach;
            ?>
        </div>
    </div>
</div>