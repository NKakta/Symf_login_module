$(function() {
    // Click first option in navigation
        $('.menu-cat ul li a').first().addClass('active');
        loadProducts();

    // Clicking on navigation
        $(document).on('click', '.sub-nav-link', function() {
            $('.menu-cat ul li a.active').removeClass('active');
            $(this).addClass('active');
            loadProducts();
        });

    // Close mobile navigation
        $(document).on('click', '.close-mobile-menu', function() {
            // $('.mobile-navigation').css('left', '-100%').hide();
            $('.mobile-navigation').animate({
                left: '-100%'
            }, 300, function() {
                $(this).hide();
            });
            $('body').removeClass('no-scroll');
        });

    // Show mobile navigation
        $(document).on('click', '.show-mobile-menu', function() {
            $('.mobile-navigation').css('left', '-100%');
            $('.mobile-navigation').show();
            $('.mobile-navigation').css('left', '0%');
            $('body').addClass('no-scroll');
        });

    // Clicking on product
        $(document).on('click', '.checkout-btn', function() {
            var in_stock = $(this).attr('in-stock'), price = $(this).attr('price');

            $('#checkoutModal').modal('show');
            $('#checkoutModal input[name="in_stock"]').val(in_stock);
            $('#checkoutModal input[name="quantity"]').val(1);
            $('#checkoutModal .total_price').html(price + '$');
            $('#checkoutModal input[name="total_price"]').val(parseFloat(price).toFixed(2));
            $('#checkoutModal input[name="original_price"]').val(price);
            $('#checkoutModal input[name="product_id"]').val($(this).attr('product-id'));
            $('#checkoutModal input[name="region"]').val($(this).attr('region-name'));
            $('#checkoutModal .product_name').html($(this).attr('product-name'));
        });

    // Changing checkout quantity
        $(document).on('change keyup keydown', '#checkoutModal input[name="quantity"]', function() {
            var value = $(this).val(),
                price = $('#checkoutModal input[name="original_price"]').val(),
                stock = $('#checkoutModal input[name="in_stock"]').val();

            if (stock < value) {
                $('#checkoutModal input[name="quantity"]').val(stock);
                $('#checkoutModal .total_price').html(parseFloat(stock * price).toFixed(2) + '$');
                $('#checkoutModal input[name="total_price"]').val(parseFloat(stock * price).toFixed(2));
            } else {
                $('#checkoutModal .total_price').html(parseFloat(value * price).toFixed(2) + '$');
                $('#checkoutModal input[name="total_price"]').val(parseFloat(value * price).toFixed(2));
            }
        });
});

function loadProducts() {
    var getProductsUrl = $('.products').data('products-url');
    if ($('.menu-cat ul li a.active').length) {
        var item = $('.menu-cat ul li a.active');
        var id = item.attr('product-id');
        var region = item.attr('region-name');

        $('.breadcrumb-category').html(item.attr('category-name'));
        $('.breadcrumb-subcategory').html(item.html());
        $('.products .row').html('');
        $('.loader').show();
        $.ajax({
            method: "GET",
            dataType: "html",
            url: getProductsUrl,
            data: {
                category: id,
                region: region
            }
        })
        .done(function(resp) {
            $('.products .row').html(resp);
        });
    }
}
