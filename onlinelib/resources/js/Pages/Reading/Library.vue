<script setup>
    import Navbar from '@/Components/Navbar.vue';
    import Footer from '@/Components/Footer.vue';
    import ClassicBooksSection from '@/Components/Books/ClassicBookSection.vue';
    import UserBooksSection from '@/Components/Books/UserBookSection.vue';
    import { router} from "@inertiajs/vue3";
    import {route} from "ziggy-js";
    import {onMounted, ref} from "vue";

    // Komponenta ievaddati
    const props = defineProps({
        hasFilteredResults: Boolean,
        books: Array,
        classicBooks: Array,
        allGenres: Array,
        ratings: Array,
        filters: {
            type: Object,
            default: () => ({
                bookType: 'all',
                ratings: [],
                genres: [],
                statuses: []
            })
        },
        statuses: {
            type: Array,
            default: () => [
                {id: 'Procesā', label: 'Procesā'},
                {id: 'Pabeigts', label: 'Pabeigts'},
                {id: 'Pamests', label: 'Pamests'}
            ]
        },
    });

    // Inicializē filtrus
    const initFilters = () => {
        // Atjauno izvēlētos vērtējumus
        selectedRatings.value = [...(props.filters.ratings || [])];

        // Atjauno izvēlētos statusus
        selectedStatuses.value = [...(props.filters.statuses || [])];

        // Iestata grāmatu tipu (noklusējums: 'all')
        filtersState.value.bookType = props.filters.bookType || 'all';

        // Izveido jaunu objektu žanru stāvokļiem
        const newStates = {};

        // Katram žanram piešķir sākotnējo stāvokli 'none'
        props.allGenres.forEach(g => {
            newStates[g.id] = 'none';
        });

        // Iestata žanrus, kuri jāiekļauj
        (props.filters.includeGenres || []).forEach(id => {
            newStates[id] = 'include';
        });

        // Iestata žanrus, kuri jāizslēdz
        (props.filters.excludeGenres || []).forEach(id => {
            newStates[id] = 'exclude';
        });

        // Saglabā aprēķinātos žanru stāvokļus
        genreStates.value = newStates;
    };

    // Inicializē kārtošanas parametrus
    const initSort = () => {
        // Iestata kārtošanas lauku vai null, ja nav definēts
        filtersState.value.sort = props.filters?.sort || null;

        // Iestata kārtošanas virzienu
        filtersState.value.direction = props.filters?.direction || 'desc';
    };

    // Izsauc inicializācijas funkcijas, kad komponents tiek ielādēts
    onMounted(() => {
        initFilters();
        initSort();
    });

    // Modālo logu stāvokļi
    const showFilterModal = ref(false);
    const showSortMenu = ref(false);

    // Atlasītie žanri, reitings un statusi
    const selectedRatings = ref(props.filters?.ratings || []);
    const selectedStatuses = ref(props.filters?.statuses || []);
    const genreStates = ref({});

    // Pārslēdz žanra stāvokli (none → include → exclude → none)
    function toggleGenre(id) {
        const current = genreStates.value[id];
        if (current === 'none') {
            genreStates.value[id] = 'include';
        } else if (current === 'include') {
            genreStates.value[id] = 'exclude';
        } else {
            genreStates.value[id] = 'none';
        }
    }

    // Funkcija nosaka CSS klasi žanra chip elementam pēc tā stāvokļa
    function genreClass(id) {
        const state = genreStates.value[id];
        return {
            'genre-include': state === 'include',
            'genre-exclude': state === 'exclude',
            'genre-none': state === 'none',
        };
    }

    // Funkcija reitinga izvēles pārslēgšanai
    const toggleRating = (ratingId) => {
        const index = selectedRatings.value.indexOf(ratingId);
        if (index === -1) {
            selectedRatings.value.push(ratingId);
        } else {
            selectedRatings.value.splice(index, 1);
        }
    };

    // Funkcija statusa izvēles pārslēgšanai
    const toggleStatus = (statusId) => {
        const index = selectedStatuses.value.indexOf(statusId);
        if (index === -1) {
            selectedStatuses.value.push(statusId);
        } else {
            selectedStatuses.value.splice(index, 1);
        }
    };

    // Glabā filtrēšanas un kārtošanas stāvokli
    const filtersState = ref({
        bookType: props.filters?.bookType || 'all',
        sort: props.filters?.sort || null,
        direction: props.filters?.direction || 'desc',
    });

    // Funkcija iestata kārtošanas kritēriju
    const setSort = (sort) => {
        filtersState.value.sort = sort;
    };

    // Funkcija atiestata kārtošanu uz noklusējuma vērtībām un piemēro filtrus
    const resetSort = () => {
        filtersState.value.sort = null;
        filtersState.value.direction = 'desc';
        applyFilters();
    };

    // Funkcija filtru piemērošanai un lapas atjaunināšanai
    const applyFilters = () => {
        showFilterModal.value = false;
        showSortMenu.value = false;

        // Sagatavo žanru sarakstus
        const includeGenres = [];
        const excludeGenres = [];

        Object.entries(genreStates.value).forEach(([id, state]) => {
            if (state === 'include') includeGenres.push(id);
            if (state === 'exclude') excludeGenres.push(id);
        });

        // Sagatavo datus sūtīšanai uz serveri
        const data = {
            bookType: filtersState.value.bookType,
            ratings: selectedRatings.value,
            statuses: selectedStatuses.value,
            includeGenres,
            excludeGenres,
            direction: filtersState.value.direction,
        };

        if (filtersState.value.sort) {
            data.sort = filtersState.value.sort;
        }

        // Nosūta pieprasījumu uz serveri ar filtriem
        router.visit(route('books.filter'), {
            method: 'get',
            data,
            preserveScroll: true,
        });

        document.body.style.overflow = '';
    };

    // Funkcija filtru atiestatīšanai
    const resetFilters = () => {
        showFilterModal.value = false;

        const data = {
            bookType: 'all',
            ratings: [],
            statuses: [],
            includeGenres: [],
            excludeGenres: [],
            direction: filtersState.value.direction,
        };

        if (filtersState.value.sort) {
            data.sort = filtersState.value.sort;
        }

        router.visit(route('books.filter'), {
            method: 'get',
            data,
            preserveScroll: true,
        });

        document.body.style.overflow = '';
    };

    // Funkcija filtru modāļa atvēršanai
    const openFilterModal = () => {
        showFilterModal.value = true;
        document.body.style.overflow = 'hidden';
    };

    // Funkcija filtru modāļa aizvēršanai
    const closeFilterModal = () => {
        showFilterModal.value = false;
        document.body.style.overflow = '';
    };

    // Funkcija kārtošanas modāļa atvēršanai
    const openSortModal = () => {
        showSortMenu.value = true;
        document.body.style.overflow = 'hidden';
    };

    // Funkcija kārtošanas modāļa aizvēršanai
    const closeSortModal = () => {
        showSortMenu.value = false;
        document.body.style.overflow = '';
    };
