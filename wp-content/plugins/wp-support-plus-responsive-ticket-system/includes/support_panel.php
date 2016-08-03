<?php 
global $wpdb;
add_thickbox();
$sql="select * from {$wpdb->prefix}wpsp_panel_custom_menu";
$menus=$wpdb->get_results($sql);
?>

<div id="support_panel_title">
	<img class="support_panel_icon" src="<?php echo $UploadImageSettings['panel_image'];?>" >
	<div id="support_panel_title_text"><?php echo $generalSettings['support_title'];?></div>
	<img id="support_panel_close" src="<?php echo WCE_PLUGIN_URL.'asset/images/close.gif';?>" >
</div>
<?php if($generalSettings['support_phone_number']){?>
<div id="support_call_phone_number" class="front_support_menu">
	<img class="support_panel_icon" src="<?php echo WCE_PLUGIN_URL.'asset/images/call.png';?>" >
	<div class="support_panel_menu_text"><?php echo __($generalSettings['support_phone_number'],'wp-support-plus-responsive');?></div>
</div>
<?php }?>
<?php if($generalSettings['display_skype_chat']){?>
	<a href="#TB_inline?width=300&height=300&inlineId=support_skype_chat_body" title="<?php _e('Online Skype Chat Agents','wp-support-plus-responsive');?>" class="thickbox">
		<div id="support_skype_chat" class="front_support_menu">
			<img class="support_panel_icon" src="<?php echo WCE_PLUGIN_URL.'asset/images/Skype-icon.png';?>" >
			<div class="support_panel_menu_text"><?php _e('Skype Chat','wp-support-plus-responsive');?></div>
		</div>
	</a>
	<div id="support_skype_chat_body" style="display:none;">
        <div>
	        <div id="supportChatContainer"></div>
	        <div class="wait">
	        	<img alt="Please Wait" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>">
	        </div>
        </div>
	</div>
	<script type="text/javascript">
		checkSkypeOnlineAgentForChat();
	</script>
<?php }?>
<?php if($generalSettings['display_skype_call']){?>
	<a href="#TB_inline?width=300&height=300&inlineId=support_skype_call_body" title="<?php _e('Online Skype Call Agents','wp-support-plus-responsive');?>" class="thickbox">
		<div id="support_skype_call" class="front_support_menu">
				<img class="support_panel_icon" src="<?php echo WCE_PLUGIN_URL.'asset/images/skype_phone.png';?>" >
				<div class="support_panel_menu_text"><?php _e('Skype Call','wp-support-plus-responsive');?></div>
		</div>
	</a>
	<div id="support_skype_call_body" style="display:none;">
		<div>
	        <div id="supportCallContainer"></div>
	        <div class="wait">
	        	<img alt="Please Wait" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>">
	        </div>
	    </div>
	</div>
	<script type="text/javascript">
		checkSkypeOnlineAgentForCall();
	</script>
<?php }?>
<?php foreach ($menus as $menu){?>
	<a href="<?php echo $menu->redirect_url;?>" >
		<div class="front_support_menu">
			<img class="support_panel_icon" src="<?php echo $menu->menu_icon;?>" >
			<div class="support_panel_menu_text"><?php echo $menu->menu_text;?></div>
		</div>
	</a>
<?php }?>
<div id="support_page_redirect" class="front_support_menu">
	<img class="support_panel_icon" src="<?php echo WCE_PLUGIN_URL.'asset/images/support-icon.png';?>" >
	<div class="support_panel_menu_text"><?php _e('Support Ticket','wp-support-plus-responsive');?></div>
</div>
