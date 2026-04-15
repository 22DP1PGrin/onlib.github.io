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
            <!-- Virsraksts -->
            <h1>Pakalpojuma noteikumi</h1>

            <!-- Ievads -->
            <h2>1. Ievads</h2>
            <p>
                Šie pakalpojuma noteikumi regulē lietotāju tiesības un pienākumus, izmantojot tiešsaistes bibliotēkas pakalpojumu.
                Izmantojot Onlib platformu, tiek uzskatīts, ka lietotājs piekrīt šiem noteikumiem.
            </p>

            <!-- Vispārējie noteikumi -->
            <h2>2. Vispārējie noteikumi</h2>
            <p>Platformas izmantošanai tiek piemēroti šādi vispārējie noteikumi:</p>
            <ul>
                <li><strong>2.1.</strong> Pakalpojums ir pieejams reģistrētiem lietotājiem, kuri ir piekrituši noteikumiem.</li>
                <li><strong>2.2.</strong> Lietotājiem ir iespēja lasīt un publicēt darbus, ievērojot noteikumus un normatīvos aktus.</li>
                <li><strong>2.3.</strong> Administrācijai ir tiesības jebkurā laikā veikt izmaiņas noteikumos.</li>
            </ul>

            <!-- Lietotāju konti -->
            <h2>3. Lietotāju konti</h2>
            <p>Kontu izmantošana tiek regulēta ar šādiem nosacījumiem:</p>
            <ul>
                <li><strong>3.1.</strong> Konta izveidei nepieciešama precīza un patiesa informācija.</li>
                <li><strong>3.2.</strong> Lietotājs ir atbildīgs par konta drošību un piekļuves datu konfidencialitāti.</li>
                <li><strong>3.3.</strong> Administrācijai ir tiesības bloķēt vai dzēst kontu noteikumu pārkāpumu gadījumā.</li>
            </ul>

            <!-- Autortiesības un satura izmantošana -->
            <h2>4. Autortiesības un satura izmantošana</h2>
            <p>Noteikumi attiecībā uz saturu:</p>
            <ul>
                <li><strong>4.1.</strong> Lietotāji saglabā autortiesības uz saviem publicētajiem darbiem.</li>
                <li><strong>4.2.</strong> Aizliegts publicēt saturu, kas pārkāpj trešo personu tiesības vai normatīvos aktus.</li>
                <li><strong>4.3.</strong> Administrācijai ir tiesības noņemt saturu, kas pārkāpj noteikumus.</li>
            </ul>

            <p>
                Papildu informācija pieejama sadaļā <a href="/contentpolicy">Satura politikā</a>.
            </p>

            <!-- Atbildība -->
            <h2>5. Atbildība</h2>
            <p>Lietotāju atbildība tiek noteikta šādi:</p>
            <ul>
                <li><strong>5.1.</strong> Lietotājs ir atbildīgs par publicētā satura kvalitāti un tā ietekmi uz citiem lietotājiem.</li>
                <li><strong>5.2.</strong> Administrācija neuzņemas atbildību par lietotāju publicēto saturu vai tā precizitāti.</li>
            </ul>

            <!-- Konfidencialitāte -->
            <h2>6. Konfidencialitāte</h2>
            <p>Datu apstrāde un privātums tiek nodrošināts šādi:</p>
            <ul>
                <li><strong>6.1.</strong> Lietotāju personiskā informācija netiek izpausta trešajām personām bez tiesiska pamata.</li>
                <li><strong>6.2.</strong> Lietotāju dati var tikt apstrādāti anonimizētā veidā statistikas un analītikas nolūkos.</li>
            </ul>

            <p>
                Papildu informācija pieejama sadaļā <a href="/privacypolicy">Privātuma politikā</a>.
            </p>

            <!-- Pārskatīšana un izmaiņas -->
            <h2>7. Pārskatīšana un izmaiņas</h2>
            <p>
                Noteikumi var tikt jebkurā laikā atjaunināti vai mainīti.
                Visas izmaiņas tiek publicētas šajā lapā un stājas spēkā pēc publicēšanas.
            </p>
        </div>
    </div>
    <!-- Kājene -->
    <Footer/>
</template>
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

    p{
        line-height: 1.8;
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

