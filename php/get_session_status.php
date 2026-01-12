<?php
session_start();
header('Content-Type: application/json');

$response = [
    'logged_in' => false,
    'izena' => ''
];

if (isset($_SESSION['id_bezeroa'])) {
    $response['logged_in'] = true;
    $response['izena'] = $_SESSION['izena'] ?? 'Bezeroa';
}

echo json_encode($response);
?>

