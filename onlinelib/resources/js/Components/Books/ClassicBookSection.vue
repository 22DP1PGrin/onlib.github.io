<script setup>
    import { router } from "@inertiajs/vue3";
    import { route } from "ziggy-js";
    import { computed, ref } from "vue";

    // Komponenta ievaddati
    const props = defineProps({
        classicBooks: {
            type: Array,
            required: true,
            default: () => []
        }
    });

    // Sākotnējais redzamo grāmatu skaits
    const initialLimit = 6;

    // Pašreizējais redzamo grāmatu limits
    const currentLimit = ref(initialLimit);

    // Ģeometriskās progresijas reizinātājs (katru reizi limits tiek palielināts 2x)
    const growthMultiplier = 2;

    // Vai ir izvērsts pilns skats
    const isExpanded = ref(false);

    // Vai tiek rādītas visas grāmatas
    const showAll = ref(false);

    // Aprēķina, cik grāmatas pašlaik tiek rādītas
    const visibleBooks = computed(() => {
        if (showAll.value) {
            return props.classicBooks;
        }
        return props.classicBooks.slice(0, currentLimit.value);
    });

    // Vai ir vēl grāmatas, ko ielādēt
    const hasMoreBooks = computed(() => {
        return currentLimit.value < props.classicBooks.length;
    });

    // Vai ir jārāda "Ielādēt vairāk" poga
    const showLoadMoreButton = computed(() => {
        return !showAll.value && hasMoreBooks.value;
    });

    // Funkcija ielādē vairāk grāmatu (palielina limitu ģeometriskā progresijā)
    const loadMore = () => {
        const newLimit = currentLimit.value * growthMultiplier;

        if (newLimit >= props.classicBooks.length) {
            // Ja jaunais limits pārsniedz vai ir vienāds ar kopējo grāmatu skaitu, parāda visas
            currentLimit.value = props.classicBooks.length;
            showAll.value = true;
        } else {
            // Pretējā gadījumā palielina limitu
            currentLimit.value = newLimit;
        }
        isExpanded.value = true;  // Atzīmē, ka saraksts ir izvērsts
    };

    // Funkcija slēpj visas papildus ielādētās grāmatas
    const collapse = () => {
        currentLimit.value = initialLimit;
        showAll.value = false;
        isExpanded.value = false;
    };

    // Funkcija aprēķina grāmatas vidējo vērtējumu un atsauksmju skaitu
    const getBookRating = (book) => {
        // Iegūst vidējo vērtējumu (noapaļo līdz 1 ciparam aiz komata)
        const avg = book.ratings_avg_grade !== null && book.ratings_avg_grade !== undefined
            ? parseFloat(book.ratings_avg_grade).toFixed(1)
            : '0.0';  // Ja nav vērtējumu, rāda 0.0

        // Iegūst atsauksmju skaitu vai 0, ja nav definēts
        const count = book.ratings_count || 0;

        // Atgriež objektu ar vidējo vērtējumu un atsauksmju skaitu
        return {
            average: avg,
            count: count
        };
    };

    // Funkcija formatē atsauksmju skaitu cilvēkam saprotamā formātā
    const formattedRatingsCount = (count) => {
        if (typeof count !== 'number' || isNaN(count)) {
            return '0';  // Ja nav skaitlis, atgriež 0
        }

        // Ja atsauksmes ir vairāk nekā 1000, formatē ar 'tukst.'
        if (count >= 1000) {
            return (count / 1000).toFixed(1) + 'tukst.';
        }

        return count.toString();
    };

    // Navigācija uz klasiskās grāmatas lasīšanas lapu
    const goToReadClassic = (bookId) => {
        router.get(route('ClassicRead', { id: bookId }));
    };
</script>

