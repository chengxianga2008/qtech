<?php
/*
Plugin Name: WPDM - Custom Access Level
Plugin URI: http://www.wpdownloadmanager.com/
Description: WPDM - Custom Access Level is a wordpress download manager pro add-on that heps you to add custom user role and user id to control accessibility to a download .
Author: Shaon
Version: 2.4.2
Author URI: http://www.wpdownloadmanager.com/
*/
 
function wpdm_cal_remote_get($url){
        $options = array(
        CURLOPT_RETURNTRANSFER => true, // return web page
        CURLOPT_HEADER => false, // don't return headers
        CURLOPT_FOLLOWLOCATION => true, // follow redirects
        CURLOPT_ENCODING => "", // handle all encodings
        CURLOPT_USERAGENT => "spider", // who am i
        CURLOPT_AUTOREFERER => true, // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
        CURLOPT_TIMEOUT => 120, // timeout on response
        CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
        );

        $ch = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err = curl_errno( $ch );
        $errmsg = curl_error( $ch );
        $header = curl_getinfo( $ch );
        curl_close( $ch );        
        return $content;
}

function wpdm_cal_remove_role(){
    if(!is_admin()) return;
    $core_roles = array('administrator','editor','author','contributor','subscriber');
    if(in_array($_POST['wpdm_cal_role_id'], $core_roles)) die("System default role can't be deleted!");
    remove_role($_POST['wpdm_cal_role_id']);
    echo 'done!';
    die();
}

function wpdm_cal_create_new_role(){
       global $wpdb;   
       $option_name = "{$wpdb->prefix}user_roles";
       $roles = maybe_unserialize(get_option($option_name));              
       $nrk = preg_replace('/([^a-z,0-9]+)/is','_',strtolower($_POST['wpdm_cal_new_role']));
       if(!@array_key_exists($nrk,$roles)&&$nrk!=''){        
        $role_obj = new WP_Roles();
        $role_obj->add_role($nrk,$_POST['wpdm_cal_new_role'],array('read'=>1,'level_0'=>1));
        echo $_POST['wpdm_cal_new_role'];
        die();
       } else {
         if(@array_key_exists($nrk,$roles))  
         die('Role name already exists');
         if($nrk=='')
         die('Invalid Role name');
       }              
}


function wpdm_cal_my_downloads($params = array()){
     global $wpdb, $current_user;
    /*
    array(
                 'key' => '__wpdm_access',
                 'value' => 'guest',
                 'compare' => 'LIKE',
             ),
    */
     $files = new WP_Query(array(
         'post_type' => 'wpdmpro',
         'posts_per_page' => -1,
         'meta_query' => array(
             array(
                 'key' => '__wpdm_user_access',
                 'value' => $current_user->user_login,
                 'compare' => 'LIKE',
             ),
             array(
                 'key' => '__wpdm_access',
                 'value' => $current_user->roles[0],
                 'compare' => 'LIKE',
             ),

             'relation' => 'OR'
         )

     ));


    extract(($params));
    $login = isset($login)?$login:0;
     ob_start();
    $pageurl = get_permalink();
     if(!is_user_logged_in() && $login==1)
         return do_shortcode("[wpdm_login_form redirect='{$pageurl}']");
     else {
         if (!isset($template))
             include("wpdm-my-downloads.php");
         else
             include("wpdm-my-downloads-wlt.php");
         $data = ob_get_clean();
         return $data;
     }
}

function wpdm_cal_suggest_members(){
      global $wpdb, $blog_id;
      if(!current_user_can('edit_posts')) return;
      $q = esc_attr($_GET['term']);
      $prefix = str_replace("{$blog_id}_","",$wpdb->prefix);
      $data = $wpdb->get_results("select user_login as `value`, user_login as name from {$prefix}users where `user_login` LIKE '%$q%' or `user_email` LIKE '%$q%'",ARRAY_A);
      header("Content-type: application/json");
      echo json_encode($data);
      die();
}

function wpdm_cal_update_access($id, $package){
     
    $package['access'] = maybe_unserialize($package['access']);
    //if($package['access']['0']=='selected_members_only'){        
        update_option("wpdm_package_selected_members_only_".$id,$_POST['allowed_members']);
    //}
}
 

