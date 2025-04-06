<template>
    <!-- Navigācijas josla -->
    <Navbar />

    <!-- Galvenais saturs -->
    <div class="main-content">
        <div class="content">
            <!-- Privātuma politikas virsraksts -->
            <h1>Privātuma politika</h1>

            <!-- Sadaļa: Ievads -->
            <h2>1. Ievads</h2>
            <p>
                Šī privātuma politika izskaidro, kā mēs apkopojam, izmantojam, apstrādājam un aizsargājam personisko informāciju, kad lietojat tiešsaistes bibliotēkas pakalpojumus.
                Mēs vērtējam Jūsu privātumu un esam apņēmušies aizsargāt datus saskaņā ar piemērojamiem tiesību aktiem.
            </p>

            <!-- Sadaļa: Apkopotās informācijas veidi -->
            <h2>2. Apkopotās informācijas veidi</h2>
            <p>Tiek apkopoti šādi dati:</p>
            <ul>
                <!-- Personiskā informācija -->
                <li><strong>2.1</strong> Vārds, uzvārds, e-pasta adrese, lietotājvārds un parole, ja reģistrējaties mūsu vietnē.</li>
                <!-- Lietošanas dati -->
                <li><strong>2.2.</strong> Informācija par to, kā lietotājs izmantojat mūsu pakalpojumu, piemēram, izlasītās grāmatas, publicētie darbi, meklēšanas vaicājumi un citas darbības.</li>
                <!-- Tehniskie dati -->
                <li><strong>2.3.</strong> IP adrese, pārlūkprogrammas veids, ierīces tips, operētājsistēma un citi tehniskie parametri.</li>
            </ul>

            <!-- Sadaļa: Informācijas izmantošanas mērķi -->
            <h2>3. Informācijas izmantošanas mērķi</h2>
            <p>Dati tiek izmantoti tādiem mērķiem:</p>
            <ul>
                <!-- Pakalpojumu nodrošināšana -->
                <li><strong>3.1.</strong> Lai nodrošinātu mūsu tiešsaistes bibliotēkas funkcionalitāti, tostarp grāmatu lasīšanu un darbu publicēšanu.</li>
                <!-- Lietotāju kontu pārvaldība -->
                <li><strong>3.2.</strong> Lai izveidotu un pārvaldītu lietotāju kontu, kā arī nodrošinātu drošu pieslēgšanos.</li>
                <!-- Analīze un uzlabošana -->
                <li><strong>3.3.</strong> Lai analizētu lietotāju uzvedību un uzlabotu mūsu pakalpojumu kvalitāti.</li>
                <!-- Saziņa ar lietotāju -->
                <li><strong>3.4.</strong> Lai sazinātos ar lietotāju par konta aktivitātēm, atjauninājumiem vai jaunumiem.</li>
            </ul>

            <!-- Sadaļa: Datu aizsardzības pasākumi -->
            <h2>4. Datu aizsardzības pasākumi</h2>
            <p>
                Mēs izmantojam dažādas drošības pasākumus, lai aizsargātu personisko informāciju no neautorizētas piekļuves, izmantošanas vai izpaušanas.
                Tie ietver šifrēšanu, drošus datu glabāšanas risinājumus un regulārus drošības auditus.
            </p>

            <!-- Sadaļa: Informācijas nodošana trešajām personām -->
            <h2>5. Informācijas nodošana trešajām personām</h2>
            <p>
                Mēs nedalāmies ar personisko informāciju ar trešajām personām, izņemot šādos gadījumos:
            </p>
            <ul>
                <!-- Ar lietotāja piekrišanu -->
                <li><strong>5.1.</strong> Ja lietotājs piekrīt, mēs varam dalīties ar datiem ar noteiktiem partneriem.</li>
                <!-- Saskaņā ar likumu -->
                <li><strong>5.2.</strong> Ja tas ir nepieciešams, lai ievērotu tiesību aktus vai reaģētu uz tiesību aizsardzības prasībām.</li>
                <!-- Trešo pušu pakalpojumu sniedzēji -->
                <li><strong>5.3.</strong> Mēs varam izmantot trešo pušu pakalpojumu sniedzējus, kas palīdz nodrošināt pakalpojumus.</li>
            </ul>

            <!-- Sadaļa: Lietotāju tiesības attiecībā uz personisko informāciju -->
            <h2>6. Lietotāju tiesības attiecībā uz personisko informāciju</h2>
            <p>Lietotājam ir šādas tiesības attiecībā uz savu personisko informāciju:</p>
            <ul>
                <!-- Piekļuve -->
                <li><strong>6.1.</strong> Lietotājs var piekļūt saviem personiskajiem datiem.</li>
                <!-- Labošana -->
                <li><strong>6.2.</strong> Lietotājs var pieprasīt labot neprecīzus vai nepilnīgus datus.</li>
                <!-- Dzēšana -->
                <li><strong>6.3.</strong> Lietotājs var pieprasīt dzēst savus datus, ja tie vairs nav nepieciešami.</li>
                <!-- Ierobežošana -->
                <li><strong>6.4.</strong> Lietotājs var ierobežot savu datu apstrādi noteiktos apstākļos.</li>
                <!-- Pretenzija -->
                <li><strong>6.5.</strong> Lietotājs var iebilst pret savu datu apstrādi.</li>
                <!-- Portabilitāte -->
                <li><strong>6.6.</strong> Lietotājs var pieprasīt savu datu pārsūtīšanu citam pakalpojumu sniedzējam.</li>
            </ul>

            <!-- Sadaļa: Sīkfaili (cookies) -->
            <h2>7. Sīkfaili (cookies)</h2>
            <p>
                Mēs izmantojam sīkfailus, lai uzlabotu pieredzi vietnē. Sīkfaili ir mazi teksta faili, kas tiek saglabāti Jūsu ierīcē.
                Jūs varat kontrolēt sīkfailu izmantošanu, mainot pārlūkprogrammas iestatījumus.
            </p>

            <!-- Sadaļa: Izmaiņas privātuma politikā -->
            <h2>8. Izmaiņas privātuma politikā</h2>
            <p>
                Mēs paturam tiesības jebkurā laikā mainīt šo privātuma politiku.
                Visas izmaiņas tiks publicētas šajā lapā, un tās stāsies spēkā nekavējoties pēc publicēšanas.
            </p>
        </div>
    </div>

    <!-- Kājene -->
    <Footer/>
