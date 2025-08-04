$(document).ready(function () {
    $(document).on('click', ".size-item:not(.item-disabled)", function () {
        if (!$(this).hasClass('sold-out')) {
            $(this).addClass('active').siblings().removeClass('active');
        }
    });
    $(document).on('click', '.color-item-outside:not(.item-disabled)', function (e) {
        $(".color-item-outside").removeClass("active")
        $(this).addClass("active");

        let name = $(this).data('color-name');
        $(".color-name").text(name);
    })
    $(document).on('click', ".plus", function () {
        var $button = $(this);
        var oldValue = $(this).prev('input').val();

        var newVal = parseFloat(oldValue) + 1;

        $(this).prev('input').val(newVal);

    });

    $(document).on('click', ".subtract", function () {

        var $button = $(this);
        var oldValue = $(this).next('input').val();

        if (oldValue > 1) {
            var newVal = parseFloat(oldValue) - 1;
            $(this).next('input').val(newVal);
        }
        else {
            $(this).next('input').val(1);
        }
    });
    $(".number-only").inputFilter(function (value) {
        return /^\d*$/.test(value) && (value === "" || parseInt(value) < 100);
    });

    $(document).on('click', '.btn-add-to-cart', function (e) {
        e.preventDefault();
        let color_name = $(".color-item-outside.active").data('color-name');
        let color_value = $(".color-item-outside.active").data('color-code');
        let fcolor = $(".color-item-outside.active").data('fcolor');

        let size_name = $(".size-item.active").data('size-name');
        let fsize = $(".size-item.active").data('size-fname');

        $(".cart-color-size-box").text('')
        var productId = $(this).data('product-id');
        var productName = $(this).data('product-name');

        var quantity = $("#quantity-order").val();
        var price = format_price($(this).data('product-price'));
        var options = {
            size: {
                label: size_name,
                value: fsize
            },
            color: {
                label: color_name,
                value: color_value,
                fcolor: fcolor
            }
        };

        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $(".cart-color-size-name").text(productName);
        $(".cart-quantity-box").text((quantity));
        $(".cart-color-size-box").text(`${options.color.label}/${options.size.label}`);

        $.ajax({
            url: '/add-to-cart',
            type: 'POST',
            dataType: 'json',
            data: {
                id: productId,
                title: productName,
                quantity: quantity,
                price: price,
                options: options,
                _token: csrfToken
            },
            success: function (response) {
                // $('html, body').animate({ scrollTop: 0 }, 'fast', function () {
                $('body').css("overflow", "auto");
                $(".add-cart-modal").html(response.html)
                $(".cart-box").show();
                showBackdrop();
                $("#prd_buynow").modal('hide');
                $(".cart-color-size-name").html(response.data.name);
                $(".thumbnail-cart").attr("src", response.data.image);
                $(".cart-quantity-box").val(1);

                // });
                update_cart();

            },
            error: function (xhr, status, error) {
                // Xử lý khi có lỗi (ví dụ: hiển thị thông báo lỗi, ...)
                console.error(error);
                alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng');
            }
        });
    });
    $(".backdrop").on("click", function (event) {
        if (!$(event.target).closest(".sidebar_cart, #openSidebar").length) {
            $(".cart-box").hide();
            $("body").css("overflow", "auto")
        }
    });

    $("#prd_buynow").on('hidden.bs.modal', function () {
        $('.main-carousel-modal').owlCarousel('destroy');
    });
    $(".buy-now").on("click", function (event) {
        event.stopPropagation();
        event.preventDefault();
        $(".product-slide-wrap").empty();

        var productId = $(this).data("id");
        var apiUrl = "/api/products/" + productId;

        $.ajax({
            url: apiUrl,
            type: "GET",
            success: function (response) {
                $(".product-slide-wrap").html(response.html);
            },
            error: function (xhr, status, error) {
                // Xử lý lỗi nếu có
                console.error(error);
            },
        });
    });

    buynow();
});


function buynow() {
    $(document).on("click", ".buynow_wrapper", function (e) {
        e.preventDefault();
        let _this = this;
        let color_name = $(".color-item-outside.active").data('color-name');
        let color_value = $(".color-item-outside.active").data('color-code');
        let size_name = $(".size-item.active").data('size-name');
        let fcolor = $(".color-item-outside.active").data('fcolor');
        let fsize = $(".size-item.active").data('size-fname');

        $(".cart-color-size-box").text('')
        var productId = $(this).data('product-id');
        var productName = $(this).data('product-name');

        var quantity = $("#quantity-order").val();
        var price = format_price($(this).data('product-price'));
        var options = {
            size: {
                label: size_name,
                value: fsize,
            },
            color: {
                label: color_name,
                value: color_value,
                fcolor: fcolor
            }
        };

        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        $(".cart-color-size-name").text(productName);
        $(".cart-quantity-box").text((quantity));
        $(".cart-color-size-box").text(`${options.color.label}/${options.size.label}`);

        $.ajax({
            url: '/add-to-cart',
            type: 'POST',
            dataType: 'json',
            data: {
                id: productId,
                title: productName,
                quantity: quantity,
                price: price,
                options: options,
                _token: csrfToken
            },
            success: function (response) {
                window.location.href = $(_this).data("link-payment");
            },
            error: function (xhr, status, error) {
                // Xử lý khi có lỗi (ví dụ: hiển thị thông báo lỗi, ...)
                console.error(error);
                alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng');
            }
        });
    })
}

function update_cart() {
    $.ajax({
        url: '/get-cart',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            $(".sidebar-content").html(response.html)
            $(".quantity-cart").text(response.quantity)

        },
        error: function (xhr, status, error) {
            // Xử lý khi có lỗi (ví dụ: hiển thị thông báo lỗi, ...)
            console.error(error);
            // alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng');
        }
    });
}

function format_price(value) {
    return value.replace(/[.\s₫]/g, "");
}

(function ($) {
    $.fn.inputFilter = function (inputFilter) {
        return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function () {
            if (inputFilter(this.value)) {
                this.oldValue = this.value;
                this.oldSelectionStart = this.selectionStart;
                this.oldSelectionEnd = this.selectionEnd;
            } else if (this.hasOwnProperty("oldValue")) {
                this.value = this.oldValue;
                this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
            } else {
                this.value = "";
            }
        });
    };
}(jQuery))
