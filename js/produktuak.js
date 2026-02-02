var produktuGuztiak = []; // Produktu guztiak gordetzeko (filtratzeko)

// PRODUKTU SAREA OSATU:
$(document).ready(function () {
  // Produktu kopuru info estiloak (jQuery-rekin)
  $("#produktu-kopuru-info").addClass("produktu-kopuru-info");

  $("#kopurua-txapa").addClass("kopurua-txapa");

  // Begiratu ea zerbitzaritik (PHP) datuak jada badatozen (hasierakoProduktuak)
  if (
    typeof hasierakoProduktuak !== "undefined" &&
    Array.isArray(hasierakoProduktuak)
  ) {
    // Datuak jada hemen daude: ez egin ajax deirik.
    // Gorde aldagai globalean iragazkientzat
    produktuGuztiak = hasierakoProduktuak;
    console.log("Produktuak PHPtik kargatuta.", produktuGuztiak.length);
  } else {
    console.error(
      "Errorea: Produktuak ez dira kargatu. 'hasierakoProduktuak' ez dago definituta.",
    );
    $(".produktu-sarea").html(
      "<p>Errorea: Ezin izan dira produktuak kargatu.</p>",
    );
  }

  // Iragazkietan aldaketak detektatu
  $(
    "#iragazkia-bilatu, #iragazkia-egoera, #iragazkia-kategoria, #iragazkia-mota, #iragazkia-ordenatu, #prezioa-min, #prezioa-max",
  ).on("input change", function () {
    produktuakFiltratu();
  });

  // Radio buttons aldaketak detektatu (Prezioa ordenatu)
  $("input[name='prezio-ordenatu']").on("change", function () {
      var balioa = $(this).val();
      window.location.href = "produktuak.php?prezio-ordenatu=" + balioa;
  });

  // "FILTROAK GARBITU" botoia
  $(".iragazkiak-berrezarri").on("click", function () {
    // URL garbitu
    window.location.href = "produktuak.php";
    /*
    $("#iragazkia-bilatu").val("");
    $("#iragazkia-egoera").val("");
    $("#iragazkia-kategoria").val("");
    $("#iragazkia-mota").val("");
    $("#iragazkia-ordenatu").val("default");
    $("#prezioa-min").val("");
    $("#prezioa-max").val("");
    $("input[name='prezio-ordenatu']").prop("checked", false);
    produktuakFiltratu(); // Berrezarri ondoren filtratu (denak erakusteko)
    */
  });

  // MUGIKORRA ETA IDAZMAHAIA: Iragazkiak erakutsi/ezkutatu
  $(".iragazki-goiburua").on("click", function () {
    $(".iragazki-edukia").slideToggle();
  });

  // Leihoa aldatzean ez dugu ezer behartuko, erabiltzaileak erabaki dezala.
  // Nahi izanez gero, hemen jarri daiteke logika bereziren bat, baina
  // orokorrean hobe da 'slideToggle'-k jarritakoa errespetatzea edo
  // CSS bidez kudeatzea hasierako egoera.
});

function produktuakBistaratu(produktuak) {
  var $sarea = $(".produktu-sarea");
  $sarea.empty();

  if (produktuak.length === 0) {
    $sarea.html("<p>Ez da produkturik aurkitu irizpide hauekin.</p>");
    return;
  }

  $.each(produktuak, function (index, produktua) {
    var stockKlasea =
      produktua.stock > 0 ? "txartel-stock" : "txartel-stock-agortuta";

    var txartelaHtml = `
      <div class="produktu-txartela" data-id="${produktua.id_produktua}">
        <div class="txartel-irudia klikagarria-joan">
          <img
            src="${produktua.irudia_url}"
            alt="${produktua.izena}"
            class="txartel-irudia"
            onerror="this.src='../irudiak/birtek1.jpeg'" 
          />
          <div class="txartel-kategoria-txapa">${produktua.id_kategoria}</div> 
        </div>
        <div class="txartel-edukia">
          <h3 class="txartel-izenburua klikagarria-joan">${produktua.izena}</h3>
          <div class="txartel-informazio-lerroa">
            <span class="txartel-marka">${produktua.marka} | ${
              produktua.egoera
            }</span>
            <span class="${stockKlasea}">Stock: ${produktua.stock}</span>
          </div>
          <p class="txartel-azalpena">
            ${produktua.deskribapena || ""}
          </p>

          <div class="txartel-oina">
            <span class="txartel-prezioa">${produktua.prezioa.toFixed(
              2,
            )} €</span>
            <button class="produktua-saskiratu-botoia" data-stock="${
              produktua.stock
            }" ${produktua.stock === 0 ? "disabled" : ""}>
              Saskiratu
            </button>
            <button class="produktua-ikusi-botoia klikagarria-joan">Ikusi</button>
          </div>
        </div>
      </div>
    `;
    $sarea.append(txartelaHtml);
  });
}

