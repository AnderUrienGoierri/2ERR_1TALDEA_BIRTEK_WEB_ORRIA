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
    foreach ($saskia as &$elementua) {
        $stmtP = $konexioa->prepare("SELECT salmenta_prezioa, stock FROM produktuak WHERE id_produktua = ?");
        $stmtP->execute([$elementua['id']]);
        $prod = $stmtP->fetch(PDO::FETCH_ASSOC);
        
        if (!$prod) {
            throw new Exception("Produktua ez da aurkitu: " . $elementua['izena']);
        }
        
        if ($prod['stock'] < $elementua['kantitatea']) {
            throw new Exception("Ez dago stock nahikorik: " . $elementua['izena'] . " (" . $prod['stock'] . " ale geratzen dira)");
        }
        
        // Kalkulatu guztira DBko prezioekin (segurtasuna)
        $guztira += $prod['salmenta_prezioa'] * $elementua['kantitatea'];
        $elementua['db_prezioa'] = $prod['salmenta_prezioa'];
    }

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
