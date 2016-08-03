jQuery(function($){

        $('.ttip').tooltip();

        $('.price-variation').on('click', function(){

            var pid = $(this).data('product-id'), price;
            price = parseFloat($('#price-'+pid).html());
            $('.price-variation-'+pid).each(function(){
                   if($(this).attr('checked') == 'checked')
                    price += parseFloat($(this).data('price'));
            });
            var pricehtml = "<i class='fa fa-shopping-cart'></i> Add to Cart <span class='label label-primary'>"+$('#total-price-'+pid).data('curr')+price+"<label>";
            //$('#total-price-'+pid).html("= "+$('#total-price-'+pid).data('curr')+price);
            $('#cart_submit').html(pricehtml);

        });

        $('.wpdm_cart_form').submit(function(){
            var btnaddtocart = $(this).find('.btn-addtocart');
            btnaddtocart.css('width', btnaddtocart.css('width'));
            btnaddtocart.attr('disabled','disabled');
            var form = $(this);
            var btnlbl = btnaddtocart.html();
            btnaddtocart.html('<i class="fa fa-spinner fa-spin"></i> Adding...')
            $(this).ajaxSubmit({
            success: function(res){
                if(btnaddtocart.data('cart-redirect') == 'on') {
                    location.href = res;
                    return false;
                }
                btnaddtocart.removeAttr('disabled');
                form.find('.btn-viewcart').remove();
                btnaddtocart.addClass('btn-wc');
                btnaddtocart.html(btnlbl).after('<a href="'+res+'" class="'+btnaddtocart.attr('class').replace('btn-addtocart','')+' btn-viewcart ttip" type="button" title="Cart"><i class="fa fa-long-arrow-right"></i></a>');
                $('.ttip').tooltip();
                window.postMessage("cart_updated", window.location.protocol+"//"+window.location.hostname);
            }
        });
            return false;
        });
});