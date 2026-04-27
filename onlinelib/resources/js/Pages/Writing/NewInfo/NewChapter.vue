<script setup>
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";
    import { ref } from "vue";
    import axios from "axios";
    import {route} from "ziggy-js";
    import SuccessModal from "@/Components/Modal/SuccessModal.vue";
    import {router, useForm} from "@inertiajs/vue3";

    // Komponenta ievaddati
    const props = defineProps({
        bookId: { type: Number, required: true }, // Grāmatas ID
        type: { type: String, required: true }    // "user" vai "classic"
    });

    // Modālo logu stāvokļi
    const showSuccesModal = ref(false);

    // Forms nodaļās izveidei
    const form = useForm({
        name: "",
        content: ""
    });

    // Aizvēr modāli
    const closeSuccesModal = () => {
        showSuccesModal.value = false;

        const redirectUrl = props.type === 'classic'
            ? `/Classic/${props.bookId}/edit`
            : `/writing/${props.bookId}/edit`;

        router.visit(redirectUrl);
    };

    // Funkcija, kas saglabā jaunu nodaļu
    const saveChapter = () => {
        // Nosaka, uz kuru servera maršrutu sūtīt datus
        const url = props.type === 'classic'
            ? route('classic.chapters.store', props.bookId)
            : route('user.chapters.store', props.bookId);

        // Nosūta datus uz serveri
        form.post(url, {
            onSuccess: () => {
                showSuccesModal.value = true;
                form.reset();
            }
        });
    };
</script>

<template>
    <!-- Navigācijas josla -->
    <Navbar />

    <!-- Veiksmīgas izveidošanas modālais logs -->
    <SuccessModal
        :is-open="showSuccesModal"
        title="Nodaļa ir veiksmīgi izveidota!"
        @close="closeSuccesModal"
    />

    <div class="main-content">
        <div class="story-form">
            <!-- Nodaļas veidošanas formas konteiners -->
            <h1>Nodaļas izveide</h1>

            <!-- Nosaukuma ievades lauks -->
            <form @submit.prevent="saveChapter">
                <div class="editor-container">
                    <input
                        v-model="form.name"
                        type="text"
                        class="chapter-title-input"
                        placeholder="Nodaļas nosaukums"
                    />

                    <!-- Kļūdas paziņojums, ja nosaukums nav derīgs -->
                    <div v-if="form.errors.name" class="error-message1">
                        {{ form.errors.name }}
                    </div>

                    <!-- Satura ievades lauks -->
                    <textarea
                        v-model="form.content"
                        class="chapter-editor"
                        placeholder="Sāciet rakstīt savu nodaļu šeit..."
                    ></textarea>

                    <!-- Kļūdas paziņojums, ja saturs nav derīgs -->
                    <div v-if="form.errors.content" class="error-message">
                        {{ form.errors.content }}
                    </div>
                    <div class="editor-footer">
                        <button type="submit" class="save-btn">Saglabāt</button>
                        <!-- Kopējas rakstszīmes skaits -->
                        <div class="word-count">
                            Rakstzīmes: {{ form.content.length }}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Kājene -->
    <Footer />
</template>


<style scoped>
    .main-content {
        padding: 20px; /* Iekšējā atstarpe */
        padding-bottom: 60px; /* Apakšējā atstarpe lapas saturam */
    }

    .story-form {
        max-width: 900px; /* Maksimālais platums formai */
        margin: 0 auto; /* Centrs horizontāli */
        padding: 20px;
    }

    h1 {
        font-size: 1.7rem; /* Virsraksta izmērs */
        margin-top: 32px;
        margin-bottom: 40px;
        text-align: center; /* Centrēts teksts */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
        font-weight: bold; /* Treknraksts */
    }

    .editor-container {
        color: rgba(26, 16, 8, 0.8);
        font-family: Tahoma, Helvetica, sans-serif;
        border: 1px solid rgba(26, 16, 8, 0.8);
        background-color: #e4a27c;
        border-radius: 10px; /* Noapaļoti stūri */
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px; /* Ēna */
        padding: 20px;
    }

    .chapter-title-input {
        width: 100%; /* Pilna platuma ievade */
        padding: 15px;
        font-size: 1.1rem;
        border: 1px solid rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        margin-bottom: 20px; /* Atstarpe no apakšas */
        outline: none;
    }

    .chapter-editor {
        width: 100%;
        min-height: 600px; /* Minimālais augstums */
        padding: 15px;
        font-size: 1rem;
        line-height: 1.6; /* Rindas augstums */
        border: 1px solid rgba(26, 16, 8, 0.8);
        font-family: Tahoma, Helvetica, sans-serif;
        border-radius: 4px;
        resize: vertical; /* Atļaut vertikāli mainīt izmēru */
        outline: none;
    }

    input:focus,
    textarea:focus {
        outline: none; /* Noņem fokusa līniju */
        box-shadow: none;
        background-color: #ffc8a9; /* Fona krāsa fokusa brīdī */
    }

    input::placeholder,
    textarea::placeholder {
        color: rgba(26, 16, 8, 0.42); /* Placeholder teksta krāsa */
    }

    .error-message1 {
        font-size: 1rem;
        color: rgb(127, 29, 29);
        margin-top: -20px !important;
        margin-bottom: 15px !important;
    }

    .error-message {
        font-size: 1rem;
        color: rgb(127, 29, 29);
        margin-top: -5px;
    }

    .editor-footer {
        display: flex;
        justify-content: space-between; /* Telpa starp elementiem */
        align-items: center;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid rgba(26, 16, 8, 0.8);
    }

    .save-btn {
        border: 2px solid rgba(26, 16, 8, 0.8);
        background-color: #c58667;
        color: rgba(26, 16, 8, 0.8);
        padding: 7px 20px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .save-btn:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;
    }

    .word-count {
        color: rgba(26, 16, 8, 0.8);
        font-size: 1rem;
    }

    @media (max-width: 768px) {
        .editor-container {
            padding: 15px;
        }

        .chapter-editor {
            min-height: 300px;
        }
    }

    @media (max-width: 500px) {
        h1 {
            font-size: 1.5rem;
        }
        .chapter-title-input {
            font-size: 1rem;
        }
            input,
            textarea,
            input::placeholder,
            textarea::placeholder,
            .error-message1,
            .error-message,
            .word-count{
                font-size: 0.9rem;
            }

            .save-btn {
                padding: 4px 10px;
                font-size: 0.9rem;
            }
    }
</style>
