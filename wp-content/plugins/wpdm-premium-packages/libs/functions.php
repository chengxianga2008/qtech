<?php

function wpdmpp_popular_files($start, $limit)
{
    global $wpdb;
    $files = $wpdb->get_results("select *, sum(oi.price) as price_total from {$wpdb->prefix}ahm_orders o inner join {$wpdb->prefix}ahm_order_items oi on oi.oid=o.order_id inner join {$wpdb->prefix}posts p on oi.pid=p.ID where p.post_type='wpmarketplace'and o.payment_status='Completed'  group by  oi.pid order by price_total desc limit $start, $limit");

    return $files;
}

//number of popular files
function wpdmpp_total_popular_files()
{
    global $wpdb;
    $files = $wpdb->get_var("select distinct count(distinct pid) from {$wpdb->prefix}ahm_orders o inner join {$wpdb->prefix}ahm_order_items oi on oi.oid=o.order_id inner join {$wpdb->prefix}posts p on oi.pid=p.ID where p.post_type='wpmarketplace' and o.payment_status='Completed'");

    return $files;
}

//number of total sales
function wpdmpp_total_purchase($pid = '')
{
    global $wpdb;
    if (!$pid) $pid = get_the_ID();
    $sales = $wpdb->get_var("select count(*) from {$wpdb->prefix}ahm_orders o, {$wpdb->prefix}ahm_order_items oi where oi.oid=o.order_id and oi.pid='$pid' and o.payment_status='Completed'");
    return $sales;
}

function get_wpdmpp_option($name, $default = '')
{
    global $wpdmpp_settings;
    $name = explode('/', $name);

    if (count($name) == 1)
        return isset($wpdmpp_settings[$name[0]]) ? $wpdmpp_settings[$name[0]] : $default;
    else if (count($name) == 2)
        return isset($wpdmpp_settings[$name[0]]) && isset($wpdmpp_settings[$name[0]][$name[1]]) ? $wpdmpp_settings[$name[0]][$name[1]] : $default;
    else if (count($name) == 3)
        return isset($wpdmpp_settings[$name[0]]) && isset($wpdmpp_settings[$name[0]][$name[1]]) && isset($wpdmpp_settings[$name[0]][$name[1]][$name[2]]) ? $wpdmpp_settings[$name[0]][$name[1]][$name[2]] : $default;
    else
        return $default;
}

//the function for adding the product from the frontend
function wpdmpp_add_product()
{
    if (isset($_POST['__product_wpmp']) && wp_verify_nonce($_POST['__product_wpmp'], 'wpmp-product') && $_POST['task'] == '') { //echo "here";exit;
        if ($_POST['post_type'] == "wpmarketplace") {
            global $current_user, $wpdb;
            get_currentuserinfo();
            $settings = get_option('_wpdmpp_settings');
            $pstatus = $settings['fstatus'] ? $settings['fstatus'] : "draft";
            $my_post = array(
                'post_title' => $_POST['product']['post_title'],
                'post_content' => $_POST['product']['post_content'],
                'post_excerpt' => $_POST['product']['post_excerpt'],
                'post_status' => $pstatus,
                'post_author' => $current_user->ID,
                'post_type' => "wpmarketplace"

            );

            if ($_POST['id']) {
                //update post
                $my_post['ID'] = $_REQUEST['id'];
                wp_update_post($my_post);
                $postid = $_REQUEST['id'];
            } else {
                //insert post
                $postid = wp_insert_post($my_post);
            }


            update_post_meta($postid, "wpdmpp_list_opts", $_POST['wpdmpp_list']);

            //set the product type
            wp_set_post_terms($postid, $_POST['product_type'], "ptype");

            foreach ($_POST['wpdmpp_list'] as $k => $v) {
                update_post_meta($postid, $k, $v);

            }


            if ($_POST['wpdmpp_list']['fimage']) {
                $wp_filetype = wp_check_filetype(basename($_POST['wpdmpp_list']['fimage']), null);
                $attachment = array(
                    'post_mime_type' => $wp_filetype['type'],
                    'post_title' => preg_replace('/\.[^.]+$/', '', basename($_POST['wpdmpp_list']['fimage'])),
                    'post_content' => '',
                    'guid' => $_POST['wpdmpp_list']['fimage'],
                    'post_status' => 'inherit'
                );
                $attach_id = wp_insert_attachment($attachment, $_POST['wpdmpp_list']['fimage'], $postid);

                set_post_thumbnail($postid, $attach_id);
            }

            //send admin email
            if ($pstatus == "draft") {
                //get user emai
                global $current_user;
                get_currentuserinfo();
                mail($current_user->user_email, "New Product Added", "Your product is successfully added and is waiting to admin review. You will be notified if your product is accepetd or rejected.");

                //now send notification to site admin about newly added product
                $admin_email = get_bloginfo('admin_email');
                mail($admin_email, "Product Review", "New Product is added by user " . $current_user->user_login . ". Please review this product to add your store.");

                //add a new post meta to identify only drafted post
                if (!update_post_meta($postid, '_z_user_review', '1')) {
                    add_post_meta($postid, '_z_user_review', '1', true);
                }
            }

        }

        header("Location: " . $_SERVER['HTTP_REFERER']);
        die();
    }
}


