<?php

use Elementor\Plugin;

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Postero_Elementor')) :

    /**
     * The Postero Elementor Integration class
     */
    class Postero_Elementor {
        private $suffix = '';

        public function __construct() {
            $this->suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';

            add_action('elementor/frontend/after_enqueue_scripts', [$this, 'register_auto_scripts_frontend']);
            add_action('elementor/elements/categories_registered', [$this, 'register_widget_category']);
            add_action('wp_enqueue_scripts', [$this, 'add_scripts'], 15);
            add_action('wp_enqueue_scripts', [$this, 'add_styles'], 9);
            add_action('elementor/widgets/register', array($this, 'customs_widgets'));
            add_action('elementor/widgets/register', array($this, 'include_widgets'));
            add_action('elementor/frontend/after_enqueue_scripts', [$this, 'add_js']);

            // Custom Animation Scroll
            add_filter('elementor/controls/animations/additional_animations', [$this, 'add_animations_scroll']);
            // Elementor Fix Noitice WooCommerce
            add_action('elementor/editor/before_enqueue_scripts', array($this, 'woocommerce_fix_notice'));

            // Backend
            add_action('elementor/editor/after_enqueue_styles', [$this, 'add_style_editor'], 99);

            // Add Icon Custom
            add_action('elementor/icons_manager/native', [$this, 'add_icons_native']);
            add_action('elementor/controls/controls_registered', [$this, 'add_icons']);

            if (!postero_is_elementor_pro_activated()) {
                require trailingslashit(get_template_directory()) . 'inc/elementor/custom-css.php';
                require trailingslashit(get_template_directory()) . 'inc/elementor/sticky-section.php';
                if (is_admin()) {
                    add_action('manage_elementor_library_posts_columns', [$this, 'admin_columns_headers']);
                    add_action('manage_elementor_library_posts_custom_column', [$this, 'admin_columns_content'], 10, 2);
                }
            }

            add_filter('elementor/fonts/additional_fonts', [$this, 'additional_fonts']);
            add_action('wp_enqueue_scripts', [$this, 'elementor_kit']);

            require get_theme_file_path('inc/elementor/modules/settings.php');
        }

        public function elementor_kit() {
            $active_kit_id = Elementor\Plugin::$instance->kits_manager->get_active_id();
            Elementor\Plugin::$instance->kits_manager->frontend_before_enqueue_styles();
            $myvals = get_post_meta($active_kit_id, '_elementor_page_settings', true);
            if (!empty($myvals)) {
                $css = '';
                foreach ($myvals['system_colors'] as $key => $value) {
                    $css .= $value['color'] !== '' ? '--' . $value['_id'] . ':' . $value['color'] . ';' : '';
                }

                $var = "body{{$css}}";
                wp_add_inline_style('postero-style', $var);
            }
        }

        public function additional_fonts($fonts) {
            $fonts["Postero"] = 'system';
            $fonts["Instrument Sans"] = 'googlefonts';
            return $fonts;
        }

        public function admin_columns_headers($defaults) {
            $defaults['shortcode'] = esc_html__('Shortcode', 'postero');

            return $defaults;
        }

        public function admin_columns_content($column_name, $post_id) {
            if ('shortcode' === $column_name) {
                ob_start();
                ?>
                <input class="elementor-shortcode-input" type="text" readonly onfocus="this.select()" value="[hfe_template id='<?php echo esc_attr($post_id); ?>']"/>
                <?php
                ob_get_contents();
            }
        }

        public function add_js() {

            wp_enqueue_script('postero-elementor-frontend', get_theme_file_uri('/assets/js/elementor-frontend.js'), [], POSTERO_VERSION);
        }

        public function add_style_editor() {

            wp_enqueue_style('postero-elementor-editor-icon', get_theme_file_uri('/assets/css/admin/elementor/icons.css'), [], POSTERO_VERSION);
        }

        public function add_styles() {
            wp_enqueue_style('e-swiper');
        }

        public function add_scripts() {

            $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
            wp_enqueue_style('postero-elementor', get_template_directory_uri() . '/assets/css/base/elementor.css', '', POSTERO_VERSION);
            wp_style_add_data('postero-elementor', 'rtl', 'replace');

            // Add Scripts

            $e_swiper_latest     = Plugin::$instance->experiments->is_feature_active('e_swiper_latest');
            $e_swiper_asset_path = $e_swiper_latest ? 'assets/lib/swiper/v8/' : 'assets/lib/swiper/';
            $e_swiper_version    = $e_swiper_latest ? '8.4.5' : '5.3.6';
            wp_register_script(
                'swiper',
                plugins_url('elementor/' . $e_swiper_asset_path . 'swiper.js', 'elementor'),
                [],
                $e_swiper_version,
                true
            );
        }

        public function register_auto_scripts_frontend() {
            $suffix = (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min';
            wp_register_script('postero-elementor-swiper', get_theme_file_uri('/assets/js/elementor-swiper' . $suffix . '.js'), array('jquery', 'elementor-frontend'), POSTERO_VERSION, true);
            // Register auto scripts frontend

            $files  = glob(get_theme_file_path('/assets/js/elementor/*' . $suffix . '.js'));
            foreach ($files as $file) {
                $file_name = wp_basename($file);
                $handle    = str_replace($suffix.".js", '', $file_name);
                $scr       = get_theme_file_uri('/assets/js/elementor/' . $file_name);
                if (file_exists($file)) {
                    wp_register_script('postero-elementor-' . $handle, $scr, ['jquery', 'elementor-frontend'], POSTERO_VERSION, true);
                }
            }
        }

        public function register_widget_category($this_cat) {
            $this_cat->add_category(
                'postero-addons',
                [
                    'title' => esc_html__('Postero Addons', 'postero'),
                    'icon'  => 'fa fa-plug',
                ]
            );
            return $this_cat;
        }

        public function add_animations_scroll($animations) {
            $animations['Postero Animation'] = [
                'opal-move-up'    => 'Move Up',
                'opal-move-down'  => 'Move Down',
                'opal-move-left'  => 'Move Left',
                'opal-move-right' => 'Move Right',
                'opal-flip'       => 'Flip',
                'opal-helix'      => 'Helix',
                'opal-scale-up'   => 'Scale',
                'opal-am-popup'   => 'Popup',
            ];

            return $animations;
        }

        public function customs_widgets() {
            $files = glob(get_theme_file_path('/inc/elementor/custom-widgets/*.php'));
            foreach ($files as $file) {
                if (file_exists($file)) {
                    require_once $file;
                }
            }
        }

        /**
         * @param $widgets_manager Elementor\Widgets_Manager
         */
        public function include_widgets($widgets_manager) {
            require 'base-swiper-widget.php';
            $files = glob(get_theme_file_path('/inc/elementor/widgets/*.php'));
            foreach ($files as $file) {
                if (file_exists($file)) {
                    require_once $file;
                }
            }
        }

        public function woocommerce_fix_notice() {
            if (postero_is_woocommerce_activated()) {
                remove_action('woocommerce_cart_is_empty', 'woocommerce_output_all_notices', 5);
                remove_action('woocommerce_shortcode_before_product_cat_loop', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_shop_loop', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_single_product', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_cart', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_checkout_form', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_account_content', 'woocommerce_output_all_notices', 10);
                remove_action('woocommerce_before_customer_login_form', 'woocommerce_output_all_notices', 10);
            }
        }


        public function add_icons( $manager ) {
            $new_icons = json_decode( '{"postero-icon-advice":"advice","postero-icon-angle-down":"angle-down","postero-icon-angle-left":"angle-left","postero-icon-angle-right":"angle-right","postero-icon-angle-up":"angle-up","postero-icon-arrow-drop-down-fill":"arrow-drop-down-fill","postero-icon-arrow-left":"arrow-left","postero-icon-arrow-right":"arrow-right","postero-icon-art":"art","postero-icon-artists":"artists","postero-icon-calendar":"calendar","postero-icon-call-outgoing":"call-outgoing","postero-icon-canvas-menu":"canvas-menu","postero-icon-card-secure":"card-secure","postero-icon-cavas-menu":"cavas-menu","postero-icon-checked-rounded":"checked-rounded","postero-icon-checked":"checked","postero-icon-chevron-double-left":"chevron-double-left","postero-icon-chevron-double-right":"chevron-double-right","postero-icon-chevron-left":"chevron-left","postero-icon-chevron-right":"chevron-right","postero-icon-clock-rounded":"clock-rounded","postero-icon-clock":"clock","postero-icon-close-menu":"close-menu","postero-icon-close":"close","postero-icon-communication":"communication","postero-icon-compare":"compare","postero-icon-config":"config","postero-icon-copy":"copy","postero-icon-credit-card-1":"credit-card-1","postero-icon-credit-card-lock":"credit-card-lock","postero-icon-customer-service":"customer-service","postero-icon-d-rotate":"d-rotate","postero-icon-delivery":"delivery","postero-icon-dots-grid":"dots-grid","postero-icon-envelope-1":"envelope-1","postero-icon-eye":"eye","postero-icon-fast-delivery":"fast-delivery","postero-icon-fast":"fast","postero-icon-filter":"filter","postero-icon-free-delivery":"free-delivery","postero-icon-free-shipping":"free-shipping","postero-icon-gallery-1":"gallery-1","postero-icon-gift-box":"gift-box","postero-icon-gluten-free":"gluten-free","postero-icon-google-plus-g":"google-plus-g","postero-icon-grid-2":"grid-2","postero-icon-group-1":"group-1","postero-icon-guarantee":"guarantee","postero-icon-headphones":"headphones","postero-icon-heart-circle":"heart-circle","postero-icon-heart":"heart","postero-icon-help":"help","postero-icon-import":"import","postero-icon-language-1":"language-1","postero-icon-linkedin-in":"linkedin-in","postero-icon-list-ul":"list-ul","postero-icon-location":"location","postero-icon-locator":"locator","postero-icon-love-world":"love-world","postero-icon-lowest-price":"lowest-price","postero-icon-mail-send":"mail-send","postero-icon-map-marker-alt":"map-marker-alt","postero-icon-map-pin":"map-pin","postero-icon-medal":"medal","postero-icon-menu":"menu","postero-icon-no-gmo":"no-gmo","postero-icon-one-click":"one-click","postero-icon-package-check":"package-check","postero-icon-palette":"palette","postero-icon-pen":"pen","postero-icon-performance":"performance","postero-icon-phone-1":"phone-1","postero-icon-phone-2":"phone-2","postero-icon-phone-3":"phone-3","postero-icon-phone":"phone","postero-icon-pin":"pin","postero-icon-play-1":"play-1","postero-icon-play-circle":"play-circle","postero-icon-play-line":"play-line","postero-icon-plus-1":"plus-1","postero-icon-plus-2":"plus-2","postero-icon-plus-circle":"plus-circle","postero-icon-popular":"popular","postero-icon-question1":"question1","postero-icon-quote-1":"quote-1","postero-icon-quote-2":"quote-2","postero-icon-quote":"quote","postero-icon-refresh":"refresh","postero-icon-responsive-design":"responsive-design","postero-icon-right-arrow-cicrle":"right-arrow-cicrle","postero-icon-save-energy":"save-energy","postero-icon-search-3":"search-3","postero-icon-search-plus":"search-plus","postero-icon-search2":"search2","postero-icon-seo-1":"seo-1","postero-icon-seo":"seo","postero-icon-setting":"setting","postero-icon-shield-security":"shield-security","postero-icon-shipping":"shipping","postero-icon-shopping-bag":"shopping-bag","postero-icon-shopping-bag2":"shopping-bag2","postero-icon-shopping-cart":"shopping-cart","postero-icon-sliders-v":"sliders-v","postero-icon-small-batch":"small-batch","postero-icon-sms":"sms","postero-icon-star-3":"star-3","postero-icon-star2":"star2","postero-icon-store-1":"store-1","postero-icon-supply":"supply","postero-icon-support-1":"support-1","postero-icon-support-2":"support-2","postero-icon-support":"support","postero-icon-sustainable-1":"sustainable-1","postero-icon-sustainable":"sustainable","postero-icon-tag":"tag","postero-icon-telephone":"telephone","postero-icon-text":"text","postero-icon-top-brand":"top-brand","postero-icon-touch-controls":"touch-controls","postero-icon-trolley":"trolley","postero-icon-trophy":"trophy","postero-icon-truck-1":"truck-1","postero-icon-twitte-1":"twitte-1","postero-icon-typography":"typography","postero-icon-unique":"unique","postero-icon-user":"user","postero-icon-verification":"verification","postero-icon-360":"360","postero-icon-bars":"bars","postero-icon-cart-empty":"cart-empty","postero-icon-check-square":"check-square","postero-icon-circle":"circle","postero-icon-cloud-download-alt":"cloud-download-alt","postero-icon-comment":"comment","postero-icon-comments":"comments","postero-icon-contact":"contact","postero-icon-credit-card":"credit-card","postero-icon-dot-circle":"dot-circle","postero-icon-edit":"edit","postero-icon-envelope":"envelope","postero-icon-expand-alt":"expand-alt","postero-icon-external-link-alt":"external-link-alt","postero-icon-file-alt":"file-alt","postero-icon-file-archive":"file-archive","postero-icon-folder-open":"folder-open","postero-icon-folder":"folder","postero-icon-frown":"frown","postero-icon-gift":"gift","postero-icon-grid":"grid","postero-icon-grip-horizontal":"grip-horizontal","postero-icon-heart-fill":"heart-fill","postero-icon-history":"history","postero-icon-home":"home","postero-icon-info-circle":"info-circle","postero-icon-instagram":"instagram","postero-icon-level-up-alt":"level-up-alt","postero-icon-list":"list","postero-icon-map-marker-check":"map-marker-check","postero-icon-meh":"meh","postero-icon-minus-circle":"minus-circle","postero-icon-minus":"minus","postero-icon-mobile-android-alt":"mobile-android-alt","postero-icon-money-bill":"money-bill","postero-icon-pencil-alt":"pencil-alt","postero-icon-plus":"plus","postero-icon-random":"random","postero-icon-reply-all":"reply-all","postero-icon-reply":"reply","postero-icon-search":"search","postero-icon-shield-check":"shield-check","postero-icon-shopping-basket":"shopping-basket","postero-icon-sign-out-alt":"sign-out-alt","postero-icon-smile":"smile","postero-icon-spinner":"spinner","postero-icon-square":"square","postero-icon-star":"star","postero-icon-store":"store","postero-icon-sync":"sync","postero-icon-tachometer-alt":"tachometer-alt","postero-icon-thumbtack":"thumbtack","postero-icon-ticket":"ticket","postero-icon-times-circle":"times-circle","postero-icon-times-square":"times-square","postero-icon-times":"times","postero-icon-trophy-alt":"trophy-alt","postero-icon-truck":"truck","postero-icon-video":"video","postero-icon-wishlist-empty":"wishlist-empty","postero-icon-adobe":"adobe","postero-icon-amazon":"amazon","postero-icon-android":"android","postero-icon-angular":"angular","postero-icon-apper":"apper","postero-icon-apple":"apple","postero-icon-atlassian":"atlassian","postero-icon-behance":"behance","postero-icon-bitbucket":"bitbucket","postero-icon-bitcoin":"bitcoin","postero-icon-bity":"bity","postero-icon-bluetooth":"bluetooth","postero-icon-btc":"btc","postero-icon-centos":"centos","postero-icon-chrome":"chrome","postero-icon-codepen":"codepen","postero-icon-cpanel":"cpanel","postero-icon-discord":"discord","postero-icon-dochub":"dochub","postero-icon-docker":"docker","postero-icon-dribbble":"dribbble","postero-icon-dropbox":"dropbox","postero-icon-drupal":"drupal","postero-icon-ebay":"ebay","postero-icon-facebook-f":"facebook-f","postero-icon-facebook":"facebook","postero-icon-figma":"figma","postero-icon-firefox":"firefox","postero-icon-google-plus":"google-plus","postero-icon-google":"google","postero-icon-grunt":"grunt","postero-icon-gulp":"gulp","postero-icon-html5":"html5","postero-icon-joomla":"joomla","postero-icon-link-brand":"link-brand","postero-icon-linkedin":"linkedin","postero-icon-mailchimp":"mailchimp","postero-icon-opencart":"opencart","postero-icon-paypal":"paypal","postero-icon-pinterest-p":"pinterest-p","postero-icon-reddit":"reddit","postero-icon-skype":"skype","postero-icon-slack":"slack","postero-icon-snapchat":"snapchat","postero-icon-spotify":"spotify","postero-icon-trello":"trello","postero-icon-twitter":"twitter","postero-icon-vimeo":"vimeo","postero-icon-whatsapp":"whatsapp","postero-icon-wordpress":"wordpress","postero-icon-yoast":"yoast","postero-icon-youtube":"youtube"}', true );
			$icons     = $manager->get_control( 'icon' )->get_settings( 'options' );
			$new_icons = array_merge(
				$new_icons,
				$icons
			);
			// Then we set a new list of icons as the options of the icon control
			$manager->get_control( 'icon' )->set_settings( 'options', $new_icons ); 
        }

        public function add_icons_native($tabs) {

            $tabs['opal-custom'] = [
                'name'          => 'postero-icon',
                'label'         => esc_html__('Postero Icon', 'postero'),
                'prefix'        => 'postero-icon-',
                'displayPrefix' => 'postero-icon-',
                'labelIcon'     => 'fab fa-font-awesome-alt',
                'ver'           => POSTERO_VERSION,
                'fetchJson'     => get_theme_file_uri('/inc/elementor/icons.json'),
                'native'        => true,
            ];

            return $tabs;
        }
    }

endif;

return new Postero_Elementor();
