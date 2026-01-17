<script setup>
import { router, useForm } from '@inertiajs/vue3';
import Navbar from "@/Components/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import { route } from "ziggy-js";

// Saņemam datus no servera kā props
const props = defineProps({
    classic_book: Object, // Stāsta objekts
    genres: Array, // Pieejamie žanri
    ratings: Array, // Vērtējumu opcijas
    flash: Object // Flash ziņojumi (ja ir)
});

// Paņemam žanru ID no esošā stāsta (ja tādi ir)
const initialGenreIds = props.classic_book.genres?.map(g => g.id) || [];

// Izveidojam veidlapas datus, sākotnēji aizpildītus ar esošo stāsta informāciju
const form = useForm({
    name: props.classic_book.name,
    description: props.classic_book.description,
    age_limit: props.classic_book.age_limit,
    Year_publication: props.classic_book.Year_publication,
    genres: [...initialGenreIds],
    Author_name: props.classic_book.Author_name,
    Author_surname: props.classic_book.Author_surname

});

// Funkcija žanru pārslēgšanai (pievienošana/noņemšana)
const toggleGenre = (genreId) => {
    if (form.genres.includes(genreId)) {
        form.genres = form.genres.filter(id => id !== genreId); // Noņemt
    } else {
        form.genres = [...form.genres, genreId]; // Pievienot
    }
};

// Veidlapas iesniegšana – atjaunina stāstu
const submitForm = () => {
    form.put(route('classic.books.update', { id: props.classic_book.id }), {
        preserveScroll: true, // Patur skatu ritināšanas vietā
        onSuccess: () => {
            alert('Stāsts veiksmīgi atjaunināts!'); // Panākumu ziņojums
            router.visit(route('book.lists')); // Pāriet uz stāstu sarakstu
        },
        onError: (errors) => {
            console.log(errors); // Kļūdu izvadīšana konsolē
        },
    });
};

// Nodaļas dzēšana pēc apstiprinājuma
const deleteChapter = (id) => {
    if (confirm('Vai tiešām vēlaties dzēst šo nodaļu?')) {
        router.delete(route('classic.chapters.destroy', { id }), {
            preserveScroll: true,
            onSuccess: () => {
                alert('Nodaļa dzēsta.'); // Ziņojums pēc dzēšanas
            },
        });
    }
};

// Pāriet uz nodaļas izveides lapu
const GoToCreate = () => {
    router.get(route('classic.chapters.create', { book: props.classic_book.id }));
};

// Pāriet uz esošās nodaļas rediģēšanu
const goToEdit = (chapterId) => {
    router.get(route('classic.chapters.edit', { chapter: chapterId }));
};
</script>

