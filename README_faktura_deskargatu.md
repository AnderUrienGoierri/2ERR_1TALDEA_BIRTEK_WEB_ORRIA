# 3.3.6. Bezeroen Faktura deskargatzea (web)

Atal honetan bezeroak web orriaren bidez bere eskaeren fakturak nola deskargatzen dituen azaltzen da. Prozesu hau Java aplikazioan hasten da (salmenta langileak faktura sortzen duenean) eta webgunean amaitzen da (bezeroak PDFa deskargatzen duenean).

## Prozesuaren Azalpena

1. **Java Aplikazioa**: Salmentako langileak, Java aplikazioaren bidez, "Osatua/Bidalita" egoeran dagoen eskaera baten faktura sortzen du. Aplikazioak PDF fitxategi bat sortzen du eta zerbitzariko `fakturak` karpetan gordetzen du (adibidez: `faktura_123.pdf` edo `123.pdf`).
2. **Datu-basea**: Eskaeraren egoera eguneratzen da eta faktura eskuragarri bihurtzen da.
3. **Web Orria**: Bezeroak "Nire Erosketak" atalean faktura deskargatzeko botoia ikusten du.
4. **Deskarga**: Botoia sakatzean, PHP script batek (`deskargatu_faktura.php`) fitxategia bilatzen du eta bezeroari bidaltzen dio.

## Kode Adibideak

### 1. Faktura Botoia Bistaratzea (`php/bezero_eskaerak.php`)

Bezeroaren eskaeren zerrendan, sistema egiaztatzen du eskaera "Osatua" edo "Bidalita" egoeran dagoen. Hala bada, "Faktura" botoia erakusten du.

```php
// php/bezero_eskaerak.php

// ... (biclearen barruan) ...
$rawStatus = $eskaera['eskaera_egoera'];
// Egiaztatu egoera 'Osatua' edo 'Bidalita' den
$isOsatua = (strpos($rawStatus, 'Osatua') !== false || strpos($rawStatus, 'Bidalita') !== false);
?>

<!-- ... (HTML egitura) ... -->

<div class="botoi-taldea-zentratuta">
    <!-- Egoera etiketa -->
    <span class="eskari-egoera-etiketa <?= $statusClass ?>">
        <?= $eskaera['eskaera_egoera'] ?>
    </span>

    <!-- ... (beste botoi batzuk) ... -->

    <!-- FAKTURA BOTOIA -->
    <?php if ($isOsatua): ?>
        <a href="deskargatu_faktura.php?id=<?= $eskaera['id_eskaera'] ?>"
           class="faktura-deskargatu-botoia">Faktura</a>
    <?php endif; ?>
</div>
```

### 2. Faktura Deskargatzeko Logika (`php/deskargatu_faktura.php`)

Fitxategi honek eskaeraren IDa jasotzen du, zerbitzarian dagokion PDF fitxategia bilatzen du, eta nabigatzaileari deskarga gisa bidaltzen dio. Segurtasun neurri gisa, bezeroaren saioa egiaztatzen da.

```php
<?php
// php/deskargatu_faktura.php
session_start();

// 1. Segurtasuna: Bezeroa saioa hasita egon behar da
if (!isset($_SESSION['id_bezeroa']) || !isset($_GET['id'])) {
    die("Sarrera baimenik ez.");
}

$id_eskaera = $_GET['id'];

// 2. Bideak definitu (Fakturak gordetzen diren karpeta)
// OHARRA: $_SERVER['DOCUMENT_ROOT'] xampp/htdocs izan ohi da.
$base_path = $_SERVER['DOCUMENT_ROOT'] . "/fakturak/";

// 3. Fitxategia bilatu (formatu ezberdinak onartzen ditu)
$filename = "faktura_" . $id_eskaera . ".pdf";
$filepath = $base_path . $filename;

// Bigarren aukera (izen formatua fixoa ez bada)
if (!file_exists($filepath)) {
    $filename = $id_eskaera . ".pdf";
    $filepath = $base_path . $filename;
}

// 4. Fitxategia zerbitzatu
if (file_exists($filepath)) {
    // Header-ak konfiguratu PDF deskargarako
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . basename($filepath) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filepath));

    // Buffer-a garbitu eta fitxategia bidali
    ob_clean();
    flush();
    readfile($filepath);
    exit;
} else {
    // Fitxategia ez bada existitzen
    die("Faktura ez da aurkitu zerbitzarian ($filename).");
}
?>
```
