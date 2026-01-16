<?php
require_once 'DB_konexioa.php';
try {
    $stmt = $konexioa->prepare("SELECT id_produktua, izena, irudia_url FROM produktuak LIMIT 20");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
