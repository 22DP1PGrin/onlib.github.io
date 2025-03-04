<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { route } from "ziggy-js";

const form = useForm({
    name: '',
    lastname: '',
    nickname: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
        <Head title="Register" />

        <div class="support-page">

            <div class="contact-form">
                <h1>Reģistrācija</h1>
                <form @submit.prevent="submit">
                    <div class="form-group">
                        <InputLabel for="name" value="Vārds" />
                        <TextInput
                            id="name"
                            type="text"
                            class="input"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="given-name"
                        />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="form-group">
                        <InputLabel for="lastname" value="Uzvārds" />
                        <TextInput
                            id="lastname"
                            type="text"
                            class="input"
                            v-model="form.lastname"
                            required
                            autofocus
                            autocomplete="family-name"
                        />
                        <InputError class="mt-2" :message="form.errors.lastname" />
                    </div>

                    <div class="form-group">
                        <InputLabel for="nickname" value="Segvārds" />
                        <TextInput
                            id="nickname"
                            type="text"
                            class="input"
                            v-model="form.nickname"
                            required
                            autofocus
                            autocomplete="nickname"
                        />
                        <InputError class="mt-2" :message="form.errors.nickname" />
                    </div>

                    <div class="form-group">
                        <InputLabel for="email" value="E-pasta adrese" />
                        <TextInput
                            id="email"
                            type="email"
                            class="input"
                            v-model="form.email"
                            required
                            autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div class="form-group">
                        <InputLabel for="password" value="Parole" />
                        <TextInput
                            id="password"
                            type="password"
                            class="input"
                            v-model="form.password"
                            required
                            autocomplete="new-password"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <div class="form-group">
                        <InputLabel for="password_confirmation" value="Apstipriniet paroli" />
                        <TextInput
                            id="password_confirmation"
                            type="password"
                            class="input"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                        />
                        <InputError class="mt-2" :message="form.errors.password_confirmation" />
                    </div>

                    <div class="form-group flex items-center justify-end">
                        <Link
                            :href="route('login')"
                            class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Jau reģistrējies?
                        </Link>

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
    max-width: 470px; /* Maksimālais platums */
    margin: 0 auto; /* Centrēšana */
    padding: 20px; /* Iekšējā atstarpe */
    font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
    line-height: 1.6; /* Teksta augstums */
    color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
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
    background-color: white; /* Fona krāsa */
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
}

/* Pogas stils, kad pele ir virs tās */
button:hover {
    background-color: rgba(255, 187, 142, 0.8); /* Fona krāsa */
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
</style>
