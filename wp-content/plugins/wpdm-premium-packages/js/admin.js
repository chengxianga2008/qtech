jQuery(function($){
    $('.recal-sa').on('click', function(e){
        e.preventDefault();
        var $this = $(this);
        var id = $(this).attr('rel');
        $this.html("<i class='fa fa-spinner fa-spin'></i>");
        $.post(ajaxurl, {action: 'RecalculateSales', id: $(this).attr('rel')}, function(res){
            $this.html(res.sales_amount);
            $('#sc-'+id).html(res.sales_quantity);
        });
    });
});