$(document).ready(function () {
  kargatuSaskia();
});

function kargatuSaskia() {
  var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
  var $edukiontzia = $("#saskia-xehetasunak");

  if (saskia.length === 0) {
    $edukiontzia.html("<p>Saskia hutsik dago. <a href='produktuak.php'>Itzuli dendara</a></p>");
    $(".botoi-container button").prop("disabled", true).css("opacity", "0.5");
    return;
  }

  var html = "<h4>Erosketaren Laburpena:</h4><ul>";
  var totala = 0;

  saskia.forEach(function (item) {
    var itemTotal = item.prezioa * item.kantitatea;
    totala += itemTotal;
      html += "<li>" + item.kantitatea + "x " + item.izena + " - " + item.prezioa + "€/unit (" + itemTotal.toFixed(2) + "€)</li>";
  });

  html += "</ul>";
  html += "<div class='saskia-totala'>Guztira: " + totala.toFixed(2) + " €</div>";

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

  var botoia = $(".botoi-container button");
  botoia.html('<i class="fas fa-spinner fa-spin"></i> Prozesatzen...');
  botoia.prop("disabled", true);

  var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];

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

        window.location.href = "ordainketa_pasarela.php?success=1";
      } else {
        alert(
          "Errorea erosketa burutzean: " + (response.message || "Ezezaguna")
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
