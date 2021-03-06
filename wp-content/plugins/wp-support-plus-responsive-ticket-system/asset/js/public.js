var page_no=0;
var page_no_faq=0;
var wpsp_page_load=true;

jQuery(document).ready(function(){
	
        if(display_ticket_data.user_logged_in=='1'){
            if(display_ticket_data.display_tab=='1'){
                wpsp_getAllTickets();       
            }else if(display_ticket_data.display_tab=='2'){          
                wpsp_getCreateTicket();             
            }else{        
                wpsp_getFAQ();       
            }
        }
	
        jQuery( '#frmCreateNewTicketGeuest' ).unbind('submit');
        
	jQuery("#filter_by_faq_category_front").change(function(){
		page_no_faq=0;
		getFaqs();
	});
	
	jQuery('#filter_by_faq_search_front').keyup(function(){
		page_no_faq=0;
		getFaqs();
	});

	jQuery( '#wpspFrontendTicketFilter' ).submit( function( e ) {
		page_no=0;
		jQuery('.wpspActionFrontBody').hide();
		getTickets();
		e.preventDefault();
	});
	
	jQuery( '#frmCreateNewTicketGeuest' ).submit( function( e ) {
		    e.preventDefault();
		    
		    if(validateTicketSubmitGuest()){
				
                            jQuery('#create_ticket_container').hide();
                            jQuery('#create_ticket .wait').show();

                            var dataform=new FormData( this );
                            
                            if( (display_ticket_data.user_logged_in=='1' && display_ticket_data.ckeditor_enable_for_loggedin=='1') || (display_ticket_data.user_logged_in=='0' && display_ticket_data.ckeditor_enable_for_guest=='1') ){
                                dataform.append("create_ticket_body", CKEDITOR.instances['create_ticket_body_guest'].getData().trim());
                                dataform.append("ckeditor_enabled","1");
                            } else {
                                dataform.append("ckeditor_enabled","0");
                            }
                            
                            jQuery.ajax( {
			      url: display_ticket_data.wpsp_ajax_url,
			      type: 'POST',
			      data: dataform,
			      processData: false,
			      contentType: false
			    }) 
			    .done(function( msg ) {
			    	if(msg=='1'){
			    		jQuery('#create_ticket .wait').hide();
			    		jQuery('#create_ticket_container').show();
			    		jQuery('#create_ticket_container').html(display_ticket_data.shortly_get_back);
			    	}
			    	else{
			    		jQuery('#create_ticket_container').html(msg);
			    		jQuery('#create_ticket .wait').hide();
			    		jQuery('#create_ticket_container').show();
			    	}
			    });
			}
			
			e.preventDefault();
	});
});

function wpsp_getGuestTicketForm(){
	/*********************divi themecompatibility*********************/
	jQuery('#tab_ticket_container').parent().addClass('active');
	jQuery('#tab_faq').parent().removeClass('active');
	jQuery('#ticketContainer').addClass('active');
	jQuery('#FAQ_TAB').removeClass('active');
	/*********************divi themecompatibility*********************/
}

function wpsp_getGuestFAQ(){
	/*********************divi themecompatibility*********************/
	jQuery('#tab_ticket_container').parent().removeClass('active');
	jQuery('#tab_faq').parent().addClass('active');
	jQuery('#ticketContainer').removeClass('active');
	jQuery('#FAQ_TAB').addClass('active');
	/*********************divi themecompatibility*********************/
	
	jQuery('.faq_filter').show();
	jQuery('#faq_container').hide();
	jQuery('#FAQ_TAB .wait').show();
	jQuery('#filter_by_faq_category_front').val('all');
	jQuery('#filter_by_faq_search_front').val('');
	page_no_faq=0;
	var data = {
		'action': 'getFrontEndFAQ',
		'page_no': page_no_faq
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#FAQ_TAB .wait').hide();
		jQuery('#faq_container').html(response);
		jQuery('#faq_container').show();
	});
}

