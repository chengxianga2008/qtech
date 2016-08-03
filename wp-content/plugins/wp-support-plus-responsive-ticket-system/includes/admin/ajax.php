<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

final class SupportPlusAjax {
	function createNewTicket(){
		//catch JS injection
		if(stristr($_POST['create_ticket_body'],"<script>")){
			die(__("Javascript Injection Not Allowed!",'wp-support-plus-responsive'));
		}
		
		//check recaptcha
		$generalSettings=get_option('wpsp_general_settings');
                $advancedSettings=get_option('wpsp_advanced_settings' );
		if($_POST['type']=='guest'&& !isset($_POST['backend']) && !isset($_POST['pipe']) && $generalSettings['google_nocaptcha_key'] && $generalSettings['google_nocaptcha_secret']){
			include( WCE_PLUGIN_DIR.'asset/lib/google_noCaptcha/checkCaptcha.php' );
		}
		
		global $wpdb;
		
		//CODE FOR ATTACHMENT START
		/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
		 * Update 11 - add support to save attachments and images from emails
		 */
		if( isset($_POST['pipe']) && $_POST['pipe'] == 1 ) {
			$attachment_ids = $_POST['attachment_ids'];
			if(!$attachment_ids) $attachment_ids=array();
                        $emailAttachments=array();
			foreach( $attachment_ids as $attachment_id ) {
				$attachments = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'wpsp_attachments WHERE id=' . $attachment_id );
				foreach ( $attachments as $attachment ) {
					$emailAttachments[] = $attachment->filepath;
				}
			}
			$attachment_ids = implode( ',', $attachment_ids );
		} else {
			$attachments=array();
			if(isset($_FILES['attachment']) && $_FILES['attachment']['name'][0]!=''){
                            for($i=0;$i<count($_FILES['attachment']['name']);$i++){
                                $ext = pathinfo($_FILES['attachment']['name'][$i], PATHINFO_EXTENSION);
                                if($ext!="php" && $ext!="exe"){
                                    $upload_dir = wp_upload_dir();
                                    $save_directory = $upload_dir['basedir'] . '/'.time().'_'.$_FILES['attachment']['name'][$i];
                                    $save_url = $upload_dir['baseurl'] . '/'.time().'_'.$_FILES['attachment']['name'][$i];
                                    move_uploaded_file($_FILES['attachment']['tmp_name'][$i], $save_directory);
                                    $attachments[]=array(
                                        'name'=>$_FILES['attachment']['name'][$i],
                                        'file_path'=>$save_directory,
                                        'file_url'=>$save_url,
                                        'type'=>$_FILES['attachment']['type'][$i]
                                    );
                                }
                            }
			}
			$attachment_ids=array();
			$emailAttachments=array();
			foreach ($attachments as $attachment){
				$values=array(
					'filename'=>$attachment['name'],
					'filetype'=>$attachment['type'],
					'filepath'=>$attachment['file_path'],
					'fileurl'=>$attachment['file_url']
				);
				$wpdb->insert($wpdb->prefix.'wpsp_attachments',$values);
				$attachment_ids[]= $wpdb->insert_id;
				
				$emailAttachments[]=$attachment['file_path'];
			}
			$attachment_ids=implode(',', $attachment_ids);
		}
		/* END CLOUGH I.T. SOLUTIONS MODIFICATION
		 */
		//CODE FOR ATTACHMENT END
		$default_assignee_id='0';
		
		if(isset($_POST['create_ticket_category'])){
			$default_assignees=$wpdb->get_var( "SELECT default_assignee FROM {$wpdb->prefix}wpsp_catagories WHERE id='".$_POST['create_ticket_category']."'" );
			if($default_assignees!='0'){
				$default_assignee_id=$default_assignees;
			}
		}
		
		if(isset($_POST['create_ticket_type']) && ($_POST['create_ticket_type']=="on" || $_POST['create_ticket_type']==1))
		{
			$ticket_type=1;
		}
		else
		{
			$ticket_type=0;
		}
		$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
                $wpsp_subject=(isset($_POST['create_ticket_subject']))?$_POST['create_ticket_subject']:$advancedSettingsFieldOrder['wpsp_default_value_of_subject'];
                $cat_id=(isset($_POST['create_ticket_category']))?$_POST['create_ticket_category']:1;
		$priority=(isset($_POST['create_ticket_priority']))?$_POST['create_ticket_priority']:'normal';

