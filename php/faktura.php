<?php
session_start();
require_once 'DB_konexioa.php';

if (!isset($_SESSION['id_bezeroa']) || !isset($_GET['id'])) {
    header("Location: bezero_eskaerak.php");
    exit();
}

$id_bezeroa = $_SESSION['id_bezeroa'];
$id_eskaera = $_GET['id'];

try {
    // 1. Lortu eskaeraren datuak eta bezeroarenak (Datu guztiak batean)
    $sql = "
        SELECT e.*, b.*, h.izena as herria_izena 
        FROM eskaerak e
        JOIN bezeroak b ON e.bezeroa_id = b.id_bezeroa
        JOIN herriak h ON b.herria_id = h.id_herria
        WHERE e.id_eskaera = :eid AND e.bezeroa_id = :bid
    ";
    $stmt = $konexioa->prepare($sql);
    $stmt->execute([':eid' => $id_eskaera, ':bid' => $id_bezeroa]);
    $faktura = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$faktura) {
        die("Ez da eskaerarik aurkitu.");
    }

    // 2. Lortu eskaera lerroak
    $sqlL = "
        SELECT el.*, p.izena as produktu_izena, p.mota, p.marka
        FROM eskaera_lerroak el
        JOIN produktuak p ON el.produktua_id = p.id_produktua
        WHERE el.eskaera_id = :eid
    ";
    $stmtL = $konexioa->prepare($sqlL);
    $stmtL->execute([':eid' => $id_eskaera]);
    $lerroak = $stmtL->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Errorea: " . $e->getMessage());
}

// Funtzioa fitxategi teknikoak lortzeko (Helper)
function lortuXehetasunTeknikoak($kont, $id, $mota) {
    $taulak = [
        'Eramangarria' => 'eramangarriak',
        'Mahai-gainekoa' => 'mahai_gainekoak',
        'Mugikorra' => 'mugikorrak',
        'Tableta' => 'tabletak',
        'Zerbitzaria' => 'zerbitzariak',
        'Pantaila' => 'pantailak',
        'Softwarea' => 'softwareak',
        'Periferikoak' => 'periferikoak',
        'Kableak' => 'kableak'
    ];

    if (!isset($taulak[$mota])) return "";

    try {
        $taula = $taulak[$mota];
        $sqlT = "SELECT * FROM $taula WHERE id_produktua = :id";
        $stmtT = $kont->prepare($sqlT);
        $stmtT->execute([':id' => $id]);
        $datuak = $stmtT->fetch(PDO::FETCH_ASSOC);

        if (!$datuak) return "";

        $gehigarriak = [];
        unset($datuak['id_produktua']); // Ez dugu IDa erakutsi behar xehetasunetan

        foreach ($datuak as $gakoa => $balioa) {
            if ($balioa === null || $balioa === "") continue;
            
            // Formatua hobetu (gakoak euskarara edo label politak)
            $label = str_replace('_', ' ', $gakoa);
            $label = ucfirst($label);
            
            // Unitateak gehitu
            if (strpos($gakoa, 'gb') !== false) $balioa .= " GB";
            if (strpos($gakoa, 'kg') !== false) $balioa .= " kg";
            if (strpos($gakoa, 'wh') !== false) $balioa .= " Wh";
            if (strpos($gakoa, 'mah') !== false) $balioa .= " mAh";
            if (strpos($gakoa, 'prezioa') !== false) $balioa .= " €";
            if (strpos($gakoa, 'tamaina') !== false || strpos($gakoa, 'hazbeteak') !== false) $balioa .= "\"";
            if (strpos($gakoa, '_w') !== false && strpos($gakoa, '_wh') === false) $balioa .= " W";
            if (strpos($gakoa, 'hz') !== false) $balioa .= " Hz";
            if (strpos($gakoa, 'mp') !== false) $balioa .= " MP";
            if (strpos($gakoa, '_luzera_m') !== false) $balioa .= " m";

            if (is_bool($balioa) || $balioa === "1" || $balioa === "0") {
                $balioa = ($balioa == "1" || $balioa === true) ? "Bai" : "Ez";
            }

            $gehigarriak[] = "<strong>$label:</strong> $balioa";
        }

        return implode(" | ", $gehigarriak);

    } catch (Exception $e) {
        return "";
    }
}