function wpsp_getAllTickets(){
	page_no=0;
	
	/*********************divi themecompatibility*********************/
	jQuery('#tab_create_ticket').parent().removeClass('active');
	jQuery('#tab_faq').parent().removeClass('active');
	jQuery('#tab_ticket_container').parent().addClass('active');
	jQuery('#create_ticket').removeClass('active');
	jQuery('#FAQ_TAB').removeClass('active');
	jQuery('#ticketContainer').addClass('active');
	/*********************divi themecompatibility*********************/
	
	if(display_ticket_data.user_logged_in==1){
		page_no=0;
		getTickets();
	}
}

function wpsp_getCreateTicket(){
	/*********************divi themecompatibility*********************/
	jQuery('#tab_ticket_container').parent().removeClass('active');
	jQuery('#tab_faq').parent().removeClass('active');
	jQuery('#tab_create_ticket').parent().addClass('active');
	jQuery('#ticketContainer').removeClass('active');
	jQuery('#FAQ_TAB').removeClass('active');
	jQuery('#create_ticket').addClass('active');
	/*********************divi themecompatibility*********************/
	
	jQuery('#create_ticket_container').hide();
	jQuery('#create_ticket .wait').show();
	var data = {
		'action': 'getCreateTicketForm',
		'backend': 0
	};
	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#create_ticket_container').html(response);
		jQuery('#create_ticket .wait').hide();
		jQuery('#create_ticket_container').show();
                
                if(!wpsp_page_load){
                    var body = jQuery("html, body");
                    var p = jQuery( ".support_bs" );
                    var position = p.position();
                    body.animate({scrollTop:position.top}, '500', 'swing', function() {});
                } else {
                    wpsp_page_load=false;
                }
                		
                if( (display_ticket_data.user_logged_in=='1' && display_ticket_data.ckeditor_enable_for_loggedin=='1') || (display_ticket_data.user_logged_in=='0' && display_ticket_data.ckeditor_enable_for_guest=='1') ){
                    CKEDITOR.replace(document.getElementById('create_ticket_body'));
                }
		
		jQuery( '#frmCreateNewTicket' ).unbind('submit');
		jQuery( '#frmCreateNewTicket' ).submit( function( e ) {
			if(validateTicketSubmit()){
				jQuery('#create_ticket_container').hide();
				jQuery('#create_ticket .wait').show();
				
				var dataform=new FormData( this );
                                if( (display_ticket_data.user_logged_in=='1' && display_ticket_data.ckeditor_enable_for_loggedin=='1') || (display_ticket_data.user_logged_in=='0' && display_ticket_data.ckeditor_enable_for_guest=='1') ){
                                    dataform.append("create_ticket_body", CKEDITOR.instances['create_ticket_body'].getData().trim());
                                    dataform.append("ckeditor_enabled","1");
                                } else {
                                    dataform.append("ckeditor_enabled","0");
                                }
				jQuery.ajax( {
                                    url: display_ticket_data.wpsp_ajax_url,
                                    type: 'POST',
                                    data: dataform,
                                    processData: false,
                                    contentType: false
                                }) 
                                .done(function( msg ) {
                                    if(msg==1){
                                        jQuery('#tab_ticket_container')[0].click();
                                    }
                                    else{
                                        jQuery('#create_ticket_container').html(msg);
                                        jQuery('#create_ticket .wait').hide();
                                        jQuery('#create_ticket_container').show();
                                    }
                                });
			}
			e.preventDefault();
		});
                
                jQuery(window).unbind('beforeunload');
                jQuery(window).on('beforeunload', function(){
                    if( (display_ticket_data.user_logged_in=='1' && display_ticket_data.ckeditor_enable_for_loggedin=='1') || (display_ticket_data.user_logged_in=='0' && display_ticket_data.ckeditor_enable_for_guest=='1') ){
                        if(CKEDITOR.instances['create_ticket_body'].getData().trim()!=''){
                            return 'Are you sure you want to leave?';
                        }
                    } else {
                        if(jQuery('#create_ticket_body').val().trim()!=''){
                            return 'Are you sure you want to leave?';
                        }
                    }
                });
                
	});
}

