<script setup>
    import { router, usePage } from '@inertiajs/vue3';
    import Footer from "@/Components/Footer.vue";
    import Navbar from "@/Components/Navbar.vue";
    import {route} from "ziggy-js";
    import {computed, ref} from "vue";

    // Saņem stāstu sarakstu no servera
    const books = usePage().props.book;
    const classicBooks = usePage().props.classicBooks;
    const authUser = usePage().props.authUser;

    const limit = 3; // Cik grāmatas rādīt sākumā

    // Pārslēdzams statuss, vai rāda visu sarakstu
    const showAllBooks = ref(false);
    const showAllClassicBooks = ref(false);

    // Aprēķina redzamās lietotāju grāmatas atkarībā no showAllBooks
    const visibleBooks = computed(() =>
        showAllBooks.value ? books : books.slice(0, limit)
    );

    // Aprēķina redzamās klasiskās grāmatas atkarībā no showAllClassicBooks
    const visibleClassicBooks = computed(() =>
        showAllClassicBooks.value ? classicBooks : classicBooks.slice(0, limit)
    );

    // Modālo logu stāvokļi
    const showModal = ref(false);
    const showSuccesModal = ref(false);

    const showDeleteModal = ref(false);
    const showDeleteSuccessModal = ref(false);

    // Atlasītie grāmatu dati
    const selectedBook = ref(null);
    const selectedType = ref('');
    const selectedBookForDelete = ref(null);

    const errors = ref({});

    // Atver dzēšanas modālo logu
    const openDeleteModal = (book, type) => {
        selectedBookForDelete.value = book;
        selectedType.value = type;
        showDeleteModal.value = true;
        document.body.style.overflow = "hidden";
    };

    // Apstiprina dzēšanu
    const confirmDelete = () => {
        if (!selectedBookForDelete.value || !selectedType.value) return;

        // Nosaka dzēšanas maršrutu atkarībā no tipa
        const routeName = selectedType.value === 'classic'
                ? 'classic_books.destroy'
                : 'deleteStory';

        router.delete(route(routeName, { id: selectedBookForDelete.value.id }), {
            preserveScroll: true,

            // Ja dzēšana veiksmīga
            onSuccess: () => {
                showDeleteModal.value = false;
                showDeleteSuccessModal.value = true;
            },

            // Ja radās kļūda
            onError: (error) => {
                console.error(error);
            }
        });
    };

    // Aizver dzēšanas apstiprinājuma modāli
    const closeDeleteModal = () => {
        showDeleteModal.value = false;
        document.body.style.overflow = "";
    };

    // Aizver veiksmīgas dzēšanas modāli un atsvaidzina lapu
    const closeDeleteSuccessModal = () => {
        showDeleteSuccessModal.value = false;
        router.visit(window.location.href);
        document.body.style.overflow = "";
    };


    // Atver atbloķēšanas modāli
    const openUnblockModal = (book, type) => {
        selectedBook.value = book;  // Izvēlētais darbs
        selectedType.value = type;  // Tips: classic/user
        showModal.value = true;
        document.body.style.overflow = "hidden";
    };

    // Universāla atbloķēšanas funkcija
    const confirmUnlock = () => {
        if (!selectedBook.value || !selectedType.value) return;

        // Nosūta bloķēšanas pieprasījumu
        router.post(
            selectedType.value === 'classic'
                ? route('classicBooks.toggleBlock', { book: selectedBook.value.id })
                : route('userBooks.toggleBlock', { userBook: selectedBook.value.id }),
            // Tikai lietotāja stāstiem sūta tēmu un pamatojumu
            {},
            {
                preserveScroll: true,
                onSuccess: () => {
                    // Aizver apstiprinājuma logu
                    closeModal();
                    // Atver veiksmīgas bloķēšanas logu
                    showSuccesModal.value = true;
                    document.body.style.overflow = 'hidden';
                },
                onError: (error) => {
                    errors.value = error; // Kļūdas
                }
            }
        );
    };

    // Aizver modāli
    const closeModal = () => {
        showModal.value = false;
        document.body.style.overflow = "";
    };

    // Aizver modāli
    const closeSuccesModal = () => {
        showSuccesModal.value = false;
        router.visit(window.location.href);
        document.body.style.overflow = "";
    };

    // Pāriet uz stāsta lasīšanas lapu
    const GoToReadStory = (UserbookId) => {
        router.get(route('UserRead', { id: UserbookId }));
    };

    // Pāriet uz klasiskās grāmatas rediģēšanas lapu
    const GoToEdit = (bookId) => {
        router.get(route('EditClassicBook', { id: bookId }));
    };
</script>
<template>
    <Navbar />

    <div class="main-content">

        <!-- Atbloķēšanas apstiprinājuma modālais logs -->
        <div v-if="showModal" class="modal-overlay">
            <div class="modal">
                <div class="success-container">
                    <h2>Vai tiešām vēlaties atbloķēt šo darbu?</h2>

                    <div class="close">
                        <button @click="closeModal" class="close-btn">Atcelt</button>
                        <button @click="confirmUnlock" class="block">Atbloķēt</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Veiksmīgas atbloķēšanas modālais logs -->
        <div v-if="showSuccesModal" class="modal-overlay">
            <div class="modal">
                <div class="success-container">
                    <h2>Darbs veiksmīgi atbloķēts!</h2>
                    <button @click="closeSuccesModal" class="close-btn">Aizvērt</button>
                </div>
            </div>
        </div>

        <!-- Dzēšanas apstiprinājuma modālais logs -->
        <div v-if="showDeleteModal" class="modal-overlay">
            <div class="modal">
                <div class="success-container">
                    <h2>Vai tiešām vēlaties dzēst šo darbu?</h2>
                    <div class="close">
                        <button @click="closeDeleteModal" class="close-btn">Atcelt</button>
                        <button @click="confirmDelete" class="block">Dzēst</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Veiksmīgas dzēšanas modālais logs -->
        <div v-if="showDeleteSuccessModal" class="modal-overlay">
            <div class="modal">
                <div class="success-container">
                    <h2>Darbs veiksmīgi dzēsts!</h2>
                    <button @click="closeDeleteSuccessModal" class="close-btn">Aizvērt</button>
                </div>
            </div>
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
                                <button
                                    v-if="authUser.role === 'superadmin'"
                                    class="delete-btn"
                                    @click="openDeleteModal(book, 'user')"
                                >
                                    Dzēst
                                </button>
                                <button class="edit-btn" @click="GoToReadStory(book.id)">Apskatīt</button>
                                <button class="edit-btn" @click="openUnblockModal(book, 'user')">Atbloķēt</button>
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
                                <button
                                    v-if="authUser.role === 'superadmin'"
                                    class="delete-btn"
                                    @click="openDeleteModal(classicBook, 'classic')"
                                >
                                    Dzēst
                                </button>
                                <button class="edit-btn" @click="GoToEdit(classicBook.id)">Rediģēt</button>
                                <button class="edit-btn" @click="openUnblockModal(classicBook, 'classic')">Atbloķēt</button>
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
        transition: all 0.3s; /* Pāreja uz mainīgām īpašībām */
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
        font-size: 16px;
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
        h1 {
            font-size: 1.5rem;
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
