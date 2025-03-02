<template>
    <Navbar />
    <div class="support-page">
        <div class="main-content">
            <h1>Tehniskais atbalsts</h1>
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
                    <label for="name">Vārds:</label>
                    <input type="text" class="input" v-model="form.name" required />
                </div>

                <!-- Uzvārds -->
                <div class="form-group">
                    <label for="name">Uzvārds:</label>
                    <input type="text" class="input" v-model="form.surname" required />
                </div>

                <!-- E-pasta adrese -->
                <div class="form-group">
                    <label for="email">E-pasta adrese:</label>
                    <input type="email" class="input" v-model="form.email" required />
                </div>

                <!-- Tēma -->
                <div class="form-group">
                    <label for="subject">Tēma:</label>
                    <select class="subject" v-model="form.subject" required>
                        <option value="" disabled>Izvēlieties tēmu</option>
                        <option value="tehniskas_problemas">Tehniskas problēmas</option>
                        <option value="konts_un_pieteiksanas">Konts un pieteikšanās</option>
                        <option value="satura_jautajumi">Satura jautājumi</option>
                        <option value="cits">Cits</option>
                    </select>
                </div>

                <!-- Ziņojums -->
                <div class="form-group">
                    <label for="message">Ziņojums:</label>
                    <textarea id="message" v-model="form.message" rows="5" required></textarea>
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
                Ja nevarat gaidīt atbildi, lūdzu, pārbaudiet mūsu <a href="/faq">bieži uzdoto jautājumu sadaļu</a>, kur jūs varat atrast ātras atbildes uz visbiežākajiem jautājumiem.
            </p>
        </div>
    </div>
    <Footer />
</template>

<script>
import Navbar from "@/components/Navbar.vue";
import Footer from "@/components/Footer.vue";

export default {
    name: "SupportPage",
    components: {
        Navbar,
        Footer,
    },
    data() {
        return {
            form: {
                name: "",
                surname:"",
                email: "",
                subject: "",
                message: "",
            },
        };
    },
    methods: {
        submitForm() {
            console.log("Forma iesniegta:", this.form);
            alert("Paldies par jūsu ziņojumu! Mēs ar jums sazināsimies drīz.");
            this.form = { name: "",surname: "", email: "", subject: "", message: "" };
        },
    },
};
</script>

<style scoped>
    .support-page {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
        line-height: 1.6;
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
    }
    .main-content p{
        margin-top: -10px;
        font-size: 1.0rem; /* Teksta izmērs */
        margin-bottom: 25px;

    }
    h1 {
        font-size: 1.7rem; /* Teksta izmērs */
        font-weight: bold; /* Treknraksts */
        text-align: center; /* Centrē tekstu */
        margin-top: 0; /* Atstarpe no augšas */
        padding: 60px; /* Iekšējā atstarpe */
    }

    h2 {
        font-weight: bold; /* Treknraksts */
        font-size: 1.4rem;
        margin-top: 20px;
        margin-bottom: 25px;
    }

    .contact-form {
        background-color: #c58667; /* Fona krāsa */
        padding: 20px;
        border-radius: 8px;
        border: 1px solid rgba(26, 16, 8, 0.8);
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px; /* Ēna navigācijas joslas apakšā */
    }

    input:focus{
        outline: none !important; /* Noņemam noklusēto apmales stāvokli */
        box-shadow: none !important;
    }
    .form-group {
        margin-bottom: 15px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    /* Noņem noklusēto zilo apmali (fokusu) */
    .subject:focus {
        outline: none; /* Noņem fokusu */
        border-color: #ccc; /* Pielāgojiet apmales krāsu pēc vajadzības */
    }

    /* Atspējo fona izmaiņas opcijām, kad uz tām tiek novadīts kursors */
    .subject option:hover {
        background-color: transparent; /* Fons nemainās */
    }

    /* Pielāgojiet opciju izskatu */
    .subject option {
        background-color: white; /* Fona krāsa opcijām */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
    }

    input,
    select,
    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        font-size: 1rem;
    }

    textarea {
        resize: vertical;
    }

    button {
        background-color: #c58667; /* Fona krāsa */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        border: 2px solid rgba(26, 16, 8, 0.8);
        padding: 5px 25px;
        border-radius: 4px;
        font-size: 1rem;
        cursor: pointer;
    }

    button:hover {
        color: rgba(255, 187, 142, 0.8); /* Teksta krāsa saitēm, kad pele tiek pārvilkta */
        border-color: rgba(255, 187, 142, 0.8); /* Teksta krāsa saitēm, kad pele tiek pārvilkta */;

    }

    .additional-info p{
        margin-top: 30px;
        font-size: 1.0rem; /* Teksta izmērs */
        margin-bottom: 25px;

    }

    a {
        text-decoration: none; /* Noņem apakšsvītrojumu */
        color: rgba(106, 51, 0, 0.8); /* Teksta krāsa */
    }

</style>
