<script setup>
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";
    import { ref } from "vue";
    import {router, useForm} from "@inertiajs/vue3";
    import SuccessModal from "@/Components/Modal/SuccessModal.vue";

    // Komponenta ievaddati
    const props = defineProps({
        genres: Array,
        ratings: Array,
        flash: Object
    });

    // Izveido formu grāmatu izveidošanai
    const form = useForm({
        title: '',
        description: '',
        rating_id: '',
        genres: [],
    });

    // Modālo logu stāvokļi
    const showSuccesModal = ref(false);

    // Aizvēr modāli
    const closeSuccesModal = () => {
        showSuccesModal.value = false;
        document.body.style.overflow = "hidden";
        router.visit('/StoryList');
    }

    // Funkcija, kas pārslēdz žanru izvēli
    const toggleGenre = (genreId) => {
        const index = form.genres.indexOf(genreId); // Meklē žanra ID formā
        if (index === -1) { // Ja žanrs vēl nav izvēlēts
            form.genres.push(genreId); // Pievieno žanru
        } else { // Ja žanrs jau ir izvēlēts
            form.genres.splice(index, 1); // Noņem žanru
        }
    };

    // Kļūdu paziņojumi žanru laukam
    const errors = ref({
        genres: ''
    });

    // Funkcija stāsta pievienošanai
    const submit = () => {
        form.post('/CreateStory', { // Iesniedz datus uz serveri
            onSuccess: () => {
                showSuccesModal.value = true
            },
        });
    };

    // Funkcija, kas automātiski pielāgo textarea augstumu atkarībā no teksta daudzuma
    const resizeTextarea = (el) => {
        el.style.height = "auto"
        el.style.height = el.scrollHeight + "px"

        // Ja teksts pārsniedz 200px, tiek parādīts vertikālais scroll
        el.style.overflowY = el.scrollHeight > 200 ? "auto" : "hidden"
    }
</script>

<template>
    <!-- Navigācijas josla -->
    <Navbar />

    <!-- Veiksmīgas izveidošanas modālais logs -->
    <SuccessModal
        :is-open="showSuccesModal"
        title="Stāsts ir veiksmīgi izveidots!"
        @close="closeSuccesModal"
    />

    <div class="main-content">
        <div class="story-form">
            <h1>Stāstu veidošana</h1>

            <form class="submit" @submit.prevent="submit">
                <!-- Nosaukuma ievades lauks -->
                <div class="form-group">
                    <label for="title">Nosaukums</label>
                    <input
                        id="title"
                        v-model="form.title"
                        type="text"
                        placeholder="Stāsta nosaukums"
                        required
                    />

                    <!-- Kļūdas paziņojums, ja apraksts nav derīgs -->
                    <div v-if="form.errors.title" class="error-message">
                        {{ form.errors.title }}
                    </div>
                </div>

                <!-- Apraksta teksta laukums -->
                <div class="form-group">
                    <label for="description">Apraksts</label>
                    <textarea
                        id="description"
                        v-model="form.description"
                        rows="4"
                        placeholder="Īsumā par stāstu"
                        @input="resizeTextarea($event.target)"
                        required
                    ></textarea>

                    <!-- Kļūdas paziņojums, ja apraksts nav derīgs -->
                    <div v-if="form.errors.description" class="error-message">
                        {{ form.errors.description }}
                    </div>
                </div>

                <!-- Vecuma ierobežojumu izvēles lauks -->
                <div class="form-group">
                    <label for="rating">Vecuma ierobežojums</label>
                    <select id="rating" v-model="form.rating_id" required>
                        <option disabled value="" class="placeholder-option">Izvēlieties vecuma ierobežojumu</option>
                        <!-- Dinamiski ģenerētas izvēles iespējas -->
                        <option
                            v-for="rating in ratings"
                            :key="rating.id"
                            :value="rating.id"
                        >
                            {{ rating.label }}
                        </option>
                    </select>
                </div>

                <!-- Žanru izvēles sadaļa -->
                <div class="form-group">
                    <label>Žanri</label>
                    <div class="genre-options">
                        <!-- Žanru izvēles rūtiņas -->
                        <div
                            v-for="genre in genres"
                            :key="genre.id"
                            class="genre-checkbox"
                            :class="{
                                'selected': form.genres.includes(genre.id),
                                'error-border': errors.genres
                            }"
                            @click="toggleGenre(genre.id)"
                        >
                            {{ genre.name }}
                        </div>
                    </div>

                    <!-- Kļūdas paziņojums, ja nav izvēlēts žanrs -->
                    <div v-if="form.errors.genres" class="error-message">
                        {{ form.errors.genres }}
                    </div>
                </div>

                <!-- Formas iesniegšanas poga -->
                <div class="form-actions">
                    <button type="submit" class="submit-btn">Saglabāt</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Kājene -->
    <Footer />
