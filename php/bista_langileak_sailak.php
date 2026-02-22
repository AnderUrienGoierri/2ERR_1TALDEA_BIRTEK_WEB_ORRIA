<?php
session_start();
require_once 'DB_konexioa.php';

// Egiaztatu formularioa bidali den (lehen kargan defektuzkoak erakusteko)
$ezarpenak_aplikatuta = isset($_GET['ezarpenak']);

// Zutabeen ikusgarritasuna (Hemen defektuzko balioak ezartzen dira lehen kargarako)
$erakutsi_nan = $ezarpenak_aplikatuta ? isset($_GET['nan']) : true;
$erakutsi_jaiotza = isset($_GET['jaiotza_data']);
$erakutsi_herria = $ezarpenak_aplikatuta ? isset($_GET['herria']) : true;
$erakutsi_helbidea = isset($_GET['helbidea']);
$erakutsi_posta_kodea = isset($_GET['posta_kodea']);
$erakutsi_telefonoa = $ezarpenak_aplikatuta ? isset($_GET['telefonoa']) : true;
$erakutsi_emaila = isset($_GET['emaila']);
$erakutsi_saila = $ezarpenak_aplikatuta ? isset($_GET['saila']) : true;
$erakutsi_saila_kokapena = isset($_GET['saila_kokapena']);

// Langileak_sailak bistatik datuak lortu
try {
    $sql = "SELECT * FROM bista_langileak_sailak";
    $stmt = $konexioa->prepare($sql);
    $stmt->execute();
    $langileak = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Errorea datuak lortzean: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="eu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIRTEK - Langileak eta Sailak</title>
    <link rel="stylesheet" href="../css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../css/estiloak_globala.css">
    <style>
        .taula-edukiontzia {
            margin: 20px;
            overflow-x: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .aukera-formularioa {
            background: #fdfdfd;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }
        .aukera-izenburua {
            font-weight: 600;
            margin-bottom: 12px;
            display: block;
            color: #34495e;
        }
        .aukera-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 12px;
            margin-bottom: 20px;
        }
        .aukera-taldea {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-size: 0.95rem;
        }
        .aukera-taldea input {
            cursor: pointer;
            width: 16px;
            height: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #f8f9fa;
            color: #333;
            font-weight: 600;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .izenburua {
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .botoi-eremua {
            display: flex;
            justify-content: flex-end;
        }
        .botoi-iragazi {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
        }
        .botoi-iragazi:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body class="web-gorputza">
    <?php include_once 'goiburua.php'; ?>

    <main class="eduki-nagusia">
        <div class="taula-edukiontzia">
            <h2 class="izenburua">Langileen Zerrenda eta Sailak</h2>

            <?php include_once 'formularioa_bista_langileak_sailak.php'; ?>
            
            <?php if (count($langileak) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Izena</th>
                            <th>Abizena</th>
                            <?php if ($erakutsi_nan): ?><th>NAN</th><?php endif; ?>
                            <?php if ($erakutsi_jaiotza): ?><th>Jaiotza Data</th><?php endif; ?>
                            <?php if ($erakutsi_herria): ?><th>Herria</th><?php endif; ?>
                            <?php if ($erakutsi_helbidea): ?><th>Helbidea</th><?php endif; ?>
                            <?php if ($erakutsi_posta_kodea): ?><th>Posta Kodea</th><?php endif; ?>
                            <?php if ($erakutsi_telefonoa): ?><th>Telefonoa</th><?php endif; ?>
                            <?php if ($erakutsi_emaila): ?><th>Emaila</th><?php endif; ?>
                            <?php if ($erakutsi_saila): ?><th>Saila</th><?php endif; ?>
                            <?php if ($erakutsi_saila_kokapena): ?><th>Kokapena</th><?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($langileak as $langilea): ?>
                            <tr>
                                <td><?= htmlspecialchars($langilea['id_langilea']) ?></td>
                                <td><?= htmlspecialchars($langilea['izena']) ?></td>
                                <td><?= htmlspecialchars($langilea['abizena']) ?></td>
                                <?php if ($erakutsi_nan): ?><td><?= htmlspecialchars($langilea['nan']) ?></td><?php endif; ?>
                                <?php if ($erakutsi_jaiotza): ?><td><?= htmlspecialchars($langilea['jaiotza_data']) ?></td><?php endif; ?>
                                <?php if ($erakutsi_herria): ?><td><?= htmlspecialchars($langilea['herria']) ?></td><?php endif; ?>
                                <?php if ($erakutsi_helbidea): ?><td><?= htmlspecialchars($langilea['helbidea']) ?></td><?php endif; ?>
                                <?php if ($erakutsi_posta_kodea): ?><td><?= htmlspecialchars($langilea['posta_kodea']) ?></td><?php endif; ?>
                                <?php if ($erakutsi_telefonoa): ?><td><?= htmlspecialchars($langilea['telefonoa']) ?></td><?php endif; ?>
                                <?php if ($erakutsi_emaila): ?><td><?= htmlspecialchars($langilea['emaila']) ?></td><?php endif; ?>
                                <?php if ($erakutsi_saila): ?><td><?= htmlspecialchars($langilea['saila']) ?></td><?php endif; ?>
                                <?php if ($erakutsi_saila_kokapena): ?><td><?= htmlspecialchars($langilea['saila_kokapena']) ?></td><?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Ez da langilerik aurkitu.</p>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
</body>
</html>
