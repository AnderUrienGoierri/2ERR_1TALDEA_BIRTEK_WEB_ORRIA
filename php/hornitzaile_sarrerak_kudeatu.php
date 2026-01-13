<?php
session_start();
require_once 'DB_konexioa.php';

if (!isset($_SESSION['id_hornitzailea'])) {
    header("Location: hornitzaile_saioa_hasi.php");
    exit();
}

$id_hornitzailea = $_SESSION['id_hornitzailea'];

// Fetch shipments (sarrerak + lerroak)
// Joining sarrerak, sarrera_lerroak, and produktuak
$sql = "
    SELECT 
        s.id_sarrera, 
        s.data, 
        s.sarrera_egoera,
        sl.id_sarrera_lerroa,
        sl.kantitatea,
        sl.sarrera_lerro_egoera,
        p.id_produktua,
        p.izena as produktu_izena,
        p.marka
    FROM sarrerak s
    JOIN sarrera_lerroak sl ON s.id_sarrera = sl.sarrera_id
    JOIN produktuak p ON sl.produktua_id = p.id_produktua
    WHERE s.hornitzailea_id = :hid
    ORDER BY s.data DESC
";

$stmt = $konexioa->prepare($sql);
$stmt->execute([':hid' => $id_hornitzailea]);
$sarrerak = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarrerak Kudeatu - BIRTEK</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/estiloak_globala.css">
    <link rel="stylesheet" href="../css/estiloak_bezero_eskaerak.css"> <!-- Reusing order list styles -->
    <style>
        .egoera-Bidean { background-color: #ffeeba; color: #856404; padding: 3px 8px; border-radius: 4px; font-weight: bold; }
        .egoera-Jasota { background-color: #d4edda; color: #155724; padding: 3px 8px; border-radius: 4px; font-weight: bold; }
        .produktu-esteka { color: #0056b3; text-decoration: underline; font-weight: 600; }
        .produktu-esteka:hover { color: #003d80; }
    </style>
</head>
<body class="web-gorputza">
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
                        <i class="fas fa-user"></i> <span><?= htmlspecialchars($_SESSION['izena_soziala'] ?? 'Hornitzailea') ?></span>
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
        <div class="eskari-edukiontzia"> <!-- Reusing class for container style -->
            <a href="hornitzaile_menua.php" class="atzera-botoia"><i class="fas fa-arrow-left"></i> Atzera</a>
            <h2>Nire Sarrerak (Bidalketak)</h2>

            <?php if (count($sarrerak) > 0): ?>
                <table class="lerro-taula" style="width: 100%; border-collapse: collapse; margin-top: 20px; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
                    <thead>
                        <tr style="background: #f8f9fa; border-bottom: 2px solid #dee2e6;">
                            <th style="padding: 15px; text-align: left;">Data</th>
                            <th style="padding: 15px; text-align: left;">Produktua</th>
                            <th style="padding: 15px; text-align: center;">Kantitatea</th>
                            <th style="padding: 15px; text-align: center;">Bidalketa Egoera</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sarrerak as $sarrera): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 15px;"><?= date('Y-m-d H:i', strtotime($sarrera['data'])) ?></td>
                                <td style="padding: 15px;">
                                    <?php if ($sarrera['sarrera_lerro_egoera'] == 'Jasota'): ?>
                                        <a href="produktua_xehetasunak.php?id=<?= $sarrera['id_produktua'] ?>" class="produktu-esteka" target="_blank">
                                            <?= htmlspecialchars($sarrera['produktu_izena']) ?>
                                        </a>
                                    <?php else: ?>
                                        <?= htmlspecialchars($sarrera['produktu_izena']) ?>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 15px; text-align: center;"><?= $sarrera['kantitatea'] ?></td>
                                <td style="padding: 15px; text-align: center;">
                                    <span class="egoera-<?= $sarrera['sarrera_lerro_egoera'] ?>">
                                        <?= $sarrera['sarrera_lerro_egoera'] ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Ez daukazu sarrerarik erregistratuta.</p>
            <?php endif; ?>
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
</body>
</html>
