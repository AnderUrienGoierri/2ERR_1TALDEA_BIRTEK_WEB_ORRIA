<?php
session_start();
if (!isset($_SESSION['id_hornitzailea'])) {
    header("Location: hornitzaile_saioa_hasi.php");
    exit();
}
$izena = $_SESSION['izena_soziala'] ?? 'Hornitzailea';
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hornitzailearen Menua - BIRTEK</title>
    <link rel="stylesheet" href="../css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="../css/estiloak_globala.css">
    <link rel="stylesheet" href="../css/estiloak_hornitzaile_menua.css">
</head>
<body class="web-gorputza">
    <?php include 'goiburua.php'; ?>

    <main class="eduki-nagusia">
        <h2 class="ongi-etorri-mezua">Ongi etorri, <?= htmlspecialchars($izena) ?>!</h2>
        
        <div class="menu-hornitzailea">
            <!-- 1. Aukera: Datuak Aldatu -->
            <a href="hornitzaile_datuak_aldatu.php" class="menu-txartela">
                <i class="fas fa-user-edit menu-ikonoa"></i>
                <span class="menu-izenburua">Datu Pertsonalak Aldatu</span>
            </a>

            <!-- 2. Aukera: Sarrera Egin (Produktuak Bidali) -->
            <a href="hornitzaile_sarrera_egin.php" class="menu-txartela">
                <i class="fas fa-truck-loading menu-ikonoa"></i>
                <span class="menu-izenburua">Sarrera Egin</span>
            </a>

            <!-- 3. Aukera: Sarrerak Kudeatu -->
            <a href="hornitzaile_sarrerak_kudeatu.php" class="menu-txartela">
                <i class="fas fa-clipboard-list menu-ikonoa"></i>
                <span class="menu-izenburua">Sarrerak Kudeatu</span>
            </a>
        </div>

        <button class="saioa-itxi-botoia botoi-gorria" id="logout-botoia-menua">
            <i class="fas fa-sign-out-alt"></i> Saioa Itxi
        </button>
    </main>

    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
</body>
</html>
