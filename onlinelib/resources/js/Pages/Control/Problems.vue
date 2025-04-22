<script setup>
    import {router, usePage} from '@inertiajs/vue3';
    import { computed } from 'vue';
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";

    // Saņemam tehniskās atbalsta formas no servera
    const forms = computed(() => usePage().props.technical_support_form);

    // Funkcija, lai apskatītu konkrētu formu
    const viewForm = (id) => {
        router.visit(`/Forms/${id}`);
    };
</script>

<template>

    <Navbar/>

    <!-- Galvenais satura bloks -->
    <div class="main-content">
        <!-- Formu pārvaldības sadaļa -->
        <div class="story-form">

            <h1>Lietotāju jautājumi</h1>

            <div class="container">
                <div class="list">
                    <!-- Paziņojums, ja nav pieejamu formu -->
                    <div v-if="forms.length === 0" class="item">
                        <span class="title">Šeit vēl nav jautājumu.</span>
                    </div>

                    <!-- Formu saraksts -->
                    <div v-for="form in forms" :key="form.id" class="item">
                        <!-- Lietotāja segvārda attēlošana -->
                        <span class="title">{{ form.nickname }}</span>

                        <div class="buttons-container">
                            <!-- Pogas, lai apskatītu formu -->
                            <button
                                class="view-btn"
                                @click="viewForm(form.id)"
                                aria-label="Apskatīt jautājumu"
                            >
                                Apskatīt
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lapas kājene -->
    <Footer/>
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

.list {
    margin-bottom: 20px;
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

button {
    padding: 3px 15px;
    background-color: #c58667;
    border: 2px solid rgba(26, 16, 8, 0.8);
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s; /* Pāreja uz mainīgām īpašībām */
}

button:hover {
    background-color: #ffc8a9;
    border-color: #ffc8a9;
}

@media (max-width: 500px) {
    h1 {
        font-size: 1.5rem;
    }
    .title{
        font-size: 1rem;
    }

    button {
        padding: 3px 12px;
        font-size: 0.9rem;
    }

}
</style>
