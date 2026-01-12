<?php
$zerbitzaria = "localhost";
$erabiltzailea = "root";
$pasahitza = "1MG32025"; 
$datu_basea = "birtek_db";

try {
    $dsn = "mysql:host=$zerbitzaria;dbname=$datu_basea;charset=utf8";
    $konexioa = new PDO($dsn, $erabiltzailea, $pasahitza);
    $konexioa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // HEMEN EZ DA ECHORIK JARRI BEHAR
} catch (PDOException $e) {
    // Errorea badago bakarrik inprimatu JSON formatuan eta gelditu
    // Hori gabe, Javascript-ak "Unexpected end of JSON input" emango du.
    header('Content-Type: application/json');
    die(json_encode(["errorea" => "DB Error: " . $e->getMessage()]));
}
?>

