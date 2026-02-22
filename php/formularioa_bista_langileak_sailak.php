<!-- Informazioa konfiguratzeko formularioa -->
<form method="GET" class="aukera-formularioa">
    <input type="hidden" name="ezarpenak" value="1">
    <span class="aukera-izenburua">Hautatu erakutsi nahi dituzun zutabeak:</span>
    
    <div class="aukera-grid">
        <label class="aukera-taldea">
            <input type="checkbox" name="nan" <?= $erakutsi_nan ? 'checked' : '' ?>> NAN
        </label>
        
        <label class="aukera-taldea">
            <input type="checkbox" name="jaiotza_data" <?= $erakutsi_jaiotza ? 'checked' : '' ?>> Jaiotza Data
        </label>

        <label class="aukera-taldea">
            <input type="checkbox" name="herria" <?= $erakutsi_herria ? 'checked' : '' ?>> Herria
        </label>
        
        <label class="aukera-taldea">
            <input type="checkbox" name="helbidea" <?= $erakutsi_helbidea ? 'checked' : '' ?>> Helbidea
        </label>
        
        <label class="aukera-taldea">
            <input type="checkbox" name="posta_kodea" <?= $erakutsi_posta_kodea ? 'checked' : '' ?>> Posta Kodea
        </label>

        <label class="aukera-taldea">
            <input type="checkbox" name="telefonoa" <?= $erakutsi_telefonoa ? 'checked' : '' ?>> Telefonoa
        </label>

        <label class="aukera-taldea">
            <input type="checkbox" name="emaila" <?= $erakutsi_emaila ? 'checked' : '' ?>> Emaila
        </label>

        <label class="aukera-taldea">
            <input type="checkbox" name="saila" <?= $erakutsi_saila ? 'checked' : '' ?>> Saila
        </label>

        <label class="aukera-taldea">
            <input type="checkbox" name="saila_kokapena" <?= $erakutsi_saila_kokapena ? 'checked' : '' ?>> Saila Kokapena
        </label>
    </div>
    
    <div class="botoi-eremua">
        <button type="submit" class="botoi-iragazi">
            Eguneratu zerrenda
        </button>
    </div>
</form>
