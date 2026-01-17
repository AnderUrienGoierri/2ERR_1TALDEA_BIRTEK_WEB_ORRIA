<?php
session_start();
require_once 'DB_konexioa.php';

// Egiaztatu bezeroa saioa hasita dagoen
if (!isset($_SESSION['id_bezeroa'])) {
    header("Location: bezero_saioa_hasi.php");
    exit();
}

$id_bezeroa = $_SESSION['id_bezeroa'];

// Bezeroaren datuak lortu (Helbidea betetzeko)
try {
    $stmt = $konexioa->prepare("SELECT izena_edo_soziala, abizena, helbidea, herria_id, posta_kodea, telefonoa FROM bezeroak WHERE id_bezeroa = ?");
    $stmt->execute([$id_bezeroa]);
    $bezeroa = $stmt->fetch(PDO::FETCH_ASSOC);

    // Herriak lortu (Dropdown-erako)
    $stmtH = $konexioa->query("SELECT id_herria, izena FROM herriak ORDER BY izena ASC");
    $herriak = $stmtH->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Errorea datubasearekin: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="eu">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BIRTEK - Erosketa Berretsi</title>

    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/estiloak_globala.css" />
    <!-- BEZERO ESKAERAK ESTILOA ERABILI -->
    <link rel="stylesheet" href="../css/estiloak_bezero_eskaerak.css" />
    
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
      <div class="eskari-edukiontzia">
        <!-- Breadcrumb or Title -->
        <a href="produktuak.php" class="atzera-botoia" style="margin-bottom: 1rem; display:inline-block;"><i class="fas fa-arrow-left"></i> Dendara itzuli</a>
        <h2 style="margin-bottom: 2rem;">Erosketa Berretsi</h2>
        
        <div class="erosketa-sareta"> 
            <!-- 1. SASKIAREN EDUKIA (Eskari Txartela estiloa) -->
            <div class="eskari-txartela" style="height: fit-content;">
                <div class="eskari-goiburua">
                    <div>
                        <div class="eskari-izenburua"><i class="fas fa-shopping-cart"></i> Zure Saskia</div>
                        <small class="testu-apala">Berrikusi zure artikuluak</small>
                    </div>
                </div>
                <div class="eskari-gorputza" id="erosketa-saski-container">
                    <!-- JS-k hemen taula sortuko du -->
                    <p>Saskia kargatzen...</p>
                </div>
                <!-- Guztira lerroa txartelaren barruan edo azpian -->
                <div style="padding: 1rem; text-align: right; background: #f9fafb; border-top: 1px solid #e5e7eb; font-weight: bold; font-size: 1.2rem;">
                    Guztira: <span id="erosketa-guztira" style="color: #166534;">0.00 €</span>
                </div>
            </div>

            <!-- 2. BIDALKETA DATUAK (Eskari Txartela estiloa) -->
            <div class="eskari-txartela" style="height: fit-content;">
                <div class="eskari-goiburua">
                    <div>
                        <div class="eskari-izenburua"><i class="fas fa-truck"></i> Bidalketa Datuak</div>
                        <small class="testu-apala">Egiaztatu zure helbidea</small>
                    </div>
                </div>
                <div class="eskari-gorputza">
                    <p style="margin-bottom: 1.5rem; color: #666;">Datu hauek erabiliko dira bidalketa egiteko.</p>
                    
                    <form action="ordainketa_pasarela.php" method="POST" id="bidalketa-form">
                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label>Izena eta Abizenak</label>
                            <input type="text" class="inprimaki-sarrera" value="<?= htmlspecialchars($bezeroa['izena_edo_soziala'] . ' ' . $bezeroa['abizena']) ?>" disabled style="background: #e5e7eb; cursor: not-allowed; color: #666;">
                        </div>

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label for="helbidea">Helbidea</label>
                            <input type="text" id="helbidea" name="helbidea" class="inprimaki-sarrera" value="<?= htmlspecialchars($bezeroa['helbidea']) ?>" required>
                        </div>

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label for="herria_id">Herria</label>
                            <select id="herria_id" name="herria_id" class="inprimaki-hautatu" required>
                                <?php foreach ($herriak as $herria): ?>
                                    <option value="<?= $herria['id_herria'] ?>" <?= ($bezeroa['herria_id'] == $herria['id_herria']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($herria['izena']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group" style="margin-bottom: 1rem;">
                            <label for="posta_kodea">Posta Kodea</label>
                            <input type="text" id="posta_kodea" name="posta_kodea" class="inprimaki-sarrera" value="<?= htmlspecialchars($bezeroa['posta_kodea']) ?>" required>
                        </div>

                        <div class="form-group" style="margin-bottom: 2rem;">
                            <label for="telefonoa">Telefonoa</label>
                            <input type="tel" id="telefonoa" name="telefonoa" class="inprimaki-sarrera" value="<?= htmlspecialchars($bezeroa['telefonoa']) ?>" required>
                        </div>

                        <button type="submit" class="botoia botoi-nagusia" style="width: 100%; justify-content: center; font-size: 1.1rem; padding: 1rem;">
                            Ordainketara Joan <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
      </div>
    </main>

    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
    <script src="../js/bezero_erosketa.js"></script>
  </body>
</html>
