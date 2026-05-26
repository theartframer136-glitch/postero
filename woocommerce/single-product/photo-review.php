<?php
/**
 * @var $image_ids []
 */

?>

<div class="postero-images-review">
    <?php foreach ($image_ids as $attach_id){
        $full_src          = wp_get_attachment_image_src( $attach_id, 'full' );
        $attr = array(
            'title'                   => _wp_specialchars( get_post_field( 'post_title', $attach_id ), ENT_QUOTES, 'UTF-8', true ),
            'data-caption'            => _wp_specialchars( get_post_field( 'post_excerpt', $attach_id ), ENT_QUOTES, 'UTF-8', true ),
            'data-src'                => esc_url( $full_src[0] ),
            'data-large_image'        => esc_url( $full_src[0] ),
            'data-large_image_width'  => esc_attr( $full_src[1] ),
            'data-large_image_height' => esc_attr( $full_src[2] )
        );
        ?>
    <div class="image-review">
        <?php echo wp_get_attachment_image($attach_id, 'thumbnail',false,$attr); ?>
    </div>
    <?php } ?>
</div>
