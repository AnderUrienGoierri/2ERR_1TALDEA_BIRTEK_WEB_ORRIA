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
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
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
    <header class="goiburu-nagusia">
      <nav class="nab-edukiontzia">
        <div class="goiburu-barnealdea">
          <!-- Mugikorra Menu Botoia -->
          <button id="mugikor-menu-botoia" class="mugikor-menu-botoia">
            <i class="fa fa-bars burger-ikonoa"></i>
          </button>

          <!-- logoa -->
          <a href="hasiera.php" class="logo-edukiontzia">
            <span class="logoa">BIRTEK</span>
          </a>

          <div class="nab-menu-mahaigaina">
            <a href="hasiera.php" class="nab-botoia aktibo">Hasiera</a>
            <a href="produktuak.php" class="nab-botoia">Produktuak</a>
            <a href="berriak.php" class="nab-botoia">Berriak</a>
            <a href="kontaktua.php" class="nab-botoia">Kontaktua</a>
            <a href="<?= isset($_SESSION['id_hornitzailea']) ? 'hornitzaile_menua.php' : 'hornitzaile_saioa_hasi.php' ?>" class="nab-botoia <?= isset($_SESSION['id_hornitzailea']) ? 'hornitzailea-aktibo' : '' ?>">Birziklatu</a>
            <a href="langileak_menua.php" class="nab-botoia">Langileak</a>
          </div>

          <div class="nab-ekintzak">
            <?php if (isset($_SESSION['id_bezeroa'])): ?>
                <div class="saio-informazio-edukiontzia">
                    <a href="bezero_menua.php" class="saioa-hasi-botoia aktibo" id="saioa-hasi-botoia" title="Joan Nire Menura">
                        <i class="fas fa-user"></i> <span><?= htmlspecialchars($_SESSION['izena']) ?></span>
                    </a>
                    <button id="saioa-itxi-botoia" class="saioa-hasi-botoia botoi-gorria">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            <?php elseif (isset($_SESSION['id_hornitzailea'])): ?>
                <div class="saio-informazio-edukiontzia">
                    <a href="hornitzaile_menua.php" class="saioa-hasi-botoia aktibo" id="saioa-hasi-botoia" title="Joan Nire Menura">
                        <i class="fas fa-user"></i> <span><?= htmlspecialchars($_SESSION['izena_soziala']) ?></span>
                    </a>
                    <button id="saioa-itxi-botoia" class="saioa-hasi-botoia botoi-gorria">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            <?php else: ?>
                <a href="bezero_saioa_hasi.php" class="saioa-hasi-botoia" id="saioa-hasi-botoia">Saioa Hasi</a>
            <?php endif; ?>
            
            <button class="saski-botoia" id="saski-botoia-toggle">
              <i class="fas fa-shopping-cart"></i>
              <span>Saskia</span>
              <span class="saski-kontagailua">0</span>
            </button>
          </div>
        </div>

        <div id="mugikor-menua" class="mugikor-menu-edukiontzia">
          <a href="hasiera.php" class="nab-botoia aktibo">Hasiera</a>
          <a href="produktuak.php" class="nab-botoia">Produktuak</a>
          <a href="berriak.php" class="nab-botoia">Berriak</a>
          <a href="kontaktua.php" class="nab-botoia">Kontaktua</a>
          <a href="<?= isset($_SESSION['id_hornitzailea']) ? 'hornitzaile_menua.php' : 'hornitzaile_saioa_hasi.php' ?>" class="nab-botoia <?= isset($_SESSION['id_hornitzailea']) ? 'hornitzailea-aktibo' : '' ?>">Birziklatu</a>
          <a href="langileak_menua.php" class="nab-botoia">Langileak</a>

          <?php if (isset($_SESSION['id_bezeroa'])): ?>
              <div class="mugikor-erabiltzaile-edukiontzia">
                  <a href="bezero_menua.php" class="nab-botoia mugikor-erabiltzaile-link">
                      <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['izena']) ?>
                  </a>
                  <button id="mugikor-saioa-itxi-botoia" class="nab-botoia mugikor-logout-botoia">
                      <i class="fas fa-sign-out-alt"></i> Saioa Itxi
                  </button>
              </div>
          <?php elseif (isset($_SESSION['id_hornitzailea'])): ?>
              <div class="mugikor-erabiltzaile-edukiontzia">
                  <a href="hornitzaile_menua.php" class="nab-botoia mugikor-erabiltzaile-link">
                      <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['izena_soziala']) ?>
                  </a>
                  <button id="mugikor-saioa-itxi-botoia" class="nab-botoia mugikor-logout-botoia">
                      <i class="fas fa-sign-out-alt"></i> Saioa Itxi
                  </button>
              </div>
          <?php else: ?>
              <a href="bezero_saioa_hasi.php" class="nab-botoia">Saioa Hasi</a>
          <?php endif; ?>
        </div>
      </nav>
    </header>

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
