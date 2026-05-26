<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!class_exists('Postero_Customize')) {

    class Postero_Customize {


        public function __construct() {
            add_action('customize_register', array($this, 'customize_register'));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         */
        public function customize_register($wp_customize) {

            /**
             * Theme options.
             */
            require_once get_theme_file_path('inc/customize-control/editor.php');
            require_once get_theme_file_path('inc/customize-control/color.php');

            $this->init_postero_blog($wp_customize);

            $this->init_postero_social($wp_customize);

            if (postero_is_woocommerce_activated()) {
                $this->init_woocommerce($wp_customize);
            }

            do_action('postero_customize_register', $wp_customize);
        }


        /**
         * @param $wp_customize WP_Customize_Manager
         *
         * @return void
         */
        public function init_postero_blog($wp_customize) {

            $wp_customize->add_section('postero_blog_archive', array(
                'title' => esc_html__('Blog', 'postero'),
            ));

            // =========================================
            // Select Style
            // =========================================

            $wp_customize->add_setting('postero_options_blog_style', array(
                'type'              => 'option',
                'default'           => 'standard',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('postero_options_blog_style', array(
                'section' => 'postero_blog_archive',
                'label'   => esc_html__('Blog style', 'postero'),
                'type'    => 'select',
                'choices' => array(
                    'standard' => esc_html__('Blog Standard', 'postero'),
                    //====start_premium
                    'style-1'  => esc_html__('Blog Grid', 'postero'),
                    'list'  => esc_html__('Blog List', 'postero'),
                    //====end_premium
                ),
            ));

            $wp_customize->add_setting('postero_options_blog_columns', array(
                'type'              => 'option',
                'default'           => 1,
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('postero_options_blog_columns', array(
                'section' => 'postero_blog_archive',
                'label'   => esc_html__('Colunms', 'postero'),
                'type'    => 'select',
                'choices' => array(
                    1 => esc_html__('1', 'postero'),
                    2 => esc_html__('2', 'postero'),
                    3 => esc_html__('3', 'postero'),
                    4 => esc_html__('4', 'postero'),
                ),
            ));

            $wp_customize->add_setting('postero_options_blog_archive_sidebar', array(
                'type'              => 'option',
                'default'           => 'right',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('postero_options_blog_archive_sidebar', array(
                'section' => 'postero_blog_archive',
                'label'   => esc_html__('Sidebar Position', 'postero'),
                'type'    => 'select',
                'choices' => array(
                    'left'  => esc_html__('Left', 'postero'),
                    'right' => esc_html__('Right', 'postero'),
                ),
            ));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         *
         * @return void
         */
        public function init_postero_social($wp_customize) {

            $wp_customize->add_section('postero_social', array(
                'title' => esc_html__('Socials', 'postero'),
            ));
            $wp_customize->add_setting('postero_options_social_share', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('postero_options_social_share', array(
                'type'    => 'checkbox',
                'section' => 'postero_social',
                'label'   => esc_html__('Show Social Share', 'postero'),
            ));
            $wp_customize->add_setting('postero_options_social_share_facebook', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('postero_options_social_share_facebook', array(
                'type'    => 'checkbox',
                'section' => 'postero_social',
                'label'   => esc_html__('Share on Facebook', 'postero'),
            ));
            $wp_customize->add_setting('postero_options_social_share_twitter', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('postero_options_social_share_twitter', array(
                'type'    => 'checkbox',
                'section' => 'postero_social',
                'label'   => esc_html__('Share on Twitter', 'postero'),
            ));
            $wp_customize->add_setting('postero_options_social_share_linkedin', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('postero_options_social_share_linkedin', array(
                'type'    => 'checkbox',
                'section' => 'postero_social',
                'label'   => esc_html__('Share on Linkedin', 'postero'),
            ));
            $wp_customize->add_setting('postero_options_social_share_google-plus', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('postero_options_social_share_google-plus', array(
                'type'    => 'checkbox',
                'section' => 'postero_social',
                'label'   => esc_html__('Share on Google+', 'postero'),
            ));

            $wp_customize->add_setting('postero_options_social_share_pinterest', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('postero_options_social_share_pinterest', array(
                'type'    => 'checkbox',
                'section' => 'postero_social',
                'label'   => esc_html__('Share on Pinterest', 'postero'),
            ));
            $wp_customize->add_setting('postero_options_social_share_email', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('postero_options_social_share_email', array(
                'type'    => 'checkbox',
                'section' => 'postero_social',
                'label'   => esc_html__('Share on Email', 'postero'),
            ));
        }

        /**
         * @param $wp_customize WP_Customize_Manager
         *
         * @return void
         */
        public function init_woocommerce($wp_customize) {

            $wp_customize->add_panel('woocommerce', array(
                'title' => esc_html__('Woocommerce', 'postero'),
            ));

            $wp_customize->add_section('postero_woocommerce_archive', array(
                'title'      => esc_html__('Archive', 'postero'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
                'priority'   => 1,
            ));

            $wp_customize->add_setting('postero_options_woocommerce_archive_layout', array(
                'type'              => 'option',
                'default'           => 'default',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('postero_options_woocommerce_archive_layout', array(
                'section' => 'postero_woocommerce_archive',
                'label'   => esc_html__('Layout Style', 'postero'),
                'type'    => 'select',
                'choices' => array(
                    'default'  => esc_html__('Sidebar', 'postero'),
                    //====start_premium
                    'canvas'   => esc_html__('Canvas Filter', 'postero'),
                    'menu'     => esc_html__('Menu Filter', 'postero'),
                    'dropdown' => esc_html__('Dropdown Filter', 'postero'),
                    //====end_premium
                ),
            ));

            $wp_customize->add_setting('postero_options_woocommerce_archive_sidebar', array(
                'type'              => 'option',
                'default'           => 'left',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('postero_options_woocommerce_archive_sidebar', array(
                'section' => 'postero_woocommerce_archive',
                'label'   => esc_html__('Sidebar Position', 'postero'),
                'type'    => 'select',
                'choices' => array(
                    'left'  => esc_html__('Left', 'postero'),
                    'right' => esc_html__('Right', 'postero'),

                ),
            ));

            $wp_customize->add_setting('postero_options_woocommerce_shop_pagination', array(
                'type'              => 'option',
                'default'           => 'default',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('postero_options_woocommerce_shop_pagination', array(
                'section' => 'postero_woocommerce_archive',
                'label'   => esc_html__('Products pagination', 'postero'),
                'type'    => 'select',
                'choices' => array(
                    'default'  => esc_html__('Pagination', 'postero'),
                    'more-btn' => esc_html__('Load More', 'postero'),
                    'infinit'  => esc_html__('Infinit Scroll', 'postero'),
                ),
            ));

            // =========================================
            // Single Product
            // =========================================

            $wp_customize->add_section('postero_woocommerce_single', array(
                'title'      => esc_html__('Single Product', 'postero'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
            ));

            $wp_customize->add_setting('postero_options_single_product_gallery_layout', array(
                'type'              => 'option',
                'default'           => 'horizontal',
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('postero_options_single_product_gallery_layout', array(
                'section' => 'postero_woocommerce_single',
                'label'   => esc_html__('Style', 'postero'),
                'type'    => 'select',
                'choices' => array(
                    'horizontal'   => esc_html__('Horizontal', 'postero'),
                    'vertical'   => esc_html__('Vertical', 'postero'),
                    'gallery'   => esc_html__('Gallery', 'postero'),
                    'sticky'    => esc_html__('Sticky', 'postero'),
                    'slider'    => esc_html__('Slider', 'postero'),
                ),
            ));

            $wp_customize->add_setting('postero_options_single_product_content_meta', array(
                'type'              => 'option',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'postero_sanitize_editor',
            ));

            $wp_customize->add_control(new Postero_Customize_Control_Editor($wp_customize, 'postero_options_single_product_content_meta', array(
                'section' => 'postero_woocommerce_single',
                'label'   => esc_html__('Single extra description', 'postero'),
            )));


            // =========================================
            // Product
            // =========================================


            $wp_customize->add_section('postero_woocommerce_product', array(
                'title'      => esc_html__('Product Block', 'postero'),
                'capability' => 'edit_theme_options',
                'panel'      => 'woocommerce',
            ));

            $wp_customize->add_setting('postero_options_wocommerce_block_horizontal_support', array(
                'type'              => 'option',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('postero_options_wocommerce_block_horizontal_support', array(
                'type'    => 'checkbox',
                'section' => 'postero_woocommerce_product',
                'label'   => esc_html__('Horizontal/Vertical images support', 'postero'),
            ));

            $wp_customize->add_setting('postero_options_wocommerce_block_horizontal_ratio', array(
                'type'              => 'option',
                'sanitize_callback' => 'sanitize_text_field',
                'default'           => '1/1',
            ));

            $wp_customize->add_control('postero_options_wocommerce_block_horizontal_ratio', array(
                'type'    => 'text',
                'section' => 'postero_woocommerce_product',
                'label'   => esc_html__('Horizontal/Vertical Ratio', 'postero'),
            ));

            if(class_exists('Postero_Customize_Control_Color')){
                $wp_customize->add_setting( 'postero_options_wocommerce_block_horizontal_bg', array(
                    'type'              => 'option',
                    'default'           => '#f5f5f5',
                    'transport'         => 'postMessage',
                    'sanitize_callback' => 'maybe_hash_hex_color',
                ) );
                $wp_customize->add_control( new Postero_Customize_Control_Color( $wp_customize, 'postero_options_wocommerce_block_horizontal_bg', array(
                    'section' => 'postero_woocommerce_product',
                    'label' => esc_html__( 'Horizontal/Vertical Background', 'postero' ),
                ) ) );
            }


            $wp_customize->add_setting('postero_options_wocommerce_block_style', array(
                'type'              => 'option',
                'default'           => '',
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_text_field',
            ));

            $wp_customize->add_control('postero_options_wocommerce_block_style', array(
                'section' => 'postero_woocommerce_product',
                'label'   => esc_html__('Style', 'postero'),
                'type'    => 'select',
                'choices' => array(
                    '' => esc_html__('Style 1', 'postero'),
                ),
            ));

            $wp_customize->add_setting('postero_options_woocommerce_product_hover', array(
                'type'              => 'option',
                'default'           => 'none',
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_text_field',
            ));
            $wp_customize->add_control('postero_options_woocommerce_product_hover', array(
                'section' => 'postero_woocommerce_product',
                'label'   => esc_html__('Animation Image Hover', 'postero'),
                'type'    => 'select',
                'choices' => array(
                    'none'          => esc_html__('None', 'postero'),
                    'bottom-to-top' => esc_html__('Bottom to Top', 'postero'),
                    'top-to-bottom' => esc_html__('Top to Bottom', 'postero'),
                    'right-to-left' => esc_html__('Right to Left', 'postero'),
                    'left-to-right' => esc_html__('Left to Right', 'postero'),
                    'swap'          => esc_html__('Swap', 'postero'),
                    'fade'          => esc_html__('Fade', 'postero'),
                    'zoom-in'       => esc_html__('Zoom In', 'postero'),
                    'zoom-out'      => esc_html__('Zoom Out', 'postero'),
                ),
            ));
        }
    }
}
return new Postero_Customize();
