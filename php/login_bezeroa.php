<?php
session_start();

//include_once edo require_once erabili
require_once 'DB_konexioa.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emaila = trim($_POST['emaila']);
    $pasahitza = trim($_POST['pasahitza']);

    try {
        $stmt = $konexioa->prepare("SELECT * FROM bezeroak WHERE emaila = :emaila");
        $stmt->bindParam(':emaila', $emaila);
        $stmt->execute();
        $bezeroa = $stmt->fetch(PDO::FETCH_ASSOC);

        // NOTE: In a real app, use password_verify(). Since sql shows plain text or simple hashes,
        // we will assume direct comparison or simple check. 
        // Based on sql INSERT: '123456Jon', 'admin2024', etc. exist.
        // Also user instruction said "pass: 1234" for all sysadmins, but bezeroak have distinct passes.
        // We'll use simple equality for now as per simple academic exercises often do, 
        // or password_verify if they are hashed. Given I see plain text in SQL, I'll use plain text check.
        
        if ($bezeroa && $pasahitza === $bezeroa['pasahitza']) {
            $_SESSION['id_bezeroa'] = $bezeroa['id_bezeroa'];
            $_SESSION['izena'] = $bezeroa['izena_edo_soziala'];
            $_SESSION['emaila'] = $bezeroa['emaila'];
            
            header("Location: bezero_menua.php");
            exit();
        } else {
            // Error handling - redirect back with query param
            header("Location: ../html/bezeroa_saioa_hasi.html?error=1");
            exit();
        }
    } catch (PDOException $e) {
        die("Errorea: " . $e->getMessage());
    }
} else {
    header("Location: ../html/bezeroa_saioa_hasi.html");
}
?>

