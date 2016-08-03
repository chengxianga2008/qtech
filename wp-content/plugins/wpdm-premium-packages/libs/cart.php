<?php
function wpdmpp_show_cart(){
    global $wpdb;

    wpdmpp_calculate_discount();
    $cart_data = wpdmpp_get_cart_data();
    //echo "<pre>"; print_r($cart_data); echo "</pre>";
    $login_html = "";
    $payment_html = "";
    $settings = get_option('_wpdmpp_settings');
    $guest_checkout = (isset($settings['guest_checkout']) && $settings['guest_checkout']==1)?1:0;
    include(wpdmpp_tpl_dir()."cart.php");
    if(!is_user_logged_in() && $guest_checkout==0)
        include_once(WPDMPP_BASE_DIR."/tpls/checkout_method.php");
    if(is_user_logged_in() || $guest_checkout==1)
        include_once(WPDMPP_BASE_DIR."/tpls/payment_method.php");
    return "<div class='w3eden'>".$cart."<div id='checkoutarea' style='display:none;'>".$login_html. $payment_html."</div></div>";
    return $cart;
}

function wpdmpp_load_saved_cart(){
    if(isset($_REQUEST['savedcart'])){
        $cartid = preg_replace("/[^a-zA-Z0-9]*/i", "", $_REQUEST['savedcart']);
        $cartfile = WPDM_CACHE_DIR.'/saved-cart-'.$cartid.'.txt';
        $saved_cart_data = '';
        if(file_exists($cartfile)) $saved_cart_data = file_get_contents($cartfile);
        $saved_cart_data = WPDM_Crypt::Decrypt($saved_cart_data);
        if(is_array($saved_cart_data) && count($saved_cart_data) > 0)
            wpdmpp_update_cart_data($saved_cart_data);

        wpdmpp_redirect(wpdmpp_cart_page());

    }
}

function wpdmpp_load_payment_methods(){
    if(!wpdm_is_ajax() || !isset($_REQUEST['wpdmpp_load_pms'])) return;
    $payment_html = "";
    include_once(WPDMPP_BASE_DIR."/tpls/payment_method.php");
    echo $payment_html;
    die();
}


//checking product coupon whether valid or not
function check_coupon($pid,$coupon){
    $coupon_code = get_post_meta($pid, '__wpdm_coupon_code', true);
    $coupon_discount = get_post_meta($pid, '__wpdm_coupon_discount', true);

    if(is_array($coupon_code)){
        foreach($coupon_code as $key=> $val){
            if($val==$coupon)
                return $coupon_discount[$key];
            //return $coupon_code[$key];
        }
    }
    return 0;
}

function wpdmpp_add_to_cart(){
    if(isset($_POST['add_to_cart']) && $_POST['add_to_cart']=="add"){
        global $wpdb, $post, $wp_query, $current_user;
        $settings = maybe_unserialize(get_option('_wpdmpp_settings'));
        $pid = isset($_REQUEST['wpdmpp_add_to_cart']) ? $_REQUEST['wpdmpp_add_to_cart']:$_POST['pid'];
        $pid = apply_filters("wpdmpp_add_to_cart", $pid);
        if($pid<=0) return;

        $sales_price=0;

        $cart_data = wpdmpp_get_cart_data();

        $q = isset($_REQUEST['quantity'])?intval($_REQUEST['quantity']):1;
        if($q<1) $q = 1;

        //$q += $cart_data[$pid]['quantity'];           
        $base_price = wpdmpp_product_price($pid);
        //
        if(!isset($_REQUEST['variation'])) {
            $_REQUEST['variation'] = "";
        }

        //if product id already exist :D
        if(array_key_exists($pid, $cart_data)){

            //print_r($cart_data); die('cart data');
            if(isset($cart_data[$pid]['multi']) && $cart_data[$pid]['multi']==1){
                $product_data = $cart_data[$pid]['item'];
                $check = false;
                foreach ($product_data as $key => $item):
                    //check same variation exist or not
                    if(wpdmpp_array_diff($item['variation'], $_REQUEST['variation'])==true){
                        //you are lucky, just incremnet qunatity value 
                        $cart_data[$pid]['item'][$key]['quantity'] += $q;
                        $cart_data[$pid]['quantity'] += $q;
                        $check = true;
                        break;
                    }
                endforeach;
                if($check == false){
                    //add this item as new item

                    $cart_data[$pid]['item'][] = array(
                        'quantity'=>$q,
                        'variation'=>$_POST['variation']
                    );
                    $cart_data[$pid]['quantity'] += $q;

                }
            }
            else {

                if(!isset($_REQUEST['variation']) || $_REQUEST['variation']=='') $_REQUEST['variation'] = array();
                if(wpdmpp_array_diff($cart_data[$pid]['variation'] , $_REQUEST['variation'])==true){
                    //wow just increment product 
                    $cart_data[$pid]['quantity'] += $q;
                }

                else {
                    //badluck implement new method

                    //$q += $cart_data[$pid]['quantity'];
                    $old_qty = $cart_data[$pid]['quantity'];
                    $old_variation = $cart_data[$pid]['variation'];
                    $coupon = isset($cart_data[$pid]['coupon']) ? $cart_data[$pid]['coupon'] : '';
                    $coupon_amount = isset($cart_data[$pid]['coupon_amount']) ? $cart_data[$pid]['coupon_amount'] : '';
                    $discount_amount = isset($cart_data[$pid]['discount_amount']) ? $cart_data[$pid]['discount_amount'] : '';
                    $prices = isset($cart_data[$pid]['prices']) ? $cart_data[$pid]['prices'] : '';
                    $variations = isset($cart_data[$pid]['variations']) ? $cart_data[$pid]['variations'] : '';
                    $new_data = array(
                        'quantity'=>$q,
                        'variation'=>$_POST['variation'],
                    );
                    $cart_data[$pid] = array();
                    $cart_data[$pid]['multi'] = 1;
                    $cart_data[$pid]['quantity'] = $q+$old_qty;
                    $cart_data[$pid]['price'] = $base_price;
                    $cart_data[$pid]['coupon'] = $coupon;
                    $cart_data[$pid]['item'][] = array(
                        'quantity' => $old_qty,
                        'variation' => $old_variation,
                    );
                    $cart_data[$pid]['item'][] = $new_data;

                }


            }
        }

        else {
            //new item
            $variation = isset($_POST['variation'])?$_POST['variation']:array();
            $cart_data[$pid] = array('quantity'=>$q,'variation'=>$variation,'price'=>$base_price);
        }

        // echo "<pre>";        print_r($cart_data); echo "</pre>";
        wpdmpp_update_cart_data($cart_data);

        wpdmpp_calculate_discount();

        //echo "<pre>";        print_r($cart_data); echo "</pre>";
        //die();
        $settings = get_option('_wpdmpp_settings');

        /* AJAX check  */
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo get_permalink($settings['page_id']);
            die();
        }

        if($settings['wpdmpp_after_addtocart_redirect']==1){
            header("location: ".get_permalink($settings['page_id']));
        }
        else header("location: ".$_SERVER['HTTP_REFERER']);
        die();
    }

}

