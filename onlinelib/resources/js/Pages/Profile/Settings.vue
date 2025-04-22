<script>
    import {computed} from "vue";
    import {router, usePage} from "@inertiajs/vue3";
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";
    import TextInput from "@/Components/TextInput.vue";
    import InputError from "@/Components/InputError.vue";
    import {route} from "ziggy-js";

    export default {
        setup() {
            // Iegūst autentificēto lietotāju no lapas datiem
            const user = computed(() => usePage().props.auth.user)

            // Izrakstīšanās funkcija
            const logout = () => {
                router.post(route('logout'), {}, {
                    onSuccess: () => {
                        // Pēc izrakstīšanās pāradresē uz sākumlapu
                        window.location.href = '/'
                    }
                })
            }

            return {
                user,
                logout
            };
        },
        components: {
            InputError,
            TextInput,
            Navbar,
            Footer
        },
        data() {
            return {
                errors: {}, // Objektu kļūdām no servera
                deletePassword: '', // Ievade konta dzēšanai

                // Profila rediģēšanas lauki
                profileData: {
                    nickname: '',
                    email: '',
                    bio: ''
                },

                // Paroles maiņas lauki
                passwordData: {
                    current: '',
                    new: '',
                    confirm: ''
                }
            }
        },

        methods: {
            // Nosūta pieprasījumu profila datu saglabāšanai
            handleProfileSubmit() {
                this.$inertia.put(route('profile.update'), {
                    nickname: this.profileData.nickname,
                    email: this.profileData.email,
                    bio: this.profileData.bio
                }, {
                    preserveScroll: true,
                    onSuccess: () => {
                        alert('Profila dati veiksmīgi saglabāti!');
                        this.errors = {}; // Notīra kļūdu ziņojumus
                    },
                    onError: (errors) => {
                        this.errors = errors; // Parāda kļūdas
                    }
                });
            },

            // Nosūta paroles maiņas pieprasījumu
            handlePasswordSubmit() {
                this.$inertia.put(route('profile.password'), {
                    current: this.passwordData.current,
                    new: this.passwordData.new,
                    new_confirmation: this.passwordData.confirm,
                }, {
                    preserveScroll: true,
                    onSuccess: () => {
                        alert('Parole veiksmīgi nomainīta!');
                        // Notīra laukus un kļūdas
                        this.passwordData.current = '';
                        this.passwordData.new = '';
                        this.passwordData.confirm = '';
                        this.errors = {};
                    },
                    onError: (errors) => {
                        this.errors = errors;
                    }
                });
            },

            // Konta dzēšana ar apstiprinājumu
            handleDeleteAccount() {
                if (confirm('Vai tiešām vēlaties dzēst kontu?\nŠī darbība ir neatgriezeniska.')) {
                    this.$inertia.delete(route('profile.destroy'), {
                        data: {
                            password: this.deletePassword
                        },
                        onSuccess: () => {
                            alert('Jūsu konts tika dzēsts.');
                        },
                        onError: (errors) => {
                            if (errors.password) {
                                alert(errors.password);
                            }
                        }
                    });
                }
            }
        },

        mounted() {
            // Kad komponents tiek ielādēts, iestata profila laukus ar lietotāja datiem
            if (this.user) {
                this.profileData.nickname = this.user.nickname;
                this.profileData.email = this.user.email;
                this.profileData.bio = this.user.bio;
            }
        }
    }
</script>


<template>
    <Navbar/>

    <!-- Iestatījumu lapas virsraksts -->
    <div class="settings-header">
        <h1><strong>Profila iestatījumi</strong></h1>
    </div>


    <div class="settings-page">
        <!-- Profila pamatinformācijas sadaļa -->
        <div class="settings-section">
            <h2><i class="fa">&#xf007;</i> Pamatinformācija</h2>

            <!-- Profila datu atjaunināšanas forma -->
            <form @submit.prevent="handleProfileSubmit" class="settings-form">
                <!-- Lietotājvārda ievades lauks -->
                <div class="form-group">
                    <label>Lietotājvārds:</label>
                    <input
                        type="text"
                        v-model="profileData.nickname"
                        placeholder="Jūsu lietotājvārds"
                        autocomplete="given-name"
                        required
                    >
                    <!-- Kļūdu paziņojums lietotājvārdam -->
                    <InputError class="mt-2" :message="errors.nickname" />
                </div>

                <!-- E-pasta ievades lauks -->
                <div class="form-group">
                    <label>E-pasts:</label>
                    <input
                        type="email"
                        v-model="profileData.email"
                        placeholder="jūsu@epasts.lv"
                        autocomplete="username"
                        required
                    >
                    <!-- Kļūdu paziņojums e-pastam -->
                    <InputError class="mt-2" :message="errors.email" />
                </div>

                <!-- Profila apraksta laukums -->
                <div class="form-group">
                    <label>Apraksts:</label>
                    <textarea
                        v-model="profileData.bio"
                        rows="4"
                        placeholder="Rakstiet kaut ko par sevi..."
                    ></textarea>
                    <!-- Kļūdu paziņojums aprakstam -->
                    <InputError class="mt-2" :message="errors.bio" />
                </div>

                <!-- Saglabāšanas poga -->
                <button type="submit" class="save-btn">Saglabāt</button>
            </form>
        </div>

        <!-- Paroles maiņas sadaļa -->
        <div class="settings-section">
            <h2><i class="fa">&#xf023;</i> Paroles maiņa</h2>

            <!-- Paroles maiņas forma -->
            <form @submit.prevent="handlePasswordSubmit" class="settings-form">
                <!-- Pašreizējās paroles ievades lauks -->
                <div class="form-group">
                    <label>Pašreizējā parole:</label>
                    <input
                        type="password"
                        v-model="passwordData.current"
                        placeholder="Jūsu vecā parole"
                    >
                    <!-- Kļūdu paziņojums paroles ievadei -->
                    <InputError class="mt-2" :message="$page.props.errors.current" />
                </div>

                <!-- Jaunās paroles ievades lauks -->
                <div class="form-group">
                    <label>Jaunā parole:</label>
                    <input
                        type="password"
                        v-model="passwordData.new"
                        placeholder="Vismaz 8 simboli"
                    >
                    <!-- Kļūdu paziņojums jaunajai parolei -->
                    <InputError class="mt-2" :message="$page.props.errors.new" />
                </div>

                <!-- Paroles apstiprināšanas lauks -->
                <div class="form-group">
                    <label>Atkārtojiet paroli:</label>
                    <input
                        type="password"
                        v-model="passwordData.confirm"
                        placeholder="Atkārtojiet jūsu paroli"
                    >
                    <!-- Kļūdu paziņojums paroles apstiprināšanai -->
                    <InputError class="mt-2" :message="$page.props.errors.new_confirmation" />
                </div>

                <!-- Paroles maiņas poga -->
                <button type="submit"  @click.prevent="handlePasswordSubmit" class="edit-btn">Mainīt paroli</button>
            </form>
        </div>

        <!-- Konta darbību sadaļa (bīstamā zona) -->
        <div class="settings-section">
            <h2><i class="fa">&#xf071;</i> Darbības ar kontu</h2>

            <div class="form-group">
                <!-- Konta dzēšanas poga -->
                <button
                    @click="handleDeleteAccount"
                    class="delete-btn"
                >
                    Dzēst kontu
                </button>

                <!-- Izrakstīšanās poga -->
                <button
                    @click.prevent="logout"
                    class="exit-btn"
                >
                    Iziet
                </button>
            </div>
        </div>
    </div>

    <Footer/>
