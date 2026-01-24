<?php
session_start();
?>
<!DOCTYPE html>
<html lang="eu">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>BIRTEK - Langileen Gunea</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="../css/fontawesome/css/all.min.css" />
  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" />

  <!-- gure css artxiboak -->
  <link rel="stylesheet" href="../css/estiloak_globala.css" />
  <link rel="stylesheet" href="../css/estiloak_kontaktua.css" />
</head>

<body class="web-gorputza">
  <?php include 'goiburua.php'; ?>

  <main class="eduki-nagusia">
    <section class="langileak-edukiontzia">
      <div class="birtek-java-ap-botoia-kanpo">
        <button class="birtek-java-ap-botoia" title="Laster erabilgarri">
          <i class="fas fa-users-cog"></i> BirtekAp
        </button>
      </div>

      <div class="inprimaki-kutxa">
        <h2 class="inprimaki-titulua testua-zentratuta">Lan egin gurekin</h2>
        <p class="testua-zentratuta tartea-behean-2 testua-grisa">Bete formulario hau eta bidali zure CV-a gure taldean
          sartzeko.</p>

        <form id="langile-eskaera-inprimakia" class="kontaktu-inprimaki-diseinua" action="#" method="POST"
          enctype="multipart/form-data">
          <div class="sareta-2-zutabe">
            <input type="text" name="izena" placeholder="Izena" class="inprimaki-sarrera" required />
            <input type="text" name="abizenak" placeholder="Abizenak" class="inprimaki-sarrera" required />
          </div>
          <input type="email" name="emaila" placeholder="Posta elektronikoa" class="inprimaki-sarrera" required />
          <input type="tel" name="telefonoa" placeholder="Telefono zenbakia" class="inprimaki-sarrera" required />
          <div>
            <label for="cv-igoera" class="label-input-fitxategia">Igo zure CV (PDF):</label>
            <input type="file" id="cv-igoera" name="cv" accept=".pdf" class="inprimaki-sarrera" style="padding: 10px"
              required />
          </div>
          <textarea name="oharra" rows="4" class="inprimaki-sarrera"
            placeholder="Zergatik nahi duzu gurekin lan egin?"></textarea>
          <button type="submit" class="botoia botoi-nagusia">Eskaera Bidali</button>
        </form>
      </div>
    </section>
  </main>

  <?php include 'footer.php'; ?>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="../js/globala.js"></script>
  <script src="../js/langileak_menua.js"></script>
</body>

</html>