function wpdmpp_add_to_cart_ucb(){
    if(isset($_REQUEST['addtocart']) && intval($_REQUEST['addtocart'])>0 && get_post_type($_REQUEST['addtocart'])=='wpdmpro'){

        global $wpdb, $post, $wp_query, $current_user;
        $settings = maybe_unserialize(get_option('_wpdmpp_settings'));
        $pid= $_REQUEST['addtocart'];
        $pid = apply_filters("wpdmpp_add_to_cart", $pid);

        if($pid<=0) return;

        $sales_price=0;


        $cart_data = wpdmpp_get_cart_data();

        $q = $_REQUEST['quantity']?intval($_REQUEST['quantity']):1;
        if($q<1) $q = 1;

        //$q += $cart_data[$pid]['quantity'];
        $base_price = wpdmpp_product_price($pid);
        //
        if(!isset($_REQUEST['variation'])) {
            $_REQUEST['variation'] = "";
        }
        //if product id already exist :D
        if(array_key_exists($pid, $cart_data)){
            //print_r($cart_data); die('cart data');
            if(isset($cart_data[$pid]['multi']) && $cart_data[$pid]['multi']==1){
                $product_data = $cart_data[$pid]['item'];
                $check = false;
                foreach ($product_data as $key => $item):
                    //check same variation exist or not
                    if(wpdmpp_array_diff($item['variation'], $_REQUEST['variation'])==true){
                        //you are lucky, just incremnet qunatity value
                        $cart_data[$pid]['item'][$key]['quantity'] += $q;
                        $cart_data[$pid]['quantity'] += $q;
                        $check = true;
                        break;
                    }
                endforeach;
                if($check == false){
                    //add this item as new item

                    $cart_data[$pid]['item'][] = array(
                        'quantity'=>$q,
                        'variation'=>$_POST['variation']
                    );
                    $cart_data[$pid]['quantity'] += $q;

                }
            }
            else {
                if(wpdmpp_array_diff($cart_data[$pid]['variation'] , $_REQUEST['variation'])==true){
                    //wow just increment product
                    $cart_data[$pid]['quantity'] += $q;
                }

                else {
                    //badluck implement new method

                    //$q += $cart_data[$pid]['quantity'];
                    $old_qty = $cart_data[$pid]['quantity'];
                    $old_variation = $cart_data[$pid]['variation'];
                    $coupon = isset($cart_data[$pid]['coupon']) ? $cart_data[$pid]['coupon'] : '';
                    $coupon_amount = isset($cart_data[$pid]['coupon_amount']) ? $cart_data[$pid]['coupon_amount'] : '';
                    $discount_amount = isset($cart_data[$pid]['discount_amount']) ? $cart_data[$pid]['discount_amount'] : '';
                    $prices = isset($cart_data[$pid]['prices']) ? $cart_data[$pid]['prices'] : '';
                    $variations = isset($cart_data[$pid]['variations']) ? $cart_data[$pid]['variations'] : '';
                    $new_data = array(
                        'quantity'=>$q,
                        'variation'=>$_POST['variation'],
                    );
                    $cart_data[$pid] = array();
                    $cart_data[$pid]['multi'] = 1;
                    $cart_data[$pid]['quantity'] = $q+$old_qty;
                    $cart_data[$pid]['price'] = $base_price;
                    $cart_data[$pid]['coupon'] = $coupon;
                    $cart_data[$pid]['item'][] = array(
                        'quantity' => $old_qty,
                        'variation' => $old_variation,
                    );
                    $cart_data[$pid]['item'][] = $new_data;

                }


            }
        }

        else {
            //new item
            $cart_data[$pid] = array('quantity'=>$q,'variation'=>$_POST['variation'],'price'=>$base_price);
        }

        // echo "<pre>";        print_r($cart_data); echo "</pre>";
        wpdmpp_update_cart_data($cart_data);

        wpdmpp_calculate_discount();

        //echo "<pre>";        print_r($cart_data); echo "</pre>";
        //die();
        $settings = get_option('_wpdmpp_settings');

        /* AJAX check  */
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            echo get_permalink($settings['page_id']);
            die();
        }

        if($settings['wpdmpp_after_addtocart_redirect']==1){
            header("location: ".get_permalink($settings['page_id']));
        }
        else header("location: ".$_SERVER['HTTP_REFERER']);
        die();
    }

}


