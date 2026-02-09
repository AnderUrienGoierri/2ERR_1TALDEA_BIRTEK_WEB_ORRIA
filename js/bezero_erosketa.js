$(document).ready(function () {
  renderErosketaSaskia();

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

  $(document).on("click", ".item-ezabatu", function (e) {
    e.preventDefault();
    const id = $(this).data("id");
    ezabatuItem(id);
  });
});

function renderErosketaSaskia() {
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

  /* Taularen egitura */
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

  /* forEach erabili $.each-en ordez */
  saskia.forEach((item) => {
    const subtotala = item.prezioa * item.kantitatea;
    totala += subtotala;
    tableHtml += `
        <tr>
            <td>
                <strong>${item.izena}</strong>
            </td>
            <td class="testua-zentratuta">
                <div class="kopuru-kontrola-lerroa">
                    <button class="kopuru-btn kopuru-minus" data-id="${item.id}">-</button>
                    <span class="kopuru-kontrola-balioa">${item.kantitatea}</span>
                    <button class="kopuru-btn kopuru-plus" data-id="${item.id}">+</button>
                </div>
            </td>
            <td class="testua-eskuinera">${item.prezioa.toFixed(2)} €</td>
            <td class="testua-eskuinera prezio-nabarmena">${subtotala.toFixed(2)} €</td>
            <td class="testua-zentratuta">
                <button class="ezabatu-btn-gorria item-ezabatu" data-id="${item.id}" title="Ezabatu">
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

function aldatuKantitatea(id, change) {
  const saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];

  const item = saskia.find((i) => i.id == id);

  if (item) {
    const newQty = item.kantitatea + change;

    // Stock egiaztapena
    if (newQty > item.stock) {
      alert("Ez dago stock nahikorik gehiago gehitzeko.");
      return;
    }

    if (newQty > 0) {
      item.kantitatea = newQty;
      window.saskiaGorde(saskia); // LocalStorage + Goiburuko Kontagailua eguneratu
      renderErosketaSaskia();
    } else {
      ezabatuItem(id);
    }
  }
}

function ezabatuItem(id) {
  const saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];

  const filtered = saskia.filter((i) => i.id != id);
  window.saskiaGorde(filtered);
  renderErosketaSaskia();
}
