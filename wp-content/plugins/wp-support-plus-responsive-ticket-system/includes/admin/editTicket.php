<?php
global $wpdb;
$advancedSettings=get_option( 'wpsp_advanced_settings' );
//$ticket_label= $advancedSettings['default_main_ticket_label'];
//$tickets_label= $advancedSettings['default_main_tickets_label'];
$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
$default_labels=$advancedSettingsFieldOrder['default_fields_label'];
?>
<h3><?php _e($advancedSettings['ticket_label_alice'][8],'wp-support-plus-responsive')?></h3><br>
<?php 
global $wpdb;
$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
if(isset($_REQUEST['action'])){
	//code to insert into db
	$values=array(
		'subject'=>htmlspecialchars($_REQUEST['subject'],ENT_QUOTES)
	);
	foreach ($customFields as $field){
		if(isset($_POST['cust'.$field->id]) && is_array($_POST['cust'.$field->id]))
		{
			$_POST['cust'.$field->id]=implode(",",$_POST['cust'.$field->id]);
		}
		$values['cust'.$field->id]=(isset($_POST['cust'.$field->id]))?$_POST['cust'.$field->id]:'';
	}
	$wpdb->update($wpdb->prefix.'wpsp_ticket',$values,array('id'=>$_REQUEST['id']));
	wp_redirect(admin_url('admin.php?page=wp-support-plus')); 
}