function wpsp_getFAQ(){
	/*********************divi themecompatibility*********************/
	jQuery('#tab_ticket_container').parent().removeClass('active');
	jQuery('#tab_create_ticket').parent().removeClass('active');
	jQuery('#tab_faq').parent().addClass('active');
	jQuery('#ticketContainer').removeClass('active');
	jQuery('#create_ticket').removeClass('active');
	jQuery('#FAQ_TAB').addClass('active');
	/*********************divi themecompatibility*********************/
	
	jQuery('.faq_filter').show();
	jQuery('#faq_container').hide();
	jQuery('#FAQ_TAB .wait').show();
	jQuery('#filter_by_faq_category_front').val('all');
	jQuery('#filter_by_faq_search_front').val('');
	page_no_faq=0;
	var data = {
            'action': 'getFrontEndFAQ',
            'page_no': page_no_faq
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
            jQuery('#FAQ_TAB .wait').hide();
            jQuery('#faq_container').html(response);
            jQuery('#faq_container').show();
            if(!wpsp_page_load){
                var body = jQuery("html, body");
                var p = jQuery( ".support_bs" );
                var position = p.position();
                body.animate({scrollTop:position.top}, '500', 'swing', function() {});
            } else {
                wpsp_page_load=false;
            }
            jQuery(window).unbind('beforeunload');
        });
}

function replyTicket(e,obj){
    if(validateReplyTicketSubmit()){
        jQuery('#ticketContainer .ticket_indivisual').hide();
        jQuery('#ticketContainer .wait').show();

        var dataform=new FormData( obj );
        
        if( (display_ticket_data.user_logged_in=='1' && display_ticket_data.ckeditor_enable_for_loggedin=='1') || (display_ticket_data.user_logged_in=='0' && display_ticket_data.ckeditor_enable_for_guest=='1') ){
            dataform.append("replyBody", CKEDITOR.instances['replyBody'].getData().trim());
            dataform.append("ckeditor_enabled","1");
        } else {
            dataform.append("ckeditor_enabled","0");
        }

        jQuery.ajax( {
            url: display_ticket_data.wpsp_ajax_url,
            type: 'POST',
            data: dataform,
            processData: false,
            contentType: false
        }) 
        .done(function( msg ) {
            if(msg==1){
                getTickets();
            }
            else{
                jQuery('#ticketContainer .ticket_indivisual').html(msg);
                jQuery('#ticketContainer .wait').hide();
                jQuery('#ticketContainer .ticket_indivisual').show();
            }
        });
    }
    e.preventDefault();
}

function addNote() {
    jQuery("input[name='notify']").val('false');
    jQuery("#frmThreadReply").submit();
}

function getTickets(){
    jQuery('#ticketContainer .ticket_list,#ticketContainer .ticket_indivisual,#ticketContainer .ticket_assignment').hide();
    jQuery('#ticketActionFront,#ticketContainer .wait').show();

    var dataform=new FormData(jQuery('#wpspFrontendTicketFilter')[0]);
    dataform.append("page_no", page_no);
    dataform.append("action", 'getFrontEndTickets');

    jQuery.ajax( {
        url: display_ticket_data.wpsp_ajax_url,
        type: 'POST',
        data: dataform,
        processData: false,
        contentType: false
    }) 
    .done(function( response ) {
        jQuery('#ticketContainer .wait').hide();
        jQuery('#ticketContainer .ticket_list').html(response);
        jQuery('#ticketContainer .ticket_list').show();
        wpspCheckBulkActionVisibility();
        jQuery(window).unbind('beforeunload');
        if(!wpsp_page_load){
            var body = jQuery("html, body");
            var p = jQuery( ".support_bs" );
            var position = p.position();
            body.animate({scrollTop:position.top}, '500', 'swing', function() {});
        } else {
            wpsp_page_load=false;
        }
    });
}

