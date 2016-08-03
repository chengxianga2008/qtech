<?php defined('ABSPATH') or die('?'); ?>
<style>
    .dmcf-inline-class{
        display: inline !important;
    }
    .panel-heading a,
    .panel-heading a:focus,
    .panel-heading a:hover{
        text-decoration: none !important;
        color: #336699;
        outline: none;
    }
    .panel-heading a:before{
        content: "\f056";
        font-family: 'FontAwesome';
        padding-right: 5px;
        color: #336699;
    }
    .panel-heading a.collapsed{
        color: #222222;
    }
    .panel-heading a.collapsed:before{
        content: "\f055";
        font-family: 'FontAwesome';
        padding-right: 5px;
        color: #222222;
    }


input{
    padding: 7px;
}
#wphead{
    border-bottom:0px;
}
#screen-meta-links{
    display: none;
}
.wrap{
    margin: 0px;
    padding: 0px;
}
#wpbody{
    margin-left: -19px;
}
select{
    min-width: 150px;
}

.wpdm-loading {
    background: url('<?php  echo plugins_url('download-manager/images/wpdm-settings.png'); ?>') center center no-repeat;
    width: 16px;
    height: 16px;
    /*border-bottom: 2px solid #2a2dcb;*/
    /*border-left: 2px solid #ffffff;*/
    /*border-right: 2px solid #c30;*/
    /*border-top: 2px solid #3dd269;*/
    /*border-radius: 100%;*/

}

.w3eden .btn{
    border-radius: 0.2em !important;
}

.w3eden .nav-pills a{
    background: #f5f5f5;
}

.w3eden .form-control,
.w3eden .nav-pills a{
    border-radius: 0.2em !important;
    box-shadow: none !important;
    font-size: 9pt !important;
}

.wpdm-spin{
    -webkit-animation: spin 2s infinite linear;
    -moz-animation: spin 2s infinite linear;
    -ms-animation: spin 2s infinite linear;
    -o-animation: spin 2s infinite linear;
    animation: spin 2s infinite linear;
}