function wpdmpp_remove_cart_item(){
    if(!isset($_REQUEST['wpdmpp_remove_cart_item']) || $_REQUEST['wpdmpp_remove_cart_item']<=0) return;
    $cart_data = wpdmpp_get_cart_data();
    if(isset($_REQUEST['item_id'])){
        unset($cart_data[$_REQUEST['wpdmpp_remove_cart_item']]['item'][$_REQUEST['item_id']]);
        if(empty($cart_data[$_REQUEST['wpdmpp_remove_cart_item']]['item'])) {
            unset($cart_data[$_REQUEST['wpdmpp_remove_cart_item']]);
        }
    }
    else{
        unset($cart_data[$_REQUEST['wpdmpp_remove_cart_item']]);
    }
    wpdmpp_update_cart_data($cart_data);
    $ret['cart_subtotal'] = wpdmpp_get_cart_subtotal();
    $ret['cart_discount'] = wpdmpp_get_cart_discount();
    $ret['cart_total'] = wpdmpp_get_cart_total();
    die(json_encode($ret));
}

function wpdmpp_update_cart(){
    if(!isset($_REQUEST['wpdmpp_update_cart']) || (isset($_REQUEST['wpdmpp_update_cart']) && $_REQUEST['wpdmpp_update_cart']<=0)) return;
    //here i need to change...
//    echo "<pre>";
//    print_r($_POST['cart_items']);
//    echo "</pre>";
//    die();

    $data = $_POST['cart_items'];
    $cart_data = wpdmpp_get_cart_data(); //get previous cart data

    foreach ($cart_data as $pid => $cdt){
        if(!$pid || get_post_type($pid)!='wpdmpro') { unset($cart_data[$pid]); continue; }
        if(isset($data[$pid]['coupon']) && trim($data[$pid]['coupon']) != '') {
            $cart_data[$pid]['coupon'] = stripslashes($data[$pid]['coupon']);


        }
        else {
            unset($cart_data[$pid]['coupon']);
        }

        if(isset($data[$pid]['item'])) {
            //print_r($data[$pid]['item']);
            foreach ($data[$pid]['item'] as $key => $val){
                if(isset($val['quantity'])) {
                    if($val['quantity']<1) $val['quantity'] = 1;

                    $cart_data[$pid]['item'][$key]['quantity'] = $val['quantity'];
                }

                if(isset($cart_data[$pid]['item'][$key]['coupon_amount'])) {
                    unset($cart_data[$pid]['item'][$key]['coupon_amount']);
                }
                if(isset($cart_data[$pid]['item'][$key]['discount_amount'])) {
                    unset($cart_data[$pid]['item'][$key]['discount_amount']);
                }
            }
        }
        else {
            if(isset($data[$pid]['quantity'])) {
                if($data[$pid]['quantity']<1) $data[$pid]['quantity'] = 1;
                $cart_data[$pid]['quantity'] = $data[$pid]['quantity'];
            }

            if(isset($cart_data[$pid]['coupon_amount'])) {
                unset($cart_data[$pid]['coupon_amount']);
            }
        }
    }

    wpdmpp_update_cart_data($cart_data);


    $ret['cart_subtotal'] = wpdmpp_get_cart_subtotal();
    $ret['cart_discount'] = wpdmpp_get_cart_discount();
    $ret['cart_total'] = wpdmpp_get_cart_total();
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        die(json_encode($ret));
    }
    header("location: ".wpdmpp_cart_page());
}

function wpdmpp_cart_id(){
    global $current_user;
    $cart_id = null;
    if(is_user_logged_in()){
        $cart_id = $current_user->ID."_cart";
    } else {
        $cart_id = md5($_SERVER['REMOTE_ADDR'])."_cart";
    }

    return $cart_id;

}

function wpdmpp_get_cart_data(){
    global $current_user;
    if(is_user_logged_in()){
        $cart_id = $current_user->ID."_cart";
    } else {
        $cart_id = md5($_SERVER['REMOTE_ADDR'])."_cart";
    }
    $cart_data = maybe_unserialize(get_option($cart_id));

    //adjust cart id after user log in
    if(is_user_logged_in()&&!$cart_data){
        $cart_id = md5($_SERVER['REMOTE_ADDR'])."_cart";
        $cart_data = maybe_unserialize(get_option($cart_id));
        delete_option($cart_id);
        $cart_id = $current_user->ID."_cart";
        update_option($cart_id, $cart_data);
    }

    return $cart_data?$cart_data:array();
}