<template>
    <!-- Klasisko grāmatu sadaļa - tiek rādīta tikai ja ir grāmatas -->
    <section class="book-section" v-if="classicBooks.length">
        <h2 class="section-title">Klasiskās grāmatas</h2>

        <!-- Grāmatu saraksts -->
        <div class="books-grid">
            <!-- Atkārtojas katrai klasiskajai grāmatai, kas ir redzama -->
            <div v-for="book in visibleBooks" :key="book.id" class="book-card">
                <div class="book-content">
                    <!-- Grāmatzīmes nosaukums (ja lietotājam ir atzīmēta grāmatzīme) -->
                    <div v-if="book.current_bookmark?.name" class="bookmark-badge">
                        {{ book.current_bookmark.name }}
                    </div>

                    <!-- Grāmatas nosaukums -->
                    <h3 class="book-title">{{ book.name }}</h3>

                    <!-- Grāmatas apraksts -->
                    <p class="book-description">{{ book.description }}</p>

                    <!-- Vērtējuma informācijas bloks -->
                    <div class="book-info">
                        <span class="book">
                            <strong>Vērtējums: </strong>{{ getBookRating(book).average }}
                            <span class="fastar">★</span>
                            ({{ formattedRatingsCount(getBookRating(book).count) }} atsauksmes)
                        </span>
                    </div>

                    <!-- Vecuma ierobežojuma informācijas bloks -->
                    <div class="book-info">
                        <span class="info-label">Vecuma ierobežojums: </span>
                        <span class="rating-badge">{{ book.age_limit }}</span>
                    </div>

                    <!-- Autora informācijas bloks -->
                    <div class="book-info">
                        <span class="book">
                            <strong>Autors: </strong> {{ book.Author_name }} {{ book.Author_surname }}
                        </span>
                    </div>

                    <!-- Izdošanas gada informācijas bloks -->
                    <div class="book-info">
                        <span class="book">
                            <strong>Izdošanas gads: </strong> {{ book.Year_publication }}
                        </span>
                    </div>

                    <!-- Žanru informācijas bloks -->
                    <div class="book-genres">
                        <span class="info-label">Žanri:</span>
                        <div class="genres-list">
                            <!-- Katrs žanrs tiek attēlots kā atsevišķs badge -->
                            <span v-for="genre in book.genres" :key="genre.id" class="genre-badge">
                                {{ genre.name }}
                            </span>
                        </div>
                    </div>

                    <!-- Lasīšanas poga - navigācija uz grāmatas lasīšanas lapu -->
                    <button class="read-btn" @click="goToReadClassic(book.id)">Lasīt</button>
                </div>
            </div>
        </div>

        <!-- "Skatīt vairāk" / "Slēpt" pogu konteiners -->
        <div class="toggle-container" v-if="classicBooks.length > initialLimit">
            <button
                v-if="showLoadMoreButton"
                class="toggle-btn"
                @click="loadMore"
            >
                Skatīt vairāk
            </button>

            <button
                v-if="currentLimit > initialLimit"
                class="toggle-btn collapse-btn"
                @click="collapse"
            >
                Slēpt
            </button>
        </div>
    </section>
</template>
<style scoped>
    .toggle-container {
        margin-top: -20px;
        display: flex;
        justify-content: center;
        gap:10px;
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

    .books-grid {
        display: grid; /* Grid izkārtojums */
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); /* Elastīgas kolonnas */
        gap: 30px; /* Atstarpes starp kartītēm */
        margin-bottom: 50px;
    }

    .bookmark-badge {
        position: absolute;
        top: 5px;
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
        margin-top: 8px;
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

    button{
        border: 2px solid rgba(26, 16, 8, 0.8);
        background-color: #c58667; /* Fona krāsa */
        color: rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        font-size: 1.0rem;
        font-family: Tahoma, Helvetica, sans-serif;
        transition: background-color 0.3s, border-color 0.3s; /* Pārejas efekts, lai uzlabotu interaktivitāti */
        cursor: pointer; /* Kursora izmaiņas pie pogas */
        padding: 5px 10px;
    }

    button:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;
    }

    .fastar{
        font-size: 1.2rem;
    }

    @media (max-width: 600px) {
        .section-title {
            font-size: 1rem;
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

    }
</style>
