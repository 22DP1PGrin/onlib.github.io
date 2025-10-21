<script setup>
    // Importē nepieciešamās funkcijas un komponentes
    import { useForm } from '@inertiajs/vue3';
    import { ref } from 'vue';
    import { route } from 'ziggy-js';
    import Navbar from '@/Components/Navbar.vue';
    import Footer from '@/Components/Footer.vue';

    const verified = ref(false); // Norāda, vai lietotājs jau ir pārbaudījis kodu
    const passwordChanged = ref(false); // Ja parole ir veiksmīgi nomainīta

    // Forma koda pārbaudei
    const codeForm = useForm({
        code: '', // Lietotāja ievadītais apstiprinājuma kods
    });

    // Forma jaunās paroles ievadei
    const passwordForm = useForm({
        password: '', // Jaunā parole
        password_confirmation: '', // Jaunās paroles atkārtojums
    });

    // Nosūta kodu pārbaudei uz serveri
    const submitCode = async () => {
        codeForm.post(route('password.check'), {
            preserveScroll: true,
            onSuccess: () => {
                verified.value = true; // Ja kods pareizs — rāda paroli ievades formu
            },
        });
    };

    // Nosūta jauno paroli serverim
    const submitPassword = () => {
        passwordForm.post(route('password.reset'), {
            onSuccess: () => passwordChanged.value = true,
        });
    };
</script>

<template>
    <Navbar />
    <div class="center-container">
        <div class="container">
            <div class="info">
                <div class="exit">
                    <!-- Atpakaļ poga -->
                    <a v-if="!passwordChanged" href="/">Iziet</a>
                </div>

                <!-- Virsraksts, kad kods vēl nav pārbaudīts -->
                <h2 v-if="!verified">
                    Ievadiet kodu no nosūtītās vēstules.
                </h2>
                <!-- Virsraksts, kad kods ir pārbaudīts, bet parole vēl nav mainīta -->
                <h2 v-if="verified && !passwordChanged">
                    Ievadiet jaunu paroli savam kontam.
                </h2>
            </div>

            <!-- Forma datu ievadei -->
            <form v-if="!verified" @submit.prevent="submitCode">
                <div class="form-group">
                    <label class="label">Apstiprinājuma kods</label>
                    <input
                        type="text"
                        class="input"
                        v-model="codeForm.code"
                        required
                    />
                    <!-- Parāda kļūdu ziņojumu, ja kods nav pareizs -->
                    <div v-if="codeForm.errors.code" class="input-error">{{ codeForm.errors.code }}</div>
                </div>

                <!-- Bloks ar pogu un saiti -->
                <div class="choice">
                    <a :href="route('forgot-password.page')">Mainīt e-pasta adresi</a>
                    <button
                        type="submit"
                        class="primary-button"
                        :disabled="codeForm.processing"
                        :style="{ opacity: codeForm.processing ? 0.25 : 1 }"
                    >
                        Parbaudīt
                    </button>
                </div>
            </form>

            <!-- Forma jaunās paroles ievadei -->
            <form v-if="verified && !passwordChanged" @submit.prevent="submitPassword">
                <div class="form-group">
                    <label class="label">Jauna parole</label>
                    <input
                        type="password"
                        class="input"
                        v-model="passwordForm.password"
                        required
                    />
                    <label class="label">Atkartot paroli</label>
                    <input
                        type="password"
                        class="input"
                        v-model="passwordForm.password_confirmation"
                        required
                    />
                    <!-- Parāda kļūdu ziņojumu, ja parole nav derīga -->
                    <div v-if="passwordForm.errors.password" class="input-error">{{ passwordForm.errors.password }}</div>
                </div>
                <div class="butt">
                    <button
                        type="submit"
                        class="primary-button"
                        :disabled="passwordForm.processing"
                        :style="{ opacity: passwordForm.processing ? 0.25 : 1 }"
                    >
                        Mainīt paroli
                    </button>
                </div>
            </form>

            <!-- Bloks, kas parāda ziņojumu par veiksmīgu paroles maiņu -->
            <div v-if="passwordChanged" class="success-container">
                Parole veiksmīgi atjaunināta! Tagad Jūs varat
                <a href="/login">pieteikties</a>
                vai
                <a href="/" class="home">atgriezties vietnē</a>.
            </div>
        </div>
    </div>
    <Footer />
</template>

<style scoped>
    /* Galvenais konteiners */
    .center-container {
        display: flex; /* Aktivizē flex izkārtojumu */
        flex-direction: column; /* Elementi tiek izvietoti kolonnā */
        align-items: center; /* Centrē horizontāli */
        justify-content: center; /* Centrē vertikāli */
        min-height: 90vh; /* Aizņem vismaz 90% no loga augstuma */
        text-align: center; /* Centrē tekstu */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
    }

    /* Konteiners ar informāciju */
    .container {
        border: 1px solid rgba(26, 16, 8, 0.8); /* Rāmis ap formu */
        background-color: #e4a27c; /* Fona krāsa */
        padding: 20px; /* Iekšējā atstarpe */
        border-radius: 4px; /* Noapaļoti stūri */
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px; /* Ēna */
        max-width: 500px; /* Maksimālais platums */
    }

    /* Formas grupu stils */
    .form-group {
        margin-bottom: 15px; /* Atstarpe starp formu grupām */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        text-align: left !important;
    }

    /* Teksta virsraksts ar paskaidrojumu lietotājam */
    h2 {
        margin-top: 0;
        font-size: 1.1rem;
        margin-bottom: 20px;
    }

    /* Izvēles bloks ar saiti un pogu */
    .choice {
        display: flex;
        justify-content: space-between; /* Vienāda atstarpe starp saitēm */
        align-items: center;
        margin-top: 35px;
        padding-right: 10px;
        padding-left: 10px;
    }

    /* Iziet saite */
    .exit {
        text-align: left !important;
        display: flex;
        align-items: center;
        margin-top: -20px;
        margin-bottom: 10px;
    }

    /* Saites stils */
    a {
        text-decoration: none;
        color: rgba(106, 51, 0, 0.8);
        font-size: 1rem;
        cursor: pointer;
        transition: color 0.3s;
    }

    a:hover {
        color: #ffc8a9;
    }

    .info {
        margin-top: 12px;
        margin-bottom: 10px;
    }

    .label {
        color: rgba(26, 16, 8, 0.8);
        font-weight: normal;
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
        outline: none;
        box-shadow: none;
        background-color: #ffd9c6;
        border-color: rgba(26, 16, 8, 0.8);
    }

    .butt {
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
        border-color: #ffc8a9;
    }

    .input-error {
        color: rgb(110, 37, 37);
        font-size: 1rem;
    }

    /* Responsīvs dizains mazākiem ekrāniem */
    @media (max-width: 625px) {
        .container {
            max-width: 400px;
        }
        h2 {
            font-size: 1rem;
        }
        .label, input::placeholder, a {
            font-size: 0.9rem;
        }
        button {
            font-size: 0.9rem;
            padding: 5px 18px;
        }
    }
</style>

