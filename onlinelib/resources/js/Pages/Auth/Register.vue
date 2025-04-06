<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from "ziggy-js";

const goBack = () => {
    window.history.back();
};
// Formas datu inicializācija
const form = useForm({
    name: '', // Lietotāja vārds
    lastname: '', // Lietotāja uzvārds
    nickname: '', // Lietotāja segvārds
    email: '', // Lietotāja e-pasta adrese
    password: '', // Lietotāja parole
    password_confirmation: '', // Paroles apstiprinājums
});

// Formas iesniegšanas metode
const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),  // Notīra paroles laukus pēc iesniegšanas
    });
};
</script>

<template>
        <!-- Lapas virsraksts -->
        <Head title="Register" />

        <div class="support-page">


            <div class="contact-form">
                <!-- Formas virsraksts -->

                <a class="back" @click="goBack"><-Atpakaļ</a>
                <h1>Reģistrācija</h1>
                <form @submit.prevent="submit">

                    <!-- Segvārda lauks -->
                    <div class="form-group">
                        <InputLabel for="nickname" value="Segvārds" class="label" />
                        <TextInput
                            id="nickname"
                            type="text"
                            class="input"
                            v-model="form.nickname"
                            required
                            autofocus
                            autocomplete="given-name"
                        />
                        <!-- Kļūdu ziņojums -->
                        <InputError class="mt-2" :message="form.errors.nickname" />
                    </div>

                    <!-- E-pasta lauks -->
                    <div class="form-group">
                        <InputLabel for="email" value="E-pasta adrese" class="label"/>
                        <TextInput
                            id="email"
                            type="email"
                            class="input"
                            v-model="form.email"
                            required
                            autocomplete="username"
                        />
                        <!-- Kļūdu ziņojums -->
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <!-- Paroles lauks -->
                    <div class="form-group">
                        <InputLabel for="password" value="Parole" class="label"/>
                        <TextInput
                            id="password"
                            type="password"
                            class="input"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                        />
                        <!-- Kļūdu ziņojums -->
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <!-- Paroles apstiprinājuma lauks -->
                    <div class="form-group">
                        <InputLabel for="password_confirmation" value="Apstipriniet paroli" class="label"/>
                        <TextInput
                            id="password_confirmation"
                            type="password"
                            class="input"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                        />
                        <!-- Kļūdu ziņojums -->
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>

                    <!-- Iesniegšanas poga un saite uz pieteikšanās lapu -->
                    <div class="form-group form-footer">

                        <!-- Saite uz pietekšānu -->
                        <Link
                            :href="route('login')"
                        >
                            Jau reģistrējies?
                        </Link>

                        <!-- Saite uz reģistrāciju -->
                        <PrimaryButton
                            class="ms-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Reģistrēties
                        </PrimaryButton>
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

    .form-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .label{
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        font-weight: normal; /* Normāls fonta stils */
    }

    /* Etiķešu stils */
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

    /* Papildu informācijas stils */
    .additional-info p {
        margin-top: 30px; /* Atstarpe no augšas */
        font-size: 1.0rem; /* Teksta izmērs */
        margin-bottom: 25px; /* Atstarpe no apakšas */
    }

    /* Saites stils */
    a {
        text-decoration: underline !important;
        color: rgba(106, 51, 0, 0.8); /* Teksta krāsa */
        font-size: 14px;
        cursor: pointer; /* Kursora izskats */
    }

    a:hover{
        color: #ffc8a9; /* Teksta krāsa */
    }

</style>
