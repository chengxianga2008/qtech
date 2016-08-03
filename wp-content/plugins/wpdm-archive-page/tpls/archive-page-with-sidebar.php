<div class="w3eden">

    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <?php

                    $parent = 0;

                    if(isset($params['category'])) {
                        if((int)$params['category']!==$params['category']){
                            $cat = get_term_by("slug", $params['category'], "wpdmcategory");
                            $parent = $cat->term_id;
                        }
                        else
                            $parent = $params['category'];
                    }

                    $terms = get_terms('wpdmcategory', array('parent'=>$parent));

                    foreach($terms as $term){
                        echo "<a class='list-group-item apc-item' href='#' data-item-id='{$term->term_id}'>{$term->name}</a>";
                    }

                ?>
            </div>
        </div>
        <div class="col-md-9">
            <div class="wpdm-loading" style="border-radius: 0;display: none;right:15px;">
                <i class="fa fa-refresh fa-spin"></i> Loading...
            </div>
            <div id="ap-content">

            </div>
        </div>
    </div>

</div>

<script>
    jQuery(function($){
        $('body').on('click', '.apc-item', function(){
            $('.wpdm-loading').fadeIn();
            $('#ap-content').load("<?php echo admin_url('admin-ajax.php'); ?>", {action:'load_ap_content', cid: $(this).data('item-id')}, function(){ $('.wpdm-loading').fadeOut(); });
            return false;
        });

        $('body').on('click', '.breadcrumb .folder', function(){
            $('.wpdm-loading').fadeIn();
            $('#ap-content').load("<?php echo admin_url('admin-ajax.php'); ?>", {action:'load_ap_content', cid: $(this).data('cat')}, function(){ $('.wpdm-loading').fadeOut(); });
            return false;
        });

        $('body').on('click', '.apc-pack', function(){
            $('.wpdm-loading').fadeIn();
            $('#ap-content').load("<?php echo admin_url('admin-ajax.php'); ?>", {action:'load_ap_content', pid: $(this).data('item-id'), pagetemplate: '<?php echo isset($params['page_template'])?$params['page_template']:''; ?>'}, function(){ $('.wpdm-loading').fadeOut(); });
            return false;
        });
    });
</script>
<style>
    .list-group-item:after{
        content: "\f105";
        font-family: FontAwesome;
        position: absolute;
        right: 10px;
        top: 10px;
    }
    .list-group, .list-group-item{
        border-radius: 0 !important;
        position: relative;
    }
    .table-border{
        border: 1px solid #dddddd;
    }
    .table-border td{
        padding: 15px 15px !important;
    }
</style>