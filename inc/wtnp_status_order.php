<?php
defined('ABSPATH') || exit;

add_action('woocommerce_order_status_changed', 'wtnp_so_status_completed', 10, 3);

function wtnp_so_status_completed($order_id, $old_status, $new_status)
{
	if ( ! is_admin() ) {
		return;
	}

    $order = wc_get_order($order_id);

    $order_total = $order->get_total();
	$status = $order->get_status();
	$order_date = $order->get_date_created()->date_i18n('l j F Y - H:i');
	$date_modified = $order->get_date_modified()->date_i18n('l j F Y - H:i');


	if ($order->get_user_id()) {
        $userlogin = __('Customer','wtnp-tn-woocommerce');
    } else {
        $userlogin = __('Guest','wtnp-tn-woocommerce');
    }
	

	$billing_first_name = $order->get_billing_first_name();
	$billing_last_name = $order->get_billing_last_name();
	$billing_company = $order->get_billing_company();
	$billing_address1 = $order->get_billing_address_1();
	$billing_address2 = $order->get_billing_address_2();
	$billing_email = $order->get_billing_email();
	$billing_postcode = $order->get_billing_postcode();
	$billing_city = $order->get_billing_city();
	$billing_phone = $order->get_billing_phone();
	$shipping_first_name = $order->get_shipping_first_name();
    $shipping_last_name = $order->get_shipping_last_name();
    $shipping_company = $order->get_shipping_company();
    $shipping_address1 = $order->get_shipping_address_1();
    $shipping_address2 = $order->get_shipping_address_2();  
    $shipping_postcode = $order->get_shipping_postcode();
    $shipping_city = $order->get_shipping_city();
	$payment_method = $order->get_payment_method_title();
	$shipping_method = $order->get_shipping_method();
	$shipping_cost = $order->get_shipping_total();
	$order_note = $order->get_customer_note();

	$coupon_codes = $order->get_coupon_codes();
    if($coupon_codes){$coupon_name = $coupon_codes[0];} else {$coupon_name = '';}
   if($coupon_codes){$discount_total = $order->get_discount_total();}


	if ($status == 'canceled' || $status == 'cancelled'){$status = 'لغو شده';$status_icon = '❌ ';}
	elseif ($status == 'processing'){$status = __('Processing', 'wtnp-tn-woocommerce') ;$status_icon = '🔄 ';}
	elseif ($status == 'pending'){$status = __('Pending', 'wtnp-tn-woocommerce');$status_icon = '🟠 ';}
	elseif ($status == 'failed'){$status = __('Failed', 'wtnp-tn-woocommerce');$status_icon = '❌ ';}
	elseif ($status == 'refunded'){$status = __('Refunded', 'wtnp-tn-woocommerce');$status_icon = '↩️ ';}
	elseif ($status == 'on-hold'){$status = __('On-hold', 'wtnp-tn-woocommerce');$status_icon = '🟠 ';}
	elseif ($status == 'completed'){$status = __('Completed', 'wtnp-tn-woocommerce');$status_icon = '✅ ';}
	elseif ($status == 'draft'){$status = __('Draft', 'wtnp-tn-woocommerce');$status_icon = '🟣 ';}

	
	$wtnp_message  = "⚠️ " . __('Update Order', 'wtnp-tn-woocommerce') . "$order_id\n";
	
	$wtnp_message .= "\n🕒 " . __('Update Time', 'wtnp-tn-woocommerce') . ": $date_modified\n";
	$wtnp_message .= "\n$status_icon " . __('Order Status', 'wtnp-tn-woocommerce') . ": $status\n";
	
	$wtnp_message .= "\n🕒 " . __('Order Time', 'wtnp-tn-woocommerce') . ": $order_date\n";
	$wtnp_message .= "\n🔖 " . __('Order Detailes', 'wtnp-tn-woocommerce') . ":\n";
	
	
	$wtnp_message .= "#️⃣ " . __('Order ID', 'wtnp-tn-woocommerce') . ": $order_id\n";
	
	$wtnp_message .= "👤" . __('Name', 'wtnp-tn-woocommerce') . ": $billing_first_name $billing_last_name\n";
	$wtnp_message .= "🧑🏻‍💻 " . __('User', 'wtnp-tn-woocommerce') . ": $userlogin\n";
	if ($billing_company) {$wtnp_message .= "🏢 " . __('Company', 'wtnp-tn-woocommerce') . ": $billing_company\n";}
    if ($billing_city || $billing_address1 || $billing_address2) { $wtnp_message .= "📍 " . __('Address', 'wtnp-tn-woocommerce') .  ": $billing_city - $billing_address1 - $billing_address2 - $billing_postcode" . "\n";}
   
    $wtnp_message .= "📞 " . __('Phone', 'wtnp-tn-woocommerce') . ": $billing_phone\n";
    $wtnp_message .= "✉️ " . __('Mail', 'wtnp-tn-woocommerce') . ": $billing_email\n";
	if ($shipping_first_name || $shipping_last_name || $shipping_city || $shipping_address1 || $shipping_postcode) { 
		$wtnp_message .= "\n📦 " . __('Shipping details', 'wtnp-tn-woocommerce') . ":\n";
		$wtnp_message .= "👤 " . __('Name', 'wtnp-tn-woocommerce') . ": $shipping_first_name $shipping_last_name\n";

		if ($shipping_company) {$wtnp_message .= "🏢 " . __('Company', 'wtnp-tn-woocommerce') . ": $shipping_company\n";}
		
		$wtnp_message .= "📍 " . __('Address', 'wtnp-tn-woocommerce') .  ": $shipping_city - $shipping_address1 - $shipping_address2 - $shipping_postcode" . "\n";
	}
	$wtnp_message .= "🏦 " . __('Payment Method', 'wtnp-tn-woocommerce') . ": $payment_method\n";
	if ($shipping_method) {$wtnp_message .= "🚚 " . __('Shipping Method', 'wtnp-tn-woocommerce') . ": $shipping_method\n";}
	
	if($order_note) {$wtnp_message .= "\n🗒 " . __('Order Note', 'wtnp-tn-woocommerce') . ": $order_note\n";}


	$wtnp_currency = get_woocommerce_currency();

	$items = $order->get_items();
	$wtnp_message .= "\n🛒 " . __('Product(s)', 'wtnp-tn-woocommerce') . "\n";
	foreach ($items as $item) {
		$product_name = $item->get_name();
		$product_quantity = $item->get_quantity();
		$product_price = $item->get_total();
		$wtnp_message .= " - $product_name • " . __('Quantity', 'wtnp-tn-woocommerce') . ": $product_quantity • " . __('Price', 'wtnp-tn-woocommerce') . " : $product_price" . "\n";
	}

	$order_total = $order->get_total();
	if($shipping_cost) {$wtnp_message .= "\n📦 " . __('Shipping Cost', 'wtnp-tn-woocommerce') . ": $shipping_cost\n"; }
	
	if($coupon_name){$wtnp_message .= "\n🎟 " . __('Coupon Name', 'wtnp-tn-woocommerce') . ": $coupon_name" . "\n" . "💲 " . __('Discount Amount', 'wtnp-tn-woocommerce') . ": - $discount_total" . "\n";}
	$wtnp_message .= "💰 " . __('Total', 'wtnp-tn-woocommerce') . ": $order_total  $wtnp_currency\n";
	$wtnp_message .= "\n" . __('#Update_Order', 'wtnp-tn-woocommerce');
	$url_link = get_admin_url() . "post.php?post=" . $order_id ."&action=edit";


	if ($order_date != $date_modified) {
		
		global $wtnp_settings_telegramcb;
		global $wtnp_settings_teltoken;


    
		if ($wtnp_settings_telegramcb == 'yes' && $wtnp_settings_teltoken) {
			notificator_send_message_wtnp_telegram($wtnp_message, $url_link);
		}

		
	}
}


