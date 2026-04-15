<script setup>
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";
</script>
<template>
    <!-- Navigācijas josla -->
    <Navbar />
    <!-- Galvenais saturs -->
    <div class="main-content">
        <div class="content">
            <h1>Privātuma politika</h1>

            <!-- Ievads -->
            <h2>1. Ievads</h2>
            <p>
                Privātuma politika nosaka, kā tiek apkopota, izmantota, apstrādāta un aizsargāta lietotāju personiskā informācija, izmantojot tiešsaistes bibliotēkas pakalpojumus.
                Platforma apņemas nodrošināt datu aizsardzību saskaņā ar piemērojamiem tiesību aktiem.
            </p>

            <!-- Apkopotās informācijas veidi -->
            <h2>2. Apkopotās informācijas veidi</h2>
            <p>Tiek apkopoti šādi datu veidi:</p>
            <ul>
                <li><strong>2.1</strong> Reģistrācijas dati, tostarp e-pasta adrese, lietotājvārds un parole.</li>
                <li><strong>2.2</strong> Lietošanas dati, piemēram, lasītās grāmatas, publicētie darbi un citas darbības platformā.</li>
                <li><strong>2.3</strong> Tehniskā informācija, tostarp IP adrese, pārlūkprogramma, ierīces tips un operētājsistēma.</li>
            </ul>

            <!-- Informācijas izmantošanas mērķi -->
            <h2>3. Informācijas izmantošanas mērķi</h2>
            <p>Dati tiek izmantoti šādiem mērķiem:</p>
            <ul>
                <li><strong>3.1</strong> Platformas funkcionalitātes nodrošināšanai, tostarp grāmatu lasīšanai un darbu publicēšanai.</li>
                <li><strong>3.2</strong> Lietotāju kontu izveidei un pārvaldībai, kā arī drošas piekļuves nodrošināšanai.</li>
                <li><strong>3.3</strong> Pakalpojuma kvalitātes uzlabošanai un lietotāju aktivitātes analīzei.</li>
                <li><strong>3.4</strong> Informācijas sniegšanai par konta aktivitātēm un sistēmas atjauninājumiem.</li>
            </ul>

            <!-- Datu aizsardzības pasākumi -->
            <h2>4. Datu aizsardzība</h2>
            <p>
                Personiskās informācijas aizsardzībai tiek izmantoti drošības pasākumi, tostarp datu šifrēšana, droša glabāšana un regulāras sistēmas pārbaudes.
            </p>

            <!-- Informācijas nodošana trešajām personām -->
            <h2>5. Informācijas nodošana trešajām personām</h2>
            <p>
                Personiskie dati netiek nodoti trešajām personām, izņemot šādos gadījumos:
            </p>
            <ul>
                <li><strong>5.1</strong> Ja ir saņemta lietotāja piekrišana.</li>
                <li><strong>5.2</strong> Ja tas nepieciešams saskaņā ar tiesību aktiem.</li>
                <li><strong>5.3</strong> Ja tiek izmantoti pakalpojumu sniedzēji, kas nodrošina platformas darbību.</li>
            </ul>

            <!-- Lietotāju tiesības attiecībā uz personisko informāciju -->
            <h2>6. Lietotāju tiesības</h2>
            <p>Lietotājiem ir šādas tiesības attiecībā uz saviem datiem:</p>
            <ul>
                <li><strong>6.1</strong> Pieeja saviem personas datiem.</li>
                <li><strong>6.2</strong> Neprecīzu datu labošana.</li>
                <li><strong>6.3</strong> Datu dzēšanas pieprasīšana.</li>
                <li><strong>6.4</strong> Datu apstrādes ierobežošana.</li>
                <li><strong>6.5</strong> Iebildumu iesniegšana pret datu apstrādi.</li>
                <li><strong>6.6</strong> Datu pārsūtīšanas pieprasīšana citam pakalpojumu sniedzējam.</li>
            </ul>

            <!-- Sīkfaili (cookies) -->
            <h2>7. Sīkfaili (cookies)</h2>
            <p>
                Sīkfaili tiek izmantoti, lai uzlabotu platformas darbību un lietotāja pieredzi.
                Lietotājs var pārvaldīt sīkfailu iestatījumus savā pārlūkprogrammā.
            </p>

            <!-- Izmaiņas privātuma politikā -->
            <h2>8. Izmaiņas privātuma politikā</h2>
            <p>
                Privātuma politika var tikt mainīta jebkurā laikā. Visas izmaiņas tiek publicētas šajā lapā un stājas spēkā uzreiz pēc publicēšanas.
            </p>
        </div>
    </div>
    <!-- Kājene -->
    <Footer/>
</template>
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

    p{
        line-height: 1.8;
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
