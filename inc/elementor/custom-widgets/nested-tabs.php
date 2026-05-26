<?php
// nested-tabs
use Elementor\Controls_Manager;

add_action('elementor/element/nested-tabs/section_tabs/before_section_end', function ($element, $args) {
    /** @var \Elementor\Element_Base $element */
    $element->add_control(
        'tabs_style',
        [
            'label'        => esc_html__('Style', 'postero'),
            'type'         => Controls_Manager::SELECT,
            'default'      => '',
            'options'      => [
                ''   => esc_html__('default', 'postero'),
                '1' => esc_html__('Style 1', 'postero'),
            ],
            'prefix_class' => 'elementor-tabs-style',
        ]
    );
}, 10, 2);