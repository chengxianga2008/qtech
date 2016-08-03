<?php
$settings = get_option('_wpdmpp_settings');
$currency_sign = wpdmpp_currency_sign();

$cart = "<div class='w3eden'>"
    . "<form method='post' class='abc' action='' name='cart_form'>"
    . "<input type='hidden' name='wpdmpp_update_cart' value='1' />"
    . "<table class='wpdm_cart table'>"
    . "<tr class='cart_header'>"
    . "<th style='width:20px !important'></th>"
    . "<th>".__("Title","wpmarketplace")."</th>"
    . "<th>".__("Unit Price","wpmarketplace")."</th>"
    . "<th> ".__("Role Discount","wpmarketplace")."</th>"
    . "<th> ".__("Coupon Code","wpmarketplace")."</th>"
    . "<th>".__("Quantity","wpmarketplace")."</th>"
    . "<th class='amt'>".__("Total","wpmarketplace")."</th>"
    . "</tr>";

if(is_array($cart_data)){
    //print_r($cart_data);
    foreach($cart_data as $item){
        //echo "<pre>" ;  print_r($item); echo "</pre>";
        //filter for adding various message after cart item
        $cart_item_info="";
        $cart_item_info = apply_filters("wpdmpp_cart_item_info", $cart_item_info, $item['ID']);
        if(isset($item['item']) && !empty($item['item'])):

            foreach ($item['item'] as $key => $var):
                //echo "<pre>" ;  print_r($item['item']); echo "</pre>";
                if(isset($var['coupon_amount']) && $var['coupon_amount'] != ""){
                    $discount_amount=$var['coupon_amount'];
                    $discount_style="style='color:#008000; text-decoration:underline;'";
                    $discount_title='Discounted $'.$discount_amount." for coupon code '{$item['coupon']}'";
                } else{
                    $discount_amount="";
                    $discount_style="";
                    $discount_title="";
                    $var['coupon_amount'] = 0;

                }

                if(isset($var['error']) && $var['error'] != ""){
                    $coupon_style="border:1px solid #ff0000;";
                    $title=$var['error'];

                } else {
                    $coupon_style="";
                    $title="";

                }

                if(!isset($item['coupon'])) $item['coupon'] = '';
                if(!isset($var['discount_amount'])) $var['discount_amount'] = 0;
                if(!isset($var['prices'])) $var['prices'] = 0;

                $variation = isset($var['variations'])?"<small><i>".implode(", ",$var['variations'])."</i></small>":"";
                $cart .= "<tr id='cart_item_{$item['ID']}_{$key}'>"
                    . "<td>"
                    . "<a class='wpdmpp_cart_delete_item btn btn-xs btn-danger' href='#' onclick='return wpdmpp_pp_remove_cart_item2({$item['ID']},{$key})'>"
                    . "<i class='fa fa-trash-o'></i>"
                    . "</a>"
                    . "</td>"
                    . "<td class='cart_item_title'>{$item['post_title']}<br>$variation".$cart_item_info ."</td>"
                    . "<td class='cart_item_unit_price' $discount_style ><span class='ttip' title='$discount_title'>".$currency_sign.number_format($item['price'],2,".","")."</span></td>"
                    . "<td class='' >"  .$currency_sign.number_format($var['discount_amount'],2,'.','') . "</td>"
                    . "<td><input style='width:100px;$coupon_style' title='$title' type='text' name='cart_items[{$item['ID']}][coupon]' value='{$item['coupon']}' id='coupon_{$item['ID']}' class='ttip input-sm form-control' size=3 /></td>"
                    . "<td class='cart_item_quantity'><input type='number'  style='width:60px' min='1' name='cart_items[$item[ID]][item][$key][quantity]' value='{$item['item'][$key]['quantity']}' size=3 class=' input-sm form-control' /></td>"
                    . "<td class='cart_item_subtotal amt'>".$currency_sign.number_format((($item['price']+$var['prices'])*$var['quantity'])-$var['discount_amount'] - $var['coupon_amount'],2,".","")."</td>"
                    . "</tr>";
            endforeach;

        else:
            //echo "<pre>";        print_r($item); echo "</pre>";

                $variations = isset($item['variations'])?"<small><i>".implode(", ",$item['variations'])."</i></small>":'';

            if(isset($item['coupon_amount'])){
                $discount_amount=$item['coupon_amount'];
                $discount_style="style='color:#008000; text-decoration:underline;'";
                $discount_title='Discounted $'.$discount_amount." for coupon code '{$item['coupon']}'";

            } else{
                $discount_amount="";
                $discount_style="";
                $discount_title="";

            }
            if(isset($item['error']) && $item['error']!=''){
                $coupon_style="border:1px solid #ff0000;";
                $title=$item['error'];

            } else {
                $coupon_style="";
                $title="";

            }
            $item['coupon'] = isset($item['coupon'])?$item['coupon']:'';
            $item['coupon_amount'] = isset($item['coupon_amount'])?$item['coupon_amount']:0;
            $cart .= "<tr id='cart_item_{$item['ID']}'>"
                . "<td>"
                . "<a class='wpdmpp_cart_delete_item btn btn-xs btn-danger' href='#' onclick='return wpdmpp_pp_remove_cart_item($item[ID])'>"
                . "<i class='fa fa-trash-o'></i>"
                . "</a>"
                . "</td>"
                . "<td class='cart_item_title'><a target=_blank href='".get_permalink($item['ID'])."'>$item[post_title]</a><br>$variations".$cart_item_info."</td>"
                . "<td class='cart_item_unit_price' $discount_style ><span class='ttip' title='$discount_title'>".$currency_sign.number_format($item['price'],2,".","")."</span></td>"
                . "<td class=''>".$currency_sign.number_format($item['discount_amount'],2,'.','')."</td>"
                . "<td><input style='width:100px;$coupon_style' title='$title' type='text' name='cart_items[{$item['ID']}][coupon]' value='{$item['coupon']}' id='{$item['ID']}' class='ttip input-sm form-control' size=3 /></td>"
                . "<td class='cart_item_quantity'><input  type='number' style='width:60px' min='1' name='cart_items[{$item['ID']}][quantity]' value='$item[quantity]' size=3 class=' input-sm form-control' /></td>"
                . "<td class='cart_item_subtotal amt'>".$currency_sign.number_format((($item['price']+$item['prices'])*$item['quantity'])-$item['coupon_amount'] - $item['discount_amount'],2,".","")."</td>"
                . "</tr>";
        endif;

    }}

