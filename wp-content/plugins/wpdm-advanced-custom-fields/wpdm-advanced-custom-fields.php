<?php
/*
  Plugin Name: WPDM - Advanced Custom Fields
  Plugin URI: http://www.wpdownloadmanager.com/
  Description: Advanced Custom Fields Manager Add-on for WordPress Download Manager
  Author: Shaon
  Version: 1.4.1
  Author URI: http://www.wpdownloadmanager.com/
 */

define('DMCF_BASE_DIR', dirname(__FILE__) . '/');
define('DMCF_BASE_URL', plugins_url('/download-manager-custom-field/'));

class wpdm_acf
{

    function __construct()
    {
        add_action('admin_menu', array($this, 'dmcf_custom_fields'));
        add_action('admin_enqueue_scripts', array($this, 'dmcf_admin_enqueue_scripts'));
        add_action('wp_ajax_save_dmcf_custom_field', array($this, 'dmcf_save_field'));
        add_action('add_meta_boxes', array($this, 'dmcf_add_metaboxes'));
        add_action('save_post', array($this, 'dmcf_save_meta_box_data'));
        add_filter('wdm_before_fetch_template', array($this, 'wpdm_show_acf_groups'));
        add_filter('wpdm_render_custom_form_fields', array($this, 'wpdm_show_s2dcf'), 10, 2);
        if(is_admin()){
            add_filter('add_wpdm_settings_tab',  array($this,'settings_tab'));
            add_filter('wpdm_export_custom_form_fields',  array($this,'s2dcf_export'));
            add_action('wpdm_custom_form_field',  array($this,'custom_form_fields'));
        }
    }

    function settings_tab($tabs){
        $tabs['s2dcf'] = wpdm_create_settings_tab('s2dcf', 'Subscription Form', array($this,'s2d_custom_fields'), 'fa fa-envelope');
        return $tabs;

    }

    function custom_form_fields(){
        $s2dcf_group_names = get_option('s2dcf_group_names');
        $sel = get_post_meta(get_the_ID(), '__wpdm_s2dcf', true);
        echo "Additional Fields:<br/><select name='file[s2dcf]' style='width: 250px' id='acffg'>";
        foreach($s2dcf_group_names as $key => $name){
            if($sel == $key)
            echo "<option value='$key' selected='selected'>$name</option>";
            else
            echo "<option value='$key'>$name</option>";
        }
        echo "</select>";
    }

    public function wpdm_show_s2dcf($html, $id){
        global $post;
        $s2dcf_fields_label = get_option('s2dcf_field_label');
        $sel = get_post_meta($id, '__wpdm_s2dcf', true);
        ob_start();
        $data = $s2dcf_fields_label[$sel];
        $data['group'] = $sel;
        $this->s2dcf_render($post, $data);
        $data = ob_get_clean();
        return $html.$data;
    }

    public function s2dcf_export($fields){
        $s2dcf_group_names = get_option('s2dcf_group_names');
        $s2dcf_field_label = get_option('s2dcf_field_label');
        $s2dcf_field_name = get_option('s2dcf_field_name');
        $tfields = array();
        foreach($s2dcf_group_names as $groupid => $gname){
            $tfields += array_combine($s2dcf_field_name[$groupid], $s2dcf_field_label[$groupid]);

        }
        $fields += array_keys($tfields);
        return $fields;
    }

    public function s2d_custom_fields(){

        if(wpdm_query_var('section') == 's2dcf' && wpdm_query_var('task') == 'wdm_save_settings' ){
            $s2dcf_field_label = $_REQUEST['field-label'];
            $s2dcf_field_name = $_REQUEST['field-name'];
            $s2dcf_field_type = $_REQUEST['field-type'];
            $s2dcf_field_choice = $_REQUEST['field-choices'];
            $s2dcf_group_names = $_REQUEST['groupnames'];
            if (!empty($dmcf_field_name) || !empty($s2dcf_field_label) || !empty($s2dcf_field_type) || !empty($s2dcf_field_choice)) {
                update_option('s2dcf_field_label', $s2dcf_field_label);
                update_option('s2dcf_field_name', $s2dcf_field_name);
                update_option('s2dcf_field_type', $s2dcf_field_type);
                update_option('s2dcf_field_choice', $s2dcf_field_choice);
                update_option('s2dcf_group_names', $s2dcf_group_names);
            } else {
                die('Error: No field created yet');
            }
            die('Settings Saved Successfully.');
        }

        require_once DMCF_BASE_DIR."tpls/s2dcf-create-field.php";


    }



