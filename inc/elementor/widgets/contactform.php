<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
if (!postero_is_contactform_activated()) {
    return;
}

use Elementor\Controls_Manager;

class Postero_Elementor_ContactForm extends Elementor\Widget_Base {

    public function get_name() {
        return 'postero-contactform';
    }

    public function get_title() {
        return esc_html__('Postero Contact Form', 'postero');
    }

    public function get_categories() {
        return array('postero-addons');
    }

    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    protected function register_controls() {
        $this->start_controls_section(
            'contactform7',
            [
                'label' => esc_html__('General', 'postero'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );
        $cf7               = get_posts('post_type="wpcf7_contact_form"&numberposts=-1');
        $contact_forms[''] = esc_html__('Please select form', 'postero');
        if ($cf7) {
            foreach ($cf7 as $cform) {
                $contact_forms[$cform->ID] = $cform->post_title;
            }
        } else {
            $contact_forms[0] = esc_html__('No contact forms found', 'postero');
        }

        $this->add_control(
            'cf_id',
            [
                'label'   => esc_html__('Select contact form', 'postero'),
                'type'    => Controls_Manager::SELECT,
                'options' => $contact_forms,
                'default' => ''
            ]
        );

        $this->add_control(
            'form_name',
            [
                'label'   => esc_html__('Form name', 'postero'),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__('Contact form', 'postero'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        if (!$settings['cf_id']) {
            return;
        }
        $args['id']    = $settings['cf_id'];
        $args['title'] = $settings['form_name'];

        echo postero_do_shortcode('contact-form-7', $args);
    }
}

$widgets_manager->register(new Postero_Elementor_ContactForm());
