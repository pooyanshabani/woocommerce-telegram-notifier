<?php
defined('ABSPATH') || exit;

add_filter( 'woocommerce_settings_tabs_array', 'wtnp_filter_woocommerce_settings_tabs_array', 99 );

function wtnp_filter_woocommerce_settings_tabs_array( $settings_tabs ) {
    $settings_tabs['woo-wtnp-tab'] =  __('Woo Notificator','wtnp-tn-woocommerce');

    return $settings_tabs;
}

function wtnp_get_settings() {

	$wtnp_main_settings = array();

        $wtnp_main_settings = array(

            // Title
            array(
                'title'     =>  __('Notification Bot settings','wtnp-tn-woocommerce'), 
                'type'      => 'title',
                'id'        => 'wtnp_settings'
            ),
			// Telegram Checkbox
			array(
				'title'     => __('Telegram','wtnp-tn-woocommerce'),
				'desc'      => __('Enable Telegram Notification','wtnp-tn-woocommerce'), 
				'default'   => 'no', 
				'id'        => 'wtnp_settings_telegramcb',
				'type'      => 'checkbox', // 
			),			
            // Telegram Token
            array(
                'title'     => __('Telegram token','wtnp-tn-woocommerce'),
                'type'      => 'text',
				'placeholder'   => 'Telegram Token', 
                'id'        => 'wtnp_settings_teltoken',
                'css'       => 'min-width:300px;',
				'desc'      => __('To receive Telegram Token, visit the @wpnotificatorbot bot','wtnp-tn-woocommerce'), 
				'desc_tip'  => true,
				'attributes'    => array(
					'required'  => 'required' 
				),
            ),

			
            
			
            // Section end
            array(
                'type'      => 'sectionend',
                'id'        => 'wtnp_settings_div'
            ),
			// Title
            array(
                'title'     => __('WooCommerce Notification Settings','wtnp-tn-woocommerce'),
                'type'      => 'title',
                'id'        => 'wtnp_settings_thank'
            ),
			// New Order Checkbox
			array(
				'title'     => __('New Order Notification','wtnp-tn-woocommerce'), 
				'desc'      => __('Enable New Product Order Notification','wtnp-tn-woocommerce'),  
				'default'   => 'no', 
				'id'        => 'wtnp_settings_neworder',
				'type'      => 'checkbox', // 
			),
			// Order Status Checkbox
			array(
				'title'     => __('Order Status Change Notification','wtnp-tn-woocommerce'), 
				'desc'      => __('Enable Order Status Change Notification','wtnp-tn-woocommerce'),
				'default'   => 'no', 
				'id'        => 'wtnp_settings_orderstatus',
				'type'      => 'checkbox', // 
			),
			// Out of Stock Checkbox
			array(
				'title'     => __('Out of Stock Notification','wtnp-tn-woocommerce'), 
				'desc'      => __('Enable Product Out of Stock Notification','wtnp-tn-woocommerce'),
				'default'   => 'no', 
				'id'        => 'wtnp_settings_outofstock',
				'type'      => 'checkbox', // 
			),
			// Low Stock Checkbox
			array(
				'title'     => __('Product Low Stock Notification','wtnp-tn-woocommerce'), 
				'desc'      => __('Enable Product Low Stock Notification','wtnp-tn-woocommerce'),
				'default'   => 'no', 
				'id'        => 'wtnp_settings_lowstock',
				'type'      => 'checkbox', // 
			),
			// Comment Checkbox
			array(
				'title'     => __('Comment Registration Notification','wtnp-tn-woocommerce'), 
				'desc'      => __('Enable New Comment Posting Notification on Product Page','wtnp-tn-woocommerce'), 
				'default'   => 'no', 
				'id'        => 'wtnp_settings_comment',
				'type'      => 'checkbox', // 
			),
			// Section end
            array(
                'type'      => 'sectionend',
                'id'        => 'wtnp_settings_end'
            ),
			
        );
		
    
    
    return $wtnp_main_settings;
	
}

// Add settings
function action_woocommerce_settings_woo_wtnp_tab() {
    $settings = wtnp_get_settings();

    WC_Admin_Settings::output_fields( $settings );  
}
add_action( 'woocommerce_sections_woo-wtnp-tab', 'action_woocommerce_settings_woo_wtnp_tab', 10 );



// Process/save the settings
function action_woocommerce_settings_save_woo_wtnp_tab() {
    global $current_section;

    $tab_id = 'woo-wtnp-tab';

    $settings = wtnp_get_settings();

    WC_Admin_Settings::save_fields( $settings );

    if ( $current_section ) {
        do_action( 'woocommerce_update_options_' . $tab_id . '_' . $current_section );
    }
}
add_action( 'woocommerce_settings_save_woo-wtnp-tab', 'action_woocommerce_settings_save_woo_wtnp_tab', 10 );


