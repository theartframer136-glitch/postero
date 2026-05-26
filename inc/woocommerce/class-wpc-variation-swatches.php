<?php
/**
 * Postero WooCommerce Settings Class
 *
 * @package  postero
 * @since    2.4.3
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Postero_WooCommerce_Wpc_Variation_Swatches') && class_exists( 'WPCleverWpcvs' )) :

    /**
     * The Postero_WooCommerce_Wpc_Variation_Swatches Class
     */
    class Postero_WooCommerce_Wpc_Variation_Swatches {

        public function __construct() {
            add_filter('wpcvs_term_html',[$this,'wpcvs_term_html'],10,3);
        }

        public function wpcvs_term_html($html, $term, $arg){
            $attr_id = wc_attribute_taxonomy_id_by_name( $term->taxonomy );
            $attr    = wc_get_attribute( $attr_id );
            if($attr->type == 'button' && !empty($term->description)) {
                    $regex = '/(<\/span>)(?!.*<\/span>)/';
                    $html = preg_replace($regex, '<span class="desc">'.$term->description. '</span>$1', $html);
            }

            return $html;
        }
    }

    return new Postero_WooCommerce_Wpc_Variation_Swatches();

endif;
