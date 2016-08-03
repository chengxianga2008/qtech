<?php
/*
Plugin Name: WPDM - Archive Page
Description: Add archive page option with wordpress download manager
Plugin URI: http://www.wpdownloadmanager.com/
Author: Shaon
Version: 2.8.0
Author URI: http://www.wpdownloadmanager.com/
*/



class WPDM_ArchivePage{

    function __construct(){

        $this->Actions();
        $this->ShortCodes();

    }

    /**
     * @usage Initiate Action Hooks
     */
    function Actions(){
        add_action( 'plugins_loaded', array($this, 'LoadTextdomain') );
        add_action('wpdm_ext_shortcode', array($this, 'MCEButtonHelper'));
        add_action("wp_ajax_wpdm_change_cat_parent", array( $this, 'ChangeCatParent'));
        add_action("wp_ajax_nopriv_wpdm_change_cat_parent", array( $this, 'ChangeCatParent'));
        add_action("wp_ajax_load_ap_content", array( $this, 'APSContent'));
        add_action("wp_ajax_nopriv_load_ap_content", array( $this, 'APSContent'));
        add_action("init",  array($this, 'GetChildCats'));
        add_action('basic_settings', array($this, 'LinkTemplateOption'));
        add_action('wp_loaded',array($this, 'GetDownloads'));
        add_action('wp_head',array($this, 'WPHead'));
        add_action('widgets_init', create_function('', 'return register_widget("WPDM_SearchWidget");'));
        add_filter("posts_where", array($this, "Where"));
    }

    /**
     * @usage Introduce All Short-codes
     */
    function ShortCodes(){
        add_shortcode( 'wpdm-archive', array($this, 'ArchivePage'));
        add_shortcode( 'wpdm_archive', array($this, 'ArchivePage'));
        add_shortcode( 'wpdm-categories', array($this, 'Categories'));
        add_shortcode( 'wpdm_categories', array($this, 'Categories'));
        add_shortcode( 'wpdm-tags', array($this, 'Tags'));
        add_shortcode( 'wpdm_tags', array($this, 'Tags'));
        add_shortcode( 'wpdm-search-page', array($this, 'SeachBar'));
        add_shortcode( 'wpdm_search_page', array($this, 'SeachBar'));
        add_shortcode( 'wpdm_simple_search', array($this, 'SimpleSeachBar'));
    }

    /**
     * @usage Load Language File
     */
    function LoadTextdomain()
    {
        load_plugin_textdomain('wpdmap', WP_PLUGIN_URL . "/wpdm-archive-page/languages/", 'wpdm-archive-page/languages/');
    }


    function ArchivePageWithSidebar($params = array()){
        ob_start();
        include(wpdm_tpl_path('archive-page-with-sidebar.php', dirname(__FILE__).'/tpls/'));
        return ob_get_clean();
    }

    function APSContent(){
        if(isset($_POST['cid']))
            include(wpdm_tpl_path('aps-content-cat.php', dirname(__FILE__).'/tpls/'));
        if(isset($_POST['pid']))
            include(wpdm_tpl_path('aps-content-pack.php', dirname(__FILE__).'/tpls/'));
        die();
    }

    /**
     * @param int $parent
     * @param string $btype
     * @param int $base
     * @usage Render WPDM Category List
     */
    function RenderCats($parent=0, $btype = 'default', $base = 0){
        global $wpdb, $current_user;
        $user_role = isset($current_user->roles[0])?$current_user->roles[0]:'guest';

        $args = array(
            'orderby'       => 'name',
            'order'         => 'ASC',
            'hide_empty'    => false,
            'exclude'       => array(),
            'exclude_tree'  => array(),
            'include'       => array(),
            'number'        => '',
            'fields'        => 'all',
            'slug'          => '',
            'parent'         => $parent,
            'hierarchical'  => true,
            'get'           => '',
            'name__like'    => '',
            'pad_counts'    => false,
            'offset'        => '',
            'search'        => '',
            'cache_domain'  => 'core'
        );
        $categories = get_terms('wpdmcategory',$args);

        if(is_array($categories)){
            if($parent!=$base)   echo "<ul  class='dropdown-menu' role='menu' aria-labelledby='dLabel'>" ;
            foreach($categories as $category) {

                $cld = get_term_children( $category->term_id, 'wpdmcategory' );
                $ccount = $category->count;
                $link = get_term_link($category);

                ?>

                <li <?php if($parent==$base&&count($cld)>0){ ?>class="col-md-4 col-sm-6"<?php } else if(count($cld)>0){ ?>class="dropdown-submenu"<?php } elseif($parent==$base){ ?>class="col-md-4 col-sm-6"<?php } ?>>

                    <?php if($parent==$base&&count($cld)>0): ?>
                        <a href="#" class="btn btn-ddm btn-sm btn-<?php echo $btype; ?> dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-chevron-down"></i>
                        </a>
                    <?php endif; ?>
                    <a class=" <?php if($parent==$base): ?>btn btn-sm btn-<?php echo $btype; ?> <?php if(count($cld)<=0||1): ?>btn-block<?php endif; ?><?php endif; ?> wpdm-cat-link" rel='<?php echo $category->term_id; ?>' href="<?php echo $link; ?>">
                        <?php echo stripcslashes($category->name); ?> (<?php echo $ccount; ?>)
                    </a>

                <?php $this->RenderCats($category->term_id, $btype, $base);

                    echo '</li>';
            }
            if($parent!=$base)   echo "</ul>" ;
        }
    }

