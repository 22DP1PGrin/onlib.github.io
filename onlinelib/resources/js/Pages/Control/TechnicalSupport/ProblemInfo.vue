<script setup>
    import { router, usePage } from '@inertiajs/vue3';
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";
    import {route} from "ziggy-js";

    // Saņemam formas datus no servera
    const form = usePage().props.form;


    const deleteForm = (id) => {
        if (confirm('Vai tiešām vēlaties dzēst pieteikumu?')) {
            router.delete(route('problems.destroy', { id }), {
                preserveScroll: true, // Saglabā lapas ritinājuma pozīciju
                onSuccess: () => {
                    alert('Pieteikums veiksmīgi dzēsts.');
                },
            });
        }
    };
</script>

<template>
    <!-- Navigācijas josla -->
    <Navbar />

    <!-- Galvenais satura bloks -->
    <div class="support-page">
        <div class="main-content">

            <h1>Detalizēta informācija</h1>

            <div class="contact-form">
                <!-- Lietotāja segvārds -->
                <div class="form-group">
                    <h2>Segvārds:</h2>
                    <div class="info">{{ form.nickname }}</div>
                </div>

                <!-- E-pasta adrese -->
                <div class="form-group">
                    <h2>E-pasta adrese:</h2>
                    <div class="info">{{ form.email }}</div>
                </div>

                <!-- Pieteikuma tēma -->
                <div class="form-group">
                    <h2>Tēma:</h2>
                    <div class="info">{{ form.subject }}</div>
                </div>

                <!-- Pieteikuma saturs -->
                <div class="form-group">
                    <h2>Ziņojums:</h2>
                    <div class="info-text">{{ form.problem }}</div>
                </div>

                <!-- Dzēšanas poga -->
                <button
                    class="delete-btn"
                    @click="deleteForm(form.id)"
                    aria-label="Dzēst pieteikumu"
                >
                    Dzēst pieteikumu
                </button>
            </div>
        </div>
    </div>

    <!-- Lapas kājene -->
    <Footer />
</template>



<style scoped>
/* Galvenais konteiners */
.support-page {
    max-width: 800px; /* Maksimālais platums */
    margin: 0 auto; /* Centrēšana */
    padding: 20px; /* Iekšējā atstarpe */
    font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
    line-height: 1.6; /* Teksta augstums */
    color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
}

/* Kontaktformas stils */
.contact-form {
    background-color: #e4a27c; /* Fona krāsa */
    padding: 20px; /* Iekšējā atstarpe */
    border-radius: 8px; /* Noapaļotie stūri */
    border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
    box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px; /* Ēna */
    margin-bottom: 60px;
}

/* Apraksta stils */
.main-content p {
    margin-top: -10px; /* Atstarpe no augšas */
    font-size: 1.0rem; /* Teksta izmērs */
    margin-bottom: 25px; /* Atstarpe no apakšas */
}

/* Virsraksta stils */
h1 {
    font-size: 1.7rem; /* Teksta izmērs */
    font-weight: bold; /* Treknraksts */
    text-align: center; /* Centrē tekstu */
    margin-top: 32px;
    margin-bottom: 40px;
}

/* Apakšvirsraksta stils */
h2 {
    font-weight: bold; /* Treknraksts */
    font-size: 1.1rem; /* Teksta izmērs */
    margin-top: 20px; /* Atstarpe no augšas */
    margin-bottom: 5px; /* Atstarpe no apakšas */
}

/* Formas grupu stils */
.form-group {
    margin-bottom: 15px; /* Atstarpe starp formu grupām */
}

/* Etiķešu stils */
label {
    display: block; /* Parāda kā bloku elementu */
    margin-bottom: 5px; /* Atstarpe no apakšas */
    font-weight: bold; /* Treknraksts */
}

/* Opciju stils */
.subject option {
    background-color: white; /* Fona krāsa opcijām */
    color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
}

/* Ievades lauku stils */
.info {
    width: 100%; /* Pilns platums */
    background-color: white;
    padding: 10px; /* Iekšējā atstarpe */
    border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
    border-radius: 4px; /* Noapaļotie stūri */
    font-size: 1rem; /* Teksta izmērs */
}

.info-text {
    width: 100%; /* Pilns platums */
    background-color: white;
    padding: 10px; /* Iekšējā atstarpe */
    border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
    border-radius: 4px; /* Noapaļotie stūri */
    font-size: 1rem; /* Teksta izmērs */
    height: 140px;
}



/* Pogas stils */
button {
    border: 2px solid rgba(35, 11, 11, 0.8);
    background-color: #714e3e;
    padding: 5px 25px; /* Iekšējā atstarpe */
    border-radius: 4px; /* Noapaļotie stūri */
    font-size: 1.0rem; /* Teksta izmērs */
    cursor: pointer; /* Kursora izskats */
    margin-top: 20px;
    display: flex
}

/* Pogas stils, kad pele ir virs tās */
button:hover {
    background-color: #ffc8a9;
    border-color: #ffc8a9;
}

/* Papildu informācijas stils */
.additional-info p {
    margin-top: 30px; /* Atstarpe no augšas */
    font-size: 1.0rem; /* Teksta izmērs */
    margin-bottom: 25px; /* Atstarpe no apakšas */
}

/* Saites stils */
a {
    text-decoration: none; /* Noņem apakšsvītrojumu */
    color: rgba(106, 51, 0, 0.8); /* Teksta krāsa */
}
a:hover{
    color: rgba(26, 16, 8, 0.8);
}

@media (max-width: 500px) {

    .info,
    .info-text{
        font-size: 0.9rem; /* Teksta izmērs */
    }
    h1{
        font-size: 1.5rem;
    }

    h2{
        font-size: 1rem;
    }
}
</style>
