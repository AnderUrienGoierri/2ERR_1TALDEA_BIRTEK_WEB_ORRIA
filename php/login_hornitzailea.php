<?php
session_start();
require_once 'DB_konexioa.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emaila = trim($_POST['emaila']);
    $pasahitza = trim($_POST['pasahitza']);

    try {
        $stmt = $konexioa->prepare("SELECT * FROM hornitzaileak WHERE emaila = :emaila");
        $stmt->bindParam(':emaila', $emaila);
        $stmt->execute();
        $hornitzailea = $stmt->fetch(PDO::FETCH_ASSOC);

        // Simple plain text comparison as per existing project pattern
        if ($hornitzailea && $pasahitza === $hornitzailea['pasahitza']) {
            $_SESSION['id_hornitzailea'] = $hornitzailea['id_hornitzailea'];
            $_SESSION['izena_soziala'] = $hornitzailea['izena_soziala'];
            $_SESSION['emaila'] = $hornitzailea['emaila'];
            
            header("Location: hornitzaile_menua.php");
            exit();
        } else {
            header("Location: hornitzaile_saioa_hasi.php?error=1");
            exit();
        }
    } catch (PDOException $e) {
        die("Errorea: " . $e->getMessage());
    }
} else {
    header("Location: hornitzaile_saioa_hasi.php");
}
?>
