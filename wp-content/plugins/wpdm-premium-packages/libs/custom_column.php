<?php

add_filter('manage_edit-wpmarketplace_columns', 'add_new_wpmarketplace_columns');
function add_new_wpmarketplace_columns($wpmarketplace_columns){
    //echo "<pre>"; print_r($wpmarketplace_columns); echo "</pre>";
    //die();
    
    $new_columns['cb'] = '<input type="checkbox" />';
    $new_columns['title'] = __('Title', 'wpmarketplace');
    $new_columns['author'] = __('Author','wpmarketplace');
    $new_columns['ptype'] = __('Category','wpmarketplace');
    $new_columns['no_of_sale'] = __('Sales Qty','wpmarketplace');
    $new_columns['total_income'] = __('Total Sales','wpmarketplace');
    $new_columns['total_dl'] = __('Downloads',"wpmarketplace");
    $new_columns['graph'] = __('Sales Graph','wpmarketplace');
    $new_columns['comments'] = __('<span class="vers"><div title="Comments" class="comment-grey-bubble"></div></span>','wpmarketplace');
    $new_columns['date'] = __('Date', 'wpmarketplace');
 
    return $new_columns;
    
}

add_action('manage_wpmarketplace_posts_custom_column', 'manage_wpmarketplace_columns', 10, 2);
function manage_wpmarketplace_columns($column_name, $id) {
    global $wpdb;
    /*
    $count = 0;
    $income = 0;
    $query = "select * from `{$wpdb->prefix}ahm_order_items` where pid=$id";
    $result = $wpdb->get_results( $query, ARRAY_A );
    $currency_sign = wpdmpp_currency_sign();
    if($result){
        //$count = $wpdb->num_rows;
        foreach ($result as $row){
            $order_id = $row['oid'];
            $get_res = $wpdb->get_row( "SELECT * FROM `{$wpdb->prefix}ahm_orders` where order_id='$order_id' and payment_status='Completed'",ARRAY_A );
            if(!empty($get_res)){
                $income += $get_res['total'];
                $count+= $row['quantity'];
            }
        }
    }
     */
    global $wpdb;
    $currency_sign = wpdmpp_currency_sign();
    $oitems = $wpdb->get_results("select * from {$wpdb->prefix}ahm_order_items where pid='{$id}'");
    $income = 0;
    $count = 0;
    $meta = get_post_meta($id, 'wpdmpp_list_opts', true);
    $discount_r = 0; //discount is not included here.
    foreach ($oitems as $oitem) {
        $order1 = new Order();
        $order = $order1->getOrder($oitem->oid);
        if($order->payment_status !='Completed'){
            continue;
        }
        $count += $oitem->quantity;
        $order->items = unserialize($order->items);
        $cart_data = unserialize($order->cart_data);
        

        $prices = 0;
        $variations = "";
        //print_r(get_post_meta($oitem->pid,"wpdmpp_list_opts",true));

        @extract($meta);
        foreach ($variation as $key => $value) {
            foreach ($value as $optionkey => $optionvalue) {
                if ($optionkey != "vname") {
                    if (isset($cart_data[$oitem->pid]['variation']) && $cart_data[$oitem->pid]['variation'] != "") {
                        //echo "adfadf";
                        foreach ($cart_data[$oitem->pid]['variation'] as $var) {
                            //echo  $optionkey;                  
                            if ($var == $optionkey) {
                                $prices+=$optionvalue['option_price'];
                                $variations.=$optionvalue['option_name'] . "=" . $optionvalue['option_price'] . " ";
                            }
                        }
                    }
                }
            }
        }

        $discount=($discount_r/100)*(($oitem->price+$prices)*$oitem->quantity);
        $income += number_format(((($oitem->price + $prices) * $oitem->quantity) - $discount - $oitem->coupon_amount), 2,".","");
    }
        switch ($column_name) {
    case 'no_of_sale':
        update_post_meta($id,"_wpdmpp_product_no_of_sale",$count);
        echo "$count";
            break;
 
    case 'total_income':
        update_post_meta($id,"_wpdmpp_product_total_income",$income);
        echo "{$currency_sign}{$income}"; 
        break;
    case 'graph':
        echo "<a href='edit.php?post_type=wpmarketplace&page=product-report&post_id=$id'>View Graph</a>";
        break;
    
    case 'ptype':
        global $post;
        $terms = get_the_terms( $id, 'ptype' );
        /* If terms were found. */
        if ( !empty( $terms ) ) {

                $out = array();

                /* Loop through each term, linking to the 'edit posts' page for the specific term. */
                foreach ( $terms as $term ) {
                        $out[] = sprintf( '<a href="%s">%s</a>',
                                esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'ptype' => $term->slug ), 'edit.php' ) ),
                                esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'ptype', 'display' ) )
                        );
                }

                /* Join the terms, separating them with a comma. */
                echo join( ', ', $out );
        }

        /* If no terms were found, output a default message. */
        else {
                _e( '--' );
        }

        break;
        
    case 'total_dl':
        $dl = get_post_meta( $id, 'wpdmpp_product_dl', true );
        if($dl != "") {
            echo $dl;
        }
        else {
            echo "0";
        }
        break;
    default:
        break;
    } // end switch
}   



// Make these columns sortable

//manage_wpmarketplace_posts_custom_column
function wpdmpp_sortable_columns($columns) {
    $columns['total_income'] = 'total_income';
    $columns['no_of_sale'] = "no_of_sale";
    //$columns['total_dl'] = "total_dl";
    return $columns;
}
add_filter( "manage_edit-wpmarketplace_sortable_columns", "wpdmpp_sortable_columns" );

/* Only run our customization on the 'edit.php' page in the admin. */
function wpdmpp_sortable_columns_load() {
    add_filter('request', 'wpdmpp_sortable_columns_sort');
}
add_action( 'load-edit.php', 'wpdmpp_sortable_columns_load' );

function wpdmpp_sortable_columns_sort($vars) {

    /* Check if we're viewing the 'movie' post type. */
    if (isset($vars['post_type']) && 'wpmarketplace' == $vars['post_type']) {

        /* Check if 'orderby' is set to 'duration'. */
        if (isset($vars['orderby']) && 'total_income' == $vars['orderby']) {

            /* Merge the query vars with our custom variables. */
            $vars = array_merge(
                    $vars, array(
                'meta_key' => '_wpdmpp_product_total_income',
                'orderby' => 'meta_value_num'
                    )
            );
        } else if (isset($vars['orderby']) && 'no_of_sale' == $vars['orderby']) {

            /* Merge the query vars with our custom variables. */
            $vars = array_merge(
                    $vars, array(
                'meta_key' => '_wpdmpp_product_no_of_sale',
                'orderby' => 'meta_value_num'
                    )
            );
        }
        /*
        else if (isset($vars['orderby']) && 'total_dl' == $vars['orderby']) {

            // Merge the query vars with our custom variables. 
            $vars = array_merge(
                    $vars, array(
                'meta_key' => 'wpdmpp_product_dl',
                'orderby' => 'meta_value_num'
                    )
            );
        }
        */
    }

    return $vars;
}
