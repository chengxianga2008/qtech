<?php 
global $wpdb;
global $current_user;
$current_user=wp_get_current_user();
$generalSettings=get_option( 'wpsp_general_settings' );
$advancedSettings=get_option( 'wpsp_advanced_settings' );
//$ticket_label= $advancedSettings['default_main_ticket_label'];
//$tickets_label= $advancedSettings['default_main_tickets_label'];

$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );
$default_labels=$advancedSettingsFieldOrder['default_fields_label'];

if(!is_numeric($_POST['ticket_id'])) die(); //sql injection

$sql="select subject,type,status,cat_id,priority,created_by,guest_name,extension_meta
		FROM {$wpdb->prefix}wpsp_ticket WHERE id=".$_POST['ticket_id'];
$ticket = $wpdb->get_row( $sql );
/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
 * Update 4 - Display thread date time
 * get create_time field
 */ 
$sql="select id,body,attachment_ids,created_by,guest_name,guest_email,create_time,
		TIMESTAMPDIFF(MONTH,create_time,UTC_TIMESTAMP()) as date_modified_month,
		TIMESTAMPDIFF(DAY,create_time,UTC_TIMESTAMP()) as date_modified_day,
		TIMESTAMPDIFF(HOUR,create_time,UTC_TIMESTAMP()) as date_modified_hour,
 		TIMESTAMPDIFF(MINUTE,create_time,UTC_TIMESTAMP()) as date_modified_min,
 		TIMESTAMPDIFF(SECOND,create_time,UTC_TIMESTAMP()) as date_modified_sec,
		is_note as note 
		FROM {$wpdb->prefix}wpsp_ticket_thread WHERE ticket_id=".$_POST['ticket_id'].' ORDER BY create_time ' ;
if($advancedSettings['wpsp_reply_form_position']==0){
    $sql.='ASC';
} else {
    $sql.='DESC';
}
/* END CLOUGH I.T. SOLUTIONS MODIFICATION
 */ 
$threads= $wpdb->get_results( $sql );
$categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_catagories" );
$priorities = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_priority" );
$advancedSettingsPriorityOrder=get_option( 'wpsp_advanced_settings_priority_order' );
if(isset($advancedSettingsPriorityOrder['priority_order'])){
	if(is_array($advancedSettingsPriorityOrder['priority_order']))
	{
		$priorities=array();
		foreach($advancedSettingsPriorityOrder['priority_order'] as $priority_id)
		{
			$sql="select * from {$wpdb->prefix}wpsp_custom_priority WHERE id=".$priority_id." ";
			$priority_data=$wpdb->get_results($sql);
			foreach($priority_data as $priority)
			{
				$priorities=array_merge($priorities,array($priority));
			}
		}
	}
}

$advancedSettingsFieldOrder=get_option( 'wpsp_advanced_settings_field_order' );

$customFields = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsp_custom_fields" );
$total_cust_field=$wpdb->num_rows;
?>

