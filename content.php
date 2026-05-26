<article id="post-<?php the_ID(); ?>" <?php post_class('article-default'); ?>>
    <div class="post-inner">
        <?php postero_post_thumbnail('post-thumbnail', true); ?>
        <div class="post-content">
            <?php
            /**
             * Functions hooked in to postero_loop_post action.
             *
             * @see postero_post_header          - 15
             * @see postero_post_content         - 30
             */
            do_action('postero_loop_post');
            ?>
        </div>
    </div>
</article><!-- #post-## -->