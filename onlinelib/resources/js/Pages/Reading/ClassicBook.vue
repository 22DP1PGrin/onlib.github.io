<script setup>
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";
    import {router} from "@inertiajs/vue3";
    import {route} from "ziggy-js";

    // Definējam saņemtos datus
    const props = defineProps({
        book: {
            type: Object,
            default: () => ({})
        },
        genres: {
            type: Array,
            default: () => []
        },
        chapters: {
            type: Array,
            default: () => []
        },
    });

    const bookName = props.book?.name;
    const bookDescription = props.book?.description;
    const bookAgeLimit = props.book?.age_limit;
    const Author_name = props.book?.Author_name;
    const Author_surname = props.book?.Author_surname;
    const bookYear = props.book?.Year_publication;
    const bookId = props.book?.id;

    const GoToReadClassic = (bookId, chapterId) => {
        router.get(route('ClassicContent', {
            bookId: bookId,
            chapterId: chapterId
        }));
    };

</script>

<template>
    <Navbar />

    <div class="main-content">

        <div class="book-header">
            <!-- Grāmatas nosaukums -->
            <h1>{{ bookName }}</h1>

            <div class="book-meta">
                <!-- Autora informācija -->
                <div class="author-info">
                    <span class="author-name">{{ Author_name }} {{ Author_surname }}</span>
                </div>

                <div class="meta-items">
                    <span class="meta-item">{{ bookYear }}.gads</span>
                    <span class="meta-item">{{ bookAgeLimit }}</span>

                </div>
            </div>
        </div>


        <div class="book-container">
            <!-- Apraksta sadaļa -->
            <h2>Par stāstu</h2>
            <div class="book-description">
                <p>{{ bookDescription }}</p>
            </div>

            <!-- Žanru sadaļa -->
            <div class="book-genres">
                <h2>Žanri</h2>
                <div class="genre-list">
                    <!-- Atkārtojas katram žanram -->
                    <span v-for="genre in genres" :key="genre.id" class="genre-tag">
                        {{ genre.name }}
                    </span>
                </div>
            </div>

            <!-- Nodaļu sadaļa -->
            <div class="book-chapters">
                <h2>Nodaļas</h2>

                <div class="chapters-list">
                    <!-- Paziņojums, ja nav nodaļu -->
                    <div v-if="chapters.length === 0" class="empty-chapters">
                        Stāstam vēl nav pievienotu nodaļu.
                    </div>

                    <div v-else class="chapter-item" v-for="chapter in chapters" :key="chapter.id">
                        <div class="chapter-content">
                            <!-- Nodaļas nosaukums -->
                            <h3 class="chapter-title">
                                {{ chapter.name }}
                            </h3>
                        </div>

                        <div class="chapter-actions">
                            <button  @click="GoToReadClassic(bookId, chapter.id)">Lasīt</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Footer />
</template>

<style scoped>

    .main-content {
        padding: 20px; /* Iekšējās atkāpes visapkārt */
        max-width: 800px; /* Maksimālais platums */
        margin: 0 auto; /* Centrs lapā */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        font-family: Tahoma, Helvetica, sans-serif; /* Fontu ģimene */
    }

    .book-container {
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmale  */
        background-color: #e4a27c; /* Fona krāsa */
        border-radius: 8px; /* Noapaļoti stūri */
        box-shadow: 0 6px 15px rgba(63, 31, 4, 0.8); /* Ēna */
        padding: 30px; /* Iekšējā atkāpe */
        margin-bottom: 40px; /* Apakšējā ārējā atkāpe */
    }

    .book-header {
        margin-top: 32px; /* Augšējā ārējā atkāpe */
        margin-bottom: 30px; /* Apakšējā ārējā atkāpe */
        text-align: center; /* Teksts centrēts */
    }

    .book-header h1 {
        color: rgba(26, 16, 8, 0.8);
        font-size: 1.7rem; /* Fonta izmērs */
        margin-bottom: 20px;
        font-weight: bold; /* Treknraksts */
    }

    .book-meta {
        display: flex; /* Flex izkārtojums */
        flex-direction: column; /* Vertikāls izkārtojums */
        align-items: center;
        gap: 15px; /* Starpība starp elementiem */
        margin-top: -10px;
    }

    .author-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .author-name {
        font-weight: bold;
        color: rgba(106, 51, 0, 0.8);
        font-size: 1rem;
    }

    .meta-items {
        display: flex;
        flex-wrap: wrap; /* Aplauzt rindas */
        justify-content: center; /* Centrēšana horizontāli */
        gap: 15px;
        font-size: 1rem;
        margin-top: -3px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .book-description {
        padding: 20px;
        background-color: #ffd9c6;
        border-radius: 8px;
        line-height: 1.7;
        box-shadow: 0 2px 4px rgba(63, 31, 4, 0.8);
        font-size: 1rem;
    }

    h2 {
        font-size: 1.1rem;
        font-weight: bold;
        color: rgba(26, 16, 8, 0.8);
        margin-bottom: 10px;
    }

    .book-genres {
        margin: 30px 0;
    }

    .genre-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .genre-tag {
        padding: 6px 12px;
        background-color: #ffd9c6;
        color: rgba(26, 16, 8, 0.8);
        box-shadow: 0 2px 4px rgba(63, 31, 4, 0.8);
        border-radius: 20px; /* Apaļi stūri */
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .book-chapters {
        margin-top: 40px;
    }

    .chapters-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .empty-chapters {
        background-color: #ffd9c6;
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 10px;
        text-align: center;
        padding: 30px;
        color: rgba(106, 51, 0, 0.8);
        font-weight: bold;
        border-radius: 8px;
        font-size: 1.1rem;
    }

    .chapter-item {
        display: flex;
        padding: 15px 20px;
        background-color: #ffd9c6;
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 10px;
        border-radius: 8px;
        justify-content: center;
        align-items: center;
    }

    .chapter-content {
        flex: 1; /* Aizņem visu iespējamo vietu */
    }

    .chapter-title {
        font-size: 1.1rem;
        margin-bottom: 5px;
        color: rgba(106, 51, 0, 0.8);
        font-weight: bold;
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

    button:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;
    }

    @media (max-width: 768px) {
        .book-container {
            padding: 20px;
        }

        .meta-items {
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .chapter-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .chapter-actions {
            width: 100%;
        }

        .chapter-actions button {
            width: 100%;
        }
    }

    @media (max-width:500px) {

        .book-header h1 {
            font-size: 1.5rem;
        }


        .author-name {
            font-size: 0.9rem;
        }

        .meta-items {
            font-size: 0.9rem;
        }


        .book-description {
            font-size: 0.9rem;
        }

        h2 {
            font-size: 1rem;
        }

        .genre-tag {
            padding: 5px 10px;
            font-size: 0.8rem;
        }

        .empty-chapters {
            font-size: 1rem;
        }


        .chapter-title {
            font-size: 1rem;
        }

        .chapter-meta {
            font-size: 0.8rem;
        }

        button {
            font-size: 0.9rem;
            padding: 3px 17px;
        }
    }
</style>