//Send notification before delete product
//add_action( 'before_delete_post', 'notify_product_rejected' );
add_action('wp_trash_post', 'notify_product_rejected');
function notify_product_rejected($post_id)
{
    global $post_type;
    if ($post_type != 'wpmarketplace') return;

    $post = get_post($post_id);
    $post_meta = get_post_meta($post_id, "_z_user_review", true);
    if ($post_meta != ""):

        $author = get_userdata($post->post_author);
        $author_email = $author->user_email;
        $email_subject = "Your product has been rejected.";

        ob_start(); ?>

        <html>
        <head>
            <title>New post at <?php bloginfo('name') ?></title>
        </head>
        <body>
        <p>
            Hi <?php echo $author->user_firstname ?>,
        </p>

        <p>
            Your product <?php the_title() ?> has been rejected.
        </p>
        </body>
        </html>

        <?php

        $message = ob_get_contents();

        ob_end_clean();

        wp_mail($author_email, $email_subject, $message);
    endif;

}

// Product accept notification email
function notify_product_accepted($post_id)
{

    //only my custom post type
    global $post_type;
    if ($post_type != 'wpmarketplace') return;

    //echo "<pre>";    print_r($_POST); echo "</pre>";
    if (($_POST['post_status'] == 'publish') && ($_POST['original_post_status'] != 'publish')) {
        $post = get_post($post_id);
        $post_meta = get_post_meta($post_id, "_z_user_review", TRUE);
        if ($post_meta != ""):

            $author = get_userdata($post->post_author);
            $author_email = $author->user_email;
            $email_subject = "Your post has been published.";

            ob_start(); ?>

            <html>
            <head>
                <title>New post at <?php bloginfo('name') ?></title>
            </head>
            <body>
            <p>
                Hi <?php echo $author->user_firstname ?>,
            </p>

            <p>
                Your product <a href="<?php echo get_permalink($post->ID) ?>"><?php the_title_attribute() ?></a> has
                been published.
            </p>
            </body>
            </html>

            <?php

            $message = ob_get_contents();

            ob_end_clean();

            wp_mail($author_email, $email_subject, $message);
        endif;
    }
    //wpmarket@wpmarketplaceplugin.com
}



///for withdraw request
function wpdmpp_withdraw_request()
{
    global $wpdb, $current_user;

    $uid = $current_user->ID;

    if (isset($_POST['withdraw'], $_POST['withdraw_amount']) && $_POST['withdraw'] == 1 && $_POST['withdraw_amount'] > 0) {

        $wpdb->insert(
            "{$wpdb->prefix}ahm_withdraws",
            array(
                'uid' => $uid,
                'date' => time(),
                'amount' => $_POST['withdraw_amount'],
                'status' => 0
            ),
            array(
                '%d',
                '%d',
                '%f',
                '%d'
            )
        );
        if (wpdm_is_ajax()) {
            _e("Withdraw Request Sent!", "wpmarketplace");
            die();
        }
        header("Location: " . $_SERVER['HTTP_REFERER']);
        die();
    }

}



function wpdmpp_redirect($url)
{
    if (!headers_sent())
        header("location: " . $url);
    else
        echo "<script>location.href='{$url}';</script>";
    die();
}

