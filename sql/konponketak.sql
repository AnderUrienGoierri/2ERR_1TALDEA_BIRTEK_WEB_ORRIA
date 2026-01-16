CREATE TABLE IF NOT EXISTS akatsak (
    id_akatsa INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    izena VARCHAR(100) NOT NULL,
    deskribapena TEXT,
    konponbide_estandarra TEXT
);

INSERT INTO akatsak (id_akatsa, izena, deskribapena, konponbide_estandarra) VALUES
(1, 'Pantaila apurtua', 'Kristala edo LCDa hautsita dago.', 'Pantaila modulua aldatu.'),
(2, 'Ez da kargatzen', 'Bateria edo kargatze konektorea akastuna.', 'Konektorea garbitu edo aldatu.'),
(3, 'Software errorea', 'Sistema eragilea ez da abiarazten.', 'Sistema berrinstalatu.'),
(4, 'Disko gogorra hautsita', 'Sektore akastunak edo ez du detektatzen.', 'Diskoa aldatu eta datuak migratu.'),
(5, 'Bateria puztua', 'Bateria egoera arriskutsuan dago.', 'Bateria berehala aldatu.');

CREATE TABLE IF NOT EXISTS konponketak (
    id_konponketa INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    produktua_id INT UNSIGNED NOT NULL,
    akatsa_id INT UNSIGNED NOT NULL,
    langilea_id INT UNSIGNED NOT NULL,
    sarrera_data TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    amaiera_data DATETIME,
    konponketa_prezioa DECIMAL(10, 2),
    konponketa_egoera ENUM('Zain', 'Konpontzen', 'Konponduta', 'Ezin da konpondu') DEFAULT 'Zain',
    oharrak TEXT,
    
    CONSTRAINT fk_konponketa_produktua FOREIGN KEY (produktua_id) REFERENCES produktuak(id_produktua),
    CONSTRAINT fk_konponketa_akatsa FOREIGN KEY (akatsa_id) REFERENCES akatsak(id_akatsa),
    CONSTRAINT fk_konponketa_langilea FOREIGN KEY (langilea_id) REFERENCES langileak(id_langilea)
);

INSERT INTO konponketak (produktua_id, akatsa_id, langilea_id, konponketa_prezioa, konponketa_egoera, oharrak) VALUES
(4, 3, 8, 50.00, 'Konponduta', 'Windows berriro instalatu da.'),
(7, 1, 9, 150.00, 'Konpontzen', 'Pantaila berriaren zain.'),
(25, 2, 10, 45.00, 'Konponduta', 'Kargatzailea aldatu da.');
