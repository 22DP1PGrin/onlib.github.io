<template>
    <!-- Navigācijas josla -->
    <Navbar />

    <!-- Galvenais saturs -->
    <div class="main-content">
        <div class="content">
            <!-- Virsraksts -->
            <h1>Pakalpojuma noteikumi</h1>

            <!-- Sadaļa: Ievads -->
            <h2>1. Ievads</h2>
            <p>Šie pakalpojumu noteikumi regulē lietotāju tiesības un pienākumus, izmantojot tiešsaistes bibliotēkas pakalpojumu. <br> Lietojot Onlib, Jūs piekrītat šiem noteikumiem.</p>


            <!-- Sadaļa: Vispārējie noteikumi -->
            <h2>2. Vispārējie noteikumi</h2>
            <p>Lai nodrošinātu drošu un kvalitatīvu bibliotēkas izmantošanu, lūdzu, iepazīstieties ar šādiem noteikumiem:</p>
            <ul>
                <li><strong>2.1.</strong> Pakalpojums ir pieejams visiem lietotājiem, kuri ir reģistrējušies un piekrituši šiem noteikumiem.</li>
                <li><strong>2.2.</strong> Lietotāji var lasīt un publicēt savus darbus, ievērojot šos noteikumus un piemērojamās tiesību aktus.</li>
                <li><strong>2.3.</strong> Administrācijai ir tiesības jebkurā laikā mainīt šos noteikumus.</li>
            </ul>

            <!-- Sadaļa: Lietotāju konti -->
            <h2>3. Lietotāju konti</h2>
            <p>Lai izmantotu visus pakalpojumus, ņemiet vērā šādus punktus:</p>
            <ul>
                <li><strong>3.1.</strong> Lai izmantotu papildus funkcijas, lietotājam ir jāizveido konts, norādot patiesu un precīzu informāciju.</li>
                <li><strong>3.2.</strong> Lietotājs ir atbildīgs par sava konta drošību un par informācijas konfidencialitāti.</li>
                <li><strong>3.3.</strong> Administrācijai ir tiesības bloķēt vai dzēst lietotāja kontu, ja tiek konstatēts noteikumu pārkāpums.</li>
            </ul>

            <!-- Sadaļa: Autortiesības un satura izmantošana -->
            <h2>4. Autortiesības un satura izmantošana</h2>
            <ul>
                <p>Lai nodrošinātu godīgu satura izmantošanu, lūdzu, ievērojiet šādus noteikumus:</p>
                <li><strong>4.1.</strong> Lietotājs saglabā visas autortiesības uz saviem publicētajiem darbiem.</li>
                <li><strong>4.2.</strong> Lietotājam ir aizliegts publicēt saturu, kas aizskar trešo personu tiesības vai ir nelikumīgs.</li>
                <li><strong>4.3.</strong> Administrācijai ir tiesības noņemt jebkuru saturu, kas pārkāpj šos Noteikumus vai piemērojamās tiesības.</li>
                <p>Papildu nosacījumus var izlasīt sadaļā <a href="/contentpolicy">Satura politikā</a>.</p>
            </ul>

            <!-- Sadaļa: Atbildība -->
            <h2>5. Atbildība</h2>
            <p>Lietotāji ir atbildīgi par savu saturu un pakalpojumu izmantošanu. Lūdzu, ņemiet vērā:</p>
            <ul>
                <li><strong>5.1.</strong> Lietotājs ir atbildīgs par savu saturu un tā ietekmi uz citiem lietotājiem.</li>
                <li><strong>5.2.</strong> Administrācija neuzņemas atbildību par lietotāju radīto saturu vai tā precizitāti.</li>
            </ul>

            <!-- Sadaļa: Konfidencialitāte -->
            <h2>6. Konfidencialitāte</h2>
            <p>Lai nodrošinātu konfidencialitāti, lūdzu, izlasiet:</p>
            <ul>
                <li><strong>6.1.</strong> Administrācija apņemas ievērot lietotāju privātumu un neizpaust viņu personisko informāciju trešām personām bez lietotāja piekrišanas.</li>
                <li><strong>6.2.</strong> Lietotāji piekrīt, ka viņu dati var tikt apstrādāti anonimizētā veidā statistikas vai analītiskiem mērķiem.</li>
            </ul>
            <p>Papildu nosacījumus var izlasīt sadaļā <a href="/privacypolicy">Privātuma politikā</a>.</p>

            <!-- Sadaļa: Pārskatīšana un izmaiņas -->
            <h2>7. Pārskatīšana un izmaiņas</h2>
            <p>Mēs paturam tiesības mainīt šo satura politiku jebkurā laikā.
                Visi izmaiņojumi tiks publicēti šajā lapā, un tie stāsies spēkā nekavējoties pēc publicēšanas.</p>
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
        align-items: center;/* Centrē saturu vertikāli */
        text-align: center;
        flex: 1; /* Aizpilda atlikušo vietu */
        padding: 20px; /* Iekšējā atstarpe */
        margin-top: 55px; /* Atstarpe no augšas */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
        display: flex; /* Flex izkārtojums */
        flex-direction: column; /* Kolonnas izkārtojums */
        padding-bottom: 60px;
    }
    .content {
        justify-items: center; /* Centrē saturu horizontāli */
        max-width: 800px; /* Maksimālais satura platums */
        margin: 0 auto; /* Centrēt saturu */
        padding: 0 20px; /*Atstarpes sānos*/
        text-align: left;
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

    /* Satura konteinera stils */


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
        .main-content{
            max-width: 1000px;
        }

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