function wpdmpp_js_redirect($url)
{

    echo "&nbsp;Redirecting...<script>location.href='{$url}';</script>";
    die();
}


function wpdmpp_members_page()
{
    $settings = get_option('_wpdmpp_settings');
    return get_permalink($settings['members_page_id']);
}

function wpdmpp_orders_page($part = '')
{
    $settings = get_option('_wpdmpp_settings');
    $url = get_permalink($settings['orders_page_id']);
    if ($part != '') {
        if (strpos($url, '?')) $url .= "&" . $part;
        else $url .= "?" . $part;
    }

    $udbpage = get_option('__wpdm_user_dashboard', 0);
    if($udbpage) {
        $udbpage = get_permalink($udbpage);
        $url = $udbpage."purchases/orders/";
        if($part != ''){
            $part = explode("=", $part);
            $url = $udbpage . "purchases/order/" . end($part) . "/";
        }
    }
    return $url;
}

function wpdmpp_guest_order_page($part = '')
{
    $settings = get_option('_wpdmpp_settings');
    $url = get_permalink($settings['guest_order_page_id']);
    if ($part != '') {
        if (strpos($url, '?')) $url .= "&" . $part;
        else $url .= "?" . $part;
    }
    return $url;
}

function wpdmpp_cart_page($part = '')
{
    $settings = get_option('_wpdmpp_settings');
    $url = get_permalink($settings['page_id']);
    if ($part != '') {
        if (strpos($url, '?')) $url .= "&" . $part;
        else $url .= "?" . $part;
    }
    return $url;
}

function wpdmpp_continue_shopping_url($part = '')
{
    $settings = get_option('_wpdmpp_settings');
    return $settings['continue_shopping_url'];
}

function wpdmpp_billing_info_form(){
    global $current_user;
    $billing = maybe_unserialize(get_user_meta($current_user->ID, 'user_billing_shipping', true));
    $billing = isset($billing['billing'])?$billing['billing']:array();
    include wpdm_tpl_path('billing-info.php', WPDMPP_BASE_DIR.'/tpls/');
}

function wpdmpp_save_billing_info(){
    global $current_user;
    if(isset($_POST['checkout']) && isset($_POST['checkout']['billing'])){
        update_user_meta($current_user->ID, 'user_billing_shipping', serialize($_POST['checkout']));
    }
}

function wpdmpp_get_purchased_items(){
    if(!isset($_GET['wpdmppaction']) || $_GET['wpdmppaction'] != 'getpurchaseditems') return;
    $user = wp_signon(array('user_login' => $_GET['user'], 'user_password' => $_GET['pass']));
    if($user->ID) wp_set_current_user($user->ID);
    if(is_user_logged_in())
        echo json_encode(Order::getPurchasedItems());
    else
        echo json_encode(array('error' => '<a href="http://www.wpdownloadmanager.com/user-dashboard/?redirect_to=[redirect]">You need to login first!</a>'));
    die();
}

/**
 * Retrienve Site Commissions on User's Sales
 * @param null $uid
 * @return mixed
 */
function wpdmpp_site_commission($uid = null)
{
    global $current_user;
    $user = $current_user;
    if ($uid) $user = get_userdata($uid);
    $comission = get_option("wpdmpp_user_comission");
    $comission = $comission[$user->roles[0]];
    return $comission;
}


function wpdmpp_get_user_earning()
{

}


function wpdmpp_user_dashboard()
{
    $data = "";
    include(WPDMPP_BASE_DIR . '/tpls/dashboard.php');
    return $data;
}


function wpdmpp_product_price($pid)
{
    $base_price = get_post_meta($pid, "__wpdm_base_price", true);
    $sales_price = get_post_meta($pid, "__wpdm_sales_price", true);
    $price = floatval($sales_price) > 0 && $sales_price < $base_price ? $sales_price : $base_price;
    if (floatval($price) == 0) return number_format(0, 2, ".", "");
    return number_format($price, 2, ".", "");
}

function wpdmpp_all_products($params)
{
    include(WPDMPP_BASE_DIR . 'tpls/catalog.php');

}


function wpdmpp_is_ajax()
{
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'
    ) return TRUE;
    return false;
}


