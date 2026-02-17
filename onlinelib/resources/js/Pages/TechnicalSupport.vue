<script>
// Importējam nepieciešamos komponentus
import Navbar from "@/components/Navbar.vue";
import Footer from "@/components/Footer.vue";

export default {
    name: "SupportPage",
    components: {
        Navbar, // Reģistrējam Navigācijas joslu
        Footer, // Reģistrējam Kājeni
    },
    data() {
        return {
            // Formas dati
            form: {
                nickname: "", // Lietotāja vārds
                email: "", // Lietotāja e-pasta adrese
                subject: "", // Tēma
                problem: "", // Ziņojums
            },
        };
    },
    methods: {
        submitForm() {
            // Nosūtīt formas datus uz serveri, izmantojot POST pieprasījumu
            axios.post('/support', this.form)
                .then(response => {
                    // Ja pieprasījums ir veiksmīgs, izvadīt atbildi konsolē un parādīt apstiprinājuma paziņojumu
                    console.log(response.data.problem);
                    alert("Paldies par jūsu ziņojumu! Mēs ar jums sazināsimies drīz.");
                    // Notīrīt formas laukus pēc veiksmīgas iesniegšanas
                    this.form = { nickname: "", email: "", subject: "", problem: "" };
                })
                .catch(error => {
                    // Apstrādāt kļūdas gadījumus
                    if (error.response) {
                        // Ja serveris atgriež kļūdas atbildi
                        console.error('Servera atbilde:', error.response.data);
                        alert('Radās kļūda: ' + error.response.data.problem);
                    } else {
                        // Citas kļūdas (piemēram, tīkla problēmas)
                        console.error('Kļūda:', error);
                    }
                });
        }
    },
};
</script>

<template>
    <!-- Navigācijas josla -->
    <Navbar />

    <!-- Galvenais saturs -->
    <div class="support-page">
        <div class="main-content">
            <!-- Virsraksts -->
            <h1>Tehniskais atbalsts</h1>
            <!-- Apraksts -->
            <p>
                Ja jums ir jautājumi, problēmas vai ierosinājumi, lūdzu, sazinieties ar mūsu atbalsta komandu. Mēs esam šeit, lai palīdzētu!
            </p>
        </div>

        <!-- Kontaktforma -->
        <div class="contact-form">
            <h2>Sazināties ar mums</h2>
            <form @submit.prevent="submitForm">
                <!-- Vārds -->
                <div class="form-group">
                    <label for="name">Lietotājvārds:</label>
                    <input type="text" class="input" v-model="form.nickname" required autocomplete="given-name" />
                </div>

                <!-- E-pasta adrese -->
                <div class="form-group">
                    <label for="email">E-pasta adrese:</label>
                    <input type="email" class="input" v-model="form.email" autocomplete="username" required />
                </div>

                <!-- Tēma -->
                <div class="form-group">
                    <label for="subject">Tēma:</label>
                    <select class="subject" v-model="form.subject" required>
                        <option value="" disabled>Izvēlieties tēmu</option>
                        <option value="Tehniskas problēmas">Tehniskas problēmas</option>
                        <option value="Konts un pieteikšanās">Konts un pieteikšanās</option>
                        <option value="Satura jautājumi">Satura jautājumi</option>
                        <option value="Cits">Cits</option>
                    </select>
                </div>

                <!-- Ziņojums -->
                <div class="form-group">
                    <label for="message">Ziņojums:</label>
                    <textarea id="message" v-model="form.problem" rows="5" required></textarea>
                </div>

                <!-- Iesniegt pogu -->
                <button type="submit">Nosūtīt</button>
            </form>
        </div>

        <!-- Papildu informācija -->
        <div class="additional-info">
            <p>
                Mēs centīsimies atbildēt uz jūsu ziņojumu 24 stundu laikā.
            </p>
            <p>
                Ja nevarat gaidīt atbildi, lūdzu, pārbaudiet mūsu <a href="/faq">bieži uzdoto jautājumu </a>sadaļu, kur jūs varat atrast ātras atbildes uz visbiežākajiem jautājumiem.
            </p>
        </div>
    </div>

    <!-- Kājene -->
    <Footer />
</template>



<style scoped>
    /* Galvenais konteiners */
    .support-page {
        max-width: 800px; /* Maksimālais platums */
        margin: 0 auto; /* Centrēšana */
        padding: 20px; /* Iekšējā atstarpe */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
        line-height: 1.6; /* Teksta augstums */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
    }

    /* Kontaktformas stils */
    .contact-form {
        background-color: #e4a27c; /* Fona krāsa */
        padding: 20px; /* Iekšējā atstarpe */
        border-radius: 8px; /* Noapaļotie stūri */
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px; /* Ēna */
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

    /* Apakšvirsraksta stils */
    h2 {
        font-weight: bold; /* Treknraksts */
        font-size: 1.4rem; /* Teksta izmērs */
        margin-top: 20px; /* Atstarpe no augšas */
        margin-bottom: 25px; /* Atstarpe no apakšas */
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

    /* Opciju stils */
    .subject option {
        background-color: white; /* Fona krāsa opcijām */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
    }

    /* Ievades lauku stils */
    input,
    select,
    textarea {
        width: 100%; /* Pilns platums */
        padding: 10px; /* Iekšējā atstarpe */
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
        border-radius: 4px; /* Noapaļotie stūri */
        font-size: 1rem; /* Teksta izmērs */
    }

    /* Teksta lauka stils */
    textarea {
        resize: vertical; /* Atļauj vertikālo izmēru maiņu */
    }

    /* Fokusa stils */
    select:focus,
    textarea:focus,
    .input:focus {
        outline: none; /* Noņem noklusēto fokusu */
        box-shadow: none; /* Noņem ēnu */
        background-color: #ffd9c6; /* Fona krāsa */
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
        transition: all 0.3s;
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
        text-decoration: none; /* Noņem apakšsvītrojumu */
        color: rgba(106, 51, 0, 0.8); /* Teksta krāsa */
    }
    a:hover{
        color: rgba(26, 16, 8, 0.8);
    }

    @media (max-width: 500px) {

        .main-content p,
        .main-content a {
            font-size: 0.9rem; /* Teksta izmērs */
        }
        h1{
            font-size: 1.5rem;
            margin-top: -20px;
        }

        h2{
            font-size: 1rem;
        }
    }
</style>
