<script setup>

    import Navbar from '@/Components/Navbar.vue';
    import Footer from '@/Components/Footer.vue';
    import {router} from "@inertiajs/vue3";
    import {route} from "ziggy-js";
    import {ref} from "vue";

    // Definē komponenta ievaddatus
    defineProps({
        books: Array,
        classicBooks: Array,
        allGenres: Array,
        ratings: Array
    });

    const showFilterModal = ref(false);
    const selectedGenres = ref([]);
    const selectedRating = ref(null);

    // Funkcija, kas pārslēdz žanru izvēli
    const toggleGenre = (genreId) => {
        const index = selectedGenres.value.indexOf(genreId); // Meklē žanra ID formā
        if (index === -1) { // Ja žanrs vēl nav izvēlēts
            selectedGenres.value.push(genreId); // Pievieno žanru
        } else { // Ja žanrs jau ir izvēlēts
            selectedGenres.value.splice(index, 1); // Noņem žanru
        }
    };



    const filters = ref({
        rating: null,
        genre: null,
        status: null,
        bookType: 'all'
    });

    const applyFilters = () => {
        router.get(route('books.filter'), {
            ...filters.value,
            genres: selectedGenres.value,
            rating: selectedRating.value // Добавляем выбранный рейтинг
        }, {
            preserveState: true,
            onSuccess: () => {
                showFilterModal.value = false;
            }
        });
    };

    const resetFilters = () => {
        filters.value = {
            rating: null,
            genre: null,
            status: null,
            bookType: 'all'
        };
        selectedGenres.value = [];
        selectedRating.value = null;
        applyFilters();
    };

    // Navigācija uz klasiskās grāmatas lasīšanas lapu
    const GoToReadClassic = (bookId) => {
        router.get(route('ClassicRead', { id: bookId }));
    };

    // Navigācija uz lietotāja stāsta lasīšanas lapu
    const GoToReadStory = (UserbookId) => {
        router.get(route('UserRead', { id: UserbookId }));
    };
</script>

<template>

    <Navbar />


    <div class="page-wrapper">

        <div class="library-container">
            <!-- Galvenais virsraksts -->
            <h1 class="library-title">Bibliotēka</h1>
            <button class="filter-btn" @click="showFilterModal = true">
                Filtri
            </button>

            <!-- Filtrēšanas modālais logs -->
            <div v-if="showFilterModal" class="modal-overlay" @click.self="showFilterModal = false">
                <div class="modal-content">
                    <h3>Filtrēšanas opcijas</h3>

                    <div class="filter-group">
                        <label>Grāmatu tips:</label>
                        <select v-model="filters.bookType">
                            <option value="all">Visas grāmatas</option>
                            <option value="classic">Klasiskās grāmatas</option>
                            <option value="user">Lietotāju grāmatas</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Vērtējums:</label>
                        <div class="rating-options">
                            <div
                                v-for="ratingItem in ratings"
                                :key="ratingItem.id"
                                class="rating-checkbox"
                                :class="{ 'selected': selectedRating === ratingItem.id }"
                                @click="selectedRating = selectedRating === ratingItem.id ? null : ratingItem.id"
                            >
                                {{ ratingItem.label }}
                            </div>
                        </div>
                    </div>

                    <div class="filter-group">
                        <label>Žanri:</label>
                        <div class="genre-options">
                            <div
                                v-for="genre in allGenres"
                                :key="genre.id"
                                class="genre-checkbox"
                                :class="{ 'selected': selectedGenres.includes(genre.id) }"
                                @click="toggleGenre(genre.id)"
                            >
                                {{ genre.name }}
                            </div>
                        </div>
                    </div>


                    <div class="filter-group">
                        <label>Statuss (lietotāju grāmatām):</label>
                        <select v-model="filters.status">
                            <option :value="null">Visi statusi</option>
                        </select>
                    </div>

                    <div class="modal-actions">
                        <button class="cancel-btn" @click="showFilterModal = false">Atcelt</button>
                        <button class="reset-btn" @click="resetFilters">Atiestatīt</button>
                        <button class="apply-btn" @click="applyFilters">Piemērot</button>
                    </div>
                </div>
            </div>

            <!-- Klasisko grāmatu sadaļa -->
            <section class="book-section">
                <h2 class="section-title">Klasiskās grāmatas</h2>

                <!-- Paziņojums, ja nav grāmatu -->
                <div v-if="classicBooks.length === 0" class="empty-message">
                    <p>Nav pievienotu klasisko grāmatu</p>
                </div>

                <div v-else class="books-grid">
                    <div v-for="book in classicBooks" :key="book.id" class="book-card">
                        <div class="book-content">
                            <!-- Grāmatas nosaukums -->
                            <h3 class="book-title">{{ book.name }}</h3>

                            <!-- Grāmatas apraksts -->
                            <p class="book-description">{{ book.description }}</p>

                            <!-- Vecuma ierobežojuma informācija -->
                            <div class="book-info">
                                <span class="info-label">Vērtējums:</span>
                                <span class="rating-badge">
                                    {{ book.age_limit }}
                                </span>
                            </div>

                            <!-- Autora informācija -->
                            <div class="book-info">
                                <span class="book"><strong>Autors: </strong> {{ book.Author_name }} {{ book.Author_surname }}</span>
                            </div>

                            <!-- Izdošanas gada informācija -->
                            <div class="book-info">
                                <span class="book"><strong>Izdošanas gads: </strong> {{ book.Year_publication }}</span>
                            </div>

                            <!-- Žanru informācija -->
                            <div class="book-genres">
                                <span class="info-label">Žanri:</span>
                                <div class="genres-list">
                                    <span v-for="genre in book.genres" :key="genre.id" class="genre-badge">
                                        {{ genre.name }}
                                    </span>
                                </div>
                            </div>
                            <button class="read-btn" @click="GoToReadClassic(book.id)">Lasīt</button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Lietotāju grāmatu sadaļa -->
            <section class="book-section">
                <h2 class="section-title">Lietotāju grāmatas</h2>

                <!-- Paziņojums, ja nav grāmatu -->
                <div v-if="books.length === 0" class="empty-message">
                    <p>Nav pievienotu lietotāju grāmatu</p>
                </div>

                <div v-else class="books-grid">
                    <!-- Atkārtojas katrai grāmatai -->
                    <div v-for="book in books" :key="book.id" class="book-card">
                        <div class="book-content">
                            <!-- Grāmatas nosaukums -->
                            <h2 class="book-title">{{ book.name }}</h2>

                            <!-- Grāmatas apraksts -->
                            <p class="book-description">{{ book.description }}</p>

                            <!-- Vecuma ierobežojuma informācija -->
                            <div class="book-info">
                                <span class="info-label">Vērtējums: </span>
                                <span class="rating-badge">
                                    {{ book.age_limit }}
                                </span>
                            </div>

                            <!-- Autora informācija -->
                            <div class="book-info">
                                <span class="book"><strong>Autors: </strong>{{ book.user.nickname }}</span>
                            </div>

                            <!-- Statusa informācija -->
                            <div class="book-info">
                                <span class="book"><strong>Status: </strong>{{ book.status }}</span>
                            </div>

                            <!-- Žanru informācija -->
                            <div class="book-genres">
                                <span class="info-label">Žanri:</span>
                                <div class="genres-list">
                                    <span v-for="genre in book.genres" :key="genre.id" class="genre-badge">
                                        {{ genre.name }}
                                    </span>
                                </div>
                            </div>
                            <button class="read-btn" @click="GoToReadStory(book.id)">Lasīt</button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <Footer />
    </div>
