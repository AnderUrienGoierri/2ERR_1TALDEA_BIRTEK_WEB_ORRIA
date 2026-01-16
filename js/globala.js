/* --- JavaScript jQuery .JS --- */

$(document).ready(function () {
  // --- KONFIGURAZIOA ---
  function lortuBideErlatiboa(filename) {
    // Uneko path-a aztertu eta fitxategira iristeko bide egokia itzuli
    var bidea = window.location.pathname;
    // Berez PHP edo HTML karpetan gauden begiratu
    if (bidea.includes("/html/") || bidea.includes("/php/")) {
      // Karpeta barruan bagaude, erroa lortzeko ".." behar dugu? Ez, karpeta berean badaude "filename" zuzenean.
      // Baina PHP eta HTML fitxategi batzuk karpeta desberdinetan daude.
      // Konbentzioa:
      // HTML -> ../php/filename (baldin eta php bada target)
      // HTML -> filename (baldin eta html bada target)
      // Sinplifikatzeko: PHP fitxategietarako beti absolute path antzekoa edo ../php erabiltzea seguruagoa da
      // kasu honetan, logika espezifikoa behar dugu.
    }
    return filename;
  }

  function ebatziUrl(helburuMota, helburuFitxategia) {
    var unekoBidea = window.location.pathname;
    var azpiKarpetaDa =
      unekoBidea.includes("/html/") || unekoBidea.includes("/php/");

    if (helburuMota === "php" || helburuMota === "html") {
      // Guztia php karpetan dagoela suposatzen dugu orain
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

  // --- SESSION CHECK LOGIKA ---
  function egiaztatuSaioa() {
    var egiaztatuUrl = ebatziUrl("php", "get_saio_egoera.php");

    $.ajax({
      url: egiaztatuUrl,
      method: "GET",
      dataType: "json",
      success: function (data) {
        if (data.saioa_hasita) {
          eguneratuGoiburuaErabiltzailearentzat(data.izena);
          $(document).trigger("saioa:baliozkoa", [data.izena, data.mota]);

          // Check for session conflict on login pages
          egiaztatuSaioaGatazka(data.mota);
        }
      },
      error: function (xhr, status, error) {
        console.error("Session check failed (" + egiaztatuUrl + "):", error);
      },
    });
  }

  function egiaztatuSaioaGatazka(unekoMota) {
    // Determine if we are on a login page
    var bidea = window.location.pathname;

    // Logic: If I am logged in as 'bezeroa', I cannot log in as 'hornitzailea' without logout
    // If I am logged in as 'hornitzailea', I cannot log in as 'bezeroa' without logout

    // Detect page context
    var hornitzaileSaioaHasiDa = bidea.includes("hornitzaile_saioa_hasi.php");
    var bezeroSaioaHasiDa = bidea.includes("bezero_saioa_hasi.php");

    if (
      (hornitzaileSaioaHasiDa && unekoMota === "bezeroa") ||
      (bezeroSaioaHasiDa && unekoMota === "hornitzailea")
    ) {
      // Disable forms and show alert on interaction
      $("form").on("submit", function (e) {
        e.preventDefault();
        alert("Beste Saioa Itxi Behar duzu");
      });

      // Also potentially alert immediately or visually indicate
      console.log(
        "Saio gatazka: " + unekoMota + " kontrako saioa hasteko orrian."
      );
    }
  }

  function eguneratuGoiburuaErabiltzailearentzat(izena) {
    var profilUrl = ebatziUrl("php", "bezero_menua.php");

    const saioaItxiBotoiHtml = `
      <div class="saio-informazio-edukiontzia">
        <a href="${profilUrl}" class="saioa-hasi-botoia aktibo" id="saioa-hasi-botoia" title="Joan Nire Menura">
            <i class="fas fa-user"></i> <span>${izena}</span>
        </a>
        <button id="saioa-itxi-botoia" class="saioa-hasi-botoia botoi-gorria">
            <i class="fas fa-sign-out-alt"></i>
        </button>
      </div>
    `;

    // Eguneratu mahaigaineko bertsioa
    // Target both the login button and the existing container if any
    var $desktopInformazioa = $(".nab-ekintzak .saio-informazio-edukiontzia");
    if ($desktopInformazioa.length > 0) {
      $desktopInformazioa.replaceWith(saioaItxiBotoiHtml);
    } else {
      $(".nab-ekintzak .saioa-hasi-botoia").replaceWith(saioaItxiBotoiHtml);
    }

    // Eguneratu mugikorreko bertsioa
    var $mugikorErabiltzaile = $(
      "#mugikor-menua .mugikor-erabiltzaile-edukiontzia"
    );
    const mugikorHtml = `
      <div class="mugikor-erabiltzaile-edukiontzia">
         <a href="${profilUrl}" class="nab-botoia mugikor-erabiltzaile-link">
            <i class="fas fa-user"></i> ${izena}
         </a>
         <button id="mugikor-saioa-itxi-botoia" class="nab-botoia mugikor-logout-botoia" style="width: calc(100% - 3rem); margin: 0.5rem 1.5rem; text-align: left;">
            <i class="fas fa-sign-out-alt"></i> Saioa Itxi
         </button>
       </div>
    `;

    if ($mugikorErabiltzaile.length > 0) {
      $mugikorErabiltzaile.replaceWith(mugikorHtml);
    } else {
      $("#mugikor-menua .saioa-hasi-botoia").remove();
      $("#mugikor-menua").append(mugikorHtml);
    }
  }

  // --- SASKI MODALA LOGIKA ---

  // HTML INJEKZIOA: Saskiaren egitura orrialdean ez badago, gehitu.
  if ($("#saski-modala").length === 0) {
    console.log("Saski modala injektatzen...");
    var saskiHtml = `
    <!-- SASKI MODALA (JS bidez injektatua) -->
    <div id="saski-modala" class="modala-geruza">
      <div class="modala-edukiontzia">
        <div class="modala-goiburua" style="position: relative; padding: 1rem; border-bottom: 1px solid #eee;">
          <h3 style="margin:0;">Saskia</h3>
          <button class="modala-itxi-botoia" id="modal-itxi-botoia-x">
            &times;
          </button>
        </div>

        <div id="saski-elementu-zerrenda" class="modala-edukia taula-scroll" style="padding: 1rem; flex: 1; overflow-y: auto;">
          <!-- JS bidez beteko da -->
        </div>
        <div class="saski-oina" style="padding: 1rem; border-top: 1px solid #eee;">
          <div class="saski-guztira-lerroa" style="display:flex; justify-content:space-between; margin-bottom:1rem; font-weight:bold;">
            <span>Guztira:</span><span id="saski-guztira">0.00 €</span>
          </div>
          <button
            id="erosketa-burutu-botoia"
            class="botoia botoi-nagusia botoi-zabalera-osoa-top"
            style="width:100%; margin-bottom:0.5rem;"
          >
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
    console.log("Saski modala 'body'-ra gehitua.");
  }

  // BOTOIA INJEKZIOA: Saski botoia ez badago, gehitu
  if ($(".saski-botoia").length === 0) {
    console.log("Saski botoia injektatzen...");

    var botoiHtml = `
            <button class="saski-botoia" id="saski-botoia-injektatua">
              <i class="fas fa-shopping-cart"></i>
              <span>Saskia</span>
              <span class="saski-kontagailu-txapa">0</span>
            </button>
      `;
    $(".nab-ekintzak").append(botoiHtml);
  } else {
    // Botoia badago, ziurtatu ikusgai dagoela
    $(".saski-botoia").css("display", "inline-flex"); // Edo flex, CSSaren arabera
  }

  // Saskia berreskuratu LocalStorage-tik
  var saskia = [];
  try {
    saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
  } catch (e) {
    console.error("Errorea saskia irakurtzean:", e);
    saskia = [];
    localStorage.removeItem("birtek_saskia");
  }

  // Funtzio globalak definitu
  window.saskiKontagailuaEguneratu = function () {
    var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
    var kopuruGuztira = 0;
    saskia.forEach((elementua) => (kopuruGuztira += elementua.kantitatea));
    $(".saski-kontagailua").text(kopuruGuztira);
  };

  window.saskiaGorde = function (saskiaBerria) {
    if (saskiaBerria) saskia = saskiaBerria;
    localStorage.setItem("birtek_saskia", JSON.stringify(saskia));
    window.saskiKontagailuaEguneratu();
  };

  window.saskiaErakutsi = function () {
    console.log("Saskia erakusten...");
    saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
    var $zerrenda = $("#saski-elementu-zerrenda");

    if ($zerrenda.length === 0) {
      console.error(
        "#saski-elementu-zerrenda elementua ez da aurkitu DOM-ean. Injekzioak huts egin du?"
      );
      return;
    }

    $zerrenda.empty();
    var totala = 0;

    if (saskia.length === 0) {
      $zerrenda.html(
        '<p style="text-align:center; padding:1rem;">Saskia hutsik dago.</p>'
      );
    } else {
      saskia.forEach(function (elementua) {
        var elementuTotala = elementua.prezioa * elementua.kantitatea;
        totala += elementuTotala;

        var html = `
                  <div class="saski-elementu-lerroa">
                      <div class="saski-elementu-informazioa">
                          <div class="saski-elementu-izena">${
                            elementua.izena
                          }</div>
                          <div class="saski-elementu-xehetasun">
                            ${elementua.prezioa.toFixed(2)} € x ${
          elementua.kantitatea
        } = <b>${elementuTotala.toFixed(2)} €</b>
                          </div>
                      </div>
                      <div class="kantitate-kontrola">
                          <button class="saski-botoi-kop" data-id="${
                            elementua.id
                          }" data-ekintza="minus">-</button>
                          <span>${elementua.kantitatea}</span>
                          <button class="saski-botoi-kop" data-id="${
                            elementua.id
                          }" data-ekintza="plus">+</button>
                      </div>
                      <button class="saski-kendu-botoia saski-botoi-kop" data-id="${
                        elementua.id
                      }" data-ekintza="remove"><i class="fas fa-trash"></i></button>
                  </div>
                `;
        $zerrenda.append(html);
      });
    }
    $("#saski-guztira").text(totala.toFixed(2) + " €");
  };

  // Hasierako eguneraketa
  window.saskiKontagailuaEguneratu();

  // --- SASKIA GEHITU LOGIKA (BERRIA) ---
  window.saskiaGehitu = function (id, izena, prezioa, stock, $botoiaRef) {
    // Saskia berreskuratu
    var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];

    // Begiratu ea saskian dagoen jada
    var badago = saskia.find((elementua) => elementua.id == id); // Loose equality id string vs number
    var saskianDagoenKantitatea = badago ? badago.kantitatea : 0;

    if (saskianDagoenKantitatea + 1 > stock) {
      alert("Ezin da gehitu: Stock nahikorik ez.");
      return false;
    }

    if (badago) {
      badago.kantitatea++;
    } else {
      saskia.push({
        id: id,
        izena: izena,
        prezioa: prezioa,
        kantitatea: 1,
        stock: stock,
      });
    }

    // Gorde
    window.saskiaGorde(saskia);

    // Animazioa exekutatu (botoiaren erreferentzia badugu)
    if ($botoiaRef && $botoiaRef.length > 0) {
      saskiratuAnimazioaGlobala($botoiaRef);
    }

    return true;
  };

  function saskiratuAnimazioaGlobala($botoia) {
    var $txapa = $(".saski-kontagailua");

    // --- BOTOIAREN ANIMAZIOA ---
    $botoia.stop(true, true);
    $({ scale: 1 }).animate(
      { scale: 1.2 },
      {
        duration: 150,
        easing: "linear",
        step: function (now) {
          $botoia.css({ transform: "scale(" + now + ")" });
        },
        complete: function () {
          $({ scale: 1.2 }).animate(
            { scale: 1 },
            {
              duration: 150,
              easing: "linear",
              step: function (now) {
                $botoia.css({ transform: "scale(" + now + ")" });
              },
            }
          );
        },
      }
    );

    // --- TXAPAREN ANIMAZIOA ---
    $txapa.stop(true, true);
    $({ scale: 1 }).animate(
      { scale: 1.5 },
      {
        duration: 200,
        easing: "linear",
        step: function (now) {
          $txapa.css({ transform: "scale(" + now + ")" });
        },
        complete: function () {
          $({ scale: 1.5 }).animate(
            { scale: 1 },
            {
              duration: 200,
              easing: "linear",
              step: function (now) {
                $txapa.css({ transform: "scale(" + now + ")" });
              },
            }
          );
        },
      }
    );

    // --- MEZUA ---
    if ($(".saskiratze-mezua").length === 0) {
      // Ziurtatu nab-ekintzak existitzen dela
      if ($(".nab-ekintzak").length === 0) {
        // Fallback: body-ri gehitu absolute positioning-ekin
        $("body").append(
          '<div class="saskiratze-mezua" style="position:fixed; top:20px; right:20px; z-index:9999;">Produktua saskira gehitu da</div>'
        );
      } else {
        $(".nab-ekintzak").append(
          '<div class="saskiratze-mezua">Produktua saskira gehitu da</div>'
        );
      }
    }
    $(".saskiratze-mezua")
      .stop(true, true)
      .hide()
      .fadeIn(200)
      .delay(2000)
      .fadeOut(200);
  }

  // 1. SASKIA IREKI (Delegatuta)
  $(document).on("click", ".saski-botoia, #saski-botoia-toggle", function (e) {
    e.preventDefault();
    console.log("Saski botoia klikatua (delegatuta).");

    // Ziurtatu modala sortuta dagoela
    if ($("#saski-modala").length === 0) {
      console.warn(
        "Saski modala ez da aurkitu, orria birkargatu beharko litzateke edo JS berrabiarazi."
      );
      location.reload(); // Simple error recovery
      return;
    }

    window.saskiaErakutsi();
    // Force styles with important
    $("#saski-modala").css("display", "flex").addClass("erakutsi");
  });

  // 2. MODALA ITXI
  function itxiModala() {
    $("#saski-modala").removeClass("erakutsi").css("display", "");
  }

  $(document).on(
    "click",
    "#modal-itxi-botoia-x, #modal-itxi-botoia-behea",
    function (e) {
      e.preventDefault();
      itxiModala();
    }
  );

  $(window).click(function (e) {
    if ($(e.target).hasClass("modala-geruza")) {
      itxiModala();
    }
  });

  // 3. KANTITATEA ALDATU (+/-) eta EZABATU
  $(document).on("click", ".saski-botoi-kop", function (e) {
    e.preventDefault(); // Stop default button behavior
    e.stopPropagation();
    var id = $(this).data("id");
    var ekintza = $(this).data("ekintza");

    saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
    // IDa string vs number izan daiteke, "==" erabiltzen dugu konparatzeko
    var elementuIndizea = saskia.findIndex((i) => i.id == id);

    if (elementuIndizea > -1) {
      var elementua = saskia[elementuIndizea];

      if (ekintza === "plus") {
        elementua.kantitatea++;
      } else if (ekintza === "minus") {
        elementua.kantitatea--;
        if (elementua.kantitatea <= 0) {
          saskia.splice(elementuIndizea, 1);
        }
      } else if (ekintza === "remove") {
        saskia.splice(elementuIndizea, 1);
      }

      window.saskiaGorde(saskia);
      window.saskiaErakutsi();
    }
  });

  // 4. EROSI / ORDAINDU BOTOIA (Checkout check)
  $(document).on("click", "#erosketa-burutu-botoia", function (e) {
    e.preventDefault();
    var unekoSaskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
    if (unekoSaskia.length === 0) {
      alert("Saskia hutsik dago!");
      return;
    }

    var egiaztatuUrl = ebatziUrl("php", "get_saio_egoera.php");
    var saioaHasiUrl = ebatziUrl("php", "bezero_saioa_hasi.php");
    // New Flow: Go to Review/Address page first
    var erosketaBerretsiUrl = ebatziUrl("php", "bezero_erosketa.php");

    $.ajax({
      url: egiaztatuUrl,
      method: "GET",
      dataType: "json",
      success: function (data) {
        if (!data.saioa_hasita) {
          window.location.href = saioaHasiUrl;
        } else {
          window.location.href = erosketaBerretsiUrl;
        }
      },
      error: function (xhr, status, error) {
        console.error("Errorea saioa egiaztatzean:", error);
        window.location.href = saioaHasiUrl;
      },
    });
  });

  // --- SAIOA ITXI EVENT ---
  $(document).on(
    "click",
    "#saioa-itxi-botoia, #mugikor-saioa-itxi-botoia",
    function (e) {
      e.preventDefault();
      var saioaItxiUrl = ebatziUrl("php", "logout_bezeroa.php");
      var saioaHasiUrl = ebatziUrl("php", "bezero_saioa_hasi.php");

      $.ajax({
        url: saioaItxiUrl,
        method: "POST",
        success: function () {
          const bidea = window.location.pathname;
          if (
            bidea.includes("bezero_menua") ||
            bidea.includes("bezero_datuak") ||
            bidea.includes("bezero_eskaerak") ||
            bidea.includes("ordainketa_pasarela")
          ) {
            window.location.href = saioaHasiUrl;
          } else {
            location.reload();
          }
        },
        error: function () {
          window.location.href = saioaHasiUrl;
        },
      });
    }
  );

  egiaztatuSaioa();

  // Menu mugikorraren logika zentralizatua
  $("#mugikor-menu-botoia").click(function () {
    $("#mugikor-menua").toggleClass("erakutsi");
  });
});

// Global function for City Toggle
function toggleHerriaInput(mota) {
  var hautatu = document.getElementById("herria_id_" + mota);
  var edukiontzia = document.getElementById("herria_berria_container_" + mota);

  if (hautatu && hautatu.value === "other") {
    edukiontzia.style.display = "block";
    var sarreraHerria = edukiontzia.querySelector(
      'input[name="herria_berria"]'
    );
    var sarreraLurraldea = edukiontzia.querySelector(
      'input[name="lurraldea_berria"]'
    );
    if (sarreraHerria) sarreraHerria.required = true;
    if (sarreraLurraldea) sarreraLurraldea.required = true;
  } else if (edukiontzia) {
    edukiontzia.style.display = "none";
    var sarreraHerria = edukiontzia.querySelector(
      'input[name="herria_berria"]'
    );
    var sarreraLurraldea = edukiontzia.querySelector(
      'input[name="lurraldea_berria"]'
    );
    if (sarreraHerria) {
      sarreraHerria.required = false;
      sarreraHerria.value = "";
    }
    if (sarreraLurraldea) {
      sarreraLurraldea.required = false;
      sarreraLurraldea.value = "";
    }
  }
}
