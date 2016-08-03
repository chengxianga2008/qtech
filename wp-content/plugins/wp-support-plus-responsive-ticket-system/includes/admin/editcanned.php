<h3>Edit Canned Reply</h3><br>
<?php 
global $wpdb;

if(isset($_REQUEST['action'])){
	//code to insert into db
	$values=array(
			'title'=>$_REQUEST['title'],
			'reply'=>$_REQUEST['wpsp_canned_reply']
			
	);
	$wpdb->update($wpdb->prefix.'wpsp_canned_reply',$values,array('id'=>$_REQUEST['id']));
	wp_redirect(admin_url('admin.php?page=wp-support-plus-Canned-Reply')); 
}


$can=$wpdb->get_row("select * from {$wpdb->prefix}wpsp_canned_reply where id=".$_REQUEST['id']);
?>
<form id="wpsp_add_canned" method="post" action="<?php echo admin_url('admin.php?page=wp-support-plus-Canned-Reply&type=edit&action=set&noheader=true&id='.$_REQUEST['id']);?>">
	
	<b>Title:</b><br>
	<input type="text" id="wpsp_can_title" name="title" value="<?php echo stripcslashes($can->title);?>" style="width: 100%;"><br><br>
	<b>Reply:</b><br>
	<?php 
	$settings = array( 'media_buttons' => true, 'wpautop'=>false );
	wp_editor( stripcslashes($can->reply), 'wpsp_canned_reply', $settings );
	?>
	<br><br/>
	<button type="submit" class="btn btn-success">Submit</button>
</form>
<script type="text/javascript">
jQuery('#wpsp_add_canned').submit(function(e){
	if(jQuery('#wpsp_can_title').val().trim()==''){
		alert('Please enter question');
		e.preventDefault();
	}
});
</script>
