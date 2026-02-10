<script setup>
    // Importē nepieciešamās funkcijas un komponentes
    import {useForm } from '@inertiajs/vue3';
    import { route } from "ziggy-js";

    // Funkcija, kas atgriež lietotāju uz iepriekšējo lapu
    const goBack = () => {
        window.history.back();
    };

    // Izveido Inertia formu ar sākotnējām vērtībām
    const form = useForm({
        email: '',// Lietotāja e-pasta adrese
        password: '', // Lietotāja parole
    });

    // Funkcija, kas tiek izsaukta, kad forma tiek iesniegta
    const submit = () => {
        form.post(route('login'), {
            onFinish: () => form.reset('password'),
        });
    };
</script>

<template>
    <div class="support-page">
        <div class="contact-form">
            <!-- Atpakaļ poga -->
            <a class="back" @click="goBack">Atpakaļ</a>
            <h1>Pieteikšanās</h1>

            <!-- Forma pieteikšanās datu ievadei -->
            <form @submit.prevent="submit">
                <!-- E-pasta lauks -->
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
                </div>

                <!-- Paroles lauks -->
                <div class="form-group">
                    <label for="password" class="label">Parole</label>
                    <input
                        id="password"
                        type="password"
                        class="input"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                    />
                    <!-- Parāda kļūdu, ja parole vai e-pasts nav pareiza -->
                    <div v-if="form.errors.password || form.errors.email" class="input-error">
                        {{ form.errors.password || form.errors.email }}
                    </div>

                    <!-- Saite "Aizmirsāt paroli?" tiek rādīta, ja ir kļūda -->
                    <div v-if="form.errors.email || form.errors.password" class="forgot-password">
                        <a :href="route('forgot-password.page')">Aizmirsāt paroli?</a>
                    </div>
                </div>

                <!-- Reģistrācijas saite un pieteikšanās poga -->
                <div class="form-group form-footer">
                    <a href="/register">Nav izveidots konts?</a>

                    <button
                        type="submit"
                        class="primary-button"
                        :disabled="form.processing"
                        :style="{ opacity: form.processing ? 0.25 : 1 }"
                    >
                        Pieteikties
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<style scoped>
    /* Galvenais konteiners */
    .support-page {
        max-width: 400px; /* Maksimālais platums */
        width: 100%; /* Pilns platums */
        margin: 0 auto; /* Centrēšana */
        padding: 20px; /* Iekšējā atstarpe */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
        line-height: 1.6; /* Teksta augstums */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        position: absolute; /* Absolūtā pozicionēšana */
        top: 50%; /* Novietošana 50% no augšas */
        left: 50%; /* Novietošana 50% no kreisās puses */
        transform: translate(-50%, -50%); /* Centrēšana */
    }

    /* Virsraksta stils */
    h1 {
        font-size: 1.7rem; /* Teksta izmērs */
        font-weight: bold; /* Treknraksts */
        text-align: center; /* Centrē tekstu */
        margin-top: 12px; /* Atstarpe no augšas */
        margin-bottom: 10px;
        padding: 0; /* Iekšējā atstarpe */
    }

    /* Kontaktformas stils */
    .contact-form {
        background-color: #e4a27c; /* Fona krāsa */
        padding: 20px; /* Iekšējā atstarpe */
        border-radius: 8px; /* Noapaļotie stūri */
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px; /* Ēna */
    }

    /* Formas grupu stils */
    .form-group {
        margin-bottom: 15px; /* Atstarpe starp formu grupām */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
    }

    .label {
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        font-weight: normal;
        font-size: 1.0rem;
    }

    /* Ievades lauku stils */
    input {
        width: 100%; /* Pilns platums */
        padding: 10px; /* Iekšējā atstarpe */
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
        border-radius: 4px; /* Noapaļotie stūri */
        font-size: 1rem; /* Teksta izmērs */
    }

    /* Kļūdas zem ievades lauka */
    .input-error{
        color: rgb(110, 37, 37);
        font-size: 1rem;
        margin-bottom: 10px;
    }

    input:focus {
        outline: none; /* Noņem noklusēto fokusu */
        box-shadow: none; /* Noņem ēnu */
        background-color: #ffd9c6; /* Fona krāsa */
        border-color: rgba(26, 16, 8, 0.8); /* Apmales krāsa */
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

    /* Pēdējās rindas izkārtojums (saite + poga) */
    .form-footer{
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Saites stils */
    a {
        text-decoration: none; /* Noņem apakšsvītrojumu */
        color: rgba(106, 51, 0, 0.8); /* Teksta krāsa */
        font-size: 1rem;
        transition: color 0.3s;
        cursor: pointer; /* Kursora izskats */
    }

    a:hover{
        color: #ffc8a9; /* Teksta krāsa */
    }

    /* Responsīvs dizains mazākiem ekrāniem */
    @media (max-width: 500px) {
        .input-error,
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
</style>
