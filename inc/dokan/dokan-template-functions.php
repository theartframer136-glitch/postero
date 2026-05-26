<?php

if(!function_exists('postero_dokan_sold_store')){
    function postero_dokan_sold_store(){
        global $product;
        $vendor       = dokan_get_vendor_by_product( $product );
        if(!$vendor->id){
            return;
        }
        $store_info   = $vendor->get_shop_info();
        ?>
        <div class="sold-by-meta">
            <span class="sold-by-label"><?php esc_html_e( 'Sold By:', 'postero' ); ?> </span>
            <a href="<?php echo esc_attr( $vendor->get_shop_url() ); ?>"><?php echo esc_html( $store_info['store_name'] ); ?></a>
        </div>
        <?php
    }
}

if (!function_exists('postero_add_vendor_info_on_product_single_page')) {
    function postero_add_vendor_info_on_product_single_page() {
        global $product;
        $vendor       = dokan_get_vendor_by_product( $product );
        if(!$vendor->id){
            return;
        }
        $store_info   = $vendor->get_shop_info();
        $store_rating = $vendor->get_rating();
        $show_vendor_info = dokan_get_option( 'show_vendor_info', 'dokan_appearance', 'off' );
        if ( 'on' === $show_vendor_info ) {
            ?>
            <div class="dokan-vendor-info-wrap">
                <div class="dokan-vendor-image">
                    <img src="<?php echo esc_url( $vendor->get_avatar() ); ?>" alt="<?php echo esc_attr( $store_info['store_name'] ); ?>">
                </div>
                <div class="dokan-vendor-info">
                    <div class="dokan-vendor-name">
                        <h5><?php echo esc_html( $store_info['store_name'] ); ?></h5>
                        <?php apply_filters( 'dokan_product_single_after_store_name', $vendor ); ?>
                    </div>
                    <div class="dokan-vendor-rating">
                        <?php echo wp_kses_post( dokan_generate_ratings( $store_rating['rating'], 5 ) ); ?>
                    </div>
                    <a class="dokan-button-vendor" href="<?php echo esc_attr( $vendor->get_shop_url() ); ?>">
                        <?php echo esc_html__('View Vendor', 'postero')?>
                    </a>
                </div>
            </div>
            <?php
        }
        else{
            return;
        }
    }
}
if (!function_exists('postero_dokan_generate_ratings')) {
    function postero_dokan_generate_ratings($rating, $stars)
    {
        $result = '';
        $rating = wc_format_decimal(floatval($rating), 2);

        for ($i = 1; $i <= $stars; $i++) {
            if ($rating >= $i) {
                $result .= "<i class='postero-icon-star'></i>";
            } elseif ($rating > ($i - 1) && $rating < $i) {
                $result .= "<i class='postero-icon-star'></i>";
            } else {
                $result .= "<i class=' postero-icon-star-empty postero-icon-star'></i>";
            }
        }

        return apply_filters('dokan_generate_ratings', $result);
    }
}


if ( ! function_exists( 'dokan_pagination_nav' ) ) {
    /**
     * Display navigation to next/previous pages when applicable
     */
    function dokan_pagination_nav( $nav_id, $query = null ) {
        global $wp_query, $post;

        if ( $query ) {
            $wp_query = $query; //phpcs:ignore
        }

        // Don't print empty markup on single pages if there's nowhere to navigate.
        if ( is_single() ) {
            $previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
            $next = get_adjacent_post( false, '', false );

            if ( ! $next && ! $previous ) {
                return;
            }
        }

        // Don't print empty markup in archives if there's only one page.
        if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) ) {
            return;
        }

        $nav_class = 'site-navigation paging-navigation';
        if ( is_single() ) {
            $nav_class = 'site-navigation post-navigation';
        }
        ?>
        <nav role="navigation" id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo esc_attr( $nav_class ); ?>">
            <?php if ( is_single() ) : // navigation links for single posts ?>
                <ul class="pager">
                    <li class="previous">
                        <?php previous_post_link( '%link', _x( '&larr;', 'Previous post link', 'postero' ) . ' %title' ); ?>
                    </li>
                    <li class="next">
                        <?php next_post_link( '%link', '%title ' . _x( '&rarr;', 'Next post link', 'postero' ) ); ?>
                    </li>
                </ul>
            <?php endif; ?>

            <?php if ( $wp_query->max_num_pages > 1 && ( dokan_is_store_page() || is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>
                <?php dokan_page_nav( '', '', $wp_query ); ?>
            <?php endif; ?>

        </nav>
        <?php
    }
}

if ( ! function_exists( 'dokan_page_nav' ) ) {

    function dokan_page_nav( $before, $after, $wp_query ) {
        if ( ! ( $wp_query instanceof WP_Query ) ) {
            return;
        }

        $posts_per_page = intval( get_query_var( 'posts_per_page' ) );
        $paged          = intval( get_query_var( 'paged' ) );
        $numposts       = $wp_query->found_posts;
        $max_page       = $wp_query->max_num_pages;

        if ( $numposts <= $posts_per_page ) {
            return;
        }

        if ( empty( $paged ) || $paged === 0 ) {
            $paged = 1;
        }

        $pages_to_show         = 7;
        $pages_to_show_minus_1 = $pages_to_show - 1;
        $half_page_start       = floor( $pages_to_show_minus_1 / 2 );
        $half_page_end         = ceil( $pages_to_show_minus_1 / 2 );
        $start_page            = $paged - $half_page_start;

        if ( $start_page <= 0 ) {
            $start_page = 1;
        }

        $end_page = $paged + $half_page_end;

        if ( ( $end_page - $start_page ) !== $pages_to_show_minus_1 ) {
            $end_page = $start_page + $pages_to_show_minus_1;
        }

        if ( $end_page > $max_page ) {
            $start_page = $max_page - $pages_to_show_minus_1;
            $end_page = $max_page;
        }

        if ( $start_page <= 0 ) {
            $start_page = 1;
        }

        echo wp_kses_post( $before ) . '<div class="dokan-pagination-container"><ul class="dokan-pagination page-numbers">';
        if ( $paged > 1 ) {
            $first_page_text = 'prev';
            echo '<li><a class="page-number prev" href="' . esc_url( get_pagenum_link() ) . '" title="First">' . esc_html( $first_page_text ) . '</a></li>';
        }

        for ( $i = $start_page; $i <= $end_page; $i++ ) {
            if ( (int) $i === $paged ) {
                echo '<li class="active"><a class="page-number" href="#">' . esc_html( $i ) . '</a></li>';
            } else {
                echo '<li><a class="page-number" href="' . esc_url( get_pagenum_link( $i ) ) . '">' . esc_html( number_format_i18n( $i ) ) . '</a></li>';
            }
        }

        if ( (int) $paged < $max_page ) {
            $last_page_text = 'next';
            echo '<li><a class="page-number next" href="' . esc_url( get_pagenum_link( $max_page ) ) . '" title="Last">' . esc_html( $last_page_text ) . '</a></li>';
        }

        echo '</ul></div>' . wp_kses_post( $after );
    }

}
