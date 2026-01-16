CREATE TABLE IF NOT EXISTS hornitzaileak (
    id_hornitzailea INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    izena_soziala VARCHAR(100) NOT NULL,
    ifz_nan VARCHAR(9) UNIQUE NOT NULL,
    
    -- Kontaktu datuak
    kontaktu_pertsona VARCHAR(100),
    helbidea VARCHAR(150) NOT NULL,
    herria_id INT UNSIGNED NOT NULL,
    posta_kodea VARCHAR(5) NOT NULL,
    telefonoa VARCHAR(15) NOT NULL,
    
    -- Login
    emaila VARCHAR(255) UNIQUE NOT NULL,
    hizkuntza ENUM('Euskara', 'Gaztelania', 'Frantsesa', 'Ingelesa') NOT NULL DEFAULT 'Gaztelania',
    pasahitza VARCHAR(255) NOT NULL,
    aktibo BOOLEAN NOT NULL DEFAULT 1,
    
    eguneratze_data DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_hornitzailea_herria FOREIGN KEY (herria_id) REFERENCES herriak(id_herria)
);

INSERT INTO hornitzaileak (id_hornitzailea, izena_soziala, ifz_nan, kontaktu_pertsona, helbidea, herria_id, posta_kodea, telefonoa, emaila, pasahitza) VALUES 
(1, 'PC Componentes Pro', 'A88776655', 'Soporte B2B', 'Poligono Industrial Alhama', 6, '28001', '910000000', 'b2b@pccomponentes.com', 'hash_pcc'),
(2, 'Ingram Micro', 'A11223344', 'Carlos Distribución', 'Calle Tecnología 5', 6, '28002', '910111222', 'pedidos@ingram.com', 'hash_ingram'),
(3, 'Amazon Business', 'W8888888', 'Logistika Zentroa', 'Trapagaran Poligonoa', 1, '48510', '900800700', 'business@amazon.es', 'hash_amazon');
