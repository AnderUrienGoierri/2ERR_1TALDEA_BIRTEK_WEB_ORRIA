$(document).ready(function () {
  renderErosketaSaskia();

  // Prevent form submission if cart is empty
  $("#bidalketa-form").on("submit", function (e) {
    var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
    if (saskia.length === 0) {
      e.preventDefault();
      alert("Saskia hutsik dago!");
    }
  });

  // EVENT LISTENERS FOR CONTROLS
  $(document).on("click", ".kopuru-plus", function (e) {
    e.preventDefault(); // Prevent button from submitting form if inside form
    var id = $(this).data("id");
    aldatuKantitatea(id, 1);
  });

  $(document).on("click", ".kopuru-minus", function (e) {
    e.preventDefault();
    var id = $(this).data("id");
    aldatuKantitatea(id, -1);
  });

  $(document).on("click", ".item-ezabatu", function (e) {
    e.preventDefault();
    var id = $(this).data("id");
    ezabatuItem(id);
  });
});

function renderErosketaSaskia() {
  var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
  var $container = $("#erosketa-saski-container");
  var totala = 0;

  $container.empty();

  if (saskia.length === 0) {
    $container.html(
      '<p class="saskia-hutsik-mezua">Ez duzu produkturik aukeratu.</p>',
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

  // Table Structure
  var tableHtml = `
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

  $.each(saskia, function (index, item) {
    var subtotala = item.prezioa * item.kantitatea;
    totala += subtotala;

    tableHtml += `
        <tr>
            <td>
                <strong>${item.izena}</strong>
            </td>
            <td class="testua-zentratuta">
                <div class="kopuru-kontrola-lerroa">
                    <button class="kopuru-btn kopuru-minus" data-id="${
                      item.id
                    }">-</button>
                    <span class="kopuru-kontrola-balioa">${item.kantitatea}</span>
                    <button class="kopuru-btn kopuru-plus" data-id="${
                      item.id
                    }">+</button>
                </div>
            </td>
            <td class="testua-eskuinera">${item.prezioa.toFixed(2)} €</td>
            <td class="testua-eskuinera prezio-nabarmena">${subtotala.toFixed(
              2,
            )} €</td>
            <td class="testua-zentratuta">
                <button class="ezabatu-btn-gorria item-ezabatu" data-id="${
                  item.id
                }" title="Ezabatu">
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
  var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
  var item = $.grep(saskia, function (i) {
    return i.id == id;
  })[0];

  if (item) {
    var newQty = item.kantitatea + change;

    // Stock Check
    if (newQty > item.stock) {
      alert("Ez dago stock nahikorik gehiago gehitzeko.");
      return;
    }

    if (newQty > 0) {
      item.kantitatea = newQty;
      window.saskiaGorde(saskia); // Updates LocalStorage + CounterBadge in global header
      renderErosketaSaskia();
    } else {
      ezabatuItem(id);
    }
  }
}

function ezabatuItem(id) {
  var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
  var filtered = $.grep(saskia, function (i) {
    return i.id != id;
  });
  window.saskiaGorde(filtered);
  renderErosketaSaskia();
}
