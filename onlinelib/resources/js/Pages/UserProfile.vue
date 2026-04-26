<script setup>
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";
    import { computed, ref } from "vue";
    import {router, useForm, usePage} from "@inertiajs/vue3";
    import { route } from "ziggy-js";
    import FormModal from "@/Components/Modal/FormModal.vue";
    import SuccessModal from "@/Components/Modal/SuccessModal.vue";

    // Komponenta ievaddati
    const props = defineProps({
        user: Object,
    });

    // Aprēķina dažādus statistikas rādītājus
    const booksCount = computed(() => usePage().props.booksCount);
    const totalRatingsCount = computed(() => usePage().props.totalRatingsCount);
    const readBooksCount = computed(() => usePage().props.readBooksCount || 0);
    const CommentsCount = computed(() => usePage().props.commentsCount || 0);

    // Modālo logu stāvokļi
    const showUserModal = ref(false);
    const showSuccessModal = ref(false);

    // Veidlapas dati paziņojumam
    const form = useForm({
        subject: '',
        problem: '',
    });

    // Uzglabā izvēlēto lietotāju ziņošanai
    const selectedUser = ref(null);

    // Atver modāli lietotāja ziņošanai
    const openUserBlockModal = (book) => {
        selectedUser.value = book;
        document.body.style.overflow = "hidden";
        showUserModal.value = true;
    };

    // Apstiprina lietotāja ziņošanu
    const submitReport = (data) => {

        form.subject = data.subject;
        form.problem = data.problem;

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

    const goToRead = (userId) => {
        router.get(route('bookmarks.user', { userId, typeId: 1 })); // Pāriet uz izlasīto grāmatu sarakstu
    };
    const goToPlanned = (userId) => {
        router.get(route('bookmarks.user', { userId, typeId: 4 })); // Pāriet uz plānoto grāmatu sarakstu
    };
    const goToReading = (userId) => {
        router.get(route('bookmarks.user', { userId, typeId: 2 })); // Pāriet uz pašlaik lasāmo grāmatu sarakstu
    };
    const goToDropped = (userId) => {
        router.get(route('bookmarks.user', { userId, typeId: 3 })); // Pāriet uz pamesto grāmatu sarakstu
    };
</script>
<template>
    <!-- Navigācijas josla -->
    <Navbar />
    <div class="main-content">

        <!-- Veiksmīgas grāmatzīmes pievienošanas modālis -->
        <SuccessModal
            :is-open="showSuccessModal"
            title="Sūdzība veiksmīgi nosūtīta!"
            @close="showSuccessModal = false"
        />

        <!-- Ziņošanas modālis -->
        <FormModal
            :is-open="showUserModal"
            title="Vai tiešām vēlaties ziņot par šo lietotāju?'"
            message="Lūdzu, norādiet ziņošanu iemeslu, lai apstiprinātu."
            :fields="[
                { name: 'subject', label: 'Tēma', type: 'select', required: true,
                      options: [
                          { value: 'Krāpnieciska vai maldinoša darbība', label: 'Krāpnieciska vai maldinoša darbība' },
                          { value: 'Noteikumu pārkāpums', label: 'Noteikumu pārkāpums' },
                          { value: 'Naida runa vai diskriminējoša uzvedība', label: 'Naida runa vai diskriminējoša uzvedība' },
                          { value: 'Citu lietotāju aizskaršana', label: 'Citu lietotāju aizskaršana' },
                      ]
                },
                { name: 'problem', label: 'Pamatojums', type: 'textarea', required: true, rows: 4 }
            ]"
            :errors="form.errors"
            submit-text="Ziņot"
            @submit="submitReport"
            @close="showUserModal = false"
        />

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
                            <div class="stat-label">Izlasītie darbi</div>
                        </div>

                        <!-- Uzrakstīto darbu skaits -->
                        <div class="stat-item">
                            <div class="stat-number">{{ booksCount || 0 }}</div>
                            <div class="stat-label">Uzrakstītie stāsti</div>
                        </div>

                        <!-- Novērtēto darbu skaits -->
                        <div class="stat-item">
                            <div class="stat-number">{{ totalRatingsCount || 0 }}</div>
                            <div class="stat-label">Novērtētie darbi</div>
                        </div>

                        <!-- Komentāru skaits -->
                        <div class="stat-item">
                            <div class="stat-number">{{ CommentsCount || 0 }}</div>
                            <div class="stat-label">Komentāru skaits</div>
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
    <!-- Kājene -->
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

