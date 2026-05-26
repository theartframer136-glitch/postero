<?php

/**
 * Main class of plugin for admin
 */
class Postero_Woocommerce_Artist {
    /**
     * Class constructor.
     */
    public function __construct() {
        $artist_taxonomy = Mas_WC_Brands()->get_brand_taxonomy();
        if($artist_taxonomy){
            add_action($artist_taxonomy.'_add_form_fields', array($this, 'add_product_artist_form_fields_html'), 10, 2);
            add_action('created_'.$artist_taxonomy, array($this, 'save_product_artist_meta'), 10, 2);
            add_action($artist_taxonomy.'_edit_form_fields', array($this, 'update_product_artist_meta_html'), 10, 2);
            add_action('edited_'.$artist_taxonomy, array($this, 'updated_product_artist_meta'), 10, 2);
            add_action('admin_enqueue_scripts', array($this, 'load_media'));

            add_action('wp_enqueue_scripts', array($this, 'woocommerce_scripts'), 20);
        }

    }

    public function woocommerce_scripts() {
        $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
        if(is_product_taxonomy() && is_tax($brand_taxonomy = Mas_WC_Brands()->get_brand_taxonomy())){
            wp_enqueue_script('postero-archive-artists', get_template_directory_uri() . '/assets/js/woocommerce/archive-artist' . $suffix . '.js', array(
                'jquery'
            ), POSTERO_VERSION, true);
        }
    }

    public function load_media() {
        $current_screen = get_current_screen();
        $artist_taxonomy = Mas_WC_Brands()->get_brand_taxonomy();
        if ($artist_taxonomy && ($current_screen->base === 'edit-tags' || $current_screen->base === 'term')) {
            if($current_screen->taxonomy === $artist_taxonomy) {
                wp_enqueue_media();
                wp_enqueue_script('postero-admin-woocommerce-artist-scripts', get_theme_file_uri('assets/js/admin/woocommerce-artist.js'), array('jquery'), POSTERO_VERSION, true);
            }
        }
    }

    /*
     * Add a form field in the new product_artist page
     * @since 1.0.0
    */
    public function add_product_artist_form_fields_html($taxonomy) { ?>
        <div class="form-field term-group">
            <label for="product_artist_facebook"><?php esc_html_e('Facebook', 'postero'); ?></label>
            <input type="url" id="product_artist_facebook" name="product_artist_facebook">
        </div>
        <div class="form-field term-group">
            <label for="product_artist_twitter"><?php esc_html_e('Twitter', 'postero'); ?></label>
            <input type="url" id="product_artist_twitter" name="product_artist_twitter">
        </div>
        <div class="form-field term-group">
            <label for="product_artist_instagram"><?php esc_html_e('Instagram', 'postero'); ?></label>
            <input type="url" id="product_artist_instagram" name="product_artist_instagram">
        </div>
        <div class="form-field term-group">
            <label for="product_artist_pinterest"><?php esc_html_e('Pinterest', 'postero'); ?></label>
            <input type="url" id="product_artist_pinterest" name="product_artist_pinterest">
        </div>
        <div class="form-field term-group">
            <label for="product_artist_website"><?php esc_html_e('Website', 'postero'); ?></label>
            <input type="url" id="product_artist_website" name="product_artist_website">
        </div>
        <div class="form-field term-group">
            <label for="product_artist_contact_form"><?php esc_html_e('Contact Form Shortcode', 'postero'); ?></label>
            <input type="text" id="product_artist_contact_form" name="product_artist_contact_form">
        </div>
        <div class="form-field term-group">
            <label for="product_artist_banner"><?php esc_html_e('Banner', 'postero'); ?></label>
            <input type="hidden" id="product_artist_banner" name="product_artist_banner" class="custom_media_url" value="">
            <div id="product_artist-image-wrapper"></div>
            <p>
                <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php esc_html_e('Add Banner', 'postero'); ?>"/>
                <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php esc_html_e('Remove Banner', 'postero'); ?>"/>
            </p>
        </div>
        <?php
    }

    /*
     * Save the form field
     * @since 1.0.0
    */
    public function save_product_artist_meta($term_id, $tt_id) {
        if (isset($_POST['product_artist_facebook'])) {
            add_term_meta($term_id, 'product_artist_facebook', $_POST['product_artist_facebook'],true);
        }
        if (isset($_POST['product_artist_twitter'])) {
            add_term_meta($term_id, 'product_artist_twitter', $_POST['product_artist_twitter'],true);
        }
        if (isset($_POST['product_artist_instagram'])) {
            add_term_meta($term_id, 'product_artist_instagram', $_POST['product_artist_instagram'],true);
        }
        if (isset($_POST['product_artist_pinterest'])) {
            add_term_meta($term_id, 'product_artist_pinterest', $_POST['product_artist_pinterest'],true);
        }
        if (isset($_POST['product_artist_website'])) {
            add_term_meta($term_id, 'product_artist_website', $_POST['product_artist_website'],true);
        }
        if (isset($_POST['product_artist_contact_form'])) {
            add_term_meta($term_id, 'product_artist_contact_form', $_POST['product_artist_contact_form'],true);
        }
        if (isset($_POST['product_artist_banner']) && '' !== $_POST['product_artist_banner']) {
            $image = $_POST['product_artist_banner'];
            add_term_meta($term_id, 'product_artist_banner', $image, true);
        }
    }

