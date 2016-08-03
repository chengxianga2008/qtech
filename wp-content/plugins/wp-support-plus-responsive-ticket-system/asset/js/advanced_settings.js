jQuery(document).ready(function(){
	getAdvancedSettings();
	jQuery('#tab_advanced_container').click(function(){
		getAdvancedSettings();
	});
	jQuery('#tab_custom_status_container').click(function(){
		getCustomStatusSettings();
	});
	jQuery('#tab_fields_reorder_container').click(function(){
		getFieldReorderSettings();
	});
	jQuery('#tab_ticket_list_container').click(function(){
		getTicketListFieldSettings();
	});
	jQuery('#tab_custom_filter_container').click(function(){
		getCustomFilterFrontEnd();
	});
	jQuery('#tab_custom_priority_container').click(function(){
		getCustomPrioritySettings();
	});
        jQuery('#tab_ckeditor_settings').click(function(){
		getCKEditorSettings();
	});
        jQuery('#tab_export_to_excel_container').click(function(){
		getExportTicketToExcel();
	});
        jQuery('#tab_support_btn_container').click(function(){
		getSupportButton();
	});
        jQuery('#tab_woo_settings_container').click(function(){
		getWooSettings();
	});
});

function getAdvancedSettings(){
	jQuery('#settingsAdvanced .settingsAdvancedContainer').hide();
	jQuery('#settingsAdvanced .wait').show();
	
	var data = {
		'action': 'getAdvancedSettings'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsAdvanced .wait').hide();
		jQuery('#settingsAdvanced .settingsAdvancedContainer').html(response);
		jQuery('#settingsAdvanced .settingsAdvancedContainer').show(function(){
			jQuery('#guest_ticket_submission_message').ckeditor();
		});
	});
}

function setAdvancedSettings(){
    var guest_ticket_submission_message = jQuery('#guest_ticket_submission_message').val().trim();
    var pending_days=jQuery('#pendingTicketClose').val();
    var datecustfield=jQuery('#datecustfield option:selected').val();
    var active_tab=jQuery('#active_tab').val();
    var reply_above=jQuery('input[name=reply_above]:checked').val();
    var wpsp_reply_form_position =jQuery('input[name=wpsp_reply_form_position]:checked').val();
    var wpsp_shortcode_used_in =jQuery('input[name=wpsp_shortcode_used_in]:checked').val();
    var enable_accordion=jQuery('input[name=enable_accordion]:checked').val();
    var ticketId=jQuery('input[name=ticketId]:checked').val();
    var hide_selected_status_ticket=jQuery('#hide_selected_status_ticket option:selected').val();
    var logout_Settings=jQuery('input[name=logout_Settings]:checked').val();
    var admin_bar_Setting=jQuery('input[name=admin_bar_Setting]:checked').val();
    var hide_selected_status_ticket_backend = jQuery("input[name='hideSelectedStatusBackend\\[\\]']:checked")
        .map(function(){return jQuery(this).val();}).get();
    var modify_raised_by = jQuery("input[name='modifyRaisedBy\\[\\]']:checked")
        .map(function(){return jQuery(this).val();}).get();
    if (pending_days.trim()=='' || (!isNaN(pending_days) && parseInt(Number(pending_days)) == pending_days && !isNaN(parseInt(pending_days, 10))))
    {
        jQuery('#settingsAdvanced .settingsAdvancedContainer').hide();
        jQuery('#settingsAdvanced .wait').show();
        var allowSignUp=0;
        if(jQuery('#wpspAllowSignUp').is(':checked')){
                allowSignUp=1;
        }

        var ticket_alice=new Array();
        var aliceCounter=1;
        jQuery('[name=wpspTicketAlice]').each(function() {
            ticket_alice[aliceCounter]=jQuery(this).val();
            aliceCounter++;
        });

        var data = {
            'action': 'setAdvancedSettings',
            'guest_ticket_submission_message': guest_ticket_submission_message,
            'pending_ticket_close' : pending_days,
            'allowSignUp':allowSignUp,
            'defaultRole':jQuery('#wpspSignUpDefaultRole').val(),
            'ticket_label_alice':ticket_alice,
            'wpsp_reply_form_position': wpsp_reply_form_position,
            'wpsp_shortcode_used_in':wpsp_shortcode_used_in,
            'enable_accordion':enable_accordion,
            'hide_selected_status_ticket':hide_selected_status_ticket,
            'hide_selected_status_ticket_backend':hide_selected_status_ticket_backend,
            'modify_raised_by':modify_raised_by,
            'wpsp_dashboard_menu_label':jQuery('#dashboardMenuLabel').val(),
            'logout_Settings':logout_Settings,
            'admin_bar_Setting':admin_bar_Setting,
            'ticket_link_page':jQuery('#setTicketLinkPage').val(),
            'ticketId':ticketId,
            'wpsp_ticket_id_prefix':jQuery('#wpsp_ticket_id_prefix').val().trim(),
            'reply_above':reply_above,
            'datecustfield':datecustfield,
            'active_tab':active_tab
        };

        jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
            getAdvancedSettings();
        });
    }
    else
    {
        alert(display_ticket_data.insert_integer_value);
        jQuery('#pendingTicketClose').val('');
        jQuery('#pendingTicketClose').focus();
    }
}

