<?php
session_start();
require_once 'DB_konexioa.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$produktua = null;
$xehetasunak = null; // Subklasearen datuak hemen gordeko dira
$error_message = "";

if ($id > 0) {
    try {
        // 1. OINARRIZKO DATUAK LORTU
        $stmt = $konexioa->prepare("SELECT p.*, k.izena as kategoria_izena FROM produktuak p LEFT JOIN produktu_kategoriak k ON p.kategoria_id = k.id_kategoria WHERE p.id_produktua = ?");
        $stmt->execute([$id]);
        $produktua = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$produktua) {
            $error_message = "Produktua ez da aurkitu.";
        } else {
            // 2. MOTAREN ARABERA ZEHETASUNAK LORTU (Subklaseak)
            $mota = $produktua['mota']; // ENUM baliotik (Eramangarria, Mahai-gainekoa, etc.)
            $taula = "";

            /* 
              mota ENUM('Generikoa', 'Eramangarria', 'Mahai-gainekoa', 'Mugikorra', 'Tableta', 'Zerbitzaria', 'Pantaila', 'Softwarea', 'Periferikoak', 'Kableak')
              DB Taulak: eramangarriak, mahai_gainekoak, mugikorrak, tabletak, zerbitzariak, pantailak, softwareak, periferikoak, kableak
            */

            switch ($mota) {
                case 'Eramangarria': $taula = 'eramangarriak'; break;
                case 'Mahai-gainekoa': $taula = 'mahai_gainekoak'; break;
                case 'Mugikorra': $taula = 'mugikorrak'; break;
                case 'Tableta': $taula = 'tabletak'; break;
                case 'Zerbitzaria': $taula = 'zerbitzariak'; break;
                case 'Pantaila': $taula = 'pantailak'; break;
                case 'Softwarea': $taula = 'softwareak'; break;
                // OHARRA: 'Periferikoak' eta 'Kableak' taula izenak SQL eskeman 'periferikoak' eta 'kableak' dira (plural).
                case 'Periferikoak': $taula = 'periferikoak'; break; 
                case 'Kableak': $taula = 'kableak'; break;
                
                case 'Generikoa': default: $taula = ""; break;
            }

            if (!empty($taula)) {
                 $stmtSub = $konexioa->prepare("SELECT * FROM $taula WHERE id_produktua = ?");
                 $stmtSub->execute([$id]);
                 $xehetasunak = $stmtSub->fetch(PDO::FETCH_ASSOC);
            }
        }

    } catch (PDOException $e) {
        $error_message = "Errorea datu-basean: " . $e->getMessage();
    }
} else {
    $error_message = "Ez da produktu ID baliozkorik eskatu.";
}
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Produktuaren izena hartu datu basetik -->
    <title><?php echo $produktua ? htmlspecialchars($produktua['izena']) . " - BIRTEK" : "Produktua ez da aurkitu"; ?></title>
     <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"/>
    <link rel="stylesheet" href="../css/estiloak_globala.css">
    <link rel="stylesheet" href="../css/estiloak_produktu_xehetasunak.css">

