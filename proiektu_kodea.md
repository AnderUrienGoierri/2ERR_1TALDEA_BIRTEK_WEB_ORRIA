# Proiektuaren Kodea

Hemen proiektuaren PHP, CSS, JS eta SQL fitxategi guztiak ikus ditzakezu.

---

## PHP Fitxategiak

### php/berriak.php
``php
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="eu">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BIRTEK - Berriak</title>

    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="../css/fontawesome/css/all.min.css"
    />
    <!-- Google Fonts -->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
    />

    <!-- gure css artxiboak -->
    <link rel="stylesheet" href="../css/estiloak_globala.css" />
    <link rel="stylesheet" href="../css/estiloak_berriak.css" />
  </head>

  <body class="web-gorputza">
    <?php include 'goiburua.php'; ?>

    <main class="eduki-nagusia">
      <section class="berriak-edukiontzia">
        <h2 class="berriak-titulua">Berriak</h2>

        <div class="berriak-sarea">
          <article class="albiste-txartela">
            <div class="albiste-irudia">
              <img src="https://images.unsplash.com/photo-1550009158-9ebf69173e03?auto=format&fit=crop&w=800&q=80" alt="Recycling Electronics" />
            </div>
            <div class="albiste-edukia">
              <span class="albiste-kategoria">Jasangarritasuna</span>
              <h2 class="albiste-titulua">Zergatik da garrantzitsua gailu elektronikoak berrerabiltzea?</h2>
              <p class="albiste-laburpena">Gailu bakoitzaren bizitza luzatzeak hondakin elektronikoak murrizten ditu eta baliabide naturalen erauzketa ekiditen du.</p>
            </div>
          </article>

          <article class="albiste-txartela">
            <div class="albiste-irudia">
              <img src="../irudiak/birtek_konponketak.png" alt="Repairing Laptop" loading="lazy" />
            </div>
            <div class="albiste-edukia">
              <span class="albiste-kategoria">Berritzea</span>
              <h2 class="albiste-titulua">Gure berritze prozesua: Kalitatea bermatuta</h2>
              <p class="albiste-laburpena">Ezagutu nola pasatzen dituzten gure gailuek kontrol zorrotzak erabiltzaileari esperientzia optimoa ziurtatzeko.</p>
            </div>
          </article>

          <article class="albiste-txartela">
            <div class="albiste-irudia">
              <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?auto=format&fit=crop&w=800&q=80" alt="Electronic Waste" />
            </div>
            <div class="albiste-edukia">
              <span class="albiste-kategoria">Ingurumena</span>
              <h2 class="albiste-titulua">Ekonomia zirkularra teknologiaren munduan</h2>
              <p class="albiste-laburpena">Hondakinak baliabide bihurtzea da gure helburu nagusia. Ezagutu nola lagundu dezakezun zu ere.</p>
            </div>
          </article>
        </div>
      </section>
    </main>

    <?php include_once 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
  </body>
</html>

``n
### php/bezero_datuak_aldatu.php
``php
<?php
session_start();

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

// Eguneraketa kudeatu
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
    $herria_izena = $_POST['herria_berria'] ?? '';
    $lurraldea = $_POST['lurraldea_berria'] ?? '';
    $nazioa = $_POST['nazioa_berria'] ?? '';

    try {
        // Herri berria bada, txertatu lehenago
        if ($herria_id === 'berria' && !empty($herria_izena)) {
            $sql_herria = "INSERT INTO herriak (izena, lurraldea, nazioa) VALUES (:izena, :lurraldea, :nazioa)";
            $stmt_herria = $konexioa->prepare($sql_herria);
            $stmt_herria->execute([
                ':izena' => $herria_izena,
                ':lurraldea' => $lurraldea,
                ':nazioa' => $nazioa
            ]);
            $herria_id = $konexioa->lastInsertId();
        }
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
        $_SESSION['izena'] = $izena_edo_soziala; 
    } catch (PDOException $e) {
        $mezua = "Errorea: " . $e->getMessage();
    }
}


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
    <link rel="stylesheet" href="../css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../css/estiloak_globala.css">
    <link rel="stylesheet" href="../css/estiloak_kontaktua.css">
    <link rel="stylesheet" href="../css/estiloak_datu_pertsonalak_aldatu.css">

</head>

<body class="web-gorputza">
    <?php include_once 'goiburua.php'; ?>

    <main class="eduki-nagusia">
        <div class="inprimaki-edukiontzia">
            <h2 class="inprimaki-titulua">Datu Pertsonalak Aldatu</h2>

            <?php if ($mezua): ?>
                <p class="arrakasta-mezua"><?= $mezua ?></p>
            <?php endif; ?>

            <form class="kontaktu-inprimaki-diseinua" method="POST">
                <div class="inprimaki-sareta">
                    <h3 class="inprimaki-atal-izenburua">Oinarrizko Informazioa</h3>

                    <div class="inprimaki-taldea">
                        <label>Izena edo Izen Soziala:</label>
                        <input type="text" name="izena_edo_soziala"
                            value="<?= htmlspecialchars($bezeroa['izena_edo_soziala']) ?>" class="inprimaki-sarrera"
                            required>
                    </div>
                    <div class="inprimaki-taldea">
                        <label>Abizena:</label>
                        <input type="text" name="abizena" value="<?= htmlspecialchars($bezeroa['abizena'] ?? '') ?>"
                            class="inprimaki-sarrera">
                    </div>
                    <div class="inprimaki-taldea">
                        <label>IFZ / NAN:</label>
                        <input type="text" name="ifz_nan" value="<?= htmlspecialchars($bezeroa['ifz_nan']) ?>"
                            class="inprimaki-sarrera" required>
                    </div>
                    <div class="inprimaki-taldea">
                        <label>Jaiotza Data:</label>
                        <input type="date" name="jaiotza_data"
                            value="<?= htmlspecialchars($bezeroa['jaiotza_data'] ?? '') ?>" class="inprimaki-sarrera">
                    </div>
                    <div class="inprimaki-taldea">
                        <label>Sexua:</label>
                        <select name="sexua" class="inprimaki-sarrera">
                            <option value="">Aukeratu...</option>
                            <option value="gizona" <?= ($bezeroa['sexua'] == 'gizona') ? 'selected' : '' ?>>Gizona</option>
                            <option value="emakumea" <?= ($bezeroa['sexua'] == 'emakumea') ? 'selected' : '' ?>>Emakumea
                            </option>
                            <option value="ez-binarioa" <?= ($bezeroa['sexua'] == 'ez-binarioa') ? 'selected' : '' ?>>
                                Ez-binarioa</option>
                        </select>
                    </div>
                    <div class="inprimaki-taldea">
                        <label>Hizkuntza:</label>
                        <select name="hizkuntza" class="inprimaki-sarrera">
                            <option value="Euskara" <?= ($bezeroa['hizkuntza'] == 'Euskara') ? 'selected' : '' ?>>Euskara
                            </option>
                            <option value="Gaztelania" <?= ($bezeroa['hizkuntza'] == 'Gaztelania') ? 'selected' : '' ?>>
                                Gaztelania</option>
                            <option value="Frantsesa" <?= ($bezeroa['hizkuntza'] == 'Frantsesa') ? 'selected' : '' ?>>
                                Frantsesa</option>
                            <option value="Ingelesa" <?= ($bezeroa['hizkuntza'] == 'Ingelesa') ? 'selected' : '' ?>>
                                Ingelesa</option>
                        </select>
                    </div>

                    <h3 class="inprimaki-atal-izenburua">Kontaktua eta Helbidea</h3>

                    <div class="inprimaki-taldea">
                        <label>Helbidea:</label>
                        <input type="text" name="helbidea" value="<?= htmlspecialchars($bezeroa['helbidea']) ?>"
                            class="inprimaki-sarrera" required>
                    </div>
                    <div class="inprimaki-taldea">
                        <label>Herria:</label>
                        <select name="herria_id" id="herria_id" class="inprimaki-sarrera" required>
                            <?php foreach ($herriak as $herria): ?>
                                <option value="<?= $herria['id_herria'] ?>" <?= ($bezeroa['herria_id'] == $herria['id_herria']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($herria['izena']) ?>
                                </option>
                            <?php endforeach; ?>
                            <option value="berria">-- Aukeratu Beste Bat --</option>
                        </select>
                    </div>

                    <div id="herri_berria_atala" class="herri-berria-panela zutabe-osoa ezkutuan">
                        <h4 class="herri-berria-izenburua">Herri Berriaren Datuak</h4>
                        <div class="herri-berria-sareta">
                            <div class="inprimaki-taldea">
                                <label>Herriaren Izena:</label>
                                <input type="text" name="herria_berria" id="herria_berria"
                                    class="inprimaki-sarrera" placeholder="Adib: Tolosa">
                            </div>
                            <div class="inprimaki-taldea">
                                <label>Lurraldea:</label>
                                <input type="text" name="lurraldea_berria" id="lurraldea_berria"
                                    class="inprimaki-sarrera" placeholder="Adib: Gipuzkoa">
                            </div>
                            <div class="inprimaki-taldea">
                                <label>Nazioa:</label>
                                <input type="text" name="nazioa_berria" id="nazioa_berria"
                                    class="inprimaki-sarrera" placeholder="Adib: Euskal Herria">
                            </div>
                        </div>
                    </div>
                    <div class="inprimaki-taldea">
                        <label>Posta Kodea:</label>
                        <input type="text" name="posta_kodea" value="<?= htmlspecialchars($bezeroa['posta_kodea']) ?>"
                            class="inprimaki-sarrera" required>
                    </div>
                    <div class="inprimaki-taldea">
                        <label>Telefonoa:</label>
                        <input type="text" name="telefonoa" value="<?= htmlspecialchars($bezeroa['telefonoa']) ?>"
                            class="inprimaki-sarrera" required>
                    </div>
                    <div class="inprimaki-taldea zutabe-osoa">
                        <label>Emaila:</label>
                        <input type="email" name="emaila" value="<?= htmlspecialchars($bezeroa['emaila']) ?>"
                            class="inprimaki-sarrera" required>
                    </div>

                    <h3 class="inprimaki-atal-izenburua">Segurtasuna eta Ordainketa</h3>

                    <div class="inprimaki-taldea">
                        <label>Ordainketa Txartela (Token):</label>
                        <input type="text" name="bezero_ordainketa_txartela"
                            value="<?= htmlspecialchars($bezeroa['bezero_ordainketa_txartela'] ?? '') ?>"
                            class="inprimaki-sarrera">
                    </div>
                    <div class="inprimaki-taldea">
                        <label>Pasahitza Berria (Utzi hutsik ez aldatzeko):</label>
                        <input type="password" name="pasahitza" class="inprimaki-sarrera"
                            placeholder="Pasahitza berria...">
                    </div>
                </div>

                <button type="submit" class="botoia botoi-nagusia datu-aldaketa-botoia">Gorde Aldaketak</button>
            </form>

            <div class="atzera-botoi-kontainer">
                <a href="bezero_menua.php" class="atzerako-botoia"><i class="fas fa-arrow-left"></i> Atzera Menura</a>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
    <script>
        $(document).ready(function () {
            $('#herria_id').on('change', function () {
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
``n
### php/bezero_erosketa.php
``php
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
      href="../css/fontawesome/css/all.min.css"
    />
    <link rel="stylesheet" href="../css/estiloak_globala.css" />
    <!-- BEZERO ESKAERAK ESTILOA ERABILI -->
    <link rel="stylesheet" href="../css/estiloak_bezero_eskaerak.css" />
  </head>
    
  <body class="web-gorputza">
    <?php include_once 'goiburua.php'; ?>

    <main class="eduki-nagusia">
      <div class="eskari-edukiontzia">
        <!-- Nabigazio bidea edo Izena -->
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

``n
### php/bezero_eskaerak.php
``php
<?php
session_start();

// include_once ere balio du
require_once 'DB_konexioa.php';

if (!isset($_SESSION['id_bezeroa'])) {
    header("Location: bezero_saioa_hasi.php");
    exit();
}

$id_bezeroa = $_SESSION['id_bezeroa'];
$mezua = "";

// Ezabatzeko ekintzak kudeatu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        try {
            $konexioa->beginTransaction();

            if ($_POST['action'] === 'delete_order' && isset($_POST['id_eskaera'])) {
                // Egiaztatu eskaera erabiltzailearena dela eta 'Prestatzen' egoeran dagoela
                $stmt = $konexioa->prepare("SELECT eskaera_egoera FROM eskaerak WHERE id_eskaera = :id AND bezeroa_id = :uid");
                $stmt->execute([':id' => $_POST['id_eskaera'], ':uid' => $id_bezeroa]);
                $order = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($order && strpos($order['eskaera_egoera'], 'Prestatzen') !== false) {
                    // 1. Stock-a berreskuratu eskaerako artikulu guztientzat
                    $linesStmt = $konexioa->prepare("SELECT produktua_id, kantitatea FROM eskaera_lerroak WHERE eskaera_id = :id");
                    $linesStmt->execute([':id' => $_POST['id_eskaera']]);
                    $lines = $linesStmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($lines as $line) {
                        $updateStock = $konexioa->prepare("UPDATE produktuak SET stock = stock + :qty WHERE id_produktua = :pid");
                        $updateStock->execute([':qty' => $line['kantitatea'], ':pid' => $line['produktua_id']]);
                    }

                    // 2. Markatu eskaera 'Ezabatua' gisa
                    $updateStmt = $konexioa->prepare("UPDATE eskaerak SET eskaera_egoera = 'Ezabatua' WHERE id_eskaera = :id");
                    $updateStmt->execute([':id' => $_POST['id_eskaera']]);

                    $konexioa->commit();
                    $mezua = "Eskaera ezabatu da eta stock-a berreskuratu da.";
                } else {
                    $konexioa->rollBack();
                }
            } elseif ($_POST['action'] === 'delete_line' && isset($_POST['id_eskaera_lerroa']) && isset($_POST['id_eskaera'])) {
                // Lehenik eskaeraren egoera egiaztatu
                $stmt = $konexioa->prepare("SELECT eskaera_egoera FROM eskaerak WHERE id_eskaera = :id AND bezeroa_id = :uid");
                $stmt->execute([':id' => $_POST['id_eskaera'], ':uid' => $id_bezeroa]);
                $order = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($order && strpos($order['eskaera_egoera'], 'Prestatzen') !== false) {
                    // 1. Lerroaren informazioa lortu stock-a berreskuratzeko eta guztira eguneratzeko
                    $lineStmt = $konexioa->prepare("SELECT produktua_id, unitate_prezioa, kantitatea FROM eskaera_lerroak WHERE id_eskaera_lerroa = :id");
                    $lineStmt->execute([':id' => $_POST['id_eskaera_lerroa']]);
                    $line = $lineStmt->fetch(PDO::FETCH_ASSOC);

                    if ($line) {
                        // 2. Stock-a berreskuratu
                        $updateStock = $konexioa->prepare("UPDATE produktuak SET stock = stock + :qty WHERE id_produktua = :pid");
                        $updateStock->execute([':qty' => $line['kantitatea'], ':pid' => $line['produktua_id']]);

                        // 3. Lerroa ezabatu
                        $deleteStmt = $konexioa->prepare("DELETE FROM eskaera_lerroak WHERE id_eskaera_lerroa = :id");
                        $deleteStmt->execute([':id' => $_POST['id_eskaera_lerroa']]);

                        // 4. Eskaeraren guztira eguneratu
                        $deduction = $line['unitate_prezioa'] * $line['kantitatea'];
                        $updateTotal = $konexioa->prepare("UPDATE eskaerak SET guztira_prezioa = guztira_prezioa - :val WHERE id_eskaera = :id");
                        $updateTotal->execute([':val' => $deduction, ':id' => $_POST['id_eskaera']]);

                        $konexioa->commit();
                        $mezua = "Produktua ezabatu da eta stock-a berreskuratu da.";
                    } else {
                        $konexioa->rollBack();
                    }
                } else {
                    $konexioa->rollBack();
                }
            } else {
                $konexioa->rollBack();
            }
        } catch (PDOException $e) {
            if ($konexioa->inTransaction()) {
                $konexioa->rollBack();
            }
            $mezua = "Errorea: " . $e->getMessage();
        }
    }
}

// Eskaerak ekarri
$stmt = $konexioa->prepare("
    SELECT * FROM eskaerak 
    WHERE bezeroa_id = :id 
    ORDER BY data DESC
");
$stmt->execute([':id' => $id_bezeroa]);
$eskaerak = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Eskaera lerroak lortzeko laguntza-funtzioa
function lortuEskeraLerroak($konexioa, $id_eskaera)
{
    $sql = "
        SELECT el.*, p.izena, p.deskribapena 
        FROM eskaera_lerroak el
        JOIN produktuak p ON el.produktua_id = p.id_produktua
        WHERE el.eskaera_id = :id
    ";
    $stmt = $konexioa->prepare($sql);
    $stmt->execute([':id' => $id_eskaera]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="eu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erosketak Kudeatu - BIRTEK</title>
    <link rel="stylesheet" href="../css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../css/estiloak_globala.css">
    <link rel="stylesheet" href="../css/estiloak_bezero_eskaerak.css">
    <script>
        function confirmDelete() {
            return confirm("Ziur zaude?");
        }
    </script>
</head>

<body class="web-gorputza">
    <?php include_once 'goiburua.php'; ?>

    <main class="eduki-nagusia">
        <div class="eskari-edukiontzia">
            <a href="bezero_menua.php" class="atzera-botoia"><i class="fas fa-arrow-left"></i> Atzera</a>
            <h2>Nire Erosketak</h2>
            <?php if ($mezua): ?>
                <div class="alert-berdea">
                    <?= htmlspecialchars($mezua) ?>
                </div>
            <?php endif; ?>

            <?php if (count($eskaerak) > 0): ?>
                <?php foreach ($eskaerak as $eskaera): ?>
                    <?php
                    $rawStatus = $eskaera['eskaera_egoera'];
                    $statusClass = 'egoera-' . explode('/', $rawStatus)[0];
                    $lerroak = lortuEskeraLerroak($konexioa, $eskaera['id_eskaera']);
                    $isPrestatzen = (strpos($rawStatus, 'Prestatzen') !== false);
                    $isOsatua = (strpos($rawStatus, 'Osatua') !== false || strpos($rawStatus, 'Bidalita') !== false);
                    ?>
                    <div class="eskari-txartela">
                        <div class="eskari-goiburua">
                            <div>
                                <div class="eskari-izenburua">Eskaera #<?= $eskaera['id_eskaera'] ?></div>
                                <small class="testu-apala"><?= $eskaera['data'] ?></small>
                            </div>
                            <div class="botoi-taldea-zentratuta">
                                <span class="eskari-egoera-etiketa <?= $statusClass ?>">
                                    <?= $eskaera['eskaera_egoera'] ?>
                                </span>
                                <?php if ($isPrestatzen): ?>
                                    <form method="POST" onsubmit="return confirmDelete()" class="m-0">
                                        <input type="hidden" name="action" value="delete_order">
                                        <input type="hidden" name="id_eskaera" value="<?= $eskaera['id_eskaera'] ?>">
                                        <button type="submit" class="ezabatu-eskaria-botoia">Ezabatu Eskaera</button>
                                    </form>
                                <?php endif; ?>
                                <?php if ($isOsatua): ?>
                                    <a href="deskargatu_faktura.php?id=<?= $eskaera['id_eskaera'] ?>"
                                        class="faktura-deskargatu-botoia">Faktura</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="eskari-gorputza">
                            <div class="taula-scroll-edukiontzia">
                                <table class="lerro-taula">
                                    <thead>
                                        <tr>
                                            <th>Produktua</th>
                                            <th>Kantitatea</th>
                                            <th>Prezioa</th>
                                            <th>Guztira</th>
                                            <th>Ekintzak</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($lerroak as $lerroa): ?>
                                            <tr>
                                                <td>
                                                    <a href="produktua_xehetasunak.php?id=<?= $lerroa['produktua_id'] ?>"
                                                        class="produktu-esteka">
                                                        <?= htmlspecialchars($lerroa['izena']) ?>
                                                    </a>
                                                </td>
                                                <td><?= $lerroa['kantitatea'] ?></td>
                                                <td><?= $lerroa['unitate_prezioa'] ?>€</td>
                                                <td><?= $lerroa['kantitatea'] * $lerroa['unitate_prezioa'] ?>€</td>
                                                <td>
                                                    <?php if ($isPrestatzen): ?>
                                                        <form method="POST" onsubmit="return confirmDelete()" class="m-0">
                                                            <input type="hidden" name="action" value="delete_line">
                                                            <input type="hidden" name="id_eskaera"
                                                                value="<?= $eskaera['id_eskaera'] ?>">
                                                            <input type="hidden" name="id_eskaera_lerroa"
                                                                value="<?= $lerroa['id_eskaera_lerroa'] ?>">
                                                            <button type="submit" class="ezabatu-lerroa-botoia"
                                                                title="Ezabatu produktua"><i class="fas fa-trash"></i></button>
                                                        </form>
                                                    <?php else: ?>
                                                        <span class="testu-apala">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="guztira-lerroa">
                                Guztira Ordainduta: <?= number_format($eskaera['guztira_prezioa'], 2) ?>€
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Ez daukazu eskaerarik oraindik.</p>
            <?php endif; ?>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
</body>

</html>
``n
### php/bezero_menua.php
``php
<?php
session_start();
if (!isset($_SESSION['id_bezeroa'])) {
    header("Location: bezero_saioa_hasi.php");
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
    <link rel="stylesheet" href="../css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="../css/estiloak_globala.css">
    <link rel="stylesheet" href="../css/estiloak_bezero_menua.css">
</head>

<body class="web-gorputza">
    <?php include_once 'goiburua.php'; ?>

    <main class="eduki-nagusia">
        <h2 class="ongi-etorri-mezua">Kaixo, <?= htmlspecialchars($izena) ?>!</h2>
        
        <div class="menu-bezeroa">
            <!-- Button 1: Datu-Pertsonalak aldatu -->
            <a href="bezero_datuak_aldatu.php" class="menu-txartela">
                <i class="fas fa-user-edit menu-ikonoa"></i>
                <span class="menu-izenburua">Datu-Pertsonalak aldatu</span>
            </a>

            <!-- Button 2: Erosketak Kudeatu -->
            <a href="bezero_eskaerak.php" class="menu-txartela">
                <i class="fas fa-shopping-bag menu-ikonoa"></i>
                <span class="menu-izenburua">Erosketak Kudeatu</span>
            </a>
            
             <!-- Button 3: Produktuak Ikusi -->
            <a href="produktuak.php" class="menu-txartela">
                <i class="fas fa-store menu-ikonoa"></i>
                <span class="menu-izenburua">Produktuak Ikusi</span>
            </a>
        </div>

        <button class="saioa-itxi-botoia botoi-gorria" id="logout-botoia-menua">
            <i class="fas fa-sign-out-alt"></i>Saioa Itxi
        </button>
    </main>

    <?php include 'footer.php'; ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
</body>

</html>

``n
### php/bezero_saioa_hasi.php
``php
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
  <title>BIRTEK - Saioa Hasi / Erregistratu</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="../css/fontawesome/css/all.min.css" />
  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" />

  <!-- gure css artxiboak -->
  <link rel="stylesheet" href="../css/estiloak_globala.css" />
  <link rel="stylesheet" href="../css/estiloak_kontaktua.css" />
</head>

<body class="web-gorputza">
  <?php include_once 'goiburua.php'; ?>

  <main class="eduki-nagusia">
    <section class="kontaktu-edukiontzia">
      <h2 class="kontaktua-titulua">Bezeroaren Gunea</h2>

      <div class="kontaktu-sareta">
        <!-- SAIOA HASI -->
        <div class="inprimaki-kutxa">
          <h3 class="inprimaki-titulua">Saioa Hasi</h3>
          <p class="tartea-behean-1-5 testua-grisa">Dagoeneko kontua baduzu, sartu hemen:</p>

          <?php if (isset($_GET['error'])): ?>
            <div class="login-errore-mezua tartea-behean-1">
              <i class="fas fa-exclamation-circle"></i> Posta elektronikoa edo pasahitza okerrak dira.
            </div>
          <?php endif; ?>
          <form class="kontaktu-inprimaki-diseinua" action="login_bezeroa.php" method="POST">
            <div>
              <input type="email" name="emaila" placeholder="Posta elektronikoa" class="inprimaki-sarrera" required />
            </div>
            <div>
              <input type="password" name="pasahitza" placeholder="Pasahitza" class="inprimaki-sarrera" required />
            </div>
            <button type="submit" class="botoia botoi-nagusia zabalera-osoa"
              id="saioa-hasi-submit-botoia">Sartu</button>
            <div class="testua-zentratuta tartea-goian-1">
              <a href="#" class="pasahitza-ahaztu-esteka">Pasahitza ahaztu duzu?</a>
            </div>
          </form>
        </div>

        <!-- ERREGISTRATU -->
        <div class="inprimaki-kutxa">
          <h3 class="inprimaki-titulua">Erregistratu</h3>
          <p class="tartea-behean-1-5 testua-grisa">Berria zara? Sortu kontu bat erraz:</p>
          <form id="bezero-erregistro-form" class="kontaktu-inprimaki-diseinua" action="erregistratu_bezeroa.php"
            method="POST">
            <div>
              <input type="text" name="izena" placeholder="Izena eta Abizenak" class="inprimaki-sarrera" required />
            </div>
            <div>
              <input type="email" name="emaila_erregistroa" placeholder="Posta elektronikoa" class="inprimaki-sarrera"
                required />
            </div>
            <div>
              <input type="text" name="nan" placeholder="NAN / IFZ (9 karaktere)" class="inprimaki-sarrera" required
                maxlength="9" minlength="9" />
            </div>
            <div>
              <input type="text" name="helbidea" placeholder="Helbidea" class="inprimaki-sarrera" />
            </div>
            <div>
              <input type="text" name="herria_izena" list="herriak_zerrenda" placeholder="Herria"
                class="inprimaki-sarrera" required />
              <datalist id="herriak_zerrenda">
                <?php foreach ($herriak as $herria): ?>
                  <option value="<?= htmlspecialchars($herria['izena']) ?>"></option>
                <?php endforeach; ?>
              </datalist>
            </div>
            <div>
              <input type="text" name="lurraldea" placeholder="Lurraldea (Probintzia) - Beharrezkoa berria bada"
                class="inprimaki-sarrera" />
            </div>
            <div>
              <input type="text" name="nazioa" placeholder="Nazioa - Beharrezkoa berria bada"
                class="inprimaki-sarrera" />
            </div>
            <div>
              <input type="text" name="posta_kodea" placeholder="Posta Kodea" class="inprimaki-sarrera" required
                pattern="[0-9]{5}" title="5 digituko posta kodea" />
            </div>
            <div>
              <input type="password" name="pasahitza_erregistroa" placeholder="Pasahitza segurua"
                class="inprimaki-sarrera" required minlength="8" />
              <p>Gutxienez 8 karaktere.</p>
            </div>
            <button type="submit" class="botoia botoi-nagusia">Sortu Kontua</button>
          </form>
        </div>
      </div>
    </section>
  </main>

  <?php include 'footer.php'; ?>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="../js/globala.js"></script>
  <script src="../js/bezero_saioa_hasi.js"></script>
  <script>
    // saioa hasita badago:
    $(document).on("saioa:baliozkoa", function (e, erabiltzailea, mota) {
      var menuUrl = (mota === 'hornitzailea') ? 'hornitzaile_menua.php' : 'bezero_menua.php';
      $(".kontaktu-sareta").html(
        '<div>' +
        '<h2>Ongi etorri berriro, ' + erabiltzailea + "!</h2>" +
        '<p>Dagoeneko saioa hasita daukazu.</p>' +
        '<a href="' + menuUrl + '" class="botoia botoi-nagusia">Joan Nire Menura</a>' +
        '</div>'
      );
    });
  </script>
</body>

</html>
``n
### php/DB_konexioa.php
``php
<?php
$zerbitzaria = "localhost";     // zerbitzaria
$datu_basea = "birtek_db";      // nire datu-basearen izena
$erabiltzailea = "root";        
// root soilik baimenak ditu lokalean erabiltzeko, GU ez gera lokalean konektatuko,
//  beraz root-en baimen berdinak dituen beste erabiltzaile bat sortu behar da.
$pasahitza = "1MG32025"; 

$dsn = "mysql:host=$zerbitzaria;port=3306;dbname=$datu_basea;charset=utf8";

try {
    
    $konexioa = new PDO($dsn, $erabiltzailea, $pasahitza);
    $konexioa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Log mezua konektatu dela adierazteko (nabigatzaileko kontsolan ikusteko)

} catch (PDOException $e) {
    // Log mezua errorea egon dela adierazteko (nabigatzaileko kontsolan ikusteko)
    // echo "<script>console.error('Datu-Basera EZ da konektatu');</script>";

    //  'application/json' goiburua kenduta, bestela nabigatzaileak 
    // script-ak testu arrunt bezala erakusten ditu eta ez ditu exekutatzen.
    
    // die: horeraino bakarrik (inprimatu eta gelditu)
    die(json_encode(["ERROREA" => "Datu-Base konexio Errorea: " . $e->getMessage()]));
}
?>


``n
### php/deskargatu_faktura.php
``php
<?php
session_start();

if (!isset($_SESSION['id_bezeroa']) || !isset($_GET['id'])) {
    die("Sarrera baimenik ez.");
}

$id_eskaera = $_GET['id'];

// Bideak definitu
$base_path = $_SERVER['DOCUMENT_ROOT'] . "/fakturak/";

// Fitxategia bilatu (.pdf formatuan bakarrik orain)
$filename = "faktura_" . $id_eskaera . ".pdf";
$filepath = $base_path . $filename;

// Bigarren aukera (izen formatu ezberdina bada)
if (!file_exists($filepath)) {
    $filename = $id_eskaera . ".pdf";
    $filepath = $base_path . $filename;
}

if (file_exists($filepath)) {
    // PDFa zerbitzatu deskarga moduan
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filepath));
    
    // Buffer-a garbitu
    ob_clean();
    flush();
    
    // Fitxategia irakurri eta bidali
    readfile($filepath);
    exit;
} else {
    // Errore mezu polita (aukerakoa, baina hobe die baino)
    die("Faktura ez da aurkitu zerbitzarian ($filename).");
}
?>

``n
### php/erregistratu_bezeroa.php
``php
<?php
session_start();
require_once 'DB_konexioa.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $izena = trim($_POST['izena']);
    $emaila = trim($_POST['emaila_erregistroa']);
    $helbidea = trim($_POST['helbidea']); // Oharra: Inprimakiak hau pasa dezake, edo agian gehitu egin behar dugu HTML-n falta bada
    $pasahitza = trim($_POST['pasahitza_erregistroa']);

    // Balio lehenetsiak
    $ifz_nan = trim($_POST['nan']);
    $herria_id = !empty($_POST['herria_id']) ? $_POST['herria_id'] : 1;
    $posta_kodea = !empty($_POST['posta_kodea']) ? substr(trim($_POST['posta_kodea']), 0, 5) : '00000';
    $telefonoa = '000000000';

    try {
        $check = $konexioa->prepare("SELECT id_bezeroa FROM bezeroak WHERE emaila = :emaila");
        $check->execute([':emaila' => $emaila]);
        if ($check->rowCount() > 0) {
            header("Location: bezero_saioa_hasi.php?error=exists");
            exit();
        }

        // Oharra: 'izena_edo_soziala' da DBko zutabearen izena 'izena'-rentzat
        $sql = "INSERT INTO bezeroak (izena_edo_soziala, emaila, pasahitza, helbidea, ifz_nan, herria_id, posta_kodea, telefonoa, aktibo) 
                VALUES (:izena, :emaila, :pasahitza, :helbidea, :ifz, :herria, :pk, :tel, 1)";

        // Herria kudeatu (Izena bidez bilatu edo sortu)
        $herria_izena = trim($_POST['herria_izena'] ?? '');
        $lurraldea = trim($_POST['lurraldea'] ?? '');
        $nazioa = trim($_POST['nazioa'] ?? 'Euskal Herria');

        if (empty($herria_izena)) {
            die("Herria beharrezkoa da.");
        }

        // Begiratu ea existitzen den jada (izena berbera)
        $checkHerria = $konexioa->prepare("SELECT id_herria FROM herriak WHERE izena = :izena LIMIT 1");
        $checkHerria->execute([':izena' => $herria_izena]);
        $existing = $checkHerria->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $herria_id = $existing['id_herria'];
        } else {
            // Berria da, lurraldea beharrezkoa da
            if (empty($lurraldea)) {
                die("Herria berria bada, Lurraldea (probintzia) zehaztea beharrezkoa da.");
            }
            // Txertatu berria
            $sqlHerria = "INSERT INTO herriak (izena, lurraldea, nazioa) VALUES (:izena, :lurraldea, :nazioa)";
            $stmtHerria = $konexioa->prepare($sqlHerria);
            $stmtHerria->execute([':izena' => $herria_izena, ':lurraldea' => $lurraldea, ':nazioa' => $nazioa]);
            $herria_id = $konexioa->lastInsertId();
        }

        $stmt = $konexioa->prepare($sql);
        $stmt->execute([
            ':izena' => $izena,
            ':emaila' => $emaila,
            ':pasahitza' => $pasahitza,
            ':helbidea' => $helbidea,
            ':ifz' => $ifz_nan,
            ':herria' => $herria_id,
            ':pk' => $posta_kodea,
            ':tel' => $telefonoa
        ]);

        $_SESSION['id_bezeroa'] = $konexioa->lastInsertId();
        $_SESSION['izena'] = $izena;
        $_SESSION['emaila'] = $emaila;

        header("Location: bezero_menua.php");
        exit();

    } catch (PDOException $e) {
        die("Errorea erregistratzean: " . $e->getMessage());
    }
} else {
    header("Location: bezero_saioa_hasi.php");
}
?>
``n
### php/erregistratu_hornitzailea.php
``php
<?php
session_start();
require_once 'DB_konexioa.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $izena = trim($_POST['izena']);
    $emaila = trim($_POST['emaila_erregistroa']);
    $helbidea = trim($_POST['helbidea']);
    $pasahitza = trim($_POST['pasahitza_erregistroa']);

    // Default values
    $ifz_nan = trim($_POST['nan']);
    $herria_id = !empty($_POST['herria_id']) ? $_POST['herria_id'] : 1;
    $posta_kodea = !empty($_POST['posta_kodea']) ? substr(trim($_POST['posta_kodea']), 0, 5) : '00000';
    $telefonoa = '000000000';

    try {
        // Check if email already exists
        $check = $konexioa->prepare("SELECT id_hornitzailea FROM hornitzaileak WHERE emaila = :emaila");
        $check->execute([':emaila' => $emaila]);
        if ($check->rowCount() > 0) {
            header("Location: hornitzaile_saioa_hasi.php?error=exists");
            exit();
        }

        $sql = "INSERT INTO hornitzaileak (izena_soziala, emaila, pasahitza, helbidea, ifz_nan, herria_id, posta_kodea, telefonoa, kontaktu_pertsona, hizkuntza, aktibo) 
                VALUES (:izena, :emaila, :pasahitza, :helbidea, :ifz, :herria, :pk, :tel, :kontaktu, 'Euskara', 1)";

        // Herria kudeatu (Izena bidez bilatu edo sortu)
        $herria_izena = trim($_POST['herria_izena'] ?? '');
        $lurraldea = trim($_POST['lurraldea'] ?? '');
        $nazioa = trim($_POST['nazioa'] ?? 'Euskal Herria');

        if (empty($herria_izena)) {
            die("Herria beharrezkoa da.");
        }

        // Begiratu ea existitzen den jada (izena berbera)
        $checkHerria = $konexioa->prepare("SELECT id_herria FROM herriak WHERE izena = :izena LIMIT 1");
        $checkHerria->execute([':izena' => $herria_izena]);
        $existing = $checkHerria->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            $herria_id = $existing['id_herria'];
        } else {
            // Berria da, lurraldea beharrezkoa da
            if (empty($lurraldea)) {
                die("Herria berria bada, Lurraldea (probintzia) zehaztea beharrezkoa da.");
            }
            // Txertatu berria
            $sqlHerria = "INSERT INTO herriak (izena, lurraldea, nazioa) VALUES (:izena, :lurraldea, :nazioa)";
            $stmtHerria = $konexioa->prepare($sqlHerria);
            $stmtHerria->execute([':izena' => $herria_izena, ':lurraldea' => $lurraldea, ':nazioa' => $nazioa]);
            $herria_id = $konexioa->lastInsertId();
        }

        $stmt = $konexioa->prepare($sql);
        $stmt->execute([
            ':izena' => $izena,
            ':emaila' => $emaila,
            ':pasahitza' => $pasahitza, // Plain text for consistency
            ':helbidea' => $helbidea,
            ':ifz' => $ifz_nan,
            ':herria' => $herria_id,
            ':pk' => $posta_kodea,
            ':tel' => $telefonoa,
            ':kontaktu' => $izena // Default to company name
        ]);

        // Auto login
        $_SESSION['id_hornitzailea'] = $konexioa->lastInsertId();
        $_SESSION['izena_soziala'] = $izena;
        $_SESSION['emaila'] = $emaila;

        header("Location: hornitzaile_menua.php");
        exit();

    } catch (PDOException $e) {
        die("Errorea erregistratzean: " . $e->getMessage());
    }
} else {
    header("Location: hornitzaile_saioa_hasi.php");
}
?>
``n
### php/footer.php
``php
<footer class="oin-nagusia">
  <div class="oin-sarea">
    <div class="testua-erdian">
      <h3 class="oin-izenburua">BIRTEK</h3>
      <img src="../irudiak/birtek_logo_berde_karratua.png" alt="BIRTEK Logo Karratua" class="oin-logo-irudia">
      <p class="oin-testua">Teknologia berreskuratzen, etorkizuna eraikitzen. Goierri Eskolan kokatuta.</p>
    </div>
    <div class="testua-erdian">
      <h4 class="oin-goiburua">Jarrai Gaitzazu</h4>
      <div class="oin-sozial-lerroa">
        <a href="#" class="sozial-lotura testua-fb"><i class="fab fa-facebook"></i></a>
        <a href="#" class="sozial-lotura testua-ig"><i class="fab fa-instagram"></i></a>
        <a href="#" class="sozial-lotura testua-x"><i class="fab fa-x-twitter"></i></a>
        <a href="#" class="sozial-lotura testua-tik"><i class="fab fa-tiktok"></i></a>
        <a href="#" class="sozial-lotura testua-tg"><i class="fab fa-telegram"></i></a>
      </div>
      <div><a href="https://wa.me/34600000000" class="oin-whatsapp-botoia"><i class="fab fa-whatsapp"></i> WhatsApp</a>
      </div>
    </div>
    <div class="testua-erdian">
      <h4 class="oin-goiburua">Lotura Azkarrak</h4>
      <ul class="oin-nab-zerrenda">
        <li><a href="hasiera.php" class="oin-lotura">Hasiera</a></li>
        <li><a href="produktuak.php" class="oin-lotura">Produktuak</a></li>
        <li><a href="berriak.php" class="oin-lotura">Berriak</a></li>
        <li><a href="kontaktua.php" class="oin-lotura">Kontaktua</a></li>
        <li><a
            href="<?= isset($_SESSION['id_hornitzailea']) ? 'hornitzaile_menua.php' : 'hornitzaile_saioa_hasi.php' ?>"
            class="oin-lotura">Birziklatu</a></li>
        <li><a href="langileak_menua.php" class="oin-lotura">Langileak</a></li>
      </ul>
    </div>
  </div>
  <div class="oin-copyright">© 2025 BIRTEK</div>

  <!-- Gora Joan Botoia -->
  <button id="gora-joan-botoia" title="Gora joan">
    <i class="fas fa-arrow-up"></i>
  </button>
</footer>
``n
### php/get_saio_egoera.php
``php
<?php
session_start();
header('Content-Type: application/json');

$response = [
    'saioa_hasita' => false,
    'izena' => ''
];

if (isset($_SESSION['id_bezeroa'])) {
    $response['saioa_hasita'] = true;
    $response['izena'] = $_SESSION['izena'] ?? 'Bezeroa';
    $response['mota'] = 'bezeroa';
} elseif (isset($_SESSION['id_hornitzailea'])) {
    $response['saioa_hasita'] = true;
    $response['izena'] = $_SESSION['izena_soziala'] ?? 'Hornitzailea';
    $response['mota'] = 'hornitzailea';
}

echo json_encode($response);
?>


``n
### php/goiburua.php
``php
<header class="goiburu-nagusia">
  <nav class="nab-edukiontzia">
    <div class="goiburu-barnealdea">
      <!-- Mugikorra Menu Botoia -->
      <button id="mugikor-menu-botoia" class="mugikor-menu-botoia">
        <i class="fas fa-bars burger-ikonoa"></i>
      </button>

      <!-- logoa -->
      <a href="hasiera.php" class="logo-edukiontzia">
        <img src="../irudiak/birtek_logo_zuri_borobila.png" alt="BIRTEK Logo" class="goiburu-logo-irudia">
        <span class="logoa">BIRTEK</span>
      </a>

      <div class="nab-menu-mahaigaina">
        <a href="hasiera.php" class="nab-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'hasiera.php') ? 'aktibo' : ''; ?>">Hasiera</a>
        <a href="produktuak.php" class="nab-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'produktuak.php') ? 'aktibo' : ''; ?>">Produktuak</a>
        <a href="berriak.php" class="nab-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'berriak.php') ? 'aktibo' : ''; ?>">Berriak</a>
        <a href="kontaktua.php" class="nab-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'kontaktua.php') ? 'aktibo' : ''; ?>">Kontaktua</a>
        <a href="<?= isset($_SESSION['id_hornitzailea']) ? 'hornitzaile_menua.php' : 'hornitzaile_saioa_hasi.php' ?>" class="nab-botoia 
        <?= isset($_SESSION['id_hornitzailea']) ? (basename($_SERVER['PHP_SELF']) == 'hornitzaile_menua.php' ? 'hornitzailea-aktibo aktibo' : 'hornitzailea-aktibo') : (basename($_SERVER['PHP_SELF']) == 'hornitzaile_saioa_hasi.php' ? 'aktibo' : '') ?>">Birziklatu</a>
        <a href="langileak_menua.php" class="nab-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'langileak_menua.php') ? 'aktibo' : ''; ?>">Langileak</a>
      </div>

      <div class="nab-ekintzak">
        <?php if (isset($_SESSION['id_bezeroa'])): ?>
          <div class="erabiltzaile-dropdown">
                <a href="bezero_menua.php" class="saioa-hasi-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'bezero_menua.php') ? 'aktibo' : ''; ?>">
                    <i class="fas fa-user-circle"></i> <span><?= htmlspecialchars($_SESSION['izena']) ?></span> <i class="fas fa-chevron-down goiburu-ikono-txikia"></i>
                </a>
                <div class="dropdown-edukia">
                    <a href="bezero_datuak_aldatu.php" class="dropdown-elementua"><i class="fas fa-id-card"></i> Nire Profila</a>
                    <a href="bezero_eskaerak.php" class="dropdown-elementua"><i class="fas fa-shopping-bag"></i> Nire Eskaerak</a>
                    <a href="#" class="dropdown-elementua gorria saioa-itxi-botoia-dropdown"><i class="fas fa-sign-out-alt"></i> Saioa Itxi</a>
                </div>
          </div>
        <?php elseif (isset($_SESSION['id_hornitzailea'])): ?>
          <div class="erabiltzaile-dropdown">
                <a href="hornitzaile_menua.php" class="saioa-hasi-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'hornitzaile_menua.php') ? 'aktibo' : ''; ?>">
                    <i class="fas fa-user-circle"></i> <span><?= htmlspecialchars($_SESSION['izena_soziala']) ?></span> <i class="fas fa-chevron-down goiburu-ikono-txikia"></i>
                </a>
                <div class="dropdown-edukia">
                    <a href="hornitzaile_datuak_aldatu.php" class="dropdown-elementua"><i class="fas fa-id-card"></i> Nire Profila</a>
                    <a href="hornitzaile_sarrerak_kudeatu.php" class="dropdown-elementua"><i class="fas fa-truck-loading"></i> Nire Sarrerak</a>
                    <a href="#" class="dropdown-elementua gorria saioa-itxi-botoia-dropdown"><i class="fas fa-sign-out-alt"></i> Saioa Itxi</a>
                </div>
          </div>
        <?php else: ?>
          <a href="bezero_saioa_hasi.php" class="saioa-hasi-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'bezero_saioa_hasi.php') ? 'aktibo' : ''; ?>" id="saioa-hasi-botoia">Saioa Hasi</a>
        <?php endif; ?>
        
        <button class="saski-botoia" id="saski-botoia-toggle">
          <i class="fas fa-shopping-cart"></i>
          <span>Saskia</span>
          <span class="saski-kontagailua">0</span>
        </button>
        <div id="saski-mezua" class="saski-mezua"></div>
      </div>
    </div>

    <div id="mugikor-menua" class="mugikor-menu-edukiontzia">
      <a href="hasiera.php" class="nab-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'hasiera.php') ? 'aktibo' : ''; ?>">Hasiera</a>
      <a href="produktuak.php" class="nab-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'produktuak.php') ? 'aktibo' : ''; ?>">Produktuak</a>
      <a href="berriak.php" class="nab-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'berriak.php') ? 'aktibo' : ''; ?>">Berriak</a>
      <a href="kontaktua.php" class="nab-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'kontaktua.php') ? 'aktibo' : ''; ?>">Kontaktua</a>
      <a href="<?= isset($_SESSION['id_hornitzailea']) ? 'hornitzaile_menua.php' : 'hornitzaile_saioa_hasi.php' ?>" class="nab-botoia <?= isset($_SESSION['id_hornitzailea']) ? (basename($_SERVER['PHP_SELF']) == 'hornitzaile_menua.php' ? 'hornitzailea-aktibo aktibo' : 'hornitzailea-aktibo') : (basename($_SERVER['PHP_SELF']) == 'hornitzaile_saioa_hasi.php' ? 'aktibo' : '') ?>">Birziklatu</a>
      <a href="langileak_menua.php" class="nab-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'langileak_menua.php') ? 'aktibo' : ''; ?>">Langileak</a>

      <?php if (isset($_SESSION['id_bezeroa'])): ?>
          <div class="mugikor-erabiltzaile-edukiontzia">
              <a href="bezero_menua.php" class="nab-botoia mugikor-erabiltzaile-link <?php echo (basename($_SERVER['PHP_SELF']) == 'bezero_menua.php') ? 'aktibo' : ''; ?>">
                  <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['izena']) ?>
              </a>
              <button id="mugikor-saioa-itxi-botoia" class="nab-botoia mugikor-logout-botoia">
                  <i class="fas fa-sign-out-alt"></i> Saioa Itxi
              </button>
          </div>
      <?php elseif (isset($_SESSION['id_hornitzailea'])): ?>
          <div class="mugikor-erabiltzaile-edukiontzia">
              <a href="hornitzaile_menua.php" class="nab-botoia mugikor-erabiltzaile-link <?php echo (basename($_SERVER['PHP_SELF']) == 'hornitzaile_menua.php') ? 'aktibo' : ''; ?>">
                  <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['izena_soziala']) ?>
              </a>
              <button id="mugikor-saioa-itxi-botoia" class="nab-botoia mugikor-logout-botoia">
                  <i class="fas fa-sign-out-alt"></i> Saioa Itxi
              </button>
          </div>
      <?php else: ?>
          <a href="bezero_saioa_hasi.php" class="nab-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'bezero_saioa_hasi.php') ? 'aktibo' : ''; ?>">Saioa Hasi</a>
      <?php endif; ?>
    </div>
  </nav>
</header>

``n
### php/gorde_eskaera_langilea.php
``php
<?php
require_once 'DB_konexioa.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Datuak jaso eta garbitu
    $izena = trim($_POST['izena'] ?? '');
    $abizena = trim($_POST['abizenak'] ?? '');
    $emaila = trim($_POST['emaila'] ?? '');
    $telefonoa = trim($_POST['telefonoa'] ?? '');

    // 2. Balidazioak
    if (empty($izena) || empty($abizena) || empty($emaila) || empty($telefonoa)) {
        http_response_code(400);
        echo "Mesedez, bete eremu guztiak.";
        exit;
    }

    // 3. Fitxategia (CV) prozesatu
    $pdfData = null;
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['cv']['tmp_name'];
        $fileType = $_FILES['cv']['type'];

        // Ziurtatu PDF dela
        if ($fileType === 'application/pdf') {
            $pdfData = file_get_contents($fileTmpPath);
        } else {
            http_response_code(400);
            echo "Mesedez, igo PDF formatuko fitxategi bat.";
            exit;
        }
    } else {
        http_response_code(400);
        echo "CV fitxategia igotzea beharrezkoa da.";
        exit;
    }

    try {
        // 4. Datu basean txertatu
        // Oharra: 'oharra' ez da gordetzen taulan ez dagoelako zutaberik.
        // Aktibo = 0 (ez dago aktibo oraindik)
        $sql = "INSERT INTO langileak (izena, abizena, emaila, telefonoa, kurrikuluma, aktibo) 
                VALUES (:izena, :abizena, :emaila, :telefonoa, :cv, 0)";

        $stmt = $konexioa->prepare($sql);
        $stmt->bindParam(':izena', $izena);
        $stmt->bindParam(':abizena', $abizena);
        $stmt->bindParam(':emaila', $emaila);
        $stmt->bindParam(':telefonoa', $telefonoa);
        $stmt->bindParam(':cv', $pdfData, PDO::PARAM_LOB); // BLOB gisa

        $stmt->execute();

        // 5. Arrakasta
        echo "Eskaera bidalita, eskerrikasko!";

    } catch (PDOException $e) {
        http_response_code(500);
        // Produkzioan ez erakutsi errore zehatza erabiltzaileari
        echo "Errorea datu basean: " . $e->getMessage();
    }
} else {
    http_response_code(405);
    echo "Metodoa ez da onartzen.";
}
?>

``n
### php/hasiera.php
``php
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="eu">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BIRTEK - Hasiera</title>

    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="../css/fontawesome/css/all.min.css"
    />
    <!-- Google Fonts -->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
    />

    <!-- bxSlider CSS -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bxslider@4.2.17/dist/jquery.bxslider.min.css"
    />

    <!-- gure css artxiboak -->
    <link rel="stylesheet" href="../css/estiloak_globala.css" />
    <link rel="stylesheet" href="../css/estiloak_hasiera.css" />
  </head>

  <body class="web-gorputza">
    <?php include 'goiburua.php'; ?>

    <main class="eduki-nagusia">
      <section>
        <div class="azal-kaxa">
          <div>
            <h1 class="azal-izenburua">Ongi Etorri BIRTEK</h1>
            <img
              src="../irudiak/birtek2.jpeg"
              alt="Birtek Eraikin Nagusia"
              class="hasiera-azal-irudia"
              loading="lazy"
            />
            <p class="azal-azpititulua">
              Teknologia berreskuratzen, etorkizuna eraikitzen.
            </p>
            <div class="azal-botoi-taldea">
              <a href="produktuak.php" class="azal-cta-botoia azal-botoi-erosi">
                <i class="fas fa-shopping-cart"></i> EROSI
              </a>
              <a href="<?= isset($_SESSION['id_hornitzailea']) ? 'hornitzaile_menua.php' : 'hornitzaile_saioa_hasi.php' ?>" class="azal-cta-botoia azal-botoi-birziklatu">
                <i class="fas fa-recycle"></i> BIRZIKLATU
              </a>
            </div>
          </div>
        </div>

        <div class="slider-kanpo-edukiontzia">
          <div class="hasiera-slider-egitura">
            <div><img src="../irudiak/birtek_biltegia.png" alt="Birtek Biltegia" title="Gure Biltegi Nagusia" /></div>
            <div><img src="../irudiak/birtek_sarrerak_birziklapena.jpeg" alt="Birtek Sarrerak Birziklapena" title="Birziklapen Prozesua" /></div>
            <div><img src="../irudiak/birtek_erakuslekua.png" alt="Birtek Erakuslekua" title="Produktuak Ikusgai" /></div>
            <div><img src="../irudiak/birtek_rezepzioa.jpeg" alt="Birtek Rezepzioa" title="Harrera Gunea" /></div>
          </div>
        </div>

        <div class="hasiera-zerrenda-edukiontzia">
          <div class="hasiera-txartel-lerroa">
            <div class="hasiera-zutabea"><img src="../irudiak/birtek_erakuslekua.png" alt="Erakuslekua" class="hasiera-txartel-irudia" loading="lazy" /></div>
            <div class="hasiera-zutabea">
              <h3 class="hasiera-txartel-izenburua">Gure Erakuslekua</h3>
              <p class="hasiera-txartel-testua">Hemen gure produktu berrituak bertatik bertara ikus ditzakezu.</p>
            </div>
          </div>
          <div class="hasiera-txartel-lerroa">
            <div class="hasiera-zutabea"><img src="../irudiak/birtek_rezepzioa.jpeg" alt="Harrera" class="hasiera-txartel-irudia" loading="lazy" /></div>
            <div class="hasiera-zutabea">
              <h3 class="hasiera-txartel-izenburua">Harrera eta Arreta</h3>
              <p class="hasiera-txartel-testua">Zalantzak argitzeko eta eskaerak jasotzeko gunea.</p>
            </div>
          </div>
          <div class="hasiera-txartel-lerroa">
            <div class="hasiera-zutabea"><img src="../irudiak/birtek_biltegia.png" alt="Biltegia" class="hasiera-txartel-irudia" loading="lazy" /></div>
            <div class="hasiera-zutabea">
              <h3 class="hasiera-txartel-izenburua">Biltegi Logistikoa</h3>
              <p class="hasiera-txartel-testua">Stock kudeaketa eta bidalketen antolaketa zentroa.</p>
            </div>
          </div>
          <div class="hasiera-txartel-lerroa">
            <div class="hasiera-zutabea"><img src="../irudiak/birtek_konponketak.png" alt="Tailerra" class="hasiera-txartel-irudia" loading="lazy" /></div>
            <div class="hasiera-zutabea">
              <h3 class="hasiera-txartel-izenburua">Konponketa Tailerra</h3>
              <p class="hasiera-txartel-testua">Gure teknikari espezialistek gailuak biziberritzen dituzten lekua.</p>
            </div>
          </div>
          <div class="hasiera-txartel-lerroa">
            <div class="hasiera-zutabea"><img src="../irudiak/birtek_sarrerak_birziklapena.jpeg" alt="Birziklapena" class="hasiera-txartel-irudia" loading="lazy" /></div>
            <div class="hasiera-zutabea">
              <h3 class="hasiera-txartel-izenburua">Birziklapena</h3>
              <p class="hasiera-txartel-testua">Material elektronikoa jasotzen eta sailkatzen dugu.</p>
            </div>
          </div>
        </div>
      </section>
    </main>

    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bxslider@4.2.17/dist/jquery.bxslider.min.js"></script>
    <script src="../js/globala.js"></script>
    <script src="../js/hasiera.js"></script>
  </body>
</html>

``n
### php/hornitzaile_datuak_aldatu.php
``php
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

// Eguneraketa kudeatu
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

// Uneko datuak lortu
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
    <link rel="stylesheet" href="../css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../css/estiloak_globala.css">
    <link rel="stylesheet" href="../css/estiloak_kontaktua.css">
    <link rel="stylesheet" href="../css/estiloak_datu_pertsonalak_aldatu.css">

</head>

<body class="web-gorputza">
    <?php include 'goiburua.php'; ?>

    <main class="eduki-nagusia">
        <div class="kontaktu-edukiontzia">
            <h2 class="kontaktua-titulua">Hornitzaile Datuak Aldatu</h2>

            <div class="kontaktu-sareta">
                <div class="inprimaki-kutxa">
                    <h3 class="inprimaki-titulua">Profila Eguneratu</h3>

                    <?php if ($mezua): ?>
                        <p class="arrakasta-mezua testua-zentratuta"><?= $mezua ?></p>
                    <?php endif; ?>

                    <form class="kontaktu-inprimaki-diseinua" method="POST">
                        <div class="inprimaki-sareta">
                            <h3 class="inprimaki-atal-izenburua">Enpresa edo Pertsona Informazioa</h3>

                            <div class="inprimaki-taldea">
                                <label>Izena edo Izen-Soziala:</label>
                                <input type="text" name="izena_soziala"
                                    value="<?= htmlspecialchars($hornitzailea['izena_soziala']) ?>"
                                    class="inprimaki-sarrera" required>
                            </div>
                            <div class="inprimaki-taldea">
                                <label>IFZ / NAN:</label>
                                <input type="text" name="ifz_nan"
                                    value="<?= htmlspecialchars($hornitzailea['ifz_nan']) ?>" class="inprimaki-sarrera"
                                    required>
                            </div>
                            <div class="inprimaki-taldea">
                                <label>Kontaktu Pertsona:</label>
                                <input type="text" name="kontaktu_pertsona"
                                    value="<?= htmlspecialchars($hornitzailea['kontaktu_pertsona'] ?? '') ?>"
                                    class="inprimaki-sarrera">
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
                                <input type="text" name="helbidea"
                                    value="<?= htmlspecialchars($hornitzailea['helbidea']) ?>" class="inprimaki-sarrera"
                                    required>
                            </div>
                            <div class="inprimaki-taldea">
                                <label>Herria:</label>
                                <select name="herria_id" id="herria_id" class="inprimaki-sarrera" required>
                                    <?php foreach ($herriak as $herria): ?>
                                        <option value="<?= $herria['id_herria'] ?>"
                                            <?= ($hornitzailea['herria_id'] == $herria['id_herria']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($herria['izena']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="berria">-- Aukeratu Beste Bat --</option>
                                </select>
                            </div>

                            <div id="herri_berria_atala" class="herri-berria-panela">
                                <h4 class="herri-berria-izenburua">Herri Berriaren Datuak</h4>
                                <div class="herri-berria-sareta">
                                    <div class="inprimaki-taldea">
                                        <label>Herriaren Izena:</label>
                                        <input type="text" name="herria_berria" id="herria_berria"
                                            class="inprimaki-sarrera" placeholder="Adib: Tolosa">
                                    </div>
                                    <div class="inprimaki-taldea">
                                        <label>Lurraldea:</label>
                                        <input type="text" name="lurraldea_berria" id="lurraldea_berria"
                                            class="inprimaki-sarrera" placeholder="Adib: Gipuzkoa">
                                    </div>
                                    <div class="inprimaki-taldea">
                                        <label>Nazioa:</label>
                                        <input type="text" name="nazioa_berria" id="nazioa_berria"
                                            class="inprimaki-sarrera" placeholder="Adib: Euskal Herria">
                                    </div>
                                </div>
                            </div>
                            <div class="inprimaki-taldea">
                                <label>Posta Kodea:</label>
                                <input type="text" name="posta_kodea"
                                    value="<?= htmlspecialchars($hornitzailea['posta_kodea']) ?>"
                                    class="inprimaki-sarrera" required>
                            </div>
                            <div class="inprimaki-taldea">
                                <label>Telefonoa:</label>
                                <input type="text" name="telefonoa"
                                    value="<?= htmlspecialchars($hornitzailea['telefonoa']) ?>"
                                    class="inprimaki-sarrera" required>
                            </div>
                            <div class="inprimaki-taldea zutabe-osoa">
                                <label>Emaila:</label>
                                <input type="email" name="emaila"
                                    value="<?= htmlspecialchars($hornitzailea['emaila']) ?>" class="inprimaki-sarrera"
                                    required>
                            </div>

                            <h3 class="inprimaki-atal-izenburua">Segurtasuna</h3>

                            <div class="inprimaki-taldea zutabe-osoa">
                                <label>Pasahitza Berria (Utzi hutsik ez aldatzeko):</label>
                                <input type="password" name="pasahitza" class="inprimaki-sarrera"
                                    placeholder="Pasahitza berria...">
                            </div>
                        </div>

                        <button type="submit" class="botoia botoi-nagusia datu-aldaketa-botoia">Gorde Aldaketak</button>
                    </form>

                    <div class="atzera-botoi-kontainer">
                        <a href="hornitzaile_menua.php" class="atzerako-botoia"><i class="fas fa-arrow-left"></i> Atzera
                            Menura</a>
                    </div>
                </div>

                <div class="sozial-kutxa">
                    <img src="../irudiak/birtek_konponketak.png" alt="Repairing" class="kontaktu-irudia" />
                    <h3 class="sozial-azpititulua">Laguntza behar duzu?</h3>
                    <p class="testua-grisa tartea-behean-1-5">Zure datuak aldatzeko arazorik baduzu, jarri gurekin
                        harremanetan erraz:</p>
                    <a href="https://wa.me/34600000000" target="_blank" class="footer-wa-botoia"><i
                            class="fab fa-whatsapp"></i> WhatsApp Laguntza</a>
                </div>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
    <script src="../js/hornitzaile_saioa_hasi.js"></script>
</body>

</html>
``n
### php/hornitzaile_menua.php
``php
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
    <link rel="stylesheet" href="../css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="../css/estiloak_globala.css">
    <link rel="stylesheet" href="../css/estiloak_hornitzaile_menua.css">
</head>
<body class="web-gorputza">
    <?php include 'goiburua.php'; ?>

    <main class="eduki-nagusia">
        <h2 class="ongi-etorri-mezua">Ongi etorri, <?= htmlspecialchars($izena) ?>!</h2>
        
        <div class="menu-hornitzailea">
            <!-- 1. Aukera: Datuak Aldatu -->
            <a href="hornitzaile_datuak_aldatu.php" class="menu-txartela">
                <i class="fas fa-user-edit menu-ikonoa"></i>
                <span class="menu-izenburua">Datu Pertsonalak Aldatu</span>
            </a>

            <!-- 2. Aukera: Sarrera Egin (Produktuak Bidali) -->
            <a href="hornitzaile_sarrera_egin.php" class="menu-txartela">
                <i class="fas fa-truck-loading menu-ikonoa"></i>
                <span class="menu-izenburua">Sarrera Egin</span>
            </a>

            <!-- 3. Aukera: Sarrerak Kudeatu -->
            <a href="hornitzaile_sarrerak_kudeatu.php" class="menu-txartela">
                <i class="fas fa-clipboard-list menu-ikonoa"></i>
                <span class="menu-izenburua">Sarrerak Kudeatu</span>
            </a>
        </div>

        <button class="saioa-itxi-botoia botoi-gorria" id="logout-botoia-menua">
            <i class="fas fa-sign-out-alt"></i> Saioa Itxi
        </button>
    </main>

    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
</body>
</html>

``n
### php/hornitzaile_saioa_hasi.php
``php
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
  <link rel="stylesheet" href="../css/fontawesome/css/all.min.css" />
  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" />

  <!-- gure css artxiboak -->
  <link rel="stylesheet" href="../css/estiloak_globala.css" />
  <link rel="stylesheet" href="../css/estiloak_kontaktua.css" />
</head>

<body class="web-gorputza">
  <?php include_once 'goiburua.php'; ?>

  <main class="eduki-nagusia">
    <section class="kontaktu-edukiontzia">
      <h2 class="kontaktua-titulua">Hornitzaileen Gunea</h2>

      <div class="kontaktu-sareta">
        <!-- SAIOA HASI -->
        <div class="inprimaki-kutxa">
          <h3 class="inprimaki-titulua">Saioa Hasi</h3>
          <p class="testua-grisa">Dagoeneko hornitzailea zara? Sartu hemen:</p>

          <?php if (isset($_GET['error'])): ?>
            <div class="login-errore-mezua">
              <i class="fas fa-exclamation-circle"></i> Posta elektronikoa edo pasahitza okerrak dira.
            </div>
          <?php endif; ?>
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
          <form id="hornitzaile-erregistro-form" class="kontaktu-inprimaki-diseinua"
            action="erregistratu_hornitzailea.php" method="POST">
            <div>
              <input type="text" name="izena" placeholder="Enpresaren Izena / Izena" class="inprimaki-sarrera"
                required />
            </div>
            <div>
              <input type="email" name="emaila_erregistroa" placeholder="Posta elektronikoa" class="inprimaki-sarrera"
                required />
            </div>
            <div>
              <input type="text" name="nan" placeholder="NAN / IFZ (9 karaktere)" class="inprimaki-sarrera" required
                maxlength="9" minlength="9" />
            </div>
            <div>
              <input type="text" name="helbidea" placeholder="Helbidea" class="inprimaki-sarrera" />
            </div>
            <div>
              <input type="text" name="herria_izena" list="herriak_zerrenda" placeholder="Herria"
                class="inprimaki-sarrera" required />
              <datalist id="herriak_zerrenda">
                <?php foreach ($herriak as $herria): ?>
                  <option value="<?= htmlspecialchars($herria['izena']) ?>"></option>
                <?php endforeach; ?>
              </datalist>
            </div>
            <div>
              <input type="text" name="lurraldea" placeholder="Lurraldea (Probintzia) - Beharrezkoa berria bada"
                class="inprimaki-sarrera" />
            </div>
            <div>
              <input type="text" name="nazioa" placeholder="Nazioa - Beharrezkoa berria bada"
                class="inprimaki-sarrera" />
            </div>
            <div>
              <input type="text" name="posta_kodea" placeholder="Posta Kodea" class="inprimaki-sarrera" required
                pattern="[0-9]{5}" title="5 digituko posta kodea" />
            </div>
            <div>
              <input type="password" name="pasahitza_erregistroa" placeholder="Pasahitza segurua"
                class="inprimaki-sarrera" required minlength="8" />
              <p>Gutxienez 8 karaktere.</p>
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
  <script src="../js/hornitzaile_saioa_hasi.js"></script>
  <script>
    $(document).on("session:valid", function (e, user, type) {
      var menuUrl = (type === 'hornitzailea') ? 'hornitzaile_menua.php' : 'bezero_menua.php';
      $(".kontaktu-sareta").html(
        '<div class="testua-zentratuta ongietorri-kutxa-osagarria">' +
        '<h2 class="tartea-behean-1">Ongi etorri berriro, ' + user + "!</h2>" +
        '<p class="tartea-behean-1-5">Dagoeneko saioa hasita daukazu.</p>' +
        '<a href="' + menuUrl + '" class="botoia botoi-nagusia">Joan Nire Menura</a>' +
        '</div>'
      );
    });
  </script>
</body>

</html>
``n
### php/hornitzaile_sarrera_egin.php
``php
<?php
session_start();
require_once 'DB_konexioa.php';

if (!isset($_SESSION['id_hornitzailea'])) {
    header("Location: hornitzaile_saioa_hasi.php");
    exit();
}

$id_hornitzailea = $_SESSION['id_hornitzailea'];
$izena_soziala = $_SESSION['izena_soziala'] ?? 'Hornitzailea';
$mezua = "";

// Inprimakiaren bidalketa kudeatu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mota_sarrera = $_POST['mota_sarrera']; // 'existing' (lehendik dagoena) edo 'new' (berria)
    $kantitatea = $_POST['kantitatea'];

    try {
        $konexioa->beginTransaction();
        $produktua_id = null;

        if ($mota_sarrera == 'existing') {
            $produktua_id = $_POST['produktua_id'];
        } else {
            // Produktu sarrera berria
            $izena = trim($_POST['izena']);
            $marka = trim($_POST['marka']);
            $mota = $_POST['mota']; // ENUM balioa
            $deskribapena = trim($_POST['deskribapena']);
            $stock = 0; // 0 lehenetsia erabiltzaileak eskatu bezala (artikuluak 'Bidean' datoz)
            $prezioa = 0.00; // Sarrera kenduta, 0 lehenetsia

            // Kategoria ID-a zehaztu
            // 1: Ordenagailuak (Eramangarria, Mahai-gainekoa)
            // 2: Telefonia (Mugikorra, Tableta)
            // 3: Irudia (Pantaila)
            // 4: Osagarriak (Periferikoa, Kablea) -> Oharra: 'Kablea' hautapenean, 'kableak' taulan
            // 5: Softwarea (Softwarea)
            // 6: Sareak eta Zerbitzariak (Zerbitzaria)

            $kategoria_id = 1; // Lehenetsia
            switch ($mota) {
                case 'Eramangarria':
                case 'Mahai-gainekoa':
                    $kategoria_id = 1;
                    break;
                case 'Mugikorra':
                case 'Tableta':
                    $kategoria_id = 2;
                    break;
                case 'Pantaila':
                    $kategoria_id = 3;
                    break;
                case 'Periferikoa':
                case 'Kablea':
                    $kategoria_id = 4;
                    break;
                case 'Softwarea':
                    $kategoria_id = 5;
                    break;
                case 'Zerbitzaria':
                    $kategoria_id = 6;
                    break;
            }

            // Taula nagusian txertatu
            // DBko 'mota' ENUM-a eguneratu beharko litzateke 'Kablea' edo 'Periferikoa' zehaztasunez ez badago
            // DB ENUM: 'Generikoa','Eramangarria','Mahai-gainekoa','Mugikorra','Tableta','Zerbitzaria','Pantaila','Softwarea','Periferikoak','Kableak'
            // Nire hautapen balioak: 'Periferikoa', 'Kablea'. DB ENUM-era mapatu behar dira.
            $db_mota = $mota;
            if ($mota == 'Periferikoa')
                $db_mota = 'Periferikoak';
            if ($mota == 'Kablea')
                $db_mota = 'Kableak';

            $stmtProd = $konexioa->prepare("INSERT INTO produktuak (izena, marka, mota, deskribapena, salmenta_prezioa, stock, hornitzaile_id, kategoria_id, aktibo, salgai) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, 0)");
            $stmtProd->execute([$izena, $marka, $db_mota, $deskribapena, $prezioa, $stock, $id_hornitzailea, $kategoria_id]);
            $produktua_id = $konexioa->lastInsertId();

            // Azpi-tauletan txertatu
            switch ($mota) {
                case 'Eramangarria':
                    $stmtSub = $konexioa->prepare("INSERT INTO eramangarriak (id_produktua, prozesadorea, ram_gb, diskoa_gb, pantaila_tamaina, bateria_wh, sistema_eragilea, pisua_kg) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmtSub->execute([
                        $produktua_id,
                        $_POST['eram_prozesadorea'],
                        $_POST['eram_ram_gb'],
                        $_POST['eram_diskoa_gb'],
                        $_POST['eram_pantaila_tamaina'],
                        $_POST['eram_bateria_wh'],
                        $_POST['eram_sistema_eragilea'],
                        $_POST['eram_pisua_kg']
                    ]);
                    break;
                case 'Mahai-gainekoa':
                    $stmtSub = $konexioa->prepare("INSERT INTO mahai_gainekoak (id_produktua, prozesadorea, plaka_basea, ram_gb, diskoa_gb, txartel_grafikoa, elikatze_iturria_w, kaxa_formatua) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmtSub->execute([
                        $produktua_id,
                        $_POST['mahai_prozesadorea'],
                        $_POST['mahai_plaka_basea'],
                        $_POST['mahai_ram_gb'],
                        $_POST['mahai_diskoa_gb'],
                        $_POST['mahai_txartel_grafikoa'],
                        $_POST['mahai_elikatze_iturria_w'],
                        $_POST['mahai_kaxa_formatua']
                    ]);
                    break;
                case 'Mugikorra':
                    $stmtSub = $konexioa->prepare("INSERT INTO mugikorrak (id_produktua, pantaila_teknologia, pantaila_hazbeteak, biltegiratzea_gb, ram_gb, kamera_nagusa_mp, bateria_mah, sistema_eragilea, sareak) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmtSub->execute([
                        $produktua_id,
                        $_POST['mug_pantaila_teknologia'],
                        $_POST['mug_pantaila_hazbeteak'],
                        $_POST['mug_biltegiratzea_gb'],
                        $_POST['mug_ram_gb'],
                        $_POST['mug_kamera_nagusa_mp'],
                        $_POST['mug_bateria_mah'],
                        $_POST['mug_sistema_eragilea'],
                        $_POST['mug_sareak']
                    ]);
                    break;
                case 'Tableta':
                    $stmtSub = $konexioa->prepare("INSERT INTO tabletak (id_produktua, pantaila_hazbeteak, biltegiratzea_gb, konektibitatea, sistema_eragilea, bateria_mah, arkatzarekin_bateragarria) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmtSub->execute([
                        $produktua_id,
                        $_POST['tab_pantaila_hazbeteak'],
                        $_POST['tab_biltegiratzea_gb'],
                        $_POST['tab_konektibitatea'],
                        $_POST['tab_sistema_eragilea'],
                        $_POST['tab_bateria_mah'],
                        isset($_POST['tab_arkatzarekin']) ? 1 : 0
                    ]);
                    break;
                case 'Zerbitzaria':
                    $stmtSub = $konexioa->prepare("INSERT INTO zerbitzariak (id_produktua, prozesadore_nukleoak, ram_mota, disko_badiak, rack_unitateak, elikatze_iturri_erredundantea, raid_kontroladora) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmtSub->execute([
                        $produktua_id,
                        $_POST['zerb_prozesadore_nukleoak'],
                        $_POST['zerb_ram_mota'],
                        $_POST['zerb_disko_badiak'],
                        $_POST['zerb_rack_unitateak'],
                        isset($_POST['zerb_elikatze_erredundantea']) ? 1 : 0,
                        $_POST['zerb_raid_kontroladora']
                    ]);
                    break;
                case 'Pantaila':
                    $stmtSub = $konexioa->prepare("INSERT INTO pantailak (id_produktua, hazbeteak, bereizmena, panel_mota, freskatze_tasa_hz, konexioak, kurbatura) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    $stmtSub->execute([
                        $produktua_id,
                        $_POST['pan_hazbeteak'],
                        $_POST['pan_bereizmena'],
                        $_POST['pan_panel_mota'],
                        $_POST['pan_freskatze_tasa_hz'],
                        $_POST['pan_konexioak'],
                        $_POST['pan_kurbatura']
                    ]);
                    break;
                case 'Softwarea':
                    $stmtSub = $konexioa->prepare("INSERT INTO softwareak (id_produktua, software_mota, lizentzia_mota, bertsioa, garatzailea) VALUES (?, ?, ?, ?, ?)");
                    $stmtSub->execute([
                        $produktua_id,
                        $_POST['soft_mota'],
                        $_POST['soft_lizentzia'],
                        $_POST['soft_bertsioa'],
                        $_POST['soft_garatzailea']
                    ]);
                    break;
                case 'Periferikoa':
                    $stmtSub = $konexioa->prepare("INSERT INTO periferikoak (id_produktua, periferiko_mota, konexioa, ezaugarriak, argiztapena) VALUES (?, ?, ?, ?, ?)");
                    $stmtSub->execute([
                        $produktua_id,
                        $_POST['peri_mota'],
                        $_POST['peri_konexioa'],
                        $_POST['peri_ezaugarriak'],
                        isset($_POST['peri_argiztapena']) ? 1 : 0
                    ]);
                    break;
                case 'Kablea':
                    $stmtSub = $konexioa->prepare("INSERT INTO kableak (id_produktua, kable_mota, luzera_m, konektore_a, konektore_b, bertsioa) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmtSub->execute([
                        $produktua_id,
                        $_POST['kab_mota'],
                        $_POST['kab_luzera_m'],
                        $_POST['kab_konektore_a'],
                        $_POST['kab_konektore_b'],
                        $_POST['kab_bertsioa']
                    ]);
                    break;
            }
        }

        if ($produktua_id && $kantitatea > 0) {
            // 1. Sarreretan txertatu
            $stmt = $konexioa->prepare("INSERT INTO sarrerak (hornitzailea_id, langilea_id, sarrera_egoera, data) VALUES (:hid, 1, 'Bidean', NOW())");
            $stmt->execute([':hid' => $id_hornitzailea]);
            $sarrera_id = $konexioa->lastInsertId();

            // 2. Sarrera lerroetan txertatu
            $stmtLine = $konexioa->prepare("INSERT INTO sarrera_lerroak (sarrera_id, produktua_id, kantitatea, sarrera_lerro_egoera) VALUES (:sid, :pid, :qty, 'Bidean')");
            $stmtLine->execute([
                ':sid' => $sarrera_id,
                ':pid' => $produktua_id,
                ':qty' => $kantitatea
            ]);

            $konexioa->commit();
            $mezua = "Sarrera ondo erregistratu da! Produktua bidean dago.";
        } else {
            throw new Exception("Datu guztiak beharrezkoak dira.");
        }
    } catch (Exception $e) {
        if ($konexioa->inTransaction())
            $konexioa->rollBack();
        $mezua = "Errorea sarrera egitean: " . $e->getMessage();
    }
}

// Hornitzailearen produktuak lortu dropdown-erako
$produktuak = [];
try {
    $stmt = $konexioa->prepare("SELECT id_produktua, izena, marka FROM produktuak WHERE hornitzaile_id = :hid ORDER BY izena");
    $stmt->execute([':hid' => $id_hornitzailea]);
    $produktuak = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
}

?>
<!DOCTYPE html>
<html lang="eu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarrera Egin - BIRTEK</title>
    <link rel="stylesheet" href="../css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../css/estiloak_globala.css">
    <link rel="stylesheet" href="../css/estiloak_kontaktua.css">
    <link rel="stylesheet" href="../css/estiloak_hornitzaile_menua.css">
</head>

<body class="web-gorputza">
    <?php include_once 'goiburua.php'; ?>

    <main class="eduki-nagusia">
        <div class="kontaktu-edukiontzia">
            <h2 class="kontaktua-titulua">Sarrera Berria Erregistratu</h2>

            <div class="inprimaki-trukatu">
                <button type="button" class="trukatu-botoia aktibo" id="btn-existing">Lehendik dagoena</button>
                <button type="button" class="trukatu-botoia" id="btn-new">Produktu Berria</button>
            </div>

            <div class="inprimaki-kutxa sarrera-edukiontzia">
                <?php if ($mezua): ?>
                    <p class="mezu-kutxa <?= strpos($mezua, 'Errorea') !== false ? 'mezu-errorea' : 'mezu-arrakasta' ?>">
                        <?= $mezua ?>
                    </p>
                <?php endif; ?>

                <form class="kontaktu-inprimaki-diseinua" method="POST" id="main-form">
                    <input type="hidden" name="mota_sarrera" id="mota_sarrera" value="existing">

                    <div id="section-existing">
                        <label class="label-input-fitxategia">Aukeratu Produktua:</label>
                        <select name="produktua_id" class="produktu-hautatzailea">
                            <option value="">-- Aukeratu --</option>
                            <?php foreach ($produktuak as $prod): ?>
                                <option value="<?= $prod['id_produktua'] ?>">
                                    <?= htmlspecialchars($prod['izena']) ?> (<?= htmlspecialchars($prod['marka']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div id="section-new" class="ezkutuan">
                        <div class="sarrera-sareta-berria">
                            <input type="text" name="izena" class="inprimaki-sarrera" placeholder="Produktuaren Izena"
                                required>
                            <input type="text" name="marka" class="inprimaki-sarrera" placeholder="Marka" required>
                            <select name="mota" id="produktu_mota_select" class="inprimaki-sarrera" required
                                onchange="toggleFormFields()">
                                <option value="" disabled selected>Aukeratu Mota</option>
                                <option value="Eramangarria">Eramangarria</option>
                                <option value="Mahai-gainekoa">Mahai-gainekoa</option>
                                <option value="Mugikorra">Mugikorra</option>
                                <option value="Tableta">Tableta</option>
                                <option value="Zerbitzaria">Zerbitzaria</option>
                                <option value="Pantaila">Pantaila</option>
                                <option value="Softwarea">Softwarea</option>
                                <option value="Periferikoa">Periferikoa</option>
                                <option value="Kablea">Kablea</option>
                            </select>
                            <!-- Stock sarrera kendu da erabiltzaileak eskatu bezala -->
                        </div>
                        <textarea name="deskribapena" class="inprimaki-sarrera" placeholder="Deskribapena"
                            rows="3"></textarea>

                        <!-- Eremu Dinamikoen Edukiontziak -->
                        <div id="fields_eramangarria" class="dynamic-fields ezkutuan">
                            <h4>Eramangarria Ezaugarriak</h4>
                            <div class="sarrera-sareta-berria">
                                <input type="text" name="eram_prozesadorea" placeholder="Prozesadorea (Adib: i7-1360P)">
                                <input type="number" name="eram_ram_gb" placeholder="RAM (GB)">
                                <input type="number" name="eram_diskoa_gb" placeholder="Diskoa (GB)">
                                <input type="number" step="0.1" name="eram_pantaila_tamaina"
                                    placeholder="Pantaila (hazbeteak)">
                                <input type="number" name="eram_bateria_wh" placeholder="Bateria (Wh)">
                                <input type="text" name="eram_sistema_eragilea" placeholder="Sistema Eragilea">
                                <input type="number" step="0.01" name="eram_pisua_kg" placeholder="Pisua (Kg)">
                            </div>
                        </div>

                        <div id="fields_mahaigainekoa" class="dynamic-fields ezkutuan">
                            <h4>Mahai-gainekoa Ezaugarriak</h4>
                            <div class="sarrera-sareta-berria">
                                <input type="text" name="mahai_prozesadorea" placeholder="Prozesadorea">
                                <input type="text" name="mahai_plaka_basea" placeholder="Plaka Basea">
                                <input type="number" name="mahai_ram_gb" placeholder="RAM (GB)">
                                <input type="number" name="mahai_diskoa_gb" placeholder="Diskoa (GB)">
                                <input type="text" name="mahai_txartel_grafikoa" placeholder="Txartel Grafikoa">
                                <input type="number" name="mahai_elikatze_iturria_w" placeholder="Elikatze Iturria (W)">
                                <select name="mahai_kaxa_formatua" class="inprimaki-sarrera">
                                    <option value="ATX">ATX</option>
                                    <option value="Micro-ATX">Micro-ATX</option>
                                    <option value="Mini-ITX">Mini-ITX</option>
                                    <option value="E-ATX">E-ATX</option>
                                </select>
                            </div>
                        </div>

                        <div id="fields_mugikorra" class="dynamic-fields ezkutuan">
                            <h4>Mugikorra Ezaugarriak</h4>
                            <div class="sarrera-sareta-berria">
                                <input type="text" name="mug_pantaila_teknologia"
                                    placeholder="Pantaila Teknologia (OLED...)">
                                <input type="number" step="0.1" name="mug_pantaila_hazbeteak"
                                    placeholder="Pantaila (hazbeteak)">
                                <input type="number" name="mug_biltegiratzea_gb" placeholder="Biltegiratzea (GB)">
                                <input type="number" name="mug_ram_gb" placeholder="RAM (GB)">
                                <input type="number" name="mug_kamera_nagusa_mp" placeholder="Kamera (MP)">
                                <input type="number" name="mug_bateria_mah" placeholder="Bateria (mAh)">
                                <input type="text" name="mug_sistema_eragilea" placeholder="OS (Android/iOS)">
                                <select name="mug_sareak" class="inprimaki-sarrera">
                                    <option value="5G">5G</option>
                                    <option value="4G">4G</option>
                                </select>
                            </div>
                        </div>

                        <div id="fields_tableta" class="dynamic-fields ezkutuan">
                            <h4>Tableta Ezaugarriak</h4>
                            <div class="sarrera-sareta-berria">
                                <input type="number" step="0.1" name="tab_pantaila_hazbeteak"
                                    placeholder="Pantaila (hazbeteak)">
                                <input type="number" name="tab_biltegiratzea_gb" placeholder="Biltegiratzea (GB)">
                                <select name="tab_konektibitatea" class="inprimaki-sarrera">
                                    <option value="WiFi">WiFi</option>
                                    <option value="WiFi + Cellular">WiFi + Cellular</option>
                                </select>
                                <input type="text" name="tab_sistema_eragilea" placeholder="OS">
                                <input type="number" name="tab_bateria_mah" placeholder="Bateria (mAh)">
                                <label class="label-flex">
                                    <input type="checkbox" name="tab_arkatzarekin"> Arkatzarekin bateragarria
                                </label>
                            </div>
                        </div>

                        <div id="fields_zerbitzaria" class="dynamic-fields ezkutuan">
                            <h4>Zerbitzaria Ezaugarriak</h4>
                            <div class="sarrera-sareta-berria">
                                <input type="number" name="zerb_prozesadore_nukleoak" placeholder="CPU Nukleoak">
                                <select name="zerb_ram_mota" class="inprimaki-sarrera">
                                    <option value="DDR4">DDR4</option>
                                    <option value="DDR5">DDR5</option>
                                    <option value="ECC">ECC</option>
                                </select>
                                <input type="number" name="zerb_disko_badiak" placeholder="Disko Badiak">
                                <input type="number" name="zerb_rack_unitateak" placeholder="Rack Unitateak (U)">
                                <input type="text" name="zerb_raid_kontroladora" placeholder="RAID Kontroladora">
                                <label class="label-flex">
                                    <input type="checkbox" name="zerb_elikatze_erredundantea" checked> Elikatze Iturri
                                    Erredundantea
                                </label>
                            </div>
                        </div>

                        <div id="fields_pantaila" class="dynamic-fields ezkutuan">
                            <h4>Pantaila Ezaugarriak</h4>
                            <div class="sarrera-sareta-berria">
                                <input type="number" step="0.1" name="pan_hazbeteak" placeholder="Hazbeteak">
                                <input type="text" name="pan_bereizmena" placeholder="Bereizmena (1920x1080)">
                                <select name="pan_panel_mota" class="inprimaki-sarrera">
                                    <option value="IPS">IPS</option>
                                    <option value="VA">VA</option>
                                    <option value="TN">TN</option>
                                    <option value="OLED">OLED</option>
                                </select>
                                <input type="number" name="pan_freskatze_tasa_hz" placeholder="Hz (60, 144...)">
                                <input type="text" name="pan_konexioak" placeholder="Konexioak (HDMI, DP...)">
                                <input type="text" name="pan_kurbatura" placeholder="Kurbatura (Flat, 1500R)">
                            </div>
                        </div>

                        <div id="fields_softwarea" class="dynamic-fields ezkutuan">
                            <h4>Softwarea Ezaugarriak</h4>
                            <div class="sarrera-sareta-berria">
                                <select name="soft_mota" class="inprimaki-sarrera">
                                    <option value="Sistema Eragilea">Sistema Eragilea</option>
                                    <option value="Ofimatika">Ofimatika</option>
                                    <option value="Antibirusa">Antibirusa</option>
                                    <option value="Bestelakoak">Bestelakoak</option>
                                </select>
                                <select name="soft_lizentzia" class="inprimaki-sarrera">
                                    <option value="Retail">Retail</option>
                                    <option value="OEM">OEM</option>
                                    <option value="Harpidetza">Harpidetza</option>
                                    <option value="OpenSource">OpenSource</option>
                                </select>
                                <input type="text" name="soft_bertsioa" placeholder="Bertsioa">
                                <input type="text" name="soft_garatzailea" placeholder="Garatzailea">
                            </div>
                        </div>

                        <div id="fields_periferikoa" class="dynamic-fields ezkutuan">
                            <h4>Periferikoa Ezaugarriak</h4>
                            <div class="sarrera-sareta-berria">
                                <select name="peri_mota" class="inprimaki-sarrera">
                                    <option value="Teklatua">Teklatua</option>
                                    <option value="Sagua">Sagua</option>
                                    <option value="Aurikularrak">Aurikularrak</option>
                                    <option value="Bozgorailuak">Bozgorailuak</option>
                                    <option value="Webkamera">Webkamera</option>
                                    <option value="Inprimagailua">Inprimagailua</option>
                                    <option value="Eskanerra">Eskanerra</option>
                                    <option value="Bestelakoak">Bestelakoak</option>
                                </select>
                                <input type="text" name="peri_konexioa" placeholder="Konexioa (USB, Bluetooth...)">
                                <textarea name="peri_ezaugarriak" class="inprimaki-sarrera"
                                    placeholder="Ezaugarriak (DPI, Mekanikoa...)" rows="1"></textarea>
                                <label class="label-flex">
                                    <input type="checkbox" name="peri_argiztapena"> Argiztapena
                                </label>
                            </div>
                        </div>

                        <div id="fields_kablea" class="dynamic-fields ezkutuan">
                            <h4>Kablea Ezaugarriak</h4>
                            <div class="sarrera-sareta-berria">
                                <select name="kab_mota" class="inprimaki-sarrera">
                                    <option value="Bideoa">Bideoa</option>
                                    <option value="Datuak">Datuak</option>
                                    <option value="Sarea">Sarea</option>
                                    <option value="Audioa">Audioa</option>
                                    <option value="Korrontea">Korrontea</option>
                                    <option value="Egokitzailea">Egokitzailea</option>
                                    <option value="Barnekoak">Barnekoak</option>
                                    <option value="Bestelakoak">Bestelakoak</option>
                                </select>
                                <input type="number" step="0.01" name="kab_luzera_m" placeholder="Luzera (m)">
                                <input type="text" name="kab_konektore_a" placeholder="Konektore A">
                                <input type="text" name="kab_konektore_b" placeholder="Konektore B">
                                <input type="text" name="kab_bertsioa" placeholder="Bertsioa (HDMI 2.1...)">
                            </div>
                        </div>
                    </div>

                    <div class="sarrera-kopuru-edukiontzia">
                        <label class="label-input-fitxategia">Kantitatea (Bidalketa):</label>
                        <input type="number" name="kantitatea" min="1" class="inprimaki-sarrera" required>
                    </div>

                    <button type="submit" class="botoia botoi-nagusia inprimaki-bidali-botoia">Bidali
                        Produktuak</button>
                </form>

                <div class="atzera-esteka-edukiontzia">
                    <a href="hornitzaile_menua.php" class="atzera-esteka-estiloa"><i class="fas fa-arrow-left"></i>
                        Atzera Menura</a>
                </div>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
    <script>
        $(document).ready(function () {
            $('#btn-existing').click(function () {
                $('.trukatu-botoia').removeClass('aktibo');
                $(this).addClass('aktibo');
                $('#section-new').addClass('ezkutuan');
                $('#section-existing').removeClass('ezkutuan');
                $('#mota_sarrera').val('existing');
            });
            $('#btn-new').click(function () {
                $('.trukatu-botoia').removeClass('aktibo');
                $(this).addClass('aktibo');
                $('#section-existing').addClass('ezkutuan');
                $('#section-new').removeClass('ezkutuan');
                $('#mota_sarrera').val('new');
            });
        });

        function toggleFormFields() {
            // Dinamikoki erakutsitako eremu guztiak ezkutatu
            $('.dynamic-fields').addClass('ezkutuan');

            var mota = $('#produktu_mota_select').val();
            // 'mota' ID-ra mapatu (Katea normalizatu: espazioak kendu, minuskulara)
            // Baina switch/if mapa erabili dezakegu balioak ezagunak direnez
            var targetId = "";
            if (mota === "Eramangarria") targetId = "fields_eramangarria";
            else if (mota === "Mahai-gainekoa") targetId = "fields_mahaigainekoa";
            else if (mota === "Mugikorra") targetId = "fields_mugikorra";
            else if (mota === "Tableta") targetId = "fields_tableta";
            else if (mota === "Zerbitzaria") targetId = "fields_zerbitzaria";
            else if (mota === "Pantaila") targetId = "fields_pantaila";
            else if (mota === "Softwarea") targetId = "fields_softwarea";
            else if (mota === "Periferikoa") targetId = "fields_periferikoa";
            else if (mota === "Kablea") targetId = "fields_kablea";

            if (targetId) {
                $('#' + targetId).removeClass('ezkutuan');
            }
        }
    </script>
</body>

</html>
``n
### php/hornitzaile_sarrerak_kudeatu.php
``php
<?php
session_start();
require_once 'DB_konexioa.php';

if (!isset($_SESSION['id_hornitzailea'])) {
    header("Location: hornitzaile_saioa_hasi.php");
    exit();
}

$id_hornitzailea = $_SESSION['id_hornitzailea'];
$mezua = "";

// 1. EZABATZE LOGIKA
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'delete_sarrera_lerroa') {
    try {
        $id_lerroa = $_POST['id_sarrera_lerroa'];
        
        // Egiaztatu lerroa hornitzailearena den eta "Bidean" dagoen
        $stmt_check = $konexioa->prepare("
            SELECT sl.id_sarrera_lerroa 
            FROM sarrera_lerroak sl
            JOIN sarrerak s ON sl.sarrera_id = s.id_sarrera
            WHERE sl.id_sarrera_lerroa = ? AND s.hornitzailea_id = ? AND sl.sarrera_lerro_egoera = 'Bidean'
        ");
        $stmt_check->execute([$id_lerroa, $id_hornitzailea]);
        
        if ($stmt_check->fetch()) {
            $stmt_del = $konexioa->prepare("DELETE FROM sarrera_lerroak WHERE id_sarrera_lerroa = ?");
            $stmt_del->execute([$id_lerroa]);
            $mezua = "Sarrera lerroa ondo ezabatu da.";
        } else {
            $mezua = "Ezin da lerro hau ezabatu (baliteke jada 'Jasota' izatea).";
        }
    } catch (PDOException $e) {
        $mezua = "Errorea ezabatzean: " . $e->getMessage();
    }
}

// Fetch shipments (sarrerak + lerroak)
// Joining sarrerak, sarrera_lerroak, and produktuak
$sql = "
    SELECT 
        s.id_sarrera, 
        s.data, 
        s.sarrera_egoera,
        sl.id_sarrera_lerroa,
        sl.kantitatea,
        sl.sarrera_lerro_egoera,
        p.id_produktua,
        p.izena as produktu_izena,
        p.marka
    FROM sarrerak s
    JOIN sarrera_lerroak sl ON s.id_sarrera = sl.sarrera_id
    JOIN produktuak p ON sl.produktua_id = p.id_produktua
    WHERE s.hornitzailea_id = :hid
    ORDER BY s.data DESC
";

$stmt = $konexioa->prepare($sql);
$stmt->execute([':hid' => $id_hornitzailea]);
$sarrerak = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarrerak Kudeatu - BIRTEK</title>
    <link rel="stylesheet" href="../css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../css/estiloak_globala.css">
    <link rel="stylesheet" href="../css/estiloak_bezero_eskaerak.css"> 
    <link rel="stylesheet" href="../css/estiloak_hornitzaile_menua.css">
</head>
<body class="web-gorputza">
    <?php include_once 'goiburua.php'; ?>

    <main class="eduki-nagusia">
        <div class="eskari-edukiontzia"> 
            <a href="hornitzaile_menua.php" class="atzera-botoia"><i class="fas fa-arrow-left"></i> Atzera</a>
            <h2>Nire Sarrerak (Bidalketak)</h2>

            <?php if ($mezua): ?>
                <div class="alert-berdea">
                    <?= htmlspecialchars($mezua) ?>
                </div>
            <?php endif; ?>

            <?php if (count($sarrerak) > 0): ?>
                <table class="sarrera-taula">
                    <thead>
                        <tr class="sarrera-buru-tr">
                            <th class="sarrera-th">Data</th>
                            <th class="sarrera-th">Produktua</th>
                            <th class="sarrera-th-zentratua">Kantitatea</th>
                            <th class="sarrera-th-zentratua">Bidalketa Egoera</th>
                            <th class="sarrera-th-zentratua">Ekintzak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sarrerak as $sarrera): ?>
                            <tr class="sarrera-gorputz-tr">
                                <td class="sarrera-td"><?= date('Y-m-d H:i', strtotime($sarrera['data'])) ?></td>
                                <td class="sarrera-td">
                                    <?php if ($sarrera['sarrera_lerro_egoera'] == 'Jasota'): ?>
                                        <a href="produktua_xehetasunak.php?id=<?= $sarrera['id_produktua'] ?>" class="produktu-esteka" target="_blank">
                                            <?= htmlspecialchars($sarrera['produktu_izena']) ?>
                                        </a>
                                    <?php else: ?>
                                        <?= htmlspecialchars($sarrera['produktu_izena']) ?>
                                    <?php endif; ?>
                                </td>
                                <td class="sarrera-td-zentratua"><?= $sarrera['kantitatea'] ?></td>
                                <td class="sarrera-td-zentratua">
                                    <span class="egoera-<?= $sarrera['sarrera_lerro_egoera'] ?>">
                                        <?= $sarrera['sarrera_lerro_egoera'] ?>
                                    </span>
                                </td>
                                <td class="sarrera-td-zentratua">
                                    <?php if ($sarrera['sarrera_lerro_egoera'] === 'Bidean'): ?>
                                        <form method="POST" class="inprimaki-inline form-baieztatu" data-mezua="Ziur zaude sarrera hau ezabatu nahi duzula?">
                                            <input type="hidden" name="action" value="delete_sarrera_lerroa">
                                            <input type="hidden" name="id_sarrera_lerroa" value="<?= $sarrera['id_sarrera_lerroa'] ?>">
                                            <button type="submit" class="ezabatu-lerroa-botoia" title="Ezabatu sarrera">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="testu-apala">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Ez daukazu sarrerarik erregistratuta.</p>
            <?php endif; ?>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
</body>
</html>

``n
### php/java_app_abiarazi.php
``php
<?php
// c:\xampp\htdocs\2ERR_1TALDEA_BIRTEK\php\launch_java_app.php

// Exekutablearen bide absolutua definitu
$exePath = "C:\\birtek_app_exekutablea\\BirtekAPP.exe";
$workingDir = "C:\\birtek_app_exekutablea";

// Komandoa eraiki
// cd /d unitatea eta direktorioa aldatzeko
// start "" izenburu huts batekin abiarazteko (start komandoaren sintaxirako beharrezkoa)

$command = "cd /d \"$workingDir\" && start \"\" \"$exePath\"";

// Execute the command
pclose(popen($command, "r"));

echo "Abiarazten: " . $command;
?>


``n
### php/kontaktua.php
``php
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="eu">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BIRTEK - Kontaktua</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="../css/fontawesome/css/all.min.css" />
  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" />

  <!-- gure css artxiboak -->
  <link rel="stylesheet" href="../css/estiloak_globala.css" />
  <link rel="stylesheet" href="../css/estiloak_kontaktua.css" />
</head>

<body class="web-gorputza">
  <?php include 'goiburua.php'; ?>

  <main class="eduki-nagusia">
    <section>
      <div class="kontaktu-edukiontzia">
        <h2 class="kontaktua-titulua">Jarri Gurekin Harremanetan</h2>
        <div class="kontaktu-sareta">
          <div class="inprimaki-kutxa">
            <h3 class="inprimaki-titulua">Bidali mezu bat</h3>
            <form class="kontaktu-inprimaki-diseinua">
              <input type="text" class="inprimaki-sarrera" placeholder="Izena" required />
              <input type="email" class="inprimaki-sarrera" placeholder="Emaila" required />
              <textarea rows="4" class="inprimaki-sarrera" placeholder="Mezua" required></textarea>
              <button type="submit" class="botoia botoi-nagusia">Bidali Mezua</button>
            </form>
          </div>
          <div class="sozial-kutxa">
            <img src="../irudiak/birtek_konponketak.png" alt="Repairing Laptop" class="kontaktu-irudia"
              loading="lazy" />
            <h3 class="sozial-azpititulua">Edo hitz egin gurekin!</h3>
            <a href="https://wa.me/34600000000" target="_blank" class="footer-wa-botoia"><i class="fab fa-whatsapp"></i>
              WhatsApp Hasi</a>
            <div class="sozial-ikono-lerroa">
              <a href="#" class="sozial-lotura"><i class="fab fa-facebook fb-kolorea"></i></a>
              <a href="#" class="sozial-lotura"><i class="fab fa-instagram ig-kolorea"></i></a>
              <a href="#" class="sozial-lotura"><i class="fab fa-x-twitter tw-kolorea"></i></a>
              <a href="#" class="sozial-lotura"><i class="fab fa-tiktok tik-kolorea"></i></a>
              <a href="#" class="sozial-lotura"><i class="fab fa-telegram tg-kolorea"></i></a>
            </div>
          </div>
          <div class="mapa-edukiontzia">
            <iframe class="mapa-iframe"
              src="https://maps.google.com/maps?q=Goierri+Eskola,+Arranomendia+2,+Ordizia&t=&z=15&ie=UTF8&iwloc=&output=embed"></iframe>
          </div>
        </div>
      </div>
    </section>
  </main>

  <?php include 'footer.php'; ?>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="../js/globala.js"></script>
</body>

</html>
``n
### php/langileak_menua.php
``php
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="eu">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BIRTEK - Langileen Gunea</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="../css/fontawesome/css/all.min.css" />
  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" />

  <!-- gure css artxiboak -->
  <link rel="stylesheet" href="../css/estiloak_globala.css" />
  <link rel="stylesheet" href="../css/estiloak_kontaktua.css" />
</head>

<body class="web-gorputza">
  <?php include 'goiburua.php'; ?>

  <main class="eduki-nagusia">
    <section class="langileak-edukiontzia">
      <div class="birtek-java-ap-botoia-kanpo">
        <a href="birtek://ireki" class="birtek-java-ap-botoia link-botoi">
          <i class="fas fa-users-cog"></i> BirtekAp
        </a>
        <a href="../../birtek_app_exekutablea.zip" class="birtek-java-ap-botoia link-botoi" download>
          <i class="fas fa-download"></i> App deskargatu
        </a>
      </div>

      <div class="inprimaki-kutxa">
        <h2 class="inprimaki-titulua testua-zentratuta">Lan egin gurekin</h2>
        <p class="testua-zentratuta tartea-behean-2 testua-grisa">Bete formulario hau eta bidali zure CV-a gure taldean
          sartzeko.</p>

        <form id="langile-eskaera-inprimakia" class="kontaktu-inprimaki-diseinua" action="#" method="POST"
          enctype="multipart/form-data">
          <div class="sareta-2-zutabe">
            <input type="text" name="izena" placeholder="Izena" class="inprimaki-sarrera" required />
            <input type="text" name="abizenak" placeholder="Abizenak" class="inprimaki-sarrera" required />
          </div>
          <input type="email" name="emaila" placeholder="Posta elektronikoa" class="inprimaki-sarrera" required />
          <input type="tel" name="telefonoa" placeholder="Telefono zenbakia" class="inprimaki-sarrera" required />
          <div>
            <label for="cv-igoera" class="label-input-fitxategia">Igo zure CV (PDF):</label>
            <input type="file" id="cv-igoera" name="cv" accept=".pdf" class="inprimaki-sarrera cv-igoera-sarrera"
              required />
          </div>
          <button type="submit" class="botoia botoi-nagusia">Eskaera Bidali</button>
        </form>
      </div>
    </section>
  </main>

  <?php include 'footer.php'; ?>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="../js/globala.js"></script>
  <script src="../js/langileak_menua.js"></script>
</body>

</html>
``n
### php/login_bezeroa.php
``php
<?php
session_start();

//include_once edo require_once erabili
require_once 'DB_konexioa.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emaila = trim($_POST['emaila']);
    $pasahitza = trim($_POST['pasahitza']);

    try {
        $stmt = $konexioa->prepare("SELECT * FROM bezeroak WHERE emaila = :emaila");
        $stmt->bindParam(':emaila', $emaila);
        $stmt->execute();
        $bezeroa = $stmt->fetch(PDO::FETCH_ASSOC);

        // OHARRA: Benetako aplikazio batean, password_verify() erabili beharko litzateke. SQL-ak testu arrunta edo hash sinpleak erakusten dituenez,
        // konparaketa zuzena edo egiaztapen sinplea egingo dugu.
        // SQLko INSERT-etan oinarrituta: '123456Jon', 'admin2024', etab. existitzen dira.
        // Era berean, erabiltzailearen argibideek esan zuten "pass: 1234" sysadmin guztientzat, baina bezeroek pasahitz desberdinak dituzte.
        // Berdintasun konparaketa sinplea erabiliko dugu gaurkoz, ariketa akademikoetan egin ohi den bezala,
        // edo password_verify hash-eratuak badira. SQL-n testu arrunta ikusten dudanez, testu arrunteko egiaztapena erabiliko dut.
        
        if ($bezeroa && $pasahitza === $bezeroa['pasahitza']) {
            $_SESSION['id_bezeroa'] = $bezeroa['id_bezeroa'];
            $_SESSION['izena'] = $bezeroa['izena_edo_soziala'];
            $_SESSION['emaila'] = $bezeroa['emaila'];
            
            header("Location: bezero_menua.php");
            exit();
        } else {
            
            header("Location: bezero_saioa_hasi.php?error=1");
            exit();
        }
    } catch (PDOException $e) {
        die("Errorea: " . $e->getMessage());
    }
} else {
    header("Location: bezero_saioa_hasi.php");
}
?>


``n
### php/login_hornitzailea.php
``php
<?php
session_start();
require_once 'DB_konexioa.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emaila = trim($_POST['emaila']);
    $pasahitza = trim($_POST['pasahitza']);

    try {
        $stmt = $konexioa->prepare("SELECT * FROM hornitzaileak WHERE emaila = :emaila");
        $stmt->bindParam(':emaila', $emaila);
        $stmt->execute();
        $hornitzailea = $stmt->fetch(PDO::FETCH_ASSOC);

        
        if ($hornitzailea && $pasahitza === $hornitzailea['pasahitza']) {
            $_SESSION['id_hornitzailea'] = $hornitzailea['id_hornitzailea'];
            $_SESSION['izena_soziala'] = $hornitzailea['izena_soziala'];
            $_SESSION['emaila'] = $hornitzailea['emaila'];
            
            header("Location: hornitzaile_menua.php");
            exit();
        } else {
            header("Location: hornitzaile_saioa_hasi.php?error=1");
            exit();
        }
    } catch (PDOException $e) {
        die("Errorea: " . $e->getMessage());
    }
} else {
    header("Location: hornitzaile_saioa_hasi.php");
}
?>

``n
### php/logout.php
``php
<?php
session_start();
session_unset();
session_destroy();

// hasiera orrira eramaten du
header("Location: hasiera.php");
exit();
?>

``n
### php/lortu_pasahitza_bezeroa.php
``php
<?php
require_once 'DB_konexioa.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['emaila'])) {
    $emaila = trim($_POST['emaila']);

    if (empty($emaila)) {
        echo "Mesedez, sartu posta elektroniko bat.";
        exit();
    }

    try {
        $stmt = $konexioa->prepare("SELECT pasahitza FROM bezeroak WHERE emaila = :emaila");
        $stmt->bindParam(':emaila', $emaila);
        $stmt->execute();
        $bezeroa = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($bezeroa) {
            echo "Zure pasahitza hau da: " . $bezeroa['pasahitza'];
        } else {
            echo "Ez da aurkitu konturik posta elektroniko horrekin.";
        }
    } catch (PDOException $e) {
        echo "Errorea datu-basean: " . $e->getMessage();
    }
} else {
    echo "Eskaera baliogabea.";
}
?>
``n
### php/lortu_pasahitza_hornitzailea.php
``php
<?php
require_once 'DB_konexioa.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['emaila'])) {
    $emaila = trim($_POST['emaila']);

    if (empty($emaila)) {
        echo "Mesedez, sartu posta elektroniko bat.";
        exit();
    }

    try {
        $stmt = $konexioa->prepare("SELECT pasahitza FROM hornitzaileak WHERE emaila = :emaila");
        $stmt->bindParam(':emaila', $emaila);
        $stmt->execute();
        $hornitzailea = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($hornitzailea) {
            echo "Zure pasahitza hau da: " . $hornitzailea['pasahitza'];
        } else {
            echo "Ez da aurkitu konturik posta elektroniko horrekin.";
        }
    } catch (PDOException $e) {
        echo "Errorea datu-basean: " . $e->getMessage();
    }
} else {
    echo "Eskaera baliogabea.";
}
?>
``n
### php/ordainketa_pasarela.php
``php
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
``n
### php/produktua_xehetasunak.php
``php
<?php
session_start();
require_once 'DB_konexioa.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$produktua = null;
$xehetasunak = null; // Subklasearen datuak hemen gordeko dira
$error_message = "";

if ($id > 0) {
    try {
        // 1. OINARRIZKO DATUAK LORTU
        $stmt = $konexioa->prepare("SELECT p.*, k.izena as kategoria_izena FROM produktuak p LEFT JOIN produktu_kategoriak k ON p.kategoria_id = k.id_kategoria WHERE p.id_produktua = ?");
        $stmt->execute([$id]);
        $produktua = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produktua) {
            $error_message = "Produktua ez da aurkitu.";
        } else {
            // 2. MOTAREN ARABERA ZEHETASUNAK LORTU (Subklaseak)
            $mota = $produktua['mota']; // ENUM baliotik (Eramangarria, Mahai-gainekoa, etc.)
            $taula = "";

            /* 
              mota ENUM('Generikoa', 'Eramangarria', 'Mahai-gainekoa', 'Mugikorra', 'Tableta', 'Zerbitzaria', 'Pantaila', 'Softwarea', 'Periferikoak', 'Kableak')
              DB Taulak: eramangarriak, mahai_gainekoak, mugikorrak, tabletak, zerbitzariak, pantailak, softwareak, periferikoak, kableak
            */

            switch ($mota) {
                case 'Eramangarria':
                    $taula = 'eramangarriak';
                    break;
                case 'Mahai-gainekoa':
                    $taula = 'mahai_gainekoak';
                    break;
                case 'Mugikorra':
                    $taula = 'mugikorrak';
                    break;
                case 'Tableta':
                    $taula = 'tabletak';
                    break;
                case 'Zerbitzaria':
                    $taula = 'zerbitzariak';
                    break;
                case 'Pantaila':
                    $taula = 'pantailak';
                    break;
                case 'Softwarea':
                    $taula = 'softwareak';
                    break;
                // OHARRA: 'Periferikoak' eta 'Kableak' taula izenak SQL eskeman 'periferikoak' eta 'kableak' dira (plural).
                case 'Periferikoak':
                    $taula = 'periferikoak';
                    break;
                case 'Kableak':
                    $taula = 'kableak';
                    break;

                case 'Generikoa':
                default:
                    $taula = "";
                    break;
            }

            if (!empty($taula)) {
                $stmtSub = $konexioa->prepare("SELECT * FROM $taula WHERE id_produktua = ?");
                $stmtSub->execute([$id]);
                $xehetasunak = $stmtSub->fetch(PDO::FETCH_ASSOC);
            }
        }

    } catch (PDOException $e) {
        $error_message = "Errorea datu-basean: " . $e->getMessage();
    }
} else {
    $error_message = "Ez da produktu ID baliozkorik eskatu.";
}
?>
<!DOCTYPE html>
<html lang="eu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Produktuaren izena hartu datu basetik -->
    <title><?php echo $produktua ? htmlspecialchars($produktua['izena']) . " - BIRTEK" : "Produktua ez da aurkitu"; ?>
    </title>
    <!-- Font Awesome (ikonoak) -->
    <link rel="stylesheet" href="../css/fontawesome/css/all.min.css" />
    <!-- Google Fonts (letra-tipoak) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" />
    <link rel="stylesheet" href="../css/estiloak_globala.css">
    <link rel="stylesheet" href="../css/estiloak_produktu_xehetasunak.css">

</head>

<body class="web-gorputza">
    <!-- GOIBURUA -->
    <?php include_once 'goiburua.php'; ?>

    <main class="eduki-nagusia">
        <?php if ($error_message): ?>
            <div class="xehetasunak-errorea">
                <h2>Arazotxo bat egon da...</h2>
                <p><?php echo htmlspecialchars($error_message); ?></p>
                <a href="produktuak.php" class="botoia botoi-nagusia botoia-itzuli-xehetasunak">Produktu guztietara
                    itzuli</a>
            </div>
        <?php elseif ($produktua): ?>
            <!-- Class renamed to xehetasunak-edukiontzia -->
            <div class="xehetasunak-edukiontzia">
                <div class="xehetasunak-irudia-container">
                    <?php
                    $irudia = $produktua['irudia_url'];
                    if (!empty($irudia)) {
                        if (strpos($irudia, 'http') === 0) {
                            $imgUrl = $irudia;
                        } else {
                            $imgUrl = '../irudiak/' . $irudia;
                        }
                    } else {
                        $imgUrl = '../irudiak/birtek1.jpeg';
                    }
                    ?>
                    <img src="<?php echo htmlspecialchars($imgUrl); ?>"
                        alt="<?php echo htmlspecialchars($produktua['izena']); ?>" class="xehetasunak-irudia"
                        onerror="this.src='../irudiak/birtek1.jpeg'">
                </div>

                <div class="xehetasunak-info">
                    <h1 class="produktu-izenburua"><?php echo htmlspecialchars($produktua['izena']); ?></h1>

                    <div class="produktu-meta">
                        <!-- Mota eta Kategoria -->
                        <span class="meta-etiketa"><i class="fas fa-tag"></i>
                            <?php echo htmlspecialchars($produktua['mota']); ?></span>
                        <span class="meta-etiketa"><i class="fas fa-certificate"></i>
                            <?php echo htmlspecialchars($produktua['marka']); ?></span>
                        <span class="meta-etiketa"><i class="fas fa-heartbeat"></i>
                            <?php echo htmlspecialchars($produktua['produktu_egoera']); ?></span>
                    </div>

                    <!-- ZEHETASUN TEKNIKOAK - Subklasearen arabera -->
                    <?php if ($xehetasunak): ?>
                        <div class="xehetasun-teknikoak-kutxa">
                            <h4 class="xehetasun-teknikoak-izenburua">Ezaugarri Teknikoak:</h4>
                            <ul class="xehetasun-teknikoak-zerrenda">
                                <?php foreach ($xehetasunak as $gakoa => $balioa): ?>
                                    <?php if ($gakoa !== 'id_produktua' && $balioa !== null && $balioa !== ''): ?>
                                        <li class="xehetasun-teknikoak-item">
                                            <strong><?php echo ucfirst(str_replace('_', ' ', $gakoa)); ?>:</strong>
                                            <?php
                                            // Balio boolearrak 'Bai'/'Ez' bihurtu
                                            if ($balioa == '1' && strlen($balioa) == 1)
                                                echo 'Bai';
                                            elseif ($balioa == '0' && strlen($balioa) == 1)
                                                echo 'Ez';
                                            else
                                                echo htmlspecialchars($balioa);
                                            ?>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="produktu-prezioa">
                        <?php echo number_format($produktua['salmenta_prezioa'], 2); ?> €
                        <?php if (!empty($produktua['eskaintza'])): ?>
                            <span class="prezioa-eskaintza">
                                <?php
                                // Adibidez, eskaintza kalkulatu nahi bada.
                                // Hemen 'eskaintza' atributua % bat den edo prezio zuzena den ez dago argi, 
                                // baina bistaratze sinple bat egingo dugu.
                                echo "Eskaintza: " . $produktua['eskaintza'] . "% DTO";
                                ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="stock-info <?php echo $produktua['stock'] > 0 ? 'stock-bai' : 'stock-ez'; ?>">
                        <?php if ($produktua['stock'] > 0): ?>
                            <i class="fas fa-check-circle"></i> Stock-ean: <?php echo $produktua['stock']; ?> ale
                        <?php else: ?>
                            <i class="fas fa-times-circle"></i> Agortuta
                        <?php endif; ?>
                    </div>

                    <p class="produktu-deskribapena">
                        <?php echo nl2br(htmlspecialchars($produktua['deskribapena'])); ?>
                    </p>

                    <div class="ekintza-eremua">
                        <?php if ($produktua['stock'] > 0): ?>
                            <div class="produktu-kantitatea-aldatu">
                                <label for="botoi-kopurua" class="kantitate-label">Kantitatea:</label>
                                <button class="kantitate-btn" id="kendu-kantitatea">-</button>
                                <input type="number" id="botoi-kopurua" class="kantitate-input" value="1" min="1"
                                    max="<?php echo $produktua['stock']; ?>">
                                <button class="kantitate-btn" id="gehitu-kantitatea">+</button>
                            </div>
                            <button class="botoia botoi-nagusia saskiratu-xehetasunak saskiratu-xehetasunak-botoia"
                                data-id="<?php echo $produktua['id_produktua']; ?>"
                                data-izena="<?php echo htmlspecialchars($produktua['izena']); ?>"
                                data-prezioa="<?php echo $produktua['salmenta_prezioa']; ?>"
                                data-stock="<?php echo $produktua['stock']; ?>">
                                <i class="fas fa-cart-plus"></i> Saskira Gehitu
                            </button>
                        <?php else: ?>
                            <button class="botoia xehetasunak-agortuta-botoia" disabled>
                                Stock agortuta
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <!-- OINA -->
    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
    <script>
        $(document).ready(function () {
            // Kantitatea aldatzeko botoiak (+ / -)
            $("#gehitu-kantitatea").click(function () {
                var input = $("#botoi-kopurua");
                var balioa = parseInt(input.val()) || 1;
                var max = parseInt(input.attr("max"));
                if (balioa < max) {
                    input.val(balioa + 1);
                }
            });

            $("#kendu-kantitatea").click(function () {
                var input = $("#botoi-kopurua");
                var balioa = parseInt(input.val()) || 1;
                var min = parseInt(input.attr("min")) || 1;
                if (balioa > min) {
                    input.val(balioa - 1);
                }
            });

            // Xehetasun orriko saskiratu logika espezifikoa
            $(".saskiratu-xehetasunak").click(function () {
                var btn = $(this);
                var id = btn.data("id");
                var izena = btn.data("izena");
                var prezioa = parseFloat(btn.data("prezioa"));
                var stock = parseInt(btn.data("stock")) || 0;
                var kantitatea = parseInt($("#botoi-kopurua").val()) || 1;

                // Saskia berreskuratu
                var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];

                // Begiratu ea badagoen
                var badago = saskia.find(i => i.id == id);
                var saskianDagoenKantitatea = badago ? badago.kantitatea : 0;

                // STOCK KONTROLA
                if ((saskianDagoenKantitatea + kantitatea) > stock) {
                    alert("Ezin da gehitu: Stock nahikorik ez (" + stock + " ale geratzen dira). Saskian: " + saskianDagoenKantitatea);
                    return;
                }

                if (badago) {
                    badago.kantitatea += kantitatea;
                } else {
                    saskia.push({
                        id: id,
                        izena: izena,
                        prezioa: prezioa,
                        kantitatea: kantitatea,
                        stock: stock // Gordetzen dugu ere, globala.js-en erabiltzeko
                    });
                }

                // Gorde (Globala.js logika erabiltzen badu, ondo. Bestela eskuz)
                if (typeof window.saskiaGorde === "function") {
                    window.saskiaGorde(saskia); // Honek dropdown ere eguneratzen du

                    // Animazioa deitu
                    if (typeof window.saskiaAnimatuKontagailua === "function") {
                        window.saskiaAnimatuKontagailua();
                    }
                } else {
                    localStorage.setItem("birtek_saskia", JSON.stringify(saskia));
                    location.reload();
                }

                // Animazio txiki bat botoian
                btn.html('<i class="fas fa-check"></i> Gehituta!');
                btn.css('background-color', '#166534');
                setTimeout(function () {
                    btn.html('<i class="fas fa-cart-plus"></i> Saskira Gehitu');
                    btn.css('background-color', '');
                }, 2000);
            });
        });
    </script>
</body>

</html>
``n
### php/produktuak.php
``php
<?php
session_start();
// DB konexioa
require_once 'DB_konexioa.php';


// produktu kopuru totala lortu (Hasieran hutsik, gero beteko dugu)
$produktu_kopuru_totala = 0;

$produktuak_lista = [];

try {
  // Kontsulta nagusia (URL parametroen bidez filtratua)
  $sql = "SELECT p.*, k.izena as produktu_kategoria_izena 
            FROM produktuak p 
            LEFT JOIN produktu_kategoriak k ON p.kategoria_id = k.id_kategoria
            WHERE p.salgai = 1 AND p.stock > 0";
  
  $params = [];

  // BILAKETA LOGIKA (PHP)
  $bilatu_hitza = "";
  if (isset($_GET['bilatu']) && !empty(trim($_GET['bilatu']))) {
      $bilatu_hitza = trim($_GET['bilatu']);
      $sql .= " AND (p.izena LIKE :bilatu OR p.deskribapena LIKE :bilatu OR p.marka LIKE :bilatu)";
      $params[':bilatu'] = "%" . $bilatu_hitza . "%";
  }

  // PREZIOAREN ARABERA ordenatu
  if (isset($_GET['prezio-ordenatu'])) {
    $ordena = $_GET['prezio-ordenatu'];
    if ($ordena === 'prezioa-asc') {
      $sql .= " ORDER BY p.salmenta_prezioa ASC";
    } elseif ($ordena === 'prezioa-desc') {
      $sql .= " ORDER BY p.salmenta_prezioa DESC";
    }
  }

  $stmt = $konexioa->prepare($sql);
  $stmt->execute($params);
  $db_produktuak = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Kategoriak lortu filtrorako
  $stmt_kategoriak = $konexioa->prepare("SELECT izena FROM produktu_kategoriak ORDER BY izena");
  $stmt_kategoriak->execute();
  $kategoriak_filtro = $stmt_kategoriak->fetchAll(PDO::FETCH_COLUMN);

  // Egoerak, motak lortu filtrorako
  $stmt_egoerak = $konexioa->prepare("SELECT DISTINCT produktu_egoera FROM produktuak WHERE salgai = 1 AND stock > 0 ORDER BY produktu_egoera");
  $stmt_egoerak->execute();
  $egoerak_filtro = $stmt_egoerak->fetchAll(PDO::FETCH_COLUMN);

  $stmt_motak = $konexioa->prepare("SELECT DISTINCT mota FROM produktuak WHERE salgai = 1 AND stock > 0 ORDER BY mota");
  $stmt_motak->execute();
  $motak_filtro = $stmt_motak->fetchAll(PDO::FETCH_COLUMN);

  // Datuak formatu egokian prestatu
  foreach ($db_produktuak as $lerroa) {
    $irudia = $lerroa['irudia_url'];
    if (!empty($irudia)) {
      // Begiratu ea URL osoa den (http...//) edo fitxategi izen soila
      if (strpos($irudia, 'http') === 0) {
        $final_url = $irudia;
      } else {
        // Irudiak karpetan badago fitxategia
        $final_url = '../irudiak/' . $irudia;
      }
    } else {
      $final_url = 'https://via.placeholder.com/300?text=Irudirik+Ez';
    }

    $produktuak_lista[] = [
      'id_produktua' => (int) $lerroa['id_produktua'],
      'izena' => $lerroa['izena'],
      'deskribapena' => $lerroa['deskribapena'],
      'id_kategoria' => $lerroa['produktu_kategoria_izena'],
      'marka' => $lerroa['marka'],
      'mota' => $lerroa['mota'],
      'egoera' => $lerroa['produktu_egoera'],
      'prezioa' => (float) $lerroa['salmenta_prezioa'],
      'stock' => (int) $lerroa['stock'],
      'salgai' => (bool) $lerroa['salgai'],
      'irudia_url' => $final_url,
      'ezaugarriak_json' => [
        'Egoera Oharra' => $lerroa['produktu_egoera_oharra'] ?? 'Informazio gehigarririk ez.',
        'Mota' => $lerroa['mota']
      ]
    ];
  }

  // JS-rako produktu GUZTIAK lortu (filtroek dinamikoki funtziona dezaten)
  $stmt_guztiak = $konexioa->prepare("SELECT p.*, k.izena as produktu_kategoria_izena 
                                     FROM produktuak p 
                                     LEFT JOIN produktu_kategoriak k ON p.kategoria_id = k.id_kategoria
                                     WHERE p.salgai = 1 AND p.stock > 0");
  $stmt_guztiak->execute();
  $db_guztiak = $stmt_guztiak->fetchAll(PDO::FETCH_ASSOC);
  $hasierako_produktu_guztiak = [];

  foreach ($db_guztiak as $lerroa) {
    // Irudiaren URL-a prestatu
    $irudia = $lerroa['irudia_url'];
    if (!empty($irudia)) {
      $final_url = (strpos($irudia, 'http') === 0) ? $irudia : '../produktuen_irudiak/' . $irudia;
    } else {
      $final_url = '../irudiak/birtek1.jpeg';
    }

    $hasierako_produktu_guztiak[] = [
      'id_produktua' => (int) $lerroa['id_produktua'],
      'izena' => $lerroa['izena'],
      'deskribapena' => $lerroa['deskribapena'],
      'id_kategoria' => $lerroa['produktu_kategoria_izena'],
      'marka' => $lerroa['marka'],
      'mota' => $lerroa['mota'],
      'egoera' => $lerroa['produktu_egoera'],
      'prezioa' => (float) $lerroa['salmenta_prezioa'],
      'stock' => (int) $lerroa['stock'],
      'salgai' => (bool) $lerroa['salgai'],
      'irudia_url' => $final_url
    ];
  }

  // Eguneratu kopuru totala filtratutakoen arabera
  $produktu_kopuru_totala = count($produktuak_lista);

} catch (Exception $e) {
  // Errorea gertatuz gero, array hutsa edo errorea kudeatu dezakegu
  // Kasu honetan, HTMLan hutsik agertuko da edo mezu bat
  $error_message = "Errorea datu basearekin: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="eu">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BIRTEK - Produktuak</title>

  <!-- Font Awesome (ikonoak) -->
  <link rel="stylesheet" href="../css/fontawesome/css/all.min.css" />
  <!-- Google Fonts (letra-tipoak) -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" />

  <link rel="stylesheet" href="../css/estiloak_globala.css?v=1.3" />
  <link rel="stylesheet" href="../css/estiloak_produktuak.css?v=1.3" />
</head>

<!-- ===================================================================================== -->

<body class="web-gorputza">
  <?php include 'goiburua.php'; ?>
  <!-- ================================================================================================= -->
  <main class="eduki-nagusia">
    <!-- PRODUKTUAK ORRIA -->
    <section>
      <h2 class="produktuak-titulua">Gure Produktuak</h2>

      <div id="produktu-kopuru-info" class="produktu-kopuru-info">
        <span>Guztira:</span>
        <span id="kopurua-txapa" class="kopurua-txapa"><?php echo $produktu_kopuru_totala; ?></span>
      </div>

      <div class="produktuak-orria">
        <div class="alboko-barra">
          <!-- Iragazkiak -->
          <aside class="iragazki-kaxa">
            <!-- Iragazki Goiburua -->
            <div class="iragazki-goiburua">
              <h3 class="iragazki-izenburua">
                <!-- Iragazki filtro IKONOA -->
                <i class="fas fa-filter"></i>Iragazkiak
              </h3>
            </div>

            <!-- Iragazki Edukia (Mugikorrean ezkutatzeko) -->
            <div class="iragazki-edukia">
              <!-- Garbitu Botoia (Mugikorrean ezkutuan egoteko) -->
              <div class="berrezarri-botoia">
                <button class="berrezarri-testua iragazkiak-berrezarri">Garbitu</button>
              </div>
              <!-- Iragazki Taldea: BILATZAILEA -->
              <div>
                <form action="produktuak.php" method="GET">
                  <label class="iragazki-etiketa">Bilatu</label>
                  <input type="search" name="bilatu" placeholder="Produktua..." id="iragazkia-bilatu" class="inprimaki-sarrera" value="<?php echo htmlspecialchars($bilatu_hitza); ?>" />
                  <?php if (isset($_GET['prezio-ordenatu'])): ?>
                      <input type="hidden" name="prezio-ordenatu" value="<?php echo htmlspecialchars($_GET['prezio-ordenatu']); ?>">
                  <?php endif; ?>
                </form>
              </div>
              <!-- Iragazki Taldea: PRODUKTU EGOERA -->
              <div>
                <label class="iragazki-etiketa">Egoera</label>
                <select id="iragazkia-egoera" class="inprimaki-hautatu">
                  <option value="">Guztiak</option>
                  <?php foreach ($egoerak_filtro as $egoera): ?>
                    <option value="<?php echo htmlspecialchars($egoera); ?>"><?php echo htmlspecialchars($egoera); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <!-- Iragazki Taldea: ORDENATU -->
              <div>
                <label class="iragazki-etiketa">Ordenatu</label>
                <select id="iragazkia-ordenatu" class="inprimaki-hautatu">
                  <option value="default">Lehenetsia</option>
                  <option value="izena-asc">Izena: A-Z</option>
                  <option value="izena-desc">Izena: Z-A</option>
                  <option value="stock-desc">Stock: Handienetik</option>
                  <option value="stock-asc">Stock: Gutxienetik</option>
                </select>
              </div>
              <!-- Iragazki Taldea: PREZIOA (RADIO BUTTONS) -->
              <div>
                <label class="iragazki-etiketa">Prezioa</label>
                <div class="prezio-ordenatu-radio">
                  <label class="radio-etiketa">
                    <input type="radio" name="prezio-ordenatu" value="prezioa-asc" id="prezio-asc" 
                      <?php if (isset($_GET['prezio-ordenatu']) && $_GET['prezio-ordenatu'] == 'prezioa-asc')
                        echo 'checked'; ?>>
                    Txikitik Handira
                  </label>
                  <label class="radio-etiketa">
                    <input type="radio" name="prezio-ordenatu" value="prezioa-desc" id="prezio-desc" 
                      <?php if (isset($_GET['prezio-ordenatu']) && $_GET['prezio-ordenatu'] == 'prezioa-desc')
                        echo 'checked'; ?>>
                    Handitik Txikira
                  </label>
                </div>
              </div>
              <!-- Iragazki Taldea: FILTRO PREZIOA  -->
              <div>
                <label class="iragazki-etiketa">Filtro Prezioa (€)</label>
                <div class="prezio-iragazki-taldea">
                  <input type="number" id="prezioa-min" placeholder="Min" class="inprimaki-sarrera zabaler-erdi" />
                  <input type="number" id="prezioa-max" placeholder="Max" class="inprimaki-sarrera zabaler-erdi" />
                </div>
              </div>

              <!-- Iragazki Taldea: KATEGORIA -->
              <div>
                <label class="iragazki-etiketa">Kategoria</label>
                <select id="iragazkia-kategoria" class="inprimaki-hautatu">
                  <option value="">Guztiak</option>
                  <?php foreach ($kategoriak_filtro as $kategoria): ?>
                    <option value="<?php echo htmlspecialchars($kategoria); ?>">
                      <?php echo htmlspecialchars($kategoria); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Iragazki Taldea: MOTA  -->
              <div>
                <label class="iragazki-etiketa">Mota</label>
                <select id="iragazkia-mota" class="inprimaki-hautatu">
                  <option value="">Guztiak</option>
                  <?php foreach ($motak_filtro as $mota): ?>
                    <option value="<?php echo htmlspecialchars($mota); ?>"><?php echo htmlspecialchars($mota); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </aside>
        </div>

        <div class="eduki-zutabea">
          <!-- PRODUKTUEN SAREA -->
          <div class="produktu-sarea">
            <?php if (isset($error_message)): ?>
              <p>Errorea produktuak kargatzean: <?php echo htmlspecialchars($error_message); ?></p>
            <?php elseif (empty($produktuak_lista)): ?>
              <p>Ez dago produkturik eskuragarri une honetan.</p>
            <?php else: ?>
              <?php foreach ($produktuak_lista as $produktua): ?>
                <?php
                $stockKlasea = $produktua['stock'] > 0 ? "txartel-stock" : "txartel-stock-agortuta";
                $prezioaFix = number_format($produktua['prezioa'], 2, '.', '');
                ?>
                  <div class="produktu-txartela" data-id="<?php echo $produktua['id_produktua']; ?>">
                    <div class="txartel-irudia klikagarria-joan">
                <!-- $p = $produktuak['irudia_url']-->
                <!-- $rutaAbs="/2ERR_1TALDEA_BIRTEK_WEB_ORRIA/produktuen_irudiak" -->
                <!-- <img src ="$rutaAbs . $p"-->
                      <img
                      
                        src="<?php echo htmlspecialchars('../produktuen_irudiak/'.$produktua['irudia_url']); ?>"
                        alt="<?php echo htmlspecialchars($produktua['izena']); ?>"
                        class="txartel-irudia"
                        onerror="this.src='../irudiak/birtek1.jpeg'"
                      />
                      <div class="txartel-kategoria-txapa"><?php echo htmlspecialchars($produktua['id_kategoria']); ?></div> 
                    </div>
                    <div class="txartel-edukia">
                      <h3 class="txartel-izenburua klikagarria-joan"><?php echo htmlspecialchars($produktua['izena']); ?></h3>
                      <div class="txartel-informazio-lerroa">
                        <span class="txartel-marka"><?php echo htmlspecialchars($produktua['marka']); ?> | <?php echo htmlspecialchars($produktua['egoera']); ?></span>
                        <span class="<?php echo $stockKlasea; ?>">Stock: <?php echo $produktua['stock']; ?></span>
                      </div>
                      <p class="txartel-azalpena">
                        <?php echo htmlspecialchars($produktua['deskribapena'] ?? ""); ?>
                      </p>

                      <div class="txartel-oina">
                        <span class="txartel-prezioa"><?php echo $prezioaFix; ?> €</span>
                        <button class="produktua-saskiratu-botoia" data-stock="<?php echo $produktua['stock']; ?>" <?php echo $produktua['stock'] === 0 ? 'disabled' : ''; ?>>
                          Saskiratu
                        </button>
                        <button class="produktua-ikusi-botoia klikagarria-joan">Ikusi</button>
                      </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>
  </main>
  <!-- ================================================================================================= -->
  <!-- OINA -->
  <?php include 'footer.php'; ?>

  <!-- ================================================================================================= -->
  <!-- SCRIPT ZATIA -->
  <!-- JQUERY LIBURUTEGIA IMPORTATU -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
    // PHP-tik datuak pasatzeko JS-ra, filtroak funtziona dezaten (GUZTIAK)
    var hasierakoProduktuak = <?php echo json_encode($hasierako_produktu_guztiak); ?>;
  </script>
  <script src="../js/globala.js"></script>
  <script src="../js/produktuak.js?v=1.4"></script>
</body>

</html>

``n
### php/prozesatu_erosketa.php
``php
<?php
session_start();
require_once 'DB_konexioa.php';

header('Content-Type: application/json');

// Egiaztatu bezeroa saioa hasita dagoen
if (!isset($_SESSION['id_bezeroa'])) {
    echo json_encode(['success' => false, 'message' => 'Saioa ez dago hasita.']);
    exit;
}

$bezeroa_id = $_SESSION['id_bezeroa'];
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['saskia']) || empty($data['saskia'])) {
    echo json_encode(['success' => false, 'message' => 'Saskia hutsik dago.']);
    exit;
}

$saskia = $data['saskia'];

try {
    $konexioa->beginTransaction();

    $guztira = 0;

    // 1. Pase bat stock-a eta prezioak egiaztatzeko
    $konprobatutako_produktuak = []; // Cachea: id => [stock, prezioa, gastatua]

    foreach ($saskia as &$elementua) {
        $prodId = $elementua['id'];

        // Datuak kargatu ez badira, DBtik ekarri (FOR UPDATE erabiltzen dugu blokeatzeko)
        if (!isset($konprobatutako_produktuak[$prodId])) {
            $stmtP = $konexioa->prepare("SELECT salmenta_prezioa, stock FROM produktuak WHERE id_produktua = ? FOR UPDATE");
            $stmtP->execute([$prodId]);
            $prod = $stmtP->fetch(PDO::FETCH_ASSOC);

            if (!$prod) {
                throw new Exception("Produktua ez da aurkitu: " . $elementua['izena']);
            }
            
            $konprobatutako_produktuak[$prodId] = [
                'stock' => $prod['stock'],
                'prezioa' => $prod['salmenta_prezioa'],
                'gastatua' => 0
            ];
        }

        // Egiaztatu nahikoa stock dagoen (orain arte gastatutakoa kontuan hartuta)
        $uneko_stock = $konprobatutako_produktuak[$prodId]['stock'];
        $dagoeneko_gastatua = $konprobatutako_produktuak[$prodId]['gastatua'];
        $beharrezkoa = $elementua['kantitatea'];

        if (($uneko_stock - $dagoeneko_gastatua) < $beharrezkoa) {
            $geratzen_direnak = max(0, $uneko_stock - $dagoeneko_gastatua);
            throw new Exception("Ez dago stock nahikorik: " . $elementua['izena'] . " (" . $geratzen_direnak . " ale geratzen dira)");
        }

        // Eguneratu gastatutako kantitatea
        $konprobatutako_produktuak[$prodId]['gastatua'] += $beharrezkoa;

        // Kalkulatu guztira DBko prezioekin (segurtasuna)
        $guztira += $konprobatutako_produktuak[$prodId]['prezioa'] * $beharrezkoa;
        $elementua['db_prezioa'] = $konprobatutako_produktuak[$prodId]['prezioa'];
    }
    unset($elementua); // Apurtu erreferentzia

    // 2. Sortu eskaera nagusia
    $stmtE = $konexioa->prepare("INSERT INTO eskaerak (bezeroa_id, guztira_prezioa, data, eskaera_egoera) VALUES (?, ?, NOW(), 'Prestatzen')");
    $stmtE->execute([$bezeroa_id, $guztira]);
    $eskaera_id = $konexioa->lastInsertId();

    // 3. Sortu eskaera lerroak eta eguneratu stock-a
    $stmtL = $konexioa->prepare("INSERT INTO eskaera_lerroak (eskaera_id, produktua_id, kantitatea, unitate_prezioa, eskaera_lerro_egoera) VALUES (?, ?, ?, ?, 'Prestatzen')");
    $stmtS = $konexioa->prepare("UPDATE produktuak SET stock = stock - ? WHERE id_produktua = ?");

    foreach ($saskia as $elementua) {
        $stmtL->execute([$eskaera_id, $elementua['id'], $elementua['kantitatea'], $elementua['db_prezioa']]);
        $stmtS->execute([$elementua['kantitatea'], $elementua['id']]);
    }

    $konexioa->commit();
    echo json_encode(['success' => true, 'eskaera_id' => $eskaera_id]);

} catch (Exception $e) {
    if ($konexioa->inTransaction()) {
        $konexioa->rollBack();
    }
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
``n
## CSS Fitxategiak

### css/estiloak_berriak.css
``css
/* =========================================
   BERRIAK ORRIAREN ESTILOAK
   ========================================= */

/* Eduki nagusiaren tartea */
.eduki-nagusia {
  padding-top: 2rem;
  padding-bottom: 4rem;
}

.berriak-edukiontzia {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
  text-align: center;
  padding: 0 1rem;
}

.berriak-titulua {
  font-size: 1.75rem;
  font-weight: 800;
  margin-top: 1rem;
  margin-bottom: 2rem;
  text-align: center;
  font-family: "Outfit", sans-serif;
  text-transform: uppercase;
  color: #166534;
  word-wrap: break-word;
  display: block;
}

/* --- GRID --- */
.berriak-sarea {
  display: grid;
  grid-template-columns: repeat(1, 1fr); /* Mugikorra lehenetsi */
  gap: 2rem;
  max-width: 1200px; /* Zabalera maximoa zabaldu */
  margin: 0 auto;
  padding: 0 1rem;
}

/* --- ALBISTE TXARTELA --- */
.albiste-txartela {
  background-color: #ffffff;
  border-radius: 0.75rem; /* 12px */
  overflow: hidden;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1),
    0 2px 4px -1px rgba(0, 0, 0, 0.06);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  display: flex;
  flex-direction: column;
  height: 100%;
}

.albiste-txartela:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1),
    0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

/* --- IRUDIA --- */
.albiste-irudia {
  height: 200px;
  overflow: hidden;
  position: relative;
}

.albiste-irudia img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.albiste-txartela:hover .albiste-irudia img {
  transform: scale(1.05);
}

.albiste-edukia {
  padding: 1rem;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
}

.albiste-kategoria {
  display: inline-block;
  padding: 0.25rem 0.75rem;
  background-color: #dbeafe; /* Urdin argia */
  color: #2563eb; /* Urdin bizia */
  font-size: 0.75rem;
  font-weight: 600;
  border-radius: 9999px; /* Pilula forma */
  text-transform: uppercase;
  letter-spacing: 0.05em;
  margin-bottom: 0.75rem;
  align-self: flex-start;
}

/* Kategoria desberdinentzako koloreak (aukerakoa) */
.albiste-kategoria:contains("Jasangarritasuna") {
  background-color: #d1fae5;
  color: #059669;
}

.albiste-titulua {
  font-size: 1.25rem;
  font-weight: 700;
  color: #1f2937;
  margin-bottom: 0.75rem;
  line-height: 1.4;
}

.albiste-laburpena {
  color: #6b7280;
  font-size: 0.95rem;
  line-height: 1.6;
  margin-bottom: 0;
}

/* =========================================
   MEDIA QUERIES
   ========================================= */

@media (min-width: 375px) {
  .albiste-edukia {
    padding: 1.5rem;
  }
}

@media (min-width: 768px) {
  .berriak-titulua {
    font-size: 2.25rem;
    margin-bottom: 3rem;
  }

  .berriak-sarea {
    grid-template-columns: repeat(2, 1fr); /* Tablet */
  }
}

@media (min-width: 1024px) {
  .berriak-sarea {
    grid-template-columns: repeat(3, 1fr); /* Mahaigaina */
  }
}

``n
### css/estiloak_bezero_eskaerak.css
``css
.eskari-edukiontzia {
  max-width: 900px;
  margin: 1rem auto;
  padding: 1rem;
}

.eskari-txartela {
  background: white;
  border-radius: 1.5rem;
  margin-bottom: 2rem;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
  border: 1px solid rgba(0, 0, 0, 0.05);
}

.eskari-goiburua {
  background: #f9fafb;
  padding: 1.5rem;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  flex-direction: column; 
  gap: 1rem;
}

.eskari-izenburua {
  font-family: "Outfit", sans-serif;
  font-weight: 800;
  font-size: 1.25rem;
  color: #166534;
}

.eskari-egoera-etiketa {
  padding: 0.25rem 0.5rem;
  border-radius: 4px;
  font-size: 0.9rem;
  font-weight: bold;
}

.egoera-Osatua {
  background-color: #d4edda;
  color: #155724;
}
.egoera-Bidalita {
  background-color: #cce5ff;
  color: #004085;
}
.egoera-Prestatzen {
  background-color: #fff3cd;
  color: #856404;
}
.egoera-Ezabatua {
  background-color: #f8d7da;
  color: #721c24;
}

.eskari-gorputza {
  padding: 0.5rem; 
}

.taula-scroll-edukiontzia,
#erosketa-saski-container {
  overflow-x: auto;
  width: 100%;
}

.lerro-taula {
  width: 100%;
  border-collapse: collapse;
  margin-top: 0.5rem;
  min-width: 500px; 
}

.lerro-taula th,
.lerro-taula td {
  text-align: left;
  padding: 0.4rem 0.2rem;
  border-bottom: 1px solid #eee;
}

.lerro-taula th {
  font-size: 0.8rem;
  color: #666;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.testu-apala {
  color: #6c757d;
}

.guztira-lerroa {
  text-align: right;
  font-weight: bold;
  margin-top: 1rem;
  font-size: 1.1rem;
  padding: 1rem;
}

.saski-oina-guztira {
  text-align: center;
  font-weight: 800;
  font-size: 1.5rem;
  padding: 1.5rem;
  border-top: 1px solid #eee;
  background: #f9fafb;
}

.atzera-botoia {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  margin-bottom: 1.5rem;
  color: #4b5563;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.3s ease;
}

.atzera-botoia:hover {
  color: #166534;
}

.ezabatu-eskaria-botoia,
.faktura-deskargatu-botoia {
  display: inline-block;
  padding: 0.6rem 1.2rem;
  font-size: 0.85rem;
  border-radius: 0.5rem;
  font-weight: 600;
  text-align: center;
  text-decoration: none;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
  width: 100%; 
}

.ezabatu-eskaria-botoia {
  background-color: #fee2e2;
  color: #991b1b;
}

.ezabatu-eskaria-botoia:hover {
  background-color: #fecaca;
}

.faktura-deskargatu-botoia {
  background-color: var(--kolore-nagusia-argia, #dcfce7);
  color: var(--kolore-nagusia-iluna, #166534);
}

.faktura-deskargatu-botoia:hover {
  background-color: #bbf7d0;
}

.ezabatu-lerroa-botoia {
  color: #dc3545;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1.1rem;
}

@media (min-width: 768px) {
  .eskari-edukiontzia {
    margin: 3rem auto;
  }

  .eskari-goiburua {
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
  }

  .eskari-gorputza {
    padding: 1.5rem;
  }

  .ezabatu-eskaria-botoia,
  .faktura-deskargatu-botoia {
    width: auto;
  }

  .lerro-taula th {
    font-size: 0.9rem;
  }
}

``n
### css/estiloak_bezero_menua.css
``css
.ongi-etorri-mezua {
  text-align: center;
  font-family: "Outfit", sans-serif;
  font-size: 2rem;
  color: #166534;
  margin-bottom: 2rem;
  margin-top: 2rem;
}

.menu-bezeroa {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
  max-width: 1000px;
  margin: 1.5rem auto 3rem auto;
  padding: 0 1.5rem;
}

.menu-txartela {
  background: white;
  border-radius: 1.5rem;
  padding: 2.5rem 2rem;
  text-align: center;
  text-decoration: none;
  color: var(--testu-nagusia);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border: 1px solid rgba(0, 0, 0, 0.05);
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1.5rem;
}

.menu-txartela:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.1);
  border-color: var(--kolore-nagusia-tainua);
}

.menu-ikonoa {
  font-size: 3.5rem;
  color: var(--kolore-nagusia);
  margin-bottom: 0;
}

.menu-izenburua {
  font-size: 1.4rem;
  font-weight: 700;
  font-family: var(--font-izenburua);
}

.logout-btn {
  display: block;
  width: fit-content;
  margin: 2rem auto;
  text-decoration: none;
  font-weight: bold;
}

@media (min-width: 640px) {
  .menu-bezeroa {
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
  }
}

``n
### css/estiloak_datu_pertsonalak_aldatu.css
``css
.inprimaki-edukiontzia {
  max-width: 800px;
  margin: 2rem auto;
  padding: 2rem;
  background: #fff;
  border-radius: 0.5rem;
  box-shadow:
    0 1px 3px 0 rgba(0, 0, 0, 0.1),
    0 1px 2px 0 rgba(0, 0, 0, 0.06);
}


.datu-aldaketa-botoia {
  width: 100%;
  margin-top: 2rem;
  padding: 12px;
  font-size: 1.1rem;
}

.atzera-botoi-kontainer {
  text-align: center;
  margin-top: 1.5rem;
}

.herri-berria-panela {
  display: none;
  grid-column: 1 / -1;
  background: rgba(0, 0, 0, 0.05);
  padding: 1.5rem;
  border-radius: 0.5rem;
  margin-top: 0.5rem;
}

.herri-berria-izenburua {
  margin-bottom: 1rem;
  color: var(--kolore-nagusia);
}


``n
### css/estiloak_globala.css
``css
/* --- BIRTEK - ESTILO GLOBALA --- */
@import url("https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;800&family=Inter:wght@400;500;600&display=swap");

/* --- ALDAGAIAK --- */
:root {
  /* Koloreak */
  --kolore-nagusia: #166534;
  --kolore-nagusia-iluna: #14532d;
  --kolore-nagusia-oso-argia: #f0fdf4;
  --kolore-nagusia-argia: #dcfce7;
  --kolore-nagusia-tainua: #bbf7d0;

  --testu-nagusia: #1f2937;
  --testu-bigarren: #374151;
  --testu-argia: #6b7280;

  --kolore-gorria: #dc2626;
  --kolore-gorria-iluna: #991b1b;
  --kolore-gorria-argia: #fee2e2;

  --kolore-urdina: #2563eb;
  --kolore-urdina-iluna: #1d4ed8;

  /* Letra-tipoak */
  --font-testua: "Inter", sans-serif;
  --font-izenburua: "Outfit", sans-serif;
}

/* --- UTILITATEAK --- */
.ezkutuan {
    display: none;
}

.inprimaki-inline {
    display: inline;
}

.link-botoi {
    text-decoration: none;
    display: inline-block;
}

.goiburu-ikono-txikia {
    font-size: 0.8em;
    margin-left: 5px;
}

/* --- RESET ETA OINARRIAK --- */
*,
*::before,
*::after {
  box-sizing: border-box;
}

html {
  scroll-behavior: smooth;
}

body {
  max-width: 100%;
  overflow-x: hidden;
  margin: 0;
  padding: 0;
}

.web-gorputza {
  background-image:
    linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7)),
    url("../irudiak/birtek1.jpeg");
  background-attachment: fixed;
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  color: var(--testu-nagusia);
  font-family: var(--font-testua);
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.eduki-nagusia {
  margin-top: 5rem; /* Mugikorretara adaptatuta */
  margin-bottom: 3rem;
  flex-grow: 1;
}

/* --- KONTAINERRAK --- */
.nab-edukiontzia {
  width: 100%;
  margin: 0 auto;
  padding: 0rem;
}

/* --- GOIBURUA (GLASSMORPHISM FONDO DESIENuA) --- */
.goiburu-nagusia {
  background-color: rgba(255, 255, 255, 0.85);
  -webkit-backdrop-filter: blur(12px);
  backdrop-filter: blur(12px);
  box-shadow: var(--itzala-md);
  border-bottom: 1px solid rgba(255, 255, 255, 0.3);
  position: fixed;
  width: 100%;
  top: 0;
  z-index: 100;
  transition: var(--trantsizioa);
}

.goiburu-barnealdea {
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 5rem; /* Mugikorretara adaptatuta */
  gap: 0.1rem;
  padding: 0 0.5rem;
}

.logo-edukiontzia {
  text-decoration: none;
  padding-left: 2%;
  padding-right: 2%;
  flex-shrink: 0;
  display: flex;
  align-items: center;
}

.goiburu-logo-irudia {
  height: 3.5rem;
  width: auto;
  object-fit: contain;
}

.logoa {
  font-size: 1.75rem; /* Mugikorretara adaptatuta(320px) */
  font-weight: 800;
  color: var(--kolore-nagusia);
  text-decoration: none;
  font-family: var(--font-izenburua);
  letter-spacing: -1px;
  transition: var(--trantsizioa);
  display: inline-block;
}

.logoa:hover {
  transform: scale(1.05);
  color: var(--kolore-nagusia-iluna);
}

.nab-menu-mahaigaina {
  display: none;
}

.nab-botoia {
  padding: 0.5rem 0.75rem;
  border-radius: 0.375rem;
  color: var(--testu-bigarren);
  text-decoration: none;
  transition: var(--trantsizioa);
  line-height: 1.5rem;
  font-weight: 500;
}

.nab-botoia:hover {
  background: var(--kolore-nagusia-argia);
  color: var(--kolore-nagusia);
}
.nab-botoia.aktibo {
  background: var(--kolore-nagusia-tainua);
  color: var(--kolore-nagusia-iluna);
  font-weight: 700;
}

.mugikor-menu-botoia {
  background: transparent;
  border: none;
  cursor: pointer;
  font-size: 1.75rem;
  color: var(--kolore-nagusia);
  padding: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: var(--trantsizioa);
  border-radius: 0.5rem;
}

.mugikor-menu-botoia:hover {
  background-color: var(--kolore-nagusia-argia);
  transform: scale(1.05);
}

.mugikor-menu-botoia:active {
  transform: scale(0.95);
}

.mugikor-menu-edukiontzia {
  display: none;
  padding-bottom: 1rem;
  flex-direction: column;
  gap: 0.5rem;
}
.mugikor-menu-edukiontzia.erakutsi {
  display: flex;
}

.nab-ekintzak {
  display: flex;
  flex-direction: column-reverse;
  justify-content: center;
  align-items: center;
  gap: 0.1rem;
  flex-shrink: 0;
}

.saioa-hasi-botoia {
  padding: 0.1rem 0.4rem;
  border: 1px solid #d1d5db;
  background: white;
  border-radius: 0.75rem;
  cursor: pointer;
  font-weight: 600;
  font-size: 0.85rem;
  font-family: var(--font-testua);
  color: var(--testu-bigarren);
  transition: var(--trantsizioa);
  line-height: 1.2;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  text-decoration: none;
  gap: 0.1rem;
  max-width: 130px; /* Mugikorretara adpatatuta (300px) */
}

.saioa-hasi-botoia span {
  display: none; /* Mobile first: pantaila txikian ezkutuan hasten da */
  white-space: normal;
  word-wrap: break-word;
  text-align: left;
}

.saioa-hasi-botoia:hover {
  background: #f9fafb;
  border-color: #9ca3af;
  transform: translateY(-1px);
  box-shadow: var(--itzala-sm);
}

.saioa-hasi-botoia:active {
  transform: scale(0.98);
}

.saioa-hasi-botoia.aktibo {
  background: var(--kolore-nagusia-tainua);
  color: var(--kolore-nagusia-iluna);
  border-color: var(--kolore-nagusia-iluna);
}

.saski-botoia {
  position: relative;
  background: transparent;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 0.2rem;
  color: var(--testu-bigarren);
  font-weight: 600;
  font-size: 0.85rem;
  font-family: var(--font-testua);
  padding: 0.3rem 0.4rem;
  transition: var(--trantsizioa);
}

.saski-botoia:hover {
  color: var(--kolore-nagusia);
  transform: scale(1.05);
}

.saski-kontagailua {
  position: absolute;
  top: -10px;
  right: 0px;
  background-color: #ff0000; 
  color: white;
  font-size: 0.75rem;
  font-weight: 800;
  padding: 2px 6px;
  border-radius: 9999px; /* pildora forma borobildua*/
  min-width: 18px;
  line-height: 1;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  z-index: 10;
  transition: transform 0.2s ease;
}

/* --- ANIMAZIOAK --- */
@keyframes saskiPop {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.3);
  }
  100% {
    transform: scale(1);
  }
}

.saski-pop-animazioa {
  animation: saskiPop 0.3s ease-out;
}

.saski-mezua {
  position: absolute;
  top: 100%;
  right: 0.5rem;
  background-color: var(--kolore-nagusia-iluna);
  color: white;
  padding: 0.4rem 0.8rem;
  border-radius: 0.5rem;
  font-size: 0.75rem;
  font-weight: 600;
  pointer-events: none;
  opacity: 0;
  transform: translateY(-10px);
  transition: all 0.3s ease;
  z-index: 1001;
  box-shadow: var(--itzala-md);
  white-space: nowrap;
}

.saski-mezua.erakutsi {
  opacity: 1;
  transform: translateY(5px);
}

/* --- OINA (FOOTER) --- */
.oin-nagusia {
  background-color: var(--kolore-nagusia);
  color: var(--kolore-nagusia-oso-argia);
  padding: 4rem 1rem 2rem;
  margin-top: auto;
}

.oin-sarea {
  display: grid;
  grid-template-columns: 1fr;
  gap: 3rem;
  max-width: 1280px;
  margin: 0 auto;
}

.oin-izenburua {
  font-size: 1.5rem;
  font-weight: 700;
  color: white;
  font-family: var(--font-izenburua);
}
.oin-goiburua {
  font-size: 1.125rem;
  font-weight: 700;
  color: white;
  font-family: var(--font-izenburua);
}
.oin-testua {
  color: var(--kolore-nagusia-tainua);
  font-size: 0.875rem;
  line-height: 1.6;
}

.oin-sozial-lerroa {
  display: flex;
  justify-content: center;
  gap: 1.5rem;
  font-size: 1.5rem;
  margin-bottom: 1.5rem;
}
.oin-sozial-lerroa a {
  color: white;
  text-decoration: none;
  transition: var(--trantsizioa);
}
.oin-sozial-lerroa a:hover {
  opacity: 0.8;
  transform: translateY(-2px);
}

.oin-whatsapp-botoia {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  background: white;
  color: var(--kolore-nagusia);
  padding: 0.6rem 1.25rem;
  border-radius: 9999px;
  font-weight: 700;
  text-decoration: none;
  transition: var(--trantsizioa);
  box-shadow: var(--itzala-md);
}
.oin-whatsapp-botoia:hover {
  transform: scale(1.05);
  box-shadow: var(--itzala-lg);
}

.oin-nab-zerrenda {
  list-style: none;
  padding: 0;
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}
.oin-lotura {
  color: var(--kolore-nagusia-tainua);
  text-decoration: none;
  transition: var(--trantsizioa);
}
.oin-lotura:hover {
  color: white;
  text-decoration: underline;
  padding-left: 0.5rem;
}

.oin-copyright {
  text-align: center;
  margin-top: 3rem;
  padding-top: 2rem;
  border-top: 1px solid rgba(255, 255, 255, 0.1);
  color: var(--kolore-nagusia-tainua);
  font-size: 0.875rem;
}

/* --- UTILITATEAK --- */
.testua-erdian {
  text-align: center;
}

/* --- SASKI DROPDOWN ESTILOAK --- */
.saski-edukiontzia {
  position: absolute;
  top: 100%;
  right: 0;
  width: 350px;
  background-color: white;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  border-radius: 8px;
  padding: 15px;
  z-index: 1000;
  border: 1px solid #e1e1e1;
}
.saski-elementua {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
  padding-bottom: 10px;
  border-bottom: 1px solid #f0f0f0;
}
.saski-elementu-informazioa h5 {
  margin: 0 0 5px 0;
  font-size: 14px;
}
.saski-elementu-informazioa p {
  margin: 0;
  font-size: 12px;
  color: #666;
}
.saski-kontrolak {
  display: flex;
  align-items: center;
  gap: 5px;
}
.saski-botoi-kop {
  background: #f0f0f0;
  border: none;
  width: 24px;
  height: 24px;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
}
.saski-botoi-kop:hover {
  background: #e0e0e0;
}

/* --- SASKIA GEHIGARRIAK --- */
#saski-edukia {
  display: none; /* Hasieran ezkutuan */
}
#saski-edukia h4 {
  margin-top: 0;
  border-bottom: 1px solid #eee;
  padding-bottom: 10px;
}
#saski-zerrenda p {
  text-align: center;
  color: #777;
}
.saski-guztira {
  border-top: 1px solid #eee;
  margin-top: 10px;
  padding-top: 10px;
  font-weight: bold;
  text-align: right;
}
.saski-ordaindu-botoia {
  width: 100%;
  margin-top: 10px;
  background-color: #28a745;
  color: white;
  border: none;
  padding: 10px;
  border-radius: 5px;
  cursor: pointer;
}

.saio-informazio-edukiontzia {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.erabiltzaile-izen-botoia {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  background-color: var(--kolore-nagusia-argia);
  color: var(--kolore-nagusia);
  border: 1px solid var(--kolore-nagusia);
  border-radius: 9999px;
  text-decoration: none;
  font-weight: 700;
  font-size: 0.9rem;
  transition: var(--trantsizioa);
}

.erabiltzaile-izen-botoia:hover {
  background-color: var(--kolore-nagusia-tainua);
  transform: translateY(-1px);
  box-shadow: var(--itzala-sm);
}

.saioa-itxi-botoia {
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: var(--kolore-gorria-argia);
  color: var(--kolore-gorria-iluna);
  border: 1px solid var(--kolore-gorria);
  border-radius: 12px;
  text-decoration: none;
  font-size: 0.9rem;
  width: fit-content;
  margin: 2rem auto;
  padding: 0.75rem 2rem;
  cursor: pointer;
  transition: var(--trantsizioa);
}

.saioa-itxi-botoia:hover {
  background-color: var(--kolore-gorria);
  color: white;
  transform: translateY(-2px);
}

/* --- GOIBURUA DROPDOWN (ERABILTZAILE MENUA) --- */
.erabiltzaile-dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-edukia {
  display: none;
  position: absolute;
  right: 0;
  top: 100%;
  background-color: white;
  min-width: 200px;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
  z-index: 1000;
  border-radius: 0.5rem;
  border: 1px solid #e5e7eb;
  overflow: hidden;
  margin-top: 0.5rem;
  animation: fadeIn 0.2s ease-out;
}

.erabiltzaile-dropdown.irekita .dropdown-edukia {
  display: block;
}

.erabiltzaile-dropdown .fa-chevron-down {
  transition: transform 0.3s ease;
}

.erabiltzaile-dropdown.irekita .fa-chevron-down {
  transform: rotate(180deg);
}

.dropdown-elementua {
  color: var(--testu-bigarren);
  padding: 12px 16px;
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 0.9rem;
  font-weight: 500;
  transition: background-color 0.2s;
}

.dropdown-elementua:hover {
  background-color: #f3f4f6;
  color: var(--kolore-nagusia);
}

.dropdown-elementua.gorria {
  color: var(--kolore-gorria);
  border-top: 1px solid #f3f4f6;
}

.dropdown-elementua.gorria:hover {
  background-color: #fef2f2;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* --- MUGIKORRAREN USER CONTAINER --- */
.mugikor-erabiltzaile-edukiontzia {
  display: flex;
  flex-direction: column;
  margin-top: 1rem;
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  overflow: hidden;
  background-color: white;
}

.mugikor-erabiltzaile-link {
  color: var(--kolore-nagusia);
}

.mugikor-saioa-itxi-botoia {
  color: var(--kolore-gorria);
  border: none;
  background: none;
  text-align: left;
  padding: 10px 1.5rem;
  cursor: pointer;
  width: 100%;
  font-weight: 600;
  transition: var(--trantsizioa);
}

#mugikor-saioa-itxi-botoia:hover {
  background-color: var(--kolore-gorria-argia);
}

/* --- MODAL ESTILOAK (BIRTEK PHP EREDUITIK) --- */
.modala-geruza {
  display: none;
  position: fixed;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.6);
  z-index: 1050;
  align-items: center; /* Zentroan agertzeko */
  justify-content: center;
}
.modala-geruza.erakutsi {
  display: flex;
}
.modala-edukiontzia {
  background-color: white;
  border-radius: var(--erradioa);
  width: 100%;
  max-width: 28rem;
  display: flex;
  flex-direction: column;
  max-height: 90vh;
  overflow-y: auto;
  padding: 0;
  box-shadow: var(--itzala-lg);
  transform: scale(0.95);
}

.modala-goiburua {
  padding: 1rem;
  border-bottom: 1px solid #eee;
  display: flex;
  justify-content: center;
  align-items: center;
  position: relative;
}
.modal-goiburua h3 {
  font-size: 1.25rem;
  font-weight: 700;
  margin: 0;
  font-family: var(--font-izenburua);
}
.modala-itxi-botoia {
  background: none;
  border: none;
  font-size: 2rem;
  cursor: pointer;
  line-height: 1;
  color: var(--kolore-gorria);
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  transition: var(--trantsizioa);
}
.modala-itxi-botoia:hover {
  color: var(--kolore-gorria-iluna);
  transform: translateY(-50%) scale(1.1);
}

/* Saskia Modal barnekoak */
.saski-elementu-lerroa {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 0;
  border-bottom: 1px solid #eee;
  gap: 0.5rem;
}
.saski-elementu-informazioa {
  flex-grow: 1;
  min-width: 200px;
}
.saski-elementu-izena {
  font-weight: bold;
}
.saski-elementu-xehetasun {
  font-size: 0.85rem;
  color: #666;
}
/* --- SASKI MODALA TAULA (BERRESKURATZEA) --- */
.saski-taula {
  width: 100%;
  border-collapse: collapse;
}

.saski-taula th {
  text-align: left;
  padding: 0.75rem 0.5rem;
  border-bottom: 2px solid #f3f4f6;
  color: #6b7280;
  font-size: 0.8rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.saski-taula td {
  padding: 0.6rem 0.1rem;
  border-bottom: 1px solid #f3f4f6;
  vertical-align: middle;
}

.saski-taula tr:hover {
  background-color: #f9fafb;
}

.saski-ezabatu-txikia {
  background: none;
  border: none;
  color: #9ca3af;
  font-size: 1.25rem;
  cursor: pointer;
  padding: 0.25rem;
  line-height: 1;
  transition: color 0.2s;
}

.saski-ezabatu-txikia:hover {
  color: var(--kolore-gorria);
}

.saski-hutsik {
  text-align: center;
  padding: 2rem 0;
  color: #9ca3af;
  font-style: italic;
}

/* Kantitate aldatzeko botoiak (Saskia) */
.kopuru-kontrola-td {
  display: flex !important;
  align-items: center;
  justify-content: center;
}
.kop-mod-btn {
  width: 24px;
  height: 24px;
  border-radius: 4px;
  border: 1px solid #d1d5db;
  background: white;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  font-size: 1.1rem;
  transition: all 0.2s;
  color: var(--kolore-nagusia);
}
.kop-mod-btn:hover {
  background-color: var(--kolore-nagusia-argia);
  border-color: var(--kolore-nagusia);
}
.kopuru-balioa {
  min-width: 20px;
  text-align: center;
  font-weight: 700;
  font-size: 1rem;
}

.saski-oina {
  padding: 1.5rem;
  border-top: 1px solid #f3f4f6;
  background: #f9fafb;
}

.saski-guztira-lerroa {
  display: flex;
  justify-content: space-between;
  font-size: 1.25rem;
  font-weight: 800;
  margin-bottom: 1.5rem;
  color: #111827;
}

.botoi-zabalera-osoa-top {
  width: 100%;
  margin-bottom: 0.75rem;
  padding: 1rem;
  font-size: 1.1rem;
}

.saski-itxi-zentratua {
  display: block;
  width: 100%;
  padding: 0.8rem;
  background: white;
  border: 1px solid #d1d5db;
  color: #4b5563;
  border-radius: 0.75rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  text-align: center;
}

.saski-itxi-zentratua:hover {
  background-color: #f3f4f6;
  border-color: #9ca3af;
  color: #111827;
}

/* --- HERO BOTOIAK (AZALA) --- */
.azal-botoi-taldea {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
  margin-top: 2rem;
}
.azal-ekintza-botoia {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  background-color: var(--kolore-nagusia);
  color: white;
  font-size: 1.25rem;
  font-weight: 800;
  padding: 1rem 2.5rem;
  border-radius: 9999px;
  border: 3px solid var(--kolore-nagusia-tainua);
  cursor: pointer;
  box-shadow: var(--itzala-lg);
  transition: var(--trantsizioa);
  text-decoration: none;
}
.azal-cta-botoia {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  color: white;
  font-size: 1.1rem;
  font-weight: 800;
  padding: 1rem 2rem;
  border-radius: 9999px;
  cursor: pointer;
  box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.2);
  transition: var(--trantsizioa);
  text-decoration: none;
  border: 3px solid rgba(255, 255, 255, 0.3);
}

.azal-cta-botoia:hover {
  transform: translateY(-3px) scale(1.02);
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
}
.azal-cta-botoia:active {
  transform: translateY(-1px);
}

.azal-botoi-erosi {
  background-color: var(--kolore-urdina);
  border-color: #93c5fd;
}
.azal-botoi-erosi:hover {
  background-color: var(--kolore-urdina-iluna);
  border-color: white;
}

.azal-botoi-birziklatu {
  background-color: var(--kolore-nagusia);
  border-color: var(--kolore-nagusia-tainua);
}
.azal-botoi-birziklatu:hover {
  background-color: var(--kolore-nagusia-iluna);
  border-color: white;
}

/* Botoi nagusi orokorra (modaletan erabiltzen da) */
.botoia {
  padding: 0.5rem 1rem;
  border-radius: 0.375rem;
  font-weight: 600;
  color: white;
  border: none;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  justify-content: center;
}
.botoi-nagusia {
  background-color: var(--kolore-nagusia-iluna);
}
.botoi-nagusia:hover {
  background-color: var(--kolore-nagusia);
}
.botoi-zabalera-osoa-top {
  width: 100%;
  margin-top: 0.5rem;
}

/* --- EDUKIAK / PRODUKTU ESTEKA --- */

.produktu-esteka {
  color: var(--kolore-urdina);
  text-decoration: none;
  font-weight: 600;
  transition: var(--trantsizioa);
}

.produktu-esteka:hover {
  color: var(--kolore-urdina-iluna);
  text-decoration: underline;
}

/* HORNITZAILEA LOGEATUTA ESTILOA (Globala, saioa itxi arte) */

.nab-botoia.hornitzailea-aktibo {
  background-color: var(--kolore-urdina);
  color: white;
  font-weight: bold;
  border: 1px solid var(--kolore-urdina-iluna);
}
.nab-botoia.hornitzailea-aktibo:hover {
  background-color: var(--kolore-urdina-iluna);
}

/* --- MUGIKORRAREN EDUKIONTZIA --- */
.mugikor-erabiltzaile-edukiontzia {
  display: flex;
  flex-direction: column;
  margin-top: 1rem;
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  overflow: hidden;
  background-color: white;
}

.mugikor-erabiltzaile-edukiontzia .nab-botoia {
  border-radius: 0;
  margin: 0;
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1rem;
  gap: 0.5rem;
}

.mugikor-erabiltzaile-link {
  background-color: #f9fafb;
  font-weight: 600;
  color: #374151;
}

/* Logout utilidade klaseak */
.botoi-gorria {
  background: var(--kolore-gorria-argia);
  color: var(--kolore-gorria-iluna);
  border-color: var(--kolore-gorria);
}

.mugikor-logout-botoia {
  color: var(--kolore-gorria-iluna);
  background: var(--kolore-gorria-argia);
  border-top: 1px solid #fecaca;
}

.ongietorri-kutxa {
  grid-column: 1 / -1;
  padding: 2rem;
}

.ezkutuan {
  display: none;
}

.erakutsi-inline-flex {
  display: inline-flex !important;
}

.erakutsi-flex {
  display: flex !important;
}

.erakutsi-block {
  display: block !important;
}

.ezkutatu {
  display: none !important;
}

.mugikor-logout-botoia-berezian {
  width: calc(100% - 3rem) !important;
  margin: 0.5rem 1.5rem !important;
  text-align: left !important;
}

.testua-eskuinera {
  text-align: right;
}

.testua-zentratuta {
  text-align: center;
}

.zabalera-osoa-grid {
  grid-column: 1 / -1;
}

.flex-zentratua-gap {
  display: flex;
  gap: 10px;
  justify-content: center;
  align-items: center;
}

.irudi-estandar-panoramikoa {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-radius: 1rem;
}

.zabalera-osoa {
  width: 100%;
}

.nab-ikonoa {
  color: #4b5563;
  font-size: 1.25rem;
  padding: 0.5rem;
  transition: color 0.3s ease;
}

.nab-ikonoa:hover {
  color: #166534;
}

.erabiltzaile-izena {
  display: flex;
  align-items: center;
  gap: 0.4rem;
  font-weight: 500;
  color: #374151;
}

.saskia-edukiontzia {
  position: relative;
  cursor: pointer;
  padding: 0.5rem;
}

.saskia-ikonoa {
  font-size: 1.5rem;
  color: #374151;
}

.testua-txikia {
  font-size: 0.875rem;
}

.testua-grisa {
  color: #6b7280;
}

.flex-gap-1 {
  display: flex;
  gap: 1rem;
}

.flex-zentratuta {
  display: flex;
  justify-content: center;
  align-items: center;
}

.pantaila-osoa-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
}

.ez-klikagarria {
  cursor: not-allowed;
  opacity: 0.6;
}

.testu-apala {
  color: #6b7280;
  font-size: 0.9rem;
}

.atzerako-botoia {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  color: #4b5563;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.3s ease;
}

.atzerako-botoia:hover {
  color: #166534;
}

.erosketa-sareta {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
  max-width: 1200px;
  margin: 0 auto;
}

/* --- EROSKETA ARRAKASTA MEZUA --- */
.arrakasta-edukiontzia {
  text-align: center;
  padding: 3rem 2rem;
  background: white;
  border-radius: var(--erradioa);
  box-shadow: var(--itzala-lg);
  max-width: 600px;
  margin: 2rem auto;
}

.arrakasta-ikonoa {
  font-size: 5rem;
  color: #10b981; /* arrakasta berdea */
  margin-bottom: 1.5rem;
  animation: arrakastaSartu 0.5s ease-out;
}

@keyframes arrakastaSartu {
  from {
    transform: scale(0.5);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}

.arrakasta-titulua {
  font-family: var(--font-izenburua);
  font-size: 2rem;
  color: var(--kolore-nagusia-iluna);
  margin-bottom: 1rem;
}

.arrakasta-testua {
  font-size: 1.1rem;
  color: var(--testu-bigarren);
  margin-bottom: 2.5rem;
  line-height: 1.6;
  max-width: 500px;
  margin-left: auto;
  margin-right: auto;
}

.arrakasta-ekintzak {
  display: flex;
  gap: 1rem;
  justify-content: center;
  flex-wrap: wrap;
}
.arrakasta-mezua {
  background: #d4edda;
  color: #155724;
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 1.5rem;
  text-align: center;
}

.arrakasta-ekintzak .botoia {
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  text-decoration: none;
  font-weight: 600;
  transition: var(--trantsizioa);
}

.botoi-sekundarioa {
  background-color: #4b5563;
  color: white;
}

.botoi-sekundarioa:hover {
  background-color: #374151;
  transform: translateY(-2px);
}

.oin-logo-irudia {
  width: 80px;
  height: auto;
  margin-top: 0.5rem;
  margin-bottom: 1rem;
  background-color: white; /* Barruan txuria mantentzeko */
  border-radius: 12px; /* Kanpoko ertza biribiltzeko eta "fusionatzeko" */
  padding: 4px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

/* Login errore mezua */
.login-errore-mezua {
  color: #d9534f;
  background-color: #f2dede;
  padding: 0.3rem;
  margin-bottom: 1rem;
  border-radius: 10px;
  border: 1px solid #ebccd1;
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.ongietorri-kutxa-osagarria {
  grid-column: 1 / -1;
  padding: 2rem;
}

.ordainketa-info-kutxa {
  text-align: center;
}

.ordainketa-ikonoa {
  font-size: 5rem;
  color: #16a34a;
  margin-bottom: 20px;
}

.ordainketa-izenburua {
  margin-bottom: 15px;
}

.ordainketa-testua {
  font-size: 1.1rem;
  color: #4b5563;
  margin-bottom: 30px;
}

.botoi-taldea-zentratuta {
  display: flex;
  gap: 10px;
  justify-content: center;
}

.botoi-grisa {
  background-color: #4b5563;
}

.zutabe-osoa {
  grid-column: 1 / -1;
}

.testu-eskubian {
  text-align: right;
}

.label-flex {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.alert-berdea {
  background: #d4edda;
  color: #155724;
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 20px;
}

/* --- MEZUA UTILITATEAK --- */
.mezua-kutxa-arrakasta {
  padding: 2rem;
  text-align: center;
}

.mezua-ikonoa-arrakasta {
  color: #28a745;
  margin-bottom: 1rem;
}

.mezua-titulua-arrakasta {
  color: #333;
}


/* --- GORA JOAN BOTOIA --- */
#gora-joan-botoia {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  width: 3.5rem;
  height: 3.5rem;
  background-color: var(--kolore-nagusia);
  color: white;
  border-radius: 50%;
  border: 2px solid var(--kolore-nagusia-argia);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  cursor: pointer;
  z-index: 999;
  opacity: 0;
  visibility: hidden;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow:
    0 10px 15px -3px rgba(0, 0, 0, 0.1),
    0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

#gora-joan-botoia.erakutsi {
  opacity: 1;
  visibility: visible;
  transform: translateY(0);
}

#gora-joan-botoia:not(.erakutsi) {
  transform: translateY(20px);
}

#gora-joan-botoia:hover {
  background-color: var(--kolore-nagusia-iluna);
  transform: scale(1.1) translateY(-5px);
  box-shadow:
    0 20px 25px -5px rgba(0, 0, 0, 0.1),
    0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* --- BOTOI HOBEKUNTZAK (DISTIRA EFEKTUA) --- */
.azal-cta-botoia {
  position: relative;
  overflow: hidden;
}

.azal-cta-botoia::after {
  content: "";
  position: absolute;
  top: -50%;
  left: -60%;
  width: 20%;
  height: 200%;
  background: rgba(255, 255, 255, 0.2);
  transform: rotate(30deg);
  transition: none;
}

.azal-cta-botoia:hover::after {
  left: 120%;
  transition: all 0.6s ease-in-out;
}

.azal-cta-botoia i {
  transition: transform 0.3s ease;
}

.azal-cta-botoia:hover i {
  transform: scale(1.2) rotate(-10deg);
}


/* ========================================================================= */
/* --- MEDIA QUERI-ak--- */
/* ========================================================================= */

@media (min-width: 400px) {
  .nab-ekintzak {
    flex-direction: row;
    justify-content: flex-end;
    gap: 0.4rem;
  }
  .saioa-hasi-botoia span {
    display: inline-block;
  }
  .saski-kontagailu-txapa {
    position: absolute;
    top: -18px;
    right: 20px;
  }
  .saski-botoia span:not(.saski-kontagailu-txapa) {
    display: inline;
  }
}

@media (min-width: 710px) {
  .nab-menu-mahaigaina {
    gap: 0.5rem;
    font-size: 1rem;
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    max-height: 4.7rem; /* (Padding 0.3*2 + Line-height 1.5) * 2 + Gap 0.5 */
    overflow: hidden;
  }
  .nab-botoia {
    padding: 0.3rem 0.5rem;
  }
  .mugikor-menu-botoia {
    display: none;
  }
  .mugikor-menu-edukiontzia.erakutsi,
  .mugikor-menu-edukiontzia {
    display: none;
  }
}

/* 768px tik gora zabaltzen denean */
@media (min-width: 768px) {
  .eduki-nagusia {
    margin-top: 7rem;
    margin-bottom: 4rem;
  }
  .goiburu-barnealdea {
    height: 7rem;
    gap: 0.2rem;
    padding: 0;
  }
  .logoa {
    font-size: 2.5rem;
  }
  .saioa-hasi-botoia {
    font-size: 0.95rem;
    padding: 0.4rem 0.8rem;
    max-width: 200px;
  }
  .saski-botoia {
    font-size: 1.1rem;
    margin-right: 1rem;
  }

  .oin-sarea {
    grid-template-columns: repeat(3, 1fr);
  }
  .md\:text-left {
    text-align: left;
  }
  .md\:text-right {
    text-align: right;
  }
}

@media (min-width: 900px) {
  .erosketa-sareta {
    grid-template-columns: 3fr 2fr;
  }
}

@media (min-width: 1170px) {
  .logoa {
    font-size: 3.5rem;
  }
}

``n
### css/estiloak_hasiera.css
``css
/* --- BIRTEK - HASIERA ORRIA -- */

/* HASIERA ORRIKO ELEMENTUAK */
.azal-kaxa {
  background-color: rgba(255, 255, 255, 0.8);
  border-radius: 2.5rem;
  box-shadow: 0 8px 32px 0 rgba(22, 101, 52, 0.1);
  padding: 4rem 2rem;
  position: relative;
  overflow: hidden;
  text-align: center;
  margin-top: 2rem;
  margin-bottom: 4rem;
  border: 1px solid rgba(255, 255, 255, 0.4);
  -webkit-backdrop-filter: blur(15px);
  backdrop-filter: blur(15px);
  transition: transform 0.4s ease, box-shadow 0.4s ease;
}

.azal-kaxa:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 45px 0 rgba(22, 101, 52, 0.15);
}

.azal-kaxa::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--kolore-nagusia), var(--kolore-nagusia-tainua), var(--kolore-nagusia));
  background-size: 200% auto;
  animation: distira-ertza 3s linear infinite;
}

@keyframes distira-ertza {
  0% { background-position: 0% center; }
  100% { background-position: 200% center; }
}

.azal-izenburua {
  font-size: 1.75rem; 
  font-weight: 800;
  margin-bottom: 1.5rem;
  text-align: center;
  color: var(--kolore-nagusia);
  font-family: var(--font-izenburua);
  text-transform: uppercase;
  letter-spacing: 1px;
}

.azal-azpititulua {
  color: var(--testu-argia);
  font-size: 1.25rem;
  max-width: 800px;
  margin: 0 auto;
}

.azal-ekintza-botoia {
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  background-color: var(--kolore-nagusia);
  color: white;
  font-size: 1.25rem;
  font-weight: 800;
  padding: 1.25rem 3rem;
  border-radius: 9999px;
  margin-top: 2.5rem;
  border: none;
  cursor: pointer;
  text-decoration: none;
  transition: var(--trantsizioa);
}

.azal-ekintza-botoia:hover {
  background-color: var(--kolore-nagusia-iluna);
  transform: translateY(-3px);
  box-shadow: 0 10px 20px rgba(22, 101, 52, 0.2);
}

.hasiera-azal-irudia {
  width: 100%;
  max-width: 1000px;
  height: auto;
  aspect-ratio: 21/9;
  object-fit: cover;
  border-radius: 2rem;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
  margin-top: 2.5rem;
  margin-bottom: 2.5rem;
  transition: transform 0.5s ease;
  border: 4px solid white;
}

.hasiera-azal-irudia:hover {
  transform: scale(1.02);
}

.hasiera-zerrenda-edukiontzia {
  display: flex;
  flex-direction: column;
  gap: 4rem;
  margin-top: 3rem;
}

.hasiera-txartel-lerroa {
  display: flex;
  flex-direction: column;
  gap: 2rem;
  background-color: white;
  padding: 2rem;
  border-radius: 1.5rem;
  box-shadow: var(--itzala-md);
  border: 1px solid rgba(0, 0, 0, 0.05);
  align-items: center;
  transition: var(--trantsizioa);
}

.hasiera-txartel-lerroa:hover {
  transform: translateY(-5px);
  box-shadow: var(--itzala-lg);
}

.hasiera-zutabea {
  width: 100%;
}

.hasiera-txartel-irudia {
  width: 100%;
  height: auto;
  aspect-ratio: 16/9;
  object-fit: cover;
  border-radius: 1rem;
}

.hasiera-txartel-izenburua {
  font-size: 1.75rem;
  font-weight: 700;
  color: var(--kolore-nagusia);
  margin-bottom: 1rem;
  font-family: var(--font-izenburua);
}

.hasiera-txartel-testua {
  line-height: 1.6;
  color: var(--testu-bigarren);
}

/* SLIDER-a */
.slider-kanpo-edukiontzia {
  display: flex;
  max-width: 1280px;
  width: 100%;
  margin: 4rem auto;
  padding: 20px;
  justify-content: center;
}

.hasiera-slider-egitura img {
  width: 100%;
  height: 400px;
  object-fit: cover;
  border-radius: 1.5rem;
}

.bx-wrapper {
  border: none !important;
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1) !important;
  border-radius: 1.5rem;
}

/* =========================================
   MEDIA QUERIES
   ========================================= */

@media (min-width: 768px) {
  .azal-kaxa {
    padding: 3rem 1rem;
    margin-top: 2rem;
    margin-bottom: 4rem;
  }

  .azal-izenburua {
    font-size: 3rem;
    letter-spacing: 2px;
    margin-bottom: 2rem;
  }

  .hasiera-txartel-lerroa {
    flex-direction: row;
  }

  .hasiera-zutabea {
    width: 50%;
  }
}

@media (min-width: 1024px) {
  .azal-izenburua {
    font-size: 4rem;
  }

  .hasiera-slider-egitura img {
    height: 600px;
  }
}

``n
### css/estiloak_hornitzaile_menua.css
``css
/* --- HORNITZAILE MENUA  --- */

.ongi-etorri-mezua {
  text-align: center;
  font-family: "Outfit", sans-serif;
  font-size: 2rem;
  color: #166534;
  margin-bottom: 2rem;
  margin-top: 2rem;
}

.menu-hornitzailea {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1.5rem;
  max-width: 1000px;
  margin: 1.5rem auto 3rem auto;
  padding: 0 1.5rem;
}

.menu-txartela {
  background: white;
  border-radius: 1.5rem;
  padding: 2.5rem 2rem;
  text-align: center;
  text-decoration: none;
  color: var(--testu-nagusia);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  border: 1px solid rgba(0, 0, 0, 0.05);
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1.5rem;
}

.menu-txartela:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.1);
  border-color: var(--kolore-nagusia-tainua);
}

.menu-ikonoa {
  font-size: 3.5rem;
  color: var(--kolore-nagusia);
  margin-bottom: 0;
}

.menu-izenburua {
  font-size: 1.4rem;
  font-weight: 700;
  font-family: var(--font-izenburua);
}

.saioa-itxi-botoia-gorria {
  display: block;
  margin: 2rem auto;
  padding: 0.75rem 2rem;
  background-color: #ef4444;
  color: white;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s;
  text-decoration: none;
}

.saioa-itxi-botoia-gorria:hover {
  background-color: #dc2626;
}

/* --- SARRERA EGIN orria --- */
.inprimaki-trukatu {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
  justify-content: center;
}

.trukatu-botoia {
  padding: 0.75rem 1.5rem;
  border: 1px solid #ddd;
  border-radius: 8px;
  cursor: pointer;
  background: #f9f9f9;
  transition: all 0.3s;
}

.trukatu-botoia.aktibo {
  background: #166534;
  color: white;
  border-color: #166534;
}

.ezkutuan {
  display: none;
}

.produktu-hautatzailea {
  width: 100%;
  padding: 1rem;
  border: 1px solid #e5e7eb;
  border-radius: 1rem;
  background: #f9fafb;
}

.sarrera-edukiontzia {
  max-width: 800px;
  margin: 0 auto;
}

/* Mobile First Grid-a */
.sarrera-sareta-berria {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1rem;
}

.inprimaki-bidali-botoia {
  width: 100%;
  margin-top: 2rem;
}

.atzera-esteka-edukiontzia {
  text-align: center;
  margin-top: 1.5rem;
}

.atzera-esteka-estiloa {
  color: #666;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
}

.atzera-esteka-estiloa:hover {
  color: #166534;
}

/* Mezu dinamikoak*/
.mezu-kutxa {
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 1.5rem;
  text-align: center;
}

.mezu-errorea {
  background: #f8d7da;
  color: #721c24;
}

.mezu-arrakasta {
  background: #d4edda;
  color: #155724;
}

.sarrera-kopuru-edukiontzia {
  margin-top: 1rem;
}

/* --- SARRERAK KUDEATU STYLES --- */
.sarrera-taula {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.sarrera-buru-tr {
  background: #f8f9fa;
  border-bottom: 2px solid #dee2e6;
}

.sarrera-th {
  padding: 15px;
  text-align: left;
}

.sarrera-th-zentratua {
  padding: 15px;
  text-align: center;
}

.sarrera-gorputz-tr {
  border-bottom: 1px solid #eee;
}

.sarrera-td {
  padding: 15px;
}

.sarrera-td-zentratua {
  padding: 15px;
  text-align: center;
}

.egoera-Bidean {
  background-color: #ffeeba;
  color: #856404;
  padding: 3px 8px;
  border-radius: 4px;
  font-weight: bold;
}

.egoera-Jasota {
  background-color: #d4edda;
  color: #155724;
  padding: 3px 8px;
  border-radius: 4px;
  font-weight: bold;
}

/* =========================================
   MEDIA QUERIES
   ========================================= */

@media (min-width: 640px) {
  .menu-hornitzailea {
    grid-template-columns: repeat(2, 1fr);
    gap: 2rem;
  }
}

@media (min-width: 768px) {
  .sarrera-sareta-berria {
    grid-template-columns: 1fr 1fr;
  }
}

@media (min-width: 1024px) {
  .menu-hornitzailea {
    grid-template-columns: repeat(3, 1fr);
  }
}

/* Taula - (Responsive) */
@media (max-width: 768px) {
  .sarrera-taula,
  .sarrera-taula thead,
  .sarrera-taula tbody,
  .sarrera-taula th,
  .sarrera-taula td,
  .sarrera-taula tr {
    display: block;
  }

  .sarrera-taula thead tr {
    position: absolute;
    top: -9999px;
    left: -9999px;
  }

  .sarrera-gorputz-tr {
    margin-bottom: 1.5rem;
    border: 1px solid #ddd;
    border-radius: 12px;
    padding: 1rem;
    background: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
  }

  .sarrera-td,
  .sarrera-td-zentratua {
    border: none;
    position: relative;
    padding: 0.75rem 0 0.75rem 55% !important;
    text-align: left !important;
  }

  .sarrera-td::before,
  .sarrera-td-zentratua::before {
    position: absolute;
    left: 0;
    width: 50%;
    padding-right: 10px;
    white-space: normal;
    font-weight: bold;
    color: #4b5563;
  }


  /* hornitzaile_sarrerak_kudeatu.php orriko div blokeak txukun agertzeko*/
  .sarrera-gorputz-tr td:nth-of-type(1)::before {
    content: "Data:";
  }
  .sarrera-gorputz-tr td:nth-of-type(2)::before {
    content: "Produktua:";
  }
  .sarrera-gorputz-tr td:nth-of-type(3)::before {
    content: "Kantitatea:";
  }
  .sarrera-gorputz-tr td:nth-of-type(4)::before {
    content: "Bidalketa Egoera:";
  }
  .sarrera-gorputz-tr td:nth-of-type(5)::before {
    content: "Ekintzak:";
  }
}

``n
### css/estiloak_kontaktua.css
``css
/* --- BIRTEK - KONTAKTUA --- */

.kontaktu-edukiontzia {
  margin: 0 auto; /*zentratua agertzeko*/
  text-align: center;
  max-width: 1280px;
  padding-left: 1rem; /*ertzetatik pixka bat banatzeko*/
  padding-right: 1rem;
}

.kontaktu-sareta {
  display: grid;
  grid-template-columns: minmax(
    0,
    1fr
  ); /* GARRANTZITSUA: Grid pistak edukiaren tamainaren azpitik txikitzea ahalbidetzen du */
  gap: 3rem;
  align-items: start;
}

.inprimaki-kutxa {
  background-color: white;
  padding: 1rem; /* 250px bezalako pantaila ultra-txikietarako optimizatua */
  border-radius: 1.5rem;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
  border: 1px solid rgba(0, 0, 0, 0.05);
  width: 80%; /* Mugikor txikietan estuagoa izateko */
  max-width: 500px; /* Constrained on tablet/desktop */
  margin: 0 auto; /* zentratuta */
  min-width: 0; /* GARRANTZITSUA: Grid elementua edukiaren tamainaren azpitik txikitzea ahalbidetzen du */
}

.inprimaki-titulua {
  font-size: 1.5rem;
  font-weight: 800;
  margin-bottom: 2rem;
  color: #166534;
  font-family: "Outfit", sans-serif;
  text-align: center;
}

.kontaktu-inprimaki-diseinua {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  width: 100%; /* Zabalera osoa behartu */
  min-width: 0; /* Flex edukiontzia txikitzea ahalbidetu */
}

.inprimaki-sarrera,
.inprimaki-hautatu {
  width: 100%;
  padding: 1rem;
  border: 1px solid #e5e7eb;
  border-radius: 1rem;
  outline: none;
  background-color: #f9fafb;
  transition: border-color 0.2s;
  min-width: 0; /* Sarrerek gainezka egin ordez txikitzea ziurtatzen du */
  box-sizing: border-box; /* Padding-a zabaleran sartuta dagoela ziurtatu */
  max-width: 100%; /* Gurasotik gainezka egitea ekidin */
}

.inprimaki-sarrera:focus {
  border-color: #166534;
}

.sozial-kutxa {
  background-color: white;
  padding: 1rem; /* 250px-rako murriztua */
  border-radius: 1.5rem;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
  border: 1px solid rgba(0, 0, 0, 0.05);
  text-align: center;
  width: 92%;
  max-width: 500px;
  margin: 0 auto;
  min-width: 0;
}

.sozial-azpititulua {
  font-size: 1.25rem;
  font-weight: 700;
  margin-bottom: 1.5rem;
  color: #374151;
  font-family: "Outfit", sans-serif;
}

.footer-wa-botoia {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
  background-color: #25d366;
  color: white;
  padding: 1rem 2rem;
  border-radius: 9999px;
  font-weight: 700;
  width: 100%;
  margin-bottom: 2rem;
  text-decoration: none;
  transition: transform 0.2s;
}

.footer-wa-botoia:hover {
  transform: scale(1.02);
}

.sozial-ikono-lerroa {
  display: flex;
  justify-content: center;
  gap: 1rem; /* 250px-tan sartzeko murriztua */
  font-size: 1.75rem; /* Pixka bat murriztua */
  flex-wrap: wrap; /* Pantaila txikietan lerroz aldatzea ahalbidetu */
}

.fb-kolorea {
  color: #1877f2;
}
.ig-kolorea {
  color: #e4405f;
}
.tw-kolorea {
  color: #000000; /* X (Twitter) beltza */
}
.tik-kolorea {
  color: #000000;
}
.tg-kolorea {
  color: #0088cc;
}

.mapa-edukiontzia {
  height: 100%;
  min-height: 400px;
  border-radius: 1.5rem;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
  width: 92%;
  max-width: 500px;
  margin: 0 auto;
}

.mapa-iframe {
  width: 100%;
  height: 100%;
  border: 0;
}

.kontaktua-titulua {
  font-size: 1.75rem; /* 320px-rako murriztua */
  font-weight: 800;
  margin-bottom: 2rem;
  text-align: center;
  font-family: "Outfit", sans-serif;
  text-transform: uppercase;
  color: #166534;
  word-wrap: break-word;
}

.botoia {
  padding: 1rem 2rem;
  border-radius: 1rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
  border: none;
}

.botoi-nagusia {
  background-color: #166534;
  color: white;
}

.botoi-nagusia:hover {
  background-color: #14532d;
  transform: translateY(-2px);
}

/* =========================================
   Langileak Orriko Estiloak
   ========================================= */
.langileak-edukiontzia {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem 1rem;
}

.birtek-java-ap-botoia-kanpo {
  display: flex;
  justify-content: center;
  margin-bottom: 3rem;
}

.birtek-java-ap-botoia {
  background-color: #2563eb; /* Urdina */
  color: white;
  font-size: 1rem;
  font-weight: 500;
  padding: 0.2rem 1rem;
  border-radius: 1rem;
  border: none;
  cursor: pointer;
  box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
  font-family: "Outfit", sans-serif;
  transition: all 0.3s ease;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 1rem;
}

.birtek-java-ap-botoia:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.4);
  background-color: #1d4ed8;
}

/* =========================================
   Erabilgarritasun Klaseak (Utility Classes)
   ========================================= */
.testua-zentratuta {
  text-align: center;
}

.tartea-behean-2 {
  margin-bottom: 2rem;
}

.tartea-behean-1-5 {
  margin-bottom: 1.5rem;
}

.testua-grisa {
  color: #6b7280;
}

.sareta-2-zutabe {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.zabalera-osoa {
  width: 100%;
}

.tartea-goian-1 {
  margin-top: 1rem;
}

.botoia-tartea-goian {
  margin-top: 1rem;
}

.input-padding {
  padding: 10px;
}

.pasahitza-ahaztu-esteka {
  color: #166534;
  text-decoration: none;
  font-size: 0.9rem;
}

.esteka-berdea-osoa {
  background: #bbf7d0;
  color: #14532d;
  border-color: #14532d;
}

.label-input-fitxategia {
  font-weight: 500;
  margin-bottom: 0.5rem;
  display: block;
  color: #374151;
}

.testua-txikia {
  font-size: 0.75rem;
}

/* ---  (Datuak Aldatu estiloetatik hartuta) --- */
.inprimaki-sareta {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px;
}

.inprimaki-atal-izenburua {
  grid-column: 1 / -1;
  border-bottom: 2px solid #e5e7eb;
  margin-top: 1.5rem;
  margin-bottom: 0.5rem;
  padding-bottom: 5px;
  color: #166534;
  font-size: 1.1rem;
  font-weight: 700;
}

.kontaktu-irudia {
  width: 100%;
  height: 200px;
  object-fit: cover;
  border-radius: 1rem;
  margin-bottom: 2rem;
}

.cv-igoera-sarrera {
  padding: 10px;
}

/* =========================================
   MEDIA QUERIES
   ========================================= */

@media (min-width: 480px) {
  .inprimaki-kutxa,
  .sozial-kutxa {
    padding: 2.5rem;
  }
}

@media (max-width: 600px) {
  .inprimaki-sareta {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 640px) {
  .sareta-2-zutabe {
    grid-template-columns: 1fr;
  }

  .birtek-java-ap-botoia {
    width: 100%;
    justify-content: center;
    padding: 0.5rem 1rem;
    font-size: 1rem;
  }
}

@media (min-width: 768px) {
  .kontaktu-sareta {
    grid-template-columns: 1fr 1fr;
  }

  .sozial-kutxa {
    grid-column: 1/2;
    grid-row: 2/3;
  }

  .mapa-edukiontzia {
    grid-column: 2/3;
    grid-row: 1/3;
  }

  .kontaktua-titulua {
    font-size: 2.25rem;
    margin-bottom: 3rem;
  }
}

@media (max-width: 320px) {
  .kontaktu-edukiontzia {
    padding-left: 0.25rem;
    padding-right: 0.25rem;
  }

  .inprimaki-kutxa,
  .sozial-kutxa,
  .mapa-edukiontzia {
    width: 100%;
    padding: 1rem 0.5rem; /* 320px-rako murriztua */
    border-radius: 1rem;
    max-width: 100%; 
  }

  .mapa-edukiontzia {
    min-height: 250px;
  }

  .inprimaki-sarrera {
    padding: 0.5rem;
  }

  .inprimaki-titulua,
  .sozial-azpititulua {
    font-size: 1.25rem;
  }

  .sozial-ikono-lerroa {
    gap: 0.5rem;
    font-size: 1.5rem;
  }

  .footer-wa-botoia {
    padding: 0.75rem 1rem;
    font-size: 0.9rem;
  }

  .kontaktua-titulua {
    font-size: 1.5rem;
  }
}

``n
### css/estiloak_ordainketa.css
``css
.ordainketa-kutxa {
  max-width: 600px;
  margin: 50px auto;
  padding: 30px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
  text-align: left;
}
.ordainketa-goiburua {
  text-align: center;
  margin-bottom: 30px;
}
.ordainketa-goiburua i {
  font-size: 3rem;
  color: var(--primarioa);
  margin-bottom: 10px;
}
.inprimaki-taldea {
  margin-bottom: 20px;
}
.inprimaki-taldea label {
  display: block;
  margin-bottom: 8px;
  font-weight: bold;
}
.inprimaki-taldea input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  font-size: 1rem;
}
.erabiltzaile-informazioa {
  background-color: #f8f9fa;
  padding: 15px;
  border-radius: 5px;
  margin-bottom: 20px;
  border-left: 4px solid var(--primarioa);
}
.botoi-edukiontzia {
  text-align: center;
  margin-top: 30px;
}
.saskia-laburpena {
  margin-bottom: 20px;
  padding: 15px;
  border: 1px solid #eee;
  border-radius: 5px;
}
.saskia-totala {
  font-weight: bold;
  text-align: right;
  font-size: 1.2rem;
  margin-top: 10px;
}

.botoi-desgaitua {
  opacity: 0.5;
  cursor: not-allowed;
}

``n
### css/estiloak_produktu_xehetasunak.css
``css
.xehetasunak-edukiontzia {
  max-width: 1200px;
  margin: 2rem auto;
  padding: 1rem 0.5rem;
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
}

.xehetasunak-irudia-container {
  background: white;
  padding: 1rem 0.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  display: flex;
  align-items: center;
  justify-content: center;
}
.xehetasunak-irudia {
  max-width: 100%;
  height: auto;
  max-height: 500px;
  object-fit: contain;
}
.xehetasunak-info {
  background: white;
  padding: 1rem;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
.produktu-izenburua {
  font-size: 2rem;
  font-weight: 700;
  color: #1f2937;
  margin: 0;
  font-family: "Outfit", sans-serif;
}
.produktu-meta {
  display: flex;
  gap: 1rem;
  color: #666;
  font-size: 0.9rem;
  flex-wrap: wrap;
}
.meta-etiketa {
  background: #f3f4f6;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
}
.produktu-prezioa {
  font-size: 2.5rem;
  font-weight: 700;
  color: #166534;
  margin: 1rem 0;
}
.produktu-deskribapena {
  line-height: 1.6;
  color: #4b5563;
}
.stock-info {
  font-weight: 600;
  margin-bottom: 1rem;
}
.stock-bai {
  color: #166534;
}
.stock-ez {
  color: #dc2626;
}

.ekintza-eremua {
  margin-top: auto;
  padding-top: 2rem;
  border-top: 1px solid #e5e7eb;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
.produktu-kantitatea-aldatu {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.kantitate-input {
  width: 60px;
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  font-size: 1.1rem;
  text-align: center;
  -moz-appearance: textfield;
  -webkit-appearance: none;
  appearance: none;
}
.kantitate-input::-webkit-outer-spin-button,
.kantitate-input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
.kantitate-btn {
  background-color: #f3f4f6;
  border: 1px solid #d1d5db;
  color: #374151;
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 0.375rem;
  font-size: 1.25rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.2s;
}
.kantitate-btn:hover {
  background-color: #e5e7eb;
}

/* Hasieran inline ziren estiloak */
.xehetasunak-errorea {
  text-align: center;
  padding: 4rem;
}
.botoia-itzuli-xehetasunak {
  margin-top: 1rem;
  display: inline-block;
}
.saskiratu-xehetasunak-botoia {
  width: 100%;
  padding: 1rem;
  font-size: 1.1rem;
}
.xehetasunak-agortuta-botoia {
  background: #9ca3af;
  cursor: not-allowed;
  width: 100%;
  padding: 1rem;
}

/* Teknika xehetasunak kutxa */
.xehetasun-teknikoak-kutxa {
  background: #f9fafb;
  padding: 0.5rem;
  border-radius: 8px;
  margin-top: 10px;
}

.xehetasun-teknikoak-izenburua {
  margin-top: 0;
}

.xehetasun-teknikoak-zerrenda {
  list-style: none;
  padding: 0;
  margin: 0;
  font-size: 0.9rem;
  color: #4b5563;
}

.xehetasun-teknikoak-item {
  margin-bottom: 4px;
}

.prezioa-eskaintza {
  font-size: 1rem;
  color: #dc2626;
  text-decoration: line-through;
  margin-left: 10px;
}

.kantitate-label {
  margin-right: 0.5rem;
}

/* =========================================
   MEDIA QUERIES
   ========================================= */

@media (min-width: 768px) {
  .xehetasunak-edukiontzia {
    grid-template-columns: 1fr 1fr;
  }
}

``n
### css/estiloak_produktuak.css
``css
/* --- BIRTEK - PRODUKTUAK --- */

/* PRODUKTU ORRIAKO ELEMENTUAK */
.produktuak-titulua {
  font-size: 2.25rem; /* Lehenik mugikorra */
  margin-bottom: 0.5rem;
  text-align: center;
  color: #166534;
  font-family: "Outfit", sans-serif;
  text-transform: uppercase;
  word-wrap: break-word;
}

.produktuak-orria {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
  max-width: 1280px;
  margin: 0 auto;
}

.produktu-kopuru-info {
  text-align: center;
  margin-bottom: 2rem;
  font-size: 1.1rem;
  color: #4b5563;
  font-weight: 500;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.75rem;
}

.kopurua-txapa {
  background-color: var(--kolore-nagusia);
  color: white;
  font-weight: 800;
  font-size: 0.9rem;
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 2.5rem;
}

.alboko-barra {
  width: 100%;
}

.eduki-zutabea {
  width: 100%;
  flex-grow: 1;
}

.iragazki-kaxa {
  background: white;
  padding: 1rem;
  border-radius: 1.5rem;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
  border: 1px solid rgba(0, 0, 0, 0.05);
  position: sticky;
  top: 10rem;
}

.iragazki-izenburua {
  font-weight: 800;
  font-size: 1.25rem;
  margin-bottom: 0; 
  display: flex;
  align-items: center;
  gap: 0.75rem;
}


.iragazki-goiburua {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.5rem;
  cursor: pointer; 
}


.iragazki-edukia {
  display: none;
}

.iragazki-edukia.erakutsi {
  display: block;
}


.inprimaki-sarrera,
.inprimaki-hautatu {
  width: 100%;
  padding: 0.75rem 1rem;
  border: 1px solid #e5e7eb;
  border-radius: 0.75rem;
  outline: none;
  background-color: #f9fafb;
  transition: border-color 0.2s;
}

.inprimaki-sarrera:focus,
.inprimaki-hautatu:focus {
  border-color: #166534;
}

.berrezarri-testua {
  font-size: 0.875rem;
  color: #ef4444;
  background: none;
  border: none;
  cursor: pointer;
  font-weight: 600;
}
.berrezarri-botoia {
  text-align: right;
}


.iragazkiak-berrezarri:hover {
  text-decoration: underline;
}

.iragazki-etiketa {
  font-size: 0.9rem;
  font-weight: 600;
  color: #374151;
  display: block;
}


.prezio-iragazki-taldea {
  display: flex;
  gap: 5px;
}

.zabaler-erdi {
  width: 50%;
}

/* Radio botoiak prezioa ordenatzeko*/
.prezio-ordenatu-radio {
  display: flex;
  flex-direction: column;
  gap: 0.2rem;
}

.radio-etiketa {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.9rem;
  font-weight: 500;
  color: #374151;
  cursor: pointer;
}

.radio-etiketa input[type="radio"] {
  cursor: pointer;
  accent-color: #166534;
}

.radio-etiketa:hover {
  color: #166534;
}

.klikagarria {
  cursor: pointer;
}

/* PRODUKTU SAREA */
.produktu-sarea {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
}

.produktu-txartela {
  background: white;
  border: 1px solid rgba(0, 0, 0, 0.05);
  border-radius: 1.5rem;
  overflow: hidden;
  display: flex;
  flex-direction: column;
  transition:
    transform 0.3s ease,
    box-shadow 0.3s ease;
}

.produktu-txartela:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 30px -10px rgba(0, 0, 0, 0.1);
}

.txartel-irudia {
  position: relative;
  height: 15rem;
  width: 100%;
}

.txartel-irudia img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.txartel-edukia {
  padding: 1rem; 
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}

.txartel-izenburua {
  font-size: 1.25rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
  font-family: "Outfit", sans-serif;
}

.txartel-azalpena {
  font-size: 0.9rem;
  color: #6b7280;
  margin-bottom: 1rem;
  line-height: 1.5;
}

.txartel-oina {
  margin-top: auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.txartel-prezioa {
  font-size: 1.25rem; 
  font-weight: 700;
  color: #166534;
}

.produktua-saskiratu-botoia {
  background-color: #166534;
  color: white;
  padding: 0.6rem 1rem;
  border-radius: 9999px;
  border: none;
  cursor: pointer;
  font-weight: 600;
}

.produktua-saskiratu-botoia:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.produktua-ikusi-botoia {
  color: #166534;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 0.9rem;
  font-weight: 600;
}

.txartel-info-lerroa {
  display: flex;
  justify-content: space-between;
  margin-bottom: 0.75rem;
  font-size: 0.8rem;
}

.txartel-marka {
  font-weight: 600;
  color: #4b5563;
}
.txartel-stock {
  font-weight: 700;
  color: #16a34a;
}
.txartel-stock-agortuta {
  font-weight: 700;
  color: #dc2626;
}

.txartel-kategoria-txapa {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: rgba(0, 0, 0, 0.7);
  color: white;
  padding: 0.3rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.7rem;
  font-weight: 800;
  text-transform: uppercase;
}

/* --- FEEDBACK MEZUA --- */
.nab-ekintzak {
  position: relative;
}

.saskiratze-mezua {
  display: none; /* Hasieran ezkutuan */
  position: absolute;
  top: 110%; /* Botoiaren azpian */
  right: 0%; /*saski botoiaren parean*/
  width: max-content;
  background-color: #dcfce7; /* Berde argia */
  color: #166534; /* Berde iluna */
  padding: 0.2rem 0.6rem;
  border-radius: 0.5rem;
  border: 1px solid #166534;
  font-weight: 600;
  font-size: 0.9rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  z-index: 1000;
  pointer-events: none; /* Ez molestatzeko */
}

/* ========================================================================= */
/* --- MEDIA QUERIES (AT THE END) --- */
/* ========================================================================= */

@media (min-width: 640px) {
  .produktu-sarea {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 768px) {
  .produktuak-titulua {
    font-size: 1.75rem;
  }
}

@media (min-width: 1024px) {
  .produktuak-orria {
    flex-direction: row;
  }
  .alboko-barra {
    width: 280px;
    flex-shrink: 0;
  }
  .iragazki-edukia {
    display: block;
  }
  .iragazki-goiburua {
    cursor: pointer;
  }
}

@media (min-width: 1200px) {
  .produktu-sarea {
    grid-template-columns: repeat(3, 1fr);
  }
}

``n
## JS Fitxategiak

### js/bezero_erosketa.js
``javascript
$(document).ready(function () {
  renderErosketaSaskia();

  // Saskia hutsik badago inprimakia bidaltzea ekidin
  $("#bidalketa-form").on("submit", function (e) {
    const saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
    if (saskia.length === 0) {
      e.preventDefault();
      alert("Saskia hutsik dago!");
    }
  });

  // KONTROLENTZAKO GERTAERA-ENTZULEAK
  $(document).on("click", ".kopuru-plus", function (e) {
    e.preventDefault(); // Botoiak inprimakia bidaltzea ekidin (inprimaki barruan badago)
    const id = $(this).data("id");
    aldatuKantitatea(id, 1);
  });

  $(document).on("click", ".kopuru-minus", function (e) {
    e.preventDefault();
    const id = $(this).data("id");
    aldatuKantitatea(id, -1);
  });

  $(document).on("click", ".item-ezabatu", function (e) {
    e.preventDefault();
    const id = $(this).data("id");
    ezabatuItem(id);
  });
});

function renderErosketaSaskia() {
  const saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
  const $container = $("#erosketa-saski-container");
  let totala = 0;

  $container.empty();

  if (saskia.length === 0) {
    $container.html(
      '<p class="saskia-hutsik-mezua">Ez duzu produkturik aukeratu.</p>'
    );
    $("#erosketa-guztira").text("0.00 €");
    $('button[type="submit"]')
      .prop("disabled", true)
      .addClass("botoi-desgaitua");
    return;
  } else {
    $('button[type="submit"]')
      .prop("disabled", false)
      .removeClass("botoi-desgaitua");
  }

  /* Taularen egitura */
  let tableHtml = `
    <div class="taula-edukiontzia-scroll">
    <table class="lerro-taula">
        <thead>
            <tr class="lerro-taula-izenburua">
                <th class="testua-ezkerrean">Produktua</th>
                <th class="testua-zentratuta">Kantitatea</th>
                <th class="testua-eskuinera">Prezioa</th>
                <th class="testua-eskuinera">Guztira</th>
                <th class="testua-zentratuta">Ekintzak</th>
            </tr>
        </thead>
        <tbody>
  `;

  /* forEach erabili $.each-en ordez */
  saskia.forEach((item) => {
    const subtotala = item.prezioa * item.kantitatea;
    totala += subtotala;

    tableHtml += `
        <tr>
            <td>
                <strong>${item.izena}</strong>
            </td>
            <td class="testua-zentratuta">
                <div class="kopuru-kontrola-lerroa">
                    <button class="kopuru-btn kopuru-minus" data-id="${item.id}">-</button>
                    <span class="kopuru-kontrola-balioa">${item.kantitatea}</span>
                    <button class="kopuru-btn kopuru-plus" data-id="${item.id}">+</button>
                </div>
            </td>
            <td class="testua-eskuinera">${item.prezioa.toFixed(2)} €</td>
            <td class="testua-eskuinera prezio-nabarmena">${subtotala.toFixed(2)} €</td>
            <td class="testua-zentratuta">
                <button class="ezabatu-btn-gorria item-ezabatu" data-id="${item.id}" title="Ezabatu">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;
  });

  tableHtml += `
        </tbody>
    </table>
    </div>
  `;

  $container.html(tableHtml);
  $("#erosketa-guztira").text(totala.toFixed(2) + " €");
}

function aldatuKantitatea(id, change) {
  const saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
  
  const item = saskia.find((i) => i.id == id);

  if (item) {
    const newQty = item.kantitatea + change;

    // Stock egiaztapena
    if (newQty > item.stock) {
      alert("Ez dago stock nahikorik gehiago gehitzeko.");
      return;
    }

    if (newQty > 0) {
      item.kantitatea = newQty;
      window.saskiaGorde(saskia); // LocalStorage + Goiburuko Kontagailua eguneratu
      renderErosketaSaskia();
    } else {
      ezabatuItem(id);
    }
  }
}

function ezabatuItem(id) {
  const saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
  
  const filtered = saskia.filter((i) => i.id != id);
  window.saskiaGorde(filtered);
  renderErosketaSaskia();
}

``n
### js/bezero_saioa_hasi.js
``javascript
$(document).ready(function () {
  // --- ERREGISTRO FORMULARIOAREN BALIDAZIOA ---
  $(document).on("submit", "#bezero-erregistro-form, #hornitzaile-erregistro-form", function (e) {
    var pasahitza = $(this).find('input[name="pasahitza_erregistroa"]').val();
    var errorea = "";

    // 1. Luzera egiaztatu
    if (pasahitza.length < 8) {
      errorea = "Pasahitzak gutxienez 8 karaktere izan behar ditu.";
    }
    // 2. Karakter berezia egiaztatu
    else if (!/[!@#$%^&*(),.?":{}|<>]/.test(pasahitza)) {
      errorea = "Pasahitzak gutxienez karakter berezi bat izan behar du (!@#$%^&*...).";
    }

    // 3. NAN/IFZ luzera egiaztatu (9 karaktere)
    var nan = $(this).find('input[name="nan"]').val();
    if (nan && nan.length !== 9) {
      errorea = "NAN/IFZ zenbakiak 9 karaktere izan behar ditu.";
    }

    if (errorea !== "") {
      e.preventDefault();
      alert(errorea);
    }
  });

  // --- PASAHITZA AHAZTU DUZU? ---
  $(".pasahitza-ahaztu-esteka").on("click", function (e) {
    e.preventDefault();

    var emaila = $('input[name="emaila"]').val();

    if (!emaila) {
      alert("Mesedez, sartu zure posta elektronikoa lehenik.");
      return;
    }

    $.ajax({
      url: "lortu_pasahitza_bezeroa.php",
      method: "POST",
      data: { emaila: emaila },
      success: function (response) {
        alert(response);
      },
      error: function () {
        alert("Errore bat gertatu da pasahitza berreskuratzean.");
      }
    });
  });
});

``n
### js/globala.js
``javascript
/* --- JavaScript jQuery .JS --- */

$(document).ready(function () {


  // --- SASKI MODALA INJEKZIOA ---
  if ($("#saski-modala").length === 0) {
    var saskiHtml = `
    <div id="saski-modala" class="modala-geruza">
      <div class="modala-edukiontzia">
        <div class="modala-goiburua">
          <h3>Saskia</h3>
          <button class="modala-itxi-botoia" id="modal-itxi-botoia-x">&times;</button>
        </div>

        <div id="saski-elementu-zerrenda" class="modala-edukia taula-scroll">
          <!-- JS bidez beteko da -->
        </div>
        <div class="saski-oina">
          <div class="saski-guztira-lerroa">
            <span>Guztira:</span><span id="saski-guztira">0.00 €</span>
          </div>
          <button id="erosketa-burutu-botoia" class="botoia botoi-nagusia botoi-zabalera-osoa-top">
            Erosketa Burutu
          </button>
          <button class="saski-itxi-zentratua" id="modal-itxi-botoia-behea">
            Jarraitu Erosketak Egiten
          </button>
        </div>
      </div>
    </div>
    `;
    $("body").append(saskiHtml);
  }


  // --- MUGIKOR MENU LOGIKA ---
  $(document).on("click", "#mugikor-menu-botoia", function () {
    $("#mugikor-menua").toggleClass("erakutsi");
  });

  // --- SASKIAREN LOGIKA (GLOBAL) ---

  // 1. Gorde saskia LocalStoragen eta eguneratu GUI
  window.saskiaGorde = function (saskia) {
    localStorage.setItem("birtek_saskia", JSON.stringify(saskia));
    window.saskiaEguneratuKontagailua();
  };

  // 2. Gehitu elementu bat saskiara
  window.saskiaGehitu = function (id, izena, prezioa, stock, $botoia) {
    var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
    var badago = saskia.find((item) => item.id == id);

    if (badago) {
      if (badago.kantitatea < stock) {
        badago.kantitatea++;
      } else {
        alert("Ezin da gehiago gehitu, stock-a agortu da.");
        return;
      }
    } else {
      saskia.push({
        id: id,
        izena: izena,
        prezioa: prezioa,
        kantitatea: 1,
        stock: stock,
      });
    }

    window.saskiaGorde(saskia);

    // 1. Jakinarazpen mezua (#saski-mezua)
    var $mezua = $("#saski-mezua");
    if ($mezua.length > 0) {
      $mezua.text("Saskira gehituta!").addClass("erakutsi");
      setTimeout(function () {
        $mezua.removeClass("erakutsi");
      }, 2000);
    }

    // 2. Botoiaren Pop Animazioa
    if ($botoia) {
      $botoia.addClass("saski-pop-animazioa");
      setTimeout(function () {
        $botoia.removeClass("saski-pop-animazioa");
      }, 300);
    }
  };

  // 3. Erakutsi saskiaren edukia modalean
  window.saskiaErakutsi = function () {
    var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
    var $zerrenda = $("#saski-elementu-zerrenda");
    var $guztira = $("#saski-guztira");
    var guztiraPrezioa = 0;

    $zerrenda.empty();

    if (saskia.length === 0) {
      $zerrenda.html("<p class='saskia-hutsik'>Saskia hutsik dago.</p>");
    } else {
      var taulaHtml = `
        <table class="saski-taula">
          <thead>
            <tr>
              <th>Produktua</th>
              <th>Kop</th>
              <th>Prezioa</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
      `;

      $.each(saskia, function (index, item) {
        var azpiTotala = item.prezioa * item.kantitatea;
        guztiraPrezioa += azpiTotala;
        taulaHtml += `
          <tr>
            <td>${item.izena}</td>
            <td class="kopuru-kontrola-td">
              <button class="kop-mod-btn minus" data-id="${item.id}" data-aldatu="-1">-</button>
              <span class="kopuru-balioa">${item.kantitatea}</span>
              <button class="kop-mod-btn plus" data-id="${item.id}" data-aldatu="1">+</button>
            </td>
            <td>${azpiTotala.toFixed(2)}€</td>
            <td><button class="saski-ezabatu-txikia" data-id="${item.id}">&times;</button></td>
          </tr>
        `;
      });

      taulaHtml += "</tbody></table>";
      $zerrenda.append(taulaHtml);
    }

    $guztira.text(guztiraPrezioa.toFixed(2) + " €");
  };

  // 4. Eguneratu saski kontagailua (txapa)
  window.saskiaEguneratuKontagailua = function () {
    var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
    var kopuruTotala = 0;
    $.each(saskia, function (index, item) {
      kopuruTotala += item.kantitatea;
    });
    $(".saski-kontagailua").text(kopuruTotala);
  };

  // 4.1. Animatu saski kontagailua (soilik "Saskiratu" egitean deitzeko)
  window.saskiaAnimatuKontagailua = function () {
    var $txapa = $(".saski-kontagailua");
    $txapa.addClass("saski-pop-animazioa");
    setTimeout(function () {
      $txapa.removeClass("saski-pop-animazioa");
    }, 300);
  };

  // 5. Ezabatu elementu bat
  window.saskiaEzabatu = function (id) {
    var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
    var berria = saskia.filter((item) => item.id != id);
    window.saskiaGorde(berria);
    window.saskiaErakutsi();
  };

  // 6. Aldatu kantitatea (+/-)
  window.saskiaAldatuKantitatea = function (id, aldatu) {
    var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
    var item = saskia.find((i) => i.id == id);
    if (item) {
      var nKop = item.kantitatea + aldatu;

      // Stock konprobazioa
      if (aldatu > 0 && nKop > item.stock) {
        alert("Ezin da gehiago gehitu, stock-a agortu da.");
        return;
      }

      if (nKop > 0) {
        item.kantitatea = nKop;
      } else {
        saskia = saskia.filter((i) => i.id != id);
      }
      window.saskiaGorde(saskia);
      window.saskiaErakutsi();
    }
  };

  // --- SASKI INTERAKZIOAK (GERTAERA-ENTZULEAK) ---

  // Ireki saskia
  $(document).on("click", "#saski-botoia-toggle", function () {
    window.saskiaErakutsi();
    $("#saski-modala").fadeIn(200).css("display", "flex"); // Flex beharrezkoa da zentratzeko
  });

  // Itxi saskia
  $(document).on(
    "click",
    "#modal-itxi-botoia-x, #modal-itxi-botoia-behea",
    function () {
      $("#saski-modala").fadeOut(200);
    },
  );

  // Itxi kanpoan klik egitean
  $(document).on("click", "#saski-modala", function (e) {
    if ($(e.target).hasClass("modala-geruza")) {
      $(this).fadeOut(200);
    }
  });

  // Erosketa burutu (Redirekzioa)
  $(document).on("click", "#erosketa-burutu-botoia", function () {
    var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
    if (saskia.length === 0) {
      alert("Saskia hutsik dago!");
      return;
    }
    window.location.href = "bezero_erosketa.php";
  });

  // Saskiko kantitatea aldatu botoiak
  $(document).on("click", ".kop-mod-btn", function () {
    var id = $(this).data("id");
    var aldatu = parseInt($(this).data("aldatu"));
    window.saskiaAldatuKantitatea(id, aldatu);
  });

  // Saskiko ezabatu botoia
  $(document).on("click", ".saski-ezabatu-txikia", function () {
    var id = $(this).data("id");
    window.saskiaEzabatu(id);
  });

  // Hasieratu kontagailua orria kargatzean
  window.saskiaEguneratuKontagailua();

  // --- ERABILTZAILEAREN DROPDOWN LOGIKA ---
  $(document).on("click", ".erabiltzaile-dropdown .saioa-hasi-botoia", function (e) {
    if ($(e.target).closest(".fa-chevron-down").length > 0) {
      e.preventDefault();
      $(this).parent(".erabiltzaile-dropdown").toggleClass("irekita");
    }
  });

  // Itxi erabiltzaile dropdown-a kanpoan klik egitean
  $(document).on("click", function (e) {
    if (!$(e.target).closest(".erabiltzaile-dropdown").length) {
      $(".erabiltzaile-dropdown").removeClass("irekita");
    }
  });

  // --- SAIOA ITXI LOGIKA ---
  $(document).on(
    "click",
    "#logout-botoia-menua, .saioa-itxi-botoia, .mugikor-logout-botoia, .saio-informazio-edukiontzia .botoi-gorria, .saioa-itxi-botoia-dropdown",
    function (e) {
      e.preventDefault();
      if (confirm("Ziur zaude saioa itxi nahi duzula?")) {
        window.location.href = "logout.php";
      }
    }
  );

  // --- INPRIMAKIEN BAIEZTAPEN OROKORRA ---
  $(document).on("submit", ".form-baieztatu", function (e) {
    var mezua = $(this).data("mezua") || "Ziur zaude aurrera egin nahi duzula?";
    if (!confirm(mezua)) {
      e.preventDefault();
    }
  });

  // --- GORA JOAN BOTOIAREN LOGIKA ---
  var $goraJoanBotoia = $("#gora-joan-botoia");

  $(window).scroll(function () {
    if ($(window).scrollTop() > 300) {
      $goraJoanBotoia.addClass("erakutsi");
    } else {
      $goraJoanBotoia.removeClass("erakutsi");
    }
  });

  $goraJoanBotoia.on("click", function () {
    $("html, body").animate({ scrollTop: 0 }, 600);
    return false;
  });
});



``n
### js/hasiera.js
``javascript
$(document).ready(function () {
  // SLIDER KONFIGURAZIOA
  $(".hasiera-slider-egitura").bxSlider({
    mode: "horizontal", // Horizontala izan dadin
    autoplay: true,
    captions: true,
    auto: true,
    /*autoControls: true, */
    stopAutoOnClick: true,
    pager: true,
    speed: 500,
    pause: 4000,
    /* slideWidth: 600,   <-- HAU KENDU DUGU RESPONSIVEA IZATEKO */
    adaptiveHeight: true,
  });
});


``n
### js/hornitzaile_saioa_hasi.js
``javascript
$(document).ready(function () {
    // --- ERREGISTRO FORMULARIOAREN BALIDAZIOA ---
    $(document).on("submit", "#hornitzaile-erregistro-form", function (e) {
        var pasahitza = $(this).find('input[name="pasahitza_erregistroa"]').val();
        var errorea = "";

        // 1. Luzera egiaztatu
        if (pasahitza.length < 8) {
            errorea = "Pasahitzak gutxienez 8 karaktere izan behar ditu.";
        }
        // 2. Karakter berezia egiaztatu
        else if (!/[!@#$%^&*(),.?":{}|<>]/.test(pasahitza)) {
            errorea = "Pasahitzak gutxienez karakter berezi bat izan behar du (!@#$%^&*...).";
        }

        // 3. NAN/IFZ luzera egiaztatu (9 karaktere)
        var nan = $(this).find('input[name="nan"]').val();
        if (nan && nan.length !== 9) {
            errorea = "NAN/IFZ zenbakiak 9 karaktere izan behar ditu.";
        }

        if (errorea !== "") {
            e.preventDefault();
            alert(errorea);
        }
    });

    // --- PASAHITZA AHAZTU DUZU? ---
    $(".pasahitza-ahaztu-esteka").on("click", function (e) {
        e.preventDefault();

        var emaila = $('input[name="emaila"]').val();

        if (!emaila) {
            alert("Mesedez, sartu zure posta elektronikoa lehenik.");
            return;
        }

        $.ajax({
            url: "lortu_pasahitza_hornitzailea.php",
            method: "POST",
            data: { emaila: emaila },
            success: function (erantzuna) {
                alert(erantzuna);
            },
            error: function () {
                alert("Errore bat gertatu da pasahitza berreskuratzean.");
            }
        });
    });

    // --- HERRIA AUKERATU (DATUAK ALDATU ORRIAN) ---
    $('#herria_id').on('change', function () {
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

``n
### js/langileak_menua.js
``javascript
/* --- Langileak Menua Logika Espezifikoa --- */
$(document).ready(function () {
  // Java aplikazioa abiarazteko botoia
  $(".birtek-java-ap-botoia").click(function (e) {
    // Deskarga botoia bada, utzi normal funtzionatzen
    if ($(this).attr("download") !== undefined) {
      return;
    }

    e.preventDefault();
    var botoia = $(this);
    var jatorrizkoEdukia = botoia.html(); // .php() konponduta .html()-ra

    // Feedback bisuala eman
    botoia.html('<i class="fas fa-cog fa-spin"></i> Abiarazten...');
    botoia.prop("disabled", true);

    
    // PHP script-a deitu
    $.ajax({
      url: "../php/java_app_abiarazi.php",
      type: "GET",
      success: function (response) {
        console.log("Java aplikazioaren abiarazte erantzuna: " + response);
        // Botoia berrezarri 3 segundu barru
        setTimeout(function () {
          botoia.html(jatorrizkoEdukia);
          botoia.prop("disabled", false);
        }, 3000);
      },
      error: function (xhr, status, error) {
        console.error("Abiarazte errorea:", error);
        alert(
          "Errorea aplikazioa abiaraztean. Ziurtatu XAMPP martxan dagoela."
        );
        botoia.html(jatorrizkoEdukia);
        botoia.prop("disabled", false);
      },
    });
  });

  // Eskaera formularioaren bidalketa arrakasta mezua(AJAX)
  $("#langile-eskaera-inprimakia").on("submit", function (e) {
    e.preventDefault();
    var $form = $(this);
    var formData = new FormData(this);
    var $submitBtn = $form.find('button[type="submit"]');

    // Desgaitu botoia klick egitean eta "Bidaltzen..." jarri
    $submitBtn.prop("disabled", true).text("Bidaltzen...");

    $.ajax({
      url: "../php/gorde_eskaera_langilea.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        // Formularioa garbitu eta mezua erakutsi
        $(".inprimaki-kutxa").html(
          '<div class="mezua-kutxa-arrakasta">' +
            '<i class="fas fa-check-circle fa-4x mezua-ikonoa-arrakasta"></i>' +
            '<h3 class="mezua-titulua-arrakasta">Eskaera bidalita, eskerrikasko!</h3>' +
            '<p class="testua-grisa tartea-goian-1">Laster jarriko gara zurekin harremanetan.</p>' +
            '<button class="botoia botoi-nagusia tartea-goian-2" onclick="location.reload()">Itzuli</button>' +
            "</div>"
        );
      },
      error: function (xhr, status, error) {
        console.error("Errorea:", error);
        alert("Errorea gertatu da eskaera bidaltzean. Mesedez, saiatu berriro.");
        $submitBtn.prop("disabled", false).text("Eskaera Bidali");
      },
    });
  });
});

``n
### js/ordainketa.js
``javascript
$(document).ready(function () {
  kargatuSaskia();
});

function kargatuSaskia() {
  var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
  var $edukiontzia = $("#saskia-xehetasunak");

  if (saskia.length === 0) {
    $edukiontzia.html(
      "<p>Saskia hutsik dago. <a href='produktuak.php'>Itzuli dendara</a></p>",
    );
    $(".botoi-edukiontzia button")
      .prop("disabled", true)
      .addClass("botoi-desgaitua");
    return;
  }

  var html = "<h4>Erosketaren Laburpena:</h4><ul>";
  var totala = 0;

  $.each(saskia, function (index, prezioa) {
    var prezioTotala = prezioa.prezioa * prezioa.kantitatea;
    totala += prezioTotala;
    html +=
      "<li>" +
      prezioa.kantitatea +
      "x " +
      prezioa.izena +
      " - " +
      prezioa.prezioa +
      "€/unit (" +
      prezioTotala.toFixed(2) +
      "€)</li>";
  });

  html += "</ul>";
  html +=
    "<div class='saskia-totala'>Guztira: " + totala.toFixed(2) + " €</div>";

  $edukiontzia.html(html);
}

function burutuErosketa() {
  // Balidazio sinplea
  var titularra = $("#titularra").val();
  var txartela = $("#txartela").val();

  if (!titularra || !txartela) {
    alert("Mesedez, bete eremu guztiak.");
    return;
  }

  var botoia = $(".botoi-edukiontzia button");
  botoia.html('<i class="fas fa-spinner fa-spin"></i> Prozesatzen...');
  botoia.prop("disabled", true);

  var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];

  /* AJAX deia **/
  $.ajax({
    url: "prozesatu_erosketa.php",
    type: "POST",
    contentType: "application/json",
    data: JSON.stringify({ saskia: saskia }),
    success: function (response) {
      if (response.success) {
        // Erosketa 'burutu' ondo
        localStorage.removeItem("birtek_saskia");
        localStorage.removeItem("birtek_saski_kopurua");

        // Eguneratu saski kontagailua (globala.js-ko funtzioa)
        if (typeof window.saskiKontagailuaEguneratu === "function") {
          window.saskiKontagailuaEguneratu();
        }

        $(".ordainketa-kutxa").fadeOut(300, function () {
          $(this)
            .html(
              `
            <div class="arrakasta-edukiontzia">
                <div class="arrakasta-ikonoa">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h2 class="arrakasta-titulua">Erosketa Burutu da!</h2>
                <p class="arrakasta-testua">
                    Eskerrik asko zure erosketagatik. Zure eskaera ondo erregistratu da eta prozesatzen ari gara.
                </p>
                <div class="arrakasta-ekintzak">
                    <a href="bezero_eskaerak.php" class="botoia botoi-sekundarioa">Erosketak Kudeatu</a>
                    <a href="hasiera.php" class="botoia botoi-nagusia">Itzuli Hasierara</a>
                </div>
            </div>
          `,
            )
            .fadeIn(300);
        });
      } else {
        alert(
          "Errorea erosketa burutzean: " + (response.message || "Ezezaguna"),
        );
        botoia.html("Ordaindu eta Erosketa Burutu");
        botoia.prop("disabled", false);
      }
    },
    error: function () {
      alert("Errorea zerbitzarira konektatzean.");
      botoia.html("Ordaindu eta Erosketa Burutu");
      botoia.prop("disabled", false);
    },
  });
}

``n
### js/produktuak.js
``javascript
var produktuGuztiak = []; // Produktu guztiak gordetzeko (filtratzeko)

// PRODUKTU SAREA OSATU:
$(document).ready(function () {
  // Produktu kopuru info estiloak (jQuery-rekin)
  $("#produktu-kopuru-info").addClass("produktu-kopuru-info");

  $("#kopurua-txapa").addClass("kopurua-txapa");

  // Begiratu ea zerbitzaritik (PHP) datuak jada badatozen (hasierakoProduktuak)
  if (
    typeof hasierakoProduktuak !== "undefined" &&
    Array.isArray(hasierakoProduktuak)
  ) {
    // Datuak jada hemen daude: ez egin ajax deirik.
    // Gorde aldagai globalean iragazkientzat
    produktuGuztiak = hasierakoProduktuak;
    console.log("Produktuak PHPtik kargatuta.", produktuGuztiak.length);
  } else {
    console.error(
      "Errorea: Produktuak ez dira kargatu. 'hasierakoProduktuak' ez dago definituta.",
    );
    $(".produktu-sarea").html(
      "<p>Errorea: Ezin izan dira produktuak kargatu.</p>",
    );
  }

  // Iragazkietan aldaketak detektatu
  $(
    "#iragazkia-bilatu, #iragazkia-egoera, #iragazkia-kategoria, #iragazkia-mota, #iragazkia-ordenatu, #prezioa-min, #prezioa-max",
  ).on("input change", function () {
    produktuakFiltratu();
  });

  // Radio botoien aldaketak detektatu (Prezioa ordenatu)
  $("input[name='prezio-ordenatu']").on("change", function () {
      var balioa = $(this).val();
      window.location.href = "produktuak.php?prezio-ordenatu=" + balioa;
  });

  // "FILTROAK GARBITU" botoia
  $(".iragazkiak-berrezarri").on("click", function () {
    // URL garbitu
    window.location.href = "produktuak.php";
    /*
    $("#iragazkia-bilatu").val("");
    $("#iragazkia-egoera").val("");
    $("#iragazkia-kategoria").val("");
    $("#iragazkia-mota").val("");
    $("#iragazkia-ordenatu").val("default");
    $("#prezioa-min").val("");
    $("#prezioa-max").val("");
    $("input[name='prezio-ordenatu']").prop("checked", false);
    produktuakFiltratu(); // Berrezarri ondoren filtratu (denak erakusteko)
    */
  });

  // MUGIKORRA ETA IDAZMAHAIA: Iragazkiak erakutsi/ezkutatu
  $(".iragazki-goiburua").on("click", function () {
    $(".iragazki-edukia").slideToggle();
  });

  // Hasieran ere filtratu (PHP-tik balioak badaude, kopurua sinkronizatzeko)
  // Atzerapen txiki bat jarriko diogu nabigatzaileak selekzioak ondo karga ditzan
  setTimeout(produktuakFiltratu, 100);
});

function produktuakBistaratu(produktuak) {
  var $sarea = $(".produktu-sarea");
  $sarea.empty();

  if (produktuak.length === 0) {
    $sarea.html("<p>Ez da produkturik aurkitu irizpide hauekin.</p>");
    return;
  }

  $.each(produktuak, function (index, produktua) {
    var stockKlasea =
      produktua.stock > 0 ? "txartel-stock" : "txartel-stock-agortuta";

    var txartelaHtml = `
      <div class="produktu-txartela" data-id="${produktua.id_produktua}">
        <div class="txartel-irudia klikagarria-joan">
          <img
            src="${produktua.irudia_url}"
            alt="${produktua.izena}"
            class="txartel-irudia"
            onerror="this.src='../irudiak/birtek1.jpeg'" 
          />
          <div class="txartel-kategoria-txapa">${produktua.id_kategoria}</div> 
        </div>
        <div class="txartel-edukia">
          <h3 class="txartel-izenburua klikagarria-joan">${produktua.izena}</h3>
          <div class="txartel-informazio-lerroa">
            <span class="txartel-marka">${produktua.marka} | ${
              produktua.egoera
            }</span>
            <span class="${stockKlasea}">Stock: ${produktua.stock}</span>
          </div>
          <p class="txartel-azalpena">
            ${produktua.deskribapena || ""}
          </p>

          <div class="txartel-oina">
            <span class="txartel-prezioa">${produktua.prezioa.toFixed(
              2,
            )} €</span>
            <button class="produktua-saskiratu-botoia" data-stock="${
              produktua.stock
            }" ${produktua.stock === 0 ? "disabled" : ""}>
              Saskiratu
            </button>
            <button class="produktua-ikusi-botoia klikagarria-joan">Ikusi</button>
          </div>
        </div>
      </div>
    `;
    $sarea.append(txartelaHtml);
  });
}

// Produktuak klikatzean
$(document).on("click", ".klikagarria-joan", function (e) {
  var id = $(this).closest(".produktu-txartela").data("id");
  if (id) {
    window.location.href = "produktua_xehetasunak.php?id=" + id;
  }
});

function produktuakFiltratu() {
  var bilatuTestua = $("#iragazkia-bilatu").val() ? $("#iragazkia-bilatu").val().trim().toLowerCase() : "";
  var egoera = $("#iragazkia-egoera").val();
  var kategoria = $("#iragazkia-kategoria").val();
  var mota = $("#iragazkia-mota").val();
  var ordenatu = $("#iragazkia-ordenatu").val();
  
  var prezioOrdenatu = $("input[name='prezio-ordenatu']:checked").val();
  if (prezioOrdenatu) {
    ordenatu = prezioOrdenatu;
  }
  
  var prezioaMin = parseFloat($("#prezioa-min").val());
  var prezioaMax = parseFloat($("#prezioa-max").val());

  console.log("Filtratzen: ", { bilatuTestua, egoera, kategoria, mota, ordenatu });

  var emaitzak = produktuGuztiak.filter(function (p) {
    if (p.stock <= 0) return false;

    var matchBilatu = !bilatuTestua || 
                      (p.izena && p.izena.toLowerCase().includes(bilatuTestua)) || 
                      (p.marka && p.marka.toLowerCase().includes(bilatuTestua));

    var matchEgoera = !egoera || p.egoera === egoera;
    
    // Kategoria konparaketa (trim eginda)
    var pKat = p.id_kategoria ? p.id_kategoria.trim() : "";
    var fKat = kategoria ? kategoria.trim() : "";
    var matchKategoria = !fKat || pKat === fKat;

    // Mota konparaketa (trim eginda)
    var pMota = p.mota ? p.mota.trim() : "";
    var fMota = mota ? mota.trim() : "";
    var matchMota = !fMota || pMota === fMota;

    var matchPrezioaMin = isNaN(prezioaMin) || p.prezioa >= prezioaMin;
    var matchPrezioaMax = isNaN(prezioaMax) || p.prezioa <= prezioaMax;

    return matchBilatu && matchEgoera && matchKategoria && matchMota && matchPrezioaMin && matchPrezioaMax;
  });

  // Ordenatu
  if (ordenatu !== "default") {
    emaitzak.sort(function (a, b) {
      if (ordenatu === "prezioa-asc") return a.prezioa - b.prezioa;
      if (ordenatu === "prezioa-desc") return b.prezioa - a.prezioa;
      if (ordenatu === "izena-asc") return (a.izena || "").localeCompare(b.izena || "");
      if (ordenatu === "izena-desc") return (b.izena || "").localeCompare(a.izena || "");
      if (ordenatu === "stock-asc") return a.stock - b.stock;
      if (ordenatu === "stock-desc") return b.stock - a.stock;
      return 0;
    });
  }

  console.log("Aurkitutako produktu kopurua:", emaitzak.length);
  produktuakBistaratu(emaitzak);
  
  // Eguneratu kopurua txapan
  var $txapa = $("#kopurua-txapa");
  if ($txapa.length > 0) {
    $txapa.text(emaitzak.length);
    console.log("Kopuru txapa eguneratua: " + emaitzak.length);
  }
}

// ==========================================================
// SASKIAREN LOGIKA (GEHITU BAKARRIK - Globala)
// ==========================================================
$(document).ready(function () {
  // 1. PRODUKTUA GEHITU (Saskiratu botoia)
  $(document).on("click", ".produktua-saskiratu-botoia", function () {
    var $botoia = $(this);
    var $txartela = $botoia.closest(".produktu-txartela");

    // Datuak jaso
    var izena = $txartela.find(".txartel-izenburua").text();
    var prezioaTestua = $txartela
      .find(".txartel-prezioa")
      .text()
      .replace(" €", "");
    var prezioa = parseFloat(prezioaTestua);
    var stock = parseInt($botoia.data("stock")) || 0;

    // Bilatu produktua array globalean ID lortzeko
    var produktuaObj = produktuGuztiak.find((p) => p.izena === izena);
    // Fallback: IDrik ezean izena erabili (baina PHPtik beti IDa etorri beharko litzateke)
    var id = produktuaObj ? produktuaObj.id_produktua : izena;

    // Globala.js-ko funtzioa deitu
    if (typeof window.saskiaGehitu === "function") {
      window.saskiaGehitu(id, izena, prezioa, stock, $botoia);
      
      //  (soilik saskiratu denean)
      if (typeof window.saskiaAnimatuKontagailua === "function") {
        window.saskiaAnimatuKontagailua();
      }

      // Saskia irekita badago, eguneratu ikuspegia
      if (
        $("#saski-modala").is(":visible") &&
        typeof window.saskiaErakutsi === "function"
      ) {
        window.saskiaErakutsi();
      }
    } else {
      console.error(
        "Errorea: window.saskiaGehitu ez dago definituta globala.js-n",
      );
    }
  });
});

``n
## SQL Fitxategiak

### sql/birtek_db.sql
``sql
-- ========================================================
-- BIRTEK DATU-BASEA - SCRIPT OSOA
-- ========================================================

DROP DATABASE IF EXISTS birtek_db;
CREATE DATABASE IF NOT EXISTS birtek_db;
USE birtek_db;

-- ========================================================
-- 1. GEOGRAFIA
-- ========================================================

CREATE TABLE IF NOT EXISTS herriak (
    id_herria INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    izena VARCHAR(100) NOT NULL,
    lurraldea VARCHAR(100) NOT NULL,
    nazioa VARCHAR(100) NOT NULL
);

-- ========================================================
-- 2. ENPRESA EGITURA (Sailak, Biltegiak)
-- ========================================================

CREATE TABLE IF NOT EXISTS langile_sailak (
    id_saila INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    izena VARCHAR(100) NOT NULL,
    kokapena VARCHAR(100) NOT NULL,
    deskribapena TEXT
);

-- ========================================================
-- 3. ERABILTZAILEAK (Langileak, Bezeroak, Hornitzaileak)
-- ========================================================

CREATE TABLE IF NOT EXISTS langileak (
    id_langilea INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    izena VARCHAR(50) NOT NULL,
    abizena VARCHAR(100) NOT NULL,
    nan VARCHAR(9) UNIQUE,
    jaiotza_data DATE,
    
    -- Kokapena
    herria_id INT unsigned,
    helbidea VARCHAR(150),
    posta_kodea VARCHAR(5),
    telefonoa VARCHAR(20),
    
    -- Login datuak eta Hizkuntza
    emaila VARCHAR(100) UNIQUE NOT NULL,
    hizkuntza ENUM('Euskara', 'Gaztelania', 'Frantsesa', 'Ingelesa') DEFAULT 'Euskara',
    pasahitza VARCHAR(255),
    salto_txartela_uid VARCHAR(50) UNIQUE, -- Langilea identifikatzeko
    
    -- Lan datuak
    alta_data TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    eguneratze_data DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    aktibo BOOLEAN NOT NULL DEFAULT 0,
    
    
    saila_id INT unsigned,
    iban VARCHAR(34)UNIQUE,
    
    kurrikuluma MEDIUMBLOB, -- pdf gordetzeko
    
    CONSTRAINT fk_langilea_saila FOREIGN KEY (saila_id) REFERENCES langile_sailak(id_saila),
    CONSTRAINT fk_langilea_herria FOREIGN KEY (herria_id) REFERENCES herriak(id_herria)
);

CREATE TABLE IF NOT EXISTS fitxaketak (
    id_fitxaketa INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    langilea_id INT UNSIGNED NOT NULL,
    data DATE NOT NULL DEFAULT (CURRENT_DATE),
    ordua TIME NOT NULL DEFAULT (CURRENT_TIME),
    mota ENUM('Sarrera', 'Irteera') NOT NULL DEFAULT 'Sarrera',
    eguneratze_data DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_fitxaketa_langilea FOREIGN KEY (langilea_id) REFERENCES langileak(id_langilea)
);

CREATE TABLE IF NOT EXISTS bezeroak (
    id_bezeroa INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    izena_edo_soziala VARCHAR(100) NOT NULL,
    abizena VARCHAR(100),
    ifz_nan VARCHAR(9) UNIQUE NOT NULL,
    jaiotza_data DATE,
    sexua ENUM('gizona', 'emakumea', 'ez-binarioa'),
    
    -- Ordaintzeko
    bezero_ordainketa_txartela VARCHAR(255),
    
    -- Bidalketarako
    helbidea VARCHAR(150) NOT NULL,
    herria_id INT UNSIGNED NOT NULL,
    posta_kodea VARCHAR(5) NOT NULL,
    telefonoa VARCHAR(15),
    
    -- Login eta Hizkuntza
    emaila VARCHAR(255) UNIQUE NOT NULL, -- NVARCHAR ordez VARCHAR erabilita bateragarritasunerako
    hizkuntza ENUM('Euskara', 'Gaztelania', 'Frantsesa', 'Ingelesa') NOT NULL DEFAULT 'Euskara',
    pasahitza VARCHAR(255) NOT NULL,
    
    alta_data DATETIME DEFAULT CURRENT_TIMESTAMP,
    eguneratze_data DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    aktibo BOOLEAN NOT NULL DEFAULT 1,
    
    CONSTRAINT fk_bezeroa_herria FOREIGN KEY (herria_id) REFERENCES herriak(id_herria)
);

CREATE TABLE IF NOT EXISTS hornitzaileak (
    id_hornitzailea INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    izena_soziala VARCHAR(100) NOT NULL,
    ifz_nan VARCHAR(9) UNIQUE NOT NULL,
    
    -- Kontaktu datuak
    kontaktu_pertsona VARCHAR(100),
    helbidea VARCHAR(150) NOT NULL,
    herria_id INT UNSIGNED NOT NULL,
    posta_kodea VARCHAR(5) NOT NULL,
    telefonoa VARCHAR(15),
    
    -- Login
    emaila VARCHAR(255) UNIQUE NOT NULL,
    hizkuntza ENUM('Euskara', 'Gaztelania', 'Frantsesa', 'Ingelesa') NOT NULL DEFAULT 'Gaztelania',
    pasahitza VARCHAR(255) NOT NULL,
    aktibo BOOLEAN NOT NULL DEFAULT 1,
    
    eguneratze_data DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_hornitzailea_herria FOREIGN KEY (herria_id) REFERENCES herriak(id_herria)
);

-- ========================================================
-- 4. PRODUKTU KATALOGOA ETA BILTEGIA
-- ========================================================

CREATE TABLE IF NOT EXISTS produktu_kategoriak (
    id_kategoria INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    izena VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE IF NOT EXISTS biltegiak (
    id_biltegia INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    izena VARCHAR(50) NOT NULL,
    biltegi_sku VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE IF NOT EXISTS produktuak (
    id_produktua INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hornitzaile_id INT UNSIGNED NOT NULL,
    kategoria_id INT UNSIGNED NOT NULL,
    izena VARCHAR(255) NOT NULL,
    marka VARCHAR(50) NOT NULL,
    mota ENUM('Eramangarria', 'Mahai-gainekoa', 'Mugikorra', 'Tableta', 'Zerbitzaria', 'Pantaila', 'Softwarea') NOT NULL,
    
    deskribapena TEXT,
    irudia_url VARCHAR(255),
    
    -- Egoera
    biltegi_id INT UNSIGNED NOT NULL,
    produktu_egoera ENUM('Berria', 'Berritua A', 'Berritua B', 'Hondatua', 'Zehazteko') NOT NULL DEFAULT 'Zehazteko',
    produktu_egoera_oharra TEXT,
    salgai BOOLEAN DEFAULT FALSE,
    
    -- Datu Ekonomikoak
    salmenta_prezioa DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    stock INT UNSIGNED DEFAULT 0,
    eskaintza DECIMAL(5, 2) DEFAULT NULL,
    zergak_ehunekoa DECIMAL(5, 2) NOT NULL DEFAULT 21.00,
    
    sortze_data TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    eguneratze_data DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_produktua_kategoria FOREIGN KEY (kategoria_id) REFERENCES produktu_kategoriak(id_kategoria),
    CONSTRAINT fk_produktua_hornitzailea FOREIGN KEY (hornitzaile_id) REFERENCES hornitzaileak(id_hornitzailea),
    CONSTRAINT fk_produktua_biltegia FOREIGN KEY (biltegi_id) REFERENCES biltegiak(id_biltegia)
);

-- ========================================================
-- SUBKLASEAK - PRODUKTUAK (HERENTZIA)
-- ========================================================

-- 1. Eramangarriak
CREATE TABLE IF NOT EXISTS eramangarriak (
    id_produktua INT UNSIGNED PRIMARY KEY,
    prozesadorea VARCHAR(100),
    ram_gb INT,
    diskoa_gb INT,
    pantaila_tamaina DECIMAL(4,1),
    bateria_wh INT,
    sistema_eragilea VARCHAR(50),
    pisua_kg DECIMAL(4,2),
    CONSTRAINT fk_eramangarria_produktua FOREIGN KEY eramangarriak(id_produktua) REFERENCES produktuak(id_produktua) ON DELETE CASCADE
);

-- 2. Mahai-gainekoak
CREATE TABLE IF NOT EXISTS mahai_gainekoak (
    id_produktua INT UNSIGNED PRIMARY KEY,
    prozesadorea VARCHAR(100),
    plaka_basea VARCHAR(100),
    ram_gb INT,
    diskoa_gb INT,
    txartel_grafikoa VARCHAR(100),
    elikatze_iturria_w INT,
    kaxa_formatua ENUM('ATX', 'Micro-ATX', 'Mini-ITX', 'E-ATX'),
    CONSTRAINT fk_mahaigainekoa_produktua FOREIGN KEY (id_produktua) REFERENCES produktuak(id_produktua) ON DELETE CASCADE
);

-- 3. Mugikorrak
CREATE TABLE IF NOT EXISTS mugikorrak (
    id_produktua INT UNSIGNED PRIMARY KEY,
    pantaila_teknologia VARCHAR(50),
    pantaila_hazbeteak DECIMAL(3,1),
    biltegiratzea_gb INT,
    ram_gb INT,
    kamera_nagusa_mp INT,
    bateria_mah INT,
    sistema_eragilea VARCHAR(50),
    sareak ENUM('4G', '5G'),
    CONSTRAINT fk_mugikorra_produktua FOREIGN KEY (id_produktua) REFERENCES produktuak(id_produktua) ON DELETE CASCADE
);

-- 4. Tabletak
CREATE TABLE IF NOT EXISTS tabletak (
    id_produktua INT UNSIGNED PRIMARY KEY,
    pantaila_hazbeteak DECIMAL(4,1),
    biltegiratzea_gb INT,
    konektibitatea ENUM('WiFi', 'WiFi + Cellular'),
    sistema_eragilea VARCHAR(50),
    bateria_mah INT,
    arkatzarekin_bateragarria BOOLEAN DEFAULT FALSE,
    CONSTRAINT fk_tableta_produktua FOREIGN KEY (id_produktua) REFERENCES produktuak(id_produktua) ON DELETE CASCADE
);

-- 5. Zerbitzariak
CREATE TABLE IF NOT EXISTS zerbitzariak (
    id_produktua INT UNSIGNED PRIMARY KEY,
    prozesadore_nukleoak INT,
    ram_mota ENUM('DDR4', 'DDR5', 'ECC'),
    disko_badiak INT,
    rack_unitateak INT,
    elikatze_iturri_erredundantea BOOLEAN DEFAULT TRUE,
    raid_kontroladora VARCHAR(50),
    CONSTRAINT fk_zerbitzaria_produktua FOREIGN KEY (id_produktua) REFERENCES produktuak(id_produktua) ON DELETE CASCADE
);

-- 6. Pantailak
CREATE TABLE IF NOT EXISTS pantailak (
    id_produktua INT UNSIGNED PRIMARY KEY,
    hazbeteak DECIMAL(4,1),
    bereizmena VARCHAR(20),
    panel_mota ENUM('IPS', 'VA', 'TN', 'OLED'),
    freskatze_tasa_hz INT,
    konexioak VARCHAR(150),
    kurbatura VARCHAR(10),
    CONSTRAINT fk_pantaila_produktua FOREIGN KEY (id_produktua) REFERENCES produktuak(id_produktua) ON DELETE CASCADE
);

-- 7. Softwareak (ZUZENDUA)
CREATE TABLE IF NOT EXISTS softwareak (
    id_produktua INT UNSIGNED PRIMARY KEY,
    software_mota ENUM('Sistema Eragilea', 'Ofimatika', 'Antibirusa', 'Bestelakoak') NOT NULL,
    lizentzia_mota ENUM('OEM', 'Retail', 'Harpidetza', 'OpenSource', 'GPL', 'LGPL') DEFAULT 'Retail',
    bertsioa VARCHAR(50),
    garatzailea VARCHAR(100),
    librea BOOLEAN DEFAULT FALSE, -- Koma gehitu da eta sintaxia zuzendu da

    CONSTRAINT fk_softwarea_produktua FOREIGN KEY (id_produktua) REFERENCES produktuak(id_produktua) ON DELETE CASCADE
);

-- ========================================================
-- 4.1. TAILERRA ETA KONPONKETAK
-- ========================================================

CREATE TABLE IF NOT EXISTS akatsak (
    id_akatsa INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    izena VARCHAR(100) NOT NULL,
    deskribapena TEXT
);

CREATE TABLE IF NOT EXISTS konponketak (
    id_konponketa INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    produktua_id INT UNSIGNED NOT NULL,
    langilea_id INT UNSIGNED NOT NULL,
    hasiera_data DATETIME DEFAULT CURRENT_TIMESTAMP,
    amaiera_data DATETIME,
    konponketa_egoera ENUM('Prozesuan', 'Konponduta', 'Konponezina') NOT NULL DEFAULT 'Prozesuan',
    akatsa_id INT UNSIGNED NOT NULL,
    oharrak TEXT,
    eguneratze_data DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_konponketa_produktua FOREIGN KEY (produktua_id) REFERENCES produktuak(id_produktua),
    CONSTRAINT fk_konponketa_langilea FOREIGN KEY (langilea_id) REFERENCES langileak(id_langilea),
    CONSTRAINT fk_konponketa_akatsa FOREIGN KEY (akatsa_id) REFERENCES akatsak(id_akatsa)
);

-- ========================================================
-- 5. LOGISTIKA (Sarrerak)
-- ========================================================

CREATE TABLE IF NOT EXISTS sarrerak (
    id_sarrera INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    data DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    eguneratze_data DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    hornitzailea_id INT UNSIGNED NOT NULL,
    langilea_id INT UNSIGNED NOT NULL,
    sarrera_egoera ENUM('Bidean', 'Jasota', 'Ezabatua') NOT NULL DEFAULT 'Bidean',
    
    CONSTRAINT fk_sarrera_hornitzailea FOREIGN KEY (hornitzailea_id) REFERENCES hornitzaileak(id_hornitzailea),
    CONSTRAINT fk_sarrera_langilea FOREIGN KEY (langilea_id) REFERENCES langileak(id_langilea)
);

CREATE TABLE IF NOT EXISTS sarrera_lerroak (
    id_sarrera_lerroa INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sarrera_id INT UNSIGNED NOT NULL,
    produktua_id INT UNSIGNED NOT NULL,
    kantitatea INT UNSIGNED NOT NULL,
    sarrera_lerro_egoera ENUM('Bidean', 'Jasota', 'Ezabatua') NOT NULL DEFAULT 'Bidean',
    CONSTRAINT fk_sl_sarrera FOREIGN KEY (sarrera_id) REFERENCES sarrerak(id_sarrera),
    CONSTRAINT fk_sl_produktua FOREIGN KEY (produktua_id) REFERENCES produktuak(id_produktua)
);

-- ========================================================
-- 6. SALMENTAK (Eskaerak)
-- ========================================================

CREATE TABLE IF NOT EXISTS eskaerak (
    id_eskaera INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    bezeroa_id INT UNSIGNED NOT NULL,
    langilea_id INT UNSIGNED,
    data DATETIME DEFAULT CURRENT_TIMESTAMP,
    eguneratze_data DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    guztira_prezioa DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    
    faktura_zenbakia VARCHAR(20) UNIQUE,
	faktura_url VARCHAR(255),
    
    eskaera_egoera ENUM('Prestatzen', 'Osatua/Bidalita', 'Ezabatua') NOT NULL DEFAULT 'Prestatzen',
    
    CONSTRAINT fk_eskaera_bezeroa FOREIGN KEY (bezeroa_id) REFERENCES bezeroak(id_bezeroa),
    CONSTRAINT fk_eskaera_langilea FOREIGN KEY (langilea_id) REFERENCES langileak(id_langilea)
);

CREATE TABLE IF NOT EXISTS eskaera_lerroak (
    id_eskaera_lerroa INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    eskaera_id INT UNSIGNED NOT NULL,
    produktua_id INT UNSIGNED NOT NULL,
    kantitatea INT UNSIGNED NOT NULL,
    unitate_prezioa DECIMAL(10, 2) NOT NULL,
    
    eskaera_lerro_egoera ENUM('Prestatzen', 'Osatua/Bidalita', 'Ezabatua') NOT NULL DEFAULT 'Prestatzen',
    
    CONSTRAINT fk_el_eskaera FOREIGN KEY (eskaera_id) REFERENCES eskaerak(id_eskaera),
    CONSTRAINT fk_el_produktua FOREIGN KEY (produktua_id) REFERENCES produktuak(id_produktua)
);


-- ========================================================
-- 7. ERABILTZAILEAK ETA BAIMENAK
-- ========================================================

FLUSH PRIVILEGES;

-- ZUZENDARITZA (SysAdmin) 
CREATE USER IF NOT EXISTS 'ander_sysadmin'@'localhost' IDENTIFIED BY '1234';
GRANT ALL PRIVILEGES ON *.* TO 'ander_sysadmin'@'localhost' WITH GRANT OPTION;

CREATE USER IF NOT EXISTS 'lander_sysadmin'@'localhost' IDENTIFIED BY '1234';
GRANT ALL PRIVILEGES ON *.* TO 'lander_sysadmin'@'localhost' WITH GRANT OPTION;

-- ADMINISTRAZIOA
CREATE USER IF NOT EXISTS 'ane_admin'@'localhost' IDENTIFIED BY '1234';
CREATE USER IF NOT EXISTS 'mikel_admin'@'localhost' IDENTIFIED BY '1234';

GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.langileak TO 'ane_admin'@'localhost', 'mikel_admin'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.langile_sailak TO 'ane_admin'@'localhost', 'mikel_admin'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.fitxaketak TO 'ane_admin'@'localhost', 'mikel_admin'@'localhost';
GRANT SELECT, INSERT, UPDATE ON birtek_db.eskaerak TO 'ane_admin'@'localhost', 'mikel_admin'@'localhost'; -- fakturak ikusi behar dituzte
GRANT SELECT, UPDATE, DELETE ON birtek_db.hornitzaileak TO 'ane_admin'@'localhost', 'mikel_admin'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.herriak TO 'ane_admin'@'localhost', 'mikel_admin'@'localhost';

-- SALMENTAK
CREATE USER IF NOT EXISTS 'leire_sales'@'localhost' IDENTIFIED BY '1234';
CREATE USER IF NOT EXISTS 'iker_sales'@'localhost' IDENTIFIED BY '1234';
CREATE USER IF NOT EXISTS 'amaia_sales'@'localhost' IDENTIFIED BY '1234';

GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.bezeroak TO 'leire_sales'@'localhost', 'iker_sales'@'localhost', 'amaia_sales'@'localhost';
GRANT SELECT, UPDATE ON birtek_db.produktuak TO 'leire_sales'@'localhost', 'iker_sales'@'localhost', 'amaia_sales'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.eskaerak TO 'leire_sales'@'localhost', 'iker_sales'@'localhost', 'amaia_sales'@'localhost';  -- fakturak orain eskaerak taula kudeatzen dira
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.eskaera_lerroak TO 'leire_sales'@'localhost', 'iker_sales'@'localhost', 'amaia_sales'@'localhost';
GRANT SELECT, INSERT, UPDATE ON birtek_db.herriak TO 'leire_sales'@'localhost', 'iker_sales'@'localhost', 'amaia_sales'@'localhost';
-- TEKNIKOAK SAT
CREATE USER IF NOT EXISTS 'unai_sat'@'localhost' IDENTIFIED BY '1234';
CREATE USER IF NOT EXISTS 'maite_sat'@'localhost' IDENTIFIED BY '1234';
CREATE USER IF NOT EXISTS 'aitor_sat'@'localhost' IDENTIFIED BY '1234';
CREATE USER IF NOT EXISTS 'nerea_sat'@'localhost' IDENTIFIED BY '1234';

GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.produktuak TO 'unai_sat'@'localhost', 'maite_sat'@'localhost', 'aitor_sat'@'localhost', 'nerea_sat'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.konponketak TO 'unai_sat'@'localhost', 'maite_sat'@'localhost', 'aitor_sat'@'localhost', 'nerea_sat'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.akatsak TO 'unai_sat'@'localhost', 'maite_sat'@'localhost', 'aitor_sat'@'localhost', 'nerea_sat'@'localhost';
GRANT SELECT, INSERT, UPDATE ON birtek_db.herriak TO 'unai_sat'@'localhost', 'maite_sat'@'localhost', 'aitor_sat'@'localhost', 'nerea_sat'@'localhost';

-- LOGISTIKA
CREATE USER IF NOT EXISTS 'gorka_biltegia'@'localhost' IDENTIFIED BY '1234';
CREATE USER IF NOT EXISTS 'oihane_biltegia'@'localhost' IDENTIFIED BY '1234';
CREATE USER IF NOT EXISTS 'xabier_biltegia'@'localhost' IDENTIFIED BY '1234';

GRANT SELECT, INSERT, UPDATE ON birtek_db.produktuak TO 'gorka_biltegia'@'localhost', 'oihane_biltegia'@'localhost', 'xabier_biltegia'@'localhost';
GRANT SELECT, INSERT, UPDATE ON birtek_db.hornitzaileak TO 'gorka_biltegia'@'localhost', 'oihane_biltegia'@'localhost', 'xabier_biltegia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.biltegiak TO 'gorka_biltegia'@'localhost', 'oihane_biltegia'@'localhost', 'xabier_biltegia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.sarrera_lerroak TO 'gorka_biltegia'@'localhost', 'oihane_biltegia'@'localhost', 'xabier_biltegia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.sarrerak TO 'gorka_biltegia'@'localhost', 'oihane_biltegia'@'localhost', 'xabier_biltegia'@'localhost';
GRANT SELECT, UPDATE ON birtek_db.eskaera_lerroak TO 'gorka_biltegia'@'localhost', 'oihane_biltegia'@'localhost', 'xabier_biltegia'@'localhost';
GRANT SELECT, INSERT, UPDATE ON birtek_db.herriak TO 'gorka_biltegia'@'localhost', 'oihane_biltegia'@'localhost', 'xabier_biltegia'@'localhost';

-- FITXAKETAK (Langile guztieenak)
GRANT SELECT, INSERT ON birtek_db.fitxaketak TO 

-- 'lander_sysadmin'            IADA BADUTE crud osoa
-- 'ander_sysadmin'@'localhost' IADA BADUTE crud osoa
-- 'ane_admin'@'localhost',     IADA BADUTE crud osoa
-- 'mikel_admin'@'localhost';   IADA BADUTE crud osoa
    'leire_sales'@'localhost', 
    'iker_sales'@'localhost', 
    'amaia_sales'@'localhost', 
    'unai_sat'@'localhost', 
    'maite_sat'@'localhost', 
    'aitor_sat'@'localhost', 
    'nerea_sat'@'localhost', 
    'gorka_biltegia'@'localhost', 
    'oihane_biltegia'@'localhost', 
    'xabier_biltegia'@'localhost';

FLUSH PRIVILEGES;


-- ========================================================================================================================================

-- ========================================================================================================================
-- INSERT INTO DATUEN TXERTAKETA
-- ========================================================================================================================

-- 1. OINARRIZKO DATUAK
INSERT INTO biltegiak (id_biltegia, izena, biltegi_sku) VALUES 
(1, 'Harrera Biltegia', 'HAR_BIL'),
(2, 'Biltegi Nagusia', 'BIL_NAG'),
(3, 'Irteera Biltegia', 'IRT_BIL');

INSERT INTO produktu_kategoriak (id_kategoria, izena) VALUES 
(1, 'Ordenagailuak'),
(2, 'Telefonia'),
(3, 'Irudia'),
(4, 'Osagarriak'),
(5, 'Softwarea'),
(6, 'Sareak eta Zerbitzariak');

-- 2. GEOGRAFIA ETA LANGILEAK
INSERT INTO herriak (id_herria, izena, lurraldea, nazioa) VALUES
(1, 'Donostia', 'Gipuzkoa', 'Euskal Herria'),
(2, 'Bilbo', 'Bizkaia', 'Euskal Herria'),
(3, 'Gasteiz', 'Araba', 'Euskal Herria'),
(4, 'Iruña', 'Nafarroa', 'Euskal Herria'),
(5, 'Eibar', 'Gipuzkoa', 'Euskal Herria'),
(6, 'Zarautz', 'Gipuzkoa', 'Euskal Herria'),
(7, 'Irun', 'Gipuzkoa', 'Euskal Herria'),
(8, 'Errenteria', 'Gipuzkoa', 'Euskal Herria'),
(9, 'Hernani', 'Gipuzkoa', 'Euskal Herria'),
(10, 'Tolosa', 'Gipuzkoa', 'Euskal Herria'),
(11, 'Arrasate', 'Gipuzkoa', 'Euskal Herria'),
(12, 'Bergara', 'Gipuzkoa', 'Euskal Herria'),
(13, 'Azpeitia', 'Gipuzkoa', 'Euskal Herria'),
(14, 'Beasain', 'Gipuzkoa', 'Euskal Herria'),
(15, 'Hondarribia', 'Gipuzkoa', 'Euskal Herria'),
(16, 'Getxo', 'Bizkaia', 'Euskal Herria'),
(17, 'Barakaldo', 'Bizkaia', 'Euskal Herria'),
(18, 'Portugalete', 'Bizkaia', 'Euskal Herria'),
(19, 'Santurtzi', 'Bizkaia', 'Euskal Herria'),
(20, 'Basauri', 'Bizkaia', 'Euskal Herria'),
(21, 'Durango', 'Bizkaia', 'Euskal Herria'),
(22, 'Gernika-Lumo', 'Bizkaia', 'Euskal Herria'),
(23, 'Bermeo', 'Bizkaia', 'Euskal Herria'),
(24, 'Mungia', 'Bizkaia', 'Euskal Herria'),
(25, 'Ermua', 'Bizkaia', 'Euskal Herria'),
(26, 'Laudio', 'Araba', 'Euskal Herria'),
(27, 'Amurrio', 'Araba', 'Euskal Herria'),
(28, 'Agurain', 'Araba', 'Euskal Herria'),
(29, 'Guardia', 'Araba', 'Euskal Herria'),
(30, 'Oion', 'Araba', 'Euskal Herria'),
(31, 'Legutio', 'Araba', 'Euskal Herria'),
(32, 'Tutera', 'Nafarroa', 'Euskal Herria'),
(33, 'Lizarra', 'Nafarroa', 'Euskal Herria'),
(34, 'Tafalla', 'Nafarroa', 'Euskal Herria'),
(35, 'Zangoza', 'Nafarroa', 'Euskal Herria'),
(36, 'Altsasu', 'Nafarroa', 'Euskal Herria'),
(37, 'Baztan', 'Nafarroa', 'Euskal Herria'),
(38, 'Corella', 'Nafarroa', 'Euskal Herria'),
(39, 'Zizur Nagusia', 'Nafarroa', 'Euskal Herria'),
(40, 'Baiona', 'Lapurdi', 'Euskal Herria'),
(41, 'Biarritz', 'Lapurdi', 'Euskal Herria'),
(42, 'Angelu', 'Lapurdi', 'Euskal Herria'),
(43, 'Donibane Lohizune', 'Lapurdi', 'Euskal Herria'),
(44, 'Hendaia', 'Lapurdi', 'Euskal Herria'),
(45, 'Azkaine', 'Lapurdi', 'Euskal Herria'),
(46, 'Donibane Garazi', 'Nafarroa Beherea', 'Euskal Herria'),
(47, 'Baigorri', 'Nafarroa Beherea', 'Euskal Herria'),
(48, 'Donapaleu', 'Nafarroa Beherea', 'Euskal Herria'),
(49, 'Maule-Lextarre', 'Zuberoa', 'Euskal Herria'),
(50, 'Atharratze-Sorholüze', 'Zuberoa', 'Euskal Herria'),
(51, 'Barkoxe', 'Zuberoa', 'Euskal Herria'),
(52, 'Lekeitio', 'Bizkaia', 'Euskal Herria'),
(53, 'Ondarroa', 'Bizkaia', 'Euskal Herria'),
(54, 'Zumaia', 'Gipuzkoa', 'Euskal Herria'),
(55, 'Getaria', 'Gipuzkoa', 'Euskal Herria'),
(56, 'Mutriku', 'Gipuzkoa', 'Euskal Herria');

INSERT INTO langile_sailak (id_saila, izena, kokapena, deskribapena) VALUES
(1, 'Zuzendaritza','Goi bulegoa', 'DIR'),
(2, 'Administrazioa','Harrera bulegoa', 'ADMIN'),
(3, 'Salmentak','Harrera', 'COM'),
(4, 'Zerbitzu Teknikoa','Tailerra', 'SAT'),
(5, 'Logistika eta Biltegia','Biltegiak', 'LOG');

INSERT INTO langileak (id_langilea, izena, abizena, nan, jaiotza_data, herria_id, helbidea, posta_kodea, telefonoa, emaila, pasahitza, salto_txartela_uid, saila_id, iban, alta_data, aktibo) VALUES
(1, 'Ander', 'Urien', '72484472H', '1992-03-02', 1, 'Askatasun Hiribidea 5', '20004', '600111222', 'ander@birtek.eus', '1234', 'UID_ADMIN_01', 1, 'ES1234567890123456789001', '2020-01-01', 1),
(2, 'Lander', 'Garmendia', '12345678Z', '2000-03-02', 1, 'Askatasun Hiribidea 5', '20004', '600111222', 'lander@birtek.eus', '1234', 'UID_ADMIN_02', 1, 'ES1234567890123456789002', '2020-01-01', 1),
(3, 'Ane', 'Lasa', '22222222B', '1985-03-20', 1, 'Easo Kalea 12', '20006', '600333444', 'ane.lasa@birtek.eus', '1234', 'UID_ADMIN_03', 2, 'ES1234567890123456789003', '2020-02-15', 1),
(4, 'Mikel', 'Otegi', '33333333C', '1990-11-10', 6, 'Nafarroa Kalea 3', '20800', '600555666', 'mikel.otegi@birtek.eus', '1234', 'UID_ADMIN_04', 2, 'ES1234567890123456789004', '2021-05-20', 1),
(5, 'Leire', 'Mendizabal', '44444444D', '1992-07-08', 2, 'Gran Via 25', '48001', '600777888', 'leire.mendi@birtek.eus', '1234', 'UID_COM_01', 3, 'ES1234567890123456789005', '2021-06-01', 1),
(6, 'Iker', 'Iriondo', '55555555E', '1995-01-30', 2, 'Licenciado Poza 10', '48011', '600999000', 'iker.iriondo@birtek.eus', '1234', 'UID_COM_02', 3, 'ES1234567890123456789006', '2022-03-10', 1),
(7, 'Amaia', 'Goikoetxea', '66666666F', '1988-09-12', 5, 'Isasi Kalea 4', '20600', '600123123', 'amaia.goiko@birtek.eus', '1234', 'UID_COM_03', 3, 'ES1234567890123456789007', '2019-11-05', 1),
(8, 'Unai', 'Zabala', '77777777G', '1993-04-25', 1, 'Tolosa Hiribidea 45', '20018', '600456456', 'unai.zabala@birtek.eus', '1234', 'UID_SAT_01', 4,'ES1234567890123456789008', '2020-09-15', 1),
(9, 'Maite', 'Arregi', '88888888H', '1996-12-05', 3, 'Dato Kalea 15', '01005', '600789789', 'maite.arregi@birtek.eus', '1234', 'UID_SAT_02', 4, 'ES1234567890123456789009', '2023-01-10', 1),
(10, 'Aitor', 'Bilbao', '99999999I', '1991-08-18', 2, 'Indautxu Plaza 2', '48010', '600321321', 'aitor.bilbao@birtek.eus', '1234', 'UID_SAT_03', 4, 'ES1234567890123456789010', '2021-02-28', 1),
(11, 'Nerea', 'Etxaniz', '12345678J', '1994-06-14', 6, 'Malekoia 8', '20800', '600654654', 'nerea.etxaniz@birtek.eus', '1234', 'UID_SAT_04', 4,'ES1234567890123456789011', '2022-07-20', 1),
(12, 'Gorka', 'Ugarte', '87654321K', '1987-02-22', 3, 'Gamarra Atea 4', '01013', '600987987', 'gorka.ugarte@birtek.eus', '1234', 'UID_LOG_01', 5, 'ES1234567890123456789012', '2018-05-12', 1),
(13, 'Oihane', 'Ibarra', '23456789L', '1998-10-30', 3, 'Frantzia Kalea 20', '01002', '600147258', 'oihane.ibarra@birtek.eus', '1234', 'UID_LOG_02', 5, 'ES1234567890123456789013', '2023-06-15', 1),
(14, 'Xabier', 'Larrea', '34567890M', '1990-03-15', 1, 'Intxaurrondo 50', '20015', '600258369', 'xabier.larrea@birtek.eus', '1234', 'UID_LOG_03', 5, 'ES1234567890123456789014', '2020-11-30', 1);

-- 3. HORNITZAILEAK ETA BEZEROAK
INSERT INTO hornitzaileak (id_hornitzailea, izena_soziala, ifz_nan, kontaktu_pertsona, helbidea, herria_id, posta_kodea, telefonoa, emaila, pasahitza) VALUES 
(1, 'PC Componentes Pro', 'A88776655', 'Soporte B2B', 'Poligono Industrial Alhama', 6, '28001', '910000000', 'b2b@pccomponentes.com', 'hash_pcc'),
(2, 'Ingram Micro', 'A11223344', 'Carlos Distribución', 'Calle Tecnología 5', 6, '28002', '910111222', 'pedidos@ingram.com', 'hash_ingram'),
(3, 'Amazon Business', 'W8888888', 'Logistika Zentroa', 'Trapagaran Poligonoa', 1, '48510', '900800700', 'business@amazon.es', 'hash_amazon'),
(4, 'Eusko Tech Solutions', 'B12345678', 'Ainhoa Etxebarria', 'Industrialdea 12', 2, '48001', '944001122', 'info@euskotech.eus', 'hash_4'),
(5, 'Gipuzkoako Logistika', 'A23456789', 'Mikel Iturbe', 'Poligono Belartza 4', 1, '20018', '943556677', 'mikel@giplogi.com', 'hash_5'),
(6, 'Araba Software', 'B34567890', 'Elena Perez', 'Postas kalea 23', 3, '01001', '945889900', 'kontaktua@arabasoft.eus', 'hash_6'),
(7, 'Nafarroa Hornikuntzak', 'A45678901', 'Inaxio Mujika', 'Industrialdea Ezkabarte', 4, '31013', '948112233', 'ventas@nafarhorni.es', 'hash_7'),
(8, 'Bidasoa Web Design', 'B56789012', 'Ane Arana', 'Iparralde Hiribidea 1', 7, '20301', '943665544', 'ane@bidasoaweb.eus', 'hash_8'),
(9, 'Durangoko Mekanizatuak', 'B67890123', 'Jon Ugarte', 'Tabira Industrialdea', 21, '48200', '946201584', 'jugarte@durmek.com', 'hash_9'),
(10, 'Lapurdi Distribución', 'A78901234', 'Maite Etcheverry', 'Rue de la Gare 5', 40, '64100', '055912345', 'maite@lapurdist.fr', 'hash_10'),
(11, 'Debagoiena Erremintak', 'B89012345', 'Karlos Arregi', 'Olandixo kalea z/g', 11, '20500', '943771122', 'erremintak@debagoiena.eus', 'hash_11'),
(12, 'Zuberoa Craft', 'B90123456', 'Pantaléon Aguer', 'Place du Marché', 49, '64130', '055965432', 'pantal@zubecraft.com', 'hash_12'),
(13, 'Bermeoko Arraina', 'A01234567', 'Begoña Lertxundi', 'Kai Zaharra 10', 23, '48370', '946881234', 'bego@bermeoarrain.eus', 'hash_13'),
(14, 'Tolosako Paperak', 'B11112222', 'Joxe Mari Mendizabal', 'San Esteban kalea 2', 10, '20400', '943675000', 'joxemari@tolopap.com', 'hash_14'),
(15, 'Baztan Esnekiak', 'B22223333', 'Irati Elizondo', 'Gaintza bidea', 37, '31700', '948580123', 'irati@baztanesne.eus', 'hash_15'),
(16, 'Goierri Altzariak', 'A33334444', 'Oier Mujika', 'Poligono Itola 8', 14, '20200', '943885522', 'oier@goierrial.com', 'hash_16'),
(17, 'Ermua IT Services', 'B44445555', 'Sara Gomez', 'Zubiaurre 15', 25, '48260', '943171122', 'sgomez@ermuait.es', 'hash_17'),
(18, 'Hernani Garraioak', 'A55556666', 'Patxi Galarraga', 'Poligono Ibaiondo', 9, '20120', '943552211', 'patxi@hernigarrai.eus', 'hash_18'),
(19, 'Getxo Digital', 'B66667777', 'Lucia Martinez', 'Las Arenas kalea 3', 16, '48930', '944631100', 'lucia@getxodigital.es', 'hash_19'),
(20, 'Oion Enologia', 'A77778888', 'Ramón Sáenz', 'Carretera Logroño z/g', 30, '01320', '941334455', 'info@oionenologia.com', 'hash_20'),
(21, 'Lizarra Marketing', 'B88889999', 'Nerea Ruiz', 'San Francisco kalea 12', 33, '31200', '948556677', 'nruiz@lizarramark.es', 'hash_21'),
(22, 'Biarritz Events', 'A99990000', 'Jean-Luc Dubois', 'Avenue de la Reine', 41, '64200', '055977889', 'jeanluc@biarritzevents.fr', 'hash_22'),
(23, 'Azpeitia Metal', 'B12121212', 'Xabier Alkorta', 'Urola Industrialdea', 13, '20730', '943812233', 'xalkorta@azpeitiametal.eus', 'hash_23'),
(24, 'Tutera Baratza', 'A23232323', 'Javier Jimeno', 'Camino del Huerto', 32, '31500', '948821100', 'jjimeno@tuterabaratza.com', 'hash_24'),
(25, 'Barakaldo Komunikazioa', 'B34343434', 'Marta Gonzalez', 'Foruen kalea 5', 17, '48901', '944371122', 'marta@barakom.es', 'hash_25'),
(26, 'Zarautz Surf Supply', 'A45454545', 'Aritz Bergara', 'Malekoi kalea 22', 6, '20800', '943831155', 'aritz@zarautzsurf.eus', 'hash_26'),
(27, 'Donapaleu Agro', 'B56565656', 'Pierre Etxebarne', 'Route de Bayonne', 48, '64120', '055922334', 'pierre@donapagro.fr', 'hash_27'),
(28, 'Bergara Elektrika', 'A67676767', 'Inma Gabilondo', 'Masterreka kalea 1', 12, '20570', '943761100', 'inma@bergelek.com', 'hash_28'),
(29, 'Gernika Bakea Tech', 'B78787878', 'Kepa Bilbao', 'Lurgorri kalea 45', 22, '48300', '946252233', 'kepa@gernikatech.eus', 'hash_29'),
(30, 'Laudio Beira', 'A89898989', 'Txema Luiaondo', 'Zubiaur kalea 8', 26, '01400', '946721155', 'txema@laudiobeira.com', 'hash_30'),
(31, 'Altsasu Trenak', 'B90909090', 'Unai Galan', 'Zelai kalea 10', 36, '31800', '948561122', 'unai@altsasutren.eus', 'hash_31'),
(32, 'Baiona Gazta', 'A01010101', 'Marie Larousse', 'Quai de la Nive', 40, '64100', '055933445', 'marie@baionagazta.fr', 'hash_32'),
(33, 'Eibar Armagintza', 'B12123434', 'Josu Arregui', 'Ipurua kalea 3', 5, '20600', '943201122', 'josu@eibararma.eus', 'hash_33');

INSERT INTO bezeroak (izena_edo_soziala, abizena, ifz_nan, jaiotza_data, sexua, bezero_ordainketa_txartela, helbidea, herria_id, posta_kodea, telefonoa, emaila, hizkuntza, pasahitza, aktibo) VALUES 
('Ane', 'Goikoetxea Lasa', '12345678A', '1990-05-15', 'emakumea', 'tok_visa_4242', 'Askatasunaren Etorbidea 14, 2.B', 1, '20004', '600123456', 'ane.goiko@email.eus', 'Euskara', 'pasahitzaSegurua1', 1),
('Jon', 'Perez Garcia', '87654321B', '1985-11-20', 'gizona', 'tok_mastercard_5555', 'Kale Nagusia 30', 2, '48001', '611222333', 'jon.perez@gmail.com', 'Gaztelania', '123456Jon', 1),
('Teknologia Berriak SL', NULL, 'B99887766', NULL, NULL, 'tok_amex_9090', 'Jundiz Industrialdea, Pab 5', 3, '01015', '945111222', 'info@teknologiaberriak.com', 'Euskara', 'admin2024', 1),
('Alex', 'Etxebarria', '44556677C', '2002-02-10', 'ez-binarioa', NULL, 'Estafeta Kalea 12', 4, '31001', '666777888', 'alex.etxe@protonmail.com', 'Ingelesa', 'alex_pass_secure', 1),
('Sarah', 'Dubois', 'X1234567Z', '1995-07-30', 'emakumea', 'tok_cb_3333', 'Rue du Port 5', 5, '64200', '+33612345678', 'sarah.dubois@orange.fr', 'Frantsesa', 'monmotdepasse', 0),

-- 30 bezero berri
('Mikel', 'Arregi Iturbe', '11223344D', '1988-03-12', 'gizona', 'tok_visa_1111', 'Artekale 5, 1.esk', 2, '48005', '600111222', 'mikel.arregi@euskaltel.net', 'Euskara', 'mikel88pass', 1),
('Ainhoa', 'Zubizarreta Marañon', '22334455E', '1992-09-25', 'emakumea', 'tok_mc_2222', 'Nafarroa Etorbidea 22', 7, '20301', '622333444', 'ainhoa.zubi@gmail.com', 'Euskara', 'zubi92safe', 1),
('Iker', 'Lasa Otegi', '33445566F', '1980-12-05', 'gizona', NULL, 'San Francisco kalea 3', 10, '20400', '633444555', 'iker.lasa@outlook.com', 'Gaztelania', 'iker1980_!', 1),
('Maite', 'Ugarteburu Solozabal', '44556677G', '1998-06-18', 'emakumea', 'tok_visa_4444', 'Urbieta kalea 40', 1, '20006', '644555666', 'maite.uga@email.com', 'Euskara', 'maite_pass', 1),
('Unai', 'Badiola Askasibar', '55667788H', '1975-01-30', 'gizona', 'tok_mc_5555', 'Bidebarrieta 12', 5, '20600', '655666777', 'unai.badiola@terra.es', 'Gaztelania', 'unai75old', 1),
('Amaia', 'Iparragirre Garmendia', '66778899I', '1994-08-14', 'emakumea', NULL, 'Kondeko Aldapa 2', 13, '20730', '666777888', 'amaia.ipar@gmail.com', 'Euskara', 'amaia94!', 1),
('Kepa', 'Bilbao Zenarruzabeitia', '77889900J', '1982-10-22', 'gizona', 'tok_visa_7777', 'Lurgorri kalea 15', 22, '48300', '677888999', 'kepa.bilbao@gernika.eus', 'Euskara', 'kepa_bak', 1),
('Nerea', 'Mendizabal Alkorta', '88990011K', '1991-04-03', 'emakumea', 'tok_mc_8888', 'Zubiaurre kalea 8', 25, '48260', '688999000', 'nerea.mendi@yahoo.com', 'Euskara', 'nerea91sql', 1),
('Gorka', 'Otxoa de Eribe', '99001122L', '1987-11-11', 'gizona', NULL, 'Avenida Gasteiz 55', 3, '01008', '699000111', 'gorka.otxoa@araba.eus', 'Gaztelania', 'gorka87gs', 1),
('Leire', 'Apraiz Larrañaga', '00112233M', '2000-02-28', 'emakumea', 'tok_visa_0000', 'Itxas Aurre 4', 23, '48370', '611000222', 'leire.apraiz@bermeo.com', 'Euskara', 'leire2000', 1),
('Euskal Janariak SL', NULL, 'B11223344', NULL, NULL, 'tok_amex_1212', 'Industrialdea 4, Pab 2', 21, '48200', '946202020', 'pedidos@euskaljanariak.eus', 'Euskara', 'janari_2024', 1),
('Jean-Pierre', 'Etcheberry', 'X9988776W', '1970-05-20', 'gizona', 'tok_cb_9999', 'Rue Mazagran 12', 40, '64100', '+33559112233', 'jp.etche@orange.fr', 'Frantsesa', 'jp_baiona', 1),
('Miren', 'Agirre Ganboa', '22114433N', '1993-07-07', 'emakumea', NULL, 'Kale Berria 45', 33, '31200', '633112244', 'miren.agirre@navarra.es', 'Gaztelania', 'miren93nav', 1),
('Beñat', 'Gaztelumendi Arana', '33225544O', '1986-09-12', 'gizona', 'tok_mc_3322', 'Txirrita kalea 2', 8, '20100', '644223355', 'benat.gazte@bertso.eus', 'Euskara', 'benat_pass', 1),
('Lorea', 'Etxaniz Mendieta', '44336655P', '1997-12-25', 'emakumea', 'tok_visa_4433', 'Donibane kalea 10', 54, '20750', '655334466', 'lorea.etxaniz@gmail.com', 'Euskara', 'lorea97Xmas', 1),
('Joseba', 'Iraola Mujika', '55447766Q', '1968-02-14', 'gizona', NULL, 'Olandixo 1', 11, '20500', '666445577', 'joseba.iraola@mondragon.edu', 'Euskara', 'joseba68', 1),
('Sonia', 'Rodriguez Diez', '66558877R', '1984-11-30', 'emakumea', 'tok_visa_6655', 'Gran Via 22', 2, '48001', '677556688', 'sonia.rodriguez@hotmail.com', 'Gaztelania', 'sonia84bilbo', 1),
('Patxi', 'Lopez de Uralde', '77669988S', '1978-04-18', 'gizona', NULL, 'Postas 14', 3, '01001', '688667799', 'patxi.uralde@euskadi.eus', 'Euskara', 'patxi78_araba', 1),
('Ines', 'Santamaria Ruiz', '88770099T', '1995-10-05', 'emakumea', 'tok_mc_8877', 'Avenida Sancho el Fuerte 2', 4, '31007', '699778800', 'ines.santa@gmail.com', 'Gaztelania', 'ines95pamplona', 1),
('Iñaki', 'Garmendia Otaegi', '99881100U', '1989-06-22', 'gizona', 'tok_visa_9988', 'Idiazabal kalea 5', 14, '20200', '600889911', 'inaki.gar@outlook.es', 'Euskara', 'inaki89beasain', 1),
('Izaskun', 'Larrañaga Azurmendi', '11225566V', '1992-01-15', 'emakumea', NULL, 'Nafarroa Etorbidea 1', 10, '20400', '611990022', 'izaskun.larra@gmail.com', 'Euskara', 'izaskun92', 1),
('Bakartxo', 'Ruiz de Gauna', '22336677W', '1981-05-30', 'emakumea', 'tok_visa_2233', 'Estafeta 50', 4, '31001', '622001133', 'bakartxo.ruiz@nafarroa.eus', 'Euskara', 'bakartxo81', 1),
('Xabier', 'Alberdi Elorza', '33447788X', '1996-08-08', 'gizona', 'tok_mc_3344', 'San Pelayo 14', 6, '20800', '633112244', 'xalberdi@gmail.com', 'Euskara', 'xabi96zarautz', 1),
('Maialen', 'Lujanbio Galarraga', '44558899Y', '1976-11-20', 'emakumea', NULL, 'Kale Nagusia 1', 9, '20120', '644223355', 'maialen.lujan@bertso.eus', 'Euskara', 'maialen76', 1),
('Gaizka', 'Toquero Pinedo', '55669900Z', '1984-08-09', 'gizona', 'tok_visa_5566', 'Zaramaga kalea 10', 3, '01013', '655334466', 'gaizka.toquero@ath.eus', 'Gaztelania', 'gaizka24', 1),
('Edurne', 'Pasaban Lizarribar', '66770011A', '1973-08-01', 'emakumea', 'tok_mc_6677', 'Tolosa etorbidea 5', 1, '20018', '666445577', 'edurne.pasaban@mendia.eus', 'Euskara', 'edurne14x8000', 1),
('Ander', 'Iturraspe Derteano', '77881122B', '1989-03-08', 'gizona', NULL, 'Ibaizabal 4', 2, '48011', '677556688', 'ander.itu@gmail.com', 'Gaztelania', 'ander89bilbo', 1),
('Olatz', 'Salvador Elkano', '88992233C', '1990-10-10', 'emakumea', 'tok_visa_8899', 'Egia kalea 12', 1, '20012', '688667799', 'olatz.salvador@musika.eus', 'Euskara', 'olatz90pass', 1),
('Xabi', 'Prieto Argarate', '99003344D', '1983-08-29', 'gizona', 'tok_mc_9900', 'Anoeta pasealekua 1', 1, '20014', '699778800', 'xabi.prieto@reala.eus', 'Euskara', 'xabi10kap', 1),
('Itziar', 'Ituño Martinez', '11004455E', '1974-06-18', 'emakumea', NULL, 'Basauri kalea 5', 20, '48970', '611889901', 'itziar.ituno@gmail.com', 'Euskara', 'itziar74', 1);

-- 4. PRODUKTUAK ETA SUBKLASEAK (IRUDIEKIN EGUNERATUA)
INSERT INTO produktuak (
    id_produktua, izena, deskribapena, hornitzaile_id, biltegi_id, kategoria_id, 
    marka, mota, produktu_egoera, salmenta_prezioa, stock, salgai, irudia_url, zergak_ehunekoa
) VALUES
(1, 'MacBook Air 11" (2014)', 'Eramangarri ultra-trinkoa, eguneroko lanetarako oraindik balekoa.', 1, 1, 1, 'Apple', 'Eramangarria', 'Berritua B', 350.00, 5, TRUE, '../produktuen_irudiak/1_mac_book_air_2024.jpg', 21.00),
(2, 'Dell XPS 13 Plus', 'Pantaila ia ertz gabea, Windows 11rako optimizatua.', 1, 1, 1, 'Dell', 'Eramangarria', 'Berria', 1499.00, 10, TRUE, '../produktuen_irudiak/2_Lenovo_ThinkPad_X230.jpg', 21.00),
(3, 'Lenovo ThinkPad X1', 'Enpresentzako estandarra, karbono zuntzezko akaberarekin.', 2, 2, 1, 'Lenovo', 'Eramangarria', 'Berria', 1850.00, 20, TRUE, '../produktuen_irudiak/3_lenovo_thinkpad_x1.jpg', 21.00),
(4, 'HP Spectre x360', 'Bihurgarria, tablet moduan erabil daiteke.', 1, 1, 1, 'HP', 'Eramangarria', 'Berritua A', 950.00, 5, TRUE, '../produktuen_irudiak/4_hp_spectre_x360.jpg', 21.00),
(5, 'Asus ROG Zephyrus', 'Gaming eramangarria, RTX 4070 grafikoarekin.', 1, 2, 1, 'Asus', 'Eramangarria', 'Berria', 2300.00, 8, TRUE, '../produktuen_irudiak/5_Asus_ROG_Gaming_Beast.jpg', 21.00),
(6, 'Acer Swift 5', 'Oso arina, bidaiatzeko aproposa.', 2, 1, 1, 'Acer', 'Eramangarria', 'Berria', 899.00, 12, TRUE, '../produktuen_irudiak/6_acer_swift_5.jpg', 21.00),
(7, 'Microsoft Surface Laptop 5', 'Ukimen-pantaila eta akabera metalikoa.', 1, 1, 1, 'Microsoft', 'Eramangarria', 'Berritua B', 750.00, 3, TRUE, '../produktuen_irudiak/7_Acer_Aspire_One_Netbook.jpg', 21.00),
(8, 'Razer Blade 15', 'Diseinu beltza, RGB teklatua eta potentzia gorena.', 1, 2, 1, 'Razer', 'Eramangarria', 'Berria', 2800.00, 4, TRUE, '../produktuen_irudiak/8_razer_blade_15.jpg', 21.00),
(9, 'LG Gram 17', '17 hazbeteko pantaila baina pisu oso txikia.', 2, 1, 1, 'LG', 'Eramangarria', 'Berria', 1350.00, 7, TRUE, '../produktuen_irudiak/9_lg_gram_17.jpg', 21.00),
(10, 'MacBook Air M2', 'Isila, haizagailurik gabea eta bateria luzea.', 1, 1, 1, 'Apple', 'Eramangarria', 'Berria', 1299.00, 25, TRUE, '../produktuen_irudiak/10_macbook_air_m2.jpg', 21.00),
(11, 'HP Omen 45L', 'Gaming dorre aurreratua hozte sistema bereziarekin.', 1, 2, 1, 'HP', 'Mahai-gainekoa', 'Berria', 2499.00, 5, TRUE, '../produktuen_irudiak/11_hp_omen_45l.jpg', 21.00),
(12, 'Dell OptiPlex 7000', 'Bulegorako ordenagailu trinkoa eta fidagarria.', 2, 1, 1, 'Dell', 'Mahai-gainekoa', 'Berria', 650.00, 30, TRUE, '../produktuen_irudiak/12_dell_optiplex_7000.jpg', 21.00),
(13, 'Apple Mac Mini M2', 'Txikia baina matoia, mahaigain garbietarako.', 1, 1, 1, 'Apple', 'Mahai-gainekoa', 'Berria', 699.00, 15, TRUE, '../produktuen_irudiak/13_apple_mac_mini_m2.jpg', 21.00),
(14, 'Lenovo Legion Tower', 'RGB argiak eta RTX grafikoa jokoetarako.', 1, 2, 1, 'Lenovo', 'Mahai-gainekoa', 'Berria', 1200.00, 8, TRUE, '../produktuen_irudiak/14_Alienware_Aurora_R15.jpg', 21.00),
(15, 'Custom PC Creator', 'Eduki sortzaileentzako muntatutako ordenagailua.', 2, 1, 1, 'Custom', 'Mahai-gainekoa', 'Berritua A', 1100.00, 2, TRUE, '../produktuen_irudiak/15_PowerMac_G4_Retro.jpg', 21.00),
(16, 'Corsair One i300', 'Formatu oso txikia (Mini-ITX) baina oso potentea.', 1, 2, 1, 'Corsair', 'Mahai-gainekoa', 'Berria', 3500.00, 2, TRUE, '../produktuen_irudiak/16_corsair_one_i300.jpg', 21.00),
(17, 'Acer Predator Orion', 'Diseinu futurista eta aireztapen bikaina.', 1, 2, 1, 'Acer', 'Mahai-gainekoa', 'Berria', 1800.00, 4, TRUE, '../produktuen_irudiak/17_acer_predator_orion.jpg', 21.00),
(18, 'HP All-in-One 27', 'Ordenagailua eta pantaila dena batean.', 2, 1, 1, 'HP', 'Mahai-gainekoa', 'Berria', 950.00, 10, TRUE, '../produktuen_irudiak/18_iMac_G5_Styled_Desktop.jpg', 21.00),
(19, 'MSI Trident 3', 'Kontsola itxurako PCa, egongelarako.', 1, 2, 1, 'MSI', 'Mahai-gainekoa', 'Berritua B', 800.00, 3, TRUE, '../produktuen_irudiak/19_MSI_Infinite_X.jpg', 21.00),
(20, 'Apple Mac Studio', 'Profesionalentzako errendimendu maximoa.', 1, 1, 1, 'Apple', 'Mahai-gainekoa', 'Berria', 2399.00, 6, TRUE, '../produktuen_irudiak/20_apple_mac_studio.jpg', 21.00),
(21, 'iPhone 15 Pro', 'Titaniozko gorputza eta A17 Pro txipa.', 1, 1, 2, 'Apple', 'Mugikorra', 'Berria', 1209.00, 50, TRUE, '../produktuen_irudiak/21_iphone_15_pro.jpg', 21.00),
(22, 'Samsung Galaxy S24 Ultra', 'AI integratua eta S-Pen arkatza barne.', 2, 1, 2, 'Samsung', 'Mugikorra', 'Berria', 1459.00, 40, TRUE, '../produktuen_irudiak/22_samsung_galaxy_s24_ultra.jpg', 21.00),
(23, 'Nexus 4 Classic', 'Android telefono klasikoa, bildumazaleentzat edo oinarrizko erabilerarako.', 1, 2, 2, 'Google', 'Mugikorra', 'Berritua B', 80.00, 3, TRUE, '../produktuen_irudiak/1_Nexus_Phone_4.jpg', 21.00),
(24, 'Xiaomi 13T Pro', 'Leica kamerak eta karga ultra-azkarra.', 2, 2, 2, 'Xiaomi', 'Mugikorra', 'Berria', 799.00, 25, TRUE, '../produktuen_irudiak/24_xiaomi_13t_pro.jpg', 21.00),
(25, 'OnePlus 11', 'Errendimendu bikaina prezio lehiakorrean.', 2, 1, 2, 'OnePlus', 'Mugikorra', 'Berritua A', 550.00, 10, TRUE, '../produktuen_irudiak/25_oneplus_11.jpg', 21.00),
(26, 'Sony Xperia 1 V', 'Zinemako pantaila formatua (21:9).', 1, 2, 2, 'Sony', 'Mugikorra', 'Berria', 1100.00, 5, TRUE, '../produktuen_irudiak/26_sony_xperia_1_v.jpg', 21.00),
(27, 'Nothing Phone (2)', 'Atzealde gardena eta LED interfazea.', 2, 1, 2, 'Nothing', 'Mugikorra', 'Berria', 650.00, 15, TRUE, '../produktuen_irudiak/27_nothing_phone_2.jpg', 21.00),
(28, 'Samsung Galaxy Z Flip 5', 'Tolestagarria eta poltsikoan eramateko erosoa.', 2, 1, 2, 'Samsung', 'Mugikorra', 'Berria', 999.00, 12, TRUE, '../produktuen_irudiak/28_Galaxy_Z_Fold_5.jpg', 21.00),
(29, 'iPhone 13 Mini', 'Tamaina txikia, esku bakarrarekin erabiltzeko.', 1, 1, 2, 'Apple', 'Mugikorra', 'Berritua B', 450.00, 8, TRUE, '../produktuen_irudiak/29_iphone_13_mini.jpg', 21.00),
(30, 'Motorola Edge 40', 'Diseinu mehea eta pantaila kurbatua.', 2, 2, 2, 'Motorola', 'Mugikorra', 'Berria', 499.00, 20, TRUE, '../produktuen_irudiak/30_motorola_edge_40.jpg', 21.00),
(31, 'Asus Zenfone 10', 'Trinkoa baina oso indartsua.', 1, 1, 2, 'Asus', 'Mugikorra', 'Berria', 750.00, 8, TRUE, '../produktuen_irudiak/31_asus_zenfone_10.jpg', 21.00),
(32, 'Realme GT 3', 'Munduko kargatze azkarrena (240W).', 2, 2, 2, 'Realme', 'Mugikorra', 'Berria', 600.00, 10, TRUE, '../produktuen_irudiak/32_realme_gt_3.jpg', 21.00),
(33, 'iPhone SE (2022)', 'Klasikoa TouchID botoiarekin.', 1, 1, 2, 'Apple', 'Mugikorra', 'Berria', 429.00, 30, TRUE, '../produktuen_irudiak/33_iphone_se_2022.jpg', 21.00),
(34, 'Honor Magic 5 Pro', 'Kamera sistema oso aurreratua.', 2, 2, 2, 'Honor', 'Mugikorra', 'Berria', 900.00, 7, TRUE, '../produktuen_irudiak/34_Huawei_Mate_60_Pro.jpg', 21.00),
(35, 'Oppo Find X5', 'Diseinu futurista eta material premiumak.', 2, 1, 2, 'Oppo', 'Mugikorra', 'Berritua A', 400.00, 5, TRUE, '../produktuen_irudiak/35_oppo_find_x5.jpg', 21.00),
(36, 'iPad Pro 12.9', 'M2 txipa eta Liquid Retina XDR pantaila.', 1, 1, 2, 'Apple', 'Tableta', 'Berria', 1449.00, 10, TRUE, '../produktuen_irudiak/36_ipad_pro_129.jpg', 21.00),
(37, 'Samsung Galaxy Tab S9', 'Urarekiko erresistentzia eta pantaila bikaina.', 2, 1, 2, 'Samsung', 'Tableta', 'Berria', 899.00, 15, TRUE, '../produktuen_irudiak/37_samsung_galaxy_tab_s9.jpg', 21.00),
(38, 'iPad Air 5', 'M1 txipa, oreka perfektua potentzia eta prezioan.', 1, 1, 2, 'Apple', 'Tableta', 'Berria', 769.00, 20, TRUE, '../produktuen_irudiak/38_ipad_air_5.jpg', 21.00),
(39, 'Microsoft Surface Pro 9', 'Ordenagailua eta tableta gailu berean.', 1, 2, 2, 'Microsoft', 'Tableta', 'Berria', 1100.00, 8, TRUE, '../produktuen_irudiak/39_Surface_Pro_X.jpg', 21.00),
(40, 'Lenovo Tab P11 Pro', 'Multimedia kontsumorako aproposa.', 2, 2, 2, 'Lenovo', 'Tableta', 'Berritua A', 350.00, 12, TRUE, '../produktuen_irudiak/40_lenovo_tab_p11_pro.jpg', 21.00),
(41, 'Xiaomi Pad 6', 'Kalitate/prezio erlazio ezin hobea.', 2, 2, 2, 'Xiaomi', 'Tableta', 'Berria', 399.00, 25, TRUE, '../produktuen_irudiak/41_xiaomi_pad_6.jpg', 21.00),
(42, 'iPad Mini 6', 'Oso txikia, oharrak hartzeko perfektua.', 1, 1, 2, 'Apple', 'Tableta', 'Berria', 649.00, 10, TRUE, '../produktuen_irudiak/42_ipad_mini_6.jpg', 21.00),
(43, 'Amazon Fire Max 11', 'Oinarrizko erabilerarako eta irakurketarako.', 2, 1, 2, 'Amazon', 'Tableta', 'Berria', 249.00, 30, TRUE, '../produktuen_irudiak/43_iPad_Mini_1st_Gen.jpg', 21.00),
(44, 'Huawei MatePad Pro', 'HarmonyOS sistema eragilearekin.', 2, 2, 2, 'Huawei', 'Tableta', 'Zehazteko', 500.00, 5, FALSE, '../produktuen_irudiak/44_Galaxy_Tab_101_Old.jpg', 21.00),
(45, 'Google Pixel Tablet', 'Base bozgorailuarekin dator.', 1, 1, 2, 'Google', 'Tableta', 'Berria', 679.00, 7, TRUE, '../produktuen_irudiak/45_Nexus_7_Tablet.jpg', 21.00),
(46, 'Dell PowerEdge R750', 'Rack zerbitzaria datu zentroetarako.', 1, 2, 6, 'Dell', 'Zerbitzaria', 'Berria', 4500.00, 2, TRUE, '../produktuen_irudiak/46_dell_poweredge_r750.jpg', 21.00),
(47, 'HPE ProLiant DL380', 'Munduko zerbitzaririk salduena, fidagarria.', 1, 2, 6, 'HPE', 'Zerbitzaria', 'Berria', 3800.00, 3, TRUE, '../produktuen_irudiak/47_hpe_proliant_dl380.jpg', 21.00),
(48, 'Lenovo ThinkSystem SR650', 'Eskalagarria eta errendimendu altukoa.', 2, 2, 6, 'Lenovo', 'Zerbitzaria', 'Berritua A', 2200.00, 1, TRUE, '../produktuen_irudiak/48_lenovo_thinksystem_sr650.jpg', 21.00),
(49, 'Synology RackStation', 'Biltegiratze masiborako NAS zerbitzaria.', 2, 1, 6, 'Synology', 'Zerbitzaria', 'Berria', 1500.00, 5, TRUE, '../produktuen_irudiak/49_Synology_NAS_Pro.jpg', 21.00),
(50, 'Cisco UCS C220', 'Birtualizaziorako optimizatua.', 1, 2, 6, 'Cisco', 'Zerbitzaria', 'Berritua B', 1200.00, 2, TRUE, '../produktuen_irudiak/50_cisco_ucs_c220.jpg', 21.00),
(51, 'LG UltraGear 27', 'Gaming pantaila azkarra 144Hz-ekin.', 2, 1, 3, 'LG', 'Pantaila', 'Berria', 299.00, 20, TRUE, '../produktuen_irudiak/51_lg_ultragear_27.jpg', 21.00),
(52, 'Dell UltraSharp 32', '4K bereizmena diseinu grafikorako.', 1, 1, 3, 'Dell', 'Pantaila', 'Berria', 850.00, 5, TRUE, '../produktuen_irudiak/52_dell_ultrasharp_32.jpg', 21.00),
(53, 'Samsung Odyssey G9', 'Pantaila ultra-zovala eta kurbatua.', 2, 2, 3, 'Samsung', 'Pantaila', 'Berria', 1200.00, 3, TRUE, '../produktuen_irudiak/53_samsung_odyssey_g9.jpg', 21.00),
(54, 'BenQ PD2700U', 'Kolore zehatzak (sRGB 100%).', 2, 1, 3, 'BenQ', 'Pantaila', 'Berritua A', 350.00, 8, TRUE, '../produktuen_irudiak/54_benq_pd2700u.jpg', 21.00),
(55, 'Asus ProArt', 'Argazkilari eta bideo editoreentzako.', 1, 1, 3, 'Asus', 'Pantaila', 'Berria', 500.00, 6, TRUE, '../produktuen_irudiak/55_Samsung_Odyssey_OLED_G8.jpg', 21.00),
(56, 'HP 24mh', 'Bulegorako oinarrizko pantaila ergonomikoa.', 2, 1, 3, 'HP', 'Pantaila', 'Berria', 149.00, 40, TRUE, '../produktuen_irudiak/56_hp_24mh.jpg', 21.00),
(57, 'MSI Optix MAG', 'Pantaila kurbatua murgiltze esperientziarako.', 1, 2, 3, 'MSI', 'Pantaila', 'Berria', 220.00, 15, TRUE, '../produktuen_irudiak/57_msi_optix_mag.jpg', 21.00),
(58, 'Apple Studio Display', '5K bereizmena eta eraikuntza bikaina.', 1, 1, 3, 'Apple', 'Pantaila', 'Berria', 1779.00, 4, TRUE, '../produktuen_irudiak/58_apple_studio_display.jpg', 21.00),
(59, 'Philips Brilliance', 'Ultrawide formatua produktibitaterako.', 2, 1, 3, 'Philips', 'Pantaila', 'Berritua B', 400.00, 5, TRUE, '../produktuen_irudiak/59_philips_brilliance.jpg', 21.00),
(60, 'AOC 24G2', 'Prezio-kalitate erlazio onena jokoetarako.', 2, 2, 3, 'AOC', 'Pantaila', 'Berria', 179.00, 25, TRUE, '../produktuen_irudiak/60_aoc_24g2.jpg', 21.00),
(61, 'ViewSonic Elite', 'Kolore biziak eta erantzun azkarra.', 1, 2, 3, 'ViewSonic', 'Pantaila', 'Berria', 550.00, 7, TRUE, '../produktuen_irudiak/61_BenQ_DesignVue_4K.jpg', 21.00),
(62, 'Lenovo ThinkVision', 'USB-C konexioa duen dock integratua.', 2, 1, 3, 'Lenovo', 'Pantaila', 'Berria', 320.00, 12, TRUE, '../produktuen_irudiak/62_lenovo_thinkvision.jpg', 21.00),
(63, 'Gigabyte M27Q', 'KVM switch integratua.', 1, 2, 3, 'Gigabyte', 'Pantaila', 'Berria', 340.00, 9, TRUE, '../produktuen_irudiak/63_gigabyte_m27q.jpg', 21.00),
(64, 'Eizo ColorEdge', 'Profesionalentzako kalibrazio zehatza.', 2, 1, 3, 'Eizo', 'Pantaila', 'Zehazteko', 1200.00, 2, FALSE, '../produktuen_irudiak/64_eizo_coloredge.jpg', 21.00),
(65, 'Huawei MateView', 'Formatu karratuagoa (3:2) kodetzeko.', 2, 2, 3, 'Huawei', 'Pantaila', 'Berria', 599.00, 6, TRUE, '../produktuen_irudiak/65_huawei_mateview.jpg', 21.00),
(66, 'Windows 7 Professional', 'Egonkortasunagatik ezaguna den sistema zaharra.', 3, 1, 5, 'Microsoft', 'Softwarea', 'Berritua B', 25.00, 10, TRUE, '../produktuen_irudiak/66_Windows_7_profesional.jpg', 21.00),
(67, 'Office 2010 Student', 'Bertsio klasikoa, harpidetzarik gabea.', 3, 1, 5, 'Microsoft', 'Softwarea', 'Berritua B', 45.00, 15, TRUE, '../produktuen_irudiak/67_office_2010_student.jpg', 21.00),
(68, 'Adobe Photoshop CC', '1 urteko harpidetza diseinatzaileentzat.', 3, 2, 5, 'Adobe', 'Softwarea', 'Berria', 290.00, 100, TRUE, '../produktuen_irudiak/68_VMware_Workstation_Pro.jpg', 21.00),
(69, 'Norton Antivirus 2012', 'Segurtasun pakete klasikoa sistema zaharrentzat.', 3, 1, 5, 'Kaspersky', 'Softwarea', 'Berritua B', 15.00, 20, TRUE, '../produktuen_irudiak/69_norton_antibirus.jpg', 21.00),
(70, 'Windows Server 2022', 'Zerbitzarietarako sistema estandarra.', 3, 2, 5, 'Microsoft', 'Softwarea', 'Berria', 800.00, 20, TRUE, '../produktuen_irudiak/70_windows_server_2022.jpg', 21.00),
(71, 'NordVPN 1 Year', 'Nabigazio segurua eta pribatua.', 3, 1, 5, 'NordSec', 'Softwarea', 'Berria', 59.00, 150, TRUE, '../produktuen_irudiak/71_Cisco_AnyConnect_30.jpg', 21.00),
(72, 'AutoCAD 2007 Classic', 'Diseinu industrialerako bertsio historikoa.', 3, 2, 5, 'Autodesk', 'Softwarea', 'Berritua B', 350.00, 2, TRUE, '../produktuen_irudiak/72_autocad_2007.jpg', 21.00),
(73, 'Norton 360 Deluxe', 'Segurtasuna eta VPN integratua.', 3, 1, 5, 'Norton', 'Softwarea', 'Berria', 39.99, 80, TRUE, '../produktuen_irudiak/73_norton_360_deluxe.jpg', 21.00),
(74, 'Red Hat Enterprise Linux', 'Enpresen zerbitzarietarako Linux.', 3, 2, 5, 'Red Hat', 'Softwarea', 'Berria', 349.00, 10, TRUE, '../produktuen_irudiak/74_red_hat_enterprise_linux.jpg', 21.00),
(75, 'VMware vSphere', 'Birtualizazio plataforma profesionala.', 3, 2, 5, 'VMware', 'Softwarea', 'Berria', 500.00, 8, TRUE, '../produktuen_irudiak/75_Docker_Desktop_Pro.jpg', 21.00),
(76, 'Ubuntu Desktop 24.04', 'Linux banaketa ezagunena eta erabilerraza.', 3, 1, 5, 'Canonical', 'Softwarea', 'Berria', 0.00, 999, TRUE, '../produktuen_irudiak/76_ubuntu_desktop_2404.jpg', 21.00),
(77, 'LibreOffice', 'Ofimatika suite osoa eta kode irekikoa.', 3, 1, 5, 'The Document Foundation', 'Softwarea', 'Berria', 0.00, 999, TRUE, '../produktuen_irudiak/77_libreoffice.jpg', 21.00),
(78, 'Blender 4.0', '3D sorkuntza eta animaziorako suitea.', 3, 2, 5, 'Blender Foundation', 'Softwarea', 'Berria', 0.00, 999, TRUE, '../produktuen_irudiak/78_blender_40.jpg', 21.00),
(79, 'GIMP', 'Irudiak editatzeko tresna profesionala.', 3, 1, 5, 'GIMP Team', 'Softwarea', 'Berria', 0.00, 999, TRUE, '../produktuen_irudiak/79_Pro_Tools_Audio_Studio.jpg', 21.00),
(80, 'VLC Media Player', 'Formatua ia guztiak irakurtzen dituen erreproduzigailua.', 3, 1, 5, 'VideoLAN', 'Softwarea', 'Berria', 0.00, 999, TRUE, '../produktuen_irudiak/80_vlc_media_player.jpg', 21.00);

INSERT INTO eramangarriak (id_produktua, prozesadorea, ram_gb, diskoa_gb, pantaila_tamaina, bateria_wh, sistema_eragilea, pisua_kg) VALUES
(1, 'Apple M3 Pro', 18, 512, 14.2, 70, 'macOS', 1.6),
(2, 'Intel Core i7-1360P', 16, 1024, 13.4, 55, 'Windows 11', 1.23),
(3, 'Intel Core i7-1260P', 32, 1024, 14.0, 57, 'Windows 11 Pro', 1.12),
(4, 'Intel Core i5-1135G7', 8, 256, 13.5, 60, 'Windows 10', 1.3),
(5, 'AMD Ryzen 9 7940HS', 32, 2048, 14.0, 76, 'Windows 11', 1.7),
(6, 'Intel Core i5-1240P', 16, 512, 14.0, 56, 'Windows 11', 1.2),
(7, 'Intel Core i5-1235U', 8, 256, 13.5, 47, 'Windows 11', 1.27),
(8, 'Intel Core i9-13800H', 32, 1024, 15.6, 80, 'Windows 11', 2.01),
(9, 'Intel Core i7-1360P', 16, 1024, 17.0, 80, 'Windows 11', 1.35),
(10, 'Apple M2', 8, 256, 13.6, 52, 'macOS', 1.24);

INSERT INTO mahai_gainekoak (id_produktua, prozesadorea, plaka_basea, ram_gb, diskoa_gb, txartel_grafikoa, elikatze_iturria_w, kaxa_formatua) VALUES
(11, 'Intel Core i9-13900K', 'Z790', 64, 2048, 'RTX 4090', 1200, 'ATX'),
(12, 'Intel Core i5-12500', 'B660', 16, 512, 'Intel UHD 770', 300, 'Micro-ATX'),
(13, 'Apple M2 Chip', 'Integrated', 8, 256, 'Apple GPU 10-core', 150, 'Mini-ITX'),
(14, 'AMD Ryzen 7 5800X', 'B550', 32, 1024, 'RTX 3070', 750, 'ATX'),
(15, 'AMD Ryzen 9 5900X', 'X570', 64, 2048, 'RX 6800 XT', 850, 'ATX'),
(16, 'Intel Core i9-12900K', 'Z690I', 32, 2048, 'RTX 3080 Ti', 750, 'Mini-ITX'),
(17, 'Intel Core i7-12700K', 'Z690', 32, 1024, 'RTX 3080', 800, 'ATX'),
(18, 'Intel Core i7-12700T', 'Custom HP', 16, 512, 'Intel Iris Xe', 180, 'Mini-ITX'),
(19, 'Intel Core i5-11400F', 'H510', 16, 512, 'RTX 3060 Aero', 450, 'Mini-ITX'),
(20, 'Apple M1 Max', 'Integrated', 32, 512, 'Apple GPU 24-core', 370, 'Mini-ITX');

INSERT INTO mugikorrak (id_produktua, pantaila_teknologia, pantaila_hazbeteak, biltegiratzea_gb, ram_gb, kamera_nagusa_mp, bateria_mah, sistema_eragilea, sareak) VALUES
(21, 'OLED', 6.1, 128, 8, 48, 3274, 'iOS', '5G'),
(22, 'AMOLED', 6.8, 256, 12, 200, 5000, 'Android', '5G'),
(23, 'OLED', 6.7, 128, 12, 50, 5050, 'Android', '5G'),
(24, 'AMOLED', 6.6, 256, 12, 50, 5000, 'Android', '5G'),
(25, 'AMOLED', 6.7, 128, 8, 50, 5000, 'Android', '5G'),
(26, 'OLED', 6.5, 256, 12, 48, 5000, 'Android', '5G'),
(27, 'OLED', 6.7, 256, 12, 50, 4700, 'Android', '5G'),
(28, 'AMOLED', 6.7, 256, 8, 12, 3700, 'Android', '5G'),
(29, 'OLED', 5.4, 64, 4, 12, 2438, 'iOS', '5G'),
(30, 'OLED', 6.5, 256, 8, 50, 4400, 'Android', '5G'),
(31, 'AMOLED', 5.9, 128, 8, 50, 4300, 'Android', '5G'),
(32, 'AMOLED', 6.7, 256, 16, 50, 4600, 'Android', '5G'),
(33, 'LCD', 4.7, 64, 4, 12, 2018, 'iOS', '5G'),
(34, 'OLED', 6.8, 512, 12, 50, 5100, 'Android', '5G'),
(35, 'AMOLED', 6.5, 256, 8, 50, 4800, 'Android', '5G');

INSERT INTO tabletak (id_produktua, pantaila_hazbeteak, biltegiratzea_gb, konektibitatea, sistema_eragilea, bateria_mah, arkatzarekin_bateragarria) VALUES
(36, 12.9, 256, 'WiFi + Cellular', 'iPadOS', 10758, TRUE),
(37, 11.0, 128, 'WiFi', 'Android', 8400, TRUE),
(38, 10.9, 64, 'WiFi', 'iPadOS', 7600, TRUE),
(39, 13.0, 256, 'WiFi', 'Windows 11', 6500, TRUE),
(40, 11.2, 128, 'WiFi', 'Android', 8000, TRUE),
(41, 11.0, 128, 'WiFi', 'Android', 8840, TRUE),
(42, 8.3, 64, 'WiFi + Cellular', 'iPadOS', 5124, TRUE),
(43, 11.0, 64, 'WiFi', 'FireOS', 7500, FALSE),
(44, 12.6, 256, 'WiFi', 'HarmonyOS', 10050, TRUE),
(45, 10.9, 128, 'WiFi', 'Android', 7020, TRUE);

INSERT INTO zerbitzariak (id_produktua, prozesadore_nukleoak, ram_mota, disko_badiak, rack_unitateak, elikatze_iturri_erredundantea, raid_kontroladora) VALUES
(46, 24, 'DDR5', 12, 2, TRUE, 'PERC H750'),
(47, 20, 'DDR4', 8, 2, TRUE, 'HPE Smart Array'),
(48, 16, 'DDR4', 16, 2, TRUE, 'ThinkSystem RAID'),
(49, 4, 'DDR4', 12, 2, TRUE, 'Software RAID'),
(50, 12, 'DDR4', 8, 1, TRUE, 'Cisco 12G SAS');

INSERT INTO pantailak (id_produktua, hazbeteak, bereizmena, panel_mota, freskatze_tasa_hz, konexioak, kurbatura) VALUES
(51, 27.0, '2560x1440', 'IPS', 144, 'HDMI, DP', 'Flat'),
(52, 32.0, '3840x2160', 'IPS', 60, 'HDMI, DP, USB-C', 'Flat'),
(53, 49.0, '5120x1440', 'VA', 240, 'HDMI, DP', '1000R'),
(54, 27.0, '3840x2160', 'IPS', 60, 'HDMI, DP', 'Flat'),
(55, 27.0, '2560x1440', 'IPS', 75, 'HDMI, DP, USB-C', 'Flat'),
(56, 23.8, '1920x1080', 'IPS', 75, 'HDMI, VGA', 'Flat'),
(57, 24.0, '1920x1080', 'VA', 165, 'HDMI, DP', '1500R'),
(58, 27.0, '5120x2880', 'IPS', 60, 'Thunderbolt 3', 'Flat'),
(59, 34.0, '3440x1440', 'VA', 100, 'HDMI, DP', '1500R'),
(60, 24.0, '1920x1080', 'IPS', 144, 'HDMI, DP', 'Flat'),
(61, 32.0, '2560x1440', 'IPS', 175, 'HDMI, DP', 'Flat'),
(62, 27.0, '2560x1440', 'IPS', 60, 'USB-C, HDMI', 'Flat'),
(63, 27.0, '2560x1440', 'IPS', 170, 'HDMI, DP, USB-C', 'Flat'),
(64, 27.0, '2560x1440', 'IPS', 60, 'HDMI, DP, DVI', 'Flat'),
(65, 28.2, '3840x2560', 'IPS', 60, 'MiniDP, HDMI', 'Flat');

INSERT INTO softwareak (id_produktua, software_mota, lizentzia_mota, bertsioa, garatzailea, librea) VALUES
(66, 'Sistema Eragilea', 'Retail', '11 Pro', 'Microsoft', FALSE),
(67, 'Ofimatika', 'Retail', '2021', 'Microsoft', FALSE),
(68, 'Bestelakoak', 'Harpidetza', 'CC 2024', 'Adobe', FALSE),
(69, 'Antibirusa', 'Retail', '2024', 'Kaspersky', FALSE),
(70, 'Sistema Eragilea', 'OEM', 'Standard 2022', 'Microsoft', FALSE),
(71, 'Bestelakoak', 'Harpidetza', '1 Urte', 'NordSec', FALSE),
(72, 'Bestelakoak', 'Harpidetza', '2024', 'Autodesk', FALSE),
(73, 'Antibirusa', 'Retail', 'Deluxe', 'Norton', FALSE),
(74, 'Sistema Eragilea', 'Harpidetza', 'RHEL 9', 'Red Hat', FALSE),
(75, 'Bestelakoak', 'Retail', 'Standard 8', 'VMware', FALSE),
(76, 'Sistema Eragilea', 'GPL', '24.04 LTS', 'Canonical', TRUE),
(77, 'Ofimatika', 'LGPL', '7.6', 'Document Foundation', TRUE),
(78, 'Bestelakoak', 'GPL', '4.0', 'Blender Foundation', TRUE),
(79, 'Bestelakoak', 'GPL', '2.10', 'GIMP Team', TRUE),
(80, 'Bestelakoak', 'GPL', '3.0', 'VideoLAN', TRUE);

-- 5. AKATSAK ETA KONPONKETAK
INSERT INTO akatsak (id_akatsa, izena, deskribapena) VALUES
(1, 'Pantaila hautsita', 'Kristala edo panela puskatuta dago.'),
(2, 'Bateria ez da kargatzen', 'Konektatzean ez du kargarik hartzen.'),
(3, 'Disko gogorraren errorea', 'Sistemak ez du diskoa antzematen edo zarata egiten du.'),
(4, 'Sistema eragilea ez da abiarazten', 'Blue Screen edo errorea abiaraztean.'),
(5, 'Teklatua ez dabil', 'Tekla batzuk edo denak ez dute erantzuten.'),
(6, 'Uraren kaltea', 'Likidoa isuri zaio gainean.'),
(7, 'Haizagailuaren zarata', 'Zarata handia edo ez dabil.'),
(8, 'Berotze arazoak', 'Ordenagailua itzali egiten da berotasunagatik.'),
(9, 'WiFi konexio arazoak', 'Ez du sarerik harrapatzen.'),
(10, 'Bluetooth errorea', 'Ez du gailurik aurkitzen.'),
(11, 'Kamera ez dabil', 'Webkamera beltza edo errorea.'),
(12, 'Audioa ez da entzuten', 'Ez dago soinurik bozgorailuetatik.'),
(13, 'Mikrofonoa ez dabil', 'Ez da soinurik grabatzen.'),
(14, 'USB portuak hondatuta', 'Ez dute gailurik antzematen.'),
(15, 'Kargagailuaren konektorea', 'Holgura du edo puskatuta dago.'),
(16, 'Birus edo Malwarea', 'Ordenagailua motel edo publizitatea.'),
(17, 'Datuak berreskuratu', 'Ezabatu edo galdu diren datuak.'),
(18, 'Pasahitza ahaztuta', 'Ezin da sisteman sartu.'),
(19, 'Pantaila keinuka', 'Irudia joan-etorrian dabil.'),
(20, 'Bateriaren iraupen motza', 'Oso azkar agortzen da.'),
(21, 'Trackpad ez dabil', 'Sagua ez da mugitzen.'),
(22, 'Bisagra hautsita', 'Pantaila ez da ondo ixten/irekitzen.'),
(23, 'Karkasa hondatuta', 'Kolpea edo pitzadura plastikoan.'),
(24, 'BIOS blokeatuta', 'Ezin da BIOSera sartu.'),
(25, 'RAM memoriaren errorea', 'Pitidoak abiaraztean.'),
(26, 'Txartel grafikoaren errorea', 'Artefaktuak pantailan.'),
(27, 'Sare txartela hondatuta', 'Ethernet ez dabil.'),
(28, 'Botoiak ez dabiltza', 'Pizteko edo bolumen botoiak.'),
(29, 'Sentsoreak ez dabiltza', 'Giro, hurbiltasun sentsoreak...'),
(30, 'Mantentze orokorra', 'Garbiketa eta pasta termikoa aldatzea.');

INSERT INTO konponketak (produktua_id, langilea_id, hasiera_data, konponketa_egoera, akatsa_id, oharrak) VALUES
(1, 8, '2023-01-10 09:00:00', 'Konponduta', 1, 'Pantaila berria jarri da.'),
(5, 9, '2023-01-12 10:30:00', 'Prozesuan', 8, 'Pasta termikoa aldatu behar da.'),
(12, 10, '2023-01-15 11:00:00', 'Prozesuan', 4, 'Diskoa formatu behar da.'),
(22, 11, '2023-02-01 16:00:00', 'Konponduta', 2, 'Bateria aldatu da.'),
(30, 8, '2023-02-05 09:15:00', 'Konponezina', 6, 'Plaka basea herdoilduta.'),
(45, 9, '2023-02-10 12:00:00', 'Prozesuan', 15, 'Soldadura berria behar du.'),
(55, 10, '2023-02-20 09:30:00', 'Konponduta', 11, 'Driver-ak eguneratu dira.'),
(3, 11, '2023-03-01 10:00:00', 'Konponduta', 30, 'Haizagailuak garbitu dira.'),
(8, 8, '2023-03-05 11:45:00', 'Prozesuan', 5, 'Teklatu osoa eskatu dugu.'),
(15, 9, '2023-03-10 15:30:00', 'Konponduta', 16, 'Malwarebytes pasata.'),
(25, 10, '2023-03-15 17:00:00', 'Prozesuan', 1, 'Aurrekontua bidali zaio bezeroari.'),
(33, 11, '2023-04-01 09:00:00', 'Konponduta', 20, 'Bateria originala jarri da.'),
(40, 8, '2023-04-05 10:30:00', 'Prozesuan', 7, 'Haizagailu berria bidean.'),
(60, 9, '2023-04-10 11:00:00', 'Konponduta', 19, 'Kable flexa ondo konektatu da.'),
(70, 10, '2023-04-15 14:00:00', 'Konponezina', 26, 'Grafikoa integratuta dago, plaka aldatu behar.'),
(2, 11, '2023-05-01 16:30:00', 'Konponduta', 17, 'Recuva erabili dugu datuak ateratzeko.'),
(6, 8, '2023-05-05 09:00:00', 'Prozesuan', 3, 'SSD berria probatu behar da.'),
(9, 9, '2023-05-10 10:00:00', 'Prozesuan', 4, 'Windows berrinstalatzen.'),
(18, 10, '2023-05-15 11:30:00', 'Konponduta', 14, 'USB plaka aldatu da.'),
(28, 11, '2023-06-01 15:00:00', 'Konponduta', 22, 'Bisagra estutu da.'),
(35, 8, '2023-06-05 16:00:00', 'Prozesuan', 23, 'Karkasa berria bilatzen.'),
(42, 9, '2023-06-10 09:30:00', 'Konponduta', 28, 'Botoiaren flexa aldatu da.'),
(50, 10, '2023-06-15 10:45:00', 'Konponduta', 12, 'Audio driverrak.'),
(65, 11, '2023-07-01 12:00:00', 'Prozesuan', 9, 'WiFi txartela aldatzen.'),
(75, 8, '2023-07-05 14:30:00', 'Konponduta', 29, 'Sentsorea kalibratu da.'),
(10, 9, '2023-07-10 16:00:00', 'Konponduta', 2, 'Kargagailua zen arazoa, berria saldu zaio.'),
(20, 10, '2023-07-15 09:00:00', 'Prozesuan', 1, 'Pantaila ez dago stock-ean.'),
(38, 11, '2023-08-01 11:00:00', 'Konponduta', 13, 'Mikrofonoaren iragazkia garbitu.'),
(48, 8, '2023-08-05 13:00:00', 'Konponduta', 25, 'RAM moduluak trukatu dira.'),
(58, 9, '2023-08-10 15:15:00', 'Prozesuan', 18, 'Pasahitza reset egiten.');

-- 6. FITXAKETAK, ETA SARRERAK
INSERT INTO fitxaketak (id_fitxaketa, langilea_id, data, ordua, mota) VALUES
(1, 1, '2024-01-08', '08:00:00', 'Sarrera'), (2, 1, '2024-01-08', '16:00:00', 'Irteera'),
(3, 2, '2024-01-08', '08:05:00', 'Sarrera'), (4, 2, '2024-01-08', '15:00:00', 'Irteera'),
(5, 3, '2024-01-08', '09:00:00', 'Sarrera'), (6, 3, '2024-01-08', '17:00:00', 'Irteera'),
(7, 4, '2024-01-08', '09:10:00', 'Sarrera'), (8, 4, '2024-01-08', '13:00:00', 'Irteera'),
(9, 5, '2024-01-08', '08:55:00', 'Sarrera'), (10, 5, '2024-01-08', '18:00:00', 'Irteera'),
(11, 6, '2024-01-08', '09:00:00', 'Sarrera'), (12, 6, '2024-01-08', '18:00:00', 'Irteera'),
(13, 7, '2024-01-08', '09:05:00', 'Sarrera'), (14, 7, '2024-01-08', '17:30:00', 'Irteera'),
(15, 8, '2024-01-08', '08:00:00', 'Sarrera'), (16, 8, '2024-01-08', '14:00:00', 'Irteera'),
(17, 9, '2024-01-08', '14:00:00', 'Sarrera'), (18, 9, '2024-01-08', '20:00:00', 'Irteera'),
(19, 10,'2024-01-08', '08:00:00', 'Sarrera'), (20, 10, '2024-01-08', '16:00:00', 'Irteera'),
(21, 11,'2024-01-08', '10:00:00', 'Sarrera'), (22, 11, '2024-01-08', '19:00:00', 'Irteera'),
(23, 12,'2024-01-08', '07:00:00', 'Sarrera'), (24, 12, '2024-01-08', '15:00:00', 'Irteera'),
(25, 13,'2024-01-08', '07:30:00', 'Sarrera'), (26, 13, '2024-01-08', '15:30:00', 'Irteera'),
(27, 14,'2024-01-08', '08:00:00', 'Sarrera'), (28, 14, '2024-01-08', '16:00:00', 'Irteera'),
(29, 1, '2024-01-09', '08:00:00', 'Sarrera'), (30, 1, '2024-01-09', '16:00:00', 'Irteera'),
(31, 5, '2024-01-09', '08:50:00', 'Sarrera'), (32, 5, '2024-01-09', '18:10:00', 'Irteera'),
(33, 8, '2024-01-09', '08:02:00', 'Sarrera'), (34, 8, '2024-01-09', '14:05:00', 'Irteera'),
(35, 12,'2024-01-09', '07:05:00', 'Sarrera'), (36, 12, '2024-01-09', '15:00:00', 'Irteera');

INSERT INTO sarrerak (id_sarrera, hornitzailea_id, langilea_id, sarrera_egoera, data) VALUES
(1, 1, 12, 'Jasota', '2023-11-01 09:00:00'),
(2, 2, 13, 'Jasota', '2023-11-02 10:00:00'),
(3, 3, 14, 'Bidean', '2023-11-03 11:30:00'),
(4, 1, 12, 'Jasota', '2023-11-05 08:15:00'),
(5, 2, 12, 'Jasota', '2023-11-10 14:00:00'),
(6, 1, 13, 'Ezabatua', '2023-11-12 16:30:00'),
(7, 3, 14, 'Jasota', '2023-11-15 09:45:00'),
(8, 2, 13, 'Bidean', '2023-11-18 10:00:00'),
(9, 1, 12, 'Jasota', '2023-11-20 11:00:00'),
(10, 3, 14, 'Jasota', '2023-11-25 15:30:00'),
(11, 2, 12, 'Jasota', '2023-12-01 09:10:00'),
(12, 1, 13, 'Jasota', '2023-12-03 10:20:00'),
(13, 1, 12, 'Bidean', '2023-12-05 12:00:00'),
(14, 2, 14, 'Jasota', '2023-12-08 14:45:00'),
(15, 3, 13, 'Jasota', '2023-12-10 09:30:00'),
(16, 1, 12, 'Jasota', '2023-12-12 16:00:00'),
(17, 2, 14, 'Jasota', '2023-12-15 11:15:00'),
(18, 3, 12, 'Bidean', '2023-12-18 08:30:00'),
(19, 1, 13, 'Ezabatua', '2023-12-20 13:45:00'),
(20, 2, 14, 'Jasota', '2023-12-22 10:00:00'),
(21, 3, 12, 'Jasota', '2023-12-24 09:00:00'),
(22, 1, 12, 'Jasota', '2023-12-28 15:00:00'),
(23, 1, 14, 'Jasota', '2024-01-03 11:30:00'),
(24, 2, 13, 'Bidean', '2024-01-05 14:00:00'),
(25, 3, 12, 'Jasota', '2024-01-08 09:15:00'),
(26, 1, 14, 'Jasota', '2024-01-10 10:45:00'),
(27, 2, 12, 'Jasota', '2024-01-12 16:00:00'),
(28, 3, 13, 'Jasota', '2024-01-15 08:30:00'),
(29, 1, 12, 'Bidean', '2024-01-18 13:20:00'),
(30, 2, 14, 'Jasota', '2024-01-20 11:00:00');

INSERT INTO sarrera_lerroak (id_sarrera_lerroa, sarrera_id, produktua_id, kantitatea, sarrera_lerro_egoera) VALUES
(1, 1, 1, 10, 'Jasota'),
(2, 1, 2, 5, 'Jasota'),
(3, 2, 5, 20, 'Jasota'),
(4, 2, 10, 10, 'Jasota'),
(5, 3, 15, 5, 'Bidean'),
(6, 4, 20, 15, 'Jasota'),
(7, 5, 25, 8, 'Jasota'),
(8, 6, 30, 5, 'Ezabatua'),
(9, 7, 35, 12, 'Jasota'),
(10, 7, 40, 4, 'Jasota'),
(11, 8, 45, 10, 'Bidean'),
(12, 9, 50, 2, 'Jasota'),
(13, 10, 55, 6, 'Jasota'),
(14, 11, 60, 20, 'Jasota'),
(15, 12, 65, 5, 'Jasota'),
(16, 13, 70, 15, 'Bidean'),
(17, 14, 75, 4, 'Jasota'),
(18, 15, 80, 10, 'Jasota'),
(19, 16, 60, 8, 'Jasota'), 
(20, 17, 55, 50, 'Jasota'), 
(21, 18, 5, 100, 'Bidean'), 
(22, 19, 25, 20, 'Ezabatua'), 
(23, 20, 5, 10, 'Jasota'),
(24, 21, 15, 5, 'Jasota'),
(25, 22, 25, 8, 'Jasota'),
(26, 23, 35, 12, 'Jasota'),
(27, 24, 45, 10, 'Bidean'),
(28, 25, 55, 6, 'Jasota'),
(29, 26, 65, 5, 'Jasota'),
(30, 27, 75, 4, 'Jasota'),
(31, 28, 60, 8, 'Jasota'), 
(32, 29, 25, 20, 'Bidean'), 
(33, 30, 5, 10, 'Jasota');

/*
CREATE TABLE IF NOT EXISTS eskaerak (
    id_eskaera INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    bezeroa_id INT UNSIGNED NOT NULL,
    langilea_id INT UNSIGNED,
    data DATETIME DEFAULT CURRENT_TIMESTAMP,
    eguneratze_data DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    guztira_prezioa DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    
    faktura_zenbakia VARCHAR(20) UNIQUE NOT NULL,
	faktura_url VARCHAR(255),
    
    eskaera_egoera ENUM('Prestatzen', 'Osatua/Bidalita', 'Ezabatua') NOT NULL DEFAULT 'Prestatzen',
    
    CONSTRAINT fk_eskaera_bezeroa FOREIGN KEY (bezeroa_id) REFERENCES bezeroak(id_bezeroa),
    CONSTRAINT fk_eskaera_langilea FOREIGN KEY (langilea_id) REFERENCES langileak(id_langilea)
);
*/

-- 7. ESKAERAK ETA FAKTURAK
INSERT INTO eskaerak (id_eskaera, bezeroa_id, langilea_id, guztira_prezioa, faktura_zenbakia, eskaera_egoera, data) VALUES
(1, 1, 5, 2199.00, 'FAK-2024-001', 'Osatua/Bidalita', '2024-01-08 10:00:00'),
(2, 2, 6, 1499.00, 'FAK-2024-002', 'Osatua/Bidalita', '2024-01-09 11:30:00'),
(3, 1, 7, 799.00, 'FAK-2024-003', 'Prestatzen', '2024-01-10 09:15:00'),
(4, 3, 5, 4500.00, 'FAK-2024-004',  'Osatua/Bidalita', '2024-01-11 14:00:00'),
(5, 4, 6, 1209.00, 'FAK-2024-005', 'Prestatzen', '2024-01-12 16:45:00'),
(6, 2, 7, 299.00, 'FAK-2024-006', 'Osatua/Bidalita', '2024-01-14 08:30:00'),
(7, 5, 5, 150.00, 'FAK-2024-007', 'Ezabatua', '2024-01-15 10:00:00'),
(8, 1, 6, 99.00, 'FAK-2024-008', 'Osatua/Bidalita', '2024-01-18 13:20:00'),
(9, 3, 7, 3800.00, 'FAK-2024-009', 'Osatua/Bidalita', '2024-01-22 09:10:00'),
(10, 4, 5, 600.00, 'FAK-2024-010', 'Osatua/Bidalita', '2024-01-25 15:40:00'),
(11, 2, 6, 135.00, 'FAK-2024-011', 'Prestatzen', '2024-01-28 11:00:00'),
(12, 1, 7, 450.00, 'FAK-2024-012', 'Osatua/Bidalita', '2024-02-01 10:30:00'),
(13, 3, 5, 2500.00,'FAK-2024-013', 'Osatua/Bidalita', '2024-02-05 14:15:00'),
(14, 5, 6, 89.00, 'FAK-2024-014','Prestatzen', '2024-02-08 09:00:00'),
(15, 2, 7, 120.00, 'FAK-2024-015', 'Osatua/Bidalita', '2024-02-12 16:20:00'),
(16, 4, 5, 2499.00, 'FAK-2024-016',  'Osatua/Bidalita', '2024-02-15 11:00:00'),
(17, 1, 6, 1400.00, 'FAK-2024-017', 'Prestatzen', '2024-02-18 13:45:00'),
(18, 3, 7, 550.00, 'FAK-2024-018', 'Osatua/Bidalita', '2024-02-22 10:15:00'),
(19, 2, 5, 15.00, 'FAK-2024-019', 'Osatua/Bidalita', '2024-02-25 09:30:00'),
(20, 5, 6, 45.00, 'FAK-2024-020', 'Ezabatua', '2024-03-01 15:00:00'),
(21, 1, 7, 1250.00, 'FAK-2024-021', 'Osatua/Bidalita', '2024-03-05 14:30:00'),
(22, 4, 5, 900.00, 'FAK-2024-022', 'Prestatzen', '2024-03-08 11:20:00'),
(23, 2, 6, 320.00, 'FAK-2024-023', 'Osatua/Bidalita', '2024-03-12 09:45:00'),
(24, 3, 7, 1800.00, 'FAK-2024-024', 'Osatua/Bidalita', '2024-03-15 16:10:00'),
(25, 1, 5, 650.00, 'FAK-2024-025', 'Prestatzen', '2024-03-18 10:00:00'),
(26, 5, 6, 75.00, 'FAK-2024-026', 'Osatua/Bidalita', '2024-03-22 13:50:00'),
(27, 2, 7, 1500.00, 'FAK-2024-027', 'Osatua/Bidalita', '2024-03-25 11:15:00'),
(28, 3, 5, 2200.00, 'FAK-2024-028', 'Prestatzen', '2024-03-28 15:30:00'),
(29, 4, 6, 60.00, 'FAK-2024-029', 'Osatua/Bidalita', '2024-04-01 09:20:00'),
(30, 1, 7, 1200.00, 'FAK-2024-030', 'Osatua/Bidalita', '2024-04-05 14:00:00');

INSERT INTO eskaera_lerroak (id_eskaera_lerroa, eskaera_id, produktua_id, kantitatea, unitate_prezioa, eskaera_lerro_egoera) VALUES
(1, 1, 1, 1, 2199.00, 'Osatua/Bidalita'),
(2, 2, 2, 1, 1499.00, 'Osatua/Bidalita'),
(3, 3, 24, 1, 799.00, 'Prestatzen'),
(4, 4, 46, 1, 4500.00, 'Osatua/Bidalita'),
(5, 5, 21, 1, 1209.00, 'Prestatzen'),
(6, 6, 51, 1, 299.00, 'Osatua/Bidalita'),
(7, 7, 57, 1, 150.00, 'Ezabatua'), 
(8, 8, 76, 1, 99.00, 'Osatua/Bidalita'),
(9, 9, 47, 1, 3800.00, 'Osatua/Bidalita'),
(10, 10, 32, 1, 600.00, 'Osatua/Bidalita'),
(11, 11, 58, 1, 135.00, 'Prestatzen'), 
(12, 12, 29, 1, 450.00, 'Osatua/Bidalita'),
(13, 13, 11, 1, 2499.00, 'Osatua/Bidalita'),
(14, 14, 77, 1, 89.00, 'Prestatzen'),
(15, 15, 80, 1, 120.00, 'Osatua/Bidalita'),
(16, 16, 11, 1, 2499.00, 'Osatua/Bidalita'),
(17, 17, 2, 1, 1400.00, 'Prestatzen'),
(18, 18, 61, 1, 550.00, 'Osatua/Bidalita'),
(19, 19, 66, 1, 15.00, 'Osatua/Bidalita'),
(20, 20, 70, 1, 25.00, 'Ezabatua'), 
(21, 20, 71, 2, 10.00, 'Ezabatua'), 
(22, 21, 4, 1, 950.00, 'Osatua/Bidalita'),
(23, 21, 54, 1, 300.00, 'Osatua/Bidalita'),
(24, 22, 34, 1, 900.00, 'Prestatzen'),
(25, 23, 62, 1, 320.00, 'Osatua/Bidalita'),
(26, 24, 17, 1, 1800.00, 'Osatua/Bidalita'),
(27, 25, 27, 1, 650.00, 'Prestatzen'),
(28, 26, 79, 1, 75.00, 'Osatua/Bidalita'),
(29, 27, 49, 1, 1500.00, 'Osatua/Bidalita'),
(30, 28, 48, 1, 2200.00, 'Prestatzen'),
(31, 29, 54, 1, 60.00, 'Osatua/Bidalita'), 
(32, 30, 50, 1, 1200.00, 'Osatua/Bidalita');




``n
