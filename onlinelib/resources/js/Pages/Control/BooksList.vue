<script setup>
    import { router, usePage } from '@inertiajs/vue3';
    import Footer from "@/Components/Footer.vue";
    import Navbar from "@/Components/Navbar.vue";
    import {route} from "ziggy-js";

    // Saņemam stāstu sarakstu no servera
    const books = usePage().props.book;
    const classicBooks = usePage().props.classicBooks;

    const deleteBook = (id) => {
        if (confirm('Vai tiešām vēlaties dzēst šo darbu?')) {
            router.delete(route('classic_destroyBook', { id }), {
                onSuccess: () => {
                    alert('Darbs veiksmīgi dzēsts!');
                    window.location.reload();

                },
            });
        }
    };

    const GoToEdit = (bookId) => {
        router.get(route('EditClassicBook', { id: bookId }));
    };

    const GoToCreate = () => {
        router.get(route('NewBook'));
    };

    const deleteClassicBook = (id) => {
        if (confirm('Vai tiešām vēlaties dzēst šo darbu?')) {
            router.delete(route('classic_books.destroy', { id }), {
                onSuccess: () => {
                    alert('Darbs veiksmīgi dzēsts!');
                    window.location.reload();

                },
            });
        }
    };
</script>

<template>

    <Navbar />


    <div class="main-content">
        <!-- Darbu pārvaldības sadaļa -->
        <div class="story-form">

            <h1>Grāmatas un stāsti</h1>


            <div class="container">
                <div class="list">
                    <!-- Paziņojums, ja nav pieejamu darbu -->
                    <h2>Stāstu saraksts</h2>
                    <div v-if="books.length === 0" class="item">
                        <span class="title">Šeit vēl nav pievienotu darbu.</span>
                    </div>

                    <!-- Darbu saraksts -->
                    <div v-for="book in books" :key="book.id" class="work-item">
                        <!-- Darba virsraksts -->
                        <h2>{{ book.name }}</h2>

                        <!-- Darba apraksts -->
                        <p class="description">{{ book.description }}</p>

                        <div class="work-meta">
                            <!-- Autora informācija -->
                            <span class="author">Autors: {{ book.user?.nickname || 'Anonīms' }}</span>


                            <div class="work-actions">
                                <!-- Dzēšanas poga -->
                                <button
                                    class="delete-btn"
                                    @click="deleteBook(book.id)"
                                >
                                    Dzēst
                                </button>
                            </div>
                        </div>
                    </div>

                    <h2>Grāmatu saraksts</h2>
                    <div v-if="classicBooks.length === 0" class="item">
                        <span class="title">Šeit vēl nav pievienotu darbu.</span>
                    </div>
                    <!-- Darbu saraksts -->
                    <div v-for="classicBook in classicBooks" :key="classicBook.id" class="work-item">
                        <!-- Darba virsraksts -->
                        <h2>{{ classicBook.name }}</h2>

                        <!-- Darba apraksts -->
                        <p class="description">{{ classicBook.description }}</p>

                        <div class="work-meta">
                            <!-- Autora informācija -->
                            <span class="author">Autors: {{ classicBook.Author_name }} {{ classicBook.Author_surname }}</span>


                            <div class="work-actions">
                                <!-- Dzēšanas poga -->
                                <button
                                    class="delete-btn"
                                    @click="deleteClassicBook(classicBook.id)"
                                >
                                    Dzēst
                                </button>
                                <button class="edit-btn" @click="GoToEdit(classicBook.id)">Rediģēt</button>
                            </div>
                        </div>
                    </div>
                    <!-- Jauna stāsta izveides sadaļa -->
                    <div class="new_section">
                        <div class="new" @click="GoToCreate">
                            <h2>Pievienot jaunu grāmatu</h2>
                            <i class="fa">&#xf055;</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Footer />
</template>

