<?php
session_start();
require_once 'DB_konexioa.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $izena = trim($_POST['izena']);
    $emaila = trim($_POST['emaila_erregistroa']);
    $helbidea = trim($_POST['helbidea']);
    $pasahitza = trim($_POST['pasahitza_erregistroa']);
    
    // Default values
    $ifz_nan = 'TEMP_' . time(); // Temporary generic ID until they update profile
    $herria_id = 1; // Default to Donostia (or 1) until updated
    $posta_kodea = '00000';
    $telefonoa = '000000000';

    try {
        // Check if email already exists
        $check = $konexioa->prepare("SELECT id_hornitzailea FROM hornitzaileak WHERE emaila = :emaila");
        $check->execute([':emaila' => $emaila]);
        if ($check->rowCount() > 0) {
            header("Location: hornitzaile_saioa_hasi.php?error=exists");
            exit();
        }

        $sql = "INSERT INTO hornitzaileak (izena_soziala, emaila, pasahitza, helbidea, ifz_nan, herria_id, posta_kodea, telefonoa, aktibo) 
                VALUES (:izena, :emaila, :pasahitza, :helbidea, :ifz, :herria, :pk, :tel, 1)";
        
        $stmt = $konexioa->prepare($sql);
        $stmt->execute([
            ':izena' => $izena,
            ':emaila' => $emaila,
            ':pasahitza' => $pasahitza, // Plain text for consistency
            ':helbidea' => $helbidea,
            ':ifz' => $ifz_nan,
            ':herria' => $herria_id,
            ':pk' => $posta_kodea,
            ':tel' => $telefonoa
        ]);

        // Auto login
        $_SESSION['id_hornitzailea'] = $konexioa->lastInsertId();
        $_SESSION['izena_soziala'] = $izena;
        $_SESSION['emaila'] = $emaila;

        header("Location: hornitzaile_menua.php");
        exit();

    } catch (PDOException $e) {
        die("Errorea erregistratzean: " . $e->getMessage());
    }
} else {
    header("Location: hornitzaile_saioa_hasi.php");
}
?>
