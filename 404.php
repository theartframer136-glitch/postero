<?php
get_header(); ?>

    <div id="primary" class="content">
        <main id="main" class="site-main">
            <div class="error-404 not-found">
                <div class="page-content">
                    <div class="error-image">
                        <img src="<?php echo get_theme_file_uri('assets/images/404/404-decor.png') ?>"
                             alt="<?php echo esc_attr__('404 Page', 'postero'); ?>">
                    </div>
                    <div class="error-content">
                        <div class="content">
                            <div class="page-title">
                                <h1 class="title"><?php esc_html_e('404', 'postero'); ?></h1>
                                <h3 class="sub-title"><?php esc_html_e('Page Not Found', 'postero') ?></h3>
                            </div>
                            <div class="error-text">
                                <p class="text"><?php esc_html_e('Sorry but the page you are looking for doesn’t exist.', 'postero') ?></p>
                            </div>
                            <div class="button-error">
                                <a href="javascript: history.go(-1)" class="elementor-button-type-link go-back"><?php esc_html_e('go back home', 'postero'); ?><i class="postero-icon-chevron-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div><!-- .page-content -->
            </div><!-- .error-404 -->
        </main><!-- #main -->
    </div><!-- #primary -->
<?php

get_footer();
