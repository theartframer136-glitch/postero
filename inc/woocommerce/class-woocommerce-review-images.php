<?php
if ( ! postero_is_woocommerce_activated() ) {
	return;
}

class Postero_Extension_Photo_Review {
    private static $instance;

    /**
     * @return Postero_Extension_Photo_Review
     */
    public static function get_instance() {
        if (null === static::$instance) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function __construct() {
        if (postero_get_theme_option('enable_photo_review') == 'yes') {
            add_action('wp_enqueue_scripts', [$this, 'scripts'], 30);
            // Add Input Upload
            add_filter('woocommerce_product_review_comment_form_args', [$this, 'add_input_uploads'], 999);
            //add enctype attribute to form
            add_action('comment_form_before', array($this, 'add_form_enctype_start'));
            add_action('comment_form_after', array($this, 'add_form_enctype_end'));

            // Upload Images
            add_filter('preprocess_comment', array($this, 'check_review_image'), 10, 1);

            // Display
            add_action('woocommerce_review_after_comment_text', [$this, 'render_images']);
        }

        // Delete Review
        add_action('delete_comment', [$this, 'delete_review']);

        add_filter('woocommerce_get_sections_products', array($this, 'product_photo_review_add_settings_tab'));
        add_filter('woocommerce_get_settings_products', array($this, 'product_photo_review_settings'), 10, 2);
    }

    public function product_photo_review_add_settings_tab($settings_tab) {
        $settings_tab['product_photo_review_notices'] = __('Photo Review', 'postero');
        return $settings_tab;
    }

    public function product_photo_review_settings($settings, $current_section) {
        if ('product_photo_review_notices' == $current_section) {

            $custom_settings = array(

                array(
                    'name' => __('Photo Review', 'postero'),
                    'type' => 'title',
                    'id'   => 'postero_options_product_photo'
                ),

                array(
                    'name'     => esc_html__( 'Enable Photo Review', 'postero' ),
                    'desc'     => esc_html__( 'Choose to enable photo review.', 'postero' ),
                    'tip'      => '',
                    'id'       => 'postero_options_enable_photo_review',
                    'css'      => '',
                    'default'  => 'yes',
                    'std'      => 'yes',
                    'type'     => 'checkbox',
                ),

                array(
                    'name' => __('Max File', 'postero'),
                    'type' => 'number',
                    'id'   => 'postero_options_photo_review_max_file',
                    'min' => 1,
                    'default'=> 4
                ),

                array(
                    'name' => __('Max Size', 'postero'),
                    'type' => 'number',
                    'id'   => 'postero_options_photo_review_max_size',
                    'min' => 1,
                    'desc'=> __('(kb)', 'postero'),
                    'default'=> 250
                ),


                array('type' => 'sectionend', 'id' => 'postero_options_product_photo'),

            );

            return $custom_settings;
        } else {
            return $settings;
        }

    }

    /**
     * @param $comment WP_Comment
     * @return
     */
    public function render_images($comment) {
        $image_ids = get_comment_meta($comment->comment_ID, 'reviews-images', true);
        if (is_array($image_ids)) {
            wc_get_template(
                'single-product/photo-review.php',
                array(
                    'image_ids' => $image_ids,
                )
            );
        }
    }

    public function upload_images($comment_id) {
	    add_filter('intermediate_image_sizes', array($this, 'reduce_image_sizes'));
        $post_id = get_comment($comment_id)->comment_post_ID;
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );
        $files  = $_FILES["postero_image_upload"];// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        $img_id = array();
        if (is_array($files['name'][0])) {
            foreach ($files['name'] as $key => $value) {
                if ($files['name'][$key][0]) {
                    $file                   = array(
                        'name'     => apply_filters('woocommerce_photo_reviews_image_file_name', $files['name'][$key][0], $comment_id, $post_id),
                        'type'     => $files['type'][$key][0],
                        'tmp_name' => $files['tmp_name'][$key][0],
                        'error'    => $files['error'][$key][0],
                        'size'     => $files['size'][$key][0]
                    );
                    $_FILES ["upload_file"] = $file;
                    $attachment_id          = media_handle_upload("upload_file", $post_id);
                    if (is_wp_error($attachment_id)) {
                        wc_add_notice($attachment_id->get_error_message(), 'error');
                        do_action('woocommerce_set_cart_cookies', true);
                        wp_safe_redirect(!$post_id ? get_permalink($post_id) : home_url());
                        exit;
                    } else {
                        $img_id[] = $attachment_id;
                    }
                }
            }
        } else {
            foreach ($files['name'] as $key => $value) {
                if ($files['name'][$key]) {
                    $file                   = array(
                        'name'     => apply_filters('woocommerce_photo_reviews_image_file_name', $files['name'][$key], $comment_id, $post_id),
                        'type'     => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error'    => $files['error'][$key],
                        'size'     => $files['size'][$key]
                    );
                    $_FILES ["upload_file"] = $file;
                    $attachment_id          = media_handle_upload("upload_file", $post_id);
                    if (is_wp_error($attachment_id)) {
                        wc_add_notice($attachment_id->get_error_message(), 'error');
                        do_action('woocommerce_set_cart_cookies', true);
                        wp_safe_redirect(!$post_id ? get_permalink($post_id) : home_url());
                        exit;

                    } else {
                        $img_id[] = $attachment_id;
                    }
                }
            }
        }
        remove_filter('intermediate_image_sizes', array($this, 'reduce_image_sizes'));

        update_comment_meta($comment_id, 'reviews-images', $img_id);
    }

	public function reduce_image_sizes() {
		return ['thumbnail'];
	}