function openTicket(ticket_id){
    jQuery('#ticketContainer .wpspActionFrontBody,#ticketActionFront,#ticketContainer .ticket_list,#ticketContainer .ticket_indivisual').hide();
    jQuery('#ticketContainer .wait').show();

    var data = {
        'action': 'openTicketFront',
        'ticket_id': ticket_id
    };

    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
        jQuery('#ticketContainer .wait').hide();
        jQuery('#ticketContainer .ticket_indivisual').html(response);
        jQuery('#ticketContainer .ticket_indivisual').show();

        if( (display_ticket_data.user_logged_in=='1' && display_ticket_data.ckeditor_enable_for_loggedin=='1') || (display_ticket_data.user_logged_in=='0' && display_ticket_data.ckeditor_enable_for_guest=='1') ){
            CKEDITOR.replace(document.getElementById('replyBody'));
        }
        
        jQuery(window).unbind('beforeunload');
        jQuery(window).on('beforeunload', function(){
            if( (display_ticket_data.user_logged_in=='1' && display_ticket_data.ckeditor_enable_for_loggedin=='1') || (display_ticket_data.user_logged_in=='0' && display_ticket_data.ckeditor_enable_for_guest=='1') ){
                if(CKEDITOR.instances['replyBody'].getData().trim()!=''){
                    return 'Are you sure you want to leave?';
                }
            } else {
                if(jQuery('#replyBody').val().trim()!=''){
                    return 'Are you sure you want to leave?';
                }
            }
        });

        var activeAcc=0;
        if(display_ticket_data.reply_ticket_position==0){
            activeAcc=jQuery("#threadAccordion h3").length-1;
        }
        if(display_ticket_data.enable_accordion==1){
            jQuery("#threadAccordion").accordion({
                heightStyle:'content',
                active:activeAcc
            });
        }
        var body = jQuery("html, body");
        var p = jQuery( ".support_bs" );
        var position = p.position();
        body.animate({scrollTop:position.top}, '500', 'swing', function() {});
    });
}

function load_prev_page(prev_page_no){
	if(prev_page_no!=0){
		page_no=prev_page_no-1;
		getTickets();
	}
}

function load_next_page(next_page_no){
	if(next_page_no!=page_no){
		page_no=next_page_no;
		getTickets();
	}
}

function validateTicketSubmit(){
	var flag=true;
	jQuery.each(jQuery( '#frmCreateNewTicket').find('.wpsp_required'),function(){
	    if(jQuery(this).val().trim()==''){
                alert(display_ticket_data.insert_all_required);
                jQuery(this).focus();
                flag=false;
                return false;
            }
            if(jQuery(this).attr('type')=='checkbox'){
                var check_attr=jQuery(this).attr('name');
                if(jQuery('input[name="'+check_attr+'"]:checked').length<1)
                {
                    alert(display_ticket_data.insert_all_required);
                    flag=false;
                    return false;
                }
            }    
        });
        
        if(jQuery('#create_ticket_category').length && jQuery('#create_ticket_category').val() === ''){
            alert(display_ticket_data.insert_all_required); 
            flag=false;
            return false;
        }
        
        if(jQuery('#create_ticket_priority').length && jQuery('#create_ticket_priority').val() === ''){
            alert(display_ticket_data.insert_all_required); 
            flag=false;
            return false;
        }
        
        if(!flag){return false;}
        
        if( (display_ticket_data.user_logged_in=='1' && display_ticket_data.ckeditor_enable_for_loggedin=='1') || (display_ticket_data.user_logged_in=='0' && display_ticket_data.ckeditor_enable_for_guest=='1') ){
            if(CKEDITOR.instances['create_ticket_body'].getData().trim()==''){
                alert(display_ticket_data.insert_all_required);
                return false;
            }
        } else {
            if(jQuery('#create_ticket_body').val().trim()==''){
                alert(display_ticket_data.insert_all_required);
                return false;
            }
        }
        return true;
}

