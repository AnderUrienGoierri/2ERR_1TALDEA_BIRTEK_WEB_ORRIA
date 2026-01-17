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
    <title>BIRTEK - Hornitzailea Saioa Hasi / Erregistratu</title>

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
            <a href="<?= isset($_SESSION['id_hornitzailea']) ? 'hornitzaile_menua.php' : 'hornitzaile_saioa_hasi.php' ?>" class="nab-botoia <?= isset($_SESSION['id_hornitzailea']) ? 'hornitzailea-aktibo' : 'aktibo' ?>">Birziklatu</a>
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
          <a href="berriak.php" class="nab-botoia">Berriak</a>
          <a href="kontaktua.php" class="nab-botoia">Kontaktua</a>
          <a href="<?= isset($_SESSION['id_hornitzailea']) ? 'hornitzaile_menua.php' : 'hornitzaile_saioa_hasi.php' ?>" class="nab-botoia <?= isset($_SESSION['id_hornitzailea']) ? 'hornitzailea-aktibo' : 'aktibo' ?>">Birziklatu</a>
          <a href="langileak_menua.php" class="nab-botoia">Langileak</a>
        </div>
      </nav>
    </header>

    <main class="eduki-nagusia">
      <section class="kontaktu-edukiontzia">
        <h2 class="kontaktua-titulua">Hornitzaileen Gunea</h2>

        <div class="kontaktu-sareta">
          <!-- SAIOA HASI -->
          <div class="inprimaki-kutxa">
            <h3 class="inprimaki-titulua">Saioa Hasi</h3>
            <p class="tartea-behean-1-5 testua-grisa">Dagoeneko hornitzailea zara? Sartu hemen:</p>
            <form class="kontaktu-inprimaki-diseinua" action="login_hornitzailea.php" method="POST">
              <div>
                <input type="email" name="emaila" placeholder="Posta elektronikoa" class="inprimaki-sarrera" required />
              </div>
              <div>
                <input type="password" name="pasahitza" placeholder="Pasahitza" class="inprimaki-sarrera" required />
              </div>
              <button type="submit" class="botoia botoi-nagusia zabalera-osoa">Sartu</button>
              <div class="testua-zentratuta tartea-goian-1">
                <a href="#" class="pasahitza-ahaztu-esteka">Pasahitza ahaztu duzu?</a>
              </div>
            </form>
          </div>

          <!-- ERREGISTRATU -->
          <div class="inprimaki-kutxa">
            <h3 class="inprimaki-titulua">Erregistratu</h3>
            <p class="tartea-behean-1-5 testua-grisa">Hornitzaile berria? Sortu kontu bat:</p>
            <form class="kontaktu-inprimaki-diseinua" action="erregistratu_hornitzailea.php" method="POST">
              <div>
                <input type="text" name="izena" placeholder="Enpresaren Izena / Izena" class="inprimaki-sarrera" required />
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
                <select name="herria_id" id="herria_id_hornitzailea" class="inprimaki-hautatu" required onchange="toggleHerriaInput('hornitzailea')">
                    <option value="" disabled selected>Aukeratu Herria</option>
                    <?php foreach ($herriak as $herria): ?>
                        <option value="<?= $herria['id_herria'] ?>"><?= htmlspecialchars($herria['izena']) ?></option>
                    <?php endforeach; ?>
                    <option value="other">Beste bat... (Gehitu berria)</option>
                </select>
              </div>
              <div id="herria_berria_container_hornitzailea" style="display: none;">
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
              <button type="submit" class="botoia botoi-nagusia zabalera-osoa">Sortu Kontua</button>
            </form>
          </div>
        </div>
      </section>
    </main>

    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
    <script>
      $(document).on("session:valid", function (e, user, type) {
        var menuUrl = (type === 'hornitzailea') ? 'hornitzaile_menua.php' : 'bezero_menua.php';
        $(".kontaktu-sareta").html(
          '<div class="testua-zentratuta" style="grid-column: 1 / -1; padding: 2rem;">' +
            '<h2 class="tartea-behean-1">Ongi etorri berriro, ' + user + "!</h2>" +
            '<p class="tartea-behean-1-5">Dagoeneko saioa hasita daukazu.</p>' +
            '<a href="' + menuUrl + '" class="botoia botoi-nagusia">Joan Nire Menura</a>' +
          '</div>'
        );
      });
    </script>
  </body>
</html>
