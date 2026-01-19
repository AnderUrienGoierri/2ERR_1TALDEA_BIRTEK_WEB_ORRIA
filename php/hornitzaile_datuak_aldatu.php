<?php
session_start();
require_once 'DB_konexioa.php';

if (!isset($_SESSION['id_hornitzailea'])) {
    header("Location: hornitzaile_saioa_hasi.php");
    exit();
}

$id_hornitzailea = $_SESSION['id_hornitzailea'];
$mezua = "";

// Herriak kargatu dropdown-erako
$herriak = [];
try {
    $stmt_herriak = $konexioa->query("SELECT id_herria, izena FROM herriak ORDER BY izena");
    $herriak = $stmt_herriak->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Ezin badira herriak kargatu
}

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $izena_soziala = $_POST['izena_soziala'];
    $ifz_nan = $_POST['ifz_nan'];
    $kontaktu_pertsona = $_POST['kontaktu_pertsona'];
    $helbidea = $_POST['helbidea'];
    $herria_id = $_POST['herria_id'];
    $posta_kodea = $_POST['posta_kodea'];
    $telefonoa = $_POST['telefonoa'];
    $emaila = $_POST['emaila'];
    $hizkuntza = $_POST['hizkuntza'];
    $pasahitza = $_POST['pasahitza'];
    
    try {
        // Herri berria bada, txertatu lehenago
        if ($herria_id === 'berria' && !empty($_POST['herria_berria'])) {
            $herria_izena = $_POST['herria_berria'];
            $lurraldea = $_POST['lurraldea_berria'] ?? '';
            $nazioa = $_POST['nazioa_berria'] ?? '';

            $sql_herria = "INSERT INTO herriak (izena, lurraldea, nazioa) VALUES (:izena, :lurraldea, :nazioa)";
            $stmt_herria = $konexioa->prepare($sql_herria);
            $stmt_herria->execute([
                ':izena' => $herria_izena,
                ':lurraldea' => $lurraldea,
                ':nazioa' => $nazioa
            ]);
            $herria_id = $konexioa->lastInsertId();
        }

        $sql = "UPDATE hornitzaileak SET 
                izena_soziala = :izena, 
                ifz_nan = :ifz_nan,
                kontaktu_pertsona = :kontaktu,
                helbidea = :helbidea, 
                herria_id = :herria_id,
                posta_kodea = :posta_kodea,
                telefonoa = :telefonoa,
                emaila = :emaila,
                hizkuntza = :hizkuntza
                WHERE id_hornitzailea = :id";
        
        $params = [
            ':izena' => $izena_soziala,
            ':ifz_nan' => $ifz_nan,
            ':kontaktu' => $kontaktu_pertsona,
            ':helbidea' => $helbidea,
            ':herria_id' => $herria_id,
            ':posta_kodea' => $posta_kodea,
            ':telefonoa' => $telefonoa,
            ':emaila' => $emaila,
            ':hizkuntza' => $hizkuntza,
            ':id' => $id_hornitzailea
        ];

        $stmt = $konexioa->prepare($sql);
        $stmt->execute($params);

        if (!empty($pasahitza)) {
            $sql_pass = "UPDATE hornitzaileak SET pasahitza = :pasahitza WHERE id_hornitzailea = :id";
            $stmt_pass = $konexioa->prepare($sql_pass);
            $stmt_pass->execute([
                ':pasahitza' => $pasahitza, 
                ':id' => $id_hornitzailea
            ]);
        }
        
        $mezua = "Datuak ondo eguneratu dira!";
        $_SESSION['izena_soziala'] = $izena_soziala; 
        
        // Eguneratu herriak zerrenda berria ager dadin
        $stmt_herriak = $konexioa->query("SELECT id_herria, izena FROM herriak ORDER BY izena");
        $herriak = $stmt_herriak->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $mezua = "Errorea: " . $e->getMessage();
    }
}

