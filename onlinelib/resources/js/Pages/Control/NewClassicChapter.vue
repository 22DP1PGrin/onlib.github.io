<script setup>
import Navbar from "@/Components/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import { ref } from "vue";
import axios from "axios";

const props = defineProps({
    bookId: Number, // Saņem stāsta ID kā rekvizītu
});

const bookId = props.bookId; // Saglabā stāsta ID mainīgajā

const title = ref(''); // Nodaļas nosaukuma laukums
const content = ref(''); // Nodaļas saturs
const validationErrors = ref({}); // Kļūdu glabāšanas objekts

function saveChapter() {
    const data = {
        book_id: props.bookId,
        name: title.value,
        content: content.value,
    };
    console.log('bookId:',  props.bookId);
    axios.post('/Classic_chapters', data) // Sūta POST pieprasījumu uz serveri
        .then(response => {
            alert('Nodaļa veiksmīgi saglabāta!'); // Paziņojums par veiksmi
            window.location.href = `/Classic/${bookId}/edit`; // Pāradresācija uz rediģēšanas lapu
        })
        .catch(error => {
            if (error.response.status === 422) {
                validationErrors.value = error.response.data.errors; // Saglabā validācijas kļūdas
                console.log(error.response.data);
            } else {
                console.error(error); // Izvada kļūdu konsolē
            }
        });
}
</script>
<template>
    <Navbar />

    <!-- Galvenais satura bloks -->
    <div class="main-content">
        <!-- Nodaļas izveides formas konteiners -->
        <div class="story-form">
            <!-- Formas virsraksts -->
            <h1>Nodaļas izveide</h1>

            <!-- Veidlapas sadaļa ar iesniegšanas apstrādi -->
            <form @submit.prevent="saveChapter">
                <div class="editor-container">
                    <!-- Nodaļas nosaukuma ievades lauks -->
                    <input
                        v-model="title"
                        type="text"
                        class="chapter-title-input"
                        placeholder="Nodaļas nosaukums"
                        required
                    >
                    <!-- Validācijas kļūdas ziņojums nosaukumam -->
                    <div v-if="validationErrors.name" class="error-message1">
                        {{ validationErrors.name[0] }}
                    </div>

                    <!-- Nodaļas satura teksta laukums -->
                    <textarea
                        v-model="content"
                        class="chapter-editor"
                        placeholder="Sāciet rakstīt savu nodaļu šeit..."
                        required
                    ></textarea>
                    <!-- Validācijas kļūdas ziņojums saturam -->
                    <div v-if="validationErrors.content" class="error-message">
                        {{ validationErrors.content[0] }}
                    </div>

                    <!-- Redaktora kājene ar pogām un statistikas -->
                    <div class="editor-footer">
                        <!-- Iesniegšanas poga -->
                        <button type="submit" class="save-btn">Saglabāt</button>

                        <!-- Rakstzīmju skaitītājs -->
                        <div class="word-count">
                            Rakstzīmes: {{ content.length }}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <Footer/>
</template>

<style scoped>
.main-content {
    padding: 20px; /* Iekšējā atstarpe */
    padding-bottom: 60px; /* Apakšējā atstarpe lapas saturam */
}

.story-form {
    max-width: 1200px; /* Maksimālais platums formai */
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
    min-height: 500px; /* Minimālais augstums */
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