// Txartelaren amaierako 4 zenbakiak lortu segurtasunagatik (simulazioa)
$txartela_moztuta = "**** **** **** " . substr($faktura['bezero_ordainketa_txartela'], -4);
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>FAKTURA #<?= $id_eskaera ?> - BIRTEK</title>
    <link rel="stylesheet" href="../css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../css/estiloak_faktura.css">

</head>
<body>

 <!--  Akzio Botoiak (Flotanteak) -->
    <div class="akzio-botoiak">
        <button onclick="window.print()" class="botoi-print">
            <i class="fas fa-download"></i> PDF moduan deskargatu
        </button>
    </div>

    <div class="faktura-edukiontzia">
        <!-- 1. Enpresa Datuak -->
        <div class="faktura-goiburua">
            <div class="logoa"><img src="../irudiak/birtek_logo_zuri_borobila.png" alt="BIRTEK Logo"></div>
            <div class="enpresa-datuak">
                <strong>BIRTEK Teknologia</strong><br>
                Goierri Eskola, Ordizia<br>
                Gipuzkoa, 20400<br>
                IFK: B12345678<br>
                info@birtek.eus | +34 943 00 00 00
            </div>
        </div>

        <!-- 2. Faktura eta Bezeroaren info -->
        <div class="faktura-info-taldea">
            <div class="info-blokea">
                <h3>Bezeroa</h3>
                <p><strong><?= htmlspecialchars($faktura['izena_edo_soziala'] . " " . ($faktura['abizena'] ?? "")) ?></strong></p>
                <p><?= htmlspecialchars($faktura['helbidea']) ?></p>
                <p><?= htmlspecialchars($faktura['posta_kodea']) ?>, <?= htmlspecialchars($faktura['herria_izena']) ?></p>
                <p>NAN/IFZ: <?= htmlspecialchars($faktura['ifz_nan']) ?></p>
                <p>Email: <?= htmlspecialchars($faktura['emaila']) ?></p>
            </div>
            <div class="info-blokea" style="text-align: right;">
                <h3>Faktura Xehetasunak</h3>
                <p><strong>Faktura zk:</strong> #<?= $id_eskaera ?></p>
                <p><strong>Data:</strong> <?= date("Y/m/d H:i", strtotime($faktura['data'])) ?></p>
                <p><strong>Ordainketa:</strong> Txartela (<?= $txartela_moztuta ?>)</p>
                <p><strong>Egoera:</strong> <?= htmlspecialchars($faktura['eskaera_egoera']) ?></p>
            </div>
        </div>

        <!-- 3. Produktuen zerrenda -->
        <table class="tab-faktura">
            <thead>
                <tr>
                    <th>Produktua</th>
                    <th style="text-align: center;">Kantitatea</th>
                    <th style="text-align: right;">Unitate Prezioa</th>
                    <th style="text-align: right;">Guztira</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lerroak as $lerroa): ?>
                <tr>
                    <td>
                        <strong><?= htmlspecialchars($lerroa['marka']) ?></strong> - <?= htmlspecialchars($lerroa['produktu_izena']) ?>
                        <div class="tekniko-xehetasunak">
                            <small><?= lortuXehetasunTeknikoak($konexioa, $lerroa['produktua_id'], $lerroa['mota']) ?></small>
                        </div>
                    </td>
                    <td style="text-align: center;"><?= $lerroa['kantitatea'] ?></td>
                    <td style="text-align: right;"><?= number_format($lerroa['unitate_prezioa'], 2) ?>€</td>
                    <td style="text-align: right;"><?= number_format($lerroa['kantitatea'] * $lerroa['unitate_prezioa'], 2) ?>€</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- 4. Laburpen Ekonomikoa -->
        <div class="guztira-atala">
            <p style="margin-bottom: 0;">Zerga-oinarria: <?= number_format($faktura['guztira_prezioa'] / 1.21, 2) ?>€</p>
            <p style="margin-top: 0;">BEZ (21%): <?= number_format($faktura['guztira_prezioa'] - ($faktura['guztira_prezioa'] / 1.21), 2) ?>€</p>
            <p>GUZTIRA: <span class="guztira-balioa"><?= number_format($faktura['guztira_prezioa'], 2) ?>€</span></p>
        </div>

        <!-- 5. Oin-OHARRA -->
        <div class="oin-oharrak">
            <p>Eskerrik asko BIRTEK-en konfiantza izateagatik. Teknologia berreskuratzen.</p>
            <p>Faktura hau automatikoki sortu da sistema bidez.</p>
        </div>
    </div>

   

</body>
</html>