		$status_priority = get_option( 'wpsp_default_status_priority_names' );
		$generalSettings=get_option( 'wpsp_general_settings' );
		
		$sql="select * from {$wpdb->prefix}wpsp_custom_status WHERE id=".$generalSettings['default_new_ticket_status']." ";
		$status_data=$wpdb->get_results($sql);
		foreach($status_data as $status)
		{
			$status_name = $status->name;
		}
		if(!isset($status_name))
		{
			$status_name = $status_priority['status_names']['open'];
		}
		
		//create ticket
                if(!(isset($_POST['pipe'])) && is_user_logged_in() && get_current_user_id()!=$_POST['user_id']){
                    $current_user_id=get_current_user_id();
                }
                else{
                    $current_user_id=0;
                }
                if($advancedSettings['ticketId']==1){
                    $values=array(
                                    'subject'=>htmlspecialchars($wpsp_subject,ENT_QUOTES),
                                    'created_by'=>$_POST['user_id'],
                                    'assigned_to'=>$default_assignee_id,
                                    'guest_name'=>$_POST['guest_name'],
                                    'guest_email'=>$_POST['guest_email'],
                                    'type'=>$_POST['type'],
                                    'status'=>$status_name,
                                    'cat_id'=>$cat_id,
                                    'create_time'=>current_time('mysql', 1),
                                    'update_time'=>current_time('mysql', 1),
                                    'priority'=>$priority,
                                    'ticket_type'=>$ticket_type,
                                    'agent_created'=>$current_user_id
                    );
                } 
                else {
                    
                    $id=0;
                    do{
                        $id=rand(111111, 999999);
                        $sql="select id from {$wpdb->prefix}wpsp_ticket where id=".$id;
                        $result=$wpdb->get_var($sql);
                    }while ($result);
                    
                    $values=array(   
                                'id'=>$id,
				'subject'=>htmlspecialchars($wpsp_subject,ENT_QUOTES),
				'created_by'=>$_POST['user_id'],
				'assigned_to'=>$default_assignee_id,
				'guest_name'=>$_POST['guest_name'],
				'guest_email'=>$_POST['guest_email'],
				'type'=>$_POST['type'],
				'status'=>$status_name,
				'cat_id'=>$cat_id,
				'create_time'=>current_time('mysql', 1),
				'update_time'=>current_time('mysql', 1),
				'priority'=>$priority,
				'ticket_type'=>$ticket_type,
                                'agent_created'=>$current_user_id
                            );
                }
                
		//custom fields values
		$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
		foreach ($customFields as $field){
			if(isset($_POST['cust'.$field->id]) && is_array($_POST['cust'.$field->id]))
			{
				$_POST['cust'.$field->id]=implode(",",$_POST['cust'.$field->id]);
			}
			$values['cust'.$field->id]=(isset($_POST['cust'.$field->id]))?htmlspecialchars($_POST['cust'.$field->id],ENT_QUOTES):'';
		}
                
                if(isset($_POST['extension_meta'])){
                    $values['extension_meta']=$_POST['extension_meta'];
                }
		
		$wpdb->insert($wpdb->prefix.'wpsp_ticket',$values);
		$ticket_id=$wpdb->insert_id;
                
                if(!(isset($_POST['pipe'])) && $_POST['type']=='guest'&& $generalSettings['enable_register_guest_user']==1){
                    $wpsp_user = get_user_by( 'email', $_POST['guest_email'] );
                    if(!$wpsp_user){
                        $user_login = $_POST['guest_email'];
                        $user_email = $_POST['guest_email'];
                        $errors = register_new_user($user_login, $user_email);
                        $wpsp_user = get_user_by( 'email', $user_email );
                        $wpsp_user->set_role( $generalSettings['guest_user_role'] );
                    }
                }
		
                if( (isset($_POST['create_ticket_body'])) && ( ((isset($_POST['ckeditor_enabled'])) && $_POST['ckeditor_enabled']=='0') || isset($_POST['extension_meta']) ) ){
                    $_POST['create_ticket_body']= $this->nl2br_save_html($_POST['create_ticket_body']);
                }
                $description=(isset($_POST['create_ticket_body']))?htmlspecialchars($_POST['create_ticket_body'],ENT_QUOTES):'';
				
