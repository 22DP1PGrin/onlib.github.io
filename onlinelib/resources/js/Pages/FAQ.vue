<script setup lang="ts">
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";
</script>

<template>
    <!-- Navigācijas josla -->
    <Navbar />
    <!-- Galvenais satura bloks -->
    <div class="main-content">
        <div class="content">

            <!-- Bieži uzdoto jautājumu lapas virsraksts -->
            <h1>Bieži uzdotie jautājumi</h1>

            <!-- Reģistrēšanās jautājums -->
            <h2>1. Kā reģistrēties platformā?</h2>
            <p>
                Lai reģistrētos datorā, jāizvēlas poga <a href="/register">Reģistrācija</a> lapas augšējā labajā stūrī.
                Lietotājam jāievada vārds, uzvārds, lietotājvārds, e-pasta adrese un jāizveido droša parole.
                Mobilajā ierīcē reģistrācijas opcija ir pieejama, atverot izvēlni augšējā labajā stūrī.
            </p>

            <!-- Darba publicēšanas jautājums -->
            <h2>2. Kā publicēt savu darbu?</h2>
            <p>
                Darba publicēšana ir pieejama tikai autorizētiem lietotājiem.
                Lai publicētu darbu, jāieiet savā kontā un jāizvēlas sadaļa <a href="/StoryList">Rakstīšana</a>.
                Tiek atvērts lietotāja stāstu saraksts, kur iespējams izvēlēties esošu darbu vai izveidot jaunu.
            </p>
            <p>
                Lai izveidotu jaunu darbu, jāizvēlas Izveidot stāstu un jāaizpilda nepieciešamie lauki.
                Darbus iespējams pārvaldīt arī caur <a href="/profile">profila sadaļu </a> Mani literārie darbi.
            </p>

            <!-- Darba dzēšanas jautājums -->
            <h2>3. Kā dzēst publicētu darbu?</h2>
            <p>
                Lai dzēstu darbu, jāatver profils un sadaļa Mani literārie darbi, pēc tam jāizvēlas
                <a href="/StoryList">Rediģēt esošos darbus</a> un atlasiet darbu, kuru vēlaties dzēst, noklikšķinot uz atbilstošās pogas.
            </p>
            <p>Lūdzu, ņemiet vērā, ka šī darbība ir neatgriezeniska.</p>

            <!-- Grāmatu meklēšanas jautājums -->
            <h2>4. Kā atrast grāmatu vai stāstu?</h2>
            <p>
                Lai atrastu darbu, jāatver sadaļa <a href="/Library">Bibliotēka</a>.
                Lietotājam tiek parādīts visu pieejamo darbu saraksts ar filtrēšanas un kartošanas iespējām.
            </p>
            <p>
                Meklēšana ir pieejama, izmantojot meklēšanas joslu augšējā daļā, kur iespējams ievadīt grāmatas nosaukumu.
            </p>

            <!-- Grāmatu vērtēšanas jautājums -->
            <h2>5. Kā vērtēt grāmatu?</h2>
            <p>
                Grāmatas vērtēšana ir pieejama tikai autorizētiem lietotājiem.
                Lai novērtētu darbu, jāatver <a href="/Library">Bibliotēka</a> un jāizvēlas konkrētā grāmata.
                Vērtējumu iespējams pievienot sadaļā Vērtējums.
            </p>
            <p>
                Katram lietotājam ir iespēja pievienot vienu vērtējumu vienai grāmatai un to vēlāk mainīt.
            </p>

            <!-- Komentēšanas jautājums -->
            <h2>6. Kā komentēt grāmatas?</h2>
            <p>
                Komentēšanas funkcija ir pieejama tikai autorizētiem lietotājiem. Grāmatas sadaļā zem nodaļām lietotājs var pievienot komentārus, lai dalītos ar savu viedokli vai atsauksmēm par saturu.
            </p>
            <p>
                Katram komentāram ir pieejama izvēlne ar trim punktiem augšējā stūrī, kur lietotājs var veikt darbības ar savu komentāru — rediģēt vai dzēst to. Tajā pašā izvēlnē ir iespējams arī iesniegt sūdzību par citu lietotāju komentāriem.
            </p>

            <!-- Grāmatzīmju jautājums -->
            <h2>7. Kā pievienot grāmatu grāmatzīmēm?</h2>
            <p>
                Grāmatzīmju funkcija ir pieejama tikai autorizētiem lietotājiem.
                Lai pievienotu grāmatu, jāatver <a href="/Library">Bibliotēka</a> un jāizvēlas konkrētā grāmata.
                Tur pieejama opcija Pievienot grāmatzīmēm.
            </p>
            <p>
                Grāmatzīmes iespējams apskatīt sadaļā <a href="/bookmarks/read">Grāmatzīmes</a> vai <a href="/profile">profila lapā</a>.
            </p>

            <!-- Iziešanas no konta jautājums -->
            <h2>8. Kā iziet no konta?</h2>
            <p>
                Lai izietu no konta, jāatver <a href="/profile">profila sadaļa</a> Rediģēt profilu un jāizvēlas opcija Iziet.
            </p>

            <!-- Konta dzēšanas jautājums -->
            <h2>9. Kā dzēst kontu?</h2>
            <p>
                Lai dzēstu kontu, jāatver <a href="/profile">profila sadaļa</a> Rediģēt profilu un jāizvēlas opcija Dzēst kontu.
            </p>
            <p>Lūdzu, ņemiet vērā, ka konts un visi saistītie dati tiek neatgriezeniski dzēsti.</p>

            <!-- Paroles maiņas jautājums -->
            <h2>10. Kā mainīt paroli?</h2>
            <p>
                Paroles maiņa ir pieejama <a href="/profile">profila sadaļā</a> Rediģēt profilu.
                Lietotājam jāievada pašreizējā parole un jāizvēlas jauna parole.
            </p>

            <!-- Sūdzību iesniegšanas jautājums -->
            <h2>11. Kā iesniegt sūdzību?</h2>
            <p>
                Sūdzības iesniegšana ir pieejama tikai autorizētiem lietotājiem. Sūdzību iespējams iesniegt par lietotāju, stāstu, grāmatu vai komentāru, izmantojot īpašu ziņošanas pogu, kas pieejama attiecīgajā vietā platformā.
            </p>
            <p>
                Iesniedzot sūdzību, nepieciešams norādīt atbilstošu paskaidrojumu, kurā tiek skaidrots sūdzības iemesls.
            </p>

            <!-- Darba atbloķēšanas jautājums -->
            <h2>12. Kā atbloķēt darbu?</h2>
            <p>
                Ja darbs tiek bloķēts noteikumu pārkāpuma dēļ, tas var kļūt nepieejams citiem lietotājiem un nebūs redzams publiskajā platformā.
                Darba autors šādā gadījumā saglabā tiesības veikt nepieciešamās izmaiņas saturā, lai tas atbilstu platformas noteikumiem.
            </p>
            <p>
                Pēc izmaiņu veikšanas autors var vērsties pie <a href="/technicalsupport">tehniskā atbalsta</a>, iesniedzot pieprasījumu par atkārtotu darba izskatīšanu un iespējamu atbloķēšanu.
            </p>

            <!-- Konta atbloķēšanas jautājums -->
            <h2>13. Kā atbloķēt savu kontu?</h2>
            <p>
                Ja tiek konstatēti noteikumu pārkāpumi, lietotāja konts var tikt bloķēts uz noteiktu vai nenoteiktu laiku. Šādā gadījumā lietotājam jebkurā laikā ir tiesības apstrīdēt bloķēšanu, sazinoties ar <a href="/technicalsupport">tehnisko atbalstu</a> un iesniedzot skaidrojumu par situāciju.
            </p>

            <!-- Citu jautājumu sadaļa -->
            <h2>14. Citi jautājumi</h2>
            <p>
                Jautājumu vai problēmu gadījumā iespējams sazināties ar <a href="/technicalsupport">tehnisko atbalstu</a>.
                Atbildes parasti tiek sniegtas 24 stundu laikā.
            </p>
        </div>
    </div>
    <!-- Kājene -->
    <Footer />
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
