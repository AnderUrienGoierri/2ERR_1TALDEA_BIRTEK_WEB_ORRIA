<?php
$zerbitzaria = "localhost";     //host
$datu_basea = "birtek_db";      // nire datu-basearen izena
$erabiltzailea = "root";        
// root soilik baimenak ditu lokalean erabiltzeko, GU ez gera lokalean konektatuko,
//  beraz root-en baimen berdinak dituen beste erabiltzaile bat sortu behar da.
$pasahitza = "1MG32025"; 

$dsn = "mysql:host=$zerbitzaria;port=3306;dbname=$datu_basea;charset=utf8";

try {
    
    $konexioa = new PDO($dsn, $erabiltzailea, $pasahitza);
    $konexioa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Log mezua konektatu dela adierazteko (nabigatzaileko kontsolan ikusteko)
    echo "<script>console.log('Datu-Basera ondo konektatu da');</script>";
} catch (PDOException $e) {
    // Log mezua errorea egon dela adierazteko (nabigatzaileko kontsolan ikusteko)
    echo "<script>console.error('Datu-Basera EZ da konektatu');</script>";

    //  'application/json' goiburua kenduta, bestela nabigatzaileak 
    // script-ak testu arrunt bezala erakusten ditu eta ez ditu exekutatzen.
    
    // die: horeraino bakarrik (inprimatu eta gelditu)
    die(json_encode(["ERROREA" => "Datu-Base konexio Errorea: " . $e->getMessage()]));
}
?>

