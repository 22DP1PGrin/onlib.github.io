<script setup>
import { defineProps} from 'vue'
import { router } from '@inertiajs/vue3'
import { route } from "ziggy-js";
import Navbar from "@/Components/Navbar.vue";
import Footer from "@/Components/Footer.vue";

// Definē komponenta ivaddatus
const props = defineProps({
    user: Object,
    books: Array, // Lietotāja grāmatu masīvs
    classicBooks: Array, // Klasisko grāmatu masīvs
    pageTitle: String, // Lapas virsraksts
    activeTab: Number, // Aktīvās cilnes ID
    viewingUserId: Number
})

const userId = props.viewingUserId;

//Aprēķina grāmatas vērtējumu
const getBookRating = (book) => {
    const average = parseFloat(book.ratings_avg_grade) || 0; // Pārveido par skaitli vai 0
    const count = book.ratings_count || 0; // Vērtējumu skaits vai 0
    return {
        average: average.toFixed(1), // Noapaļo uz 1 zīmi aiz komata
        count: count
    }
}


//Formatē vērtējumu skaitu ar pareiziem locījumiem
const formattedRatingsCount = (count) => {
    if (count === 1) return `${count} atsauksme`;
    if (count > 1 && count < 10) return `${count} atsauksmes`;
    return `${count} atsauksmju`;
}

// Cilņu definīcijas
const tabs = [
    { id: 1, title: 'Izlasītās grāmatas', typeId: 1 },
    { id: 2, title: 'Grāmatas lasāmas', typeId: 2 },
    { id: 3, title: 'Pamestas grāmatas', typeId: 3 },
    { id: 4, title: 'Plānotās grāmatas', typeId: 4 }
]


//Pārslēdz starp cilnēm
const switchTab = (tabId) => {
    const tab = tabs.find(t => t.id === tabId);
    if (!userId) {
        console.warn('viewingUserId is missing!');
        return;
    }
    if (tab) {
        router.get(route('bookmarks.user', { userId, typeId: tab.typeId }));
    }
}


//Navigācija uz klasiskās grāmatas lasīšanas lapu
const GoToReadClassic = (bookId) => {
    router.get(route('ClassicRead', { id: bookId }));
};


//Navigācija uz lietotāja grāmatas lasīšanas lapu
const GoToReadStory = (UserbookId) => {
    router.get(route('UserRead', { id: UserbookId }));
};
</script>

<template>

    <Navbar />
    <div class="page-wrapper">
        <div class="library-container">
            <div class="library-header">
                <h1 class="library-title">{{ pageTitle }}</h1>
            </div>
            <div class="tabs-container">
                <button
                    v-for="tab in tabs"
                    :key="tab.id"
                    @click="switchTab(tab.id)"
                    :class="{ 'active-tab': activeTab === tab.id }"
                    class="tab-button"
                >
                    {{ tab.title }}
                </button>
            </div>

            <!-- Klasisko grāmatu sadaļa -->
            <section class="book-section" v-if="classicBooks.length">
                <h2 class="section-title">Klasiskās grāmatas</h2>
                <div class="books-grid">
                    <div v-for="book in classicBooks" :key="book.id" class="book-card">
                        <div class="book-content">

                            <!-- Grāmatas nosaukums -->
                            <h3 class="book-title">{{ book.name }}</h3>

                            <!-- Grāmatas apraksts -->
                            <p class="book-description">{{ book.description }}</p>

                            <div class="book-info">
                            <span class="book">
                                <strong>Vērtējums: </strong>
                                {{ getBookRating(book).average }}<span class="fastar">★</span>
                                ({{ formattedRatingsCount(getBookRating(book).count) }})
                            </span>
                            </div>

                            <!-- Vecuma ierobežojuma informācija -->
                            <div class="book-info">
                                <span class="info-label">Vecuma ierobežojums: </span>
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

            <!-- Lietotāja grāmatu sadaļa -->
            <section class="book-section" v-if="books.length">
                <h2 class="section-title">Lietotāju grāmatas</h2>
                <div class="books-grid">
                    <div v-for="book in books" :key="book.id" class="book-card">
                        <div class="book-content">
                            <div v-if="book.current_bookmark.name" class="bookmark-badge">
                                {{ book.current_bookmark.name }}
                            </div>
                            <!-- Grāmatas nosaukums -->
                            <h2 class="book-title">{{ book.name }}</h2>

                            <!-- Grāmatas apraksts -->
                            <p class="book-description">{{ book.description }}</p>

                            <div class="book-info">
                            <span class="book">
                                <strong>Vērtējums: </strong>
                                {{ getBookRating(book).average }}<span class="fastar">★</span>
                                ({{ formattedRatingsCount(getBookRating(book).count) }})
                            </span>
                            </div>

                            <!-- Vecuma ierobežojuma informācija -->
                            <div class="book-info">
                                <span class="info-label">Vecuma ierobežojums: </span>
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
            <div v-if="books.length === 0 && classicBooks.length === 0" class="empty-message">
                <p>Nav pievienotu grāmatu vai stāstu</p>
            </div>
        </div>
        <Footer />
    </div>