function getCustomStatusSettings(){
	jQuery('#settingsCustomStatus .wait').show();
	jQuery('#settingsCustomStatus .settingsCustomStatusContainer').hide();
	
	var data = {
		'action': 'getCustomStatusSettings'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsCustomStatus .wait').hide();
		jQuery('#settingsCustomStatus .settingsCustomStatusContainer').html(response);
		jQuery('#settingsCustomStatus .settingsCustomStatusContainer').show();
		/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
		 * Update 1 - Change Custom Status Color
		 * Initialise color picker
		 */
		jQuery('.wp-support-plus-color-picker').wpColorPicker();
		/* END CLOUGH I.T. SOLUTIONS MODIFICATION
		 */
	});
}

function delete_custom_status(id){
	if(confirm(display_ticket_data.sure+display_ticket_data.custom_status_warning)){
		jQuery('#settingsCustomStatus .wait').show();
		jQuery('#settingsCustomStatus .settingsCustomStatusContainer').hide();
		
		var data = {
			'action': 'deleteCustomStatus',
			'id': id
		};

		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			getCustomStatusSettings();
		});
	}
}

function getFieldReorderSettings(){
	jQuery('#settingsFieldsReorder .wait').show();
	jQuery('#settingsFieldsReorder .settingsCustomStatusContainer').hide();
	
	var data = {
		'action': 'getFieldsReorderSettings'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsFieldsReorder .wait').hide();
		jQuery('#settingsFieldsReorder .settingsFieldsReorderContainer').html(response);
		jQuery('#settingsFieldsReorder .settingsFieldsReorderContainer').show();
	});
}

function setFieldReorderSettings(){
	jQuery('#settingsFieldsReorder .wait').show();
	jQuery('#settingsFieldsReorder .settingsFieldsReorderContainer').hide();
	var Adata=new Array();
	var Adata_display=new Array();
	jQuery('#field_order_table tbody tr').each(function(){
		var counter=1;var field_name="";var field_id="";
		jQuery(this).find('td').each (function() {   
                	var html_data =  jQuery(this).html();
			if(counter==3)
			{
				field_name="field_status_"+html_data;
				field_id=html_data;
				Adata.push(html_data);
			}
			if(counter==4)
			{
				if(jQuery('input:radio[name='+field_name+']:checked').val()==1){
					Adata_display.push(field_id);
				}
			}
			counter++;
            	});
	});
	
	var data = {
		'action': 'setFieldsReorderSettings',
		'data': Adata,
		'display_data': Adata_display,
		'name_label':jQuery('#wpsp_default_name_label').val(),
		'email_label':jQuery('#wpsp_default_email_label').val(),
		'subject_label':jQuery('#wpsp_default_subject_label').val(),
		'description_label':jQuery('#wpsp_default_description_label').val(),
		'category_label':jQuery('#wpsp_default_category_label').val(),
		'priority_label':jQuery('#wpsp_default_priority_label').val(),
		'attachment_label':jQuery('#wpsp_default_attachment_label').val(),
                'wpsp_default_value_of_subject':jQuery('#wpsp_default_subject_value').val()
	};
	
	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		getFieldReorderSettings();
	});
}

function getTicketListFieldSettings(){
	jQuery('#settingsTicketListFields .wait').show();
	jQuery('#settingsTicketListFields .settingsTicketListFieldsContainer').hide();
	
	var data = {
		'action': 'getTicketListFieldSettings'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsTicketListFields .wait').hide();
		jQuery('#settingsTicketListFields .settingsTicketListFieldsContainer').html(response);
		jQuery('#settingsTicketListFields .settingsTicketListFieldsContainer').show();
	});
}

