<?php
session_start();
if (!isset($_SESSION['id_hornitzailea'])) {
    header("Location: hornitzaile_saioa_hasi.php");
    exit();
}
$izena = $_SESSION['izena_soziala'] ?? 'Hornitzailea';
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hornitzailearen Menua - BIRTEK</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="../css/estiloak_globala.css">
    <link rel="stylesheet" href="../css/estiloak_hornitzaile_menua.css">
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
            <a href="hornitzaile_menua.php" class="nab-botoia hornitzailea-aktibo">Birziklatu</a>
            <a href="langileak_menua.php" class="nab-botoia">Langileak</a>
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
          </div>
        </div>

        <div id="mugikor-menua" class="mugikor-menu-edukiontzia">
          <a href="hasiera.php" class="nab-botoia">Hasiera</a>
          <a href="produktuak.php" class="nab-botoia">Produktuak</a>
          <a href="berriak.php" class="nab-botoia">Berriak</a>
          <a href="kontaktua.php" class="nab-botoia">Kontaktua</a>
          <a href="hornitzaile_menua.php" class="nab-botoia hornitzailea-aktibo">Birziklatu</a>
          <a href="langileak_menua.php" class="nab-botoia">Langileak</a>

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
        <h2 class="welcome-msg">Ongi etorri, <?= htmlspecialchars($izena) ?>!</h2>
        
        <div class="menu-grid">
            <!-- 1. Aukera: Datuak Aldatu -->
            <a href="hornitzaile_datuak_aldatu.php" class="menu-card">
                <i class="fas fa-user-edit menu-icon"></i>
                <span class="menu-title">Datu Pertsonalak Aldatu</span>
            </a>

            <!-- 2. Aukera: Sarrera Egin (Produktuak Bidali) -->
            <a href="hornitzaile_sarrera_egin.php" class="menu-card">
                <i class="fas fa-truck-loading menu-icon"></i>
                <span class="menu-title">Sarrera Egin</span>
            </a>

            <!-- 3. Aukera: Sarrerak Kudeatu -->
            <a href="hornitzaile_sarrerak_kudeatu.php" class="menu-card">
                <i class="fas fa-clipboard-list menu-icon"></i>
                <span class="menu-title">Sarrerak Kudeatu</span>
            </a>
        </div>

        <button id="saioa-itxi-botoia" class="logout-btn" onclick="window.location.href='../php/logout.php'">
            <i class="fas fa-sign-out-alt"></i> Saioa itxi
        </button>
    </main>

    <footer class="oin-nagusia">
        <div class="oin-copyright">© 2025 BIRTEK</div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
</body>
</html>
