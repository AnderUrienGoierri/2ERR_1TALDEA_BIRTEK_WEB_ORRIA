/* --- Langileak Menua Logika Espezifikoa --- */
$(document).ready(function () {
  // Java aplikazioa abiarazteko botoia
  $(".birtek-java-ap-botoia").click(function (e) {
    e.preventDefault();
    var btn = $(this);
    var originalContent = btn.html(); // .php() konponduta .html()-ra

    // Feedback bisuala eman
    btn.html('<i class="fas fa-cog fa-spin"></i> Abiarazten...');
    btn.prop("disabled", true);

    // PHP script-a deitu
    $.ajax({
      url: "../php/launch_java_app.php",
      type: "GET",
      success: function (response) {
        console.log("Java App launch response: " + response);
        // Botoia berrezarri 3 segundu barru
        setTimeout(function () {
          btn.html(originalContent);
          btn.prop("disabled", false);
        }, 3000);
      },
      error: function (xhr, status, error) {
        console.error("Launch error:", error);
        alert(
          "Errorea aplikazioa abiaraztean. Ziurtatu XAMPP martxan dagoela."
        );
        btn.html(originalContent);
        btn.prop("disabled", false);
      },
    });
  });
});