function wpdm_cal_interface_action(){
    if(get_post_type()!='wpdmpro') return;
  
    ?>
 <script type="text/javascript">

jQuery(function(){
    var uacc = '<?php

        $susers = maybe_unserialize(get_post_meta(get_the_ID(), '__wpdm_user_access', true));
        if($susers){
        foreach($susers as $suser){
         echo '<span  id="uaco-'.sanitize_title($suser).'"><input type="hidden" name="file[user_access][]" value="'.$suser.'" /> <a class="ntdelbutton uaco-del" onclick="jQuery(this.rel).remove()" rel="#uaco-'.sanitize_title($suser).'">X</a>&nbsp;'.$suser.'</span>';
        }}

    ?>';
    jQuery('<tr id="select_members_row"><td>Select&nbsp;Members:</td>  <td><div class="tagchecklist" id="uaco" style="float: left">'+uacc+'<div style="clear: both"></div></div><input type="text" size="10" id="maname" name="mname" placeholder="Enter name or email" title="Enter name or email"></td></tr>').insertAfter('#access_row');

    function split( val ) {
        return val.split( /,\s*/ );
    }
    function extractLast( term ) {
        return split( term ).pop();
    }
    jQuery( "#maname" )
        .bind( "keydown", function( event ) {
            if ( event.keyCode === jQuery.ui.keyCode.TAB &&
                jQuery( this ).data( "ui-autocomplete" ).menu.active ) {
                event.preventDefault();
            }
        })
        .autocomplete({
            source: function( request, response ) {
                jQuery.getJSON( ajaxurl+'?action=wpdm_cal_suggest_members', {
                    action: 'wpdm_cal_suggest_members',
                    term: extractLast( request.term )
                }, response );
            },
            search: function() {

                var term = extractLast( this.value );
                if ( term.length < 2 ) {
                    return false;
                }
            },
            focus: function() {

                return false;
            },
            select: function( event, ui ) {
                /*
                var terms = split( this.value );

                terms.pop();

                terms.push( ui.item.value );

                terms.push( "" );
                this.value = terms.join( ", " );
                return false;
                */
                //alert(ui.item.value);
                jQuery('#uaco').prepend('<span  id="uaco-'+ui.item.value.replace(/[^a-zA-Z]/ig,'-')+'"><input type="hidden" name="file[user_access][]" value="'+ui.item.value+'" /> <a class="ntdelbutton uaco-del" onclick="jQuery(this.rel).remove()" rel="#uaco-'+ui.item.value.replace(/[^a-zA-Z]/ig,'-')+'">X</a>&nbsp;'+ui.item.value+'</span>');
                this.value = "";
                return false;
            }
        });
    
     
    
});
 
function createrole(){    
    if(jQuery('#wpdm_cal_new_role').val()=='') {
        alert('Enter a valid role name!');
        return false;
    }
    jQuery('#pwt').fadeIn();
    jQuery.post(ajaxurl,{action:'wpdm_cal_create_new_role',wpdm_cal_new_role:jQuery('#wpdm_cal_new_role').val()},function(res){                        
        jQuery('#roletable tbody tr:last-child').before("<tr><td>"+res+"</td><td></td></tr>");
        jQuery('#pwt').fadeOut();        
    });    
    return false;
}

function delete_role(role){
   if(!confirm('Are you sure?')) return false; 
   jQuery('#tr'+role+" td:last-child input").val('Deleting...').attr('disabled','disabled');
   jQuery.post(ajaxurl,{action:'wpdm_cal_remove_role',wpdm_cal_role_id:role},function(res){                        
       if(res=='done!')
        jQuery('#tr'+role).fadeOut();        
       else alert(res); 
   });  
}

function edit_role(role){
   if(jQuery('#tr'+role+" td:last-child input.button-primary").val()=='Edit')
   jQuery('#tr'+role+" td:last-child input.button-primary").val('Cancel Edit');
   else 
   jQuery('#tr'+role+" td:last-child input.button-primary").val('Edit');
   jQuery('#edit-'+role).slideToggle();
   /*jQuery.post(ajaxurl,{action:'wpdm_cal_remove_role',wpdm_cal_role_id:role},function(res){                        
       if(res=='done!')
        jQuery('#tr'+role).fadeOut();        
       else alert(res); 
   });  */
}


</script>
    
    
    <?php
} 


