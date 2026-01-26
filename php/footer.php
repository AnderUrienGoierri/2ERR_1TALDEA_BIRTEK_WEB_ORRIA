<footer class="oin-nagusia">
  <div class="oin-sarea">
    <div class="testua-erdian">
      <h3 class="oin-izenburua">BIRTEK</h3>
      <img src="../irudiak/birtek_logo_berde_karratua.png" alt="BIRTEK Logo Karratua" class="oin-logo-irudia">
      <p class="oin-testua">Teknologia berreskuratzen, etorkizuna eraikitzen. Goierri Eskolan kokatuta.</p>
    </div>
    <div class="testua-erdian">
      <h4 class="oin-goiburua">Jarrai Gaitzazu</h4>
      <div class="oin-sozial-lerroa">
        <a href="#" class="sozial-lotura testua-fb"><i class="fab fa-facebook"></i></a>
        <a href="#" class="sozial-lotura testua-ig"><i class="fab fa-instagram"></i></a>
        <a href="#" class="sozial-lotura testua-x"><i class="fab fa-x-twitter"></i></a>
        <a href="#" class="sozial-lotura testua-tik"><i class="fab fa-tiktok"></i></a>
        <a href="#" class="sozial-lotura testua-tg"><i class="fab fa-telegram"></i></a>
      </div>
      <div><a href="https://wa.me/34600000000" class="oin-whatsapp-botoia"><i class="fab fa-whatsapp"></i> WhatsApp</a></div>
    </div>
    <div class="testua-erdian">
      <h4 class="oin-goiburua">Lotura Azkarrak</h4>
      <ul class="oin-nab-zerrenda">
        <li><a href="hasiera.php" class="oin-lotura">Hasiera</a></li>
        <li><a href="produktuak.php" class="oin-lotura">Produktuak</a></li>
        <li><a href="berriak.php" class="oin-lotura">Berriak</a></li>
        <li><a href="kontaktua.php" class="oin-lotura">Kontaktua</a></li>
        <li><a href="<?= isset($_SESSION['id_hornitzailea']) ? 'hornitzaile_menua.php' : 'hornitzaile_saioa_hasi.php' ?>" class="oin-lotura">Birziklatu</a></li>
        <li><a href="langileak_menua.php" class="oin-lotura">Langileak</a></li>
      </ul>
    </div>
  </div>
  <div class="oin-copyright">© 2025 BIRTEK</div>
</footer>
