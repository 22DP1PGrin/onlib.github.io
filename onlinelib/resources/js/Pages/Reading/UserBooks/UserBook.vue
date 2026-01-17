<script setup>
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";
    import {router} from "@inertiajs/vue3";
    import {route} from "ziggy-js";
    import {computed, ref} from "vue";

    // Definējam saņemtos datus
    const props = defineProps({
        book: {
            type: Object,
            default: () => ({})
        },
        user: {
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
        initialUserBookmark: {
            type: Object,
            default: null
        },
        initialAverageRating: Number,
        initialRatingsCount: Number,
        initialUserRating: Number
    });

    const bookName = props.book?.name;
    const bookDescription = props.book?.description;
    const bookAgeLimit = props.book?.age_limit;
    const bookId = props.book?.id;
    const bookStatus= props.book?.status;


    // Definē vērtējumu stāvokļa mainīgos
    const averageRating = ref(props.initialAverageRating); // Vidējais vērtējums
    const ratingsCount = ref(props.initialRatingsCount); // Vērtējumu skaits
    const userRating = ref(props.initialUserRating); // Lietotāja vērtējums
    const showModal = ref(false); // Modalā loga redzamība
    const tempRating = ref(userRating.value || 0); // Pagaidu vērtējums
    const bookmarkTypes = ref([
        { id: 1, name: 'Izlasīts' },
        { id: 2, name: 'Lasu' },
        { id: 3, name: 'Pamests' },
        { id: 4, name: 'Plānots' }
    ]);

    const currentBookmark = ref(props.initialUserBookmark);
    const showBookmarkModal = ref(false);

    const handleBookmark = async (bookmarkTypeId) => {
        try {
            // Pārbauda, vai grāmatzīme jau ir pievienota ar šādu tipu
            if (currentBookmark.value?.id === bookmarkTypeId) {
                // Dzēš esošo grāmatzīmi
                await axios.delete(route('bookmarks.remove', { bookId: bookId }));
                currentBookmark.value = null;
            } else {
                // Pievieno jaunu grāmatzīmi vai atjaunina esošo
                const response = await axios.post(route('bookmarks.add'), {
                    book_id: bookId,          // Grāmatas ID
                    bookmark_type_id: bookmarkTypeId,  // Grāmatzīmes tipa ID
                    is_classic: false          // Norāda, ka tā ir lietotāja grāmata
                });
                // Saglabā atbildē saņemto grāmatzīmes informāciju
                currentBookmark.value = response.data.bookmark;
            }
            // Aizver grāmatzīmju modālo logu
            showBookmarkModal.value = false;
        } catch (error) {
            // Ja lietotājs nav autorizējies, novirza uz autorizācijas lapu
            if (error.response?.status === 401) {
                window.location.href = route('login');
            }
            // Varētu pievienot papildu kļūdu apstrādi šeit
        }
    };

    const submitRating = async () => {
        try {
            console.log('Iesniedzams vērtējums:', { bookId, grade: tempRating.value });

            // Nosūta POST pieprasījumu uz serveri, lai saglabātu vērtējumu
            const response = await axios.post(route('user-books.rate', {
                book: bookId
            }), {
                grade: tempRating.value
            });

            // Ja atbilde ir veiksmīga, atjaunina datus
            if (response.data.success) {
                averageRating.value = response.data.averageRating;
                ratingsCount.value = response.data.ratingsCount;
                userRating.value = response.data.userRating;
                alert('Vērtējums veiksmīgi saglabāts!');
                showModal.value = false; // Aizver modalā logu
            } else {
                console.error('Servera kļūda:', response.data.message);
            }
        } catch (error) {
            if (error.response?.status === 401) {
                window.location.href = route('login');
            }
        }
    };

    // Aprēķina formatētu vērtējumu skaitu (piemēram, "1.5k", ja vairāk par 1000)
    const formattedRatingsCount = computed(() => {
        if (ratingsCount.value >= 1000) {
            return (ratingsCount.value / 1000).toFixed(1) + 'k';
        }
        return ratingsCount.value;
    });


    const GoToRead = (bookId, chapterId) => {
        router.visit(route('chapter.content', {
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
                    <span class="author-name">{{ user.nickname }}</span>
                </div>

                <div class="meta-items">
                    <span class="meta-item">{{ bookStatus }}</span>
                    <span class="meta-item">{{ bookAgeLimit }}</span>

                </div>

                <button
                    @click="showBookmarkModal = true"
                    class="bookmark-button"
                >
                    {{ currentBookmark ? currentBookmark.name : 'Pievienot grāmatzīmēm' }}
                </button>

                <!-- Grāmatzīmju izvēlnes modālais logs -->
                <div v-if="showBookmarkModal" class="modal-overlay" @click.self="showBookmarkModal = false">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Grāmatzīmes</h3>
                            <button class="close-button" @click="showBookmarkModal = false">
                                <i class="fa">&#xf00d;</i>
                            </button>
                        </div>

                        <div class="bookmark-options">
                            <div
                                v-for="type in bookmarkTypes"
                                :key="type.id"
                                class="bookmark-option"
                                :class="{'active': currentBookmark?.id === type.id }"
                                @click="handleBookmark(type.id)"
                            >
                                {{ type.name }}
                                <span v-if="currentBookmark?.id === type.id" class="remove-hint"><i class="fa">&#xf00d;</i></span>
                            </div>
                        </div>
                    </div>
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

            <!-- Grāmatas vērtējuma sadaļa -->
            <div class="book-grade">
                <h2>Vērtējums:</h2>
                <div class="rating-container">
                    <!-- Vidējā vērtējuma rādītājs -->
                    <div class="average-rating">
                        <span class="rating-value">
                            {{ averageRating.toFixed(1)}}<span class="fastar">★</span>
                        </span>
                        <span class="rating-count">
                            ({{ formattedRatingsCount}} atsauksmes)
                        </span>
                    </div>
                    <!-- Poga vērtējuma pievienošanai (ja lietotājs vēl nav novērtējis) -->
                    <button
                        @click="showModal = true"
                        class="rate-button"
                        v-if="!userRating"
                    >
                        Novērtēt
                    </button>

                    <!-- Lietotāja vērtējuma rādītājs (ja ir novērtējis) -->
                    <div v-else class="user-rating">
                        Jūsu vērtējums:
                        <span class="star">
                            {{userRating}}<span class="fastar">★</span>
                        </span>
                        <button @click="showModal = true" class="edit-rating">
                            Mainīt
                        </button>
                    </div>
                </div>
            </div>

            <!-- Vērtēšanas modālais logs -->
            <div v-if="showModal" class="modal-overlay" @click.self="showModal = false">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Novērtējiet grāmatu</h3>
                        <!-- Aizvēršanas poga -->
                        <button class="close-button" @click="showModal = false">
                            <i class="fa">&#xf00d;</i>
                        </button>
                    </div>

                    <!-- Zvaigžņu vērtēšanas sadaļa -->
                    <div class="rating-stars">
                        <span
                            v-for="star in 5"
                            :key="star"
                            class="star"
                            :class="{ 'filled': star <= tempRating }"
                            @click="tempRating = star"
                            @mouseover="tempRating = star"
                        >
                            ★
                        </span>
                    </div>

                    <!-- Modālā loga darbību poga -->
                    <div class="modal-actions">
                        <button
                            @click="submitRating"
                            class="submit-button"
                            :disabled="tempRating === 0"
                        >
                            Saglabāt
                        </button>
                    </div>
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
                            <button  @click="GoToRead(bookId, chapter.id)">Lasīt</button>
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

    .bookmark-button {
        padding: 4px 15px;
    }


    .bookmark-options {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .bookmark-option {
        padding: 10px 15px;
        background-color: #ffd9c6;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 2px 4px rgba(63, 31, 4, 0.8);
    }

    .bookmark-option.active {
        background-color: #ffc8a9;
    }

    .remove-hint .fa:hover{
        color: #ffd9c6 !important;
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
        word-wrap: break-word;
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

    .rating-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .average-rating {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .rating-value,
    .rating-count{
        font-size: 1rem;
    }

    .rate-button{
        padding: 5px 20px;
        width: 15%;
    }

    .user-rating {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .edit-rating {
        padding: 3px 10px;
        width: 10%;
    }

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(26, 16, 8, 0.43);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-content {
        background-color: #e4a27c; /* Fona krāsa */
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmale  */
        padding: 2rem;
        border-radius: 8px;
        width: 100%;
        max-width: 400px;
    }

    .modal-header {
        display: flex;
        justify-content: center;
        text-align: center;
        align-items: center;
        margin-bottom: 1rem;
        position: relative;
    }

    .close-button {
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        color: rgba(26, 16, 8, 0.8);
        transition: all 0.3s ease;
        position: absolute;
        left: 100%;
        bottom: 80%;
    }

    .close-button:hover {
        background-color: #e4a27c; /* Fona krāsa */
        border:none
    }

    .modal-content .fa{
        transition: all 0.3s ease;
        font-size: 1rem;
    }

    .fastar{
        font-size: 1.2rem;
    }
    .modal-content .fa:hover{
        color: #ffc8a9;
    }

    .modal-content h3{
        font-size: 1.1rem;
        font-weight: bold;
        text-align: center;
    }

    .rating-stars {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin: 1.3rem 0;
    }

    .rating-stars .star {
        font-size: 2.5rem;
        color: rgba(26, 16, 8, 0.8);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .rating-stars .star.filled {
        color: #ffc8a9;
    }

    .modal-actions {
        display: flex;
        justify-content: center;
        gap: 1rem;
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

        button {
            font-size: 0.9rem;
            padding: 3px 17px;
        }

        .rating-value,
        .rating-count{
            font-size: 0.9rem;
        }

        .rate-button{
            padding: 3px 15px;
            font-size: 0.9rem;
            width: 30%;
        }

        .edit-rating {
            padding: 2px 8px;
            width: 20%;
            font-size: 0.9rem;
        }

        .user-rating {
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .fastar{
            font-size: 1.1rem;
        }

        .modal-content {
            padding: 1.8rem;
            width: 100%;
            max-width: 350px;
        }

        .modal-content .fa{
            font-size: 0.9rem;
        }
        .modal-content h3{
            font-size: 1rem;
        }

        .rating-stars .star {
            font-size: 1.8rem;
        }

        .modal-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
    }
</style>
