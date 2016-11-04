<?php 
/*
* Plugin Name: WooEnhancer Pro 
* Plugin URI: http://alborotado.com
* Description: Activate all WooEnhancer Functions.
* Version: 1.01
* Author: Miguras
* Author URI: http://alborotado.com
*/

if ( ! defined( 'ABSPATH' ) ) {
exit; // Exit if accessed directly
}

/*================ Required Files =====================*/
require_once('inc/plugins/plugin-activation.php');

/*=============== Enqueue Scripts and Styles =============================*/

	
	function wooenhancer_pro_front_styles(){
		global $woocommerce;
		global $migwoo_enhancer;
		$quickview = $migwoo_enhancer['shop-activate-quickview'];
		$suffix      = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
		wp_enqueue_script('jquery');
		wp_enqueue_script( 'wooenhancer-pro-front', plugins_url('/assets/js/wooenhancer-pro-front.js', __FILE__), '', rand(0, 50));
		wp_enqueue_style( 'wooenhancer-pro-front', plugins_url('/assets/css/wooenhancer-pro-front.css', __FILE__), '', rand(0, 50));
		
		if(is_shop() && $quickview == 'yes'){
			wp_enqueue_script( 'prettyPhoto', $woocommerce->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto' . $suffix . '.js', array( 'jquery' ), '3.1.5', true );
    		wp_enqueue_script( 'prettyPhoto-init', $woocommerce->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto.init' . $suffix . '.js', array( 'jquery' ), $woocommerce->version, true );
    		wp_enqueue_style( 'woocommerce_prettyPhoto_css', $woocommerce->plugin_url() . '/assets/css/prettyPhoto.css' );
		}
	}
	

add_action( 'wp_enqueue_scripts', 'wooenhancer_pro_front_styles' );


/*=================== product main video ===============================*/
function wooenhancer_product_main_video(){
	global $post;
	global $wooenhancermeta;
	
	echo wp_oembed_get(get_post_meta($post->ID, 'wooenmeta_product_video_link', true));

}


/*======================== catalog quickview ==============================*/

add_filter( 'body_class','wooenhancer_quickview' );
function wooenhancer_quickview( $classes ) {
 	global $migwoo_enhancer;
	$quickview = $migwoo_enhancer['shop-activate-quickview'];
	
	if(is_shop() && !empty($quickview) && $quickview != 'no'){
    	$classes[] = 'wooenhancer-quickview';
    } 
	
    return $classes;
     
}




// DISPLAY WOOCOMMERCE TOTAL SALES

function wooenhancer_product_total_sales(){
	global $post;
	$total = get_post_meta($post->ID,'total_sales', true);
	$output = '';
	
	$output .= '<div class="wooenhancer_product_total_sales">';
		$output .= __($total.' Sales', 'woocommerce');
	$output .= '</div>';
	
	echo $output;
}


// REGISTER FULL SINGLE PRODUCT AREA 
add_action('woocommerce_before_single_product', 'wooenhancer_full_width');
function wooenhancer_full_width(){
	do_action('wooenhancer_full_width');
}


/*=================== ENQUIRE =======================*/

function wooenhancer_enquire(){
	global $post;
	global $migwoo_enhancer;
	
	$output = '';
	
	$output .= '<div class="wooenhancer_enquire_open_form">'.$migwoo_enhancer['enquire-button-text'].'</div>';
	
	$output .= '<div class="wooenhancer_enquire_background">';
		$output .= '<div class="wooenhancer_enquire_wrapper clearfix">';
			$output .= '<p class="migwooenhancer_enquire_custom_message">'.$migwoo_enhancer['enquire-custom-message'].'</p>';
			$output .= '<form action="" method="POST">';
				$output .= '<span>Name</span><input type="text" name="name">';
				$output .= '<span>Email</span><input type="text" name="email">';
				$output .= '<span>Message</span><textarea name="message" rows="4" cols="30">';
				$output .= '</textarea>';
				$output .= '<input type="submit" value="Send">';
			$output .= '</form>';
		$output .= '</div>';
	$output .= '</div>';
	
	if(!empty($_POST['name'])){
		$name = $_POST['name'];
		
	}
	if(!empty($_POST['message'])){
		$email = $_POST['email'];
		
		
	}
	if(!empty($_POST['message'])){
		$message = $_POST['message'];
		$formcontent = "$message";
	}
	
	$recipient = $migwoo_enhancer['enquire-email'];
	$subject = "Question About:";
	
	
	if(!empty($name) && !empty($email) && !empty($message)){
		$mailheader = 'From: $name \r\n Reply-To: $email \r\n';
		mail($recipient, $subject, $formcontent, $mailheader) or die("Error!");
		echo '<div class="woocommerce-message">'.$migwoo_enhancer['enquire-success-message'].'</div>';
		$_POST = array();
	}
	
	echo $output;
}








































?>