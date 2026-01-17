<?php
session_start();
// DB konexioa
//include_once 'DB_konexioa.php'
require_once 'DB_konexioa.php';


//include 'DB_konexioa.php'

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
    
    $stmt = $konexioa->prepare($sql);
    $stmt->execute();
    $db_produktuak = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
            'id_produktua' => (int)$lerroa['id_produktua'],
            'izena' => $lerroa['izena'],
            'deskribapena' => $lerroa['deskribapena'],
            'id_kategoria' => $lerroa['produktu_kategoria_izena'], 
            'marka' => $lerroa['marka'],
            'mota' => $lerroa['mota'], 
            'egoera' => $lerroa['produktu_egoera'],
            'prezioa' => (float)$lerroa['salmenta_prezioa'],
            'stock' => (int)$lerroa['stock'],
            'salgai' => (bool)$lerroa['salgai'],
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
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <!-- Google Fonts -->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
    />

    <!-- gure css artxiboak -->
    <link rel="stylesheet" href="../css/estiloak_globala.css" />
    <link rel="stylesheet" href="../css/estiloak_produktuak.css" />
  </head>

  <!-- ===================================================================================== -->
  <body class="web-gorputza">
    <header class="goiburu-nagusia">
      <nav class="nab-edukiontzia">
        <div class="goiburu-barnealdea">
          <!-- Mugikorra Menu Botoia -->
          <button id="mugikor-menu-botoia" class="mugikor-menu-botoia">
            <!-- burger menu botoia -->
            <i class="fas fa-bars burger-ikonoa"></i>
          </button>

          <!-- logoa -->
          <a href="hasiera.php" class="logo-edukiontzia">
            <span class="logoa">BIRTEK</span>
          </a>

          <div class="nab-menu-mahaigaina">
            <a href="hasiera.php" class="nab-botoia">Hasiera</a>
            <a href="produktuak.php" class="nab-botoia aktibo">Produktuak</a>
            <a href="berriak.php" class="nab-botoia">Berriak</a>
            <a href="kontaktua.php" class="nab-botoia">Kontaktua</a>
            <a href="<?= isset($_SESSION['id_hornitzailea']) ? 'hornitzaile_menua.php' : 'hornitzaile_saioa_hasi.php' ?>" class="nab-botoia <?= isset($_SESSION['id_hornitzailea']) ? 'hornitzailea-aktibo' : '' ?>">Birziklatu</a>
            <a href="langileak_menua.php" class="nab-botoia">Langileak</a>
          </div>

          <div class="nab-ekintzak">
            <?php if (isset($_SESSION['id_bezeroa'])): ?>
                <div class="saio-informazio-edukiontzia">
                    <a href="bezero_menua.php" class="saioa-hasi-botoia aktibo" id="saioa-hasi-botoia" title="Joan Nire Menura">
                        <i class="fas fa-user"></i> <span><?= htmlspecialchars($_SESSION['izena']) ?></span>
                    </a>
                    <button id="saioa-itxi-botoia" class="saioa-hasi-botoia botoi-gorria">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            <?php elseif (isset($_SESSION['id_hornitzailea'])): ?>
                <div class="saio-informazio-edukiontzia">
                    <a href="hornitzaile_menua.php" class="saioa-hasi-botoia aktibo" id="saioa-hasi-botoia" title="Joan Nire Menura">
                        <i class="fas fa-user"></i> <span><?= htmlspecialchars($_SESSION['izena_soziala']) ?></span>
                    </a>
                    <button id="saioa-itxi-botoia" class="saioa-hasi-botoia botoi-gorria">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            <?php else: ?>
                <a href="bezero_saioa_hasi.php" class="saioa-hasi-botoia" id="saioa-hasi-botoia">Saioa Hasi</a>
            <?php endif; ?>
            
            <button class="saski-botoia" id="saski-botoia-toggle">
              <i class="fas fa-shopping-cart"></i>
              <span>Saskia</span>
              <span class="saski-kontagailua">0</span>
            </button>
          </div>
        </div>

        <div id="mugikor-menua" class="mugikor-menu-edukiontzia">
          <a href="hasiera.php" class="nab-botoia">Hasiera</a>
          <a href="produktuak.php" class="nab-botoia aktibo">Produktuak</a>
          <a href="berriak.php" class="nab-botoia">Berriak</a>
          <a href="kontaktua.php" class="nab-botoia">Kontaktua</a>
          <a href="<?= isset($_SESSION['id_hornitzailea']) ? 'hornitzaile_menua.php' : 'hornitzaile_saioa_hasi.php' ?>" class="nab-botoia <?= isset($_SESSION['id_hornitzailea']) ? 'hornitzailea-aktibo' : '' ?>">Birziklatu</a>
          <a href="langileak_menua.php" class="nab-botoia">Langileak</a>

          <?php if (isset($_SESSION['id_bezeroa'])): ?>
              <div class="mugikor-erabiltzaile-edukiontzia">
                  <a href="bezero_menua.php" class="nab-botoia mugikor-erabiltzaile-link">
                      <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['izena']) ?>
                  </a>
                  <button id="mugikor-saioa-itxi-botoia" class="nab-botoia mugikor-logout-botoia">
                      <i class="fas fa-sign-out-alt"></i> Saioa Itxi
                  </button>
              </div>
          <?php elseif (isset($_SESSION['id_hornitzailea'])): ?>
              <div class="mugikor-erabiltzaile-edukiontzia">
                  <a href="hornitzaile_menua.php" class="nab-botoia mugikor-erabiltzaile-link">
                      <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['izena_soziala']) ?>
                  </a>
                  <button id="mugikor-saioa-itxi-botoia" class="nab-botoia mugikor-logout-botoia">
                      <i class="fas fa-sign-out-alt"></i> Saioa Itxi
                  </button>
              </div>
          <?php else: ?>
              <a href="bezero_saioa_hasi.php" class="nab-botoia">Saioa Hasi</a>
          <?php endif; ?>
        </div>
      </nav>
    </header>
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
                  <div class="testua-eskuinera tartea-behean-1">
                      <button class="iragazkiak-berrezarri">Garbitu</button>
                  </div>
                  <!-- Iragazki Taldea: BILATU -->
                  <div class="iragazki-taldea">
                    <label class="iragazki-etiketa">Bilatu</label>
                    <input
                      type="search"
                      placeholder="Produktua..."
                      id="iragazkia-bilatu"
                      class="inprimaki-sarrera"
                    />
                  </div>
                  <!-- Iragazki Taldea: PRODUKTU EGOERA -->
                  <div class="iragazki-taldea">
                    <label class="iragazki-etiketa">Egoera</label>
                    <select id="iragazkia-egoera" class="inprimaki-hautatu">
                      <option value="">Guztiak</option>
                      <option value="Berria">Berria</option>
                      <option value="Berritua A">Berritua A</option>
                      <option value="Berritua B">Berritua B</option>
                    </select>
                  </div>
                  <!-- Iragazki Taldea: ORDENATU -->
                  <div class="iragazki-taldea">
                    <label class="iragazki-etiketa">Ordenatu</label>
                    <select id="iragazkia-ordenatu" class="inprimaki-hautatu">
                      <option value="default">Lehenetsia</option>
                      <option value="prezioa-asc">Prezioa: Txikitik Handira</option>
                      <option value="prezioa-desc">
                        Prezioa: Handitik Txikira
                      </option>
                      <option value="izena-asc">Izena: A-Z</option>
                      <option value="izena-desc">Izena: Z-A</option>
                      <option value="stock-desc">Stock: Handienetik</option>
                      <option value="stock-asc">Stock: Gutxienetik</option>
                    </select>
                  </div>
                  <!-- Iragazki Taldea: PREZIOA (NEW) -->
                  <div class="iragazki-taldea">
                    <label class="iragazki-etiketa">Prezioa (€)</label>
                    <div class="prezio-iragazki-taldea">
                      <input
                        type="number"
                        id="prezioa-min"
                        placeholder="Min"
                        class="inprimaki-sarrera zabaler-erdi"
                      />
                      <input
                        type="number"
                        id="prezioa-max"
                        placeholder="Max"
                        class="inprimaki-sarrera zabaler-erdi"
                      />
                    </div>
                  </div>
    
                  <!-- Iragazki Taldea: KATEGORIA (NEW - From DB Categories) -->
                  <div class="iragazki-taldea">
                    <label class="iragazki-etiketa">Kategoria</label>
                    <select id="iragazkia-kategoria" class="inprimaki-hautatu">
                      <option value="">Guztiak</option>
                      <option value="Ordenagailuak">Ordenagailuak</option>
                      <option value="Telefonia">Telefonia</option>
                      <option value="Irudia">Irudia</option>
                      <option value="Osagarriak">Osagarriak</option>
                      <option value="Softwarea">Softwarea</option>
                      <option value="Sareak eta Zerbitzariak">
                        Sareak eta Zerbitzariak
                      </option>
                    </select>
                  </div>
    
                  <!-- Iragazki Taldea: MOTA (Renamed from Kategoria) -->
                  <div class="iragazki-taldea">
                    <label class="iragazki-etiketa">Mota</label>
                    <select id="iragazkia-mota" class="inprimaki-hautatu">
                      <option value="">Guztiak</option>
                      <option value="Generikoa">Generikoa</option>
                      <option value="Eramangarria">Eramangarria</option>
                      <option value="Mahai-gainekoa">Mahai-gainekoa</option>
                      <option value="Mugikorra">Mugikorra</option>
                      <option value="Tableta">Tableta</option>
                      <option value="Zerbitzaria">Zerbitzaria</option>
                      <option value="Pantaila">Pantaila</option>
                      <option value="Softwarea">Softwarea</option>
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
                  <div class="produktu-txartela">
                    <div class="txartel-irudia">
                      <img
                        src="<?php echo htmlspecialchars($produktua['irudia_url']); ?>"
                        alt="<?php echo htmlspecialchars($produktua['izena']); ?>"
                        class="txartel-irudia"
                        onerror="this.src='../irudiak/birtek1.jpeg'"
                      />
                      <div class="txartel-kategoria-txapa"><?php echo htmlspecialchars($produktua['id_kategoria']); ?></div> 
                    </div>
                    <div class="txartel-edukia">
                      <h3 class="txartel-izenburua"><?php echo htmlspecialchars($produktua['izena']); ?></h3>
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
                        <button class="produktua-ikusi-botoia" onclick="window.location.href='produktua_xehetasunak.php?id=<?php echo $produktua['id_produktua']; ?>'">Ikusi</button>
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