<template>
    <Navbar />

    <!-- Galvenais satura bloks -->
    <div class="main-content">
        <!-- Stāsta rediģēšanas forma -->
        <div class="story-form">

            <h1>Grāmatu rediģēšana</h1>

            <!-- Veidlapas sadaļa -->
            <form @submit.prevent="submitForm" class="submit">
                <!-- Nosaukuma ievades lauks -->
                <div class="form-group">
                    <label for="title">Nosaukums</label>
                    <input
                        type="text"
                        id="name"
                        v-model="form.name"
                        class="form-input"
                        placeholder="Stāsta nosaukums"
                        required
                    >
                    <!-- Validācijas kļūda nosaukumam -->
                    <div v-if="form.errors.name" class="error-message">
                        {{ form.errors.name }}
                    </div>
                </div>

                <!-- Apraksta teksta laukums -->
                <div class="form-group">
                    <label for="description">Apraksts</label>
                    <textarea
                        id="description"
                        v-model="form.description"
                        class="form-textarea"
                        placeholder="Īsumā par stāstu"
                        required
                    ></textarea>
                    <!-- Validācijas kļūda aprakstam -->
                    <div v-if="form.errors.description" class="error-message">
                        {{ form.errors.description }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="title">Autora vārds</label>
                    <input
                        type="text"
                        id="name"
                        v-model="form.Author_name"
                        class="form-input"
                        placeholder="Autora vārds"
                        required
                    >
                    <!-- Validācijas kļūda autora vārdam-->
                    <div v-if="form.errors.Author_name" class="error-message">
                        {{ form.errors.Author_name }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="title">Autora uzvārds</label>
                    <input
                        type="text"
                        id="name"
                        v-model="form.Author_surname"
                        class="form-input"
                        placeholder="Autora uzvārds"
                        required
                    >
                    <!-- Validācijas kļūda nosaukumam -->
                    <div v-if="form.errors.Author_surname" class="error-message">
                        {{ form.errors.Author_surname }}
                    </div>
                </div>

                <div class="form-group">
                    <label for="title">Izdošanas gads</label>
                    <input
                        type="text"
                        id="name"
                        v-model="form.Year_publication"
                        class="form-input"
                        placeholder="Izdošanas gads"
                        required
                    >
                    <!-- Validācijas kļūda nosaukumam -->
                    <div v-if="form.errors.Year_publication" class="error-message">
                        {{ form.errors.Year_publication }}
                    </div>
                </div>

                <!-- Vecuma ierobežojumu izvēle -->
                <div class="form-group">
                    <label for="rating">Vecuma ierobežojums</label>
                    <select v-model="form.age_limit" class="form-select" required>
                        <option disabled value="" class="placeholder-option">Izvēlieties reitingu</option>
                        <!-- Dinamiski ģenerētas vecuma ierobežojumu opcijas -->
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
                        <!-- Žanru izvēles-->
                        <div
                            v-for="genre in genres"
                            :key="genre.id"
                            class="genre-checkbox"
                            :class="{
                                'selected': form.genres.includes(genre.id),
                                'initial': initialGenreIds.includes(genre.id)
                            }"
                            @click="toggleGenre(genre.id)"
                        >
                            {{ genre.name }}
                        </div>
                    </div>
                    <!-- Validācijas kļūda žanriem -->
                    <div v-if="form.errors.genres" class="error-message">
                        {{ form.errors.genres }}
                    </div>
                </div>

                <!-- Formas iesniegšanas poga -->
                <div class="form-actions">
                    <button type="submit" class="submit-btn">Saglabāt</button>
                </div>
            </form>
            <!-- Nodaļu saraksta sadaļa -->
            <h1 class="chapter">Nodaļas</h1>
            <div class="chapters-container">
                <div class="chapter-list">
                    <!-- Paziņojums, ja nav nodaļu -->
                    <div v-if="classic_book.chapters.length === 0" class="chapter-item">
                        <span class="chapter-title">Šeit pagaidām nav nevienas nodaļas.</span>
                    </div>

                    <!-- Nodaļu saraksts -->
                    <div v-for="chapter in classic_book.chapters" :key="chapter.id" class="chapter-item">
                        <span class="chapter-title">{{ chapter.name }}</span>
                        <!-- Nodaļu pārvaldības pogas -->
                        <div class="buttons-container">
                            <button class="edit-btn" @click="goToEdit(chapter.id)">Rediģēt</button>
                            <button class="delete-btn" @click="deleteChapter(chapter.id)">Dzēst</button>
                        </div>
                    </div>
                </div>

                <!-- Jaunas nodaļas pievienošanas poga -->
                <button class="add-chapter-btn" @click="GoToCreate">Pievienot nodaļu</button>
            </div>
        </div>
    </div>

    <Footer/>
</template>

<style scoped>
option::placeholder{
    color: rgba(26, 16, 8, 0.42); /* Krāsa vietturim */
    font-size: 1.0rem; /* izmērs */
}
.main-content {
    padding-bottom: 45px; /* Atstarpe apakšā */
}
.story-form {
    max-width: 800px; /* Maksimālais platums formai */
    margin: 0 auto; /* Centrēšana */
    padding: 20px; /* Iekšējā atstarpe */
}

.submit{
    color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
    font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
    border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
    background-color: #e4a27c; /* Fona krāsa */
    border-radius: 10px; /* Apmales noapaļojums */
    padding: 30px; /* Iekšējā atstarpe */
    box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px; /* Ēna */
}
h1 {
    font-size: 1.7rem;
    margin-top: 32px;
    margin-bottom: 40px;
    text-align: center; /* Centrēts teksts */
    color: rgba(26, 16, 8, 0.8);
    font-family: Tahoma, Helvetica, sans-serif;
    font-weight: bold; /* Treknraksts */
}

.form-group {
    margin-bottom: 20px; /* Atstarpe starp laukiem */
}

.form-group label {
    display: block; /* zem viena bloka */
    font-size: 1.1rem;
    margin-bottom: 8px; /* Atstarpe no ievadlaukuma */
    font-weight: bold;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%; /* Aizņem visu pieejamo platumu */
    padding: 10px; /* Iekšējā atstarpe */
    border: 1px solid rgba(26, 16, 8, 0.8); /* Apmale */
    border-radius: 4px; /* Noapaļoti stūri */
    background-color: white; /* Fona krāsa */
    font-size: 1rem;
}
.form-group input::placeholder,
.form-group textarea::placeholder,
.placeholder-option{
    color: rgba(26, 16, 8, 0.42);
    font-size: 1.0rem;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus{
    outline: none; /* Noņem standarta iezīmēšanu */
    box-shadow: none;
    background-color: #ffc8a9; /* Fona krāsa fokusa laikā */
}
.genre-options {
    display: flex;
    flex-wrap: wrap; /* Pārnešana jaunā rindā */
    gap: 10px; /* Atstatums starp elementiem */
}

.genre-checkbox {
    background-color: #ffd9c6;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer; /* Pelītes rādītājs */
    transition: 0.3s; /* Pārejas efekts */
    font-size: 1rem;
}
.genre-checkbox.selected {
    background-color: #ffc8a9;
}

.status-options {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 10px;
}

.status-checkbox {
    background-color: #ffd9c6;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    transition: 0.3s;
    font-size: 1rem;
}

.status-checkbox.selected {
    background-color: #ffc8a9;
}

button {
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 1.0rem;
}
.form-actions {
    display: flex;
    justify-content: flex-end; /* Pogas labajā pusē */
    gap: 15px;
    margin-top: 30px;
}
.submit-btn {
    border: 2px solid rgba(26, 16, 8, 0.8);
    background-color: #c58667;
    color: rgba(26, 16, 8, 0.8);
    padding: 5px 20px;
}
.error-message{
    font-size: 1rem;
    color:rgb(127,29,29);
}

.chapters-container {
    padding: 20px;
    font-family: Tahoma, Helvetica, sans-serif;
    border: 1px solid rgba(26, 16, 8, 0.8);
    background-color: #e4a27c;
    border-radius: 10px;
    box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px;
}

.chapter{
    margin-top: 70px;
}

.chapter-list {
    margin-bottom: 20px;
}

.chapter-item {
    background-color: #ffd9c6;
    border-radius: 8px;
    text-align: center;
    box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 10px;
    font-family: Tahoma, Helvetica, sans-serif;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 15px;
    margin-bottom: 10px;
}

.chapter-title {
    color: rgba(26, 16, 8, 0.8);
    font-family: Tahoma, Helvetica, sans-serif;
    font-size: 1.1rem;
    font-weight: bold;
}

.delete-btn {
    padding: 3px 15px;
    border: 2px solid rgba(35, 11, 11, 0.8);
    background-color: #714e3e;
    border-radius: 4px;
    cursor: pointer;
}

.edit-btn{
    background-color: #c58667;
    border: 2px solid rgba(26, 16, 8, 0.8);
    padding: 3px 12px;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background-color: #ffc8a9;
    border-color: #ffc8a9;
}

.chapter-item button {
    margin-left: 10px;
}

.chapter-item .buttons-container {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.add-chapter-btn {
    background-color: #c58667;
    border: 2px solid rgba(26, 16, 8, 0.8);
    display: block;
    width: 100%;
    padding: 12px;
}

@media (max-width: 500px) {
    h1 {
        font-size: 1.5rem;
    }
    .form-group label {
        font-size: 1rem;
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
    .status-checkbox
    .genre-checkbox {
        font-size: 0.9rem;
        padding: 7px 8px;
    }
    .chapter-title{
        font-size: 1rem;
    }
    .chapter-item .buttons-container {
        gap: 3px;
    }
    .chapter-item button {
        margin-left: 3px; /* Отступ между кнопками */
    }

    .delete-btn {
        padding: 3px 12px;
        font-size: 0.9rem;
    }

    .edit-btn{
        padding: 3px 9px;
        font-size: 0.9rem;
    }
    .add-chapter-btn {
        font-size: 0.9rem;
    }
    .submit-btn{
        font-size: 0.9rem;
        padding: 5px 15px;
    }
    .error-message{
        font-size: 0.9rem;
    }
}
</style>