</template>

<style scoped>
    .main-content {
        padding-bottom: 45px; /* Papildus attālums apakšā */
    }

    .story-form {
        max-width: 800px; /* Maksimālais platums */
        margin: 0 auto; /* Centrs horizontāli */
        padding: 20px; /* Iekšējais piepildījums */
    }

    .submit {
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonta stils */
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
        background-color: #e4a27c; /* Fona krāsa */
        border-radius: 10px; /* Noapaļoti stūri */
        padding: 30px; /* Iekšējais piepildījums */
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px; /* Ēna */
    }

    h1 {
        font-size: 1.7rem; /* Virsraksta izmērs */
        margin-top: 32px; /* Attālums no augšas */
        margin-bottom: 40px; /* Attālums apakšā */
        text-align: center; /* Centrs */
        color: rgba(26, 16, 8, 0.8);
        font-family: Tahoma, Helvetica, sans-serif;
        font-weight: bold;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block; /* Bloķēts elements */
        font-size: 1.1rem;
        margin-bottom: 8px;
        font-weight: bold;
    }

    textarea {
        resize: none;
        overflow: hidden;
        min-height: 40px;
        max-height: 250px;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%; /* Pilna platuma ievades lauks */
        padding: 10px; /* Iekšējais piepildījums */
        border: 1px solid rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        background-color: white;
        font-size: 1rem;
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder,
    .placeholder-option {
        color: rgba(26, 16, 8, 0.42);
        font-size: 1.0rem;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none; /* Nav kontūras */
        box-shadow: none; /* Nav ēnas */
        background-color: #ffc8a9; /* Fona krāsa uz fokusa */
    }

    .genre-options {
        display: flex; /* Flexbox izvietojums */
        flex-wrap: wrap; /* Ļauj elementiem iet uz jauniem rindiem */
        gap: 10px; /* Attālums starp elementiem */
    }

    .genre-checkbox {
        background-color: #ffd9c6;
        padding: 8px 12px; /* Iekšējais piepildījums */
        border-radius: 4px;
        cursor: pointer; /* Kursors, kas norāda uz klikšķināmu objektu */
        transition: 0.3s; /* Pārejas efekts */
        font-size: 1rem;
    }

    .genre-checkbox.selected {
        background-color: #ffb18e;
    }

    .error-message {
        font-size: 1rem;
        color: rgb(127,29,29);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
    }

    .submit-btn {
        border: 2px solid rgba(26, 16, 8, 0.8);
        background-color: #c58667;
        color: rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        font-size: 1.0rem;
        cursor: pointer;
        padding: 5px 20px;
        transition: all 0.3s;
    }

    button:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;
    }

    @media (max-width: 500px) {
        h1 {
            font-size: 1.5rem;
        }
        .form-group label {
            font-size: 1rem;
        }

        button{
            font-size: 0.9rem;
            padding: 5px 15px;
        }
        .form-group input,
        .form-group textarea,
        .form-group select {
            font-size: 0.9rem;
        }
        .form-group input::placeholder,
        .form-group textarea::placeholder,
        .placeholder-option{
            font-size: 0.9rem;
        }
        .genre-checkbox {
            font-size: 0.9rem;
            padding: 7px 8px;
        }
        .error-message{
            font-size: 0.9rem;
            color:rgb(127,29,29);
        }
    }
</style>
