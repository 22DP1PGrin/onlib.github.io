<script>
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";
    import { computed, ref } from "vue";
    import { router, usePage } from "@inertiajs/vue3";
    import { route } from "ziggy-js";

    export default {
        setup() {
            // Izmanto `computed`, lai iegūtu datus par lietotāju un grāmatu skaitu no servera
            const user = computed(() => usePage().props.auth.user); // Iegūst pašreizējo lietotāju
            const booksCount = computed(() => usePage().props.booksCount); // Iegūst grāmatu skaitu
            const totalRatingsCount = computed(() => usePage().props.totalRatingsCount);
            const readBooksCount = computed(() => usePage().props.readBooksCount || 0);

            // Izveido mainīgo, kas uzrauga profila rediģēšanas stāvokli
            const isEditing = ref(false);

            // Lietotāja statistika
            const stats = ref({
                booksRead: 24, // Lasīto grāmatu skaits
                favorites: 8, // Iepriekš izvēlēto grāmatu skaits
                inProgress: 5, // Grāmatas, kuras tiek lasītas
                abandoned: 3 // Grāmatas, kuras ir pamestas
            });

            // Funkcijas, lai pārietu uz dažādiem maršrutiem
            const goToEdit = () => {
                router.get(route('profile.settings')); // Pāriet uz profila iestatījumiem
            };
            const GoToWatch = () => {
                router.get(route('StoryList')); // Pāriet uz stāstu sarakstu
            };
            const GoToCreate = () => {
                router.get(route('NewStory')); // Pāriet uz jauna stāsta izveidi
            };
            const GoToUser = () => {
                router.get(route('users')); // Pāriet uz lietotāju sarakstu
            };

            const GoToForm = () => {
                router.get(route('problems')); // Pāriet uz lietotāju sarakstu
            };

            const GoToBookList = () => {
                router.get(route('book.lists')); // Pāriet uz grāmatu sarakstu
            };

            const goToRead = () => {
                router.get(route('bookmarks.read'));
            };
            const goToPlanned = () => {
                router.get(route('bookmarks.planned'));
            };
            const goToReading = () => {
                router.get(route('bookmarks.reading'));
            };
            const goToDropped = () => {
                router.get(route('bookmarks.dropped'));
            };

            // Atgriež visus datus un metodes, lai izmantotu šablonā
            return {
                user,
                isEditing,
                stats,
                booksCount,
                totalRatingsCount,
                readBooksCount,
                goToEdit,
                GoToWatch,
                GoToCreate,
                GoToUser,
                GoToForm,
                GoToBookList,
                goToRead,
                goToPlanned,
                goToReading,
                goToDropped
            };
        },
        components: {
            Navbar, // Reģistrē Navbar komponentu, lai to varētu izmantot šablonā
            Footer // Reģistrē Footer komponentu, lai to varētu izmantot šablonā
        }
    };
</script>


<template>

    <Navbar />

    <div class="main-content">
        <div class="content">
            <!-- Lietotāja profila sadaļa -->
            <div class="profile-header">
                <!-- Lietotājvārds kā virsraksts -->
                <h1><strong>{{ user.nickname }}</strong></h1>

                <!-- Biogrāfijas sadaļa -->
                <div class="bio-section">
                    <!-- Biogrāfijas teksts (ja tukšs - rāda aizvietotājtekstu) -->
                    <p class="bio-text">{{ user.bio || 'Šeit pagaidām tukšs' }}</p>
                    <!-- Profila rediģēšanas poga -->
                    <button class="edit-btn" @click="goToEdit">Rediģēt profilu</button>
                </div>
            </div>

            <!-- Satura panelis ar statistikām un saistēm -->
            <div class="content-panel">
                <!-- Lietotāja aktivitātes statistika -->
                <div class="stats-section">
                    <h2>Manā aktivitāte</h2>
                    <div class="stats-grid">
                        <!-- Izlasīto grāmatu skaits -->
                        <div class="stat-item">
                            <div class="stat-number">{{ readBooksCount }}</div>
                            <div class="stat-label">Izlasītās grāmatas</div>
                        </div>
                        <!-- Uzrakstīto darbu skaits -->
                        <div class="stat-item">
                            <div class="stat-number">{{ booksCount || 0 }}</div>
                            <div class="stat-label">Uzrakstītie darbi</div>
                        </div>
                        <!-- Novērtēto darbu skaits -->
                        <div class="stat-item">
                            <div class="stat-number">{{ totalRatingsCount || 0 }}</div>
                            <div class="stat-label">Novērtētie darbi</div>
                        </div>
                    </div>
                </div>

                <!-- Grāmatzīmju sadaļa -->
                <div class="bookmarks-links">
                    <h2>Manas grāmatzīmes</h2>
                    <div class="links-grid">
                        <!-- Izlasīto grāmatu saite -->
                        <div class="bookmark-link read" @click="goToRead">
                            <span class="fa">&#xf02d;</span> <!-- Grāmatas ikona -->
                            <span class="link-text">Izlasītās grāmatas</span>
                        </div>

                        <!-- Plānoto grāmatu saite -->
                        <div class="bookmark-link" @click="goToPlanned">
                            <span class="fa">&#xf046;</span> <!-- Atzīmēšanas ikona -->
                            <span class="link-text">Plānotās grāmatas</span>
                        </div>

                        <!-- Pašlaik lasāmo grāmatu saite -->
                        <div class="bookmark-link" @click="goToReading">
                            <span class="fa">&#xf02e;</span> <!-- Atvērtas grāmatas ikona -->
                            <span class="link-text">Lasu tagad</span>
                        </div>

                        <!-- Pamesto grāmatu saite -->
                        <div class="bookmark-link" @click="goToDropped">
                            <span class="fa">&#xf1f8;</span> <!-- Dzēšanas ikona -->
                            <span class="link-text">Pamestās grāmatas</span>
                        </div>
                    </div>
                </div>

                <!-- Literāro darbu sadaļa -->
                <h2>Mani literārie darbi</h2>
                <div class="links-grid">
                    <!-- Jauna darba izveides saite -->
                    <div class="bookmark-link" @click="GoToCreate">
                        <span class="fa">&#xf067;</span> <!-- Plusa ikona -->
                        <span class="link-text">Izveidot jaunu darbu</span>
                    </div>

                    <!-- Esošo darbu rediģēšanas saite -->
                    <div class="bookmark-link" @click="GoToWatch">
                        <span class="fa">&#xf040;</span> <!-- Rediģēšanas ikona -->
                        <span class="link-text">Rediģēt esošos darbus</span>
                    </div>
                </div>

                <!-- Administratora funkcionalitāte (rādās tikai adminiem) -->
                <div v-if="user.role === 'admin'" class="admin-actions">
                    <h2>Pārvaldība</h2>
                    <div class="links-grid">
                        <!-- Lietotāju pārvaldības saite -->
                        <div class="bookmark-link" @click="GoToUser">
                            <i class="fa">&#xf007;</i> <!-- Lietotāja ikona -->
                            <span class="link-text">Lietotāji</span>
                        </div>
                        <!-- Jautājumu pārvaldības saite -->
                        <div class="bookmark-link" @click="GoToForm">
                            <i class="fa">&#xf0ad;</i> <!-- Dokumenta ikona -->
                            <span class="link-text">Lietotāju jautājumi</span>
                        </div>
                        <!-- Visu stāstu pārvaldības saite -->
                        <div class="bookmark-link" @click="GoToBookList">
                            <i class="fa">&#xf2ba;</i> <!-- Grāmatu plaukta ikona -->
                            <span class="link-text">Visi stāsti</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Footer/>
