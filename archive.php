<?php
get_header(); ?>
<?php
$blog_style = postero_get_theme_option('blog_style');

if ($blog_style == 'style-1' && have_posts()) {
    the_post();
    get_template_part('template-parts/posts-grid/item-post-modern');
}

?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main">

            <?php if (have_posts()) : ?>

                <header class="page-header">
                    <?php
                    the_archive_description('<div class="taxonomy-description">', '</div>');
                    ?>
                </header><!-- .page-header -->

                <?php
                get_template_part('loop');

            else :

                get_template_part('content', 'none');

            endif;
            ?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php
do_action('postero_sidebar');
get_footer();
