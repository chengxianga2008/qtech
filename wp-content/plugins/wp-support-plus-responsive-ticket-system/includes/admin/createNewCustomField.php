<?php 
global $wpdb;
$cu = wp_get_current_user();
if ($cu->has_cap('manage_options')) {
	$field_options=$_POST['field_options'];
	$field_options=explode("\n",$field_options);
	$field_options_array=array();
	if(is_array($field_options) && count($field_options)>0)
	{
		foreach($field_options as $field_option)
		{
			$field_options_array=array_merge($field_options_array,array($field_option=>$field_option));
		}
	}
        if(empty($_POST['field_categories'])){
            $field_categories='0';
            echo $field_categories;
        }
        else{
            $field_categories=implode(',', $_POST['field_categories']);
        }
	$values=array('label'=>$_POST['label'],'required'=>$_POST['required'],'field_type'=>$_POST['field_type'],'field_options'=>serialize($field_options_array),'field_categories'=>$field_categories);
	$wpdb->insert($wpdb->prefix.'wpsp_custom_fields',$values);
	$last_id=$wpdb->insert_id;
	
	$sql = "alter table {$wpdb->prefix}wpsp_ticket ADD cust".$last_id." TEXT CHARACTER SET utf8 COLLATE utf8_general_ci";
	$wpdb->query($sql);
	
	$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
	if(isset($advancedSettingsFieldOrder['fields_order']) && $advancedSettingsFieldOrder['fields_order']){
		$advancedSettingsFieldOrder['fields_order']=array_merge($advancedSettingsFieldOrder['fields_order'],array($last_id));
		update_option('wpsp_advanced_settings_field_order',$advancedSettingsFieldOrder);
	}

	$advancedSettingsTicketList=get_option( 'wpsp_advanced_settings_ticket_list_order' );
	if(isset($advancedSettingsTicketList['backend_ticket_list']) && $advancedSettingsTicketList['backend_ticket_list']){
		$advancedSettingsTicketList['backend_ticket_list']=$advancedSettingsTicketList['backend_ticket_list'] + array($last_id=>0);
		$advancedSettingsTicketList['frontend_ticket_list']=$advancedSettingsTicketList['frontend_ticket_list'] + array($last_id=>0);
		update_option('wpsp_advanced_settings_ticket_list_order',$advancedSettingsTicketList);
	}
	
	$wpsp_et_create_new_ticket=get_option( 'wpsp_et_create_new_ticket' );
	$wpsp_et_create_new_ticket['templates']['cust'.$last_id]=$_POST['label'];
	update_option('wpsp_et_create_new_ticket',$wpsp_et_create_new_ticket);
	
	$wpsp_et_reply_ticket=get_option( 'wpsp_et_reply_ticket' );
	$wpsp_et_reply_ticket['templates']['cust'.$last_id]=$_POST['label'];
	update_option('wpsp_et_reply_ticket',$wpsp_et_reply_ticket);
}
?>