function validateTicketSubmitGuest(){
	var flag=true;
	jQuery.each(jQuery( '#frmCreateNewTicketGeuest').find('.wpsp_required'),function(){
	    if(jQuery(this).val().trim()==''){
                alert(display_ticket_data.insert_all_required);
                jQuery(this).focus();
                flag=false;
                return false;
	    }
	    if(jQuery(this).attr('type')=='checkbox'){
                var check_attr=jQuery(this).attr('name');
                if(jQuery('input[name="'+check_attr+'"]:checked').length<1)
                {
                alert(display_ticket_data.insert_all_required);
                flag=false;
                return false;
                }
	    }            
        });
        
        if(jQuery('#create_ticket_category').length && jQuery('#create_ticket_category').val() === ''){
            alert(display_ticket_data.insert_all_required); 
            flag=false;
            return false;
        }
        
        if(jQuery('#create_ticket_priority').length && jQuery('#create_ticket_priority').val() === ''){
            alert(display_ticket_data.insert_all_required); 
            flag=false;
            return false;
        }
        
        var email = jQuery('#create_ticket_guest_email').val();
        var regex = /^([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)@([0-9a-zA-Z]([-_\\.]*[0-9a-zA-Z]+)*)[\\.]([a-zA-Z]{2,9})$/;
        if(!regex.test(email)){
            alert(display_ticket_data.Not_valid_email_address);
            flag=false;
            return false;;
        }
        
        if(!flag){return false;}
        
        if( (display_ticket_data.user_logged_in=='1' && display_ticket_data.ckeditor_enable_for_loggedin=='1') || (display_ticket_data.user_logged_in=='0' && display_ticket_data.ckeditor_enable_for_guest=='1') ){
            if(CKEDITOR.instances['create_ticket_body_guest'].getData().trim()==''){
                alert(display_ticket_data.insert_all_required);
                return false;
            }
        } else {
            if(jQuery('#create_ticket_body_guest').val().trim()==''){
                alert(display_ticket_data.insert_all_required);
                return false;
            }
        }
        
        return true;
}

function validateReplyTicketSubmit(){
    if( (display_ticket_data.user_logged_in=='1' && display_ticket_data.ckeditor_enable_for_loggedin=='1') || (display_ticket_data.user_logged_in=='0' && display_ticket_data.ckeditor_enable_for_guest=='1') ){
        if(CKEDITOR.instances['replyBody'].getData().trim()==''){
            alert(display_ticket_data.reply_not_empty);
            return false;
        }
    } else {
        if(jQuery('#replyBody').val().trim()==''){
            alert(display_ticket_data.reply_not_empty);
            return false;
        }
    }
    return true;
}

function backToTicketFromIndisual(){
	getTickets();
}

function setSignature(id){
	jQuery('#agent_settings #agent_settings_area').hide();
	jQuery('#agent_settings .wait').show();
	
	var data = {
		'action': 'setAgentSettings',
		'id':id,
		'signature':jQuery('#agentSignature').val()
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#tab_agent_settings')[0].click();
	});
}

function getChangeTicketStatus(ticket_id){
	jQuery('#ticketContainer .ticket_indivisual').hide();
	jQuery('#ticketContainer .wait').show();
	
	var data = {
		'action': 'getChangeTicketStatus',
		'ticket_id':ticket_id
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#ticketContainer .wait').hide();
		jQuery('#ticketContainer .ticket_indivisual').html(response);
		jQuery('#ticketContainer .ticket_indivisual').show();
	});
}

function setChangeTicketStatus(ticket_id){
	jQuery('#ticketContainer .ticket_indivisual').hide();
	jQuery('#ticketContainer .wait').show();
	
	var data = {
		'action': 'setChangeTicketStatus',
		'ticket_id':ticket_id,
		'status': jQuery('#change_status_ticket_status').val(),
		'category': jQuery('#change_status_category').val(),
		'priority': jQuery('#change_status_priority').val(),
		'ticket_type': jQuery('#change_status_type').val()
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		getTickets();
	});
}

function loginGuestFacebook(){
	var data = {
		'action': 'loginGuestFacebook',
		'name':wsp_fUserName,
		'username': wsp_fUserId,
		'email': wsp_fUserEmail
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		location.reload(true);
	});
}

function getFaqs(){
	jQuery('#FAQ_TAB #faq_container').hide();
	jQuery('#FAQ_TAB .faq_filter,#faq_container .wait').show();
	
	var data = {
		'action': 'getFrontEndFAQ',
		'category': jQuery('#filter_by_faq_category_front').val(),
		'search': jQuery('#filter_by_faq_search_front').val(),
		'page_no':page_no_faq
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#faq_container .wait').hide();
		jQuery('#FAQ_TAB #faq_container').html(response);
		jQuery('#FAQ_TAB #faq_container').show();
	});
}

function openFAQ(id){
	jQuery('#faq_container').hide();
	jQuery('#FAQ_TAB .wait').show();
	jQuery('.faq_filter').hide();
	
	var data = {
		'action': 'openFrontEndFAQ',
		'id':id
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#FAQ_TAB .wait').hide();
		jQuery('#faq_container').html(response);
		jQuery('#faq_container').show();
	});
}

