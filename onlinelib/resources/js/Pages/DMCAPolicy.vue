<script setup>
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";
</script>

<template>
    <!-- Navigācijas josla -->
    <Navbar />
    <!-- Galvenais satura bloks -->
    <div class="main-content">
        <div class="content">

            <!-- DMCA politikas lapas virsraksts -->
            <h1>DTAL Politika</h1>

            <!-- Ievada sadaļa - kas ir DTAL politika -->
            <h2>1. Kas ir DTAL Politika?</h2>
            <p>
                Šī DTAL (Digitālo tiesību aizsardzības likuma) politika nosaka, kā platformā tiek apstrādātas autortiesību pārkāpumu sūdzības.
                Tā izskaidro, kā iespējams ziņot par iespējamiem pārkāpumiem, kā notiek sūdzību izskatīšana un kādi pienākumi ir lietotājiem, publicējot saturu.
            </p>

            <!-- Lietotāja atbildības sadaļa -->
            <h2>2. Lietotāja atbildība</h2>
            <p>
                Lietotājs ir atbildīgs par to, ka viņa publicētais saturs nepārkāpj citu personu autortiesības.
                Publicējot materiālus, jānodrošina, ka tie ir oriģināli vai tiek izmantoti ar atbilstošu atļauju no autortiesību īpašnieka.
            </p>

            <!-- Sūdzību iesniegšanas sadaļa -->
            <h2>3. Autortiesību pārkāpumu sūdzības iesniegšana</h2>
            <p>
                Ja tiek konstatēts iespējams autortiesību pārkāpums, par to var ziņot, izmantojot īpašu ziņošanas pogu pie attiecīgā darba satura vai sazinoties ar
                <a href="/technicalsupport">tehnisko atbalstu</a>.
                <br /><br />
                Sūdzībā jānorāda skaidra un pamatota informācija, tostarp:
            </p>
            <ul>
                <li><strong>3.1</strong> Saite uz oriģinālo darbu, uz kuru attiecas autortiesības.</li>
                <li><strong>3.2</strong> Paskaidrojums, kāpēc attiecīgais darbs tiek uzskatīts par plaģiātu vai autortiesību pārkāpumu.</li>
            </ul>
            <p>
                Ja sūdzība tiek iesniegta, izmantojot tehnisko atbalstu, obligāti jānorāda saite uz saturu platformā, kā arī attiecīgā darba nosaukums, kurā konstatēts iespējamais pārkāpums.
            </p>

            <!-- Sūdzību apstrādes sadaļa -->
            <h2>4. Kā tiek apstrādātas sūdzības</h2>
            <p>
                Pēc sūdzības saņemšanas tā tiek izvērtēta. Ja tiek konstatēts pārkāpums, piekļuve darbam var tikt ierobežota vai darbs var tikt noņemts.
                Par pieņemto lēmumu tiek informēts lietotājs, kurš publicējis attiecīgo darbu.
                <br /><br />
                Atkārtotu pārkāpumu gadījumā lietotājs var tikt bloķēts uz noteiktu laiku vai neatgriezeniski. Smagu vai sistemātisku pārkāpumu gadījumā platforma patur tiesības dzēst lietotāja kontu un noņemt visus ar to saistītos darbus.
            </p>

            <!-- Pretpaziņojumu iesniegšanas sadaļa -->
            <h2>5. Pretpaziņojumu iesniegšana</h2>
            <p>
                Ja lietotājs uzskata, ka darbs ir ierobežots vai noņemts nepamatoti, iespējams iesniegt pretpaziņojumu.
                Pretpaziņojumā jānorāda:
            </p>
            <ul>
                <li><strong>5.1</strong> Apraksts par darbu, kas tika noņemts vai ierobežots.</li>
                <li><strong>5.2</strong> Saite uz attiecīgo darbu platformā.</li>
                <li><strong>5.3</strong> Paskaidrojums, kāpēc ierobežojums tiek uzskatīts par nepamatotu.</li>
            </ul>

            <!-- Politikas izmaiņu sadaļa -->
            <h2>6. Izmaiņas politikā</h2>
            <p>
                Platforma patur tiesības jebkurā laikā veikt izmaiņas šajā politikā.
                Atjauninātā versija tiek publicēta šajā lapā un stājas spēkā nekavējoties pēc publicēšanas.
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
