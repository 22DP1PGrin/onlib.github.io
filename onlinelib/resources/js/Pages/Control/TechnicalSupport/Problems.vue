<script setup>
    import { router, usePage } from '@inertiajs/vue3';
    import { computed, ref } from 'vue';
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";
    import { route } from "ziggy-js";
    import ConfirmModal from "@/Components/Modal/ConfirmModal.vue";
    import SuccessModal from "@/Components/Modal/SuccessModal.vue";
    import SearchComponent from "@/Components/SearchComponent.vue";

    // Saņem tehniskās atbalsta formas no servera
    const forms = computed(() => usePage().props.technical_support_form);

    // Uzglabā izvēlēto jautājumu dzešanai
    const selectedProblem = ref(null);

    // Saglabā meklēšanas ievadi
    const search = ref(usePage().props.filters?.search || '');

    const limit = 3; // Cik jautājumus rādīt sākumā

    // Pārslēdzami statusi, vai rādīt visu sarakstu
    const showAllForms = ref(false);

    // Aprēķina redzamās pietekumus
    const visibleForms = computed(() =>
        showAllForms.value
            ? forms.value
            : forms.value?.slice(0, limit) ?? []
    );

    // Modālo logu stāvokļi
    const showSuccessModal = ref(false);
    const showDeleteModal = ref(false);

    // Atver dzēšanas modālo logu
    const openDeleteModal = (problem) => {
        selectedProblem.value = problem;
        showDeleteModal.value = true;
        document.body.style.overflow = "hidden";
    };

    // Meklēšanas funkcija pēc lietotājvārda
    const searchQuestions = () => {
        router.get(route('problems'),
            { search: search.value },
            {
                preserveState: true, // Saglabā lapas stāvokli
                replace: true,       // Aizstāj URL
            }
        );
    };

    // Apstiprina ziņojuma dzēšanu
    const confirmDelete = () => {
        if (!selectedProblem.value) return;

        router.delete(
            route('problems.destroy', { id: selectedProblem.value.id }),
            {
                preserveScroll: true,
                onSuccess: () => {
                    showDeleteModal.value = false;
                    showSuccessModal.value = true;
                }
            }
        );
    };

    // Lejupielādēt PDF ar visām formām
    const downloadPdf = () => {
        window.location.href = route('admin.forms.pdf');
    };
</script>

<template>
    <!-- Navigācijas josla -->
    <Navbar />

    <div class="main-content">

        <!-- Dzēšanas apstiprinājuma modālais logs -->
        <ConfirmModal
            :is-open="showDeleteModal"
            title="Vai tiešām vēlaties dzēst šo jautājumu?"
            confirm-text="Dzēst"
            @confirm="confirmDelete"
            @cancel="showDeleteModal = false"
        />

        <!-- Veiksmīgas dzēšanas modālais logs -->
        <SuccessModal
            :is-open="showSuccessModal"
            title="Jautājums veiksmīgi dzēsts!"
            @close="showSuccessModal = false"
        />

        <!-- PDF lejupielādes poga -->
        <div class="pdf-wrapper">
            <button @click="downloadPdf" class="pdf">
                Lejupielādēt PDF
            </button>
        </div>

        <!-- Meklēšanas josla -->
        <SearchComponent
            v-model="search"
            placeholder="Meklēt jautājumu..."
            @search="searchQuestions"
        />

        <!-- Tehniskā atbalsta pieteikumu saraksts -->
        <div class="story-form">

            <h1>Lietotāju jautājumi</h1>

            <div class="container">
                <div class="list">
                    <!-- Paziņojums, ja nav pieejamu pieteikumu -->
                    <div v-if="forms.length === 0" class="item">
                        <span class="title">Šeit vēl nav jautājumu.</span>
                    </div>

                    <!-- Saraksts ar pieteikumiem -->
                    <div v-for="form in visibleForms" :key="form.id" class="work-item">
                        <!-- Lietotājvārds -->
                        <h2>{{ form.nickname }}</h2>

                        <!-- Pieteikuma saturs -->
                        <p><b>Iemesls:</b> {{ form.subject }}</p>
                        <p><b>Pamatojums:</b> {{ form.problem }}</p>

                        <div class="work-meta">

                            <div class="work-actions">
                                <!-- Dzēšana, apskatīšana -->
                                <button class="delete-btn" @click="openDeleteModal(form)">
                                    Dzēst
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Skatīt vairāk -->
                    <button class="toggle-btn"
                            @click="showAllForms = !showAllForms"
                            v-if="forms.length > limit">
                        {{ showAllForms ? 'Paslēpt' : 'Skatīt vairāk' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Kājene -->
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

    .pdf-wrapper {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
        margin-right: 20px;
    }

    .pdf{
        padding: 2px 15px;
        margin-left: auto;
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
    }

    p{
        font-size: 1rem; /* Fonta lielums */
        color: rgba(26, 16, 8, 0.8); /* Krāsa */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
        word-wrap: break-word;
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

    .work-item {
        border: 1px solid rgba(26, 16, 8, 0.8);
        background-color: #e4a27c;
        padding: 20px;
        border-radius: 4px;
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px;
        flex-direction: column;
    }

    .work-item h2,
    .new h2{
        font-family: Tahoma, Helvetica, sans-serif;
        font-weight: bold;
        margin-top: 0; /* Noņem noklusēto atstarpi */
        color: rgba(106, 51, 0, 0.8);
        font-size: 1.1rem;
    }

    .work-meta {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-top: 15px;
        color: rgba(26, 16, 8, 0.7);
    }

    .work-actions {
        display: flex;
        gap: 10px;
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
        font-family: Tahoma, Helvetica, sans-serif;
        padding: 3px 15px;
        border: 2px solid rgba(35, 11, 11, 0.8);
        background-color: #714e3e;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s; /* Pāreja uz mainīgām īpašībām */
    }

    .toggle-btn{
        font-family: Tahoma, Helvetica, sans-serif;
        background-color: #c58667;
        border: 2px solid rgba(26, 16, 8, 0.8);
        align-self: center;
        padding: 6px 20px;
        border-radius: 4px;
        cursor: pointer;
        transition: all
        0.3s; /* Pāreja uz mainīgām īpašībām */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
    }

    button:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;
    }

    label {
        font-weight: bold;
        text-align: left;
        color: rgba(26, 16, 8, 0.8); /* Krāsa */
    }

    textarea::placeholder {
        color: rgba(26, 16, 8, 0.42);
        font-size: 1.0rem;
    }

    .form-group select,
    .form-group textarea {
        padding: 10px;
        border: 1px solid rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        font-size: 1rem;
    }

    option{
        font-size: 1rem;
    }

    .form-group textarea {
        resize: vertical; /* Atļauj tekstlaukam mainīt izmērus vertikāli */
        min-height: 100px; /* Minimālais augstums */
    }

    textarea:focus,
    input:focus {
        outline: none; /* Noņem apmales fokusa režīmā */
        box-shadow: none; /* Noņem nokrāsu ap laukiem */
        background-color: #ffc8a9; /* Fona krāsa, kad lauks ir fokusēts */
    }

    @media (max-width: 500px) {
        h1 {
            font-size: 1.5rem;
        }

        .pdf{
            padding: 1px 10px;
        }

        p,
        label,
        select,
        option,
        button
        {
            font-size: 0.9rem;
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

        .delete-btn {
            padding: 2px 8px;
            font-size: 0.9rem;
        }

        input::placeholder,
        textarea::placeholder{
            font-size: 0.9rem;
        }

        .success-container h2{
            font-size: 1.1rem;
        }
    }
</style>
