<script setup>
    import {router, useForm, usePage} from '@inertiajs/vue3';
    import Footer from "@/Components/Footer.vue";
    import Navbar from "@/Components/Navbar.vue";
    import { route } from "ziggy-js";
    import { computed, ref } from "vue";

    // Saņem ziņotu objektu sarakstu no servera
    const storyReports = computed(() => usePage().props.storyReports);
    const bookReports = computed(() => usePage().props.bookReports);
    const userReports = computed(() => usePage().props.userReports);
    const search = ref(usePage().props.filters?.search || '');

    const selectedReport = ref(null);

    const limit = 3; // Cik sūdzības rādīt sākumā

    // Pārslēdzami statusi, vai rādīt visu sarakstu
    const showAllBooks = ref(false);
    const showAllClassicBooks = ref(false);
    const showAllUsers = ref(false);

    // Aprēķina redzamās sudzības uz lietotāju grāmatas
    const visibleBooks = computed(() =>
        showAllBooks.value
            ? storyReports.value
            : storyReports.value?.slice(0, limit) ?? []
    );

    // Aprēķina redzamās sūdzības uz klasiskās grāmatas
    const visibleClassicBooks = computed(() =>
        showAllClassicBooks.value
            ? bookReports.value
            : bookReports.value?.slice(0, limit) ?? []
    );

    // Aprēķina redzamās sūdzības uz lietotājus
    const visibleUsers = computed(() =>
        showAllUsers.value
            ? userReports.value
            : userReports.value?.slice(0, limit) ?? []
    );

    // Modālo logu stāvokļi
    const showSuccessModal = ref(false);

    const showDeleteModal = ref(false);

    // Atver dzēšanas modālo logu
    const openDeleteModal = (report) => {
        selectedReport.value = report;
        showDeleteModal.value = true;
        document.body.style.overflow = "hidden";
    };

    // Aizver veiksmīgas darbības modāli
    const closeSuccessModal = () => {
        showSuccessModal.value = false;
        router.visit(window.location.href);
        document.body.style.overflow = "";
    };

    // Aizver dzēšanas modālo logu
    const closeDeleteModal = () => {
        showDeleteModal.value = false;
        selectedReport.value = null;
        document.body.style.overflow = "";
    };

    // Meklēt sudzība pēc  grāmata/stāsta nosaukuma vai lietotājvārdu
    const searchUsers = () => {
        router.get(route('admin.reports'),
            { search: search.value },
            {
                preserveState: true,
                replace: true,
            }
        );
    };

    // Apstiprina ziņojuma dzēšanu
    const confirmDelete = () => {
        if (!selectedReport.value) return;

        router.delete(
            route('reports.delete', { report: selectedReport.value.id }),
            {
                preserveScroll: true,
                onSuccess: () => {
                    showDeleteModal.value = false;
                    showSuccessModal.value = true;
                }
            }
        );
    };

    // Navigācija uz klasiskās grāmatas lasīšanas lapu
    const GoToReadClassic = (bookId) => {
        router.get(route('ClassicRead', { id: bookId }));
    };

    // Navigācija uz lietotāja stāsta lasīšanas lapu
    const GoToReadStory = (UserbookId) => {
        router.get(route('UserRead', { id: UserbookId }));
    };

    // Navigācija uz lietotāja profila lapu
    const GoToUser = (userId) => {
        if (!userId) return;
        router.get(route('other.users.watch', { id: userId }));
    };
</script>