function wpdmpp_update_cart_data($cart_data){
    //echo "<pre>";
    //print_r($cart_data);
    //die();
    global $current_user;
    if(is_user_logged_in()){
        $cart_id = $current_user->ID."_cart";
    } else {
        $cart_id = md5($_SERVER['REMOTE_ADDR'])."_cart";
    }

    //check enable stock or not
    $settings = maybe_unserialize(get_option('_wpdmpp_settings'));
    //check if quantity of product is more than stock

    //here i need to develop for multiple product
    /**  disabled for digital products
    if($settings['stock']['enable']==1){
        foreach($cart_data as $pid=>$cartitem){
            $post_meta=array();
            $post_meta=get_post_meta($pid,"wpdmpp_list_opts",true);
            if($post_meta['manage_stock']==1){
                if(isset($cartitem['item'])){
                    $cnt = 0;
                    //
                    foreach($cartitem['item'] as $a => $b):
                        $cnt += $b['quantity'];
                    endforeach;
                    if($cnt>$post_meta['stock_qty']){
                        $totstock = $post_meta['stock_qty'];
                        foreach($cartitem['item'] as $a => $b):
                            if($b['quantity']>$totstock){
                                $cart_data[$pid]['item'][$a]['qunatity'] = $totstock;
                                //now if another product exist 
                                //do whatever you want to do man

                            }
                            $totstock -= $b['quantity'];

                        endforeach;
                    }
                }
                else{
                    if($cartitem['quantity']>$post_meta['stock_qty']){
                        $cart_data[$pid]['quantity'] = $post_meta['stock_qty'];
                    }
                }


            }
        }
    }
    */
    $cart_data = update_option($cart_id, $cart_data);
    return $cart_data;
}

function wpdmpp_get_cart_items(){
    global $current_user, $wpdb;
    $cart_data = wpdmpp_get_cart_data();
    return ($cart_data);
}

function wpdmpp_calculate_discount(){
    //echo "I'm here ";
    global $current_user;
    $role = is_user_logged_in()?$current_user->roles[0]:'guest';
    $discount_r = 0;
    $cart_items = wpdmpp_get_cart_items();
    $total = 0;
    $currency_sign = wpdmpp_currency_sign();
    if(is_array($cart_items)){
        //$lprices = array();
        foreach($cart_items as $pid=>$item)    {

            if(!is_array($cart_items[$pid])) $cart_items[$pid] = array();
            $cart_items[$pid]['ID'] = $pid;
            $cart_items[$pid]['post_title'] = get_the_title($pid);

            $prices=0;
            $variations="";
            $svariation = array();
            $lvariation = array();
            $lvariations = array();
            $lprices = array();

            $discount = get_post_meta($pid,"__wpdm_discount",true);
            $base_price = get_post_meta($pid,"__wpdm_base_price",true);
            $sales_price = get_post_meta($pid,"__wpdm_sales_price",true);
            $price_variation = get_post_meta($pid,"__wpdm_price_variation",true);
            $variation = get_post_meta($pid,"__wpdm_variation",true);
            if(is_array($variation) && count($variation)>0){
            foreach($variation as $key=>$value){
                foreach($value as $optionkey=>$optionvalue){
                    if($optionkey!="vname" && $optionkey != 'multiple'){

                        if(isset($item['multi']) && ($item['multi'] == 1)){

                            foreach ($item['item'] as $a => $b) { //different variations, $b is single variation contain variation and quantity
                                if($b['variation']):
                                    $lprices[$a] = isset($lprices[$a])?$lprices[$a]:0;
                                    foreach ($b['variation'] as $c):
                                        if($c == $optionkey) {
                                            $lprices[$a] += $optionvalue['option_price'];
                                            $lvariation[$a][] = $optionvalue['option_name'].": ".($optionvalue['option_price']>0?'+':'').$currency_sign.number_format(floatval($optionvalue['option_price']),2,".","");
                                        }
                                    endforeach;
                                endif;

                            }
                        }

                        else{
                            if(isset($item['variation']))
                                foreach($item['variation'] as $var){
                                    if($var==$optionkey){
                                        $prices+=$optionvalue['option_price'];
                                        $svariation[] = $optionvalue['option_name'].": ".($optionvalue['option_price']>0?'+':'').$currency_sign.number_format(floatval($optionvalue['option_price']),2,".","");
                                    }
                                }
                        }


                    }
                }
            }
            }






            if(isset($item['coupon']) && trim($item['coupon'])!='') $valid_coupon=check_coupon($pid,$item['coupon']);
            else $valid_coupon = false;
            //echo $valid_coupon . ' :D ';

            if(!isset($item['multi'])){
                $cart_items[$pid]['prices'] = $prices;
                $cart_items[$pid]['variations'] = $svariation;
                if(is_numeric($valid_coupon) && $valid_coupon != false) {
                    $cart_items[$pid]['coupon_amount'] =  (($item['price']+$prices)*$item['quantity']*$valid_coupon)/100;
                    $cart_items[$pid]['discount_amount'] = (((($item['price']+$prices)*$item['quantity'] ) - $cart_items[$pid]['coupon_amount'] ) * $discount[$role])/100 ;

                }
                else {

                    $cart_items[$pid]['discount_amount'] = isset($discount[$role])?((($item['price']+$prices)*$item['quantity'] )  * floatval($discount[$role]))/100:0;
                }
                if($valid_coupon == false) {
                    if(isset($item['coupon']) && trim($item['coupon'])!='')
                    $cart_items[$pid]['error'] = "No Valid Coupon Found";
                }
                else {
                    unset($cart_items[$pid]['error']);
                }

            }
            elseif(isset($item['multi']) && $item['multi'] == 1) {

                foreach ($lprices as $key => $value):
                    $cart_items[$pid]['item'][$key]['prices'] = $value;
                    $cart_items[$pid]['item'][$key]['variations'] = $lvariation[$key];

                    if($valid_coupon != 0) {
                        $cart_items[$pid]['item'][$key]['coupon_amount'] =   (($item['price']+$value)*$item['item'][$key]['quantity']*$valid_coupon)/100;
                        $cart_items[$pid]['item'][$key]['discount_amount'] =   (((($item['price']+$value)*$item['item'][$key]['quantity']) - $cart_items[$pid]['item'][$key]['coupon_amount'])* $opt['discount'][$role])/100 ;
                    }
                    else {
                        $cart_items[$pid]['item'][$key]['discount_amount'] =   ((($item['price']+$value)*$item['item'][$key]['quantity'])* $discount[$role])/100 ;
                    }

                    if($valid_coupon == false) {
                        if(isset($item['coupon']) && trim($item['coupon'])!='')
                        $cart_items[$pid]['item'][$key]['error'] = "No Valid Coupon Found";
                    }

                endforeach;
            }



            //

        }
        wpdmpp_update_cart_data($cart_items);
    }
}