$ticket=$wpdb->get_row("select * from {$wpdb->prefix}wpsp_ticket where id=".$_REQUEST['id']);
?>
<form method="post" action="<?php echo admin_url('admin.php?page=wp-support-plus-faq&type=editTicket&action=set&noheader=true&id='.$_REQUEST['id']);?>">
	<span class="label label-info wpsp_title_label"><?php _e($default_labels['ds'],'wp-support-plus-responsive');?></span><code>*</code><br>
	<input class="wpsp_required" type="text" id="subject" name="subject" value="<?php echo $ticket->subject;?>" maxlength="80" style="width: 95%; margin-top: 10px;"/><br><br>
	<?php $i=0;
	foreach ($customFields as $field){ 
		$custom="cust".$field->id;
		if($field->required)
		{
			switch($field->field_type){
				case '1': ?>
					<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br>
					<input class="wpsp_required" type="text" name="cust<?php echo $field->id;?>" value="<?php echo $ticket->$custom?>" maxlength="80" style="width: 95%; margin-top: 10px;"/><br><br>
				<?php
				break;
				case '2': ?>
					<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br>
					<select id="cust<?php echo $field->id;?>" name="cust<?php echo $field->id;?>">
					<?php 
					if($field->field_options==NULL)
					{
						$field_options=array();
					}
					else
					{
						$field_options=unserialize($field->field_options);
					}
					foreach ($field_options as $field_option_key=>$field_option_value){
						if($ticket->$custom==$field_option_key)
						{
							$selected='selected';
						}
						else
						{
							$selected='';
						}
						echo '<option value="'.$field_option_key.'" '.$selected.'>'.$field_option_value.'</option>';
					}
					?>
					</select><br/><br/>
				<?php
				break;
				case '3': ?>
					<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br/><br>
					<?php 
					if($field->field_options==NULL)
					{
						$field_options=array();
					}
					else
					{
						$field_options=unserialize($field->field_options);
					}
					$check_values=explode(",",$ticket->$custom);
					foreach ($field_options as $field_option_key=>$field_option_value){
						if(in_array($field_option_value,$check_values))
						{
							$checked="checked";
						}
						else
						{
							$checked="";
						}
						echo '<input type="checkbox" name="cust'.$field->id.'[]" class="form-control wpsp_required" value="'.$field_option_key.'" '.$checked.'> '.$field_option_value.'<br/>';
					}
					?><br/>
				<?php
				break;
				case '4': ?>
					<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br/><br>
					<div id="cust<?php echo $field->id;?>" name="cust<?php echo $field->id;?>">
					<?php 
					if($field->field_options==NULL)
					{
						$field_options=array();
					}
					else
					{
						$field_options=unserialize($field->field_options);
					}
					foreach ($field_options as $field_option_key=>$field_option_value){
						if($ticket->$custom==$field_option_key)
						{
							$checked='checked';
						}
						else
						{
							$checked='';
						}
						echo '<input type="radio" class="form-control" name="cust'.$field->id.'" value="'.$field_option_key.'" '.$checked.' required> '.$field_option_value.'<br/>';
					}
					?></div><br/>
				<?php
				break;
				case '5': ?>
					<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br/><br/>
					<textarea class="wpsp_required" id="cust<?php echo $field->id;?>" name="cust<?php echo $field->id;?>"><?php echo $ticket->$custom?></textarea><br/><br/>
				<?php
				break;
                                case '6': ?>
                                        <span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><code>*</code><br> 
                                        <input class="wpsp_required wpsp_datepicker" type="text"  name="cust<?php echo $field->id;?>" style="width: 95%; margin-top: 10px;" value="<?php echo $ticket->$custom?>"/><br><br>
                                <?php
                                break;
			}
		}
		else
		{
			switch($field->field_type){
				case '1': ?>
					<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br>
					<input type="text" name="cust<?php echo $field->id;?>" value="<?php echo $ticket->$custom?>" maxlength="80" style="width: 95%; margin-top: 10px;"/><br><br>
				<?php 
				break;
				case '2':?>
					<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br/>
					<select id="cust<?php echo $field->id;?>" name="cust<?php echo $field->id;?>">
					<?php 
					if($field->field_options==NULL)
					{
						$field_options=array();
					}
					else
					{
						$field_options=unserialize($field->field_options);
					}
					foreach ($field_options as $field_option_key=>$field_option_value){
						if($ticket->$custom==$field_option_key)
						{
							$selected='selected';
						}
						else
						{
							$selected='';
						}
						echo '<option value="'.$field_option_key.'" '.$selected.'>'.$field_option_value.'</option>';
					}
					?>
					</select><br/><br/>
				<?php 
				break;
				case '3': ?>
					<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br/><br>
					<?php 
					if($field->field_options==NULL)
					{
						$field_options=array();
					}
					else
					{
						$field_options=unserialize($field->field_options);
					}
					$check_values=explode(",",$ticket->$custom);
					foreach ($field_options as $field_option_key=>$field_option_value){
						if(in_array($field_option_value,$check_values))
						{
							$checked="checked";
						}
						else
						{
							$checked="";
						}
						echo '<input type="checkbox" name="cust'.$field->id.'[]" class="form-control" value="'.$field_option_key.'" '.$checked.'> '.$field_option_value.'<br/>';
					}
					?><br/>
				<?php
				break;
				case '4': ?>
					<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br/><br>
					<div id="cust<?php echo $field->id;?>" name="cust<?php echo $field->id;?>">
					<?php 
					if($field->field_options==NULL)
					{
						$field_options=array();
					}
					else
					{
						$field_options=unserialize($field->field_options);
					}
					foreach ($field_options as $field_option_key=>$field_option_value){
						if($ticket->$custom==$field_option_key)
						{
							$checked='checked';
						}
						else
						{
							$checked='';
						}
						echo '<input type="radio" class="form-control" name="cust'.$field->id.'" value="'.$field_option_key.'" '.$checked.'> '.$field_option_value.'<br/>';
					}
					?></div><br/>
				<?php
				break;
				case '5': ?>
					<span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br/><br/>
					<textarea id="cust<?php echo $field->id;?>" name="cust<?php echo $field->id;?>"><?php echo $ticket->$custom?></textarea><br/><br/>
				<?php
				break;
                                case '6': ?>
                                        <span class="label label-info" style="font-size: 13px;"><?php echo $field->label;?></span><br> 
                                        <input class="wpsp_datepicker" type="text"  name="cust<?php echo $field->id;?>" style="width: 95%; margin-top: 10px;" value="<?php echo $ticket->$custom?>"/><br><br>
                                <?php
                                break;
			}
		}
	}
	?>
	<br>
	<button type="submit" class="btn btn-success">Submit</button>
</form>
<?php
add_action('admin_footer','wpsp_datepicker_add_to_footer',88000);
function wpsp_datepicker_add_to_footer(){
    $advancedSettings=get_option( 'wpsp_advanced_settings' );
    ?>
    <script>
    jQuery(document).ready(function() {
        jQuery('.wpsp_datepicker').datepicker({
            dateFormat : '<?php echo $advancedSettings['datecustfield'];?>',
            changeMonth: true,
            changeYear: true,
            yearRange: '1950:2050',                      
            defaultDate:'+0',                      
            onSelect: function (selected) {
                var dt1 = new Date(selected);
                dt1.setDate(dt1.getDate());
                jQuery(this).datepicker(dt1);
            }
        });
    });
    </script>
    <?php
}
?>

