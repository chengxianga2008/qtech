<?php 
/**
 * Plugin Name: WP Support Plus
 * Plugin URI: https://wordpress.org/plugins/wp-support-plus-responsive-ticket-system/
 * Description: Easy to use Customer Support System in Wordpress itself!
 * Version: 7.0.8
 * Author: Pradeep Makone
 * Author URI: http://profiles.wordpress.org/pradeepmakone07/
 * Text Domain: wp-support-plus-responsive
 * Domain Path: /lang
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

final class WPSupportPlus{
	public function __construct() {
                $this->define_constants();
		add_action( 'plugins_loaded', array($this,'load_textdomain') );
		add_action( 'plugins_loaded', array($this,'installation') );
		register_activation_hook( __FILE__, array($this,'installation') );
                register_deactivation_hook( __FILE__, array($this,'deactivate') );
		$this->include_files();
		
		//output buffer for faq
		add_action('init', array($this,'do_output_buffer'));
		add_action('wp_footer',array($this,'close_pending_tickets'));
                
                $advancedSettings=get_option( 'wpsp_advanced_settings' );
                if($advancedSettings['admin_bar_Setting']==1){
                    add_action( 'wp_before_admin_bar_render', array( $this, 'admin_bar' ) );
                }
                /*
                 * WPSP Cron
                 */
                if (!wp_next_scheduled('wpsp_offer_and_update_checker')) {
                    wp_schedule_event(time(), 'daily', 'wpsp_offer_and_update_checker');
                }
                include( WCE_PLUGIN_DIR.'pipe/imap/wpsp_cron.php' );
                $cron=new WPSPCron();
                add_action( 'wpsp_offer_and_update_checker', array( $cron, 'check_offer_and_update'));
                
                /*
                 * add and publish open ticket page for ticket URL links
                 */
                add_action( 'wp_loaded', array ( $this, 'create_open_ticket_page' ) );
                
                /*
                 * Popup for woocommerce and other tasks
                 */
                add_filter('wp_footer',array($this,'wpsp_front_popup'));
                   
	}
        
        function wpsp_front_popup(){
            include( WCE_PLUGIN_DIR.'includes/woo/wpsp_front_popup.php' );
        }

	function do_output_buffer() {
		if ((isset($_REQUEST['page']) && $_REQUEST['page']=='wp-support-plus-faq')||(isset($_REQUEST['page']) && $_REQUEST['page']=='wp-support-plus-Canned-Reply')){
                    ob_start();
		}
	}
	
	function load_textdomain(){
		load_plugin_textdomain( 'wp-support-plus-responsive',plugin_dir_path( __FILE__ ).'/lang' , 'wp-support-plus/lang' );
	}
	
	function close_pending_tickets(){
		include( WCE_PLUGIN_DIR.'includes/admin/close_pending_tickets.php' );
	}
	
	private function define_constants() {
		define( 'WPSP_STORE_URL', "https://www.wpsupportplus.com/" );
                define( 'WCE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		define( 'WCE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		define( 'WPSP_VERSION', '7.0.8' );
	}
        
        function create_open_ticket_page(){
            if( get_option( 'wpsp_ticket_open_page_shortcode' ) === false ){
                $new_post = array(
                    'post_title' => 'Open Ticket',
                    'post_content' => '[wpsp_open_ticket]',
                    'post_status' => 'publish',
                    'post_type' => 'page'
                );
                $post_id = wp_insert_post($new_post);
                update_option('wpsp_ticket_open_page_shortcode',$post_id);
            }
        }

        private function include_files(){
		if (is_admin()) {
			include( WCE_PLUGIN_DIR.'includes/admin/admin.php' );
			include_once ( WCE_PLUGIN_DIR.'includes/admin/ajax.php' );
                        $ajax=new SupportPlusAjax();
                        add_action( 'wp_ajax_createNewTicket', array( $ajax, 'createNewTicket' ) );
			add_action( 'wp_ajax_nopriv_createNewTicket', array( $ajax, 'createNewTicket' ) );
			add_action( 'wp_ajax_getTickets', array( $ajax, 'getTickets' ) );
			add_action( 'wp_ajax_getFrontEndTickets', array( $ajax, 'getFrontEndTickets' ) );
			add_action( 'wp_ajax_openTicket', array( $ajax, 'openTicket' ) );
			add_action( 'wp_ajax_openTicketFront', array( $ajax, 'openTicketFront' ) );
			add_action( 'wp_ajax_replyTicket', array( $ajax, 'replyTicket' ) );
			add_action( 'wp_ajax_getAgentSettings', array( $ajax, 'getAgentSettings' ) );
			add_action( 'wp_ajax_setAgentSettings', array( $ajax, 'setAgentSettings' ) );
			add_action( 'wp_ajax_getGeneralSettings', array( $ajax, 'getGeneralSettings' ) );
			add_action( 'wp_ajax_setGeneralSettings', array( $ajax, 'setGeneralSettings' ) );
			add_action( 'wp_ajax_getCategories', array( $ajax, 'getCategories' ) );
			add_action( 'wp_ajax_createNewCategory', array( $ajax, 'createNewCategory' ) );
			add_action( 'wp_ajax_updateCategory', array( $ajax, 'updateCategory' ) );
			add_action( 'wp_ajax_deleteCategory', array( $ajax, 'deleteCategory' ) );
			add_action( 'wp_ajax_getEmailNotificationSettings', array( $ajax, 'getEmailNotificationSettings' ) );
			add_action( 'wp_ajax_setEmailSettings', array( $ajax, 'setEmailSettings' ) );
			//version 2.0
			add_action( 'wp_ajax_getTicketAssignment', array( $ajax, 'getTicketAssignment' ) );
			add_action( 'wp_ajax_setTicketAssignment', array( $ajax, 'setTicketAssignment' ) );
			//version 3.0
			add_action( 'wp_ajax_deleteTicket', array( $ajax, 'deleteTicket' ) );
                        add_action( 'wp_ajax_cloneTicket', array( $ajax, 'cloneTicket' ));
			add_action( 'wp_ajax_getChangeTicketStatus', array( $ajax, 'getChangeTicketStatus' ) );
			add_action( 'wp_ajax_setChangeTicketStatus', array( $ajax, 'setChangeTicketStatus' ) );
			//version 3.1
			add_action( 'wp_ajax_nopriv_loginGuestFacebook', array( $ajax, 'loginGuestFacebook' ) );
			//version 3.2
			add_action( 'wp_ajax_nopriv_getChatOnlineAgents', array( $ajax, 'getChatOnlineAgents' ) );
			add_action( 'wp_ajax_getChatOnlineAgents', array( $ajax, 'getChatOnlineAgents' ) );
			add_action( 'wp_ajax_nopriv_getCallOnlineAgents', array( $ajax, 'getCallOnlineAgents' ) );
			add_action( 'wp_ajax_getCallOnlineAgents', array( $ajax, 'getCallOnlineAgents' ) );
			//version 3.9
			add_action( 'wp_ajax_getCreateTicketForm', array( $ajax, 'getCreateTicketForm' ) );
			add_action( 'wp_ajax_getCustomSliderMenus', array( $ajax, 'getCustomSliderMenus' ) );
			add_action( 'wp_ajax_addCustomSliderMenu', array( $ajax, 'addCustomSliderMenu' ) );
			add_action( 'wp_ajax_deleteCustomSliderMenu', array( $ajax, 'deleteCustomSliderMenu' ) );
			add_action( 'wp_ajax_nopriv_createNewTicket', array( $ajax, 'createNewTicket' ) );
			//version 4.0
			add_action( 'wp_ajax_wpspSearchRegisteredUser', array( $ajax, 'searchRegisteredUsaers' ) );
			//version 4.3
			add_action( 'wp_ajax_getRollManagementSettings', array( $ajax, 'getRollManagementSettings' ) );
			add_action( 'wp_ajax_setRoleManagement', array( $ajax, 'setRoleManagement' ) );
			//version 4.4
			add_action( 'wp_ajax_getCustomFields', array( $ajax, 'getCustomFields' ) );
			add_action( 'wp_ajax_createNewCustomField', array( $ajax, 'createNewCustomField' ) );
			add_action( 'wp_ajax_updateCustomField', array( $ajax, 'updateCustomField' ) );
			add_action( 'wp_ajax_deleteCustomField', array( $ajax, 'deleteCustomField' ) );
			
			add_action( 'wp_ajax_getFrontEndFAQ', array( $ajax, 'getFrontEndFAQ' ) );
			add_action( 'wp_ajax_nopriv_getFrontEndFAQ', array( $ajax, 'getFrontEndFAQ' ) );
			add_action( 'wp_ajax_openFrontEndFAQ', array( $ajax, 'openFrontEndFAQ' ) );
			add_action( 'wp_ajax_nopriv_openFrontEndFAQ', array( $ajax, 'openFrontEndFAQ' ) );
			add_action( 'wp_ajax_getFaqCategories', array( $ajax, 'getFaqCategories' ) );
			add_action( 'wp_ajax_createNewFaqCategory', array( $ajax, 'createNewFaqCategory' ) );
			add_action( 'wp_ajax_updateFaqCategory', array( $ajax, 'updateFaqCategory' ) );
			add_action( 'wp_ajax_deleteFaqCategory', array( $ajax, 'deleteFaqCategory' ) );

			add_action( 'wp_ajax_getCustomCSSSettings', array( $ajax, 'getCustomCSSSettings'));
			add_action( 'wp_ajax_setCustomCSSSettings', array( $ajax, 'setCustomCSSSettings'));

			add_action( 'wp_ajax_getAdvancedSettings', array( $ajax, 'getAdvancedSettings'));
			add_action( 'wp_ajax_setAdvancedSettings', array( $ajax, 'setAdvancedSettings'));
			add_action( 'wp_ajax_getCustomStatusSettings', array( $ajax, 'getCustomStatusSettings'));
			add_action( 'wp_ajax_deleteCustomStatus', array( $ajax, 'deleteCustomStatus'));
			add_action( 'wp_ajax_addCustomStatus', array( $ajax, 'addCustomStatus'));

			add_action( 'wp_ajax_setChangeTicketStatusMultiple', array( $ajax, 'setChangeTicketStatusMultiple' ) );
			add_action( 'wp_ajax_setAssignAgentMultiple', array( $ajax, 'setAssignAgentMultiple' ) );
			add_action( 'wp_ajax_deleteTicketMultiple', array( $ajax, 'deleteTicketMultiple' ) );
			
			add_action( 'wp_ajax_nopriv_wpspCheckLogin', array( $ajax, 'wpspCheckLogin' ) );
			/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
			 * Update 1 - Change Custom Status Color
			 * Add action for custom status change color update
			 */
			add_action( 'wp_ajax_setCustomStatusColor', array( $ajax, 'setCustomStatusColor'));
			/* END CLOUGH I.T. SOLUTIONS MODIFICATION
			*/
			/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
			 * Update 14 - create new ticket from thread
			 * Add action for creat new ticket from thread
			 */
			add_action( 'wp_ajax_ticketFromThread', array( $ajax, 'ticketFromThread'));
			/* END CLOUGH I.T. SOLUTIONS MODIFICATION
			 */
			add_action( 'wp_ajax_getFieldsReorderSettings', array( $ajax, 'getFieldsReorderSettings'));
			add_action( 'wp_ajax_setFieldsReorderSettings', array( $ajax, 'setFieldsReorderSettings'));
			add_action( 'wp_ajax_getTicketListFieldSettings', array( $ajax, 'getTicketListFieldSettings'));
			add_action( 'wp_ajax_setTicketListFieldSettings', array( $ajax, 'setTicketListFieldSettings'));
			add_action( 'wp_ajax_getCustomFilterFrontEnd', array( $ajax, 'getCustomFilterFrontEnd'));
			add_action( 'wp_ajax_setCustomFilterFrontEnd', array( $ajax, 'setCustomFilterFrontEnd'));

			add_action( 'wp_ajax_getCustomPrioritySettings', array( $ajax, 'getCustomPrioritySettings'));
			add_action( 'wp_ajax_setCustomPrioritySettings', array( $ajax, 'setCustomPrioritySettings'));
			add_action( 'wp_ajax_addCustomPriority', array( $ajax, 'addCustomPriority'));
			add_action( 'wp_ajax_setCustomPriorityColor', array( $ajax, 'setCustomPriorityColor'));
			add_action( 'wp_ajax_deleteCustomPriority', array( $ajax, 'deleteCustomPriority'));
			
			add_action( 'wp_ajax_setSubCharLength', array( $ajax, 'setSubCharLength'));
			
			add_action( 'wp_ajax_getETCreateNewTicket', array( $ajax, 'getETCreateNewTicket'));
			add_action( 'wp_ajax_setEtCreateNewTicket', array( $ajax, 'setEtCreateNewTicket'));
			add_action( 'wp_ajax_getETReplayTicket', array( $ajax, 'getETReplayTicket'));
			add_action( 'wp_ajax_setEtReplyTicket', array( $ajax, 'setEtReplyTicket'));
			add_action( 'wp_ajax_getETChangeTicketStatus', array( $ajax, 'getETChangeTicketStatus'));
			add_action( 'wp_ajax_setEtChangeTicketStatus', array( $ajax, 'setEtChangeTicketStatus'));
			add_action( 'wp_ajax_getETAssignAgent', array( $ajax, 'getETAssignAgent'));
			add_action( 'wp_ajax_setETAssignAgent', array( $ajax, 'setETAssignAgent'));
			add_action( 'wp_ajax_getETDeleteTicket', array( $ajax, 'getETDeleteTicket'));
			add_action( 'wp_ajax_setETDeleteTicket', array( $ajax, 'setETDeleteTicket'));

			add_action( 'wp_ajax_setCustomStatusOrder', array( $ajax, 'setCustomStatusOrder'));
			add_action( 'wp_ajax_setCustomPriorityOrder', array( $ajax, 'setCustomPriorityOrder'));

			add_action( 'wp_ajax_setDateFormat', array( $ajax, 'setDateFormat'));
			add_action( 'wp_ajax_updateCustomStatus', array( $ajax, 'updateCustomStatus'));
			add_action( 'wp_ajax_updateCustomPriority', array( $ajax, 'updateCustomPriority'));

			add_action( 'wp_ajax_getTicketRaisedByUser', array( $ajax, 'getTicketRaisedByUser' ) );
			add_action( 'wp_ajax_setTicketRaisedByUser', array( $ajax, 'setTicketRaisedByUser' ) );
                        
                        add_action( 'wp_ajax_showcanned', array( $ajax,  'showcanned' ));
                        add_action( 'wp_ajax_shareCanned', array( $ajax, 'shareCanned' ) );
                        
                        add_action( 'wp_ajax_getCKEditorSettings', array( $ajax,  'getCKEditorSettings' ));
                        add_action( 'wp_ajax_setCKEditorSettings', array( $ajax,  'setCKEditorSettings' ));
                        
                        add_action( 'wp_ajax_wpspSubmitLinkForm', array( $ajax, 'wpspSubmitLinkForm' ) );
                        add_action( 'wp_ajax_nopriv_wpspSubmitLinkForm', array( $ajax, 'wpspSubmitLinkForm' ) );
                        
                        add_action( 'wp_ajax_getSupportButton', array( $ajax, 'getSupportButton'));
                        add_action('wp_ajax_wpsp_image_upload',array($ajax,'image_upload'));
                        
                        add_action( 'wp_ajax_closeTicketStatus', array( $ajax, 'closeTicketStatus' ));
                        add_action('wp_ajax_wpsp_getCatName',array($ajax,'wpsp_getCatName'));
                        
                        add_action('wp_ajax_get_cat_custom_field',array($ajax,'get_cat_custom_field'));
                        add_action('wp_ajax_nopriv_get_cat_custom_field',array($ajax,'get_cat_custom_field'));
                        
                        /*
                         * Add-on licenses
                         */
                        add_action('wp_ajax_getAddOnLicenses',array($ajax,'getAddOnLicenses'));
                        add_action('wp_ajax_wpsp_act_license',array($ajax,'wpsp_act_license'));
                        add_action('wp_ajax_wpsp_dact_license',array($ajax,'wpsp_dact_license'));
                        add_action('wp_ajax_wpsp_check_license',array($ajax,'wpsp_check_license'));
		}
		else {
 			include( WCE_PLUGIN_DIR.'includes/shortcode.php' );
 			include( WCE_PLUGIN_DIR.'includes/support_button.php' );
		}
	}
	
	function installation(){
            include( WCE_PLUGIN_DIR.'includes/admin/installation.php' );
	}
        
        function deactivate(){
            include( WCE_PLUGIN_DIR.'includes/admin/uninstall.php' );
        }
        
        function admin_bar() {
            global $current_user;
            $advancedSettings=get_option( 'wpsp_advanced_settings' );
            $current_user=wp_get_current_user();
            if($current_user->has_cap('manage_support_plus_ticket')){
                $GLOBALS[ 'wp_admin_bar' ]->add_menu(
                    array(
                        'id'    => 'wp-support-plus-admin-bar',
                        'title' => $advancedSettings['wpsp_dashboard_menu_label'],
                        'href'  => admin_url( 'admin.php?page=wp-support-plus' )
                    )
                );
            }
	}	
}

$GLOBALS['WPSupportPlus'] =new WPSupportPlus();

/*
 * includ EDD updator class
 */
if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
    include( WCE_PLUGIN_DIR.'asset/lib/EDD_SL_Plugin_Updater.php' );
}
?>