function wpdmpp_all_feature_products($params)
{
    include(WPDMPP_BASE_DIR . 'tpls/catalog_feature.php');

}

//delete product from front-end
function wpdmpp_delete_product()
{
    if (is_user_logged_in() && isset($_GET['dproduct'])) {
        global $current_user;
        $pid = intval($_GET['dproduct']);
        $pro = get_post($pid);

        if ($current_user->ID == $pro->post_author) {
            wp_update_post(array('ID' => $pid, 'post_status' => 'trash'));
            $settings = get_option('_wpdmpp_settings');
            if ($settings['frontend_product_delete_notify'] == 1) {
                wp_mail(get_option('admin_email'), "I had to delete a product", "Hi, Sorry, but I had to delete following product for some reason:<br/>{$pro->post_title}", "From: {$current_user->user_email}\r\nContent-type: text/html\r\n\r\n");
            }
            $_SESSION['dpmsg'] = 'Product Deleted';
            header("location: " . $_SERVER['HTTP_REFERER']);
            die();
        }
    }
}

function wpdmpp_order_completed_mail()
{

}

function wpdmpp_head()
{
    ?>

    <script language="JavaScript">
        <!--
        var wpdmpp_base_url = '<?php echo plugins_url('/wpdm-premium-packages/'); ?>';
        jQuery(function () {
            //jQuery('.wpmp-thumbnails a').lightBox({fixedNavigation:true});
        });
        //-->
    </script>

    <?php
}

function wpdmpp_product_report_scripts()
{
    wp_enqueue_script(
        'flot',
        WP_PLUGIN_URL . '/wpdm-premium-packages/js/jquery.flot.js',
        array('jquery')
    );
    wp_enqueue_script(
        'float-resize',
        WP_PLUGIN_URL . '/wpdm-premium-packages/js/jquery.flot.resize.js',
        array('jquery')
    );
    wp_enqueue_script(
        'float-time',
        WP_PLUGIN_URL . '/wpdm-premium-packages/js/jquery.flot.time.js',
        array('jquery')
    );
    $path = WP_PLUGIN_URL . '/wpdm-premium-packages/js/excanvas.min.js';
    echo '<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="' . $path . '"></script><![endif]-->';
    //
    /*
    add_action('wp_head',function(){
        $path = WP_PLUGIN_URL . '/wpdm-premium-packages/js/excanvas.min.js';
        echo '<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="'.$path.'"></script><![endif]-->';
    });
     */
}

function wpdmpp_product_report_styles()
{
    wp_enqueue_style(
        'float-css',
        WP_PLUGIN_URL . '/wpdm-premium-packages/css/admin/product_report.css'
    );
}

add_action("wp_ajax_wpdmpp_delete_frontend_order", "wpdmpp_delete_frontend_order");
add_action("wp_ajax_nopriv_wpdmpp_delete_frontend_order", "wpdmpp_delete_frontend_order");

function wpdmpp_delete_frontend_order()
{

    if (!wp_verify_nonce($_REQUEST['nonce'], "delete_order")) {
        exit("No naughty business please");
    }

    $result['type'] = 'failed';
    global $wpdb;
    $order_id = esc_attr($_REQUEST['order_id']);
    $ret = $wpdb->query(
        $wpdb->prepare(
            "
            DELETE FROM {$wpdb->prefix}ahm_orders
             WHERE order_id = %s
            ", $order_id
        )
    );
    if ($ret) {
        //echo $ret;
        $ret = $wpdb->query(
            $wpdb->prepare(
                "
            DELETE FROM {$wpdb->prefix}ahm_order_items
             WHERE oid = %s
            ", $order_id
            )
        );
        //echo $ret;
        if ($ret)
            $result['type'] = 'success';
    }


    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $result = json_encode($result);
        echo $result;
    } else {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }

    die();
}

function wpdmpp_plugin_active($plugin = "wpdm-premium-packages/wpmarketplace.php")
{
    //$ret =  get_option( 'active_plugins', array() );
    //print_r($ret);
    return in_array($plugin, (array)get_option('active_plugins', array()));
}

//wpdmpp_plugin_active();


