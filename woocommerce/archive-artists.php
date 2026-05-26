<?php
$product_style = postero_get_theme_option('wocommerce_block_style', 0) == 0 ? '' : postero_get_theme_option('wocommerce_block_style', 0);

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action('woocommerce_before_main_content');
?>
    <header class="woocommerce-products-author-header">
        <div class="author-header-caption">
            <?php
            $term = get_queried_object();
            $contact_form  = get_term_meta((int)$term->term_id, 'product_artist_contact_form', true);
            echo mas_wcbr_get_brand_thumbnail_image($term);
            ?>
            <h3 class="author-name"><?php woocommerce_page_title(true); ?></h3>
            <?php
            $facebook_url  = get_term_meta((int)$term->term_id, 'product_artist_facebook', true);
            $twitter_url   = get_term_meta((int)$term->term_id, 'product_artist_twitter', true);
            $instagram_url = get_term_meta((int)$term->term_id, 'product_artist_instagram', true);
            $pinterest_url = get_term_meta((int)$term->term_id, 'product_artist_pinterest', true);
            $website_url   = get_term_meta((int)$term->term_id, 'product_artist_website', true);
            ?>
            <ul class="author-social">
                <?php
                if ($facebook_url) echo '<li><a href="' . esc_url($facebook_url) . '">' . esc_html__('Facebook', 'postero') . '</a></li>';
                if ($twitter_url) echo '<li><a href="' . esc_url($twitter_url) . '">' . esc_html__('Twitter', 'postero') . '</a></li>';
                if ($instagram_url) echo '<li><a href="' . esc_url($instagram_url) . '">' . esc_html__('Instagram', 'postero') . '</a></li>';
                if ($pinterest_url) echo '<li><a href="' . esc_url($pinterest_url) . '">' . esc_html__('Pinterest', 'postero') . '</a></li>';
                if ($website_url) echo '<li><a href="' . esc_url($website_url) . '">' . esc_html__('Website', 'postero') . '</a></li>';
                ?>
            </ul>
        </div>
        <div class="author-banner">
            <?php
            $image_banner = get_term_meta((int)$term->term_id, 'product_artist_banner', true);
            if (!empty($image_banner)) {
                echo wp_get_attachment_image($image_banner, 'full');
            }
            ?>
        </div>
    </header>
    <div class="tabs-container artist-tabs">
        <ul class="tabs-nav">
            <li class="active"><a href="#tab1"><?php echo esc_html( _n('Poster', 'Posters', wc_get_loop_prop( 'total' ), 'postero')); ?></a></li>
            <?php

            if ($term && !empty($term->description)) {
                ?>
                <li><a href="#tab2"><?php echo esc_html__('About', 'postero'); ?></a></li>
                <?php
            }
            if ($contact_form) {
                ?>
                <li><a href="#tab3"><?php echo esc_html__('Contact', 'postero'); ?></a></li>
                <?php
            }
            ?>
        </ul>
        <div class="tabs-content">
            <div id="tab1" class="tab-pane active">
                <?php
                if (woocommerce_product_loop()) {

                    woocommerce_output_all_notices();
                    ?>
                    <div class="artist-title"><?php echo sprintf('%1s %2s', esc_html__('Posters By', 'postero'), woocommerce_page_title(false)); ?></div>

                    <?php

                    wc_set_loop_prop('columns', 4);

                    wc_set_loop_prop('product-class', 'postero-artist-products products');

                    woocommerce_product_loop_start();

                    if (wc_get_loop_prop('total')) {
                        while (have_posts()) {
                            the_post();

                            /**
                             * Hook: woocommerce_shop_loop.
                             */
                            do_action('woocommerce_shop_loop');
                            wc_get_template_part('content-product', $product_style);

                        }
                    }

                    woocommerce_product_loop_end();
                    ?>

                    <?php

                    /**
                     * Hook: woocommerce_after_shop_loop.
                     *
                     * @hooked woocommerce_pagination - 10
                     */
                    do_action('woocommerce_after_shop_loop');
                } else {
                    /**
                     * Hook: woocommerce_no_products_found.
                     *
                     * @hooked wc_no_products_found - 10
                     */
                    do_action('woocommerce_no_products_found');
                }
                ?>
            </div>
            <?php
            if ($term && !empty($term->description)) {
                ?>
                <div id="tab2" class="tab-pane">
                    <div class="artist-title"><?php echo esc_html__('Biography', 'postero'); ?></div>
                    <?php echo '<div class="term-description">' . wc_format_content(wp_kses_post($term->description)) . '</div>'; ?>
                </div>
                <?php
            }
            if ($contact_form) {
                ?>
                <div id="tab3" class="tab-pane">
                    <div class="artist-title"><?php echo esc_html__('Contact Artist', 'postero'); ?></div>
                    <?php
                    echo do_shortcode($contact_form);
                    ?>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
<?php


/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');