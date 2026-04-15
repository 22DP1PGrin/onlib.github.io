<script setup>
    import Navbar from '@/Components/Navbar.vue'
    import Footer from '@/Components/Footer.vue'
    import {router} from "@inertiajs/vue3";
    import {route} from "ziggy-js";
    import {ref} from "vue";
    import UserBooksSection from "@/Components/Books/UserBookSection.vue";
    import ClassicBooksSection from "@/Components/Books/ClassicBookSection.vue";
    import SearchComponent from "@/Components/SearchComponent.vue";

    // Komponenta ievaddati
    const props = defineProps({
        classicBooks: Array, // Klasisko grāmatu masīvs
        userBooks: Array    // Lietotāju stāstu masīvs
    });

    // Meklēšanas vaicājums
    const search = ref('');

    // Meklēšanas funkcija
    const performSearch = () => {
        if (search.value.trim()) { // Pārbauda, vai meklēšanas lauks nav tukšs
            router.get(route('search.books'), {
                query: search.value // Meklēšanas vaicājums
            }, {
                preserveState: true,
                replace: true
            })
        }
    };

    //Pārvirza uz jauna stāsta izveides lapu
    const GoToCreate = () => {
        router.get(route('NewStory'));
    };
</script>

<template>
    <!-- Navigācijas josla -->
    <Navbar />

    <div class="page-wrapper">
        <div class="library-container">
            <!-- Platformas apraksta sadaļa -->
            <div class="about-platform">
                <h1 class="platform-title">Onlib</h1> <!-- Platformas nosaukums -->
                <!-- Platformas apraksts -->
                <p class="platform-description">
                    Šī nav tikai bibliotēka, bet gan vesela pasaule, kur satiekas pagātne un tagadne:
                    lasiet nemirstīgo klasiku un radiet savus stāstus.
                    Jūsu radošums šeit atradīs savus lasītājus, un Jūsu dvēsele atradīs savas dzimtos vārdus.
                </p>

                <!-- Meklēšanas josla -->
                <SearchComponent
                    v-model="search"
                    placeholder="Meklēt darbu..."
                    @search="performSearch"
                />
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
            <ClassicBooksSection
                v-if="classicBooks.length"
                :classicBooks="classicBooks"
            />

            <!-- Lietotāju stāstu sadaļa -->
            <section class="section-intro">
                <h2 class="intro-title">Lietotāju stāsti</h2>
                <p class="intro-text">
                    Atklājiet literatūras pasauli no šejienes lietotājiem.
                    Svaigas idejas, spilgti tēli un oriģināli stāsti palīdzēs īsināt Jūsu brīvo laiku un iedvesmot jauniem sasniegumiem.
                </p>
            </section>

            <!-- Lietotāju grāmatu sadaļa -->
            <UserBooksSection
                v-if="userBooks.length"
                :books="userBooks"
            />

            <!-- Vērtējumu sadaļa -->
            <section class="section-intro">
                <div class="line">
                    <h2 class="intro-title">Vērtējumi</h2>
                    <p class="intro-text">
                        Šeit lietotāji var dalīties ar savu vērtējumu par grāmatām, novērtējot tās skalā no 1 līdz 5.
                        Tas palīdz citiem vieglāk izvēlēties sev interesantus darbus.
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

            <!-- Komentāru sadaļas apraksts -->
            <section class="section-intro">
                <div class="line">
                    <h2 class="intro-title">Komentāri</h2>
                    <p class="intro-text">
                        Šeit var dalīties ar savu viedokli par lasītajiem darbiem, apspriest sižetu un idejas,
                        kā arī iepazīties ar citu lasītāju domām.
                    </p>
                </div>
            </section>

            <!-- Jauna stāsta izveides sadaļa -->
            <section class="section-intro">
                <div class="line">
                    <h2 class="intro-title">Kļūsti par daļu</h2>
                    <p class="intro-text">
                        Ja tev ir savs stāsts, šī ir vieta, kur to var publicēt un dalīties ar citiem lasītājiem.
                        Autori šeit var augt, saņemt atsauksmes un iedvesmot citus.
                    </p>
                </div>
            </section>

            <!-- Jauna stāsta izveides poga -->
            <div class="new_section">
                <div class="new" @click="GoToCreate">
                    <h2>Izveidot jaunu stāstu</h2>
                    <i class="fa">&#xf055;</i>
                </div>
            </div>
        </div>
    </div>
    <!-- Kājene -->
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

    .btn .fa{
        font-size: 20px;
        text-align: center;
        transition: color 0.3s !important;
    }

    .search input::placeholder {
        color: rgba(26, 16, 8, 0.42); /* Krāsa */
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

    button:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;
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