    /**
     * @param array $params
     * @return string
     * @usage Category List
     */
    function Categories($params = array()){
        global $wpdb;
        @extract($params);
        $parent = isset($parent)?$parent:0;
        $args = array(
            'orderby'       => 'name',
            'order'         => 'ASC',
            'hide_empty'    => false,
            'exclude'       => array(),
            'exclude_tree'  => array(),
            'include'       => array(),
            'number'        => '',
            'fields'        => 'all',
            'slug'          => '',
            'parent'         => $parent,
            'hierarchical'  => false,
            'child_of'      => 0,
            'get'           => '',
            'name__like'    => '',
            'pad_counts'    => false,
            'offset'        => '',
            'search'        => '',
            'cache_domain'  => 'core'
        );
        $categories = get_terms('wpdmcategory',$args);
        $pluginsurl = plugins_url();
        $cols = isset($cols)&&$cols>0?$cols:2;
        $scols = intval(12/$cols);
        $icon = isset($icon)?"<style>.wpdm-all-categories li{background: url('{$icon}') left center no-repeat;}</style>":"";
        $k = 0;
        $html = "
        {$icon}
        <div  class='wpdm-all-categories wpdm-categories-{$cols}col'><div class='row'>";
        foreach($categories as $id=>$category){
            $catlink = get_term_link($category);
            if($category->parent==$parent) {
                $ccount = $category->count;
                if(isset($showcount)&&$showcount) $count  = "&nbsp;<span class='wpdm-count'>($ccount)</span>";
                $html .= "<div class='col-md-{$scols} cat-div'><a class='wpdm-pcat' href='$catlink' >".htmlspecialchars(stripslashes($category->name))." (".$category->count.")</a>";
                if(isset($subcat) && $subcat==1) {
                    $sargs = array(
                        'orderby' => 'name',
                        'order' => 'ASC',
                        'hide_empty' => false,
                        'fields' => 'all',
                        'hierarchical' => false,
                        'child_of' => $category->term_id,
                        'pad_counts' => false
                    );
                    $subcategories = get_terms('wpdmcategory', $sargs);
                    $html .= "<div class='wpdm-subcats'>";
                    foreach ($subcategories as $sid => $subcategory) {
                        $scatlink = get_term_link($subcategory);
                        $html .= "<a  class='wpdm-scat' href='$scatlink' >" . htmlspecialchars(stripslashes($subcategory->name)) . " (".$subcategory->count.") </a>";
                    }
                    $html .= "</div>";
                }
                $html .= "</div>";
                $k++;
            }
        }

        $html .= "</div><div style='clear:both'></div></div>";
        if($k==0) $html = '';
        return "<div class='w3eden'>".str_replace(array("\r","\n"),"",$html)."</div>";
    }

    /**
     * @param array $params
     * @return mixed
     * @usage Short-code callback function for [wpdm_archive]
     */
    function ArchivePage($params = array()){
        global $wpdb;
        @extract($params);

        $cat_view = isset($cat_view) && in_array($cat_view, array('hidden','compact','expanded','sidebar'))?$cat_view:'expanded';

        if($cat_view == 'sidebar') return $this->ArchivePageWithSidebar($params);

        $button_style = isset($button_style)?$button_style:'default';
        if(isset($category))
        {
            if(intval($category) == 0 && $category != ''){
                $cat = get_term_by("slug", $category, "wpdmcategory");
                $category = is_object($cat) && isset($cat->term_id)? $cat->term_id:0;
            }
        }
        //$initc = isset($params['category'])?$params['category']:"";
        $category = isset($category)?$category:0;
        $link_template = isset($link_template)?$link_template:'';
        $items_per_page = isset($items_per_page)?$items_per_page:0;
        update_post_meta(get_the_ID(),"__wpdm_link_template",$link_template);
        update_post_meta(get_the_ID(),"__wpdm_items_per_page",$items_per_page);

        if(isset($order)) {
            update_post_meta(get_the_ID(), '__wpdm_z_order', $order);
        }
        else {
            update_post_meta(get_the_ID(), '__wpdm_z_order', '');
        }

        if(isset($order_by)) {
            update_post_meta(get_the_ID(), '__wpdm_z_order_by', $order_by);
        }
        else {
            update_post_meta(get_the_ID(), '__wpdm_z_order_by', '');
        }



        $categories = maybe_unserialize(get_option('_fm_categories',array()));
        $pluginsurl = plugins_url();
        $comcat = '';
        $sw = 6;
        if($cat_view == 'compact') {
            $comcat = "<div class='col-md-3'>".'<label for="wpdm-cats-compact">'.__('Category:','wpdmap').'</label>'.wpdm_dropdown_categories('wpdm-cats-compact', '', 'wpdm-cats-compact', 0)."</div>";
            $sw = 3;
        }
        $html = '
        <div class=\'w3eden\'>
        <form id="srcp" style="margin-bottom: 10px">
        <div  class="row">
        <input type="hidden" name="category" id="initc" value="'.$category.'" />
        <span class="col-md-'.$sw.'">
        <label for="src">'.__('Search Package','wpdmap').':</label>
        <input type="text" class="form-control" name="src" placeholder="'.__('Search Package','wpdmap').'" id="src">
        </span>

        '.$comcat.'

        <span class="col-md-3">
        <label for="order_by">'.__('Order By:','wpdmap').'</label>
        <select name="order_by" id="order_by" class="form-control selectpicker">
        <option value="date">'.__('Publish Date','wpdmap').'</option>
        <option value="modified">'.__('Last Updated','wpdmap').'</option>
        <option value="view_count">'.__('View Count','wpdmap').'</option>
        <option value="download_count">'.__('Download Count','wpdmap').'</option>
        <option value="package_size_b">'.__('Package Size','wpdmap').'</option>
        </select>
        </span>
        <span class="col-md-3">
        <label for="order">'.__('Order:','wpdmap').'</label>
        <select name="order" id="order" class="form-control selectpicker">
        <option value="DESC">'.__('Descending Order','wpdmap').'</option>
        <option value="ASC">'.__('Ascending Order','wpdmap').'</option>
        </select>
        </span>

        </div><br class="clear"/>
        </form>
        <div class="row">
        <div class="col-md-12">
        <div class="breadcrumb">
        <a href="#" id="wpdmap-home">'.__('Home','wpdmap').'</a> <i class="fa fa-angle-right icon icon-angle-right"></i>
        <span id="inp">'.__('All Packages','wpdmap').'</span>
        </div>
        </div>
        </div>';

        if($cat_view == 'expanded') {
            $html .= '<div  class=\'wpdm-categories\'><ul class=\'row\'>';
            ob_start();
            $this->RenderCats($category, $button_style, $category);
            $html .= ob_get_clean();

            $html .= "</ul><div class='clear'></div></div><div class='clear'><br/></div>";
        }
        $html .="

        <div style='margin: 3px;clear: both;'>
        <div  class='wpdm-downloads row' id='wpdm-downloads'>
        ".__('Select category or search','wpdmap')."...
        </div></div></div>
        ";

        return str_replace(array("\r","\n"),"",$html);
    }

