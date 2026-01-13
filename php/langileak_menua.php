<?php
session_start();
?>
<!DOCTYPE html>
<html lang="eu">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BIRTEK - Langileen Gunea</title>

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
    <link rel="stylesheet" href="../css/estiloak_kontaktua.css" />
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
            <a href="berriak.php" class="nab-botoia">Berriak</a>
            <a href="kontaktua.php" class="nab-botoia">Kontaktua</a>
            <a href="<?= isset($_SESSION['id_hornitzailea']) ? 'hornitzaile_menua.php' : 'hornitzaile_saioa_hasi.php' ?>" class="nab-botoia <?= isset($_SESSION['id_hornitzailea']) ? 'hornitzailea-aktibo' : '' ?>">Birziklatu</a>
            <a href="langileak_menua.php" class="nab-botoia aktibo">Langileak</a>
          </div>

          <div class="nab-ekintzak">
            <?php if (isset($_SESSION['id_bezeroa'])): ?>
                <div class="saio-info-edukiontzia">
                    <a href="bezero_menua.php" class="saioa-hasi-botoia aktibo" id="saioa-hasi-botoia" title="Joan Nire Menura">
                        <i class="fas fa-user"></i> <span><?= htmlspecialchars($_SESSION['izena']) ?></span>
                    </a>
                    <button id="saioa-itxi-botoia" class="saioa-hasi-botoia" style="background:#fee2e2; color:#991b1b; border-color:#f87171;">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            <?php elseif (isset($_SESSION['id_hornitzailea'])): ?>
                <div class="saio-info-edukiontzia">
                    <a href="hornitzaile_menua.php" class="saioa-hasi-botoia aktibo" id="saioa-hasi-botoia" title="Joan Nire Menura">
                        <i class="fas fa-user"></i> <span><?= htmlspecialchars($_SESSION['izena_soziala']) ?></span>
                    </a>
                    <button id="saioa-itxi-botoia" class="saioa-hasi-botoia" style="background:#fee2e2; color:#991b1b; border-color:#f87171;">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            <?php else: ?>
                <a href="bezero_saioa_hasi.php" class="saioa-hasi-botoia" id="saioa-hasi-botoia">Saioa Hasi</a>
            <?php endif; ?>
            
            <button class="saski-botoia" id="saski-botoia-toggle">
              <i class="fas fa-shopping-cart"></i>
              <span>Saskia</span>
              <span class="saski-kontagailu-txapa">0</span>
            </button>
          </div>
        </div>

        <div id="mugikor-menua" class="mugikor-menu-edukiontzia">
          <a href="hasiera.php" class="nab-botoia">Hasiera</a>
          <a href="produktuak.php" class="nab-botoia">Produktuak</a>
          <a href="berriak.php" class="nab-botoia">Berriak</a>
          <a href="kontaktua.php" class="nab-botoia">Kontaktua</a>
          <a href="<?= isset($_SESSION['id_hornitzailea']) ? 'hornitzaile_menua.php' : 'hornitzaile_saioa_hasi.php' ?>" class="nab-botoia <?= isset($_SESSION['id_hornitzailea']) ? 'hornitzailea-aktibo' : '' ?>">Birziklatu</a>
          <a href="langileak_menua.php" class="nab-botoia aktibo">Langileak</a>

          <?php if (isset($_SESSION['id_bezeroa'])): ?>
              <div class="mugikor-user-container">
                  <a href="bezero_menua.php" class="nab-botoia mugikor-user-link">
                      <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['izena']) ?>
                  </a>
                  <a href="logout_bezeroa.php" class="nab-botoia" style="color: #991b1b; background: #fee2e2; border-top: 1px solid #fecaca;">
                      <i class="fas fa-sign-out-alt"></i> Saioa Itxi
                  </a>
              </div>
          <?php elseif (isset($_SESSION['id_hornitzailea'])): ?>
              <div class="mugikor-user-container">
                  <a href="hornitzaile_menua.php" class="nab-botoia mugikor-user-link">
                      <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['izena_soziala']) ?>
                  </a>
                  <a href="logout_bezeroa.php" class="nab-botoia" style="color: #991b1b; background: #fee2e2; border-top: 1px solid #fecaca;">
                      <i class="fas fa-sign-out-alt"></i> Saioa Itxi
                  </a>
              </div>
          <?php else: ?>
              <a href="bezero_saioa_hasi.php" class="nab-botoia">Saioa Hasi</a>
          <?php endif; ?>
        </div>
      </nav>
    </header>

    <main class="eduki-nagusia">
      <section class="langileak-edukiontzia">
        <div class="birtek-java-ap-botoia-kanpo">
          <button class="birtek-java-ap-botoia" title="Laster erabilgarri">
            <i class="fas fa-users-cog"></i> BirtekAp
          </button>
        </div>

        <div class="inprimaki-kutxa">
          <h2 class="inprimaki-titulua testua-zentratuta">Lan egin gurekin</h2>
          <p class="testua-zentratuta tartea-behean-2 testua-grisa">Bete formulario hau eta bidali zure CV-a gure taldean sartzeko.</p>

          <form class="kontaktu-inprimaki-diseinua" action="#" method="POST" enctype="multipart/form-data">
            <div class="sareta-2-zutabe">
              <input type="text" name="izena" placeholder="Izena" class="inprimaki-sarrera" required />
              <input type="text" name="abizenak" placeholder="Abizenak" class="inprimaki-sarrera" required />
            </div>
            <input type="email" name="emaila" placeholder="Posta elektronikoa" class="inprimaki-sarrera" required />
            <input type="tel" name="telefonoa" placeholder="Telefono zenbakia" class="inprimaki-sarrera" required />
            <div>
              <label for="cv-igoera" class="label-input-fitxategia">Igo zure CV (PDF):</label>
              <input type="file" id="cv-igoera" name="cv" accept=".pdf" class="inprimaki-sarrera" style="padding: 10px" required />
            </div>
            <textarea name="oharra" rows="4" class="inprimaki-sarrera" placeholder="Zergatik nahi duzu gurekin lan egin?"></textarea>
            <button type="submit" class="botoia botoi-nagusia">Eskaera Bidali</button>
          </form>
        </div>
      </section>
    </main>

    <footer class="oin-nagusia">
      <div class="oin-sarea">
        <div class="testua-erdian md:text-left">
          <h3 class="oin-izenburua">BIRTEK</h3>
          <p class="oin-testua">Teknologia berreskuratzen, etorkizuna eraikitzen. Goierri Eskolan kokatuta.</p>
        </div>
        <div class="testua-erdian">
          <h4 class="oin-goiburua">Jarrai Gaitzazu</h4>
          <div class="oin-sozial-lerroa">
            <a href="#" class="sozial-lotura testua-fb"><i class="fab fa-facebook"></i></a>
            <a href="#" class="sozial-lotura testua-ig"><i class="fab fa-instagram"></i></a>
            <a href="#" class="sozial-lotura testua-x"><i class="fab fa-x-twitter"></i></a>
            <a href="#" class="sozial-lotura testua-tik"><i class="fab fa-tiktok"></i></a>
            <a href="#" class="sozial-lotura testua-tg"><i class="fab fa-telegram"></i></a>
          </div>
          <div><a href="https://wa.me/34600000000" class="oin-whatsapp-botoia"><i class="fab fa-whatsapp"></i> WhatsApp</a></div>
        </div>
        <div class="testua-erdian md:text-right">
          <h4 class="oin-goiburua">Lotura Azkarrak</h4>
          <ul class="oin-nab-zerrenda">
            <li><a href="hasiera.php" class="oin-lotura">Hasiera</a></li>
            <li><a href="produktuak.php" class="oin-lotura">Produktuak</a></li>
            <li><a href="berriak.php" class="oin-lotura">Berriak</a></li>
            <li><a href="kontaktua.php" class="oin-lotura">Kontaktua</a></li>
            <li><a href="<?= isset($_SESSION['id_hornitzailea']) ? 'hornitzaile_menua.php' : 'hornitzaile_saioa_hasi.php' ?>" class="oin-lotura">Birziklatu</a></li>
            <li><a href="langileak_menua.php" class="oin-lotura">Langileak</a></li>
          </ul>
        </div>
      </div>
      <div class="oin-copyright">© 2025 BIRTEK</div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
    <script src="../js/langileak_menua.js"></script>
  </body>
</html>
