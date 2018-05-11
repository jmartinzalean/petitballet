$(document).ready(function(){
    var link = $('#hidden-link');
    var button = $('.ajax_add_to_cart_button');
    var cart = $('#logo-cart-petit');
    var msg = $('.shopping_cart');
    var remove = $('.ajax_cart_block_remove_link');
    button.on('click',button,function(){
        cart.attr('href', link.val());
        msg.removeClass('cart-msg');
    });
    remove.on('click',remove,function(){
        cart.attr('href','#');
        msg.addClass('cart-msg');
    });
});


