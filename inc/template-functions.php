<?php

if (!function_exists('postero_display_comments')) {
    /**
     * Postero display comments
     *
     * @since  1.0.0
     */
    function postero_display_comments() {
        // If comments are open or we have at least one comment, load up the comment template.
        if (comments_open() || 0 !== intval(get_comments_number())) :
            comments_template();
        endif;
    }
}

if (!function_exists('postero_comment')) {
    /**
     * Postero comment template
     *
     * @param array $comment the comment array.
     * @param array $args the comment args.
     * @param int $depth the comment depth.
     *
     * @since 1.0.0
     */
    function postero_comment($comment, $args, $depth) {
        if ('div' === $args['style']) {
            $tag       = 'div';
            $add_below = 'comment';
        } else {
            $tag       = 'li';
            $add_below = 'div-comment';
        }
        ?>
        <<?php echo esc_attr($tag) . ' '; ?><?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?> id="comment-<?php comment_ID(); ?>">

        <div class="comment-body">
            <div class="comment-author vcard">
                <?php echo get_avatar($comment, 50); ?>
            </div>
            <?php if ('div' !== $args['style']) : ?>
            <div id="div-comment-<?php comment_ID(); ?>" class="comment-content">
                <?php endif; ?>
                <div class="comment-head">
                    <div class="comment-meta commentmetadata">
                        <?php printf('<cite class="fn">%s</cite>', get_comment_author_link()); ?>
                        <?php if ('0' === $comment->comment_approved) : ?>
                            <em class="comment-awaiting-moderation"><?php esc_attr_e('Your comment is awaiting moderation.', 'postero'); ?></em>
                            <br/>
                        <?php endif; ?>

                        <a href="<?php echo esc_url(htmlspecialchars(get_comment_link($comment->comment_ID))); ?>"
                           class="comment-date">
                            <?php echo '<time datetime="' . get_comment_date('c') . '">' . get_comment_date() . '</time>'; ?>
                        </a>
                    </div>
                </div>
                <div class="comment-text">
                    <?php comment_text(); ?>
                </div>
                <div class="reply">
                    <?php
                    comment_reply_link(
                        array_merge(
                            $args, array(
                                'add_below' => $add_below,
                                'depth'     => $depth,
                                'max_depth' => $args['max_depth'],
                            )
                        )
                    );
                    ?>
                    <?php edit_comment_link(esc_html__('Edit', 'postero'), '  ', ''); ?>
                </div>
                <?php if ('div' !== $args['style']) : ?>
            </div>
        <?php endif; ?>
        </div>
        <?php
    }
}

if (!function_exists('postero_credit')) {
    /**
     * Display the theme credit
     *
     * @return void
     * @since  1.0.0
     */
    function postero_credit() {
        ?>
        <div class="site-info">
            <?php echo apply_filters('postero_copyright_text', $content = '&copy; ' . date('Y') . ' ' . '<a class="site-url" href="' . esc_url(site_url()) . '">' . esc_html(get_bloginfo('name')) . '</a>' . esc_html__('. All Rights Reserved.', 'postero')); ?>
        </div><!-- .site-info -->
        <?php
    }
}

if (!function_exists('postero_social')) {
    function postero_social() {
        $social_list = postero_get_theme_option('social_text', []);
        if (empty($social_list)) {
            return;
        }
        ?>
        <div class="postero-social">
            <ul>
                <?php

                foreach ($social_list as $social_item) {
                    ?>
                    <li><a href="<?php echo esc_url($social_item); ?>"></a></li>
                    <?php
                }
                ?>

            </ul>
        </div>
        <?php
    }
}

if (!function_exists('postero_site_branding')) {
    /**
     * Site branding wrapper and display
     *
     * @return void
     * @since  1.0.0
     */
    function postero_site_branding() {
        ?>
        <div class="site-branding">
            <?php echo postero_site_title_or_logo(); ?>
        </div>
        <?php
    }
}

