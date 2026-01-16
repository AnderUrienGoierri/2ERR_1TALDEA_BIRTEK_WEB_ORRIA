<?php
session_start();
header('Content-Type: application/json');

$response = [
    'saioa_hasita' => false,
    'izena' => ''
];

if (isset($_SESSION['id_bezeroa'])) {
    $response['saioa_hasita'] = true;
    $response['izena'] = $_SESSION['izena'] ?? 'Bezeroa';
    $response['mota'] = 'bezeroa';
} elseif (isset($_SESSION['id_hornitzailea'])) {
    $response['saioa_hasita'] = true;
    $response['izena'] = $_SESSION['izena_soziala'] ?? 'Hornitzailea';
    $response['mota'] = 'hornitzailea';
}

echo json_encode($response);
?>

