<?php
session_start();

//include_once
require_once 'DB_konexioa.php';

if (!isset($_SESSION['id_bezeroa'])) {
    header("Location: bezero_saioa_hasi.php");
    exit();
}

$id_bezeroa = $_SESSION['id_bezeroa'];
$mezua = "";

// Herriak kargatu dropdown-erako
$herriak = [];
try {
    $stmt_herriak = $konexioa->query("SELECT id_herria, izena FROM herriak ORDER BY izena");
    $herriak = $stmt_herriak->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Ezin badira herriak kargatu, isilik jarraitu (edo mezua eman)
}

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $izena_edo_soziala = $_POST['izena_edo_soziala'];
    $abizena = $_POST['abizena'];
    $ifz_nan = $_POST['ifz_nan'];
    $jaiotza_data = $_POST['jaiotza_data'];
    $sexua = $_POST['sexua'];
    $bezero_ordainketa_txartela = $_POST['bezero_ordainketa_txartela'];
    $helbidea = $_POST['helbidea'];
    $herria_id = $_POST['herria_id'];
    $posta_kodea = $_POST['posta_kodea'];
    $telefonoa = $_POST['telefonoa'];
    $emaila = $_POST['emaila'];
    $hizkuntza = $_POST['hizkuntza'];
    $pasahitza = $_POST['pasahitza'];
    
    try {
        $sql = "UPDATE bezeroak SET 
                izena_edo_soziala = :izena, 
                abizena = :abizena, 
                ifz_nan = :ifz_nan,
                jaiotza_data = :jaiotza_data,
                sexua = :sexua,
                bezero_ordainketa_txartela = :txartela,
                helbidea = :helbidea, 
                herria_id = :herria_id,
                posta_kodea = :posta_kodea,
                telefonoa = :telefonoa,
                emaila = :emaila,
                hizkuntza = :hizkuntza
                WHERE id_bezeroa = :id";
        
        $params = [
            ':izena' => $izena_edo_soziala,
            ':abizena' => $abizena,
            ':ifz_nan' => $ifz_nan,
            ':jaiotza_data' => !empty($jaiotza_data) ? $jaiotza_data : null,
            ':sexua' => !empty($sexua) ? $sexua : null,
            ':txartela' => $bezero_ordainketa_txartela,
            ':helbidea' => $helbidea,
            ':herria_id' => $herria_id,
            ':posta_kodea' => $posta_kodea,
            ':telefonoa' => $telefonoa,
            ':emaila' => $emaila,
            ':hizkuntza' => $hizkuntza,
            ':id' => $id_bezeroa
        ];

        $stmt = $konexioa->prepare($sql);
        $stmt->execute($params);

        // Pasahitza aldatu bada soilik eguneratu
        if (!empty($pasahitza)) {
            $sql_pass = "UPDATE bezeroak SET pasahitza = :pasahitza WHERE id_bezeroa = :id";
            $stmt_pass = $konexioa->prepare($sql_pass);
            $stmt_pass->execute([
                ':pasahitza' => $pasahitza, // GOGORATU: idealen password_hash erabiltzea, baina hemen oraingoz testu gisa
                ':id' => $id_bezeroa
            ]);
        }
        
        $mezua = "Datuak ondo eguneratu dira!";
        $_SESSION['izena'] = $izena_edo_soziala; // Update session name
    } catch (PDOException $e) {
        $mezua = "Errorea: " . $e->getMessage();
    }
}

