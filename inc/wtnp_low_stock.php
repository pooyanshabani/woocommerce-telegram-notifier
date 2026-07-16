<?php
defined('ABSPATH') || exit;

add_action( 'woocommerce_low_stock', 'wtnp_custom_low_stock_action');
function wtnp_custom_low_stock_action( $product ) {

    $current_time = date_i18n('l j F Y - H:i');
	$product_link = $product->get_permalink();
	$product_ID = $product->get_id();
	$product_name = $product->get_name();
	$stock_quantity = $product->get_stock_quantity();

	$wtnp_message = "⚠️ " . __('Product inventory is low', 'wtnp-tn-woocommerce') . "\n";
	$wtnp_message .= "\n🕒 " . __('Time', 'wtnp-tn-woocommerce') . ": $current_time\n";
	$wtnp_message .= "📦 " . __('Product Title', 'wtnp-tn-woocommerce') . ": $product_name\n";

	if ($stock_quantity) {$wtnp_message .= "🥡 " . __('Low Stock:','wtnp-tn-woocommerce') . $stock_quantity . "\n";}
	//$wtnp_message .= "\n" . "$product_link" . "\n";
	$wtnp_message .= "\n" . __('#Low_Stock','wtnp-tn-woocommerce');
	 

    global $wtnp_settings_telegramcb;
	global $wtnp_settings_teltoken;


    
    if ($wtnp_settings_telegramcb == 'yes' && $wtnp_settings_teltoken) {
		notificator_send_message_wtnp_telegram($wtnp_message, $product_link);
	}

}