function setTicketListFieldSettings(){
	jQuery('#settingsTicketListFields .wait').show();
	jQuery('#settingsTicketListFields .settingsTicketListFieldsContainer').hide();

	var Adata_frontend=new Array();
	var Adata_display_frontend=new Array();
	jQuery('#frontend_field_list_table tbody tr').each(function(){
		var counter=1;var field_name="";var field_id="";
		jQuery(this).find('td').each (function() {   
                	var html_data_frontend =  jQuery(this).html();
			if(counter==3)
			{
				field_name="frontend_field_"+html_data_frontend;
				field_id=html_data_frontend;
				Adata_frontend.push(html_data_frontend);
			}
			if(counter==4)
			{
				Adata_display_frontend.push(jQuery('input:radio[name='+field_name+']:checked').val());
			}
			counter++;
            	});
	});

	var Adata_backend=new Array();
	var Adata_display_backend=new Array();
	jQuery('#backend_field_list_table tbody tr').each(function(){
		var counter=1;var field_name="";var field_id="";
		jQuery(this).find('td').each (function() {   
                	var html_data_backend =  jQuery(this).html();
			if(counter==3)
			{
				field_name="backend_field_"+html_data_backend;
				field_id=html_data_backend;
				Adata_backend.push(html_data_backend);
			}
			if(counter==4)
			{
				Adata_display_backend.push(jQuery('input:radio[name='+field_name+']:checked').val());
			}
			counter++;
            	});
	});

	

	var data = {
		'action': 'setTicketListFieldSettings',
		'backend_data': Adata_backend,
		'backend_display_data': Adata_display_backend,
		'frontend_data': Adata_frontend,
		'frontend_display_data': Adata_display_frontend
	};
	
	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		getTicketListFieldSettings();
        });
}

function getCustomFilterFrontEnd(){
	jQuery('#settingsCustomFilterFrontEnd .wait').show();
	jQuery('#settingsCustomFilterFrontEnd .settingsCustomFilterFrontEndContainer').hide();
	
	var data = {
		'action': 'getCustomFilterFrontEnd'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsCustomFilterFrontEnd .wait').hide();
		jQuery('#settingsCustomFilterFrontEnd .settingsCustomFilterFrontEndContainer').html(response);
		jQuery('#settingsCustomFilterFrontEnd .settingsCustomFilterFrontEndContainer').show();
	});
}

function setCustomFilterFrontEnd(){
	jQuery('#settingsCustomFilterFrontEnd .wait').show();
	jQuery('#settingsCustomFilterFrontEnd .settingsCustomFilterFrontEndContainer').hide();

	var logged_in=new Array();
	var agents=new Array();
	var supervisors=new Array();
	jQuery('#custom_filter_front_end tbody tr').each(function(){
		var counter=1;var field_name="";var field_id="";
		jQuery(this).find('td').each (function() {   
                	var html_data =  jQuery(this).html();
			if(counter==3)
			{
				field_name=html_data;
			}
			if(counter==4)
			{
				if(jQuery('#logged_in_'+field_name).attr('checked')=='checked'){
					logged_in.push(field_name);
				}
				
			}
			if(counter==5)
			{
				if(jQuery('#agents_'+field_name).attr('checked')=='checked'){
					agents.push(field_name);
				}
				
			}
			if(counter==6)
			{
				if(jQuery('#supervisors_'+field_name).attr('checked')=='checked'){
					supervisors.push(field_name);
				}
				
			}
			counter++;
        });
	});
	
	var data = {
		'action': 'setCustomFilterFrontEnd',
		'logged_in': logged_in,
		'agents': agents,
		'supervisors': supervisors
	};
	
	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		getCustomFilterFrontEnd();
	});
}

