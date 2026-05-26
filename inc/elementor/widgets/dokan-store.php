<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!postero_is_woocommerce_activated() || !postero_is_dokan_activated()) {
    return;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;

class Postero_Elementor_Dokan_Stores extends Postero_Base_Widgets_Swiper {
    public function get_name() {
        return 'postero-dokan-stores';
    }

    public function get_script_depends() {
        return ['postero-elementor-dokan-store', 'postero-elementor-swiper'];
    }

    public function get_title() {
        return esc_html__('Postero Dokan Stores', 'postero');
    }

    public function get_categories() {
        return ['postero-addons'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'stores_config',
            [
                'label' => esc_html__('Settings', 'postero'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'limit',
            [
                'label'   => esc_html__('Stores Per Page', 'postero'),
                'type'    => Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );

        $this->add_responsive_control(
            'column',
            [
                'label'          => esc_html__('Columns', 'postero'),
                'type'           => \Elementor\Controls_Manager::SELECT,
                'default'        => 3,
                'options'   => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6],
                'selectors' => [
                    '{{WRAPPER}} .d-grid' => 'grid-template-columns: repeat({{VALUE}}, 1fr)',
                ],
                'condition' => ['enable_carousel!' => 'yes'],
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
                'condition' => ['enable_carousel!' => 'yes']
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
            'featured',
            [
                'label' => __('Stores featured', 'postero'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'with_products_only',
            [
                'label' => __('Stores with products only', 'postero'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'store_id',
            [
                'label'       => __('Include', 'postero'),
                'type'        => Controls_Manager::TEXT,
                'description' => esc_html__('Include vendor by id separated by "," for example: (1,2,3)', 'postero')
            ]
        );

        $this->add_control(
            'style',
            [
                'label'        => esc_html__('Style', 'postero'),
                'type'         => \Elementor\Controls_Manager::SELECT,
                'options'      => [
                    'style-1' => esc_html__('Style 1', 'postero'),
                    'style-2' => esc_html__('Style 2', 'postero'),
                    'style-3' => esc_html__('Style 3', 'postero'),
                ],
                'default'      => 'style-1',
                'prefix_class' => 'elementor-store-'
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'        => 'store-wrapper_border',
                'placeholder' => '1px',
                'default'     => '1px',
                'selector'    => '{{WRAPPER}} .store-wrapper',
                'separator'   => 'before',
            ]
        );

        $this->add_control(
            'store-wrapper_radius',
            [
                'label'      => esc_html__('Border Radius', 'postero'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .store-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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

    protected function render() {
        $settings = $this->get_settings_for_display();

        $seller_args = array(
            'number' => $settings['limit'],
            'order'  => 'DESC',
        );

        if ('yes' === $settings['featured']) {
            $seller_args['featured'] = 'yes';
        }

        if (!empty($settings['order'])) {
            $seller_args['order'] = $settings['order'];
        }

        if (!empty($settings['orderby'])) {
            $seller_args['orderby'] = $settings['orderby'];
        }

        if (!empty($settings['with_products_only']) && 'yes' === $settings['with_products_only']) {
            $seller_args['has_published_posts'] = ['product'];
        }

        if (!empty($settings['store_id'])) {
            $seller_args['include'] = $settings['store_id'];
        }

        $sellers = dokan_get_sellers($seller_args);

        $this->add_render_attribute('wrapper', 'class', 'elementor-store-wrapper');
        // Carousel
        $this->get_data_elementor_columns();
        ?>
        <div <?php $this->print_render_attribute_string('wrapper'); ?>>
            <div <?php $this->print_render_attribute_string('row'); ?>>
                <?php if ($sellers['users']) { ?>

                    <?php
                    foreach ($sellers['users'] as $key => $seller) {
                        $vendor       = dokan()->vendor->get($seller->ID);
                        $store_name   = $vendor->get_shop_name();
                        $store_url    = $vendor->get_shop_url();
                        $avatar_id    = $vendor->get_avatar_id();
                        $store_rating = $vendor->get_rating();
                        if (!$avatar_id && !empty($vendor->data->user_email)) {
                            $avata_url = get_avatar_url($vendor->data->user_email, 300);
                        } else {
                            $avata_url = wp_get_attachment_url($avatar_id);
                        }
                        $user_id        = (int)$vendor->data->ID;
                        $products_count = dokan_count_posts('product', $user_id);
                        $banner_id      = $vendor->get_banner_id();
                        $banner_url     = $banner_id ? wp_get_attachment_image_url($banner_id, 'medium') : '';
                        ?>

                        <div class="dokan-single-seller">
                            <?php if ($settings['style'] == 'style-3'): ?>
                                <div class="product-wrapper">
                                    <?php
                                    $args = [
                                        'post_type'      => 'product',
                                        'posts_per_page' => 3,
                                        'orderby'        => 'rand',
                                        'author'         => $seller->ID,
                                    ];

                                    $products = new WP_Query($args);

                                    if ($products->have_posts()) {

                                        while ($products->have_posts()) {
                                            $products->the_post();
                                            global $product;
                                            ?>
                                            <div class="product-item">
                                                <a href="<?php echo esc_url($product->get_permalink()); ?>" title="<?php echo esc_attr($product->get_name()); ?>">
                                                    <?php printf('%s', $product->get_image()); ?>
                                                    <span class="screen-reader-text"><?php echo wp_kses_post($product->get_name()); ?></span>
                                                </a>
                                            </div>
                                            <?php
                                        }

                                    } else {
                                        esc_html_e('No product has been found!', 'postero');
                                    }

                                    wp_reset_postdata();
                                    ?>
                                </div>
                            <?php endif; ?>
                            <div class="store-wrapper">
                                <span class="count"><?php echo str_pad($key + 1, 2, "0", STR_PAD_LEFT) . '.'; ?></span>
                                <?php if ($banner_url) { ?>
                                    <div class="profile-info-img">
                                        <img src="<?php echo esc_url($banner_url); ?>"
                                             alt="<?php echo esc_attr($vendor->get_shop_name()); ?>"
                                             title="<?php echo esc_attr($vendor->get_shop_name()); ?>">
                                    </div>
                                <?php } else { ?>
                                    <div class="profile-info-img dummy-image"></div>
                                <?php } ?>
                            <?php if ($settings['style'] != 'style-3'): ?>
                                <div class="seller-avatar">
                                    <a href="<?php echo esc_url($store_url); ?>">
                                        <img src="<?php echo esc_url($avata_url) ?>" alt="<?php echo esc_attr($vendor->get_shop_name()) ?>">
                                    </a>
                                </div>

                            <?php endif; ?>
                                <div class="store-data">
                                    <h3>
                                        <a href="<?php echo esc_attr($store_url); ?>"><?php echo esc_html($store_name); ?></a>
                                    </h3>
                                    <div class="product-count"><?php echo sprintf(_n('%d Product', '%d Products', $products_count->publish, 'postero'), $products_count->publish); ?></div>
                                    <?php if ($settings['style'] != 'style-3'): ?>
                                        <?php if (!empty($store_rating['count'])): ?>
                                            <div class="dokan-seller-rating" title="<?php echo sprintf(esc_attr__('Rated %s out of 5', 'postero'), esc_attr($store_rating['rating'])) ?>">
                                                <?php echo wp_kses_post(postero_dokan_generate_ratings($store_rating['rating'], 5)); ?>
                                            </div>
                                        <?php endif ?>
                                    <?php endif ?>
                                </div>
                            </div>
                        </div>

                    <?php }

                } else { ?>
                    <div class="dokan-error"><?php esc_html_e('No vendor found!', 'postero'); ?></div>
                <?php } ?>
            </div>
        </div>
        <?php
    }
}

$widgets_manager->register(new Postero_Elementor_Dokan_Stores());
