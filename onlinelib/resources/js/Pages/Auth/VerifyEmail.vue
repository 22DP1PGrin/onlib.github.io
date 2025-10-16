<script setup>
import {computed, ref} from 'vue';
import { usePage, Head, Link, useForm } from '@inertiajs/vue3';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';
import {route} from "ziggy-js";

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});
const linkSent = ref(false);
const submit = () => {
    form.post(route('verification.resend'), {
        onSuccess: () => {
            linkSent.value = true;
        },
    });
};

const goBack = () => {
    window.history.back();
};

const page = usePage();
const verified = computed(() => page.url.includes('verified=1'));
</script>

<template>
    <Navbar />
    <div class="center-container">
            <div v-if="verified" class="verify">
                Jūsu e-pasts ir apstiprināts! Tagad Jūs varat
                <a href="/login">pieteikties</a>
                vai
                <a href="/" class="home">atgriezties vietnē</a>.
            </div>

            <div v-else class="container">
                <div class="info">
                    <h2>Paldies par reģistrēšanos!
                    Pirms turpināt, lūdzu, apstipriniet savu e-pasta adresi, noklikšķinot uz Jums nosūtītas saites.
                    Ja neesat saņēmis vēstuli, varat to pieprasīt vēlreiz.</h2>
                </div>

                <div class="again" v-if="linkSent">
                    Uz Jūsu e-pastu ir nosūtīta jauna apstiprinājuma saite.
                </div>

                    <div class="choice">
                        <a @click.prevent="submit">Nosūtīt atkārtoti</a>
                        <a @click="goBack">Iziet</a>
                    </div>
            </div>
    </div>
    <Footer />
</template>

<style scoped>
.center-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 90vh;
    text-align: center;
    font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
}

.container{
    border: 1px solid rgba(26, 16, 8, 0.8);
    background-color: #e4a27c;
    padding: 20px;
    border-radius: 4px;
    box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px;
    max-width: 800px;
}

.verify{
    border: 1px solid rgba(26, 16, 8, 0.8);
    background-color: #e4a27c;
    padding: 20px;
    border-radius: 4px;
    box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px;
}

.again{
    margin-top: 20px;
    font-weight: bold;
    font-size: 1rem;
}

h2 {
    margin-top: 0; /* Noņem noklusēto atstarpi */
    font-size: 1.1rem;
}

.choice{
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 35px;
    padding-right:15px;
    padding-left:15px;
}

a{
    text-decoration: none; /* Noņem apakšsvītrojumu */
    color: rgba(106, 51, 0, 0.8); /* Teksta krāsa */
    font-size: 1rem;
    cursor: pointer;
    transition: color 0.3s;
}

a:hover{
    color: #ffc8a9; /* Teksta krāsa */
}

@media (max-width: 850px) {
    .container, .verify{
        max-width: 600px;
    }
}

@media (max-width: 625px) {
    .container, .verify{
        max-width: 400px;
    }

    .verify{
        font-size: 0.9rem;
    }

    .again{
        margin-top: 20px;
        font-weight: bold;
        font-size: 1rem;
    }

    h2 {
        font-size: 1rem;
    }

    .choice {
        margin-top: 20px;
        white-space: nowrap;
    }

    a{
        font-size: 0.9rem;
    }
}
@media (max-width: 425px) {
    .container, .verify{
        max-width: 325px;
    }
}

</style>
