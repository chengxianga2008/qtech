<?php 
global $wpdb;
global $current_user;
$current_user=wp_get_current_user();
$emailSettings=get_option( 'wpsp_email_notification_settings' );
$generalSettings=get_option( 'wpsp_general_settings' );
$wpsp_et_change_ticket_assign_agent=get_option( 'wpsp_et_change_ticket_assign_agent' );

$advancedSettings=get_option( 'wpsp_advanced_settings' );

/*****************************************************/
$sql="select * FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$_POST['ticket_id'];
$ticket = $wpdb->get_row( $sql );
/*****************************************************/
$ticket_created_by = $_POST['user_id'];
if($ticket_created_by==0){
	$ticket_type="guest";
}
else{
	$ticket_type="user";
}
$values=array(
		'created_by'=>$ticket_created_by,
		'update_time'=>current_time('mysql', 1),
		'updated_by'=>$current_user->ID,
		'type'=>$ticket_type
);
$wpdb->update($wpdb->prefix.'wpsp_ticket',$values,array('id'=>$_POST['ticket_id']));
die();
?>