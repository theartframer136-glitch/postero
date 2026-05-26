<div class="post-inner blog-modern">
    <?php postero_post_thumbnail('post-thumbnail', false); ?>
    <div class="post-content">
        <div class="entry-header">
            <div class="entry-meta">
                <?php postero_post_meta(['show_cat' => false, 'show_date' => true, 'show_author' => true, 'show_comment' => false]); ?>
            </div>
            <?php
            the_title('<h3 class="delta entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h3>');
            ?>
            <?php
            echo '<div class="more-link-wrap"><a class="more-link" href="' . get_permalink() . '">' . esc_html__('Continue Reading', 'postero') . '<i class="postero-icon-chevron-right"></i></a></div>';
            ?>
        </div>
    </div>
</div>