$(document).ready(function(){
    var contador = $('.ajax_cart_quantity');
    var title = 'aquí metes el string traducido, y verás como funciona esta mierda';
    var carrito = $('.shopping_cart > a:first-child');
    function tooltip_shopcart () {
        if (contador.is(':visible')) {
            carrito.removeAttr('title').removeAttr('data-original-title').css({'cursor':'pointer'}).attr('disabled', true);
            console.log('tengo productos, y no tooltip');
        } else {
            carrito.attr('title', title).css({'cursor':'default'});
            console.log('no tengo productos ni enlace, pero si tooltip');
        }
    }
    tooltip_shopcart();
    carrito.on('click', function () {
        $(this).tooltip('enable').tooltip('open');
    });
    carrito.tooltip({
        disabled: true,
        close: function( event, ui ) { $(this).tooltip('disable'); }
    });
    $(document).on('click', function(){
        tooltip_shopcart();
    });
    
});