function wpmpp_get_cart_total(){
    $cart_items = wpdmpp_get_cart_items();

    $total = 0;
    if(is_array($cart_items)){

        foreach($cart_items as $pid=>$item)    {
            if(isset($item['item'])){
                foreach ($item['item'] as $key => $val){
                    $role_discount = isset($val['discount_amount']) ? $val['discount_amount']: 0;
                    $coupon_discount = isset($val['coupon_amount']) ? $val['coupon_amount']: 0;
                    $val['prices'] = isset($val['prices']) ? $val['prices']: 0;
                    $total += (($item['price'] + $val['prices']) * $val['quantity']) - $role_discount - $coupon_discount;
                }
            }
            else {
                $role_discount = isset($item['discount_amount']) ? $item['discount_amount']: 0;
                $coupon_discount = isset($item['coupon_amount']) ? $item['coupon_amount']: 0;
                $total += (($item['price'] + $item['prices'])* $item['quantity']) - $role_discount - $coupon_discount;
            }
        }

    }

    $total = apply_filters('wpdmpp_cart_subtotal',$total);
    return number_format($total,2,".","");
}


function wpdmpp_get_cart_subtotal(){
    $cart_items = wpdmpp_get_cart_items();

    $total = 0;
    if(is_array($cart_items)){

        foreach($cart_items as $pid=>$item)    {
            if(isset($item['item'])){
                foreach ($item['item'] as $key => $val){
                    $role_discount = isset($val['discount_amount']) ? $val['discount_amount']: 0;
                    $coupon_discount = isset($val['coupon_amount']) ? $val['coupon_amount']: 0;
                    $val['prices'] = isset($val['prices']) ? $val['prices']: 0;
                    $total += (($item['price'] + $val['prices'] - $role_discount - $coupon_discount)*$item['quantity']);
                }
            }
            else {
                $role_discount = isset($item['discount_amount']) ? $item['discount_amount']: 0;
                $coupon_discount = isset($item['coupon_amount']) ? $item['coupon_amount']: 0;
                $total += (($item['price'] + $item['prices'] - $role_discount - $coupon_discount)*$item['quantity']);
            }
        }

    }

    $total = apply_filters('wpdmpp_cart_subtotal',$total);
    return number_format($total,2,".","");
}


//calculating discount
function wpdmpp_get_cart_discount(){
    global $current_user;

    $role = is_user_logged_in()?$current_user->roles[0]:'guest';
    //$subtotal = wpdmpp_get_cart_subtotal();
    $cart_items = wpdmpp_get_cart_items();
    $discount_r=0;

    //print_r($cart_items);
    foreach($cart_items as $pid=>$item){

        $opt = get_post_meta($pid,'wpdmpp_list_opts',true);
        //print_r($opt);
        $prices=0;
        $lprices = array();

        $discount = get_post_meta($pid,"__wpdm_discount",true);
        $base_price = get_post_meta($pid,"__wpdm_base_price",true);
        $sales_price = get_post_meta($pid,"__wpdm_sales_price",true);
        $price_variation = get_post_meta($pid,"__wpdm_price_variation",true);
        $variation = get_post_meta($pid,"__wpdm_variation",true);

        if(is_array($variation) && count($variation)>0){
        foreach($variation as $key=>$value){
            foreach($value as $optionkey=>$optionvalue){
                if($optionkey!="vname" && $optionkey != 'multiple'){
                    if(isset($item['variation']) && is_array($item['variation'])){
                        foreach($item['variation'] as $var){
                            if($var==$optionkey){
                                $prices+=$optionvalue['option_price'];

                            }
                        }
                    }

                    elseif(isset($item['item']) && !empty ($item['item'])){

                        foreach ($item['item'] as $a => $b) { //different variations, $b is single variation contain variation and quantity
                            if($b['variation']):
                                $lprices[$a] = isset($lprices[$a])?$lprices[$a]:0;
                                foreach ($b['variation'] as $c):
                                    if($c == $optionkey) {
                                        $lprices[$a] += $optionvalue['option_price'];
                                    }
                                endforeach;
                            endif;

                        }
                    }
                }
            }
        }}

        if(!isset($discount[$role])) $discount[$role] = 0;

        if(!empty($lprices)):
            foreach($lprices as $key => $val):
                $discount_r += ((($item['price']+$val)*$item['item'][$key]['quantity'])*$discount[$role])/100;
            endforeach;
        else:
            $discount_r +=  ((($item['price']+$prices)*$item['quantity'])*$discount[$role])/100;
        endif;


    }
    return number_format($discount_r,2,".","");
}
//calculating subtotal by subtracting discount
function wpdmpp_get_cart_total(){
    return number_format((wpdmpp_get_cart_subtotal()-wpdmpp_get_cart_discount()),2,".","");
}