<button class="btn btn-primary wpsp_ticket_nav_btn" onclick="backToTicketFromIndisual();"><?php _e($advancedSettings['ticket_label_alice'][17],'wp-support-plus-responsive');?></button>
<button class="btn btn-primary wpsp_ticket_nav_btn" onclick="getChangeTicketStatus(<?php echo $_POST['ticket_id'];?>);"><?php _e('Change Status','wp-support-plus-responsive');?></button>
<button class="btn btn-primary wpsp_ticket_nav_btn" id="psmwpsp_canned" onclick="cannedrep();"><?php _e('Canned Reply','wp-support-plus-responsive');?></button>
<?php if(!$generalSettings['close_ticket_btn_status_val']=='' && $generalSettings['close_ticket_btn_status_val']!=$ticket->status){?>
    <button id="wpsp_close_btn_action" class="btn btn-primary wpsp_ticket_nav_btn" onclick="wpsp_closeTicketStatus(<?php echo $_POST['ticket_id'];?>,'<?php echo $generalSettings['close_ticket_btn_status_val'];?>');" > <?php echo $generalSettings['close_btn_alice'];?></button>
<?php }?>
<button id="wpsp_slide_demo" class="btn btn-primary wpsp_ticket_nav_btn"><?php _e('+More Actions','wp-support-plus-responsive');?></button>
<div id="wpsp_show_more">  
    <button class="btn btn-primary wpsp_ticket_nav_btn" id="clone" onclick="cloneTicket(<?php echo $_POST['ticket_id'];?>);"><?php _e('Clone Ticket','wp-support-plus-responsive');?></button>
    <?php
    if(current_user_can( 'manage_options' )){
            ?><button class="btn btn-primary wpsp_ticket_nav_btn" onclick="getRaisedByTicketUser(<?php echo $_POST['ticket_id'];?>);"><?php _e('Change Raised By','wp-support-plus-responsive');?></button>&nbsp;<?php
    }
    else{
            foreach($advancedSettings['modify_raised_by'] as $modifyRaisedBy){
                    if((($modifyRaisedBy == 'wp_support_plus_agent') && $current_user->has_cap('manage_support_plus_ticket')) || (($modifyRaisedBy == 'wp_support_plus_supervisor') && $current_user->has_cap('manage_support_plus_agent'))){
                            ?><button class="btn btn-primary wpsp_ticket_nav_btn" onclick="getRaisedByTicketUser(<?php echo $_POST['ticket_id'];?>);"><?php _e('Change Raised By','wp-support-plus-responsive');?></button>&nbsp;<?php
                    }
            }   
    }
    ?>                            
    <?php if($current_user->has_cap('manage_support_plus_agent')){?>
    <button class="btn btn-primary wpsp_ticket_nav_btn"onclick="assignAgent(<?php echo $_POST['ticket_id'];?>);"><?php _e('Assign Agent','wp-support-plus-responsive');?></button>
    <button class="btn btn-danger wpsp_ticket_nav_btn" onclick="deleteTicket(<?php echo $_POST['ticket_id'];?>);"><?php _e($advancedSettings['ticket_label_alice'][10],'wp-support-plus-responsive');?></button>
    <?php }
    else if(!$current_user->has_cap('manage_support_plus_agent') && $current_user->has_cap('manage_support_plus_ticket') && $generalSettings['allow_agents_to_assign_tickets']==1){?>
    <button class="btn btn-primary wpsp_ticket_nav_btn"onclick="assignAgent(<?php echo $_POST['ticket_id'];?>);"><?php _e('Assign Agent','wp-support-plus-responsive');?></button>
    <?php }
    if(!$current_user->has_cap('manage_support_plus_agent')){?>
    <button class="btn btn-primary wpsp_ticket_nav_btn"><?php _e('Demo','wp-support-plus-responsive');?></button>
    <?php }
    if(!$current_user->has_cap('manage_support_plus_agent') && $current_user->has_cap('manage_support_plus_ticket') && $generalSettings['allow_agents_to_delete_tickets']==1){?>
    <button class="btn btn-danger wpsp_ticket_nav_btn" onclick="deleteTicket(<?php echo $_POST['ticket_id'];?>);"><?php _e($advancedSettings['ticket_label_alice'][10],'wp-support-plus-responsive');?></button>
    <?php }
    ?>        
