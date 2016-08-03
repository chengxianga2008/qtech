<div style="clear: both;margin-top:20px ;"></div>

<input type="hidden" name="action" value="wpdmpp_save_settings"> 
<div>
<?php
    global $wpdb;
    $countries=$wpdb->get_results("select * from {$wpdb->prefix}ahm_country order by country_name");
?>
    <div class="panel panel-default">    
        <div class="panel-heading"><b>Base Country</b></div>
        <div class="panel-body">
            <select class="chosen" name="_wpdmpp_settings[base_country]">
                <option><?php _e('---Select Country---','wpmarketplace'); ?></option>
                <?php
                foreach ($countries as $country) { $country->country_name = strtolower($country->country_name);
                    ?>
                <option <?php if (isset($settings['base_country']) && $settings['base_country'] == $country->country_code) echo 'selected=selected' ?> value="<?php echo $country->country_code; ?>"><?php echo ucwords($country->country_name); ?></option>
                    <?php
                }
                ?>
            </select><br />
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading"><b><?php echo __("Allowed Countries", "wpmarketplace"); ?></b></div> 
        <div class="panel-body">

            <ul id="listbox" style="height: 200px;overflow: auto;">
                <li><label for="allowed_cn"><input type="checkbox" name="allowed_cn_all" id="allowed_cn" /> Select All/None</label></li>
                <?php
                foreach ($countries as $country) { $country->country_name = strtolower($country->country_name);
                    ?>
                <li><label><input <?php
                            $select = '';
                            if (isset($settings['allow_country'])){
                                foreach ($settings['allow_country'] as $ac) {
                                    if ($ac == $country->country_code) {
                                        $select = 'checked="checked"';
                                        break;
                                    } else
                                        $select = '';
                                }} echo $select;
                            ?> type="checkbox" name="_wpdmpp_settings[allow_country][]" value="<?php echo $country->country_code; ?>"><?php echo " " . ucwords($country->country_name); ?></label></li>

                    <?php
                }
                ?>
            </ul>
        </div>
    </div>
<div class="panel panel-default">
    <div class="panel-heading"><b><?php echo __("Frontend Settings","wpmarketplace");?></b></div>
    <div class="panel-body">

        <label><input type="checkbox" name="_wpdmpp_settings[guest_checkout]" <?php if(isset($settings['guest_checkout']) && $settings['guest_checkout']==1) echo 'checked=checked' ?> value="1"> <?php echo __("Enable Guest Checkout","wpmarketplace");?></label><br/>
        <input type="hidden" name="_wpdmpp_settings[guest_download]"  value="0">
        <label><input type="checkbox" name="_wpdmpp_settings[guest_download]" <?php if(isset($settings['guest_download']) && $settings['guest_download']==1) echo 'checked=checked' ?> value="1"> <?php echo __("Enable Guest Download","wpmarketplace");?></label>

        <hr/>
 
<?php echo __("Cart Page :","wpmarketplace");?> <br>
<?php
//print_r($settings['page_id']);
if ($settings['page_id'])
    $args = array(
        'name' => '_wpdmpp_settings[page_id]',
        'selected' => $settings['page_id']
    );
else
    $args = array(
        'name' => '_wpdmpp_settings[page_id]'
    );
wp_dropdown_pages($args);
?>

<hr/>

<?php echo __("Orders Page :", "wpmarketplace"); ?> <br>
<?php
if (isset($settings['orders_page_id']))
    $args = array(
        'name' => '_wpdmpp_settings[orders_page_id]',
        'selected' => $settings['orders_page_id']
    );
else
    $args = array(
        'name' => '_wpdmpp_settings[orders_page_id]'
    );
wp_dropdown_pages($args);
?>

        <hr/>

        <?php echo __("Guest Order Page :", "wpmarketplace"); ?> <br>
<?php
if (isset($settings['guest_order_page_id']))
    $args = array(
        'name' => '_wpdmpp_settings[guest_order_page_id]',
        'selected' => $settings['guest_order_page_id']
    );
else
    $args = array(
        'name' => '_wpdmpp_settings[guest_order_page_id]'
    );