    function Where($where){
        if(!isset($_GET['wpdmtask'])||$_GET['wpdmtask']!='get_downloads') return $where;
        $where = str_replace(array("\n", "\r",""), "", $where);
        $where = str_replace("AND (   ( wp_postmeta.meta_key","OR (   ( wp_postmeta.meta_key", $where);
        $where = str_replace("AND wp_posts.post_type = 'wpdmpro'",") AND wp_posts.post_type = 'wpdmpro'", $where);
        if(strpos($where, "AND wp_posts.post_type = 'wpdmpro'"))
        $where = str_replace("(((wp_posts.post_title LIKE","((((wp_posts.post_title LIKE", $where);
        //dd($where);
        return $where;
    }

    /**
     * @usage Fetch Packages
     */
    function GetDownloads(){

        if(!isset($_GET['wpdmtask'])||$_GET['wpdmtask']!='get_downloads') return;

        global $wpdb, $current_user;
        get_currentuserinfo();

        $actpl =  get_option('_wpdm_ap_search_page_template','link-template-default.php');
        $tctpl = get_post_meta((int)$_GET['pg'],'__wpdm_link_template', true);

        $item_per_page = get_post_meta((int)$_GET['pg'],'__wpdm_items_per_page', true);



        if($tctpl!='') $actpl = $tctpl;

        $category = isset($_REQUEST['category'])?addslashes($_REQUEST['category']):'';
        $src = isset($_GET['search'])?esc_html($_GET['search']):'';

        $item_per_page =  $item_per_page<=0?10:$item_per_page;

        $page = isset($_GET['cp'])?$_GET['cp']:1;
        $start = ($page-1)*$item_per_page;
        $params = array("post_status" => "publish", "post_type"=>"wpdmpro","posts_per_page"=>$item_per_page,"offset"=>$start);

        //order parameter
        $order = get_post_meta((int)$_GET['pg'],'__wpdm_z_order',true);
        $order_by = get_post_meta((int)$_GET['pg'],'__wpdm_z_order_by',true);
        //echo $order . ' ' . $order_by . '<br>';
        //
        $order = isset($_GET['order']) ? addslashes($_GET['order']) : $order;
        $order_by = isset($_GET['order_by']) ? addslashes($_GET['order_by']) : $order_by;
        //echo $order . ' ' . $order_by . '<br>';

        if(isset($order_by) && $order_by != '') {
            //order parameter
            if($order_by == 'view_count' || $order_by == 'download_count' || $order_by == 'package_size_b'){
                $params['meta_key'] = '__wpdm_' . $order_by;
                $params['orderby'] = 'meta_value_num';
            }
            else {
                $params['orderby'] = $order_by;
            }
            if($order == '') $order = 'ASC';
            $params['order'] = $order;

        }

        if($category)
            $params['tax_query'] = array(array(
                'taxonomy' => 'wpdmcategory',
                'field' => 'id',
                'terms' => $category
            ));



        if($src!=''){
            $params['s'] = esc_sql($src);
            $params['meta_query'] = array(
                'relation' => 'OR',
                array(
                    'key'     => '__wpdm_files',
                    'value'   => $src,
                    'compare' => 'LIKE',
                ),
                array(
                    'key'     => '__wpdm_fileinfo',
                    'value'   => $src,
                    'compare' => 'LIKE',
                ),
                'meta_compare' => 'OR'
            );
        }

        $q = new WP_Query($params);
        //echo $wpdb->last_query;
        //precho($q);

        $total = $q->found_posts;

        $pages = ceil($total/$item_per_page);
        $pag = new \WPDM\libs\Pagination();
        $pag->changeClass('wpdm-ap-pag');
        $pag->items($total);
        $pag->limit($item_per_page);
        $pag->currentPage($page);
        $url = strpos($_SERVER['REQUEST_URI'],'?')?$_SERVER['REQUEST_URI'].'&':$_SERVER['REQUEST_URI'].'?';
        $url = preg_replace("/\&cp=[0-9]+/","",$url);
        $pag->urlTemplate($url."cp=[%PAGENO%]");

        $html = '';
        $role = @array_shift(array_keys($current_user->caps));

        while ($q->have_posts()){ $q->the_post();
            $package_role = maybe_unserialize(get_post_meta(get_the_ID(), '__wpdm_access', true));
            //if(is_array($package_role) && !in_array('guest', $package_role) && !in_array($role, $package_role)) {
            //    continue;
            // }
            $ext = "_blank";
            $data = wpdm_custom_data(get_the_ID());
            $data += get_post(get_the_ID(), ARRAY_A);
            $data['download_count'] = isset($data['download_count'])?$data['download_count']:0;
            $data['ID'] = get_the_ID();
            $data['id'] = get_the_ID();
            if(isset($data['files'])&&count($data['files'])>0){
                $tmpvar = $data['files'];
                $tmpvar = array_shift($tmpvar);
                $tmpvar = explode(".",$tmpvar);
                $ext = count($tmpvar) > 1 ? end($tmpvar) : $ext;
            }

            $link_label = isset($data['link_label'])?stripslashes($data['link_label']):'Download';

            $data['page_url'] = get_permalink(get_the_ID());



            $templates = maybe_unserialize(get_option("_fm_link_templates",true));


            $data['files'] = isset($data['files']) ? maybe_unserialize($data['files']):array();


            if(isset($templates[$actpl]['content'])&&$templates[$actpl]['content']!='') $actpl = $templates[$actpl]['content'];

            $repeater = \WPDM\Package::fetchTemplate($actpl, $data, 'link');

            $html .= $repeater;





            //}



        }
        // $html = term_description($category, 'wpdmcategory').$html;
        if($total==0) $html = __('No download found!','wpdmap');
        echo str_replace(array("\r","\n"),"","$html<div class='clear'></div>".$pag->show()."<div class='clear'></div>");
        die();
    }

