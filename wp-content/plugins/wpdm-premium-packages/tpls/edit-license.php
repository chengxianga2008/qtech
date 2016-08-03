<?php
    global $wpdb;
?>
<div class="wrap">
    <div class="icon32" id="icon-file-manager"><br></div>
<h2>Edit License   </h2>

 
<div id="poststuff" class="metabox-holder has-right-sidebar">

<div id="post-body">
<div id="post-body-content">
<form method="post" action="" id="posts-filter">
<input type="hidden" name="lid" value="<?php echo $_GET['id']; ?>">
 <div id="titlediv">
 <div id="titlewrap">
 <label>License No:</label><br/>
 <input id="title" style="width: 400px" type="text" disabled="disabled" value="<?php echo $license->licenseno; ?>">
 </div>
 </div>
 <div style="width: 220px;float: left;">
 <label>Order ID:</label><br/>
 <input id="title" style="width: 150px" type="text" disabled="disabled" value="<?php echo $license->oid; ?>">
 </div>
 <div style="float: left;">
 <label>Status:</label><br/>
 <select style="width: 100px" name="license[status]"><option value="1">Online</option><option value="0" <?php echo $license->status?'':'selected=selected'; ?> >Offline</option></select>
 </div>
 <br clear="all">
 <br clear="all">
 
 <div>
 <label>Domains: <span class="info infoicon" title="One domain per line. don't use<br/><b>http://</b> or <b>www</b> only <b>domain.com</b>"></span></label><br/>
 <textarea cols="60" rows="6" name="license[domain]"><?php echo @implode("\n",@unserialize($license->domain)); ?></textarea>
 </div>    <br>
 
 <div style="float: left;width:200px">
 <label>Activation Date:</label><br/>
 <input type="text" name="license[activation_date]" onfocus="if(this.value=='yyyy-mm-dd') this.value='';" onblur="if(this.value=='') this.value='yyyy-mm-dd';" value="<?php echo $license->activation_date?date("Y-m-d",$license->activation_date):'yyyy-mm-dd'; ?>" />
 </div>
 <div style="float: left;">
 <label>Expire Period:</label><br/>
 <input type="text" size="5" name="license[expire_period]" value="<?php echo $license->expire_period; ?>" /> day(s)
 </div>
 <br clear="all"/>
 <br clear="all"/>
 <input type="submit" class="button-primary" value="Save License">
</form>
<br class="clear">
</div>
</div>
</div>