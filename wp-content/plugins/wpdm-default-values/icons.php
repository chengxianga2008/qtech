


<div id="package-icons" class="tab-pane">

    <?php
    $path = WPDM_BASE_DIR."/file-type-icons/";
    $scan = scandir( $path );
    $k = 0;
    $fileinfo = array();
    foreach( $scan as $v )
    {
        if( $v=='.' or $v=='..' or is_dir($path.$v) ) continue;

        $fileinfo[$k]['file'] = 'download-manager/file-type-icons/'.$v;
        $fileinfo[$k]['name'] = $v;
        $k++;
    }

    if(!isset($default['icon']))
    $default['icon'] = '';
    ?>
    <div id="w-icons">
        <img  id="icon-loading" src="<?php  echo plugins_url('download-manager/images/loading.gif'); ?>" style=";display:none;padding:5px; margin:1px; float:left; border:#fff 2px solid;height: 32px;width:auto; " />
        <?php
        $img = array('jpg','gif','jpeg','png');
        foreach($fileinfo as $index=>$value): $tmpvar = explode(".",$value['file']); $ext = strtolower(end($tmpvar)); if(in_array($ext,$img)): ?>
            <label>
                <img class="wdmiconfile" id="<?php echo md5($value['file']) ?>" src="<?php  echo plugins_url().'/'.$value['file'] ?>" alt="<?php echo $value['name'] ?>" style="padding:5px; margin:1px; float:left; border:#fff 2px solid;height: 32px;width:auto; " />
                <input rel="wdmiconfile" style="display:none" <?php checked($default['icon'],$value['file']); ?> type="radio"  name="wpdm_defaults[icon]"  class="checkbox"  value="<?php echo $value['file'] ?>"></label>
        <?php endif; endforeach; ?>
    </div>
    <script type="text/javascript">
        //border:#CCCCCC 2px solid

        <?php if(isset($_GET['action'])&&$_GET['action']=='edit'){ ?>
        jQuery('#<?php echo md5($default['icon']) ?>').addClass("iactive");
        <?php } ?>    
        jQuery('img.wdmiconfile').live('click',function(){

            jQuery('img.wdmiconfile').removeClass('iactive');
            jQuery(this).addClass('iactive');



        });

    </script>
    <style>

        .iactive{
            -moz-box-shadow:    inset 0 0 10px #5FAC4F;
            -webkit-box-shadow: inset 0 0 10px #5FAC4F;
            box-shadow:         inset 0 0 10px #5FAC4F;
            background: #D9FCD1;
        }
    </style>

    <div class="clear"></div>
</div>
 
 