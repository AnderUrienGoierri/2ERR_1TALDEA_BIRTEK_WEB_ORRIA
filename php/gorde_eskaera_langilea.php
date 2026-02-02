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