	public function check_review_image($comment) {
		$link = !empty($comment['comment_post_ID']) ? get_permalink($comment['comment_post_ID']) : home_url();
		$comment_type = isset( $comment['comment_type'] ) ? $comment['comment_type'] : '';
		if ( $comment_type !== 'review' ) {
			return $comment;
		}

		if ( ! isset( $_POST['postero_image_upload_nonce'] ) || ! wp_verify_nonce( wc_clean($_POST['postero_image_upload_nonce']), 'postero_image_upload' ) || empty($_FILES['postero_image_upload']['name'][0]) ) {
			return $comment;
		}

		$max_file_up     = postero_get_theme_option('photo_review_max_file', 4);

		$names = $this->array_flatten(wc_clean($_FILES['postero_image_upload']['name'] ?? array()));
		$sizes =array_map('intval',$this->array_flatten(wc_clean($_FILES['postero_image_upload']['size'] ?? array())));
		$types = $this->array_flatten(wc_clean($_FILES['postero_image_upload']['type'] ?? array()));
		$errors = array_unique(array_map('intval',$this->array_flatten(wc_clean($_FILES['postero_image_upload']['error'] ?? array()), false)));


		if (!empty($errors) && !in_array(UPLOAD_ERR_NO_FILE, $errors)){
			wc_add_notice( sprintf(esc_html__( 'There was an error uploading files: %s', 'postero' ),implode(',',$errors) ), 'error' );
			do_action( 'woocommerce_set_cart_cookies',  true );
			wp_safe_redirect($link);
			exit;
		}

		if (count($names) > $max_file_up){
			wc_add_notice( sprintf(esc_html__( 'Maximum number of files allowed is: %s.', 'postero' ),$max_file_up) , 'error' );
			do_action( 'woocommerce_set_cart_cookies',  true );
			wp_safe_redirect($link);
			exit;
		}

        $upload_allow = ["image/jpg" , "image/jpeg" ,"image/bmp" , "image/png", "image/webp","image/gif"];
		foreach ($types as $type){
			if ( !in_array($type,$upload_allow) ) {
				wc_add_notice( esc_html__( 'Only JPG, JPEG, BMP, PNG, WEBP and GIF are allowed.', 'postero' ) , 'error' );
				do_action( 'woocommerce_set_cart_cookies',  true );
				wp_safe_redirect($link);
				exit;
			}
		}

		$file_type_pattern ='/[^\?]+\.(jpg|JPG|jpeg|JPEG|jpe|JPE|gif|GIF|png|PNG|bmp|BMP|webp|WEBP)/';
		foreach ($names as $name){
			if ($name && !preg_match( $file_type_pattern, $name)){
				wc_add_notice( esc_html__( 'Only JPG, JPEG, BMP, PNG, WEBP and GIF are allowed.', 'postero' ) , 'error' );
				do_action( 'woocommerce_set_cart_cookies',  true );
				wp_safe_redirect($link);
			}
		}

		$maxsize_allowed = postero_get_theme_option('photo_review_max_size', 250);
		foreach ($sizes as $size){
			if (!$size){
				wc_add_notice( esc_html__( 'File\'s too large!', 'postero' ) , 'error' );
				do_action( 'woocommerce_set_cart_cookies',  true );
				wp_safe_redirect($link);
				exit;
			}

			if ( $size > ( $maxsize_allowed * 1024 ) ) {
				wc_add_notice( sprintf(esc_html__( 'Max size allowed: %skB.', 'postero' ),$maxsize_allowed) , 'error' );
				do_action( 'woocommerce_set_cart_cookies',  true );
				wp_safe_redirect($link);
				exit;
			}
		}

		if ( empty($_POST['postero_image_gdpr'] )) {
			wc_add_notice( esc_html__( 'Please agree with the GDPR policy!', 'postero' ) , 'error' );
			do_action( 'woocommerce_set_cart_cookies',  true );
			wp_safe_redirect($link);
			exit;
		}

		add_action('comment_post', [$this, 'upload_images']);

		return $comment;
	}

    public function delete_review($comment_id) {
        $ids = get_comment_meta($comment_id, 'reviews-images', true);
        if (is_array($ids) && count($ids) > 0) {
            foreach ($ids as $attach_id) {
                wp_delete_attachment($attach_id, true);
            }
        }
    }

    public function add_input_uploads($comment_form) {
        $comment_field                 = wc_get_template_html(
            'single-product/photo-review-field.php',
            array(
                'comment_form' => $comment_form,
            )
        );
        $comment_form['comment_field'] .= $comment_field;
        add_action('comment_form', array($this, 'add_image_upload_nonce'));

        return $comment_form;
    }

    public function add_image_upload_nonce() {
        wp_nonce_field('postero_image_upload', 'postero_image_upload_nonce');
    }

    public function add_form_enctype_start() {
        if (!is_product() || !is_single()) {
            return;
        }
        ob_start();
    }

    public function add_form_enctype_end() {
        if (!is_product() || !is_single()) {
            return;
        }
        $v = ob_get_clean();
        $v = str_replace('<form', '<form enctype="multipart/form-data"', $v);
        print($v);
    }

    public function scripts() {
        if (is_product()) {
            $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
            wp_enqueue_script(
                'postero-woocommerce-photo-review',
	            get_template_directory_uri() . '/assets/js/woocommerce/photo-review' . $suffix . '.js',
                array('jquery'),
                POSTERO_VERSION,
                true
            );
        }
    }

    private function array_flatten( $params , $allow_empty = true) {
	    if (!is_array($params)){
		    return !$allow_empty && !$params ? array() :array($params);
	    }
	    $result = array();
	    foreach ($params as $val){
		    if (!$allow_empty && !$val){
			    continue;
		    }
		    $result = array_merge($result, $this->array_flatten($val));
	    }
	    return $result;
    }

}

Postero_Extension_Photo_Review::get_instance();
