<script setup>
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";
    import { ref } from "vue";
    import { route } from "ziggy-js";
    import {router, useForm} from "@inertiajs/vue3";
    import SuccessModal from "@/Components/Modal/SuccessModal.vue";

    // Komponenta ievaddati
    const props = defineProps({
        chapter: { type: Object, required: true },
        bookId: { type: Number, required: true },
        type: { type: String, required: true },
    });

    // Modālo logu stāvokļi
    const showSuccesModal = ref(false);

    // Forms nodaļās rediģēšanai
    const form = useForm({
        name: props.chapter.name ?? "",
        content: props.chapter.content ?? ""
    });

    // Aizver veiksmīgas atjaunināšanas modāli un pārvirza atpakaļ uz grāmatas rediģēšanas lapu
    const closeSuccesModal = () => {
        showSuccesModal.value = false;

        // Izvēlas pareizo pārvirzīšanas ceļu atkarībā no grāmatas tipa
        const redirectUrl = props.type === 'classic'
            ? `/Classic/${props.bookId}/edit`
            : `/writing/${props.bookId}/edit`;

        router.visit(redirectUrl);
    };

    // Funkcija nodaļas saglabāšanai (rediģēšanai)
    const saveChapter = () => {
        // Izvēlas pareizo route atkarībā no grāmatas tipa
        const url = props.type === 'classic'
            ? route('classic.chapters.update', props.chapter.id)
            : route('user.chapters.update', props.chapter.id);

        // Nosūta PUT pieprasījumu uz serveri ar atjauninātajiem datiem
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

    <!-- Veiksmīgas atjaunināšanas modālis -->
    <SuccessModal
        :is-open="showSuccesModal"
        title="Nodaļa veiksmīgi atjaunināta!"
        @close="closeSuccesModal"
    />

    <!-- Galvenais satura bloks -->
    <div class="main-content">
        <div class="story-form">
            <h1>Nodaļas rediģēšana</h1>

            <!-- Rediģēšanas forma -->
            <form @submit.prevent="saveChapter">
                <div class="editor-container">
                    <!-- Nodaļas nosaukuma ievades lauks -->
                    <input
                        v-model="form.name"
                        type="text"
                        class="chapter-title-input"
                        placeholder="Nodaļas nosaukums"
                        required
                    />

                    <!-- Kļūdas paziņojums, ja nosaukums nav derīgs -->
                    <div v-if="form.errors.name" class="error-message1">
                        {{ form.errors.name }}
                    </div>

                    <!-- Nodaļas satura ievades lauks (teksta laukums) -->
                    <textarea
                        v-model="form.content"
                        class="chapter-editor"
                        placeholder="Sāciet rakstīt savu nodaļu šeit..."
                        required
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
        padding: 20px; /* Attālums iekšpusē */
        padding-bottom: 60px;
    }

    .story-form {
        max-width: 900px; /* Maksimālais platums */
        margin: 0 auto; /* Centrēšana */
        padding: 20px; /* Iekšējais attālums */
    }

    h1 {
        font-size: 1.7rem; /* Virsraksta izmērs */
        margin-top: 32px;
        margin-bottom: 40px;
        text-align: center; /* Centrēts teksts */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        font-family: Tahoma, Helvetica, sans-serif;
        font-weight: bold; /* Treknraksts */
    }

    .editor-container {
        color: rgba(26, 16, 8, 0.8);
        font-family: Tahoma, Helvetica, sans-serif;
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmale */
        background-color: #e4a27c; /* Fona krāsa */
        border-radius: 10px; /* Noapaļoti stūri */
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px; /* Ēna */
        padding: 20px
    }

    .chapter-title-input {
        width: 100%; /* Pilns platums */
        padding: 15px;
        font-size: 1.1rem;
        border: 1px solid rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        margin-bottom: 20px;
        outline: none; /* Bez ārējā fokusa rāmja */
    }

    .chapter-editor {
        width: 100%;
        min-height: 600px; /* Minimālais augstums */
        padding: 15px;
        font-size: 1rem;
        line-height: 1.6; /* Teksta augstums */
        border: 1px solid rgba(26, 16, 8, 0.8);
        font-family: Tahoma, Helvetica, sans-serif;
        border-radius: 4px;
        resize: vertical; /* Atļaut tikai vertikālu izmēra maiņu */
        outline: none;
    }

    input:focus,
    textarea:focus {
        outline: none;
        box-shadow: none;
        background-color: #ffc8a9; /* Fona krāsa fokusa laikā */
    }

    input::placeholder,
    textarea::placeholder {
        color: rgba(26, 16, 8, 0.42);
    }

    .error-message {
        font-size: 1rem;
        color: rgb(127, 29, 29);
        margin-top: -5px;
    }

    .editor-footer {
        display: flex; /* Flex izkārtojums */
        justify-content: space-between; /* Starp elementiem */
        align-items: center;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid rgba(26, 16, 8, 0.8); /* Augšējā līnija */
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


