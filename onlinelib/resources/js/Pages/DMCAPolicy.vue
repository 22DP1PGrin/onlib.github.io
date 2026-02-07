<template>
    <!-- Navigācijas josla -->
    <Navbar />

    <!-- Galvenais saturs -->
    <div class="main-content">
        <div class="content">

            <!-- DMCA politikas virsraksts -->
            <h1>DMCA Politika</h1>

            <!-- Sadaļa: Kas ir DMCA Politika? -->
            <h2>1. Kas ir DMCA Politika?</h2>
            <p>
                Šī DMCA (Digital Millennium Copyright Act) Politika nosaka, kā tiek apstrādātas autortiesību pārkāpumu sūdzības mūsu platformā.
                Tā izskaidro, kā lietotāji var iesniegt sūdzības, kā mēs tās apstrādājam un kādas ir lietotāju atbildības, publicējot saturu.
                Visa nepieciešamā informācija ir jānosūta mūsu <a href="/technicalsupport"> Tehniskajam atbalstam</a>.
            </p>

            <!-- Sadaļa: Lietotāja atbildība -->
            <h2>2. Lietotāja atbildība</h2>
            <p>
                Lietotājs ir atbildīgs par to, ka viņa publicētais saturs nepārkāpj citu personu autortiesības.
                Ja lietotājs publicē saturu, kas pieder citai personai, viņam ir jāiegūst atļauja no autortiesību īpašnieka.
            </p>

            <!-- Sadaļa: Autortiesību pārkāpumu sūdzības iesniegšana -->
            <h2>3. Autortiesību pārkāpumu sūdzības iesniegšana</h2>
            <p>
                Ja lietotājs uzskata, ka viņa autortiesības ir pārkāptas, viņš var iesniegt oficiālu DMCA sūdzību.
                Sūdzībai jāietver šāda informācija:
            </p>
            <ul>
                <!-- Sūdzības iesniegšanas prasības -->
                <li><strong>3.1</strong> Fizisks vai elektronisks paraksts no autortiesību īpašnieka vai personas, kurai ir tiesības rīkoties viņa vārdā.</li>
                <li><strong>3.2</strong> Detalizēts apraksts par darbu, kura autortiesības ir pārkāptas.</li>
                <li><strong>3.3</strong> Precīzs vietrādis (URL), kur pārkāptais materiāls atrodas mūsu platformā.</li>
                <li><strong>3.4</strong> Sūdzības iesniedzēja vārds un e-pasta adrese.</li>
                <li><strong>3.5</strong> Paziņojums, ka sūdzības iesniedzējs uzskata, ka materiāla izmantošana nav atļauta.</li>
                <li><strong>3.6</strong> Paziņojums, ka sniegtā informācija ir patiesa un ka sūdzības iesniedzējs ir autortiesību īpašnieks vai personas, kurai ir tiesības rīkoties viņa vārdā.</li>
            </ul>

            <!-- Sadaļa: Kā tiek apstrādātas sūdzības -->
            <h2>4. Kā tiek apstrādātas sūdzības</h2>
            <p>
                Saņemot derīgu DMCA sūdzību, mēs veicam nepieciešamo pārbaudi.
                Ja sūdzība ir pamatota, mēs noņemam vai bloķējam piekļuvi pārkāptajam saturam.
                Lietotājs, kurš publicēja materiālu, tiks informēts par noņemšanu un varēs iesniegt pretpaziņojumu.
            </p>

            <!-- Sadaļa: Pretpaziņojumu iesniegšana -->
            <h2>5. Pretpaziņojumu iesniegšana</h2>
            <p>
                Ja lietotājs uzskata, ka viņa saturs tika noņemts kļūdas vai pārpratuma dēļ, viņš var iesniegt pretpaziņojumu.
                Pretpaziņojumā jāietver šāda informācija:
            </p>
            <ul>
                <!-- Pretpaziņojuma iesniegšanas prasības -->
                <li><strong>5.1</strong> Fizisks vai elektronisks paraksts no lietotāja vai personas, kurai ir tiesības rīkoties viņa vārdā.</li>
                <li><strong>5.2</strong> Detalizēts apraksts par materiālu, kas tika noņemts.</li>
                <li><strong>5.3</strong> Paziņojums, ka materiāls tika noņemts kļūdas vai pārpratuma dēļ.</li>
                <li><strong>5.4</strong> Lietotāja vārds un e-pasta adrese.</li>
                <li><strong>5.5</strong> Paziņojums, ka lietotājs piekrīt jurisdikcijai, kurā atrodas mūsu platforma.</li>
            </ul>

            <!-- Sadaļa: Izmaiņas DMCA politikā -->
            <h2>6. Izmaiņas DMCA politikā</h2>
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
