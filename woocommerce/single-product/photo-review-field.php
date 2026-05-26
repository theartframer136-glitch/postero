<div class="postero-photo-review-input">
    <div class="comment-form-photo">
        <label for="postero_image_upload">
            <?php echo sprintf( 'Choose pictures (maxsize: %skB, max files: %s)',
                postero_get_theme_option( 'photo_review_max_size', 250 ),
                postero_get_theme_option( 'photo_review_max_file', 4 ) );
            ?>
        </label>
        <div class="input-file-container">
            <div class="input-file-wrap">
                <input type="file" name="postero_image_upload[]" id="postero_image_upload" class="image_upload" multiple accept=".jpg, .jpeg, .png, .bmp, .webp, .gif">
                <div class="selected-image-container"></div>
            </div>
        </div>
    </div>
    <div class="comment-form-photo">
        <input type="checkbox" name="postero_image_gdpr" id="postero_image_gdpr"/>
        <label for="postero_image_gdpr"><?php esc_html_e( 'I agree with the term and condition.', 'postero' ) ?></label>
    </div>
</div>
