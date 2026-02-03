# Web Kode Adibideak

Dokumentu honek proiektuan erabilitako teknologia nagusien (HTML, CSS, jQuery, PHP) kode adibide esanguratsuak biltzen ditu, txosten teknikoaren inplementazio atalean erabiltzeko.

## 1. HTML (Egitura eta Semantika)

Adibide hau `php/hasiera.php` fitxategitik aterata dago. Orriaren egitura nagusia, goiburua, eduki nagusia (atalak eta txartelak) eta oina erakusten ditu.

```html
<!-- php/hasiera.php -->
<!DOCTYPE html>
<html lang="eu">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BIRTEK - Hasiera</title>
    <!-- ... CSS estekak ... -->
  </head>

  <body class="web-gorputza">
    <?php include 'goiburua.php'; ?>

    <main class="eduki-nagusia">
      <section>
        <div class="azal-kaxa">
          <div>
            <h1 class="azal-izenburua">Ongi Etorri BIRTEK</h1>
            <p class="azal-azpititulua">
              Teknologia berreskuratzen, etorkizuna eraikitzen.
            </p>
            <!-- ... Botoiak ... -->
          </div>
        </div>

        <!-- Eduki zerrenda -->
        <div class="hasiera-zerrenda-edukiontzia">
          <div class="hasiera-txartel-lerroa">
            <div class="hasiera-zutabea">
              <img
                src="../irudiak/birtek_erakuslekua.png"
                alt="Erakuslekua"
                class="hasiera-txartel-irudia"
                loading="lazy"
              />
            </div>
            <div class="hasiera-zutabea">
              <h3 class="hasiera-txartel-izenburua">Gure Erakuslekua</h3>
              <p class="hasiera-txartel-testua">
                Hemen gure produktu berrituak bertatik bertara ikus ditzakezu.
              </p>
            </div>
          </div>
          <!-- ... Beste txartel batzuk ... -->
        </div>
      </section>
    </main>

    <?php include 'footer.php'; ?>
    <!-- ... JS script-ak ... -->
  </body>
</html>
```

## 2. CSS (Diseinua eta Aldagaiak)

Adibide hau `css/estiloak_globala.css` fitxategitik dator. CSS aldagaiak (koloreak, iturriak) eta oinarrizko reset-a nola definitu den erakusten du.

```css
/* css/estiloak_globala.css */
:root {
  /* Koloreak */
  --kolore-nagusia: #166534;
  --kolore-nagusia-iluna: #14532d;
  --kolore-nagusia-argia: #dcfce7;

  --testu-nagusia: #1f2937;
  --kolore-gorria: #dc2626;

  /* Letra-tipoak */
  --font-testua: "Inter", sans-serif;
  --font-izenburua: "Outfit", sans-serif;
}

/* --- RESET ETA OINARRIAK --- */
*,
*::before,
*::after {
  box-sizing: border-box;
}

body {
  max-width: 100%;
  margin: 0;
  padding: 0;
}

.web-gorputza {
  background-image:
    linear-gradient(rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7)),
    url("../irudiak/birtek1.jpeg");
  background-attachment: fixed;
  background-size: cover;
  color: var(--testu-nagusia);
  font-family: var(--font-testua);
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}
```

## 3. jQuery (Frontend Logika)

Adibide hau `js/produktuak.js` fitxategikoa da. Produktuak bezeroaren aldean iragazteko logika erakusten du (bilaketa, kategoria, prezioa, etab.).

```javascript
// js/produktuak.js
function produktuakFiltratu() {
  var bilatuTestua = $("#iragazkia-bilatu").val().toLowerCase();
  var egoera = $("#iragazkia-egoera").val();
  var kategoria = $("#iragazkia-kategoria").val();
  var prezioaMin = parseFloat($("#prezioa-min").val());
  var prezioaMax = parseFloat($("#prezioa-max").val());

  // Array globala iragazi
  var emaitzak = produktuGuztiak.filter(function (p) {
    // Stock-a egiaztatu
    if (p.stock <= 0) return false;

    // Bilatu (Izena edo Marka)
    var matchBilatu =
      !bilatuTestua ||
      p.izena.toLowerCase().includes(bilatuTestua) ||
      p.marka.toLowerCase().includes(bilatuTestua);

    // Kategoria
    var matchKategoria = !kategoria || p.id_kategoria === kategoria;

    // Prezioa
    var matchPrezioaMin = isNaN(prezioaMin) || p.prezioa >= prezioaMin;
    var matchPrezioaMax = isNaN(prezioaMax) || p.prezioa <= prezioaMax;

    return matchBilatu && matchKategoria && matchPrezioaMin && matchPrezioaMax;
  });

  // Emaitzak bistaratu
  produktuakBistaratu(emaitzak);
}
```

## 4. PHP (Backend Logika)

### 4.1 Datu-base konexioa

Hau da `php/DB_konexioa.php`, PDO erabiliz.

```php
// php/DB_konexioa.php
<?php
$zerbitzaria = "localhost";
$datu_basea = "birtek_db";
$erabiltzailea = "root";
$pasahitza = "1MG32025";

$dsn = "mysql:host=$zerbitzaria;port=3306;dbname=$datu_basea;charset=utf8";

try {
    $konexioa = new PDO($dsn, $erabiltzailea, $pasahitza);
    $konexioa->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(["ERROREA" => "Datu-Base konexio Errorea: " . $e->getMessage()]));
}
?>
```

### 4.2 Bezeroaren Login-a

Hau da `php/login_bezeroa.php`, datuak jaso eta datu-basean egiaztatzeko.

```php
// php/login_bezeroa.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emaila = trim($_POST['emaila']);
    $pasahitza = trim($_POST['pasahitza']);

    try {
        $stmt = $konexioa->prepare("SELECT * FROM bezeroak WHERE emaila = :emaila");
        $stmt->bindParam(':emaila', $emaila);
        $stmt->execute();
        $bezeroa = $stmt->fetch(PDO::FETCH_ASSOC);

        // Pasahitza egiaztatu
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
}
```
