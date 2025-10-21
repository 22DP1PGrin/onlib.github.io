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
        name: '', // Lietotāja vārds
        lastname: '', // Lietotāja uzvārds
        nickname: '', // Lietotāja segvārds
        email: '', // Lietotāja e-pasta adrese
        password: '', // Lietotāja parole
        password_confirmation: '', // Paroles apstiprinājums
    });

    // Funkcija, kas tiek izsaukta, kad forma tiek iesniegta
    const submit = () => {
        form.post(route('register'), {
            onFinish: () => form.reset('password', 'password_confirmation'),  // Notīra paroles laukus pēc iesniegšanas
        });
    };
</script>

<template>
        <div class="support-page">
            <div class="contact-form">
                <!-- Atpakaļ poga -->
                <a class="back" @click="goBack">Atpakaļ</a>
                <h1>Reģistrācija</h1>

                <!-- Forma reģistrācijas datu ievadei -->
                <form @submit.prevent="submit">
                    <!-- Lietotājvārds -->
                    <div class="form-group">
                        <label for="nickname" class="label">Lietotājvārds</label>
                        <input
                            id="nickname"
                            type="text"
                            class="input"
                            v-model="form.nickname"
                            required
                            autofocus
                            autocomplete="given-name"
                        />
                        <!-- Parāda kļūdu, ja lietotājvārds nav derīgs -->
                        <div v-if="form.errors.nickname" class="input-error">{{ form.errors.nickname }}</div>
                    </div>

                    <!-- E-pasts -->
                    <div class="form-group">
                        <label for="email" class="label">E-pasta adrese</label>
                        <input
                            id="email"
                            type="email"
                            class="input"
                            v-model="form.email"
                            required
                            autocomplete="username"
                        />
                        <!-- Parāda kļūdu, ja e-pasts nav derīgs -->
                        <div v-if="form.errors.email" class="input-error">{{ form.errors.email }}</div>
                    </div>

                    <!-- Parole -->
                    <div class="form-group">
                        <label for="password" class="label">Parole</label>
                        <input
                            id="password"
                            type="password"
                            class="input"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                        />
                        <!-- Parāda kļūdu, ja parole nav pareiza -->
                        <div v-if="form.errors.password" class="input-error">{{ form.errors.password }}</div>
                    </div>

                    <!-- Apstiprinājums parolei -->
                    <div class="form-group">
                        <label for="password_confirmation" class="label">Atkartot paroli</label>
                        <input
                            id="password_confirmation"
                            type="password"
                            class="input"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                        />
                        <!-- Parāda kļūdu, ja parole nav pareiza -->
                        <div v-if="form.errors.password_confirmation" class="input-error">{{ form.errors.password_confirmation }}</div>
                    </div>

                    <!-- Iesniegšanas poga un saite uz pieteikšanās lapu -->
                    <div class="form-group form-footer">
                        <a href="/login">Jau reģistrējies?</a>
                        <button
                            type="submit"
                            class="primary-button "
                            :disabled="form.processing"
                            :style="{ opacity: form.processing ? 0.25 : 1 }"
                        >
                            Reģistrēties
                        </button>
                    </div>
                </form>
            </div>
        </div>
</template>

<style scoped>
    /* Galvenais konteiners */
    .support-page {
        justify-content: center;
        max-width: 470px; /* Maksimālais platums */
        width: 100%; /* Pilns platums */
        margin: 0 auto; /* Centrēšana */
        padding: 20px; /* Iekšējā atstarpe */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
        line-height: 1.6; /* Teksta augstums */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
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

    /* Pēdējās rindas izkārtojums (saite + poga) */
    .form-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .label{
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        font-weight: normal;
        font-size: 1.0rem;
    }

    label {
        display: block; /* Parāda kā bloku elementu */
        margin-bottom: 5px; /* Atstarpe no apakšas */
        font-weight: bold; /* Treknraksts */
    }

    /* Ievades lauku stils */
    .input {
        width: 100%; /* Pilns platums */
        padding: 10px; /* Iekšējā atstarpe */
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
        border-radius: 4px; /* Noapaļotie stūri */
        font-size: 1rem; /* Teksta izmērs */
    }

    /* Fokusa stils */
    .input:focus {
        outline: none; /* Noņem noklusēto fokusu */
        box-shadow: none; /* Noņem ēnu */
        background-color: #ffd9c6; /* Fona krāsa */
        border-color: rgba(26, 16, 8, 0.8); /* Apmales krāsa */
    }

    /* Kļūdas zem ievades lauka */
    .input-error{
        font-size: 1rem;
        color: rgb(110, 37, 37);
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
        transition: background-color 0.3s;
    }

    /* Pogas stils, kad pele ir virs tās */
    button:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;
    }

    /* Saites stils */
    a {
        text-decoration: none;
        color: rgba(106, 51, 0, 0.8); /* Teksta krāsa */
        font-size: 1rem;
        cursor: pointer; /* Kursora izskats */
        transition: color 0.3s;
    }

    a:hover{
        color: #ffc8a9; /* Teksta krāsa */
    }

    /* Responsīvs dizains mazākiem ekrāniem */
    @media (max-width: 500px) {
        .main-content p,
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
