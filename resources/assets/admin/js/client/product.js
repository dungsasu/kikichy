$(document).ready(function () {
    var target_date = new Date().getTime() + 1000 * 3600 * 48;

    // Get the elements to display the countdown
    var $hours = $(".hours");
    var $minutes = $(".minutes");
    var $secs = $(".secs");

    // Update the countdown every second
    setInterval(function () {
        getCountdown();
    }, 1000);

    function getCountdown() {
        // Find the amount of "seconds" between now and target
        var current_date = new Date().getTime();
        var seconds_left = (target_date - current_date) / 1000;

        var hours = pad(parseInt(seconds_left / 3600));
        seconds_left = seconds_left % 3600;

        var minutes = pad(parseInt(seconds_left / 60));
        var seconds = pad(parseInt(seconds_left % 60));

        // Update the HTML content
        $hours.text(hours);
        $minutes.text(minutes);
        $secs.text(seconds);
    }

    function pad(n) {
        return (n < 10 ? "0" : "") + n;
    }
    Fancybox.bind('[data-fancybox="gallery"]', {
        Thumbs: {
            type: "classic",
        },
    });
    $(".main-carousel").owlCarousel({
        loop: false,
        margin: 10,
        nav: false,
        items: 1,
        autoplay: true,
        slideSpeed: 300,
        animateOut: "animate__fadeOutDown",
        animateIn: "animate__fadeInDown",
    });

    var owl = $(".main-carousel");
    // Listen to owl events:

    owl.on("changed.owl.carousel", function (event) {
        let item = event.item.index - 2;
        $(".nav-item").removeClass("active");
        $(`.nav-item[data-position=${item}]`).addClass("active");
    });

    $(".nav-item").click(function () {
        let position = $(this).data("position");
        owl.trigger("to.owl.carousel", [position, 1000]);
        $(".nav-item").removeClass("active");
        $(this).addClass("active");
    });

    $(".nav-item").hover(function () {
        owl.trigger("stop.owl.autoplay");
    });

    let point = $(".my-rating-8").attr("data-point");

    $(".my-rating-8").starRating({
        initialRating: point,
        starSize: 25,
        readOnly: true,
        starSize: 20,
        hoverColor: "salmon",
        activeColor: "crimson",
        starGradient: false,
        strokeWidth: 1,
        strokeColor: "black",
        callback: function (currentRating, $el) {},
    });

    $(".san-pham-mua-cung-slide").owlCarousel({
        nav: false,
        responsive: {
            0: {
                items: 2,
            },
            769: {
                items: 4,
            },
            1025: {
                items: 5,
            },
        },
        loop: false,
        margin: 20,
        dots: false,
        navText: [
            `<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
      <rect width="40" height="40" rx="20" transform="matrix(-1 0 0 1 40 0)" fill="white"/>
      <path d="M22 24L18 20L22 16" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      `,
            `<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
      <g clip-path="url(#clip0_2001_3022)">
      <rect width="40" height="40" rx="20" fill="white"/>
      <path d="M18 24L22 20L18 16" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </g>
      <defs>
      <clipPath id="clip0_2001_3022">
      <rect width="40" height="40" rx="20" fill="white"/>
      </clipPath>
      </defs>
      </svg>

      `,
        ],
    });

    $(".viewed-carousel").owlCarousel({
        // loop: true,
        nav: false,
        items: 5,
        responsive: {
            0: {
                items: 2,
            },
            769: {
                items: 4,
            },
            1025: {
                items: 5,
            },
        },
        loop: false,
        margin: 20,
        autoplay: true,
        navText: [
            `<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
      <g clip-path="url(#clip0_815_11190)">
      <rect width="40" height="40" rx="20" transform="matrix(-1 0 0 1 40 0)" fill="black" fill-opacity="0.24"/>
      <path d="M22 24L18 20L22 16" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </g>
      <defs>
      <clipPath id="clip0_815_11190">
      <rect width="40" height="40" rx="20" transform="matrix(-1 0 0 1 40 0)" fill="white"/>
      </clipPath>
      </defs>
      </svg>
      `,
            `<svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
      <rect width="40" height="40" rx="20" fill="black" fill-opacity="0.24"/>
      <path d="M18 24L22 20L18 16" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      `,
        ],
    });

    // $('.btn-add-to-cart').click(function (e) {
    //     e.preventDefault();
    //     let color_name = $(".color-item-outside.active").data('color-name');
    //     let color_value = $(".color-item-outside.active").data('color-code');
    //     let size_name = $(".size-item.active").data('size-name');

    //     $(".cart-color-size-box").text('')
    //     var productId = $(this).data('product-id');
    //     var productName = $(this).data('product-name');

    //     var quantity = format_price($("#quantity-order").val());
    //     var price = $(this).data('product-price');
    //     var options = {
    //         size: {
    //             label: size_name,
    //             value: size_name
    //         },
    //         color: {
    //             label: color_name,
    //             value: color_value
    //         }
    //     };

    //     var csrfToken = $('meta[name="csrf-token"]').attr('content');
    //     $(".cart-color-size-name").text(productName);
    //     $(".cart-quantity-box").text((quantity));
    //     $(".cart-color-size-box").text(`${options.color.label}/${options.size.label}`);

    //     $.ajax({
    //         url: '/add-to-cart',
    //         type: 'POST',
    //         dataType: 'json',
    //         data: {
    //             id: productId,
    //             title: productName,
    //             quantity: quantity,
    //             price: price,
    //             options: options,
    //             _token: csrfToken
    //         },
    //         success: function (response) {
    //             $('html, body').animate({ scrollTop: 0 }, 'fast', function () {
    //                 $('body').css("overflow", "auto");
    //                 $(".cart-box").show();
    //                 showBackdrop();
    //             });
    //             update_cart();

    //         },
    //         error: function (xhr, status, error) {
    //             // Xử lý khi có lỗi (ví dụ: hiển thị thông báo lỗi, ...)
    //             console.error(error);
    //             alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng');
    //         }
    //     });
    // });

    //coundown sale products event
    var target_date = new Date().getTime() + 1000 * 3600 * 48;

    // Get the elements to display the countdown
    var $hours = $(".hours");
    var $minutes = $(".minutes");
    var $secs = $(".secs");

    // Update the countdown every second
    setInterval(function () {
        getCountdown();
    }, 1000);

    function getCountdown() {
        // Find the amount of "seconds" between now and target
        var current_date = new Date().getTime();
        var seconds_left = (target_date - current_date) / 1000;

        var hours = pad(parseInt(seconds_left / 3600));
        seconds_left = seconds_left % 3600;

        var minutes = pad(parseInt(seconds_left / 60));
        var seconds = pad(parseInt(seconds_left % 60));

        // Update the HTML content
        $hours.text(hours);
        $minutes.text(minutes);
        $secs.text(seconds);
    }

    function pad(n) {
        return (n < 10 ? "0" : "") + n;
    }
});

// function update_cart() {
//     $.ajax({
//         url: '/get-cart',
//         type: 'GET',
//         dataType: 'json',
//         success: function (response) {
//             $(".sidebar-content").html(response.html)
//             $(".quantity-cart").text(response.quantity)

//         },
//         error: function (xhr, status, error) {
//             // Xử lý khi có lỗi (ví dụ: hiển thị thông báo lỗi, ...)
//             console.error(error);
//             // alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng');
//         }
//     });
// }
