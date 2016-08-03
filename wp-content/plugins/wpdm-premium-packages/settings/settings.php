 
<script type="text/javascript">
function select_my_list(selectid,val){
    var ln=document.getElementById(selectid).options.length;
    for(var i=0;i<ln; i++){
        if(document.getElementById(selectid).options[i].value==val)
            document.getElementById(selectid).options[i].selected = true;                                               }
}

jQuery(function(){
    jQuery('#message').live('click',function(){
        jQuery('#message').slideUp();
    });

});
</script>
 <?php
    $settings = maybe_unserialize(get_option('_wpdmpp_settings'));
    //echo "<pre>" ; print_r($settings); echo "</pre>";
?>
 
<div class="wrap">
    
    <ul class="nav nav-tabs">
        <li class="active"><a href="#tab1" data-toggle="tab"><?php echo __("Basic Settings","wpmarketplace");?></a></li>
        <li><a href="#tab2" data-toggle="tab"><?php echo __("Payment Options","wpmarketplace");?></a></li>                 
        <li><a href="#tab3" data-toggle="tab"><?php echo __("Tax","wpmarketplace");?></a></li>        
    </ul>
 
    <div class="tab-content">
        <section class="tab-pane active" id="tab1">
            <?php include_once("basic_settings.php"); ?>
        </section>
        <section class="tab-pane" id="tab2"> 
            <?php include_once("payment_options.php"); ?>
        </section>
        <section class="tab-pane" id="tab3"> 
            <?php include_once("tax_options.php"); ?>
        </section>
    </div>
</div>

<script type="text/javascript">

jQuery(document).ready(function(){
    
    jQuery('#wpdmpp_settings_form').submit(function(){
       
       jQuery(this).ajaxSubmit({
        url:ajaxurl,
        beforeSubmit: function(formData, jqForm, options){
          jQuery('#wdms_saving').fadeIn();  
        },   
        success: function(responseText, statusText, xhr, $form){
          jQuery('#message').html("<p>"+responseText+"</p>").slideDown();
          //setTimeout("jQuery('#message').slideUp()",4000);
          jQuery('#wdms_saving').fadeOut();  
          jQuery('#wdms_loading').fadeOut();  
          window.setTimeout('location.reload()', 1000);
        }   
       });
        
       return false; 
    });
    
   
});
 
</script>