function triggerFAQ(){
	jQuery('#tab_faq')[0].click();
}

function load_prev_page_faq(prev_page_no){
	if(prev_page_no!=0){
		page_no_faq=prev_page_no-1;
		getFaqs();
	}
}

function load_next_page_faq(next_page_no){
	if(next_page_no!=page_no){
		page_no_faq=next_page_no;
		getFaqs();
	}
}

function assignAgent(ticket_id){
	jQuery('#ticketContainer .ticket_indivisual').hide();
	jQuery('#ticketContainer .wait').show();
	
	var data = {
		'action': 'getTicketAssignment',
		'ticket_id':ticket_id
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		jQuery('#ticketContainer .wait').hide();
		jQuery('#ticketContainer .ticket_assignment').html(response);
		jQuery('#ticketContainer .ticket_assignment').show();
	});
}

function setTicketAssignment(ticket_id){
	jQuery('#ticketContainer .ticket_assignment').hide();
	jQuery('#ticketContainer .wait').show();
	
	var data = {
		'action': 'setTicketAssignment',
		'ticket_id':ticket_id,
		'agent_id': jQuery('#assignTicketAgentId').val()
	};

	jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
		getTickets();
	});
}

function deleteTicket(ticket_id){
	if(confirm(display_ticket_data.sure_to_delete+"\n("+display_ticket_data.can_not_undone+")"))
	{
		jQuery('#ticketContainer .ticket_indivisual').hide();
		jQuery('#ticketContainer .wait').show();
		
		var data = {
			'action': 'deleteTicket',
			'ticket_id':ticket_id
		};

		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			getTickets();
		});
	}
}

function wpspCheckLogin(){
	jQuery('#wpspLoginErrorDiv').hide();
	var username=jQuery('#wpspLoginUsername').val();
	var password=jQuery('#wpspLoginPassword').val();
	if(username && password){
		jQuery('#create_ticket_container').hide();
		jQuery('#create_ticket .wait').show();
		
		var data = {
			'action': 'wpspCheckLogin',
			'username':username,
			'password':password
		};

		jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
			if(response=='OK'||response==''){
				location.reload();
			}
			else{
				jQuery('#wpspLoginErrorDiv').text(response);
				jQuery('#wpspLoginErrorDiv').show();
				jQuery('#wpspLoginPassword').val('');
				jQuery('#create_ticket .wait').hide();
				jQuery('#create_ticket_container').show();				
			}
		});
	}
	else {
		jQuery('#wpspLoginErrorDiv').text(display_ticket_data.username_or_password_missing);
		jQuery('#wpspLoginErrorDiv').show();
	}
}

function wpsp_open_apply_filter(){
	if(jQuery('#wpspBodyFrontTicketFilter').is(":visible")){
		
		jQuery('#wpspBodyFrontTicketFilter').slideUp();
	}else{

		jQuery('.wpspActionFrontBody').hide();
		jQuery('#wpspBodyFrontTicketFilter').slideDown();
	} 
	
}

function wpspHideFilterFrontBody(){
	jQuery('.wpspActionFrontBody').slideUp();
}

function wpspCheckBulkActionVisibility(){
	var values = jQuery('.bulk_action_checkbox:checked').map(function () {
  		return this.value;
	}).get();
	var str=String(values); 
	
	if(str==''){
		jQuery('.wpspActionFrontBody').hide();
		jQuery('.wpspBulkActionBtn').slideUp();
	}  
	else {
		jQuery('.wpspActionFrontBody').hide();
		jQuery('.wpspBulkActionBtn').slideDown();
	}
}

function wpsp_closeTicketStatus(ticket_id,status){   
    if(confirm(display_ticket_data.sure_to_close_status)){
        jQuery('#ticketContainer .ticket_indivisual').hide();
        jQuery('#ticketContainer .wait').show();
        var data = {
            'action': 'closeTicketStatus',
            'ticket_id':ticket_id,
            'status':status     
        };
        jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
            getTickets();      
        });
    }
}

