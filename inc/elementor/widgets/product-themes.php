<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!postero_is_woocommerce_activated()) {
    return;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

/**
 * Elementor Postero_Elementor_Products_Categories
 * @since 1.0.0
 */
class Postero_Elementor_Products_Themes extends Postero_Base_Widgets_Swiper {

    public function get_themes() {
        return array('postero-addons');
    }

    /**
     * Get widget name.
     *
     * Retrieve tabs widget name.
     *
     * @return string Widget name.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_name() {
        return 'postero-product-themes';
    }

    /**
     * Get widget title.
     *
     * Retrieve tabs widget title.
     *
     * @return string Widget title.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_title() {
        return esc_html__('Product Themes', 'postero');
    }

    /**
     * Get widget icon.
     *
     * Retrieve tabs widget icon.
     *
     * @return string Widget icon.
     * @since  1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-tabs';
    }

    public function get_script_depends() {
        return ['postero-elementor-product-themes', 'postero-elementor-swiper'];
    }

    protected function register_controls() {

        //Section Query
        $this->start_controls_section(
            'section_setting',
            [
                'label' => esc_html__('Themes', 'postero'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        $repeater = new Repeater();

        $repeater->add_control(
            'themes_name',
            [
                'label' => esc_html__('Alternate Name', 'postero'),
                'type'  => Controls_Manager::TEXT,
            ]
        );

        $repeater->add_control(
            'themes',
            [
                'label'       => esc_html__('Themes', 'postero'),
                'type'        => Controls_Manager::SELECT2,
                'label_block' => true,
                'options'     => $this->get_product_themes(),
                'multiple'    => false,
            ]
        );

        $repeater->add_control(
            'theme_image',
            [
                'label'      => esc_html__('Choose Image', 'postero'),
                'type'       => Controls_Manager::GALLERY,
                'show_label' => false,
                'dynamic'    => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'themes_list',
            [
                'label'       => esc_html__('Items', 'postero'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ themes }}}',
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Image_Size::get_type(),
            [
                'name'      => 'thumbnail',
                'default'   => 'full',
                'separator' => 'none',
            ]
        );

        $this->add_responsive_control(
            'column',
            [
                'label'     => esc_html__('Columns', 'postero'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 1,
                'options'   => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, ],
                'selectors' => [
                    '{{WRAPPER}} .d-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr)',
                ],
                'condition' => ['enable_carousel!' => 'yes']

            ]
        );
        $this->add_responsive_control(
            'product_gutter',
            [
                'label'      => esc_html__('Gutter', 'postero'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'size_units' => ['px'],
                'selectors'  => [
                    '{{WRAPPER}} .d-grid' => 'grid-gap:{{SIZE}}{{UNIT}}',
                ],
                'condition' => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_control(
            'enable_carousel',
            [
                'label' => esc_html__('Enable Carousel', 'postero'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'product_theme_style',
            [
                'label' => esc_html__('Box', 'postero'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'box_bg',
            [
                'label'     => __('Background Color', 'postero'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .product-theme' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->add_responsive_control(
            'box_padding',
            [
                'label'      => esc_html__('Padding', 'postero'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .product-theme' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'content-stretch',
            [
                'label' => esc_html__('Stretch', 'postero'),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'content-stretch-'
            ]
        );


        $this->add_control(
            'box_border_radius',
            [
                'label'      => esc_html__('Border Radius', 'postero'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .product-theme' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'box_shadow',
                'selector' => '{{WRAPPER}} .product-theme',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'box_border',
                'selector'  => '{{WRAPPER}} .product-theme',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'title_style',
            [
                'label' => esc_html__('Title', 'postero'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'tilte_typography',
                'selector' => '{{WRAPPER}} .product-theme-title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__('Margin', 'postero'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .product-theme-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label'      => esc_html__('Padding', 'postero'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .product-theme-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->start_controls_tabs('tab_title');
        $this->start_controls_tab(
            'tab_title_normal',
            [
                'label' => esc_html__('Normal', 'postero'),
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => esc_html__('Color', 'postero'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .product-theme-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_title_hover',
            [
                'label' => esc_html__('Hover', 'postero'),
            ]
        );
        $this->add_control(
            'title_color_hover',
            [
                'label'     => esc_html__('Hover Color', 'postero'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .product-theme:hover .product-theme-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->end_controls_section();

        $this->start_controls_section(
            'count_style',
            [
                'label' => esc_html__('Count', 'postero'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'count_typography',
                'selector' => '{{WRAPPER}} .product-theme-count',
            ]
        );
        $this->add_control(
            'count_color',
            [
                'label'     => esc_html__('Color Count', 'postero'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .product-theme-count' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        // Carousel options
        $this->add_control_carousel(['enable_carousel' => 'yes']);
    }

    protected function get_product_themes() {
        $themes = get_terms(array(
                'taxonomy'   => 'product_themes',
                'hide_empty' => false,
            )
        );
        $results    = array();
        if (!is_wp_error($themes)) {
            foreach ($themes as $theme) {
                $results[$theme->slug] = $theme->name;
            }
        }
        return $results;
    }

    /**
     * Render tabs widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        if (!empty($settings['themes_list']) && is_array($settings['themes_list'])) {
            $this->add_render_attribute('wrapper', 'class', 'elementor-themes-item-wrapper');
            $this->add_render_attribute('row', 'class', 'layout-');

            // Carousel
            $this->get_data_elementor_columns();

            // Item
            $this->add_render_attribute('item', 'class', 'elementor-themes-item');

            ?>
            <div <?php $this->print_render_attribute_string('wrapper'); // WPCS: XSS ok. ?>>
                <div <?php $this->print_render_attribute_string('row'); // WPCS: XSS ok. ?>>
                    <?php foreach ($settings['themes_list'] as $index => $item) { ?>

                        <?php
                        $class_item            = 'elementor-repeater-item-' . $item['_id'];
                        $tab_title_setting_key = $this->get_repeater_setting_key('tab_title', 'tabs', $index);
                        $this->add_render_attribute($tab_title_setting_key, ['class' => ['product-theme', $class_item]]);
                        ?>
                        <div <?php $this->print_render_attribute_string('item'); // WPCS: XSS ok. ?>>
                            <?php if (empty($item['themes'])) {
                                echo esc_html__('Choose Category', 'postero');
                                return;
                            }
                            $theme = get_term_by('slug', $item['themes'], 'product_themes');
                            if ($theme && !is_wp_error($theme)) {
                                $query = new WP_Query(array(
                                    'tax_query' => array(
                                        array(
                                            'taxonomy'         => 'product_themes',
                                            'field'            => 'id',
                                            'terms'            => $theme->term_id,
                                            'include_children' => true,
                                        ),
                                    ),
                                    'nopaging'  => true,
                                    'fields'    => 'ids',
                                ));
                                ?>
                                <div <?php $this->print_render_attribute_string($tab_title_setting_key); ?>>
                                    <?php if (!empty($item['theme_image'])) {?>
                                    <div class="theme-product-img">
                                    <?php foreach ($item['theme_image'] as $items => $attachment) {
                                        $image_url      = Group_Control_Image_Size::get_attachment_image_src($attachment['id'], 'thumbnail', $settings);
                                    ?>
                                            <div class="theme-product-img-item">
                                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(Elementor\Control_Media::get_image_alt($attachment)); ?>"/>
                                            </div>
                                    <?php } ?>
                                    </div>
                                    <?php } ?>
                                    <div class="product-theme-content">
                                        <div class="product-theme-title">
                                            <a href="<?php echo esc_url(get_term_link($theme)); ?>" title="<?php echo esc_attr($theme->name); ?>"><span data-hover="<?php echo empty($item['themes_name']) ? esc_html($theme->name) : sprintf('%s', $item['themes_name']); ?>">
                                                <?php echo empty($item['themes_name']) ? esc_html($theme->name) : sprintf('%s', $item['themes_name']); ?>
                                            </span>
                                            </a>
                                        </div>
                                        <div class="product-theme-count"><?php echo sprintf(_n('%d Poster', '%d Posters', $query->post_count, 'postero'), $query->post_count); ?></div>
                                    </div>
                                </div>
                                <?php
                                wp_reset_postdata();
                            } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <?php $this->render_swiper_pagination_navigation();?>
            <?php
        }
    }

}

$widgets_manager->register(new Postero_Elementor_Products_Themes());

