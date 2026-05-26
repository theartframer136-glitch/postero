<header id="masthead" class="site-header header-1" role="banner">
    <div class="header-container">
        <div class="header-main">
            <div class="header-left">
                <?php
                postero_site_branding();
                if (postero_is_woocommerce_activated()) {
                    ?>
                    <div class="site-header-cart header-cart-mobile">
                        <?php postero_cart_link(); ?>
                    </div>
                    <?php
                }
                ?>
                <?php postero_mobile_nav_button(); ?>
            </div>
            <div class="header-center">
                <?php postero_primary_navigation(); ?>
            </div>
            <div class="header-right desktop-hide-down">
                <div class="header-group-action">
                    <?php
                    postero_header_account();
                    if (postero_is_woocommerce_activated()) {
                        postero_header_cart();
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</header><!-- #masthead -->
