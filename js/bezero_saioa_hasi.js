$(document).ready(function () {
  // --- ERREGISTRO FORMULARIOAREN BALIDAZIOA ---
  $(document).on("submit", "#bezero-erregistro-form, #hornitzaile-erregistro-form", function (e) {
    var pasahitza = $(this).find('input[name="pasahitza_erregistroa"]').val();
    var errorea = "";

    // 1. Luzera egiaztatu
    if (pasahitza.length < 8) {
      errorea = "Pasahitzak gutxienez 8 karaktere izan behar ditu.";
    }
    // 2. Karakter berezia egiaztatu
    else if (!/[!@#$%^&*(),.?":{}|<>]/.test(pasahitza)) {
      errorea = "Pasahitzak gutxienez karakter berezi bat izan behar du (!@#$%^&*...).";
    }

    // 3. NAN/IFZ luzera egiaztatu (9 karaktere)
    var nan = $(this).find('input[name="nan"]').val();
    if (nan && nan.length !== 9) {
      errorea = "NAN/IFZ zenbakiak 9 karaktere izan behar ditu.";
    }

    if (errorea !== "") {
      e.preventDefault();
      alert(errorea);
    }
  });

  // --- PASAHITZA AHAZTU DUZU? ---
  $(".pasahitza-ahaztu-esteka").on("click", function (e) {
    e.preventDefault();

    var emaila = $('input[name="emaila"]').val();

    if (!emaila) {
      alert("Mesedez, sartu zure posta elektronikoa lehenik.");
      return;
    }

    $.ajax({
      url: "lortu_pasahitza_bezeroa.php",
      method: "POST",
      data: { emaila: emaila },
      success: function (response) {
        alert(response);
      },
      error: function () {
        alert("Errore bat gertatu da pasahitza berreskuratzean.");
      }
    });
  });
});
