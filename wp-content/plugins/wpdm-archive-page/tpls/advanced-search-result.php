<div class="row">
    <?php

    if(isset($_GET['q'])) {

        global $wpdb, $current_user;
        get_currentuserinfo();


        $tax_query = array();

        //date meta
        if ($extra_search['publish_date'] != '') {
            $publish_dates = explode(' to ', $extra_search['publish_date']);
            $tax_query['date_query'][] = array(
                'column' => 'post_date_gmt',
                'after' => $publish_dates[0],
                'before' => $publish_dates[1],
                'inclusive' => true,
            );
        }

        if ($extra_search['update_date'] != '') {
            $update_dates = explode(' to ', $extra_search['update_date']);
            $tax_query['date_query'][] = array(
                'column' => 'post_modified_gmt',
                'after' => $update_dates[0],
                'before' => $update_dates[1],
                'inclusive' => true,
            );
        }


        //post meta query
        if ($extra_search['view_count'] != '' || $extra_search['view_count'] > 0) {
            $tax_query['meta_query'][] = array(
                'key' => '__wpdm_view_count',
                'value' => $extra_search['view_count'],
                'compare' => '>='
            );
        }

        if ($extra_search['download_count'] != '' || $extra_search['download_count'] > 0) {
            $tax_query['meta_query'][] = array(
                'key' => '__wpdm_download_count',
                'value' => $extra_search['download_count'],
                'compare' => '>='
            );
        }

        if ($extra_search['package_size'] != '' || $extra_search['package_size'] > 0) {
            $tax_query['meta_query'][] = array(
                'key' => '__wpdm_package_size_b',
                'value' => $extra_search['package_size'],
                'compare' => '>='
            );
        }

        //order parameter
        if ($extra_search['order_by'] != '') {
            if ($extra_search['order_by'] != 'modified' && $extra_search['order_by'] != 'date') {
                $tax_query['meta_key'] = $extra_search['order_by'];
                $tax_query['orderby'] = 'meta_value_num';
            } else {
                $tax_query['orderby'] = $extra_search['order_by'];
            }

            $tax_query['order'] = $extra_search['order'];


        }

        //category parameter
        if (isset($extra_search['category']) && !empty($extra_search['category'])) {
            $tax_query['tax_query'][] = array(
                'taxonomy' => 'wpdmcategory',
                'field' => 'term_id',
                'terms' => $extra_search['category'],
                'operator' => 'IN',
                'include_children' => false
            );
        }

        //search parameter

        if ($src != '') {
            $tax_query['s'] = $src;
            $tax_query['meta_query'] = array(
                array(
                    'key'     => '__wpdm_files',
                    'value'   => $src,
                    'compare' => 'LIKE',
                ),
                array(
                    'key'     => '__wpdm_fileinfo',
                    'value'   => $src,
                    'compare' => 'LIKE',
                )
            );

        }

        //template select
        $pg = isset($_GET['pg']) ? (int)$_GET['pg'] : 0;
        $actpl = get_option('_wpdm_ap_search_page_template', 'link-template-default.php');

        if ($link_template != '') $actpl = $link_template;

        //post_type and pagination parameter
        $items_per_page = !isset($items_per_page) || $items_per_page <= 0 ? 10 : $items_per_page;
        $page = isset($_GET['cp']) ? $_GET['cp'] : 1;
        $start = ($page - 1) * $items_per_page;
        $tax_query["post_type"] = "wpdmpro";
        $tax_query["posts_per_page"] = $items_per_page;
        $tax_query["offset"] = $start;

        $q = new WP_Query($tax_query);

        $total = $q->found_posts;

        //pagination
        $pages = ceil($total / $items_per_page);
        $pag = new \WPDM\libs\Pagination();
        $pag->changeClass('wpdm-ap-pag');
        $pag->items($total);
        $pag->limit($items_per_page);
        $pag->currentPage($page);
        $url = strpos($_SERVER['REQUEST_URI'], '?') ? $_SERVER['REQUEST_URI'] . '&' : $_SERVER['REQUEST_URI'] . '?';
        $url = preg_replace("/\&cp=[0-9]+/", "", $url);
        $pag->urlTemplate($url . "cp=[%PAGENO%]");

        $html = '';

        $role = @array_shift(array_keys($current_user->caps));

        while ($q->have_posts()) {
            $q->the_post();
            $package_role = maybe_unserialize(get_post_meta(get_the_ID(), '__wpdm_access', true));
            if (is_array($package_role) && !in_array('guest', $package_role) && !in_array($role, $package_role)) {
                continue;
            }

            $ext = "_blank";
            $data = wpdm_custom_data(get_the_ID());
            $data += get_post(get_the_ID(), ARRAY_A);

            $data['download_count'] = isset($data['download_count']) ? $data['download_count'] : 0;
            $data['ID'] = get_the_ID();
            $data['id'] = get_the_ID();
            if (isset($data['files']) && count($data['files']) > 0) {
                $tmpvar = explode(".", $data['files'][0]);
                $ext = count($tmpvar) > 1 ? end($tmpvar) : $ext;
            }

            $link_label = isset($data['link_label']) ? stripslashes($data['link_label']) : 'Download';

            $data['page_url'] = get_permalink(get_the_ID());


            $role = @array_shift(array_keys($current_user->caps));
            $templates = maybe_unserialize(get_option("_fm_link_templates", true));


            $data['files'] = isset($data['files']) ? maybe_unserialize($data['files']) : array();


            if (isset($templates[$actpl]['content']) && $templates[$actpl]['content'] != '') $actpl = $templates[$actpl]['content'];

            $repeater = FetchTemplate($actpl, $data, 'link');

            $html .= "<div class='{$cols}'>" . $repeater . "</div>";

        }

        if ($total == 0) $html = __('No download found!','wpdmap');
        echo str_replace(array("\r", "\n"), "", "$html<div class='clear'></div>" . $pag->show() . "<div class='clear'></div>");
    }
    ?>
</div>