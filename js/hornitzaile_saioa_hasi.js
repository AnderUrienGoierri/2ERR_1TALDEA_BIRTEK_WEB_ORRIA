$(document).ready(function () {
    // --- ERREGISTRO FORMULARIOAREN BALIDAZIOA ---
    $(document).on("submit", "#hornitzaile-erregistro-form", function (e) {
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
            url: "lortu_pasahitza_hornitzailea.php",
            method: "POST",
            data: { emaila: emaila },
            success: function (erantzuna) {
                alert(erantzuna);
            },
            error: function () {
                alert("Errore bat gertatu da pasahitza berreskuratzean.");
            }
        });
    });

    // --- HERRIA AUKERATU (DATUAK ALDATU ORRIAN) ---
    $('#herria_id').on('change', function () {
        if ($(this).val() === 'berria') {
            $('#herri_berria_atala').slideDown();
            $('#herria_berria').attr('required', true);
            $('#lurraldea_berria').attr('required', true);
            $('#nazioa_berria').attr('required', true);
        } else {
            $('#herri_berria_atala').slideUp();
            $('#herria_berria').removeAttr('required');
            $('#lurraldea_berria').removeAttr('required');
            $('#nazioa_berria').removeAttr('required');
        }
    });
});
