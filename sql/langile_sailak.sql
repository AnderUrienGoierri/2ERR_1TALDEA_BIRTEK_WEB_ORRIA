CREATE TABLE IF NOT EXISTS langile_sailak (
    id_saila INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    izena VARCHAR(100) NOT NULL,
    kokapena VARCHAR(100) NOT NULL,
    deskribapena TEXT
);

INSERT INTO langile_sailak (id_saila, izena, kokapena, deskribapena) VALUES
(1, 'Zuzendaritza','Goi bulegoa', 'DIR'),
(2, 'Administrazioa','Harrera bulegoa', 'ADMIN'),
(3, 'Salmentak','Harrera', 'COM'),
(4, 'Zerbitzu Teknikoa','Tailerra', 'SAT'),
(5, 'Logistika eta Biltegia','Biltegiak', 'LOG');
