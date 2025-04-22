<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from "ziggy-js";

const goBack = () => {
    window.history.back();
};

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

    <div class="support-page">
        <div class="contact-form">
            <a class="back" @click="goBack"><-Atpakaļ</a>
            <h1>Pieteikšanās</h1> <!-- Formas virsraksts -->

            <div v-if="status" >
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <!-- E-pasta lauks -->
                <div class="form-group">
                    <InputLabel for="email" value="E-pasta adrese" class="label"/>
                    <TextInput
                        id="email"
                        type="email"
                        class="input"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />
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
                        autocomplete="current-password"
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <!-- Atcerēties mani -->
                <div class="form-group block">
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" class="tick" />
                        <span class="ms-2 text-sm"> Atcerēties mani</span>
                    </label>
                </div>

                <!-- Iesniegšanas poga un saite uz paroles atiestatīšanu -->
                <div class="form-group form-footer">
                    <Link
                        :href="route('register')"
                    >
                        Nav izveidots konts?
                    </Link>

                    <PrimaryButton
                        class="ms-4"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Pieteikties
                    </PrimaryButton>
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
    }

    /* Fokusa stils */
    .input:focus {
        outline: none; /* Noņem noklusēto fokusu */
        box-shadow: none; /* Noņem ēnu */
        background-color: #ffd9c6; /* Fona krāsa */
        border-color: rgba(26, 16, 8, 0.8); /* Apmales krāsa */
    }

    .tick{
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */

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

    /* Pogas stils, kad pele ir virs tās */
    button:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;;
    }
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
    }

    a:hover{
        color: #ffc8a9; /* Teksta krāsa */
    }
    @media (max-width: 500px) {
        .mt-2,
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
