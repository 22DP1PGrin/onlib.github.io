<script setup>
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";
    import {router, useForm} from "@inertiajs/vue3";
    import {route} from "ziggy-js";
    import {computed, ref} from "vue";
    import axios from 'axios'
    import CommentsSection from "@/Components/Comments/CommentsSection.vue";
    import ConfirmModal from "@/Components/Modal/ConfirmModal.vue";
    import SuccessModal from "@/Components/Modal/SuccessModal.vue";
    import FormModal from "@/Components/Modal/FormModal.vue";

    // Definējam saņemtos datus
    const props = defineProps({
        book: {
            type: Object,
            default: () => ({})
        },
        comments: {
            type: Array,
            default: () => []
        },
        commentsCount: {
            type: Number,
            default: 0
        },
        authUser: {
            type: Object,
            default: null
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

    // Modālo logu stāvokļi
    const showDeleteModal = ref(false)
    const showDeleteSuccessModal = ref(false)
    const showReportModal = ref(false)
    const showBookmarkModal = ref(false)
    const showModal = ref(false)
    const showSuccessModal = ref(false)
    const showRatingSuccessModal = ref(false)
    const showBookmarkSuccessModal = ref(false)

    // Atlasītie elementi
    const selectedComment = ref(null);
    const selectedBook = ref(null);
    const reportType = ref(null);
    const commentToDelete = ref(null)

    // Grāmatas dati
    const bookId = props.book?.id;
    const bookName = props.book?.name;
    const is_blocked = props.book?.is_blocked;
    const bookDescription = props.book?.description;
    const bookAgeLimit = props.book?.age_limit;
    const bookStatus = props.book?.status;

    // Vērtējumi
    const averageRating = computed(() => props.initialAverageRating);
    const ratingsCount = computed(() => props.initialRatingsCount);
    const userRating = computed(() => props.initialUserRating);
    const tempRating = ref(userRating.value || 0);
    const ratingForm = useForm({ grade: tempRating.value });

    // Grāmatzīmes
    const bookmarkTypes = ref([
        { id: 1, name: 'Izlasīts' },
        { id: 2, name: 'Lasu' },
        { id: 3, name: 'Pamests' },
        { id: 4, name: 'Plānots' }
    ]);
    const currentBookmark = ref(props.initialUserBookmark);

    // Ziņošanas forma
    const reportForm = useForm({
        subject: '',
        problem: '',
    });

    // Atver dzēšanas apstiprinājuma modālo logu
    const openDeleteModal = (comment) => {
        commentToDelete.value = comment;
        showDeleteModal.value = true;
    };

    // Atver ziņošanas modālo logu par komentāru
    const openCommentReport = (comment) => {
        reportType.value = 'comment'
        selectedComment.value = comment
        showReportModal.value = true
    }

    // Atver ziņošanas modālo logu par grāmatu
    const openBookReport = () => {
        reportType.value = 'book'
        selectedBook.value = props.book
        showReportModal.value = true
    }

    // Atver modālo logu grāmatzīmes izvēlei
    const openBookmarkModal = () => {
        document.body.style.overflow = "hidden";
        showBookmarkModal.value = true;
    };

    // Atver vērtēšanas modālo logu
    const openRatingModal = () => {
        document.body.style.overflow = "hidden";
        showModal.value = true;
    };

    // Aizver visas logas
    const closeAllModal = () => {
        document.body.style.overflow = "";
        showModal.value = false;
        showBookmarkModal.value = false;
    }

    // Grāmatzīmju pievienošana
    const handleBookmark = async (bookmarkTypeId) => {
        try {
            if (currentBookmark.value?.id === bookmarkTypeId) {
                await axios.delete(route('bookmarks.remove', { bookId: bookId }));
                currentBookmark.value = null;
            } else {
                const response = await axios.post(route('bookmarks.add'), {
                    book_id: bookId,
                    bookmark_type_id: bookmarkTypeId,
                    is_classic: false
                });
                currentBookmark.value = response.data.bookmark;
            }
            showBookmarkModal.value = false;
            showBookmarkSuccessModal.value = true;
        } catch (error) {
            if (error.response?.status === 401) {
                window.location.href = route('login');
            }
        }
    };

    // Vērtēšana
    const submitRating = () => {
        ratingForm.grade = tempRating.value;
        ratingForm.post(route('user-books.rate', { book: bookId }), {
            preserveScroll: true,
            onSuccess: () => {
                showModal.value = false;
                showRatingSuccessModal.value = true;
                router.reload({
                    only: ['book', 'initialAverageRating', 'initialRatingsCount', 'initialUserRating']
                });
            },
        });
    };

    // Formatēts vērtējumu skaits
    const formattedRatingsCount = computed(() => {
        if (ratingsCount.value >= 1000) {
            return (ratingsCount.value / 1000).toFixed(1) + 'tūkst.';
        }
        return ratingsCount.value;
    });

    // Apstiprina dzēšanu
    const confirmDelete = () => {
        if (!commentToDelete.value) return;

        router.delete(route('comments.delete', { id: commentToDelete.value.id }), {
            preserveScroll: true,
            onSuccess: () => {
                showDeleteModal.value = false
                showDeleteSuccessModal.value = true
                commentToDelete.value = null
            }
        });
    };

    // Apstiprina ziņošanu (grāmata vai komentārs)
    const submitReport = (data) => {
        let url = null;

        // Automātiski nosaka, vai ziņo par komentāru vai grāmatu
        if (reportType.value === 'book' && selectedBook.value) {
            url = route('report.user.book', { userBook: selectedBook.value.id });
        }

        if (reportType.value === 'comment' && selectedComment.value) {
            url = route('report.comment', { comment: selectedComment.value.id });
        }

        if (!url) return;

        reportForm.subject = data.subject
        reportForm.problem = data.problem

        reportForm.post(url, {
            preserveScroll: true,
            onSuccess: () => {
                reportForm.reset()
                showReportModal.value = false
                showSuccessModal.value = true
            }
        });
    };

    // Funkcija, lai pārvietotos uz klasiskās grāmatas lasīšanas lapu
    const GoToRead = (bookId, chapterId) => {
        router.visit(route('chapter.content', {
            bookId: bookId,
            chapterId: chapterId
        }));
    };
</script>
<template>
    <!-- Navigācijas josla -->
    <Navbar />

    <div class="main-content">

        <!-- Dzēšanas apstiprinājums -->
        <ConfirmModal
            :is-open="showDeleteModal"
            title="Vai tiešām vēlaties dzēst šo komentāru?"
            confirm-text="Dzēst"
            @confirm="confirmDelete"
            @cancel="showDeleteModal = false"
        />

        <!-- Veiksmīgas dzēšanas modālis -->
        <SuccessModal
            :is-open="showDeleteSuccessModal"
            title="Komentārs veiksmīgi dzēsts!"
            @close="showDeleteSuccessModal = false"
        />

        <!-- Veiksmīgas ziņošanas modālis -->
        <SuccessModal
            :is-open="showSuccessModal"
            title="Sūdzība veiksmīgi nosūtīta!"
            @close="showSuccessModal = false"
        />

        <!-- Veiksmīgas vērtēšanas modālis -->
        <SuccessModal
            :is-open="showRatingSuccessModal"
            title="Vērtējums veiksmīgi saglabāts!"
            @close="showRatingSuccessModal = false"
        />

        <!-- Veiksmīgas grāmatzīmes pievienošanas modālis -->
        <SuccessModal
            :is-open="showBookmarkSuccessModal"
            title="Izmaiņas veiksmīgi saglabātas!"
            @close="showBookmarkSuccessModal = false"
        />

        <!-- Ziņošanas modālis -->
        <FormModal
            :is-open="showReportModal"
            :title="reportType === 'comment' ? 'Vai tiešām vēlaties ziņot par šo komentāru?' : 'Vai tiešām vēlaties ziņot par šo darbu?'"
            message="Lūdzu, norādiet ziņošanu iemeslu, lai apstiprinātu."
            :fields="[
                { name: 'subject', label: 'Tēma', type: 'select', required: true,
                      options: [
                          { value: 'Maldinošs vai kaitīgs saturs', label: 'Maldinošs vai kaitīgs saturs' },
                          { value: 'Noteikumu pārkāpums', label: 'Noteikumu pārkāpums' },
                          { value: 'Spams vai reklāma', label: 'Spams vai reklāma' },
                          { value: 'Naida runa vai aizskarošs saturs', label: 'Naida runa vai aizskarošs saturs' },
                          { value: 'Zemas kvalitātes saturs', label: 'Zemas kvalitātes saturs' }
                      ]
                },
                { name: 'problem', label: 'Pamatojums', type: 'textarea', required: true, rows: 4 }
            ]"
            :errors="reportForm.errors"
            submit-text="Ziņot"
            @submit="submitReport"
            @close="showReportModal = false"
        />

        <div class="book-header">
            <!-- Grāmatas nosaukums -->
            <h1>{{ bookName }}</h1>

            <div class="book-meta">
                <!-- Autora informācija -->
                <div class="author-info">
                    <a
                        :href="route('other.users.watch', { id: book.user.id })"
                        class="author-name"
                    >
                        {{ book.user.nickname }}
                    </a>
                </div>

                <div class="meta-items">
                    <!-- Statusa informācija -->
                    <span class="meta-item">{{ bookStatus }}</span>
                    <!-- Vēcuma ierobēžojuma informācija -->
                    <span class="meta-item">{{ bookAgeLimit }}</span>

                </div>

                <!-- Poga pievenot grāmatzīmēm. Radas tikai tad, kad grāmata nav bloķēta -->
                <div v-if="!is_blocked">
                    <button
                        @click="openBookmarkModal"
                        class="bookmark-button"
                    >
                        {{ currentBookmark ? currentBookmark.name : 'Pievienot grāmatzīmei' }}
                    </button>
                </div>

                <!-- Paziņojums par bloķētu stāstu -->
                <div v-if="is_blocked" class="warning">
                    <h2><i style="font-size:24px" class="fa">&#xf071;</i>
                        Stāsts ir bloķēts drošības apsvērumu dēļ!</h2>

                    <div class="explain">
                        <div><h3>Bloķēšanas iemesls:</h3> {{ book.block.subject }}</div>
                        <div><h3>Pamatojums:</h3> {{ book.block.problem }}</div>
                    </div>

                </div>

                <!-- Grāmatzīmju izvēlnes modālais logs -->
                <div v-if="showBookmarkModal" class="modal-overlay" @click.self="showBookmarkModal = false">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Grāmatzīmes</h3>
                            <button class="close-button" @click="closeAllModal">
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

        <!-- Ziņošāna -->
        <div class="book-container">
            <div class="report-wrapper">
                <button class="report" @click="openBookReport(book)">
                    <i style="font-size:1rem" class="fa">&#xf071;</i> Ziņot
                </button>
            </div>

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
                        @click="openRatingModal"
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
                        <button @click="openRatingModal" class="edit-rating">
                            Mainīt
                        </button>
                    </div>
                </div>
            </div>

            <!-- Vērtēšanas modālais logs -->
            <div v-if="showModal" class="modal-overlay" @click.self="closeAllModal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Novērtējiet grāmatu</h3>
                        <!-- Aizvēršanas poga -->
                        <button class="close-button" @click="closeAllModal">
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

        <!-- Komentāri -->
        <CommentsSection
            :comments="comments"
            :comments-count="commentsCount"
            :auth-user="authUser"
            :book-id="book.id"
            comment-type="user"
            @open-delete-modal="openDeleteModal"
            @open-report="openCommentReport"
        />
    </div>
    <!-- Kājene -->
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

    h1  {
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
        color: rgba(26, 16, 8, 0.8) !important;
        font-size: 1rem;
        transition: color 0.3s;
    }

    .author-name:hover{
        color: rgba(106, 51, 0, 0.8) !important;
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
        background-color: #ffb18e;
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


    .reply-actions button{
        padding: 1px 6px;
    }

    .report-wrapper {
        display: flex;
        justify-content: flex-end;
    }

    .report{
        padding: 2px 15px;
        margin-left: auto;
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
        .report-wrapper .fa{
            font-size: 0.9rem;
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
