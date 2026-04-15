<script setup>
    import {router, useForm, usePage} from '@inertiajs/vue3';
    import Footer from "@/Components/Footer.vue";
    import Navbar from "@/Components/Navbar.vue";
    import { route } from "ziggy-js";
    import { computed, ref } from "vue";
    import FormModal from "@/Components/Modal/FormModal.vue";
    import SuccessModal from "@/Components/Modal/SuccessModal.vue";
    import ConfirmModal from "@/Components/Modal/ConfirmModal.vue";
    import SearchComponent from "@/Components/SearchComponent.vue";

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

    // Uzglabā izvēlēto grāmatu bloķēšanai
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
    const searchBooks = () => {
        router.get(route('book.lists'),
            { search: search.value },
            {
                preserveState: true,
                replace: true,
            }
        );
    };

    // Apstiprina lietotāja grāmatas bloķēšanu
    const confirmUserBlock = (formData) => {
        // Aizpilda formas laukus ar datiem no modāļa
        form.subject = formData.subject;
        form.problem = formData.problem;

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

    // Lejupielādēt PDF ar visiem darbiem
    const downloadPdf = () => {
        window.location.href = route('admin.books.pdf');
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
    <!-- Navigācijas josla -->
    <Navbar />

    <div class="main-content">

        <!-- Bloķēšanas apstiprinājuma modālais logs stāstam -->
        <FormModal
            :is-open="showUserModal"
            title="Vai tiešām vēlaties bloķēt šo stāstu?"
            message="Lūdzu, norādiet bloķēšanas iemeslu, lai apstiprinātu."
            :fields="[
                { name: 'subject', label: 'Tēma', type: 'select', required: true,
                      options: [
                          { value: 'Maldinošs vai kaitīgs saturs', label: 'Maldinošs vai kaitīgs saturs' },
                          { value: 'Noteikumu pārkāpums', label: 'Noteikumu pārkāpums' },
                          { value: 'Spams vai reklāma', label: 'Spams vai reklāma' },
                          { value: 'Naida runa vai aizskarošs saturs', label: 'Naida runa vai aizskarošs saturs' },
                          { value: 'Zemas kvalitātes saturs', label: 'Zemas kvalitātes saturs' },
                          { value: 'Sūdzības no lietotājiem', label: 'Sūdzības no lietotājiem' }
                      ]
                },
                { name: 'problem', label: 'Pamatojums', type: 'textarea', required: true, rows: 4 }
            ]"
            :errors="form.errors"
            submit-text="Bloķēt"
            @submit="confirmUserBlock"
            @close="closeAllModals"
        />

        <!-- Bloķēšanas apstiprinājuma modālais logs (klasiskas grāmatas) -->
        <ConfirmModal
            :is-open="showClassicModal"
            title="Vai tiešām vēlaties bloķēt šo grāmatu?"
            confirm-text="Bloķēt"
            @confirm="confirmClassicBlock"
            @cancel="showClassicModal = false"
        />

        <!-- Veiksmīgas bloķēšanas modālais logs -->
        <SuccessModal
            :is-open="showSuccessModal"
            title="Darbs veiksmīgi bloķēts!"
            @close="showSuccessModal = false"
        />

        <!-- PDF lejupielādes poga -->
        <div class="pdf-wrapper">
            <button @click="downloadPdf" class="pdf">
                Lejupielādēt PDF
            </button>
        </div>

        <!-- Meklēšanas josla -->
        <SearchComponent
            v-model="search"
            placeholder="Meklēt darbu..."
            @search="searchBooks"
        />

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
                                <!-- Bloķēšanas poga -->
                                <button class="delete-btn" @click="openUserBlockModal(book)">Bloķēt</button>
                                <button class="edit-btn" @click="GoToReadStory(book.id)">Apskatīt</button>
                            </div>
                        </div>
                    </div>

                    <!-- Skatīt vairāk -->
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

                    <!-- Paziņojums, ja nav pieejamu darbu -->
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

                        <!-- Skatīt vairāk -->
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
    <!-- Kājene -->
    <Footer />
</template>


<style scoped>
    .main-content {
        padding-bottom: 45px; /* Apakšējais atstatums */
    }

    .story-form {
        max-width: 800px; /* Maksimālais platums */
        margin: 0 auto; /* Centrē forma horizontāli */
        padding: 20px; /* Piepildījums ap saturu */
    }

    .pdf-wrapper {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
        margin-right: 20px;
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */    }

    .pdf{
        padding: 2px 15px;
        margin-left: auto;
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
        .btn .fa{
            font-size: 18px;
        }

        h1 {
            font-size: 1.5rem;
        }

        p,
        label,
        select,
        option,
        button
        {
            font-size: 0.9rem;
        }

        .pdf{
            padding: 1px 10px;
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
    }
</style>
