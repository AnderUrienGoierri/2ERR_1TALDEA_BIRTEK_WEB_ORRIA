$(document).ready(function () {
  // Kantitatea aldatzeko botoiak (+ / -)
  $("#gehitu-kantitatea").click(function () {
    var input = $("#botoi-kopurua");
    var balioa = parseInt(input.val()) || 1;
    var max = parseInt(input.attr("max"));
    if (balioa < max) {
      input.val(balioa + 1);
    }
  });

  $("#kendu-kantitatea").click(function () {
    var input = $("#botoi-kopurua");
    var balioa = parseInt(input.val()) || 1;
    var min = parseInt(input.attr("min")) || 1;
    if (balioa > min) {
      input.val(balioa - 1);
    }
  });

  // Xehetasun orriko saskiratu logika espezifikoa
  $(".saskiratu-xehetasunak").click(function () {
    var btn = $(this);
    var id = btn.data("id");
    var izena = btn.data("izena");
    var prezioa = parseFloat(btn.data("prezioa"));
    var stock = parseInt(btn.data("stock")) || 0;
    var kantitatea = parseInt($("#botoi-kopurua").val()) || 1;

    // Saskia berreskuratu
    var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];

    // Begiratu ea badagoen
    var badago = saskia.find((i) => i.id == id);
    var saskianDagoenKantitatea = badago ? badago.kantitatea : 0;

    // STOCK KONTROLA
    if (saskianDagoenKantitatea + kantitatea > stock) {
      alert(
        "Ezin da gehitu: Stock nahikorik ez (" +
          stock +
          " ale geratzen dira). Saskian: " +
          saskianDagoenKantitatea,
      );
      return;
    }

    if (badago) {
      badago.kantitatea += kantitatea;
    } else {
      saskia.push({
        id: id,
        izena: izena,
        prezioa: prezioa,
        kantitatea: kantitatea,
        stock: stock, // Gordetzen dugu ere, globala.js-en erabiltzeko
      });
    }

    // Gorde (Globala.js logika erabiltzen badu, ondo. Bestela eskuz)
    if (typeof window.saskiaGorde === "function") {
      window.saskiaGorde(saskia); // Honek dropdown ere eguneratzen du

      // Animazioa deitu
      if (typeof window.saskiaAnimatuKontagailua === "function") {
        window.saskiaAnimatuKontagailua();
      }
    } else {
      localStorage.setItem("birtek_saskia", JSON.stringify(saskia));
      location.reload();
    }

    // Animazio txiki bat botoian
    btn.html('<i class="fas fa-check"></i> Gehituta!');
    btn.css("background-color", "#166534");
    setTimeout(function () {
      btn.html('<i class="fas fa-cart-plus"></i> Saskira Gehitu');
      btn.css("background-color", "");
    }, 2000);
  });
});
