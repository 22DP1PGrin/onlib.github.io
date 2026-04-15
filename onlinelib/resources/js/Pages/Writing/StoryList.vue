<script setup>
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";
    import ConfirmModal from "@/Components/Modal/ConfirmModal.vue";
    import SuccessModal from "@/Components/Modal/SuccessModal.vue";
    import { computed, ref } from "vue";
    import { router } from "@inertiajs/vue3";
    import { route } from "ziggy-js";

    // Komponenta ievaddati
    const props = defineProps({
        works: Array,
    });

    // Cik grāmatas rādīt sākumā
    const limit = 3;

    // Kontrolē, vai rādīt visas grāmatas vai tikai limit skaitu
    const showAllBooks = ref(false);

    // Saglabā izvēlētās grāmatas ID dzēšanai
    const selectedBookId = ref(null);

    // Modālo logu stāvokļi
    const showDeleteModal = ref(false);
    const showSuccesModal = ref(false);

    // Atver dzēšanas apstiprinājuma modāli
    const openDeleteModal = (id) => {
        selectedBookId.value = id;
        showDeleteModal.value = true;
    };

    // Aprēķina redzamās grāmatas atkarībā no showAllBooks stāvokļa
    const visibleBooks = computed(() =>
        showAllBooks.value
            ? props.works ?? []
            : (props.works ?? []).slice(0, limit)
    );

    // Pāriet uz jaunas grāmatas izveides lapu
    const GoToCreate = () => {
        router.get(route('NewStory'));
    };

    // Pāriet uz esošās grāmatas rediģēšanas lapu
    const GoToEdit = (bookId) => {
        router.get(route('EditStory', { id: bookId }));
    };

    // Datuma formatēšana latviešu valodas formātā
    const formatDate = (timestamp) => {
        return new Date(timestamp).toLocaleDateString("lv-LV", {
            year: 'numeric',    // Gads kā skaitlis (piem., 2024)
            month: '2-digit',   // Mēnesis ar 2 cipariem (01-12)
            day: '2-digit'      // Diena ar 2 cipariem (01-31)
        });
    };

    // Apstiprina un dzēš izvēlēto grāmatu
    const confirmDelete = () => {
        router.delete(route('deleteStory', { id: selectedBookId.value }), {
            preserveScroll: true,
            onSuccess: () => {
                showDeleteModal.value = false;
                showSuccesModal.value = true;
            },
        });
    };
</script>

<template>
    <!-- Navigācijas josla -->
    <Navbar />

    <!-- Grāmatas dzēšanas apstiprinājuma modālis -->
    <ConfirmModal
        :is-open="showDeleteModal"
        title="Vai tiešām vēlaties dzēst šo grāmatu?"
        confirm-text="Dzēst"
        @confirm="confirmDelete"
        @cancel="showDeleteModal = false"
    />

    <!-- Veiksmīgas grāmatas dzēšanas modālis -->
    <SuccessModal
        :is-open="showSuccesModal"
        title="Grāmata veiksmīgi dzēsta!"
        @close="showSuccesModal = false"
    />

    <div class="main-content">
        <div class="content">
            <div class="content-panel">
                <div class="profile-header">
                    <h1>Mani darbi</h1>
                </div>

                <!-- Jauna stāsta izveides sadaļa -->
                <div class="new_section">
                    <div class="new" @click="GoToCreate">
                        <h2>Izveidot jaunu stāstu</h2>
                        <i class="fa">&#xf055;</i>
                    </div>
                </div>

                <div class="my-works-section">
                    <!-- Darbu saraksts -->
                    <div class="works-list">
                        <!-- Atkārtojas katram darbam-->
                        <div v-for="work in visibleBooks" :key="work.id" class="work-item" :class="{ 'blocked': work.is_blocked }">

                            <div v-if="work.is_blocked" class="warning">
                                <p><i style="font-size:24px" class="fa">&#xf023;</i> Stāsts ir bloķēts!</p>
                            </div>

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
                                    <button class="edit-btn" :class="{ 'blocked': work.is_blocked }" @click="GoToEdit(work.id)">Rediģēt</button>
                                    <button class="delete-btn" @click="openDeleteModal(work.id)">Dzēst</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Skatīt vairāk -->
                <div class="toggle-container">
                    <button
                        v-if="props.works && props.works.length > limit"
                        class="toggle-btn"
                        @click="showAllBooks = !showAllBooks"
                    >
                        {{ showAllBooks ? 'Paslēpt' : 'Skatīt vairāk' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Kājene -->
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
        margin-bottom: 20px;
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
        position: relative;
        border: 1px solid rgba(26, 16, 8, 0.8);
        background-color: #e4a27c;
        padding: 20px;
        border-radius: 4px;
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px;
    }

    .warning{
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        text-align: center;
        padding: 8px 0;
        font-weight: bold;
        z-index: 2;
        border: 2px dashed rgba(35, 11, 11, 0.8);
        background-color: #714e3e;
        font-size: 1rem;
    }

    .work-item.blocked {
        padding-top: 60px;
        background-color: #cda08c;
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

    .toggle-container {
        margin-top: 10px;
        display: flex;
        justify-content: center;
    }


    button {
        border: 2px solid rgba(26, 16, 8, 0.8);
        background-color: #c58667; /* Fona krāsa */
        color: rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        font-size: 1.0rem;
        transition: background-color 0.3s, border-color 0.3s; /* Pārejas efekts, lai uzlabotu interaktivitāti */
        cursor: pointer; /* Kursora izmaiņas pie pogas */
        padding: 5px 10px;
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

    .edit-btn.blocked {
        background-color: #a17460;
    }

    .edit-btn.blocked:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;
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

