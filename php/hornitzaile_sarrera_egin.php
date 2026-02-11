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
    $kantitatea = $_POST['kantitatea'];

    try {
        $konexioa->beginTransaction();

        // Produktoaren datu guztiak JSON batean bildu (katalogora ez gehitzeko oraindik)
        $produktu_datuak = $_POST;
        // Kantitatea kendu JSONetik, sarrera-lerroan gordeko baita separatuta
        unset($produktu_datuak['kantitatea']);
        $json_datuak = json_encode($produktu_datuak, JSON_UNESCAPED_UNICODE);

        if ($kantitatea > 0) {
            // 1. Sarreretan txertatu
            $stmt = $konexioa->prepare("INSERT INTO sarrerak (hornitzailea_id, langilea_id, sarrera_egoera, data) VALUES (:hid, 1, 'Bidean', NOW())");
            $stmt->execute([':hid' => $id_hornitzailea]);
            $sarrera_id = $konexioa->lastInsertId();

            // 2. Sarrera lerroetan txertatu (produktua_id = NULL baimendu dugu DBan)
            $stmtLine = $konexioa->prepare("INSERT INTO sarrera_lerroak (sarrera_id, produktua_id, kantitatea, sarrera_lerro_egoera, produktu_berria_datuak) VALUES (:sid, NULL, :qty, 'Bidean', :data)");
            $stmtLine->execute([
                ':sid' => $sarrera_id,
                ':qty' => $kantitatea,
                ':data' => $json_datuak
            ]);

            $konexioa->commit();
            $mezua = "Sarrera ondo erregistratu da! Produktu berria egiaztatzeko zain dago (Langileek 'Jasota' gisa markatzean katalogoan sartuko da).";
        } else {
            throw new Exception("Sarrera kopurua 1 baino handiagoa izan behar da.");
        }
    } catch (Exception $e) {
        if ($konexioa->inTransaction())
            $konexioa->rollBack();
        $mezua = "Errorea sarrera egitean: " . $e->getMessage();
    }
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
            <h2 class="kontaktua-titulua">Produktu Berria Erregistratu</h2>

            <div class="inprimaki-kutxa sarrera-edukiontzia">
                <?php if ($mezua): ?>
                    <p class="mezu-kutxa <?= strpos($mezua, 'Errorea') !== false ? 'mezu-errorea' : 'mezu-arrakasta' ?>">
                        <?= htmlspecialchars($mezua) ?>
                    </p>
                <?php endif; ?>

                <form class="kontaktu-inprimaki-diseinua" method="POST" id="sarrera_inprimaki_nagusia">
                    <div id="atal_produktua_berria">
                        <div class="sarrera-sareta-berria">
                            <input type="text" name="izena" class="inprimaki-sarrera" placeholder="Produktuaren Izena" required>
                            <input type="text" name="marka" class="inprimaki-sarrera" placeholder="Marka" required>
                            <select name="mota" id="produktu_mota_hautatzailea" class="inprimaki-sarrera" required onchange="eremuDinamikoakKudeatu()">
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
                        </div>
                        <textarea name="deskribapena" class="inprimaki-sarrera" placeholder="Deskribapena" rows="3"></textarea>

                        <!-- Eremu Dinamikoen Edukiontziak -->
                        <div id="eremuak_eramangarria" class="eremu-dinamikoak ezkutuan">
                            <h4>Eramangarria Ezaugarriak</h4>
                            <div class="sarrera-sareta-berria">
                                <input type="text" name="eram_prozesadorea" placeholder="Prozesadorea (Adib: i7-1360P)">
                                <input type="number" name="eram_ram_gb" placeholder="RAM (GB)">
                                <input type="number" name="eram_diskoa_gb" placeholder="Diskoa (GB)">
                                <input type="number" step="0.1" name="eram_pantaila_tamaina" placeholder="Pantaila (hazbeteak)">
                                <input type="number" name="eram_bateria_wh" placeholder="Bateria (Wh)">
                                <input type="text" name="eram_sistema_eragilea" placeholder="Sistema Eragilea">
                                <input type="number" step="0.01" name="eram_pisua_kg" placeholder="Pisua (Kg)">
                            </div>
                        </div>

                        <div id="eremuak_mahaigainekoa" class="eremu-dinamikoak ezkutuan">
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

                        <div id="eremuak_mugikorra" class="eremu-dinamikoak ezkutuan">
                            <h4>Mugikorra Ezaugarriak</h4>
                            <div class="sarrera-sareta-berria">
                                <input type="text" name="mug_pantaila_teknologia" placeholder="Pantaila Teknologia (OLED...)">
                                <input type="number" step="0.1" name="mug_pantaila_hazbeteak" placeholder="Pantaila (hazbeteak)">
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

                        <div id="eremuak_tableta" class="eremu-dinamikoak ezkutuan">
                            <h4>Tableta Ezaugarriak</h4>
                            <div class="sarrera-sareta-berria">
                                <input type="number" step="0.1" name="tab_pantaila_hazbeteak" placeholder="Pantaila (hazbeteak)">
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

                        <div id="eremuak_zerbitzaria" class="eremu-dinamikoak ezkutuan">
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
                                    <input type="checkbox" name="zerb_elikatze_erredundantea" checked> Elikatze Iturri Erredundantea
                                </label>
                            </div>
                        </div>

                        <div id="eremuak_pantaila" class="eremu-dinamikoak ezkutuan">
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

                        <div id="eremuak_softwarea" class="eremu-dinamikoak ezkutuan">
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

                        <div id="eremuak_periferikoa" class="eremu-dinamikoak ezkutuan">
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
                                <textarea name="peri_ezaugarriak" class="inprimaki-sarrera" placeholder="Ezaugarriak (DPI, Mekanikoa...)" rows="1"></textarea>
                                <label class="label-flex">
                                    <input type="checkbox" name="peri_argiztapena"> Argiztapena
                                </label>
                            </div>
                        </div>

                        <div id="eremuak_kablea" class="eremu-dinamikoak ezkutuan">
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

                    <button type="submit" class="botoia botoi-nagusia inprimaki-bidali-botoia">Bidali Produktuak</button>
                </form>

                <div class="atzera-esteka-edukiontzia">
                    <a href="hornitzaile_menua.php" class="atzera-esteka-estiloa"><i class="fas fa-arrow-left"></i> Atzera Menura</a>
                </div>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
    <script>
        function eremuDinamikoakKudeatu() {
            // Dinamikoki erakutsitako eremu guztiak ezkutatu eta desgaitu
            $('.eremu-dinamikoak').addClass('ezkutuan');
            $('.eremu-dinamikoak :input').prop('disabled', true);

            var mota = $('#produktu_mota_hautatzailea').val();
            var targetId = "";

            if (mota === "Eramangarria") targetId = "eremuak_eramangarria";
            else if (mota === "Mahai-gainekoa") targetId = "eremuak_mahaigainekoa";
            else if (mota === "Mugikorra") targetId = "eremuak_mugikorra";
            else if (mota === "Tableta") targetId = "eremuak_tableta";
            else if (mota === "Zerbitzaria") targetId = "eremuak_zerbitzaria";
            else if (mota === "Pantaila") targetId = "eremuak_pantaila";
            else if (mota === "Softwarea") targetId = "eremuak_softwarea";
            else if (mota === "Periferikoa") targetId = "eremuak_periferikoa";
            else if (mota === "Kablea") targetId = "eremuak_kablea";

            if (targetId) {
                var targetEremua = $('#' + targetId);
                targetEremua.removeClass('ezkutuan');
                targetEremua.find(':input').prop('disabled', false);
            }
        }
    </script>
</body>
</html>