function create_custom_status(){
	if(jQuery('#custom_status_text').val().trim()==''){
		alert(display_ticket_data.insert_menu_text);
		jQuery('#custom_status_text').focus();
		return;
	}

	jQuery('#settingsCustomStatus .wait').show();
	jQuery('#settingsCustomStatus .settingsCustomStatusContainer').hide();	
	/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
	 * Update 1 - Change Custom Status Color
	 * Add custom_status_color value to post data
	 */
	var data = {
		'action': 'addCustomStatus',
		'custom_status_text': jQuery('#custom_status_text').val().trim(),
		'custom_status_color': jQuery('#custom_status_color').val().trim()
	};
	/*var data = {
		'action': 'addCustomStatus',
		'custom_status_text': jQuery('#custom_status_text').val().trim()
	};*/
	/* END CLOUGH I.T. SOLUTIONS MODIFICATION
	 */

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		getCustomStatusSettings();
	});
}

/* BEGIN CLOUGH I.T. SOLUTIONS MODIFICATION
 * Update 1 - Change Custom Status Color
 * Initialise color picker
 */
function save_custom_status_color( theID ) {
	var color = jQuery( '#custom_status_color_' + theID).val();

	var data = {
		'action': 'setCustomStatusColor',
		'custom_status_id': theID,
		'custom_status_color': color
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#custom-status-color-saved-' + theID).show().delay(5000).fadeOut();
	});
}
/* END CLOUGH I.T. SOLUTIONS MODIFICATION
 */

function getCustomPrioritySettings(){
	jQuery('#settingsCustomPriority .wait').show();
	jQuery('#settingsCustomPriority .settingsCustomPriorityContainer').hide();
	
	var data = {
		'action': 'getCustomPrioritySettings'
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsCustomPriority .wait').hide();
		jQuery('#settingsCustomPriority .settingsCustomPriorityContainer').html(response);
		jQuery('#settingsCustomPriority .settingsCustomPriorityContainer').show();

		jQuery('.wp-support-plus-color-picker').wpColorPicker();
	});
}

function create_custom_priority(){
	if(jQuery('#custom_priority_text').val().trim()==''){
		alert(display_ticket_data.insert_menu_text);
		jQuery('#custom_priority_text').focus();
		return;
	}

	jQuery('#settingsCustomPriority .wait').show();
	jQuery('#settingsCustomPriority .settingsCustomPriorityContainer').hide();	
	var data = {
		'action': 'addCustomPriority',
		'custom_priority_text': jQuery('#custom_priority_text').val().trim(),
		'custom_priority_color': jQuery('#custom_priority_color').val().trim()
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		getCustomPrioritySettings();
	});
}

function save_custom_priority_color( theID ) {
	var color = jQuery( '#custom_priority_color_' + theID).val();

	var data = {
		'action': 'setCustomPriorityColor',
		'custom_priority_id': theID,
		'custom_priority_color': color
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#custom-priority-color-saved-' + theID).show().delay(5000).fadeOut();
	});
}

function delete_custom_priority(id){
	if(confirm(display_ticket_data.sure+display_ticket_data.custom_priority_warning)){
		jQuery('#settingsCustomPriority .wait').show();
		jQuery('#settingsCustomPriority .settingsCustomPriorityContainer').hide();
		
		var data = {
			'action': 'deleteCustomPriority',
			'id': id
		};

		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			getCustomPrioritySettings();
		});
	}
}

function setSubCharLength(){
	var front_end_length=jQuery('#wpsp_frontend_sub_char_length').val().trim();
	var back_end_length=jQuery('#wpsp_backend_sub_char_length').val().trim();
	var data = {
		'action': 'setSubCharLength',
		'front_end_length': front_end_length,
		'back_end_length':back_end_length
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('.setSubCharLengthCSS').show().delay(3000).fadeOut();
	});
}

function setCustomStatusOrder(){
	jQuery('#settingsCustomStatus .wait').show();
	jQuery('#settingsCustomStatus .settingsCustomStatusContainer').hide();

	var Adata=new Array();
	jQuery('#custom_status_order_table tbody tr').each(function(){
		var counter=1;var status_name="";var status_id="";
		jQuery(this).find('td').each (function() {   
                	var html_data =  jQuery(this).html();
			if(counter==2)
			{
				Adata.push(html_data);
			}
			counter++;
            	});
	});

	var data = {
		'action': 'setCustomStatusOrder',
		'status_order': Adata
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		getCustomStatusSettings();
	});
}

function setCustomPriorityOrder(){
	jQuery('#settingsCustomPriority .wait').show();
	jQuery('#settingsCustomPriority .settingsCustomPriorityContainer').hide();

	var Adata=new Array();
	jQuery('#custom_priority_order_table tbody tr').each(function(){
		var counter=1;var status_name="";var status_id="";
		jQuery(this).find('td').each (function() {   
                	var html_data =  jQuery(this).html();
			if(counter==2)
			{
				Adata.push(html_data);
			}
			counter++;
            	});
	});

	var data = {
		'action': 'setCustomPriorityOrder',
		'priority_order': Adata
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		getCustomPrioritySettings();
	});
}