if (!function_exists('postero_site_title_or_logo')) {
    /**
     * Display the site title or logo
     *
     * @param bool $echo Echo the string or return it.
     *
     * @return string
     * @since 2.1.0
     */
    function postero_site_title_or_logo() {
        ob_start();
        the_custom_logo(); ?>
        <div class="site-branding-text">
            <?php if (is_front_page()) : ?>
                <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
                                          rel="home"><?php bloginfo('name'); ?></a></h1>
            <?php else : ?>
                <p class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>"
                                         rel="home"><?php bloginfo('name'); ?></a></p>
            <?php endif; ?>

            <?php
            $description = get_bloginfo('description', 'display');

            if ($description || is_customize_preview()) :
                ?>
                <p class="site-description"><?php echo esc_html($description); ?></p>
            <?php endif; ?>
        </div><!-- .site-branding-text -->
        <?php
        $html = ob_get_clean();
        return $html;
    }
}

if (!function_exists('postero_primary_navigation')) {
    /**
     * Display Primary Navigation
     *
     * @return void
     * @since  1.0.0
     */
    function postero_primary_navigation() {
        ?>
        <nav class="main-navigation" role="navigation"
             aria-label="<?php esc_html_e('Primary Navigation', 'postero'); ?>">
            <?php
            $args = apply_filters('postero_nav_menu_args', [
                'fallback_cb'     => '__return_empty_string',
                'theme_location'  => 'primary',
                'container_class' => 'primary-navigation',
            ]);
            wp_nav_menu($args);
            ?>
        </nav>
        <?php
    }
}

if (!function_exists('postero_mobile_navigation')) {
    /**
     * Display Handheld Navigation
     *
     * @return void
     * @since  1.0.0
     */
    function postero_mobile_navigation() {
        if (isset(get_nav_menu_locations()['handheld']) && isset(get_nav_menu_locations()['handheld_categories'])) {
            ?>
            <div class="mobile-nav-tabs">
                <ul>
                    <?php if (isset(get_nav_menu_locations()['handheld'])) { ?>
                        <li class="mobile-tab-title mobile-pages-title active" data-menu="pages">
                            <span><?php echo esc_html(get_term(get_nav_menu_locations()['handheld'], 'nav_menu')->name); ?></span>
                        </li>
                    <?php } ?>
                    <?php if (isset(get_nav_menu_locations()['handheld_categories'])) { ?>
                        <li class="mobile-tab-title mobile-categories-title" data-menu="categories">
                            <span><?php echo esc_html(get_term(get_nav_menu_locations()['handheld_categories'], 'nav_menu')->name); ?></span>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <?php
        }
        ?>
        <nav class="mobile-menu-tab mobile-navigation mobile-pages-menu active"
             aria-label="<?php esc_html_e('Mobile Navigation', 'postero'); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location'  => 'handheld',
                    'container_class' => 'handheld-navigation',
                )
            );
            ?>
        </nav>
        <?php
        if (isset(get_nav_menu_locations()['handheld_categories'])) {

            ?>
            <nav class="mobile-menu-tab mobile-navigation-categories mobile-categories-menu"
                 aria-label="<?php esc_html_e('Mobile Navigation', 'postero'); ?>">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location'  => 'handheld_categories',
                        'container_class' => 'handheld-navigation',
                    )
                );
                ?>
            </nav>
            <?php
        }
    }
}

if (!function_exists('postero_homepage_header')) {
    /**
     * Display the page header without the featured image
     *
     * @since 1.0.0
     */
    function postero_homepage_header() {
        edit_post_link(esc_html__('Edit this section', 'postero'), '', '', '', 'button postero-hero__button-edit');
        ?>
        <header class="entry-header">
            <?php
            the_title('<h1 class="entry-title">', '</h1>');
            ?>
        </header><!-- .entry-header -->
        <?php
    }
}

