/* --- Langileak Menua Logika Espezifikoa --- */
$(document).ready(function () {
  // Java aplikazioa abiarazteko botoia
  $(".birtek-java-ap-botoia").click(function (e) {
    e.preventDefault();
    var botoia = $(this);
    var jatorrizkoEdukia = botoia.html(); // .php() konponduta .html()-ra

    // Feedback bisuala eman
    botoia.html('<i class="fas fa-cog fa-spin"></i> Abiarazten...');
    botoia.prop("disabled", true);

    
    // PHP script-a deitu
    /*
    $.ajax({
      url: "../php/java_app_abiarazi.php",
      type: "GET",
      success: function (response) {
        console.log("Java App launch response: " + response);
        // Botoia berrezarri 3 segundu barru
        setTimeout(function () {
          botoia.html(jatorrizkoEdukia);
          botoia.prop("disabled", false);
        }, 3000);
      },
      error: function (xhr, status, error) {
        console.error("Launch error:", error);
        alert(
          "Errorea aplikazioa abiaraztean. Ziurtatu XAMPP martxan dagoela."
        );
        console.error("Launch error:", error);
        alert(
          "Errorea aplikazioa abiaraztean. Ziurtatu XAMPP martxan dagoela."
        );
        botoia.html(jatorrizkoEdukia);
        botoia.prop("disabled", false);
      },
    });
    */
  });

  // Eskaera formularioaren bidalketa arrakasta mezua(AJAX)
  $("#langile-eskaera-inprimakia").on("submit", function (e) {
    e.preventDefault();
    var $form = $(this);
    var formData = new FormData(this);
    var $submitBtn = $form.find('button[type="submit"]');

    // Desgaitu botoia klick egitean eta "Bidaltzen..." jarri
    $submitBtn.prop("disabled", true).text("Bidaltzen...");

    $.ajax({
      url: "../php/gorde_eskaera_langilea.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        // Formularioa garbitu eta mezua erakutsi
        $(".inprimaki-kutxa").html(
          '<div class="mezua-kutxa-arrakasta">' +
            '<i class="fas fa-check-circle fa-4x mezua-ikonoa-arrakasta"></i>' +
            '<h3 class="mezua-titulua-arrakasta">Eskaera bidalita, eskerrikasko!</h3>' +
            '<p class="testua-grisa tartea-goian-1">Laster jarriko gara zurekin harremanetan.</p>' +
            '<button class="botoia botoi-nagusia tartea-goian-2" onclick="location.reload()">Itzuli</button>' +
            "</div>"
        );
      },
      error: function (xhr, status, error) {
        console.error("Errorea:", error);
        alert("Errorea gertatu da eskaera bidaltzean. Mesedez, saiatu berriro.");
        $submitBtn.prop("disabled", false).text("Eskaera Bidali");
      },
    });
  });
});
