$(document).ready(function () {
  kargatuSaskia();
});

function kargatuSaskia() {
  var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
  var $edukiontzia = $("#saskia-xehetasunak");

  if (saskia.length === 0) {
    $edukiontzia.html(
      "<p>Saskia hutsik dago. <a href='produktuak.php'>Itzuli dendara</a></p>",
    );
    $(".botoi-edukiontzia button")
      .prop("disabled", true)
      .addClass("botoi-desgaitua");
    return;
  }

  var html = "<h4>Erosketaren Laburpena:</h4><ul>";
  var totala = 0;

  $.each(saskia, function (index, prezioa) {
    var prezioTotala = prezioa.prezioa * prezioa.kantitatea;
    totala += prezioTotala;
    html +=
      "<li>" +
      prezioa.kantitatea +
      "x " +
      prezioa.izena +
      " - " +
      prezioa.prezioa +
      "€/unit (" +
      prezioTotala.toFixed(2) +
      "€)</li>";
  });

  html += "</ul>";
  html +=
    "<div class='saskia-totala'>Guztira: " + totala.toFixed(2) + " €</div>";

  $edukiontzia.html(html);
}

function burutuErosketa() {
  // Balidazio sinplea
  var titularra = $("#titularra").val();
  var txartela = $("#txartela").val();

  if (!titularra || !txartela) {
    alert("Mesedez, bete eremu guztiak.");
    return;
  }

  var botoia = $(".botoi-edukiontzia button");
  botoia.html('<i class="fas fa-spinner fa-spin"></i> Prozesatzen...');
  botoia.prop("disabled", true);

  var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];

  /* AJAX **/
  $.ajax({
    url: "prozesatu_erosketa.php",
    type: "POST",
    contentType: "application/json",
    data: JSON.stringify({ saskia: saskia }),
    success: function (response) {
      if (response.success) {
        // Erosketa 'burutu' ondo
        localStorage.removeItem("birtek_saskia");
        localStorage.removeItem("birtek_saski_kopurua");

        // Eguneratu saski kontagailua (globala.js-ko funtzioa)
        if (typeof window.saskiKontagailuaEguneratu === "function") {
          window.saskiKontagailuaEguneratu();
        }

        $(".ordainketa-kutxa").fadeOut(300, function () {
          $(this)
            .html(
              `
            <div class="arrakasta-edukiontzia">
                <div class="arrakasta-ikonoa">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 class="arrakasta-titulua">Erosketa Burutu da!</h2>
                <p class="arrakasta-testua">
                    Eskerrik asko zure erosketagatik. Zure eskaera ondo erregistratu da eta prozesatzen ari gara.
                </p>
                <div class="arrakasta-ekintzak">
                    <a href="bezero_eskaerak.php" class="botoia botoi-sekundarioa">Erosketak Kudeatu</a>
                    <a href="hasiera.php" class="botoia botoi-nagusia">Itzuli Hasierara</a>
                </div>
            </div>
          `,
            )
            .fadeIn(300);
        });
      } else {
        alert(
          "Errorea erosketa burutzean: " + (response.message || "Ezezaguna"),
        );
        botoia.html("Ordaindu eta Erosketa Burutu");
        botoia.prop("disabled", false);
      }
    },
    error: function () {
      alert("Errorea zerbitzarira konektatzean.");
      botoia.html("Ordaindu eta Erosketa Burutu");
      botoia.prop("disabled", false);
    },
  });
}
