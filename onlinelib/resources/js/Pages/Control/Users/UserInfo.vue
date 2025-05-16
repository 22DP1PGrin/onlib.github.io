<script setup>
import { router, useForm } from '@inertiajs/vue3';
import Navbar from "@/Components/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import { route } from "ziggy-js";

// Saņemam datus no servera kā props
const props = defineProps({
    users: Object, // Objekts
});

// Izveidojam veidlapas datus, sākotnēji aizpildītus ar esošo stāsta informāciju
const form = useForm({
    id: props.users.id,
    nickname: props.users.nickname,
    email: props.users.email,
    bio: props.users.bio

});

// Nodaļas dzēšana pēc apstiprinājuma
const deleteUser = (userId) => {
    if (confirm('Vai tiešām vēlaties dzēst šo lietotāju?')) {
        router.delete(route('users.destroy', userId), {
            onSuccess: () => {
                // Pēc veiksmīgas dzēšanas varat pievienot papildus darbības
                alert('Lietotājs veiksmīgi dzēsts!');
            },
            onError: (errors) => {
                console.error('Kļūda dzēšot lietotāju:', errors);
            }
        });
    }
};

</script>

<template>
    <Navbar />

    <!-- Galvenais satura bloks -->
    <div class="main-content">
        <!-- Stāsta rediģēšanas forma -->
        <div class="user-form">

            <h1>Lietotāja apskate</h1>

            <!-- Veidlapas sadaļa -->
            <div class="form">
                <!-- Nosaukuma ievades lauks -->
                <div class="form-group">
                    <h2 class="title">Segvārds</h2>
                    <div class="form-info">{{form.nickname}}</div>
                </div>

                <div class="form-group">
                    <h2 class="title">E-pasts</h2>
                    <div class="form-info">{{form.email}}</div>
                </div>

                <div class="form-group">
                    <h2 class="title">Bio</h2>
                    <div class="form-info">{{form.bio || 'Nav informācijas par lietotāju.'}}</div>
                </div>

                <!-- Formas iesniegšanas poga -->
                <div class="form-actions">
                    <button class="delete-btn" @click="deleteUser(form.id)">Dzēst</button>
                </div>
            </div>
        </div>
    </div>

    <Footer/>
</template>

<style scoped>

.main-content {
    padding-bottom: 45px; /* Atstarpe apakšā */
}
.user-form {
    max-width: 800px; /* Maksimālais platums formai */
    margin: 0 auto; /* Centrēšana */
    padding: 20px; /* Iekšējā atstarpe */
}

.form{
    color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
    font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
    border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
    background-color: #e4a27c; /* Fona krāsa */
    border-radius: 10px; /* Apmales noapaļojums */
    padding: 30px; /* Iekšējā atstarpe */
    box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px; /* Ēna */
}
h1 {
    font-size: 1.7rem;
    margin-top: 32px;
    margin-bottom: 40px;
    text-align: center; /* Centrēts teksts */
    color: rgba(26, 16, 8, 0.8);
    font-family: Tahoma, Helvetica, sans-serif;
    font-weight: bold; /* Treknraksts */
}

.form-group {
    margin-bottom: 20px; /* Atstarpe starp laukiem */
    word-wrap: break-word;
}

.title {
    display: block; /* zem viena bloka */
    font-size: 1.1rem;
    margin-bottom: 8px; /* Atstarpe no ievadlaukuma */
    font-weight: bold;
}

.form-info{
    width: 100%; /* Aizņem visu pieejamo platumu */
    padding: 10px; /* Iekšējā atstarpe */
    border: 1px solid rgba(26, 16, 8, 0.8); /* Apmale */
    border-radius: 4px; /* Noapaļoti stūri */
    background-color: white; /* Fona krāsa */
    font-size: 1rem;
    color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
    font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
}

button {
    padding: 6px 12px;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 1.0rem;
}
.form-actions {
    display: flex;
    justify-content: flex-end; /* Pogas labajā pusē */
    gap: 15px;
    margin-top: 30px;
}
.delete-btn {
    border: 2px solid rgba(35, 11, 11, 0.8);
    background-color: #714e3e;
    color: rgba(26, 16, 8, 0.8);
    padding: 5px 20px;
}

button:hover {
    background-color: #ffc8a9;
    border-color: #ffc8a9;
}


@media (max-width: 500px) {
    h1 {
        font-size: 1.5rem;
    }
    .title {
        font-size: 1rem;
    }

    .form-info {
        font-size: 0.9rem;
    }

    .delete-btn{
        font-size: 0.9rem;
        padding: 5px 15px;
    }
}
</style>

