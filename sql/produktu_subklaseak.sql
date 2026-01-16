-- 1. Eramangarriak
CREATE TABLE IF NOT EXISTS eramangarriak (
    id_produktua INT UNSIGNED PRIMARY KEY,
    prozesadorea VARCHAR(100),
    ram_gb INT,
    diskoa_gb INT,
    pantaila_tamaina DECIMAL(4,1),
    bateria_wh INT,
    sistema_eragilea VARCHAR(50),
    pisua_kg DECIMAL(4,2),
    CONSTRAINT fk_eramangarria_produktua FOREIGN KEY (id_produktua) REFERENCES produktuak(id_produktua) ON DELETE CASCADE
);

INSERT INTO eramangarriak (id_produktua, prozesadorea, ram_gb, diskoa_gb, pantaila_tamaina, bateria_wh, sistema_eragilea, pisua_kg) VALUES
(1, 'Apple M3 Pro', 18, 512, 14.2, 70, 'macOS', 1.6),
(2, 'Intel Core i7-1360P', 16, 1024, 13.4, 55, 'Windows 11', 1.23),
(3, 'Intel Core i7-1260P', 32, 1024, 14.0, 57, 'Windows 11 Pro', 1.12),
(4, 'Intel Core i5-1135G7', 8, 256, 13.5, 60, 'Windows 10', 1.3),
(5, 'AMD Ryzen 9 7940HS', 32, 2048, 14.0, 76, 'Windows 11', 1.7),
(6, 'Intel Core i5-1240P', 16, 512, 14.0, 56, 'Windows 11', 1.2),
(7, 'Intel Core i5-1235U', 8, 256, 13.5, 47, 'Windows 11', 1.27),
(8, 'Intel Core i9-13800H', 32, 1024, 15.6, 80, 'Windows 11', 2.01),
(9, 'Intel Core i7-1360P', 16, 1024, 17.0, 80, 'Windows 11', 1.35),
(10, 'Apple M2', 8, 256, 13.6, 52, 'macOS', 1.24);

-- 2. Mahai-gainekoak
CREATE TABLE IF NOT EXISTS mahai_gainekoak (
    id_produktua INT UNSIGNED PRIMARY KEY,
    prozesadorea VARCHAR(100),
    plaka_basea VARCHAR(100),
    ram_gb INT,
    diskoa_gb INT,
    txartel_grafikoa VARCHAR(100),
    elikatze_iturria_w INT,
    kaxa_formatua ENUM('ATX', 'Micro-ATX', 'Mini-ITX', 'E-ATX'),
    CONSTRAINT fk_mahaigainekoa_produktua FOREIGN KEY (id_produktua) REFERENCES produktuak(id_produktua) ON DELETE CASCADE
);

INSERT INTO mahai_gainekoak (id_produktua, prozesadorea, plaka_basea, ram_gb, diskoa_gb, txartel_grafikoa, elikatze_iturria_w, kaxa_formatua) VALUES
(11, 'Intel Core i9-13900K', 'Z790', 64, 2048, 'RTX 4090', 1200, 'ATX'),
(12, 'Intel Core i5-12500', 'B660', 16, 512, 'Intel UHD 770', 300, 'Micro-ATX'),
(13, 'Apple M2 Chip', 'Integrated', 8, 256, 'Apple GPU 10-core', 150, 'Mini-ITX'),
(14, 'AMD Ryzen 7 5800X', 'B550', 32, 1024, 'RTX 3070', 750, 'ATX'),
(15, 'AMD Ryzen 9 5900X', 'X570', 64, 2048, 'RX 6800 XT', 850, 'ATX'),
(16, 'Intel Core i9-12900K', 'Z690I', 32, 2048, 'RTX 3080 Ti', 750, 'Mini-ITX'),
(17, 'Intel Core i7-12700K', 'Z690', 32, 1024, 'RTX 3080', 800, 'ATX'),
(18, 'Intel Core i7-12700T', 'Custom HP', 16, 512, 'Intel Iris Xe', 180, 'Mini-ITX'),
(19, 'Intel Core i5-11400F', 'H510', 16, 512, 'RTX 3060 Aero', 450, 'Mini-ITX'),
(20, 'Apple M1 Max', 'Integrated', 32, 512, 'Apple GPU 24-core', 370, 'Mini-ITX');

-- 3. Mugikorrak
CREATE TABLE IF NOT EXISTS mugikorrak (
    id_produktua INT UNSIGNED PRIMARY KEY,
    pantaila_teknologia VARCHAR(50),
    pantaila_hazbeteak DECIMAL(3,1),
    biltegiratzea_gb INT,
    ram_gb INT,
    kamera_nagusa_mp INT,
    bateria_mah INT,
    sistema_eragilea VARCHAR(50),
    sareak ENUM('4G', '5G'),
    CONSTRAINT fk_mugikorra_produktua FOREIGN KEY (id_produktua) REFERENCES produktuak(id_produktua) ON DELETE CASCADE
);