if (!function_exists('postero_page_header')) {
    /**
     * Display the page header
     *
     * @since 1.0.0
     */
    function postero_page_header() {

        if (is_front_page() || !is_page_template('default')) {
            return;
        }

        if (postero_is_elementor_activated() && function_exists('hfe_init')) {
            if (Postero_breadcrumb::get_template_id() !== '') {
                return;
            }
        }

        ?>
        <header class="entry-header">
            <?php
            if (has_post_thumbnail()) {
                postero_post_thumbnail('full');
            }
            the_title('<h1 class="entry-title">', '</h1>');
            ?>
        </header><!-- .entry-header -->
        <?php
    }
}

if (!function_exists('postero_page_content')) {
    /**
     * Display the post content
     *
     * @since 1.0.0
     */
    function postero_page_content() {
        ?>
        <div class="entry-content">
            <?php the_content(); ?>
            <?php
            wp_link_pages(
                array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'postero'),
                    'after'  => '</div>',
                )
            );
            ?>
        </div><!-- .entry-content -->
        <?php
    }
}

if (!function_exists('postero_post_header')) {
    /**
     * Display the post header with a link to the single post
     *
     * @since 1.0.0

     * Display post thumbnail
     *
     * @param string $size the post thumbnail size.
     *
     * @uses has_post_thumbnail()
     * @uses the_post_thumbnail
     * @var $size . thumbnail|medium|large|full|$custom
     */
    function postero_post_header($size = 'post-thumbnail') {
        ?>
        <header class="entry-header <?php if (has_post_thumbnail()) { echo esc_html__('header-post-thumbnail', 'postero'); } ?>">
            <?php if (is_single()) {
                if (has_post_thumbnail()) {
                    the_post_thumbnail($size ? $size : 'post-thumbnail');
                } ?>
                <div class="entry-header-content">
                    <?php
                    the_title('<h1 class="beta entry-title">', '</h1>');
                    ?>
                    <div class="entry-meta">
                        <?php postero_post_meta(['show_cat' => true, 'show_date' => true, 'show_author' => true, 'show_comment' => false]); ?>
                    </div>
                </div>
                <?php
            } else {
                the_title('<h2 class="gamma entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
            } ?>
        </header><!-- .entry-header -->
        <?php
    }
}

if (!function_exists('postero_post_content')) {
    /**
     * Display the post content with a link to the single post
     *
     * @since 1.0.0
     */
    function postero_post_content() {
        ?>
        <div class="entry-content">
            <?php

            /**
             * Functions hooked in to postero_post_content_before action.
             *
             */
            do_action('postero_post_content_before');


            if (is_single()) {
                the_content(
                    sprintf(
                    /* translators: %s: post title */
                        esc_html__('Read More', 'postero') . ' %s',
                        '<span class="screen-reader-text">' . get_the_title() . '</span>'
                    )
                );
            } else {
                the_excerpt();
            }

            /**
             * Functions hooked in to postero_post_content_after action.
             *
             */
            do_action('postero_post_content_after');

            wp_link_pages(
                array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'postero'),
                    'after'  => '</div>',
                )
            );
            ?>
        </div><!-- .entry-content -->
        <?php
        if (!is_single()) { ?>
            <div class="entry-footer">
                <div class="entry-meta">
                    <?php postero_post_meta(['show_cat' => false, 'show_date' => true, 'show_author' => true, 'show_comment' => false]); ?>
                </div>
                <?php echo '<div class="more-link-wrap"><a class="more-link" href="' . get_permalink() . '">' . esc_html__('Continue Reading', 'postero') . '<i class="postero-icon-chevron-right"></i></a></div>'; ?>
            </div>
        <?php } ?>
        <?php
    }
}

