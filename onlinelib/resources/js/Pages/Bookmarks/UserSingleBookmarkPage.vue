<script setup>
    import {computed, defineProps} from 'vue'
    import { router } from '@inertiajs/vue3'
    import { route } from "ziggy-js";
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";
    import UserBooksSection from "@/Components/Books/UserBookSection.vue";
    import ClassicBooksSection from "@/Components/Books/ClassicBookSection.vue";

    // Komponenta ievaddati
    const props = defineProps({
        user: Object,
        books: Array, // Lietotāja grāmatu masīvs
        classicBooks: Array, // Klasisko grāmatu masīvs
        pageTitle: String, // Lapas virsraksts
        activeTab: Number, // Aktīvās cilnes ID
        viewingUserId: Number
    })

    // Lietotāja id, kam pieder cilnes
    const userId = props.viewingUserId;

    // Cilņu definīcijas - katra cilne satur ID, nosaukumu un atbilstošo maršrutu
    const tabs = [
        { id: 1, title: 'Izlasītās grāmatas', typeId: 1 },
        { id: 2, title: 'Grāmatas lasāmas', typeId: 2 },
        { id: 3, title: 'Pamestas grāmatas', typeId: 3 },
        { id: 4, title: 'Plānotās grāmatas', typeId: 4 }
    ]

    // Meklē cilni ar id, kas atbilst activeTab
    const activeTypeId = computed(() => {
        const tab = tabs.find(t => t.id === props.activeTab)
        return tab ? tab.typeId : 1
    })

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
</script>

<template>
    <!-- Navigācijas josla -->
    <Navbar />
    <div class="page-wrapper">
        <div class="library-container">
            <!-- Lapas galvene ar virsrakstu -->
            <div class="library-header">
                <h1 class="library-title">{{ pageTitle }}</h1>
            </div>
            <!-- Cilņu konteiners - tiek rādītas visas 4 cilnes -->
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
            <ClassicBooksSection
                v-if="activeTypeId !== 0 && classicBooks.length"
                :classicBooks="classicBooks"
            />

            <!-- Lietotāja grāmatu sadaļa -->
            <UserBooksSection
                v-if="activeTypeId !== 0 && books.length"
                :books="books"
            />

            <!-- Tukšuma ziņojums - parādās, ja nav nevienas grāmatas (ne klasiskās, ne lietotāja) -->
            <div v-if="books.length === 0 && classicBooks.length === 0" class="empty-message">
                <p>Nav pievienotu grāmatu vai stāstu</p>
            </div>
        </div>
        <!-- Kājene -->
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

    @media (max-width: 600px) {


        .library-title {
            font-size: 1.5rem;
        }

        .empty-message {
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
