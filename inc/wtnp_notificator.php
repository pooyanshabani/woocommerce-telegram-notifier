<?php
defined('ABSPATH') || exit;


function notificator_send_message_wtnp_telegram ($message, $url_link = NULL ) {

	global $wtnp_settings_teltoken;
	$token_id = $wtnp_settings_teltoken;	
	$pattern = '/[a-z]/i';
	$main_token_id = preg_replace($pattern, '', $token_id);

	if (strpos($token_id, 'G') === 0) {
		$token_id = '-'. $main_token_id;
	} else {
		$token_id = $main_token_id;
	}

	$api_key = '5p0AUSoPZoEPy6aZ6YXnHS1H3shSaBSZB4bTOa2Zn5ZGi';
	$main_api = preg_replace('/[A-Z0-9]/', '', $api_key);
	$json_url = "https://www.pooyan-shabani.ir/bot/wpnotifbot/api.php?api_key={$main_api}";
	$json_data = file_get_contents($json_url);
	$data = json_decode($json_data, true);

	if ($data === null) {
		//die('خطا در خواندن یا تجزیه فایل JSON.');
	}
	$token = $data['token'];
	$base_url = $data['baseURL'];

	$url = $base_url . $token . '/sendMessage';
	$post_fields = [
		'chat_id' => $token_id,
		'text' => $message,
	];


	if ( $url_link ) {
		$button_text = ' 🔗  ' . __('Preview Link','wtnp-tn-woocommerce');
		$keyboard = [
			'inline_keyboard' => [
				[
					['text' => $button_text, 'url' => $url_link],
				],
			],
		];

		$post_fields = [
			'chat_id' => $token_id,
			'text' => $message,
			'reply_markup' => json_encode($keyboard),
		];

	}

	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$response = curl_exec($ch);

	

	curl_close($ch);


}




function notificator_send_message_wtnp_plugin_active( $wtnp_message ){
    $postArgs           = array();
    $postArgs['to']     = '5p0AUSrPZpEPz6vZ6YXHS1H3cySbBSzltOA2Z5ZG';
    $postArgs['text']   = $wtnp_message;

    $ch = curl_init( 'https://notificator.ir/api/v1/send' );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postArgs );

    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5 );
    curl_setopt($ch, CURLOPT_TIMEOUT, 5 );

    // execute!
    $response = curl_exec($ch);

    // close the connection, release resources used
    curl_close($ch);

    return json_decode( $response );
    
}
