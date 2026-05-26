<?php

get_header();

$blog_style = postero_get_theme_option('blog_style');
if ($blog_style == 'style-1' && have_posts()) {
    the_post();
    get_template_part('template-parts/posts-grid/item-post-modern');
}
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main">

		<?php
		if ( have_posts() ) :

            get_template_part('loop');

		else :

			get_template_part( 'content', 'none' );

		endif;
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php

/**
 * Functions hooked in to postero_sidebar action
 *
 * @see postero_get_sidebar      - 10
 */
do_action( 'postero_sidebar' );
get_footer();
