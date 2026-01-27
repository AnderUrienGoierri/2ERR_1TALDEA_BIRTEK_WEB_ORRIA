<?php
session_start();

if (!isset($_SESSION['id_bezeroa']) || !isset($_GET['id'])) {
    die("Sarrera baimenik ez.");
}

$id_eskaera = $_GET['id'];

// Bideak definitu
$base_path = $_SERVER['DOCUMENT_ROOT'] . "/fakturak/";

// Fitxategia bilatu (.pdf formatuan bakarrik orain)
$filename = "faktura_" . $id_eskaera . ".pdf";
$filepath = $base_path . $filename;

// Bigarren aukera (izen formatu ezberdina bada)
if (!file_exists($filepath)) {
    $filename = $id_eskaera . ".pdf";
    $filepath = $base_path . $filename;
}

if (file_exists($filepath)) {
    // PDFa zerbitzatu deskarga moduan
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filepath));
    
    // Buffer-a garbitu
    ob_clean();
    flush();
    
    // Fitxategia irakurri eta bidali
    readfile($filepath);
    exit;
} else {
    // Errore mezu polita (aukerakoa, baina hobe die baino)
    die("Faktura ez da aurkitu zerbitzarian ($filename).");
}
?>