</div>
<br>
<h3><?php echo '['.__($advancedSettings['ticket_label_alice'][1],'wp-support-plus-responsive')?> <?php echo $advancedSettings['wpsp_ticket_id_prefix'].$_POST['ticket_id'].'] '.stripcslashes(htmlspecialchars_decode($ticket->subject,ENT_QUOTES));?></h3>
<?php if($current_user->has_cap('manage_support_plus_agent')){?>
<div style="float:right;margin-top:-20px;">
	<a href="<?php echo admin_url('admin.php?page=wp-support-plus-faq&type=editTicket&id='.$_POST['ticket_id']);?>">
		<img alt="Edit" title="Edit" src="<?php echo WCE_PLUGIN_URL.'asset/images/edit.png';?>" />
	</a>
</div>
<?php }?>
<!-- Custom Field -->
<?php if($total_cust_field){?>
	<div class="threadContainer">
		<?php 
		foreach ($customFields as $field){
			if(in_array($field->id,$advancedSettingsFieldOrder['display_fields']))
			{
                            $fieldValue=$wpdb->get_var("select cust".$field->id." from {$wpdb->prefix}wpsp_ticket WHERE id=".$_POST['ticket_id']);
                            if($fieldValue){
				switch($field->field_type)
				{
					case '1':
						echo "<b>".$field->label.":</b> ".stripcslashes(htmlspecialchars_decode($fieldValue,ENT_QUOTES))."<br>"; 
					break;
					case '2':
						if($field->field_options!=NULL)
						{
							$field_options=unserialize($field->field_options);
							if(isset($field_options[$fieldValue]))
							{
								echo "<b>".$field->label.":</b> ".stripcslashes(htmlspecialchars_decode($field_options[$fieldValue],ENT_QUOTES))."<br>"; 
							}
							else
							{
								echo "<b>".$field->label.":</b> ".stripcslashes(htmlspecialchars_decode($fieldValue,ENT_QUOTES))."<br>"; 
							}
						}
					break;
					case '3':
						if($field->field_options!=NULL)
						{
							$field_options=unserialize($field->field_options);
							if(isset($field_options[$fieldValue]))
							{
								echo "<b>".$field->label.":</b> ".stripcslashes(htmlspecialchars_decode($field_options[$fieldValue],ENT_QUOTES))."<br>"; 
							}
							else
							{
								echo "<b>".$field->label.":</b> ".stripcslashes(htmlspecialchars_decode($fieldValue,ENT_QUOTES))."<br>"; 
							}
						}
					break;
					case '4':
						if($field->field_options!=NULL)
						{
							$field_options=unserialize($field->field_options);
							if(isset($field_options[$fieldValue]))
							{
								echo "<b>".$field->label.":</b> ".stripcslashes(htmlspecialchars_decode($field_options[$fieldValue],ENT_QUOTES))."<br>"; 
							}
							else
							{
								echo "<b>".$field->label.":</b> ".stripcslashes(htmlspecialchars_decode($fieldValue,ENT_QUOTES))."<br>"; 
							}
						}
					break;
					case '5':
                                            echo "<b>".$field->label.":</b> <br>".nl2br($fieldValue)."<br>"; 
					break;
                                        case '6':
                                            echo "<b>".$field->label.":</b> ".stripcslashes(htmlspecialchars_decode($fieldValue,ENT_QUOTES))."<br>"; 
                                        break;
				}
                            }
			}
		}
		?>
	</div>
<?php } ?>
<!-- End Of Custom Field -->
<!-- Extension integration start -->
<?php
if($ticket->extension_meta){
    ?>
    <div class="threadContainer">
    <?php
    $extension_meta=  explode(',', $ticket->extension_meta);
    if($extension_meta[0]==1){ //woocommerce
        if($extension_meta[1]==1){ //product
            $pf = new WC_Product_Factory();
            $product = $pf->get_product($extension_meta[2]);            
            $prod_url = get_permalink( $product->id );
            $prod_title=$product->post->post_title;
            echo "<b>".__('Product','wp-support-plus-responsive').":</b> ".'<a href="'.$prod_url.'" target="__blank">'.$prod_title.'</a>'."<br>";
        } else if($extension_meta[1]==2){ //order
            $order = new WC_Order($extension_meta[2]);
            $order_title=__('Order','wp-support-plus-responsive').' #'.$extension_meta[2];
            echo "<b>".__('Order','wp-support-plus-responsive').":</b> ".'<a href="'.admin_url('post.php').'?post='.$extension_meta[2].'&action=edit" target="__blank">'.$order_title."</a><br>";
            ?>
            <table class="wpsp_order_display_open_ticket">
                <tr>
                    <th class="wpsp_order_tbl_col1"><?php echo _e('Product','wp-support-plus-responsive');?></th>
                    <th class="wpsp_order_tbl_col2"><?php echo _e('Total','wp-support-plus-responsive');?></th>
                </tr>
            <?php
            foreach ($order->get_items() as $key => $lineItem) {
                $pf = new WC_Product_Factory();
                $product = $pf->get_product($lineItem['product_id']);            
                $prod_url = get_permalink( $product->id );
                $prod_title=$product->post->post_title;
                ?>
                <tr>
                    <td class="wpsp_order_tbl_col1"><a href="<?php echo $prod_url;?>" target="__blank"><?php echo $prod_title;?></a> x <?php echo $lineItem['qty'];?></td>
                    <td class="wpsp_order_tbl_col2"><?php echo get_woocommerce_currency_symbol($order->order_currency).$lineItem['line_subtotal'];?></td>
                </tr>
                <?php
            }
            ?>
                <tr>
                    <td class="wpsp_order_tbl_col1"><b><?php _e('Subtotal','wp-support-plus-responsive');?></b></td>
                    <td class="wpsp_order_tbl_col2"><b><?php echo get_woocommerce_currency_symbol($order->order_currency).$order->get_subtotal();?></b></td>
                </tr>
                <tr>
                    <td class="wpsp_order_tbl_col1"><b><?php _e('Discount','wp-support-plus-responsive');?></b></td>
                    <td class="wpsp_order_tbl_col2"><b>-<?php echo $order->get_discount_to_display();?></b></td>
                </tr>
                <tr>
                    <td class="wpsp_order_tbl_col1"><b><?php _e('Payment Method','wp-support-plus-responsive');?></b></td>
                    <td class="wpsp_order_tbl_col2"><b><?php echo $order->payment_method;?></b></td>
                </tr>
                <tr>
                    <td class="wpsp_order_tbl_col1"><b><?php _e('Total','wp-support-plus-responsive');?></b></td>
                    <td class="wpsp_order_tbl_col2"><b><?php echo get_woocommerce_currency_symbol($order->order_currency).$order->order_total;?></b></td>
                </tr>
            </table>
            <?php
        }
    }
    ?>
    </div>
    <?php
}
?>
<!-- Extension integration end -->
<?php 
if($advancedSettings['wpsp_reply_form_position']==1){
    include( WCE_PLUGIN_DIR.'includes/admin/replyFormPosition.php' );
}
?>

