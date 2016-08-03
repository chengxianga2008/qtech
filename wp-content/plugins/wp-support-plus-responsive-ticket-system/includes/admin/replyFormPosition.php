<form id="frmThreadReply" onsubmit="replyTicket(event,this);">
	<div id="theadReplyContainer">
		<textarea id="replyBody" name="replyBody"></textarea>
		<?php
		/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
	         * Update 13 - reply additional recipients
	         */
	        ?>
		<div id="replyFloatedContainer">
		    <div class="replyCC" id="wpsp_replycc">
		        <span class="label label-info wpsp_title_label"><?php _e('CC:','wp-support-plus-responsive');?></span> (<?php _e('Comma separated list','wp-support-plus-responsive');?>)<br>
		        <input type="text" name="reply_cc" id="reply_cc" />
		    </div>
		    <div class="replyCC" id="wpsp_replybcc">
		        <span class="label label-info wpsp_title_label"><?php _e('BCC:','wp-support-plus-responsive');?></span> (<?php _e('Comma separated list','wp-support-plus-responsive');?>)<br>
		        <input type="text" name="reply_bcc" id="reply_bcc" />
		    </div>
		</div>
		<?php
	        /* EMD CLOUGH I.T. SOLUTIONS MODIFICATION
	         */
	        ?>
		<div id="replyFloatedContainer">
			<div class="replyFloatLeft wpsp_reply" id="wpsp_status_reply">
				<span class="label label-info wpsp_title_label"><?php _e('Status','wp-support-plus-responsive');?></span><br>
				<select id="reply_ticket_status" name="reply_ticket_status">
					<?php
					$sql_status="select * from {$wpdb->prefix}wpsp_custom_status";
					$custom_statusses=$wpdb->get_results($sql_status);
					$advancedSettingsStatusOrder=get_option( 'wpsp_advanced_settings_status_order' );
					if(isset($advancedSettingsStatusOrder['status_order'])){
						if(is_array($advancedSettingsStatusOrder['status_order']))
						{
							$custom_statusses=array();
							foreach($advancedSettingsStatusOrder['status_order'] as $status_id)
							{
								$sql="select * from {$wpdb->prefix}wpsp_custom_status WHERE id=".$status_id." ";	
								$status_data=$wpdb->get_results($sql);
								foreach($status_data as $status)
								{
									$custom_statusses=array_merge($custom_statusses,array($status));
								}
							}
						}
					}
					foreach($custom_statusses as $custom_status){?>
						<option value="<?php echo strtolower($custom_status->name);?>" <?php echo ($ticket->status==strtolower($custom_status->name))?'selected="selected"':'';?>><?php _e(ucfirst($custom_status->name),'wp-support-plus-responsive');?></option>
					<?php
					}
					?>
				</select>
			</div>
			<?php
			if(in_array("dc",$advancedSettingsFieldOrder['display_fields']))
			{
			?>
			<div class="replyFloatLeft wpsp_reply" id="wpsp_category_reply">
				<span class="label label-info wpsp_title_label"><?php _e($default_labels['dc'],'wp-support-plus-responsive');?></span><br>
				<select id="reply_ticket_category" name="reply_ticket_category">
					<?php 
					foreach ($categories as $category){
						$selected=($category->id==$ticket->cat_id)?'selected="selected"':'';
						echo '<option value="'.$category->id.'" '.$selected.'>'.stripcslashes($category->name).'</option>';
					}
					?>
				</select>
			</div>
			<?php
			}
			else{
			?><input type="hidden" name="reply_ticket_category" id="reply_ticket_category" value="<?php echo $ticket->cat_id;?>"><?php
			}
			if(in_array("dp",$advancedSettingsFieldOrder['display_fields']))
			{
			?>
			<div class="replyFloatLeft wpsp_reply" id="wpsp_priority_reply">
				<span class="label label-info wpsp_title_label"><?php _e($default_labels['dp'],'wp-support-plus-responsive');?></span><br>
				<select id="reply_ticket_priority" name="reply_ticket_priority">
					<?php 
					foreach ($priorities as $priority){
					?>
						<option value="<?php echo strtolower($priority->name);?>" <?php echo ($ticket->priority==strtolower($priority->name))?'selected="selected"':'';?>><?php _e($priority->name,'wp-support-plus-responsive');?></option>
					<?php
					}
					?>
				</select>
			</div>
			<?php
			}
			else{
			?><input type="hidden" name="reply_ticket_priority" id="reply_ticket_priority" value="<?php echo $ticket->priority;?>"><?php
			}
			if(in_array("da",$advancedSettingsFieldOrder['display_fields']))
			{
			?>
			<div class="replyFloatLeft wpsp_reply">
				<span class="label label-info wpsp_title_label"><?php _e($default_labels['da'],'wp-support-plus-responsive');?></span><br>
				<input id="wpsp_reply_attachment" type="file" name="attachment[]" multiple>
			</div>
			<?php
			}?>
			<input type="hidden" name="action" value="replyTicket">
			<input type="hidden" name="ticket_id" value="<?php echo $_POST['ticket_id'];?>">
			<input type="hidden" name="user_id" value="<?php echo $current_user->ID;?>">
			<input type="hidden" name="type" value="user">
			<input type="hidden" name="guest_name" value="">
			<input type="hidden" name="guest_email" value="">
			<?php
			/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
			 * Update 15 - add note (no notifications)
			 */
			if($current_user->has_cap('manage_support_plus_ticket')){
			?>
				<input type="hidden" name="notify" value="true">
				<input type="button" id="wpsp_add_note_btn" class="btn btn-success replyFloatRight" value="<?php _e('Add Note','wp-support-plus-responsive');?>" onClick="addNote()" />
			<?php
			}
			/* EMD CLOUGH I.T. SOLUTIONS MODIFICATION
			 */
			?>
			<input type="submit" id="wpsp_submit_reply_btn" class="btn btn-success replyFloatRight" value="<?php _e('Submit Reply','wp-support-plus-responsive');?>">
		</div>
	</div>
</form>