function wpdmpp_grand_total(){
    $tax=wpdmpp_calculate_tax();
    return number_format((wpdmpp_get_cart_subtotal()+$tax['rate']-wpdmpp_get_cart_discount()),2,".","");
}
//shipping calculation
function wpdmpp_calculate_shipping(){
    $ship=array();
    $order = new Order();
    $order_info=$order->GetOrder($_SESSION['orderid']);
    $ship['method']=$order_info->shipping_method;
    $ship['cost']=$order_info->shipping_cost;
    return $ship;
}
//tax calculation
function wpdmpp_calculate_tax(){
    $cartsubtotal=wpdmpp_get_cart_subtotal();
    $taxr=array();
    $order = new Order();
    $order_info=$order->GetOrder($_SESSION['orderid']);
    $bdata=unserialize($order_info->billing_shipping_data);
    $settings = maybe_unserialize(get_option('_wpdmpp_settings'));
    if($settings['tax']['enable']==1){
        if($settings['tax']['tax_rate']){
            foreach($settings['tax']['tax_rate'] as $key=> $rate){
                if($rate['country']){
                    foreach($rate['country'] as $r_country){
                        if($r_country==$bdata['shippingin']['country']){
                            $taxr['label']= $rate['label'];
                            $taxr['rate']= (($cartsubtotal*$rate['rate'])/100);
                            break;
                        }
                    }
                }
            }
        }
    }

    return $taxr;
}

function wpdmpp_empty_cart(){
    global $current_user;
    if(is_user_logged_in()){
        get_currentuserinfo();
        $cart_id = $current_user->ID."_cart";
    } else {
        $cart_id = md5($_SERVER['REMOTE_ADDR'])."_cart";
    }
    delete_option($cart_id);
    if(isset($_SESSION['orderid'])){
        $_SESSION['last_order'] = $_SESSION['orderid'];
        $_SESSION['orderid'] = '';
        unset($_SESSION['orderid']);
    }
}

function wpdmpp_checkout(){
    wp_enqueue_script('jquery');
    $settings = get_option('_wpdmpp_settings');
    include(WP_PLUGIN_DIR."/wpmarketplace/tpls/checkout.php");
}

function wpdmpp_addtocart_js(){
    if(get_option('wpdmpp_ajaxed_addtocart',0)==0) return;
    ?>
    <script language="JavaScript">
        <!--
        jQuery(function(){
            jQuery('.wpdm-pp-add-to-cart-link').click(function(){
                if(this.href!=''){
                    var lbl;
                    var obj = jQuery(this);
                    lbl = jQuery(this).html();
                    jQuery(this).html('<img src="<?php echo plugins_url();?>/wpdm-premium-packages/images/wait.gif"/> adding...');
                    jQuery.post(this.href,function(){
                        obj.html('added').unbind('click').click(function(){ return false; });
                    })

                }
                return false;
            });

            jQuery('.wpdm-pp-add-to-cart-form').submit(function(){

                var form = jQuery(this);
                var fid = this.id;
                form.ajaxSubmit({
                    'beforeSubmit':function(){
                        jQuery('#submit_'+fid).val('adding...').attr('disabled','disabled');
                    },
                    'success':function(res){
                        jQuery('#submit_'+fid).val('added').attr('disabled','disabled');
                    }
                });

                return false;
            });
        });
        //-->
    </script>
<?php
}


function wpdmpp_buynow($content){
    global $wpdb, $post, $wp_query, $current_user;
    $settings = maybe_unserialize(get_option('_wpdmpp_settings'));
    if(!isset($wp_query->query_vars['wpmarketplace'])||$wp_query->query_vars['wpmarketplace']==''||!isset($_REQUEST['buy'])||$_REQUEST['buy']=='')
        return $content;
    @extract(get_post_meta($post->ID,"wpdmpp_list_opts",true));
    wpdmpp_add_to_cart($post->ID, $_REQUEST['buy']);
    return '';
}

function update_os(){
    global $wpdb;

    if(!current_user_can(WPDMPP_MENU_ACCESS_CAP)) return;

    //order status change hook, order_id, new_status_message
    //apply_filters("order_status_completed",$_POST['order_id'],$_POST['status']);

    $order = new Order();
    $order->Update(array('order_status'=>$_POST['status']),$_POST['order_id']);

    $settings = maybe_unserialize(get_option('_wpdmpp_settings'));


    //reduce stock 
//    if($settings['stock']['enable']==1){
//        if($_POST['status']=="Completed"){
//            if($settings['stock']['reduce_auto']==1)
//                wpdmpp_reduce_stock($_POST['order_id']);
//        }
//    }

    $siteurl=home_url("/");
    //email to customer of that order
    $userid=$wpdb->get_var("select uid from {$wpdb->prefix}mp_orders where order_id='".$_POST['order_id']."'");
    $user_info = get_userdata($userid);
    $admin_email=get_bloginfo("admin_email");
    //$from=home_url("/");
    $email = array();
    $subject="Order Status Changed";
    $message="The order {$_POST['order_id']} is changed to {$_POST['status']}";
    $email['subject']=$subject;
    $email['body']=$message;
    $email['headers'] = 'From:  <'.$admin_email.'>' . "\r\n";
    $email = apply_filters("order_status_change_email", $email);
    //wp_mail($user_info->user_email,$email['subject'],$email['body'],$email['headers']);
    //wp_mail($admin_email,$email['subject'],$email['body'],$email['headers']);
    //print_r($email);   
    die(__('Order status updated',"wpmarketplace"));
}

function update_ps(){
    $order = new Order();
    if(!current_user_can(WPDMPP_MENU_ACCESS_CAP)) return;
    $order->Update(array('payment_status'=>$_POST['status']),$_POST['order_id']);
    die(__('Payment status updated',"wpmarketplace"));
}



