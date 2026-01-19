<?php
session_start();
if (!isset($_SESSION['id_bezeroa'])) {
    header("Location: bezero_saioa_hasi.php");
    exit();
}
$izena = $_SESSION['izena'];
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bezeroaren Menua - BIRTEK</title>
    <!-- Use existing CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="../css/estiloak_globala.css">
    <link rel="stylesheet" href="../css/estiloak_bezero_menua.css">
</head>
<body class="web-gorputza">
    <?php include_once 'goiburua.php'; ?>

    <main class="eduki-nagusia">
        <h2 class="ongi-etorri-mezua">Kaixo, <?= htmlspecialchars($izena) ?>!</h2>
        
        <div class="menu-bezeroa">
            <!-- Button 1: Datu-Pertsonalak aldatu -->
            <a href="bezero_datuak_aldatu.php" class="menu-txartela">
                <i class="fas fa-user-edit menu-ikonoa"></i>
                <span class="menu-izenburua">Datu-Pertsonalak aldatu</span>
            </a>

            <!-- Button 2: Erosketak Kudeatu -->
            <a href="bezero_eskaerak.php" class="menu-txartela">
                <i class="fas fa-shopping-bag menu-ikonoa"></i>
                <span class="menu-izenburua">Erosketak Kudeatu</span>
            </a>
        </div>

        <button class="saioa-itxi-botoia botoi-gorria" id="logout-botoia-menua">
            <i class="fas fa-sign-out-alt"></i>Saioa Itxi
        </button>
    </main>

    <?php include 'footer.php'; ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
</body>
</html>

