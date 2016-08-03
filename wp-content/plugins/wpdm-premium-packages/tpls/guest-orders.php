<div class="w3eden">
<div id="gonotice"></div>
<form method="post" id="goform">
<div class="panel panel-success">
    <div class="panel-heading text-lg">Guest Order Access</div>
    <div class="panel-body">
            <div class="row">
                <div class="col-md-6"><div class="form-group">
                        <label>Order Email:</label>
                        <input type="email" required="required" id="goemail" name="go[email]" class="form-control">
                    </div></div>
                <div class="col-md-6"><div class="form-group">
                        <label>Order#</label>
                        <input type="text" required="required" id="goorder" name="go[order]" value="<?php echo isset($_SESSION['last_order'])?$_SESSION['last_order']:''; ?>" class="form-control" />
                    </div></div>
            </div>
    </div>
    <div class="panel-footer text-right">
        <button class="btn btn-primary btn-sm" id="goproceed">Proceed &nbsp; <i class="fa fa-chevron-right"></i></button>
    </div>
</div>
</form>
<?php
if(isset($_SESSION['guest_order'])){
    $o = new Order();
    $order = $o->GetOrder($_SESSION['guest_order']);
    $oitems = Order::GetOrderItems($order->order_id);
    echo "<ul class='list-group'>";
    foreach($oitems as $item){
        $product = get_post($item['pid']);
    ?>
     <li class="list-group-item"><a href="<?php echo wpdm_download_url((array)$product, "&oid={$order->order_id}"); ?>" class="btn btn-success pull-right">Download</a><b><?php echo $product->post_title; ?></b><br/><?php echo count(maybe_unserialize(get_post_meta($item['pid'], '__wpdm_files', true))); ?> files</li>
 <?php }
    echo "</ul>";
}  ?>
<script>
    jQuery(function($){
        var goerrors = new Array();
        goerrors['nosess'] = 'Session was expired. Please try again';
        goerrors['noordr'] = 'Order not found, Please re-check your info';
        goerrors['nogues'] = 'Order is already associated with an account. Please login using that account to get access';
        $('#goform').submit(function(){
            var gop = $('#goproceed').html();
            $('#goproceed').html("<i class='fa fa-spinner fa-spin'></i>");
            $(this).ajaxSubmit({
                success: function(res){
                    if(res.match(/nosess/))  $('#gonotice').html('<div class="alert alert-danger">' + goerrors['nosess'] + '</div>');
                    else if(res.match(/noordr/))  $('#gonotice').html('<div class="alert alert-danger">' + goerrors['noordr'] + '</div>');
                    else if(res.match(/nogues/))  $('#gonotice').html('<div class="alert alert-danger">' + goerrors['nogues'] + '</div>');
                    else if(res.match(/success/)) { location.href = '<?php echo wpdmpp_guest_order_page(); ?>'; gop = "<i class='fa fa-refresh fa-spin'></i>"; }
                    $('#goproceed').html(gop);
                }
            });
            return false;
        });
    });
</script>

<style>
    .list-group{ padding: 0 !important; }
    .list-group, .list-group li{ margin: 0 !important; }
</style>
    </div>