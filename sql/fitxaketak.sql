CREATE TABLE IF NOT EXISTS fitxaketak (
    id_fitxaketa INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    langilea_id INT UNSIGNED NOT NULL,
    data DATE NOT NULL DEFAULT (CURRENT_DATE),
    ordua TIME NOT NULL DEFAULT (CURRENT_TIME),
    mota ENUM('Sarrera', 'Irteera') NOT NULL DEFAULT 'Sarrera',
    eguneratze_data DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_fitxaketa_langilea FOREIGN KEY (langilea_id) REFERENCES langileak(id_langilea)
);

INSERT INTO fitxaketak (id_fitxaketa, langilea_id, data, ordua, mota) VALUES
(1, 1, '2024-01-08', '08:00:00', 'Sarrera'), (2, 1, '2024-01-08', '16:00:00', 'Irteera'),
(3, 2, '2024-01-08', '08:05:00', 'Sarrera'), (4, 2, '2024-01-08', '15:00:00', 'Irteera'),
(5, 3, '2024-01-08', '09:00:00', 'Sarrera'), (6, 3, '2024-01-08', '17:00:00', 'Irteera'),
(7, 4, '2024-01-08', '09:10:00', 'Sarrera'), (8, 4, '2024-01-08', '13:00:00', 'Irteera'),
(9, 5, '2024-01-08', '08:55:00', 'Sarrera'), (10, 5, '2024-01-08', '18:00:00', 'Irteera'),
(11, 6, '2024-01-08', '09:00:00', 'Sarrera'), (12, 6, '2024-01-08', '18:00:00', 'Irteera'),
(13, 7, '2024-01-08', '09:05:00', 'Sarrera'), (14, 7, '2024-01-08', '17:30:00', 'Irteera'),
(15, 8, '2024-01-08', '08:00:00', 'Sarrera'), (16, 8, '2024-01-08', '14:00:00', 'Irteera'),
(17, 9, '2024-01-08', '14:00:00', 'Sarrera'), (18, 9, '2024-01-08', '20:00:00', 'Irteera'),
(19, 10,'2024-01-08', '08:00:00', 'Sarrera'), (20, 10, '2024-01-08', '16:00:00', 'Irteera'),
(21, 11,'2024-01-08', '10:00:00', 'Sarrera'), (22, 11, '2024-01-08', '19:00:00', 'Irteera'),
(23, 12,'2024-01-08', '07:00:00', 'Sarrera'), (24, 12, '2024-01-08', '15:00:00', 'Irteera'),
(25, 13,'2024-01-08', '07:30:00', 'Sarrera'), (26, 13, '2024-01-08', '15:30:00', 'Irteera'),
(27, 14,'2024-01-08', '08:00:00', 'Sarrera'), (28, 14, '2024-01-08', '16:00:00', 'Irteera'),
(29, 1, '2024-01-09', '08:00:00', 'Sarrera'), (30, 1, '2024-01-09', '16:00:00', 'Irteera'),
(31, 5, '2024-01-09', '08:50:00', 'Sarrera'), (32, 5, '2024-01-09', '18:10:00', 'Irteera'),
(33, 8, '2024-01-09', '08:02:00', 'Sarrera'), (34, 8, '2024-01-09', '14:05:00', 'Irteera'),
(35, 12,'2024-01-09', '07:05:00', 'Sarrera'), (36, 12, '2024-01-09', '15:00:00', 'Irteera');