// Fetch current data
$stmt = $konexioa->prepare("SELECT * FROM bezeroak WHERE id_bezeroa = :id");
$stmt->execute([':id' => $id_bezeroa]);
$bezeroa = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datuak Aldatu - BIRTEK</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estiloak_globala.css">
    <link rel="stylesheet" href="../css/estiloak_kontaktua.css">
    <link rel="stylesheet" href="../css/estiloak_bezero_datuak_aldatu.css">

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
            <?php elseif (isset($_SESSION['id_hornitzailea'])): ?>
                <div class="saio-informazio-edukiontzia">
                    <a href="hornitzaile_menua.php" class="saioa-hasi-botoia aktibo" id="saioa-hasi-botoia" title="Joan Nire Menura">
                        <i class="fas fa-user"></i> <span><?= htmlspecialchars($_SESSION['izena_soziala']) ?></span>
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
          <?php elseif (isset($_SESSION['id_hornitzailea'])): ?>
              <div class="mugikor-erabiltzaile-edukiontzia">
                  <a href="hornitzaile_menua.php" class="nab-botoia mugikor-erabiltzaile-link">
                      <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['izena_soziala']) ?>
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
        <div class="inprimaki-edukiontzia bazter-borobilduak itzala-arin" style="max-width: 800px; margin: 2rem auto; padding: 2rem; background: #fff;">
            <h2 class="inprimaki-titulua" style="text-align: center; margin-bottom: 2rem;">Datu Pertsonalak Aldatu</h2>
            
            <?php if ($mezua): ?>
                <p class="arrakasta-mezua" style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 1.5rem; text-align: center;"><?= $mezua ?></p>
            <?php endif; ?>
            
            <form class="kontaktu-inprimaki-diseinua" method="POST">
                <div class="inprimaki-sareta">
                    <h3 class="inprimaki-atal-izenburua">Oinarrizko Informazioa</h3>
                    
                    <div class="inprimaki-taldea">
                        <label>Izena edo Izen Soziala:</label>
                        <input type="text" name="izena_edo_soziala" value="<?= htmlspecialchars($bezeroa['izena_edo_soziala']) ?>" class="inprimaki-sarrera" required>
                    </div>
                    <div class="inprimaki-taldea">
                        <label>Abizena:</label>
                        <input type="text" name="abizena" value="<?= htmlspecialchars($bezeroa['abizena'] ?? '') ?>" class="inprimaki-sarrera">
                    </div>
                    <div class="inprimaki-taldea">
                        <label>IFZ / NAN:</label>
                        <input type="text" name="ifz_nan" value="<?= htmlspecialchars($bezeroa['ifz_nan']) ?>" class="inprimaki-sarrera" required>
                    </div>
                    <div class="inprimaki-taldea">
                        <label>Jaiotza Data:</label>
                        <input type="date" name="jaiotza_data" value="<?= htmlspecialchars($bezeroa['jaiotza_data'] ?? '') ?>" class="inprimaki-sarrera">
                    </div>
                    <div class="inprimaki-taldea">
                        <label>Sexua:</label>
                        <select name="sexua" class="inprimaki-sarrera">
                            <option value="">Aukeratu...</option>
                            <option value="gizona" <?= ($bezeroa['sexua'] == 'gizona') ? 'selected' : '' ?>>Gizona</option>
                            <option value="emakumea" <?= ($bezeroa['sexua'] == 'emakumea') ? 'selected' : '' ?>>Emakumea</option>
                            <option value="ez-binarioa" <?= ($bezeroa['sexua'] == 'ez-binarioa') ? 'selected' : '' ?>>Ez-binarioa</option>
                        </select>
                    </div>
                    <div class="inprimaki-taldea">
                        <label>Hizkuntza:</label>
                        <select name="hizkuntza" class="inprimaki-sarrera">
                            <option value="Euskara" <?= ($bezeroa['hizkuntza'] == 'Euskara') ? 'selected' : '' ?>>Euskara</option>
                            <option value="Gaztelania" <?= ($bezeroa['hizkuntza'] == 'Gaztelania') ? 'selected' : '' ?>>Gaztelania</option>
                            <option value="Frantsesa" <?= ($bezeroa['hizkuntza'] == 'Frantsesa') ? 'selected' : '' ?>>Frantsesa</option>
                            <option value="Ingelesa" <?= ($bezeroa['hizkuntza'] == 'Ingelesa') ? 'selected' : '' ?>>Ingelesa</option>
                        </select>
                    </div>

                    <h3 class="inprimaki-atal-izenburua">Kontaktua eta Helbidea</h3>
                    
                    <div class="inprimaki-taldea">
                        <label>Helbidea:</label>
                        <input type="text" name="helbidea" value="<?= htmlspecialchars($bezeroa['helbidea']) ?>" class="inprimaki-sarrera" required>
                    </div>
                    <div class="inprimaki-taldea">
                        <label>Herria:</label>
                        <select name="herria_id" class="inprimaki-sarrera" required>
                            <?php foreach ($herriak as $herria): ?>
                                <option value="<?= $herria['id_herria'] ?>" <?= ($bezeroa['herria_id'] == $herria['id_herria']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($herria['izena']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="inprimaki-taldea">
                        <label>Posta Kodea:</label>
                        <input type="text" name="posta_kodea" value="<?= htmlspecialchars($bezeroa['posta_kodea']) ?>" class="inprimaki-sarrera" required>
                    </div>
                    <div class="inprimaki-taldea">
                        <label>Telefonoa:</label>
                        <input type="text" name="telefonoa" value="<?= htmlspecialchars($bezeroa['telefonoa']) ?>" class="inprimaki-sarrera" required>
                    </div>
                    <div class="inprimaki-taldea" style="grid-column: 1 / -1;">
                        <label>Emaila:</label>
                        <input type="email" name="emaila" value="<?= htmlspecialchars($bezeroa['emaila']) ?>" class="inprimaki-sarrera" required>
                    </div>

                    <h3 class="inprimaki-atal-izenburua">Segurtasuna eta Ordainketa</h3>
                    
                    <div class="inprimaki-taldea">
                        <label>Ordainketa Txartela (Token):</label>
                        <input type="text" name="bezero_ordainketa_txartela" value="<?= htmlspecialchars($bezeroa['bezero_ordainketa_txartela'] ?? '') ?>" class="inprimaki-sarrera">
                    </div>
                    <div class="inprimaki-taldea">
                        <label>Pasahitza Berria (Utzi hutsik ez aldatzeko):</label>
                        <input type="password" name="pasahitza" class="inprimaki-sarrera" placeholder="Pasahitza berria...">
                    </div>
                </div>
                
                <button type="submit" class="botoia botoi-nagusia" style="width:100%; margin-top:2rem; padding: 12px; font-size: 1.1rem;">Gorde Aldaketak</button>
            </form>
            
            <div style="text-align: center; margin-top: 1.5rem;">
                <a href="bezero_menua.php" class="atzerako-botoia"><i class="fas fa-arrow-left"></i> Atzera Menura</a>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
</body>
</html>

