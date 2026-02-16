<script setup>
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";
    import { computed, ref } from "vue";
    import {router, useForm, usePage} from "@inertiajs/vue3";
    import { route } from "ziggy-js";

    const props = defineProps({
        user: Object,
    });

    // Aprēķina dažādus statistikas rādītājus, izmantojot Inertia lapas props
    const booksCount = computed(() => usePage().props.booksCount);
    const totalRatingsCount = computed(() => usePage().props.totalRatingsCount);
    const readBooksCount = computed(() => usePage().props.readBooksCount || 0);

    // Modālo logu stāvokļi
    const showUserModal = ref(false);
    const showSuccessModal = ref(false);

    // Veidlapas dati paziņojumam
    const form = useForm({
        subject: '',
        problem: '',
    });

    // Atlasītie lietotāja dati
    const selectedUser = ref(null);

    // Atver modāli lietotāja ziņošanai
    const openUserBlockModal = (book) => {
        selectedUser.value = book;
        document.body.style.overflow = "hidden";
        showUserModal.value = true;
    };

    // Apstiprina lietotāja ziņošanu
    const submitReport = () => {
        if (!selectedUser.value) return;

        form.post(
            route('report.user', {user: selectedUser.value.id}),
            {
                preserveScroll: true,

                onSuccess: () => {
                    form.reset();
                    showUserModal.value = false;
                    showSuccessModal.value = true;
                }
            }
        );
    };

    // Aizver visus bloķēšanas modāļus
    const closeAllModals = () => {
        showUserModal.value = false;
        form.reset();
        document.body.style.overflow = "";
    };

    // Aizver veiksmīgas darbības modāli
    const closeSuccessModal = () => {
        showSuccessModal.value = false;
        router.visit(window.location.href);
        document.body.style.overflow = "";
    };

    // Navigācijas funkcijas uz dažādām grāmatu sadaļām
    const goToRead = (userId) => {
        router.get(route('bookmarks.user', { userId, typeId: 1 }));
    };
    const goToPlanned = (userId) => {
        router.get(route('bookmarks.user', { userId, typeId: 4 }));
    };
    const goToReading = (userId) => {
        router.get(route('bookmarks.user', { userId, typeId: 2 }));
    };
    const goToDropped = (userId) => {
        router.get(route('bookmarks.user', { userId, typeId: 3 }));
    };
</script>


