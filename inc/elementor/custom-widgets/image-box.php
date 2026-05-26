<?php
// Image Box
use Elementor\Controls_Manager;
add_action( 'elementor/element/image-box/section_image/before_section_end', function ( $element, $args ) {
    $element->add_control(
        'image-box_style_theme',
        [
            'label' => esc_html__('Theme Style', 'postero'),
            'type' => Controls_Manager::SWITCHER,
            'default' => '',
            'prefix_class' => 'image-box-style-postero-',
        ]
    );

}, 10, 2 );
