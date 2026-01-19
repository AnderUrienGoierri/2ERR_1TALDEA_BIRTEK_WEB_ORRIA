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
  </head>
    
  <body class="web-gorputza">
    <?php include_once 'goiburua.php'; ?>

    <main class="eduki-nagusia">
      <div class="eskari-edukiontzia">
        <!-- Breadcrumb or Title -->
        <a href="produktuak.php" class="atzera-botoia tartea-behean-1"><i class="fas fa-arrow-left"></i> Dendara itzuli</a>
        <h2 class="tartea-behean-2">Erosketa Berretsi</h2>
        
        <div class="erosketa-sareta"> 
            <!-- 1. SASKIAREN EDUKIA (Eskari Txartela estiloa) -->
            <div class="eskari-txartela">
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
                <div class="saski-oina-guztira">
                    Guztira: <span id="erosketa-guztira" class="prezio-nabarmena">0.00 €</span>
                </div>
            </div>

            <!-- 2. BIDALKETA DATUAK (Eskari Txartela estiloa) -->
            <div class="eskari-txartela">
                <div class="eskari-goiburua">
                    <div>
                        <div class="eskari-izenburua"><i class="fas fa-truck"></i> Bidalketa Datuak</div>
                        <small class="testu-apala">Egiaztatu zure helbidea</small>
                    </div>
                </div>
                <div class="eskari-gorputza">
                    <p class="tartea-behean-1-5 testua-grisa">Datu hauek erabiliko dira bidalketa egiteko.</p>
                    
                    <form action="ordainketa_pasarela.php" method="POST" id="bidalketa-form">
                        <div class="inprimaki-taldea">
                            <label>Izena eta Abizenak</label>
                            <input type="text" class="inprimaki-sarrera ez-klikagarria" value="<?= htmlspecialchars($bezeroa['izena_edo_soziala'] . ' ' . $bezeroa['abizena']) ?>" disabled>
                        </div>

                        <div class="inprimaki-taldea">
                            <label for="helbidea">Helbidea</label>
                            <input type="text" id="helbidea" name="helbidea" class="inprimaki-sarrera" value="<?= htmlspecialchars($bezeroa['helbidea']) ?>" required>
                        </div>

                        <div class="inprimaki-taldea">
                            <label for="herria_id">Herria</label>
                            <select id="herria_id" name="herria_id" class="inprimaki-hautatu" required>
                                <?php foreach ($herriak as $herria): ?>
                                    <option value="<?= $herria['id_herria'] ?>" <?= ($bezeroa['herria_id'] == $herria['id_herria']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($herria['izena']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="inprimaki-taldea">
                            <label for="posta_kodea">Posta Kodea</label>
                            <input type="text" id="posta_kodea" name="posta_kodea" class="inprimaki-sarrera" value="<?= htmlspecialchars($bezeroa['posta_kodea']) ?>" required>
                        </div>

                        <div class="inprimaki-taldea extra-behean-2">
                            <label for="telefonoa">Telefonoa</label>
                            <input type="tel" id="telefonoa" name="telefonoa" class="inprimaki-sarrera" value="<?= htmlspecialchars($bezeroa['telefonoa']) ?>" required>
                        </div>

                        <button type="submit" class="botoia botoi-nagusia zabalera-osoa handia">
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
