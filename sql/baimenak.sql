-- ========================================================
-- 8. ERABILTZAILEAK ETA BAIMENAK
-- ========================================================

FLUSH PRIVILEGES;

-- ZUZENDARITZA (SysAdmin)
CREATE USER IF NOT EXISTS 'ander_sysadmin'@'localhost' IDENTIFIED BY '1234';
GRANT ALL PRIVILEGES ON *.* TO 'ander_sysadmin'@'localhost' WITH GRANT OPTION;

CREATE USER IF NOT EXISTS 'lander_sysadmin'@'localhost' IDENTIFIED BY '1234';
GRANT ALL PRIVILEGES ON *.* TO 'lander_sysadmin'@'localhost' WITH GRANT OPTION;

-- ADMINISTRAZIOA
CREATE USER IF NOT EXISTS 'ane_admin'@'localhost' IDENTIFIED BY '1234';
CREATE USER IF NOT EXISTS 'mikel_admin'@'localhost' IDENTIFIED BY '1234';

GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.langileak TO 'ane_admin'@'localhost', 'mikel_admin'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.langile_sailak TO 'ane_admin'@'localhost', 'mikel_admin'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.fitxaketak TO 'ane_admin'@'localhost', 'mikel_admin'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.bezero_fakturak TO 'ane_admin'@'localhost', 'mikel_admin'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.hornitzaileak TO 'ane_admin'@'localhost', 'mikel_admin'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.herriak TO 'ane_admin'@'localhost', 'mikel_admin'@'localhost';

-- SALMENTAK
CREATE USER IF NOT EXISTS 'leire_sales'@'localhost' IDENTIFIED BY '1234';
CREATE USER IF NOT EXISTS 'iker_sales'@'localhost' IDENTIFIED BY '1234';
CREATE USER IF NOT EXISTS 'amaia_sales'@'localhost' IDENTIFIED BY '1234';

GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.bezeroak TO 'leire_sales'@'localhost', 'iker_sales'@'localhost', 'amaia_sales'@'localhost';
GRANT SELECT, UPDATE ON birtek_db.produktuak TO 'leire_sales'@'localhost', 'iker_sales'@'localhost', 'amaia_sales'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.eskaerak TO 'leire_sales'@'localhost', 'iker_sales'@'localhost', 'amaia_sales'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.eskaera_lerroak TO 'leire_sales'@'localhost', 'iker_sales'@'localhost', 'amaia_sales'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.bezero_fakturak TO 'leire_sales'@'localhost', 'iker_sales'@'localhost', 'amaia_sales'@'localhost';
GRANT SELECT, INSERT ON birtek_db.herriak TO 'leire_sales'@'localhost', 'iker_sales'@'localhost', 'amaia_sales'@'localhost';

-- SAT
CREATE USER IF NOT EXISTS 'unai_sat'@'localhost' IDENTIFIED BY '1234';
CREATE USER IF NOT EXISTS 'maite_sat'@'localhost' IDENTIFIED BY '1234';
CREATE USER IF NOT EXISTS 'aitor_sat'@'localhost' IDENTIFIED BY '1234';
CREATE USER IF NOT EXISTS 'nerea_sat'@'localhost' IDENTIFIED BY '1234';

GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.produktuak TO 'unai_sat'@'localhost', 'maite_sat'@'localhost', 'aitor_sat'@'localhost', 'nerea_sat'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.konponketak TO 'unai_sat'@'localhost', 'maite_sat'@'localhost', 'aitor_sat'@'localhost', 'nerea_sat'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.akatsak TO 'unai_sat'@'localhost', 'maite_sat'@'localhost', 'aitor_sat'@'localhost', 'nerea_sat'@'localhost';
GRANT SELECT, INSERT ON birtek_db.herriak TO 'unai_sat'@'localhost', 'maite_sat'@'localhost', 'aitor_sat'@'localhost', 'nerea_sat'@'localhost';

-- LOGISTIKA
CREATE USER IF NOT EXISTS 'gorka_biltegia'@'localhost' IDENTIFIED BY '1234';
CREATE USER IF NOT EXISTS 'oihane_biltegia'@'localhost' IDENTIFIED BY '1234';
CREATE USER IF NOT EXISTS 'xabier_biltegia'@'localhost' IDENTIFIED BY '1234';

GRANT SELECT, INSERT, UPDATE ON birtek_db.produktuak TO 'gorka_biltegia'@'localhost', 'oihane_biltegia'@'localhost', 'xabier_biltegia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.biltegiak TO 'gorka_biltegia'@'localhost', 'oihane_biltegia'@'localhost', 'xabier_biltegia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.sarrera_lerroak TO 'gorka_biltegia'@'localhost', 'oihane_biltegia'@'localhost', 'xabier_biltegia'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON birtek_db.sarrerak TO 'gorka_biltegia'@'localhost', 'oihane_biltegia'@'localhost', 'xabier_biltegia'@'localhost';
GRANT SELECT, UPDATE ON birtek_db.eskaera_lerroak TO 'gorka_biltegia'@'localhost', 'oihane_biltegia'@'localhost', 'xabier_biltegia'@'localhost';
GRANT SELECT, INSERT ON birtek_db.herriak TO 'gorka_biltegia'@'localhost', 'oihane_biltegia'@'localhost', 'xabier_biltegia'@'localhost';

-- FITXAKETAK (Langile guztieenak)
GRANT SELECT, INSERT ON birtek_db.fitxaketak TO 
    'leire_sales'@'localhost', 
    'iker_sales'@'localhost', 
    'amaia_sales'@'localhost', 
    'unai_sat'@'localhost', 
    'maite_sat'@'localhost', 
    'aitor_sat'@'localhost', 
    'nerea_sat'@'localhost', 
    'gorka_biltegia'@'localhost', 
    'oihane_biltegia'@'localhost', 
    'xabier_biltegia'@'localhost';

FLUSH PRIVILEGES;