    /**
     * @param array $params
     * @return array|null|WP_Post
     */
    function SeachBar($params = array()){
        @extract($params);
        $dir = dirname(__FILE__);
        $url = WP_PLUGIN_URL . '/' . basename($dir);
        $extra_search = (isset($_GET['search'])) ? array_map_recursive('stripslashes',$_GET['search']) : array();
        $src = isset($_GET['q']) ? esc_attr($_GET['q']): '' ;
        //if($extra_search)        print_r($extra_search);
        $button_style = isset($button_style)?$button_style:'default';
        $link_template = isset($link_template)?$link_template:'';
        $cols = isset($cols)&&$cols>0?$cols:2;
        $cols = 'col-md-'.intval(12/$cols);
        ob_start();
        include wpdm_tpl_path("advanced-search-form.php", __DIR__.'/tpls/');
        $search_form = ob_get_clean();

        ob_start();
        include wpdm_tpl_path("advanced-search-result.php", __DIR__.'/tpls/');
        $search_result = ob_get_clean();

        if(!isset($position) || $position == '' || $position == 'top') return "<div class='w3eden'>{$search_form}{$search_result}</div>";

        if($position == 'left') return "<div class='w3eden'><div class='row'><div class='col-md-4 col-full-inner'>{$search_form}</div><div class='col-md-8'>{$search_result}</div></div></div>";

        if($position == 'right') return "<div class='w3eden'><div class='row'><div class='col-md-8'>{$search_result}</div><div class='col-md-4 col-full-inner'>{$search_form}</div></div></div>";

    }

    /**
     * @param array $params
     * @return array|null|WP_Post
     */
    function SimpleSeachBar($params = array()){
        global $wpdb;
        @extract($params);
        $link_template = isset($template)?$template:'link-template-calltoaction3';
        $items_per_page = isset($items_per_page)?$items_per_page:0;
        update_post_meta(get_the_ID(),"__wpdm_link_template",$link_template);
        update_post_meta(get_the_ID(),"__wpdm_items_per_page",$items_per_page);


        $pluginsurl = plugins_url();
        $comcat = '';
        $sw = 6;
        ob_start();
       ?>
        <div class='w3eden'>
        <form id="srcp" style="margin-bottom: 10px">
            <input type="text" class="form-control input-lg" style="border-radius: 3px;background-position: 16px 11px;padding-left: 50px !important;" name="src" placeholder="<?php _e('Search Package','wpdmap'); ?>" id="src">
        </form>

        <div style='clear: both;'>
        <div  class='wpdm-downloads' id='wpdm-downloads'>

        </div></div></div>
        <script>
            function htmlEncode(value){
                return jQuery('<div/>').text(value).html();
            }

            jQuery('#srcp').submit(function(e){
                e.preventDefault();
                jQuery('.wpdm-cat-link').removeClass('active');

                jQuery('#inp').html('Search Result For <b>'+htmlEncode(jQuery('#src').val())+'</b>');
                jQuery('#wpdm-downloads').prepend('<div class="wpdm-loading"><i class="fa fa-spin fa-spinner icon icon-spin icon-spinner"></i>  <?php _e('Loading','wpdmap'); ?>...</div>').load('<?php echo  home_url('/?wpdmtask=get_downloads&pg='.get_the_ID().'&search='); ?>'+encodeURIComponent(jQuery('#src').val() ));

            });

            jQuery('body').on('click', '.pagination a',function(e){
                e.preventDefault();
                jQuery('#wpdm-downloads').prepend('<div class="wpdm-loading"><i class="fa fa-spin fa-spinner icon icon-spin icon-spinner"></i> Loading...</div>').load(this.href);
                return false;
            });


        </script>
       <?php
        $html = ob_get_clean();
        return str_replace(array("\r","\n"),"",$html);

    }

    function LinkTemplateOption(){
        ?>
        <tr>
            <td>Link Template for Archive Page</td>
            <td><select name="_wpdm_ap_search_page_template" id="ltac">
                    <?php
                    $actpl = get_option("_wpdm_ap_search_page_template",'link-template-default.php');
                    $ctpls = scandir(WPDM_BASE_DIR.'/tpls/link-templates/');
                    array_shift($ctpls);
                    array_shift($ctpls);
                    foreach($ctpls as $ctpl){
                        $tmpdata = file_get_contents(WPDM_BASE_DIR.'/tpls/link-templates/'.$ctpl);
                        if(preg_match("/WPDM[\s]+Link[\s]+Template[\s]*:([^\-\->]+)/",$tmpdata, $matches)){
                            ?>
                            <option value="<?php echo $ctpl; ?>"  <?php echo ( $actpl==$ctpl )?' selected ':'';  ?>><?php echo $matches[1]; ?></option>
                            <?php
                        }}

                    $templates = unserialize(get_option("_fm_link_templates",true));

                    foreach($templates as $id=>$template) {
                        ?>
                        <option value="<?php echo $id; ?>"  <?php echo ( $actpl==$id )?' selected ':'';  ?>><?php echo $template['title']; ?></option>
                    <?php }  ?>
                </select></td>
        </tr>
        <?php

    }