<?php
if($advancedSettings['enable_accordion']){
?>
<div id="threadAccordion" class="wpSupportPlus">
<?php
}
?>
    <?php foreach ($threads as $thread){?>
    <?php
    /* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
     * Update 18 - Thread accordion
     * jQuery accordion for threads
     */ 
    /*<div class="threadContainer">*/
    /* END CLOUGH I.T. SOLUTIONS MODIFICATION
     */

    if($thread->note==0 || ($thread->note==1 && $current_user->has_cap('manage_support_plus_ticket'))){
    ?>
		<?php 
			$user_name='';
			$user_email='';
			$signature='';
			if($thread->created_by){
				$user=get_userdata( $thread->created_by );
				$user_name=$user->display_name;
				$user_email=$user->user_email;
				
				$userSignature = $wpdb->get_row( "select signature FROM {$wpdb->prefix}wpsp_agent_settings WHERE agent_id=".$thread->created_by );
				if($wpdb->num_rows){
					$signature='<br>---<br>'.stripcslashes(htmlspecialchars_decode($userSignature->signature,ENT_QUOTES));
				}
			}
			else{
				$user_name=$thread->guest_name;
				$user_email=$thread->guest_email;
			}
			$modified='';
			if ($thread->date_modified_month) $modified=$thread->date_modified_month.' '.__('months ago','wp-support-plus-responsive');
			else if ($thread->date_modified_day) $modified=$thread->date_modified_day.' '.__('days ago','wp-support-plus-responsive');
			else if ($thread->date_modified_hour) $modified=$thread->date_modified_hour.' '.__('hours ago','wp-support-plus-responsive');
			else if ($thread->date_modified_min) $modified=$thread->date_modified_min.' '.__('minutes ago','wp-support-plus-responsive');
			else $modified=$thread->date_modified_sec.' '.__('seconds ago','wp-support-plus-responsive');
			/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
			 * Update 4 - Display thread date time
			 * convert create_time to local time from gmt and add to $modified
			 */ 
			$modified .= ' (' . date_i18n( get_option( 'date_format' ), strtotime( get_date_from_gmt( $thread->create_time, 'Y-m-d H:i:s') ) ) . ' ' . get_date_from_gmt( $thread->create_time, 'H:i:s') . ')';
			/* END CLOUGH I.T. SOLUTIONS MODIFICATION
			 */
			$attachments=array();
			if($thread->attachment_ids){
				$attachments=explode(',', $thread->attachment_ids);
			}
			
			$body=stripcslashes(htmlspecialchars_decode($thread->body,ENT_QUOTES));
			$body.=$signature;
			?>
			<?php
			/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
			 * Update 18 - Thread accordion
			 * jQuery accordion for threads
			 */ 
			?>
			<h3><strong><?php echo $user_name;?></strong> <em><?php echo $user_email;?></em> <?php echo $modified;?></h3>
			<div class="threadContainer">
			<div class="threadHeader">
                            <?php
                            /* END CLOUGH I.T. SOLUTIONS MODIFICATION
                             */
                            ?>
                            <div class="gravtar_container">
                                    <?php echo get_avatar($user_email,60);?>
                            </div>
                            <div class="threadInfo">
                                    <span class="threadUserName"><?php echo $user_name;?></span><br>
                                    <small class="threadUserType"><?php echo $user_email;?></small><br>
                                    <small class="threadCreateTime"><?php echo $modified;?></small>
                            </div>
                            <?php if($current_user->has_cap('manage_support_plus_agent')){?>
                                <div style="float: right; margin-right: 5px;">
                                        <a href="<?php echo admin_url('admin.php?page=wp-support-plus-faq&type=editThread&id='.$thread->id);?>">
                                                <img alt="Edit" title="Edit" src="<?php echo WCE_PLUGIN_URL.'asset/images/edit.png';?>" />
                                        </a>
                                </div>
                            <?php }
                            /* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
                             * Update 14 - create new ticket from thread
                             */ 
                            ?>
                            <div style="float: right; margin-right: 5px;">
                                    <img alt="<?php echo __($advancedSettings['ticket_label_alice'][16],'wp-support-plus-responsive');?>" title="<?php echo __($advancedSettings['ticket_label_alice'][16],'wp-support-plus-responsive');?>" src="<?php echo WCE_PLUGIN_URL.'asset/images/new.png';?>" style="cursor:pointer;" onclick="ticketFromThread(<?php echo $thread->id; ?>)" />
                            </div>
			</div>
			<?php
			/* END CLOUGH I.T. SOLUTIONS MODIFICATION
			 */
			?>
			<?php 
			if($thread->note==1 && $current_user->has_cap('manage_support_plus_ticket')){ ?>
				<div class='note' style='size:18px;color:red;'><?php _e('Private Note : Not Visible to Customers','wp-support-plus-responsive');?></div>
			<?php
			}?>
			
			<div class="threadBody"><?php echo $body;?></div>
			<?php if(count($attachments)){?>
			<div class="threadAttachment">
				<span id="wpsp_reply_attach_label"><?php _e('Attachment: ','wp-support-plus-responsive');?></span>
				<?php 
				$attachCount=0;
				foreach ($attachments as $attachment){
					$attach=$wpdb->get_row( "select * from {$wpdb->prefix}wpsp_attachments where id=".$attachment );
					$attachCount++;
				?>
				<a class="attachment_link" title="Download" target="_blank" href="<?php echo $attach->fileurl;?>" ><?php echo ($attachCount>1)?', ':'';echo $attach->filename;?></a>
				<?php }?>
			</div>
			<?php }?>
</div>
<?php }
}?>
<?php
/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
 * Update 18 - Thread accordion
 * jQuery accordion for threads
 */ 