// Produktuak klikatzean
$(document).on("click", ".klikagarria-joan", function (e) {
  var id = $(this).closest(".produktu-txartela").data("id");
  if (id) {
    window.location.href = "produktua_xehetasunak.php?id=" + id;
  }
});

function produktuakFiltratu() {
  var bilatuTestua = $("#iragazkia-bilatu").val().toLowerCase();
  var egoera = $("#iragazkia-egoera").val();
  var kategoria = $("#iragazkia-kategoria").val();
  var mota = $("#iragazkia-mota").val();
  var ordenatu = $("#iragazkia-ordenatu").val();
  var prezioOrdenatu = $("input[name='prezio-ordenatu']:checked").val();
  if (prezioOrdenatu) {
    ordenatu = prezioOrdenatu;
  }
  var prezioaMin = parseFloat($("#prezioa-min").val());
  var prezioaMax = parseFloat($("#prezioa-max").val());

  var emaitzak = produktuGuztiak.filter(function (p) {
    // Stock-a egiaztatu (0 bada ez erakutsi)
    if (p.stock <= 0) return false;

    // Bilatu (Izena edo Marka)
    var matchBilatu =
      !bilatuTestua ||
      p.izena.toLowerCase().includes(bilatuTestua) ||
      p.marka.toLowerCase().includes(bilatuTestua);

    // Egoera
    var matchEgoera = !egoera || p.egoera === egoera;

    // Kategoria (id_kategoria gisa stringa dator API-tik momentuz)
    var matchKategoria = !kategoria || p.id_kategoria === kategoria;

    // Mota
    var matchMota = !mota || p.mota === mota;

    // Prezioa
    var matchPrezioaMin = isNaN(prezioaMin) || p.prezioa >= prezioaMin;
    var matchPrezioaMax = isNaN(prezioaMax) || p.prezioa <= prezioaMax;

    return (
      matchBilatu &&
      matchEgoera &&
      matchKategoria &&
      matchMota &&
      matchPrezioaMin &&
      matchPrezioaMax
    );
  });

  // Ordenatu
  if (ordenatu !== "default") {
    emaitzak.sort(function (a, b) {
      if (ordenatu === "prezioa-asc") return a.prezioa - b.prezioa;
      if (ordenatu === "prezioa-desc") return b.prezioa - a.prezioa;
      if (ordenatu === "izena-asc") return a.izena.localeCompare(b.izena);
      if (ordenatu === "izena-desc") return b.izena.localeCompare(a.izena);
      if (ordenatu === "stock-asc") return a.stock - b.stock;
      if (ordenatu === "stock-desc") return b.stock - a.stock;
      return 0;
    });
  }

  produktuakBistaratu(emaitzak);
}

// ==========================================================
// SASKIAREN LOGIKA (GEHITU BAKARRIK - Globala)
// ==========================================================
$(document).ready(function () {
  // 1. PRODUKTUA GEHITU (Saskiratu botoia)
  $(document).on("click", ".produktua-saskiratu-botoia", function () {
    var $botoia = $(this);
    var $txartela = $botoia.closest(".produktu-txartela");

    // Datuak jaso
    var izena = $txartela.find(".txartel-izenburua").text();
    var prezioaTestua = $txartela
      .find(".txartel-prezioa")
      .text()
      .replace(" €", "");
    var prezioa = parseFloat(prezioaTestua);
    var stock = parseInt($botoia.data("stock")) || 0;

    // Bilatu produktua array globalean ID lortzeko
    var produktuaObj = produktuGuztiak.find((p) => p.izena === izena);
    // Fallback: IDrik ezean izena erabili (baina PHPtik beti IDa etorri beharko litzateke)
    var id = produktuaObj ? produktuaObj.id_produktua : izena;

    // Globala.js-ko funtzioa deitu
    if (typeof window.saskiaGehitu === "function") {
      window.saskiaGehitu(id, izena, prezioa, stock, $botoia);
      
      //  (soilik saskiratu denean)
      if (typeof window.saskiaAnimatuKontagailua === "function") {
        window.saskiaAnimatuKontagailua();
      }

      // Saskia irekita badago, eguneratu ikuspegia
      if (
        $("#saski-modala").is(":visible") &&
        typeof window.saskiaErakutsi === "function"
      ) {
        window.saskiaErakutsi();
      }
    } else {
      console.error(
        "Errorea: window.saskiaGehitu ez dago definituta globala.js-n",
      );
    }
  });
});