if (!function_exists('postero_post_meta')) {
    /**
     * Display the post meta
     *
     * @since 1.0.0
     */
    function postero_post_meta($atts = array()) {
        global $post;
        if ('post' !== get_post_type()) {
            return;
        }

        extract(
            shortcode_atts(
                array(
                    'show_date'    => true,
                    'show_cat'     => false,
                    'show_author'  => true,
                    'show_comment' => false,
                ),
                $atts
            )
        );
        $author = '';
        // Author.
        if ($show_author == 1) {
            $author_id = $post->post_author;
            $author    = sprintf(
                '<div class="post-author"><span class="text-author">%1$s</span><a href="%2$s" class="url fn" rel="author">%3$s</a></div>',
                esc_html__('BY ', 'postero'),
                esc_url(get_author_posts_url(get_the_author_meta('ID'))),
                esc_html(get_the_author_meta('display_name', $author_id))
            );
        }

        $posted_on = '';
        // Posted on.
        if ($show_date) {
            $posted_on = '<div class="posted-on">' . sprintf('<a href="%1$s" rel="bookmark"> <i class="postero-icon-clock-rounded"></i>%2$s</a>', esc_url(get_permalink()), get_the_modified_date('<b>d </b> M')) . '</div>';
        }

        $categories_list = get_the_category_list(', ');
        $categories      = '';
        if ($show_cat && $categories_list) {
            // Make sure there's more than one category before displaying.
            $categories = '<div class="categories-link"><span class="screen-reader-text">' . esc_html__('Categories', 'postero') . '</span>' . $categories_list . '</div>';
        }


        echo wp_kses(
            sprintf(' %3$s %1$s %2$s', $posted_on, $author, $categories), array(
                'div'  => array(
                    'class' => array(),
                ),
                'span' => array(
                    'class' => array(),
                ),
                'i'    => array(
                    'class' => array(),
                ),
                'a'    => array(
                    'href'  => array(),
                    'rel'   => array(),
                    'class' => array(),
                ),
                'time' => array(
                    'datetime' => array(),
                    'class'    => array(),
                )
            )
        );

        if ($show_comment) { ?>
            <div class="meta-reply">
                <?php
                comments_popup_link(esc_html__('0 comments', 'postero'), esc_html__('1 comment', 'postero'), esc_html__('% comments', 'postero'));
                ?>
            </div>
            <?php
        }

    }
}

if (!function_exists('postero_get_allowed_html')) {
    function postero_get_allowed_html() {
        return apply_filters(
            'postero_allowed_html',
            array(
                'br'     => array(),
                'i'      => array(),
                'b'      => array(),
                'u'      => array(),
                'em'     => array(),
                'del'    => array(),
                'a'      => array(
                    'href'  => true,
                    'class' => true,
                    'title' => true,
                    'rel'   => true,
                ),
                'strong' => array(),
                'span'   => array(
                    'style' => true,
                    'class' => true,
                ),
            )
        );
    }
}

