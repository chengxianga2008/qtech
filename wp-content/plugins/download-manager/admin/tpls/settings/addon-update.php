<div class="panel panel-default">
    <div class="panel-heading">
        <div class="pull-right"><a href="edit.php?post_type=wpdmpro&page=settings&tab=plugin-update&newpurchase=1">Reload</a> / <a href="edit.php?post_type=wpdmpro&page=settings&tab=plugin-update&logout=1">Logout</a></div>
        <i class="fa fa-refresh"></i> &nbsp; <?php _e('Add-on Update', 'wpdmpro') ?></div>
    <div class="panel-body-x">

        <?php if(get_option('__wpdm_suname') =='') { ?>
        <div class="panel-body">
            <div class="form-group">
                <em>Enter your <a href="http://www.wpdownloadmanager.com/" target="_blank">wpdownloadmanager.com</a> login info:</em>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon1"><i class="fa fa-user"></i></span>
                    <input placeholder="Username" name="__wpdm_suname" id="user_login"
                           class="form-control required text" value="" size="20" tabindex="38" type="text">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon1"><i class="fa fa-key"></i></span>
                    <input placeholder="Password" name="__wpdm_supass" id="user_pass"
                           class="form-control required password" value="" size="20" tabindex="39" type="password">
                </div>
            </div>
            </div>


            <?php
        } else {
            //print_r($purchased_items);
        ?>
<ul id="plugin-updates-nav" class="nav nav-pills nav-justified">
    <li class="active"><a href="#pro-add-ons" data-toggle="tab">Purchased Add-ons</a></li>
    <li><a href="#free-add-ons" data-toggle="tab">Free Add-ons</a></li>
</ul>
            <div class="tab-content">
                <div class="tab-pane active" id="pro-add-ons">
                    <table class="table" style="margin: 0;">
                <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Active&nbsp;(V)</th>
                    <th>Latest&nbsp;(V)</th>
                    <th>Download</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $latest = maybe_unserialize(get_option('wpdm_latest'));

                if(is_array($purchased_items)){
                foreach($purchased_items as $item){
                    if($item->download_url){
                    foreach($item->download_url as $file => $dlu){
                        $plugin_name = str_replace(".zip", "", basename($file));
                        $plugin_data = wpdm_plugin_data($plugin_name);
                    ?>
                    <tr class="<?php if($item->order_status == 'Expired'){  ?>bg-danger<?php } else { ?><?php echo version_compare($latest[$plugin_name], $plugin_data['Version'], '>')?'bg-warning':(!$plugin_data?'':'bg-success'); ?><?php } ?>">
                        <td><a href="http://www.wpdownloadmanager.com/?p=<?php echo $item->pid; ?>" target="_blank"><?php echo $item->post_title; ?> ( <?php echo basename($file); ?> )</a></td>
                        <td><?php echo $plugin_data['Version']; ?></td>
                        <td><?php echo $latest[$plugin_name]?$latest[$plugin_name]:'NA'; ?></td>
                        <td style="width: 100px">
                            <?php if($item->order_status == 'Completed'){  ?>
                                <?php if(!$plugin_data){ ?>
                                    <a href="#" data-url="<?php echo $dlu; ?>" data-action="installaddon" data-plugin="<?php echo $plugin_name; ?>" class="btn btn-xs btn-success btn-block btn-update"><i class="fa fa-plus"></i> Install</a>
                                <?php } else if(version_compare($latest[$plugin_name], $plugin_data['Version'], '>')){ ?>
                                    <a href="#" data-url="<?php echo $dlu; ?>" data-action="updateaddon" data-plugin="<?php echo $plugin_name; ?>" class="btn btn-xs btn-warning btn-block btn-update"><i class="fa fa-refresh"></i> Update</a>
                                <?php } else echo "<span class='text-success'><i class='fa fa-check-circle'></i> Updated</span>"; ?>
                                <?php } else { ?>
                                    <a href="http://www.wpdownloadmanager.com/user-dashboard/purchases/order/<?php echo $item->oid; ?>/" target="_blank" class="btn btn-xs btn-danger btn-block">Expired</a>
                                <?php } ?>
                        </td>
                    </tr>

                <?php }}}} ?>
                </tbody>
            </table>
                </div>
                <div class="tab-pane active" id="free-add-ons">
                    <table class="table" style="margin: 0;">
                        <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Active&nbsp;(V)</th>
                            <th>Latest&nbsp;(V)</th>
                            <th>Download</th>
                        </tr>
                        </thead>
                        <tbody>
                    <?php

                    foreach($freeaddons->post_extra as $addon){
                        $file = $addon->pinfo->files[0];
                        $plugin_name = str_replace(".zip", "", basename($file));
                        $plugin_data = wpdm_plugin_data($plugin_name);
                        ?>

                        <tr class="<?php echo version_compare($latest[$plugin_name], $plugin_data['Version'], '>')?'bg-warning':(!$plugin_data?'':'bg-success'); ?>">
                            <td><a href="<?php echo $addon->link; ?>" target="_blank"><?php echo $addon->title; ?></a></td>
                            <td><?php echo $plugin_data['Version']; ?></td>
                            <td><?php echo $latest[$plugin_name]?$latest[$plugin_name]:'NA'; ?></td>
                            <td style="width: 100px">

                                <?php if(!$plugin_data){ ?>
                                        <a href="#" data-url="http://www.wpdownloadmanager.com/?wpdmdl=<?php echo $addon->ID; ?>" data-action="installaddon" data-plugin="<?php echo $plugin_name; ?>" class="btn btn-xs btn-success btn-block btn-update"><i class="fa fa-plus"></i> Install</a>
                                <?php } else if(version_compare($latest[$plugin_name], $plugin_data['Version'], '>')){ ?>
                                    <a href="#" data-url="http://www.wpdownloadmanager.com/?wpdmdl=<?php echo $addon->ID; ?>" data-action="updateaddon" data-plugin="<?php echo $plugin_name; ?>" class="btn btn-xs btn-warning btn-block btn-update"><i class="fa fa-refresh"></i> Update</a>
                                <?php } else echo "<span class='text-success'><i class='fa fa-check-circle'></i> Updated</span>"; ?>

                            </td>
                        </tr>

                    <?php }
                    ?>
                        </tbody>
                        </table>
                </div>
            </div>
            <script>
                jQuery(function($){
                    $('.btn-update').on('click', function (res) {
                        var bhtml = $(this).html(), btn = $(this);
                        btn.html('<i class="fa fa-refresh fa-spin"></i> Please Wait...');
                        $.post('admin-ajax.php?action='+$(this).data('action'), {updateurl: $(this).data('url'),  plugin: $(this).data('plugin')}, function (res) {
                            btn.html('<i class="fa fa-check-circle"></i> Success!');
                        });
                        return false;
                    })
                });
            </script>

        <?php } ?>

    </div>
</div>