    /**
     * @usage Get Child Categories
     */
    function GetChildCats(){
        if(isset($_REQUEST['wpdmtask']) && $_REQUEST['wpdmtask'] == 'wpdm_ap_get_child_cats'){
            $bu = get_terms('wpdmcategory', array('hide_empty'=> false, 'parent'=>(int)$_REQUEST['parent']));
            echo (count($bu)>0)?'<option value="">Select</option>':'<option value="">Nothing here</option>';
            foreach($bu as $term){
                echo "<option value='{$term->term_id}'>{$term->name}</option>";
            }

            die();
        }
    }

    /**
     * @usage Callback function for short-code [wpdm_tags]
     * @param array $params
     * @return string
     */
    function Tags($params = array()){
        global $wpdb;
        @extract($params);
        $parent = isset($parent)?$parent:0;
        $args = array(
            'orderby'       => 'name',
            'order'         => 'ASC',
            'hide_empty'    => false,
            'exclude'       => array(),
            'exclude_tree'  => array(),
            'include'       => array(),
            'number'        => '',
            'fields'        => 'all',
            'slug'          => '',
            'parent'         => $parent,
            'hierarchical'  => true,
            'child_of'      => 0,
            'get'           => '',
            'name__like'    => '',
            'pad_counts'    => false,
            'offset'        => '',
            'search'        => '',
            'cache_domain'  => 'core'
        );
        $categories = get_terms('post_tag',$args);
        $pluginsurl = plugins_url();
        $cols = isset($cols)&&$cols>0?$cols:2;
        $scols = intval(12/$cols);
        $icon = isset($icon)?"<i class='fa fa-{$icon}'></i>":"<i class='fa fa-tag'></i>";
        $btnstyle = isset($btnstyle)?$btnstyle:'success';
        $k = 0;
        $html = "<div  class='wpdm-all-categories wpdm-categories-{$cols}col'><ul class='row'>";
        foreach($categories as $id=>$category){
            $catlink = get_term_link($category);
            if($category->parent==$parent) {
                $ccount = $category->count;
                if(isset($showcount)&&$showcount) $count  = "&nbsp;<span class='wpdm-count'>($ccount)</span>";
                $html .= "<div class='col-md-{$scols} col-tag'><a class='btn btn-{$btnstyle} btn-block text-left' href='$catlink' >{$icon} &nbsp; ".htmlspecialchars(stripslashes($category->name))."</a></div>";
                $k++;
            }
        }

        $html .= "</ul><div style='clear:both'></div></div><style>.col-tag{ margin-bottom: 10px !important; } .col-tag .btn{ text-align: left !important; padding-left: 10px !important; box-shadow: none !important; }</style>";
        if($k==0) $html = '';
        return "<div class='w3eden'>".str_replace(array("\r","\n"),"",$html)."</div>";
    }

    /**
     * @param $id
     * @param bool|false $taxonomy
     * @param bool|false $link
     * @param string $separator
     * @param bool|false $nicename
     * @param array $visited
     * @return array|mixed|null|object|string|WP_Error
     */
    function GetCustomCategoryParents( $id, $taxonomy = false, $link = false, $separator = '/', $nicename = false, $visited = array() ) {

        if(!($taxonomy && is_taxonomy_hierarchical( $taxonomy )))
            return '';

        $chain = '';
        // $parent = get_category( $id );
        $parent = get_term( $id, $taxonomy);
        if ( is_wp_error( $parent ) )
            return $parent;

        if ( $nicename )
            $name = $parent->slug;
        else
            $name = $parent->name;

        if ( $parent->parent && ( $parent->parent != $parent->term_id ) && !in_array( $parent->parent, $visited ) ) {
            $visited[] = $parent->parent;
            // $chain .= get_category_parents( $parent->parent, $link, $separator, $nicename, $visited );
            $chain .= $this->GetCustomCategoryParents( $parent->parent, $taxonomy, $link, $separator, $nicename, $visited );
        }

        if ( $link ) {
            // $chain .= '<a href="' . esc_url( get_category_link( $parent->term_id ) ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $parent->name ) ) . '">'.$name.'</a>' . $separator;
            $chain .= '<a href="' . esc_url( get_term_link( (int) $parent->term_id, $taxonomy ) ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s","wpdmap" ), $parent->name ) ) . '">'.$name.'</a>' . $separator;
        } else {
            $chain .= $name.$separator;
        }
        return $chain;
    }

    /**
     * @usage Save Parent ID of Current Category
     */
    function ChangeCatParent(){
        $cat_id = isset($_REQUEST['cat_id']) ? (int) $_REQUEST['cat_id'] : '';
        $result['type'] = 'failed';
        if(is_numeric($cat_id)) {
            $result['type'] = 'success';

            $parents = rtrim($this->GetCustomCategoryParents($cat_id,'wpdmcategory',false,'>',false),'>');
            $temp = explode('>', $parents);
            //print_r($temp);
            $count = count($temp);
            $str = "";
            for($i = 1; $i<=$count ; $i++){
                if($i == $count) {
                    $str .= "{$temp[$i-1]}";
                }
                else {

                    $parent = get_term_by('name', $temp[$i-1], 'wpdmcategory');
                    //print_r($parent);
                    $link = get_term_link($parent);
                    //print_r($link);
                    $a = "<a class='wpdm-cat-link2' rel='{$parent->term_id}' test_rel='{$parent->term_id}' title='{$parent->description}' href='$link'>{$parent->name}</a> > ";
                    $str .= $a;
                }
            }
            $result['parent'] = $str;

        }

        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $result = json_encode($result);
            echo $result;
        }
        else {
            header("Location: ".$_SERVER["HTTP_REFERER"]);
        }

        die();
    }

