<?php 
global $wpdb;
$emailSettings=get_option( 'wpsp_email_notification_settings' );
$categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_catagories" );
?>
<br>
<span class="label label-info wpsp_title_label"><?php _e('Mail Settings','wp-support-plus-responsive');?></span><br><br>
<table id="tblEmailFrom">
  <tr>
    <td><?php _e('From Email:','wp-support-plus-responsive');?></td>
    <td><input type="text" id="txtFromEmail" value="<?php echo $emailSettings['default_from_email'];?>" /></td>
  </tr>
  <tr>
    <td><?php _e('From Name:','wp-support-plus-responsive');?></td>
    <td><input type="text" id="txtFromName" value="<?php echo $emailSettings['default_from_name'];?>"/></td>
  </tr>
  <tr>
    <td><?php _e('Reply To:','wp-support-plus-responsive');?></td>
    <td><input type="text" id="wpsp_txtReplyTo" value="<?php echo $emailSettings['default_reply_to'];?>"/></td>
  </tr>
</table>

<hr>
<span class="label label-info wpsp_title_label"><?php _e('Administrator Notifications','wp-support-plus-responsive');?></span><br><br>
<table>
  <tr>
    <td><?php _e('Administrator Emails :','wp-support-plus-responsive');?></td>
    <td><textarea id="adminEmails" rows="3" cols="30"><?php echo $emailSettings['administrator_emails'];?></textarea></td>
  </tr>
</table>
<small><code>*</code><?php _e('Please add one email address per line. These email addresses will receive administrator email notifications','wp-support-plus-responsive');?></small><br><br>
<?php
if(class_exists('WPSupportPlusEmailPipe')){
    include( WPSP_PIPE_PLUGIN_DIR.'includes/admin/getEmailNotificationSettings.php' );
}
?>
<hr>
<button class="btn btn-success" onclick="setEmailSettings();"><?php _e('Save Settings','wp-support-plus-responsive');?></button>

