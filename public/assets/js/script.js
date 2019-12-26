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
    if ($('.menu-cat ul li a.active').length) {
        var item = $('.menu-cat ul li a.active');
        var id = item.attr('product-id');

        $('.breadcrumb-category').html(item.attr('category-name'));
        $('.breadcrumb-subcategory').html(item.html());
        $('.products .row').html('');
        $('.loader').show();
        $.ajax({
            method: "GET",
            dataType: "json",
            url: window.location.href.split('?')[0]+"ajax/products",
            data: { category: id }
        })
        .done(function(resp) {
            setTimeout(function() {
                $('.loader').hide();
            }, 1000);

            if (resp.success) {
                var output = '';
                $.each(resp.data, function(key, value) {
                    console.log(value);
                    output += '<div class="col-md-4 mt-3">';
                        output += '<div class="product-photo">';
                            let photoUrl = window.location.href.split('?')[0]+'assets/images/products/'+value.photo_filename;
                            if (value.in_stock > 0) {
                                output += '<div class="hovered-photo-bg checkout-btn" photo="'+ photoUrl +'" product-id="'+value.id+'" price="'+parseFloat(value.price).toFixed(2)+'" product-name="'+value.name+'" in-stock="'+value.in_stock+'"><p>Buy now</p></div>';
                            } else {
                                output += '<div class="hovered-photo-bg"><p>Out of stock</p></div>';
                            }
                            output += '<img class="img-fluid" src="'+ photoUrl +'" width="100%">';
                        output += '</div>';

                        output += '<div class="row product-details">';
                            output += '<div class="col-md-8">';
                                output += '<h4 class="mb-0 title">'+value.name+'</h4>';
                                output += '<h4 class="mb-0 stock">In stock: <span>'+value.in_stock+'</span></h4>';
                            output += '</div>';
                            output += '<div class="col-md-4">';
                                output += '<h1 class="mb-0 price text-right">'+parseFloat(value.price).toFixed(2)+'$</h1>';
                            output += '</div>';
                        output += '</div>';
                    output += '</div>';
                });
            } else {
                output = '<div class="col-md-12"><div class="alert alert-danger d-block w-100" role="alert">No products in this category!</div></div>';
            }
            $('.products .row').html(output);
        });
    }
}