function setDateFormat(){
	var cdt_date_format=jQuery('#wpsp_backend_cdt_format').val().trim();
	var udt_date_format=jQuery('#wpsp_backend_udt_format').val().trim();
	var cdt_date_format_front=jQuery('#wpsp_frontend_cdt_format').val().trim();
	var udt_date_format_front=jQuery('#wpsp_frontend_udt_format').val().trim();
	var data = {
		'action': 'setDateFormat',
		'cdt_date_format': cdt_date_format,
		'udt_date_format': udt_date_format,
		'cdt_date_format_front': cdt_date_format_front,
		'udt_date_format_front': udt_date_format_front
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('.setDateFormatCSS').show().delay(3000).fadeOut();
	});
}

function editStatus(id,name,is_default){
	jQuery('#editCustomStatus').show();
	jQuery('#editCustomStatusID').val(id);
	jQuery('#editCustomStatusName').val(name);
	jQuery('#editCustomStatusDefault').val(is_default);
	
	window.location.href='#editCustomStatus';
	jQuery('#editCustomStatusName').focus();
}

function updateCustomStatus(){
	if(jQuery('#editCustomStatusName').val().trim()!=''){
		jQuery('#settingsCustomStatus .wait').show();
		jQuery('#settingsCustomStatus .settingsCustomStatusContainer').hide();

		var data = {
			'action': 'updateCustomStatus',
			'status_id': jQuery('#editCustomStatusID').val(),
			'name':jQuery('#editCustomStatusName').val(),
			'is_default':jQuery('#editCustomStatusDefault').val()
		};
		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			getCustomStatusSettings();
		});
	}
	else{
		alert(display_ticket_data.insert_field_label);
		jQuery('#editCustomStatusName').val('');
		jQuery('#editCustomStatusName').focus();
	}
}

function editPriority(id,name,is_default){
	jQuery('#editCustomPriority').show();
	jQuery('#editCustomPriorityID').val(id);
	jQuery('#editCustomPriorityName').val(name);
	jQuery('#editCustomPriorityDefault').val(is_default);
	
	window.location.href='#editCustomPriority';
	jQuery('#editCustomPriorityName').focus();
}

function updateCustomPriority(){
	if(jQuery('#editCustomPriorityName').val().trim()!=''){
		jQuery('#settingsCustomPriority .wait').show();
		jQuery('#settingsCustomPriority .settingsCustomPriorityContainer').hide();

		var data = {
			'action': 'updateCustomPriority',
			'priority_id': jQuery('#editCustomPriorityID').val(),
			'name':jQuery('#editCustomPriorityName').val(),
			'is_default':jQuery('#editCustomPriorityDefault').val()
		};
		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			getCustomPrioritySettings();
		});
	}
	else{
		alert(display_ticket_data.insert_field_label);
		jQuery('#editCustomPriorityName').val('');
		jQuery('#editCustomPriorityName').focus();
	}
}

function getCKEditorSettings(){
    jQuery('#settingsCKEditor .wait').show();
    jQuery('#settingsCKEditor .settingsCKEditorContainer').hide();

    var data = {
        'action': 'getCKEditorSettings'
    };

    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
        jQuery('#settingsCKEditor .wait').hide();
        jQuery('#settingsCKEditor .settingsCKEditorContainer').html(response);
        jQuery('#settingsCKEditor .settingsCKEditorContainer').show();
    });
}

function setCKEditorSettings(){
    jQuery('#settingsCKEditor .wait').show();
    jQuery('#settingsCKEditor .settingsCKEditorContainer').hide();

    var ck_for_guest='0';
    if(jQuery('#ckeditor_enable_guest').is(':checked')){
        ck_for_guest='1';
    }
    var ck_for_login_user='0';
    if(jQuery('#ckeditor_enable_login_user').is(':checked')){
        ck_for_login_user='1';
    }
    
    var data = {
        'action': 'setCKEditorSettings',
        'guestUserFront': ck_for_guest,
        'loginUserFront': ck_for_login_user
    };

    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
        getCKEditorSettings()
    });
}

