$(document).ready(function () {
  erakutsiErosketaSaskia();

  // Saskia hutsik badago inprimakia bidaltzea ekidin
  $("#bidalketa-form").on("submit", function (e) {
    const saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
    if (saskia.length === 0) {
      e.preventDefault();
      alert("Saskia hutsik dago!");
    }
  });

  // KONTROLENTZAKO GERTAERA-ENTZULEAK
  $(document).on("click", ".kopuru-plus", function (e) {
    e.preventDefault(); // Botoiak inprimakia bidaltzea ekidin (inprimaki barruan badago)
    const id = $(this).data("id");
    aldatuKantitatea(id, 1);
  });

  $(document).on("click", ".kopuru-minus", function (e) {
    e.preventDefault();
    const id = $(this).data("id");
    aldatuKantitatea(id, -1);
  });

  $(document).on("click", ".eskaera-lerroa-ezabatu", function (e) {
    e.preventDefault();
    const id = $(this).data("id");
    eskaera_lerroa_ezabatu(id);
  });
});

function erakutsiErosketaSaskia() {
  const saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
  const $container = $("#erosketa-saski-container");
  let totala = 0;

  $container.empty();

  if (saskia.length === 0) {
    $container.html(
      '<p class="saskia-hutsik-mezua">Ez duzu produkturik aukeratu.</p>'
    );
    $("#erosketa-guztira").text("0.00 €");
    $('button[type="submit"]')
      .prop("disabled", true)
      .addClass("botoi-desgaitua");
    return;
  } else {
    $('button[type="submit"]')
      .prop("disabled", false)
      .removeClass("botoi-desgaitua");
  }

  /* Erosketa Saski Taularen egitura */
  let tableHtml = `
    <div class="taula-edukiontzia-scroll">
    <table class="lerro-taula">
        <thead>
            <tr class="lerro-taula-izenburua">
                <th class="testua-ezkerrean">Produktua</th>
                <th class="testua-zentratuta">Kantitatea</th>
                <th class="testua-eskuinera">Prezioa</th>
                <th class="testua-eskuinera">Guztira</th>
                <th class="testua-zentratuta">Ekintzak</th>
            </tr>
        </thead>
        <tbody>
  `;

  saskia.forEach((eskaera_lerroa) => {
    const subtotala = eskaera_lerroa.prezioa * eskaera_lerroa.kantitatea;
    totala += subtotala;
    tableHtml += `
        <tr>
            <td>
                <strong>${eskaera_lerroa.izena}</strong>
            </td>
            <td class="testua-zentratuta">
                <div class="kopuru-kontrola-lerroa">
                    <button class="kopuru-btn kopuru-minus" data-id="${eskaera_lerroa.id}">-</button>
                    <span class="kopuru-kontrola-balioa">${eskaera_lerroa.kantitatea}</span>
                    <button class="kopuru-btn kopuru-plus" data-id="${eskaera_lerroa.id}">+</button>
                </div>
            </td>
            <td class="testua-eskuinera">${eskaera_lerroa.prezioa.toFixed(2)} €</td>
            <td class="testua-eskuinera prezio-nabarmena">${subtotala.toFixed(2)} €</td>
            <td class="testua-zentratuta">
                <button class="ezabatu-btn-gorria eskaera-lerroa-ezabatu" data-id="${eskaera_lerroa.id}" title="Ezabatu">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;
  });

  tableHtml += `
        </tbody>
    </table>
    </div>
  `;

  $container.html(tableHtml);
  $("#erosketa-guztira").text(totala.toFixed(2) + " €");
}

function aldatuKantitatea(id, aldaketa) {
  const saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];

  const eskaera_lerroa = saskia.find((i) => i.id == id);

  if (eskaera_lerroa) {
    const kantitateBerria = eskaera_lerroa.kantitatea + aldaketa;

    // Stock egiaztapena
    if (kantitateBerria > eskaera_lerroa.stock) {
      alert("Ez dago stock nahikorik gehiago gehitzeko.");
      return;
    }

    if (kantitateBerria > 0) {
      eskaera_lerroa.kantitatea = kantitateBerria;
      window.saskiaGorde(saskia); // LocalStorage + Goiburuko Kontagailua eguneratu
      erakutsiErosketaSaskia();
    } else {
      eskaera_lerroa_ezabatu(id);
    }
  }
}

function eskaera_lerroa_ezabatu(id) {
  const saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];

  const eskaera_lerro_filtratua = saskia.filter((i) => i.id != id);
  window.saskiaGorde(eskaera_lerro_filtratua);
  erakutsiErosketaSaskia();
}