@keyframes "spin" {
    from {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    to {
        -webkit-transform: rotate(359deg);
        -moz-transform: rotate(359deg);
        -o-transform: rotate(359deg);
        -ms-transform: rotate(359deg);
        transform: rotate(359deg);
    }

}

@-moz-keyframes spin {
    from {
        -moz-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    to {
        -moz-transform: rotate(359deg);
        transform: rotate(359deg);
    }

}

@-webkit-keyframes "spin" {
    from {
        -webkit-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    to {
        -webkit-transform: rotate(359deg);
        transform: rotate(359deg);
    }

}

@-ms-keyframes "spin" {
    from {
        -ms-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    to {
        -ms-transform: rotate(359deg);
        transform: rotate(359deg);
    }

}

@-o-keyframes "spin" {
    from {
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    to {
        -o-transform: rotate(359deg);
        transform: rotate(359deg);
    }

}

.panel-heading h3.h{
    font-size: 11pt;
    font-weight: 700;
    margin: 0;
    padding: 5px 10px;
    font-family: 'Open Sans';
}

.btn-primary {
    background-color: #2081D5;
    background-image: linear-gradient(to bottom, #2081D5 0px, #1B6CB2 100%);
    background-repeat: repeat-x;
    border-color: #1D76C3 #1B6CB2 #134B7C !important;
    color: #FFFFFF;
}

.panel-heading .btn.btn-primary{

    border-radius: 3px;

    -webkit-transition: all 400ms ease-in-out;
    -moz-transition: all 400ms ease-in-out;
    -o-transition: all 400ms ease-in-out;
    transition: all 400ms ease-in-out;
}

.btn-info {
    background-color: #5AA2D3 !important;
    background-image: linear-gradient(to bottom, #5AA2D3 0px, #3A90CA 100%) !important;
    background-repeat: repeat-x;
    border-color: #4A99CF #3A90CA #2A6E9D !important;
    color: #FFFFFF;
}

.btn-danger {
    background-color: #DE090B !important;
    background-image: linear-gradient(to bottom, #DE090B 0px, #B70709 100%) !important;
    background-repeat: repeat-x;
    border-color: #CA080A #B70709 #7C0506 !important;
    color: #FFFFFF;
}

.btn-success {
    background-color: #5D9C22 !important;
    background-image: linear-gradient(to bottom, #5D9C22 0px, #497B1B 100%) !important;
    background-repeat: repeat-x;
    border-color: #538B1E #497B1B #2B4810 !important;
    color: #FFFFFF;
}

.btn-default {
    background-color: #FFFFFF;
    background-image: linear-gradient(to bottom, #FFFFFF 0px, #EBEBEB 100%) !important;
    background-repeat: repeat-x;
    border-color: #EBEBEB #E0E0E0 #C2C2C2 !important;
    color: #555555;
}

.alert-info {
    background-color: #DFECF7 !important;
    border-color: #B0D1EC !important;
}

ul.nav li a:active,
ul.nav li a:focus,
ul.nav li a{
    outline: none !important;
}

.w3eden .nav-pills li.active a,
.btn-primary,
.w3eden .panel-primary > .panel-heading{
    background-image: linear-gradient(to bottom, #2081D5 0px, #1B6CB2 100%) !important;
}
.w3eden .panel-default > .panel-heading {
    background-image: linear-gradient(to bottom, #F5F5F5 0px, #E1E1E1 100%);
    background-repeat: repeat-x;
}


</style>
<div class="w3eden">
    <div class="panel panel-primary" style="margin: 30px">
        <div class="panel-heading">
            <b style="font-size: 12pt;line-height:28px"><i class="fa fa-code"></i> &nbsp; <?php echo __("Advanced Custom Fields", "wpdmpro"); ?></b>

            <div style="clear: both"></div>
        </div>

            <div id="dmcf-message-label">

            </div>
            <form id="save-custom-field" class="form" method="post">
                <div class="panel-body">
                <input type="hidden" name="action" value="save_dmcf_custom_field" />
                <!--<div class="form-control">-->
                <div id="custom-field-body" class="panel-group">
                    <?php
                    $dmcf_fields_label = get_option('dmcf_field_label');
                    $dmcf_fields_name = get_option('dmcf_field_name');
                    $dmcf_fields_types = get_option('dmcf_field_type');
                    $dmcf_fields_choice = get_option('dmcf_field_choice');
                    $dmcf_group_names = get_option('dmcf_group_names');
//                    delete_option('dmcf_field_label');
//                    delete_option('dmcf_field_name');
//                    delete_option('dmcf_field_type');
//                    delete_option('dmcf_field_choice');
//                    delete_option('dmcf_group_names');
                    $total_array = count($dmcf_fields_label);
                    $dmcf_flag = 0;
                    $count_group = 0;
                    if (!empty($dmcf_fields_label)) {
                        foreach ($dmcf_fields_label as $key => $group) {
                            $dmcf_flag = $dmcf_flag + 1;
                            ?>
                            <div id='dmcf-new-group-<?php echo $key; ?>' class='panel panel-default panel-acf-group'>
                                <div class='panel-heading'> 
                                    <h4 class ='panel-title'> 
                                        <a data-toggle = 'collapse' data-parent = '#custom-field-body' href = '#dmcf-group<?php echo $dmcf_flag; ?>' > 
                                            <?php echo $dmcf_group_names[$key] ?>
                                        </a> 
                                        <span rel='<?php echo $key; ?>' type='button' style='margin-left:5px;' class='dmcf-delete-group pull-right btn btn-danger btn-xs'><i class='fa fa-trash-o'></i> Delete Group</span>
                                        <span data-group='<?php echo $key; ?>' rel='<?php echo $dmcf_flag; ?>' type='button' class='dmcf-add-field pull-right btn btn-primary btn-xs'><i class='fa fa-plus'></i> Add New Field</span>
                                        <input type='hidden' name='groupnames[<?php echo $key ;?>]' value='<?php echo $dmcf_group_names[$key]; ?>' />
                                    </h4> 
                                </div> 
                                <div id='dmcf-group<?php echo $dmcf_flag; ?>' class='panel-collapse collapse in'>
                                    <div class='panel-body'> 
                                        <div id ='panel-group-<?php echo $dmcf_flag; ?>' class='panel-group'>
                                            <?php
                                            foreach ($group as $field_key => $field_value) { $fid = uniqid();
                                                ?>
                                                <div id='dmcf-new-field-<?php echo $key . $field_key ?>' class='panel panel-default'> 
                                                    <div class='panel-heading'> 
                                                        <h4 class ='panel-title'> 
                                                            <a class="collapsed" data-toggle = 'collapse' data-parent = '#panel-group-<?php echo $dmcf_flag; ?>' href = '#<?php echo $key . $field_key; ?>' >
                                                                <?php echo $field_value; ?> 
                                                            </a> 
                                                            <span rel='<?php echo $key . $field_key ?>' type='button' class='dmcf-delete-field pull-right btn btn-danger btn-xs'><i class='fa fa-trash-o'></i> Delete Field</span>
                                                        </h4> 
                                                    </div> 
                                                    <div id='<?php echo $key . $field_key ?>' class='panel-collapse collapse'>
                                                        <div id = 'panel-<?php echo $key . $field_key; ?>' class='panel-body'> 
                                                            <label>Field Label</label> 
                                                            <input type = 'text' name= 'field-label[<?php echo $key; ?>][]' value="<?php echo isset($field_value) ? $field_value : '' ?>" class= 'form-control' style = 'width: 400px;' /><br> 
                                                            <label>Field Name</label> 
                                                            <input type = 'text' onkeyup="jQuery('#tt<?php echo $fid; ?>').val('[wpdm_acf-<?php echo $key; ?>-'+this.value+']');" name= 'field-name[<?php echo $key; ?>][]' value="<?php echo isset($dmcf_fields_name[$key][$field_key]) ? $dmcf_fields_name[$key][$field_key] : '' ?>" class= 'form-control' style = 'width: 400px;' /><br>
                                                            <label>Field Type</label> 
                                                            <select data-identity="<?php echo $key . $field_key; ?>" id='field-state-<?php echo $key . $field_key; ?>' name= 'field-type[<?php echo $key; ?>][]' class = 'dmcf_field_select form-control' style = 'width: 400px;'> 
                                                                <option value= 'text' <?php echo (isset($dmcf_fields_types[$key][$field_key]) && $dmcf_fields_types[$key][$field_key] == 'text') ? 'selected=selected' : '' ?>>Text</option> 
                                                                <option value= 'textarea' <?php echo (isset($dmcf_fields_types[$key][$field_key]) && $dmcf_fields_types[$key][$field_key] == 'textarea') ? 'selected=selected' : '' ?>>TextArea</option> 
                                                                <option value= 'number' <?php echo (isset($dmcf_fields_types[$key][$field_key]) && $dmcf_fields_types[$key][$field_key] == 'number') ? 'selected=selected' : '' ?>>Number</option> 
                                                                <option value= 'select' <?php echo (isset($dmcf_fields_types[$key][$field_key]) && $dmcf_fields_types[$key][$field_key] == 'select') ? 'selected=selected' : '' ?>>Select</option> 
                                                                <option value= 'checkbox' <?php echo (isset($dmcf_fields_types[$key][$field_key]) && $dmcf_fields_types[$key][$field_key] == 'checkbox') ? 'selected=selected' : '' ?>>Checkbox</option> 
                                                                <option value= 'radiobutton' <?php echo (isset($dmcf_fields_types[$key][$field_key]) && $dmcf_fields_types[$key][$field_key] == 'radiobutton') ? 'selected=selected' : '' ?>>Radiobutton</option> 
                                                            </select><br> 
                                                            <div id = 'field-choice-<?php echo $key . $field_key; ?>' class="<?php
                                                            if ($dmcf_fields_types[$key][$field_key] == 'select' || $dmcf_fields_types[$key][$field_key] == 'checkbox' || $dmcf_fields_types[$key][$field_key] == 'radiobutton') {
                                                                
                                                            } else {
                                                                echo 'hide';
                                                            }
                                                            ?>"> 
                                                                <label>Choices</label> 
                                                                <textarea name = 'field-choices[<?php echo $key; ?>][]' rows = '6' placeholder = 'Enter one choice in every new line' class = 'form-control' style = 'width: 400px;'><?php echo $dmcf_fields_choice[$key][$field_key]; ?></textarea><br> 
                                                            </div> 
                                                        </div>

                                                    </div>
                                                    <div class="panel-footer">
                                                        Template Tag: <input onfocus="this.select()" id="tt<?php echo $fid;?>" class="ttp" title="Use this tag in link or page template to show field data" data-toggle="tooltip" style="border: 0;color: #3173AD;width: 300px;text-align: center;font-family: 'Courier New'"  type="text" value="[wpdm_acf-<?php echo $key; ?>-<?php echo isset($dmcf_fields_name[$key][$field_key]) ? $dmcf_fields_name[$key][$field_key] : '' ?>]" />

                                                    </div>

                                                </div> 
                                            <?php }
                                            ?>
                                        </div> 
                                    </div> 
                                </div>
                                <div class="panel-footer">
                                    Template Tag: <input onfocus="this.select()" class="ttp" title="Use this tag in link or page template to show group data" data-toggle="tooltip" style="border: 0;color: #3173AD;width: 300px;text-align: center;font-family: 'Courier New'" type="text" value="[wpdm_acf-<?php echo $key; ?>]" />
                                    <div class="pull-right">&nbsp;PHP Code: <input onfocus="this.select()" class="ttp" data-toggle="tooltip" style="border: 0;width: 500px;text-align: center;font-family: 'Courier New';color: #3173AD" type="text" value="<?php echo '&lt;?php echo wpdm_acf::acf_group([ID],\''.$key.'\'); ?&gt;'; ?>" /></div>

                                </div>
                            </div>
                            <?php
                            $count_group++;
                        }
                    }
                    ?>
                </div>
                </div>
                <div  class="panel-footer">
                    <input id="dmcf-group-input" type="text" name="group-name" placeholder="Enter a Group Name" class="dmcf-inline-class form-control" style="width: 250px" />
                    <input id="dmcf-add-group" type="button" style="margin-bottom: 3px" class="dmcf-inline-class btn btn-primary" value="Add Group" />
                    <span id="dmcf-loading-custom-field" class="hide" style="margin-left: 20px;"><i class="fa fa-spinner fa-spin"></i> Please wait.....</span>
                    <input type="submit" class="btn btn-success pull-right" value="Save All Changes" />
                </div>
            </form>

    </div>

    <div class="alert alert-info" style="margin: 30px">
        Top print field value use tag <code>[wpdm_acf-<b>GroupName</b>-<b>FieldName</b>]</code> in link or page template
    </div>
</div>
<script>
    jQuery(document).ready(function($) {
        $('#save-custom-field').submit(function() {
            $('#dmcf-message-label').text('');
            $('#dmcf-message-label').removeClass('alert alert-success alert-danger')
            $('#dmcf-loading-custom-field').removeClass('hide');
            $(this).ajaxSubmit({
                url: ajaxurl,
                success: function(res) {
                    $('#dmcf-loading-custom-field').addClass('hide');
                    if (res != 'Error: No field created yet') {
                        $('#dmcf-message-label').addClass('alert alert-success');
                        $('#dmcf-message-label').append(res);
                    }
                    else {
                        $('#dmcf-message-label').addClass('alert alert-danger');
                        $('#dmcf-message-label').append(res);
                    }
                }
            });
            return false;
        });

        $('body').on('click', '.dmcf-delete-group', function(event) {
            var id = $(this).attr('rel');
            $('#dmcf-new-group-' + id).remove();
            return false;
        });

        $('body').on('click', '.dmcf-delete-field', function(event) {
            var id = $(this).attr('rel');
            $('#dmcf-new-field-' + id).remove();
            return false;
        });

        $('.dmcf_field_select').on('click', function(event) {
            var id = $(this).data('identity');
            $('#field-state-' + id).on('change', function(e) {
                if ($(this).val() == "select" || $(this).val() == "checkbox" || $(this).val() == "radiobutton") {
                    $('#field-choice-' + id).removeClass("hide");
                }
                else {
                    $('#field-choice-' + id).addClass("hide");
                }
                return false;
            });
            return false;
        });
        $('#dmcf-add-group').on('click', function(event) {
            var fieldID; // = new Date().getTime();
            var groupName = $('#dmcf-group-input').val();
            fieldID = groupName.replace(/[^A-Za-z0-9]+/, "");
            if (groupName != '') {
                $("<div id='dmcf-new-group-" + fieldID + "' class='panel panel-default'>" +
                        "<div class='panel-heading'>" +
                        "<h4 class ='panel-title'>" +
                        "<a data-toggle = 'collapse' data-parent = '#custom-field-body' href = '#dmcf-group" + fieldID + "' >" +
                        groupName +
                        "</a>" +
                        "<span type='button' rel='" + fieldID + "' style='margin-left:5px;' class='dmcf-delete-group pull-right btn btn-danger btn-xs'><i class='fa fa-trash-o'></i> Delete Group</span>" +
                        "<span data-group='" + groupName + "' rel='" + fieldID + "' type='button' class='dmcf-add-field pull-right btn btn-primary btn-xs'><i class='fa fa-plus'></i> Add New Field</span>" +
                        "<input type='hidden' name='groupnames["+fieldID+"]' value='" + groupName + "' /></h4>" +
                        "</div>" +
                        "<div id='dmcf-group" + fieldID + "' class='panel-collapse collapse in'>" +
                        "<div class='panel-body'>" +
                        "<div id ='panel-group-" + fieldID + "' class='panel-group'>" +
                        "</div>" +
                        "</div>" +
                        "<div class='panel-footer' style='background: #fafafa'>" +
                        "Template Tag: <input onfocus='this.select()' class='ttp' title='Use this tag in link or page template to show group data'  style='border: 0;width: 200px;text-align: center' type='text' value='[wpdm_acf-"+fieldID+"]' />" +
                        "</div>" +
                        "</div>" +
                        "</div>").appendTo('#custom-field-body');
            } else {
                $('#dmcf-message-label').text('');
                $('#dmcf-message-label').addClass('alert alert-danger');
                $('#dmcf-message-label').append('Error: insert group name');
            }

            /*
             $('body').on('click', '.dmcf-add-field', function(event) {
             event.preventDefault();
             var groupID = $(this).attr('rel');
             var groupName = $(this).attr('data-group');
             groupName = groupName.replace(/^[a-zA-Z]/,"");                
             var fieldID = new Date().getTime();
             $("<div class='panel panel-default'>" +
             "<div class='panel-heading'>" +
             "<h4 class ='panel-title'>" +
             "<a data-toggle ='collapse' data-parent = '#panel-group-" + groupID + "' href = '#dmcf-field" + fieldID + "' >" +
             "New Custom Field" +
             "</a>" +
             "<span type='button' class='dmcf-delete-field pull-right btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></span>" +
             "</h4>" +
             "</div>" +
             "<div id='dmcf-field" + fieldID + "' class='panel-collapse collapse in'>" +
             "<div id = 'panel-" + fieldID + "' class='panel-body'>" +
             "<label>Field Label</label>" +
             "<input type = 'text' name= 'field-label[" + groupName + "][]' class= 'form-control' style = 'width: 400px;' /><br>" +
             "<label>Field Name</label>" +
             "<input type = 'text' name= 'field-name[" + groupName + "][]' class= 'form-control' style = 'width: 400px;' /><br>" +
             "<label>Field Type</label>" +
             "<select id='field-state-" + fieldID + "' name= 'field-type[" + groupName + "][]' class = 'form-control' style = 'width: 400px;'>" +
             "<option value= 'text'>Text</option>" +
             "<option value= 'textarea'>TextArea</option>" +
             "<option value= 'number'>Number</option>" +
             "<option value= 'select'>Select</option>" +
             "<option value= 'checkbox'>Checkbox</option>" +
             "<option value= 'radiobutton'>Radiobutton</option>" +
             "</select><br>" +
             "<div id = 'field-choice-" + fieldID + "' class = 'hide'>" +
             "<label>Choices</label>" +
             "<textarea name = 'field-choices[" + groupName + "][]' rows = '6' placeholder = 'Enter one choice in every new line' class = 'form-control' style = 'width: 400px;'></textarea><br>" +
             "</div>" +
             "</div>" +
             "</div>" +
             "</div>").appendTo('#panel-group-' + groupID);
             $('#field-state-' + fieldID).on('change', function(e) {
             e.preventDefault();
             if ($(this).val() == "select" || $(this).val() == "checkbox" || $(this).val() == "radiobutton") {
             $('#field-choice-' + fieldID).removeClass("hide");
             }
             else {
             $('#field-choice-' + fieldID).addClass("hide");
             }
             return false;
             });
             return false;
             });
             */
            return false;
        });
        $('body').on('click', '.dmcf-add-field', function(event) {
            event.preventDefault();
            var groupID = $(this).attr('rel');
            var groupName = $(this).attr('data-group');
            groupName = groupName.replace(/[^A-Za-z0-9]+/, "");
            var fieldID = new Date().getTime();
//            $('#dmcf-group'+groupID).toggle();
            $("<div id='dmcf-new-field-" + fieldID + "' class='panel panel-default'>" +
                    "<div class='panel-heading'>" +
                    "<h4 class ='panel-title'>" +
                    "<a data-toggle ='collapse' data-parent = '#panel-group-" + groupID + "' href = '#dmcf-field" + fieldID + "' >" +
                    "New Custom Field" +
                    "</a>" +
                    "<span type='button' rel='" + fieldID + "' class='dmcf-delete-field pull-right btn btn-danger btn-xs'><i class='fa fa-trash-o'></i> Delete Field</span>" +
                    "</h4>" +
                    "</div>" +
                    "<div id='dmcf-field" + fieldID + "' class='panel-collapse collapse in'>" +
                    "<div id = 'panel-" + fieldID + "' class='panel-body'>" +
                    "<label>Field Label</label>" +
                    "<input type = 'text' name= 'field-label[" + groupName + "][]' class= 'form-control' style = 'width: 400px;' /><br>" +
                    "<label>Field Name</label>" +
                    "<input type = 'text' name= 'field-name[" + groupName + "][]' class= 'form-control' style = 'width: 400px;' /><br>" +
                    "<label>Field Type</label>" +
                    "<select id='field-state-" + fieldID + "' name= 'field-type[" + groupName + "][]' class = 'form-control' style = 'width: 400px;'>" +
                    "<option value= 'text'>Text</option>" +
                    "<option value= 'textarea'>TextArea</option>" +
                    "<option value= 'number'>Number</option>" +
                    "<option value= 'select'>Select</option>" +
                    "<option value= 'checkbox'>Checkbox</option>" +
                    "<option value= 'radiobutton'>Radiobutton</option>" +
                    "</select><br>" +
                    "<div id = 'field-choice-" + fieldID + "' class = 'hide'>" +
                    "<label>Choices</label>" +
                    "<textarea name = 'field-choices[" + groupName + "][]' rows = '6' placeholder = 'Enter one choice in every new line' class = 'form-control' style = 'width: 400px;'></textarea><br>" +
                    "</div>" +
                    "</div>" +
                    "</div>" +
                    "</div>").appendTo('#panel-group-' + groupID);
            $('#field-state-' + fieldID).on('change', function(e) {
                e.preventDefault();
                if ($(this).val() == "select" || $(this).val() == "checkbox" || $(this).val() == "radiobutton") {
                    $('#field-choice-' + fieldID).removeClass("hide");
                }
                else {
                    $('#field-choice-' + fieldID).addClass("hide");
                }
                return false;
            });
            return false;
        });

        $('.ttp').tooltip();
    });
</script>
