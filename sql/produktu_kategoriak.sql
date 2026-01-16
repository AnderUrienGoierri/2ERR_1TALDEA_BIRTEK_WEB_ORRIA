CREATE TABLE IF NOT EXISTS produktu_kategoriak (
    id_kategoria INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    izena VARCHAR(50) UNIQUE NOT NULL
);

INSERT INTO produktu_kategoriak (id_kategoria, izena) VALUES 
(1, 'Ordenagailuak'),
(2, 'Telefonia'),
(3, 'Irudia'),
(4, 'Osagarriak'),
(5, 'Softwarea'),
(6, 'Sareak eta Zerbitzariak');
