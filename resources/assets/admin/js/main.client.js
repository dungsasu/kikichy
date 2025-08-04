$(document).ready(function () {
    $('.owl-carousel-home').owlCarousel({
      // loop: true,
      nav: true,
      items: 1,
      loop: true,
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
        `
      ],
    })
  
    $('.hot-carousel').owlCarousel({
      // loop: true,
      nav: true,
      items: 2.5,
      loop: true,
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
        
        `
      ],
    })
  
    var msnry = new Masonry('.my-grid', {
      // options
      itemSelector: '.grid-item',
      columnWidth: '.grid-item'
    });
  
  
    lightGallery(document.getElementById('dmc-video'), {
      plugins: [lgVideo],
    });
  
  
  })