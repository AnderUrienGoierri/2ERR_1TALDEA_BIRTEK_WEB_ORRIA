<?php
session_start();

require_once 'DB_konexioa.php';

// Egiaztatu bezeroa saioa hasita dagoen
if (!isset($_SESSION['id_bezeroa'])) {
    header("Location: bezero_saioa_hasi.php");
    exit();
}

$id_bezeroa = $_SESSION['id_bezeroa'];
$izena = $_SESSION['izena'];

// 1. HELBIDE EGUNERAKETA (Formulariotik badator)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['helbidea'])) {
    try {
        $helbidea = $_POST['helbidea'];
        $herria_id = $_POST['herria_id'];
        $posta_kodea = $_POST['posta_kodea'];
        $telefonoa = $_POST['telefonoa'];

        $stmtUpdate = $konexioa->prepare("UPDATE bezeroak SET helbidea = ?, herria_id = ?, posta_kodea = ?, telefonoa = ? WHERE id_bezeroa = ?");
        $stmtUpdate->execute([$helbidea, $herria_id, $posta_kodea, $telefonoa, $id_bezeroa]);
        
    } catch (PDOException $e) {
        // Errorea gertatu da, baina jarraitu dezakegu aurreko datuekin edo abisua eman
        error_log("Errorea helbidea eguneratzean: " . $e->getMessage());
    }
}

// 2. Lortu bezeroaren datu guztiak (Txartela barne)
$stmt = $konexioa->prepare("SELECT izena_edo_soziala, abizena, bezero_ordainketa_txartela FROM bezeroak WHERE id_bezeroa = ?");
$stmt->execute([$id_bezeroa]);
$bezeroDatuak = $stmt->fetch(PDO::FETCH_ASSOC);

$titularraOsoa = $bezeroDatuak['izena_edo_soziala'] . ($bezeroDatuak['abizena'] ? ' ' . $bezeroDatuak['abizena'] : '');
$gordetakoTxartela = $bezeroDatuak['bezero_ordainketa_txartela'] ?? '';

?>
<!DOCTYPE html>
<html lang="eu">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BIRTEK - Ordainketa Pasarela</title>

    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/estiloak_globala.css" />
    <link rel="stylesheet" href="../css/estiloak_ordainketa.css" />
  </head>
  <body class="web-gorputza">
    <header class="goiburu-nagusia">
      <nav class="nab-edukiontzia">
        <div class="goiburu-barnealdea">
          <!-- Mugikorra Menu Botoia -->
          <button id="mugikor-menu-botoia" class="mugikor-menu-botoia">
            <!-- burger menu botoia -->
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
            <?php if (isset($_SESSION['id_bezeroa'])): ?>
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
          <?php else: ?>
              <a href="bezero_saioa_hasi.php" class="nab-botoia">Saioa Hasi</a>
          <?php endif; ?>
        </div>
      </nav>
    </header>

    <main class="eduki-nagusia">
      <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="ordainketa-kutxa" style="text-align: center;">
            <div style="font-size: 5rem; color: #16a34a; margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 style="margin-bottom: 15px;">Erosketa Burutu da!</h2>
            <p style="font-size: 1.1rem; color: #4b5563; margin-bottom: 30px;">
                Eskerrik asko zure erosketagatik. Zure eskaera ondo erregistratu da eta prozesatzen ari gara.
            </p>
            <div class="botoi-edukiontzia" style="display: flex; gap: 10px; justify-content: center;">
                <a href="bezero_eskaerak.php" class="botoia" style="background-color: #4b5563;">Erosketak Kudeatu</a>
                <a href="hasiera.php" class="botoia botoi-nagusia">Itzuli Hasierara</a>
            </div>
        </div>
      <?php else: ?>
        <div class="ordainketa-kutxa">
          <div class="ordainketa-goiburua">
              <i class="fas fa-credit-card"></i>
              <h2>Ordainketa Pasarela Segurua</h2>
          </div>

          <!-- 1. Bezeroaren datuak (Saio hasitakoa) -->
          <div class="erabiltzaile-informazioa">
              <p><strong>Bezeroa:</strong> <?= htmlspecialchars($izena) ?></p>
          </div>

          <!-- 2. Saskiaren laburpena (JS-k beteko du) -->
          <div id="saskia-xehetasunak" class="saskia-laburpena">
              <p>Saskia kargatzen...</p>
          </div>

          <form id="ordainketa-form" onsubmit="return false;">
              <!-- 3. Txartelaren titularra -->
              <div class="form-group">
                  <label for="titularra">Txartelaren Titularraren Izen Abizenak</label>
                  <input type="text" id="titularra" name="titularra" value="<?= htmlspecialchars($titularraOsoa) ?>" placeholder="Adib: Ane Goikoetxea" required>
              </div>

              <!-- 4. Txartel zenbakia -->
              <div class="inprimaki-taldea">
                  <label for="txartela">Bezero Ordainketa Txartela</label>
                  <input type="text" id="txartela" name="txartela" value="<?= htmlspecialchars($gordetakoTxartela) ?>" placeholder="xxxx-xxxx-xxxx-xxxx" required>
              </div>

              <div class="botoi-edukiontzia">
                  <button type="submit" class="botoia botoi-nagusia" onclick="burutuErosketa()">
                    Ordaindu eta Erosketa Burutu
                  </button>
              </div>
          </form>
        </div>
      <?php endif; ?>
    </main>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
    <script src="../js/ordainketa.js"></script>
  </body>
</html>

