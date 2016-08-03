 
<input type="hidden" name="tab" value="default-settings">
<div id="tabs">
    <div class="panel panel-default">
        <div class="panel-heading"><b>Select Default Setting</b></div>
    <div class="panel-body1">
<div id="package-settings" class="tabc tab-pane active">
    <table cellpadding="5" id="file_settings_table" cellspacing="0" width="100%" class="frm table table-striped">
        <tr id="version_row">
            <td width="90px"><?php echo __('Version:','wpdmpro'); ?></td>
            <td>
                <div class="input-group"  style="width: 200px">
                <input size="10" type="text" class="form-control input-sm" value="<?php echo $default['version']; ?>" id="wd_verion" name="wpdm_defaults[version]" />
                <span class="input-group-btn"><button data-target="wd_verion" data-field="__wpdm_version" class="btn btn-default btn-sm btn-update-all"><i class="fa fa-floppy-o"></i> Update All</button></span></div>
            </td></tr>

        <tr id="link_label_row">
            <td width="90px"><?php echo __('Link Label:','wpdmpro'); ?></td>
            <td>
                <div class="input-group" style="width: 300px">
                <input size="10" id="wd_link_label" type="text" class="form-control input-sm" value="<?php echo $default['link_label']; ?>" name="wpdm_defaults[link_label]" />
                <span class="input-group-btn"><button data-target="wd_link_label" data-field="__wpdm_link_label" class="btn btn-default btn-sm btn-update-all"><i class="fa fa-floppy-o"></i> Update All</button></span></div>
            </td></tr>

        <tr id="stock_row">
            <td><?php echo __('Stock&nbsp;Limit:','wpdmpro'); ?></td>
            <td>
                <div class="input-group" style="width: 200px">
                <input size="10" type="number" id="wd_quota" class="form-control input-sm" name="wpdm_defaults[quota]" value="<?php echo $default['quota']; ?>" />
                <span class="input-group-btn"><button data-target="wd_quota" data-field="__wpdm_quota" class="btn btn-default btn-sm btn-update-all"><i class="fa fa-floppy-o"></i> Update All</button></span></div>
            </td>
        </tr>

        <tr id="downliad_limit_row">
            <td><?php echo __('Download&nbsp;Limit:','wpdmpro'); ?></td>
            <td>
                <div class="input-group" style="width: 200px">
                <input size="10"  type="number" id="wd_ul" class="form-control input-sm" name="wpdm_defaults[download_limit_per_user]" value="<?php echo $default['download_limit_per_user']; ?>" />
                <span class="input-group-btn"><button data-target="wd_ul" data-field="__wpdm_download_limit_per_user" class="btn btn-default btn-sm btn-update-all"><i class="fa fa-floppy-o"></i> Update All</button></span></div>
                / user</td>
        </tr>

        <tr id="view_count_row">
            <td><?php echo __('View&nbsp;Count:','wpdmpro'); ?></td>
            <td>
                <div class="input-group" style="width: 200px">
                <input size="10" type="number" id="wd_view_count" class="form-control input-sm" name="wpdm_defaults[view_count]" value="<?php echo $default['view_count']; ?>" />
                <span class="input-group-btn"><button data-target="wd_view_count" data-field="__wpdm_view_count" class="btn btn-default btn-sm btn-update-all"><i class="fa fa-floppy-o"></i> Update All</button></span></div>
                </td>
        </tr>

       <tr id="download_count_row">
            <td><?php echo __('Download&nbsp;Count:','wpdmpro'); ?></td>
            <td>
                <div class="input-group" style="width: 200px">
                <input size="10" type="number" class="form-control input-sm" id="wd_download_count" name="wpdm_defaults[download_count]" value="<?php echo $default['download_count']; ?>" />
                    <span class="input-group-btn"><button data-target="wd_download_count" data-field="__wpdm_download_count" class="btn btn-default btn-sm btn-update-all"><i class="fa fa-floppy-o"></i> Update All</button></span></div>
            </td>
        </tr>

       <tr id="package_size_row">
            <td><?php echo __('Package&nbsp;Size:','wpdmpro'); ?></td>
            <td>
                <div class="input-group" style="width: 200px">
                <input size="10" type="text" id="package_size" class="form-control input-sm" name="wpdm_defaults[package_size]" value="<?php echo $default['package_size']; ?>" />
                <span class="input-group-btn"><button data-target="wd_package_size" data-field="__wpdm_package_size" class="btn btn-default btn-sm btn-update-all"><i class="fa fa-floppy-o"></i> Update All</button></span></div>
            </td>
        </tr>

        <tr id="access_row">
            <td valign="top"><?php echo __('Allow Access:','wpdmpro'); ?></td>
            <td>
                <div class="pull-left">
                <select name="wpdm_defaults[access][]" class="chzn-select role" multiple="multiple" id="wd_access" style="min-width: 250px;">
                    <?php

                    $currentAccess = $default['access'];
                    $selz = '';
                    if(  $currentAccess ) $selz = (in_array('guest',$currentAccess))?'selected=selected':'';
                    //if(!isset($_GET['post'])) $selz = 'selected=selected';
                    ?>

                    <option value="guest" <?php echo $selz  ?>><?php echo __("All Visitors","wpdmpro"); ?></option>
                    <?php
                    global $wp_roles;
                    $roles = array_reverse($wp_roles->role_names);
                    foreach( $roles as $role => $name ) {



                        if(  $currentAccess ) $sel = (in_array($role,$currentAccess))?'selected=selected':'';
                        else $sel = '';



                        ?>
                        <option value="<?php echo $role; ?>" <?php echo $sel  ?>> <?php echo $name; ?></option>
                    <?php } ?>
                </select>
                    </div>
                &nbsp;<button data-target="wd_access" data-field="__wpdm_access" class="btn btn-default btn-sm btn-update-all"><i class="fa fa-floppy-o"></i> Update All</button>
            </td></tr>

        <tr id="individual_file_download_row">
            <td><?php echo __('Single File Download:','wpdmpro'); ?></td>
            <td>


                <div  class="pull-left">
                    <select name="wpdm_defaults[individual_file_download]" id="wd_eid">
                        <option value="-1">Use Global</option>
                        <option value="1">Enable</option>
                        <option value="0">Disable</option>
                    </select>
                </div>
                    &nbsp;<button data-target="wd_eid"  data-field="__wpdm_individual_file_download" class="btn btn-default btn-sm btn-update-all"><i class="fa fa-floppy-o"></i> Update All</button>


            </td>
        </tr>

        <tr id="template_row">
            <td><?php echo __('Link Template:','wpdmpro'); ?></td>
            <td>
                <div  class="pull-left">
                <select name="wpdm_defaults[template]" id="wd_lnk_tpl" onchange="jQuery('#lerr').remove();">
                    <?php
                    $ctpls = scandir(WPDM_BASE_DIR.'/templates/');
                    array_shift($ctpls);
                    array_shift($ctpls);
                    $ptpls = $ctpls;
                    foreach($ctpls as $ctpl){
                        $tmpdata = file_get_contents(WPDM_BASE_DIR.'/templates/'.$ctpl);
                        if(preg_match("/WPDM[\s]+Link[\s]+Template[\s]*:([^\-\->]+)/",$tmpdata, $matches)){

                            ?>
                            <option value="<?php echo $ctpl; ?>"  <?php selected($default['template'],$ctpl); ?>><?php echo $matches[1]; ?></option>
                        <?php
                        }
                    }
                    $templates = get_option("_fm_link_templates");
                    if($templates) $templates = maybe_unserialize($templates);
                    if(is_array($templates)){
                        foreach($templates as $id=>$template) {
                            ?>
                            <option value="<?php echo $id; ?>"  <?php selected($default['template'],$id); ?>><?php echo $template['title']; ?></option>
                        <?php } } ?>
                </select>
                </div>
                &nbsp;<button data-target="wd_lnk_tpl" data-field="__wpdm_template" class="btn btn-default btn-sm btn-update-all"><i class="fa fa-floppy-o"></i> Update All</button>
            </td>
        </tr>


        <tr id="page_template_row">
            <td><?php echo __('Page Template:','wpdmpro'); ?></td>
            <td><div  class="pull-left">
                <select name="wpdm_defaults[page_template]" id="wd_pge_tpl" onchange="jQuery('#perr').remove();">
                    <?php


                    foreach($ptpls as $ctpl){
                        $tmpdata = file_get_contents(WPDM_BASE_DIR.'/templates/'.$ctpl);
                        if(preg_match("/WPDM[\s]+Template[\s]*:([^\-\->]+)/",$tmpdata, $matches)){

                            ?>
                            <option value="<?php echo $ctpl; ?>"  <?php selected($default['page_template'],$ctpl); ?>><?php echo $matches[1]; ?></option>
                        <?php
                        }
                    }

                    $templates = get_option("_fm_page_templates");
                    if($templates) $templates = maybe_unserialize($templates);
                    if(is_array($templates)){
                        foreach($templates as $id=>$template) {
                            ?>
                            <option value="<?php echo $id; ?>"  <?php selected($default['page_template'],$id); ?>><?php echo $template['title']; ?></option>
                        <?php } } ?>
                </select>
                </div>
                &nbsp;<button data-target="wd_pge_tpl" data-field="__wpdm_page_template" class="btn btn-default btn-sm btn-update-all"><i class="fa fa-floppy-o"></i> Update All</button>
            </td>
        </tr>
        <?php if(isset($_GET['post'])&&$_GET['post']!=''){ ?>
            <tr>
                <td><?php echo __('Reset Key','wpdmpro'); ?></td>
                <td><input type="checkbox" value="1" name="reset_key" /> <?php echo __('Regenerate Master Key for Download','wpdmpro'); ?> <span class="info infoicon" title="<?php echo __('This key can be used for direct download','wpdmpro'); ?>"> </span></td>
            </tr>
        <?php } ?>

    </table>
    <div class="clear"></div>
</div>
</div></div>
<?php //include("lock-options.php"); ?>
    <div class="panel panel-default">
        <div class="panel-heading"><b>Select Default Icon</b></div>
        <div class="panel-body">

<?php include("icons.php"); ?>


</div>
</div>

    <?php //include("bulkops.php"); ?>

</div>








<!-- all js ------>

<script type="text/javascript">

    jQuery(document).ready(function($) {


        $('#section').val('default-values');
        $('.form-control, select').on('focus', function(){
            $('.btn-update-all').html('<i class="fa fa-floppy-o"></i> Update All');
        });
        $('.btn-update-all').on('click', function(e){
            e.preventDefault();
            if(!confirm('Are you sure to update all packages?')) return false;
            var bhtml = $(this).html();
            $(this).html('<i class="fa fa-spin fa-refresh"></i> Updating...');
            var $this = $(this);
            $.post(ajaxurl, { action: 'wpdm_bulk_update', meta_name: $(this).data('field'), meta_value: $('#'+$(this).data('target')).val()}, function(res){
                $this.html('<i class="fa fa-check"></i> Updated');
            });
            return false;
        });


    });


</script>
<style>
    .form-control{
        display: inline !important;
    }
    td{
        vertical-align: middle !important;
    }
</style>