    function dmcf_save_meta_box_data($post_id)
    {
        if (!isset($_POST['dmcf_meta_box_nonce'])) {
            return;
        }
        if (!wp_verify_nonce($_POST['dmcf_meta_box_nonce'], 'dmcf_meta_box')) {
            return;
        }
        if (!isset($_POST['wpdmproacf'])) {
            return;
        }
        $wpdmpro_data = $_POST['wpdmproacf'];
        update_post_meta($post_id, '__wpdmpro_custom_fields', $wpdmpro_data);
    }

    function wpdm_show_acf_groups($vars)
    {
        $dmcf_group_names = get_option('dmcf_group_names');
        $alldata = get_post_meta($vars['ID'], '__wpdmpro_custom_fields', true);

        if(is_array($dmcf_group_names)){
        foreach ($dmcf_group_names as $key => $name) {

            $vars['wpdm_acf-' . $key] = self::acf_group($vars['ID'], $key);

            if (is_array($alldata) && isset($alldata[$key])) {
                $data = $alldata[$key];
                if(is_array($data)){
                foreach ($data as $skey => $value) {
                    if (is_array($value)) $value = implode(", ", $value);
                    $vars['wpdm_acf-' . $key . "-" . $skey] = $value;
                }}
            }

        }}
        return $vars;
    }

    public static function acf_group($ID, $groupid)
    {
        $alldata = get_post_meta($ID, '__wpdmpro_custom_fields', true);
        $dmcf_group_names = get_option('dmcf_group_names');
        $dmcf_field_label = get_option('dmcf_field_label');
        $dmcf_field_name = get_option('dmcf_field_name');
        if (!isset($alldata[$groupid])) return '';
        $data = $alldata[$groupid];
        $fields = array_combine($dmcf_field_name[$groupid], $dmcf_field_label[$groupid]);
        ob_start();
        ?>
        <div class="panel panel-default">
            <div class="panel-heading"><?php echo $dmcf_group_names[$groupid]; ?></div>
            <table class="table">
                <?php foreach ($data as $key => $value):
                    if (is_array($value)) $value = implode(", ", $value);
                    ?>
                    <tr>
                        <td><?php echo $fields[$key]; ?></td>
                        <td><?php echo $value; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <?php
        $datahtml = ob_get_clean();
        return $datahtml;
    }

    function dmcf_add_metaboxes()
    {
        $dmcf_field_label = get_option('dmcf_field_label');
        $dmcf_group_names = get_option('dmcf_group_names');
        $total_groups = count($dmcf_group_names);
        $count = 0;
        if (!empty($dmcf_field_label)) {
            foreach ($dmcf_field_label as $key => $meta_data) {
                $meta_data['group'] = $key;
                add_meta_box(
                    $dmcf_group_names[$key], __($dmcf_group_names[$key], 'download-manager-custom-fields'), array($this, 'dmcf_create_metabox'), 'wpdmpro', 'advanced', 'default', array('my_data' => $meta_data)
                );
                $count++;
            }
        }
    }