</template>



<style scoped>
    .settings-page {
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa - Krāsa, kas izmantota visam tekstam */
        max-width: 800px; /* Maksimālais platums - lapas platums tiks ierobežots līdz 800px */
        margin: 0 auto; /* Automātiski horizontālie malas, lai centrs būtu uz ekrāna */
        padding: 20px; /* Iekšējais attālums ap saturu */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
    }

    .settings-header {
        text-align: center; /* Centrē tekstu */
        margin-top: 38px; /* Augšējā atstarpe */
        padding: 20px 20px; /* Iekšējais attālums */
        color: rgba(26, 16, 8, 0.8);
        font-family: Tahoma, Helvetica, sans-serif;
        font-size: 1.7rem; /* Fonta izmērs */
    }

    .settings-section {
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
        background-color: #e4a27c;
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px; /* Ēna  */
        border-radius: 4px; /* Noapaļotie stūri */
        padding: 20px;
        margin-bottom: 20px; /* Attālums no nākamās sadaļas */
    }

    .settings-section h2 {
        font-size: 1.1rem;
        margin-bottom: 20px;
    }

    .settings-form {
        display: flex; /* Flexbox izkārtojums */
        flex-direction: column; /* Novietojiet elementus vertikāli */
        gap: 15px; /* Attālums starp elementiem */
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .form-group label {
        font-weight: bold;
    }

    input::placeholder,
    textarea::placeholder {
        color: rgba(26, 16, 8, 0.42);
        font-size: 1.0rem;
    }

    button {
        border: 2px solid rgba(26, 16, 8, 0.8);
        background-color: #c58667; /* Fona krāsa */
        color: rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        font-size: 1.0rem;
        transition: background-color 0.3s, border-color 0.3s; /* Pārejas efekts, lai uzlabotu interaktivitāti */
        cursor: pointer; /* Kursora izmaiņas pie pogas */
        padding: 5px 10px;
    }

    a {
        text-decoration: none; /* Noņem apakšsvītrojumu no saitēm */
        color: rgba(106, 51, 0, 0.8);
        font-size: 1rem;
    }

    a:hover {
        color: #ffc8a9;
    }

    button:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;
    }

    .edit-btn {
        align-self: flex-start; /* Pozicionē pogu pa kreisi */
    }

    .save-btn {
        align-self: flex-start;
        padding: 5px 22px;
    }

    .delete-btn {
        align-self: flex-start;
        margin-bottom: 5px;
        border: 2px solid rgba(35, 11, 11, 0.8);
        background-color: #714e3e;
    }

    .exit-btn {
        align-self: flex-start;
        padding: 5px 37px;
        border: 2px solid rgba(35, 11, 11, 0.8);
        background-color: #714e3e;
    }

    .form-group input,
    .form-group textarea {
        padding: 10px;
        border: 1px solid rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        font-size: 16px;
    }

    .form-group textarea {
        resize: vertical; /* Atļauj tekstlaukam mainīt izmērus vertikāli */
        min-height: 100px; /* Minimālais augstums */
    }

    textarea:focus,
    input:focus {
        outline: none; /* Noņem apmales fokusa režīmā */
        box-shadow: none; /* Noņem nokrāsu ap laukiem */
        background-color: #ffc8a9; /* Fona krāsa, kad lauks ir fokusēts */
    }

    @media (max-width: 600px) {
        .settings-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    }

    @media (max-width: 500px) {
        .main-content p,
        .main-content ul {
            font-size: 0.9rem; /* Teksta izmērs */
        }
        .settings-header {
            font-size: 1.5rem;
        }

        .settings-section{
            font-size: 1rem;
        }
        input::placeholder,
        textarea::placeholder{
            font-size: 0.9rem;
        }
        button{
            font-size: 0.9rem;
            padding: 5px 8px;
        }

        .save-btn{
            padding: 5px 17px;
        }

        .exit-btn{
            padding: 5px 32px;
        }
    }

</style>
