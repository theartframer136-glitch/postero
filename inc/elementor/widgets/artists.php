<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!postero_is_mas_woocommerce_brands_activated()) {
    return;
}

use Elementor\Controls_Manager;

/**
 * Elementor tabs widget.
 *
 * Elementor widget that displays vertical or horizontal tabs with different
 * pieces of content.
 *
 * @since 1.0.0
 */
class Postero_Elementor_Artists extends Postero_Base_Widgets_Swiper {

    public function get_categories() {
        return array('postero-addons');
    }

    /**
     * Get widget name.
     *
     * Retrieve tabs widget name.
     *
     * @return string Widget name.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_name() {
        return 'postero-artists';
    }

    /**
     * Get widget title.
     *
     * Retrieve tabs widget title.
     *
     * @return string Widget title.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_title() {
        return esc_html__('Artists', 'postero');
    }

    /**
     * Get widget icon.
     *
     * Retrieve tabs widget icon.
     *
     * @return string Widget icon.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_icon() {
        return 'eicon-person';
    }

    /**
     * Enqueue scripts.
     *
     * Registers all the scripts defined as element dependencies and enqueues
     * them. Use `get_script_depends()` method to add custom script dependencies.
     *
     * @since 1.3.0
     * @access public
     */

    public function get_script_depends() {
        return ['postero-elementor-artists'];
    }

    /**
     * Register tabs widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function register_controls() {

        $this->start_controls_section(
            'section_author',
            [
                'label' => esc_html__('Artists', 'postero'),
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label'   => esc_html__('Posts Per Page', 'postero'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );

        $this->add_control(
            'include',
            [
                'label'       => esc_html__('Include', 'postero'),
                'type'        => Controls_Manager::SELECT2,
                'label_block' => true,
                'options'     => $this->get_product_artists(),
                'multiple'    => true,
            ]
        );

        $this->add_control(
            'exclude',
            [
                'label'       => esc_html__('Exclude', 'postero'),
                'type'        => Controls_Manager::SELECT2,
                'label_block' => true,
                'options'     => $this->get_product_artists(),
                'multiple'    => true,
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

        $this->add_responsive_control(
            'column',
            [
                'label'     => esc_html__('Columns', 'postero'),
                'type'      => \Elementor\Controls_Manager::SELECT,
                'default'   => 3,
                'options'   => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6],
                'selectors' => [
                    '{{WRAPPER}} .d-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr)',
                ],
                'condition' => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_responsive_control(
            'item_spacing',
            [
                'label'      => esc_html__('Spacing', 'postero'),
                'type'       => Controls_Manager::SLIDER,
                'range'      => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'            => [
                    'size' => 30
                ],
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .d-grid' => 'grid-gap:{{SIZE}}{{UNIT}}',
                ],
                'condition'  => ['enable_carousel!' => 'yes']
            ]
        );

        $this->add_control(
            'style',
            [
                'label'        => esc_html__('Style', 'postero'),
                'type'         => \Elementor\Controls_Manager::SELECT,
                'options'      => [
                    '1' => esc_html__('Style 1', 'postero'),
                    '2' => esc_html__('Style 2', 'postero'),
                    '3' => esc_html__('Style 3', 'postero'),
                    '4' => esc_html__('Style 4', 'postero'),
                ],
                'render_type'  => 'template',
                'default'      => '1',
                'prefix_class' => 'style-'
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

        $this->add_control_carousel(['enable_carousel' => 'yes']);

    }

    /**
     * Render tabs widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render() {

        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('wrapper', 'class', 'elementor-artists-wrapper');
        $this->get_data_elementor_columns();;

        $artists_taxonomy = Mas_WC_Brands()->get_brand_taxonomy();

        $exclude = !empty($settings['exclude']) ? $settings['exclude'] : array();
        $include = !empty($settings['include']) ? $settings['include'] : array();

        $taxonomy_args = array(
            'taxonomy'   => $artists_taxonomy,
            'hide_empty' => true,
            'orderby'    => 'name',
            'slug'       => '',
            'include'    => $include,
            'exclude'    => $exclude,
            'number'     => $settings['posts_per_page'],
            'order'      => $settings['order']
        );

        $artists = get_terms($taxonomy_args);

        if (!$artists || !is_array($artists)) {
            return;
        }

        ?>
        <div <?php $this->print_render_attribute_string('wrapper'); ?>>
            <div <?php $this->print_render_attribute_string('row'); ?>>
                <?php
                foreach ($artists as $index => $artist) :
                    ?>
                    <div <?php $this->print_render_attribute_string('item'); // WPCS: XSS ok.
                    ?>>
                        <div class="artists-block">
                            <?php
                            if ($settings['style'] == '1' || $settings['style'] == '3') {
                                $args = array(
                                    'post_type'      => 'product',
                                    'posts_per_page' => 3,
                                    'tax_query'      => array(
                                        array(
                                            'taxonomy' => $artists_taxonomy,
                                            'field'    => 'term_id',
                                            'terms'    => array($artist->term_id),
                                        ),
                                    ),
                                );

                                $products            = get_posts($args);
                                $class_product_count = 'product-layout-count-' . count($products);
                                ?>
                                <div class="artists-product <?php echo esc_attr($class_product_count); ?>">
                                    <?php
                                    if (!empty($products)) {
                                        foreach ($products as $product) {
                                            $product_id = $product->ID;
                                            if (has_post_thumbnail($product_id)) {
                                                echo '<div><a href="' . esc_url(get_permalink($product_id)) . '">' . get_the_post_thumbnail($product_id, 'medium') . '</a></div>';
                                            }
                                        }
                                    }
                                    ?>
                                </div>
                            <?php } ?>
                            <div class="artists-caption">
                                <a class="thumbnail" href="<?php echo esc_url(get_term_link($artist->slug, $artists_taxonomy)); ?>" title="<?php echo esc_attr($artist->name); ?>">
                                    <?php echo mas_wcbr_get_brand_thumbnail_image($artist, 'medium'); ?>
                                </a>
                                <?php if ($settings['style'] == '4') { ?>
                                <div>
                                    <?php } ?>
                                    <a class="name" href="<?php echo esc_url(get_term_link($artist->slug, $artists_taxonomy)); ?>" title="<?php echo esc_attr($artist->name); ?>">
                                        <?php echo esc_attr($artist->name); ?>
                                    </a>
                                    <div class="count"><?php echo esc_html($artist->count . ' ' . _n('poster', 'posters', $artist->count, 'postero')); ?></div>
                                    <?php if ($settings['style'] == '4') { ?>
                                </div>
                            <?php } ?>
                            </div>
                            <?php
                            if ($settings['style'] == '3') {
                                ?>
                                <div class="artists-button">
                                    <a class="button" href="<?php echo esc_url(get_term_link($artist->slug, $artists_taxonomy)); ?>" title="<?php echo esc_attr($artist->name); ?>"><?php echo esc_html__('View profile', 'postero'); ?>
                                        <i class="postero-icon-chevron-right"></i></a>
                                </div>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                <?php endforeach;
                ?>
            </div>
        </div>
        <?php

    }

    protected function get_product_artists() {
        $artists_taxonomy = Mas_WC_Brands()->get_brand_taxonomy();
        $artists          = get_terms(array(
                'taxonomy'   => $artists_taxonomy,
                'hide_empty' => false,
            )
        );
        $results          = array();
        if (!is_wp_error($artists)) {
            foreach ($artists as $artist) {
                $results[$artist->term_id] = $artist->name;
            }
        }
        return $results;
    }
}

$widgets_manager->register(new Postero_Elementor_Artists());
