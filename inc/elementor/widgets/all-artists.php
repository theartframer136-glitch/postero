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
class Postero_Elementor_All_Author extends Elementor\Widget_Base {

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
        return 'postero-all-author';
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
        return esc_html__('All Artists', 'postero');
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
                'options'     => $this->get_product_brands(),
                'multiple'    => true,
            ]
        );

        $this->add_control(
            'exclude',
            [
                'label'       => esc_html__('Exclude', 'postero'),
                'type'        => Controls_Manager::SELECT2,
                'label_block' => true,
                'options'     => $this->get_product_brands(),
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
                ]
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
                ],
                'render_type'  => 'template',
                'default'      => '1',
                'prefix_class' => 'style-'
            ]
        );

        $this->end_controls_section();

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

        $this->add_render_attribute('wrapper', 'class', 'elementor-author-wrapper');

        $this->add_render_attribute('row', 'class', 'd-grid');

        $this->add_render_attribute('item', 'class', 'grid-item');

        $brand_taxonomy = Mas_WC_Brands()->get_brand_taxonomy();

        $first_letter = (get_query_var('first_letter')) ? get_query_var('first_letter') : '';

        $page         = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $exclude = !empty($settings['exclude']) ? $settings['exclude'] : array();
        $include = !empty($settings['include']) ? $settings['include'] : array();

        $offset        = ($page - 1) * $settings['posts_per_page'];
        $taxonomy_args = array(
            'taxonomy'     => $brand_taxonomy,
            'hide_empty'   => true,
            'orderby'      => 'name',
            'slug'         => '',
            'include'      => $include,
            'exclude'      => $exclude,
            'number'       => $settings['posts_per_page'],
            'order'        => $settings['order'],
            'offset'       => $offset,
            'first_letter' => $first_letter
        );

        $total_terms_args = $taxonomy_args;
        unset($total_terms_args['offset']);
        $total_terms = wp_count_terms($brand_taxonomy, $total_terms_args);
        $pages       = ceil($total_terms / $settings['posts_per_page']);

        $brands = get_terms($taxonomy_args);

        $current_link = get_permalink();
        $index        = array_merge( apply_filters('postero_data_author_filter', range('A', 'Z')), array('0-9'));

        ?>
        <div class="authors_a_z">
            <div class="all-authors">
                <ul class="author-index-pagination">
                    <?php

                    if (empty($first_letter) || !in_array($first_letter, $index)) {
                        echo '<li class="active"><a href="' . esc_url(remove_query_arg(array('first_letter', 'paged'), $current_link)) . '">' . esc_html__('ALL', 'postero') . '</a></li>';
                    } else {
                        echo '<li><a href="' . esc_url(remove_query_arg(array('first_letter', 'paged'), $current_link)) . '">' . esc_html__('ALL', 'postero') . '</a></li>';
                    }

                    foreach ($index as $i) {
                        $link = add_query_arg(array(
                            'first_letter' => $i,
                        ), $current_link);
                        if ($first_letter == $i) {
                            echo '<li class="active"><a href="' . esc_url($link) . '">' . esc_html($i) . '</a></li>';
                        } else {
                            echo '<li><a href="' . esc_url($link) . '">' . esc_html($i) . '</a></li>';
                        }
                    }
                    ?>
                </ul>

                <div class="author-content">
                    <?php if (!$brands || !is_array($brands)) : ?>
                        <span class="text-center"><?php echo esc_html__('No authors available.', 'postero'); ?></span>
                    <?php else : ?>
                        <div <?php $this->print_render_attribute_string('wrapper'); ?>>
                            <div <?php $this->print_render_attribute_string('row'); ?>>
                                <?php

                                foreach ($brands as $index => $brand) :
                                    ?>
                                    <div <?php $this->print_render_attribute_string('item'); // WPCS: XSS ok.
                                    ?>>
                                        <div class="artists-block">
                                            <?php

                                                $args = array(
                                                    'post_type'      => 'product',
                                                    'posts_per_page' => 3,
                                                    'tax_query'      => array(
                                                        array(
                                                            'taxonomy' => $brand_taxonomy,
                                                            'field'    => 'term_id',
                                                            'terms'    => array($brand->term_id),
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

                                            <div class="artists-caption">
                                                <a class="thumbnail" href="<?php echo esc_url(get_term_link($brand->slug, $brand_taxonomy)); ?>" title="<?php echo esc_attr($brand->name); ?>">
                                                    <?php echo mas_wcbr_get_brand_thumbnail_image($brand, 'medium'); ?>
                                                </a>

                                                    <a class="name" href="<?php echo esc_url(get_term_link($brand->slug, $brand_taxonomy)); ?>" title="<?php echo esc_attr($brand->name); ?>">
                                                        <?php echo esc_attr($brand->name); ?>
                                                    </a>
                                                    <div class="count"><?php echo esc_html($brand->count . ' ' . _n('poster', 'posters', $brand->count, 'postero')); ?></div>

                                            </div>
                                            <?php
                                            if ($settings['style'] == '1') {
                                                ?>
                                                <div class="artists-button">
                                                    <a class="button" href="<?php echo esc_url(get_term_link($brand->slug, $brand_taxonomy)); ?>" title="<?php echo esc_attr($brand->name); ?>"><?php echo esc_html__('View profile', 'postero'); ?>
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
                    <?php endif; ?>
                </div>

                <?php if ($pages > 1) : ?>
                    <div class="author-pagination pagination">
                        <ul class="page-numbers">
                            <?php
                            for ($pagecount = 1; $pagecount <= $pages; $pagecount++) {
                                $link = add_query_arg(array(
                                    'paged'        => $pagecount,
                                    'first_letter' => $first_letter,
                                ), $current_link);
                                if ($page == $pagecount) {
                                    echo '<li><a class="page-numbers current" href="' . esc_url($link) . '">' . esc_html($pagecount) . '</a></li>';
                                } else {
                                    echo '<li><a class="page-numbers" href="' . esc_url($link) . '">' . esc_html($pagecount) . '</a></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                <?php endif; ?>

            </div>
        </div>
        <?php

    }

    protected function get_product_brands() {
        $brand_taxonomy = Mas_WC_Brands()->get_brand_taxonomy();
        $brands         = get_terms(array(
                'taxonomy'   => $brand_taxonomy,
                'hide_empty' => false,
            )
        );
        $results        = array();
        if (!is_wp_error($brands)) {
            foreach ($brands as $brand) {
                $results[$brand->term_id] = $brand->name;
            }
        }
        return $results;
    }


}

$widgets_manager->register(new Postero_Elementor_All_Author());
