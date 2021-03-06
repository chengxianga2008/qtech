<?php 
global $current_user;
$current_user=wp_get_current_user();
$roleManage=get_option('wpsp_role_management');
$advancedSettings=get_option( 'wpsp_advanced_settings' );
$ticket_label= $advancedSettings['default_main_ticket_label'];
$tickets_label= $advancedSettings['default_main_tickets_label'];

/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
 * Update 10 - disable front end new ticket submission
 * get general settings
 */
$generalSettings=get_option( 'wpsp_general_settings' );
/* END CLOUGH I.T. SOLUTIONS MODIFICATION
 */
$allowed_roles=array_intersect($roleManage['front_ticket'],$current_user->roles);
if($roleManage['front_ticket_all'] || count($allowed_roles)>0)
{?>

    <div class="tab-content">
            <!-- Tickets Tab Body Start Here -->
            <div class="tab-pane active" id="ticketContainer">
                    <div id="ticketActionFront">
                            <?php include( WCE_PLUGIN_DIR.'includes/ticketActionFront.php' );?>
                    </div>
                    <div class="ticket_list"></div>
                    <div class="ticket_indivisual"></div>
                    <div class="ticket_assignment"></div>
                    <div class="wait"><img alt="<?php echo __('Please Wait', 'wp-support-plus-responsive');?>" src="<?php echo WCE_PLUGIN_URL.'asset/images/ajax-loader@2x.gif?ver='.WPSP_VERSION;?>"></div>
            </div>
    </div>
<?php }?>
