<?php
/**
 * =================================================
 * Hook postero_page
 * =================================================
 */
add_action('postero_page', 'postero_page_header', 10);
add_action('postero_page', 'postero_page_content', 20);

/**
 * =================================================
 * Hook postero_single_post_top
 * =================================================
 */

/**
 * =================================================
 * Hook postero_single_post
 * =================================================
 */
add_action('postero_single_post', 'postero_post_header', 10);
add_action('postero_single_post', 'postero_post_content', 30);

/**
 * =================================================
 * Hook postero_single_post_bottom
 * =================================================
 */
add_action('postero_single_post_bottom', 'postero_post_taxonomy', 5);
add_action('postero_single_post_bottom', 'postero_single_author', 10);
add_action('postero_single_post_bottom', 'postero_post_nav', 15);
add_action('postero_single_post_bottom', 'postero_display_comments', 20);

/**
 * =================================================
 * Hook postero_loop_post
 * =================================================
 */
add_action('postero_loop_post', 'postero_post_header', 15);
add_action('postero_loop_post', 'postero_post_content', 30);

/**
 * =================================================
 * Hook postero_footer
 * =================================================
 */
add_action('postero_footer', 'postero_footer_default', 20);

/**
 * =================================================
 * Hook postero_after_footer
 * =================================================
 */

/**
 * =================================================
 * Hook wp_footer
 * =================================================
 */
add_action('wp_footer', 'postero_template_account_dropdown', 1);
add_action('wp_footer', 'postero_mobile_nav', 1);

/**
 * =================================================
 * Hook wp_head
 * =================================================
 */
add_action('wp_head', 'postero_pingback_header', 1);

/**
 * =================================================
 * Hook postero_before_header
 * =================================================
 */

/**
 * =================================================
 * Hook postero_before_content
 * =================================================
 */

/**
 * =================================================
 * Hook postero_content_top
 * =================================================
 */

/**
 * =================================================
 * Hook postero_post_content_before
 * =================================================
 */

/**
 * =================================================
 * Hook postero_post_content_after
 * =================================================
 */

/**
 * =================================================
 * Hook postero_sidebar
 * =================================================
 */
add_action('postero_sidebar', 'postero_get_sidebar', 10);

/**
 * =================================================
 * Hook postero_loop_after
 * =================================================
 */
add_action('postero_loop_after', 'postero_paging_nav', 10);

/**
 * =================================================
 * Hook postero_page_after
 * =================================================
 */
add_action('postero_page_after', 'postero_display_comments', 10);

/**
 * =================================================
 * Hook postero_woocommerce_before_shop_loop_item
 * =================================================
 */

/**
 * =================================================
 * Hook postero_woocommerce_before_shop_loop_item_title
 * =================================================
 */

/**
 * =================================================
 * Hook postero_woocommerce_after_shop_loop_item
 * =================================================
 */
