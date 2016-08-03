<div class="panel panel-default dashboard-panel">
    <div class="panel-heading"><a href="<?php the_permalink(); ?>purchases/orders/" class="pull-right"><?php _e('All Orders', 'wpmarketplace'); ?></a><?php _e('Purchased Items', 'wpmarketplace'); ?> </div>
    <table class="table">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Order ID</th>
                <th>Purchase Date</th>
                <th>Download</th>
            </tr>
        </thead>
        <tbody>
<?php foreach($purchased_items as $item){ ?>

        <tr>
            <td><?php $title = get_the_title($item->pid); echo $title?$title:'<span class="text-danger"><i class="fa fa-warning"></i> Product Deleted</span>'; ?></td>
            <td><?php echo wpdmpp_currency_sign().number_format($item->price,2); ?></td>
            <td><a href="<?php the_permalink(); ?>purchases/order/<?php echo $item->oid; ?>/"><?php echo $item->oid; ?></a></td>
            <td><?php echo date(get_option('date_format'),$item->date); ?></td>
            <td>
                <?php if($item->order_status == 'Completed'){ ?>
                    <a href="<?php the_permalink(); ?>purchases/order/<?php echo $item->oid; ?>/" class="btn btn-xs btn-primary btn-block">Download</a>
                <?php } else { ?>
                    <a href="<?php the_permalink(); ?>purchases/order/<?php echo $item->oid; ?>/" class="btn btn-xs btn-danger btn-block">Expired</a>
                <?php } ?>
            </td>
        </tr>

<?php } ?>
        </tbody>
    </table>
    <div class="panel-footer">
        If you are not seeing your purchased item: <a class="btn btn-warning btn-xs" style="color: #ffffff !important;" href="<?php the_permalink(); ?>purchases/orders/">Fix It Here</a>
    </div>
</div>

