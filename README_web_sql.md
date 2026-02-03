# BIRTEK - SQL Kontsulta Konplexuak

Dokumentu honek BIRTEK webgunearen garapenean erabilitako SQL kontsulta konplexuak biltzen ditu, datu-basearen egitura profitatuz.

## 1. JOIN (Taulen arteko loturak)

`JOIN` eragiketa funtsezkoa da BIRTEKeko modulu askotan, taula desberdinetako datuak bateratzeko.

### Sarrera Xehetasunak (JOIN hirukoitza)

`hornitzaile_sarrerak_kudeatu.php` orrian erabiltzen da, hornitzaile baten bidalketak, haien lerroak eta produktuen izenak lotzeko.

```sql
SELECT
    s.id_sarrera,
    s.data,
    s.sarrera_egoera,
    sl.id_sarrera_lerroa,
    sl.kantitatea,
    sl.sarrera_lerro_egoera,
    p.id_produktua,
    p.izena as produktu_izena,
    p.marka
FROM sarrerak s
JOIN sarrera_lerroak sl ON s.id_sarrera = sl.sarrera_id
JOIN produktuak p ON sl.produktua_id = p.id_produktua
WHERE s.hornitzailea_id = :hid
ORDER BY s.data DESC;
```

### Produktuen Zerrenda (LEFT JOIN)

`produktuak.php` orrian erabiltzen da, produktu bakoitzari bere kategoriaren izena esleitzeko, kategoria ezabatu bada ere (nahiz eta gure kasuan beti egon beharko luketen).

```sql
SELECT p.*, k.izena as produktu_kategoria_izena
FROM produktuak p
LEFT JOIN produktu_kategoriak k ON p.kategoria_id = k.id_kategoria
WHERE p.salgai = 1 AND p.stock > 0;
```

---

## 2. GROUP BY (Datuak Taldekatzea)

`GROUP BY` estatistikak eta laburpenak egiteko erabiltzen da.

Hobekuntza

Kontsulta hori **estatistikak lortzeko eta erabakiak hartzeko** (Business Intelligence) erabili dugu, batez ere administrazioaren edo kudeaketaren ikuspuntutik.

Nahiz eta bezeroen orrietan (produktuen zerrendan, adibidez) zuzenean agertu ez, kontsulta hau funtsezkoa da BIRTEKeko kudeatzaileentzat (eta mahaigaineko Java aplikazioko txostenetan aurreikusita dago) arrazoi hauengatik:

1. **Salmenten analisia** : Kategoria bakoitzak (Ordenagailuak, Telefonia, Softwarea...) zenbat diru sortzen duen jakiteko. Horrela, kategoria errentagarrienak zeintzuk diren ikus dezakegu.
2. **Stock kudeaketa** : Diru gehien ematen duten kategorietan hornikuntza lehenesteko erabiltzen dugu.
3. **Txostenak** : Zuzendaritzari salmenten laburpen argi bat aurkezteko (adibidez, grafikoetan irudikatzeko).

Laburbilduz, kontsulta konplexu honek **datu hutsak informazio baliagarri** bihurtzen ditu enpresaren martxa aztertzeko.


Egia esan, kontsulta aurreratu horiek (estatistikak, Business Intelligence...)  **ez daude bezeroek edo hornitzaileek ikusten dituzten orri publikoetan** .

BIRTEKeko arkitekturan, datu-analisiaren zati hori honela banatuta dago:

1. **Web Orria (Bezero/Hornitzaile):** Webgunea batez ere "eragiketak" egiteko da: erosketak egin, produktuak ikusi, bidalketak kudeatu... Horregatik erabiltzen dugu

   ```
   JOIN
   ```

   bakarrik (datuak lotzeko).
2. **Kudeaketa (Business Intelligence):** Estatistika konplexu hauek (Kategorien araberako salmentak, bezeroen gastu totalak...) **BIRTEKeko kudeatzaileentzat** pentsatuta daude.
3. **BirtekAp (Java):**

   langileak_menua.php orrian ikus dezakezun bezala, proiektuak **Java mahaigaineko aplikazio bat** du (

   ```
   BirtekAp
   ```

   ). Estatistika eta txosten aurreratu horiek aplikazio administratibo horretan exekutatzeko diseinatuta daude, enpresaren barne kudeaketarako.

Guk README_web_sql.md fitxategian gehitu ditugu proiektuaren **gaitasun teknikoa eta SQL konplexutasun-maila** erakusteko, nahiz eta web-interfazean (publikoan) zuzenean ikusgai ez egon.

### Salmenta Totalak Kategoria bakoitzeko

Kategoria bakoitzak sortutako diru-kopuru osoa kalkulatzeko aztertutako kontsulta.

```sql
SELECT
    k.izena as kategoria,
    SUM(el.kantitatea * el.unitate_prezioa) as guztira_saldua
FROM eskaera_lerroak el
JOIN produktuak p ON el.produktua_id = p.id_produktua
JOIN produktu_kategoriak k ON p.kategoria_id = k.id_kategoria
GROUP BY k.izena;
```

---

## 3. HAVING (Taldeen Iragazkiak)

`HAVING` bitartez, taldekatutako datuen gaineko baldintzak jartzen ditugu.

### 5 Eskaera baino gehiago egin dituzten bezeroak

Bezero aktiboenak identifikatzeko erabilitako logika.

```sql
SELECT
    b.izena_edo_soziala,
    COUNT(e.id_eskaera) as eskaera_kopurua
FROM bezeroak b
JOIN eskaerak e ON b.id_bezeroa = e.bezeroa_id
GROUP BY b.id_bezeroa
HAVING eskaera_kopurua > 5;
```

### Gastu handiko bezeroak (1.000€-tik gora)

Bezero fidelduak saritzeko azterketa.

```sql
SELECT
    b.izena_edo_soziala,
    SUM(e.guztira_prezioa) as gastua_guztira
FROM bezeroak b
JOIN eskaerak e ON b.id_bezeroa = e.bezeroa_id
GROUP BY b.id_bezeroa
HAVING gastua_guztira > 1000;
```