function wpdm_block_dllink($package){

    global $wpdb, $current_user, $wpdm_download_button_class;
    $uroles = array_keys($current_user->caps);
    $urole = array_shift($uroles);      
    get_currentuserinfo();
    $users = maybe_unserialize(get_post_meta($package['ID'], '__wpdm_user_access', true));
    if(!isset($package['access'])) $package['access'] = array();
    if(!$users || (is_user_logged_in() && in_array($current_user->roles[0], $package['access'])) || in_array('guest', $package['access'])) return $package;

    if(is_user_logged_in() && in_array($current_user->user_login, $users)){
       $dkey = is_array($package['files'])?md5(serialize($package['files'])):md5($package['files']);
       $package['access'] = array('guest');
       $package['download_url'] = wpdm_download_url($package,'');
       if(wpdm_is_download_limit_exceed($package['ID'])){
          $package['download_url'] = '#';
          $package['link_label'] = __msg('DOWNLOAD_LIMIT_EXCEED');
          }
       $package['download_link'] = $package['download_link_extended'] = "<a class='wpdm-download-link $wpdm_download_button_class' rel='noindex nofollow' href='{$package['download_url']}'>{$package['link_label']}</a>";

        return $package;
    } else {
        $package['download_url'] = "#";
        $package['access'] = array();
        $package['download_link'] = $package['download_link_extended'] = stripslashes(get_option('wpdm_permission_msg'));
        if (get_option('_wpdm_hide_all', 0) == 1) {

            $package['download_link'] = $package['download_link_extended'] = 'blocked';
            if(!is_user_logged_in()) $package['download_link'] = $package['download_link_extended'] = 'loginform';

        }
    }

   return $package;
}

 

function wpdm_cal_check_permission($package){
    global $wpdb, $current_user;
    $uroles = array_keys($current_user->caps);
    $urole = array_shift($uroles);      
    get_currentuserinfo();
    $users = maybe_unserialize(get_post_meta($package['ID'], '__wpdm_user_access', true));
    if(!$users) return $package;

    if(is_user_logged_in() && in_array($current_user->user_login, $users)){
     $package['access']  = array('guest');
     $_GET['masterkey'] = $package['masterkey'] = 1;
    }                
    return $package; 
} 

function wpdm_cal_capabilities_html(){
   $html = wpdm_cal_default_capabilities()  ;
}

function g1et_editable_roles() {
    global $wp_roles;

    $all_roles = $wp_roles->roles;
    $editable_roles = apply_filters('editable_roles', $all_roles);

    return $editable_roles;
}

function wpdm_cal_default_capabilities() {
    
    $defaults = array(
        'activate_plugins',
        'add_users',
        'create_users',
        'delete_others_pages',
        'delete_others_posts',
        'delete_pages',
        'delete_plugins',
        'delete_posts',
        'delete_private_pages',
        'delete_private_posts',
        'delete_published_pages',
        'delete_published_posts',
        'delete_users',
        'edit_dashboard',
        'edit_files',
        'edit_others_pages',
        'edit_others_posts',
        'edit_pages',
        'edit_plugins',
        'edit_posts',
        'edit_private_pages',
        'edit_private_posts',
        'edit_published_pages',
        'edit_published_posts',
        'edit_theme_options',
        'edit_themes',
        'edit_users',
        'import',
        'install_plugins',
        'install_themes',
        'list_users',
        'manage_categories',
        'manage_links',
        'manage_options',
        'moderate_comments',
        'promote_users',
        'publish_pages',
        'publish_posts',
        'read',
        'read_private_pages',
        'read_private_posts',
        'remove_users',
        'switch_themes',
        'unfiltered_html',
        'unfiltered_upload',
        'update_core',
        'update_plugins',
        'update_themes',
        'upload_files'
    );

    /* Return the array of default capabilities. */
    return $defaults;
}


