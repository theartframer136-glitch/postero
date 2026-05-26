<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="single-content">
        <?php
        /**
         * Functions hooked in to postero_single_post_top action
         *
         */
        do_action('postero_single_post_top');

        /**
         * Functions hooked in to postero_single_post action
         * @see postero_post_header         - 10
         * @see postero_post_content         - 30
         */
        do_action('postero_single_post');

        /**
         * Functions hooked in to postero_single_post_bottom action
         *
         * @see postero_post_taxonomy      - 5
         * @see postero_single_author      - 10
         * @see postero_post_nav            - 15
         * @see postero_display_comments    - 20
         */
        do_action('postero_single_post_bottom');
        ?>

    </div>

</article><!-- #post-## -->
