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
      '<p style="text-align:center; padding: 2rem; color: #666;">Ez duzu produkturik aukeratu.</p>'
    );
    $("#erosketa-guztira").text("0.00 €");
    $('button[type="submit"]').prop("disabled", true).css("opacity", "0.5");
    return;
  } else {
    $('button[type="submit"]').prop("disabled", false).css("opacity", "1");
  }

  // Table Structure
  var tableHtml = `
    <div style="overflow-x:auto;">
    <table class="lerro-taula" style="width:100%; border-collapse: collapse;">
        <thead>
            <tr style="background:#f9fafb; border-bottom:2px solid #e5e7eb;">
                <th style="padding:1rem; text-align:left;">Produktua</th>
                <th style="padding:1rem; text-align:center;">Kantitatea</th>
                <th style="padding:1rem; text-align:right;">Prezioa</th>
                <th style="padding:1rem; text-align:right;">Guztira</th>
                <th style="padding:1rem; text-align:center;">Ekintzak</th>
            </tr>
        </thead>
        <tbody>
  `;

  saskia.forEach(function (item) {
    var subtotala = item.prezioa * item.kantitatea;
    totala += subtotala;

    tableHtml += `
        <tr style="border-bottom:1px solid #eee;">
            <td style="padding:1rem;">
                <strong>${item.izena}</strong>
            </td>
            <td style="padding:1rem; text-align:center;">
                <div style="display:flex; align-items:center; justify-content:center; gap:5px;">
                    <button class="kopuru-btn kopuru-minus" data-id="${
                      item.id
                    }" style="padding: 2px 8px; cursor:pointer;">-</button>
                    <span style="font-weight: bold; width: 30px; text-align: center;">${
                      item.kantitatea
                    }</span>
                    <button class="kopuru-btn kopuru-plus" data-id="${
                      item.id
                    }" style="padding: 2px 8px; cursor:pointer;">+</button>
                </div>
            </td>
            <td style="padding:1rem; text-align:right;">${item.prezioa.toFixed(
              2
            )} €</td>
            <td style="padding:1rem; text-align:right; font-weight:bold; color:#166534;">${subtotala.toFixed(
              2
            )} €</td>
            <td style="padding:1rem; text-align:center;">
                <button class="ezabatu-btn item-ezabatu" data-id="${
                  item.id
                }" title="Ezabatu" style="cursor:pointer; background-color:#fee2e2; border:none; color:#991b1b; padding:0.5rem; border-radius:4px;">
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
  var item = saskia.find((i) => i.id == id);

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
  var filtered = saskia.filter((i) => i.id != id);
  window.saskiaGorde(filtered);
  renderErosketaSaskia();
}
