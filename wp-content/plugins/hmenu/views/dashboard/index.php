<?php
	header("X-Robots-Tag: noindex, nofollow", true);
?>
<script type="text/javascript" data-cfasync="false" src="<?php echo $_GET['p']; ?>assets/js/promotions_manager.js"></script>
<script type="text/javascript" data-cfasync="false" src="<?php echo $_GET['v']; ?>js/view.core.js"></script>

<!--BEGIN: dashboard-->
<div class="hero_top_dashboard">
    <div class="hero_dashboard_intro">
        <div class="hero_dashboard_logo"><img src="<?php echo $_GET['p']; ?>assets/images/admin/plugin_logo.png" /></div>
    </div>
</div>
<div class="hero_top_version">
    <div class="hero_version hero_white" id="plugin_version"></div>
    <div class="hero_version_date hero_white">
        <div class="hero_last"><span id="plugin_last_update"></span><br />Last Update</div>
        <div class="hero_release"><span id="plugin_release_date"></span><br /> Release Date</div>
    </div>
</div>
<div class="hero_views">
	<div class="dashboard_grid">    
    	<!--BEGIN: custom content area-->
        <h2 class="hero_red size_18">Menu</h2>
        <div class="hero_list_holder hero_grey size_11">
        	<div class="hero_col_12 hero_list_heading hero_white">
                <div class="hero_col_4"><span>Name</span></div>
                <div class="hero_col_5"><span>Shortcode</span></div>
            </div>
            <div class="hero_misc_load">
        	</div>
        </div>
        <!--END: custom content area-->      
    </div>    
    <div class="promo_expand">
        <div class="promo_holder">
        	<div class="promo_btn">
                <a href="http://heroplugins.com" target="_blank">
                </a>
            </div>
        </div>
        <div class="hero_grey hero_license_holder">
        	Please note that this plugin is licensed for use with one active site only. <span class="license_toggle hero_red">Read more</span>
 			<br><br>
            Should you not have a license for this installation, please -purchase a new license <a style="color:#E05158" href="http://codecanyon.net/item/hero-menu-responsive-wordpress-mega-menu-plugin/10324895" target="_blank">here</a>.
            Should you choose to proceed without a license, you and the site owner will be in contravention of the terms of sale and copyright inherent to this plugin.
            <br><br>
            Please be a responsible and honest plugin user!
        </div> 
    </div>
</div>
<!--END: dashboard-->