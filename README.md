# Proiektuaren Dokumentazio Teknikoa

Dokumentu honek proiektuko orri guztietan (HTML, PHP, JS eta CSS) erabilitako elementu, funtzio, metodo eta teknikak zerrendatzen ditu, bakoitza bere azalpenarekin.

## 1. HTML (HyperText Markup Language)

Web orrien egitura definitzeko erabilitako etiketak eta atributuak.

### Etiketa Nagusiak

- `<!DOCTYPE html>`: Dokumentua HTML5 motakoa dela definitzen du.
- `<html>`: Orriaren erro elementua (`lang="eu"` atributuarekin hizkuntza zehazteko).
- `<head>`: Dokumentuaren metadatuak, izenburua eta kanpo-loturak (CSS, script-ak) biltzen ditu.
- `<body>`: Orriaren eduki ikusgarria biltzen du.

### Egiturazko Etiketak

- `<header>` / `<div class="goiburu-nagusia">`: Orriaren goiburua, nabigazioa eta logotipoa biltzeko.
- `<main>`: Eduki nagusiarentzako edukiontzia.
- `<section>`: Edukiaren atal tematikoak banatzeko (adibidez, produktu sarea edo harremanetarako orria).
- `<div>`: Edukiak multzokatzeko erabiltzen den edukiontzi orokorra.
- `<footer>` / `<div class="oin-nagusia">`: Orriaren oina (harremanetarako datuak, copyright, estekak).
- `<span>`: Testu zati txikiak edo elementu linealak estiloekin markatzeko (adibidez, prezioak edo stock abisuak).

### Inprimakiak

- `<form>`: Datuak bidaltzeko formularioa (`action` eta `method` atributuekin).
- `<input>`: Datuak sartzeko eremuak. Erabilitako motak:
  - `type="text"`: Testu arrunta.
  - `type="email"`: Posta elektroniko formatua baliztatzeko.
  - `type="password"`: Pasahitza ezkutatzeko.
  - `type="number"`: Zenbakiak sartzeko.
  - `type="checkbox/radio"`: Aukerak hautatzeko (iragazkietan).
- `<button>`: Ekintzak burutzeko botoiak (`type="submit"` bidaltzeko).
- `<select>` eta `<option>`: Aukera anitzeko zerrendak sortzeko (adibidez, ordenatzeko irizpideak).
- `<datalist>`: Input bati aurredefinitutako aukerak emateko (adibidez, herrien zerrenda).

### Testua eta Edukia

- `<h1>` - `<h6>`: Izenburuak eta azpi-izenburuak hierarkiaren arabera.
- `<p>`: Paragrafoak.
- `<a>`: Hiperestekak beste orrietara edo sekzioetara nabigatzeko (`href`).
- `<ul>` eta `<li>`: Zerrenda desordenatuak (buletdun zerrendak).
- `<table>`, `<thead>`, `<tbody>`, `<tr>`, `<th>`, `<td>`: Datuak taula formatuan egituratzeko (adibidez, saskiaren xehetasunak).

### Multimedia eta Metadatuak

- `<img>`: Irudiak txertatzeko (`src`, `alt`, `class`, `onerror` irudia kargatzen ez bada lehenestekoa jartzeko).
- `<meta>`: Dokumentuaren informazioa (kodeketa `charset="UTF-8"`, `viewport` responsibitatea lortzeko).
- `<link>`: Kanpo baliabideak (CSS fitxategiak, ikonoak) estekatzeko.
- `<script>`: JavaScript kodea edo fitxategiak txertatzeko.

---

## 2. PHP (Hypertext Preprocessor)

Zerbitzari aldeko logika kudeatzeko eta datu-basearekin komunikatzeko erabilitakoa.

### Datu-base Konexioa (PDO)

- `new PDO(...)`: Datu-basearekin konexio segurua sortzeko objektua.
- `setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION)`: Erroreak kudeatzeko konfigurazioa.
- `try { ... } catch (PDOException $e) { ... }`: Erroreak harrapatu eta kudeatzeko blokea. Datu-base konexioan akatsak gertatuz gero, `die()` bidez exekuzioa gelditzen da errore mezua erakutsiz.

