<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
global $wpdb;
$categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_catagories" );
$roleManage=get_option( 'wpsp_role_management' );
$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
$default_labels=$advancedSettingsFieldOrder['default_fields_label'];
$agents=array();
$agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>'wp_support_plus_agent')));
$agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>'wp_support_plus_supervisor')));
$agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>'administrator')));
foreach($roleManage['agents'] as $agentRole)
{
	$agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>$agentRole)));
}
foreach($roleManage['supervisors'] as $supervisorRole)
{
	$agents=array_merge($agents,get_users(array('orderby'=>'display_name','role'=>$supervisorRole)));
}
?>
<div id="catDisplayTableContainer" class="table-responsive">
	<table class="table table-striped">
		<tr>
			<th><?php _e($default_labels['dc'].' Name','wp-support-plus-responsive');?></th>
			<th><?php _e('Default Assignee','wp-support-plus-responsive');?></th>
			<th><?php _e('Action','wp-support-plus-responsive');?></th>
		</tr>
		<?php foreach ($categories as $category){?>
			<tr>
				<td><?php _e(stripslashes($category->name),'wp-support-plus-responsive');?></td>
				<?php 
				if($category->default_assignee!='0')
				{
					$cat_users=explode(',', $category->default_assignee);
					$u_display_names=array();
					foreach ($cat_users as $user){
						$userdata=get_userdata($user);
						$u_display_names[]=$userdata->display_name;
					}
					?>
					<td><?php echo implode(',',$u_display_names);?></td>
				<?php
				}
				else
				{?>
					<td><?php _e('None','wp-support-plus-responsive');?></td>
				<?php
				}?>				
				<td>
					<img alt="Edit" onclick="editCategory(<?php echo $category->id;?>,'<?php echo $category->default_assignee;?>');" class="catEdit" title="<?php _e('Edit','wp-support-plus-responsive');?>" src="<?php echo WCE_PLUGIN_URL.'asset/images/edit.png';?>"/>
                                        <?php if($category->id!=1){?>
                                            <img alt="Delete" onclick="deleteCategory(<?php echo $category->id;?>);" class="catDelete" title="<?php _e('Delete','wp-support-plus-responsive');?>" src="<?php echo WCE_PLUGIN_URL.'asset/images/delete.png';?>" />
					<?php }?>
				</td>
			</tr>
		<?php }?>
	</table>
</div>
<div id="createCategoryContainer">
	<input id="newCatName" class="form-control" type="text" placeholder="<?php _e('Enter '.$default_labels['dc'].' Name','wp-support-plus-responsive');?>" >
	<span><?php _e('Default Assignee','wp-support-plus-responsive');?></span>
	<select id="setCatAgent" multiple="multiple">
		<?php 
		foreach ($agents as $agent){
			echo '<option value="'.$agent->ID.'">'.$agent->data->display_name.'</option>';
		}
		?>
			
	</select><br/><br/>
	<button class="btn btn-success" onclick="createNewCategory();"><?php _e('Create New '.$default_labels['dc'],'wp-support-plus-responsive');?></button>
</div>
<div id="editCategoryContainer">
	<input type="hidden" id="editCatID" value="">
	<input id="editCatName" class="form-control" type="text" >
	<span><?php _e('Default Assignee','wp-support-plus-responsive');?></span>
	<select id="editCatAgent" multiple="multiple">
		<?php 
		foreach ($agents as $agent){
			echo '<option value="'.$agent->ID.'">'.$agent->data->display_name.'</option>';
		}
		?>
		<?php 
		if($category->default_assignee!='0')
				{
					$cat_users=explode(',', $category->default_assignee);
					$u_display_names=array();
					foreach ($cat_users as $user){
						$userdata=get_userdata($user);
						$u_display_names[]=$userdata->display_name;
					}
				?>
				<?php echo implode(',',$u_display_names);?>
				<?php
				}
				else
				{?>
					<?php _e('None','wp-support-plus-responsive');?>
				<?php
				}?>
				
	</select><br/><br/>
	<button onclick="updateCategory();" class="btn btn-success"><?php _e('Update '.$default_labels['dc'],'wp-support-plus-responsive');?></button>
</div>
