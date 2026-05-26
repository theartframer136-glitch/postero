<article id="post-<?php the_ID(); ?>" <?php post_class('article-default'); ?>>
    <div class="post-inner blog-list">
        <?php postero_post_thumbnail('post-thumbnail', true); ?>
        <div class="post-content">
            <div class="entry-group">
                <div class="entry-header">
                    <?php the_title('<h3 class="delta entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>'); ?>
                </div>
                <div class="entry-content">
                    <div class="entry-excerpt">
                        <?php
                        the_excerpt(); ?>
                    </div>
                </div>
                <div class="entry-footer">
                    <div class="entry-meta">
                        <?php postero_post_meta(['show_cat' => false, 'show_date' => true, 'show_author' => true, 'show_comment' => false]); ?>
                    </div>
                    <?php
                    echo '<div class="more-link-wrap"><a class="more-link" href="' . get_permalink() . '">' . esc_html__('Continue Reading', 'postero') . '<i class="postero-icon-chevron-right"></i></a></div>';
                    ?>
                </div>
            </div>
        </div>
    </div>
</article><!-- #post-## -->