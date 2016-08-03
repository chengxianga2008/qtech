<?php

function wpdmpp_getlicensekey(){
    if(!isset($_REQUEST['execute']) || $_REQUEST['execute']!='getlicensekey' || !is_user_logged_in()) return;
     global $wpdb, $current_user;
     $oid =  esc_attr($_REQUEST['orderid']);
     $pid = intval($_REQUEST['fileid']);
     $order = new Order();
     $odata = $order->GetOrder($oid);
     $items = unserialize($odata->items);
     
     if(in_array($pid, $items) && $odata->order_status=='Completed' && $current_user->ID == $odata->uid) {
        $licenseno = $wpdb->get_var("select licenseno from {$wpdb->prefix}ahm_licenses where oid='{$oid}' and pid='{$pid}'");
        if(!$licenseno){
            $licenseno = strtoupper(substr(uniqid(rand()),3,5).'-'.substr(uniqid(rand()),3,5).'-'.substr(uniqid(rand()),3,5).'-'.substr(uniqid(rand()),3,5));
            $wpdb->insert("{$wpdb->prefix}ahm_licenses",array('licenseno'=>$licenseno,'status'=>0,'oid'=>$oid,'pid'=>$pid));
            die($licenseno);
        }else
        die($licenseno);
        
     } else die('error!');
}




function wpdm_pp_add_domain(){    
   if(!$_POST||!$_GET['id']) return;
   global $current_user, $wpdb;
   get_currentuserinfo(); 
   $order = new Order();
   $item = (int)$_GET['item'];
   $ord = $order->GetOrder($_GET['id']);
   $cart_data = unserialize($ord->cart_data);
   $mxd = $cart_data[$item]?$cart_data[$item]:1;
   if($ord->uid!=$current_user->ID||$_POST['domain']==''||!$current_user->ID||$ord->uid=='') return false;
   $oid = mysql_escape_string($_GET['id']);   
   $lic = $wpdb->get_row("select * from {$wpdb->prefix}ahm_licenses where oid='$oid' and pid='$item'");
   
   $domain = is_array(unserialize($lic->domain))?unserialize($lic->domain):array($lic->domain);
   $licenseno = strtoupper(substr(uniqid(rand()),3,5).'-'.substr(uniqid(rand()),3,5).'-'.substr(uniqid(rand()),3,5).'-'.substr(uniqid(rand()),3,5));
   if(count($domain)==1&&$domain[0]=='') $domain = array();
   
   if(count($domain)<$mxd){
     $domain[] = str_replace(array("http://","https://","www."),"",strtolower($_POST['domain']));     
     $domain = array_unique($domain);
      
     if($lic->id>0)
     $wpdb->update("{$wpdb->prefix}ahm_licenses",array('domain'=>serialize($domain)),array('oid'=>$oid,'pid'=>$item));
     else
     $wpdb->insert("{$wpdb->prefix}ahm_licenses",array('domain'=>serialize($domain),'licenseno'=>$licenseno,'oid'=>$oid,'pid'=>$item));
   }
   
   header("location: $_SERVER[HTTP_REFERER]");
   die();
}
