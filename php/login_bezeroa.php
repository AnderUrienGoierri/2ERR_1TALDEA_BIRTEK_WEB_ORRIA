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

        // OHARRA: Benetako aplikazio batean, password_verify() erabili beharko litzateke. SQL-ak testu arrunta edo hash sinpleak erakusten dituenez,
        // konparaketa zuzena edo egiaztapen sinplea egingo dugu.
        // SQLko INSERT-etan oinarrituta: '123456Jon', 'admin2024', etab. existitzen dira.
        // Era berean, erabiltzailearen argibideek esan zuten "pass: 1234" sysadmin guztientzat, baina bezeroek pasahitz desberdinak dituzte.
        // Berdintasun konparaketa sinplea erabiliko dugu gaurkoz, ariketa akademikoetan egin ohi den bezala,
        // edo password_verify hash-eratuak badira. SQL-n testu arrunta ikusten dudanez, testu arrunteko egiaztapena erabiliko dut.
        
        if ($bezeroa && $pasahitza === $bezeroa['pasahitza']) {
            $_SESSION['id_bezeroa'] = $bezeroa['id_bezeroa'];
            $_SESSION['izena'] = $bezeroa['izena_edo_soziala'];
            $_SESSION['emaila'] = $bezeroa['emaila'];
            
            header("Location: bezero_menua.php");
            exit();
        } else {
            
            header("Location: bezero_saioa_hasi.php?error=1");
            exit();
        }
    } catch (PDOException $e) {
        die("Errorea: " . $e->getMessage());
    }
} else {
    header("Location: bezero_saioa_hasi.php");
}
?>

