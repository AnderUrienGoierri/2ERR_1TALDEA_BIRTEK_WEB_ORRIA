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
        SELECT el.*, p.izena as produktu_izena
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

// Txartelaren amaierako 4 zenbakiak lortu segurtasunagatik (simulazioa)
$txartela_moztuta = "**** **** **** " . substr($faktura['bezero_ordainketa_txartela'], -4);
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <title>FAKTURA #<?= $id_eskaera ?> - BIRTEK</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #333; line-height: 1.6; margin: 0; padding: 20px; background: #f4f4f4; }
        .faktura-edukiontzia { max-width: 800px; margin: auto; background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .faktura-goiburua { display: flex; justify-content: space-between; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 30px; }
        .logoa { font-size: 2rem; font-weight: bold; color: #007bff; }
        .enpresa-datuak { text-align: right; font-size: 0.9rem; color: #666; }
        
        .faktura-info-taldea { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .info-blokea h3 { border-bottom: 1px solid #ddd; padding-bottom: 5px; margin-bottom: 10px; font-size: 1.1rem; }
        .info-blokea p { margin: 5px 0; font-size: 0.95rem; }
        
        .tab-faktura { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .tab-faktura th { background: #f8f9fa; text-align: left; padding: 12px; border-bottom: 2px solid #dee2e6; }
        .tab-faktura td { padding: 12px; border-bottom: 1px solid #eee; }
        
        .guztira-atala { text-align: right; }
        .guztira-balioa { font-size: 1.5rem; font-weight: bold; color: #007bff; }
        
        .oin-oharrak { margin-top: 50px; font-size: 0.85rem; color: #888; border-top: 1px solid #eee; pt: 20px; text-align: center; }
        
        .akzio-botoiak { display: flex; justify-content: center; }
        .botoi-print { background: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 50px; cursor: pointer; font-size: 1rem; box-shadow: 0 4px 6px rgba(0,0,0,0.2); }
        .botoi-print:hover { background: #218838; }

        @media print {
            body { background: white; padding: 0; }
            .faktura-edukiontzia { box-shadow: none; border: none; width: 100%; max-width: 100%; }
            .akzio-botoiak { display: none; }
        }
    </style>
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
            <div class="logoa">BIRTEK</div>
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
                    <td><?= htmlspecialchars($lerroa['produktu_izena']) ?></td>
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
