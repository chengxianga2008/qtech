<?php
    if(isset($_POST['psub']))
        update_option("wpdmpp_payout_duration",$_POST['payout_duration']);
        
    if(isset($_POST['csub']))
        update_option("wpdmpp_user_comission",$_POST['comission']);
        
    if(isset($_POST['pschange'])){
        global $wpdb;
        //echo $_POST['payout_status'];
        if($_POST['payout_status']!="-1" && $_POST['payout_status']!="2"){
            if($_POST['poutid']){
                foreach($_POST['poutid'] as $payout_id){
                    $wpdb->update( 
                        "{$wpdb->prefix}ahm_withdraws", 
                        array( 
                            'status' => $_POST['payout_status']    
                               
                        ), 
                        array( 'ID' => $payout_id ), 
                        array( 
                            '%d',    // value1
                            
                        ), 
                        array( '%d' ) 
                    );
                }
            }
        }
        if($_POST['payout_status']=="2"){
           if($_POST['poutid']){
                foreach($_POST['poutid'] as $payout_id){
                    $wpdb->query("delete from {$wpdb->prefix}ahm_withdraws where id={$payout_id}"); 
                        
                }
            } 
        }
    }
    $payout_duration=get_option("wpdmpp_payout_duration");
    $comission=get_option("wpdmpp_user_comission");
?>
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url('/download-manager/bootstrap/css/bootstrap.css');?>" />
<style>
    .nav-tabs{
        margin-bottom: 0 !important;
    }
    .w3eden{
        max-width: 95%;
    }
    .tab-content{
        background: #ffffff;
        border:1px solid #dddddd;
        border-top: 0;
        padding: 20px;
    }
    table.widefat{
        border-radius: 4px;border-collapse:separate; overflow: hidden;
    }
    thead th, tfoot th{ font-size: 9pt !important; text-transform: uppercase; font-weight: 900 !important; }
</style>
  <div class="icon32" id="icon-options-general"><br></div><h2><?php echo __("Payouts","wpmarketplace");?> <img style="display: none;" id="wdms_loading" src="images/loading.gif" /></h2>
 
<div class="w3eden">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab1" data-toggle="tab"><?php echo __("All Payouts","wpmarketplace");?></a></li>
                    <li><a href="#tab2" data-toggle="tab"><?php echo __("Dues","wpmarketplace");?></a></li>
                    <li><a href="#tab3" data-toggle="tab"><?php echo __("Payout Settings","wpmarketplace");?></a></li>                                 
                </ul>
                 
                <div class="tab-content">
                <div class="tab-pane active" id="tab1">
                <?php
                    include_once("all_payouts.php");
                ?>
                </div>
                <div class="tab-pane" id="tab2">
                <?php
                    include_once("payout_dues.php");
                ?>
                </div>
                <div class="tab-pane" id="tab3">
                <?php
                    include_once("payout_settings.php");
                ?>
                </div>   
                </div>
</div>