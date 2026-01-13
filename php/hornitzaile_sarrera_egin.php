<?php
session_start();
require_once 'DB_konexioa.php';

if (!isset($_SESSION['id_hornitzailea'])) {
    header("Location: hornitzaile_saioa_hasi.php");
    exit();
}

$id_hornitzailea = $_SESSION['id_hornitzailea'];
$izena_soziala = $_SESSION['izena_soziala'] ?? 'Hornitzailea';
$mezua = "";

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mota_sarrera = $_POST['mota_sarrera']; // 'existing' or 'new'
    $kantitatea = $_POST['kantitatea'];
    
    try {
        $konexioa->beginTransaction();
        $produktua_id = null;

        if ($mota_sarrera == 'existing') {
            $produktua_id = $_POST['produktua_id'];
        } else {
            // New product entry
            $izena = $_POST['izena'];
            $marka = $_POST['marka'];
            $mota = $_POST['mota'];
            $deskribapena = $_POST['deskribapena'];
            $prezioa = $_POST['prezioa'];
            $stock = $_POST['stock'];

            $stmtProd = $konexioa->prepare("INSERT INTO produktuak (izena, marka, mota, deskribapena, prezioa, stock, hornitzaile_id, aktibo) VALUES (?, ?, ?, ?, ?, ?, ?, 1)");
            $stmtProd->execute([$izena, $marka, $mota, $deskribapena, $prezioa, $stock, $id_hornitzailea]);
            $produktua_id = $konexioa->lastInsertId();
        }

        if ($produktua_id && $kantitatea > 0) {
            // 1. Insert into sarrerak
            $stmt = $konexioa->prepare("INSERT INTO sarrerak (hornitzailea_id, langilea_id, sarrera_egoera, data) VALUES (:hid, 1, 'Bidean', NOW())");
            $stmt->execute([':hid' => $id_hornitzailea]);
            $sarrera_id = $konexioa->lastInsertId();

            // 2. Insert into sarrera_lerroak
            $stmtLine = $konexioa->prepare("INSERT INTO sarrera_lerroak (sarrera_id, produktua_id, kantitatea, sarrera_lerro_egoera) VALUES (:sid, :pid, :qty, 'Bidean')");
            $stmtLine->execute([
                ':sid' => $sarrera_id, 
                ':pid' => $produktua_id, 
                ':qty' => $kantitatea
            ]);

            $konexioa->commit();
            $mezua = "Sarrera ondo erregistratu da! Produktua bidean dago.";
        } else {
            throw new Exception("Datu guztiak beharrezkoak dira.");
        }
    } catch (Exception $e) {
        if ($konexioa->inTransaction()) $konexioa->rollBack();
        $mezua = "Errorea sarrera egitean: " . $e->getMessage();
    }
}