		//create thread
		$values=array(
				'ticket_id'=>$ticket_id,
				'body'=>$description,
				'attachment_ids'=>$attachment_ids,
				'create_time'=>current_time('mysql', 1),
				'created_by'=>$_POST['user_id'],
				'guest_name'=>$_POST['guest_name'],
				'guest_email'=>$_POST['guest_email']
		);
		$wpdb->insert($wpdb->prefix.'wpsp_ticket_thread',$values);
		//check mail settings
		include( WCE_PLUGIN_DIR.'includes/admin/sendTicketCreateMail.php' );
		//end
		if(!(isset($_POST['pipe'])||isset($_POST['extension_meta']))){
			echo "1";die();
		}
	}
	
	function replyTicket(){
	
		//catch JS injection
		if(stristr($_POST['replyBody'],"<script>")){
			die(__("Javascript Injection Not Allowed!",'wp-support-plus-responsive'));
		}
		
		global $wpdb;
	
		//CODE FOR ATTACHMENT START
		/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
         * Update 11 - add support to save attachments and images from emails
         */
        if( isset($_POST['pipe']) && $_POST['pipe'] == 1 ) {
            $attachment_ids = $_POST['attachment_ids'];
            if(!$attachment_ids) $attachment_ids=array();
            $emailAttachments=array();
            foreach( $attachment_ids as $attachment_id ) {
                $attachments = $wpdb->get_results( 'SELECT * FROM ' . $wpdb->prefix . 'wpsp_attachments WHERE id=' . $attachment_id );
                foreach ( $attachments as $attachment ) {
                    $emailAttachments[] = $attachment->filepath;
                }
            }
            $attachment_ids = implode( ',', $attachment_ids );
        } else {
            $attachments=array();
            if(isset($_FILES['attachment']) && $_FILES['attachment']['name'][0]!=''){
                for($i=0;$i<count($_FILES['attachment']['name']);$i++){
                    $ext = pathinfo($_FILES['attachment']['name'][$i], PATHINFO_EXTENSION);
                    if($ext!="php" && $ext!="exe"){
                        $upload_dir = wp_upload_dir();
                        $save_directory = $upload_dir['basedir'] . '/'.time().'_'.$_FILES['attachment']['name'][$i];
                        $save_url = $upload_dir['baseurl'] . '/'.time().'_'.$_FILES['attachment']['name'][$i];
                        move_uploaded_file($_FILES['attachment']['tmp_name'][$i], $save_directory);
                        $attachments[]=array(
                            'name'=>$_FILES['attachment']['name'][$i],
                            'file_path'=>$save_directory,
                            'file_url'=>$save_url,
                            'type'=>$_FILES['attachment']['type'][$i]
                        );
                    }
                }
            }
            $attachment_ids=array();
            $emailAttachments=array();
            foreach ($attachments as $attachment){
                $values=array(
                    'filename'=>$attachment['name'],
                    'filetype'=>$attachment['type'],
                    'filepath'=>$attachment['file_path'],
                    'fileurl'=>$attachment['file_url']
                );
                $wpdb->insert($wpdb->prefix.'wpsp_attachments',$values);
                $attachment_ids[]= $wpdb->insert_id;
                
                $emailAttachments[]=$attachment['file_path'];
            }
            $attachment_ids=implode(',', $attachment_ids);
        }
        /* END CLOUGH I.T. SOLUTIONS MODIFICATION
         */
		//CODE FOR ATTACHMENT END
	
		//create ticket
		$generalSettings=get_option( 'wpsp_general_settings' );
		$sql="select * FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$_POST['ticket_id'];
		$ticket = $wpdb->get_row( $sql );
		
		if(!isset($_POST['pipe'])){
			$replyStatus=$_POST['reply_ticket_status'];
			
			if($generalSettings['ticket_status_after_cust_reply']!='default' && $ticket->created_by==$_POST['user_id']){
				$replyStatus=$generalSettings['ticket_status_after_cust_reply'];
			}
			$values=array(
					'status'=>$replyStatus,
					'cat_id'=>$_POST['reply_ticket_category'],
					'update_time'=>current_time('mysql', 1),
					'priority'=>$_POST['reply_ticket_priority']
			);
		}
		else {
			$replyStatus='';
			if($generalSettings['ticket_status_after_cust_reply']!='default' && $ticket->created_by==$_POST['user_id']){
                            $replyStatus=$generalSettings['ticket_status_after_cust_reply'];
			} else {
                            $status_priority = get_option( 'wpsp_default_status_priority_names' );
                            $sql="select * from {$wpdb->prefix}wpsp_custom_status WHERE id=".$generalSettings['default_new_ticket_status']." ";
                            $status_data=$wpdb->get_results($sql);
                            foreach($status_data as $status){
                                $replyStatus = $status->name;
                            }
                            if(!$replyStatus){
                                $status_name = $status_priority['status_names']['open'];
                            }
                        }
			$values=array(
                            'status'=>$replyStatus,
                            'update_time'=>current_time('mysql', 1)
			);
		}
		$wpdb->update($wpdb->prefix.'wpsp_ticket',$values,array('id' => $_POST['ticket_id']));
                
                if( (isset($_POST['replyBody'])) && (isset($_POST['ckeditor_enabled'])) && $_POST['ckeditor_enabled']=='0' ){
                    $_POST['replyBody']= $this->nl2br_save_html($_POST['replyBody']);
                }
		//create thread
		$values=array(
				'ticket_id'=>$_POST['ticket_id'],
				'body'=>htmlspecialchars($_POST['replyBody'],ENT_QUOTES),
				'attachment_ids'=>$attachment_ids,
				'create_time'=>current_time('mysql', 1),
				'created_by'=>$_POST['user_id']
		);
		if(isset($_POST['pipe'])){
			$values['guest_name']=$_POST['guest_name'];
			$values['guest_email']=$_POST['guest_email'];
		}
		if (!( !isset($_POST['notify']) || ( isset( $_POST['notify'] ) && $_POST['notify'] == 'true' ) )) {
			$values['is_note']=1;
		}
		$wpdb->insert($wpdb->prefix.'wpsp_ticket_thread',$values);
		
		//check mail settings
		/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
	         * Update 15 - add note (no notifications)
	         */
	        if ( !isset($_POST['notify']) || ( isset( $_POST['notify'] ) && $_POST['notify'] == 'true' ) ) {
			include( WCE_PLUGIN_DIR.'includes/admin/sendTicketReplyMail.php' );
	        }
	        /* END CLOUGH I.T. SOLUTIONS MODIFICATION
	         */
		//end
		if(!isset($_POST['pipe'])){
			echo "1";die();
		}
	}
