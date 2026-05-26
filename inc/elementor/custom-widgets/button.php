<?php
// Button
use Elementor\Controls_Manager;

add_action('elementor/element/button/section_button/after_section_end', function ($element, $args) {
    /** @var \Elementor\Element_Base $element */
    $element->update_control(
        'button_type',
        [
            'label'        => esc_html__('Type', 'postero'),
            'type'         => Controls_Manager::SELECT,
            'default'      => 'default',
            'options'      => [
                'default'   => esc_html__('Default', 'postero'),
                'outline' => esc_html__('OutLine', 'postero'),
                'info'    => esc_html__('Info', 'postero'),
                'success' => esc_html__('Success', 'postero'),
                'warning' => esc_html__('Warning', 'postero'),
                'danger'  => esc_html__('Danger', 'postero'),
                'type-link'  => esc_html__('Link', 'postero'),
            ],
            'prefix_class' => 'elementor-button-',
        ]
    );
}, 10, 2);

add_action('elementor/element/button/section_button/before_section_end', function ($element, $args) {
    /** @var \Elementor\Element_Base $element */
    $element->add_control(
        'style_icon',
        [
            'label'        => esc_html__('Show Style Icon', 'postero'),
            'type'         => Controls_Manager::SWITCHER,
            'prefix_class' => 'show-style-icon-',
            'condition' => [
                'selected_icon[value]!' => '',
                'button_type' => 'type-link',
            ],
        ]
    );
    $element->add_control(
        'color_icon',
        [
            'label'        => esc_html__('Color Icon', 'postero'),
            'type' => Controls_Manager::COLOR,
            'condition' => [
                'style_icon' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-button-type-link.show-style-icon-yes .elementor-button-icon i' => 'color: {{VALUE}};  border-color:{{VALUE}};',
            ],
        ]
    );

    $element->add_control(
        'color_icon_hover',
        [
            'label'        => esc_html__('Hover Color Icon', 'postero'),
            'type' => Controls_Manager::COLOR,
            'condition' => [
                'style_icon' => 'yes',
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-button-type-link.show-style-icon-yes .elementor-button:hover .elementor-button-icon i' => 'color: {{VALUE}};  border-color:{{VALUE}};',
            ],
        ]
    );
    $element->add_control(
        'show_line',
        [
            'label'        => esc_html__('Show Line', 'postero'),
            'type'         => Controls_Manager::SWITCHER,
            'prefix_class' => 'show-line-',
            'condition' => [
                'button_type' => 'type-link',
            ],
        ]
    );
}, 10, 2);

add_action('elementor/element/button/section_style/after_section_end', function ($element, $args) {
    /** @var \Elementor\Element_Base $element */
    $element->update_control(
        'typography_typography',
        [
            'global'    => [
                'default' => '',
            ]
        ]
    );
    $element->update_control(
        'background_color',
        [
            'global'    => [
                'default' => '',
            ],
            'selectors' => [
                '{{WRAPPER}}.elementor-widget-button .elementor-button' => 'background-color: {{VALUE}};  background-image: -webkit-linear-gradient(90deg, transparent 0%, {{VALUE}} 0%);background-image: linear-gradient(90deg, transparent 0%, {{VALUE}} 0%);',
                '{{WRAPPER}}.elementor-widget-button.elementor-button-outline .elementor-button' => 'border-color: {{VALUE}}; background-color: transparent;',
            ],

        ]
    );

    $element->update_control(
            'button_background_hover_color',
            [
                'global'    => [
                    'default' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}}.elementor-widget-button .elementor-button:hover' => 'background-color: {{VALUE}};  background-image: -webkit-linear-gradient(90deg, transparent 0%, {{VALUE}} 0%);background-image: linear-gradient(90deg, transparent 0%, {{VALUE}} 0%);',
                    '{{WRAPPER}}.elementor-widget-button.elementor-button-outline .elementor-button:hover' => 'border-color: {{VALUE}}; background-color: transparent;',
                ],

            ]
        );

    $element->update_control(
        'button_text_color',
        [
            'global'    => [
                'default' => '',
            ],
            'selectors' => [
                '{{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
                '{{WRAPPER}}.elementor-button-link .elementor-button .elementor-button-text:after,{{WRAPPER}}.elementor-button-link .elementor-button .elementor-button-text:before' => 'background-color: {{VALUE}};',
                '{{WRAPPER}}.elementor-button-type-link.show-line-yes .elementor-button .elementor-button-text:before' => 'background-color: {{VALUE}};',
            ],
        ]
    );

    $element->update_control(
        'hover_color',
        [
            'global'    => [
                'default' => '',
            ],
            'selectors' => [
                '{{WRAPPER}} .elementor-button:hover, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};',
                '{{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} .elementor-button:focus svg' => 'fill: {{VALUE}};',
                '{{WRAPPER}}.elementor-button-link .elementor-button:hover .elementor-button-text:after, {{WRAPPER}}.elementor-button-link .elementor-button:hover .elementor-button-text:before' => 'background-color: {{VALUE}};',
                '{{WRAPPER}}.elementor-button-type-link.show-line-yes .elementor-button:hover .elementor-button-text:before' => 'background-color: {{VALUE}};',
            ],
        ]
    );


}, 10, 2);




