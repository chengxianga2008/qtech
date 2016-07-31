<?php 
require_once 'IndeedFeedSystem.class.php';
if (empty($ind_menu)){
	$ind_menu = '';
}
$obj = new IndeedFeedSystem($ind_menu);