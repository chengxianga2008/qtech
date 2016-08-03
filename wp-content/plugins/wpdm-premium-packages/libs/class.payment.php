<?php
class Payment{
    
    var $Processor;
     
    function Payment(){
        
    }
    
    function InitiateProcessor($MethodID){                     
        $MethodClass = $MethodID;
        if(!class_exists($MethodClass)) die('<span class="label label-danger">Payment method is not active!</span>');
        $this->Processor = new $MethodClass();        
    }
    
    function ProcessPayment(){
        
    }
    
    function ListMethods() {
         global $wpdb;
         $methods = $wpdb->get_results("select * from {$wpdb->prefix}ahm_payment_methods where enabled='1'",ARRAY_A);                  
         return $methods;
    } 
    
    function CountMethods(){
         global $wpdb;
         return $wpdb->get_var("select count(*) from {$wpdb->prefix}ahm_payment_methods where enabled='1'");                  
    }
    
    function PaymentMethodDropDown(){
        $methods = $this->ListMethods();
        $html = "";
        if(count($methods)>1){
        foreach($methods as $method){
            $html .= "<option value='{$method['class_name']}'>{$method['title']}</option>\r\n";
        }}
        return $html;
    }


    /**
     * Return credit card type if number is valid
     * @return string
     * @param $number string
     **/
    public static function cardType($number)
    {
        $number=preg_replace('/[^\d]/','',$number);
        if (preg_match('/^3[47][0-9]{13}$/',$number))
        {
            return 'AMEX';
        }
        elseif (preg_match('/^3(?:0[0-5]|[68][0-9])[0-9]{11}$/',$number))
        {
            return 'DINERS';
        }
        elseif (preg_match('/^6(?:011|5[0-9][0-9])[0-9]{12}$/',$number))
        {
            return 'DISCOVER';
        }
        elseif (preg_match('/^(?:2131|1800|35\d{3})\d{11}$/',$number))
        {
            return 'JCB';
        }
        elseif (preg_match('/^5[1-5][0-9]{14}$/',$number))
        {
            return 'MASTERCARD';
        }
        elseif (preg_match('/^4[0-9]{12}(?:[0-9]{3})?$/',$number))
        {
            return 'VISA';
        }
        else
        {
            return 'Unknown';
        }
    }


    static function GateWaySettings($gateway){
        if(!is_object($gateway)) $gateway = new $gateway();
        $options  = $gateway->ConfigOptions();
        $enabled['Paypal_enable'] = array(
            'name'          =>      '_wpdmpp_settings['.get_class($gateway).'][enabled]',
            'id'            =>      'enable_'.get_class($gateway),
            'label'         =>      __("Active","wpmarketplace"),
            'type'          =>      'checkbox',
            'value'         =>      1,
            'checked'       =>      get_wpdmpp_option(get_class($gateway).'/enabled',0)
        );

        $options = array_merge($enabled, $options);
        foreach($options as $id => $option){
            if(!isset($option['id']))
                $option['name'] = '_wpdmpp_settings['.get_class($gateway).']['.$id.']';
            if(!isset($option['id']))
                $option['id'] = $id;
            $options[$id] = $option;
        }
        return wpdm_option_page($options)."<script>jQuery(function($){ $('#enable_".get_class($gateway)."').on('click', function(){ if(this.checked) $('#pmstatus_".get_class($gateway)."').html('<span class=\"text-success\"> <i class=\"fa fa-check-circle\"></i> Active</span>'); else $('#pmstatus_".get_class($gateway)."').html('<span class=\"text-danger\"> <i class=\"fa fa-times-circle\"></i>  Inactive</span>');  }); });</script>";
    }
    
    function getMonthOptions(){
            return 
                '<option value="01">January</option>\r\n'.
                '<option value="02">February</option>\r\n'.
                '<option value="03">March</option>\r\n'.
                '<option value="04">April</option>\r\n'.
                '<option value="05">May</option>\r\n'.
                '<option value="06">June</option>\r\n'.
                '<option value="07">July</option>\r\n'.
                '<option value="08">August</option>\r\n'.
                '<option value="09">September</option>\r\n'.
                '<option value="10">October</option>\r\n'.
                '<option value="11">November</option>\r\n'.
                '<option value="12">December</option>\r\n';
    }
    
    function getYearOptions(){
            $start = date("Y");
            $fin = $start + 25;
            $options = "";
            for($i=$start; $i<$fin; $i++){
                $options .='<option value="'.$i.'>'.$i.'</option>\r\n';
            }
            return $options;
        }
       
    
    
    
}


class CommonVars{
    var $Currency = 'USD';
    var $OrderTitle;
    var $Amount;
    var $InvoiceNo;
    var $OrderID;
    var $Settings;
    var $VerificationError;
}