<template>
    <Navbar />

    <div class="main-content">

        <!-- Dzēšanas apstiprinājuma modālais logs -->
        <div v-if="showDeleteModal" class="modal-overlay">
            <div class="modal">
                <div class="success-container">
                    <h2>Vai tiešām vēlaties dzēst šo sūdzību?</h2>
                    <div class="close">
                        <button @click="closeDeleteModal" class="close-btn">Atcelt</button>
                        <button @click="confirmDelete" class="block">Dzēst</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Veiksmīgas dzēšanas modālais logs -->
        <div v-if="showSuccessModal" class="modal-overlay">
            <div class="modal">
                <div class="success-container">
                    <h2>Sūdzība veiksmīgi dzēsta!</h2>
                    <button @click="closeSuccessModal" class="close-btn">Aizvērt</button>
                </div>
            </div>
        </div>

        <div class="search">
            <input
                v-model="search"
                type="text"
                class="input"
                placeholder="Meklēt sūdzību..."
            >
            <button class="btn" @click="searchUsers">
                <i class="fa bar">&#xf002;</i>
            </button>
        </div>

        <!-- Lietotāju sūdzību pārvaldības sadaļa -->
        <div class="story-form">

            <h1>Lietotāju sūdzības</h1>

            <div class="container">
                <div class="list">
                    <h2>Ziņotie lietotāji</h2>

                    <!-- Paziņojums, ja nav ziņojumu par lietotājiem -->
                    <div v-if="userReports.length === 0" class="item">
                        <span class="title">Nav ziņojumu par lietotājiem.</span>
                    </div>

                    <!-- Saraksts ar ziņotajiem lietotāiem -->
                    <div v-for="report in visibleUsers" :key="report.id" class="work-item">
                        <!-- Lietotājvārds -->
                        <h2>{{ report.reported_user?.nickname }}</h2>

                        <!-- Ziņojuma saturs -->
                        <p><b>Iemesls:</b> {{ report.subject }}</p>
                        <p><b>Pamatojums:</b> {{ report.problem }}</p>

                        <div class="work-meta">
                            <!-- Ziņotāja informācija -->
                            <span class="author">No: {{ report.reporter?.nickname }}</span>

                            <div class="work-actions">
                                <!-- Dzēšana, apskatīšana -->
                                <button class="delete-btn" @click="openDeleteModal(report)">
                                    Dzēst
                                </button>
                                <button class="edit-btn" @click="GoToUser(report.reported_user?.id)">
                                    Apskatīt
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Skatīt vairāk -->
                    <button class="toggle-btn"
                            @click="showAllUsers = !showAllUsers"
                            v-if="userReports.length > limit">
                        {{ showAllUsers ? 'Paslēpt' : 'Skatīt vairāk' }}
                    </button>

                    <h2>Ziņotie stāsti</h2>

                    <!-- Paziņojums, ja nav ziņojumu par stāstiem -->
                    <div v-if="storyReports.length === 0" class="item">
                        <span class="title">Nav ziņojumu par stāstiem.</span>
                    </div>

                    <!-- Saraksts ar ziņotajiem stāstiem -->
                    <div v-for="report in visibleBooks" :key="report.id" class="work-item">
                        <!-- Stāsta virsraksts -->
                        <h2>{{ report.user_book?.name }}</h2>

                        <!-- Ziņojuma saturs -->
                        <p><b>Iemesls:</b> {{ report.subject }}</p>
                        <p><b>Pamatojums:</b> {{ report.problem }}</p>

                        <div class="work-meta">
                            <!-- Ziņotāja informācija -->
                            <span class="author">No: {{ report.reporter?.nickname }}</span>

                            <div class="work-actions">
                                <!-- Dzēšana, apskatīšana -->
                                <button class="delete-btn" @click="openDeleteModal(report)">
                                    Dzēst
                                </button>
                                <button class="edit-btn" @click="GoToReadStory(report.user_book?.id)">Apskatīt</button>
                            </div>
                        </div>
                    </div>

                    <!-- Skatī vairāk -->
                    <button
                        v-if="storyReports.length > limit"
                        class="toggle-btn"
                        @click="showAllBooks = !showAllBooks"
                    >
                        {{ showAllBooks ? 'Paslēpt' : 'Skatīt vairāk' }}
                    </button>

                    <h2>Ziņotās grāmatas</h2>

                    <!-- Paziņojums, ja nav ziņojumu par grāmatām -->
                    <div v-if="bookReports.length === 0" class="item">
                        <span class="title">Nav ziņojumu par grāmatām.</span>
                    </div>

                    <!-- Saraksts ar ziņotajām grāmatām -->
                    <div v-for="report in visibleClassicBooks" :key="report.id" class="work-item">
                        <!-- Grāmatas virsraksts -->
                        <h2>{{ report.classic_book?.name }}</h2>

                        <!-- Ziņojuma saturs -->
                        <p><b>Iemesls:</b> {{ report.subject }}</p>
                        <p><b>Pamatojums:</b> {{ report.problem }}</p>

                        <div class="work-meta">
                            <!-- Ziņotāja informācija -->
                            <span class="author">No: {{ report.reporter?.nickname }}</span>


                            <div class="work-actions">
                                <!-- Dzēšana, apskatīšana -->
                                <button class="delete-btn" @click="openDeleteModal(report)">
                                    Dzēst
                                </button>
                                <button class="edit-btn" @click="GoToReadClassic(report.classic_book?.id)">Apskatīt</button>
                            </div>
                        </div>
                    </div>

                    <!-- Skatī vairāk -->
                    <div class="toggle-container">
                        <button
                            v-if="bookReports.length > limit"
                            class="toggle-btn"
                            @click="showAllClassicBooks = !showAllClassicBooks"
                        >
                            {{ showAllClassicBooks ? 'Paslēpt' : 'Skatīt vairāk' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Footer />
</template>


<style scoped>
    .main-content {
        padding-bottom: 45px; /* Apakšējais atstatums */
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

    /* Meklēšanas josla */
    .search {
        display: flex;  /* Flexbox izkārtojums konta sadaļai */
        justify-content: center;
        align-items: center;  /* Elementu vertikāla izlīdzināšana */
        margin: 80px auto;
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

    .story-form {
        max-width: 800px; /* Maksimālais platums */
        margin: 0 auto; /* Centrē forma horizontāli */
        padding: 20px; /* Piepildījums ap saturu */
    }

    p{
        font-size: 1rem; /* Fonta lielums */
        color: rgba(26, 16, 8, 0.8); /* Krāsa */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
        word-wrap: break-word;
    }

    h1 {
        font-size: 1.7rem; /* Fonta lielums */
        margin-top: 32px;
        margin-bottom: 40px;
        text-align: center; /* Centrēts teksts */
        color: rgba(26, 16, 8, 0.8); /* Krāsa */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
        font-weight: bold; /* Trekns fonts */
    }

    h2{
        font-size: 1.1rem; /* Fonta lielums */
        text-align: center; /* Centrēts teksts */
        color: rgba(26, 16, 8, 0.8); /* Krāsa */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
        font-weight: bold; /* Trekns fonts */
    }

    .list {
        margin-bottom: 20px;
        display: flex; /* Flexbox izkārtojums */
        flex-direction: column; /* Elementi kolonnā */
        gap: 20px; /* Atstarpe starp elementiem */
    }

    .item {
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
        background-color: #e4a27c;
        border-radius: 8px; /* Noapaļotas malas */
        text-align: center;
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 10px; /* Ēna*/
        font-family: Tahoma, Helvetica, sans-serif;
        display: flex; /* Izmanto flexbox režīmu */
        justify-content: space-between; /* Izlīdzina saturu horizontāli */
        align-items: center;
        padding: 10px 15px; /* Piepildījums ap saturu */
        margin-bottom: 10px;
    }

    .title {
        color: rgba(26, 16, 8, 0.8);
        font-family: Tahoma, Helvetica, sans-serif;
        font-size: 1.1rem;
        font-weight: bold;
    }

    .work-item {
        border: 1px solid rgba(26, 16, 8, 0.8);
        background-color: #e4a27c;
        padding: 20px;
        border-radius: 4px;
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px;
    }

    .work-item h2,
    .new h2{
        font-family: Tahoma, Helvetica, sans-serif;
        font-weight: bold;
        margin-top: 0; /* Noņem noklusēto atstarpi */
        color: rgba(106, 51, 0, 0.8);
        font-size: 1.1rem;
    }

    .author {
        font-family: Tahoma, Helvetica, sans-serif;
        font-size: 0.9rem;
    }

    .work-meta {
        display: flex;
        justify-content: space-between; /* Elementi izvietoti pa labi un kreisi */
        align-items: center;
        margin-top: 15px;
        color: rgba(26, 16, 8, 0.7);
    }

    .work-actions {
        display: flex;
        gap: 10px;
    }

    button {
        border: 2px solid rgba(26, 16, 8, 0.8);
        background-color: #c58667; /* Fona krāsa */
        color: rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        font-size: 1.0rem;
        transition: background-color 0.3s, border-color 0.3s; /* Pārejas efekts, lai uzlabotu interaktivitāti */
        cursor: pointer; /* Kursora izmaiņas pie pogas */
        padding: 5px 10px;
    }

    .block {
        align-self: flex-start;
        margin-bottom: 5px;
        border: 2px solid rgba(35, 11, 11, 0.8);
        background-color: #714e3e;
    }

    .delete-btn {
        font-family: Tahoma, Helvetica, sans-serif;
        padding: 3px 15px;
        border: 2px solid rgba(35, 11, 11, 0.8);
        background-color: #714e3e;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s; /* Pāreja uz mainīgām īpašībām */
    }

    .edit-btn{
        font-family: Tahoma, Helvetica, sans-serif;
        background-color: #c58667;
        border: 2px solid rgba(26, 16, 8, 0.8);
        padding: 3px 12px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s; /* Pāreja uz mainīgām īpašībām */
    }

    .toggle-container {
        display: flex;
        justify-content: center;
    }

    .toggle-btn{
        font-family: Tahoma, Helvetica, sans-serif;
        background-color: #c58667;
        border: 2px solid rgba(26, 16, 8, 0.8);
        align-self: center;
        padding: 6px 20px;
        border-radius: 4px;
        cursor: pointer;
        transition: all
        0.3s; /* Pāreja uz mainīgām īpašībām */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
    }

    button:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;
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

        h1 {
            font-size: 1.5rem;
        }

        p,
        label,
        select,
        option
        {
            font-size: 0.9rem;
        }

        h2{
            font-size: 1.0rem;
        }
        .title{
            font-size: 1rem;
        }

        .work-item h2,
        .new h2{
            font-size: 1rem;
        }

        .author{
            font-size: 0.8rem;
        }

        .delete-btn {
            padding: 2px 8px;
            font-size: 0.9rem;
        }
        .edit-btn{
            padding: 2px 8px;
            font-size: 0.9rem;
        }

        input::placeholder,
        textarea::placeholder{
            font-size: 0.9rem;
        }

        .modal{
            max-width: 300px;
        }

        .success-container h2{
            font-size: 1.1rem;
        }

        .report-wrapper .fa{
            font-size: 0.9rem;
        }
    }
</style>
