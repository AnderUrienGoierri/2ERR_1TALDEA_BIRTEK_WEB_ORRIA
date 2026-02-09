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