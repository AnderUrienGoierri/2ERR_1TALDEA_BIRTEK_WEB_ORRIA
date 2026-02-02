<?php
session_start();
require_once 'DB_konexioa.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $izena = trim($_POST['izena']);
    $emaila = trim($_POST['emaila_erregistroa']);
    $helbidea = trim($_POST['helbidea']); // Note: Form might pass this, or we might need to add it if missing in HTML
    $pasahitza = trim($_POST['pasahitza_erregistroa']);

    // Default values
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

        // Note: 'izena_edo_soziala' is the column name in DB for 'izena'
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