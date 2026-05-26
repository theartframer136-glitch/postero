<?php
if (!defined( 'ABSPATH' )){
    exit;
}

/**
 * Class Postero_Customize_Control_Color
 */
class Postero_Customize_Control_Color extends WP_Customize_Control {
    public $type = 'postero-color';
    public $palette;
    public $show_opacity;

    public function enqueue() {
        wp_enqueue_style(
            'alpha-color-picker',
            get_template_directory_uri() . '/assets/css/admin/alpha-color-picker.css',
            array( 'wp-color-picker' ),
            POSTERO_VERSION
        );

        wp_enqueue_script(
            'postero-admin-color',
            get_template_directory_uri() . '/assets/js/admin/alpha-color-picker.js',
            array('jquery', 'wp-color-picker'),
            POSTERO_VERSION,
            true
        );
    }

    /**
     * Render the control.
     */
    public function render_content() {
        // Process the palette
        if (is_array( $this->palette )){
            $palette = implode( '|', $this->palette );
        } else{
            // Default to true.
            $palette = ( false === $this->palette || 'false' === $this->palette ) ? 'false' : 'true';
        }
        // Support passing show_opacity as string or boolean. Default to true.
        $show_opacity = ( false === $this->show_opacity || 'false' === $this->show_opacity ) ? 'false' : 'true';

        // Begin the output. ?>
        <label>
            <?php // Output the label and description if they were passed in.
            if (isset( $this->label ) && '' !== $this->label){
                echo '<span class="customize-control-title">' . sanitize_text_field( $this->label ) . '</span>';
            }
            if (isset( $this->description ) && '' !== $this->description){
                echo '<span class="description customize-control-description">' . sanitize_text_field( $this->description ) . '</span>';
            } ?>
            <input class="alpha-color-control" type="text" data-show-opacity="<?php echo $show_opacity; ?>"
                   data-palette="<?php echo esc_attr( $palette ); ?>"
                   data-default-color="<?php echo esc_attr( $this->settings['default']->default ); ?>" <?php $this->link(); ?> />
        </label>
        <?php
    }
}