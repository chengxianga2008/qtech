<?php
    global $payment_methods;
    ob_start();

?>
<div id="select-payment-method">
<form action="" name="payment_form" id="payment_form" method="post" class="text-right well">
<div class="a-item" id="csp">
<?php if(!is_user_logged_in()): ?>
<div class="pull-left text-left">
    <input type="email" class="form-control" name="order_email" id="email_m" style="width: 300px" placeholder="<?php echo __("Enter Order Notification Email","wpmarketplace"); ?>">
</div>
<?php endif; ?>
<label for="payment_m"><?php echo __("Select Payment Method:","wpmarketplace"); ?></label>
<select name="payment_m" id="payment_method" class="form-control" style="display: inline;width:200px">
  <?php

    $settings = maybe_unserialize(get_option('_wpdmpp_settings'));
    $payment_methods = apply_filters('payment_method', $payment_methods);

    $payment_methods = isset($settings['pmorders']) && count($settings['pmorders']) == count($payment_methods)?$settings['pmorders']:$payment_methods;
  foreach($payment_methods as $payment_method){
        if(class_exists($payment_method)){
            if(isset($settings[$payment_method]['enabled']) && $settings[$payment_method]['enabled'] == 1){
                $obj = new $payment_method();
                $name = isset($obj->GatewayName)?$obj->GatewayName:$payment_method;
                echo '<option value="'.$payment_method.'" >'.$name.'</option>';

            }
        }
    }
    
  ?>
</select>
<input type="hidden" name="payment_method" id="payment__method">
<button id="pay_btn" style="width: 100px" class="button btn btn-success" type="submit"><?php echo __("Pay Now","wpmarketplace");?></button> <div class="hide pull-right" id="payment_w8"><img src='<?php echo admin_url('/images/loading.gif'); ?>' /></div>
</div>
</form><br/>
<div id="paymentform"></div>
<script type="text/javascript">
window.onload=pay_method();
jQuery('#payment_method').change(function(){
    //jQuery('#pay_btn').html("<i class='fa fa-spinner fa-spin'></i>").attr('disabled','disabled');
    pay_method();
});

function pay_method(){
    jQuery('#payment__method').val(jQuery('#payment_method').val());
}

               
      jQuery('#payment_form').submit(function(){

          jQuery('#pay_btn').attr('disabled','disabled').html('<i class="fa fa-spin fa-spinner"></i>').css('outline','none');
          jQuery('#wpdmpp-cart-form .btn').attr('disabled','disabled');
           jQuery(this).ajaxSubmit({
               'url': '<?php echo home_url("/?task=paynow");?>',
               'beforeSubmit':function(){
                   //jQuery('#payment_w8').fadeIn();
               },
               'success':function(res){
                   //var obj = jQuery.parseJSON(res); 
                   //alert(obj.success);
                   jQuery('#paymentform').html(res);
                    if(res.match(/error/)){
                        alert(res);
                   
                    }else{
                      jQuery('#payment_w8').fadeOut();
                       
                    }
               }
           });
      return false;
      });
</script>
</div>
<?php
$payment_html = ob_get_clean();