<style scoped>

    .main-content {
        padding-bottom: 45px; /* Apakšējais atstatums */
    }

    .story-form {
        max-width: 800px; /* Maksimālais platums */
        margin: 0 auto; /* Centrē forma horizontāli */
        padding: 20px; /* Piepildījums ap saturu */
    }

    h1 {
        font-size: 1.7rem; /* Fonta lielums */
        margin-top: 32px;
        margin-bottom: 40px;
        text-align: center; /* Centrēts teksts */
        color: rgba(26, 16, 8, 0.8); /* Krāsa */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
        font-weight: bold; /* Trekns fonts */
    }

    h2{
        font-size: 1.1rem; /* Fonta lielums */
        text-align: center; /* Centrēts teksts */
        color: rgba(26, 16, 8, 0.8); /* Krāsa */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
        font-weight: bold; /* Trekns fonts */
    }

    .list {
        margin-bottom: 20px;
        display: flex; /* Flexbox izkārtojums */
        flex-direction: column; /* Elementi kolonnā */
        gap: 20px; /* Atstarpe starp elementiem */
    }

    .item {
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
        background-color: #e4a27c;
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

    .new_section {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-top: 20px;
    }

    .new {
        border: 2px dashed rgba(26, 16, 8, 0.8); /* Pārtraukta apmale */
        background-color: #ffd9c6; /* Fona krāsa */
        padding: 20px; /* Iekšējās atstarpes */
        border-radius: 4px; /* Noapaļoti stūri */
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px; /* Ēnas efekts */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-align: center;
        cursor: pointer; /* Peles kursors mainās uz pointer */
        height: 9rem; /* Fiksēts augstums */
        transition: all 0.3s; /* Pāreju efekts */
    }

    .new:hover {
        transform: translateY(-3px); /* Nedaudz paceļas uz augšu */
        background-color: #ffc8a9; /* Fona krāsa mainās */
        border: none; /* Noņem apmali */
    }

    .work-item {
        border: 1px solid rgba(26, 16, 8, 0.8);
        background-color: #e4a27c;
        padding: 20px;
        border-radius: 4px;
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px;
    }

    .work-item h2,
    .new h2{
        font-family: Tahoma, Helvetica, sans-serif;
        font-weight: bold;
        margin-top: 0; /* Noņem noklusēto atstarpi */
        color: rgba(106, 51, 0, 0.8);
        font-size: 1.1rem;
    }

    .new .fa {
        text-align: center;
        align-items: center;
        justify-content: center;
        margin-top: 0;
        font-size: 3rem;
    }

    .description {
        font-family: Tahoma, Helvetica, sans-serif;
        font-size: 1rem;
        color: rgba(26, 16, 8, 0.8);
    }
    .author {
        font-family: Tahoma, Helvetica, sans-serif;
        font-size: 0.9rem;
    }

    .work-meta {
        display: flex;
        justify-content: space-between; /* Elementi izvietoti pa labi un kreisi */
        align-items: center;
        margin-top: 15px;
        color: rgba(26, 16, 8, 0.7);
    }

    .work-actions {
        display: flex;
        gap: 10px;
    }

    .delete-btn {
        font-family: Tahoma, Helvetica, sans-serif;
        padding: 3px 15px;
        border: 2px solid rgba(35, 11, 11, 0.8);
        background-color: #714e3e;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s; /* Pāreja uz mainīgām īpašībām */
    }

    .edit-btn{
        font-family: Tahoma, Helvetica, sans-serif;
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

    @media (max-width: 500px) {
        h1 {
            font-size: 1.5rem;
        }
        h2{
            font-size: 1.0rem;
        }
        .title{
            font-size: 1rem;
        }

        .work-item h2,
        .new h2{
            font-size: 1rem;
        }

        .new .fa{
            font-size: 2.8rem;
        }

        .description {
            font-size: 0.9rem;
        }

        .author{
            font-size: 0.8rem;
        }

        .delete-btn {
            padding: 3px 12px;
            font-size: 0.9rem;
        }
        .edit-btn{
            padding: 3px 9px;
            font-size: 0.9rem;
        }

    }
</style>