if($advancedSettings['enable_accordion']){
?>
</div>
<?php
}
/* END CLOUGH I.T. SOLUTIONS MODIFICATION
 */
if($advancedSettings['wpsp_reply_form_position']==0){
    include( WCE_PLUGIN_DIR.'includes/admin/replyFormPosition.php' );
}
?>
<div id="psmwpsp" style="display: none">
    <div  id="myModal">
     <h4 id="myModalLabel"><?php _e('Canned Reply','wp-support-plus-responsive');?></h4>
    </div>
        <div id="popup">
                <?php 
                  global $wpdb;
                  $sql="select * from {$wpdb->prefix}wpsp_canned_reply where uID=".$current_user->ID." OR sid LIKE '%".$current_user->ID."%'";
                  $canned = $wpdb->get_results( $sql );
                ?>
                <table class="table table-striped table-hover" id="wpspCannedTBL">
                    <tr>
                      <th style="width: 50px;">#</th>
                      <th><?php _e('Title','wp-support-plus-responsive');?></th>
                      <th style="display:none;">Body</th>
                    </tr>
                    <?php 
                    $wpsp_canned_id=0;
                    foreach($canned as $can){ ?>
                        <tr id="mytr" onclick="replyonclick(<?php echo $can->id;?>)">
                            <td style="width: 50px;"><?php echo ++$wpsp_canned_id;?></td>
                            <td><?php echo stripcslashes($can->title);?></td>
                            <td style="display:none;" id="reply<?php echo $can->id; ?>"><?php echo stripcslashes($can->reply);?></td>
                        </tr>
                    <?php }?>
                </table>
                <?php 
                if(!$canned){?>
                        <div style="text-align: center;"><?php _e("No Reply Found",'wp-support-plus-responsive');?></div>
                        <hr>
                <?php }?>
                        <button type="button" class="btn-default" id="wpsp_canned_Less" onclick="wpsp_canned_previous();"><?php _e('Previous','wp-support-plus-responsive');?></button>
                        <button type="button" class="btn-default" id="wpsp_canned_More" style="alignment:right" onclick="wpsp_canned_next();"><?php _e('Next','wp-support-plus-responsive');?></button>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" onclick="closepopup();"><?php _e('Close','wp-support-plus-responsive');?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var currentIndex=10;
    jQuery(document).ready(function(){
        jQuery("#psmwpsp_canned").click(function(){
            jQuery("#psmwpsp").show();
        });        
        jQuery("#wpsp_popup_ticket").click(function(){
            jQuery('#wpsp_popup_ticket_div').show();       
        });        
        jQuery('#wpsp_show_more').hide();
        jQuery("#wpsp_slide_demo").click(function(){
            jQuery('#wpsp_show_more').slideToggle();
        });
    });
    function replyonclick(cid){
        var value = CKEDITOR.instances['replyBody'].getData();
        var x=document.getElementById("reply"+cid);
        CKEDITOR.instances["replyBody"].setData(value+x.innerHTML); 
        jQuery('#psmwpsp').hide();
    }
    function closepopup(){
        jQuery('#psmwpsp').hide();
    }
    function cannedrep(){
        jQuery("#wpspCannedTBL tr").hide();
        jQuery("#wpspCannedTBL tr").slice(0, 10).show();
        checkButton();
    }
    function wpsp_canned_next(){
        jQuery("#wpspCannedTBL tr").hide(); 
        jQuery("#wpspCannedTBL tr").slice(currentIndex, currentIndex +10).show();
        currentIndex+=10;
        checkButton();
    } 
    function wpsp_canned_previous(){
       currentIndex-=10;
       jQuery("#wpspCannedTBL tr").hide(); 
       jQuery("#wpspCannedTBL tr").slice(currentIndex-10, currentIndex).show();          
       checkButton();
    }
   function checkButton(){ 
        var currentLength;
        currentLength =jQuery("#wpspCannedTBL tr").length;
        if(currentLength<currentIndex){
            jQuery('#wpsp_canned_More').prop('disabled', true);
        } else {
            jQuery('#wpsp_canned_More').prop('disabled', false); 
        }
        if(currentIndex<=10){
            jQuery('#wpsp_canned_Less').prop('disabled', true); 
        }else{
            jQuery('#wpsp_canned_Less').prop('disabled', false); 
        }
    }
</script>
<style type="text/css">
    #wpspCannedTBL td,#wpspCannedTBL th{
        color: #000000;
    }
</style>