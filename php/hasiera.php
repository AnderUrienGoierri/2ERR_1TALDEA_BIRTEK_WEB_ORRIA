<?php
session_start();
?>
<!DOCTYPE html>
<html lang="eu">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BIRTEK - Hasiera</title>

    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="../css/fontawesome/css/all.min.css"
    />
    <!-- Google Fonts -->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
    />

    <!-- bxSlider CSS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bxslider@4.2.17/dist/jquery.bxslider.min.css"
    />

    <!-- gure css artxiboak -->
    <link rel="stylesheet" href="../css/estiloak_globala.css" />
    <link rel="stylesheet" href="../css/estiloak_hasiera.css" />
  </head>

  <body class="web-gorputza">
    <?php include 'goiburua.php'; ?>

    <main class="eduki-nagusia">
      <section>
        <div class="azal-kaxa">
          <div>
            <h1 class="azal-izenburua">Ongi Etorri BIRTEK</h1>
            <img
              src="../irudiak/birtek2.jpeg"
              alt="Birtek Eraikin Nagusia"
              class="hasiera-azal-irudia"
              loading="lazy"
            />
            <p class="azal-azpititulua">
              Teknologia berreskuratzen, etorkizuna eraikitzen.
            </p>
            <div class="azal-botoi-taldea">
              <a href="produktuak.php" class="azal-cta-botoia azal-botoi-erosi">
                <i class="fas fa-shopping-cart"></i> EROSI
              </a>
              <a href="<?= isset($_SESSION['id_hornitzailea']) ? 'hornitzaile_menua.php' : 'hornitzaile_saioa_hasi.php' ?>" class="azal-cta-botoia azal-botoi-birziklatu">
                <i class="fas fa-recycle"></i> BIRZIKLATU
              </a>
            </div>
          </div>
        </div>

        <div class="slider-kanpo-edukiontzia">
          <div class="hasiera-slider-egitura">
            <div><img src="../irudiak/birtek_biltegia.png" alt="Birtek Biltegia" title="Gure Biltegi Nagusia" /></div>
            <div><img src="../irudiak/birtek_sarrerak_birziklapena.jpeg" alt="Birtek Sarrerak Birziklapena" title="Birziklapen Prozesua" /></div>
            <div><img src="../irudiak/birtek_erakuslekua.png" alt="Birtek Erakuslekua" title="Produktuak Ikusgai" /></div>
            <div><img src="../irudiak/birtek_rezepzioa.jpeg" alt="Birtek Rezepzioa" title="Harrera Gunea" /></div>
          </div>
        </div>

        <div class="hasiera-zerrenda-edukiontzia">
          <div class="hasiera-txartel-lerroa">
            <div class="hasiera-zutabea"><img src="../irudiak/birtek_erakuslekua.png" alt="Erakuslekua" class="hasiera-txartel-irudia" loading="lazy" /></div>
            <div class="hasiera-zutabea">
              <h3 class="hasiera-txartel-izenburua">Gure Erakuslekua</h3>
              <p class="hasiera-txartel-testua">Hemen gure produktu berrituak bertatik bertara ikus ditzakezu.</p>
            </div>
          </div>
          <div class="hasiera-txartel-lerroa">
            <div class="hasiera-zutabea"><img src="../irudiak/birtek_rezepzioa.jpeg" alt="Harrera" class="hasiera-txartel-irudia" loading="lazy" /></div>
            <div class="hasiera-zutabea">
              <h3 class="hasiera-txartel-izenburua">Harrera eta Arreta</h3>
              <p class="hasiera-txartel-testua">Zalantzak argitzeko eta eskaerak jasotzeko gunea.</p>
            </div>
          </div>
          <div class="hasiera-txartel-lerroa">
            <div class="hasiera-zutabea"><img src="../irudiak/birtek_biltegia.png" alt="Biltegia" class="hasiera-txartel-irudia" loading="lazy" /></div>
            <div class="hasiera-zutabea">
              <h3 class="hasiera-txartel-izenburua">Biltegi Logistikoa</h3>
              <p class="hasiera-txartel-testua">Stock kudeaketa eta bidalketen antolaketa zentroa.</p>
            </div>
          </div>
          <div class="hasiera-txartel-lerroa">
            <div class="hasiera-zutabea"><img src="../irudiak/birtek_konponketak.png" alt="Tailerra" class="hasiera-txartel-irudia" loading="lazy" /></div>
            <div class="hasiera-zutabea">
              <h3 class="hasiera-txartel-izenburua">Konponketa Tailerra</h3>
              <p class="hasiera-txartel-testua">Gure teknikari espezialistek gailuak biziberritzen dituzten lekua.</p>
            </div>
          </div>
          <div class="hasiera-txartel-lerroa">
            <div class="hasiera-zutabea"><img src="../irudiak/birtek_sarrerak_birziklapena.jpeg" alt="Birziklapena" class="hasiera-txartel-irudia" loading="lazy" /></div>
            <div class="hasiera-zutabea">
              <h3 class="hasiera-txartel-izenburua">Birziklapena</h3>
              <p class="hasiera-txartel-testua">Material elektronikoa jasotzen eta sailkatzen dugu.</p>
            </div>
          </div>
        </div>
      </section>
    </main>

    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bxslider@4.2.17/dist/jquery.bxslider.min.js"></script>
    <script src="../js/globala.js"></script>
    <script src="../js/hasiera.js"></script>
  </body>
</html>
