CREATE TABLE IF NOT EXISTS bezeroak (
    id_bezeroa INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    izena_edo_soziala VARCHAR(100) NOT NULL,
    abizena VARCHAR(100),
    ifz_nan VARCHAR(9) UNIQUE NOT NULL,
    jaiotza_data DATE,
    sexua ENUM('gizona', 'emakumea', 'ez-binarioa'),
    
    -- Ordaintzeko
    bezero_ordainketa_txartela VARCHAR(255),
    
    -- Bidalketarako
    helbidea VARCHAR(150) NOT NULL,
    herria_id INT UNSIGNED NOT NULL,
    posta_kodea VARCHAR(5) NOT NULL,
    telefonoa VARCHAR(15) NOT NULL,
    
    -- Login eta Hizkuntza
    emaila VARCHAR(255) UNIQUE NOT NULL,
    hizkuntza ENUM('Euskara', 'Gaztelania', 'Frantsesa', 'Ingelesa') NOT NULL DEFAULT 'Euskara',
    pasahitza VARCHAR(255) NOT NULL,
    
    alta_data DATETIME DEFAULT CURRENT_TIMESTAMP,
    eguneratze_data DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    aktibo BOOLEAN NOT NULL DEFAULT 1,
    
    CONSTRAINT fk_bezeroa_herria FOREIGN KEY (herria_id) REFERENCES herriak(id_herria)
);

INSERT INTO bezeroak (izena_edo_soziala, abizena, ifz_nan, jaiotza_data, sexua, bezero_ordainketa_txartela, helbidea, herria_id, posta_kodea, telefonoa, emaila, hizkuntza, pasahitza, aktibo) VALUES 
('Ane', 'Goikoetxea Lasa', '12345678A', '1990-05-15', 'emakumea', 'tok_visa_42424242', 'Askatasunaren Etorbidea 14, 2.B', 1, '20004', '600123456', 'ane.goiko@email.eus', 'Euskara', 'pasahitzaSegurua1', 1),
('Jon', 'Perez Garcia', '87654321B', '1985-11-20', 'gizona', 'tok_mastercard_5555', 'Kale Nagusia 30', 2, '48001', '611222333', 'jon.perez@gmail.com', 'Gaztelania', '123456Jon', 1),
('Teknologia Berriak SL', NULL, 'B99887766', NULL, NULL, 'tok_amex_9090', 'Jundiz Industrialdea, Pab 5', 3, '01015', '945111222', 'info@teknologiaberriak.com', 'Euskara', 'admin2024', 1),
('Alex', 'Etxebarria', '44556677C', '2002-02-10', 'ez-binarioa', NULL, 'Estafeta Kalea 12', 4, '31001', '666777888', 'alex.etxe@protonmail.com', 'Ingelesa', 'alex_pass_secure', 1),
('Sarah', 'Dubois', 'X1234567Z', '1995-07-30', 'emakumea', 'tok_cb_3333', 'Rue du Port 5', 5, '64200', '+33612345678', 'sarah.dubois@orange.fr', 'Frantsesa', 'monmotdepasse', 0);
