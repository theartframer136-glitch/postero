<?php
/**
 * Postero WooCommerce hooks
 *
 * @package postero
 */

/**
 * Layout
 *
 * @see  postero_before_content()
 * @see  postero_after_content()
 * @see  woocommerce_breadcrumb()
 * @see  postero_shop_messages()
 */

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

add_action('woocommerce_before_main_content', 'postero_before_content', 10);
add_action('woocommerce_after_main_content', 'postero_after_content', 10);


//Position label onsale
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);

/**
 * Products
 *
 * @see postero_upsell_display()
 * @see postero_single_product_pagination()
 */
add_action('woocommerce_single_product_summary', 'postero_single_product_after_title', 10);
add_action('woocommerce_single_product_summary', 'postero_woocommerce_product_themes', 2);
add_action('woocommerce_single_product_summary', 'postero_single_product_time_sale', 11);
add_action('woocommerce_single_product_summary', 'postero_single_product_extra', 45);

add_action('woocommerce_share', 'postero_social_share', 10);

$product_single_style = postero_get_theme_option('single_product_gallery_layout', 'horizontal');

if ($product_single_style === 'horizontal' || $product_single_style === 'vertical') {
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-slider');
}

if ($product_single_style === 'gallery') {
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
    add_filter('woocommerce_single_product_image_thumbnail_html', 'postero_woocommerce_single_product_image_thumbnail_html', 10, 2);
    add_action('woocommerce_single_product_summary_after', 'postero_output_product_data_accordion', 70);
}
add_theme_support('wc-product-gallery-lightbox');

if ($product_single_style === 'sticky') {
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
    add_action('woocommerce_single_product_summary_after', 'postero_output_product_data_accordion', 70);
    add_filter('woocommerce_single_product_image_thumbnail_html', 'postero_woocommerce_single_product_image_thumbnail_html_sticky', 10, 2);
}


if ($product_single_style === 'slider'){
    add_filter('woocommerce_single_product_image_thumbnail_html', 'postero_woocommerce_single_product_image_thumbnail_html', 10, 2);

}

add_action('postero_single_product_video_360', 'postero_single_product_video_360', 10);


/**
 * Cart fragment
 *
 * @see postero_cart_link_fragment()
 */

add_filter('woocommerce_add_to_cart_fragments', 'postero_cart_link_fragment');

remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
add_action('woocommerce_after_cart', 'woocommerce_cross_sell_display');

add_action('woocommerce_checkout_order_review', 'woocommerce_checkout_order_review_start', 5);
add_action('woocommerce_checkout_order_review', 'woocommerce_checkout_order_review_end', 15);

add_filter('woocommerce_get_script_data', function ($params, $handle) {
    if ($handle == "wc-add-to-cart") {
        $params['i18n_view_cart'] = '';
    }
    return $params;
}, 10, 2);

add_filter('woocommerce_gallery_thumbnail_size',function (){
    return 'medium';
});

add_filter('woocommerce_single_product_photoswipe_options', function ($args) {
    return array(
        'shareEl'               => false,
        'closeOnScroll'         => true,
        'history'               => false,
        'hideAnimationDuration' => 333,
        'showAnimationDuration' => 333,
        'showHideOpacity'       => true,
        'fullscreenEl'          => false,
    );
});
/*
 *
 * Layout Product
 *
 * */

add_filter('woosc_button_position_archive', '__return_false');
add_filter('woosq_button_position', '__return_false');
add_filter('woosw_button_position_archive', '__return_false');

function postero_include_hooks_product_blocks() {

    remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
    remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
    // Remove product content link
    remove_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

}

if (class_exists('WPCleverWoosc')) {
    remove_action('woocommerce_after_single_product_summary', [WPCleverWoosc::instance(), 'show_quick_table'], 19);
}

add_filter('woocommerce_loop_add_to_cart_link', function ($quantity, $product) {
    return '<div class="opal-add-to-cart-button">' . $quantity . '</div>';
}, 10, 2);


if (isset($_GET['action']) && $_GET['action'] === 'elementor') {
    return;
}

postero_include_hooks_product_blocks();
