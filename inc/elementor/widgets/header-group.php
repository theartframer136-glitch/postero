<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Postero_Elementor_Header_Group extends Elementor\Widget_Base {

    public function get_name() {
        return 'postero-header-group';
    }

    public function get_title() {
        return esc_html__('Postero Header Group', 'postero');
    }

    public function get_icon() {
        return 'eicon-lock-user';
    }

    public function get_categories() {
        return array('postero-addons');
    }

    protected function register_controls() {

        $this->start_controls_section(
            'header_group_config',
            [
                'label' => esc_html__('Config', 'postero'),
            ]
        );

        $this->add_control(
            'show_search',
            [
                'label' => esc_html__('Show search', 'postero'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'show_account',
            [
                'label' => esc_html__('Show account', 'postero'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'display_content_acc',
            [
                'label'        => esc_html__('Show Content', 'postero'),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'prefix_class' => 'hidden-postero-content-acc-',
                'condition'  => [
                        'show_account' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_wishlist',
            [
                'label' => esc_html__('Show wishlist', 'postero'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'show_cart',
            [
                'label' => esc_html__('Show cart', 'postero'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'hide_dropdown',
            [
                'label' => esc_html__('Hide dropdown', 'postero'),
                'type'  => Controls_Manager::SWITCHER,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'header_group_icon',
            [
                'label' => esc_html__('Icon', 'postero'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label'     => esc_html__('Color', 'postero'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:not(:hover) i:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:not(:hover):before'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div span:not(.login-form-title)'                   => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'postero'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:hover i:before' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:hover:before'   => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:hover span'     => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label'     => esc_html__('Font Size', 'postero'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a i:before' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action > div a:before'   => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'icon_padding',
            [
                'label'      => esc_html__('Padding', 'postero'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .header-group-action > div a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'icon-border',
            [
                'label'        => esc_html__('Icon Border', 'postero'),
                'type'         => Controls_Manager::SWITCHER,
                'prefix_class' => 'icon-border-',
            ]
        );

        $this->add_responsive_control(
            'border_width',
            [
                'label'      => esc_html__('Border Width', 'postero'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .header-group-action > div' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition'  => [
                    'icon-border' => 'yes',
                ],
            ]
        );
        $this->add_control(
            'border_color',
            [
                'label'     => esc_html__('Border Color', 'postero'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .header-group-action > div' => 'border-color: {{VALUE}};',
                    'condition'                              => [
                        'icon-border' => 'yes',
                    ],
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_min-width',
            [
                'label'     => esc_html__('Min Height', 'postero'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.icon-border-yes .header-group-action > div' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'icon-border' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_min-height',
            [
                'label'     => esc_html__('Min Height', 'postero'),
                'type'      => Controls_Manager::SLIDER,
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}.icon-border-yes .header-group-action > div' => 'min-height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'icon-border' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'header_group_conut',
            [
                'label' => esc_html__('Count', 'postero'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'count_color',
            [
                'label'     => esc_html__('Color', 'postero'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action .count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'count_background_color',
            [
                'label'     => esc_html__('Background Color', 'postero'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-header-group-wrapper .header-group-action .count' => 'background-color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'header_group_content',
            [
                'label' => esc_html__('Content', 'postero'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .account-content',
            ]
        );

        $this->add_control(
            'content_color',
            [
                'label'     => esc_html__('Color', 'postero'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .header-group-action a .account-content' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .header-group-action a .account-content:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'content_color_hover',
            [
                'label'     => esc_html__('Color Hover', 'postero'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .header-group-action a:hover .account-content' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .header-group-action a:hover .account-content:before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute('wrapper', 'class', 'elementor-header-group-wrapper');
        if ('yes' == $settings['hide_dropdown']) {

            $this->add_render_attribute('wrapper', 'class', 'header-group-hide-dropdown');
        }
        ?>
        <div <?php $this->print_render_attribute_string('wrapper'); ?>>
            <div class="header-group-action">
                <?php if ($settings['show_search'] === 'yes') {
                    postero_header_search_button();
                }
                if ($settings['show_account'] === 'yes') {
                    if (postero_is_woocommerce_activated()) {
                        $account_link = get_permalink(get_option('woocommerce_myaccount_page_id'));
                    } else {
                        $account_link = wp_login_url();
                    }
                    ?>
                    <div class="site-header-account">
                        <a href="<?php echo esc_url($account_link); ?>">
                            <i class="postero-icon-user"></i>
                            <span class="account-content">
                                <?php
                                if (!is_user_logged_in()) {
                                    esc_attr_e('Login / Register', 'postero');
                                } else {
                                    $user = wp_get_current_user();
                                    echo esc_html($user->display_name);
                                }

                                ?>
                            </span>
                        </a>
                        <div class="account-dropdown">

                        </div>
                    </div>
                    <?php
                }
                if ($settings['show_wishlist'] === 'yes' && postero_is_woocommerce_activated()) {
                    postero_header_wishlist();
                }
                if ($settings['show_cart'] === 'yes' && postero_is_woocommerce_activated()) {
                    postero_header_cart();
                }
                ?>

            </div>
        </div>
        <?php
    }
}

$widgets_manager->register(new Postero_Elementor_Header_Group());