    function dmcf_create_metabox($post, $meta_data)
    {

        wp_nonce_field('dmcf_meta_box', 'dmcf_meta_box_nonce');
        $wpdmpro_fields = get_post_meta($post->ID, '__wpdmpro_custom_fields', true);
        $dmcf_field_name = get_option('dmcf_field_name');
        $dmcf_field_types = get_option('dmcf_field_type');
        $dmcf_field_choices = get_option('dmcf_field_choice');
        $label_data = $meta_data['args']['my_data'];
        $group_name = $label_data['group'];
        unset($label_data['group']);
        foreach ($label_data as $key => $value) {
            $field_name = $dmcf_field_name[$group_name][$key];
            $field_type = $dmcf_field_types[$group_name][$key];
            $field_choices = $dmcf_field_choices[$group_name][$key];
            $field_choices_array = explode("\n", $field_choices);
            ?>
            <div class="field-group">
            <?php
            if ($field_type == 'select') {
                ?>
                <label><?php echo $value; ?></label>
                <select name="<?php echo 'wpdmproacf[' . $group_name . '][' . $field_name . ']' ?>"
                        style="margin-bottom: 7px">
                    <?php foreach ($field_choices_array as $key => $value) { ?>
                        <option value="<?php echo $value ?>" <?php
                        if (!empty($wpdmpro_fields[$group_name][$field_name]) && $wpdmpro_fields[$group_name][$field_name] == $value) {
                            echo 'selected=selected';
                        } else {

                        }
                        ?>><?php echo $value ?></option>
                    <?php } ?>
                </select><br>
                <hr>
            <?php
            } else if ($field_type == 'radiobutton') {
                ?>
                <fieldset>
                    <legend><?php echo $value; ?></legend>
                    <ul>
                        <?php
                        foreach ($field_choices_array as $key => $value) {
                            ?>
                            <li>
                                <label>
                                    <input type="radio"
                                           name="<?php echo 'wpdmproacf[' . $group_name . '][' . $field_name . ']' ?>"
                                           value="<?php echo $value ?>" <?php
                                    if (!empty($wpdmpro_fields[$group_name][$field_name]) && $wpdmpro_fields[$group_name][$field_name] == $value) {
                                        echo 'checked=checked';
                                    } else {

                                    }
                                    ?>>
                                    <?php echo $value ?>
                                </label>
                            </li>
                        <?php
                        } ?>
                    </ul>
                </fieldset>
            <?php } else if ($field_type == 'checkbox') { ?>
                <fieldset>
                    <legend><?php echo $value; ?></legend>
                    <ul>
                        <?php
                        foreach ($field_choices_array as $key => $value) {
                            ?>
                            <li>
                                <label>
                                    <input
                                        name='<?php echo 'wpdmproacf[' . $group_name . '][' . $field_name . '][' . $key . ']' ?>'
                                        value="<?php echo $value ?>" type="checkbox" <?php
                                    if (!empty($wpdmpro_fields[$group_name][$field_name][$key]) && $wpdmpro_fields[$group_name][$field_name][$key] == $value) {
                                        echo 'checked=checked';
                                    } else {

                                    }
                                    ?>> <?php echo $value ?>
                                </label>
                            </li>
                        <?php
                        } ?></ul>
                </fieldset>
            <?php
            } else if ($field_type == 'text') {
                ?>
                <label><?php echo $value; ?></label>
                <input type="text" name="<?php echo 'wpdmproacf[' . $group_name . '][' . $field_name . ']' ?>"
                       value="<?php echo !empty($wpdmpro_fields[$group_name][$field_name]) ? esc_html($wpdmpro_fields[$group_name][$field_name]) : '' ?>"
                       style="margin-bottom: 7px"/><br>
            <?php
            } else if ($field_type == 'textarea') {
                ?>
                <fieldset>
                    <legend><?php echo $value; ?></legend>
                    <textarea rows="5" cols="80"
                              name="<?php echo 'wpdmproacf[' . $group_name . '][' . $field_name . ']' ?>"
                              style="margin-bottom: 7px"><?php echo !empty($wpdmpro_fields[$group_name][$field_name]) ? esc_html($wpdmpro_fields[$group_name][$field_name]) : '' ?></textarea>
                </fieldset>
            <?php
            } else if ($field_type == 'number') {
                ?>
                <label><?php echo $value; ?></label>
                <input type="number" name="<?php echo 'wpdmproacf[' . $group_name . '][' . $field_name . ']' ?>"
                       value="<?php echo !empty($wpdmpro_fields[$group_name][$field_name]) ? esc_html($wpdmpro_fields[$group_name][$field_name]) : '' ?>"
                       style="margin-bottom: 7px"/><br>
            <?php
            }
            echo '</div>';
        } ?>
        <style>
            .field-group {
                margin-bottom: 10px;
            }

            fieldset {
                border: 1px solid #eeeeee;
                padding: 10px;
            }

            legend {
                font-weight: bold;
            }
        </style>
    <?php
    }

