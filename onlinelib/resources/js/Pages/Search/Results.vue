<script setup>
    import Navbar from '@/Components/Navbar.vue'
import Footer from '@/Components/Footer.vue'
import { router } from '@inertiajs/vue3'
import {route} from "ziggy-js";
import UserBooksSection from "@/Components/Books/UserBookSection.vue";
import ClassicBooksSection from "@/Components/Books/ClassicBookSection.vue";

    // Komponenta ievaddati
    const props = defineProps({
        books: {
            type: Array,
            default: () => []
        },
        classicBooks: {
            type: Array,
            default: () => []
        },
        searchQuery: String
    })
</script>

<template>
    <Navbar />

    <div class="page-wrapper">
        <div class="library-container">
            <div class="library-header">
                <h1 class="library-title">Meklēšanas rezultāti: "{{ searchQuery }}"</h1>
            </div>

            <!-- Klasisko grāmatu sadaļa -->
            <ClassicBooksSection
                v-if="classicBooks.length"
                :classicBooks="classicBooks"
            />

            <!-- Lietotāju grāmatu sadaļa -->
            <UserBooksSection
                v-if="books.length"
                :books="books"
            />

            <!-- Paziņojums, ja nav grāmatas -->
            <div v-if="books.length === 0 && classicBooks.length === 0" class="empty-message">
                <p>Nav atrastu darbu ar nosaukumu "{{ searchQuery }}"</p>
            </div>
        </div>
    </div>

    <Footer />
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
    }
</style>