</template>

<style scoped>

.tabs-container {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-bottom: 30px;
    padding-bottom: 10px;
}
.tab-button {
    padding: 5px 15px;
    background-color: #c58667;
    border: 2px solid rgba(26, 16, 8, 0.8);
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    transition: all 0.3s ease;
    font-family: Tahoma, Helvetica, sans-serif;
}

.active-tab {
    background-color: #ffc8a9;
    border-color: #ffc8a9;
    font-weight: bold;
}

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

.library-header {
    display: flex; /* Izmanto flex izkārtojumu */
    flex-direction: column; /* Elementus novieto vertikāli */
    align-items: center; /* Centrē elementus horizontāli */
    margin-bottom: 30px; /* Apakšējā atstarpe */
}

.library-title {
    font-size: 1.7rem;
    margin: 32px 0 20px; /* Ārējās atstarpes */
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
    display: flex;
    justify-content: center;
    align-items: center; /* Centrēts teksts */
    padding: 20px;
    margin: 20px auto;
    color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
    font-size: 1.1rem; /* Fonta izmērs */
    background-color: #e4a27c;
    border: 1px solid rgba(26, 16, 8, 0.8); /* Pogas apmale */
    border-radius: 8px;
    text-align: center;
    width: 50%;
    box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 10px;
    font-weight: bold;
}

.books-grid {
    display: grid; /* Grid izkārtojums */
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); /* Elastīgas kolonnas */
    gap: 30px; /* Atstarpes starp kartītēm */
    margin-bottom: 50px;
}

.bookmark-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    color: rgba(26, 16, 8, 0.8);
    padding: 3px 10px; /* Iekšējā atstarpe */
    background-color: #ffd9c6;
    box-shadow: 0 2px 4px rgba(63, 31, 4, 0.8);
    border-radius: 15px;
    font-size: 0.85rem;
    transition: all 0.3s;

}

.book-card {
    border: 1px solid rgba(26, 16, 8, 0.8); /* Apmale */
    background-color: #e4a27c; /* Fona krāsa */
    border-radius: 8px; /* Noapaļoti stūri */
    box-shadow: 0 6px 15px rgba(63, 31, 4, 0.8); /* Ēna */
    overflow: hidden; /* Pārplūdes slēpšana */
    transition: all 0.3s ease; /* Pārejas efekts */
    position: relative;
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
    word-wrap: break-word;
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

button:hover {
    background-color: #ffc8a9;
    border-color: #ffc8a9;
}

.modal-content .fa{
    transition: all 0.3s ease;
    font-size: 1rem;
}

.modal-content .fa:hover{
    color: #ffc8a9;
}

/* Modālā loga virsraksts */
.modal-content h3 {
    margin-top: 0; /* Bez augšējās atstarpes */
    color: rgba(26, 16, 8, 0.8);
    text-align: center;
    font-size: 1.1rem;
    font-weight: bold; /* Treknraksts */
    margin-bottom: 10px;
}

/* Filtru grupas konteiners */
.filter-group {
    margin-bottom: 12px;
}

/* Filtru grupu nosaukumi */
.filter-group label {
    display: block; /* Bloka elements */
    margin-bottom: 5px;
    font-weight: bold;
    color: rgba(26, 16, 8, 0.8);
    font-size: 1.1rem;
}

/* Izvēlņu lauki */
.filter-group select {
    width: 100%; /* Pilns platums */
    padding: 8px;
    border: 1px solid rgba(26, 16, 8, 0.8);
    border-radius: 4px;
    background-color: white;
}

/* Fokusēta izvēlne */
.filter-group select:focus {
    outline: none;
    box-shadow: none;
    background-color: #ffc8a9;
}

.fastar{
    font-size: 1.2rem;
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

    .fastar{
        font-size: 1.1rem;
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

    .tab-button {
        padding: 2px 10px;
        font-size: 0.9rem;
    }
    .tabs-container {
        gap: 8px;

    }

}
</style>
