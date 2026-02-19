// <!-- Initialize Swiper -->
var swiper = new Swiper(".mySwiper1", {
  slidesPerView: 2,
  spaceBetween: 0,
  freeMode: true,
  loop: true,
  speed: 5500, // smooth continuous effect
  autoplay: {
    delay: 0, // continuous sliding
    disableOnInteraction: false,
  },
  breakpoints: {
    640: {
      slidesPerView: 2,
    },
    768: {
      slidesPerView: 2,
    },
    1024: {
      slidesPerView: 4,
    },
  },
});
var swiper = new Swiper(".mySwiper2", {
  slidesPerView: 2,
  spaceBetween: 0,
  freeMode: true,
  loop: true,
  speed: 5500, // smooth continuous effect
  autoplay: {
    delay: 0, // continuous sliding
    disableOnInteraction: false,
    reverseDirection: true, // 🔥 this makes it move left → right
  },
  breakpoints: {
    640: {
      slidesPerView: 2,
    },
    768: {
      slidesPerView: 2,
    },
    1024: {
      slidesPerView: 4,
    },
  },
});