if (!function_exists('postero_edit_post_link')) {
    /**
     * Display the edit link
     *
     * @since 2.5.0
     */
    function postero_edit_post_link() {
        edit_post_link(
            sprintf(
                wp_kses(__('Edit <span class="screen-reader-text">%s</span>', 'postero'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                get_the_title()
            ),
            '<div class="edit-link">',
            '</div>'
        );
    }
}

if (!function_exists('postero_categories_link')) {
    /**
     * Prints HTML with meta information for the current cateogries
     */
    function postero_categories_link() {

        // Get Categories for posts.
        $categories_list = get_the_category_list('');

        if ('post' === get_post_type() && $categories_list) {
            // Make sure there's more than one category before displaying.
            echo '<div class="categories-link"><span class="screen-reader-text">' . esc_html__('Categories', 'postero') . '</span>' . $categories_list . '</div>';
        }
    }
}

if (!function_exists('postero_post_taxonomy')) {
    /**
     * Display the post taxonomies
     *
     * @since 2.4.0
     */
    function postero_post_taxonomy() {
        /* translators: used between list items, there is a space after the comma */

        /* translators: used between list items, there is a space after the comma */
        $tags_list = get_the_tag_list('', ' ');
        ?>
        <aside class="entry-taxonomy">
            <?php if ($tags_list) : ?>
                <div class="tags-links">
                    <span class="screen-reader-text"><?php echo esc_html(_n('Tag:', 'Tags:', count(get_the_tags()), 'postero')); ?></span>
                    <?php printf('%s', $tags_list); ?>
                </div>
            <?php endif;
            ?>
        </aside>
        <?php
    }
}

if (!function_exists('postero_paging_nav')) {
    /**
     * Display navigation to next/previous set of posts when applicable.
     */
    function postero_paging_nav() {

        $args = array(
            'type'      => 'list',
            'prev_text' => esc_html__('Prev', 'postero'),
            'next_text' => esc_html__('Next', 'postero'),
        );
        the_posts_pagination($args);
    }
}

if (!function_exists('postero_post_nav')) {
    /**
     * Display navigation to next/previous post when applicable.
     */
    function postero_post_nav() {

        $prev_post = get_previous_post();
        $next_post = get_next_post();
        $args      = [];

        if ($next_post) {
            $args['next_text'] = '<span class="nav-content"><span class="reader-text">' . esc_html__('Next post', 'postero') . ' </span><span class="title">%title</span></span>';
        }
        if ($prev_post) {
            $args['prev_text'] = '<span class="nav-content"><span class="reader-text">' . esc_html__('Previous post', 'postero') . ' </span><span class="title">%title</span></span> ';
        }

        the_post_navigation($args);

    }
}

if (!function_exists('postero_posted_on')) {
    /**
     * Prints HTML with meta information for the current post-date/time and author.
     *
     * @deprecated 2.4.0
     */
    function postero_posted_on() {
        _deprecated_function('postero_posted_on', '2.4.0');
    }
}

if (!function_exists('postero_homepage_content')) {
    /**
     * Display homepage content
     * Hooked into the `homepage` action in the homepage template
     *
     * @return  void
     * @since  1.0.0
     */
    function postero_homepage_content() {
        while (have_posts()) {
            the_post();

            get_template_part('content', 'homepage');

        } // end of the loop.
    }
}

if (!function_exists('postero_get_sidebar')) {
    /**
     * Display postero sidebar
     *
     * @uses get_sidebar()
     * @since 1.0.0
     */
    function postero_get_sidebar() {
        get_sidebar();
    }
}

if (!function_exists('postero_post_thumbnail')) {
    /**
     * Display post thumbnail
     *
     * @param string $size the post thumbnail size.
     *
     * @uses has_post_thumbnail()
     * @uses the_post_thumbnail
     * @var $size . thumbnail|medium|large|full|$custom
     * @since 1.5.0
     */
    function postero_post_thumbnail($size = 'post-thumbnail', $show_cat = false) {
        if (has_post_thumbnail()) {
            echo '<div class="post-thumbnail">';
            if ($show_cat) {
                $categories_list = get_the_category_list(' ');
                if ($categories_list) {
                    echo '<div class="categories-link"><span class="screen-reader-text">' . esc_html__('Categories', 'postero') . '</span>' . $categories_list . '</div>';
                }
            }
            the_post_thumbnail($size ? $size : 'post-thumbnail');
            echo '</div>';
        }
    }
}

if (!function_exists('postero_primary_navigation_wrapper')) {
    /**
     * The primary navigation wrapper
     */
    function postero_primary_navigation_wrapper() {
        echo '<div class="postero-primary-navigation"><div class="col-fluid">';
    }
}

if (!function_exists('postero_primary_navigation_wrapper_close')) {
    /**
     * The primary navigation wrapper close
     */
    function postero_primary_navigation_wrapper_close() {
        echo '</div></div>';
    }
}

if (!function_exists('postero_header_container')) {
    /**
     * The header container
     */
    function postero_header_container() {
        echo '<div class="col-fluid">';
    }
}

if (!function_exists('postero_header_container_close')) {
    /**
     * The header container close
     */
    function postero_header_container_close() {
        echo '</div>';
    }
}

if (!function_exists('postero_header_custom_link')) {
    function postero_header_custom_link() {
        echo postero_get_theme_option('custom-link', '');
    }

}

if (!function_exists('postero_header_contact_info')) {
    function postero_header_contact_info() {
        echo postero_get_theme_option('contact-info', '');
    }

}

if (!function_exists('postero_header_account')) {
    function postero_header_account() {

        if (!postero_get_theme_option('show_header_account', true)) {
            return;
        }

        $account_link = wp_login_url();

        ?>
        <div class="site-header-account">
            <a href="<?php echo esc_url($account_link); ?>">
                <i class="postero-icon-user"></i>
                <span class="account-content">
                    <?php
                    if (!is_user_logged_in()) {
                        esc_attr_e('Login / Register', 'postero');
                    } else {
                        $user = wp_get_current_user();
                        echo esc_html($user->display_name);
                    }

                    ?>
                </span>
            </a>
            <div class="account-dropdown">

            </div>
        </div>
        <?php
    }

}

if (!function_exists('postero_template_account_dropdown')) {
    function postero_template_account_dropdown() {
        if (!postero_get_theme_option('show_header_account', true)) {
            return;
        }
        ?>
        <div class="account-wrap d-none">
            <div class="account-inner <?php if (is_user_logged_in()): echo "dashboard"; endif; ?>">
                <?php if (!is_user_logged_in()) {
                    postero_form_login();
                } else {
                    postero_account_dropdown();
                }
                ?>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('postero_form_login')) {
    function postero_form_login() {

        $register_link = wp_registration_url();
        ?>
        <div class="login-form-head">
            <span class="login-form-title"><?php esc_attr_e('Sign in', 'postero') ?></span>
            <span class="pull-right">
                <a class="register-link" href="<?php echo esc_url($register_link); ?>"
                   title="<?php esc_attr_e('Register', 'postero'); ?>"><?php esc_attr_e('Create an Account', 'postero'); ?></a>
            </span>
        </div>
        <form class="postero-login-form-ajax" data-toggle="validator">
            <p>
                <label><?php esc_attr_e('Username or email', 'postero'); ?> <span class="required">*</span></label>
                <input name="username" type="text" required placeholder="<?php esc_attr_e('Username', 'postero') ?>">
            </p>
            <p>
                <label><?php esc_attr_e('Password', 'postero'); ?> <span class="required">*</span></label>
                <input name="password" type="password" required
                       placeholder="<?php esc_attr_e('Password', 'postero') ?>">
            </p>
            <button type="submit" data-button-action
                    class="btn btn-primary btn-block w-100 mt-1"><?php esc_html_e('Login', 'postero') ?></button>
            <input type="hidden" name="action" value="postero_login">
            <?php wp_nonce_field('ajax-postero-login-nonce', 'security-login'); ?>
        </form>
        <div class="login-form-bottom">
            <a href="<?php echo wp_lostpassword_url(get_permalink()); ?>" class="lostpass-link"
               title="<?php esc_attr_e('Lost your password?', 'postero'); ?>"><?php esc_attr_e('Lost your password?', 'postero'); ?></a>
        </div>
        <?php
    }
}

if (!function_exists('')) {
    function postero_account_dropdown() { ?>
        <?php if (has_nav_menu('my-account')) : ?>
            <nav class="social-navigation" role="navigation" aria-label="<?php esc_attr_e('Dashboard', 'postero'); ?>">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'my-account',
                    'menu_class'     => 'account-links-menu',
                    'depth'          => 1,
                ));
                ?>
            </nav><!-- .social-navigation -->
        <?php else: ?>
            <ul class="account-dashboard">

                <?php if (postero_is_woocommerce_activated()): ?>
                    <li>
                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"
                           title="<?php esc_html_e('Dashboard', 'postero'); ?>"><?php esc_html_e('Dashboard', 'postero'); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>"
                           title="<?php esc_html_e('Orders', 'postero'); ?>"><?php esc_html_e('Orders', 'postero'); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url('downloads')); ?>"
                           title="<?php esc_html_e('Downloads', 'postero'); ?>"><?php esc_html_e('Downloads', 'postero'); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-address')); ?>"
                           title="<?php esc_html_e('Edit Address', 'postero'); ?>"><?php esc_html_e('Edit Address', 'postero'); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>"
                           title="<?php esc_html_e('Account Details', 'postero'); ?>"><?php esc_html_e('Account Details', 'postero'); ?></a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="<?php echo esc_url(get_dashboard_url(get_current_user_id())); ?>"
                           title="<?php esc_html_e('Dashboard', 'postero'); ?>"><?php esc_html_e('Dashboard', 'postero'); ?></a>
                    </li>
                <?php endif; ?>
                <li>
                    <a title="<?php esc_html_e('Log out', 'postero'); ?>" class="tips"
                       href="<?php echo esc_url(wp_logout_url(home_url())); ?>"><?php esc_html_e('Log Out', 'postero'); ?></a>
                </li>
            </ul>
        <?php endif;

    }
}