wpmpp_get_cart_total();
$extra_row = '';
$cart .= apply_filters('wpdmpp_cart_extra_row',$extra_row);

$cart .= "

<tr><td colspan=6 align=right class='text-right'>".__("Total:","wpmarketplace")."</td><td class='amt' id='wpdmpp_cart_total'>".     $currency_sign.number_format((double)str_replace(',','',wpmpp_get_cart_total()),2)."</td></tr>
<tr><td colspan=2><button type='button' class='btn btn-info ' onclick='location.href=\"".$settings['continue_shopping_url']."\"'><i class='fa fa-white fa-long-arrow-left'></i> &nbsp;".__("Continue Shopping","wpmarketplace")."</button> <button id='save-cart' type='button' class='btn btn-default'><i class='fa fa-save'></i>&nbsp; Save Cart</button></td><td colspan=5 align=right class='text-right'><button class='btn btn-primary' type='button' onclick='document.cart_form.submit();'><i class='fa fa-white fa-check'></i> ".__("Update Cart","wpmarketplace")."</button> <button class='btn btn-success' type='button' id='checkoutbtn' >".__("Checkout","wpmarketplace")." <i class='fa fa fa-check-circle-o'></i></button></td></tr>
</table>

</form></div>
<div id='wpdm-after-cart'></div>
<script language='JavaScript'>

    function  wpdmpp_pp_remove_cart_item(id){
    
           if(!confirm('Are you sure?')) return false;
           jQuery('#save-cart').removeAttr('disabled');
           jQuery('#cart_item_'+id+' *').css('color','#ccc');
           jQuery.post('".home_url('?wpdmpp_remove_cart_item=')."'+id
           ,function(res){ 
           var obj = jQuery.parseJSON(res);
           
           jQuery('#cart_item_'+id).fadeOut().remove(); 
           jQuery('#wpdmpp_cart_total').html(obj.cart_total); 
           jQuery('#wpdmpp_cart_discount').html(obj.cart_discount); 
           jQuery('#wpdmpp_cart_subtotal').html(obj.cart_subtotal); });
           return false;
    }
    function  wpdmpp_pp_remove_cart_item2(id,item){
           if(!confirm('Are you sure?')) return false;
           jQuery('#cart_item_'+id+'_'+item+' *').css('color','#ccc');
           jQuery.post('".home_url('?wpdmpp_remove_cart_item=')."'+id + '&item_id='+item  
           ,function(res){ 
           var obj = jQuery.parseJSON(res);
           jQuery('#save-cart').removeAttr('disabled');
           jQuery('#cart_item_'+id+'_'+item).fadeOut().remove(); 
           jQuery('#wpdmpp_cart_total').html(obj.cart_total); 
           jQuery('#wpdmpp_cart_discount').html(obj.cart_discount); 
           jQuery('#wpdmpp_cart_subtotal').html(obj.cart_subtotal); });
           return false;
    }
    