// Fetch current data
$stmt = $konexioa->prepare("SELECT * FROM hornitzaileak WHERE id_hornitzailea = :id");
$stmt->execute([':id' => $id_hornitzailea]);
$hornitzailea = $stmt->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hornitzaile Datuak Aldatu - BIRTEK</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estiloak_globala.css">
    <link rel="stylesheet" href="../css/estiloak_kontaktua.css">
    <link rel="stylesheet" href="../css/estiloak_datu_pertsonalak_aldatu.css">

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
                <div class="saio-informazio-edukiontzia">
                    <a href="bezero_menua.php" class="saioa-hasi-botoia aktibo" id="saioa-hasi-botoia" title="Joan Nire Menura">
                        <i class="fas fa-user"></i> <span><?= htmlspecialchars($_SESSION['izena']) ?></span>
                    </a>
                    <button class="saioa-hasi-botoia botoi-gorria">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            <?php elseif (isset($_SESSION['id_hornitzailea'])): ?>
                <div class="saio-informazio-edukiontzia">
                    <a href="hornitzaile_menua.php" class="saioa-hasi-botoia aktibo" id="saioa-hasi-botoia" title="Joan Nire Menura">
                        <i class="fas fa-user"></i> <span><?= htmlspecialchars($_SESSION['izena_soziala'] ?? 'Hornitzailea') ?></span>
                    </a>
                    <button  class="saioa-hasi-botoia botoi-gorria">
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
          <a href="hornitzaile_menua.php" class="nab-botoia hornitzailea-aktibo">Birziklatu</a>
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
        <div class="kontaktu-edukiontzia">
            <h2 class="kontaktua-titulua">Hornitzaile Datuak Aldatu</h2>
            
            <div class="kontaktu-sareta">
                <div class="inprimaki-kutxa">
                    <h3 class="inprimaki-titulua">Profila Eguneratu</h3>
                    
                    <?php if ($mezua): ?>
                        <p class="arrakasta-mezua" style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 1.5rem; text-align: center;"><?= $mezua ?></p>
                    <?php endif; ?>

                    <form class="kontaktu-inprimaki-diseinua" method="POST">
                        <div class="inprimaki-sareta">
                            <h3 class="inprimaki-atal-izenburua">Enpresa edo Pertsona Informazioa</h3>
                            
                            <div class="inprimaki-taldea">
                                <label>Izena edo Izen-Soziala:</label>
                                <input type="text" name="izena_soziala" value="<?= htmlspecialchars($hornitzailea['izena_soziala']) ?>" class="inprimaki-sarrera" required>
                            </div>
                            <div class="inprimaki-taldea">
                                <label>IFZ / NAN:</label>
                                <input type="text" name="ifz_nan" value="<?= htmlspecialchars($hornitzailea['ifz_nan']) ?>" class="inprimaki-sarrera" required>
                            </div>
                            <div class="inprimaki-taldea">
                                <label>Kontaktu Pertsona:</label>
                                <input type="text" name="kontaktu_pertsona" value="<?= htmlspecialchars($hornitzailea['kontaktu_pertsona'] ?? '') ?>" class="inprimaki-sarrera">
                            </div>
                            <div class="inprimaki-taldea">
                                <label>Hizkuntza:</label>
                                <select name="hizkuntza" class="inprimaki-sarrera">
                                    <option value="Euskara" <?= ($hornitzailea['hizkuntza'] == 'Euskara') ? 'selected' : '' ?>>Euskara</option>
                                    <option value="Gaztelania" <?= ($hornitzailea['hizkuntza'] == 'Gaztelania') ? 'selected' : '' ?>>Gaztelania</option>
                                    <option value="Frantsesa" <?= ($hornitzailea['hizkuntza'] == 'Frantsesa') ? 'selected' : '' ?>>Frantsesa</option>
                                    <option value="Ingelesa" <?= ($hornitzailea['hizkuntza'] == 'Ingelesa') ? 'selected' : '' ?>>Ingelesa</option>
                                </select>
                            </div>

                            <h3 class="inprimaki-atal-izenburua">Kontaktua eta Helbidea</h3>
                            
                            <div class="inprimaki-taldea">
                                <label>Helbidea:</label>
                                <input type="text" name="helbidea" value="<?= htmlspecialchars($hornitzailea['helbidea']) ?>" class="inprimaki-sarrera" required>
                            </div>
                            <div class="inprimaki-taldea">
                                <label>Herria:</label>
                                <select name="herria_id" id="herria_id" class="inprimaki-sarrera" required>
                                    <?php foreach ($herriak as $herria): ?>
                                        <option value="<?= $herria['id_herria'] ?>" <?= ($hornitzailea['herria_id'] == $herria['id_herria']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($herria['izena']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="berria">-- Aukeratu Beste Bat --</option>
                                </select>
                            </div>

                            <div id="herri_berria_atala" style="display: none; grid-column: 1 / -1; background: rgba(0,0,0,0.05); padding: 1.5rem; border-radius: 0.5rem; margin-top: 0.5rem;">
                                <h4 style="margin-bottom: 1rem; color: var(--kolore-primarioa);">Herri Berriaren Datuak</h4>
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                                    <div class="inprimaki-taldea">
                                        <label>Herriaren Izena:</label>
                                        <input type="text" name="herria_berria" id="herria_berria" class="inprimaki-sarrera" placeholder="Adib: Tolosa">
                                    </div>
                                    <div class="inprimaki-taldea">
                                        <label>Lurraldea:</label>
                                        <input type="text" name="lurraldea_berria" id="lurraldea_berria" class="inprimaki-sarrera" placeholder="Adib: Gipuzkoa">
                                    </div>
                                    <div class="inprimaki-taldea">
                                        <label>Nazioa:</label>
                                        <input type="text" name="nazioa_berria" id="nazioa_berria" class="inprimaki-sarrera" placeholder="Adib: Euskal Herria">
                                    </div>
                                </div>
                            </div>
                            <div class="inprimaki-taldea">
                                <label>Posta Kodea:</label>
                                <input type="text" name="posta_kodea" value="<?= htmlspecialchars($hornitzailea['posta_kodea']) ?>" class="inprimaki-sarrera" required>
                            </div>
                            <div class="inprimaki-taldea">
                                <label>Telefonoa:</label>
                                <input type="text" name="telefonoa" value="<?= htmlspecialchars($hornitzailea['telefonoa']) ?>" class="inprimaki-sarrera" required>
                            </div>
                            <div class="inprimaki-taldea" style="grid-column: 1 / -1;">
                                <label>Emaila:</label>
                                <input type="email" name="emaila" value="<?= htmlspecialchars($hornitzailea['emaila']) ?>" class="inprimaki-sarrera" required>
                            </div>

                            <h3 class="inprimaki-atal-izenburua">Segurtasuna</h3>
                            
                            <div class="inprimaki-taldea" style="grid-column: 1 / -1;">
                                <label>Pasahitza Berria (Utzi hutsik ez aldatzeko):</label>
                                <input type="password" name="pasahitza" class="inprimaki-sarrera" placeholder="Pasahitza berria...">
                            </div>
                        </div>
                        
                        <button type="submit" class="botoia botoi-nagusia" style="margin-top:2rem;">Gorde Aldaketak</button>
                    </form>
                    
                    <div style="text-align: center; margin-top: 1.5rem;">
                        <a href="hornitzaile_menua.php" class="atzerako-botoia"><i class="fas fa-arrow-left"></i> Atzera Menura</a>
                    </div>
                </div>

                <div class="sozial-kutxa">
                    <img src="../irudiak/birtek_konponketak.png" alt="Repairing" class="kontaktu-irudia" style="width: 100%; height: 200px; object-fit: cover; border-radius: 1rem; margin-bottom: 2rem;">
                    <h3 class="sozial-azpititulua">Laguntza behar duzu?</h3>
                    <p class="testua-grisa tartea-behean-1-5">Zure datuak aldatzeko arazorik baduzu, jarri gurekin harremanetan erraz:</p>
                    <a href="https://wa.me/34600000000" target="_blank" class="footer-wa-botoia"><i class="fab fa-whatsapp"></i> WhatsApp Laguntza</a>
                </div>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
    <script>
        $(document).ready(function() {
            $('#herria_id').on('change', function() {
                if ($(this).val() === 'berria') {
                    $('#herri_berria_atala').slideDown();
                    $('#herria_berria').attr('required', true);
                    $('#lurraldea_berria').attr('required', true);
                    $('#nazioa_berria').attr('required', true);
                } else {
                    $('#herri_berria_atala').slideUp();
                    $('#herria_berria').removeAttr('required');
                    $('#lurraldea_berria').removeAttr('required');
                    $('#nazioa_berria').removeAttr('required');
                }
            });
        });
    </script>
</body>
</html>
