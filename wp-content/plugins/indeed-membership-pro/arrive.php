<?php
require_once '../../../wp-load.php';
require_once 'classes/ResetPassword.class.php';

if (!empty($_GET['do_reset_pass']) && !empty($_GET['uid']) && !empty($_GET['c'])){
	/// DO RESET PASSWORD
	$object = new IHC\ResetPassword();
	$object->proceed($_GET['uid'], $_GET['c']);
}


/// AND OUT
$redirect = get_option('ihc_general_redirect_default_page');
if (!empty($redirect) && $redirect!=-1){
	$redirect_url = get_permalink($redirect);
} else {
	$redirect_url = get_home_url();	
}
wp_redirect($redirect_url);
exit();