    /**
     * @usage Add short-code generator function with tinymce button add-on
     */
    function MCEButtonHelper(){
        ?>
        <style>#apc_chosen{ width: 100% !important; } #plnk_tpl_ap_chosen{ width: 140px !important; } #apms_chosen{ width: 200px !important; }</style>
        <div class="panel panel-default">
            <div class="panel-heading">Archive Page</div>
            <div class="panel-body">
                <div style="display: inline-block;width: 250px">
                <?php wpdm_dropdown_categories('c',0, 'apc'); ?>
                </div>
                <select style="margin-left: 5px;" id="catvw_ap">
                    <option value="extended">Cat. View:</option>
                    <option value="hidden">Hidden</option>
                    <option value="compact">Compact</option>
                    <option value="extended">Extended</option>
                    <option value="sidebar">Sidebar</option>
                </select>
                <select style="margin-right: 5px;" id="btns_ap">
                    <option value="default">Button:</option>
                    <option value="default">Default</option>
                    <option value="success">Success</option>
                    <option value="primary">Primary</option>
                    <option value="warning">Warning</option>
                    <option value="danger">Danger</option>
                    <option value="info">Info</option>
                    <option value="inverse">Inverse</option>
                </select>
                <div style="clear: both;margin-bottom: 5px"></div>

                <?php echo \WPDM\admin\menus\Templates::Dropdown(array('id'=>'plnk_tpl_ap')); ?>
                <select id="acob" style="margin-right: 5px;width: 100px">
                    <option value="post_title">Order By:</option>
                    <option value="post_title">Title</option>
                    <option value="download_count">Downloads</option>
                    <option value="package_size_b">Package Size</option>
                    <option value="view_count">Views</option>
                    <option value="date">Publish Date</option>
                    <option value="modified">Update Date</option>
                </select><select id="acobs" style="margin-right: 5px">
                    <option value="asc">Order:</option>
                    <option value="asc">Asc</option>
                    <option value="desc">Desc</option>
                </select>
                <button class="btn btn-primary" id="acps">Insert to Post</button>
                <script>
                    jQuery('#acps').click(function(){

                        var cats = jQuery('#apc').val()!='-1'?' category="' + jQuery('#apc').val() + '" ':'';
                        var bts = ' button_style="' + jQuery('#btns_ap').val() + '" ';
                        var catvw = ' cat_view="' + jQuery('#catvw_ap').val() + '" ';
                        var linkt = ' link_template="' + jQuery('#plnk_tpl_ap').val() + '" ';
                        var acob = ' order_by="' + jQuery('#acob').val() + '" order="' + jQuery('#acobs').val() + '"';
                        var win = window.dialogArguments || opener || parent || top;
                        win.send_to_editor('[wpdm-archive' + cats + catvw + bts + linkt + acob + ' items_per_page="10"]');
                        tinyMCEPopup.close();
                        return false;
                    });
                </script>
            </div>
            <div class="panel-heading">Categories</div>
            <div class="panel-body">
                <select id="spc" style="margin-right: 5px">
                    <option value="1">Package Count:</option>
                    <option value="1">Show</option>
                    <option value="0">Hide</option>
                </select><select id="ssc" style="margin-right: 5px">
                    <option value="1">Sub Cats:</option>
                    <option value="1">Show</option>
                    <option value="0">Hide</option>
                </select><select id="apcols" style="margin-right: 5px">
                    <option value="3">Cols:</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select>
                <button class="btn btn-primary" id="apcts">Insert to Post</button>
                <script>
                    jQuery('#apcts').click(function(){

                        var scats =' subcat="' + jQuery('#ssc').val() + '" ';
                        var count = ' showcount="' + jQuery('#spc').val() + '" ';
                        var cols = ' cols="' + jQuery('#apcols').val() + '" ';
                        var win = window.dialogArguments || opener || parent || top;
                        win.send_to_editor('[wpdm-categories' + scats + count + cols + ']');
                        tinyMCEPopup.close();
                        return false;
                    });
                </script>
            </div>
            <div class="panel-heading">More...</div>
            <div class="panel-body">
                <select id="apms" style="margin-right: 5px">
                    <option value="" disabled="disabled" selected="selected">More Shortcodes...</option>
                    <option value='[wpdm-tags cols="4" icon="tag"  btnstyle="default"]'>Tags</option>
                    <option value='[wpdm_search_page cols="1" items_per_page="10" link_template="link-template-calltoaction4" position="top"]'>Advanced Search ( Top )</option>
                    <option value='[wpdm_search_page cols="1" items_per_page="10" link_template="link-template-calltoaction4" position="left"]'>Advanced Search ( Left )</option>
                    <option value='[wpdm_search_page cols="1" items_per_page="10" link_template="link-template-calltoaction4" position="right"]'>Advanced Search ( Right )</option>
                </select>
                <button class="btn btn-primary" id="apmsb">Insert to Post</button>
                <script>
                    jQuery('#apmsb').click(function(){
                        var win = window.dialogArguments || opener || parent || top;
                        win.send_to_editor(jQuery('#apms').val());
                        tinyMCEPopup.close();
                        return false;
                    });
                </script>
            </div>
        </div>

        <?php
    }

