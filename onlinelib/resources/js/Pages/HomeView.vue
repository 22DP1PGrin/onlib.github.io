<script setup>
    import Navbar from '@/Components/Navbar.vue'
    import Footer from '@/Components/Footer.vue'
    import {router} from "@inertiajs/vue3";
    import {route} from "ziggy-js";
    import {ref} from "vue";

    // Definē komponenta datus, kas tiek padoti no servera
    const props = defineProps({
        classicBooks: Array, // Klasisko grāmatu masīvs
        userBooks: Array    // Lietotāju stāstu masīvs
    });

    const searchQuery = ref('');

    const performSearch = () => {
        if (searchQuery.value.trim()) {
            router.get(route('search.books'), {
                query: searchQuery.value
            }, {
                preserveState: true,
                replace: true
            })
        }
    };

     //Aprēķina grāmatas vērtējumu
    const getBookRating = (book) => {
        const average = parseFloat(book.ratings_avg_grade) || 0; // Iegūst vidējo vērtējumu
        const count = book.ratings_count || 0; // Iegūst vērtējumu skaitu
        return {
            average: average.toFixed(1), // Noapaļo līdz 1 ciparam aiz komata
            count: count
        }
    };

    //Formatē vērtējumu skaitu atbilstoši latviešu valodas likumiem
    const formattedRatingsCount = (count) => {
        if (count === 1) return `${count} atsauksme`;
        if (count > 1 && count < 10) return `${count} atsauksmes`;
        return `${count} atsauksmju`;
    };


     //Pārvirza uz klasiskās grāmatas lasīšanas lapu

    const GoToReadClassic = (bookId) => {
        router.get(route('ClassicRead', { id: bookId }));
    };


    //Pārvirza uz lietotāja stāsta lasīšanas lapu
    const GoToReadStory = (UserbookId) => {
        router.get(route('UserRead', { id: UserbookId }));
    };

    //Pārvirza uz jauna stāsta izveides lapu
    const GoToCreate = () => {
        router.get(route('NewStory'));
    };
</script>

