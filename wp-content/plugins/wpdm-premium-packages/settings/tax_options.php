<div style="clear: both;margin-top:20px ;"></div>
<?php 
$us_states = array('AL'=>"Alabama",  
			'AK'=>"Alaska",  
			'AZ'=>"Arizona",  
			'AR'=>"Arkansas",  
			'CA'=>"California",  
			'CO'=>"Colorado",  
			'CT'=>"Connecticut",  
			'DE'=>"Delaware",  
			'DC'=>"District Of Columbia",  
			'FL'=>"Florida",  
			'GA'=>"Georgia",  
			'HI'=>"Hawaii",  
			'ID'=>"Idaho",  
			'IL'=>"Illinois",  
			'IN'=>"Indiana",  
			'IA'=>"Iowa",  
			'KS'=>"Kansas",  
			'KY'=>"Kentucky",  
			'LA'=>"Louisiana",  
			'ME'=>"Maine",  
			'MD'=>"Maryland",  
			'MA'=>"Massachusetts",  
			'MI'=>"Michigan",  
			'MN'=>"Minnesota",  
			'MS'=>"Mississippi",  
			'MO'=>"Missouri",  
			'MT'=>"Montana",
			'NE'=>"Nebraska",
			'NV'=>"Nevada",
			'NH'=>"New Hampshire",
			'NJ'=>"New Jersey",
			'NM'=>"New Mexico",
			'NY'=>"New York",
			'NC'=>"North Carolina",
			'ND'=>"North Dakota",
			'OH'=>"Ohio",  
			'OK'=>"Oklahoma",  
			'OR'=>"Oregon",  
			'PA'=>"Pennsylvania",  
			'RI'=>"Rhode Island",  
			'SC'=>"South Carolina",  
			'SD'=>"South Dakota",
			'TN'=>"Tennessee",  
			'TX'=>"Texas",  
			'UT'=>"Utah",  
			'VT'=>"Vermont",  
			'VA'=>"Virginia",  
			'WA'=>"Washington",  
			'WV'=>"West Virginia",  
			'WI'=>"Wisconsin",  
			'WY'=>"Wyoming");

$ca_states = array( 
    "BC"=>"British Columbia", 
    "ON"=>"Ontario", 
    "NL"=>"Newfoundland and Labrador", 
    "NS"=>"Nova Scotia", 
    "PE"=>"Prince Edward Island", 
    "NB"=>"New Brunswick", 
    "QC"=>"Quebec", 
    "MB"=>"Manitoba", 
    "SK"=>"Saskatchewan", 
    "AB"=>"Alberta", 
    "NT"=>"Northwest Territories", 
    "NU"=>"Nunavut",
    "YT"=>"Yukon Territory");

?>    
<div class="panel panel-default">
    <div class="panel-heading"><b><?php echo __("Tax Options", "wpmarketplace"); ?></b></div>    
    <div class="panel-body">      
             <b><?php echo __("Tax Calculation", "wpmarketplace"); ?></b>
                     <div style="clear: both;margin-top:20px ;"></div>
                     
                     <input type="checkbox" value="1" <?php if (isset($settings['tax']['enable']) && $settings['tax']['enable'] == 1) echo "checked='checked'"; ?> id="calc_shipping" name="_wpdmpp_settings[tax][enable]"> <?php echo __("Enable tax calculation", "wpmarketplace"); ?><br/>
                     <input type="checkbox" class="global_low_tax" <?php if (isset($settings['tax']['tax_on_cart']) && $settings['tax']['tax_on_cart'] == 1) echo 'checked="checked"'; ?> value="1"  name="_wpdmpp_settings[tax][tax_on_cart]"> <?php echo __("Display taxes on cart page", "wpmarketplace"); ?>                 
                             
                     <div style="clear: both;margin-top:20px ;"></div>
                     
            <b><?php echo __("Tax Rates", "wpmarketplace"); ?> </b>
                     <div style="clear: both;margin-top:20px ;"></div>

        <table class="dtable table table-striped">
            <thead>
                <tr> 
                    <th><?php echo __("Country", "wpmarketplace"); ?></th>
                    <th><?php echo __("State", "wpmarketplace"); ?></th>
                    <th><?php echo __("Rate(%)", "wpmarketplace"); ?></th>
                    <th><?php echo __("Action", "wpmarketplace"); ?></th>
                </tr>
            </thead>   
            <tbody id="intr_rate">
        <?php
        
        if (isset($settings['tax']['tax_rate'])) {

            foreach ($settings['tax']['tax_rate'] as $key => $rate) {
                //print_r($settings['tax']['tax_rate']);
                ?>
                <tr id="r_<?php echo $key; ?>">
                    <td>
                        <select style="max-width: 200px;width: 200px" class="changecountry" rel="<?php echo $key; ?>" name="_wpdmpp_settings[tax][tax_rate][<?php echo $key; ?>][country]">
                                    
                                    <?php
                                    foreach ($countries as $country) {
                                        ?>
                                        <option <?php if ($settings['tax']['tax_rate'][$key]['country'] == $country->country_code) echo 'selected=selected' ?> value="<?php echo $country->country_code; ?>"><?php echo ucwords($country->country_name); ?></option>
                                        <?php
                                    }
                                    ?>
                        </select>
                    </td>
                    <td id="cahngestates_<?php echo $key; ?>">
                       
                        <?php
                        $has_states = TRUE;
                        if($settings['tax']['tax_rate'][$key]['country']=='US')
                            $states = $us_states;
                        else if($settings['tax']['tax_rate'][$key]['country']=='CA')
                            $states = $ca_states;
                        else{
                            //$states = array();
                            $has_states = FALSE;
                        }
                       if($has_states){
                           ?>
                               <select style="max-width: 150px;" name="_wpdmpp_settings[tax][tax_rate][<?php echo $key; ?>][state]">
                               <?php
                        foreach ($states as $s_code => $state) {
                            ?>
                            <option <?php if ($settings[tax][tax_rate][$key][state] == $s_code) echo 'selected=selected' ?> value="<?php echo $s_code; ?>"><?php echo ucwords($state); ?></option>
                            <?php
                            }
                            ?>
                       </select>
                           <?php
                            }
                       else{
                        echo "<input class='form-control input-sm' size='17' type='text'  name='_wpdmpp_settings[tax][tax_rate][$key][state]' />";
                       }
                       ?>
                    
                    </td>
                    <td>
                    <input size="4" class='form-control input-sm' type="text" name="_wpdmpp_settings[tax][tax_rate][<?php echo $key; ?>][rate]" value="<?php echo $rate['rate']; ?>">
                    </td>
                    <td><a href="#" class="del_rate" rel="<?php echo $key; ?>">Delete</a></td>
                </tr>
                   <?php
                        }
                    }
                    ?>  
            </tbody>
         </table> 
        
        <div style="clear: both;margin-top:20px ;"></div>
        <input class="btn btn-default" type="button" id="add_tax_rate" value="Add Tax Rate">           
     </div>
