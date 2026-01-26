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

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mota_sarrera = $_POST['mota_sarrera']; // 'existing' or 'new'
    $kantitatea = $_POST['kantitatea'];

    try {
        $konexioa->beginTransaction();
        $produktua_id = null;

        if ($mota_sarrera == 'existing') {
            $produktua_id = $_POST['produktua_id'];
        } else {
            // New product entry
            $izena = trim($_POST['izena']);
            $marka = trim($_POST['marka']);
            $mota = $_POST['mota']; // ENUM value
            $deskribapena = trim($_POST['deskribapena']);
            $stock = 0; // Default to 0 as per user request (goods are Incoming 'Bidean')
            $prezioa = 0.00; // Removed input, default 0

            // Determine Kategoria ID
            // 1: Ordenagailuak (Eramangarria, Mahai-gainekoa)
            // 2: Telefonia (Mugikorra, Tableta)
            // 3: Irudia (Pantaila)
            // 4: Osagarriak (Periferikoa, Kablea) -> Note: 'Kablea' value in select, 'kableak' table
            // 5: Softwarea (Softwarea)
            // 6: Sareak eta Zerbitzariak (Zerbitzaria)

            $kategoria_id = 1; // Default
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

            // Insert into main table
            // Note: 'mota' ENUM in DB might need update if 'Kablea' or 'Periferikoa' are not present accurately
            // DB ENUM: 'Generikoa','Eramangarria','Mahai-gainekoa','Mugikorra','Tableta','Zerbitzaria','Pantaila','Softwarea','Periferikoak','Kableak'
            // My select values: 'Periferikoa', 'Kablea'. Need to map to DB ENUM.
            $db_mota = $mota;
            if ($mota == 'Periferikoa')
                $db_mota = 'Periferikoak';
            if ($mota == 'Kablea')
                $db_mota = 'Kableak';

            $stmtProd = $konexioa->prepare("INSERT INTO produktuak (izena, marka, mota, deskribapena, salmenta_prezioa, stock, hornitzaile_id, kategoria_id, aktibo, salgai) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, 0)");
            $stmtProd->execute([$izena, $marka, $db_mota, $deskribapena, $prezioa, $stock, $id_hornitzailea, $kategoria_id]);
            $produktua_id = $konexioa->lastInsertId();

            // Insert into Sub-tables
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
            // 1. Insert into sarrerak
            $stmt = $konexioa->prepare("INSERT INTO sarrerak (hornitzailea_id, langilea_id, sarrera_egoera, data) VALUES (:hid, 1, 'Bidean', NOW())");
            $stmt->execute([':hid' => $id_hornitzailea]);
            $sarrera_id = $konexioa->lastInsertId();

            // 2. Insert into sarrera_lerroak
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

// Fetch Supplier's Products for Dropdown
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
                            <!-- Stock input removed as per user request -->
                        </div>
                        <textarea name="deskribapena" class="inprimaki-sarrera" placeholder="Deskribapena"
                            rows="3"></textarea>

                        <!-- Dynamic Fields Containers -->
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
            // Hide all dynamic fields
            $('.dynamic-fields').addClass('ezkutuan');

            var mota = $('#produktu_mota_select').val();
            // Map 'mota' to ID (Normalize string: replace spaces with empty, to lowercase)
            // But we can just use simple switch/if map since values are known
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