function wpdm_hide_onno_permission($package){
    global $wpdb, $current_user;
    $uroles = array_keys($current_user->caps);
    $urole = array_shift($uroles); 
    if(!@in_array('selected_members_only', maybe_unserialize($package['access']))||@in_array($urole, maybe_unserialize($package['access']))) return $package;
    get_currentuserinfo(); 
    $users = explode(',',trim(get_option('wpdm_package_selected_members_only_'.((int)$package['id'])),','));      
    $role = array_shift(array_keys($current_user->caps));          
    if(in_array($current_user->user_login, $users)&&@in_array('selected_members_only', @unserialize($package['access']))&&$current_user->user_login!=''){
     return $package;
    }                
    if(@in_array('selected_members_only', @unserialize($package['access'])))
    return array(); 
    else
    return $package;
} 


function wpdm_acl_user_roles(){
    global $wp_roles;
    $roles = array_reverse($wp_roles->role_names);
    ?>
    <style type="text/css">
    .wpdm-cal-tab-wrapper
    {
        width: 960px;
        margin: 0px auto;
        padding-top: 20px;
        min-height: 600px;
    }

    .wpdm-cal-tab-wrapper h1, .wpdm-cal-tab-wrapper h4, .wpdm-cal-tab-wrapper p, .wpdm-cal-tab-wrapper pre, .wpdm-cal-tab-wrapper ul, .wpdm-cal-tab-wrapper li
    {
        margin: 0;
        padding: 0;
        border: 0;
        vertical-align: baseline;
        background: transparent;
    }

    .wpdm-cal-tab-wrapper li
    {
        outline: 0;
        text-decoration: none;
        -webkit-transition-property: background color;
        -moz-transition-property: background color;
        -o-transition-property: background color;
        -ms-transition-property: background color;
        transition-property: background color;
        -webkit-transition-duration: 0.12s;
        -moz-transition-duration: 0.12s;
        -o-transition-duration: 0.12s;
        -ms-transition-duration: 0.12s;
        transition-duration: 0.12s;
        -webkit-transition-timing-function: ease-out;
        -moz-transition-timing-function: ease-out;
        -o-transition-timing-function: ease-out;
        -ms-transition-timing-function: ease-out;
        transition-timing-function: ease-out;
    }

    #v-nav
    {
        height: 100%;
        margin: auto;
        color: #333;
        font: 12px/18px "Lucida Grande", "Lucida Sans Unicode", Helvetica, Arial, Verdana, sans-serif;
    }

    #v-nav >ul
    {
        float: left;
        width: 210px;
        display: block;
        position: relative;
        top: 0;
        border: 1px solid #DDD;
        border-right-width: 0;
        margin: auto 0 !important;
        padding:0;
    }

    #v-nav >ul >li
    {
        width: 180px;
        list-style-type: none;
        display: block;
        text-shadow: 0px 1px 1px #F2F1F0;
        font-size: 1.11em;
        position: relative;
        border-right-width: 0;
        border-bottom: 1px solid #DDD;
        margin: auto;
        padding: 10px 15px !important;  
        background: whiteSmoke; /* Old browsers */
        background: -moz-linear-gradient(top, #ffffff 0%, #f2f2f2 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #ffffff), color-stop(100%, #f2f2f2)); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top, #ffffff 0%, #f2f2f2 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top, #ffffff 0%, #f2f2f2 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(top, #ffffff 0%, #f2f2f2 100%); /* IE10+ */
        background: linear-gradient(top, #ffffff 0%, #f2f2f2 100%); /* W3C */       
    }

    #v-nav >ul >li.current
    {
        color: black;
        border-right: none;
        z-index: 10;
        background: white !important;
        position: relative;
        moz-box-shadow: inset 0 0 35px 5px #fafbfd;
        -webkit-box-shadow: inset 0 0 35px 5px #fafbfd;
        box-shadow: inset 0 0 35px 5px #fafbfd;
    }

    #v-nav >ul >li.first.current
    {
        border-bottom: 1px solid #DDD;
    }

    #v-nav >ul >li.last
    {
        border-bottom: none;
    }

    #v-nav >div.tab-content
    {
        margin-left: 210px;
        border: 1px solid #ddd;
        background-color: #FFF;
        min-height: 400px;
        position: relative;
        z-index: 9;
        padding: 12px;
        moz-box-shadow: inset 0 0 35px 5px #fafbfd;
        -webkit-box-shadow: inset 0 0 35px 5px #fafbfd;
        box-shadow: inset 0 0 35px 5px #fafbfd;
        display: none;
        padding: 25px;
    }

    #v-nav >div.tab-content >h4
    {
        font-size: 1.2em;
        color: Black;
        text-shadow: 0px 1px 1px #F2F1F0;
        border-bottom: 1px dotted #EEEDED;
        padding-top: 5px;
        padding-bottom: 5px;
    }



        </style>
    <?php
    echo "<table id='roletable' class='widefat'><thead><tr><th width='80%'>Role Name</th><th>Action</th></thead><tbody>";
    $core_roles = array('administrator','editor','author','contributor','subscriber');
    foreach( $roles as $role => $name ) { 
        ?>
    <tr id="tr<?php echo $role; ?>"><td><?php echo $name;?><div style="display: none" id="edit-<?php echo $role; ?>"><br/><br/>Capabilities Edit Option coming with next update... <br/>Thanks for your patient<br/></div></td><td><?php if(!in_array($role,$core_roles)): ?><div class="button-group"><input type="button" onclick="return edit_role('<?php echo $role; ?>')" class="button button-primary left button-small" value="Edit" /><input type="button" onclick="return delete_role('<?php echo $role; ?>')" class="button button-secondary button-small" value="Delete" /></div><?php endif; ?></td></tr>
    <?php
    }
    ?>
    <tr><td colspan="2"><input placeholder="Enter a role name here" type="text" id="wpdm_cal_new_role" /><input type="button" class="button button-large" value="Create" onclick="return createrole();" /> <span id='pwt' style="display: none;">Please wait...</span></td></tr>
    <?php
    echo "</tbody></table>";
}
 
function wpdm_cal_enqueue_scripts(){
    if(get_post_type()=='wpdmpro'){
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-autocomplete');
    }

}

function wpdm_cal_tobyte($p_sFormatted) {
    $aUnits = array('B'=>0, 'KB'=>1, 'MB'=>2, 'GB'=>3, 'TB'=>4, 'PB'=>5, 'EB'=>6, 'ZB'=>7, 'YB'=>8);
    $sUnit = strtoupper(trim(substr($p_sFormatted, -2)));
    if (intval($sUnit) !== 0) {
        $sUnit = 'B';
    }
    if (!in_array($sUnit, array_keys($aUnits))) {
        return false;
    }
    $iUnits = trim(substr($p_sFormatted, 0, strlen($p_sFormatted) - 2));
    if (!intval($iUnits) == $iUnits) {
        return false;
    }
    return $iUnits * pow(1024, $aUnits[$sUnit]);
}

function wpdm_cal_custom_data($data){
    global $current_user;
    if(is_user_logged_in() && isset($data['user_access']) && is_array($data['user_access']) && in_array($current_user->user_login, $data['user_access']))
    $data['access'] = array('guest');
    return $data;
}


function wpdm_cal_category_query_params($params){
    unset($params['meta_query']);
    return $params;
}

if(is_admin()){
      
    //add_action('wp_ajax_wpdm_cal_create_new_role','wpdm_cal_create_new_role');
    //add_action('wp_ajax_wpdm_cal_remove_role','wpdm_cal_remove_role');
    add_action('wp_ajax_wpdm_cal_suggest_members','wpdm_cal_suggest_members');
    add_action("admin_head",'wpdm_cal_interface_action');
    add_action("admin_enqueue_scripts",'wpdm_cal_enqueue_scripts');
}

add_filter("wdm_before_fetch_template","wpdm_block_dllink");
add_shortcode('wpdm_my_downloads','wpdm_cal_my_downloads');
add_filter("before_download",'wpdm_cal_check_permission');
add_filter("wdm_pre_render_link",'wpdm_hide_onno_permission');
add_filter("wpdm_custom_data",'wpdm_cal_custom_data');
add_filter("wpdm_embed_category_query_params",'wpdm_cal_category_query_params');
//add_wdm_settings_tab('user-roles', 'User Roles', 'wpdm_acl_user_roles');