</script>
<template>
    <!-- Navigācijas josla -->
    <Navbar />
    <div class="page-wrapper">
        <div class="library-container">
            <div class="library-header">
                <!-- Galvenais virsraksts -->
                <h1 class="library-title">Bibliotēka</h1>
                <div class="buttons">
                    <!-- Pogas filtrešānai un kartošanai-->
                    <button class="filter-btn" @click="openFilterModal">
                        Filtrēt
                    </button>
                    <button class="filter-btn" @click="openSortModal">
                        Kārtot
                    </button>
                </div>
            </div>

            <!-- Kartošana -->
            <div v-if="showSortMenu" class="modal-overlay" @click.self="closeSortModal">
                <div class="modal-content">
                    <button class="close-button" @click="closeSortModal">
                        <i class="fa">&#xf00d;</i>
                    </button>

                    <h3>Kārtošanas opcijas</h3>
                    <div class="filter-group">
                        <div class="sort">
                            <!-- Kārtošana pēc datuma -->
                            <div
                                class="status-option"
                                :class="{ selected: filtersState.sort === 'date' }"
                                @click="setSort('date')"
                            >
                                Pēc datuma
                            </div>

                            <!-- Kārtošana pēc vērtējuma -->
                            <div
                                class="status-option"
                                :class="{ selected: filtersState.sort === 'rating' }"
                                @click="setSort('rating')"
                            >
                                Pēc vērtējumiem
                            </div>

                            <!-- Kārtošana pēc nodaļu skaita -->
                            <div
                                class="status-option"
                                :class="{ selected: filtersState.sort === 'chapters' }"
                                @click="setSort('chapters')"
                            >
                                Pēc nodaļām
                            </div>

                            <!-- Kārtošana pēc komentāru skaita -->
                            <div
                                class="status-option"
                                :class="{ selected: filtersState.sort === 'comments' }"
                                @click="setSort('comments')"
                            >
                                Pēc komentāriem
                            </div>
                        </div>
                    </div>

                    <div class="filter-group">
                        <div class="status-options">
                            <!-- Dilstoša kārtošana -->
                            <div
                                class="status-option"
                                :class="{ selected: filtersState.direction === 'desc' }"
                                @click="filtersState.direction = 'desc'"
                            >
                                Dilstoši
                            </div>

                            <!-- Augoša kārtošana -->
                            <div
                                class="status-option"
                                :class="{ selected: filtersState.direction === 'asc' }"
                                @click="filtersState.direction = 'asc'"
                            >
                                Augoši
                            </div>
                        </div>
                    </div>

                    <!-- Kārtošanas darbību pogas -->
                    <div class="modal-actions">
                        <button class="reset-btn" @click="resetSort">
                            Atiestatīt
                        </button>
                        <button class="apply-btn" @click="applyFilters">
                            Lietot
                        </button>
                    </div>
                </div>

            </div>

            <!-- Filtrēšanas modālais logs -->
            <div v-if="showFilterModal" class="modal-overlay" @click.self="closeFilterModal">
                <div class="modal-content">

                    <button class="close-button" @click="closeFilterModal">
                        <i class="fa">&#xf00d;</i>
                    </button>

                    <h3>Filtrēšanas opcijas</h3>

                    <!-- Grāmatu tipa filtrs -->
                    <div class="filter-group">
                        <label>Grāmatu tips:</label>
                        <select v-model="filtersState.bookType">
                            <option value="all">Visas grāmatas</option>
                            <option value="classic">Klasiskās grāmatas</option>
                            <option value="user">Lietotāju grāmatas</option>
                        </select>
                    </div>

                    <!-- Vecuma ierobežojuma filtrs -->
                    <div class="filter-group">
                        <label>Vecuma ierobežojums:</label>
                        <div class="rating-options">
                            <div
                                v-for="ratingItem in ratings"
                                :key="ratingItem.id"
                                class="rating-checkbox"
                                @click="toggleRating(ratingItem.label)"
                                :class="{ 'selected': selectedRatings.includes(ratingItem.label) }"
                            >
                                {{ ratingItem.label }}
                            </div>
                        </div>
                    </div>

                    <!-- Žanru filtrs -->
                    <div class="filter-group">
                        <label>Žanri:</label>
                        <div class="filter-info">
                            <div class="legend-item">
                                <div class="cube"></div>
                                <span>Ietver</span>
                            </div>

                            <div class="legend-item">
                                <div class="cube2"></div>
                                <span>Izslēgts</span>
                            </div>
                        </div>

                        <div class="genre-options">
                            <div
                                v-for="genre in allGenres"
                                :key="genre.id"
                                @click="toggleGenre(genre.id)"
                                :class="genreClass(genre.id)"
                                class="genre-chip"
                            >
                                {{ genre.name }}
                            </div>
                        </div>
                    </div>

                    <!-- Statusu filtrs (netiek rādīts klasiskajām grāmatām) -->
                        <div class="filter-group" v-if="filtersState.bookType !== 'classic'">
                            <label>Statusi:</label>
                            <div class="status-options">
                                <div v-for="status in statuses"
                                     :key="status.id"
                                     @click="toggleStatus(status.id)"
                                     :class="{ 'selected': selectedStatuses.includes(status.id) }"
                                     class="status-option">
                                    {{ status.label }}
                                </div>
                            </div>
                        </div>

                    <!-- Filtru darbību pogas -->
                    <div class="modal-actions">
                        <button class="reset-btn" @click="resetFilters">
                            Atiestatīt
                        </button>
                        <button class="apply-btn" @click="applyFilters">
                            Lietot
                        </button>
                    </div>
                </div>
            </div>

            <!-- Klasisko grāmatu sadaļa -->
            <ClassicBooksSection
                v-if="filters.bookType !== 'user' && classicBooks.length"
                :classicBooks="classicBooks"
            />

            <!-- Lietotāju grāmatu sadaļa -->
            <UserBooksSection
                v-if="filters.bookType !== 'classic' && books.length"
                :books="books"
            />

            <!-- Tukša stāvokļa ziņojums -->
            <div v-if="(filters.bookType === 'all' && books.length === 0 && classicBooks.length === 0) ||
                       (filters.bookType === 'user' && books.length === 0) ||
                       (filters.bookType === 'classic' && classicBooks.length === 0)"
                 class="empty-message">
                <p>Nav pievienotu grāmatu vai stāstu</p>
            </div>
        </div>
        <!-- Kājene -->
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

    .library-header {
        display: flex; /* Izmanto flex izkārtojumu */
        flex-direction: column; /* Elementus novieto vertikāli */
        align-items: center; /* Centrē elementus horizontāli */
        margin-bottom: 30px; /* Apakšējā atstarpe */
    }

    /* Filtrēšanas pogas stils */
    .filter-btn {
        padding: 5px 20px; /* Iekšējās atstarpes (vertikālā/horizontālā) */
        background-color: #c58667; /* Pogas fona krāsa */
        border: 2px solid rgba(26, 16, 8, 0.8); /* Pogas apmale */
        border-radius: 4px; /* Noapaļoti stūri */
        cursor: pointer;
        transition: all 0.3s ease;
        font-family: Tahoma, Helvetica, sans-serif;
        display: inline-block; /* Bloka elements, bet var būt citos elementos */
        margin: 0 auto; /* Automātiskas atstarpes no sāniem (centrēšana) */
        margin-bottom: 20px;
        font-size: 1rem;
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

    button:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;
    }


    /* Modālā loga pārklājuma stils */
    .modal-overlay {
        position: fixed; /* Fiksēta pozīcija */
        top: 0; /* Augšā ekrāna */
        left: 0; /* Kreisā malā */
        right: 0; /* Labā malā */
        bottom: 0; /* Apakšā ekrāna */
        background-color: rgba(26, 16, 8, 0.43);
        display: flex;
        justify-content: center; /* Centrē horizontāli */
        align-items: center; /* Centrē vertikāli */
        z-index: 1000; /* Pārklāj citus elementus */
    }

    /* Modālā loga satura konteiners */
    .modal-content {
        position: relative;
        border: 1px solid rgba(26, 16, 8, 0.8);
        background-color: #e4a27c;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(63, 31, 4, 0.8);
        padding: 20px;
        width: 90%; /* Platums 90% no ekrāna */
        max-width: 500px; /* Maksimālais platums */
        max-height: 80vh; /* Maksimālais augstums 80% no ekrāna */
        overflow-y: auto; /* Vertikālais ritināšanas josla */
        scrollbar-width: thin; /* Plāna ritjosla */
    }

    .close-button {
        position: absolute;
        top: 10px;
        right: 10px;
        background: none;
        border: none;
        cursor: pointer;
        padding: 5px;
        color: rgba(26, 16, 8, 0.8);
        transition: all 0.3s ease;
        font-size: 1.2rem;
        line-height: 1;
        z-index: 1;
    }

    .close-button:hover {
        background-color: #e4a27c; /* Fona krāsa */
        border:none
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

    .buttons{
        flex-direction: row;
        display: flex;
        gap: 5px;
    }

    .sort {
        display: flex;
        flex-direction: column;
        gap:10px;
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

    .rating-options {
        display: flex;
        flex-wrap: wrap; /* Aptver vairākās rindās */
        gap: 10px;
        margin-top: 8px;
    }

    .rating-checkbox {
        background-color: #ffd9c6;
        padding: 4px 10px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
    }


    .rating-checkbox.selected {
        background-color: #ffb18e;
    }

    .genre-options {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 8px;
    }

    .genre-chip {
        background-color: #ffd9c6;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        transition: 0.3s;
        font-size: 1rem;
    }

    .genre-include {
        background-color: #ffb18e;
    }

    .genre-exclude {
        background-color: #a5705b;
        text-decoration: line-through;
    }

    .filter-info {
        display: flex;
        flex-direction: row;
        gap: 18px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cube,
    .cube2 {
        width: 16px;
        height: 16px;
        border-radius: 4px;
        border: 1px solid rgba(35, 11, 11, 0.8);
    }

    .cube {
        background-color: #ffb18e;
    }

    .cube2 {
        background-color: #a5705b;
    }


    .status-options {
        display: flex;
        gap: 8px;
        margin-top: 8px;
        flex-wrap: wrap; /* Aptver vairākās rindās */
    }

    .status-option {
        background-color: #ffd9c6;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer; /* Roka kursors */
        transition: all 0.3s; /* Pārejas efekts */
        font-size: 1rem;
    }

    .status-option.selected {
        background-color: #ffb18e;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end; /* Izlīdzina pa labi */
        gap: 10px; /* Atstarpe starp pogām */
        margin-top: 30px;
    }

    .modal-actions button {
        padding: 5px 18px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1rem;
    }


    .reset-btn {
        border: 2px solid rgba(35, 11, 11, 0.8);
        background-color: #714e3e;
    }

    .apply-btn {
        background-color: #c58667; /* Fona krāsa */
        border: 2px solid rgba(26, 16, 8, 0.8); /* Apmale */
    }


    @media (max-width: 600px) {
        .filter-btn {
            padding: 3px 16px; /* Iekšējās atstarpes (vertikālā/horizontālā) */
            font-size: 0.9rem;
        }

        .library-title {
            font-size: 1.5rem;
        }

        .empty-message {
            font-size: 0.9rem;
        }

        /*Modalais logs filtrešānai*/

        .modal-content h3 {
            font-size: 1rem;
        }

        .filter-group label {
            font-size: 1rem;
        }


        .rating-checkbox {
            padding: 3px 8px;
            font-size: 0.9rem;
        }

        .genre-chip {
            padding: 5px 10px;
            font-size: 0.9rem;
        }

        .status-option {
            padding: 5px 10px;
            font-size: 0.9rem;
        }

        .modal-actions button {
            padding: 3px 15px;
            font-size: 0.9rem;
        }
    }
</style>
