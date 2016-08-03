<?php
global $wpdb;
$sql = "select * from {$wpdb->prefix}ahm_withdraws order by date desc";
$payouts = $wpdb->get_results($sql);
?>
<form action="" method="post">
    <select name="payout_status"><option value="-1">Payout Status:</option><option value="0">Pending</option><option value="1">Completed</option><option value="2">Cancel</option></select>
    <input type="submit" name="pschange" value="Apply" class="button"><br/><br/>
    <table cellspacing="0" class="widefat fixed table table-striped">
    <thead>
    <tr>
        <th style="width: 30px"><input type="checkbox" /></th>
    <th><?php echo __("Username","wpmarketplace");?></th>
    <th><?php echo __("Amount","wpmarketplace");?></th>
    <th><?php echo __("Status","wpmarketplace");?></th>
    </tr>
    </thead>
        <tfoot>
        <tr>
            <th><input type="checkbox" /></th>
            <th><?php echo __("Username","wpmarketplace");?></th>
            <th><?php echo __("Amount","wpmarketplace");?></th>
            <th><?php echo __("Status","wpmarketplace");?></th>
        </tr>
        </tfoot>
    <tbody>
    <?php
    foreach($payouts as $payout){
      if($payout->status==0) $st="Pending"; else if ($payout->status==1)$st="Completed";   
      echo "<tr><td><input type='checkbox' name='poutid[]' value='".$payout->id."'></td><td><a href='user-edit.php?user_id=".$payout->uid."'>".__(get_userdata($payout->uid)->display_name,"wpmarketplace")."</a></td><td>".wpdmpp_currency_sign().number_format($payout->amount,2)."</td><td>".__($st,"wpmarketplace")."</td></tr>";
    }
    ?>

    </tbody>
    </table>
</form>
