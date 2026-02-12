<header class="goiburu-nagusia">
  <nav class="nab-edukiontzia">
    <div class="goiburu-barnealdea">
      <!-- Mugikorra Menu Botoia -->
      <button id="mugikor-menu-botoia" class="mugikor-menu-botoia">
        <i class="fas fa-bars burger-ikonoa"></i>
      </button>

      <!-- logoa -->
      <a href="hasiera.php" class="logo-edukiontzia">
        <img src="../irudiak/birtek_logo_zuri_borobila.png" alt="BIRTEK Logo" class="goiburu-logo-irudia">
        <span class="logoa">BIRTEK</span>
      </a>

      <!-- Mahai gaineko pantaila menua -->
      <div class="nab-menu-mahaigaina">

        <!-- erabiltzailea kokatuta dagoen orrialdearen esteka berdez markatuta agertzen da (aktibo) -->
        <a href="hasiera.php" class="nab-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'hasiera.php') ? 'aktibo' : ''; ?>">Hasiera</a>
        <a href="produktuak.php" class="nab-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'produktuak.php') ? 'aktibo' : ''; ?>">Produktuak</a>
        <a href="berriak.php" class="nab-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'berriak.php') ? 'aktibo' : ''; ?>">Berriak</a>
        <a href="kontaktua.php" class="nab-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'kontaktua.php') ? 'aktibo' : ''; ?>">Kontaktua</a>
        <!-- gainera, erabiltzailea hornitzailea bada eta hornitzaile saioa hasita badago urdinez markatuta agertuko da BIRZIKLATU esteka  -->
        <a href="<?= isset($_SESSION['id_hornitzailea']) ? 'hornitzaile_menua.php' : 'hornitzaile_saioa_hasi.php' ?>" class="nab-botoia
          <?= isset($_SESSION['id_hornitzailea']) ? (basename($_SERVER['PHP_SELF']) == 'hornitzaile_menua.php' ? 'hornitzailea-aktibo aktibo' : 'hornitzailea-aktibo') : (basename($_SERVER['PHP_SELF']) == 'hornitzaile_saioa_hasi.php' ? 'aktibo' : '') ?>">Birziklatu</a>
        <a href="langileak_menua.php" class="nab-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'langileak_menua.php') ? 'aktibo' : ''; ?>">Langileak</a>
      </div>

      <div class="nab-ekintzak">
        <!-- bezeroak saioa hasita badu erabiltzaile ikonoa berdez markatuta agertzen da (aktibo) -->
        <?php if (isset($_SESSION['id_bezeroa'])): ?>
          <div class="erabiltzaile-dropdown">
                <a href="bezero_menua.php" class="saioa-hasi-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'bezero_menua.php') ? 'aktibo' : ''; ?>">
                  <!-- gainera bezeroaren izena agertzen da -->
                  <i class="fas fa-user-circle"></i> <span><?= htmlspecialchars($_SESSION['izena']) ?></span> <i class="fas fa-chevron-down goiburu-ikono-txikia"></i>
                </a>
                <div class="dropdown-edukia">
                    <a href="bezero_datuak_aldatu.php" class="dropdown-elementua"><i class="fas fa-id-card"></i> Nire Profila</a>
                    <a href="bezero_eskaerak.php" class="dropdown-elementua"><i class="fas fa-shopping-bag"></i> Nire Eskaerak</a>
                    <a href="#" class="dropdown-elementua gorria saioa-itxi-botoia-dropdown"><i class="fas fa-sign-out-alt"></i> Saioa Itxi</a>
                </div>
          </div>

        <!-- hornitzaileak saioa hasita badu erabiltzaile ikonoa berdez markatuta agertzen da (aktibo) -->
        <?php elseif (isset($_SESSION['id_hornitzailea'])): ?>
          <div class="erabiltzaile-dropdown">
                <a href="hornitzaile_menua.php" class="saioa-hasi-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'hornitzaile_menua.php') ? 'aktibo' : ''; ?>">
                  <!-- gainera hornitzailearen izena agertzen da -->
                  <i class="fas fa-user-circle"></i> <span><?= htmlspecialchars($_SESSION['izena_soziala']) ?></span> <i class="fas fa-chevron-down goiburu-ikono-txikia"></i>
                </a>
                <div class="dropdown-edukia">
                    <a href="hornitzaile_datuak_aldatu.php" class="dropdown-elementua"><i class="fas fa-id-card"></i> Nire Profila</a>
                    <a href="hornitzaile_sarrerak_kudeatu.php" class="dropdown-elementua"><i class="fas fa-truck-loading"></i> Nire Sarrerak</a>
                    <a href="#" class="dropdown-elementua gorria saioa-itxi-botoia-dropdown"><i class="fas fa-sign-out-alt"></i> Saioa Itxi</a>
                </div>
          </div>
        <?php else: ?>
          <!-- SAIOA HASI botoia bezeroarentzat da, saioa hasita badago beseroa berdez markatuta agertzen da (aktibo) -->
          <a href="bezero_saioa_hasi.php" class="saioa-hasi-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'bezero_saioa_hasi.php') ? 'aktibo' : ''; ?>" id="saioa-hasi-botoia">Saioa Hasi</a>
        <?php endif; ?>

        <button class="saski-botoia" id="saski-botoia-toggle">
          <i class="fas fa-shopping-cart"></i>
          <span>Saskia</span>
          <span class="saski-kontagailua">0</span>
        </button>
        <div id="saski-mezua" class="saski-mezua"></div>
      </div>
    </div>

    <!-- Mugikor menua (burger menu ikonoa klikatuta) -->
    <div id="mugikor-menua" class="mugikor-menu-edukiontzia">

        <!-- erabiltzailea kokatuta dagoen orrialdearen esteka berdez markatuta agertzen da (aktibo) -->
      <a href="hasiera.php" class="nab-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'hasiera.php') ? 'aktibo' : ''; ?>">Hasiera</a>
      <a href="produktuak.php" class="nab-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'produktuak.php') ? 'aktibo' : ''; ?>">Produktuak</a>
      <a href="berriak.php" class="nab-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'berriak.php') ? 'aktibo' : ''; ?>">Berriak</a>
      <a href="kontaktua.php" class="nab-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'kontaktua.php') ? 'aktibo' : ''; ?>">Kontaktua</a>
      <!-- gainera, erabiltzailea hornitzailea bada eta hornitzaile saioa hasita badago urdinez markatuta agertuko da BIRZIKLATU esteka  -->
      <a href="<?= isset($_SESSION['id_hornitzailea']) ? 'hornitzaile_menua.php' : 'hornitzaile_saioa_hasi.php' ?>" class="nab-botoia <?= isset($_SESSION['id_hornitzailea']) ? (basename($_SERVER['PHP_SELF']) == 'hornitzaile_menua.php' ? 'hornitzailea-aktibo aktibo' : 'hornitzailea-aktibo') : (basename($_SERVER['PHP_SELF']) == 'hornitzaile_saioa_hasi.php' ? 'aktibo' : '') ?>">Birziklatu</a>
      <a href="langileak_menua.php" class="nab-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'langileak_menua.php') ? 'aktibo' : ''; ?>">Langileak</a>

      <!-- bezeroak saioa hasita badu erabiltzaile ikonoa berdez markatuta agertzen da (aktibo) -->
      <?php if (isset($_SESSION['id_bezeroa'])): ?>
          <div class="mugikor-erabiltzaile-edukiontzia">
              <a href="bezero_menua.php" class="nab-botoia mugikor-erabiltzaile-link <?php echo (basename($_SERVER['PHP_SELF']) == 'bezero_menua.php') ? 'aktibo' : ''; ?>">
                <!-- gainera bezeroaren izena agertzen da -->
                <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['izena']) ?>
              </a>
              <button id="mugikor-saioa-itxi-botoia" class="nab-botoia mugikor-logout-botoia">
                <i class="fas fa-sign-out-alt"></i> Saioa Itxi
              </button>
          </div>

      <!-- hornitzaileak saioa hasita badu erabiltzaile ikonoa berdez markatuta agertzen da (aktibo) -->
      <?php elseif (isset($_SESSION['id_hornitzailea'])): ?>
          <div class="mugikor-erabiltzaile-edukiontzia">
              <a href="hornitzaile_menua.php" class="nab-botoia mugikor-erabiltzaile-link <?php echo (basename($_SERVER['PHP_SELF']) == 'hornitzaile_menua.php') ? 'aktibo' : ''; ?>">
                <!-- gainera hornitzailearen izena agertzen da -->
                <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['izena_soziala']) ?>
              </a>
              <button id="mugikor-saioa-itxi-botoia" class="nab-botoia mugikor-logout-botoia">
                <i class="fas fa-sign-out-alt"></i> Saioa Itxi
              </button>
          </div>
      <?php else: ?>
          <!-- SAIOA HASI botoia bezeroarentzat da, saioa hasita badago beseroa berdez markatuta agertzen da (aktibo) -->
          <a href="bezero_saioa_hasi.php" class="nab-botoia <?php echo (basename($_SERVER['PHP_SELF']) == 'bezero_saioa_hasi.php') ? 'aktibo' : ''; ?>">Saioa Hasi</a>
      <?php endif; ?>
    </div>
  </nav>
</header>