    /**
     * @usage Add Styles and Scripts in WP Head
     */
    function WPHead(){

    global $post;

    ?>
<style type="text/css">
    .w3eden .bootstrap-select span.filter-option{ background: transparent !important; }
    div.w3eden .wpdm-all-categories div.cat-div{
        margin-bottom: 10px;
    }
    div.w3eden .wpdm-all-categories a.wpdm-pcat{
        font-weight: 800;
    }
    div.w3eden .wpdm-all-categories a.wpdm-scat{
        font-weight: 400;
        font-size: 9pt;
        margin-right: 10px;
        opacity: 0.6;
    }
    div.w3eden .wpdm-categories ul li,
    div.w3eden .wpdm-downloads ul li{
        list-style: none !important;
        list-style-type: none !important;
    }

    div.w3eden .wpdm-categories ul{
        list-style: none!important;
        padding: 0px !important;
        margin: 0px !important;
    }

    .dropdown-menu li,
    .w3eden .wpdm-downloads-ul li,
    .w3eden .wpdm-all-categories ul{
        padding: 0px !important;
        margin: 0px !important;
    }
    .w3eden .wpdm-categories ul.row li.col-md-4{
        margin-bottom: 5px !important;
        margin-left: 0 !important;
        padding: 4px;

    }
    .w3eden .btn-group.ap-btn > a:first-child:not(.btn-block){
        width: 82%;;
    }
    .wpdm-categories .dropdown-menu{
        width: 98%;
        left: 1%;
        box-shadow: 0 0px 4px rgba(0,0,0,0.2);
        font-size: 9pt;

    }
    .w3eden .wpdm-categories  .dropdown-menu > li > a{
        padding: 10px 20px !important;
        border-bottom: 1px solid rgba(0,0,0,0.07) !important;
        color: #333333;
    }
    .w3eden .wpdm-categories  .dropdown-menu > li:last-child > a{
        border-bottom: 0 !important;
    }

    .wpdm-count{
        font-size:12px;
        color:#888;
    }
    #wpdm-downloads *{
        font-size: 10pt;
    }

    .w3eden #src{
        box-shadow: none;
        padding-left: 35px;
        background: url("data:image/png;base64,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") 7px 5px no-repeat;
        background-size: 20px;
    }


    .w3eden .btn-ddm{
        position: absolute;right: 4px;border:0 !important;
        -webkit-border-top-left-radius: 0px !important;-webkit-border-bottom-left-radius: 0px !important;-moz-border-radius-topleft: 0px !important;-moz-border-radius-bottomleft: 0px !important;border-top-left-radius: 0px !important;border-bottom-left-radius: 0px !important;
        background:rgba(0,0,0,0.2) !important; color: #ffffff !important;
    }

    .w3eden .panel-footer img{
        max-height: 30px;
    }

    .dropdown-submenu{position:relative;}
    .dropdown-submenu>.dropdown-menu{top:0;left:100%;margin-top:-6px;margin-left:-1px;-webkit-border-radius:0 6px 6px 6px;-moz-border-radius:0 6px 6px 6px;border-radius:0 6px 6px 6px;}
    .dropdown-submenu:hover>.dropdown-menu{display:block;}
    .dropdown-submenu>a:after{display:block;content:" ";float:right;width:0;height:0;border-color:transparent;border-style:solid;border-width:5px 0 5px 5px;border-left-color:#cccccc;margin-top:5px;margin-right:-10px;}
    .dropdown-submenu:hover>a:after{border-left-color:#ffffff;}
    .dropdown-submenu.pull-left{float:none;}.dropdown-submenu.pull-left>.dropdown-menu{left:-100%;margin-left:10px;-webkit-border-radius:6px 0 6px 6px;-moz-border-radius:6px 0 6px 6px;border-radius:6px 0 6px 6px;}
    .dropdown-menu li a{ padding: 7px 20px !important; }
</style>
<?php if(is_object($post)&&!strpos($post->post_content,'wpdm-archive')&&!strpos($post->post_content,'wpdm_archive')) return; ?>

<script language="JavaScript">

    function htmlEncode(value){
        return jQuery('<div/>').text(value).html();
    }

    jQuery(function(){

        jQuery('body').on('click', '.pagination a',function(e){
            e.preventDefault();
            jQuery('#wpdm-downloads').prepend('<div class="wpdm-loading"><i class="fa fa-spin fa-spinner icon icon-spin icon-spinner"></i> Loading...</div>').load(this.href);
        });

        jQuery('.wpdm-cat-link').click(function(e){
            e.preventDefault();
            jQuery('.wpdm-cat-link').removeClass('active');
            jQuery(this).addClass('active');

            var cat_id = jQuery(this).attr('rel');
            jQuery.ajax({
                type : "post",
                dataType : "json",
                url : '<?php echo admin_url('admin-ajax.php'); ?>',
                data : {action: "wpdm_change_cat_parent", cat_id : cat_id},
                success: function(response) {
                    console.log(response);
                    if(response.type == "success") {
                        jQuery('#inp').html(response.parent);
                    }
                }
            });

            jQuery('#wpdm-downloads').prepend('<div class="wpdm-loading"><i class="fa fa-spin fa-spinner icon icon-spin icon-spinner"></i>  <?php _e('Loading','wpdmap'); ?>...</div>').load('<?php echo home_url('/?wpdmtask=get_downloads&pg='.get_the_ID().'&category=');?>'+this.rel + '&order_by=' + jQuery('#order_by').val() +'&order=' + jQuery('#order').val());

        });

        jQuery('#wpdm-cats-compact').on('change',function(e){

            var cat_id = jQuery(this).val();
            if(cat_id == -1) cat_id = 0;
            jQuery('#initc').val(cat_id);
            jQuery.ajax({
                type : "post",
                dataType : "json",
                url : '<?php echo admin_url('admin-ajax.php'); ?>',
                data : {action: "wpdm_change_cat_parent", cat_id : cat_id},
                success: function(response) {
                    console.log(response);
                    if(response.type == "success") {
                        jQuery('#inp').html(response.parent);
                    }
                }
            });

            jQuery('#wpdm-downloads').prepend('<div class="wpdm-loading"><i class="fa fa-spin fa-spinner icon icon-spin icon-spinner"></i>  <?php _e('Loading','wpdmap'); ?>...</div>').load('<?php echo home_url('/?wpdmtask=get_downloads&pg='.get_the_ID().'&category=');?>'+cat_id + '&order_by=' + jQuery('#order_by').val() +'&order=' + jQuery('#order').val());

        });

        jQuery('body').on('click', '.wpdm-cat-link2', function(e){

            e.preventDefault();
            jQuery('.wpdm-cat-link').removeClass('active');
            var new_rel = jQuery(this).attr('test_rel');
            if( new_rel !== 'undefined') {
                jQuery('a[rel=' + new_rel + ']').addClass('active');
            }

            var cat_id = jQuery(this).attr('rel');
            jQuery.ajax({
                type : "post",
                dataType : "json",
                url : '<?php echo admin_url('admin-ajax.php'); ?>',
                data : {action: "wpdm_change_cat_parent", cat_id : cat_id},
                success: function(response) {
                    console.log(response);
                    if(response.type == "success") {
                        jQuery('#inp').html(response.parent)
                    }
                }
            });


            jQuery('#wpdm-downloads').prepend('<div class="wpdm-loading"><i class="fa fa-spin fa-spinner icon icon-spin icon-spinner"></i>  <?php _e('Loading','wpdmap'); ?>...</div>').load('<?php echo home_url('/?wpdmtask=get_downloads&pg='.get_the_ID().'&category=');?>'+this.rel + '&order_by=' + jQuery('#order_by').val() +'&order=' + jQuery('#order').val());

        });

        jQuery('#order_by, #order').on('change',function(){
            jQuery('#wpdm-downloads').prepend('<div class="wpdm-loading"><i class="fa fa-spin fa-spinner icon icon-spin icon-spinner"></i>  <?php _e('Loading','wpdmap'); ?>...</div>').load('<?php echo home_url('/?wpdmtask=get_downloads&pg='.get_the_ID().'&category=');?>'+jQuery('#initc').val() + '&search=' + encodeURIComponent(jQuery('#src').val() ) + '&order_by=' + jQuery('#order_by').val() +'&order=' + jQuery('#order').val());
        });


        jQuery('#srcp').submit(function(e){
            e.preventDefault();
            jQuery('.wpdm-cat-link').removeClass('active');

            jQuery('#inp').html('Search Result For <b>'+htmlEncode(jQuery('#src').val())+'</b>');
            jQuery('#wpdm-downloads').prepend('<div class="wpdm-loading"><i class="fa fa-spin fa-spinner icon icon-spin icon-spinner"></i>  <?php _e('Loading','wpdmap'); ?>...</div>').load('<?php echo home_url('/?wpdmtask=get_downloads&pg='.get_the_ID().'&search=');?>'+encodeURIComponent(jQuery('#src').val() )+'&category='+jQuery('#initc').val() + '&order_by=' + jQuery('#order_by').val() +'&order=' + jQuery('#order').val() );

        });
        jQuery('#wpdmap-home').click(function(e){
            e.preventDefault();
            jQuery('.wpdm-cat-link').removeClass('active');
            jQuery('#inp').html('All Packages');
            jQuery('#wpdm-downloads').prepend('<div class="wpdm-loading"><i class="fa fa-spin fa-spinner icon icon-spin icon-spinner"></i> <?php _e('Loading','wpdmap'); ?>...</div>').load('<?php echo home_url('/?wpdmtask=get_downloads&pg='.get_the_ID().'&search=');?>'+encodeURIComponent(jQuery('#src').val()) +'&category='+jQuery('#initc').val() + '&order_by=' + jQuery('#order_by').val() +'&order=' + jQuery('#order').val());
        });
        jQuery('#wpdm-downloads').prepend('<div class="wpdm-loading"><i class="fa fa-spin fa-spinner icon icon-spin icon-spinner"></i> <?php _e('Loading','wpdmap'); ?>...</div>').load('<?php echo home_url('/?wpdmtask=get_downloads&pg='.get_the_ID().'&category=');?>'+encodeURIComponent(jQuery('#initc').val()));



    });

</script>
<?php
}



}


