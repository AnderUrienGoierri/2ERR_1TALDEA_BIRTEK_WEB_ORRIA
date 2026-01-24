<?php
session_start();
?>
<!DOCTYPE html>
<html lang="eu">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BIRTEK - Kontaktua</title>

    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="../css/fontawesome/css/all.min.css"
    />
    <!-- Google Fonts -->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap"
    />

    <!-- gure css artxiboak -->
    <link rel="stylesheet" href="../css/estiloak_globala.css" />
    <link rel="stylesheet" href="../css/estiloak_kontaktua.css" />
  </head>

  <body class="web-gorputza">
    <?php include 'goiburua.php'; ?>

    <main class="eduki-nagusia">
      <section>
        <div class="kontaktu-edukiontzia">
          <h2 class="kontaktua-titulua">Jarri Gurekin Harremanetan</h2>
          <div class="kontaktu-sareta">
            <div class="inprimaki-kutxa">
              <h3 class="inprimaki-titulua">Bidali mezu bat</h3>
              <form class="kontaktu-inprimaki-diseinua">
                <input type="text" class="inprimaki-sarrera" placeholder="Izena" required />
                <input type="email" class="inprimaki-sarrera" placeholder="Emaila" required />
                <textarea rows="4" class="inprimaki-sarrera" placeholder="Mezua" required></textarea>
                <button type="submit" class="botoia botoi-nagusia">Bidali Mezua</button>
              </form>
            </div>
            <div class="sozial-kutxa">
              <img src="../irudiak/birtek_konponketak.png" alt="Repairing Laptop" class="kontaktu-irudia" loading="lazy" style="width: 100%; height: 200px; object-fit: cover; border-radius: 1rem; margin-bottom: 2rem;" />
              <h3 class="sozial-azpititulua">Edo hitz egin gurekin!</h3>
              <a href="https://wa.me/34600000000" target="_blank" class="footer-wa-botoia"><i class="fab fa-whatsapp"></i> WhatsApp Hasi</a>
              <div class="sozial-ikono-lerroa">
                <a href="#" class="sozial-lotura"><i class="fab fa-facebook fb-kolorea"></i></a>
                <a href="#" class="sozial-lotura"><i class="fab fa-instagram ig-kolorea"></i></a>
                <a href="#" class="sozial-lotura"><i class="fab fa-x-twitter tw-kolorea"></i></a>
                <a href="#" class="sozial-lotura"><i class="fab fa-tiktok tik-kolorea"></i></a>
                <a href="#" class="sozial-lotura"><i class="fab fa-telegram tg-kolorea"></i></a>
              </div>
            </div>
            <div class="mapa-edukiontzia">
              <iframe class="mapa-iframe" src="https://maps.google.com/maps?q=Goierri+Eskola,+Arranomendia+2,+Ordizia&t=&z=15&ie=UTF8&iwloc=&output=embed"></iframe>
            </div>
          </div>
        </div>
      </section>
    </main>

    <?php include 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="../js/globala.js"></script>
  </body>
</html>
