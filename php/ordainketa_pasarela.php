<?php
session_start();

// Egiaztatu bezeroa saioa hasita dagoen
if (!isset($_SESSION['id_bezeroa'])) {
    header("Location: bezeroa_saioa_hasi.php");
    exit();
}

$izena = $_SESSION['izena'];
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
          <button id="mugikor-menu-botoia" class="mugikor-menu-botoia">
            <i class="fas fa-bars burger-ikonoa"></i>
          </button>
          <a href="hasiera.php" class="logo-edukiontzia">
            <span class="logoa">BIRTEK</span>
          </a>
          <div class="nab-menu-mahaigaina">
            <a href="bezero_menua.php" class="nab-botoia">Nire Menua</a>
            <a href="hasiera.php" class="nab-botoia">Hasiera</a>
          </div>
        </div>
      </nav>
    </header>

    <main class="eduki-nagusia">
      <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="pasarela-container" style="text-align: center;">
            <div style="font-size: 5rem; color: #16a34a; margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 style="margin-bottom: 15px;">Erosketa Burutu da!</h2>
            <p style="font-size: 1.1rem; color: #4b5563; margin-bottom: 30px;">
                Eskerrik asko zure erosketagatik. Zure eskaera ondo erregistratu da eta prozesatzen ari gara.
            </p>
            <div class="botoi-container" style="display: flex; gap: 10px; justify-content: center;">
                <a href="bezero_eskaerak.php" class="botoia" style="background-color: #4b5563;">Erosketak Kudeatu</a>
                <a href="../html/hasiera.html" class="botoia botoi-nagusia">Itzuli Hasierara</a>
            </div>
        </div>
      <?php else: ?>
        <div class="pasarela-container">
          <div class="pasarela-header">
              <i class="fas fa-credit-card"></i>
              <h2>Ordainketa Pasarela Segurua</h2>
          </div>

          <!-- 1. Bezeroaren datuak (Saio hasitakoa) -->
          <div class="erabiltzaile-info">
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
                  <input type="text" id="titularra" name="titularra" placeholder="Adib: Ane Goikoetxea" required>
              </div>

              <!-- 4. Txartel zenbakia -->
              <div class="form-group">
                  <label for="txartela">Bezero Ordainketa Txartela</label>
                  <input type="text" id="txartela" name="txartela" placeholder="xxxx-xxxx-xxxx-xxxx" required>
              </div>

              <div class="botoi-container">
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