</head>
<body class="web-gorputza">
    <!-- GOIBURUA -->
    <header class="goiburu-nagusia">
      <nav class="nab-edukiontzia">
        <div class="goiburu-barnealdea">
          <!-- Mugikorra Menu Botoia -->
          <button id="mugikor-menu-botoia" class="mugikor-menu-botoia">
            <i class="fas fa-bars burger-ikonoa"></i>
          </button>

          <!-- logoa -->
          <a href="hasiera.php" class="logo-edukiontzia">
            <span class="logoa">BIRTEK</span>
          </a>

          <div class="nab-menu-mahaigaina">
            <a href="hasiera.php" class="nab-botoia">Hasiera</a>
            <a href="produktuak.php" class="nab-botoia">Produktuak</a>
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
                <a href="bezero_saioa_hasi.php" class="saioa-hasi-botoia">Saioa Hasi</a>
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
            <a href="produktuak.php" class="nab-botoia">Produktuak</a>
            <a href="berriak.php" class="nab-botoia">Berriak</a>
            <a href="kontaktua.php" class="nab-botoia">Kontaktua</a>
            <a href="<?= isset($_SESSION['id_hornitzailea']) ? 'hornitzaile_menua.php' : 'hornitzaile_saioa_hasi.php' ?>" class="nab-botoia <?= isset($_SESSION['id_hornitzailea']) ? 'hornitzailea-aktibo' : '' ?>">Birziklatu</a>
            <a href="langileak_menua.php" class="nab-botoia">Langileak</a>
        </div>
      </nav>
    </header>

    <main class="eduki-nagusia">
        <?php if ($error_message): ?>
            <div class="xehetasunak-errorea">
                <h2>Arazotxo bat egon da...</h2>
                <p><?php echo htmlspecialchars($error_message); ?></p>
                <a href="produktuak.php" class="botoia botoi-nagusia botoia-itzuli-xehetasunak">Produktu guztietara itzuli</a>
            </div>
        <?php elseif ($produktua): ?>
            <!-- Class renamed to xehetasunak-edukiontzia -->
            <div class="xehetasunak-edukiontzia">
                <div class="xehetasunak-irudia-container">
                    <?php 
                        $irudia = $produktua['irudia_url'];
                        if (!empty($irudia)) {
                            if (strpos($irudia, 'http') === 0) {
                                $imgUrl = $irudia;
                            } else {
                                $imgUrl = '../irudiak/' . $irudia;
                            }
                        } else {
                            $imgUrl = '../irudiak/birtek1.jpeg';
                        }
                    ?>
                    <img src="<?php echo htmlspecialchars($imgUrl); ?>" 
                         alt="<?php echo htmlspecialchars($produktua['izena']); ?>" 
                         class="xehetasunak-irudia"
                         onerror="this.src='../irudiak/birtek1.jpeg'">
                </div>
                
                <div class="xehetasunak-info">
                    <h1 class="produktu-izenburua"><?php echo htmlspecialchars($produktua['izena']); ?></h1>
                    
                    <div class="produktu-meta">
                        <!-- Mota eta Kategoria -->
                        <span class="meta-etiketa"><i class="fas fa-tag"></i> <?php echo htmlspecialchars($produktua['mota']); ?></span> 
                        <span class="meta-etiketa"><i class="fas fa-certificate"></i> <?php echo htmlspecialchars($produktua['marka']); ?></span>
                        <span class="meta-etiketa"><i class="fas fa-heartbeat"></i> <?php echo htmlspecialchars($produktua['produktu_egoera']); ?></span>
                    </div>

                    <!-- ZEHETASUN TEKNIKOAK - Subklasearen arabera -->
                    <?php if ($xehetasunak): ?>
                        <div class="xehetasun-teknikoak-kutxa">
                            <h4 class="xehetasun-teknikoak-izenburua">Ezaugarri Teknikoak:</h4>
                            <ul class="xehetasun-teknikoak-zerrenda">
                                <?php foreach ($xehetasunak as $gakoa => $balioa): ?>
                                    <?php if ($gakoa !== 'id_produktua' && $balioa !== null && $balioa !== ''): ?>
                                        <li class="xehetasun-teknikoak-item">
                                            <strong><?php echo ucfirst(str_replace('_', ' ', $gakoa)); ?>:</strong> 
                                            <?php 
                                                // Balio boolearrak 'Bai'/'Ez' bihurtu
                                                if ($balioa == '1' && strlen($balioa) == 1) echo 'Bai';
                                                elseif ($balioa == '0' && strlen($balioa) == 1) echo 'Ez';
                                                else echo htmlspecialchars($balioa); 
                                            ?>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="produktu-prezioa">
                        <?php echo number_format($produktua['salmenta_prezioa'], 2); ?> €
                        <?php if(!empty($produktua['eskaintza'])): ?> 
                            <span class="prezioa-eskaintza">
                                <?php 
                                    // Adibidez, eskaintza kalkulatu nahi bada.
                                    // Hemen 'eskaintza' atributua % bat den edo prezio zuzena den ez dago argi, 
                                    // baina bistaratze sinple bat egingo dugu.
                                    echo "Eskaintza: " . $produktua['eskaintza'] . "% DTO"; 
                                ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="stock-info <?php echo $produktua['stock'] > 0 ? 'stock-bai' : 'stock-ez'; ?>">
                        <?php if ($produktua['stock'] > 0): ?>
                            <i class="fas fa-check-circle"></i> Stock-ean: <?php echo $produktua['stock']; ?> ale
                        <?php else: ?>
                            <i class="fas fa-times-circle"></i> Agortuta
                        <?php endif; ?>
                    </div>

                    <p class="produktu-deskribapena">
                        <?php echo nl2br(htmlspecialchars($produktua['deskribapena'])); ?>
                    </p>

                    <div class="ekintza-eremua">
                        <?php if ($produktua['stock'] > 0): ?>
                            <div class="produktu-kantitatea-aldatu">
                                <label for="botoi-kopurua" class="kantitate-label">Kantitatea:</label>
                                <button class="kantitate-btn" id="kendu-kantitatea">-</button>
                                <input type="number" id="botoi-kopurua" class="kantitate-input" value="1" min="1" max="<?php echo $produktua['stock']; ?>">
                                <button class="kantitate-btn" id="gehitu-kantitatea">+</button>
                            </div>
                            <button class="botoia botoi-nagusia saskiratu-xehetasunak saskiratu-xehetasunak-botoia" 
                                    data-id="<?php echo $produktua['id_produktua']; ?>"
                                    data-izena="<?php echo htmlspecialchars($produktua['izena']); ?>"
                                    data-prezioa="<?php echo $produktua['salmenta_prezioa']; ?>"
                                    data-stock="<?php echo $produktua['stock']; ?>"
                                    >
                                <i class="fas fa-cart-plus"></i> Saskira Gehitu
                            </button>
                        <?php else: ?>
                            <button class="botoia xehetasunak-agortuta-botoia" disabled>
                                Stock agortuta
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <!-- OINA - FOOTER -->
    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
    <script>
        $(document).ready(function() {
            // Kantitatea aldatzeko botoiak (+ / -)
            $("#gehitu-kantitatea").click(function() {
                var input = $("#botoi-kopurua");
                var balioa = parseInt(input.val()) || 1;
                var max = parseInt(input.attr("max"));
                if (balioa < max) {
                    input.val(balioa + 1);
                }
            });

            $("#kendu-kantitatea").click(function() {
                var input = $("#botoi-kopurua");
                var balioa = parseInt(input.val()) || 1;
                var min = parseInt(input.attr("min")) || 1;
                if (balioa > min) {
                    input.val(balioa - 1);
                }
            });

            // Xehetasun orriko saskiratu logika espezifikoa
            $(".saskiratu-xehetasunak").click(function() {
                var btn = $(this);
                var id = btn.data("id");
                var izena = btn.data("izena");
                var prezioa = parseFloat(btn.data("prezioa"));
                var stock = parseInt(btn.data("stock")) || 0;
                var kantitatea = parseInt($("#botoi-kopurua").val()) || 1;
                
                // Saskia berreskuratu
                var saskia = JSON.parse(localStorage.getItem("birtek_saskia")) || [];
                
                // Begiratu ea badagoen
                var badago = saskia.find(i => i.id == id);
                var saskianDagoenKantitatea = badago ? badago.kantitatea : 0;

                // STOCK KONTROLA
                if ((saskianDagoenKantitatea + kantitatea) > stock) {
                    alert("Ezin da gehitu: Stock nahikorik ez (" + stock + " ale geratzen dira). Saskian: " + saskianDagoenKantitatea);
                    return;
                }

                if (badago) {
                    badago.kantitatea += kantitatea;
                } else {
                    saskia.push({
                        id: id,
                        izena: izena,
                        prezioa: prezioa,
                        kantitatea: kantitatea,
                        stock: stock // Gordetzen dugu ere, globala.js-en erabiltzeko
                    });
                }
                
                // Gorde (Globala.js logika erabiltzen badu, ondo. Bestela eskuz)
                if (typeof window.saskiaGorde === "function") {
                    window.saskiaGorde(saskia); // Honek dropdown ere eguneratzen du
                } else {
                    localStorage.setItem("birtek_saskia", JSON.stringify(saskia));
                    location.reload(); 
                }
                
                // Animazio txiki bat botoian
                btn.html('<i class="fas fa-check"></i> Gehituta!');
                btn.css('background-color', '#166534');
                setTimeout(function() {
                    btn.html('<i class="fas fa-cart-plus"></i> Saskira Gehitu');
                    btn.css('background-color', ''); 
                }, 2000);
            });
        });
    </script>
</body>
</html>