    /*
     * Edit the form field
     * @since 1.0.0
    */
    public function update_product_artist_meta_html($term, $taxonomy) { ?>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="product_artist_facebook"><?php esc_html_e('Facebook', 'postero'); ?></label>
            </th>
            <td>
                <?php $facebook_url = get_term_meta($term->term_id, 'product_artist_facebook', true); ?>
                <input type="url" id="product_artist_facebook" name="product_artist_facebook" value="<?php echo esc_attr($facebook_url); ?>">
            </td>
        </tr>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="product_artist_twitter"><?php esc_html_e('Twitter', 'postero'); ?></label>
            </th>
            <td>
                <?php $facebook_url = get_term_meta($term->term_id, 'product_artist_twitter', true); ?>
                <input type="url" id="product_artist_twitter" name="product_artist_twitter" value="<?php echo esc_attr($facebook_url); ?>">
            </td>
        </tr>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="product_artist_instagram"><?php esc_html_e('Instagram', 'postero'); ?></label>
            </th>
            <td>
                <?php $instagram_url = get_term_meta($term->term_id, 'product_artist_instagram', true); ?>
                <input type="url" id="product_artist_instagram" name="product_artist_instagram" value="<?php echo esc_attr($instagram_url); ?>">
            </td>
        </tr>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="product_artist_pinterest"><?php esc_html_e('Pinterest', 'postero'); ?></label>
            </th>
            <td>
                <?php $pinterest_url = get_term_meta($term->term_id, 'product_artist_pinterest', true); ?>
                <input type="url" id="product_artist_pinterest" name="product_artist_pinterest" value="<?php echo esc_attr($pinterest_url); ?>">
            </td>
        </tr>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="product_artist_website"><?php esc_html_e('Website', 'postero'); ?></label>
            </th>
            <td>
                <?php $website_url = get_term_meta($term->term_id, 'product_artist_website', true); ?>
                <input type="url" id="product_artist_website" name="product_artist_website" value="<?php echo esc_attr($website_url); ?>">
            </td>
        </tr>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="product_artist_contact_form"><?php esc_html_e('Contact Form Shortcode', 'postero'); ?></label>
            </th>
            <td>
                <?php $contact_form = get_term_meta($term->term_id, 'product_artist_contact_form', true); ?>
                <input type="text" id="product_artist_contact_form" name="product_artist_contact_form" value="<?php echo esc_attr($contact_form); ?>">
            </td>
        </tr>
        <tr class="form-field term-group-wrap">
            <th scope="row">
                <label for="product_artist_banner"><?php esc_html_e('Banner', 'postero'); ?></label>
            </th>
            <td>
                <?php $image_id = get_term_meta($term->term_id, 'product_artist_banner', true); ?>
                <input type="hidden" id="product_artist_banner" name="product_artist_banner" value="<?php echo esc_attr($image_id); ?>">
                <div id="product_artist-image-wrapper">
                    <?php if ($image_id) { ?>
                        <?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?>
                    <?php } ?>
                </div>
                <p>
                    <input type="button" class="button button-secondary ct_tax_media_button" id="ct_tax_media_button" name="ct_tax_media_button" value="<?php esc_html_e('Add Banner', 'postero'); ?>"/>
                    <input type="button" class="button button-secondary ct_tax_media_remove" id="ct_tax_media_remove" name="ct_tax_media_remove" value="<?php esc_html_e('Remove Banner', 'postero'); ?>"/>
                </p>
            </td>
        </tr>
        <?php
    }

    /*
     * Update the form field value
     * @since 1.0.0
     */
    public function updated_product_artist_meta($term_id, $tt_id) {
        if (isset($_POST['product_artist_facebook'])) {
            update_term_meta($term_id, 'product_artist_facebook', $_POST['product_artist_facebook']);
        }
        if (isset($_POST['product_artist_twitter'])) {
            update_term_meta($term_id, 'product_artist_twitter', $_POST['product_artist_twitter']);
        }
        if (isset($_POST['product_artist_instagram'])) {
            update_term_meta($term_id, 'product_artist_instagram', $_POST['product_artist_instagram']);
        }
        if (isset($_POST['product_artist_pinterest'])) {
            update_term_meta($term_id, 'product_artist_pinterest', $_POST['product_artist_pinterest']);
        }
        if (isset($_POST['product_artist_website'])) {
            update_term_meta($term_id, 'product_artist_website', $_POST['product_artist_website']);
        }
        if (isset($_POST['product_artist_contact_form'])) {
            update_term_meta($term_id, 'product_artist_contact_form', $_POST['product_artist_contact_form']);
        }
        if (isset($_POST['product_artist_banner']) && '' !== $_POST['product_artist_banner']) {
            $image = $_POST['product_artist_banner'];
            update_term_meta($term_id, 'product_artist_banner', $image);
        } else {
            update_term_meta($term_id, 'product_artist_banner', '');
        }
    }

}

new Postero_Woocommerce_Artist;
