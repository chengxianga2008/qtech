<?php
class WPSPCron{
    function check_offer_and_update(){
        $siteDataWPSP = file_get_contents('http://pradeepmakone.com/wp_support_plus_update_and_offers.txt');
        $wpsp_update_notice=array(
            'text'=>$siteDataWPSP
        );
        update_option('wpsp_update_notice_txt_time',$wpsp_update_notice);
    }
}
?>