<?php
session_start();
?>
<!DOCTYPE html>
<html lang="eu">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BIRTEK - Berriak</title>

    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <!-- Google Fonts -->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
    />

    <!-- gure css artxiboak -->
    <link rel="stylesheet" href="../css/estiloak_globala.css" />
    <link rel="stylesheet" href="../css/estiloak_berriak.css" />
  </head>

  <body class="web-gorputza">
    <?php include 'goiburua.php'; ?>

    <main class="eduki-nagusia">
      <section class="berriak-edukiontzia">
        <h2 class="berriak-titulua">Berriak</h2>

        <div class="berriak-sarea">
          <article class="albiste-txartela">
            <div class="albiste-irudia">
              <img src="https://images.unsplash.com/photo-1550009158-9ebf69173e03?auto=format&fit=crop&w=800&q=80" alt="Recycling Electronics" />
            </div>
            <div class="albiste-edukia">
              <span class="albiste-kategoria">Jasangarritasuna</span>
              <h2 class="albiste-titulua">Zergatik da garrantzitsua gailu elektronikoak berrerabiltzea?</h2>
              <p class="albiste-laburpena">Gailu bakoitzaren bizitza luzatzeak hondakin elektronikoak murrizten ditu eta baliabide naturalen erauzketa ekiditen du.</p>
            </div>
          </article>

          <article class="albiste-txartela">
            <div class="albiste-irudia">
              <img src="../irudiak/birtek_konponketak.png" alt="Repairing Laptop" loading="lazy" />
            </div>
            <div class="albiste-edukia">
              <span class="albiste-kategoria">Berritzea</span>
              <h2 class="albiste-titulua">Gure berritze prozesua: Kalitatea bermatuta</h2>
              <p class="albiste-laburpena">Ezagutu nola pasatzen dituzten gure gailuek kontrol zorrotzak erabiltzaileari esperientzia optimoa ziurtatzeko.</p>
            </div>
          </article>

          <article class="albiste-txartela">
            <div class="albiste-irudia">
              <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?auto=format&fit=crop&w=800&q=80" alt="Electronic Waste" />
            </div>
            <div class="albiste-edukia">
              <span class="albiste-kategoria">Ingurumena</span>
              <h2 class="albiste-titulua">Ekonomia zirkularra teknologiaren munduan</h2>
              <p class="albiste-laburpena">Hondakinak baliabide bihurtzea da gure helburu nagusia. Ezagutu nola lagundu dezakezun zu ere.</p>
            </div>
          </article>
        </div>
      </section>
    </main>

    <?php include_once 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
  </body>
</html>
