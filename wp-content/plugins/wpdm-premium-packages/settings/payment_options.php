<?php

global $payment_methods;

$payment_methods = apply_filters('payment_method', $payment_methods);
$payment_methods = count(get_wpdmpp_option('pmorders', array())) == count($payment_methods)?get_wpdmpp_option('pmorders'):$payment_methods;

?>

<div style="clear: both;margin-top:20px ;"></div>



<div class="panel panel-default">
    <div class="panel-heading"><b><?php echo __("Payment Methods Configuration", "wpmarketplace"); ?></b></div>
    <div id="paccordion" class="wpmppgac">
        <div class="panel-body">
            <div class="panel-group" id="accordion" style="margin: 0">
                <?php
                foreach ($payment_methods as $payment_method) {
                    if (class_exists($payment_method)) {
                        $obj = new $payment_method();
                        $name = isset($obj->GatewayName)?$obj->GatewayName:$payment_method;
                        ?>
                        <div class="panel panel-default">
                            <?php

                            echo '<div class="panel-heading"><b><i title="'.__('Drag and drop to re-order','wpmarketplace').'" class="fa fa-arrows-v" style="color: #B27CD6;cursor: move"></i> &nbsp; <a data-toggle="collapse" data-parent="#accordion" href="#'.$payment_method.'">' . ucwords($name) . '</a></b>';
                            echo '<div class="pull-right" id="pmstatus_'.$payment_method.'">';
                            if (isset($settings[$payment_method]['enabled']) && $settings[$payment_method]['enabled'] == 1)
                                echo "<span class='text-success'> <i class='fa fa-check-circle'></i> " . __("Active", "wpmarketplace")."</span>";
                            else
                                echo '<span class="text-danger"> <i class="fa fa-times-circle"></i> '.__("Inactive", "wpmarketplace").'</span>';

                            echo '</div>';
                            echo '</div>';
                            echo '<div id="'.$payment_method.'" class="panel-collapse collapse">';
                            echo '<div class="panel-body">';
                            //echo "<div>";
                            echo Payment::GateWaySettings($payment_method);
                            //echo wpdm_option_page($obj->ConfigOptions());
                            //echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            ?>
                            <input type="hidden" name="_wpdmpp_settings[pmorders][]" value="<?php echo $payment_method; ?>">
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
 
<div style="clear: both;margin-top:20px ;"></div>

<div class="panel panel-default">
    <div class="panel-heading"><b><?php echo __("Currency Configuration", "wpmarketplace"); ?></b></div>
    <div id="paccordion1">

        <table class="table">
            <tr><td><?php _e('Currency:'); ?></td><td><?php Currencies::CurrencyListHTML(array('name'=>'_wpdmpp_settings[currency]', 'selected'=> (isset($settings['currency'])?$settings['currency']:''))); ?></td></tr>
        </table>

            <?php
            //$currencies = get_option('wpdmpp_currencies');print_r($currencies);
            $currencies = isset($settings['currency'])?$settings['currency']:''; //print_r($currencies);
            $currency_key = get_option('_wpdmpp_curr_key');
            //echo $currency_key;
            ?>
            <!-- table id="currency_table" width="50%" border="0" class="currency_table wc_gateways table table-striped">
                <thead>
                    <tr><th><?php echo __("Default", "wpmarketplace"); ?></th><th><?php echo __("Currency Code", "wpmarketplace"); ?></th><th><?php echo __("Currency Symbol", "wpmarketplace"); ?></th><th><?php echo __("Action", "wpmarketplace"); ?></th></tr>
                </thead>
                <tbody>
                    <?php
                    if ($currencies) {
                        //echo '';
                        foreach ($currencies as $key => $currency) {
                            if ($key == $currency_key)
                                $select = 'checked="checked"';
                            else
                                $select = "";
                            echo '<tr id="currency_row_' . $key . '"><td><span id="w8c_' . $key . '" style="position:absolute;display:none;text-decoration:blink;margin-left:20px;">Saving...</span><input type="radio" ' . $select . ' name="currency_radio" class="currency_radio" id="' . $key . '"></td><td><input id="c_n_' . $key . '" type="text" name="_wpdmpp_settings[currency][' . $key . '][currency_name]" value="' . $currency['currency_name'] . '" class="currency_name  form-control input-sm"></td><td><input id="c_s_' . $key . '" class="currency_symbol form-control input-sm" type="text" name="_wpdmpp_settings[currency][' . $key . '][currency_symbol]" value="' . $currency['currency_symbol'] . '"></td><td><a href="#" class="del_currency" id="' . $key . '">' . __("Delete", "wpmarketplace") . '</a></td></tr>';
                        }
                    }else {
                        
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr><td></td><td><input type="text" id="currency_n" class="currency_name  form-control input-sm"></td><td><input type="text" id="currency_s" class="currency_symbol form-control input-sm" width="100px"></td><td><input type="button" id="add_currency" value="Add" class="button">
                    <span id="loadingc" style="display: none;"><img src="images/loading.gif" alt=""> saving...</span></td></tr>
                </tfoot>
            </table -->
                                  

    </div>
</div>
<script type="text/javascript" src="<?php echo plugins_url("/wpdm-premium-packages/js/currency.js");?>"></script>