jQuery(function(){
    jQuery('.ttip').tooltip();
    jQuery('#checkoutbtn').click(function(){
            jQuery(this).attr('disabled','disabled');
            jQuery('#checkoutarea').slideDown();
    });
    jQuery('#save-cart').on('click', function(){
            jQuery(this).attr('disabled','disabled').html('<i class=\"fa fa-spinner fa-spin\"></i> Saving...');
            jQuery.post(location.href, {action: 'wpdm_pp_ajax_call', execute: 'SaveCart'}, function(res){
                jQuery('#wpdm-after-cart').html('<div class=\"panel panel-primary\"><div class=\"panel-body\"><div class=\"input-group\"><span class=\"input-group-addon\"><strong>".__("Saved Cart URL","wpmarketplace")."</strong></span><input type=text readonly=readonly style=\"background: #fff\" onclick=\"this.select()\" id=\"carturl\"  class=\"form-control group-item\" value=\"".wpdmpp_cart_page("savedcart=")."'+res+'\"></div></div><div class=\"panel-footer text-right\"><div class=\"input-group\"><span class=\"input-group-addon\"><i id=\"fae\" class=\"fa fa-envelope\"></i></span><input type=email class=\"form-control group-item\" id=\"cmail\" placeholder=\"".__("Email Address", "wpmarketplace")."\"><span class=\"input-group-btn\"><button id=\"email-cart\" style=\"width:140px\" type=button class=\"btn btn-primary\">".__("Email This Cart", "wpmarketplace")."</button></span></div></div></div></form>');
                jQuery('#save-cart').html('<i class=\"fa fa-check-circle\"></i> Saved');
            })
    });
    jQuery('body').on('click', '#email-cart', function(){
           jQuery('#fae').removeClass('fa-envelope').addClass('fa-spinner fa-spin');
           jQuery('#email-cart').attr('disabled','disabled').html('Sending...');
           jQuery.post(location.href, {action: 'wpdm_pp_ajax_call', execute: 'EmailCart', email: jQuery('#cmail').val(), carturl: jQuery('#carturl').val()}, function(res){
                jQuery('#fae').removeClass('fa-spinner fa-spin').addClass('fa-envelope');
                jQuery('#email-cart').html('Sent');
           });
    });
});
      

</script>

";

if(count($cart_data)==0) $cart = "<div class='panel panel-default'><div class='panel-body text-danger'>". __("No item in cart.","wpmarketplace")."</div><div class='panel-footer text-right'><a class='btn btn-sm btn-primary' href='".$settings['continue_shopping_url']."'>".__("Continue Shopping","wpmarketplace")." &nbsp;<i class='fa fa-long-arrow-right'></i></a></div></div>";
