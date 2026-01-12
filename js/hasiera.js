$(document).ready(function () {
  // SLIDER KONFIGURAZIOA
  $(".hasiera-slider-egitura").bxSlider({
    mode: "horizontal", // Horizontala izan dadin
    autoplay: true,
    captions: true,
    auto: true,
    /*autoControls: true, */
    stopAutoOnClick: true,
    pager: true,
    speed: 500,
    pause: 4000,
    /* slideWidth: 600,   <-- HAU KENDU DUGU RESPONSIVEA IZATEKO */
    adaptiveHeight: true,
  });
});