function cat_wise_custom_field(){
    var ids=jQuery("#create_ticket_category").val();
    var data = {
        'action': 'get_cat_custom_field',
        'cat_id':ids
    };
    if(ids==''){
        jQuery('.wpsp_conditional_fields').hide();
    }else{
        jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
            var data = JSON.parse(response);
            var field_ids=data[0];
            var other_field_ids=data[1];
            jQuery('.wpsp_conditional_fields').hide();
            for(var i=0;i<field_ids.length;i++){
                jQuery('#wpsp_custom_'+field_ids[i]).show();
                if(jQuery('#cust'+field_ids[i]).length){
                    jQuery('#cust'+field_ids[i]).val("");
                }
            }
            for(var i=0;i<other_field_ids.length;i++){
                switch(other_field_ids[i][1]){
                    case '1':   jQuery('#cust'+other_field_ids[i][0]).val(display_ticket_data.not_applicable);
                                break;
                    case '2':   jQuery('#cust'+other_field_ids[i][0]+" option:eq(1)").prop('selected', true);
                                break;
                    case '3':   jQuery('#wpsp_custom_'+other_field_ids[i][0]).find(":checkbox:eq(0)").attr("checked", "checked");
                                break;
                    case '4':   jQuery('#wpsp_custom_'+other_field_ids[i][0]).find(":radio:eq(0)").attr("checked", "checked");
                                break;        
                    case '5':   jQuery('#cust'+other_field_ids[i][0]).val(display_ticket_data.not_applicable);
                                break;
                    case '6':   jQuery('#cust'+other_field_ids[i][0]).val(display_ticket_data.not_applicable);
                                break;
                }
            }
        });
    }   
}

function wpsp_woo_product_help(prod_id){
    wpsp_show_front_popup();
    var data = {
        'action': 'wpspWooProductGetForm',
        'prod_id':prod_id
    };
    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
        jQuery('#wpsp_front_popup_body').html(response);
        jQuery('#wpsp_front_popup_blank,#wpsp_front_popup_loading_img').hide();
        jQuery('#wpsp_front_popup_body').show();
    });
}

function wpsp_woo_order_help(order_id){
    wpsp_show_front_popup();
    var data = {
        'action': 'wpspWooProductGetForm',
        'order_id':order_id
    };
    jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
        jQuery('#wpsp_front_popup_body').html(response);
        jQuery('#wpsp_front_popup_blank,#wpsp_front_popup_loading_img').hide();
        jQuery('#wpsp_front_popup_body').show();
    });
}

function wpsp_show_front_popup(){
    jQuery('#wpsp_front_popup,#wpsp_front_popup_inner,#wpsp_front_popup_blank,#wpsp_front_popup_close_btn,#wpsp_front_popup_loading_img').show();
    jQuery('#wpsp_front_popup_body').hide();
}

function wpsp_close_front_popup(){
    jQuery('#wpsp_front_popup,#wpsp_front_popup_inner,#wpsp_front_popup_blank,#wpsp_front_popup_close_btn,#wpsp_front_popup_loading_img').hide();
}

function wpsp_extension_form_submit(){
    validate=true;
    jQuery('.wpsp_extension_form_field').each(function(){
        if(jQuery(this).val().trim()==''){
            jQuery(this).css('border-color','#ff0000');
            validate=false;
        } else {
            jQuery(this).css('border-color','#616161');
        }
    });
    if(validate){
        wpsp_show_front_popup();
        var data = {
            'action': 'wpspSubmitExtensionForm',
            'wpsp_extension_form_name' : jQuery('#wpsp_extension_form_name').val(),
            'wpsp_extension_form_email' : jQuery('#wpsp_extension_form_email').val(),
            'wpsp_extension_form_subjcet' : jQuery('#wpsp_extension_form_subjcet').val(),
            'wpsp_extension_form_desc' : jQuery('#wpsp_extension_form_desc').val(),
            'wpsp_extension_form_info' : jQuery('#wpsp_extension_form_info').val()
        };
        jQuery.post(display_ticket_data.wpsp_ajax_url, data, function(response) {
            jQuery('#wpsp_front_popup_body').html(response);
            jQuery('#wpsp_front_popup_blank,#wpsp_front_popup_loading_img').hide();
            jQuery('#wpsp_front_popup_body').show();
        });
    }
}