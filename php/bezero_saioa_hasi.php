<?php
session_start();
require_once 'DB_konexioa.php';

// Herriak lortu
try {
    $stmt_h = $konexioa->prepare("SELECT id_herria, izena FROM herriak ORDER BY izena");
    $stmt_h->execute();
    $herriak = $stmt_h->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $herriak = [];
}
?>
<!DOCTYPE html>
<html lang="eu">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BIRTEK - Saioa Hasi / Erregistratu</title>

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
            <a href="langileak_menua.php" class="nab-botoia">Langileak</a>
          </div>

          <div class="nab-ekintzak">
            <?php if (isset($_SESSION['id_hornitzailea'])): ?>
                <div class="saio-informazio-edukiontzia">
                    <a href="hornitzaile_menua.php" class="saioa-hasi-botoia aktibo" id="saioa-hasi-botoia" title="Joan Nire Menura">
                        <i class="fas fa-user"></i> <span><?= htmlspecialchars($_SESSION['izena_soziala']) ?></span>
                    </a>
                    <button id="saioa-itxi-botoia" class="saioa-hasi-botoia botoi-gorria">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            <?php elseif (isset($_SESSION['id_bezeroa'])): ?>
                <div class="saio-informazio-edukiontzia">
                    <a href="bezero_menua.php" class="saioa-hasi-botoia aktibo" id="saioa-hasi-botoia" title="Joan Nire Menura">
                        <i class="fas fa-user"></i> <span><?= htmlspecialchars($_SESSION['izena']) ?></span>
                    </a>
                    <button id="saioa-itxi-botoia" class="saioa-hasi-botoia botoi-gorria">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            <?php else: ?>
                <a href="bezero_saioa_hasi.php" class="saioa-hasi-botoia saioa-hasi-botoia-nab" id="saioa-hasi-botoia">Saioa Hasi</a>
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
          <?php endif; ?>
        </div>
      </nav>
    </header>

    <main class="eduki-nagusia">
      <section class="kontaktu-edukiontzia">
        <h2 class="kontaktua-titulua">Bezeroaren Gunea</h2>

        <div class="kontaktu-sareta">
          <!-- SAIOA HASI -->
          <div class="inprimaki-kutxa">
            <h3 class="inprimaki-titulua">Saioa Hasi</h3>
            <p class="tartea-behean-1-5 testua-grisa">Dagoeneko kontua baduzu, sartu hemen:</p>
            <form class="kontaktu-inprimaki-diseinua" action="login_bezeroa.php" method="POST">
              <div>
                <input type="email" name="emaila" placeholder="Posta elektronikoa" class="inprimaki-sarrera" required />
              </div>
              <div>
                <input type="password" name="pasahitza" placeholder="Pasahitza" class="inprimaki-sarrera" required />
              </div>
              <button type="submit" class="botoia botoi-nagusia zabalera-osoa" id="saioa-hasi-submit-botoia">Sartu</button>
              <div class="testua-zentratuta tartea-goian-1">
                <a href="#" class="pasahitza-ahaztu-esteka">Pasahitza ahaztu duzu?</a>
              </div>
            </form>
          </div>

          <!-- ERREGISTRATU -->
          <div class="inprimaki-kutxa">
            <h3 class="inprimaki-titulua">Erregistratu</h3>
            <p class="tartea-behean-1-5 testua-grisa">Berria zara? Sortu kontu bat erraz:</p>
            <form class="kontaktu-inprimaki-diseinua" action="erregistratu_bezeroa.php" method="POST">
              <div>
                <input type="text" name="izena" placeholder="Izena eta Abizenak" class="inprimaki-sarrera" required />
              </div>
              <div>
                <input type="email" name="emaila_erregistroa" placeholder="Posta elektronikoa" class="inprimaki-sarrera" required />
              </div>
              <div>
                <input type="text" name="nan" placeholder="NAN / IFZ" class="inprimaki-sarrera" required />
              </div>
              <div>
                <input type="text" name="helbidea" placeholder="Helbidea" class="inprimaki-sarrera" />
              </div>
              <div>
                <select name="herria_id" id="herria_id_bezeroa" class="inprimaki-hautatu" required onchange="toggleHerriaInput('bezeroa')">
                    <option value="" disabled selected>Aukeratu Herria</option>
                    <?php foreach ($herriak as $herria): ?>
                        <option value="<?= $herria['id_herria'] ?>"><?= htmlspecialchars($herria['izena']) ?></option>
                    <?php endforeach; ?>
                    <option value="other">Beste bat... (Gehitu berria)</option>
                </select>
              </div>
              <div id="herria_berria_container_bezeroa" class="ezkutuan">
                <input type="text" name="herria_berria" placeholder="Herriaren izena" class="inprimaki-sarrera" />
                <input type="text" name="lurraldea_berria" placeholder="Lurraldea (probintzia)" class="inprimaki-sarrera" />
              </div>
              <div>
                <input type="text" name="posta_kodea" placeholder="Posta Kodea" class="inprimaki-sarrera" required pattern="[0-9]{5}" title="5 digituko posta kodea" />
              </div>
              <div>
                <input type="password" name="pasahitza_erregistroa" placeholder="Pasahitza segurua" class="inprimaki-sarrera" required minlength="8" />
                <p class="testua-txikia testua-grisa tartea-goian-txikia">Gutxienez 8 karaktere.</p>
              </div>
              <button type="submit" class="botoia botoi-nagusia">Sortu Kontua</button>
            </form>
          </div>
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
    <script>
      // saioa hasita badago:
      $(document).on("saioa:baliozkoa", function (e, erabiltzailea, mota) {
        var menuUrl = (mota === 'hornitzailea') ? 'hornitzaile_menua.php' : 'bezero_menua.php';
        $(".kontaktu-sareta").html(
          '<div class="testua-zentratuta ongietorri-kutxa">' +
            '<h2 class="tartea-behean-1">Ongi etorri berriro, ' + erabiltzailea + "!</h2>" +
            '<p class="tartea-behean-1-5">Dagoeneko saioa hasita daukazu.</p>' +
            '<a href="' + menuUrl + '" class="botoia botoi-nagusia">Joan Nire Menura</a>' +
          '</div>'
        );
      });
    </script>
  </body>
</html>
