<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Postero_Lookbook extends Elementor\Widget_Base {

    public function get_name() {
        return 'postero-image-lookbook';
    }

    public function get_title() {
        return esc_html__('Postero Look Book', 'postero');
    }

    public function get_categories() {
        return array('postero-addons');
    }

    public function get_icon() {
        return 'eicon-image-hotspot';
    }

    public function get_style_depends() {
        return ['swiper'];
    }

    public function get_script_depends() {
        return ['postero-elementor-look-book', 'swiper'];
    }

    protected function register_controls() {

        $this->start_controls_section('image_lookbook_image_section',
            [
                'label' => esc_html__('Image', 'postero'),
            ]
        );

        $this->add_control('image_lookbook_image',
            [
                'label'       => esc_html__('Choose Image', 'postero'),
                'type'        => Controls_Manager::MEDIA,
                'default'     => [
                    'url' => Elementor\Utils::get_placeholder_image_src(),
                ],
                'label_block' => true
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'    => 'background_image', // Actually its `image_size`.
                'default' => 'full'
            ]
        );
        $this->end_controls_section();
        $this->start_controls_section('image_lookbook_icons_settings',
            [
                'label' => esc_html__('Hotspots', 'postero'),
            ]
        );
        $repeater = new Elementor\Repeater();

        $repeater->add_responsive_control('postero_image_lookbook_main_horizontal_position',
            [
                'label'      => esc_html__('Horizontal Position', 'postero'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'default'    => [
                    'size' => 50,
                    'unit' => '%'
                ],
                'selectors'  => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.lookbook_dot' => 'left: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        $repeater->add_responsive_control('postero_image_lookbook_main_vertical_position',
            [
                'label'      => esc_html__('Vertical Position', 'postero'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 20,
                    ],
                ],
                'default'    => [
                    'size' => 50,
                    'unit' => '%'
                ],
                'selectors'  => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}.lookbook_dot' => 'top: {{SIZE}}{{UNIT}}'
                ]
            ]
        );

        if (postero_is_woocommerce_activated()) {
            $repeater->add_control('image_lookbooks_content',
                [
                    'label'   => esc_html__('Content to Show', 'postero'),
                    'type'    => Controls_Manager::SELECT,
                    'options' => [
                        'text_editor'       => esc_html__('Text Editor', 'postero'),
                        'elementor_product' => esc_html__('Product', 'postero'),
                    ],
                    'default' => 'text_editor'
                ]
            );
        } else {
            $repeater->add_control('image_lookbooks_content',
                [
                    'label'   => esc_html__('Content to Show', 'postero'),
                    'type'    => Controls_Manager::SELECT,
                    'options' => [
                        'text_editor' => esc_html__('Text Editor', 'postero'),
                    ],
                    'default' => 'text_editor'
                ]
            );
        }

        $repeater->add_control('image_lookbook_text',
            [
                'label'     => esc_html__('Content', 'postero'),
                'type'      => Controls_Manager::WYSIWYG,
                'default'   => 'Lorem ipsum',
                'condition' => [
                    'image_lookbooks_content' => 'text_editor'
                ]
            ]);
        if (postero_is_woocommerce_activated()) {
            $repeater->add_control('image_lookbooks_product',
                [
                    'label'       => esc_html__('Products name', 'postero'),
                    'type'        => 'products',
                    'multiple'    => false,
                    'label_block' => true,
                    'condition'   => [
                        'image_lookbooks_content' => 'elementor_product'
                    ],
                ]
            );
        }

        $this->add_control('image_lookbook_icons',
            [
                'label'  => esc_html__('lookbook', 'postero'),
                'type'   => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_image',
            [
                'label' => esc_html__('Image', 'postero'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'image_border',
                'selector'  => '{{WRAPPER}} .main-image-lookbook',
            ]
        );

        $this->add_responsive_control(
            'image_padding',
            [
                'label'      => esc_html__('Padding', 'postero'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .main-image-lookbook' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_margin',
            [
                'label'      => esc_html__('Margin', 'postero'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .main-image-lookbook' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section('image_lookbook_hotspots_style_settings',
            [
                'label' => esc_html__('Hotspots', 'postero'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'hotspots_color',
            [
                'label'     => esc_html__('Color', 'postero'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .lookbook_dot:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hotspots_shadow',
            [
                'label'     => esc_html__('Shadow Color', 'postero'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .lookbook_dot:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__('Content', 'postero'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'content_border',
                'selector'  => '{{WRAPPER}} .image-lookbook-content',
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label'      => esc_html__('Padding', 'postero'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .image-lookbook-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label'      => esc_html__('Margin', 'postero'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .image-lookbook-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_section();

    }

    protected function render() {
        $settings       = $this->get_settings_for_display();
        $image_src      = $settings['image_lookbook_image'];
        $image_src_size = Group_Control_Image_Size::get_attachment_image_src($image_src['id'], 'background_image', $settings);
        if (empty($image_src_size)) {
            $image_src_size = $image_src['url'];
        }

        $content_html = '';

        $this->add_render_attribute([
            'swiper'      => [
                'class' => ['swiper', 'look-book-swiper'],
            ],
            'swiper-wrap' => [
                'class' => 'swiper-wrapper',
            ],
        ]);

        ?>

        <div class="image-lookbook-wrapper">
            <div class="image-lookbook-dots">
                <img class="main-image-lookbook" alt="<?php echo esc_html__('Background', 'postero'); ?>" src="<?php echo esc_url($image_src_size); ?>">
                <?php foreach ($settings['image_lookbook_icons'] as $index => $item) { ?>
                    <?php
                    $class_item            = $index == 0 ? 'active elementor-repeater-item-' . $item['_id'] : 'elementor-repeater-item-' . $item['_id'];
                    $tab_title_setting_key = $this->get_repeater_setting_key('tab_title', 'tabs', $index);
                    $this->add_render_attribute($tab_title_setting_key, [
                        'class'     => ['lookbook_dot', $class_item],
                        'data-goto' => $index,
                    ]);

                    $tab_content_setting_key = $this->get_repeater_setting_key('tab_content', 'tabs', $index);
                    $this->add_render_attribute($tab_content_setting_key, [
                        'class' => ['hostpot-content', 'swiper-slide'],
                    ]);
                    ?>
                    <div <?php $this->print_render_attribute_string($tab_title_setting_key); ?>></div>
                    <?php

                    ob_start();
                    ?>
                    <div <?php $this->print_render_attribute_string($tab_content_setting_key); ?>>
                        <?php
                        if (($item['image_lookbooks_content'] == 'elementor_product') && postero_is_woocommerce_activated()) {
                            $this->render_product($item['image_lookbooks_product']);
                        } else {
                            $this->print_text_editor($item['image_lookbook_text']);
                        } ?></div>

                    <?php
                    $content_html .= ob_get_clean();
                } ?>
            </div>
            <div class="image-lookbook-content">
                <a href="#" class="button-close"><svg xmlns="http://www.w3.org/2000/svg" role="presentation" viewBox="0 0 320 512"><path d="M310.6 361.4c12.5 12.5 12.5 32.75 0 45.25C304.4 412.9 296.2 416 288 416s-16.38-3.125-22.62-9.375L160 301.3L54.63 406.6C48.38 412.9 40.19 416 32 416S15.63 412.9 9.375 406.6c-12.5-12.5-12.5-32.75 0-45.25l105.4-105.4L9.375 150.6c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L160 210.8l105.4-105.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-105.4 105.4L310.6 361.4z"></path></svg></a>
                <div <?php $this->print_render_attribute_string('swiper'); ?>>
                    <div <?php $this->print_render_attribute_string('swiper-wrap'); ?>>
                        <?php
                        printf('%s', $content_html);
                        ?>
                    </div>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>

        <?php
    }

    public function render_product($product_id) {
        $args      = array(
            'post_type'      => 'product',
            'posts_per_page' => 1,
            'post__in'       => array($product_id)
        );
        $the_query = new WP_Query($args);
        if ($the_query->have_posts()) :
            ?>
            <ul class="products">
                <?php
                while ($the_query->have_posts()) : $the_query->the_post();
                    wc_get_template_part('content-product', 1);
                endwhile;
                ?>
            </ul>
        <?php
        endif;
        wp_reset_postdata();
    }
}

$widgets_manager->register(new Postero_Lookbook());
