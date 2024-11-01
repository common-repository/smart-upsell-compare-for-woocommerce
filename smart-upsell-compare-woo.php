<?php
/**
 * Plugin Name: Smart Upsell Compare for Woocommerce
 * Plugin URI: https://crea8ivedots.com/
 * Description: Smart Upsell services compare archive product to different categories.
 * Author: Irfan
 * Author URI: http://iffikhan30.crea8ivedots.com/
 * Text Domain: Crea8ivedots
 * Domain Path: https://crea8ivedots.com/
 * Version: 1.0.0
 * WC requires at least: 3.0.0
 * WC tested up to: 5.5.2
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WSUC_PLUGIN_PATH', plugin_dir_url( __FILE__ ) );

//Create Woocommerce Settings tabs
require_once( plugin_basename( 'Classes/class-wc-setting-tab-smart-upsell-compare.php' ) );
WC_Settings_Tab_Smart_Upsell_Compare::init();

if(get_option('wc_settings_tab_smart_upsell_compare_wcsuc_enabled') == 'yes'){
	add_action('woocommerce_before_shop_loop_item', 'woocommerce_before_shop_loop_item_after',10);
	function woocommerce_before_shop_loop_item_after(){
		global $product;
		$wcsuc_product_id		=	get_option( 'wc_settings_tab_smart_upsell_compare_wcsuc_product' );
		$wcsuc_product_cp_id	=	get_option( 'wc_settings_tab_smart_upsell_compare_wcsuc_product_cp' );
		if($product->id == $wcsuc_product_id){
			echo '<span onClick="upselldivopen('.$wcsuc_product_cp_id.');" class="button wsuc_addtocart" >Up Sell</span>';	
		}
	}

	add_action('wp_footer', 'wcsuc_action_example'); 
	function wcsuc_action_example() { 
		?>
		<div id="popup_box" class="popup_box_in">
			<input type="button" id="cancel_button" class="cancel-btn" value="X">
			<div class="popup_box_inner"></div>
		</div>
		<?php
	}

	function add_upselldiv_my_script() {
		wp_enqueue_style( 'upselldiv_style', WSUC_PLUGIN_PATH . 'assets/css/upselldiv_style.css');
		wp_register_script( 'wcsuc-template-script', WSUC_PLUGIN_PATH . 'assets/js/upselldiv_script.js', array( 'jquery' ));
		wp_enqueue_script( 'wcsuc-template-script','','','',true );
		$translation_array = array( 'ajaxurl' => admin_url('admin-ajax.php') );
        //after wp_enqueue_script
        wp_localize_script( 'wcsuc-template-script', 'wsuc_ajax_obj', $translation_array );

	}
	add_action( 'wp_enqueue_scripts', 'add_upselldiv_my_script' );


	add_action( 'wp_ajax_wcsuc_ajax', 'wcsuc_ajax_function' );
	add_action( 'wp_ajax_nopriv_wcsuc_ajax', 'wcsuc_ajax_function' );
	function wcsuc_ajax_function() {
		global $product;
		if ( isset($_REQUEST) ) {
			$cp_product_id = $_REQUEST['productid'];
			$index_query = new WP_Query(array( 'post_type' => 'product', 'p' => $cp_product_id));
			while ($index_query->have_posts()) : $index_query->the_post();
				echo '<ul class="products columns-1">';
				echo  wc_get_template_part( 'content', 'product' );
				echo '</ul>';
			endwhile; wp_reset_postdata();
		}
	die();
	}

}