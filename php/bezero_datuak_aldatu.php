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
        $_SESSION['izena'] = $izena_edo_soziala; // 
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

                    <div id="herri_berria_atala" class="herri-berria-panela zutabe-osoa" style="display: none;">
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