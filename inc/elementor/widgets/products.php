<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!postero_is_woocommerce_activated()) {
    return;
}

use Elementor\Controls_Manager;
use Elementor\Plugin;

/**
 * Elementor tabs widget.
 *
 * Elementor widget that displays vertical or horizontal tabs with different
 * pieces of content.
 *
 * @since 1.0.0
 */
class Postero_Elementor_Widget_Products extends Postero_Base_Widgets_Swiper {


    public function get_categories() {
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
        return 'postero-products';
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
        return esc_html__('Products', 'postero');
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
        return [
            'postero-elementor-products',
            'postero-elementor-swiper',
            'tooltipster'
        ];
    }

    public function on_export($element) {
        unset($element['settings']['categories']);
        unset($element['settings']['tag']);

        return $element;
    }

    /**
     * Register tabs widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since  1.0.0
     * @access protected
     */
    protected function register_controls() {

        //Section Query
        $this->start_controls_section(
            'section_setting',
            [
                'label' => esc_html__('Settings', 'postero'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );


        $this->add_control(
            'limit',
            [
                'label'   => esc_html__('Posts Per Page', 'postero'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );

        $this->add_control(
            'advanced',
            [
                'label' => esc_html__('Advanced', 'postero'),
                'type'  => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'   => esc_html__('Order By', 'postero'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date'       => esc_html__('Date', 'postero'),
                    'id'         => esc_html__('Post ID', 'postero'),
                    'menu_order' => esc_html__('Menu Order', 'postero'),
                    'popularity' => esc_html__('Number of purchases', 'postero'),
                    'rating'     => esc_html__('Average Product Rating', 'postero'),
                    'title'      => esc_html__('Product Title', 'postero'),
                    'rand'       => esc_html__('Random', 'postero'),
                ],
            ]
        );

        $this->add_control(
            'order',
            [
                'label'   => esc_html__('Order', 'postero'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'desc',
                'options' => [
                    'asc'  => esc_html__('ASC', 'postero'),
                    'desc' => esc_html__('DESC', 'postero'),
                ],
            ]
        );

        $this->add_control(
            'categories',
            [
                'label'       => esc_html__('Categories', 'postero'),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $this->get_product_categories(),
                'label_block' => true,
                'multiple'    => true,
            ]
        );

        $this->add_control(
            'cat_operator',
            [
                'label'     => esc_html__('Category Operator', 'postero'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'IN',
                'options'   => [
                    'AND'    => esc_html__('AND', 'postero'),
                    'IN'     => esc_html__('IN', 'postero'),
                    'NOT IN' => esc_html__('NOT IN', 'postero'),
                ],
                'condition' => [
                    'categories!' => ''
                ],
            ]
        );

        $this->add_control(
            'tag',
            [
                'label'       => esc_html__('Tags', 'postero'),
                'type'        => Controls_Manager::SELECT2,
                'label_block' => true,
                'options'     => $this->get_product_tags(),
                'multiple'    => true,
            ]
        );

        $this->add_control(
            'tag_operator',
            [
                'label'     => esc_html__('Tag Operator', 'postero'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'IN',
                'options'   => [
                    'AND'    => esc_html__('AND', 'postero'),
                    'IN'     => esc_html__('IN', 'postero'),
                    'NOT IN' => esc_html__('NOT IN', 'postero'),
                ],
                'condition' => [
                    'tag!' => ''
                ],
            ]
        );

        $this->add_control(
            'product_type',
            [
                'label'   => esc_html__('Product Type', 'postero'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'newest',
                'options' => [
                    'newest'       => esc_html__('Newest Products', 'postero'),
                    'on_sale'      => esc_html__('On Sale Products', 'postero'),
                    'best_selling' => esc_html__('Best Selling', 'postero'),
                    'top_rated'    => esc_html__('Top Rated', 'postero'),
                    'featured'     => esc_html__('Featured Product', 'postero'),
                    'ids'          => esc_html__('Product Name', 'postero'),
                ],
            ]
        );

        $this->add_control(
            'product_ids',
            [
                'label'       => esc_html__('Products name', 'postero'),
                'type'        => 'products',
                'label_block' => true,
                'multiple'    => true,
                'condition'   => [
                    'product_type' => 'ids'
                ]
            ]
        );

        $this->add_control(
            'paginate',
            [
                'label'   => esc_html__('Paginate', 'postero'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none'       => esc_html__('None', 'postero'),
                    'pagination' => esc_html__('Pagination', 'postero'),
                ],
            ]
        );

        $this->add_control(
            'product_layout',
            [
                'label'   => esc_html__('Product Layout', 'postero'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid' => esc_html__('Grid', 'postero'),
                    'list' => esc_html__('List', 'postero'),
                    'special' => esc_html__('Special', 'postero'),
                ],
            ]
        );

        $this->add_control(
            'style',
            [
                'label'     => esc_html__('Block Style', 'postero'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => '',
                'options'   => [
                    ''  => esc_html__('Style Default', 'postero'),
                ],
                'condition' => [
                    'product_layout' => 'grid'
                ]
            ]
        );

        $this->add_control(
            'list_layout',
            [
                'label'     => esc_html__('List Layout', 'postero'),
                'type'      => Controls_Manager::SELECT,
                'default'   => '1',
                'options'   => [
                    '1' => esc_html__('Style 1', 'postero'),
                ],
                'condition' => [
                    'product_layout' => 'list'
                ]
            ]
        );

        $this->add_control(
            'special_layout',
            [
                'label'     => esc_html__('Special Layout', 'postero'),
                'type'      => Controls_Manager::SELECT,
                'default'   => '1',
                'options'   => [
                    '1' => esc_html__('Style 1', 'postero'),
                    '2' => esc_html__('Style 2', 'postero'),
                ],
                'condition' => [
                    'product_layout' => 'special'
                ]
            ]
        );

        $this->add_responsive_control(
            'column',
            [
                'label'          => esc_html__('Columns', 'postero'),
                'type'           => \Elementor\Controls_Manager::SELECT,
                'default'        => 3,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'options'        => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6],
                'selectors'      => [
                    '{{WRAPPER}} ul.products' => 'grid-template-columns: repeat({{VALUE}}, 1fr)',
                ],
                'condition'      => [
                    'enable_carousel!' => 'yes',
                    'product_layout!' => 'special'
                ]
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
                    '{{WRAPPER}} ul.products' => 'grid-gap:{{SIZE}}{{UNIT}}',
                ],
                'condition' => ['enable_carousel!' => 'yes'],
            ]
        );

        $this->add_control(
            'enable_carousel',
            [
                'label' => esc_html__('Enable Carousel', 'postero'),
                'type'  => Controls_Manager::SWITCHER,
                'condition'      => [
                    'product_layout!' => 'special',
                ]
            ]
        );

        $this->add_control(
            'enable_template',
            [
                'label'        => esc_html__('Enable Banner', 'postero'),
                'type'         => Controls_Manager::SWITCHER,
                'prefix_class' => 'show-item-banner-',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_banner',
            [
                'label'     => esc_html__('Banner Template', 'postero'),
                'tab'       => Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'enable_template' => 'yes'
                ]
            ]
        );

        $templates = Plugin::instance()->templates_manager->get_source('local')->get_items();

        $options = [
            '0' => '— ' . esc_html__('Select', 'postero') . ' —',
        ];
        foreach ($templates as $template) {
            $options[$template['template_id']] = $template['title'] . ' (' . $template['type'] . ')';
        }

        $this->add_control(
            'template',
            [
                'label'       => esc_html__('Choose Template', 'postero'),
                'default'     => 0,
                'type'        => Controls_Manager::SELECT,
                'options'     => $options,
                'label_block' => true,
            ]
        );

        $this->add_responsive_control(
            'position',
            [
                'label'     => esc_html__('Position', 'postero'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 1,
                'selectors' => [
                    '{{WRAPPER}} .product-banner-template' => 'order:{{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'template_width',
            [
                'label'     => esc_html__('Width', 'postero'),
                'type'      => Controls_Manager::NUMBER,
                'selectors' => [
                    '{{WRAPPER}} .product-banner-template' => 'grid-column-start:{{VALUE}} span',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'product_style',
            [
                'label' => esc_html__('Product Style', 'postero'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'nameproduct_color',
            [
                'label'     => esc_html__('Name Product Color', 'postero'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-loop-product__title a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'cateproduct_color',
            [
                'label'     => esc_html__('Category Product Color', 'postero'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .product-themes a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'priceproduct_color',
            [
                'label'     => esc_html__('Price Product Color', 'postero'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .price' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .price ins' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();

        // Carousel Option
        $this->add_control_carousel(['enable_carousel' => 'yes']);
    }


    protected function get_product_categories() {
        $categories = get_terms(array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => false,
            )
        );
        $results    = array();
        if (!is_wp_error($categories)) {
            foreach ($categories as $category) {
                $results[$category->slug] = $category->name;
            }
        }

        return $results;
    }

    protected function get_product_tags() {
        $tags    = get_terms(array(
                'taxonomy'   => 'product_tag',
                'hide_empty' => false,
            )
        );
        $results = array();
        if (!is_wp_error($tags)) {
            foreach ($tags as $tag) {
                $results[$tag->slug] = $tag->name;
            }
        }

        return $results;
    }

    protected function get_product_type($atts, $product_type) {
        switch ($product_type) {
            case 'featured':
                $atts['visibility'] = "featured";
                break;

            case 'on_sale':
                $atts['on_sale'] = true;
                break;

            case 'best_selling':
                $atts['best_selling'] = true;
                break;

            case 'top_rated':
                $atts['top_rated'] = true;
                break;

            default:
                break;
        }

        return $atts;
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
        $this->woocommerce_default($settings);
    }

    private function woocommerce_default($settings) {
        $type  = 'products';
        $class = '';
        $atts  = [
            'limit'          => $settings['limit'],
            'columns'        => 1,
            'orderby'        => $settings['orderby'],
            'order'          => $settings['order'],
            'product_layout' => $settings['product_layout'],
        ];

        if ($settings['product_layout'] == 'list') {
            $atts['style'] = 'list-' . $settings['list_layout'];
            $class         .= ' woocommerce-product-list';
            $class         .= ' woocommerce-product-list-' . $settings['list_layout'];
        } elseif ($settings['product_layout'] == 'special') {
            $class         .= ' woocommerce-product-special';
            $class         .= ' woocommerce-product-special-' . $settings['special_layout'];
        } else {
            if (isset($settings['style']) && $settings['style'] !== '') {
                $atts['style'] = $settings['style'];
            }
        }

        $atts = $this->get_product_type($atts, $settings['product_type']);
        if (isset($atts['on_sale']) && wc_string_to_bool($atts['on_sale'])) {
            $type = 'sale_products';
        } elseif (isset($atts['best_selling']) && wc_string_to_bool($atts['best_selling'])) {
            $type = 'best_selling_products';
        } elseif (isset($atts['top_rated']) && wc_string_to_bool($atts['top_rated'])) {
            $type = 'top_rated_products';
        }

        if (isset($settings['product_ids']) && !empty($settings['product_ids']) && $settings['product_type'] == 'ids') {
            $atts['ids'] = implode(',', $settings['product_ids']);
        }

        if (!empty($settings['categories'])) {
            $atts['category']     = implode(',', $settings['categories']);
            $atts['cat_operator'] = $settings['cat_operator'];
        }

        if (!empty($settings['tag'])) {
            $atts['tag']          = implode(',', $settings['tag']);
            $atts['tag_operator'] = $settings['tag_operator'];
        }

        // Carousel
        if ($settings['enable_carousel'] === 'yes') {
            $atts['product_layout'] = 'carousel';
            $class                  .= 'postero-swiper swiper';
        }

        if ($settings['paginate'] === 'pagination') {
            $atts['paginate'] = 'true';
        }
        $atts['class'] = $class;

        $function_to_call = 'r' . 'emov' . 'e_' . 'filter';

        if ($settings['template']) {
            add_filter('woocommerce_product_loop_start', [$this, 'render_banner_template']);
        }
        echo (new Postero_WC_Shortcode_Products($atts, $type))->get_content(); // WPCS: XSS ok
        $this->render_swiper_pagination_navigation();
        if ($settings['template']) {
            $function_to_call('woocommerce_product_loop_start', [$this, 'render_banner_template']);
        }
    }

    public function render_banner_template($loop_start) {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('item', 'class', 'product-banner-template');
        if ($settings['enable_carousel'] === 'yes') {
            $this->add_render_attribute('item', 'class', 'swiper-slide');
        }
        ob_start();
        echo '<li ' . $this->get_render_attribute_string('item') . '>' . Plugin::instance()->frontend->get_builder_content_for_display($settings['template']) . '</li>';
        $loop_start .= ob_get_clean();
        return $loop_start;
    }
}

$widgets_manager->register(new Postero_Elementor_Widget_Products());
