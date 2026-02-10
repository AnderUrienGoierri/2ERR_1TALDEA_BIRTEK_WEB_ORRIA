$(document).ready(function () {
  // SLIDER KONFIGURAZIOA
  $(".hasiera-slider-egitura").bxSlider({
    mode: "horizontal", // Horizontala izan dadin
    autoplay: true,
    captions: true,
    auto: true,

    stopAutoOnClick: true,
    pager: true,
    speed: 500,
    pause: 4000,

    adaptiveHeight: true,
  });
});