wp_dropdown_pages($args);
?>

        <hr/>
<?php echo __("Continue Shopping URL:","wpmarketplace");?><br/>
<input type="text" class="form-control" name="_wpdmpp_settings[continue_shopping_url]" size="50" id="continue_shopping_url" value="<?php echo $settings['continue_shopping_url']?>" />
</div>
</div>

    <div class="panel panel-default">
        <div class="panel-heading"><b><?php echo __("Purchase Settings","wpmarketplace");?></b></div>
        <div class="panel-body">

            <label><input type="checkbox" name="_wpdmpp_settings[license_key_validity]" <?php if(isset($settings['license_key_validity']) && $settings['license_key_validity']==1) echo 'checked=checked' ?> value="1"> <?php echo __("Keep License Key Valid for Expired Orders","wpmarketplace");?></label><br/>
            <label><input type="checkbox" name="_wpdmpp_settings[order_expiry_alert]" <?php if(isset($settings['order_expiry_alert']) && $settings['order_expiry_alert']==1) echo 'checked=checked' ?> value="1"> <?php echo __("Send Order Expiration Alert to Customer","wpmarketplace");?></label>

            <div style="clear: both;margin-top:20px ;"></div>

            <div style="clear: both;margin-top:20px ;"></div>

            <?php echo __("Order Validity Period:","wpmarketplace");?> <br>

            <div class="input-group col-md-5">
                <input type="text" class="form-control" value="<?php echo (isset($settings['order_validity_period']))? $settings['order_validity_period']:365; ?>" name="_wpdmpp_settings[order_validity_period]" />
                <span class="input-group-addon">Days</span>
            </div>
            <div style="clear: both;margin-top:20px ;"></div>
           </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><b><?php _e('Invoice','wpmarketplace'); ?></b></div>
        <div class="panel-body">
            <div class="form-group">
                <label for="invoice-logo">Invoice Logo URL</label>
                <div class="input-group">
                <input type="text" name="_wpdmpp_settings[invoice_logo]" id="invoice-logo" class="form-control" value="<?php echo isset($settings['invoice_logo'])?$settings['invoice_logo']:''; ?>" />
                    <span class="input-group-btn">
                        <button class="btn btn-default btn-media-upload" type="button" rel="#invoice-logo"><i class="fa fa-picture-o"></i></button>
                    </span>
                    </div>
            </div>
            <div class="form-group">
                <label for="company-address"><?php _e('Company Address','wpmarketplace'); ?></label>
                <textarea class="form-control" name="_wpdmpp_settings[invoice_company_address]" id="company-address"><?php echo isset($settings['invoice_company_address'])?$settings['invoice_company_address']:''; ?></textarea>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><b><?php _e('Miscellaneous','wpmarketplace'); ?></b></div>
        <div class="panel-body">
        <input type="checkbox" name="_wpdmpp_settings[disable_fron_end_css]" id="disable_fron_end_css" value="1" <?php if(isset($settings['disable_fron_end_css']) && $settings['disable_fron_end_css']==1)echo "checked='checked'";?>> <?php echo __("Disable plugin CSS from front-end","wpmarketplace");?>
        <div style="clear: both;margin-top:20px ;"></div>
        <input type="checkbox" name="_wpdmpp_settings[wpdmpp_after_addtocart_redirect]" id="wpdmpp_after_addtocart_redirect" value="1" <?php if($settings['wpdmpp_after_addtocart_redirect']==1) echo "checked='checked'";?>> <?php echo __("Redirect to shopping cart after a product is added to the cart","wpmarketplace");?>
        </div>
    </div>
    

</div>

<script type="text/javascript">
    jQuery(document).ready(function($){
        $('#allowed_cn').on('click', function () {
            $(this).closest('ul').find(':checkbox').prop('checked', this.checked);
        });
    });
    
</script>
<style>
    .w3eden input[type="radio"], .w3eden input[type="checkbox"] {
        line-height: normal;
        margin: -2px 0 0;
    }
    .panel-body label{
        font-weight: 400 !important;
    }
</style>