<template>
    <Navbar />

    <div class="main-content">

        <!-- Ziņošanas apstiprinājuma modālais logs stāstam -->
        <div v-if="showUserModal" class="modal-overlay">
            <div class="modal">
                <div class="success-container">
                    <h2>Vai tiešām vēlaties ziņot par šo lietotāju?</h2>
                    <p>Lūdzu, norādiet ziņošanu iemeslu, lai apstiprinātu.</p>

                    <div class="form-group">
                        <!-- Tēmas izvēle -->
                        <label for="subject">Tēma:</label>
                        <select v-model="form.subject" required>
                            <option value="" disabled>Izvēlieties tēmu</option>
                            <option value="Krāpnieciska vai maldinoša darbība">Krāpnieciska vai maldinoša darbība</option>
                            <option value="Noteikumu pārkāpums">Noteikumu pārkāpums</option>
                            <option value="Naida runa vai diskriminējoša uzvedība"> Naida runa vai diskriminējoša uzvedība</option>
                            <option value="Citu lietotāju aizskaršana">Citu lietotāju aizskaršana</option>
                        </select>

                        <div v-if="form.errors.subject" class="error">
                            {{ form.errors.subject }}
                        </div>
                    </div>

                    <!-- Pamatojuma ievades lauks -->
                    <div class="form-group">
                        <label for="problem">Pamatojums:</label>
                        <textarea v-model="form.problem" required></textarea>

                        <!-- Validācijas kļūdas paziņojums pamatojumam -->
                        <div v-if="form.errors.problem" class="error">
                            {{ form.errors.problem }}
                        </div>
                    </div>
                    <div class="close">
                        <button @click="closeAllModals" class="close-btn">Atcelt</button>
                        <button @click="submitReport" class="close-btn">Ziņot</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Veiksmīgas ziņošanas modālais logs -->
        <div v-if="showSuccessModal" class="modal-overlay">
            <div class="modal">
                <div class="success-container">
                    <h2>Sūdzība veiksmīgi nosūtīta!</h2>
                    <button @click="closeSuccessModal" class="close-btn">Aizvērt</button>
                </div>
            </div>
        </div>

        <div class="content">
            <!-- Lietotāja profila sadaļa -->
            <div class="profile-header">
                <!-- Lietotājvārds kā virsraksts -->
                <h1><strong>{{ user.nickname }}</strong></h1>
                <div class="avatar-wrapper">
                    <div class="avatar">
                        <i v-if="!user.avatar" class="fa profile-icon">&#xf2be;</i>
                        <img v-else :src="`/storage/${user.avatar}`" alt="avatar" />
                    </div>
                </div>
                <!-- Biogrāfijas sadaļa -->
                <div class="bio-section">
                    <!-- Biogrāfijas teksts (ja tukšs - rāda aizvietotājtekstu) -->
                    <p class="bio-text">{{ user.bio || 'Šeit pagaidām tukšs' }}</p>
                </div>
            </div>

            <!-- Satura panelis ar statistikām un saistēm -->
            <div class="content-panel">
                <div class="report-wrapper">
                    <button class="report" @click="openUserBlockModal(user)">
                        <i style="font-size:1rem" class="fa">&#xf071;</i> Ziņot
                    </button>
                </div>
                <!-- Lietotāja aktivitātes statistika -->
                <div class="stats-section">
                    <h2>{{ user.nickname }} aktivitāte</h2>
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
                    <h2>{{ user.nickname }} grāmatzīmes</h2>
                    <div class="links-grid">
                        <!-- Izlasīto grāmatu saite -->
                        <div class="bookmark-link read" @click="goToRead(user.id)">
                            <span class="fa">&#xf02d;</span> <!-- Grāmatas ikona -->
                            <span class="link-text">Izlasītās grāmatas</span>
                        </div>

                        <!-- Plānoto grāmatu saite -->
                        <div class="bookmark-link" @click="goToPlanned(user.id)">
                            <span class="fa">&#xf046;</span> <!-- Atzīmēšanas ikona -->
                            <span class="link-text">Plānotās grāmatas</span>
                        </div>

                        <!-- Pašlaik lasāmo grāmatu saite -->
                        <div class="bookmark-link" @click="goToReading(user.id)">
                            <span class="fa">&#xf02e;</span> <!-- Atvērtas grāmatas ikona -->
                            <span class="link-text">Lasu tagad</span>
                        </div>

                        <!-- Pamesto grāmatu saite -->
                        <div class="bookmark-link" @click="goToDropped(user.id)">
                            <span class="fa">&#xf1f8;</span> <!-- Dzēšanas ikona -->
                            <span class="link-text">Pamestās grāmatas</span>
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

    /* Modala loga stils */
    .modal-overlay {
        position: fixed; /* Fiksēta pozicija */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(19, 8, 0, 0.59);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000; /* Virs visiem elementiem */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
    }

    .modal {
        border-radius: 12px;
        padding: 15px;
        max-width: 400px;
        width: 90%;
        position: relative;
        background-color: #e4a27c; /* Fona krāsa */
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
    }

    .close{
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .close-btn{
        align-self: flex-start;
        margin-bottom: 5px;
    }

    .success-container {
        text-align: center;
        padding: 15px;
    }

    .success-container h2 {
        margin-bottom: 15px;
        font-size:  1.3rem;
        font-weight: bold;
        color: rgba(26, 16, 8, 0.8);
    }

    .success-container p {
        margin-bottom: 15px;
        color: rgba(26, 16, 8, 0.8);

    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin-bottom: 10px;
    }

    label {
        font-weight: bold;
        text-align: left;
        color: rgba(26, 16, 8, 0.8); /* Krāsa */
    }

    textarea::placeholder {
        color: rgba(26, 16, 8, 0.42);
        font-size: 1.0rem;
    }

    /* Kļūdas zem ievades lauka */
    .error{
        color: rgb(110, 37, 37);
        font-size: 1rem;
        text-align: left;
        margin-bottom: 5px;
    }

    .form-group select,
    .form-group textarea {
        padding: 10px;
        border: 1px solid rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        font-size: 1rem;
    }

    option{
        font-size: 1rem;
    }

    .form-group textarea {
        resize: vertical; /* Atļauj tekstlaukam mainīt izmērus vertikāli */
        min-height: 100px; /* Minimālais augstums */
    }

    textarea:focus,
    input:focus {
        outline: none; /* Noņem apmales fokusa režīmā */
        box-shadow: none; /* Noņem nokrāsu ap laukiem */
        background-color: #ffc8a9; /* Fona krāsa, kad lauks ir fokusēts */
    }
    .content {
        max-width: 1000px; /* Maksimālais platums */
        margin: 0 auto; /* Centrē saturs */
    }
    .avatar-wrapper {
        display: flex;
        justify-content: center; /* Horizontāli centrēts */
        margin-bottom: 10px;
    }

    .avatar {
        width: 120px; /* Avatar platums */
        height: 120px; /* Avatar augstums */
        border-radius: 50%; /* Padara avataru pilnīgu apli */
        border: 1px solid rgba(26, 16, 8, 0.8);
        background-color: #e4a27c;
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px;
        border-radius: 50%; /* Pilns aplis */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .avatar img {
        width: 100%; /* Attēla platums pilnībā atbilst avataram */
        height: 100%; /* Attēla augstums pilnībā atbilst avataram */
        object-fit: cover; /* Attēls aptver visu aplīti, saglabājot proporcijas */
        border-radius: 50%;
    }

    .profile-icon {
        font-size: 90px; /* Ikonas lielums, ja nav bildes */
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
        word-wrap:break-word
    }

    button {
        background-color: #c58667;
        border: 2px solid rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        font-family: Tahoma, Helvetica, sans-serif;
        font-size: 1rem;
        padding: 3px 20px;
        align-items: center;
    }

    .close-btn{
        align-self: flex-start;
        margin-bottom: 5px;
    }

    .report-wrapper {
        display: flex;
        justify-content: flex-end;
    }

    .report{
        padding: 2px 15px;
        margin-left: auto;
    }

    .report .fa{
        margin-bottom: 0 !important;
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

    .bookmark-link:hover {
        transform: translateY(-3px); /* Pārvieto elementu uz augšu */
        background-color: #ffc8a9; /* Fona krāsa pelēkajā režīmā */
    }

    .fa {
        font-size: 1.7rem;
        margin-bottom: 10px;
    }

    .profile-icon {
        font-size: 80px;
        line-height: 1;
        margin-bottom: 0;
        cursor: default;
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

        .avatar {
            width: 100px;
            height: 100px;
        }

        .profile-icon {
            font-size: 70px;
        }

        .my-works-section h3,
        h2{
            font-size: 1.0rem;
        }
        .link-text,
        .stat-label{
            font-size: 0.9rem;
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

