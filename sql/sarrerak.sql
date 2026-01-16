CREATE TABLE IF NOT EXISTS sarrerak (
    id_sarrera INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    hornitzailea_id INT UNSIGNED NOT NULL,
    data TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    albarana VARCHAR(50),
    oharrak TEXT,
    
    CONSTRAINT fk_sarrera_hornitzailea FOREIGN KEY (hornitzailea_id) REFERENCES hornitzaileak(id_hornitzailea)
);

INSERT INTO sarrerak (id_sarrera, hornitzailea_id, albarana, oharrak) VALUES
(1, 1, 'ALB-PC-001', 'Lehenengo bidalketa'),
(2, 2, 'ALB-IM-002', 'Eramangarri berriak'),
(3, 3, 'AMZ-123456', 'Logistika proba');

CREATE TABLE IF NOT EXISTS sarrera_lerroak (
    id_sarrera_lerroa INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sarrera_id INT UNSIGNED NOT NULL,
    produktua_id INT UNSIGNED NOT NULL,
    kantitatea INT UNSIGNED NOT NULL,
    prezio_unitarioa DECIMAL(10, 2) NOT NULL,
    
    CONSTRAINT fk_sarrera_lerroa_sarrera FOREIGN KEY (sarrera_id) REFERENCES sarrerak(id_sarrera),
    CONSTRAINT fk_sarrera_lerroa_produktua FOREIGN KEY (produktua_id) REFERENCES produktuak(id_produktua)
);

INSERT INTO sarrera_lerroak (sarrera_id, produktua_id, kantitatea, prezio_unitarioa) VALUES
(1, 1, 5, 1800.00),
(1, 11, 2, 2000.00),
(2, 3, 10, 1500.00),
(3, 21, 20, 1000.00);
