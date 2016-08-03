<?php 
global $wpdb,$current_user;
$current_user=wp_get_current_user();

$advancedSettings=get_option( 'wpsp_advanced_settings' );
$emailSettings=get_option( 'wpsp_email_notification_settings' );
$wpsp_et_change_ticket_status=get_option( 'wpsp_et_change_ticket_status' );

$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
$default_labels=$advancedSettingsFieldOrder['default_fields_label'];


$headers = array("Content-Type: text/html;charset=utf-8");
$headers[] = 'From: ' . $emailSettings['default_from_name'] . ' <' . $emailSettings['default_from_email'] . '>';
if ( isset( $emailSettings['default_reply_to']) && $emailSettings['default_reply_to'] != '' ) {
    $headers[] = 'Reply-To: ' .  $emailSettings['default_reply_to'];
}
add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));

/*
 * prepare email templete mail
 */

$et_success_staff_subject='['.__($advancedSettings['ticket_label_alice'][1],'wp-support-plus-responsive').' '.$advancedSettings['wpsp_ticket_id_prefix'].$_POST['ticket_id'].']'.' '.stripslashes($wpsp_et_change_ticket_status['mail_subject']);
$et_staff_body=stripslashes($wpsp_et_change_ticket_status['mail_body']);

$wpsp_open_ticket_page_url=get_permalink(get_option( 'wpsp_ticket_open_page_shortcode' ));
$wpsp_open_ticket_page_url.='?ticket_id='.$this->Encrypt($_POST['ticket_id']);

$sql="select name FROM {$wpdb->prefix}wpsp_catagories WHERE id=".$_POST['category'];
$category = $wpdb->get_row( $sql );

$sql="select * FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$_POST['ticket_id'];
$ticket = $wpdb->get_row( $sql );

$sql="select body FROM {$wpdb->prefix}wpsp_ticket_thread WHERE ticket_id=".$_POST['ticket_id'].' ORDER BY create_time ASC';
$thread=$wpdb->get_row($sql);

$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
$etCustomField=array();
foreach ($customFields as $field){
    $cust_alice='cust'.$field->id;
    if ($field->field_type=='5'){
        $etCustomField['cust'.$field->id]=nl2br($ticket->$cust_alice);
    }
    else {
        $etCustomField['cust'.$field->id]=$ticket->$cust_alice;
    }
}

$customerName='';
$customerEmail='';
if($ticket->created_by){
    $user=get_userdata($ticket->created_by);
    $customerName=$user->display_name;
    $customerEmail=$user->user_email;
}
else {
    $customerName=$ticket->guest_name;
    $customerEmail=$ticket->guest_email;
}

$description=stripcslashes(htmlspecialchars_decode($thread->body,ENT_QUOTES));
foreach ($wpsp_et_change_ticket_status['templates'] as $et_key=>$et_val){
    switch ($et_key){
        case 'ticket_status':
            $et_success_staff_subject = str_replace('{ticket_status}', __(ucfirst($_POST['status']),'wp-support-plus-responsive'), $et_success_staff_subject);
            $et_staff_body = str_replace('{ticket_status}', __(ucfirst($_POST['status']),'wp-support-plus-responsive'), $et_staff_body);
            break;
        case 'customer_name':
            $et_success_staff_subject = str_replace('{customer_name}', $customerName, $et_success_staff_subject);
            $et_staff_body = str_replace('{customer_name}', $customerName, $et_staff_body);
            break;
        case 'customer_email':
            $et_success_staff_subject = str_replace('{customer_email}', $customerEmail, $et_success_staff_subject);
            $et_staff_body = str_replace('{customer_email}', $customerEmail, $et_staff_body);
            break;
        case 'ticket_id':
            $et_success_staff_subject = str_replace('{ticket_id}', $ticket->id, $et_success_staff_subject);
            $et_staff_body = str_replace('{ticket_id}', $ticket->id, $et_staff_body);
            break;
        case 'ticket_subject':
            $et_success_staff_subject = str_replace('{ticket_subject}', stripcslashes(htmlspecialchars_decode($ticket->subject,ENT_QUOTES)), $et_success_staff_subject);
            $et_staff_body = str_replace('{ticket_subject}', stripcslashes(htmlspecialchars_decode($ticket->subject,ENT_QUOTES)), $et_staff_body);
            break;
        case 'ticket_description':
            $et_success_staff_subject = str_replace('{ticket_description}', $description, $et_success_staff_subject);
            $et_staff_body = str_replace('{ticket_description}', $description, $et_staff_body);
            break;
        case 'ticket_category':
            $et_success_staff_subject = str_replace('{ticket_category}', __($category->name,'wp-support-plus-responsive'), $et_success_staff_subject);
            $et_staff_body = str_replace('{ticket_category}', __($category->name,'wp-support-plus-responsive'), $et_staff_body);
            break;
        case 'ticket_priotity':
            $et_success_staff_subject = str_replace('{ticket_priotity}', __($_POST['priority'],'wp-support-plus-responsive'), $et_success_staff_subject);
            $et_staff_body = str_replace('{ticket_priotity}', __($_POST['priority'],'wp-support-plus-responsive'), $et_staff_body);
            break;
        case 'updated_by':
            $et_success_staff_subject = str_replace('{updated_by}', $current_user->display_name, $et_success_staff_subject);
            $et_staff_body = str_replace('{updated_by}', $current_user->display_name, $et_staff_body);
            break;
        case 'ticket_url':
            $et_staff_body = str_replace('{ticket_url}', $wpsp_open_ticket_page_url, $et_staff_body);
            break;
        case 'time_created':
            $et_success_staff_subject = str_replace('{time_created}', $ticket->create_time, $et_success_staff_subject);
            $et_staff_body = str_replace('{time_created}', $ticket->create_time, $et_staff_body);
            break;
        default:
            break;
    }
}
foreach ($etCustomField as $etFieldKey=>$etFieldVal){
    $et_success_staff_subject = str_replace('{'.$etFieldKey.'}', $etFieldVal, $et_success_staff_subject);
    $et_staff_body = str_replace('{'.$etFieldKey.'}', $etFieldVal, $et_staff_body);
}

