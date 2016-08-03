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
	$field_categories=  implode(',', $_POST['field_categories_update']);
	$values=array('label'=>$_POST['label'],'required'=>$_POST['required'],'field_type'=>$_POST['field_type'],'field_options'=>serialize($field_options_array),'field_categories'=>$field_categories);
	$wpdb->update($wpdb->prefix.'wpsp_custom_fields',$values,array('id'=>$_POST['field_id']));
       
        $wpsp_et_create_new_ticket=get_option('wpsp_et_create_new_ticket');
        $wpsp_et_create_new_ticket['templates']['cust'.$_POST['field_id']]=$_POST['label'];
        update_option('wpsp_et_create_new_ticket',$wpsp_et_create_new_ticket);
        
        $wpsp_et_reply_ticket=get_option( 'wpsp_et_reply_ticket' );
        $wpsp_et_reply_ticket['templates']['cust'.$_POST['field_id']]=$_POST['label'];
        update_option('wpsp_et_reply_ticket',$wpsp_et_reply_ticket);
}
?>
