<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
 * Update 19 - improved registered user search
 * allows wildcard searching
 */ 
global $wpdb;
$term = $_POST['search_keywords'];
$sql = "SELECT * FROM " . $wpdb->prefix . "users WHERE 1=1 AND (user_login LIKE '%" . $term . "%' OR user_email LIKE '%" . $term . "%' OR display_name LIKE '%" . $term . "%') ORDER BY display_name ASC LIMIT 5";
$wpspUsers = $wpdb->get_results( $sql );
if (!empty( $wpspUsers ) ) {
    ?>
    <div class="table-responsive" style="margin-top: 5px;">
        <table class="table table-striped table-hover">
            <tr>
              <th><?php _e('Name','wp-support-plus-responsive');?></th>
              <th><?php _e('Username','wp-support-plus-responsive');?></th>
              <th><?php _e('Email','wp-support-plus-responsive');?></th>
            </tr>
            <?php
            foreach( $wpspUsers as $wpspUser ) {
                ?>
                <tr style="cursor: pointer;" onclick="wpspChangeUserFromSearchTable(<?php echo $wpspUser->ID;?>,'<?php echo $wpspUser->display_name;?>');">
                    <td><?php echo $wpspUser->display_name;?></td>
                    <td><?php echo $wpspUser->user_login;?></td>
                    <td><?php echo $wpspUser->user_email;?></td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
    <?php
} else {
    ?>
    <div style="text-align: center;"><?php _e('No Results Found','wp-support-plus-responsive');?></div>
    <hr>
    <?php
}
/* END CLOUGH I.T. SOLUTIONS MODIFICATION
 */
?>