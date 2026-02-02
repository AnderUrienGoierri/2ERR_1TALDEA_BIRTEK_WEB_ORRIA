<?php
require_once 'DB_konexioa.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['emaila'])) {
    $emaila = trim($_POST['emaila']);

    if (empty($emaila)) {
        echo "Mesedez, sartu posta elektroniko bat.";
        exit();
    }

    try {
        $stmt = $konexioa->prepare("SELECT pasahitza FROM bezeroak WHERE emaila = :emaila");
        $stmt->bindParam(':emaila', $emaila);
        $stmt->execute();
        $bezeroa = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($bezeroa) {
            echo "Zure pasahitza hau da: " . $bezeroa['pasahitza'];
        } else {
            echo "Ez da aurkitu konturik posta elektroniko horrekin.";
        }
    } catch (PDOException $e) {
        echo "Errorea datu-basean: " . $e->getMessage();
    }
} else {
    echo "Eskaera baliogabea.";
}
?>