function wpdmpp_recalculate_sales()
{
    if (!isset($_POST['id'])) return;
    global $wpdb;
    $id = (int)$_POST['id'];
    $sql = "select sum(quantity*price) as sales_amount, sum(quantity) as sales_quantity from {$wpdb->prefix}ahm_order_items oi, {$wpdb->prefix}ahm_orders o where oi.oid = o.order_id and oi.pid = {$id} and o.order_status IN ('Completed', 'Expired')";
    $data = $wpdb->get_row($sql);
    header('Content-type: application/json');
    update_post_meta($id, '__wpdm_sales_amount', $data->sales_amount);
    update_post_meta($id, '__wpdm_sales_count', $data->sales_quantity);
    $data->sales_amount = wpdmpp_currency_sign() . floatval($data->sales_amount);
    $data->sales_quantity = intval($data->sales_quantity);
    echo json_encode($data);
    die();

}

function wpdmpp_effective_price($pid)
{
    $base_price = get_post_meta($pid, "__wpdm_base_price", true);
    $sales_price = get_post_meta($pid, "__wpdm_sales_price", true);
    $price = intval($sales_price) > 0 ? $sales_price : $base_price;
    if(!$price) $price = 0;
    return number_format($price, 2);
}

function wpdmpp_currency_sign()
{
    $settings = get_option('_wpdmpp_settings');
    $currency = isset($settings['currency']) ? $settings['currency'] : 'USD';
    $cdata = Currencies::GetCurrency($currency);
    $sign = is_array($cdata) ? $cdata['symbol'] : '$';
    return $sign;
}

function wpdmpp_currency_code()
{
    $settings = get_option('_wpdmpp_settings');
    $currency = isset($settings['currency']) ? $settings['currency'] : 'USD';
    return $currency;
}


function wpdmpp_validate_download($package)
{
    global $current_user, $wpdb;

    get_currentuserinfo();
    $order = new Order();
    //if(wpdm_premium_user($package['id'])) return $package;         
    $price = wpdmpp_product_price($package['ID']);
    if (floatval($price) == 0) return $package;
    $oid = isset($_GET['oid']) ? $_GET['oid'] : "";
    $ord = $order->getOrder($oid);
    if (($oid == "" || !is_object($ord)) && $price > 0) wp_die('You do not have permission to download this file');

    $settings = get_option('_wpdmpp_settings');
    $order = new Order();
    $odata = $order->GetOrder($_GET['oid']);
    $items = unserialize($odata->items);

    if (@in_array($_GET['wpdmdl'], $items)
        && isset($_GET['oid'])
        && $_GET['oid'] != ''
        && !is_user_logged_in()
        && $odata->uid == 0
        && $odata->order_status == 'Completed'
        && isset($settings['guest_download'])
        && isset($_SESSION['guest_order'])) {
        //for guest download
        return $package;

    }

    if ((is_user_logged_in() && $current_user->ID != $ord->uid && $price > 0) || (!is_user_logged_in() && $price > 0)) wp_die('You do not have permission to download this file');
    return $package;
}

function wpdmpp_assign_user_2order()
{
    if (isset($_REQUEST['assignuser']) && isset($_REQUEST['order'])) {
        $u = get_user_by('login', $_REQUEST['assignuser']);
        $order = new Order();
        $order->Update(array('uid' => $u->ID), $_REQUEST['order']);
        die('Done!');
    }
}

function wpdmpp_download_order_note_attachment()
{
    global $current_user;
    if (!isset($_GET['_atcdl']) || !is_user_logged_in()) return;
    $key = WPDM_Crypt::Decrypt($_GET['_atcdl']);
    $key = explode("|||", $key);
    $order = new Order($key[0]);
    if ($order->Uid != $current_user->ID && !current_user_can('manage_options')) wp_die('Unauthorized Access');
    $files = $order->OrderNotes['messages'][$key[1]]['file'];
    $filename = preg_replace("/^[0-9]+?wpdm_/", "", $key[2]);
    if (in_array($key[2], $files)) {
        wpdm_download_file(UPLOAD_DIR . $key[2], $filename);
        die();
    }
}

function wpdmpp_tpl_dir(){
    //if(get_stylesheet_directory().'/download-manager/') return get_stylesheet_directory().'/download-manager/';
    //else
        return WPDMPP_BASE_DIR."/tpls/";
}