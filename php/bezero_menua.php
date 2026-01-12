<?php
session_start();
if (!isset($_SESSION['id_bezeroa'])) {
    header("Location: ../html/bezeroa_saioa_hasi.html");
    exit();
}
$izena = $_SESSION['izena'];
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bezeroaren Menua - BIRTEK</title>
    <!-- Use existing CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="../css/estiloak_globala.css">
    <link rel="stylesheet" href="../css/estiloak_bezero_menua.css">
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
          <a href="../html/hasiera.html" class="logo-edukiontzia">
            <span class="logoa">BIRTEK</span>
          </a>

          <div class="nab-menu-mahaigaina">
            <a href="../html/hasiera.html" class="nab-botoia">Hasiera</a>
            <a href="produktuak.php" class="nab-botoia">Produktuak</a>
            <a href="../html/berriak.html" class="nab-botoia">Berriak</a>
            <a href="../html/kontaktua.html" class="nab-botoia">Kontaktua</a>
            <a href="../html/hornitzailea_saioa_hasi.html" class="nab-botoia">Birziklatu</a>
            <a href="../html/langileak_menua.html" class="nab-botoia">Langileak</a>
          </div>

          <div class="nab-ekintzak">
            <!-- saioa hasi botoia -->
            <a href="../html/bezeroa_saioa_hasi.html" class="saioa-hasi-botoia"
              >Saioa Hasi</a
            >
            <!-- saskia botoia -->
            <button class="saski-botoia">
              <!-- karrito ikonoa -->
              <i class="fas fa-shopping-cart"></i>
              <span>Saskia</span>
              <!-- Hemen saski kontagailua hasten da (0 alioarekin adibidez) -->
              <span class="saski-kontagailu-txapa">0</span>
            </button>
          </div>
        </div>

        <div id="mugikor-menua" class="mugikor-menu-edukiontzia">
          <a href="../html/hasiera.html" class="nab-botoia">Hasiera</a>
          <a href="produktuak.php" class="nab-botoia">Produktuak</a>
          <a href="../html/berriak.html" class="nab-botoia">Berriak</a>
          <a href="../html/kontaktua.html" class="nab-botoia">Kontaktua</a>
          <a href="../html/hornitzailea_saioa_hasi.html" class="nab-botoia">Birziklatu</a>
          <a href="../html/langileak_menua.html" class="nab-botoia">Langileak</a>
        </div>
      </nav>
    </header>

    <main class="eduki-nagusia">
        <h2 class="welcome-msg">Kaixo, <?= htmlspecialchars($izena) ?>!</h2>
        
        <div class="menu-grid">
            <!-- Button 1: Datu-Pertsonalak aldatu -->
            <a href="bezero_datuak_aldatu.php" class="menu-card">
                <i class="fas fa-user-edit menu-icon"></i>
                <span class="menu-title">Datu-Pertsonalak aldatu</span>
            </a>

            <!-- Button 2: Erosketak Kudeatu -->
            <a href="bezero_eskaerak.php" class="menu-card">
                <i class="fas fa-shopping-bag menu-icon"></i>
                <span class="menu-title">Erosketak Kudeatu</span>
            </a>
        </div>

        <button id="saioa-itxi-botoia" class="logout-btn" >
            <i class="fas fa-sign-out-alt"></i> Saioa itxi
        </button>
    </main>

    <footer class="oin-nagusia">
        <div class="oin-copyright">© 2025 BIRTEK</div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
</body>
</html>