if (!function_exists('postero_header_search_popup')) {
    function postero_header_search_popup() {
        ?>
        <div class="site-search-popup">
            <div class="site-search-popup-wrap">
                <div class="site-search widget_search">
                    <?php get_search_form(); ?>
                </div>
                <a href="#" class="site-search-popup-close">
                    <svg class="close-icon" xmlns="http://www.w3.org/2000/svg" width="23.691" height="22.723" viewBox="0 0 23.691 22.723">
                        <g transform="translate(-126.154 -143.139)">
                            <line x2="23" y2="22" transform="translate(126.5 143.5)" fill="none" stroke="CurrentColor" stroke-width="1"></line>
                            <path d="M0,22,23,0" transform="translate(126.5 143.5)" fill="none" stroke="CurrentColor" stroke-width="1"></path>
                        </g>
                    </svg>
                </a>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('postero_header_search_button')) {
    function postero_header_search_button() {
        add_action('wp_footer', 'postero_header_search_popup', 1);
        ?>
        <div class="site-header-search">
            <a href="#" class="button-search-popup"><i class="postero-icon-search-3"></i></a>
        </div>
        <?php
    }
}


if (!function_exists('postero_header_sticky')) {
    function postero_header_sticky() {
        get_template_part('template-parts/header', 'sticky');
    }
}

if (!function_exists('postero_mobile_nav')) {
    function postero_mobile_nav() {
        if (isset(get_nav_menu_locations()['handheld'])) {
            ?>
            <div class="postero-mobile-nav">
                <div class="menu-scroll-mobile">
                    <?php
                    postero_site_branding();
                    ?>
                    <a href="#" class="mobile-nav-close"><i class="postero-icon-times"></i></a>
                    <?php
                    postero_mobile_navigation();
                    postero_social();
                    ?>
                </div>
            </div>
            <div class="postero-overlay"></div>
            <?php
        }
    }
}

if (!function_exists('postero_mobile_nav_button')) {
    function postero_mobile_nav_button() {
        if (isset(get_nav_menu_locations()['handheld'])) {
            ?>
            <a href="#" class="menu-mobile-nav-button">
				<span
                        class="toggle-text screen-reader-text"><?php echo esc_attr(apply_filters('postero_menu_toggle_text', esc_html__('Menu', 'postero'))); ?></span>
                <div class="postero-icon">
                    <span class="icon-1"></span>
                    <span class="icon-2"></span>
                    <span class="icon-3"></span>
                </div>
            </a>
            <?php
        }
    }
}

if (!function_exists('postero_language_switcher')) {
    function postero_language_switcher() {
        $languages = apply_filters('wpml_active_languages', []);
        if (postero_is_wpml_activated() && count($languages) > 0) {
            ?>
            <div class="postero-language-switcher">
                <ul class="menu">
                    <li class="item">
                        <div class="language-switcher-head">
                            <span class="title sgdg"><?php echo esc_html($languages[ICL_LANGUAGE_CODE]['translated_name']); ?></span>
                            <i class="postero-icon-angle-down"></i>
                        </div>

                        <ul class="sub-item">
                            <?php
                            foreach ($languages as $key => $language) {
                                if (ICL_LANGUAGE_CODE === $key) {
                                    continue;
                                }
                                ?>
                                <li>
                                    <a href="<?php echo esc_url($language['url']) ?>">
                                        <img width="18" height="12" src="<?php echo esc_url($language['country_flag_url']) ?>" alt="<?php esc_attr($language['default_locale']) ?>">
                                        <span><?php echo esc_html($language['translated_name']); ?></span>
                                    </a>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                    </li>
                </ul>
            </div>
            <?php
        }

    }
}


if (!function_exists('postero_footer_default')) {
    function postero_footer_default() {
        get_template_part('template-parts/copyright');
    }
}

if (!function_exists('postero_social_share')) {
    function postero_social_share() {
        get_template_part('template-parts/socials');
    }
}

if (!function_exists('postero_pingback_header')) {
    /**
     * Add a pingback url auto-discovery header for single posts, pages, or attachments.
     */
    function postero_pingback_header() {
        if (is_singular() && pings_open()) {
            echo '<link rel="pingback" href="', esc_url(get_bloginfo('pingback_url')), '">';
        }
    }
}

if (!function_exists('postero_comment_form_defaults')) {
    function postero_comment_form_defaults($defaults) {
        $defaults['submit_button'] = '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s <i class="postero-icon-chevron-right"></i></button>';
        return $defaults;
    }
}

add_filter('comment_form_defaults', 'postero_comment_form_defaults');

function postero_replace_categories_list($output, $args) {
    if ($args['show_count'] = 1) {
        $pattern     = '#<li([^>]*)><a([^>]*)>(.*?)<\/a>\s*\(([0-9]*)\)\s*#i';  // removed ( and )
        $replacement = '<li$1><a$2><span class="cat-name">$3</span> <span class="cat-count">($4)</span></a>';
        return preg_replace($pattern, $replacement, $output);
    }
    return $output;
}

add_filter('wp_list_categories', 'postero_replace_categories_list', 10, 2);

function postero_replace_archive_list($link_html, $url, $text, $format, $before, $after, $selected) {
    if ($format == 'html') {
        $pattern     = '#<li><a([^>]*)>(.*?)<\/a>&nbsp;\s*\(([0-9]*)\)\s*#i';  // removed ( and )
        $replacement = '<li><a$1><span class="archive-name">$2</span> <span class="archive-count">($3)</span></a>';
        return preg_replace($pattern, $replacement, $link_html);
    }
    return $link_html;
}

add_filter('get_archives_link', 'postero_replace_archive_list', 10, 7);


add_filter('bcn_breadcrumb_title', 'postero_breadcrumb_title_swapper', 3, 10);
function postero_breadcrumb_title_swapper($title, $type, $id) {
    if (in_array('home', $type)) {
        $title = esc_html__('Home', 'postero');
    }
    return $title;
}

if (!function_exists('postero_single_author')) {
    function postero_single_author() {
        get_template_part('template-parts/author');
    }
}

if (!function_exists('render_html_back_to_top')) {
    function render_html_back_to_top() {
        echo <<<HTML
        <a href="#" class="scrollup">
        <span class="elementor-button-content-wrapper">
	
				<i class="postero-icon-angle-up"></i>	
						<span class="elementor-button-text">on top</span>
		</span>
        </a>
HTML;

    }
}
