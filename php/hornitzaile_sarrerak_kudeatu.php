<?php
session_start();
require_once 'DB_konexioa.php';

if (!isset($_SESSION['id_hornitzailea'])) {
    header("Location: hornitzaile_saioa_hasi.php");
    exit();
}

$id_hornitzailea = $_SESSION['id_hornitzailea'];
$mezua = "";

// 1. EZABATZE LOGIKA
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] === 'delete_sarrera_lerroa') {
    try {
        $id_lerroa = $_POST['id_sarrera_lerroa'];
        
        // Egiaztatu lerroa hornitzailearena den eta "Bidean" dagoen
        $stmt_check = $konexioa->prepare("
            SELECT sl.id_sarrera_lerroa 
            FROM sarrera_lerroak sl
            JOIN sarrerak s ON sl.sarrera_id = s.id_sarrera
            WHERE sl.id_sarrera_lerroa = ? AND s.hornitzailea_id = ? AND sl.sarrera_lerro_egoera = 'Bidean'
        ");
        $stmt_check->execute([$id_lerroa, $id_hornitzailea]);
        
        if ($stmt_check->fetch()) {
            $stmt_del = $konexioa->prepare("DELETE FROM sarrera_lerroak WHERE id_sarrera_lerroa = ?");
            $stmt_del->execute([$id_lerroa]);
            $mezua = "Sarrera lerroa ondo ezabatu da.";
        } else {
            $mezua = "Ezin da lerro hau ezabatu (baliteke jada 'Jasota' izatea).";
        }
    } catch (PDOException $e) {
        $mezua = "Errorea ezabatzean: " . $e->getMessage();
    }
}

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
    <link rel="stylesheet" href="../css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../css/estiloak_globala.css">
    <link rel="stylesheet" href="../css/estiloak_bezero_eskaerak.css"> 
    <link rel="stylesheet" href="../css/estiloak_hornitzaile_menua.css">
</head>
<body class="web-gorputza">
    <?php include_once 'goiburua.php'; ?>

    <main class="eduki-nagusia">
        <div class="eskari-edukiontzia"> 
            <a href="hornitzaile_menua.php" class="atzera-botoia"><i class="fas fa-arrow-left"></i> Atzera</a>
            <h2>Nire Sarrerak (Bidalketak)</h2>

            <?php if ($mezua): ?>
                <div class="alert-berdea">
                    <?= htmlspecialchars($mezua) ?>
                </div>
            <?php endif; ?>

            <?php if (count($sarrerak) > 0): ?>
                <table class="sarrera-taula">
                    <thead>
                        <tr class="sarrera-buru-tr">
                            <th class="sarrera-th">Data</th>
                            <th class="sarrera-th">Produktua</th>
                            <th class="sarrera-th-zentratua">Kantitatea</th>
                            <th class="sarrera-th-zentratua">Bidalketa Egoera</th>
                            <th class="sarrera-th-zentratua">Ekintzak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sarrerak as $sarrera): ?>
                            <tr class="sarrera-gorputz-tr">
                                <td class="sarrera-td"><?= date('Y-m-d H:i', strtotime($sarrera['data'])) ?></td>
                                <td class="sarrera-td">
                                    <?php if ($sarrera['sarrera_lerro_egoera'] == 'Jasota'): ?>
                                        <a href="produktua_xehetasunak.php?id=<?= $sarrera['id_produktua'] ?>" class="produktu-esteka" target="_blank">
                                            <?= htmlspecialchars($sarrera['produktu_izena']) ?>
                                        </a>
                                    <?php else: ?>
                                        <?= htmlspecialchars($sarrera['produktu_izena']) ?>
                                    <?php endif; ?>
                                </td>
                                <td class="sarrera-td-zentratua"><?= $sarrera['kantitatea'] ?></td>
                                <td class="sarrera-td-zentratua">
                                    <span class="egoera-<?= $sarrera['sarrera_lerro_egoera'] ?>">
                                        <?= $sarrera['sarrera_lerro_egoera'] ?>
                                    </span>
                                </td>
                                <td class="sarrera-td-zentratua">
                                    <?php if ($sarrera['sarrera_lerro_egoera'] === 'Bidean'): ?>
                                        <form method="POST" class="inprimaki-inline form-baieztatu" data-mezua="Ziur zaude sarrera hau ezabatu nahi duzula?">
                                            <input type="hidden" name="action" value="delete_sarrera_lerroa">
                                            <input type="hidden" name="id_sarrera_lerroa" value="<?= $sarrera['id_sarrera_lerroa'] ?>">
                                            <button type="submit" class="ezabatu-lerroa-botoia" title="Ezabatu sarrera">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="testu-apala">-</span>
                                    <?php endif; ?>
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
    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
</body>
</html>