### SQL Kontsultak

- `prepare($sql)`: SQL sententzia bat prestatzen du segurtasuna bermatzeko (SQL Injection ekiditeko).
- `execute()`: Prestatutako sententzia exekutatzen du.
- `fetchAll(PDO::FETCH_ASSOC)`: Emaitza guztiak array asoziatibo batean lortzen ditu.

### Datu Trukea (PHP -> JS)

- `json_encode($data)`: PHP array-ak JSON formatura bihurtzen ditu. Hau bereziki erabilia da datu-basetik lortutako produktuak JavaScript aldagai globaletara pasatzeko (adibidez, `var hasierakoProduktuak = <?php echo json_encode($produktuak); ?>;`).

### Saio Kudeaketa

- `session_start()`: Erabiltzailearen saioa hasi edo berreskuratzen du orrien artean datuak mantentzeko.
- `$_SESSION`: Saioan gordetako aldagaiak (adibidez, erabiltzailearen izena edo mota).

### Datuen Sarrera/Irteera

- `$_GET`: URL bidez bidalitako parametroak jasotzeko.
- `$_POST`: Formulario bidez modu seguruan bidalitako datuak jasotzeko.
- `echo`: Testua edo HTML kodea pantailaratzeko.
- `htmlspecialchars($string)`: Karaktere bereziak entitate HTML bihurtzeko (XSS segurtasuna).

### Kontrol Egiturak eta Funtzioak

- `include` / `include_once`: Beste PHP fitxategi baten edukia txertatzeko (adibidez, goiburua, oina edo konexioa).
- `require_once`: `include` bezala, baina fitxategia ezinbestekoa bada errorea ematen du.
- `header("Location: ...")`: Erabiltzailea beste orri batera birbideratzeko.
- `isset($var)`: Aldagai bat definituta dagoen eta null ez den egiaztatzeko.
- `empty($var)`: Aldagai bat hutsik dagoen egiaztatzeko.
- `foreach ($array as $item)`: Zerrendak (array-ak) iteratzeko begizta.

---

## 3. JavaScript eta jQuery

Bezero aldeko interaktibitatea eta dinamismoa kudeatzeko.

### jQuery Liburutegia

- `$(document).ready(function() { ... })`: DOM-a guztiz kargatu denean kodea exekutatzeko.
- `$(selector)`: Elementuak aukeratzeko (ID `#`, klase `.`, edo etiketa bidez).

### DOM Manipulazioa

- `.html(content)`: Elementu baten HTML edukia aldatzeko.
- `.text(content)`: Elementu baten testu hutsa aldatzeko.
- `.append(content)`: Elementu baten amaieran edukia gehitzeko.
- `.empty()`: Elementu baten eduki guztia ezabatzeko.
- `.addClass()`, `.removeClass()`: CSS klaseak gehitu edo kentzeko (estiloak dinamikoki aldatzeko).
- `.css(property, value)`: CSS estiloak zuzenean aldatzeko.
- `.val()`: Input eremu baten balioa lortzeko edo aldatzeko.
- `.attr(attribute, value)`: Elementu baten atributuak aldatzeko (adibidez, irudiaren `src`).
- `.prop("checked", bool)`: Checkbox edo radio botoien egoera aldatzeko.

### Gertaerak (Events)

- `.on("click", selector, function() { ... })`: Klik ekitaldiak kudeatzeko (event delegation barne, elementu dinamikoetarako).
- `.on("input change", ...)`: Input eremuetan aldaketak detektatzeko (iragazkiak).
- `.fadeIn()`, `.fadeOut()`: Elementuak agertzeko edo desagertzeko animazio leunak.
- `.slideToggle()`: Elementu bat bertikalki ireki edo ixteko (adibidez, iragazkiak mugikorrean).

### Logika eta Datuen Kudeaketa