if(!class_exists('WPDM_SearchWidget')){

    class WPDM_SearchWidget extends WP_Widget {
    /** constructor */
    function __construct() {
        parent::__construct(false, 'WPDM Search');
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
        $url = get_permalink($instance['rpage']);
        ?>
        <?php echo $before_widget; ?>
        <?php if ( $title )
            echo $before_title . $title . $after_title;
        echo "<form action='".$url."' class='wpdm-pro'>";
        echo "<div class='input-append'><input type=text name='q' /><button class='btn'><i class='icon icon-search'></i>".__("Search","wpdmap")."</button></div><div class='clear'></div>";
        echo "</form>";
        echo $after_widget; ?>
        <?php
    }

    /** @see WP_Widget::update */
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['rpage'] = strip_tags($new_instance['rpage']);
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
    $title = esc_attr($instance['title']);
    $rpage = esc_attr($instance['rpage']);

    ?>
    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        <?php echo __("Search Result Page","wpdmap").":<br/>".wp_dropdown_pages("selected={$rpage}&echo=0&name=".$this->get_field_name('rpage'));  ?>
    </p>
    <div style="border:1px solid #cccccc;padding:10px;border-radius:4px;font-size:8pt">
        <?php _e("Note: Create a page with short-code <code>[wpdm_search_page]</code> and select that page as search redult page", "wpdmap");?>
    </div>
<?php
}

}
}

if(!function_exists('array_map_recursive')) {
    function array_map_recursive($callback, $value){
        if (is_array($value)) {
            return array_map(function($value) use ($callback) { return array_map_recursive($callback, $value); }, $value);
        }
        return $callback($value);
    }
}


new WPDM_ArchivePage();