</template>

<style scoped>
    .page-wrapper {
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        min-height: 100vh; /* Minimālais augstums – visa ekrāna augstums */
        display: flex; /* Flexbox izkārtojums */
        flex-direction: column; /* Kolonnu virziens */
    }

    .library-container {
        max-width: 1200px; /* Maksimālais platums */
        width: 100%; /* Pilns platums */
        margin: 0 auto; /* Centrēšana horizontāli */
        padding: 20px; /* Iekšējās atstarpes */
        flex: 1; /* Izplešas atlikušajā telpā */
    }

    .library-title {
        font-size: 1.7rem;
        margin: 32px 0 40px; /* Ārējās atstarpes */
        text-align: center; /* Centrēts teksts */
        color: rgba(26, 16, 8, 0.8);
        font-family: Tahoma, Helvetica, sans-serif;
        font-weight: bold; /* Treknraksts */
        position: relative; /* Relatīvā pozicionēšana */
    }

    .section-title {
        font-size: 1.1rem;
        color: rgba(26, 16, 8, 0.8);
        font-family: Tahoma, Helvetica, sans-serif;
        font-weight: bold;
        border-bottom: 1px solid rgba(26, 16, 8, 0.8); /* Apakšējā līnija */
        padding-bottom: 10px; /* Atstarpe apakšā */
        margin: 30px 0 20px; /* Ārējās atstarpes */
    }

    .empty-message {
        text-align: center; /* Centrēts teksts */
        padding: 40px 0; /* Augšējā un apakšējā atstarpe */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        font-style: italic; /* Kursīvs stils */
        font-size: 1rem; /* Fonta izmērs */
    }

    .books-grid {
        display: grid; /* Grid izkārtojums */
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); /* Elastīgas kolonnas */
        gap: 30px; /* Atstarpes starp kartītēm */
        margin-bottom: 50px;
    }

    .book-card {
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmale */
        background-color: #e4a27c; /* Fona krāsa */
        border-radius: 8px; /* Noapaļoti stūri */
        box-shadow: 0 6px 15px rgba(63, 31, 4, 0.8); /* Ēna */
        overflow: hidden; /* Pārplūdes slēpšana */
        transition: all 0.3s ease; /* Pārejas efekts */
    }

    .book-card:hover {
        transform: translateY(-5px); /* Paceļas nedaudz augstāk */
        box-shadow: 0 8px 20px rgba(63, 31, 4, 0.8);
    }

    .book-content {
        flex: 1;
        padding: 25px;
        display: flex;
        flex-direction: column; /* Vertikāls saturs */
        height: 100%;
    }

    .book-title {
        font-size: 1.1rem;
        font-weight: bold;
        margin-bottom: 15px;
        text-align: center;
        color: rgba(106, 51, 0, 0.8);
        font-family: Tahoma, Helvetica, sans-serif;
        line-height: 1.3; /* Rindu augstums */
    }

    .book-description {
        color: rgba(26, 16, 8, 0.62);
        margin-bottom: 20px;
        font-size: 1rem;
    }

    .book { /* Papildu teksta stils */
        font-size: 1rem;
        color: rgba(26, 16, 8, 0.8);
    }

    .book-info {
        margin-bottom: 12px;
        display: flex;
        text-align: left; /* Kreisais līdzinājums */
    }

    .info-label {
        font-weight: bold;
        margin-right: 8px;
        color: rgba(26, 16, 8, 0.8);
        min-width: 100px; /* Minimālais platums */
        flex-shrink: 0; /* Neļauj sašaurināties */
    }

    .rating-badge {
        display: inline-block; /* Blakus elementi */
        padding: 3px 10px; /* Iekšējā atstarpe */
        background-color: #ffd9c6;
        color: rgba(26, 16, 8, 0.8);
        box-shadow: 0 2px 4px rgba(63, 31, 4, 0.8);
        border-radius: 15px;
        font-size: 0.85rem;
        margin-left: -10px;
    }

    .book-genres {
        margin-top: 0;
    }

    .genres-list {
        display: flex;
        flex-wrap: wrap; /* Aptin vairākās rindās */
        gap: 8px; /* Atstarpe starp žetoniem */
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
        cursor: pointer; /* Kursoru maina uz roku */
        transition: all 0.3s ease;
        text-align: center;
        font-family: Tahoma, Helvetica, sans-serif;
        font-size: 1rem;
    }

    .read-btn:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;
    }

    .header-with-filter {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .filter-btn {
        padding: 8px 16px;
        background-color: #c58667;
        border: 2px solid rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: Tahoma, Helvetica, sans-serif;
    }

    .filter-btn:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;
    }

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-content {
        background-color: #e4a27c;
        padding: 25px;
        border-radius: 8px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    }

    .modal-content h3 {
        margin-top: 0;
        color: rgba(26, 16, 8, 0.8);
        text-align: center;
    }

    .filter-group {
        margin-bottom: 15px;
    }

    .filter-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: rgba(26, 16, 8, 0.8);
    }

    .filter-group select {
        width: 100%;
        padding: 8px;
        border: 1px solid rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        background-color: #ffd9c6;
    }

    /* Добавляем стили для рейтингов аналогично жанрам */
    .rating-options {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 8px;
    }

    .rating-checkbox {
        background-color: #ffd9c6;
        padding: 8px 12px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
        border: 1px solid rgba(26, 16, 8, 0.3);
    }

    .rating-checkbox.selected {
        background-color: #c58667;
        color: white;
        border-color: rgba(26, 16, 8, 0.8);
    }

    .rating-checkbox:hover {
        background-color: #e4a27c;
    }

    .genre-options {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 8px;
    }

    .genre-checkbox {
        background-color: #ffd9c6;
        padding: 8px 12px;
        border-radius: 4px;
        cursor: pointer;
        transition: 0.3s;
        font-size: 1rem;
    }


    .genre-checkbox.selected {
        background-color: #ffc8a9;
    }


    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .modal-actions button {
        padding: 8px 16px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .cancel-btn {
        background-color: #f1f1f1;
        border: 1px solid #ccc;
    }

    .reset-btn {
        background-color: #ffd9c6;
        border: 1px solid rgba(26, 16, 8, 0.8);
    }

    .apply-btn {
        background-color: #c58667;
        border: 1px solid rgba(26, 16, 8, 0.8);
        color: white;
    }

    @media (max-width: 600px) {

        .library-title {
            font-size: 1.5rem;
        }

        .section-title {
            font-size: 1rem;
        }

        .empty-message {
            font-size: 0.9rem;
        }

        .books-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .book-content {
            padding: 25px;
            display: flex;
            flex-direction: column;
            height: 100%;
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
            padding: 3px 8px;
            font-size: 0.75rem;
        }

        .genres-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .read-btn {
            padding: 8px;
            font-size: 0.9rem;
        }

    }
</style>