<template>

    <Navbar />

    <div class="page-wrapper">
        <div class="library-container">
            <!-- Platformas apraksta sadaļa -->
            <div class="about-platform">
                <h1 class="platform-title">Onlib</h1> <!-- Platformas nosaukums -->
                <p class="platform-description">
                    Šī nav tikai bibliotēka, bet gan vesela pasaule, kur satiekas pagātne un tagadne:
                    lasiet nemirstīgo klasiku un radiet savus stāstus.
                    Jūsu radošums šeit atradīs savus lasītājus, un Jūsu dvēsele atradīs savas dzimtos vārdus.
                </p> <!-- Platformas apraksts -->

                <div class="search">
                    <input
                        v-model="searchQuery"
                        type="text"
                        class="input"
                        placeholder="Meklēt grāmatu..."
                    >
                    <button class="btn" @click="performSearch">
                        <i class="fa bar">&#xf002;</i>
                    </button>
                </div>
            </div>

            <!-- Klasiskās literatūras sadaļa -->
            <section class="section-intro">
                <h2 class="intro-title">Klasiskā literatūra</h2>
                <p class="intro-text">
                    Klasiskā literatūra ir tilts starp diviem laikmetiem, kur katra grāmata saglabā tā laika domas un kaislības.
                    Onlib platformā Jūs varat pārlasīt savus iecienītākos klasiskos darbus un atklāt veselu Latvijas vēsturiskās kultūras pasauli.
                </p>
            </section>

            <!-- Klasisko grāmatu saraksts -->
            <section class="book-section" v-if="classicBooks.length">
                <div class="books-grid">
                    <!-- Klasiskās grāmatas kartes -->
                    <div v-for="book in classicBooks" :key="book.id" class="book-card">
                        <div class="book-content">
                            <h3 class="book-title">{{ book.name }}</h3> <!-- Grāmatas nosaukums -->
                            <p class="book-description">{{ book.description }}</p> <!-- Apraksts -->

                            <!-- Vērtējuma informācija -->
                            <div class="book-info">
                                <span class="book">
                                    <strong>Vērtējums: </strong>
                                    {{ getBookRating(book).average }}<span class="fastar">★</span>
                                    ({{ formattedRatingsCount(getBookRating(book).count) }})
                                </span>
                            </div>

                            <!-- Vecuma ierobežojums -->
                            <div class="book-info">
                                <span class="info-label">Vecuma ierobežojums: </span>
                                <span class="rating-badge">{{ book.age_limit }}</span>
                            </div>

                            <!-- Autora informācija -->
                            <div class="book-info">
                                <span class="book"><strong>Autors: </strong> {{ book.Author_name }} {{ book.Author_surname }}</span>
                            </div>

                            <!-- Žanri -->
                            <div class="book-genres">
                                <span class="info-label">Žanri:</span>
                                <div class="genres-list">
                                    <span v-for="genre in book.genres" :key="genre.id" class="genre-badge">
                                        {{ genre.name }}
                                    </span>
                                </div>
                            </div>

                            <!-- Lasīšanas poga -->
                            <button class="read-btn" @click="GoToReadClassic(book.id)">Lasīt</button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Lietotāju stāstu sadaļa -->
            <section class="section-intro">
                <h2 class="intro-title">Lietotāju stāsti</h2>
                <p class="intro-text">
                    Atklājiet literatūras pasauli no šejienes lietotājiem.
                    Svaigas idejas, spilgti tēli un oriģināli stāsti palīdzēs īsināt Jūsu brīvo laiku un iedvesmot jauniem sasniegumiem.
                </p>
            </section>

            <!-- Lietotāju stāstu saraksts -->
            <section class="book-section" v-if="userBooks.length">
                <div class="books-grid">
                    <!-- Lietotāju stāstu kartes -->
                    <div v-for="book in userBooks" :key="book.id" class="book-card">
                        <div class="book-content">
                            <div class="bookmark-badge">
                                Jaunums
                            </div>

                            <h2 class="book-title">{{ book.name }}</h2> <!-- Stāsta nosaukums -->
                            <p class="book-description">{{ book.description }}</p> <!-- Apraksts -->

                            <!-- Vērtējuma informācija -->
                            <div class="book-info">
                                <span class="book">
                                    <strong>Vērtējums: </strong>
                                    {{ getBookRating(book).average }}<span class="fastar">★</span>
                                    ({{ formattedRatingsCount(getBookRating(book).count) }})
                                </span>
                            </div>

                            <!-- Vecuma ierobežojums -->
                            <div class="book-info">
                                <span class="info-label">Vecuma ierobežojums: </span>
                                <span class="rating-badge">{{ book.age_limit }}</span>
                            </div>

                            <!-- Autora informācija -->
                            <div class="book-info">
                                <span class="book"><strong>Autors: </strong>{{ book.user.nickname }}</span>
                            </div>

                            <!-- Statusa informācija -->
                            <div class="book-info">
                                <span class="book"><strong>Status: </strong>{{ book.status }}</span>
                            </div>

                            <!-- Žanri -->
                            <div class="book-genres">
                                <span class="info-label">Žanri:</span>
                                <div class="genres-list">
                                    <span v-for="genre in book.genres" :key="genre.id" class="genre-badge">
                                        {{ genre.name }}
                                    </span>
                                </div>
                            </div>

                            <!-- Lasīšanas poga -->
                            <button class="read-btn" @click="GoToReadStory(book.id)">Lasīt</button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Vērtējumu sadaļa -->
            <section class="section-intro">
                <div class="line">
                    <h2 class="intro-title">Vērtējumi</h2>
                    <p class="intro-text">
                        Dalieties savā viedoklī ar citiem lietotājiem, novērtējot literārā darba kvalitāti no 1 līdz 5.
                    </p>
                </div>
            </section>

            <!-- Zvaigžņu vērtējuma vizualizācija -->
            <section class="section-intro">
                <div class="stars-container">
                    <span class="star1">★★★</span> <!-- 3 zvaigznes -->
                    <span class="star2">★★</span> <!-- 2 zvaigznes -->
                </div>
            </section>

            <!-- Jauna stāsta izveides sadaļa -->
            <section class="section-intro">
                <div class="line">
                    <h2 class="intro-title">Kļūsti par daļu</h2>
                    <p class="intro-text">
                        Vai jums ir stāsts, kas gaida savu lasītāju? Mūsu platforma dod iespēju jaunajiem
                        autoriem rasties un attīstīties kopā ar vērtīgu atsauksmju.
                    </p>
                </div>
            </section>

            <div class="new_section">
                <div class="new" @click="GoToCreate">
                    <h2>Izveidot jaunu stāstu</h2>
                    <i class="fa">&#xf055;</i>
                </div>
            </div>
        </div>
    </div>

    <Footer />