/*
 * update settings for ticket
 */
$values=array(
    'status'=>$_POST['status'],
    'cat_id'=>$_POST['category'],
    'priority'=>$_POST['priority'],
    'update_time'=>current_time('mysql', 1),
    'updated_by'=>$current_user->ID,
    'ticket_type'=>$_POST['ticket_type']
);
$wpdb->update($wpdb->prefix.'wpsp_ticket',$values,array('id'=>$_POST['ticket_id']));


/*
 * Send Email based on email template settings
 */
$to=array();

$piping_emails=array();
if($emailSettings['enable_email_pipe'] && $emailSettings['piping_type']=='cpanel'){
    $piping_mail=$emailSettings['default_reply_to'];
    if(!$emailSettings['default_reply_to']){
        $piping_mail=$emailSettings['default_from_email'];
    }
    $piping_emails[]=$piping_emails;
} else if($emailSettings['enable_email_pipe'] && $emailSettings['piping_type']=='imap'){
    $imap_pipe_list=get_option( 'wpsp_imap_pipe_list' );
    foreach ($imap_pipe_list as $pipe_connection){
        $piping_emails[]=$pipe_connection['pipe_email'];
    }
}

if($wpsp_et_change_ticket_status['notify_to']['customer'] && $current_user->user_email!=$customerEmail){
    $to[]=$customerEmail;
}

if($wpsp_et_change_ticket_status['notify_to']['assigned_agent'] && $ticket->assigned_to != '0'){
    $assigned_users=explode(',', $ticket->assigned_to);
    if (!$to){
        foreach ($assigned_users as $user){
            $userdata=get_userdata($user);
            if($current_user->user_email!=$userdata->user_email){
                $to[] = $userdata->user_email;
            }
        }
    }
    else {
        foreach ($assigned_users as $user){
            $userdata=get_userdata($user);
            if($current_user->user_email!=$userdata->user_email && !(array_search($userdata->user_email, $piping_emails)>-1)){
                $headers[] = " Bcc:" . $userdata->user_email;
            }
        }
    }
}

$administrator_emails=explode("\n",$emailSettings['administrator_emails']);
if($wpsp_et_change_ticket_status['notify_to']['administrator']){
    if($administrator_emails && !$to){
        $to=$administrator_emails;
    }
    else if($administrator_emails){
        foreach ($administrator_emails as $admin_email){
            if($current_user->user_email != $admin_email && !(array_search($admin_email, $piping_emails)>-1)){
                $headers[] = " Bcc:" . $admin_email;
            }
        }
    }
}

$roleManage=get_option( 'wpsp_role_management' );

if($wpsp_et_change_ticket_status['notify_to']['supervisor']){
    $supervisors=array();
    $supervisors=array_merge($supervisors,get_users(array('orderby'=>'display_name','role'=>'wp_support_plus_supervisor')));
    foreach($roleManage['supervisors'] as $supervisorRole)
    {
        $supervisors=array_merge($supervisors,get_users(array('orderby'=>'display_name','role'=>$supervisorRole)));
    }
    $supervisors_emails=array();
    foreach ($supervisors as $supervisor){
        $supervisors_emails[]=$supervisor->user_email;
    }

    if($supervisors_emails && !$to){
        $to=$supervisors_emails;
    }
    else if($supervisors_emails){
        foreach ($supervisors_emails as $supervisor_email){
            if($current_user->user_email!=$supervisor_email && !(array_search($supervisor_email, $piping_emails)>-1)){
                $headers[] = " Bcc:" . $supervisor_email;
            }
        }
    }
}

if($wpsp_et_change_ticket_status['notify_to']['all_agents']){
    $agents=array();
    $agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>'wp_support_plus_agent')));
    foreach($roleManage['agents'] as $agentRole)
    {
        $agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>$agentRole)));
    }

    $agents_emails=array();
    foreach ($agents as $agent){
        $agents_emails[]=$agent->user_email;
    }

    if($agents_emails && !$to){
        $to=$agents_emails;
    }
    else if($agents_emails){
        foreach ($agents_emails as $agents_email){
            if($current_user->user_email!=$agents_email && !(array_search($agents_email, $piping_emails)>-1)){
                $headers[] = " Bcc:" . $agents_email;
            }
        }
    }
}


foreach ($to as $key=>$val){
    if(array_search($val, $piping_emails)>-1){
        unset($to[$key]);
    }
}

if($to && ( !isset( $_POST['notify'] ) || ( isset( $_POST['notify']) && $_POST['notify'] == '1' ) )){
    wp_mail($to,$et_success_staff_subject,$et_staff_body,$headers);
    add_filter('wp_mail_content_type',create_function('', 'return "text/plain"; '));
}
/* END CLOUGH I.T. SOLUTIONS MODIFICATION
 */



?>
