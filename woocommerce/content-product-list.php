<?php

defined('ABSPATH') || exit;

global $product;

// Ensure visibility.
if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<li <?php wc_product_class('product-list', $product); ?>>
    <?php
    /**
     * Functions hooked in to postero_woocommerce_before_shop_loop_item action
     *
     */
    do_action('postero_woocommerce_before_shop_loop_item');


    ?>
    <div class="product-transition">
        <?php woocommerce_show_product_loop_sale_flash(); ?>
        <div class="product-image image-main">
            <?php
            /**
             * Functions hooked in to postero_woocommerce_before_shop_loop_item_title action
             *
             * @see woocommerce_template_loop_product_thumbnail - 15 - woo
             *
             *
             */
            do_action('postero_woocommerce_before_shop_loop_item_title');
            ?>
        </div>
    </div>
    <div class="product-caption">
        <?php woocommerce_template_loop_rating(); ?>
        <?php postero_woocommerce_product_themes(); ?>
        <?php woocommerce_template_loop_product_title(); ?>
        <?php woocommerce_template_loop_price(); ?>
        <?php postero_woocommerce_get_product_short_description(); ?>

        <?php postero_woocommerce_group_action();?>
    </div>
    <?php
    /**
     * Functions hooked in to postero_woocommerce_after_shop_loop_item action
     *
     */
    do_action('postero_woocommerce_after_shop_loop_item');
    ?>
</li>