    function s2dcf_render($post, $meta_data)
    {
        wp_nonce_field('s2dcf_meta_box', 's2dcf_meta_box_nonce');
        //$wpdmpro_fields = get_post_meta($post->ID, '__wpdmpro_custom_fields', true);
        $dmcf_field_name = get_option('s2dcf_field_name');
        $dmcf_field_types = get_option('s2dcf_field_type');
        $dmcf_field_choices = get_option('s2dcf_field_choice');
        $label_data = $meta_data;
        $group_name = $label_data['group'];
        unset($label_data['group']);
        foreach ($label_data as $key => $value) {
            $field_name = $dmcf_field_name[$group_name][$key];
            $field_type = $dmcf_field_types[$group_name][$key];
            $field_choices = $dmcf_field_choices[$group_name][$key];
            $field_choices_array = explode("\n", $field_choices);
            //[' . $group_name . ']
            ?>
            <div class="field-group">
            <?php
            if ($field_type == 'select') {
                ?>
                <label><?php echo $value; ?></label>
                <select class="form-control" name="<?php echo 'custom_form_field[' . $field_name . ']' ?>" >
                    <?php foreach ($field_choices_array as $key => $value) { ?>
                        <option value="<?php echo $value ?>"><?php echo $value ?></option>
                    <?php } ?>
                </select><br>
                <hr>
            <?php
            } else if ($field_type == 'radiobutton') {
                ?>
                <fieldset>
                    <legend><?php echo $value; ?></legend>
                    <ul>
                        <?php
                        foreach ($field_choices_array as $key => $value) {
                            ?>
                            <li>
                                <label>
                                    <input type="radio" name="<?php echo 'custom_form_field[' . $field_name . ']' ?>" value="<?php echo $value ?>" >
                                    <?php echo $value ?>
                                </label>
                            </li>
                        <?php
                        } ?>
                    </ul>
                </fieldset>
            <?php } else if ($field_type == 'checkbox') { ?>
                <fieldset>
                    <legend style="font-size: inherit; color: inherit"><?php echo $value; ?></legend>
                    <ul>
                        <?php
                        foreach ($field_choices_array as $key => $value) {
                            ?>
                            <li style="list-style: none">
                                <label>
                                    <input name='<?php echo 'custom_form_field[' . $field_name . '][' . $key . ']' ?>' value="<?php echo $value ?>" type="checkbox"> <?php echo $value ?>
                                </label>
                            </li>
                        <?php
                        } ?></ul>
                </fieldset>
            <?php
            } else if ($field_type == 'text') {
                ?>
                <label><?php echo $value; ?></label>
                <input class="form-control" type="text" name="<?php echo 'custom_form_field[' . $field_name . ']' ?>" />
            <?php
            } else if ($field_type == 'textarea') {
                ?>
                <fieldset>
                    <legend><?php echo $value; ?></legend>
                    <textarea class="form-control" rows="5" cols="80" name="<?php echo 'custom_form_field[' . $field_name . ']' ?>"></textarea>
                </fieldset>
            <?php
            } else if ($field_type == 'number') {
                ?>
                <label><?php echo $value; ?></label>
                <input class="form-control" type="number" name="<?php echo 'custom_form_field[' . $field_name . ']' ?>" />
            <?php
            }
            echo '</div>';
        } ?>
        <style>
            .field-group {
                margin-bottom: 10px;
            }

            fieldset {
                border: 1px solid #dddddd !important;
                padding: 10px;
                border-radius: 4px;
            }

            legend {
                font-weight: bold;
                font-size: inherit; color: inherit;display: inline;float: left;padding: 10px !important;
                border-bottom: 1px solid #dddddd !important;

            }
        </style>
    <?php
    }


    function dmcf_custom_fields()
    {
        add_submenu_page("edit.php?post_type=wpdmpro", __('Custom Fields', 'download-manager-custom-field'), __('Custom Fields', 'download-manager-custom-field'), 'manage_options', 'wpdm-acf', array($this, 'dmcf_add_custom_fields'));
    }

    function dmcf_add_custom_fields()
    {
        include(DMCF_BASE_DIR . 'tpls/dmcf-create-field.php');
    }

    function dmcf_admin_enqueue_scripts($hook)
    {
        if ($hook != 'wpdmpro_page_wpdm-acf') return;
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-form');
        wp_enqueue_style('dmcf-admin-bootstrap-css', WPDM_BASE_URL . 'bootstrap/css/bootstrap.css');
        wp_enqueue_style('dmcf-admin-font-awesome-css', '//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css');
        wp_enqueue_script('dmcf-admin-bootstrap-js', WPDM_BASE_URL . 'bootstrap/js/bootstrap.min.js');
    }

    function dmcf_save_field()
    {
        $dmcf_field_label = array();
        $dmcf_field_name = array();
        $dmcf_field_type = array();
        $dmcf_field_choice = array();
        $dmcf_field_label = $_REQUEST['field-label'];
        $dmcf_field_name = $_REQUEST['field-name'];
        $dmcf_field_type = $_REQUEST['field-type'];
        $dmcf_field_choice = $_REQUEST['field-choices'];
        $dmcf_group_names = $_REQUEST['groupnames'];
        if (!empty($dmcf_field_name) || !empty($dmcf_field_label) || !empty($dmcf_field_type) || !empty($dmcf_field_choice)) {
            update_option('dmcf_field_label', $dmcf_field_label);
            update_option('dmcf_field_name', $dmcf_field_name);
            update_option('dmcf_field_type', $dmcf_field_type);
            update_option('dmcf_field_choice', $dmcf_field_choice);
            update_option('dmcf_group_names', $dmcf_group_names);
            echo 'Saved successfully';
            die;
        } else {
            die('Error: No field created yet');
        }
    }

}

function wpdm_acf($post_id, $name)
{
    $orgn_name = $name;
    $alldata = get_post_meta($post_id, '__wpdmpro_custom_fields', true);
    if(strpos($name, "/")) {
        $name = explode("/", $name);
        if(isset($alldata[$name[0]][$name[1]])) return $alldata[$name[0]][$name[1]];
    }
    if($orgn_name == '' || !is_string($orgn_name)) return '';
    if(is_array($alldata) && isset($alldata[$orgn_name])) return $alldata[$orgn_name];

}

new wpdm_acf();
