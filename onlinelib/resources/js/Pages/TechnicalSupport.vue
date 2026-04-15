<script setup>
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";
    import SuccessModal from "@/Components/Modal/SuccessModal.vue";
    import { ref } from "vue";
    import { route } from "ziggy-js";
    import {useForm} from "@inertiajs/vue3";

    // Modālā loga stāvoklis
    const showSuccessModal = ref(false);

    // Formas dati
    const form = useForm({
        nickname: "",   // Lietotāja vārds
        email: "",      // Lietotāja e-pasta adrese
        subject: "",    // Tēma
        problem: "",    // Ziņojums
    });

    // Formas iesniegšanas funkcija
    const submitForm = () => {
        form.post(route("support.store"), {
            onSuccess: () => {
                showSuccessModal.value = true;
                document.body.style.overflow = "hidden";
                form.reset();
            },
        });
    };

    // Aizver veiksmīgas nosūtīšanas modāli
    const closeSuccessModal = () => {
        showSuccessModal.value = false;
        document.body.style.overflow = "";
    };
</script>

<template>
    <!-- Navigācijas josla -->
    <Navbar />

    <!-- Veiksmīgas nosūtīšanas modālais logs -->
    <SuccessModal
        :is-open="showSuccessModal"
        title="Ziņojums veiksmīgi nosūtīts!"
        message="Paldies par jūsu ziņojumu! Mēs ar jums sazināsimies drīz."
        @close="closeSuccessModal"
    />

    <!-- Galvenais saturs -->
    <div class="support-page">
        <div class="main-content">
            <!-- Virsraksts -->
            <h1>Tehniskais atbalsts</h1>
            <!-- Apraksts -->
            <p>
                Tehniskais atbalsts nodrošina iespēju sazināties problēmu, jautājumu vai ieteikumu gadījumā.
                Visi iesniegtie ziņojumi tiek apstrādāti pēc iespējas ātrāk.
            </p>
        </div>

        <!-- Kontaktforma -->
        <div class="contact-form">
            <h2>Saziņas forma</h2>
            <form @submit.prevent="submitForm">
                <!-- Lietotājvārds -->
                <div class="form-group">
                    <label for="name">Lietotājvārds:</label>
                    <input
                        type="text"
                        id="name"
                        class="input"
                        v-model="form.nickname"
                        required
                        autocomplete="given-name"
                    />
                    <div v-if="form.errors.nickname" class="error-message">{{ errors.nickname[0] }}</div>
                </div>

                <!-- E-pasta adrese -->
                <div class="form-group">
                    <label for="email">E-pasta adrese:</label>
                    <input
                        type="email"
                        id="email"
                        class="input"
                        v-model="form.email"
                        autocomplete="username"
                        required
                    />
                    <div v-if="form.errors.email" class="error-message">{{ errors.email[0] }}</div>
                </div>

                <!-- Tēma -->
                <div class="form-group">
                    <label for="subject">Tēma:</label>
                    <select id="subject" class="subject" v-model="form.subject" required>
                        <option value="" disabled>Izvēlieties tēmu</option>
                        <option value="Tehniskas problēmas">Tehniskas problēmas</option>
                        <option value="Konts un pieteikšanās">Konts un pieteikšanās</option>
                        <option value="Satura jautājumi">Satura jautājumi</option>
                        <option value="Cits">Cits</option>
                    </select>
                    <div v-if="form.errors.subject" class="error-message">{{ errors.subject[0] }}</div>
                </div>

                <!-- Ziņojums -->
                <div class="form-group">
                    <label for="message">Ziņojums:</label>
                    <textarea id="message" v-model="form.problem" rows="5" required></textarea>
                    <div v-if="form.errors.problem" class="error-message">{{ errors.problem[0] }}</div>
                </div>

                <!-- Iesniegt poga -->
                <button type="submit">Nosūtīt</button>
            </form>
        </div>

        <!-- Papildu informācija -->
        <div class="additional-info">
            <p>
                Atbildes uz iesniegtajiem ziņojumiem parasti tiek sniegtas 24 stundu laikā.
            </p>
            <p>
                Pirms ziņojuma iesniegšanas ieteicams pārbaudīt
                <a href="/faq">biežāk uzdotos jautājumus</a>,
                kur pieejamas atbildes uz izplatītākajām problēmām.
            </p>
        </div>
    </div>
    <!-- Kājene -->
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
        margin: 0 auto;
        background-color: #e4a27c; /* Fona krāsa */
        padding: 20px; /* Iekšējā atstarpe */
        border-radius: 8px; /* Noapaļotie stūri */
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px; /* Ēna */
        width: 75%;
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
        margin-top: 0; /* Atstarpe no augšas */
        padding: 60px; /* Iekšējā atstarpe */
    }

    /* Apakšvirsraksta stils */
    h2 {
        font-weight: bold; /* Treknraksts */
        font-size: 1.4rem; /* Teksta izmērs */
        margin-top: 0; /* Atstarpe no augšas */
        margin-bottom: 0; /* Atstarpe no apakšas */
        text-align: center;
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
    input,
    select,
    textarea {
        width: 100%; /* Pilns platums */
        padding: 10px; /* Iekšējā atstarpe */
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
        border-radius: 4px; /* Noapaļotie stūri */
        font-size: 1rem; /* Teksta izmērs */
    }

    /* Teksta lauka stils */
    textarea {
        resize: vertical; /* Atļauj vertikālo izmēru maiņu */
    }

    /* Fokusa stils */
    select:focus,
    textarea:focus,
    .input:focus {
        outline: none; /* Noņem noklusēto fokusu */
        box-shadow: none; /* Noņem ēnu */
        background-color: #ffd9c6; /* Fona krāsa */
        border-color: rgba(26, 16, 8, 0.8); /* Apmales krāsa */
    }

    /* Pogas stils */
    button {
        background-color: #c58667; /* Fona krāsa */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        border: 2px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
        padding: 5px 25px; /* Iekšējā atstarpe */
        border-radius: 4px; /* Noapaļotie stūri */
        font-size: 1.0rem; /* Teksta izmērs */
        cursor: pointer; /* Kursora izskats */
        transition: all 0.3s;
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

        .main-content p,
        .main-content a {
            font-size: 0.9rem; /* Teksta izmērs */
        }
        h1{
            font-size: 1.5rem;
            margin-top: -20px;
        }

        h2{
            font-size: 1rem;
        }
    }
</style>
