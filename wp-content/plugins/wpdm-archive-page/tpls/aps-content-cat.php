<?php
$term = get_term($_POST['cid'], 'wpdmcategory');
?>
<div class="breadcrumb" style="border-radius:0;">
    <?php \WPDM\libs\CategoryHandler::CategoryBreadcrumb($_POST['cid'],0); ?>
</div>

<h2 style="margin: 0 0 10px 0"><?php echo $term->name; ?></h2>
<div class="list-group">
<?php

$terms = get_terms('wpdmcategory', array('parent'=>$_POST['cid']));

foreach($terms as $term){
    echo "<a class='list-group-item apc-item' href='#' data-item-id='{$term->term_id}'>{$term->name}</a>";
}

?>
</div>

<table class="table table-border table-striped">

    <?php
    global $post;
    $cparams['posts_term_page'] = -1;
    $cparams['tax_query'] = array(array(
        'taxonomy' => 'wpdmcategory',
        'field' => 'id',
        'terms' => array($_POST['cid'])
    ));

    $packs = new WP_Query($cparams);

    while($packs->have_posts()){ $packs->the_post();
        $icon = get_post_meta(get_the_ID(), '__wpdm_icon', true);
        $icon = $icon==''?WPDM_BASE_URL.'file-type-icons/download4.png':$icon;
            ?>

            <tr>
                <td><img src="<?php echo $icon; ?>" style="float: left;margin-right: 10px;width: 20px;" /> <?php the_title(); ?></td>
                <td><?php echo get_the_modified_date(); ?></td>
                <td class="text-right"><a href="#" class="apc-pack"
                                          data-item-id="<?php the_ID(); ?>"><?php _e('View Details', 'wpdmap'); ?>
                        &nbsp;<i class="fa fa-angle-right"></i></a></td>
            </tr>

            <?php

    }

    ?>
</table>

