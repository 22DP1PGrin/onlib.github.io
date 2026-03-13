<script setup>
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";
    import {router, useForm} from "@inertiajs/vue3";
    import {route} from "ziggy-js";
    import {computed, nextTick, onMounted, ref, watch} from "vue";

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
    const showUserModal = ref(false);
    const showSuccessModal = ref(false);
    const showModal = ref(false);
    const showBookmarkModal = ref(false);
    const activeMenu = ref(null);
    const showDeleteModal = ref(false);
    const showDeleteSuccessModal = ref(false);
    const selectedComment = ref(null);
    const editingCommentId = ref(null);
    const editedContent = ref('');
    const reportType = ref(null);

    // Saņem pieprasījuma parametru 'comment' no URL
    const urlParams = new URLSearchParams(window.location.search)
    const commentId = urlParams.get('comment')

    // Novēro izmaiņas komentāru masīvā
    watch(
        () => props.comments,
        async () => {
            if (!commentId) return;

            // Gaida, līdz DOM tiek atjaunināts
            await nextTick();

            // Mēģina atrast komentāra elementu pēc ID
            let el = document.getElementById('comment-' + commentId);

            // Ja elements jau pastāv — tiek veiktas ritināšanas darbības
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            // Meklē komentāra vecāku, ja tas ir atbilde
            const parent = props.comments.find(c =>
                c.replies?.some(r => r.id == commentId)
            );

            if (parent) {
                // Atver visas atbildes konkrētā komentārā
                showAllReplies.value = {
                    ...showAllReplies.value,
                    [parent.id]: true
                };

                // Gaidām, līdz DOM tiek atjaunināts pēc atbilžu atvēršanas
                await nextTick();

                // Mēģina vēlreiz atrast komentāra elementu pēc ID
                el = document.getElementById('comment-' + commentId);

                if (el) {
                    // Veic gludu ritināšanu līdz komentāram
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        },
        { immediate: true, deep: true } // Sākotnēji izpilda un seko dziļām izmaiņām masīvā
    );

    // Funkcija, kas automātiski pielāgo textarea augstumu atkarībā no teksta daudzuma
    const resizeTextarea = (el) => {
        el.style.height = "auto"
        el.style.height = el.scrollHeight + "px"

        // Ja teksts pārsniedz 200px, tiek parādīts vertikālais scroll
        el.style.overflowY = el.scrollHeight > 200 ? "auto" : "hidden"
    }

    // Funkcija datuma formatēšanai
    const formatDate = (date) => {
        return new Date(date).toLocaleString('lv-LV', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        })
    }

    // Funkcija, kas izsauc textarea izmēra pielāgošanu ievades laikā
    const autoResize = (e) => resizeTextarea(e.target)

    // Funkcija komentāra izvēlnes atvēršanai vai aizvēršanai
    const toggleMenu = (commentId) => {
        activeMenu.value =
            activeMenu.value === commentId
                ? null
                : commentId;
    };

    const repliesLimit = 3; // Cik atbildes rādīt sākumā

    // Pārslēdzams statuss, vai rāda visu sarakstu
    const showAllReplies = ref({});

    // Atgriež redzamās atbildes konkrētam komentāram
    const visibleReplies = (comment) => {
        // Ja komentāram nav atbilžu, atgriež tukšu masīvu
        if (!comment.replies) return [];

        return showAllReplies.value[comment.id]
            ? comment.replies
            : comment.replies.slice(0, repliesLimit);
    };

    // Veidlapas dati paziņojumam
    const form = useForm({
        subject: '',
        problem: '',
    });

    // Veidlapas dati komentaram
    const comment = useForm({
        content: '',
    });

    // Komentāru atbildes veidlapas dati
    const replyForm = useForm({
        content: '',
        comment_parent_id: null
    });

    // Mainīgais, kas glabā pašlaik aktīvā komentāra ID
    const activeReplyId = ref(null);

    // Atlasītie grāmatu dati
    const selectedBook = ref(null);

    // Definē grāmatas atribūtus no komponenta props
    const bookName = props.book?.name;
    const is_blocked = props.book?.is_blocked;
    const bookDescription = props.book?.description;
    const bookAgeLimit = props.book?.age_limit;
    const bookId = props.book?.id;
    const bookStatus= props.book?.status;

    // Definē vērtējumu stāvokļa mainīgos
    const averageRating = ref(props.initialAverageRating); // Vidējais vērtējums
    const ratingsCount = ref(props.initialRatingsCount); // Vērtējumu skaits
    const userRating = ref(props.initialUserRating); // Lietotāja vērtējums
    const tempRating = ref(userRating.value || 0); // Pagaidu vērtējums

    const ratingForm = useForm({
        grade: tempRating.value
    });

    // Definē grāmatzīmes nosaukumu
    const bookmarkTypes = ref([
        { id: 1, name: 'Izlasīts' },
        { id: 2, name: 'Lasu' },
        { id: 3, name: 'Pamests' },
        { id: 4, name: 'Plānots' }
    ]);

    // Mainīgais, kas glabā lietotāja pašreizējo grāmatzīmi
    const currentBookmark = ref(props.initialUserBookmark);

    // Funkcija atbildes formas atvēršanai vai aizvēršanai
    const openReply = (commentId) => {
        if (activeReplyId.value === commentId) {
            // Ja lietotājs nospiež pogu pie tā paša komentāra, atbildes forma tiek aizvērta
            cancelReply();
        } else {
            // Ja tiek nospiests cits komentārs, tiek atvērta jauna atbildes forma
            activeReplyId.value = commentId;
            replyForm.comment_parent_id = commentId;
        }
    };

    // Funkcija atbildes formas aizvēršanai
    const cancelReply = () => {
        activeReplyId.value = null;
        replyForm.reset();
    };

    // Funkcija komentāra rediģēšanas režīma aktivizēšanai
    const startEdit = async (comment) => {
        editingCommentId.value = comment.id
        editedContent.value = comment.content

        // Aizver aktīvo izvēlni un atbilžu formu
        activeMenu.value = activeReplyId.value = null

        await nextTick()

        // Automātiski pielāgo textarea augstumu rediģēšanas laukiem
        document.querySelectorAll('.reply').forEach(resizeTextarea)
    }

    // Funkcija komentāra izmaiņu saglabāšanai
    const saveEdit = async (commentId) => {
        await axios.put(route('comments.update', commentId), {
            content: editedContent.value
        })

        // Iziet no rediģēšanas režīma pēc veiksmīgas saglabāšanas
        editingCommentId.value = null

        router.reload({ only: ['comments'] })
    }

    // Apstiprina dzēšanu
    const confirmDelete = () => {
        if (!selectedComment.value) return;

        router.delete(route('comments.delete', { id: selectedComment.value.id }), {
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

    // Atver dzēšanas modālo logu
    const openDeleteModal = (comment) => {
        selectedComment.value = comment;
        showDeleteModal.value = true;
        document.body.style.overflow = "hidden";
    };

    // Atver modāli grāmatas ziņošanai
    const openBookReport = (book) => {
        reportType.value = 'book'
        selectedBook.value = book
        showUserModal.value = true
    }

    // Atver modāli komentāru ziņošanai
    const openCommentReport = (comment) => {
        reportType.value = 'comment'
        selectedComment.value = comment
        showUserModal.value = true
    }

    // Apstiprina ziņošanu (grāmata vai komentārs)
    const submitReport = () => {
        let url = null;

        if (reportType.value === 'book' && selectedBook.value) {
            url = route('report.user.book', { userBook: selectedBook.value.id });
        }

        if (reportType.value === 'comment' && selectedComment.value) {
            url = route('report.comment', { comment: selectedComment.value.id });
        }

        if (!url) return; // Nekas nav izvēlēts

        form.post(url, {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                showUserModal.value = false;
                showSuccessModal.value = true;
            }
        });
    };

    // Grāmatu pievinošana grāmatzīmem
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

    // Funkcija vērtējuma iesniegšanai
    const submitRating = () => {
        // Atjauna atzīme pirms nosūtīšanas
        ratingForm.grade = tempRating.value;

        ratingForm.post(route('user-books.rate', { book: bookId }), {
            preserveScroll: true,
            onSuccess: (response) => {
                if (response.success) {
                    averageRating.value = response.averageRating;
                    ratingsCount.value = response.ratingsCount;
                    userRating.value = response.userRating;
                    showModal.value = false;
                    alert('Vērtējums veiksmīgi saglabāts!');
                } else {
                    console.error('Servera kļūda:', response.message);
                }
            },
            onError: (errors) => {
                console.error('Validācijas kļūdas:', errors);
            }
        });
    };

    // Funkcija jauna komentāra iesniegšanai
    const submitComment = () => {
        // Tiek nosūtīti komentāra dati uz serveri
        comment.post(
            route('user.comments.store', { user_book: bookId }),
            {
                preserveScroll: true,
                onSuccess: () => {
                    comment.reset();
                    comment.comment_parent_id = null;
                }
            }
        )
    };

    // Funkcija atbildes uz komentāru iesniegšanai
    const submitReply = () => {
        // Tiek nosūtīti atbildes dati uz serveri
        replyForm.post(
            route('user.comments.store', { user_book: bookId }),
            {
                preserveScroll: true,
                onSuccess: () => {
                    cancelReply();
                }
            }
        );
    };


    // Aprēķina formatētu vērtējumu skaitu (piemēram, "1.5k", ja vairāk par 1000)
    const formattedRatingsCount = computed(() => {
        if (ratingsCount.value >= 1000) {
            return (ratingsCount.value / 1000).toFixed(1) + 'k';
        }
        return ratingsCount.value;
    });

    // Aizver visus bloķēšanas modāļus
    const closeAllModals = () => {
        showUserModal.value = false;
        form.reset();
        document.body.style.overflow = "";
    };

    // Aizver veiksmīgas darbības modāli
    const closeSuccessModal = () => {
        showSuccessModal.value = false;
        router.visit(window.location.href);
        document.body.style.overflow = "";
    };

    // Aizver dzēšanas apstiprinājuma modāli
    const closeDeleteModal = () => {
        showDeleteModal.value = false;
        document.body.style.overflow = "";
    };

    // Aizver veiksmīgas dzēšanas modāli un atsvaidzina lapu
    const closeDeleteSuccessModal = () => {
        showDeleteSuccessModal.value = false;
        document.body.style.overflow = "";
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
    <Navbar />

    <div class="main-content">

        <!-- Dzēšanas apstiprinājuma modālais logs -->
        <div v-if="showDeleteModal" class="modal-overlay">
            <div class="modal">
                <div class="success-container">
                    <h2>Vai tiešām vēlaties dzēst šo komentāru?</h2>
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
                    <h2>Komentārs veiksmīgi dzēsts!</h2>
                    <button @click="closeDeleteSuccessModal" class="close-btn">Aizvērt</button>
                </div>
            </div>
        </div>

        <!-- Ziņošanas apstiprinājuma modālais logs stāstam -->
        <div v-if="showUserModal" class="modal-overlay">
            <div class="modal">
                <div class="success-container">
                    <h2>Vai tiešām vēlaties ziņot par šo {{ reportType === 'comment' ? 'komentāru' : 'darbu' }}?</h2>
                    <p>Lūdzu, norādiet ziņošanu iemeslu, lai apstiprinātu.</p>

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
                        <button @click="submitReport" class="close-btn">Ziņot</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Veiksmīgas ziņošanas modālais logs -->
        <div v-if="showSuccessModal" class="modal-overlay">
            <div class="modal">
                <div class="success-container">
                    <h2>Sūdzība veiksmīgi nosūtīta!</h2>
                    <button @click="closeSuccessModal" class="close-btn">Aizvērt</button>
                </div>
            </div>
        </div>

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
                    <span class="meta-item">{{ bookStatus }}</span>
                    <span class="meta-item">{{ bookAgeLimit }}</span>

                </div>

                <div v-if="!is_blocked">
                    <button
                        @click="showBookmarkModal = true"
                        class="bookmark-button"
                    >
                        {{ currentBookmark ? currentBookmark.name : 'Pievienot grāmatzīmēm' }}
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

        <!-- Komentāri -->
        <div class="book-header">
            <h1>
                Komentāri
            </h1>
        </div>

        <div class="book-container">
            <div v-if="authUser">
                <form @submit.prevent="submitComment" class="form-group">
                    <textarea
                        @input="autoResize"
                        v-model="comment.content"
                        rows="3"
                        placeholder="Ievadiet savu komentāru"
                        required
                    ></textarea>
                    <div v-if="comment.errors.content" class="error-message">
                        {{ comment.errors.content }}
                    </div>
                    <div class="form-actions">
                        <button
                            type="submit"
                            class="submit-btn"
                            :disabled="comment.processing"
                        >
                            Nosūtīt
                        </button>
                    </div>
                </form>
            </div>

            <h2>{{ commentsCount }} komentāri</h2>

            <!-- Jā nav komentārus -->
            <div v-if="comments.length === 0" class="item">
                <span class="title">Šeit vēl nav pievienotu komentāru.</span>
            </div>

            <div v-if="comments?.length" class="comments-list">

                <div
                    v-for="comment in comments"
                    :key="comment.id"
                    class="comment-item"
                    :id="'comment-' + comment.id"
                >
                    <!-- Lietotāja avatars, lietotājvards, izveidošanas laiks un opcijas rediģēšanai, dzešanai, ziņošanai -->
                    <div class="comment-header">
                        <div class="comment-user-info">
                            <div class="comment-user-row">
                                <div class="avatar-wrapper">
                                    <div class="avatar">
                                        <i v-if="!comment.user?.avatar" class="fa profile-icon">&#xf2be;</i>
                                        <img v-else :src="`/storage/${comment.user?.avatar}`" alt="avatar" />
                                    </div>
                                </div>

                                <div class="comment-user-name">
                                    <a
                                        :href="route('other.users.watch', { id: comment.user?.id })"
                                        class="author-name"
                                    >
                                        {{ comment.user?.nickname }}
                                    </a>
                                </div>

                                <div v-if="comment.updated_at && comment.created_at !== comment.updated_at" class="comment-time-update">
                                    (Rediģēts)
                                </div>
                            </div>

                            <!-- Komentāra izveidošanas laiks -->
                            <div class="comment-time">
                                {{ formatDate(comment.created_at) }}
                            </div>
                        </div>

                        <div class="comment-menu-wrapper">
                            <button v-if="authUser" class="comment-menu-button"  @click="toggleMenu(comment.id)">
                                ⋮
                            </button>
                            <div v-if="activeMenu === comment.id" class="comment-menu">
                                <button
                                    v-if="authUser?.id === comment.user?.id"
                                    class="menu-item"
                                    @click="startEdit(comment)"
                                >
                                    Rediģēt
                                </button>

                                <button v-if="authUser?.id === comment.user?.id|| authUser?.role === 'admin' || authUser?.role === 'superadmin'" class="menu-item" @click="openDeleteModal(comment)">
                                    Dzēst
                                </button>

                                <button class="menu-item" @click="openCommentReport(comment)">
                                    Ziņot
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Komentāra saturs -->
                    <div class="reply-form" v-if="editingCommentId === comment.id">
                        <textarea class="reply"
                                  @focus="autoResize"
                                  v-model="editedContent"
                                  required
                                  @input="autoResize"
                                  rows="3">
                        </textarea>

                        <div class="reply-actions">
                            <button @click="saveEdit(comment.id)">
                                Saglabāt
                            </button>

                            <button @click="editingCommentId = null">
                                Atcelt
                            </button>
                        </div>
                    </div>

                    <div class="comment-content" v-else >
                        {{ comment.content }}
                    </div>

                    <div v-if="authUser && editingCommentId !== comment.id" class="reply-button-wrapper">
                        <button
                            @click="openReply(comment.id)"
                            class="reply-btn"
                        >
                            Atbildēt
                        </button>
                    </div>

                    <!-- Lai uzrakstīt atbilde -->
                    <form v-if="activeReplyId === comment.id" @submit.prevent="submitReply" class="reply-form">
                    <textarea
                        @input="autoResize"
                        class="reply"
                        v-model="replyForm.content"
                        placeholder="Rakstīt atbildi..."
                        required
                        rows="2"
                    ></textarea>

                        <div v-if="replyForm.errors.content" class="error">
                            {{ replyForm.errors.content }}
                        </div>

                        <div class="reply-actions">
                            <button
                                type="submit"
                                class="reply-submit-btn"
                            >
                                Nosūtīt
                            </button>
                        </div>
                    </form>

                    <!-- Atbildes -->
                    <div v-if="comment.replies?.length" class="comment-replies">

                        <div v-for="reply in visibleReplies(comment)"
                             :key="reply.id"
                             :id="'comment-' + reply.id"
                             class="comment-reply">

                            <div class="comment-header">
                                <div class="comment-user-info">
                                    <div class="comment-user-row">
                                        <div class="avatar-wrapper">
                                            <div class="avatar">
                                                <i v-if="!reply.user?.avatar" class="fa profile-icon">&#xf2be;</i>
                                                <img v-else :src="`/storage/${reply.user?.avatar}`" alt="avatar" />
                                            </div>
                                        </div>

                                        <div class="comment-user-name">
                                            <a
                                                :href="route('other.users.watch', { id: reply.user?.id })"
                                                class="author-name"
                                            >
                                                {{ reply.user?.nickname }}
                                            </a>
                                        </div>

                                        <div v-if="reply.updated_at && reply.created_at !== reply.updated_at" class="comment-time-update">
                                            (Rediģēts)
                                        </div>
                                    </div>

                                    <div class="comment-time">
                                        {{ formatDate(reply.created_at) }}
                                    </div>
                                </div>

                                <div class="comment-menu-wrapper">
                                    <button v-if="authUser" class="comment-menu-button"  @click="toggleMenu(reply.id)">
                                        ⋮
                                    </button>
                                    <div v-if="activeMenu === reply.id" class="comment-menu">
                                        <button v-if="authUser?.id === reply.user?.id" class="menu-item" @click="startEdit(reply)">
                                            Rediģēt
                                        </button>
                                        <button v-if="authUser?.id === reply.user?.id || authUser?.role === 'admin' || authUser?.role === 'superadmin'" class="menu-item" @click="openDeleteModal(reply)">
                                            Dzēst
                                        </button>
                                        <button class="menu-item" @click="openCommentReport(reply)">
                                            Ziņot
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Komentāra saturs -->
                            <div class="reply-form" v-if="editingCommentId === reply.id">
                                <textarea class="reply"
                                          @focus="autoResize"
                                          v-model="editedContent"
                                          @input="autoResize"
                                          required
                                          rows="3"
                                ></textarea>

                                <div class="reply-actions">
                                    <button @click="saveEdit(reply.id)">
                                        Saglabāt
                                    </button>

                                    <button @click="editingCommentId = null">
                                        Atcelt
                                    </button>
                                </div>
                            </div>

                            <div class="comment-content" v-else >
                                {{ reply.content }}
                            </div>
                        </div>
                    </div>

                    <!-- Skatīt vairāk -->
                    <div class="toggle-container">
                        <button v-if="comment.replies?.length > repliesLimit"  class="toggle-btn" @click="showAllReplies[comment.id] = !showAllReplies[comment.id]">
                            {{
                                showAllReplies[comment.id]
                                    ? 'Paslēpt'
                                    : 'Skatīt vairāk'
                            }}
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

.form-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 5px;
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
    resize: none;
    overflow: hidden;
    padding: 10px;
    border: 1px solid rgba(26, 16, 8, 0.8);
    border-radius: 4px;
    font-size: 1rem;
}

option{
    font-size: 1rem;
}

textarea {
    resize: none;
    overflow: hidden;
    min-height: 40px;
    max-height: 300px;
    width: 100%;
    box-sizing: border-box;
    padding: 10px;
    border: 1px solid rgba(26, 16, 8, 0.8);
    border-radius: 4px;
    font-size: 1rem;
}

textarea:focus,
input:focus {
    outline: none; /* Noņem apmales fokusa režīmā */
    box-shadow: none; /* Noņem nokrāsu ap laukiem */
    background-color: #ffc8a9; /* Fona krāsa, kad lauks ir fokusēts */
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

.reply-actions {
    gap:5px;
}

.reply-actions button{
    padding: 1px 6px;
}

.block {
    align-self: flex-start;
    margin-bottom: 5px;
    border: 2px solid rgba(35, 11, 11, 0.8);
    background-color: #714e3e;
}

.reply-button-wrapper {
    display: flex;
    justify-content: flex-end;
    margin-top: 8px;
}

.toggle-container {
    margin-top: 5px;
    display: flex;
    justify-content: center;
}

.toggle-btn{
    padding: 2px 10px;
    margin-top: 5px;
}

.reply-btn{
    padding: 1px 6px;
    margin-top: -20px;
}

.reply{
    margin-top: 7px;
}

.report-wrapper {
    display: flex;
    justify-content: flex-end;
}

.reply-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 5px;
}

.reply-submit-btn {
    padding: 1px 10px;
}

.report{
    padding: 2px 15px;
    margin-left: auto;
}

button:hover {
    background-color: #ffc8a9;
    border-color: #ffc8a9;
}

.item {
    background-color: #ffc8a9;
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

.comments-list {
    margin-top: 25px;
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.comment-item {
    background-color: #ffd9c6;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(63, 31, 4, 0.8);
}

.comment-header {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-bottom: 10px;
    margin-top: -5px;
    font-size: 1rem;
    justify-content: space-between;
}

.comment-menu-wrapper {
    position: relative;
}

.comment-menu-button {
    background: none;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
    color: rgba(26, 16, 8, 0.8);
    transition: color 0.3s;
}

.comment-menu-button:hover {
    color: rgba(106, 51, 0, 0.8);
    background-color: transparent !important;
}

.comment-menu {
    position: absolute;
    right: 0;
    top: 25px;
    background-color: #e4a27c;
    border: 1px solid rgba(26, 16, 8, 0.8);
    border-radius: 6px;
    box-shadow: 0 2px 6px rgba(63, 31, 4, 0.8);
    display: flex;
    flex-direction: column;
    min-width: 120px;
    z-index: 50;
}

.menu-item {
    background: none;
    border: none;
    padding: 8px 12px;
    text-align: left;
    cursor: pointer;
    font-size: 0.9rem;
}

.menu-item:hover {
    background-color: #ffc8a9;
}

.avatar-wrapper {
    display: flex;
    justify-content: center; /* Horizontāli centrēts */
}

.avatar {
    width: 25px; /* Avatar platums */
    height: 25px; /* Avatar augstums */
    border-radius: 50%; /* Padara avataru pilnīgu apli */
    border: 1px solid rgba(26, 16, 8, 0.8);
    background-color: #e4a27c;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar img {
    width: 100%; /* Attēla platums pilnībā atbilst avataram */
    height: 100%; /* Attēla augstums pilnībā atbilst avataram */
    object-fit: cover; /* Attēls aptver visu aplīti, saglabājot proporcijas */
    border-radius: 50%;
}
.comment-user-info {
    display: flex;
    flex-direction:column;
}

.comment-user-row{
    display:flex;
    align-items:center;
    gap:8px;
}

.comment-user-name a {
    font-weight: bold;
    color: rgba(26, 16, 8, 0.8) !important;
    transition: color 0.3s;
}

.comment-user-name a:hover {
    color: rgba(106, 51, 0, 0.8) !important;
}

.comment-time,
.comment-time-update{
    font-size: 0.9rem;
    opacity: 0.7;
    line-height: 1;
}

.comment-time{
    margin-top: 5px;
}

.comment-time-update{
    margin-left: -5px;
}

.comment-content {
    line-height: 1.5;
    word-wrap:break-word;
    font-size: 1rem;
}

.comment-replies {
    margin-left: 30px;
    margin-top: 15px;
    padding-left: 15px;
    border-left: 2px solid rgba(26, 16, 8, 0.3);
    word-wrap:break-word
}

.comment-reply {
    background-color: #ffc8a9;
    padding: 10px;
    border-radius: 6px;
    margin-top: 8px;
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
        .title{
            font-size: 1rem;
        }

        .reply-btn{
            margin-top: -5px;
        }

        .comment-content{
            font-size: 0.9rem;
        }

        .comment-header{
            font-size: 0.9rem;
        }

        .comment-time,
        .comment-time-update{
            font-size: 0.85rem;
        }

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