// Fetch Supplier's Products for Dropdown
$produktuak = [];
try {
    $stmt = $konexioa->prepare("SELECT id_produktua, izena, marka FROM produktuak WHERE hornitzaile_id = :hid ORDER BY izena");
    $stmt->execute([':hid' => $id_hornitzailea]);
    $produktuak = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {}

?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarrera Egin - BIRTEK</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estiloak_globala.css">
    <link rel="stylesheet" href="../css/estiloak_kontaktua.css">
    <style>
        .form-toggle {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            justify-content: center;
        }
        .toggle-btn {
            padding: 0.75rem 1.5rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            background: #f9f9f9;
            transition: all 0.3s;
        }
        .toggle-btn.active {
            background: #166534;
            color: white;
            border-color: #166534;
        }
        .hidden { display: none; }
        .product-select { width: 100%; padding: 1rem; border: 1px solid #e5e7eb; border-radius: 1rem; background: #f9fafb; }
    </style>
</head>
<body class="web-gorputza">
    <header class="goiburu-nagusia">
      <nav class="nab-edukiontzia">
        <div class="goiburu-barnealdea">
          <button id="mugikor-menu-botoia" class="mugikor-menu-botoia">
            <i class="fas fa-bars burger-ikonoa"></i>
          </button>
          <a href="hasiera.php" class="logo-edukiontzia">
            <span class="logoa">BIRTEK</span>
          </a>
          <div class="nab-menu-mahaigaina">
            <a href="hasiera.php" class="nab-botoia">Hasiera</a>
            <a href="produktuak.php" class="nab-botoia">Produktuak</a>
            <a href="berriak.php" class="nab-botoia">Berriak</a>
            <a href="kontaktua.php" class="nab-botoia">Kontaktua</a>
            <a href="hornitzaile_menua.php" class="nab-botoia hornitzailea-aktibo">Birziklatu</a>
            <a href="langileak_menua.php" class="nab-botoia">Langileak</a>
          </div>
          <div class="nab-ekintzak">
            <?php if (isset($_SESSION['id_bezeroa'])): ?>
                <div class="saio-info-edukiontzia">
                    <a href="bezero_menua.php" class="saioa-hasi-botoia aktibo" id="saioa-hasi-botoia" title="Joan Nire Menura">
                        <i class="fas fa-user"></i> <span><?= htmlspecialchars($_SESSION['izena']) ?></span>
                    </a>
                    <button id="saioa-itxi-botoia" class="saioa-hasi-botoia" style="background:#fee2e2; color:#991b1b; border-color:#f87171;">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            <?php elseif (isset($_SESSION['id_hornitzailea'])): ?>
                <div class="saio-info-edukiontzia">
                    <a href="hornitzaile_menua.php" class="saioa-hasi-botoia aktibo" id="saioa-hasi-botoia" title="Joan Nire Menura">
                        <i class="fas fa-user"></i> <span><?= htmlspecialchars($_SESSION['izena_soziala']) ?></span>
                    </a>
                    <button id="saioa-itxi-botoia" class="saioa-hasi-botoia" style="background:#fee2e2; color:#991b1b; border-color:#f87171;">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            <?php else: ?>
                <a href="bezero_saioa_hasi.php" class="saioa-hasi-botoia" id="saioa-hasi-botoia">Saioa Hasi</a>
            <?php endif; ?>
          </div>
        </div>
        <div id="mugikor-menua" class="mugikor-menu-edukiontzia">
          <a href="hasiera.php" class="nab-botoia">Hasiera</a>
          <a href="produktuak.php" class="nab-botoia">Produktuak</a>
          <a href="berriak.php" class="nab-botoia">Berriak</a>
          <a href="kontaktua.php" class="nab-botoia">Kontaktua</a>
          <a href="hornitzaile_menua.php" class="nab-botoia hornitzailea-aktibo">Birziklatu</a>
          <a href="langileak_menua.php" class="nab-botoia">Langileak</a>

          <?php if (isset($_SESSION['id_bezeroa'])): ?>
              <div class="mugikor-user-container">
                  <a href="bezero_menua.php" class="nab-botoia mugikor-user-link">
                      <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['izena']) ?>
                  </a>
                  <a href="logout_bezeroa.php" class="nab-botoia" style="color: #991b1b; background: #fee2e2; border-top: 1px solid #fecaca;">
                      <i class="fas fa-sign-out-alt"></i> Saioa Itxi
                  </a>
              </div>
          <?php elseif (isset($_SESSION['id_hornitzailea'])): ?>
              <div class="mugikor-user-container">
                  <a href="hornitzaile_menua.php" class="nab-botoia mugikor-user-link">
                      <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['izena_soziala']) ?>
                  </a>
                  <a href="logout_bezeroa.php" class="nab-botoia" style="color: #991b1b; background: #fee2e2; border-top: 1px solid #fecaca;">
                      <i class="fas fa-sign-out-alt"></i> Saioa Itxi
                  </a>
              </div>
          <?php else: ?>
              <a href="bezero_saioa_hasi.php" class="nab-botoia">Saioa Hasi</a>
          <?php endif; ?>
        </div>
      </nav>
    </header>

    <main class="eduki-nagusia">
        <div class="kontaktu-edukiontzia">
            <h2 class="kontaktua-titulua">Sarrera Berria Erregistratu</h2>
            
            <div class="form-toggle">
                <button type="button" class="toggle-btn active" id="btn-existing">Lehendik dagoena</button>
                <button type="button" class="toggle-btn" id="btn-new">Produktu Berria</button>
            </div>

            <div class="inprimaki-kutxa" style="max-width: 800px; margin: 0 auto;">
                <?php if ($mezua): ?>
                    <p class="success-msg" style="background: <?= strpos($mezua, 'Errorea') !== false ? '#f8d7da' : '#d4edda' ?>; color: <?= strpos($mezua, 'Errorea') !== false ? '#721c24' : '#155724' ?>; padding: 10px; border-radius: 5px; margin-bottom: 1.5rem; text-align: center;"><?= $mezua ?></p>
                <?php endif; ?>

                <form class="kontaktu-inprimaki-diseinua" method="POST" id="main-form">
                    <input type="hidden" name="mota_sarrera" id="mota_sarrera" value="existing">
                    
                    <div id="section-existing">
                        <label class="label-input-fitxategia">Aukeratu Produktua:</label>
                        <select name="produktua_id" class="product-select">
                            <option value="">-- Aukeratu --</option>
                            <?php foreach ($produktuak as $prod): ?>
                                <option value="<?= $prod['id_produktua'] ?>">
                                    <?= htmlspecialchars($prod['izena']) ?> (<?= htmlspecialchars($prod['marka']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div id="section-new" class="hidden">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <input type="text" name="izena" class="inprimaki-sarrera" placeholder="Produktuaren Izena">
                            <input type="text" name="marka" class="inprimaki-sarrera" placeholder="Marka">
                            <select name="mota" class="inprimaki-sarrera">
                                <option value="Eramangarria">Eramangarria</option>
                                <option value="Mugikorra">Mugikorra</option>
                                <option value="Pantaila">Pantaila</option>
                                <option value="Osagaia">Osagaia</option>
                                <option value="Periferikoa">Periferikoa</option>
                            </select>
                            <input type="number" step="0.01" name="prezioa" class="inprimaki-sarrera" placeholder="Prezioa">
                            <input type="number" name="stock" class="inprimaki-sarrera" placeholder="Hasierako Stock-a">
                        </div>
                        <textarea name="deskribapena" class="inprimaki-sarrera" placeholder="Deskribapena" rows="3"></textarea>
                    </div>

                    <div style="margin-top: 1rem;">
                        <label class="label-input-fitxategia">Kantitatea (Bidalketa):</label>
                        <input type="number" name="kantitatea" min="1" class="inprimaki-sarrera" required>
                    </div>
                    
                    <button type="submit" class="botoia botoi-nagusia" style="width:100%; margin-top:2rem;">Bidali Produktuak</button>
                </form>
                
                <div style="text-align: center; margin-top: 1.5rem;">
                    <a href="hornitzaile_menua.php" class="back-link" style="color: #666; text-decoration: none;"><i class="fas fa-arrow-left"></i> Atzera Menura</a>
                </div>
            </div>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
    <script>
        $(document).ready(function() {
            $('#btn-existing').click(function() {
                $('.toggle-btn').removeClass('active');
                $(this).addClass('active');
                $('#section-new').addClass('hidden');
                $('#section-existing').removeClass('hidden');
                $('#mota_sarrera').val('existing');
            });
            $('#btn-new').click(function() {
                $('.toggle-btn').removeClass('active');
                $(this).addClass('active');
                $('#section-existing').addClass('hidden');
                $('#section-new').removeClass('hidden');
                $('#mota_sarrera').val('new');
            });
        });
    </script>
</body>
</html>
