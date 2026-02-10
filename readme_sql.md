# SQL Kontsulta Aurreratuak

Dokumentu honek **BIRTEK** datu-basearekin erabilitako edo erabilgarriak diren SQL kontsulta aurreratuen adibideak biltzen ditu. Bertan `JOIN`, `GROUP BY` eta `HAVING` klausulen erabilerak azaltzen dira.

## 1. JOIN (Taulen Arteko Loturak)

Aplikazioan hainbat tokitan erabiltzen ditugu `JOIN`ak datuak taula ezberdinetatik erlazionatzeko.

### Adibidea: Eskaera Lerroak eta Produktuak (`php/bezero_eskaerak.php`)

Bezeroaren eskaerak bistaratzean, eskaera lerro bakoitzeko produktuaren izena eta deskribapena lortzeko `INNER JOIN` bat erabiltzen da `eskaera_lerroak` eta `produktuak` taulen artean.

```sql
SELECT el.*, p.izena, p.deskribapena
FROM eskaera_lerroak el
JOIN produktuak p ON el.produktua_id = p.id_produktua
WHERE el.eskaera_id = :id
```

### Adibidea: Sarrerak eta Hornitzaileak (`php/hornitzaile_sarrerak_kudeatu.php`)

Hornitzaileek egindako sarrerak kudeatzeko, hiru taula lotzen dira: `sarrerak`, `sarrera_lerroak`, eta `produktuak`.

```sql
SELECT
    s.id_sarrera, s.data, s.sarrera_egoera,
    sl.id_sarrera_lerroa, sl.kantitatea, sl.sarrera_lerro_egoera,
    p.id_produktua, p.izena as produktu_izena, p.marka
FROM sarrerak s
JOIN sarrera_lerroak sl ON s.id_sarrera = sl.sarrera_id
JOIN produktuak p ON sl.produktua_id = p.id_produktua
WHERE s.hornitzailea_id = :hid
ORDER BY s.data DESC
```

### Adibidea: Produktuak eta Kategoriak (`php/produktuak.php`)

Produktuen zerrenda erakustean, kategoriaren izena lortzeko `LEFT JOIN` erabiltzen da. Honek bermatzen du produktua agertzea nahiz eta kategoriarik ez izan (nahiz eta gure kasuan denek izan).

```sql
SELECT p.*, k.izena as produktu_kategoria_izena
FROM produktuak p
LEFT JOIN produktu_kategoriak k ON p.kategoria_id = k.id_kategoria
WHERE p.salgai = 1 AND p.stock > 0
```

---

## 2. GROUP BY (Datuen Taldekatzea)

`GROUP BY` klausula datuak zutabe baten edo gehiagoren arabera taldekatzeko erabiltzen da, askotan funtzio agregatuekin (`COUNT`, `SUM`, `AVG`) batera.

### Adibidea: Produktu kopurua kategoria bakoitzeko

Kontsulta honek produktu kategoria bakoitzean zenbat produktu dauden zenbatzen du.

```sql
SELECT k.izena, COUNT(p.id_produktua) as produktu_kopurua
FROM produktu_kategoriak k
LEFT JOIN produktuak p ON k.id_kategoria = p.kategoria_id
GROUP BY k.id_kategoria, k.izena;
```

### Adibidea: Eskaeren guztizkoa bezero bakoitzeko

Bezero bakoitzak guztira zenbat diru gastatu duen jakiteko.

```sql
SELECT bezeroa_id, SUM(guztira_prezioa) as gastu_totala
FROM eskaerak
WHERE eskaera_egoera != 'Ezabatua'
GROUP BY bezeroa_id;
```

---

## 3. HAVING (Taldekatutako Datuen Iragazketa)

`HAVING` klausula `GROUP BY`-rekin sortutako taldeak iragazteko erabiltzen da (WHERE klausulak ez du funtzionatzen funtzio agregatuekin).

### Adibidea: 5 produktu baino gehiago dituzten kategoriak

Kategoriaren barruan 5 produktu baino gehiago dituzten kategoriak bakarrik erakusteko.

```sql
SELECT k.izena, COUNT(p.id_produktua) as produktu_kopurua
FROM produktu_kategoriak k
JOIN produktuak p ON k.id_kategoria = p.kategoria_id
GROUP BY k.id_kategoria, k.izena
HAVING COUNT(p.id_produktua) > 5;
```

### Adibidea: Batez beste 50€ baino gehiagoko eskaerak dituzten bezeroak

Webguneko bezero "onak" identifikatzeko, batez besteko eskaera altua dutenak.

```sql
SELECT bezeroa_id, AVG(guztira_prezioa) as batez_besteko_gastua
FROM eskaerak
WHERE eskaera_egoera = 'Osatua'
GROUP BY bezeroa_id
HAVING AVG(guztira_prezioa) > 50;
```
