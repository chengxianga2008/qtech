<?php
 
add_action('init', 'wpdmpp_update_cart');
add_action('init', 'wpdmpp_load_payment_methods');
add_action('init', 'wpdmpp_remove_cart_item');

add_action('init', 'wpdmpp_get_purchased_items');


add_action('init', 'wpdmpp_getlicensekey');

add_action('init', 'wpdmpp_paynow');
add_action('init','wpdmpp_invoice'); 

//for the payment notification by the user
add_action("init", "wpdmpp_payment_notification");

//for the withdraw payment notification
add_action("init", "wpdmpp_withdraw_paypal_notification");
add_action('init', 'wpdmpp_execute');

//Load Saved Cart
add_action('wp_loaded', 'wpdmpp_load_saved_cart');

//add to cart using form submit
add_action('init', 'wpdmpp_add_to_cart');

//add to cart from url call
add_action('init', 'wpdmpp_add_to_cart_ucb');

//the function for adding the product from the frontend
add_action('wp_loaded', 'wpdmpp_add_product');

//payment from the theme orders panel
add_action('init', 'wpdmpp_ajax_payfront');

//for withdraw request
add_action('init', 'wpdmpp_withdraw_request');

//for the print invoice
add_action('init', 'wpdmpp_print_invoice');
 
add_action('init', 'wpdmpp_download', 0);
add_action('the_content', 'wpdmpp_buynow', 999999);

add_filter('wp_head', 'wpdmpp_head');

add_shortcode("wpdm-pp-purchases", "wpdmpp_user_purchases");

add_shortcode("wpdm-pp-cart", "wpdmpp_show_cart");

add_shortcode("wpdm-pp-guest-orders", "wpdmpp_guest_orders");

add_action("init", "wpdmpp_process_guest_order");

//for the cart page
//add_shortcode("wpdm-pp-tabs", "wpdmpp_tabs");
add_filter("wpdm_frontend", "wpdmpp_frontend_tabs");

//short code for the earnings
add_shortcode("wpdm-pp-earnings", "wpdmpp_earnings");

//short code for edit profile
add_shortcode("wpdm-pp-edit-profile", "wpdmpp_edit_profile");

//required for guest checkout
add_action('wpdm_login_form','wpdmpp_invoide_field');
add_action('wpdm_register_form','wpdmpp_invoide_field');
add_action('wpdm_edit_profile_form','wpdmpp_billing_info_form');
add_action('wpdm_update_profile','wpdmpp_save_billing_info');
add_action("wp_login", "wpdmpp_associate_invoice", 10, 2);
add_action( 'user_register', 'wpdmpp_associate_invoice_signup', 10, 1 );

add_filter('wpdm_check_lock', 'wpdmpp_lock_download', 10, 2);

add_filter('wpdm_package_settings_tabs', 'wpdmpp_meta_boxes');
add_action('save_post', 'wpdmpp_save_meta_data', 10, 2);

add_action('wp_ajax_resolveorder', 'wpdmpp_resolveorder');

add_action('wp', 'wpdmpp_download_order_note_attachment');

if (is_admin()) {
	add_action("admin_menu", "wpdmpp_menu");
	//add_action('delete_post', 'wpdmpp_delete_product');
	add_action('wp_ajax_assign_user_2order', 'wpdmpp_assign_user_2order');
	add_action('wp_ajax_wpdmpp_save_settings', 'wpdmpp_save_settings');
	add_action('wp_ajax_wpdmpp_ajax_call', 'wpdmpp_ajax_call');
	add_action('wp_ajax_moveuploadprevfile', 'wpdmpp_move_upload_previewfile');
	add_action('wp_ajax_moveuploadprofile', 'wpdmpp_move_upload_productfile');
	add_action('wp_ajax_moveuploadfeaturedfile', 'wpdmpp_move_upload_featuredfile');
	//for auto suggest tool
	add_action('wp_ajax_wpdmpp_autosuggest', 'wpdmpp_autosuggest');
	//for removing feature product
	add_action('wp_ajax_wpdmpp_remove_featured', 'wpdmpp_remove_featured');

	//add_action( 'wp_ajax_wpdmpp_save_currencies', 'wpdmpp_save_currencies');
	//for default currency saving
	add_action('wp_ajax_wpdmpp_default_currency', 'wpdmpp_default_currency');
	//for default currency deleting
	add_action('wp_ajax_wpdmpp_default_currency_del', 'wpdmpp_default_currency_del');

    //Reacalculate Sales
	add_action('wp_ajax_RecalculateSales', 'wpdmpp_recalculate_sales');

    // SEND EMAIL ONCE POST IS PUBLISHED
    add_action( 'publish_post', 'notify_product_accepted' );


	//wp_enqueue_script('jquery-form');


}
if(!is_admin())
    add_action('init', 'wpdmpp_execute');
if(!is_admin())
add_action('init', 'wpdmpp_delete_product');
add_action('wp_enqueue_scripts', 'wpdmpp_enqueue_scripts');
add_action('admin_enqueue_scripts', 'wpdmpp_enqueue_scripts');


add_action('init', 'wpdmpp_languages');
add_action('init', 'wpdmpp_update_profile');

add_action('wpdm_onstart_download','wpdmpp_validate_download');