</template>

<style scoped>
    .main-content {
        padding: 20px;
        margin-top: 55px; /* Augšējais attālums */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
        padding-bottom: 60px; /* Apakšējais izsistējs */
    }

    .content {
        max-width: 1000px; /* Maksimālais platums */
        margin: 0 auto; /* Centrē saturs */
    }

    .profile-header {
        text-align: center; /* Teksta izlīdzinājums */
        margin-bottom: 20px; /* Apakšējais attālums */
        margin-top: -20px;
    }

    .profile-header h1 {
        font-size: 1.7rem; /* Fonta lielums */
        margin-bottom: 10px;
    }

    .bio-text {
        font-size: 1.0rem; /* Fonta lielums */
        line-height: 1.6; /* Rindu augstums */
        margin-bottom: 15px;
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        max-width: 700px; /* Maksimālais platums */
        margin-left: auto; /* Automātiska centēšana pa kreisi */
        margin-right: auto; /* Automātiska centēšana pa labi */
    }

    .content-panel {
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
        background-color: #e4a27c;
        border-radius: 10px; /* Noapaļotie stūri */
        padding: 30px; /* Iekšējais atstarpe */
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px; /* Ēna */
    }

    .stats-section {
        margin-bottom: 30px;
    }

    h2 {
        text-align: center; /* Teksta izlīdzinājums */
        margin-bottom: 25px;
        font-size: 1.1rem;
        font-weight: bold; /* Teksta biezums */
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 20px; /* Attālums starp elementiem */
        max-width: 800px;
        margin: 0 auto;
    }

    .stat-item {
        background-color: #ffd9c6;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 10px;
    }

    .stat-number {
        font-size: 1.7rem;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 1rem;
        letter-spacing: 1px; /* Burtu attālums */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
    }

    .bookmarks-links {
        margin-bottom: 40px;
    }

    .links-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .bookmark-link {
        background-color: #ffd9c6;
        display: flex;
        flex-direction: column; /* Elementi vertikāli */
        align-items: center; /* Horizontāla centrēšana */
        justify-content: center; /* Vertikāla centrēšana */
        padding: 20px 15px;
        border-radius: 8px;
        text-decoration: none; /* Noņem apakšsvītrojumu */
        transition: all 0.3s; /* Pāreja uz visām īpašībām */
        text-align: center;
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 10px;
        cursor: pointer; /* Rādīt kursoru kā pogu */
    }

    .admin-actions {
        margin-top: 25px;
    }

    .bookmark-link:hover {
        transform: translateY(-3px); /* Pārvieto elementu uz augšu */
        background-color: #ffc8a9; /* Fona krāsa pelēkajā režīmā */
    }

    .fa {
        font-size: 1.7rem;
        margin-bottom: 10px;
    }

    .link-text {
        font-size: 1.0rem;
    }

    .my-works-section h3 {
        color: rgba(106, 51, 0, 0.9);
        text-align: center;
        margin-bottom: 20px;
        font-size: 1.1rem;
    }


    button:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;
    }

    .edit-btn {
        background-color: #c58667;
        color: rgba(26, 16, 8, 0.8);
        border: 2px solid rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        font-size: 1.0rem;
        transition: background-color 0.3s, border-color 0.3s;
        padding: 8px 15px;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .links-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 500px) {
        .profile-header h1,
        .fa,
        .stat-number{
            font-size: 1.5rem;
        }
        .my-works-section h3,
        h2{
            font-size: 1.0rem;
        }
        .link-text,
        .stat-label{
            font-size: 0.9rem;
        }
        .edit-btn{
            font-size: 0.9rem;
            padding: 8px 13px;
        }

        .stats-grid,
        .links-grid {
            grid-template-columns: 1fr;
        }

        .content-panel {
            padding: 20px 15px;
        }
    }
</style>

