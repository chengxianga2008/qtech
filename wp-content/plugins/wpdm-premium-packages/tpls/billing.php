<div id="wpmp-section-billing">
    <div class="step-title"> 
        <h2><?php echo __("Billing and Shipping Info", "wpmarketplace"); ?> </h2>
    </div>
    <?php
    $billing_shipping = unserialize(get_user_meta($current_user->ID, 'user_billing_shipping', true));
    if (is_array($billing_shipping))
        extract($billing_shipping);
    //print_r($billing);
    ?>

    <form action="" name="billing_form" id="billing_form" method="post">

        <div style="<?php if ($current_user->ID) echo "";
    else echo "display:none"; ?>" class="step a-item" id="csb">
            <div id="customer_details" class="row row-fluid">
                <div class="col-md-6 span6">            
                    <h4><?php echo __("Billing Address", "wpmarketplace"); ?></h4>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email">
                        </div>
                        
                    <div id="billing_first_name_field" class="form-group">
                        <label class="" for="billing_first_name"><?php echo __("First Name", "wpmarketplace"); ?> <abbr title="required" class="required">*</abbr></label>
                        <input type="text" value="<?php if ($billing['first_name']) echo $billing['first_name']; ?>" placeholder="First Name" id="billing_first_name " name="checkout[billing][first_name]" class="input-text required form-control">
                    </div>    


                    <div id="billing_last_name_field" class="form-group">
                        <label class="" for="billing_last_name"><?php echo __("Last Name", "wpmarketplace"); ?> <abbr title="required" class="required">*</abbr></label>
                        <input type="text" value="<?php if ($billing['last_name']) echo $billing['last_name']; ?>" placeholder="Last Name" id="billing_last_name" name="checkout[billing][last_name]" class="input-text required form-control">
                    </div>    


                    <div id="billing_company_field" class="form-group">
                        <label  class="" for="billing_company"><?php echo __("Company Name", "wpmarketplace"); ?></label>
                        <input type="text" value="<?php if ($billing['company']) echo $billing['company']; ?>" placeholder="Company (optional)" id="billing_company" name="checkout[billing][company]" class="input-text  form-control">
                    </div>    


                    <div id="billing_address_1_field" class="form-group">
                        <label class="" for="billing_address_1"><?php echo __("Address", "wpmarketplace"); ?> <abbr title="required" class="required">*</abbr></label>
                        <input type="text" value="<?php if ($billing['address_1']) echo $billing['address_1']; ?>" placeholder="Address" id="billing_address_1" name="checkout[billing][address_1]" class="input-text required  form-control">
                    </div>    


                    <div id="billing_address_2_field" class="form-group">
                        <label  class="hidden" for="billing_address_2"><?php echo __("Address 2", "wpmarketplace"); ?></label>
                        <input type="text" value="<?php if ($billing['address_2']) echo $billing['address_2']; ?>" placeholder="Address 2 (optional)" id="billing_address_2" name="checkout[billing][address_2]" class="input-text  form-control">
                    </div>    


                    <div id="billing_city_field" class="form-group">
                        <label class="" for="billing_city"><?php echo __("Town/City", "wpmarketplace"); ?> <abbr title="required" class="required">*</abbr></label>
                        <input type="text" value="<?php if ($billing['city']) echo $billing['city']; ?>" placeholder="Town/City" id="billing_city" name="checkout[billing][city]" class="input-text required  form-control">
                    </div>    


                    <div id="billing_postcode_field" class="form-group update_totals_on_change">
                        <label class="" for="billing_postcode"><?php echo __("Postcode/Zip", "wpmarketplace"); ?> <abbr title="required" class="required">*</abbr></label>
                        <input type="text" value="<?php if ($billing['postcode']) echo $billing['postcode']; ?>" placeholder="Postcode/Zip" id="billing_postcode" name="checkout[billing][postcode]" class="input-text required  form-control">
                    </div>    


                    <div id="billing_country_field" class="form-group update_totals_on_change country_select">
                        <label class="" for="billing_country"><?php echo __("Country", "wpmarketplace"); ?> <abbr title="required" class="required">*</abbr></label>
                        <?php
                        global $wpdb;
                        $countries = $wpdb->get_results("select * from {$wpdb->prefix}ahm_country order by country_name");
                        ?>
                        <select class="required  form-control" id="billing_country" name="checkout[billing][country]">
                            <option value="">--Select a country--</option>
                            <?php
                            foreach ($countries as $country) {
                                if ($billing['country'] == $country->country_code) {
                                    $selected = ' selected="selected"';
                                } else {
                                    $selected = "";
                                }
                                if ($settings['allow_country']) {
                                    foreach ($settings['allow_country'] as $ac) {
                                        if ($ac == $country->country_code) {

                                            echo '<option value="' . $country->country_code . '"' . $selected . '>' . $country->country_name . '</option>';
                                            break;
                                        }
                                    }
                                } else {
                                    echo '<option value="' . $country->country_code . '" ' . $selected . '>' . $country->country_name . '</option>';
                                }
                            }
                            ?>
                        </select></div>    


                    <div id="billing_state_field" class="form-group update_totals_on_change">
                        <label class="" for="billing_state"><?php echo __("State/County", "wpmarketplace"); ?> <abbr title="required" class="required">*</abbr></label>
                        <input type="text" id="billing_state" name="checkout[billing][state]" placeholder="State/County" value="<?php if ($billing['state']) echo $billing['state']; ?>" class="input-text required  form-control">
                    </div>   


                    <div id="billing_email_field" class="form-group">
                        <label class="" for="billing_email"><?php echo __("Email Address", "wpmarketplace"); ?> <abbr title="required" class="required">*</abbr></label>
                        <input type="text" value="<?php if ($billing['email']) echo $billing['email']; ?>" placeholder="Email Address" id="billing_email" name="checkout[billing][email]" class="input-text required email  form-control">
                    </div>    


                    <div id="billing_phone_field" class="form-group">
                        <label class="" for="billing_phone"><?php echo __("Phone", "wpmarketplace"); ?> <abbr title="required" class="required">*</abbr></label>
                        <input type="text" value="<?php if ($billing['phone']) echo $billing['phone']; ?>" placeholder="Phone" id="billing_phone" name="checkout[billing][phone]" class="input-text required  form-control">
                    </div>
                    
                </div>
                
                <div class="clear"></div>


            </div>

            <button id="billing_btn" class="btn btn-success" type="submit"><span><span><?php echo __("Continue", "wpmarketplace"); ?></span></span></button>
            <div style="display: none;float: right" id="bloading_first"><img  src="<?php echo home_url(); ?>/wp-admin/images/loading.gif" /></div><div id="bloading_message"></div>
        </div>

    </form>
</div>
