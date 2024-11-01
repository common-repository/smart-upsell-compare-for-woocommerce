<?php 
if ( ! class_exists( 'WC_Settings_Tab_Smart_Upsell_Compare' ) ) {
    class WC_Settings_Tab_Smart_Upsell_Compare{

        /**
         * Bootstraps the class and hooks required actions & filters.
         *
         */
        public static function init(){
            
            add_filter( 'woocommerce_settings_tabs_array', __CLASS__.'::add_settings_tab', 50);
            add_action( 'woocommerce_settings_tabs_settings_tab_smart_upsell_compare',  __CLASS__ .'::settings_tab' );
            add_action( 'woocommerce_update_options_settings_tab_smart_upsell_compare', __CLASS__ . '::update_settings' );
            
        }

        /**
         * Add a new settings tab to the WooCommerce settings tabs array.
         *
         * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
         * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
         */
        public static function add_settings_tab($settings_tabs){
            $settings_tabs['settings_tab_smart_upsell_compare'] = __( 'Woo Smart Upsell', 'woocommerce-settings-smart-upsell-compare');
            return $settings_tabs;
        }

        /**
         * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
         *
         * @uses woocommerce_admin_fields()
         * @uses self::get_settings()
         */
        public static function settings_tab() {
            woocommerce_admin_fields( self::get_settings() );
        }

        /**
         * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
         *
         * @uses woocommerce_update_options()
         * @uses self::get_settings()
         */
        public static function update_settings() {
            woocommerce_update_options( self::get_settings() );
        }

        public static function get_woo_option(){
            $no_exists_value = get_option( 'no_exists_value' );
            var_dump( $no_exists_value ); /* outputs false */
        }

        /**
         * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
         *
         * @return array Array of settings for @see woocommerce_admin_fields() function.
         */
        public static function get_settings() {
            $terms = get_terms( array(
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
            ));
            $termi = array();
            foreach ($terms as $termkey => $term) {
                $loop = new WP_Query( array('post_type' => 'product',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field'    => 'id',
                        'terms'    => $term->term_id,
                    ),
                ),'showposts'=>'-1'));
                if ( $loop->have_posts() ) : while ( $loop->have_posts() ) : $loop->the_post();
                    $termi[get_the_ID()]  =  get_the_title().'- {catid}'.$term->term_id;
                endwhile; endif; wp_reset_query(); 
            }
            //var_dump($termi);
            $settings = array(
                'wcsuc_title' => array(
                    'name'     => __( 'Smart Upsell Compare', 'woocommerce-settings-tab-smart-upsell-compare' ),
                    'type'     => 'title',
                    'desc'     => '',
                    'id'       => 'wc_settings_tab_smart_upsell_compare_wcsuc_title'
                ),
                'wcsuc_enabled' => array(
                    'title'         => __( 'Enabled', 'woocommerce' ),
                    'desc'          => __( 'Enable Woocommerce Smart Upsell Compare', 'woocommerce' ),
                    'id'            => 'wc_settings_tab_smart_upsell_compare_wcsuc_enabled',
                    'default'       => 'no',
                    'type'          => 'checkbox',
                    'checkboxgroup' => 'start',
                    'autoload'      => false,
                    'class'         => 'manage_stock_field',
                ),
                'wcsuc_product' => array(
                    'name' => __( 'Select Product', 'woocommerce-settings-tab-smart-upsell-compare' ),
                    'type' => 'select',
                    'options' => $termi,
                    'desc' => __( '', 'woocommerce-settings-tab-smart-upsell-compare' ),
                    'id'   => 'wc_settings_tab_smart_upsell_compare_wcsuc_product',
                    'class'=>'wc-enhanced-select'
                ),
                'wcsuc_product_cp' => array(
                    'name' => __( 'Select Compare Product', 'woocommerce-settings-tab-smart-upsell-compare' ),
                    'type' => 'select',
                    'options' => $termi,
                    'desc' => __( '', 'woocommerce-settings-tab-smart-upsell-compare' ),
                    'id'   => 'wc_settings_tab_smart_upsell_compare_wcsuc_product_cp',
                    'class'=>'wc-enhanced-select'
                ),
                'section_end' => array(
                    'type' => 'sectionend',
                    'id' => 'wc_settings_tab_smart_upsell_compare_section_end'
                )
            );
            return apply_filters( 'wc_settings_tab_smart_upsell_compare_settings', $settings );
        }

    }
    
}

?>