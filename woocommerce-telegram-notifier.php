<?php
/*
 * Plugin Name: Woocommerce Telegram Notifier
 * Plugin URI: https://github.com/pooyanshabani
 * Description: Receive notifications on Telegram from WooCommerce store (order registration, order update, new comment registration, product shortage or out of stock)
 * Author: Pooyan Shabani
 * Author URI: https://github.com/pooyanshabani
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html
 * Text Domain: wtnp-tn-woocommerce
 * Domain Path: /languages
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.2
 */


//remove if direct
defined('ABSPATH') || exit;
//set version
define('WTNP_VER', '1.0.0');
//set assets images,css & js  folders
define('WTNP_TNOTIF_ASSEST_URL', plugin_dir_url(__FILE__) . 'assets/');
define('WTNP_TNOTIF_IMAGES_URL', WTNP_TNOTIF_ASSEST_URL . 'img/');
define('WTNP_TNOTIF_CSS_URL', WTNP_TNOTIF_ASSEST_URL . 'css/');
define('WTNP_TNOTIF_JS_URL', WTNP_TNOTIF_ASSEST_URL . 'js/');

//set assets libs & view folders
define('WTNP_INC', plugin_dir_path(__FILE__) . 'inc/');

//JS CSS VER
define('WTNP_JSCCS_VER', '1.0.0');
define('WTNP_JSCCS_ASSEST_VER', defined('WP_DEBUG') && WP_DEBUG ? time() : WTNP_JSCCS_VER );

//include notificator & menu page(add admin menu)
include(WTNP_INC . 'wtnp_notificator.php');


//check woo active? , php & wp version when plugin active
register_activation_hook(__FILE__, function () {

    $php = '7';
    $wp = '6.0';

    global $wp_version;

    if (version_compare($wp_version, $wp, '<')) {

        wp_die(
            sprintf( 'You must have atleast wordpress version %s your curent version is %s', $wp, $wp_version)
        );
    }

    if (version_compare(PHP_VERSION, $php, '<')) {

        wp_die(
            sprintf( 'You must have atleast php version %s', $php)
        );

    }
	if (!is_plugin_active('woocommerce/woocommerce.php')){
		wp_die(
			'WooCommerce plugin is not installed/activated! To use the this plugin, first install and activate WooCommerce'
        );
	}

	notificator_send_message_wtnp_plugin_active ( 'Plugin WooTelNotificator Activated at ' . home_url() );
	
	

});

//when plugin deactive
register_deactivation_hook(__FILE__, function () {

    notificator_send_message_wtnp_plugin_active ('Plugin WooTelNotificator Deactivated at ' . home_url() );
    notificator_send_message_wtnp_telegram ('Plugin WooTelNotificator Deactivated at ' . home_url() );

});

//add css & js files in admin
add_action('admin_enqueue_scripts', function () {
	global $pagenow;
	if ($pagenow === 'admin.php' && isset($_GET['page']) && $_GET['page'] === 'wc-settings' && isset($_GET['tab']) && $_GET['tab'] === 'woo-wtnp-tab'){
		wp_enqueue_script(
			'wtnp-admin-script',
			WTNP_TNOTIF_JS_URL . 'wtnp_script_admin.js',
			['jquery'],
			WTNP_JSCCS_ASSEST_VER,
		);
	}
	if ($pagenow === 'admin.php' && isset($_GET['page']) && $_GET['page'] === 'wc-settings'){
		wp_enqueue_style(
			'wtnp-admin-style',
			WTNP_TNOTIF_CSS_URL . 'wtnp_style_admin.css',
			[],
			WTNP_JSCCS_ASSEST_VER
		);
		$telegrambeforebg = WTNP_TNOTIF_IMAGES_URL . 'wtnp-telegram.svg';
		$balebeforebg = WTNP_TNOTIF_IMAGES_URL . 'wtnp-bale.svg';
		wp_add_inline_style(
			'wtnp-admin-style',
			".wtnp-telicon-settings::before { background-image: url($telegrambeforebg)}
			.wtnp-baleicon-settings::before { background-image: url($balebeforebg)}
			"
        );
	}
});


$wtnp_settings_telegramcb = '';
$wtnp_settings_teltoken ='';

$wtnp_settings_neworder ='';
$wtnp_settings_orderstatus ='';
$wtnp_settings_outofstock ='';
$wtnp_settings_lowstock ='';
$wtnp_settings_comment ='';

global $wpdb;
if ( get_option('wtnp_settings_telegramcb') ) {$wtnp_settings_telegramcb = get_option('wtnp_settings_telegramcb');}
if ( get_option('wtnp_settings_teltoken') ) {$wtnp_settings_teltoken = get_option('wtnp_settings_teltoken');}
if ( get_option('wtnp_settings_neworder') ) {$wtnp_settings_neworder = get_option('wtnp_settings_neworder');}
if ( get_option('wtnp_settings_orderstatus') ) {$wtnp_settings_orderstatus = get_option('wtnp_settings_orderstatus');}
if ( get_option('wtnp_settings_outofstock') ) {$wtnp_settings_outofstock = get_option('wtnp_settings_outofstock');}
if ( get_option('wtnp_settings_lowstock') ) {$wtnp_settings_lowstock = get_option('wtnp_settings_lowstock');}
if ( get_option('wtnp_settings_comment') ) {$wtnp_settings_comment = get_option('wtnp_settings_comment');}


//Add Settings
include(WTNP_INC . 'wtnp_settings.php');

//New Order
if ($wtnp_settings_neworder == 'yes') {
	include(WTNP_INC . 'wtnp_neworder.php');
}

//Change Order Status
if ($wtnp_settings_orderstatus == 'yes') {
	include(WTNP_INC . 'wtnp_status_order.php');
}

//Manage Comment
if ($wtnp_settings_comment == 'yes') {
	include(WTNP_INC . 'wtnp_manage_comment.php');
}

//Low Stock
if ($wtnp_settings_lowstock == 'yes') {
	include(WTNP_INC . 'wtnp_low_stock.php');
}

//Out of Stock
if ($wtnp_settings_outofstock == 'yes') {
	include(WTNP_INC . 'wtnp_outof_stock.php');
}



function wtnp_plugins_page_settings_link($links) { 
  $settings_link = '<a href="' . admin_url('admin.php') . '?page=wc-settings&tab=woo-wtnp-tab">' . __('Setting','wtnp-tn-woocommerce') . '</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
$wtnp_plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$wtnp_plugin", 'wtnp_plugins_page_settings_link' );
