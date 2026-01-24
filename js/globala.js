/* --- JavaScript jQuery .JS --- */

$(document).ready(function () {
  // --- KONFIGURAZIOA ---
  function ebatziUrl(helburuMota, helburuFitxategia) {
    var unekoBidea = window.location.pathname;
    var azpiKarpetaDa =
      unekoBidea.includes("/html/") || unekoBidea.includes("/php/");

    if (helburuMota === "php" || helburuMota === "html") {
      var file = helburuFitxategia;
      if (helburuMota === "html" && file.endsWith(".html")) {
        file = file.replace(".html", ".php");
      }

      if (azpiKarpetaDa && unekoBidea.includes("/php/")) return file;
      if (azpiKarpetaDa && unekoBidea.includes("/html/"))
        return "../php/" + file;
      return "php/" + file;
    }
    return helburuFitxategia;
  }


  // --- SASKI MODALA INJEKZIOA ---
  if ($("#saski-modala").length === 0) {
    var saskiHtml = `
    <div id="saski-modala" class="modala-geruza">
      <div class="modala-edukiontzia">
        <div class="modala-goiburua">
          <h3>Saskia</h3>
          <button class="modala-itxi-botoia" id="modal-itxi-botoia-x">&times;</button>
        </div>

        <div id="saski-elementu-zerrenda" class="modala-edukia taula-scroll">
          <!-- JS bidez beteko da -->
        </div>
        <div class="saski-oina">
          <div class="saski-guztira-lerroa">
            <span>Guztira:</span><span id="saski-guztira">0.00 €</span>
          </div>
          <button id="erosketa-burutu-botoia" class="botoia botoi-nagusia botoi-zabalera-osoa-top">
            Erosketa Burutu
          </button>
          <button class="saski-itxi-zentratua" id="modal-itxi-botoia-behea">
            Jarraitu Erosketak Egiten
          </button>
        </div>
      </div>
    </div>
    `;
    $("body").append(saskiHtml);
  }



  // --- SASKIAREN LOGIKA (GLOBAL) ---

  // 1. Gorde saskia LocalStoragen eta eguneratu GUI
  window.saskiaGorde = function (saskia) {
    localStorage.setItem("birtek_saskia", JSON.stringify(saskia));
    window.saskiaEguneratuKontagailua();
  };

  // 2. Gehitu elementu bat saskiara
  window.saskiaGehitu = function (id, izena, prezioa, stock, $botoia) {
    var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
    var badago = saskia.find((item) => item.id == id);

    if (badago) {
      if (badago.kantitatea < stock) {
        badago.kantitatea++;
      } else {
        alert("Ezin da gehiago gehitu, stock-a agortu da.");
        return;
      }
    } else {
      saskia.push({
        id: id,
        izena: izena,
        prezioa: prezioa,
        kantitatea: 1,
        stock: stock,
      });
    }

    window.saskiaGorde(saskia);

    // 1. Notification message (#saski-mezua)
    var $mezua = $("#saski-mezua");
    if ($mezua.length > 0) {
      $mezua.text("Saskira gehituta!").addClass("erakutsi");
      setTimeout(function () {
        $mezua.removeClass("erakutsi");
      }, 2000);
    }

    // 2. Button Pop Animation
    if ($botoia) {
      $botoia.addClass("saski-pop-animazioa");
      setTimeout(function () {
        $botoia.removeClass("saski-pop-animazioa");
      }, 300);
    }
  };

  // 3. Erakutsi saskiaren edukia modalean
  window.saskiaErakutsi = function () {
    var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
    var $zerrenda = $("#saski-elementu-zerrenda");
    var $guztira = $("#saski-guztira");
    var guztiraPrezioa = 0;

    $zerrenda.empty();

    if (saskia.length === 0) {
      $zerrenda.html("<p class='saskia-hutsik'>Saskia hutsik dago.</p>");
    } else {
      var taulaHtml = `
        <table class="saski-taula">
          <thead>
            <tr>
              <th>Produktua</th>
              <th>Kop</th>
              <th>Prezioa</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
      `;

      $.each(saskia, function (index, item) {
        var azpiTotala = item.prezioa * item.kantitatea;
        guztiraPrezioa += azpiTotala;
        taulaHtml += `
          <tr>
            <td>${item.izena}</td>
            <td class="kopuru-kontrola-td">
              <button class="kop-mod-btn minus" data-id="${item.id}" data-aldatu="-1">-</button>
              <span class="kopuru-balioa">${item.kantitatea}</span>
              <button class="kop-mod-btn plus" data-id="${item.id}" data-aldatu="1">+</button>
            </td>
            <td>${azpiTotala.toFixed(2)}€</td>
            <td><button class="saski-ezabatu-txikia" data-id="${item.id}">&times;</button></td>
          </tr>
        `;
      });

      taulaHtml += "</tbody></table>";
      $zerrenda.append(taulaHtml);
    }

    $guztira.text(guztiraPrezioa.toFixed(2) + " €");
  };

  // 4. Eguneratu saski kontagailua (badge)
  window.saskiaEguneratuKontagailua = function () {
    var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
    var kopuruTotala = 0;
    $.each(saskia, function (index, item) {
      kopuruTotala += item.kantitatea;
    });
    $(".saski-kontagailua").text(kopuruTotala).addClass("saski-pop-animazioa");

    setTimeout(function () {
      $(".saski-kontagailua").removeClass("saski-pop-animazioa");
    }, 300);
  };

  // 5. Ezabatu elementu bat
  window.saskiaEzabatu = function (id) {
    var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
    var berria = saskia.filter((item) => item.id != id);
    window.saskiaGorde(berria);
    window.saskiaErakutsi();
  };

  // 6. Aldatu kantitatea (+/-)
  window.saskiaAldatuKantitatea = function (id, aldatu) {
    var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
    var item = saskia.find((i) => i.id == id);
    if (item) {
      var nKop = item.kantitatea + aldatu;

      // Stock konprobazioa
      if (aldatu > 0 && nKop > item.stock) {
        alert("Ezin da gehiago gehitu, stock-a agortu da.");
        return;
      }

      if (nKop > 0) {
        item.kantitatea = nKop;
      } else {
        saskia = saskia.filter((i) => i.id != id);
      }
      window.saskiaGorde(saskia);
      window.saskiaErakutsi();
    }
  };

  // --- SASKI INTERAKZIOAK (EVENT LISTENERS) ---

  // Ireki saskia
  $(document).on("click", "#saski-botoia-toggle", function () {
    window.saskiaErakutsi();
    $("#saski-modala").fadeIn(200).css("display", "flex"); // Flex beharrezkoa da zentratzeko
  });

  // Itxi saskia
  $(document).on(
    "click",
    "#modal-itxi-botoia-x, #modal-itxi-botoia-behea",
    function () {
      $("#saski-modala").fadeOut(200);
    },
  );

  // Itxi kanpoan klik egitean
  $(document).on("click", "#saski-modala", function (e) {
    if ($(e.target).hasClass("modala-geruza")) {
      $(this).fadeOut(200);
    }
  });

  // Erosketa burutu (Redirekzioa)
  $(document).on("click", "#erosketa-burutu-botoia", function () {
    var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
    if (saskia.length === 0) {
      alert("Saskia hutsik dago!");
      return;
    }
    window.location.href = ebatziUrl("php", "bezero_erosketa.php");
  });

  // Saskiko kantitatea aldatu botoiak
  $(document).on("click", ".kop-mod-btn", function () {
    var id = $(this).data("id");
    var aldatu = parseInt($(this).data("aldatu"));
    window.saskiaAldatuKantitatea(id, aldatu);
  });

  // Saskiko ezabatu botoia
  $(document).on("click", ".saski-ezabatu-txikia", function () {
    var id = $(this).data("id");
    window.saskiaEzabatu(id);
  });

  // Hasieratu kontagailua orria kargatzean
  window.saskiaEguneratuKontagailua();

  // --- SAIOA ITXI LOGIKA ---
  $(document).on(
    "click",
    "#logout-botoia-menua, .saioa-itxi-botoia, .mugikor-logout-botoia, .saio-informazio-edukiontzia .botoi-gorria",
    function (e) {
      e.preventDefault();
      if (confirm("Ziur zaude saioa itxi nahi duzula?")) {
        window.location.href = ebatziUrl("php", "logout.php");
      }
    },
  );

  // --- MUGIKOR MENU LOGIKA ---
  $(document).on("click", "#mugikor-menu-botoia", function () {
    $("#mugikor-menua").toggleClass("erakutsi");
  });
});