</div>
                        
<script type="text/javascript"> 
jQuery(function() {
      jQuery('#add_tax_rate').click(function(){
          
            var tmy=new Date().getTime(); 
           //alert(tmy);
          jQuery('#intr_rate').append('<tr id="r_'+tmy+'"><td><select style="max-width:200px;width:200px;" class="taxcountry" rel="'+tmy+'" name="_wpdmpp_settings[tax][tax_rate]['+tmy+'][country]"><option value="">Select Country</option><?php
          foreach ($countries as $country){?><option value="<?php echo $country->country_code; ?>"><?php echo ucwords(str_replace("'"," ",  strtolower($country->country_name))); ?></option><?php
            }
?></select></td><td id="states_'+tmy+'"><input type="text" class="form-control input-sm" size="17" name="_wpdmpp_settings[tax][tax_rate]['+tmy+'][state]" value="">\n\
</td> <td><input type="text" size="4" class="form-control input-sm" name="_wpdmpp_settings[tax][tax_rate]['+tmy+'][rate]" value=""></td><td><a href="#" class="del_rate" rel="'+tmy+'">Delete</a></td></tr>');
      }); 
                            
      jQuery('body').on("click",'.del_rate',function(){
          if(confirm("Are you sure to delete?")){
              jQuery('#r_'+jQuery(this).attr("rel")).remove();
          }
          return false;
      }); 
      
      jQuery('.taxcountry').on("change",function(){
           var ids = jQuery(this).attr('rel');
           var uss = "<select style='max-width: 150px;' name='_wpdmpp_settings[tax][tax_rate]["+ids+"][state]'><?php foreach ($us_states as $s_code => $state) {?><option value='<?php echo $s_code; ?>'><?php echo ucwords($state); ?></option><?php } ?></select>";
           var cas = "<select style='max-width: 150px;' name='_wpdmpp_settings[tax][tax_rate]["+ids+"][state]'><?php foreach ($ca_states as $s_code => $state) {?><option value='<?php echo $s_code; ?>'><?php echo ucwords($state); ?></option><?php } ?></select>";
           var ocs = "<input size='17' type='text'  name='_wpdmpp_settings[tax][tax_rate]["+ids+"][state]' />";
           var v = jQuery(this).val();
           if(v=='US') jQuery('#states_'+ids).html(uss);
           else if(v=='CA') jQuery('#states_'+ids).html(cas);
           else jQuery('#states_'+ids).html(ocs);
           
      });
      
      jQuery('.changecountry').on("change",function(){
           var ids = jQuery(this).attr('rel');
           var uss = "<select style='max-width: 150px;' name='_wpdmpp_settings[tax][tax_rate]["+ids+"][state]'><?php foreach ($us_states as $s_code => $state) {?><option value='<?php echo $s_code; ?>'><?php echo ucwords($state); ?></option><?php } ?></select>";
           var cas = "<select style='max-width: 150px;' name='_wpdmpp_settings[tax][tax_rate]["+ids+"][state]'><?php foreach ($ca_states as $s_code => $state) {?><option value='<?php echo $s_code; ?>'><?php echo ucwords($state); ?></option><?php } ?></select>";
           var ocs = "<input size='17' type='text'  name='_wpdmpp_settings[tax][tax_rate]["+ids+"][state]' />";
           var v = jQuery(this).val();
           if(v=='US') jQuery('#cahngestates_'+ids).html(uss);
           else if(v=='CA') jQuery('#cahngestates_'+ids).html(cas);
           else jQuery('#cahngestates_'+ids).html(ocs);
           
      });
      
  });  
</script> 

    