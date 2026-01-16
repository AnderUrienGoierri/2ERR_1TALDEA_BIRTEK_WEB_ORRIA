CREATE TABLE IF NOT EXISTS herriak (
    id_herria INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    izena VARCHAR(100) NOT NULL,
    lurraldea VARCHAR(100) NOT NULL,
    nazioa VARCHAR(100) NOT NULL
);

INSERT INTO herriak (id_herria, izena, lurraldea, nazioa) VALUES
(1, 'Donostia', 'Gipuzkoa', 'Euskal Herria'),
(2, 'Bilbo', 'Bizkaia', 'Euskal Herria'),
(3, 'Gasteiz', 'Araba', 'Euskal Herria'),
(4, 'Iruña', 'Nafarroa', 'Euskal Herria'),
(5, 'Eibar', 'Gipuzkoa', 'Euskal Herria'),
(6, 'Zarautz', 'Gipuzkoa', 'Euskal Herria');
