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

    <div class="panel panel-default">
        <div class="panel-heading">
            <b style="font-size: 12pt;line-height:28px"><i class="fa fa-code"></i> &nbsp; <?php echo __("Subscription Form Fields", "wpdmpro"); ?></b>

            <div style="clear: both"></div>
        </div>

            <div id="dmcf-message-label">

            </div>

                <div class="panel-body">
                <!-- input type="hidden" name="task" value="save_s2dcf_custom_field" / -->
                <!--<div class="form-control">-->
                <div id="custom-field-body" class="panel-group">
                    <?php
                    $s2dcf_fields_label = get_option('s2dcf_field_label');
                    $s2dcf_fields_name = get_option('s2dcf_field_name');
                    $s2dcf_fields_types = get_option('s2dcf_field_type');
                    $s2dcf_fields_choice = get_option('s2dcf_field_choice');
                    $s2dcf_group_names = get_option('s2dcf_group_names');
//                    delete_option('s2dcf_field_label');
//                    delete_option('s2dcf_field_name');
//                    delete_option('s2dcf_field_type');
//                    delete_option('s2dcf_field_choice');
//                    delete_option('s2dcf_group_names');
                    $total_array = count($s2dcf_fields_label);
                    $s2dcf_flag = 0;
                    $count_group = 0;

                    if (!empty($s2dcf_fields_label)) {
                        foreach ($s2dcf_fields_label as $key => $group) {
                            $s2dcf_flag = $s2dcf_flag + 1;
                            ?>
                            <div id='dmcf-new-group-<?php echo $key; ?>' class='panel panel-default panel-acf-group'>
                                <div class='panel-heading'> 
                                    <h4 class ='panel-title'> 
                                        <a data-toggle = 'collapse' data-parent = '#custom-field-body' href = '#dmcf-group<?php echo $s2dcf_flag; ?>' > 
                                            <?php echo $s2dcf_group_names[$key] ?>
                                        </a> 
                                        <span rel='<?php echo $key; ?>' type='button' style='margin:-6px -9px 0 5px;' class='dmcf-delete-group pull-right btn btn-danger btn-sm'><i class='fa fa-trash-o'></i> Delete Group</span>
                                        <span data-group='<?php echo $key; ?>' rel='<?php echo $s2dcf_flag; ?>' style="margin-top: -6px" type='button' class='dmcf-add-field pull-right btn btn-success btn-sm'><i class='fa fa-plus'></i> Add New Field</span>
                                        <input type='hidden' name='groupnames[<?php echo $key ;?>]' value='<?php echo $s2dcf_group_names[$key]; ?>' />
                                    </h4> 
                                </div> 
                                <div id='dmcf-group<?php echo $s2dcf_flag; ?>' class='panel-collapse collapse in'>
                                    <div class='panel-body'> 
                                        <div id ='panel-group-<?php echo $s2dcf_flag; ?>' class='panel-group'>
                                            <?php
                                            foreach ($group as $field_key => $field_value) { $fid = uniqid();
                                                ?>
                                                <div id='dmcf-new-field-<?php echo $key . $field_key ?>' class='panel panel-default'> 
                                                    <div class='panel-heading'> 
                                                        <h4 class ='panel-title'> 
                                                            <a class="collapsed" data-toggle = 'collapse' data-parent = '#panel-group-<?php echo $s2dcf_flag; ?>' href = '#<?php echo $key . $field_key; ?>' >
                                                                <?php echo $field_value; ?> 
                                                            </a> 
                                                            <span rel='<?php echo $key . $field_key ?>' type='button' class='dmcf-delete-field pull-right btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></span>
                                                        </h4> 
                                                    </div> 
                                                    <div id='<?php echo $key . $field_key ?>' class='panel-collapse collapse'>
                                                        <div id = 'panel-<?php echo $key . $field_key; ?>' class='panel-body'> 
                                                            <label>Field Label</label>
                                                            <input type = 'text' name= 'field-label[<?php echo $key; ?>][]' value="<?php echo isset($field_value) ? $field_value : '' ?>" class= 'form-control' style = 'width: 400px;' /><br> 
                                                            <label>Field Name</label>
                                                            <input type = 'text' onkeyup="jQuery('#tt<?php echo $fid; ?>').val('[wpdm_acf-<?php echo $key; ?>-'+this.value+']');" name= 'field-name[<?php echo $key; ?>][]' value="<?php echo isset($s2dcf_fields_name[$key][$field_key]) ? $s2dcf_fields_name[$key][$field_key] : '' ?>" class= 'form-control' style = 'width: 400px;' /><br>
                                                            <label>Field Type</label> 
                                                            <select data-identity="<?php echo $key . $field_key; ?>" id='field-state-<?php echo $key . $field_key; ?>' name= 'field-type[<?php echo $key; ?>][]' class = 's2dcf_field_select form-control' style = 'width: 400px;'> 
                                                                <option value= 'text' <?php echo (isset($s2dcf_fields_types[$key][$field_key]) && $s2dcf_fields_types[$key][$field_key] == 'text') ? 'selected=selected' : '' ?>>Text</option> 
                                                                <option value= 'textarea' <?php echo (isset($s2dcf_fields_types[$key][$field_key]) && $s2dcf_fields_types[$key][$field_key] == 'textarea') ? 'selected=selected' : '' ?>>TextArea</option> 
                                                                <option value= 'number' <?php echo (isset($s2dcf_fields_types[$key][$field_key]) && $s2dcf_fields_types[$key][$field_key] == 'number') ? 'selected=selected' : '' ?>>Number</option> 
                                                                <option value= 'select' <?php echo (isset($s2dcf_fields_types[$key][$field_key]) && $s2dcf_fields_types[$key][$field_key] == 'select') ? 'selected=selected' : '' ?>>Select</option> 
                                                                <option value= 'checkbox' <?php echo (isset($s2dcf_fields_types[$key][$field_key]) && $s2dcf_fields_types[$key][$field_key] == 'checkbox') ? 'selected=selected' : '' ?>>Checkbox</option> 
                                                                <option value= 'radiobutton' <?php echo (isset($s2dcf_fields_types[$key][$field_key]) && $s2dcf_fields_types[$key][$field_key] == 'radiobutton') ? 'selected=selected' : '' ?>>Radiobutton</option> 
                                                            </select><br> 
                                                            <div id = 'field-choice-<?php echo $key . $field_key; ?>' class="<?php
                                                            if ($s2dcf_fields_types[$key][$field_key] == 'select' || $s2dcf_fields_types[$key][$field_key] == 'checkbox' || $s2dcf_fields_types[$key][$field_key] == 'radiobutton') {
                                                                
                                                            } else {
                                                                echo 'hide';
                                                            }
                                                            ?>"> 
                                                                <label>Choices</label> 
                                                                <textarea name = 'field-choices[<?php echo $key; ?>][]' rows = '6' placeholder = 'Enter one choice in every new line' class = 'form-control' style = 'width: 400px;'><?php echo $s2dcf_fields_choice[$key][$field_key]; ?></textarea><br> 
                                                            </div> 
                                                        </div>

                                                    </div>

                                                </div> 
                                            <?php }
                                            ?>
                                        </div> 
                                    </div> 
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

        $('.s2dcf_field_select').on('click', function(event) {
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
                        "<span type='button' rel='" + fieldID + "' style='margin:-6px -9px 0 5px;' class='dmcf-delete-group pull-right btn btn-danger btn-sm'><i class='fa fa-trash-o'></i> Delete Group</span>" +
                        "<span data-group='" + groupName + "' rel='" + fieldID + "' style='margin-top: -6px' type='button' class='dmcf-add-field pull-right btn btn-success btn-sm'><i class='fa fa-plus'></i> Add New Field</span>" +
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
        $('.dmcf-add-field').unbind('click');
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
                    "<span type='button' rel='" + fieldID + "' class='dmcf-delete-field pull-right btn btn-danger btn-xs'><i class='fa fa-trash-o'></i></span>" +
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