INSERT INTO mugikorrak (id_produktua, pantaila_teknologia, pantaila_hazbeteak, biltegiratzea_gb, ram_gb, kamera_nagusa_mp, bateria_mah, sistema_eragilea, sareak) VALUES
(21, 'OLED', 6.1, 128, 8, 48, 3274, 'iOS', '5G'),
(22, 'AMOLED', 6.8, 256, 12, 200, 5000, 'Android', '5G'),
(23, 'OLED', 6.7, 128, 12, 50, 5050, 'Android', '5G'),
(24, 'AMOLED', 6.6, 256, 12, 50, 5000, 'Android', '5G'),
(25, 'AMOLED', 6.7, 128, 8, 50, 5000, 'Android', '5G'),
(26, 'OLED', 6.5, 256, 12, 48, 5000, 'Android', '5G'),
(27, 'OLED', 6.7, 256, 12, 50, 4700, 'Android', '5G'),
(28, 'AMOLED', 6.7, 256, 8, 12, 3700, 'Android', '5G'),
(29, 'OLED', 5.4, 64, 4, 12, 2438, 'iOS', '5G'),
(30, 'OLED', 6.5, 256, 8, 50, 4400, 'Android', '5G'),
(31, 'AMOLED', 5.9, 128, 8, 50, 4300, 'Android', '5G'),
(32, 'AMOLED', 6.7, 256, 16, 50, 4600, 'Android', '5G'),
(33, 'LCD', 4.7, 64, 4, 12, 2018, 'iOS', '5G'),
(34, 'OLED', 6.8, 512, 12, 50, 5100, 'Android', '5G'),
(35, 'AMOLED', 6.5, 256, 8, 50, 4800, 'Android', '5G');

-- 4. Tabletak
CREATE TABLE IF NOT EXISTS tabletak (
    id_produktua INT UNSIGNED PRIMARY KEY,
    pantaila_hazbeteak DECIMAL(4,1),
    biltegiratzea_gb INT,
    konektibitatea ENUM('WiFi', 'WiFi + Cellular'),
    sistema_eragilea VARCHAR(50),
    bateria_mah INT,
    arkatzarekin_bateragarria BOOLEAN DEFAULT FALSE,
    CONSTRAINT fk_tableta_produktua FOREIGN KEY (id_produktua) REFERENCES produktuak(id_produktua) ON DELETE CASCADE
);

INSERT INTO tabletak (id_produktua, pantaila_hazbeteak, biltegiratzea_gb, konektibitatea, sistema_eragilea, bateria_mah, arkatzarekin_bateragarria) VALUES
(36, 12.9, 256, 'WiFi + Cellular', 'iPadOS', 10758, TRUE),
(37, 11.0, 128, 'WiFi', 'Android', 8400, TRUE),
(38, 10.9, 64, 'WiFi', 'iPadOS', 7600, TRUE),
(39, 13.0, 256, 'WiFi', 'Windows 11', 6500, TRUE),
(40, 11.2, 128, 'WiFi', 'Android', 8000, TRUE),
(41, 11.0, 128, 'WiFi', 'Android', 8840, TRUE),
(42, 8.3, 64, 'WiFi + Cellular', 'iPadOS', 5124, TRUE),
(43, 11.0, 64, 'WiFi', 'FireOS', 7500, FALSE),
(44, 12.6, 256, 'WiFi', 'HarmonyOS', 10050, TRUE),
(45, 10.9, 128, 'WiFi', 'Android', 7020, TRUE);

-- 5. Zerbitzariak
CREATE TABLE IF NOT EXISTS zerbitzariak (
    id_produktua INT UNSIGNED PRIMARY KEY,
    prozesadore_nukleoak INT,
    ram_mota ENUM('DDR4', 'DDR5', 'ECC'),
    disko_badiak INT,
    rack_unitateak INT,
    elikatze_iturri_erredundantea BOOLEAN DEFAULT TRUE,
    raid_kontroladora VARCHAR(50),
    CONSTRAINT fk_zerbitzaria_produktua FOREIGN KEY (id_produktua) REFERENCES produktuak(id_produktua) ON DELETE CASCADE
);

INSERT INTO zerbitzariak (id_produktua, prozesadore_nukleoak, ram_mota, disko_badiak, rack_unitateak, elikatze_iturri_erredundantea, raid_kontroladora) VALUES
(46, 24, 'DDR5', 12, 2, TRUE, 'PERC H750'),
(47, 20, 'DDR4', 8, 2, TRUE, 'HPE Smart Array'),
(48, 16, 'DDR4', 16, 2, TRUE, 'ThinkSystem RAID'),
(49, 4, 'DDR4', 12, 2, TRUE, 'Software RAID'),
(50, 12, 'DDR4', 8, 1, TRUE, 'Cisco 12G SAS');

