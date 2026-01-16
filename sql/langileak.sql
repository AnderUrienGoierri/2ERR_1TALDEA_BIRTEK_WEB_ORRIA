CREATE TABLE IF NOT EXISTS langileak (
    id_langilea INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    izena VARCHAR(50) NOT NULL,
    abizena VARCHAR(100) NOT NULL,
    nan VARCHAR(9) UNIQUE NOT NULL,
    jaiotza_data DATE NOT NULL,
    
    -- Kokapena
    herria_id INT UNSIGNED NOT NULL,
    helbidea VARCHAR(150) NOT NULL,
    posta_kodea VARCHAR(5) NOT NULL,
    telefonoa VARCHAR(20) NOT NULL,
    
    -- Login datuak eta Hizkuntza
    emaila VARCHAR(100) UNIQUE NOT NULL,
    hizkuntza ENUM('Euskara', 'Gaztelania', 'Frantsesa', 'Ingelesa') NOT NULL DEFAULT 'Euskara',
    pasahitza VARCHAR(255) NOT NULL,
    salto_txartela_uid VARCHAR(50) UNIQUE, -- Langilea identifikatzeko
    
    -- Lan datuak
    alta_data TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    eguneratze_data DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    aktibo BOOLEAN NOT NULL DEFAULT 0,
    
    saila_id INT UNSIGNED NOT NULL,
    iban VARCHAR(34) NOT NULL UNIQUE,
    
    CONSTRAINT fk_langilea_saila FOREIGN KEY (saila_id) REFERENCES langile_sailak(id_saila),
    CONSTRAINT fk_langilea_herria FOREIGN KEY (herria_id) REFERENCES herriak(id_herria)
);

INSERT INTO langileak (id_langilea, izena, abizena, nan, jaiotza_data, herria_id, helbidea, posta_kodea, telefonoa, emaila, pasahitza, salto_txartela_uid, saila_id, iban, alta_data, aktibo) VALUES
(1, 'Ander', 'Urien', '72484472H', '1992-03-02', 1, 'Askatasun Hiribidea 5', '20004', '600111222', 'ander@birtek.eus', '1234', 'UID_ADMIN_01', 1, 'ES1234567890123456789001', '2020-01-01', 1),
(2, 'Lander', 'Garmendia', '12345678Z', '2000-03-02', 1, 'Askatasun Hiribidea 5', '20004', '600111222', 'lander@birtek.eus', '1234', 'UID_ADMIN_02', 1, 'ES1234567890123456789002', '2020-01-01', 1),
(3, 'Ane', 'Lasa', '22222222B', '1985-03-20', 1, 'Easo Kalea 12', '20006', '600333444', 'ane.lasa@birtek.eus', '1234', 'UID_ADMIN_03', 2, 'ES1234567890123456789003', '2020-02-15', 1),
(4, 'Mikel', 'Otegi', '33333333C', '1990-11-10', 6, 'Nafarroa Kalea 3', '20800', '600555666', 'mikel.otegi@birtek.eus', '1234', 'UID_ADMIN_04', 2, 'ES1234567890123456789004', '2021-05-20', 1),
(5, 'Leire', 'Mendizabal', '44444444D', '1992-07-08', 2, 'Gran Via 25', '48001', '600777888', 'leire.mendi@birtek.eus', '1234', 'UID_COM_01', 3, 'ES1234567890123456789005', '2021-06-01', 1),
(6, 'Iker', 'Iriondo', '55555555E', '1995-01-30', 2, 'Licenciado Poza 10', '48011', '600999000', 'iker.iriondo@birtek.eus', '1234', 'UID_COM_02', 3, 'ES1234567890123456789006', '2022-03-10', 1),
(7, 'Amaia', 'Goikoetxea', '66666666F', '1988-09-12', 5, 'Isasi Kalea 4', '20600', '600123123', 'amaia.goiko@birtek.eus', '1234', 'UID_COM_03', 3, 'ES1234567890123456789007', '2019-11-05', 1),
(8, 'Unai', 'Zabala', '77777777G', '1993-04-25', 1, 'Tolosa Hiribidea 45', '20018', '600456456', 'unai.zabala@birtek.eus', '1234', 'UID_SAT_01', 4,'ES1234567890123456789008', '2020-09-15', 1),
(9, 'Maite', 'Arregi', '88888888H', '1996-12-05', 3, 'Dato Kalea 15', '01005', '600789789', 'maite.arregi@birtek.eus', '1234', 'UID_SAT_02', 4, 'ES1234567890123456789009', '2023-01-10', 1),
(10, 'Aitor', 'Bilbao', '99999999I', '1991-08-18', 2, 'Indautxu Plaza 2', '48010', '600321321', 'aitor.bilbao@birtek.eus', '1234', 'UID_SAT_03', 4, 'ES1234567890123456789010', '2021-02-28', 1),
(11, 'Nerea', 'Etxaniz', '12345678J', '1994-06-14', 6, 'Malekoia 8', '20800', '600654654', 'nerea.etxaniz@birtek.eus', '1234', 'UID_SAT_04', 4,'ES1234567890123456789011', '2022-07-20', 1),
(12, 'Gorka', 'Ugarte', '87654321K', '1987-02-22', 3, 'Gamarra Atea 4', '01013', '600987987', 'gorka.ugarte@birtek.eus', '1234', 'UID_LOG_01', 5, 'ES1234567890123456789012', '2018-05-12', 1),
(13, 'Oihane', 'Ibarra', '23456789L', '1998-10-30', 3, 'Frantzia Kalea 20', '01002', '600147258', 'oihane.ibarra@birtek.eus', '1234', 'UID_LOG_02', 5, 'ES1234567890123456789013', '2023-06-15', 1),
(14, 'Xabier', 'Larrea', '34567890M', '1990-03-15', 1, 'Intxaurrondo 50', '20015', '600258369', 'xabier.larrea@birtek.eus', '1234', 'UID_LOG_03', 5, 'ES1234567890123456789014', '2020-11-30', 1);
