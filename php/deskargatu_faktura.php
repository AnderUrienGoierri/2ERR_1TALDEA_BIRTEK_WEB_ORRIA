<?php
session_start();

if (!isset($_SESSION['id_bezeroa']) || !isset($_GET['id'])) {
    die("Sarrera baimenik ez.");
}

$id_eskaera = $_GET['id'];

// Bideak definitu
$base_url = "http://192.168.115.155/fakturak/";

// Fitxategiaren izena eraiki
$filename = "faktura_" . $id_eskaera . ".pdf";
$file_url = $base_url . $filename;

// Egiaztatu lehen izen formatuarekin (faktura_XX.pdf)
$headers = @get_headers($file_url);
if (!$headers || strpos($headers[0], '200') === false) {
    // Bigarren aukera (XX.pdf)
    $filename = $id_eskaera . ".pdf";
    $file_url = $base_url . $filename;
    $headers = @get_headers($file_url);
}

if ($headers && strpos($headers[0], '200') !== false) {
    // PDFa zerbitzatu deskarga moduan
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');

    // Remote fitxategiaren tamaina saiatu lortzen
    foreach ($headers as $header) {
        if (stripos($header, 'Content-Length:') === 0) {
            header($header);
            break;
        }
    }

    // Buffer-a garbitu
    ob_clean();
    flush();

    // Fitxategia irakurri eta bidali
    readfile($file_url);
    exit;
} else {
    die("Faktura ez da aurkitu zerbitzarian: " . $filename);
}
?>
