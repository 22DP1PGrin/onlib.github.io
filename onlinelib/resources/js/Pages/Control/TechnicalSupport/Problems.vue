<script setup>
    import {router, usePage} from '@inertiajs/vue3';
    import {computed, ref} from 'vue';
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";

    // Saņemam tehniskās atbalsta formas no servera
    const forms = computed(() => usePage().props.technical_support_form);

    const search = ref(usePage().props.filters?.search || '');

    // Funkcija, lai apskatītu konkrētu formu
    const viewForm = (id) => {
        router.visit(`/Forms/${id}`);
    };

    // Meklēt jautaumu pēc lietotājvārdu
    const searchUsers = () => {
        router.get(route('problems'),
            { search: search.value },
            {
                preserveState: true,
                replace: true,
            }
        );
    };
</script>

<template>

    <Navbar/>

    <!-- Galvenais satura bloks -->
    <div class="main-content">

        <div class="search">
            <input
                v-model="search"
                type="text"
                class="input"
                placeholder="Meklēt jautājumu..."
            >
            <button class="btn" @click="searchUsers">
                <i class="fa bar">&#xf002;</i>
            </button>
        </div>

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

/* Meklēšanas josla */
.search {
    display: flex;  /* Flexbox izkārtojums konta sadaļai */
    justify-content: center;
    align-items: center;  /* Elementu vertikāla izlīdzināšana */
    margin: 80px auto;
    max-width: 800px;
    margin-bottom: 30px;
}

.search:hover {
    transform: none; /*noņemam transformāciju, kad pele tiek pārvilkta */
}

.search .input {
    background-color: #ffffff; /*Krasa fona */
    border: 0; /* Noņemam apmales */
    border-radius: 20px; /* Noapaļo apmalas*/
    border-color: rgba(26, 16, 8, 0.8); /* Mainam apmales krāsu */
    font-size: 1rem; /* Fonta izmērs */
    padding: 10px; /* Iekšējās atstarpes */
    height: 15%;
    width: 90%; /* Sakam ar nulles platumu */
}

/* Poga meklēšanai */
.search .btn {
    background-color: #c58667;
    border: 2px solid rgba(26, 16, 8, 0.8); /*apmales vērtības */
    padding: 0;
    border-radius: 20px;
    cursor: pointer; /* Peles formāts */
    outline: none; /* Noņemam noklusēto apmales stāvokli */
    margin-left: 7px; /* Atstarpe no labās puses */
    width: 41px;
    height: 40px;
    transition: border-color 0.3s;
}

.btn .fa{
    font-size: 20px;
    text-align: center;
    transition: color 0.3s !important;
}

.input{
    color: rgba(26, 16, 8, 0.8);
    font-family: Tahoma, Helvetica, sans-serif; /* Fonta tips */
}
.search input::placeholder {
    color: rgba(26, 16, 8, 0.42); /* Krāsa */
}

.input:focus {
    outline: none !important; /* Noņemam noklusēto apmales stāvokli */
    box-shadow: none !important;
    background-color: #ffd9c6; /* Fona krāsa */
}
.btn:hover {
    border-color: rgba(255, 187, 142, 0.8); /* Mainam apmales krāsu, kad pele tiek pārvilkta */
}
.btn:hover .fa {
    color: rgba(255, 187, 142, 0.8); /* Mainam ikonas krāsu, kad pele tiek pārvilkta */
}
.fa{
    color: rgba(26, 16, 8, 0.8);  /* Fonta krāsa */
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
    .search .input {
        font-size: 0.9rem; /* Fonta izmērs */
        height: 30px;
        width: 75%;
    }

    /* Poga meklēšanai */
    .search .btn {
        padding: 0;
        width: 34px;
        height: 34px;
    }

    .btn .fa{
        font-size: 18px;
    }

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
