<script>
import Navbar from "@/Components/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import { ref } from "vue";
import {router} from '@inertiajs/vue3';
import {route} from "ziggy-js";
import { usePage } from '@inertiajs/vue3';

export default {
    components: {
        Navbar,
        Footer,
    },
    props: {
        works: Array,
    },
    setup() {
        // Iegūt lapas datus un sagatavot darbu sarakstu
        const page = usePage();
        const works = ref(page.props.works);

        // Navigācijas funkcija uz stāsta izveides lapu
        const GoToCreate = () => {
            router.get(route('NewStory'));
        };

        // Navigācijas funkcija uz stāsta rediģēšanas lapu
        const GoToEdit = (bookId) => {
            router.get(route('EditStory', { id: bookId }));
        };

        // Datuma formāta funkcija (latviešu valodā)
        const formatDate = (timestamp) => {
            return new Date(timestamp).toLocaleDateString("lv-LV", {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            });
        };

        // Stāsta dzēšanas funkcija
        const deleteStory = (bookId) => {
            if (confirm('Vai tiešām vēlaties dzēst šo stāstu un visas tā nodaļas?')) {
                axios.delete(route('deleteStory', { id: bookId }))
                    .then(response => {
                        alert('Stāsts un visas nodaļas tika veiksmīgi dzēstas!');
                        window.location.reload(); // Pārlādēt lapu, lai atspoguļotu izmaiņas
                    })
                    .catch(error => {
                        console.error(error);
                        alert('Radās kļūda, mēģiniet vēlreiz.');
                    });
            }
        };

        return {
            works,
            GoToCreate,
            formatDate,
            GoToEdit,
            deleteStory
        };
    }
};
</script>

<template>
    <!-- Galvenā lapas struktūra -->
    <Navbar />

    <div class="main-content">
        <div class="content">
            <div class="content-panel">
                <!-- Virsraksta sadaļa -->
                <div class="profile-header">
                    <h1>Mani darbi</h1>
                </div>

                <!-- Galvenā darbu saraksta sadaļa -->
                <div class="my-works-section">
                    <!-- Darbu saraksts -->
                    <div class="works-list">
                        <!-- Atkārtojas katram darbam no works masīva -->
                        <div v-for="work in works" :key="work.id" class="work-item">
                            <!-- Darba nosaukums -->
                            <h2>{{ work.name }}</h2>
                            <!-- Darba apraksts -->
                            <p class="description">{{ work.description }}</p>
                            <!-- Papildinformācija un darbību pogas -->
                            <div class="work-meta">
                                <!-- Datums, kad darbs izveidots -->
                                <span class="created">Izveidots: {{ formatDate(work.created_at) }}</span>
                                <!-- Rediģēšanas un dzēššanas pogas -->
                                <div class="work-actions">
                                    <button class="edit-btn" @click="GoToEdit(work.id)">Rediģēt</button>
                                    <button class="delete-btn" @click="deleteStory(work.id)">Dzēst</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Jauna stāsta izveides sadaļa -->
                    <div class="new_section">
                        <div class="new" @click="GoToCreate">
                            <h2>Izveidot jaunu stāstu</h2>
                            <i class="fa">&#xf055;</i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Footer/>
</template>

<style scoped>
    .main-content {
        padding: 20px; /* Iekšējās atstarpes */
        margin-top: 55px; /* Atstarpe no navigācijas joslas */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa - tumši brūna ar caurspīdīgumu */
        font-family: Tahoma, Helvetica, sans-serif; /* Rakstzīmju fonts */
        padding-bottom: 60px; /* Papildus atstarpe apakšā */
    }


    .content {
        max-width: 1000px; /* Maksimālais platums */
        margin: 0 auto; /* Centrēšana */
    }

    .profile-header {
        text-align: center; /* Teksta centrēšana */
        margin-bottom: 20px; /* Atstarpe apakšā */
        margin-top: -20px; /* Negatīva atstarpe augšpusē */
    }

    .profile-header h1 {
        font-weight: bold; /* Treknraksts */
        font-size: 1.7rem; /* Izmērs */
        margin-bottom: 10px;
    }

    .works-list {
        display: flex; /* Flexbox izkārtojums */
        flex-direction: column; /* Elementi kolonnā */
        gap: 20px; /* Atstarpe starp elementiem */
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
    .new h2 {
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
        font-size: 1rem;
        word-wrap: break-word;
    }
    .created {
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
        padding: 5px 18px;
        border: 2px solid rgba(35, 11, 11, 0.8);
        background-color: #714e3e;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 1rem;
    }

    .edit-btn {
        background-color: #c58667; /* Fona krāsa */
        border: 2px solid rgba(26, 16, 8, 0.8); /* Apmale */
        padding: 5px 15px; /* Atstarpes iekšpusē */
        border-radius: 4px; /* Noapaļoti stūri */
        cursor: pointer; /* Peles kursors */
        transition: all 0.3s; /* Pāreju efekts */
        font-size: 1rem; /* Teksta izmērs */
    }

    button:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;
    }

    @media (max-width: 500px) {

        h1 {
            font-size: 1.5rem;
        }

        .work-item h2,
        .new h2{
            font-size: 1rem;
        }
        .new .fa{
            font-size: 2.8rem;
        }
        .description{
            font-size: 0.9rem;
        }
        .created{
            font-size: 0.8rem;
        }
        .delete-btn {
            padding: 4px 13px;
            font-size: 0.9rem;
        }

        .edit-btn{
            padding: 4px 10px;
            font-size: 0.9rem;
        }
    }
</style>

