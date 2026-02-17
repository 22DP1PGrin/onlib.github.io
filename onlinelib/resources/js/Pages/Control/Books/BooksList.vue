<script setup>
    import {router, useForm, usePage} from '@inertiajs/vue3';
    import Footer from "@/Components/Footer.vue";
    import Navbar from "@/Components/Navbar.vue";
    import { route } from "ziggy-js";
    import { computed, ref } from "vue";

    // Saņem stāstu sarakstu no servera
    const books = computed(() => usePage().props.book);
    const classicBooks = computed(() => usePage().props.classicBooks);
    const search = ref(usePage().props.filters?.search || '');

    const limit = 3; // Cik grāmatas rādīt sākumā

    // Pārslēdzams statuss, vai rāda visu sarakstu
    const showAllBooks = ref(false);
    const showAllClassicBooks = ref(false);

    // Aprēķina redzamās lietotāju grāmatas atkarībā no showAllBooks
    const visibleBooks = computed(() =>
        showAllBooks.value
            ? books.value
            : books.value?.slice(0, limit) ?? []
    );

    // Aprēķina redzamās klasiskās grāmatas atkarībā no showAllClassicBooks
    const visibleClassicBooks = computed(() =>
        showAllClassicBooks.value
            ? classicBooks.value
            : classicBooks.value?.slice(0, limit) ?? []
    );

    // Modālo logu stāvokļi
    const showUserModal = ref(false);
    const showClassicModal = ref(false);
    const showSuccessModal = ref(false);

    // Atlasītie grāmatu dati
    const selectedBook = ref(null);


    // Veidlapas dati bloķēšanas paziņojumam (tikai lietotāja grāmatām)
    const form = useForm({
        subject: '',
        problem: '',
    });

    // Atver modāli lietotāja grāmatas bloķēšanai
    const openUserBlockModal = (book) => {
        selectedBook.value = book;
        document.body.style.overflow = "hidden";
        showUserModal.value = true;
    };

    // Atver modāli klasiskās grāmatas bloķēšanai
    const openClassicBlockModal = (book) => {
        selectedBook.value = book;
        document.body.style.overflow = "hidden";
        showClassicModal.value = true;
    };

    // Meklēt grāmatu pēc nosaukuma
    const searchUsers = () => {
        router.get(route('book.lists'),
            { search: search.value },
            {
                preserveState: true,
                replace: true,
            }
        );
    };

    // Apstiprina lietotāja grāmatas bloķēšanu
    const confirmUserBlock = () => {
        if (!selectedBook.value) return;

        form.post(
            route('userBooks.toggleBlock', {userBook: selectedBook.value.id}),
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

    // Apstiprina klasiskās grāmatas bloķēšanu
    const confirmClassicBlock = () => {
        if (!selectedBook.value) return;

        router.post(
            route('classicBooks.toggleBlock', { book: selectedBook.value.id }),
            {},
            {
                preserveScroll: true,

                onSuccess: () => {
                    showClassicModal.value = false;
                    showSuccessModal.value = true;
                }
            }
        );
    };

    // Aizver visus bloķēšanas modāļus
    const closeAllModals = () => {
        showUserModal.value = false;
        showClassicModal.value = false;
        form.reset();
        document.body.style.overflow = "";
    };

    // Aizver veiksmīgas darbības modāli
    const closeSuccessModal = () => {
        showSuccessModal.value = false;
        router.visit(window.location.href);
        document.body.style.overflow = "";
    };

    // Pāriet uz klasiskās grāmatas rediģēšanas lapu
    const GoToEdit = (bookId) => {
        router.get(route('EditClassicBook', { id: bookId }));
    };

    // Pāriet uz klasiskās grāmatas izveidošānas lapu
    const GoToCreate = () => {
        router.get(route('NewBook'));
    };

    // Pāriet uz stāsta lasīšanas lapu
    const GoToReadStory = (UserbookId) => {
        router.get(route('UserRead', { id: UserbookId }));
    };
</script>

<template>
    <Navbar />

    <div class="main-content">

        <!-- Bloķēšanas apstiprinājuma modālais logs stāstam -->
        <div v-if="showUserModal" class="modal-overlay">
            <div class="modal">
                <div class="success-container">
                    <h2>Vai tiešām vēlaties bloķēt šo darbu?</h2>
                    <p>Lūdzu, norādiet bloķēšanas iemeslu, lai apstiprinātu.</p>

                    <div class="form-group">
                        <!-- Tēmas izvēle -->
                        <label for="subject">Tēma:</label>
                        <select v-model="form.subject" required>
                            <option value="" disabled>Izvēlieties tēmu</option>
                            <option value="Maldinošs vai kaitīgs saturs">Maldinošs vai kaitīgs saturs</option>
                            <option value="Noteikumu pārkāpums">Noteikumu pārkāpums</option>
                            <option value="Spams vai reklāma">Spams vai reklāma</option>
                            <option value="Naida runa vai aizskarošs saturs">Naida runa vai aizskarošs saturs</option>
                            <option value="Zemas kvalitātes saturs">Zemas kvalitātes saturs</option>
                            <option value="Sūdzības no lietotājiem">Sūdzības no lietotājiem</option>
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
                        <button @click="confirmUserBlock" class="block">Bloķēt</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Atbloķēšanas apstiprinājuma modālais logs grāmatai -->
        <div v-if="showClassicModal" class="modal-overlay">
            <div class="modal">
                <div class="success-container">

                    <h2>Vai tiešām vēlaties bloķēt šo darbu?</h2>

                    <div class="close">
                        <button @click="closeAllModals" class="close-btn">Atcelt</button>
                        <button @click="confirmClassicBlock" class="block">Bloķēt</button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Veiksmīgas bloķēšanas modālais logs -->
        <div v-if="showSuccessModal" class="modal-overlay">
            <div class="modal">
                <div class="success-container">
                    <h2>Darbs veiksmīgi bloķēts!</h2>
                    <button @click="closeSuccessModal" class="close-btn">Aizvērt</button>
                </div>
            </div>
        </div>

        <div class="search">
            <input
                v-model="search"
                type="text"
                class="input"
                placeholder="Meklēt darbu..."
            >
            <button class="btn" @click="searchUsers">
                <i class="fa bar">&#xf002;</i>
            </button>
        </div>

        <!-- Darbu pārvaldības sadaļa -->
        <div class="story-form">

            <h1>Grāmatas un stāsti</h1>

            <div class="container">
                <div class="list">
                    <!-- Paziņojums, ja nav pieejamu darbu -->
                    <h2>Stāstu saraksts</h2>
                    <div v-if="books.length === 0" class="item">
                        <span class="title">Šeit vēl nav pievienotu darbu.</span>
                    </div>

                    <!-- Darbu saraksts -->
                    <div v-for="book in visibleBooks" :key="book.id" class="work-item">
                        <!-- Darba virsraksts -->
                        <h2>{{ book.name }}</h2>

                        <!-- Darba apraksts -->
                        <p class="description">{{ book.description }}</p>

                        <div class="work-meta">
                            <!-- Autora informācija -->
                            <span class="author">Autors: {{ book.user?.nickname || 'Anonīms' }}</span>


                            <div class="work-actions">
                                <!-- Dzēšanas poga -->
                                <button class="delete-btn" @click="openUserBlockModal(book)">Bloķēt</button>
                                <button class="edit-btn" @click="GoToReadStory(book.id)">Apskatīt</button>
                            </div>
                        </div>
                    </div>

                    <button
                        v-if="books.length > limit"
                        class="toggle-btn"
                        @click="showAllBooks = !showAllBooks"
                    >
                        {{ showAllBooks ? 'Paslēpt' : 'Skatīt vairāk' }}
                    </button>

                    <h2>Grāmatu saraksts</h2>

                    <!-- Jauna stāsta izveides sadaļa -->
                    <div class="new_section">
                        <div class="new" @click="GoToCreate">
                            <h2>Pievienot jaunu grāmatu</h2>
                            <i class="fa">&#xf055;</i>
                        </div>
                    </div>

                    <div v-if="classicBooks.length === 0" class="item">
                        <span class="title">Šeit vēl nav pievienotu darbu.</span>
                    </div>

                    <!-- Darbu saraksts -->
                    <div v-for="classicBook in visibleClassicBooks" :key="classicBook.id" class="work-item">
                        <!-- Darba virsraksts -->
                        <h2>{{ classicBook.name }}</h2>

                        <!-- Darba apraksts -->
                        <p class="description">{{ classicBook.description }}</p>

                        <div class="work-meta">
                            <!-- Autora informācija -->
                            <span class="author">Autors: {{ classicBook.Author_name }} {{ classicBook.Author_surname }}</span>


                            <div class="work-actions">
                                <!-- Blokēšana poga -->
                                <button class="delete-btn" @click="openClassicBlockModal(classicBook)">Bloķēt</button>
                                <button class="edit-btn" @click="GoToEdit(classicBook.id)">Rediģēt</button>
                            </div>
                        </div>
                    </div>
                    <div class="toggle-container">
                        <button
                            v-if="classicBooks.length > limit"
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

    .new_section {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-top: 20px;
    }

    .new {
        border: 2px dashed rgba(26, 16, 8, 0.8); /* Pārtraukta apmale */
        background-color: #ffd9c6; /* Fona krāsa */
        padding: 20px; /* Iekšējās atstarpes */
        border-radius: 4px; /* Noapaļoti stūri */
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px; /* Ēnas efekts */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-align: center;
        cursor: pointer; /* Peles kursors mainās uz pointer */
        height: 9rem; /* Fiksēts augstums */
        transition: all 0.3s; /* Pāreju efekts */
    }

    .new:hover {
        transform: translateY(-3px); /* Nedaudz paceļas uz augšu */
        background-color: #ffc8a9; /* Fona krāsa mainās */
        border: none; /* Noņem apmali */
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

    .new .fa {
        text-align: center;
        align-items: center;
        justify-content: center;
        margin-top: 0;
        font-size: 3rem;
        color: rgba(106, 51, 0, 0.8);
    }

    .description {
        font-family: Tahoma, Helvetica, sans-serif;
        font-size: 1rem;
        color: rgba(26, 16, 8, 0.8);
        word-wrap: break-word;
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
        .error,
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

        .new .fa{
            font-size: 2.8rem;
        }

        .description {
            font-size: 0.9rem;
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
    }
</style>
