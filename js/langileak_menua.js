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
  });
});
