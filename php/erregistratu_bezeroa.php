<?php
session_start();
require_once 'DB_konexioa.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $izena = trim($_POST['izena']);
    $emaila = trim($_POST['emaila_erregistroa']);
    $helbidea = trim($_POST['helbidea']); // Note: Form might pass this, or we might need to add it if missing in HTML
    $pasahitza = trim($_POST['pasahitza_erregistroa']);
    
    // Default values
    $ifz_nan = 'TEMP_' . time(); 
    $herria_id = 1; 
    $posta_kodea = '00000';
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
