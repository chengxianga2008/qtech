<?php
/*
Plugin Name: WPDM - Default Values
Description: Add-on to setup default values for packages when you hit add new button
Plugin URI: http://www.wpdownloadmanager.com/
Author: Shaon
Version: 1.4.0
Author URI: http://www.wpdownloadmanager.com/
*/


class WPDM_Default_Values {

    function __construct(){
        if(!defined('WPDM_BASE_URL')) return;
        //add_wdm_settings_tab('default-values', 'Default Values', array($this, 'default_values'));
        add_filter('add_wpdm_settings_tab',array($this, 'settings_tab'));
        add_filter('get_post_metadata',array($this, 'get_post_metadata'),10,4);
        //add_filter('post_row_actions',array($this, 'clone_package_action'),10,2);
        //add_action('init',array($this, 'clone_package'),10,2);
        add_action('wp_ajax_wpdm_bulk_update',array($this, 'bulk_update'),10,2);
    }

    function settings_tab($tabs){
        $tabs['default-values'] = wpdm_create_settings_tab('default-values','Default Values',array($this, 'default_values'), 'fa fa-bolt');
        return $tabs;
    }

    function default_values(){
        if(isset($_POST['task']) && $_POST['task']=='wdm_save_settings' && isset($_POST['tab']) && $_POST['tab']=='default-settings'){
            update_option('__wpdm_defaults',$_POST['wpdm_defaults']);
            die("Default Values Saved Successfully!");
        }
        $default = get_option('__wpdm_defaults', array());
        if(count($default)==0){
            $default = array('version'=>'',
                'link_label'=>'',
                'quota'=>'',
                'download_limit_per_user'=>'',
                'view_count'=>'',
                'download_count'=>'',
                'package_size'=>'',
                'access'=>array('guest'),
                'individual_file_download'=>1,
                'template'=>'',
                'page_template'=>'',
                'password_lock'=>'',
                'linkedin_lock'=>'',
                'tweet_lock'=>'',
                'gplusone_lock'=>'',
                'facebooklike_lock'=>'',
                'email_lock'=>'',
                'icon'=>'',
            );
        }

        include(__DIR__.'/package-settings.php');
    }

    function bulk_update(){
        if(!current_user_can(WPDM_ADMIN_CAP)) return;
        global $wpdb;
        if(is_array($_POST['meta_value']))
            $meta_value = serialize($_POST['meta_value']);
        else
            $meta_value = esc_attr($_POST['meta_value']);
        $meta_name = esc_attr($_POST['meta_name']);
        $wpdb->update($wpdb->prefix."postmeta", array('meta_value' => $meta_value), array('meta_key' => $meta_name));
        die('ok');
    }


    function get_post_metadata($check, $object_id, $meta_key, $single ){
        global $pagenow;
        if($pagenow != 'post-new.php' || get_post_type()!='wpdmpro') return $check;
        $default = get_option('__wpdm_defaults', array());
        $meta_key_tmp = str_replace("__wpdm_","", $meta_key);
        if($meta_key_tmp=='access')  $default[$meta_key_tmp] = array($default[$meta_key_tmp]);
        if(isset($default[$meta_key_tmp])) return $default[$meta_key_tmp];
        return $check;
    }


    function clone_package_action($actions, $page_object){
        if($page_object->post_type!='wpdmpro') return $actions;
        $actions['wpdm_clone'] = '<a href="post.php?post=' . $page_object->ID . '&action=wpdm-clone" class="google_link">' . __('Clone','wpdmpro') . '</a>';
        return $actions;
    }

    function clone_package(){
        if(wpdm_query_var('action','txt')=='wpdm-clone'){
            $post_data = get_post(wpdm_query_var('post','int'), ARRAY_A);
            unset($post_data['ID']);
            $ID = wp_insert_post($post_data);
            $cdata = get_post_custom(wpdm_query_var('post','int'));

            if(is_array($cdata)){
                foreach ($cdata as $k => $v) {
                    update_post_meta($ID, $k, maybe_unserialize($v[0]));
                }}
            wp_redirect("post.php?post={$ID}&action=edit");
            die();
        }
    }




}

new WPDM_Default_Values();
 


