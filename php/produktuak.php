<?php
session_start();
// DB konexioa
//include_once 'DB_konexioa.php'
require_once 'DB_konexioa.php';


// produktu kopuru totala lortu
$stmt_total = $konexioa->prepare("SELECT COUNT(*) FROM produktuak WHERE salgai = 1 AND stock > 0");
$stmt_total->execute();
$produktu_kopuru_totala = $stmt_total->fetchColumn();

$produktuak_lista = [];

try {
  // Kontsulta nagusia
  $sql = "SELECT p.*, k.izena as produktu_kategoria_izena 
            FROM produktuak p 
            LEFT JOIN produktu_kategoriak k ON p.kategoria_id = k.id_kategoria
            WHERE p.salgai = 1 AND p.stock > 0";
  
  $params = []; // Parametroak gordetzeko arraya

  // BILAKETA LOGIKA (PHP)
  $bilatu_hitza = "";
  if (isset($_GET['bilatu']) && !empty(trim($_GET['bilatu']))) {
      $bilatu_hitza = trim($_GET['bilatu']);
      $sql .= " AND (p.izena LIKE :bilatu OR p.deskribapena LIKE :bilatu OR p.marka LIKE :bilatu)";
      $params[':bilatu'] = "%" . $bilatu_hitza . "%";
  }

  // PREZIOAREN ARABERA ordenatu produktuak php bidez 
  if (isset($_GET['prezio-ordenatu'])) {
    $ordena = $_GET['prezio-ordenatu'];
    if ($ordena === 'prezioa-asc') {
      $sql .= " ORDER BY p.salmenta_prezioa ASC";
    } elseif ($ordena === 'prezioa-desc') {
      $sql .= " ORDER BY p.salmenta_prezioa DESC";
    }
  }

  $stmt = $konexioa->prepare($sql);
  $stmt->execute($params);
  $db_produktuak = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Kategoriak lortu filtrorako
  $stmt_kategoriak = $konexioa->prepare("SELECT izena FROM produktu_kategoriak ORDER BY izena");
  $stmt_kategoriak->execute();
  $kategoriak_filtro = $stmt_kategoriak->fetchAll(PDO::FETCH_COLUMN);

  // Egoerak lortu filtrorako (salgai dauden produktuetatik)
  $stmt_egoerak = $konexioa->prepare("SELECT DISTINCT produktu_egoera FROM produktuak WHERE salgai = 1 AND stock > 0 ORDER BY produktu_egoera");
  $stmt_egoerak->execute();
  $egoerak_filtro = $stmt_egoerak->fetchAll(PDO::FETCH_COLUMN);

  // Motak lortu filtrorako (salgai dauden produktuetatik)
  $stmt_motak = $konexioa->prepare("SELECT DISTINCT mota FROM produktuak WHERE salgai = 1 AND stock > 0 ORDER BY mota");
  $stmt_motak->execute();
  $motak_filtro = $stmt_motak->fetchAll(PDO::FETCH_COLUMN);

  // Datuak formatu egokian prestatu (JS-ak espero duen bezala)
  foreach ($db_produktuak as $lerroa) {
    $irudia = $lerroa['irudia_url'];
    if (!empty($irudia)) {
      // Begiratu ea URL osoa den (http...//) edo fitxategi izen soila
      if (strpos($irudia, 'http') === 0) {
        $final_url = $irudia;
      } else {
        // Irudiak karpetan badago fitxategia
        $final_url = '../irudiak/' . $irudia;
      }
    } else {
      $final_url = 'https://via.placeholder.com/300?text=Irudirik+Ez';
    }

    $produktuak_lista[] = [
      'id_produktua' => (int) $lerroa['id_produktua'],
      'izena' => $lerroa['izena'],
      'deskribapena' => $lerroa['deskribapena'],
      'id_kategoria' => $lerroa['produktu_kategoria_izena'],
      'marka' => $lerroa['marka'],
      'mota' => $lerroa['mota'],
      'egoera' => $lerroa['produktu_egoera'],
      'prezioa' => (float) $lerroa['salmenta_prezioa'],
      'stock' => (int) $lerroa['stock'],
      'salgai' => (bool) $lerroa['salgai'],
      'irudia_url' => $final_url,
      'ezaugarriak_json' => [
        'Egoera Oharra' => $lerroa['produktu_egoera_oharra'] ?? 'Informazio gehigarririk ez.',
        'Mota' => $lerroa['mota']
      ]
    ];
  }

} catch (Exception $e) {
  // Errorea gertatuz gero, array hutsa edo errorea kudeatu dezakegu
  // Kasu honetan, HTMLan hutsik agertuko da edo mezu bat
  $error_message = "Errorea datu basearekin: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="eu">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BIRTEK - Produktuak</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="../css/fontawesome/css/all.min.css" />
  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" />

  <!-- gure css artxiboak -->
  <link rel="stylesheet" href="../css/estiloak_globala.css" />
  <link rel="stylesheet" href="../css/estiloak_produktuak.css" />
</head>

<!-- ===================================================================================== -->

<body class="web-gorputza">
  <?php include 'goiburua.php'; ?>
  <!-- ================================================================================================= -->
  <main class="eduki-nagusia">
    <!-- PRODUKTUAK ORRIA -->
    <section>
      <h2 class="produktuak-titulua">Gure Produktuak</h2>

      <div id="produktu-kopuru-info">
        [ <span id="kopurua-txapa"><?php echo $produktu_kopuru_totala; ?></span> ]
      </div>

      <div class="produktuak-orria">
        <div class="alboko-barra">
          <!-- Iragazkiak -->
          <div class="iragazki-kaxa">
            <!-- Iragazki Goiburua -->
            <div class="iragazki-goiburua">
              <h3 class="iragazki-izenburua">
                <!-- Iragazki filtro IKONOA -->
                <i class="fas fa-filter"></i>Iragazkiak
              </h3>
            </div>

            <!-- Iragazki Edukia (Mobilean ezkutatzeko) -->
            <div class="iragazki-edukia">
              <!-- Garbitu Botoia (Mobilean ezkutuan egoteko) -->
              <div class="berrezarri-botoia">
                <button class="berrezarri-testua">Garbitu</button>
              </div>
              <!-- Iragazki Taldea: BILATZAILEA -->
              <div>
                <form action="produktuak.php" method="GET">
                  <label class="iragazki-etiketa">Bilatu</label>
                  <input type="search" name="bilatu" placeholder="Produktua..." id="iragazkia-bilatu" class="inprimaki-sarrera" value="<?php echo htmlspecialchars($bilatu_hitza); ?>" />
                  <?php if (isset($_GET['prezio-ordenatu'])): ?>
                      <input type="hidden" name="prezio-ordenatu" value="<?php echo htmlspecialchars($_GET['prezio-ordenatu']); ?>">
                  <?php endif; ?>
                </form>
              </div>
              <!-- Iragazki Taldea: PRODUKTU EGOERA -->
              <div>
                <label class="iragazki-etiketa">Egoera</label>
                <select id="iragazkia-egoera" class="inprimaki-hautatu">
                  <option value="">Guztiak</option>
                  <?php foreach ($egoerak_filtro as $egoera): ?>
                    <option value="<?php echo htmlspecialchars($egoera); ?>"><?php echo htmlspecialchars($egoera); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <!-- Iragazki Taldea: ORDENATU -->
              <div>
                <label class="iragazki-etiketa">Ordenatu</label>
                <select id="iragazkia-ordenatu" class="inprimaki-hautatu">
                  <option value="default">Lehenetsia</option>
                  <option value="izena-asc">Izena: A-Z</option>
                  <option value="izena-desc">Izena: Z-A</option>
                  <option value="stock-desc">Stock: Handienetik</option>
                  <option value="stock-asc">Stock: Gutxienetik</option>
                </select>
              </div>
              <!-- Iragazki Taldea: PREZIOA (RADIO BUTTONS) -->
              <div>
                <label class="iragazki-etiketa">Prezioa</label>
                <div class="prezio-ordenatu-radio">
                  <label class="radio-etiketa">
                    <input type="radio" name="prezio-ordenatu" value="prezioa-asc" id="prezio-asc" 
                      <?php if (isset($_GET['prezio-ordenatu']) && $_GET['prezio-ordenatu'] == 'prezioa-asc')
                        echo 'checked'; ?>>
                    Txikitik Handira
                  </label>
                  <label class="radio-etiketa">
                    <input type="radio" name="prezio-ordenatu" value="prezioa-desc" id="prezio-desc" 
                      <?php if (isset($_GET['prezio-ordenatu']) && $_GET['prezio-ordenatu'] == 'prezioa-desc')
                        echo 'checked'; ?>>
                    Handitik Txikira
                  </label>
                </div>
              </div>
              <!-- Iragazki Taldea: FILTRO PREZIOA  -->
              <div>
                <label class="iragazki-etiketa">Filtro Prezioa (€)</label>
                <div class="prezio-iragazki-taldea">
                  <input type="number" id="prezioa-min" placeholder="Min" class="inprimaki-sarrera zabaler-erdi" />
                  <input type="number" id="prezioa-max" placeholder="Max" class="inprimaki-sarrera zabaler-erdi" />
                </div>
              </div>

              <!-- Iragazki Taldea: KATEGORIA -->
              <div>
                <label class="iragazki-etiketa">Kategoria</label>
                <select id="iragazkia-kategoria" class="inprimaki-hautatu">
                  <option value="">Guztiak</option>
                  <?php foreach ($kategoriak_filtro as $kategoria): ?>
                    <option value="<?php echo htmlspecialchars($kategoria); ?>">
                      <?php echo htmlspecialchars($kategoria); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Iragazki Taldea: MOTA  -->
              <div>
                <label class="iragazki-etiketa">Mota</label>
                <select id="iragazkia-mota" class="inprimaki-hautatu">
                  <option value="">Guztiak</option>
                  <?php foreach ($motak_filtro as $mota): ?>
                    <option value="<?php echo htmlspecialchars($mota); ?>"><?php echo htmlspecialchars($mota); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="eduki-zutabea">
          <!-- PRODUKTUEN SAREA -->
          <div class="produktu-sarea">
            <?php if (isset($error_message)): ?>
              <p>Errorea produktuak kargatzean: <?php echo htmlspecialchars($error_message); ?></p>
            <?php elseif (empty($produktuak_lista)): ?>
              <p>Ez dago produkturik eskuragarri une honetan.</p>
            <?php else: ?>
              <?php foreach ($produktuak_lista as $produktua): ?>
                <?php
                $stockKlasea = $produktua['stock'] > 0 ? "txartel-stock" : "txartel-stock-agortuta";
                $prezioaFix = number_format($produktua['prezioa'], 2, '.', '');
                ?>
                  <div class="produktu-txartela" data-id="<?php echo $produktua['id_produktua']; ?>">
                    <div class="txartel-irudia klikagarria-joan">
                <!-- $p = $produktuak['irudia_url']-->
                <!-- $rutaAbs="/2ERR_1TALDEA_BIRTEK_WEB_ORRIA/produktuen_irudiak" -->
                <!-- <img src ="$rutaAbs . $p"-->
                      <img
                      
                        src="<?php echo htmlspecialchars('../produktuen_irudiak/'.$produktua['irudia_url']); ?>"
                        alt="<?php echo htmlspecialchars($produktua['izena']); ?>"
                        class="txartel-irudia"
                        onerror="this.src='../irudiak/birtek1.jpeg'"
                      />
                      <div class="txartel-kategoria-txapa"><?php echo htmlspecialchars($produktua['id_kategoria']); ?></div> 
                    </div>
                    <div class="txartel-edukia">
                      <h3 class="txartel-izenburua klikagarria-joan"><?php echo htmlspecialchars($produktua['izena']); ?></h3>
                      <div class="txartel-informazio-lerroa">
                        <span class="txartel-marka"><?php echo htmlspecialchars($produktua['marka']); ?> | <?php echo htmlspecialchars($produktua['egoera']); ?></span>
                        <span class="<?php echo $stockKlasea; ?>">Stock: <?php echo $produktua['stock']; ?></span>
                      </div>
                      <p class="txartel-azalpena">
                        <?php echo htmlspecialchars($produktua['deskribapena'] ?? ""); ?>
                      </p>

                      <div class="txartel-oina">
                        <span class="txartel-prezioa"><?php echo $prezioaFix; ?> €</span>
                        <button class="produktua-saskiratu-botoia" data-stock="<?php echo $produktua['stock']; ?>" <?php echo $produktua['stock'] === 0 ? 'disabled' : ''; ?>>
                          Saskiratu
                        </button>
                        <button class="produktua-ikusi-botoia klikagarria-joan">Ikusi</button>
                      </div>
                  </div>
                </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>
  </main>
  <!-- ================================================================================================= -->
  <!-- OINA - FOOTER -->
  <?php include 'footer.php'; ?>

  <!-- ================================================================================================= -->
  <!-- SCRIPT ZATIA -->
  <!-- JQUERY LIBURUTEGIA IMPORTATU -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script>
    // PHP-tik datuak pasatzeko JS-ra, filtroak funtziona dezaten
    var hasierakoProduktuak = <?php echo json_encode($produktuak_lista); ?>;
  </script>
  <script src="../js/globala.js"></script>
  <script src="../js/produktuak.js"></script>
</body>

</html>
