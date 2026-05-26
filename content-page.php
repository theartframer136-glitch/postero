<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Functions hooked in to postero_page action
	 *
	 * @see postero_page_header          - 10
	 * @see postero_page_content         - 20
	 *
	 */
	do_action( 'postero_page' );
	?>
</article><!-- #post-## -->