function PayNow($post_data){
    global $wpdb,$current_user;
    get_currentuserinfo();
    $order = new Order();
    $corder = $order->GetOrder($post_data['order_id']);
    $payment = new Payment();
    if(!isset($post_data['payment_method']) || $post_data['payment_method']=='')  $post_data['payment_method'] = $corder->payment_method;
    $post_data['payment_method'] = $post_data['payment_method']?$post_data['payment_method']:'PayPal';
    $payment->InitiateProcessor($post_data['payment_method']);
    $payment->Processor->OrderTitle = 'WPMP Order# '.$corder->order_id;
    $payment->Processor->InvoiceNo = $corder->order_id;
    $payment->Processor->Custom = $corder->order_id;
    $payment->Processor->Amount = number_format($corder->total,2,".","");
    echo $payment->Processor->ShowPaymentForm(1);
}

function ProcessOrder(){
    global $current_user;
    get_currentuserinfo();
    $order = new Order();
    if(preg_match("@\/payment\/([^\/]+)\/([^\/]+)@is",$_SERVER['REQUEST_URI'],$process)){
        $gateway = $process[1];
        $page = $process[2];
        $_POST['invoice'] = array_shift(explode("_",$_POST['invoice']));
        $odata = $order->GetOrder($_POST['invoice']);
        $current_user = get_userdata($odata->uid);
        $uname = $current_user->display_name;
        $uid = $current_user->ID;
        $email = $current_user->user_email;

        $myorders = get_option('_wpdmpp_users_orders',true);
        if($page=='notify'){
            if(!$uid) {
                $uname = str_replace(array("@",'.'),'',$_POST['payer_email']);
                $password = $_POST['invoice'];
                $email = $_POST['payer_email'];
                $uid = wp_create_user($uname,$password,$_POST['payer_email']);
                $logininfo = "
         Username: $uname<br/>
         Password: $password<br/>
        ";
            }


            $order->Update(array('order_status'=>$_POST['payment_status'],'payment_status'=>$_POST['payment_status'],'uid'=>$uid), $_POST['invoice']);

            $sitename = get_option('blogname');
            $message = <<<MAIL
                    Hello {$uname},<br/>
                    Thanks for your business with us.<br/>                    
                    Please <a href="{$myorders}">click here</a> to view your purchased items.<br/>
                    {$myorders} <br/>
                    {$logininfo}                    
                    <br/><br/>
                    Regards,<br/>
                    Admin<br/>
                    <b>{$sitename}</b>
                    
MAIL;
            $headers = 'From: '.get_option('blogname').' <'.get_option('admin_email').'>' . "\r\n\\";
            wp_mail( $email, "You order on ".get_option('blogname'), $message, $headers, $attachments );
            die("OK");
        }

        if($page=='return'&&$_POST['payment_status']=='Completed'){
            if(!$current_user->ID){
                $uname = str_replace(array("@",'.'),'',$_POST['payer_email']);
                $password = $_POST['invoice'];
                $creds = array();
                $creds['user_login'] = $uname;
                $creds['user_password'] = $password;
                $creds['remember'] = true;
                $user = wp_signon( $creds, false );
            }
            die("<script>location.href='$myorders';</script>");
        }

        die();
    }
}

function get_all_coupon($data){
    $total = 0;
    if(!is_array($data)) return 0;
    foreach($data as $pid => $item){
        $valid_coupon=isset($item['coupon'])?check_coupon($pid,$item['coupon']):0;
        if($valid_coupon != 0) {

            $total +=  ($item['price']*$item['quantity']*($valid_coupon/100));
        }
    }
    return $total;

}