- `localStorage`: Nabigatzailean datuak gordetzeko (Saskiaren persistentzia lortzeko `setItem` eta `getItem` bidez).
- `JSON.stringify` / `JSON.parse`: Objektuak testu bihurtzeko eta alderantziz (localStorage erabiltzeko).
- `filter()`: Array-ak iragazteko (adibidez, produktuak bilatzean edo kategoriaz iragaztean).
- `sort()`: Array-ak ordenatzeko (adibidez, prezioaren arabera).
- `find()`: Array baten barruan elementu zehatz bat bilatzeko.
- `forEach` / `$.each`: Zerrendak iteratzeko.

### Beste Funtzio Batzuk

- `window.location.href`: Orriaren URL-a aldatu eta nabigatzeko.
- `setTimeout()`: Ekintzak atzeratzeko (adibidez, jakinarazpen mezuak 2 segundoz erakusteko).
- `confirm()`: Baieztapen leiho bat erakusteko.
- `alert()`: Ohar leiho sinple bat erakusteko.

### AJAX (Asynchronous JavaScript and XML)

- `$.ajax({ ... })`: Zerbitzariarekin komunikazio asinkronoa egiteko. Proiektu honetan honako kasu hauetan erabili da:
  - **Pasahitza berreskuratzeko** (`bezero_saioa_hasi.js` eta `hornitzaile_saioa_hasi.js`):
    - Erabiltzaileak posta elektronikoa sartzean, PHP fitxategiari (`lortu_pasahitza_*.php`) deitzen dio `POST` bidez, pasahitza berreskuratzeko prozesua hasteko orria birkargatu gabe.
  - **Langileen eskaerak kudeatzeko** (`langileak_menua.js`):
    - `gorde_eskaera_langilea.php` fitxategiari datuak eta fitxategiak (`FormData`) bidaltzeko `POST` bidez. Honek fitxategien igoera ahalbidetzen du orria freskatu gabe, eta erantzuna jasotzean interfazea eguneratzen du.
  - **Erosketa prozesatzeko** (`ordainketa.js`):
    - Saskiaren edukia JSON formatuan bidaltzen du `prozesatu_erosketa.php` fitxategira `POST` bidez. Arrakasta kasuan, `localStorage` garbitu eta erosketa burutuaren mezua erakusten du dinamikoki, erabiltzailea beste orri batera eraman gabe.

---

## 4. CSS (Cascading Style Sheets)

Web orrien itxura eta diseinua definitzeko.

### Diseinu Teknikak

- **Flexbox** (`display: flex`): Elementuak lerro edo zutabeetan malgutasunez antolatzeko (nabigazio barra, inprimakiak).
- **Grid** (`display: grid`): Diseinu konplexuagoak (saretak) sortzeko (footer-a).
- **Responsive Design**: Mugikorretarako egokitzapenak egitea ("Mobile First" ikuspegia eta zabaleraren araberako doikuntzak).
- **Glassmorphism**: Atzealde zeharrargiak eta lausoak (`backdrop-filter: blur`, `rgba` koloreak) erabiltzea itxura modernoa lortzeko (goiburuan).

### Hautatzaileak eta Sasi-klaseak (Pseudo-classes)

- `:root`: Aldagai globalak definitzeko.
- `:hover`: Sagua elementuaren gainean pasatzean estiloa aldatzeko (botoietan, esteketan).
- `:active`: Elementua klikatzean estiloa aldatzeko.
- `::before`, `::after`: Elementuen aurretik edo atzetik eduki dekoratiboa txertatzeko.

### Aldagaiak (CSS Variables)

- `var(--aldagai-izena)`: Koloreak, iturriak eta neurriak leku bakarrean definitu eta webgune osoan berrerabiltzeko (`--kolore-nagusia`, `--font-testua`, etab.).

### Animazioak eta Trantsizioak

- `transition`: Propietateen aldaketak leuntzeko (adibidez, kolorea aldatzean, tamaina aldatzean).
- `@keyframes`: Animazio pertsonalizatuak sortzeko (adibidez, `saskiPop` saskira elementu bat gehitzean botoia puzteko).
- `animation`: Definitutako keyframe animazioak aplikatzeko.
