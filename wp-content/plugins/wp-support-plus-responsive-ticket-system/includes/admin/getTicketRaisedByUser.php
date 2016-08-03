<?php 
global $wpdb;
global $current_user;
$current_user=wp_get_current_user();
$generalSettings=get_option( 'wpsp_general_settings' );
$advancedSettings=get_option( 'wpsp_advanced_settings' );

$sql="select subject,type,status,cat_id,priority,created_by,guest_name,updated_by,assigned_to
FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$_POST['ticket_id'];
$ticket = $wpdb->get_row( $sql );

$roleManage=get_option( 'wpsp_role_management' );
$guestUser = new stdClass();
$guestUser->ID = 0;
$guestUser->display_name = "Guest";
$users=array_merge(array($guestUser),get_users(array('orderby'=>'display_name')));
?>

<h3><?php echo '['.__($advancedSettings['ticket_label_alice'][1],'wp-support-plus-responsive')?> <?php echo $advancedSettings['wpsp_ticket_id_prefix'].$_POST['ticket_id'].'] '.stripcslashes(htmlspecialchars_decode($ticket->subject,ENT_QUOTES));?></h3><br>

<span class="label label-info wpsp_title_label"><?php _e('Raised By','wp-support-plus-responsive');?></span><br><br>

<select id="assignTicketRaisedById">
	<?php 
	$created_by=$ticket->created_by;
	foreach ($users as $user){
		?>
		<option <?php echo ($user->ID==$ticket->created_by)?'selected="selected"':'';?> value="<?php echo $user->ID;?>"><?php echo $user->display_name;?></option>
		<?php 
	}
	?>
</select><br><br>
<button class="btn btn-success changeTicketSubBtn" onclick="backToTicketFromIndisual();"><?php _e('Cancel','wp-support-plus-responsive');?></button>
<button class="btn btn-success changeTicketSubBtn" onclick="setRaisedByTicketUser(<?php echo $_POST['ticket_id'];?>);"><?php _e('Save Changes','wp-support-plus-responsive');?></button>
