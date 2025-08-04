$(document).ready(function () {
    // Khởi tạo Owl Carousel
    var bannerCarousel = $('.banner-carousel').owlCarousel({
        loop: true,
        margin: 0,
        nav: false, // Tắt navigation mặc định
        dots: true,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true,
        items: 1, // Chỉ hiển thị 1 item
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 1
            },
            1000: {
                items: 1
            }
        }
    });

    // Kết nối nút prev với carousel
    $('.banner-prev').click(function () {
        bannerCarousel.trigger('prev.owl.carousel');
    });

    // Kết nối nút next với carousel
    $('.banner-next').click(function () {
        bannerCarousel.trigger('next.owl.carousel');
    });

    // Dừng autoplay khi hover vào nút
    $('.banner-nav button').hover(
        function () {
            bannerCarousel.trigger('stop.owl.autoplay');
        },
        function () {
            bannerCarousel.trigger('play.owl.autoplay');
        }
    );
});