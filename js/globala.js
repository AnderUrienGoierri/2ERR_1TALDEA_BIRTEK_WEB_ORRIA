/* --- JavaScript jQuery .JS --- */

$(document).ready(function () {
  // --- KONFIGURAZIOA ---
  function getRelativePath(filename) {
    // Uneko path-a aztertu eta fitxategira iristeko bide egokia itzuli
    var path = window.location.pathname;
    // Berez PHP edo HTML karpetan gauden begiratu
    if (path.includes("/html/") || path.includes("/php/")) {
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

  function resolveUrl(targetType, targetFile) {
    var currentPath = window.location.pathname;
    var isSubDir =
      currentPath.includes("/html/") || currentPath.includes("/php/");

    if (targetType === "php") {
      if (isSubDir && currentPath.includes("/php/")) return targetFile; // PHP-n gaude
      if (isSubDir && currentPath.includes("/html/"))
        return "../php/" + targetFile; // HTML-n gaude
      return "php/" + targetFile; // Erroan gaude
    } else if (targetType === "html") {
      if (isSubDir && currentPath.includes("/html/")) return targetFile; // HTML-n gaude
      if (isSubDir && currentPath.includes("/php/"))
        return "../html/" + targetFile; // PHP-n gaude
      return "html/" + targetFile; // Erroan gaude
    }
    return targetFile;
  }

  // --- SESSION CHECK LOGIKA ---
  function checkSession() {
    var checkUrl = resolveUrl("php", "get_session_status.php");

    $.ajax({
      url: checkUrl,
      method: "GET",
      dataType: "json",
      success: function (data) {
        if (data.logged_in) {
          updateHeaderForLoggedInUser(data.izena);
          $(document).trigger("session:valid", [data.izena]);
        }
      },
      error: function (xhr, status, error) {
        console.error("Session check failed (" + checkUrl + "):", error);
      },
    });
  }

  function updateHeaderForLoggedInUser(izena) {
    var profileUrl = resolveUrl("php", "bezero_menua.php");

    // User wants JUST the name to appear where "Saioa Hasi" was, and click to go to menu.
    // We can also add a logout icon or small text, but the request emphasized clicking the name opens the menu.
    // To match the "Saioa Hasi" button style but show name:

    const logoutBtnHtml = `
      <div class="saio-info-edukiontzia">
        <a href="${profileUrl}" class="saioa-hasi-botoia aktibo" id="saioa-hasi-botoia" title="Joan Nire Menura">
            <i class="fas fa-user"></i> <span>${izena}</span>
        </a>
        <button id="saioa-itxi-botoia" class="saioa-hasi-botoia" style="background:#fee2e2; color:#991b1b; border-color:#f87171;">
            <i class="fas fa-sign-out-alt"></i>
        </button>
      </div>
    `;

    // Eguneratu mahaigaineko bertsioa (IDa edo klasea erabiliz)
    $(
      ".nab-ekintzak .saioa-hasi-botoia, .nab-ekintzak #saioa-hasi-botoia"
    ).replaceWith(logoutBtnHtml);

    // Eguneratu mugikorreko bertsioa
    // Mugikorrean "Langileak" azpian edo antzeko lekuan.
    $("#mugikor-menua .saioa-hasi-botoia").replaceWith(
      `<div class="mugikor-user-container">
         <a href="${profileUrl}" class="nab-botoia mugikor-user-link">
            <i class="fas fa-user"></i> ${izena} (Nire Menua)
         </a>
         <button id="saioa-itxi-botoia-mugikor" class="nab-botoia" style="color:red;">
            <i class="fas fa-sign-out-alt"></i> Saioa Itxi
         </button>
       </div>`
    );
  }

  // --- SASKI MODALA LOGIKA ---

  // HTML INJEKZIOA: Saskiaren egitura orrialdean ez badago, gehitu.
  if ($("#saski-modala").length === 0) {
    console.log("Saski modala injektatzen...");
    var saskiHtml = `
    <!-- SASKI MODALA (JS bidez injektatua) -->
    <div id="saski-modala" class="modal-geruza">
      <div class="modal-edukiontzia">
        <div class="modal-goiburua" style="position: relative; padding: 1rem; border-bottom: 1px solid #eee;">
          <h3 style="margin:0;">Saskia</h3>
          <button class="modal-itxi-botoia" id="modal-itxi-botoia-x">
            &times;
          </button>
        </div>

        <div id="saski-elementu-zerrenda" class="modal-edukia taula-scroll" style="padding: 1rem; flex: 1; overflow-y: auto;">
          <!-- JS bidez beteko da -->
        </div>
        <div class="saski-footer" style="padding: 1rem; border-top: 1px solid #eee;">
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
    console.log("Saski botoia injektatzen...")

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
    var totalCount = 0;
    saskia.forEach((item) => (totalCount += item.kantitatea));
    $(".saski-kontagailu-txapa").text(totalCount);
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
      saskia.forEach(function (item) {
        var itemTotala = item.prezioa * item.kantitatea;
        totala += itemTotala;

        var html = `
                  <div class="saski-item-lerroa">
                      <div class="saski-item-info">
                          <div class="saski-item-izena">${item.izena}</div>
                          <div class="saski-item-xehetasun">
                            ${item.prezioa.toFixed(2)} € x ${
          item.kantitatea
        } = <b>${itemTotala.toFixed(2)} €</b>
                          </div>
                      </div>
                      <div class="kantitate-kontrola">
                          <button class="saski-btn-qty" data-id="${
                            item.id
                          }" data-action="minus">-</button>
                          <span>${item.kantitatea}</span>
                          <button class="saski-btn-qty" data-id="${
                            item.id
                          }" data-action="plus">+</button>
                      </div>
                      <button class="saski-kendu-botoia saski-btn-qty" data-id="${
                        item.id
                      }" data-action="remove"><i class="fas fa-trash"></i></button>
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
    var badago = saskia.find((item) => item.id == id); // Loose equality id string vs number
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
    var $txapa = $(".saski-kontagailu-txapa");

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
    if ($(e.target).hasClass("modal-geruza")) {
      itxiModala();
    }
  });

  // 3. KANTITATEA ALDATU (+/-) eta EZABATU
  $(document).on("click", ".saski-btn-qty", function (e) {
    e.preventDefault(); // Stop default button behavior
    e.stopPropagation();
    var id = $(this).data("id");
    var akzioa = $(this).data("action");

    saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
    // IDa string vs number izan daiteke, "==" erabiltzen dugu konparatzeko
    var itemIndex = saskia.findIndex((i) => i.id == id);

    if (itemIndex > -1) {
      var item = saskia[itemIndex];

      if (akzioa === "plus") {
        item.kantitatea++;
      } else if (akzioa === "minus") {
        item.kantitatea--;
        if (item.kantitatea <= 0) {
          saskia.splice(itemIndex, 1);
        }
      } else if (akzioa === "remove") {
        saskia.splice(itemIndex, 1);
      }

      window.saskiaGorde(saskia);
      window.saskiaErakutsi();
    }
  });

  // 4. EROSI / ORDAINDU BOTOIA (Checkout check)
  $(document).on("click", "#erosketa-burutu-botoia", function (e) {
    e.preventDefault();
    var currentCart = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
    if (currentCart.length === 0) {
      alert("Saskia hutsik dago!");
      return;
    }

    var checkUrl = resolveUrl("php", "get_session_status.php");
    var loginUrl = resolveUrl("html", "bezeroa_saioa_hasi.html");
    var checkoutUrl = resolveUrl("php", "ordainketa_pasarela.php");

    $.ajax({
      url: checkUrl,
      method: "GET",
      dataType: "json",
      success: function (data) {
        if (!data.logged_in) {
          window.location.href = loginUrl;
        } else {
          window.location.href = checkoutUrl;
        }
      },
      error: function (xhr, status, error) {
        console.error("Errorea saioa egiaztatzean:", error);
        window.location.href = loginUrl;
      },
    });
  });

  // --- SAIOA ITXI EVENT ---
  $(document).on(
    "click",
    "#saioa-itxi-botoia, #saioa-itxi-botoia-mugikor",
    function (e) {
      e.preventDefault();
      var logoutUrl = resolveUrl("php", "logout_bezeroa.php");
      var loginUrl = resolveUrl("html", "bezeroa_saioa_hasi.html");

      $.ajax({
        url: logoutUrl,
        method: "POST",
        success: function () {
          const path = window.location.pathname;
          if (
            path.includes("bezero_menua") ||
            path.includes("bezero_datuak") ||
            path.includes("bezero_eskaerak") ||
            path.includes("ordainketa_pasarela")
          ) {
            window.location.href = loginUrl;
          } else {
            location.reload();
          }
        },
        error: function () {
          window.location.href = loginUrl;
        },
      });
    }
  );

  checkSession();

  // Menu mugikorraren logika zentralizatua
  $("#mugikor-menu-botoia").click(function () {
    $("#mugikor-menua").toggleClass("erakutsi");
  });
});
