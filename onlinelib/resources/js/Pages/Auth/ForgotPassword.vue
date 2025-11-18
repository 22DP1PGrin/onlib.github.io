<script setup>
    // Importē nepieciešamās funkcijas un komponentes
    import {useForm } from '@inertiajs/vue3';
    import Navbar from '@/Components/Navbar.vue';
    import Footer from '@/Components/Footer.vue';
    import { route } from "ziggy-js";
    import {ref} from "vue";

    // Saņem props, kas tiek padoti no servera puses
    defineProps({
        status: {
            type: String,
        },
    });

    // Izveido Inertia formu ar sākotnējām vērtībām
    const form = useForm({
        email: '', // Lietotāja e-pasta adrese
    });

    const success = ref(false);

    // Funkcija, kas tiek izsaukta, kad forma tiek iesniegta
    const submit = () => {
        form.post(route('password.email'), {
            onSuccess: () => {
                success.value = true;
            },
        });
    };

    // Funkcija, kas atgriež lietotāju uz iepriekšējo lapu
    const goBack = () => {
        window.history.back();
    };
</script>

<template>
    <Navbar />
    <div class="center-container">
        <div class="container">
            <!-- Atpakaļ poga -->
            <div class="choice">
                <a @click="goBack">Iziet</a>
            </div>

            <div class="info">
                <h2>
                    Aizmirsāt savu paroli?
                    Vienkārši ievadiet savu e-pasta adresi zemāk, un Jūs saņemsiet vēstuli ar saiti, lai droši atiestatītu savu paroli.
                    Pārliecinieties, ka norādāt pareizo e-pasta adresi.
                    Ja neesat saņēmis e-pastu ar kodu, pārbaudiet surogātpasta mapi vai ievadīto e-pasta adresi.
                </h2>
            </div>

            <!-- Forma datu ievadei -->
            <form @submit.prevent="submit">
                <div class="form-group">
                    <label for="email" class="label">E-pasta adrese</label>
                    <input
                        id="email"
                        type="email"
                        class="input"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <!-- Parāda kļūdu, ja e-pasts nav derīgs -->
                    <div v-if="form.errors.email" class="input-error">{{ form.errors.email }}</div>
                </div>

                <!-- Poga-->
                <div class="butt">
                    <button
                        type="submit"
                        class="primary-button"
                        :disabled="form.processing"
                        :style="{ opacity: form.processing ? 0.25 : 1 }"
                    >
                        Apstiprināt
                    </button>
                </div>
            </form>
        </div>
    </div>
    <Footer />
</template>

<style scoped>
    /* Galvenais konteiners */
    .center-container {
        display: flex; /* Izmanto flex izkārtojumu */
        flex-direction: column; /* Novieto elementus kolonnā */
        align-items: center; /* Centrē saturu horizontāli */
        justify-content: center; /* Centrē saturu vertikāli */
        min-height: 90vh; /* Aizņem vismaz 90% no ekrāna augstuma */
        text-align: center; /* Centrē tekstu */
        font-family: Tahoma, Helvetica, sans-serif; /* Teksta fonts */
    }

    .container {
        border: 1px solid rgba(26, 16, 8, 0.8); /* Rāmis ap konteineru */
        background-color: #e4a27c; /* Fona krāsa */
        padding: 20px; /* Iekšējā atstarpe ap saturu */
        border-radius: 4px; /* Noapaļoti stūri */
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px; /* Ēna zem bloka */
        max-width: 700px; /* Maksimālais platums */
    }

    /* Formas grupu stils */
    .form-group {
        margin-bottom: 15px; /* Atstarpe starp formu grupām */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        text-align: left !important;
    }

    /* Galvenais virsraksts */
    h2 {
        margin-top: 0; /* Noņem noklusēto augšējo atstarpi */
        font-size: 1.1rem; /* Teksta izmērs */
    }

    /* Izvēles bloks ar saitēm */
    .choice {
        text-align: left !important;
        display: flex;
        align-items: center;
    }

    /* Saites stils */
    a {
        text-decoration: none; /* Noņem apakšsvītrojumu */
        color: rgba(106, 51, 0, 0.8);
        font-size: 1rem;
        cursor: pointer;
        transition: color 0.3s; /* Gluda pāreja, mainot krāsu */
    }

    a:hover {
        color: #ffc8a9;
    }

    .info{
        margin-top: 12px;
        margin-bottom: 10px;
    }

    .label {
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        font-weight: normal; /* Normāls fonta stils */
        font-size: 1.0rem;
    }

    /* Ievades lauku stils */
    .input {
        width: 100%; /* Pilns platums */
        padding: 10px; /* Iekšējā atstarpe */
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
        border-radius: 4px; /* Noapaļotie stūri */
        font-size: 1rem; /* Teksta izmērs */
        margin-bottom: 5px;
    }

    .input:focus {
        outline: none; /* Noņem noklusēto fokusu */
        box-shadow: none; /* Noņem ēnu */
        background-color: #ffd9c6; /* Fona krāsa */
        border-color: rgba(26, 16, 8, 0.8); /* Apmales krāsa */
    }

    .input-error {
        color: rgb(110, 37, 37);
        font-size: 1rem;
    }

    .butt{
        text-align: right !important;
    }

    /* Pogas stils */
    button {
        background-color: #c58667; /* Fona krāsa */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        border: 2px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
        padding: 5px 25px; /* Iekšējā atstarpe */
        border-radius: 4px; /* Noapaļotie stūri */
        font-size: 1.0rem; /* Teksta izmērs */
        cursor: pointer; /* Kursora izskats */
        transition: background-color 0.3s, border-color 0.3s;
    }

    button:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;;
    }

    /* Responsīvs dizains mazākiem ekrāniem */
    @media (max-width: 850px) {
        .container {
            max-width: 600px;
        }
    }

    @media (max-width: 625px) {
        .container {
            max-width: 400px;
        }

        h2 {
            font-size: 1rem;
        }

        .label,
        input::placeholder,
        a
        {
            font-size: 0.9rem; /* Teksta izmērs */
        }
        h1{
            font-size: 1.5rem;
        }

        button{
            font-size: 0.9rem;
            padding: 5px 18px;
        }
    }

    @media (max-width: 425px) {
        .container {
            max-width: 325px;
        }
    }
</style>