</template>

<style scoped>

    .page-wrapper {
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa  */
        min-height: 100vh; /* Minimālais augstums - visa ekrāna augstums */
        display: flex; /* Flexbox izkārtojums */
        flex-direction: column; /* Elementus novieto vertikāli */
    }

    /* Meklēšanas josla */
    .search {
        display: flex;  /* Flexbox izkārtojums konta sadaļai */
        justify-content: center;
        align-items: center;  /* Elementu vertikāla izlīdzināšana */
        margin: 40px auto;
        max-width: 800px;
        margin-bottom: 30px;
    }

    .search:hover {
        transform: none; /*noņemam transformāciju, kad pele tiek pārvilkta */
    }

    .search .input {
        background-color: #ffffff; /*Krasa fona */
        border: 0; /* Noņemam apmales */
        border-radius: 20px; /* Noapaļo apmalas*/
        border-color: rgba(26, 16, 8, 0.8); /* Mainam apmales krāsu */
        font-size: 1rem; /* Fonta izmērs */
        padding: 10px; /* Iekšējās atstarpes */
        height: 15%;
        width: 90%; /* Sakam ar nulles platumu */
    }

    /* Poga meklēšanai */
    .search .btn {
        background-color: #c58667;
        border: 2px solid rgba(26, 16, 8, 0.8); /*apmales vērtības */
        padding: 0;
        border-radius: 20px;
        cursor: pointer; /* Peles formāts */
        outline: none; /* Noņemam noklusēto apmales stāvokli */
        margin-left: 7px; /* Atstarpe no labās puses */
        width: 41px;
        height: 40px;
        transition: border-color 0.3s;
    }

    .btn .fa{
        font-size: 20px;
        text-align: center;
        transition: color 0.3s !important;
    }

    .input{
        color: rgba(26, 16, 8, 0.8);
        font-family: Tahoma, Helvetica, sans-serif; /* Fonta tips */
    }
    .search input::placeholder {
        color: rgba(26, 16, 8, 0.42); /* Krāsa */
    }

    .input:focus {
        outline: none !important; /* Noņemam noklusēto apmales stāvokli */
        box-shadow: none !important;
        background-color: #ffd9c6; /* Fona krāsa */
    }
    .btn:hover {
        border-color: rgba(255, 187, 142, 0.8); /* Mainam apmales krāsu, kad pele tiek pārvilkta */
    }
    .btn:hover .fa {
        color: rgba(255, 187, 142, 0.8); /* Mainam ikonas krāsu, kad pele tiek pārvilkta */
    }
    .fa{
        color: rgba(26, 16, 8, 0.8);  /* Fonta krāsa */
    }

    .library-container {
        max-width: 1200px; /* Maksimālais platums */
        width: 100%; /* Aizpilda visu pieejamo platumu */
        margin: 0 auto; /* Centrēšana horizontāli */
        padding: 20px; /* Iekšējās atstarpes */
        flex: 1; /* Aizpilda atlikušo vietu */
    }

    .about-platform {
        text-align: center; /* Teksta centrēšana */
        margin-bottom: 50px; /* Apakšējā atstarpe */
        padding-bottom: 30px; /* Atstarpe zem teksta */
        border-bottom: 1px solid rgba(26, 16, 8, 0.8); /* Apakšējā līnija */
    }

    .platform-title {
        color: rgba(26, 16, 8, 0.8);
        font-size: 1.7rem; /* Fonta izmērs */
        margin-bottom: 25px;
        margin-top: 25px;
        font-weight: bold; /* Treknraksts */
    }

    .platform-description {
        font-size: 1.1rem;
        line-height: 2; /* rindu augstuma */
        color: rgba(26, 16, 8, 0.8);
        max-width: 800px;
        margin: 0 auto;
        margin-bottom: 25px;
    }


    .section-intro {
        margin: 50px 0 30px;
        padding: 0 20px; /* Sānu atstarpes */
    }

    .intro-title {
        font-size: 1.7rem;
        margin-bottom: 15px;
        text-align: center; /* Centrēšana */
        font-weight: bold;
    }

    .intro-text {
        font-size: 1.1rem;
        line-height: 2;
        color: rgba(26, 16, 8, 0.8);
        text-align: center;
        max-width: 700px;
        margin: 0 auto;
    }


    .line {
        border-top: 1px solid rgba(26, 16, 8, 0.8);
        margin-top: 50px;
        padding-top: 50px;
    }


    .books-grid {
        display: grid; /* Grid izkārtojums */
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); /* Adaptīvas kolonnas */
        gap: 30px; /* Atstarpes starp elementiem */
        margin-bottom: 50px;
    }

    .bookmark-badge {
        position: absolute; /* Absolūtā pozicionēšana */
        top: 10px;
        right: 10px;
        color: rgba(26, 16, 8, 0.8);
        padding: 3px 10px;
        background-color: #ffd9c6; /* Fona krāsa */
        box-shadow: 0 2px 4px rgba(63, 31, 4, 0.8); /* Ēna */
        border-radius: 15px; /* Noapaļoti stūri */
        font-size: 0.85rem;
        transition: all 0.3s; /* Pārejas efekts */
    }

    .book-card {
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmale */
        background-color: #e4a27c;
        border-radius: 8px;
        box-shadow: 0 6px 15px rgba(63, 31, 4, 0.8);
        overflow: hidden; /* Pārplūdes slēpšana */
        transition: all 0.3s ease;
        position: relative; /* Relatīvā pozīcija */
    }

    .book-card:hover {
        transform: translateY(-5px); /* Nedaudz paceļas */
        box-shadow: 0 8px 20px rgba(63, 31, 4, 0.8);
    }

    .book-content {
        flex: 1; /* Aizpilda visu pieejamo vietu */
        padding: 25px;
        display: flex;
        flex-direction: column; /* Elementus novieto vertikāli */
        height: 100%;
    }

    .book-title {
        font-size: 1.1rem;
        font-weight: bold;
        margin-bottom: 15px;
        text-align: center;
        color: rgba(106, 51, 0, 0.8);
        font-family: Tahoma, Helvetica, sans-serif;
        line-height: 1.3;
    }

    .book-description {
        color: rgba(26, 16, 8, 0.62);
        margin-bottom: 20px;
        font-size: 1rem;
        word-wrap: break-word; /* Vārdu pārnešana */
    }

    .book {
        font-size: 1rem;
        color: rgba(26, 16, 8, 0.8);
    }


    .book-info {
        margin-bottom: 12px;
        display: flex; /* Flexbox izkārtojums */
        text-align: left; /* Kreisais līdzinājums */
    }

    .info-label {
        font-weight: bold;
        margin-right: 8px;
        color: rgba(26, 16, 8, 0.8);
        min-width: 100px;
        flex-shrink: 0; /* Neļauj sašaurināties */
    }

    .rating-badge {
        display: inline-block; /* Bloks, kas neaizpilda visu rindu */
        padding: 3px 10px; /* Iekšējās atstarpes */
        background-color: #ffd9c6;
        color: rgba(26, 16, 8, 0.8);
        box-shadow: 0 2px 4px rgba(63, 31, 4, 0.8);
        border-radius: 15px;
        font-size: 0.85rem;
    }

    .book-genres {
        margin-top: 0; /* Noņem augšējo atstarpes */
    }


    .genres-list {
        display: flex;
        flex-wrap: wrap; /* Atļauj ielauzties jaunā rindā */
        gap: 8px;
        margin-top: 8px;
        margin-bottom: 20px;
    }

    .genre-badge {
        display: inline-block;
        padding: 5px 12px;
        background-color: #ffd9c6;
        border-radius: 15px;
        font-size: 0.85rem;
        color: rgba(26, 16, 8, 0.8);
        box-shadow: 0 2px 4px rgba(63, 31, 4, 0.8);
    }

    .read-btn {
        margin-top: auto;
        display: block;
        width: 100%;
        padding: 12px;
        background-color: #c58667;
        border: 2px solid rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease; /* Pārejas animācija */
        text-align: center;
        font-family: Tahoma, Helvetica, sans-serif;
        font-size: 1rem;
    }

    button:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;
    }

    .fastar {
        font-size: 1.2rem;
    }

    .stars-container {
        margin-top: -40px;
        display: flex;
        justify-content: center;
        width: 100%;
    }

    .star1 {
        font-size: 4rem;
        color: #ffc8a9;
    }

    .star2 {
        font-size: 4rem;
    }


    .new_section {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin: 20px auto;
        align-items: center;
        margin-bottom: 70px;
    }


    .new {
        border: 2px dashed rgba(26, 16, 8, 0.8);
        background-color: #ffd9c6;
        padding: 20px;
        border-radius: 4px;
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-align: center;
        cursor: pointer;
        height: 9rem;
        transition: all 0.3s;
        width: 60%;
    }

    .new:hover {
        transform: translateY(-3px);
        background-color: #ffc8a9;
        border: none;
    }

    .new .fa {
        text-align: center;
        align-items: center;
        justify-content: center;
        margin-top: 0;
        font-size: 3rem;
    }

    @media (max-width: 500px) {

        .search .input {
            font-size: 0.9rem; /* Fonta izmērs */
            height: 30px;
            width: 75%;
        }

        /* Poga meklēšanai */
        .search .btn {
            padding: 0;
            width: 34px;
            height: 34px;
        }

        .btn .fa{
            font-size: 18px;
        }

        .platform-title {
            font-size: 1.5rem;
        }

        .platform-description {
            font-size: 1rem;
            line-height: 1.8;
        }

        .intro-title {
            font-size: 1.5rem;

        }

        .intro-text {
            font-size: 1rem;
            line-height: 1.8;
        }

        .bookmark-badge {
            font-size: 0.75rem;


        }

        .book-title {
            font-size: 1rem;
        }

        .book-description {
            font-size: 0.9rem;
        }

        .book {
            font-size: 0.9rem;
        }

        .rating-badge {
            display: inline-block; /* Blakus elementi */
            padding: 3px 10px; /* Iekšējā atstarpe */
            background-color: #ffd9c6;
            color: rgba(26, 16, 8, 0.8);
            box-shadow: 0 2px 4px rgba(63, 31, 4, 0.8);
            border-radius: 15px;
            font-size: 0.75rem;
        }

        .genre-badge {
            font-size: 0.75rem;
        }

        .read-btn {
            padding: 8px;
            font-size: 0.9rem;
        }

        .fastar{
            font-size: 1.1rem;
        }


        .star1{
            font-size: 3.8rem;
        }

        .star2{
            font-size: 3.8rem;
        }

        .new h2{
            font-size: 1rem;
        }


        .new .fa {
            font-size: 2.8rem;
        }

        .new {
            padding: 20px; /* Iekšējās atstarpes */
            height: 8rem; /* Fiksēts augstums */
            width: 65%;
        }
    }
</style>