function wpdmpp_add_to_cart_html($post_id){
    global $current_user;
    //$pinfo = get_post_meta($post_id,"wpdmpp_list_opts",true);
    $discount = get_post_meta($post_id,"__wpdm_discount",true);
    $base_price = get_post_meta($post_id,"__wpdm_base_price",true);
    $sales_price = get_post_meta($post_id,"__wpdm_sales_price",true);
    $price_variation = get_post_meta($post_id,"__wpdm_price_variation",true);
    $variation = get_post_meta($post_id,"__wpdm_variation",true);
    //@extract($pinfo);
    //check settings for the stock enable or not
    $settings = maybe_unserialize(get_option('_wpdmpp_settings'));
    $cart_enable="";

    $cart_enable = apply_filters("wpdmpp_cart_enable", $cart_enable,$post_id);
    //$cart_enable...


    $currency_sign = wpdmpp_currency_sign();
    $discount = is_user_logged_in()&&isset($discount[$current_user->roles[0]])?$discount[$current_user->roles[0]]:0;
    $base_price = (double)$base_price;

    $prices_text = apply_filters('price_text',__('Price','wpmarketplace'));
    $prices = <<<PRICE
        <form method="post" action="" name="cart_form" class="wpdm_cart_form" id="wpdm_cart_form_{$post_id}">
        <input type="hidden" name="add_to_cart" value="add">
        <input type="hidden" name="pid" value="$post_id">
        <input type="hidden" name="discount" value="$discount">

        <div class='wpdm-prices'>


PRICE;

    $script='<script> ';
    $price_html = number_format($base_price,2,".","");
    if($sales_price>0) $price_html = "<sub><strike>{$currency_sign}{$price_html}</strike></sub> ".'<div itemprop="offers" itemscope itemtype="http://schema.org/Offer"><span itemprop="priceCurrency" content="".wpdmpp_currency_code().">'.$currency_sign.'</span><span itemprop="price" content="'.number_format($sales_price,2).'">'.number_format($sales_price,2,".","").'</span></div>';
    else $price_html = '<div itemprop="offers" itemscope itemtype="http://schema.org/Offer"><span itemprop="priceCurrency" content="USD">'.$currency_sign.'</span><span itemprop="price" id="price-'.$post_id.'" content="'.number_format($base_price,2).'" >'.$price_html.'</span></span></div>';
    if($base_price==0) $price_html = __('Free', 'wpmarketplace');
    $prices.='<div class="wpmp-regular-price"><span class="prices-text" >'.$prices_text .'</span><h3>'.$price_html.'</h3></div>';
    if($price_variation){
        foreach($variation as $key=>$value){
            $vtype = "radio";
            if(isset($value['multiple'])){
                $multiple = "multiple='multiple'";
                $vtype = "checkbox";
            }
            else $multiple = "";
            $prices.='<fieldset class="wpdmpp-price-variations"><legend>'.ucfirst($value['vname']).'</legend><div class="fieldset-contents">';

            $variations_html = '<select name="variation[]" id="var_price_'.uniqid().'"' . $multiple .' >';

            foreach($value as $optionkey=>$optionvalue){
                if(is_array($optionvalue)){
                    $vari = (intval($optionvalue['option_price'])!=0)?" ( + {$currency_sign}".number_format($optionvalue['option_price'],2,".","")." )":"";
                    $variations_html .='<option value="'.$optionkey.'">'." ".$optionvalue['option_name'].$vari.'</option>';
                }
            }
            $variations_html .= '</select>';

            $variations_html = "";
            foreach($value as $optionkey=>$optionvalue){
                if(is_array($optionvalue)){
                    $optionvalue['option_price'] = floatval($optionvalue['option_price']);
                    $vindex = $vtype == 'radio'?$key:'';
                    $vari = (intval($optionvalue['option_price'])!=0)?" ( + {$currency_sign}".number_format($optionvalue['option_price'],2,".","")." )":"";
                    $variations_html .='<label class="eden-'.$vtype.'"><input type='.$vtype.' data-product-id="'.$post_id.'" data-price="'.number_format($optionvalue['option_price'],2,".","").'" name="variation['.$vindex.']" class="price-variation price-variation-'.$post_id.'"  value="'.$optionkey.'"><span><i class="fa fa-check"></i></span> '." ".$optionvalue['option_name'].$vari."</label>";
                }
            }

            $prices .= $variations_html . '</div></fieldset>';
        }
    }

    if($discount>0){
        $discount_msg = "$discount% discount";
        $prices .=  "<div class='discount-msg'><span class='label label-info'>$discount_msg ".__("will be applied on all prices","wpmarketplace")."</span></div>";
    }
    $message="";

    $messages = apply_filters("wpdmpp_product_pricing_message",$message, $post_id);

    $prices .= $messages;
    if(isset($settings['instant_download']) && $settings['instant_download']==1 && $base_price==0)
        $prices .= '<a href="'.home_url('/?wpmpfile='.$post_id).'" class="btn btn-success btn-download">'.__('Download','wpmarketpalce').'</a>';
    else
        $prices.='<div class="clearboth"></div><div data-curr="'.$currency_sign.'" id="total-price-'.$post_id.'"></div><div class="clearboth"></div><div class="add-to-cart-button"><div class="btn-group"><button '.$cart_enable.' class="btn btn-primary btn-addtocart" data-cart-redirect="'.(isset($settings['wpdmpp_after_addtocart_redirect'])?'on':'off').'" type="submit" id="cart_submit"><i class="fa fa-shopping-cart"></i> '.__("Add to Cart","wpmarketplace").'</button></div></div>';

    $prices .= <<<PRICE


        </div>
        </form>
PRICE;

    $prices = apply_filters("wpdmpp_product_price",$prices);

    return $prices;
}

/**
 * @param $post
 * @param string $btnclass
 * @return string
 */
function wpdmpp_waytocart($post, $btnclass = 'btn-info'){
    $post = (array) $post;
    $price_variation = get_post_meta($post['ID'], '__wpdm_price_variation', true);
    if(!$price_variation && wpdmpp_product_price($post['ID'])==0) return '<a href="'.get_permalink($post['ID']).'" class="btn '.$btnclass.'  btn-addtocart" ><i class="fa fa-download icon-white"></i> '.__("Download","wpmarketplace").'</a>';
    if($price_variation)
        $html = "<a href='".get_permalink($post['ID'])."' class='btn $btnclass' ><i class='fa fa-shopping-cart icon-white'></i> ".__("Add to Cart","wpmarketplace")."</a>";
    else{
        $html = <<<PRICE
                        <form method="post" action="" name="cart_form" class="wpdm_cart_form" id="wpdm_cart_form_{$post['ID']}">
                        <input type="hidden" name="add_to_cart" value="add">
                        <input type="hidden" name="pid" value="{$post['ID']}">

PRICE;

        $html.='<div class="btn-group"><button class="btn '.$btnclass.'  btn-addtocart" type="submit" ><i class="fa fa-shopping-cart icon-white"></i> '.__("Add to Cart","wpmarketplace").'</button></div></form>';

    }
    return $html;
}


/**
 * @param $user_login
 * @param $user
 */
function wpdmpp_clear_user_cartdata($user_login, $user) {
    delete_option($user->ID."_cart");
}
add_action('wp_login', 'wpdmpp_clear_user_cartdata', 10, 2);


function wpdmpp_array_diff($a, $b){
//    echo "<pre>";
//    print_r($a);
//    print_r($b);
//    echo "</pre>";
//    die();
    if(is_array($a)&&is_array($b)){
        if(count($a) != count($b)) {
            return false;
        }
        else {
            sort($a); sort($b);
            return $a == $b  ;
        }

    }
    else if($a == "" && $b == ""){
        return true;
    }
}