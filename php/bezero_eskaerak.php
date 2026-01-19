<?php
session_start();

// include_once ere bailu du
require_once 'DB_konexioa.php';

if (!isset($_SESSION['id_bezeroa'])) {
    header("Location: bezero_saioa_hasi.php");
    exit();
}

$id_bezeroa = $_SESSION['id_bezeroa'];
$mezua = "";

// Handle Delete Actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        try {
            $konexioa->beginTransaction();

            if ($_POST['action'] === 'delete_order' && isset($_POST['id_eskaera'])) {
                // Verify order belongs to user and is in 'Prestatzen' state
                $stmt = $konexioa->prepare("SELECT eskaera_egoera FROM eskaerak WHERE id_eskaera = :id AND bezeroa_id = :uid");
                $stmt->execute([':id' => $_POST['id_eskaera'], ':uid' => $id_bezeroa]);
                $order = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($order && strpos($order['eskaera_egoera'], 'Prestatzen') !== false) {
                    // 1. Restore Stock for all items in the order
                    $linesStmt = $konexioa->prepare("SELECT produktua_id, kantitatea FROM eskaera_lerroak WHERE eskaera_id = :id");
                    $linesStmt->execute([':id' => $_POST['id_eskaera']]);
                    $lines = $linesStmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($lines as $line) {
                        $updateStock = $konexioa->prepare("UPDATE produktuak SET stock = stock + :qty WHERE id_produktua = :pid");
                        $updateStock->execute([':qty' => $line['kantitatea'], ':pid' => $line['produktua_id']]);
                    }

                    // 2. Mark order as 'Ezabatua'
                    $updateStmt = $konexioa->prepare("UPDATE eskaerak SET eskaera_egoera = 'Ezabatua' WHERE id_eskaera = :id");
                    $updateStmt->execute([':id' => $_POST['id_eskaera']]);

                    $konexioa->commit();
                    $mezua = "Eskaera ezabatu da eta stock-a berreskuratu da.";
                } else {
                    $konexioa->rollBack();
                }
            } elseif ($_POST['action'] === 'delete_line' && isset($_POST['id_eskaera_lerroa']) && isset($_POST['id_eskaera'])) {
                 // Check order status first
                $stmt = $konexioa->prepare("SELECT eskaera_egoera FROM eskaerak WHERE id_eskaera = :id AND bezeroa_id = :uid");
                $stmt->execute([':id' => $_POST['id_eskaera'], ':uid' => $id_bezeroa]);
                $order = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($order && strpos($order['eskaera_egoera'], 'Prestatzen') !== false) {
                    // 1. Get line info to restore stock and update total
                    $lineStmt = $konexioa->prepare("SELECT produktua_id, unitate_prezioa, kantitatea FROM eskaera_lerroak WHERE id_eskaera_lerroa = :id");
                    $lineStmt->execute([':id' => $_POST['id_eskaera_lerroa']]);
                    $line = $lineStmt->fetch(PDO::FETCH_ASSOC);

                    if ($line) {
                        // 2. Restore Stock
                        $updateStock = $konexioa->prepare("UPDATE produktuak SET stock = stock + :qty WHERE id_produktua = :pid");
                        $updateStock->execute([':qty' => $line['kantitatea'], ':pid' => $line['produktua_id']]);

                        // 3. Remove line
                        $deleteStmt = $konexioa->prepare("DELETE FROM eskaera_lerroak WHERE id_eskaera_lerroa = :id");
                        $deleteStmt->execute([':id' => $_POST['id_eskaera_lerroa']]);
                        
                        // 4. Update Order Total
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

// Fetch orders
$stmt = $konexioa->prepare("
    SELECT * FROM eskaerak 
    WHERE bezeroa_id = :id 
    ORDER BY data DESC
");
$stmt->execute([':id' => $id_bezeroa]);
$eskaerak = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Helper function to fetch order lines
function lortuEskeraLerroak($konexioa, $id_eskaera) {
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
    <title>Erosketak Kudeatu - BIRTEK</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
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
                            <div style="display:flex; align-items:center; gap:10px;">
                                <span class="eskari-egoera-etiketa <?= $statusClass ?>">
                                    <?= $eskaera['eskaera_egoera'] ?>
                                </span>
                                <?php if ($isPrestatzen): ?>
                                    <form method="POST" onsubmit="return confirmDelete()" style="margin:0;">
                                        <input type="hidden" name="action" value="delete_order">
                                        <input type="hidden" name="id_eskaera" value="<?= $eskaera['id_eskaera'] ?>">
                                        <button type="submit" class="ezabatu-eskaria-botoia">Ezabatu Eskaera</button>
                                    </form>
                                <?php endif; ?>
                                <?php if ($isOsatua): ?>
                                    <a href="faktura.php?id=<?= $eskaera['id_eskaera'] ?>" target="_blank" class="faktura-deskargatu-botoia">Faktura Deskargatu</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="eskari-gorputza">
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
                                            <a href="produktua_xehetasunak.php?id=<?= $lerroa['produktua_id'] ?>" class="produktu-esteka">
                                                <?= htmlspecialchars($lerroa['izena']) ?>
                                            </a>
                                        </td>
                                        <td><?= $lerroa['kantitatea'] ?></td>
                                        <td><?= $lerroa['unitate_prezioa'] ?>€</td>
                                        <td><?= $lerroa['kantitatea'] * $lerroa['unitate_prezioa'] ?>€</td>
                                        <td>
                                            <?php if ($isPrestatzen): ?>
                                                <form method="POST" onsubmit="return confirmDelete()" style="margin:0;">
                                                    <input type="hidden" name="action" value="delete_line">
                                                    <input type="hidden" name="id_eskaera" value="<?= $eskaera['id_eskaera'] ?>">
                                                    <input type="hidden" name="id_eskaera_lerroa" value="<?= $lerroa['id_eskaera_lerroa'] ?>">
                                                    <button type="submit" class="ezabatu-lerroa-botoia" title="Ezabatu produktua"><i class="fas fa-trash"></i></button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
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

