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

  <link rel="stylesheet" href="../css/fontawesome/css/all.min.css" />
  <link rel="stylesheet" href="../css/estiloak_globala.css" />
  <link rel="stylesheet" href="../css/estiloak_ordainketa.css" />
</head>

<body class="web-gorputza">
  <?php include_once 'goiburua.php'; ?>

  <main class="eduki-nagusia">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
      <div class="ordainketa-kutxa ordainketa-info-kutxa">
        <div class="ordainketa-ikonoa">
          <i class="fas fa-check-circle"></i>
        </div>
        <h2 class="ordainketa-izenburua">Erosketa Burutu da!</h2>
        <p class="ordainketa-testua">
          Eskerrik asko zure erosketagatik. Zure eskaera ondo erregistratu da eta prozesatzen ari gara.
        </p>
        <div class="botoi-edukiontzia botoi-taldea-zentratuta">
          <a href="bezero_eskaerak.php" class="botoia botoi-grisa">Erosketak Kudeatu</a>
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
            <input type="text" id="titularra" name="titularra" value="<?= htmlspecialchars($titularraOsoa) ?>"
              placeholder="Adib: Ane Goikoetxea" required>
          </div>

          <!-- 4. Txartel zenbakia -->
          <div class="inprimaki-taldea">
            <label for="txartela">Bezero Ordainketa Txartela</label>
            <input type="text" id="txartela" name="txartela" value="<?= htmlspecialchars($gordetakoTxartela) ?>"
              placeholder="xxxx-xxxx-xxxx-xxxx" required>
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

  <?php include 'footer.php'; ?>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="../js/globala.js"></script>
  <script src="../js/ordainketa.js"></script>
</body>

</html>