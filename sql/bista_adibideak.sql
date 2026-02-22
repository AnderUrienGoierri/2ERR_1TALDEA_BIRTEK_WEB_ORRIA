-- ========================================================
-- BISTA ADIBIDEAK (VIEW adibideak)
-- ========================================================

-- 1. BISTA: PRODUKTUEN XEHETASUN OSOAK
-- Produktuen informazio nagusia erakusten du, erlazionatutako taulen izenekin (Kategoria, Hornitzailea, Biltegia).
CREATE OR REPLACE VIEW bista_produktu_xehetasunak AS
SELECT p.id_produktua,
    p.izena AS produktu_izena,
    p.marka,
    p.mota,
    k.izena AS kategoria,
    h.izena_soziala AS hornitzailea,
    b.izena AS biltegia,
    p.salmenta_prezioa,
    p.stock,
    p.produktu_egoera
FROM produktuak p
    JOIN produktu_kategoriak k ON p.kategoria_id = k.id_kategoria
    JOIN hornitzaileak h ON p.hornitzaile_id = h.id_hornitzailea
    JOIN biltegiak b ON p.biltegi_id = b.id_biltegia;

-- 2. BISTA: BEZEROEN ESKAERAK ETA FAKTURA ZENBATEKOAK
-- Bezero bakoitzaren eskaerak zerrendatzen ditu, guztizko prezioarekin eta egoerarekin.
CREATE OR REPLACE VIEW bista_bezero_eskaerak AS
SELECT e.id_eskaera,
    e.faktura_zenbakia,
    e.data AS eskaera_data,
    CONCAT(
        bez.izena_edo_soziala,
        ' ',
        IFNULL(bez.abizena, '')
    ) AS bezeroa,
    e.guztira_prezioa,
    e.eskaera_egoera,
    CONCAT(l.izena, ' ', l.abizena) AS kudeatzailea
FROM eskaerak e
    JOIN bezeroak bez ON e.bezeroa_id = bez.id_bezeroa
    LEFT JOIN langileak l ON e.langilea_id = l.id_langilea;

-- 3. BISTA: LANGILEEN FITXAKETAK
-- Langileen sarrera eta irteera orduak erakusten ditu, zein sailetan lan egiten duten adieraziz.
CREATE OR REPLACE VIEW bista_langile_fitxaketak AS
SELECT f.id_fitxaketa,
    CONCAT(l.izena, ' ', l.abizena) AS langilea,
    s.izena AS saila,
    f.data,
    f.ordua,
    f.mota
FROM fitxaketak f
    JOIN langileak l ON f.langilea_id = l.id_langilea
    JOIN langile_sailak s ON l.saila_id = s.id_saila
ORDER BY f.data DESC,
    f.ordua DESC;

-- 4. BISTA: KOMPONKETEN EGOERA
-- Tailerrean dauden konponketen egoera, zein produkturi dagokion, nor den arduraduna eta zein den akatsa.
CREATE OR REPLACE VIEW bista_konponketa_egoera AS
SELECT k.id_konponketa,
    p.izena AS produktua,
    a.izena AS akatsa,
    k.konponketa_egoera,
    k.hasiera_data,
    CONCAT(l.izena, ' ', l.abizena) AS teknikaria,
    k.oharrak
FROM konponketak k
    JOIN produktuak p ON k.produktua_id = p.id_produktua
    JOIN akatsak a ON k.akatsa_id = a.id_akatsa
    JOIN langileak l ON k.langilea_id = l.id_langilea;

-- 5. BISTA: STOCK BALORAZIOA KATEGORIAKA
-- Kategoria bakoitzean zenbat stock dagoen eta bere balio totala (Prezioa * Stock) kalkulatzen du.
CREATE OR REPLACE VIEW bista_stock_balorazioa AS
SELECT k.izena AS kategoria,
    COUNT(p.id_produktua) AS produktu_kopurua,
    SUM(p.stock) AS ale_guztira,
    SUM(p.stock * p.salmenta_prezioa) AS stock_balio_totala
FROM produktuak p
    JOIN produktu_kategoriak k ON p.kategoria_id = k.id_kategoria
GROUP BY k.izena;