/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
* Update 14 - create new ticket from thread
*/ 
function ticketFromThread() {
	global $wpdb;
	$thread_id = $_POST['thread_id'];
	$now = time();
	$generalSettings = get_option( 'wpsp_general_settings' );
        
	// get ticket id
	$sql = "SELECT * FROM {$wpdb->prefix}wpsp_ticket_thread WHERE id='" . $thread_id . "'";
	$result = $wpdb->get_row( $sql );
	$ticket_id = $result->ticket_id;

	// get existing ticket and place into temporary table
	$sql = "CREATE TEMPORARY TABLE {$wpdb->prefix}wpsp_temp_table AS SELECT * FROM {$wpdb->prefix}wpsp_ticket WHERE id='" . $ticket_id . "'";
	$wpdb->query( $sql );
        
	// set default values
	// id, subject, updated_by, status, cat_id, create_time, update_time, priority, ticket_type
	$sql = "UPDATE 
			{$wpdb->prefix}wpsp_temp_table 
		SET 
			id='0',
			subject='New ticket from Ticket #" . $ticket_id . " (" . $thread_id . ")',
			updated_by='0',
			status='open',
			cat_id='" . $generalSettings['default_ticket_category'] . "',
			create_time='" . gmdate('Y-m-d H:i:s',$now) . "',
			update_time='" . gmdate('Y-m-d H:i:s',$now) . "',
			priority='normal',
			ticket_type='" . $generalSettings['default_ticket_type'] . "'
		WHERE 
			id='" . $ticket_id . "'";
	$wpdb->query( $sql );
        
	// add updated entry into tickets table from temp table
	$sql = "INSERT INTO {$wpdb->prefix}wpsp_ticket SELECT * FROM {$wpdb->prefix}wpsp_temp_table";
	$wpdb->query( $sql );
        
	// get new ticket id
	$new_ticket = $wpdb->insert_id;
        
	// drop temp table
	$sql = "DROP TEMPORARY TABLE {$wpdb->prefix}wpsp_temp_table";
	$wpdb->query( $sql );
        
	// get ticket owner information
	$sql = "SELECT * FROM {$wpdb->prefix}wpsp_ticket WHERE id='" . $new_ticket . "'";
	$result = $wpdb->get_row( $sql );
	$created_by = $result->created_by;
	$guest_name = $result->guest_name;
	$guest_email = $result->guest_email;
        
	// get existing thread and place into temporary table
	$sql = "CREATE TEMPORARY TABLE {$wpdb->prefix}wpsp_temp_table AS SELECT * FROM {$wpdb->prefix}wpsp_ticket_thread WHERE id='" . $thread_id . "'";
	$wpdb->query( $sql );
        
	// set default values
	// id, ticket_id, create_time, created_by, guest_name, guest_email
	$sql = "UPDATE 
			{$wpdb->prefix}wpsp_temp_table 
		SET 
			id='0',
			ticket_id='" . $new_ticket . "', 
			create_time='" . gmdate('Y-m-d H:i:s',$now) . "',
			created_by='" . $created_by . "',
			guest_name='" . $guest_name . "',
			guest_email='" . $guest_email . "' 
		WHERE id='" . $thread_id . "'";
	$wpdb->query( $sql );
        
	// add updated entry into thread table from temp table
	$sql = "INSERT INTO {$wpdb->prefix}wpsp_ticket_thread SELECT * FROM {$wpdb->prefix}wpsp_temp_table";
	$wpdb->query( $sql );
        
	// drop temp table
	$sql = "DROP TEMPORARY TABLE {$wpdb->prefix}wpsp_temp_table";
	$wpdb->query( $sql );
	echo $new_ticket;
}
/* END CLOUGH I.T. SOLUTIONS MODIFICATION
*/ 
	
	function getTickets(){
		include( WCE_PLUGIN_DIR.'includes/admin/getTicketsByFilter.php' );
		die();
	}
	
	function getFrontEndTickets(){
		include( WCE_PLUGIN_DIR.'includes/admin/getFrontEndTicket.php' );
		die();
	}
	
	function openTicket(){
		include( WCE_PLUGIN_DIR.'includes/admin/getIndivisualTicket.php' );
		die();
	}
	
	function openTicketFront(){
		include( WCE_PLUGIN_DIR.'includes/admin/getIndivisualTicketFront.php' );
		die();
	}
	
	function getAgentSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/getAgentSettings.php' );
		die();
	}
	
	function setAgentSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/setAgentSettings.php' );
		die();
	}
	
	function getGeneralSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/getGeneralSettings.php' );
		die();
	}
	
	function setGeneralSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/setGeneralSettings.php' );
		die();
	}
	
	function getCategories(){
		include( WCE_PLUGIN_DIR.'includes/admin/getCategories.php' );
		die();
	}
	
	function createNewCategory(){
		include( WCE_PLUGIN_DIR.'includes/admin/createNewCategory.php' );
		die();
	}
	
	function updateCategory(){
		include( WCE_PLUGIN_DIR.'includes/admin/updateCategory.php' );
		die();
	}
	
	function deleteCategory(){
		include( WCE_PLUGIN_DIR.'includes/admin/deleteCategory.php' );
		die();
	}
	
	function getEmailNotificationSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/getEmailNotificationSettings.php' );
		die();
	}
	
	function setEmailSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/setEmailSettings.php' );
		die();
	}
	
	//version 2.0
	function getTicketAssignment(){
		include( WCE_PLUGIN_DIR.'includes/admin/getTicketAssignment.php' );
		die();
	}
	
	//version 2.0
	function setTicketAssignment(){
		include( WCE_PLUGIN_DIR.'includes/admin/setTicketAssignment.php' );
		die();
	}
	
	//Version 3.0
	function deleteTicket(){
		include( WCE_PLUGIN_DIR.'includes/admin/deleteTicket.php' );
		die();
	}
	function cloneTicket(){
                 include_once( WCE_PLUGIN_DIR.'includes/admin/cloneTicket.php' );
		die();
        }
	//Version 3.0
	function getChangeTicketStatus(){
		include( WCE_PLUGIN_DIR.'includes/admin/getChangeTicketStatus.php' );
		die();
	}
	
	//Version 3.0
	function setChangeTicketStatus(){
		include( WCE_PLUGIN_DIR.'includes/admin/setChangeTicketStatus.php' );
		die();
	}
	
	//Version 3.1
	function loginGuestFacebook(){
		include( WCE_PLUGIN_DIR.'includes/admin/loginGuestFacebook.php' );
		die();
	}
	
	//Version 3.2
	function getChatOnlineAgents(){
		include( WCE_PLUGIN_DIR.'includes/admin/getChatOnlineAgents.php' );
		die();
	}
	
	//Version 3.2
	function getCallOnlineAgents(){
		include( WCE_PLUGIN_DIR.'includes/admin/getCallOnlineAgents.php' );
		die();
	}
	
	//version 3.9
	function getCreateTicketForm(){
		include( WCE_PLUGIN_DIR.'includes/admin/create_new_ticket.php' );
		die();
	}
	
	//version 3.9
	function getCustomSliderMenus(){
		include( WCE_PLUGIN_DIR.'includes/admin/getCustomSliderMenus.php' );
		die();
	}
	
	//version 3.9
	function addCustomSliderMenu(){
		include( WCE_PLUGIN_DIR.'includes/admin/addCustomSliderMenu.php' );
		die();
	}
	
	//version 3.9
	function deleteCustomSliderMenu(){
		include( WCE_PLUGIN_DIR.'includes/admin/deleteCustomSliderMenu.php' );
		die();
	}
	
	//version 4.0
	function searchRegisteredUsaers(){
		include( WCE_PLUGIN_DIR.'includes/admin/searchRegisteredUsaers.php' );
		die();
	}
	
	//version 4.3
	function getRollManagementSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/getRollManagementSettings.php' );
		die();
	}
	
	function setRoleManagement(){
		include( WCE_PLUGIN_DIR.'includes/admin/setRoleManagement.php' );
		die();
	}
	
	//version 4.4
	function getCustomFields(){
		include( WCE_PLUGIN_DIR.'includes/admin/getCustomFields.php' );
		die();
	}
	
	function createNewCustomField(){
		include( WCE_PLUGIN_DIR.'includes/admin/createNewCustomField.php' );
		die();
	}
	
	function updateCustomField(){
		include( WCE_PLUGIN_DIR.'includes/admin/updateCustomField.php' );
		die();
	}
	
	function deleteCustomField(){
		include( WCE_PLUGIN_DIR.'includes/admin/deleteCustomField.php' );
		die();
	}

	function getFrontEndFAQ(){
		include( WCE_PLUGIN_DIR.'includes/admin/getFrontEndFAQ.php' );
		die();
	}
	function openFrontEndFAQ(){
		include( WCE_PLUGIN_DIR.'includes/admin/openFrontEndFAQ.php' );
		die();
	}

	function getFaqCategories(){
		include( WCE_PLUGIN_DIR.'includes/admin/getFaqCategories.php' );
		die();
	}
	
	function createNewFaqCategory(){
		include( WCE_PLUGIN_DIR.'includes/admin/createNewFaqCategory.php' );
		die();
	}
	
	function updateFaqCategory(){
		include( WCE_PLUGIN_DIR.'includes/admin/updateFaqCategory.php' );
		die();
	}
	
	function deleteFaqCategory(){
		include( WCE_PLUGIN_DIR.'includes/admin/deleteFaqCategory.php' );
		die();
	}
	
	function getCustomCSSSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/getCustomCSSSettings.php' );
		die();
	}
	
	function setCustomCSSSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/setCustomCSSSettings.php' );
		die();
	}

	function getAdvancedSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/getAdvancedSettings.php' );
		die();
	}
	
	function setAdvancedSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/setAdvancedSettings.php' );
		die();
	}

	function getCustomStatusSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/getCustomStatusSettings.php' );
		die();
	}

	function deleteCustomStatus(){
		include( WCE_PLUGIN_DIR.'includes/admin/deleteCustomStatus.php' );
		die();
	}

	function addCustomStatus(){
		include( WCE_PLUGIN_DIR.'includes/admin/addCustomStatus.php' );
		die();
	}
	
	function setChangeTicketStatusMultiple(){
		include( WCE_PLUGIN_DIR.'includes/admin/setChangeTicketStatusMultiple.php' );
		die();
	}
	
	function setAssignAgentMultiple(){
		include( WCE_PLUGIN_DIR.'includes/admin/setAssignAgentMultiple.php' );
		die();
	}
	
	function deleteTicketMultiple(){
		include( WCE_PLUGIN_DIR.'includes/admin/deleteTicketMultiple.php' );
		die();
	}
	
	function wpspCheckLogin(){
		include( WCE_PLUGIN_DIR.'includes/admin/wpspCheckLogin.php' );
		die();
	}
	
	/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
	 * Update 1 - Change Custom Status Color
	 * Include file required to process database change for existing custom status color change
	 */
	function setCustomStatusColor(){
		include( WCE_PLUGIN_DIR.'includes/admin/setCustomStatusColor.php' );
		die();
	}
	/* END CLOUGH I.T. SOLUTIONS MODIFICATION
	*/
	
	function getFieldsReorderSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/getFieldsReorderSettings.php' );
		die();
	}

	function setFieldsReorderSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/setFieldsReorderSettings.php' );
		die();
	}

	function getTicketListFieldSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/getTicketListFieldSettings.php' );
		die();
	}

	function setTicketListFieldSettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/setTicketListFieldSettings.php' );
		die();
	}

	function getCustomFilterFrontEnd(){
		include( WCE_PLUGIN_DIR.'includes/admin/getCustomFilterFrontEnd.php' );
		die();
	}

	function setCustomFilterFrontEnd(){
		include( WCE_PLUGIN_DIR.'includes/admin/setCustomFilterFrontEnd.php' );
		die();
	}

	function getCustomPrioritySettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/getCustomPrioritySettings.php' );
		die();
	}

	function setCustomPrioritySettings(){
		include( WCE_PLUGIN_DIR.'includes/admin/setCustomPrioritySettings.php' );
		die();
	}

	function addCustomPriority(){
		include( WCE_PLUGIN_DIR.'includes/admin/addCustomPriority.php' );
		die();
	}

	function setCustomPriorityColor(){
		include( WCE_PLUGIN_DIR.'includes/admin/setCustomPriorityColor.php' );
		die();
	}

	function deleteCustomPriority(){
		include( WCE_PLUGIN_DIR.'includes/admin/deleteCustomPriority.php' );
		die();
	}
	
	function setSubCharLength(){
		include( WCE_PLUGIN_DIR.'includes/admin/setSubCharLength.php' );
		die();
	}
	
	function getETCreateNewTicket(){
		include( WCE_PLUGIN_DIR.'includes/admin/getETCreateNewTicket.php' );
		die();
	}
	
	function setEtCreateNewTicket(){
		include( WCE_PLUGIN_DIR.'includes/admin/setEtCreateNewTicket.php' );
		die();
	}
	
	function getETReplayTicket(){
		include( WCE_PLUGIN_DIR.'includes/admin/getETReplayTicket.php' );
		die();
	}
	
	function setEtReplyTicket(){
		include( WCE_PLUGIN_DIR.'includes/admin/setEtReplyTicket.php' );
		die();
	}
	
	function getETChangeTicketStatus(){
		include( WCE_PLUGIN_DIR.'includes/admin/getETChangeTicketStatus.php' );
		die();
	}
	
	function setEtChangeTicketStatus(){
		include( WCE_PLUGIN_DIR.'includes/admin/setEtChangeTicketStatus.php' );
		die();
	}
	
	function getETAssignAgent(){
		include( WCE_PLUGIN_DIR.'includes/admin/getETAssignAgent.php' );
		die();
	}
	
	function setETAssignAgent(){
		include( WCE_PLUGIN_DIR.'includes/admin/setETAssignAgent.php' );
		die();
	}
	
	function getETDeleteTicket(){
		include( WCE_PLUGIN_DIR.'includes/admin/getETDeleteTicket.php' );
		die();
	}
	
	function setETDeleteTicket(){
		include( WCE_PLUGIN_DIR.'includes/admin/setETDeleteTicket.php' );
		die();
	}

	function setCustomStatusOrder(){
		include( WCE_PLUGIN_DIR.'includes/admin/setCustomStatusOrder.php' );
		die();
	}
	
	function setCustomPriorityOrder(){
		include( WCE_PLUGIN_DIR.'includes/admin/setCustomPriorityOrder.php' );
		die();
	}
	
	function setDateFormat(){
		include( WCE_PLUGIN_DIR.'includes/admin/setDateFormat.php' );
		die();
	}
	
	function updateCustomStatus(){
		include( WCE_PLUGIN_DIR.'includes/admin/updateCustomStatus.php' );
		die();
	}
	
	function updateCustomPriority(){
		include( WCE_PLUGIN_DIR.'includes/admin/updateCustomPriority.php' );
		die();
	}

	function getTicketRaisedByUser(){
		include( WCE_PLUGIN_DIR.'includes/admin/getTicketRaisedByUser.php' );
		die();
	}

	function setTicketRaisedByUser(){
		include( WCE_PLUGIN_DIR.'includes/admin/setTicketRaisedByUser.php' );
		die();
	}
        
        function showcanned(){
		include( WCE_PLUGIN_DIR.'includes/admin/showcanned.php' );
		die();
	}
        
        function shareCanned(){
		include( WCE_PLUGIN_DIR.'includes/admin/shareCanned.php' );
		die();
	}
        
        function getCKEditorSettings(){
                include_once( WCE_PLUGIN_DIR.'includes/admin/getCKEditorSettings.php' );
                die();
        }
        
        function setCKEditorSettings(){
                include_once( WCE_PLUGIN_DIR.'includes/admin/setCKEditorSettings.php' );
                die();
        }
        
        function wpspSubmitLinkForm(){
                include_once( WCE_PLUGIN_DIR.'includes/wpspSubmitLinkForm.php' );
                die();
        }
        function getSupportButton(){
                include( WCE_PLUGIN_DIR.'includes/admin/getSupportButton.php' );
                die();
        }
        function image_upload(){
                include( WCE_PLUGIN_DIR.'includes/admin/imageUpload.php' );
                die();
        }
        function nl2br_save_html( $string ) {
                $string = str_replace( array( "\r\n", "\r", "\n" ), "\n", $string );
                $lines = explode( "\n", $string );
                $output = '';
                foreach( $lines as $line ) {
                    $line .= '<br />';
                    $output .= $line;
                }
                return $output;
        }
        function Encrypt($data){
            return dechex(rand()).'gqlrsdvfjfhds'.decbin($data).'mtdkjsdlsjjhc'.dechex(rand());
        }
        function Decrypt($e){
            $h=substr($e, strpos($e, 'gqlrsdvfjfhds')+strlen('gqlrsdvfjfhds'),strpos($e,'mtdkjsdlsjjhc')-(strpos($e,'gqlrsdvfjfhds')+strlen('gqlrsdvfjfhds')));
            return bindec($h);
        }
        function closeTicketStatus() {
                include_once( WCE_PLUGIN_DIR.'includes/admin/closeTicketStatus.php' );
                die();
        }
        function wpsp_getCatName(){
            include( WCE_PLUGIN_DIR.'includes/admin/wpsp_getCatName.php' );
            die();
        }
        function get_cat_custom_field(){
            include( WCE_PLUGIN_DIR.'includes/admin/cat_get_custom_field.php' );
            die();
        }
        function getAddOnLicenses(){
            include( WCE_PLUGIN_DIR.'includes/licenses/getAddOnLicenses.php' );
            die();
        }
        function wpsp_act_license(){
            include( WCE_PLUGIN_DIR.'includes/licenses/wpsp_act_license.php' );
            die();
        }
        function wpsp_dact_license(){
            include( WCE_PLUGIN_DIR.'includes/licenses/wpsp_dact_license.php' );
            die();
        }
        function wpsp_check_license(){
            include( WCE_PLUGIN_DIR.'includes/licenses/wpsp_check_license.php' );
            die();
        }
}
?>
