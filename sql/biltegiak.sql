CREATE TABLE IF NOT EXISTS biltegiak (
    id_biltegia INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    izena VARCHAR(50) NOT NULL,
    biltegi_sku VARCHAR(50) UNIQUE NOT NULL
);

INSERT INTO biltegiak (id_biltegia, izena, biltegi_sku) VALUES 
(1, 'Harrera Biltegia', 'HAR_BIL'),
(2, 'Biltegi Nagusia', 'BIL_NAG'),
(3, 'Irteera Biltegia', 'IRT_BIL');
