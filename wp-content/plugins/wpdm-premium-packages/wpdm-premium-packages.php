<?php
/*
Plugin Name:  WPDM - Premium Packages
Plugin URI: http://www.wpdownloadmanager.com/download/premium-package/
Description: Complete solution for selling digital products
Author: Shaon
Version: 3.3.5
Author URI: http://wpdownloadmanager.com/
*/

if(!isset($_SESSION))
    session_start();

define('WPDMPP_BASE_DIR', dirname(__FILE__).'/');
define('WPDMPP_BASE_URL', plugins_url('wpdm-premium-packages/'));
define('WPDMPP_MENU_ACCESS_CAP', 'manage_categories');
define('WPDMPP_ADMIN_CAP', 'manage_categories');

define('WPDMPP_Version', '3.3.5');

function wpdmpp_languages()
{
    load_plugin_textdomain('wpmarketplace', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

if(defined('WPDM_BASE_DIR')){

include(dirname(__FILE__) . "/libs/license-manager.php");
include(dirname(__FILE__) . "/libs/functions.php");
include(dirname(__FILE__) . "/libs/class.order.php");
include(dirname(__FILE__) . "/libs/class.payment.php");
include(dirname(__FILE__) . "/libs/class.CustomActions.php");
include(dirname(__FILE__) . "/libs/class.CustomColumns.php");
include(dirname(__FILE__) . "/libs/class.Currencies.php");
include(dirname(__FILE__) . "/libs/cart.php");
include(dirname(__FILE__) . "/libs/print_invoice.php");
include(dirname(__FILE__) . "/libs/install.php");
include(dirname(__FILE__) . "/widget.php");
include(dirname(__FILE__) . "/libs/custom_user_info.php");
include(dirname(__FILE__) . "/libs/custom_column.php");
include(dirname(__FILE__) . "/featureset/functions.php");

  //  if(class_exists('CommonVers')) { echo dirname(__FILE__) . "/libs/class.payment.php";die(); }

//auto load default payment mothods
global $payment_methods, $wpdmpp_settings;
$pdir = WPDMPP_BASE_DIR . "libs/payment_methods/";
$methods = scandir($pdir, 1);
//array_shift($methods);
//array_shift($methods);
foreach ($methods as $method) {
    if ($method != "." && $method != "..") {
        if (file_exists($pdir . $method . "/class.{$method}.php")) {
            $payment_methods[] = $method;
            include_once($pdir . $method . "/class.{$method}.php");
        }
    }


}

    $wpdmpp_settings = maybe_unserialize(get_option('_wpdmpp_settings'));

global $sap; //seperator
if (function_exists('get_option')) {
    if (get_option('permalink_structure') != '') $sap = '?';
    else $sap = "&";
}


//returns live preview url
function wpdmpp_live_preview()
{

}

//returns screen shots url
function wpdmpp_screen_shots()
{

}

//pricing meta box
function wpdmpp_meta_box_pricing()
{
    global $post;
    include(dirname(__FILE__) . '/tpls/metaboxes/pricing.php');

}

//pricing, icon, tax, stock metabox called from here
function wpdmpp_meta_boxes($tabs)
{

    if(is_admin())
    $tabs['pricing'] = array('name' => __('Pricing & Discounts', "wpmarketplace"), 'callback' => 'wpdmpp_meta_box_pricing');
    return $tabs;

    $settings = maybe_unserialize(get_option('_wpdmpp_settings'));
    $meta_boxes = array(
        'wpmp-info' => array('title' => __('Pricing & Discounts', "wpmarketplace"), 'callback' => 'wpdmpp_meta_box_pricing', 'position' => 'normal', 'priority' => 'low'),
        'wpmp-tax-status' => array('title' => __('Tax', "wpmarketplace"), 'callback' => 'wpdmpp_meta_box_tax', 'position' => 'side', 'priority' => 'core'),
    );

    //check the settings to add stock metabox
    if (isset($settings['stock']['enable']) && $settings['stock']['enable'] == 1) {
        $meta_boxes['wpmp-stock'] = array('title' => __('Stock', "wpmarketplace"), 'callback' => 'wpdmpp_meta_box_stock', 'position' => 'side', 'priority' => 'core');
    }

    $meta_boxes = apply_filters("wpdmpp_meta_box", $meta_boxes);
    foreach ($meta_boxes as $id => $meta_box) {
        extract($meta_box);
        add_meta_box($id, $title, $callback, 'wpdmpro', $position, $priority);
    }
}


//tax metabox
function wpdmpp_meta_box_tax()
{
    $settings = maybe_unserialize(get_option('_wpdmpp_settings'));
    global $post;
    @extract(get_post_meta($post->ID, "wpdmpp_list_opts", true));
    //echo "<script>alert('$tax_status');</script>";
    ?>
    <label><?php echo __("Tax Status", "wpmarketplace"); ?></label> <select id="mng_tax" name="wpdmpp_list[tax_status]">
    <option <?php if (isset($tax_status) && $tax_status == "taxable") echo 'selected="selected"'; ?>
        value="taxable"><?php echo __("Taxable", "wpmarketplace"); ?></option>
    <option <?php if (isset($tax_status) && $tax_status == "shipping") echo 'selected="selected"'; ?>
        value="shipping"><?php echo __("Shipping only", "wpmarketplace"); ?></option>
    <option <?php if (isset($tax_status) && $tax_status == "") echo 'selected="selected"'; ?>
        value=""><?php echo __("None", "wpmarketplace"); ?></option>
</select><br/>
    <div id="">
        <label><?php echo __("Tax Class", "wpmarketplace"); ?></label>
        <?php
        $tax_classes = $settings['tax']['tax_class'];
        $textAr = explode("\n", $tax_classes);
        ?>
        <select name="wpdmpp_list[tax_class]"><?php echo $stock_qty; ?>"
            <option value=""><?php echo __("Standard Rate", "wpmarketplace"); ?></option>
            <?php
            foreach ($textAr as $class) {
                if ($tax_class == $class) $sele = 'selected=selected'; else $sele = "";

                echo '<option value="' . $class . '" ' . $sele . '>' . __($class, "wpmarketplace") . '</option>';
            }
            ?>
        </select>
    </div>
<?php
}

function wpdmpp_save_meta_data($postid, $post)
{

    if (isset($_POST['post_author'])) {
        $userinfo = get_userdata($_POST['post_author']);

        if ($userinfo->roles[0] != "administrator") {
            if ($_POST['original_post_status'] == "draft" && $_POST['post_status'] == "publish") {
                global $current_user;
                $siteurl = home_url("/");
                $admin_email = get_bloginfo("admin_email");
                $to = $userinfo->user_email; //post author
                $from = $current_user->user_email;
                $link = get_permalink($post->ID);
                $message = "Your product {$post->post_title} {$link} is approved to {$siteurl} ";
                $email['subject'] = $subject;
                $email['body'] = $message;
                $email['headers'] = 'From:  <' . $from . '>' . "\r\n";
                $email = apply_filters("product_approval_email", $email);
                wp_mail($to, $email['subject'], $email['body'], $email['headers']);
                //wp_mail($admin_email,$email['subject'],$email['body'],$email['headers']);
            }
        }
    }
}

//marketplace settings
function wpdmpp_settings()
{
    include("settings/settings.php");
}

function wpdmpp_invoice()
{
    if (isset($_GET['id']) && $_GET['id'] != '' && isset($_GET['wpdminvoice'])) {
        include(WPDMPP_BASE_DIR . 'tpls/wpdmpp-invoice.php');
        die();
    }
}

//orders list section
function wpdmpp_orders()
{
    if(!current_user_can(WPDMPP_MENU_ACCESS_CAP)) return;
    $order1 = new Order();
    global $wpdb;
    //$wpdb->show_errors();
    $l = 15;
    $currency_sign = get_option('_wpdmpp_curr_sign', '$');

    //if(isset($_GET['paged'])) {
    $p = isset($_GET['paged']) ? $_GET['paged'] : 1;
    $s = ($p - 1) * $l;
    //}
//        echo "<pre>";
//        print_r($_REQUEST);
//        echo "</pre>";
    if (isset($_GET['task']) && $_GET['task'] == 'vieworder') {
        $order = $order1->getOrder($_GET['id']);
        include('tpls/view-order.php');
    } else {
        if (isset($_GET['task']) && $_GET['task'] == 'delete_order') {
            $order_id = esc_attr($_GET['id']);
            $ret = $wpdb->query(
                $wpdb->prepare(
                    "
                        DELETE FROM {$wpdb->prefix}ahm_orders
                         WHERE order_id = %s
                        ",
                    $order_id
                )
            );
            if ($ret) {
                //echo $ret;
                $ret = $wpdb->query(
                    $wpdb->prepare(
                        "
                        DELETE FROM {$wpdb->prefix}ahm_order_items
                         WHERE oid = %s
                        ",
                        $order_id
                    )
                );
                //echo $ret;
                if ($ret) $msg = "Record Deleted for Order ID $order_id...";
            }

        } else if (isset($_GET['delete_selected'], $_GET['delete_confirm']) && $_GET['delete_confirm'] == 1) {
            $order_ids = $_GET['id'];
            if (!empty($order_ids) && is_array($order_ids)) {
                foreach ($order_ids as $key => $order_id) {
                    $order_id = esc_attr($order_id);
                    $ret = $wpdb->query(
                        $wpdb->prepare(
                            "
                                DELETE FROM {$wpdb->prefix}ahm_orders
                                 WHERE order_id = %s
                                ",
                            $order_id
                        )
                    );
                    if ($ret) {
                        //echo $ret;
                        $ret = $wpdb->query(
                            $wpdb->prepare(
                                "
                                DELETE FROM {$wpdb->prefix}ahm_order_items
                                 WHERE oid = %s
                                ",
                                $order_id
                            )
                        );
                        //echo $ret;
                        if ($ret) $msg[] = "Record Deleted for Order ID $order_id...";
                    }
                }
            }
        } else if (isset($_GET['delete_by_payment_sts'], $_GET['delete_all_by_payment_sts']) && $_GET['delete_all_by_payment_sts'] != "") {
            $payment_status = esc_attr($_GET['delete_all_by_payment_sts']);

            $order_ids = $wpdb->get_results(
                "
                                SELECT order_id 
                                FROM {$wpdb->prefix}ahm_orders
                                WHERE payment_status = '$payment_status'
                                "
                , ARRAY_A);
            if ($order_ids) {
                foreach ($order_ids as $row) {
                    //print_r($row);
                    $order_id = $row['order_id'];
                    $ret = $wpdb->query(
                        $wpdb->prepare(
                            "
                                DELETE FROM {$wpdb->prefix}ahm_orders
                                 WHERE order_id = %s
                                ",
                            $order_id
                        )
                    );
                    if ($ret) {
                        //echo $ret;
                        $ret = $wpdb->query(
                            $wpdb->prepare(
                                "
                                DELETE FROM {$wpdb->prefix}ahm_order_items
                                 WHERE oid = %s
                                ",
                                $order_id
                            )
                        );
                        //echo $ret;
                        if ($ret) $msg[] = "Record Deleted for Order ID $order_id...";
                    }
                }
            }


        }


        //$wpdb->print_error();
        if (isset($_REQUEST['oid']) && $_REQUEST['oid'])
            $qry[] = "order_id='$_REQUEST[oid]'";
        if (isset($_REQUEST['customer']) && intval($_REQUEST['customer'])>0)
            $qry[] = "uid='$_REQUEST[customer]'";
        if (isset($_REQUEST['ost']) && $_REQUEST['ost'])
            $qry[] = "order_status='$_REQUEST[ost]'";
        if (isset($_REQUEST['pst']) && $_REQUEST['pst'])
            $qry[] = "payment_status='$_REQUEST[pst]'";
        if (isset($_REQUEST['sdate'], $_REQUEST['edate']) && ($_REQUEST['sdate'] != '' || $_REQUEST['edate'] != '')) {
            $_REQUEST['edate'] = $_REQUEST['edate'] ? $_REQUEST['edate'] : $_REQUEST['sdate'];
            $_REQUEST['sdate'] = $_REQUEST['sdate'] ? $_REQUEST['sdate'] : $_REQUEST['edate'];
            $sdate = strtotime("$_REQUEST[sdate] 00:00:00");
            $edate = strtotime("$_REQUEST[edate] 23:59:59");
            $qry[] = "(`date` >=$sdate and `date` <=$edate)";
        }

        if (isset($qry))
            $qry = "where " . implode(" and ", $qry);
        else $qry = "";
        $t = $order1->totalOrders($qry);
        $orders = $order1->GetAllOrders($qry, $s, $l);
        include('tpls/orders.php');
    }
}



//frontend user profile
function wpdmpp_user_purchases()
{

    global $current_user, $_ohtml;
    get_currentuserinfo();
    $order = new Order();
    $myorders = $order->GetOrders($current_user->ID);
    $_ohtml = '';
    $dashboard = true;

    $wpdmpp_settings = get_option('_wpdmpp_settings');

    ob_start();
    ?>
    <div class="w3eden">
    <?php if(!is_user_logged_in()) {
        include(wpdm_tpl_path('wpdm-be-member.php'));
        ?>
        <?php if(isset($_SESSION['last_order']) && $_SESSION['last_order'] != '' && isset($wpdmpp_settings['guest_download']) && $wpdmpp_settings['guest_download'] == 1){ ?>
        <div class="panel panel-info" style="width: 300px;max-width: 98%;margin: 50px auto">
        <div class="panel-heading">Guest Order</div>
        <div class="panel-body">
        We strongly recommend your to signup/login to get access to your order and product support.
        But, if you don't want to signup now, please go to <a class="label label-primary" href="<?php echo wpdmpp_guest_order_page("orderid=".$_SESSION['last_order']); ?>">Guest Order</a> page
        </div>
        </div>
        <?php } ?>

<?php
    } else {

    include('tpls/orders_purchases.php');
    $content = $_ohtml;

    ?>
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tab1" data-toggle="tab"><?php _e('Orders',''); ?></a></li>
            <li><a href="#tab2" data-toggle="tab">Edit Profile</a></li>
        </ul>

        <div class="tab-content">
            <section class="tab-pane active" id="tab1">
                <?php echo $content; ?>
            </section>
            <section class="tab-pane" id="tab2">
                <?php include(dirname(__FILE__) . '/tpls/edit-profile.php'); ?>
            </section>
        </div>

    <?php }
    echo '</div>';
    $tabs = ob_get_clean();

    return $tabs;
}

function wpdmpp_purchased_items($params = array()){
    global $wpdb, $current_user;
    $uid = $current_user->ID;
    $purchased_items = $wpdb->get_results("select oi.*,o.date, o.order_status from {$wpdb->prefix}ahm_order_items oi,{$wpdb->prefix}ahm_orders o where o.order_id = oi.oid and o.uid = {$uid} and o.order_status IN ('Expired', 'Completed') order by `date` desc");
    ob_start();
    if(isset($params[2]) && $params[1] == 'order')
    include wpdm_tpl_path('order-details.php', WPDMPP_BASE_DIR.'/tpls/');
    else if(isset($params[1]) && $params[1] == 'orders')
    include wpdm_tpl_path('purchase-orders.php', WPDMPP_BASE_DIR.'/tpls/');
    else
    include wpdm_tpl_path('purchased-items.php', WPDMPP_BASE_DIR.'/tpls/');
    return ob_get_clean();
}

function wpdmpp_guest_orders(){
    ob_start();
    include dirname(__FILE__).'/tpls/guest-orders.php';
    return ob_get_clean();
}

function wpdmpp_process_guest_order(){
    global $post;

    if(isset($_POST['go'])) {

        if(!isset($_SESSION['guest_order_init'])) { $_SESSION['guest_order_init'] = uniqid(); die('nosess'); }

        $orderid = $_POST['go']['order'];
        $orderemail = $_POST['go']['email'];
        $o = new Order();
        $order = $o->GetOrder($orderid);

        if(!is_object($order) || !isset($order->order_id) || $order->order_id != $orderid) die('noordr');

        if(is_email($orderemail) && $orderemail == get_option("email_".$orderid, 0) && $order->uid <=0){
            $_SESSION['guest_order'] = $orderid;
            die('success');
        }
        if($order->uid >= 0) die('nogues');

        die('noordr');
    }

    if(is_object($post) && $post->ID == wpdmpp_guest_order_page() && !isset($_SESSION['guest_order_init']) && !isset($_POST['go'])) $_SESSION['guest_order_init'] = uniqid();

}

function wpdmpp_tabs($attrs, $content)
{
    $tabs = explode("|", $attrs['tabs']);
    $html = "<div class='wpmp-tab-container'><ul class='tabs'>";
    foreach ($tabs as $tab) {
        ++$tn;
        $html .= "<li><a href='#tab{$tn}'>{$tab}</a></li>\n";
    }
    $html .= "</ul>";
    $html .= '<div class="tab_container">';
    $tab_cons = explode("######", $content);
    foreach ($tab_cons as $con) {
        ++$tc;
        $html .= '<div id="tab' . $tc . '" class="tab_content">' . __($con, "wpmarketplace") . '</div>';
    }
    $html .= '</div></div>';
    return $html;
}

function wpdmpp_frontend_tabs($tabs){
    $tabs['sales'] = array('label'=>'Sales','shortcode' => '[wpdm-pp-earnings]');
    return $tabs;
}

function wpdmpp_extends()
{
    require_once WPDMPP_BASE_DIR . 'libs/extends.php';
}

function wpdmpp_extension_styles()
{
    //wp_enqueue_style('wp-marketplace', plugins_url() . '/wpdm-premium-packages/bootstrap/css/bootstrap.css');
    wp_enqueue_style('wp-extends-css', plugins_url() . '/wpdm-premium-packages/css/extends_page.css');
}

function wpdmpp_extension_scripts()
{

}


function wpdmpp_license()
{
    global $wpdb;
    $l = 15;

    $p = isset($_GET['paged']) ? $_GET['paged'] : 1;
    $s = ($p - 1) * $l;
    if (isset($_GET['task']) && $_GET['task'] == 'editlicense') {
        $lid = intval($_GET['id']);
        $license = $wpdb->get_row("select * from {$wpdb->prefix}ahm_licenses where id='{$lid}'");
        include('tpls/edit-license.php');
    } else {
        $qry = array();
        if (isset($_REQUEST['licenseno']))
            $qry[] = "licenseno='$_REQUEST[licenseno]'";
        if (isset($_REQUEST['oid']))
            $qry[] = "oid='$_REQUEST[oid]'";
        if (isset($_REQUEST['pid']))
            $qry[] = "pid='$_REQUEST[pid]'";
        if (count($qry) > 0)
            $qry = "and " . implode(" and ", $qry);
        else $qry = "";

        $t = $wpdb->get_var("select count(*) from {$wpdb->prefix}ahm_licenses where 1 $qry");
        $licenses = $wpdb->get_results("select l.*,f.post_title as productname from {$wpdb->prefix}ahm_licenses l,{$wpdb->prefix}posts f where l.pid=f.ID $qry limit $s, $l");
        //dd($t);
        include("tpls/manage-license.php");
    }
}

function wpdm_update_license()
{
    global $wpdb;
    if ($_GET['task'] != 'editlicense' || !is_array($_POST['license'])) return;
    $id = (int)$_POST['lid'];
    $lic = $_POST['license'];
    $lic = explode("\n", str_replace(array("\r", "http://", "www."), "", $lic['domain']));
    $lic['domain'] = trim(implode("",$lic))!=""?serialize($lic):"";
    $lic['activation_date'] = strtotime($lic['activation_date']);
    $lic['expire_date'] = $lic['activation_date'] + ($lic['expire_period'] * 86400);
    $wpdb->update("{$wpdb->prefix}ahm_licenses", $lic, array('id' => $id));
    header("location: edit.php?post_type=wpdmpro&page=pp-license");
    die();
}

//menus for the marketplace
function wpdmpp_menu()
{
    add_submenu_page('edit.php?post_type=wpdmpro', __('Payouts', "wpmarketplace"), __('Payouts', "wpmarketplace"), WPDMPP_MENU_ACCESS_CAP, 'payouts', 'wpdmpp_all_payouts');
    add_submenu_page('edit.php?post_type=wpdmpro', __('Orders &lsaquo; Marketplace', "wpmarketplace"), __('Orders', "wpmarketplace"), WPDMPP_MENU_ACCESS_CAP, 'orders', 'wpdmpp_orders');
    add_submenu_page('edit.php?post_type=wpdmpro', __('License Manager', "wpmarketplace"), __('License Manager', "wpmarketplace"), WPDMPP_MENU_ACCESS_CAP, 'pp-license', 'wpdmpp_license');

}

//payouts section
function wpdmpp_all_payouts()
{
    include_once("tpls/payouts.php");
}

//featured products
function wpdmpp_featured_product()
{
    global $wpdb;
    if (isset($_POST['task']) && $_POST['task'] == "add_feature") {
        //print_r($_POST);
        foreach ($_POST['fids'] as $fpid) {
            $wpdb->insert(
                "{$wpdb->prefix}ahm_feature_products",
                array(
                    'productid' => $fpid,
                    'startdate' => strtotime($_POST['sdate']),
                    'enddate' => strtotime($_POST['edate'])
                ),
                array(
                    '%d',
                    '%d',
                    '%d'
                )
            );
        }
    }
    $featured_products = $wpdb->get_results("select * from {$wpdb->prefix}ahm_feature_products fp inner join {$wpdb->prefix}posts p on p.ID=fp.productid where p.post_type='wpmarketplace' ");
    include_once('tpls/featured_products.php');
}

//admin settings options save
function wpdmpp_save_settings()
{

    update_option('_wpdmpp_settings', $_POST['_wpdmpp_settings']);

    die(__('Settings Saved Successfully', "wpmarketplace"));
}

function wpdmpp_download()
{
    if (!isset($_GET['wpdmdl']) || !isset($_GET['oid'])) return;

    if(wpdm_query_var('preact') == 'login'){
        $user = wp_signon(array('user_login' => wpdm_query_var('user'), 'user_password' => wpdm_query_var('pass') ));
        if(!$user->ID)
        die('Error!');
        else
        wp_set_current_user($user->ID);
    }

    global $wpdb, $current_user;
    $settings = get_option('_wpdmpp_settings');

    $order = new Order();
    $odata = $order->GetOrder($_GET['oid']);
    $items = unserialize($odata->items);

    if($odata->uid != $current_user->ID) wp_die(__("Dailing 911! You better run now!!","wpmarketplace"));
    if($odata->order_status == 'Expired') wp_die(__("Sorry! Support and Update Access Period is Already Expired","wpmarketplace"));

    $base_price = get_post_meta($_GET['wpdmdl'], '__wpdm_base_price', true);

    $package = get_post($_GET['wpdmdl'], ARRAY_A);
    $package['files'] = maybe_unserialize(get_post_meta($package['ID'], '__wpdm_files', true));
    $package['individual_file_download'] = maybe_unserialize(get_post_meta($package['ID'], '__wpdm_individual_file_download', true));

    if ($base_price == 0 && (int)$_GET['wpdmdl'] > 0) {
        //for free items
        include(WPDM_BASE_DIR . "/wpdm-start-download.php");
    }
    if (@in_array($_GET['wpdmdl'], $items) && $_GET['oid'] != '' && is_user_logged_in() && $current_user->ID == $odata->uid && $odata->order_status == 'Completed') {
        //for premium item
        include(WPDM_BASE_DIR . "/wpdm-start-download.php");
    }

    if (@in_array($_GET['wpdmdl'], $items)
        && isset($_GET['oid'])
        && $_GET['oid'] != ''
        && !is_user_logged_in()
        && $odata->uid == 0
        && $odata->order_status == 'Completed'
        && isset($settings['guest_download'])
        && isset($_SESSION['guest_order'])) {
            //for guest download
            include(WPDM_BASE_DIR . "/wpdm-start-download.php");

    }

}



//create new
function create_order()
{

    global $current_user;

    if(floatval(wpdmpp_get_cart_total()) <=0 ) return;

    $order = new Order();
    if (isset($_SESSION['orderid']) && $_SESSION['orderid'] != '') {
        $order_info = $order->GetOrder($_SESSION['orderid']);
        if ($order_info->order_id) {
            $data = array(
                'cart_data' => serialize(wpdmpp_get_cart_data()),
                'items' => serialize(array_keys(wpdmpp_get_cart_data()))
            );
            $order->UpdateOrderItems(wpdmpp_get_cart_data(), $_SESSION['orderid']);
            $insertid = $order->Update($data, $_SESSION['orderid']);
        } else {
            $cart_data = serialize(wpdmpp_get_cart_data());
            $items = serialize(array_keys(wpdmpp_get_cart_data()));
            //print_r($cart_data);
            $order->NewOrder($_SESSION['orderid'], "", $items, 0, $current_user->ID, 'Processing', 'Processing', $cart_data);
            $order->UpdateOrderItems($cart_data, $_SESSION['orderid']);
        }
    } else {
        $cart_data = serialize(wpdmpp_get_cart_data());
        $items = serialize(array_keys(wpdmpp_get_cart_data()));
        $insertid = $order->NewOrder(uniqid(), "", $items, 0, $current_user->ID, 'Processing', 'Processing', $cart_data);
        $order->UpdateOrderItems($cart_data, $_SESSION['orderid']);
    }


}


//saving payment method info from checkout process
function wpdmpp_paynow()
{


    if (isset($_REQUEST['task']) && $_REQUEST['task'] == "paynow") {

        if(floatval(wpdmpp_get_cart_total()) <= 0 ) die('Empty Cart!');

        global $current_user;
        get_currentuserinfo();
        // if(!isset($_SESSION['orderid'])||$_SESSION['orderid']==''){
        create_order();
        // }

        $data = array(
            'payment_method' => $_POST['payment_method']
        );
        $order = new Order();
        $od = $order->Update($data, $_SESSION['orderid']);
        $order_info = $order->GetOrder($_SESSION['orderid']);
        if(isset($_POST['order_email'])) update_option("email_".$_SESSION['orderid'], $_POST['order_email']);
        wpdmpp_place_order();
        die();
    }
}

//placing order from checkout process
function wpdmpp_place_order()
{

    if(floatval(wpdmpp_get_cart_total()) <=0 ) return;

    $order = new Order();
    $order_total = $order->CalcOrderTotal($_SESSION['orderid']);
    //$tax=wpdmpp_calculate_tax();

    $data = array(
        'total' => $order_total,
        'order_notes' => '',
        'cart_discount' => 0
    );
    $od = $order->Update($data, $_SESSION['orderid']);
    do_action("wpdm_before_placing_order", $_SESSION['orderid']);
    //update order items
    //$order->UpdateOrderItems(serialize($_POST['cart_items']), $_SESSION['orderid']);
    // If order total is not 0 then go to payment gateway
    if ($order_total > 0) {
        $payment = new Payment();
        $payment->InitiateProcessor($_POST['payment_method']);
        $payment->Processor->OrderTitle = 'Order# ' . $_SESSION['orderid'];
        $payment->Processor->InvoiceNo = $_SESSION['orderid'];
        $payment->Processor->Custom = $_SESSION['orderid'];
        $payment->Processor->Amount = number_format($order_total,2);
        echo $payment->Processor->ShowPaymentForm(1);
        if(!isset($payment->Processor->EmptyCartOnPlaceOrder) || $payment->Processor->EmptyCartOnPlaceOrder == true)
        wpdmpp_empty_cart();
        die();
    } else {

        // if order total is 0 then empty cart and redirect to home
        wpdmpp_empty_cart();
        wpdmpp_js_redirect(home_url('/'));
    }


}

//payment notification process
function wpdmpp_payment_notification()
{
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "wpdmpp-payment-notification") {
        //include_once(WP_PLUGIN_DIR."/wpdm-premium-packages/libs/payment_methods/".$_REQUEST['class']."/class.".$_REQUEST['class'].".php");
        $payment_method = new $_REQUEST['class']();

        if ($payment_method->VerifyNotification()) {
            global $wpdb;
            Order::complete_order($payment_method->InvoiceNo, true, $payment_method);
            do_action("wpdm_after_checkout",$payment_method->InvoiceNo);
            //header("location: ".wpdmpp_orders_page());
            die('OK');
        }
        die("FAILED");

    }
}

//withdraw money from paypal noti
function wpdmpp_withdraw_paypal_notification()
{
    if (isset($_REQUEST['action']) && $_REQUEST['action'] == "withdraw_paypal_notification" && current_user_can(WPDMPP_MENU_ACCESS_CAP)) {

        if (isset($_POST["txn_id"]) && isset($_POST["txn_type"]) && $_POST["status"] == "Completed") {
            global $wpdb;
            $wpdb->update(
                "{$wpdb->prefix}ahm_withdraws",
                array(
                    'status' => 1
                ),
                array('id' => $_POST['custom']),
                array(
                    '%d'
                ),
                array('%d')
            );
        }
    }
}

//payment using ajax
function wpdmpp_ajax_payfront()
{
    if (isset($_POST['task'], $_POST['action']) && $_POST['task'] == "paymentfront" && $_POST['action'] == "wpdmpp_ajax_call") {
        $data['order_id'] = $_POST['order_id'];
        $data['payment_method'] = $_POST['payment_method'];
        PayNow($data);
        die();
    }
}

function wpdmpp_ajax_call()
{
    $CustomActions = new CustomActions();
    if (method_exists($CustomActions, $_POST['execute'])) {
        $method = esc_attr($_POST['execute']);
        echo $CustomActions->$method();
        die();
    } else
    die("Function doesn't exist");
}


function wpdmpp_execute()
{
    $CustomActions = new CustomActions();
    if(isset($_POST['action']) && $_POST['action']=='wpdm_pp_ajax_call'){
        if (method_exists($CustomActions, $_POST['execute'])) {
            $method = esc_attr($_POST['execute']);
            echo $CustomActions->$method();
        die();
    }}
}


//function for earnings using shortcode
function wpdmpp_earnings()
{

        include("tpls/earnings.php");

}

//function for members tabs
function wpdmpp_frontend()
{
    include("tpls/wpdmpp_frontend.php");
}


function wpdmpp_edit_profile()
{
    include(dirname(__FILE__) . '/tpls/edit-profile.php');
}

function wpdmpp_my_orders()
{

}

function wpdmpp_move_upload_featuredfile()
{

    die($_POST['fileurl']);
}


function wpdmpp_update_profile()
{
    global $current_user;
    if (!is_user_logged_in() || !isset($_POST['profile'])) return;

    $userdata = $_POST['profile'];
    $userdata['ID'] = $current_user->ID;
    if ($_POST['password'] == $_POST['cpassword']) {
        wp_update_user($userdata);
        $userdata['user_pass'] = $_POST['password'];
        update_user_meta($current_user->ID, 'payment_account', $_POST['payment_account']);
        update_user_meta($current_user->ID, 'phone', $_POST['phone']);
        $_SESSION['member_success'] = __("Profile Updated Successfully", "wpmarketplace");

    } else {
        $_SESSION['member_error'][] = __("Confirm Password Not Matched. Profile Update Failed!", "wpmarketplace");
    }
    update_user_meta($current_user->ID, 'user_billing_shipping', serialize($_POST['checkout']));

    wpdmpp_redirect($_SERVER['HTTP_REFERER']);
    die();

}

//auto sugession function
function wpdmpp_autosuggest()
{
    if ($_REQUEST['tag']) {
        global $wpdb;
        $featured_products = $wpdb->get_results("select * from  {$wpdb->prefix}posts p  where p.post_type='wpmarketplace' and p.post_title like '%{$_REQUEST['tag']}%' and p.post_status='publish' ");

        $rtn = "[";
        foreach ($featured_products as $value) {
            $fp[] = array('key' => $value->ID, 'value' => $value->post_title);
        }

        echo json_encode($fp);
        die();
    }
}

function wpdmpp_remove_featured()
{
    if ($_POST['id']) {
        global $wpdb;
        $wpdb->query("delete from {$wpdb->prefix}ahm_feature_products where id='{$_POST['id']}'");
        die();
    }
}

//default currency saving function
function wpdmpp_default_currency()
{
    update_option('_wpdmpp_curr_key', $_POST['currency_key']);
    update_option('_wpdmpp_curr_name', $_POST['currency_name']);
    update_option('_wpdmpp_curr_sign', $_POST['currency_value']);
    die("success");
}

//default currency delete
function wpdmpp_default_currency_del()
{
    $cur_key = get_option('_wpdmpp_curr_key');
    if ($cur_key == $_POST['currency_key']) {
        delete_option('_wpdmpp_curr_key');
        delete_option('_wpdmpp_curr_name');
        delete_option('_wpdmpp_curr_sign');
    }
}

function wpdmpp_enqueue_scripts()
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-form');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('jquery-ui-accordion');


    if(is_admin() && get_query_var('post_type')=='wpdmpro'){
        wp_enqueue_script('wpdmpp-admin-js', WPDMPP_BASE_URL.'js/admin.js', array('jquery'));
    }

    if(is_admin() && wpdm_query_var('post_type')=='wpdmpro' && in_array(wpdm_query_var('page'), array('settings','payouts','orders', 'pp-license'))){
        wp_enqueue_style('wpdm-bootstrap', plugins_url('/download-manager/assets/bootstrap/css/bootstrap.css'));
        wp_enqueue_script('wpdm-bootstrap', plugins_url('/download-manager/assets/bootstrap/js/bootstrap.min.js'), array('jquery'));
    }

    wp_enqueue_script('wpdm-jquery-validate', WPDM_BASE_URL.'assets/js/jquery.validate.min.js',  array('jquery'));
    wp_enqueue_script('wpdm-bootstrap-select', WPDM_BASE_URL.'assets/js/bootstrap-select.min.js',  array('jquery', 'wpdm-bootstrap'));

    if(!is_admin())
    wp_enqueue_script('wpdm-pp-js', plugins_url('/wpdm-premium-packages/js/wpdmpp.js'), array('jquery'));

    wp_enqueue_style('wpdm-bootstrap-select', WPDM_BASE_URL.'assets/css/bootstrap-select.min.css');

    $settings = get_option('_wpdmpp_settings');

    if( get_the_ID() == $settings['orders_page_id'] ){
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
        wp_enqueue_script('media-upload');
        wp_enqueue_media();
    }

}


register_activation_hook(__FILE__, 'wpdmpp_install');


/** new functions added for wpdm pro **/

function wpdmpp_premium_user($pid)
{
    global $wpdb, $current_user, $wp_roles;
    $data = $wpdb->get_row("select * from {$wpdb->prefix}ahm_premium_packages where pid='{$pid}'");
    $roles = @array_reverse($wp_roles->role_names);
    $pu = explode(",", $data->premium_users);

    if (is_user_logged_in() && in_array($current_user->roles[0], $pu)) return true;
    return false;
}

function wpdmpp_is_purchased($pid, $uid = 0){
    global $current_user, $wpdb;
    if(!is_user_logged_in() && !$uid) return false;
    $uid = $uid?$uid:$current_user->ID;
    $orderid = $wpdb->get_var("select o.order_id from {$wpdb->prefix}ahm_orders o, {$wpdb->prefix}ahm_order_items oi  where uid='{$uid}' and o.order_id = oi.oid and oi.pid = {$pid} and order_status='Completed'");
    return $orderid;
}

/**
 * @param $id
 * @return string|void
 * @usage Generate Customer Download URL
 */
function wpdmpp_customer_download_link($id){
        $orderid = wpdmpp_is_purchased($id);
        if($orderid)
            return $orderid?wpdm_download_url($id, "&oid=$orderid"):"";

}

function fetch_template_tag($vars)
{
    global $wpdb;
    $vars['base_price'] = get_post_meta($vars['ID'], '__wpdm_base_price', true);
    $vars['currency'] = wpdmpp_cart_page();
    if ($vars['base_price'] > 0) { //&&!wpdmpp_premium_user($vars['ID'])
        $vars['addtocart_url'] = home_url("?wpdm_add_to_cart={$vars['ID']}");
        $vars['addtocart_link'] = wpdmpp_waytocart($vars);
        $vars['addtocart_form'] = wpdmpp_add_to_cart_html($vars['ID']);
        $vars['customer_download_link'] = wpdmpp_customer_download_link($vars['ID']);
        $vars['download_link'] = $vars['addtocart_form'];
        $vars['download_link_extended'] = $vars['addtocart_form'];

    } else {
        $vars['addtocart_url'] = $vars['download_url'];
        $vars['addtocart_link'] = $vars['download_link'];
        $vars['addtocart_form'] = $vars['download_link'];
    }
    return $vars;
}


function wpdmpp_invoide_field(){
    if(isset($_GET['orderid'])){
        echo "<input type='hidden' name='invoice' value='{$_GET['orderid']}' />";
    }
}

function wpdmpp_associate_invoice($user_login, $user){
    if(isset($_POST['invoice'])){
       $order = new Order();
       $orderdata = $order->GetOrder($_POST['invoice']);
        if($orderdata && intval($orderdata->uid) == 0){
            Order::Update(array('uid'=>$user->ID), $_POST['invoice']);
        }
    }
}
function wpdmpp_associate_invoice_signup($user_id){
    if(isset($_POST['invoice'])){
       $order = new Order();
       $orderdata = $order->GetOrder($_POST['invoice']);
        if($orderdata && intval($orderdata->uid) == 0){
            Order::Update(array('uid'=>$user_id), $_POST['invoice']);
        }
    }
}

function wpdmpp_resolveorder(){
    global $current_user;
    $order = new Order();
    $data = $order->GetOrder($_REQUEST['orderid']);
    if(!$data) die("Order not found!");
    if($data->uid!=0) {
        if($data->uid==$current_user->ID)
        die("Order is already linked with your account!");
        else
        die("Order is already linked with an account!");
    }
    Order::Update(array('uid'=>$current_user->ID), $data->order_id);
    die("ok");
}

function wpdmpp_lock_download($lock, $id){
    if(intval(get_post_meta($id, '__wpdm_base_price', true))>0) $lock = 'locked';
    return $lock;
}

    include(dirname(__FILE__) . "/libs/hooks.php");

function wpdmpp_settings_tab($tabs){
    $tabs['ppsettings'] = wpdm_create_settings_tab('ppsettings', 'Premium Package', "wpdmpp_settings", $icon = 'fa fa-shopping-cart');
    return $tabs;
}

function wpdmpp_user_dashboard_menu($menu){
    $menu = array_merge(array_splice($menu, 0, 1), array('purchases' => array('name' => 'Purchases', 'callback' => 'wpdmpp_purchased_items')), $menu);
    //$menu['purchases'] = array('name' => 'Purchases', 'callback' => 'wpdmpp_purchased_items');
    return $menu;
}

add_filter("wpdm_user_dashboard_menu", "wpdmpp_user_dashboard_menu");

add_filter("add_wpdm_settings_tab", "wpdmpp_settings_tab");

add_filter('wpdm_after_prepare_package_data', 'fetch_template_tag');
add_filter('wdm_before_fetch_template', 'fetch_template_tag');

   add_action('wpdm-package-form-left', 'wpdmpp_meta_box_pricing');

}