</template>

<script>
/* Komponentu importēšana */
import Navbar from "@/components/Navbar.vue"; // Importē navigācijas joslu
import Footer from "@/components/Footer.vue"; // Importē kājeni

/* Komponentu reģistrēšana */
export default {
    components: {
        Navbar, // Reģistrē navigācijas joslu
        Footer  // Reģistrē kājeni
    }
};
</script>

<style scoped>
    /* Galvenā satura konteinera stils */
    .main-content {
        align-items: center; /* Centrē saturu vertikāli */
        flex: 1; /* Aizpilda atlikušo vietu */
        padding: 20px; /* Iekšējā atstarpe */
        margin-top: 55px; /* Atstarpe no augšas */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
        display: flex; /* Flex izkārtojums */
        flex-direction: column; /* Kolonnas izkārtojums */
        padding-bottom: 60px;
    }

    /* Satura konteinera stils */
    .content {
        justify-items: center; /* Centrē saturu horizontāli */
        max-width: 800px; /* Maksimālais satura platums */
        margin: 0 auto; /* Centrēt saturu */
        padding: 0 20px; /*Atstarpes sānos*/
    }

    /* Galvenā virsraksta stils */
    h1 {
        font-size: 1.7rem; /* Teksta izmērs */
        font-weight: bold; /* Treknraksts */
        text-align: center; /* Centrē tekstu */
        margin-top: -80px; /* Atstarpe no augšas */
        padding: 60px; /* Iekšējā atstarpe */
    }

    /* Apakšvirsrakstu stils */
    h2 {
        font-size: 1.1rem; /* Teksta izmērs */
        font-style: italic; /* Slīpraksts */
        font-weight: bold; /* Treknraksts */
    }

    /* Rindkopu stils */
    .main-content p {
        font-size: 1.0rem; /* Teksta izmērs */
        margin: 10px 20px; /* Ārējā atstarpe */
    }

    /* Saraksta elementu stils */
    li {
        display: block; /* Parāda kā bloku elementu */
        padding: 10px; /* Iekšējā atstarpe */
        margin-left: 50px; /* Atstarpe no kreisās puses */
    }

    /* Sarakstu stils */
    .main-content ul {
        font-size: 1.0rem; /* Teksta izmērs */
        margin: 10px -10px; /* Ārējā atstarpe */
    }

    /* Saišu stils */
    a {
        text-decoration: none; /* Noņem apakšsvītrojumu */
        color: rgba(106, 51, 0, 0.8); /* Teksta krāsa */
    }
    a:hover{
        color: rgba(26, 16, 8, 0.8);
    }

    /* Responsivitāte */
    @media (max-width: 768px) {

        .main-content p,
        .main-content ul {
            font-size: 1rem; /* Teksta izmērs */
        }

        li {
            margin-right: 100px; /* Atstarpe no labās puses */
        }
    }

    @media (max-width: 500px) {

        .main-content p,
        .main-content ul {
            font-size: 0.9rem; /* Teksta izmērs */
        }
        h1{
            font-size: 1.5rem;
        }

        h2{
            font-size: 1rem;
        }

        li {
            margin-right: 0; /* Atstarpe no labās puses */
            margin-left: 30px;
        }
    }
</style>
