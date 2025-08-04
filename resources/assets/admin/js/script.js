
$(document).ready(function() {
    $('.owl-carousel').owlCarousel({
        loop:true,
        margin:10,
        //nav:true,
        nav: false,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:3
            },
            1000:{
                items:4
            }
        }
    })
    var owl = $('.owl-carousel');
    $('#custom-prev').click(function() {
      owl.trigger('prev.owl.carousel');
    });

    $('#custom-next').click(function() {
      owl.trigger('next.owl.carousel');
    });
});

const backToTopButton = document.getElementById('backtotop');

window.addEventListener('scroll', () => {
  if (window.scrollY > 100) {
    backToTopButton.style.display = 'block';
  } else {
    backToTopButton.style.display = 'none';
  }
});

backToTopButton.addEventListener('click', () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
});

$(document).ready(function() {
  $('.question-item').on('click', function() {
      const $clickedItem = $(this);

      // Kiểm tra xem phần tử đang được click có class 'highlighted' hay không
      const isHighlighted = $clickedItem.hasClass('highlighted');
      // Đóng tất cả các content khác và loại bỏ class 'highlighted'
      $('.question-item.highlighted').not($clickedItem);
      $('.collapse.show').not($clickedItem.next('.collapse'));

      // Toggle class 'highlighted' và hiển thị/ẩn content khi click vào phần tử
      $clickedItem.toggleClass('highlighted');
      $clickedItem.next('.collapse').toggleClass('show', !isHighlighted);
  });
});
$(document).ready(function() {
  // Chọn tất cả các phần tử có class "navItem" và thêm sự kiện click
  $(".navItem").click(function() {
    $(".navItem").removeClass("active");

    $(this).addClass("active");
  });
});
