<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
import Navbar from '@/Components/Navbar.vue'
import Footer from '@/Components/Footer.vue'
import { route } from 'ziggy-js'

const props = defineProps({
    chapter: { type: Object, required: true },
    bookChapters: { type: Array, default: () => [] },
    bookId: { type: Number, required: true },
    type: { type: String, required: true } // classic или user
})

const currentIndex = computed(() =>
    props.bookChapters.findIndex(c => c.id === props.chapter.id)
)

const prevChapter = computed(() =>
    currentIndex.value > 0 ? props.bookChapters[currentIndex.value - 1] : null
)

const nextChapter = computed(() =>
    currentIndex.value < props.bookChapters.length - 1
        ? props.bookChapters[currentIndex.value + 1]
        : null
)

const goToChapter = (chapter) => {
    router.visit(route('chapter.content', {
        bookId: props.bookId,
        chapterId: chapter.id
    }))
}
</script>

<template>
    <Navbar />
    <div class="chapter-container">
        <div class="chapter-content">
            <!-- Nodaļas nosaukums -->
            <h1 class="chapter-title">{{ chapter.name }}</h1>

            <div class="chapter-text" v-html="chapter.content"></div>

            <!-- Navigācijas pogas -->
            <div class="chapter-navigation">
                <!-- Iepriekšējās nodaļas poga (rāda tikai, ja nodaļa eksistē) -->
                <button
                    v-if="prevChapter"
                    @click="goToChapter(prevChapter)"
                    class="nav-button prev-button"
                >
                    ← Iepriekšējā nodaļa
                </button>

                <!-- Nākamās nodaļas poga (rāda tikai, ja nodaļa eksistē) -->
                <button
                    v-if="nextChapter"
                    @click="goToChapter(nextChapter)"
                    class="nav-button next-button"
                >
                    Nākamā nodaļa →
                </button>
            </div>
        </div>
    </div>

    <Footer />
</template>
<style scoped>

    .chapter-container {
        max-width: 1100px; /* Maksimālais platums */
        margin: 0 auto; /* Centrēšana horizontāli */
        padding: 20px; /* Iekšējās atstarpes */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa ar caurspīdīgumu */
        font-family: Tahoma, Helvetica, sans-serif; /* Fontu ģimene */
    }

    .chapter-content {
        border-radius: 8px; /* Noapaļoti stūri */
        padding: 30px; /* Iekšējās atstarpes */
    }

    /* Nodaļas virsraksta stils */
    .chapter-title {
        font-size: 1.7rem; /* Fonta izmērs */
        margin-bottom: 25px; /* Apakšējā atstarpe */
        text-align: center; /* Centrēts teksts */
        border-bottom: 1px solid rgba(26, 16, 8, 0.8); /* Apakšējā apmale */
        padding-bottom: 10px; /* Atstarpe zem virsraksta */
        font-weight: bold; /* Trekns teksts */
    }

    .chapter-text {
        white-space: pre-line; /* Saglabā teksta formatējumu */
        line-height: 1.7; /* Rindu augstums */
        font-size: 1rem;
        margin-bottom: 40px;
        word-wrap: break-word;
    }

    .chapter-navigation {
        display: flex; /* Flex izkārtojums */
        justify-content: space-between; /* Vienmērīga sadalīšana */
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid rgba(26, 16, 8, 0.3);
    }

    .nav-button {
        background-color: #c58667; /* Pamatkrāsa */
        border: 2px solid rgba(26, 16, 8, 0.8); /* Apmale */
        border-radius: 4px;
        padding: 8px 15px; /* Atstarpes iekšpusē */
        cursor: pointer; /* Peles kursors mainās uz rādītāju */
        transition: all 0.3s ease; /* Gluda pāreja visām īpašībām */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
    }

    button:hover {
        background-color: #ffc8a9; /* Maina fona krāsu */
        border-color: #ffc8a9; /* Maina apmales krāsu */
    }

    .prev-button {
        margin-right: auto; /* Pievilina pa kreisi */
    }

    .next-button {
        margin-left: auto; /* Pievilina pa labi */
    }

    @media (max-width: 768px) {
        .chapter-container {
            padding: 15px;
        }

        .chapter-content {
            padding: 20px;
        }

        .chapter-title {
            font-size: 1.5rem;
        }

        .chapter-text {
            font-size: 0.9rem;
        }

        .nav-button {
            padding: 6px 12px;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 500px) {

        .chapter-title {
            font-size: 1.5rem;
        }

        .chapter-text {
            font-size: 0.9rem;
        }

        .nav-button {
            padding: 6px 12px;
            font-size: 0.9rem;
        }

        .nav-button {
            padding: 5px 8px;
            font-size: 0.9rem;
        }
    }
</style>
