CREATE TABLE IF NOT EXISTS bezero_fakturak (
    id_faktura INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    faktura_zenbakia VARCHAR(20) UNIQUE NOT NULL,
    eskaera_id INT UNSIGNED NOT NULL,
    data DATE NOT NULL,
    eguneratze_data DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_faktura_eskaera FOREIGN KEY (eskaera_id) REFERENCES eskaerak(id_eskaera)
);

INSERT INTO bezero_fakturak (id_faktura, faktura_zenbakia, eskaera_id, data) VALUES
(1, 'FAK-2024-001', 1, '2024-01-10'),
(2, 'FAK-2024-002', 2, '2024-01-11'),
(3, 'FAK-2024-003', 4, '2024-01-12'),
(4, 'FAK-2024-004', 6, '2024-01-15'),
(5, 'FAK-2024-005', 8, '2024-01-20'),
(6, 'FAK-2024-006', 9, '2024-01-25'),
(7, 'FAK-2024-007', 10, '2024-02-01'),
(8, 'FAK-2024-008', 12, '2024-02-05'),
(9, 'FAK-2024-009', 13, '2024-02-10'),
(10, 'FAK-2024-010', 15, '2024-02-15'),
(11, 'FAK-2024-011', 16, '2024-02-20'),
(12, 'FAK-2024-012', 18, '2024-03-01'),
(13, 'FAK-2024-013', 19, '2024-03-05'),
(14, 'FAK-2024-014', 21, '2024-03-10'),
(15, 'FAK-2024-015', 23, '2024-03-15'),
(16, 'FAK-2024-016', 24, '2024-03-20'),
(17, 'FAK-2024-017', 26, '2024-04-01'),
(18, 'FAK-2024-018', 27, '2024-04-05'),
(19, 'FAK-2024-019', 29, '2024-04-10'),
(20, 'FAK-2024-020', 30, '2024-04-15');