-- 6. Pantailak
CREATE TABLE IF NOT EXISTS pantailak (
    id_produktua INT UNSIGNED PRIMARY KEY,
    hazbeteak DECIMAL(4,1),
    bereizmena VARCHAR(20),
    panel_mota ENUM('IPS', 'VA', 'TN', 'OLED'),
    freskatze_tasa_hz INT,
    konexioak VARCHAR(150),
    kurbatura VARCHAR(10),
    CONSTRAINT fk_pantaila_produktua FOREIGN KEY (id_produktua) REFERENCES produktuak(id_produktua) ON DELETE CASCADE
);

INSERT INTO pantailak (id_produktua, hazbeteak, bereizmena, panel_mota, freskatze_tasa_hz, konexioak, kurbatura) VALUES
(51, 27.0, '2560x1440', 'IPS', 144, 'HDMI, DP', 'Flat'),
(52, 32.0, '3840x2160', 'IPS', 60, 'HDMI, DP, USB-C', 'Flat'),
(53, 49.0, '5120x1440', 'VA', 240, 'HDMI, DP', '1000R'),
(54, 27.0, '3840x2160', 'IPS', 60, 'HDMI, DP', 'Flat'),
(55, 27.0, '2560x1440', 'IPS', 75, 'HDMI, DP, USB-C', 'Flat'),
(56, 23.8, '1920x1080', 'IPS', 75, 'HDMI, VGA', 'Flat'),
(57, 24.0, '1920x1080', 'VA', 165, 'HDMI, DP', '1500R'),
(58, 27.0, '5120x2880', 'IPS', 60, 'Thunderbolt 3', 'Flat'),
(59, 34.0, '3440x1440', 'VA', 100, 'HDMI, DP', '1500R'),
(60, 24.0, '1920x1080', 'IPS', 144, 'HDMI, DP', 'Flat'),
(61, 32.0, '2560x1440', 'IPS', 175, 'HDMI, DP', 'Flat'),
(62, 27.0, '2560x1440', 'IPS', 60, 'USB-C, HDMI', 'Flat'),
(63, 27.0, '2560x1440', 'IPS', 170, 'HDMI, DP, USB-C', 'Flat'),
(64, 27.0, '2560x1440', 'IPS', 60, 'HDMI, DP, DVI', 'Flat'),
(65, 28.2, '3840x2560', 'IPS', 60, 'MiniDP, HDMI', 'Flat');

-- 7. Softwareak
CREATE TABLE IF NOT EXISTS softwareak (
    id_produktua INT UNSIGNED PRIMARY KEY,
    software_mota ENUM('Sistema Eragilea', 'Ofimatika', 'Antibirusa', 'Bestelakoak') NOT NULL,
    lizentzia_mota ENUM('OEM', 'Retail', 'Harpidetza', 'OpenSource', 'GPL', 'LGPL') DEFAULT 'Retail',
    bertsioa VARCHAR(50),
    garatzailea VARCHAR(100),
    librea BOOLEAN DEFAULT FALSE,

    CONSTRAINT fk_softwarea_produktua FOREIGN KEY (id_produktua) REFERENCES produktuak(id_produktua) ON DELETE CASCADE
);

INSERT INTO softwareak (id_produktua, software_mota, lizentzia_mota, bertsioa, garatzailea, librea) VALUES
(66, 'Sistema Eragilea', 'Retail', '11 Pro', 'Microsoft', FALSE),
(67, 'Ofimatika', 'Retail', '2021', 'Microsoft', FALSE),
(68, 'Bestelakoak', 'Harpidetza', 'CC 2024', 'Adobe', FALSE),
(69, 'Antibirusa', 'Retail', '2024', 'Kaspersky', FALSE),
(70, 'Sistema Eragilea', 'OEM', 'Standard 2022', 'Microsoft', FALSE),
(71, 'Bestelakoak', 'Harpidetza', '1 Urte', 'NordSec', FALSE),
(72, 'Bestelakoak', 'Harpidetza', '2024', 'Autodesk', FALSE),
(73, 'Antibirusa', 'Retail', 'Deluxe', 'Norton', FALSE),
(74, 'Sistema Eragilea', 'Harpidetza', 'RHEL 9', 'Red Hat', FALSE),
(75, 'Bestelakoak', 'Retail', 'Standard 8', 'VMware', FALSE),
(76, 'Sistema Eragilea', 'GPL', '24.04 LTS', 'Canonical', TRUE),
(77, 'Ofimatika', 'LGPL', '7.6', 'Document Foundation', TRUE),
(78, 'Bestelakoak', 'GPL', '4.0', 'Blender Foundation', TRUE),
(79, 'Bestelakoak', 'GPL', '2.10', 'GIMP Team', TRUE),
(80, 'Bestelakoak', 'GPL', '3.0', 'VideoLAN', TRUE);
