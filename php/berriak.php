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
    <header class="goiburu-nagusia">
      <nav class="nab-edukiontzia">
        <div class="goiburu-barnealdea">
          <!-- Mugikorra Menu Botoia -->
          <button id="mugikor-menu-botoia" class="mugikor-menu-botoia">
            <i class="fas fa-bars burger-ikonoa"></i>
          </button>

          <!-- logoa -->
          <a href="hasiera.php" class="logo-edukiontzia">
            <span class="logoa">BIRTEK</span>
          </a>

          <div class="nab-menu-mahaigaina">
            <a href="hasiera.php" class="nab-botoia">Hasiera</a>
            <a href="produktuak.php" class="nab-botoia">Produktuak</a>
            <a href="berriak.php" class="nab-botoia aktibo">Berriak</a>
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
          <a href="hasiera.php" class="nab-botoia">Hasiera</a>
          <a href="produktuak.php" class="nab-botoia">Produktuak</a>
          <a href="berriak.php" class="nab-botoia aktibo">Berriak</a>
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

    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
  </body>
</html>