function getExportTicketToExcel(){
    jQuery('#settingsExportToExcel .wait').show();
	jQuery('#settingsExportToExcel .settingsExportToExcelContainer').hide();
	
	var data = {
		'action': 'getExportTicketToExcel'
	};
        
	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsExportToExcel .wait').hide();
		jQuery('#settingsExportToExcel .settingsExportToExcelContainer').html(response);
		jQuery('#settingsExportToExcel .settingsExportToExcelContainer').show();
		
	});
}

function setExportTicketToExcel(){
    if(jQuery('#from_export').val().trim()!='' && jQuery('#to_export').val().trim()!=''){
        jQuery('#settingsExportToExcel .wait').show();
        jQuery('#settingsExportToExcel .settingsExportToExcelContainer').hide();

        var from_date=jQuery('#from_export').val();
        var to_date=jQuery('#to_export').val();
        var data = {
            'action': 'setExportTicketToExcel',
            'from_date':from_date,
            'to_date':to_date
        };
        jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
            var obj = jQuery.parseJSON( response );                 
            window.open(obj.url_to_export,'_blank');
            getExportTicketToExcel();
        });
    } else {
        alert(display_ticket_data.export_date_missing);
    }
}
function getSupportButton(){
    jQuery('#settingsSupportButton .wait').show();
	jQuery('#settingsSupportButton .settingsSupportButtonContainer').hide();
	
	var data = {
		'action': 'getSupportButton'
	};
        
	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#settingsSupportButton .wait').hide();
		jQuery('#settingsSupportButton .settingsSupportButtonContainer').html(response);
		jQuery('#settingsSupportButton .settingsSupportButtonContainer').show();
		
	});
}
function wpsp_image_upload(){
    var dataform=new FormData(jQuery('#wpsp_upload_icons')[0]);
    dataform.append("action","wpsp_image_upload");
   
    if(jQuery('#wpsp_fileToUpload_first').val().trim()!='' || jQuery('#wpsp_fileToUpload_second').val().trim()!='' || jQuery('#wpsp_fileToUpload_thried').val().trim()!=''){
        jQuery('#settingsSupportButton .wait').show();
        jQuery('#settingsSupportButton .settingsSupportButtonContainer').hide();
     
        jQuery.ajax( {
            url: display_ticket_data.wpsp_ajax_url,
            type: 'POST',
            data: dataform,
            processData: false,
            contentType: false
        }) 
        .done(function( msg ) {
            getSupportButton();
        });
    } else {
        alert(display_ticket_data.select_image);
    }
       
}

function getWooSettings(){
    jQuery('#settingsWooCommerce .wait').show();
    jQuery('#settingsWooCommerce .settingsWooCommerceContainer').hide();

    var data = {
            'action': 'getWooSettings'
    };

    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
            jQuery('#settingsWooCommerce .wait').hide();
            jQuery('#settingsWooCommerce .settingsWooCommerceContainer').html(response);
            jQuery('#settingsWooCommerce .settingsWooCommerceContainer').show(function(){
                jQuery('#wpsp_woo_submission_message').ckeditor();
            });
    });
}

function setWooSettings(){
    jQuery('#settingsWooCommerce .wait').show();
    jQuery('#settingsWooCommerce .settingsWooCommerceContainer').hide();
    
    var wpsp_woo_extension =jQuery('input[name=wpsp_woo_extension]:checked').val();
    var wpsp_prod_help =jQuery('input[name=wpsp_prod_help]:checked').val();
    var wpsp_prod_btn_label =jQuery('input[name=wpsp_prod_btn_label]').val();
    var wpsp_order_help =jQuery('input[name=wpsp_order_help]:checked').val();
    var wpsp_order_btn_label =jQuery('input[name=wpsp_order_btn_label]').val();
    var wpsp_woo_submission_message =jQuery('#wpsp_woo_submission_message').val().trim();
    
    var data = {
        'action': 'setWooSettings',
        'wpsp_woo_extension':wpsp_woo_extension,
        'wpsp_prod_help':wpsp_prod_help,
        'wpsp_prod_btn_label':wpsp_prod_btn_label,
        'wpsp_order_help':wpsp_order_help,
        'wpsp_order_btn_label':wpsp_order_btn_label,
        'wpsp_woo_submission_message':wpsp_woo_submission_message
    };

    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
        getWooSettings();
    });
}