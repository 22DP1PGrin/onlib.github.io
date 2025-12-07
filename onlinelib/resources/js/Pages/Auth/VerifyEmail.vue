<script setup>
    // Importē nepieciešamās funkcijas un komponentes
    import { computed, ref } from 'vue';
    import { usePage, useForm } from '@inertiajs/vue3';
    import Navbar from '@/Components/Navbar.vue';
    import Footer from '@/Components/Footer.vue';
    import { route } from "ziggy-js";

    // Saņem props, kas tiek padoti no servera puses
    const props = defineProps({
        status: {
            type: String,
        },
    });

    // Izveido tukšu formu, kas tiks izmantota verifikācijas saites atkārtotai nosūtīšanai
    const form = useForm({});

    // Reaktīvs mainīgais, kas norāda, vai jaunā verifikācijas saite jau ir nosūtīta
    const linkSent = ref(false);

    // Funkcija, kas nosūta pieprasījumu uz serveri, lai atkārtoti nosūtītu e-pasta verifikācijas saiti
    const submit = () => {
        form.post(route('verification.resend'), {
            onSuccess: () => {
                linkSent.value = true; // Ja pieprasījums veiksmīgs, tiek parādīts paziņojums
            },
        });
    };

    // Funkcija, kas atgriež lietotāju uz iepriekšējo lapu
    const goBack = () => {
        window.history.back();
    };

    // Iegūst informāciju par pašreizējo lapu (URL un datus) no Inertia.js
    const page = usePage();

    // Aprēķinātais mainīgais, kas pārbauda, vai URL satur "verified=1" (tas nozīmē, ka e-pasts jau ir apstiprināts)
    const verified = computed(() => page.url.includes('verified=1'));
</script>

<template>
    <Navbar />
    <div class="center-container">
        <!-- Ja lietotājs jau ir apstiprinājis savu e-pastu -->
        <div v-if="verified" class="verify">
            Jūsu e-pasts ir apstiprināts! Tagad Jūs varat
            <a href="/login">pieteikties</a>
            vai
            <a href="/" class="home">atgriezties vietnē</a>.
        </div>

        <!-- Ja e-pasts vēl nav apstiprināts -->
        <div v-else class="container">
            <div class="info">
                <h2>
                    Paldies par reģistrēšanos! <br />
                    Pirms turpināt, lūdzu, apstipriniet savu e-pasta adresi, noklikšķinot uz Jums nosūtītas saites. <br />
                    Ja neesat saņēmis vēstuli, varat to pieprasīt vēlreiz.
                </h2>
            </div>

            <!-- Parāda paziņojumu, ja jauna saite tika nosūtīta -->
            <div class="again" v-if="linkSent">
                Uz Jūsu e-pastu ir nosūtīta jauna apstiprinājuma saite.
            </div>

            <!-- Divas darbības: atkārtoti nosūtīt vai iziet -->
            <div class="choice">
                <a @click.prevent="submit">Nosūtīt atkārtoti</a>
                <a @click="goBack">Iziet</a>
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

    /* Konteiners ar informāciju par e-pasta verifikāciju */
    .container {
        border: 1px solid rgba(26, 16, 8, 0.8); /* Rāmis ap formu */
        background-color: #e4a27c; /* Fona krāsa */
        padding: 20px; /* Iekšējā atstarpe */
        border-radius: 4px; /* Noapaļoti stūri */
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px; /* Ēna */
        max-width: 800px; /* Maksimālais platums */
    }

    /* Bloks, kas tiek parādīts, ja e-pasts ir apstiprināts */
    .verify {
        border: 1px solid rgba(26, 16, 8, 0.8);
        background-color: #e4a27c;
        padding: 20px;
        border-radius: 4px;
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px;
    }

    /* Paziņojums, ka atkārtotā verifikācijas saite ir nosūtīta */
    .again {
        margin-top: 20px;
        font-weight: bold;
        font-size: 1rem;
        color: rgba(26, 16, 8, 0.8);
    }

    /* Teksta virsraksts ar paskaidrojumu lietotājam */
    h2 {
        margin-top: 0;
        font-size: 1.1rem;
        color: rgba(26, 16, 8, 0.8);
    }

    /* Izvēles bloks ar divām saitēm (nosūtīt atkārtoti / iziet) */
    .choice {
        display: flex;
        justify-content: space-between; /* Vienāda atstarpe starp saitēm */
        align-items: center;
        margin-top: 35px;
        padding-right: 15px;
        padding-left: 15px;
    }

    /* Saites stils */
    a {
        text-decoration: none; /* Noņem apakšsvītrojumu */
        color: rgba(106, 51, 0, 0.8); /* Teksta krāsa */
        font-size: 1rem;
        cursor: pointer;
        transition: color 0.3s; /* Gluda pāreja krāsai */
    }

    a:hover {
        color: #ffc8a9;
    }

    /* Responsīvs dizains mazākiem ekrāniem */
    @media (max-width: 850px) {
        .container, .verify {
            max-width: 600px;
        }
    }

    @media (max-width: 625px) {
        .container, .verify {
            max-width: 400px;
        }

        .verify {
            font-size: 0.9rem;
        }

        .again {
            margin-top: 20px;
            font-weight: bold;
            font-size: 1rem;
        }

        h2 {
            font-size: 1rem;
        }

        .choice {
            margin-top: 20px;
            white-space: nowrap; /* Neļauj saitēm pārnesties jaunā rindā */
        }

        a {
            font-size: 0.9rem;
        }
    }

    @media (max-width: 425px) {
        .container, .verify {
            max-width: 325px;
        }
    }
</style>
