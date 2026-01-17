<script setup>
import { useForm, usePage, router } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import Navbar from "@/Components/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import { route } from "ziggy-js";

// Iegūst pašreizējo lietotāju
const user = computed(() => usePage().props.auth.user);

// Modālo logu kontrole
const showModal = ref(false);
const showPasswordModal = ref(false);
const showDeleteModal = ref(false);
const showAvatarModal = ref(false);

const avatarInput = ref(null);

const avatarForm = useForm({
    avatar: null
});


// Aizver profila modāli
const closeModal = () => {
    showModal.value = false;
    document.body.style.overflow = "";
};

// Aizver paroles maiņas modāli
const closePasswordModal = () => {
    showPasswordModal.value = false;
    document.body.style.overflow = "";
};

// Aizver attēla modāli
const closeAvatarModal = () => {
    showAvatarModal.value = false;
    document.body.style.overflow = "";
};

// Atver konta dzēšanas apstiprinājuma modāli
const openDeleteModal = () => {
    showDeleteModal.value = true;
    document.body.style.overflow = "hidden";
};

// Aizver konta dzēšanas modāli
const closeDeleteModal = () => {
    showDeleteModal.value = false;
    document.body.style.overflow = "";
};

// Profila rediģēšanas forma
const profileForm = useForm({
    nickname: user.value?.nickname ?? "",
    email: user.value?.email ?? "",
    bio: user.value?.bio ?? ""
});

// Paroles maiņas forma
const passwordForm = useForm({
    current: "",
    new: "",
    new_confirmation: ""
});

// Konta dzēšanas forma
const deleteForm = useForm({
    password: ""
});

// Nosūta profila saglabāšanas formu
const handleProfileSubmit = () => {
    profileForm.put(route("profile.update"), {
        preserveScroll: true,

        onSuccess: (page) => {
            // Ja atgriežamies tajā pašā maršrutā, tad email nav mainīts
            if (route().current("profile.settings")) {
                showModal.value = true;
                document.body.style.overflow = "hidden";
            }
        }
    });
};

// Nosūta paroles maiņas formu
const handlePasswordSubmit = () => {
    passwordForm.put(route("profile.password"), {
        preserveScroll: true,
        onSuccess: () => {
            showPasswordModal.value = true;
            document.body.style.overflow = "hidden";
        }
    });
};

// Apstiprina konta dzēšanu
const confirmDeleteAccount = () => {
    deleteForm.delete(route("profile.destroy"), {
        onSuccess: () => {
            closeDeleteModal();
        }
    });
};

// Izlogo lietotāju
const logout = () => {
    router.post(route("logout"), {}, {
        onSuccess: () => window.location.href = "/"
    });
};

const selectAvatar = () => {
    avatarInput.value.click(); // Atver faila izvēles dialogu
};

// Augšpielāde attēli
const uploadAvatar = (event) => {
    const file = event.target.files[0]; // Saglabā izvēlēto failu
    if (!file) return;

    avatarForm.avatar = file;

    avatarForm.post(route('profile.avatar'), {
        preserveScroll: true,
        onSuccess: () => {
            avatarForm.reset('avatar');
            showAvatarModal.value = true;
            document.body.style.overflow = "hidden";
        }
    });
};
</script>
<template>
    <Navbar/>

    <div class="settings-header">
        <h1><strong>Profila iestatījumi</strong></h1>
    </div>

    <!-- Modālie logi dažādiem notikumiem -->
    <div v-if="showModal" class="modal-overlay">
        <div class="modal">
            <div class="success-container">
                <h2>Profila dati veiksmīgi saglabāti!</h2>
                <p>Jūsu lietotājvārds un/vai apraksts ir atjaunināti.</p>
                <button @click="closeModal" class="close-btn">Aizvērt</button>
            </div>
        </div>
    </div>

    <div v-if="showPasswordModal" class="modal-overlay">
        <div class="modal">
            <div class="success-container">
                <h2>Profila dati veiksmīgi saglabāti!</h2>
                <p>Jūsu parole ir atjaunināta.</p>
                <button @click="closePasswordModal" class="close-btn">Aizvērt</button>
            </div>
        </div>
    </div>

    <div v-if="showDeleteModal" class="modal-overlay">
        <div class="modal">
            <div class="success-container">
                <h2>Vai tiešām vēlaties dzēst savu kontu?</h2>
                <p>Šī darbība ir neatgriezeniska. Ievadiet paroli, lai apstiprinātu.</p>

                <div class="form-group">
                    <input type="password" v-model="deleteForm.password">
                    <div class="error" v-if="deleteForm.errors.password">
                        {{ deleteForm.errors.password }}
                    </div>
                </div>

                <div class="close">
                    <button @click="closeDeleteModal" class="close-btn">Atcelt</button>
                    <button @click="confirmDeleteAccount" class="delete-btn">Dzēst</button>
                </div>
            </div>
        </div>
    </div>

    <div v-if="showAvatarModal" class="modal-overlay">
        <div class="modal">
            <div class="success-container">
                <h2>Profila dati veiksmīgi saglabāti!</h2>
                <p>Jūsu avatars ir atjaunināts.</p>
                <button @click="closeAvatarModal" class="close-btn">Aizvērt</button>
            </div>
        </div>
    </div>

    <div class="settings-page">
        <!-- Avataru izvēles un attēlošanas bloks -->
        <div class="avatar-wrapper">
            <div class="avatar" @click="selectAvatar">
                <input
                    type="file"
                    ref="avatarInput"
                    class="hidden"
                    @change="uploadAvatar"
                    accept="image/*"
                />
                <i v-if="!user.avatar" class="fa profile-icon">&#xf2be;</i>
                <img v-else :src="`/storage/${user.avatar}`" alt="avatar" />

                <div class="avatar-overlay">
                    <i class="fa fa-plus overlay-icon"></i>
                </div>
            </div>
        </div>

        <!-- Profila informācija -->
        <div class="settings-section">
            <h2><i class="fa">&#xf007;</i> Pamatinformācija</h2>

            <form @submit.prevent="handleProfileSubmit" class="settings-form">

                <div class="form-group">
                    <label>Lietotājvārds:</label>
                    <input
                        type="text"
                        v-model="profileForm.nickname"
                        placeholder="Jūsu lietotājvārds"
                        required
                    >
                    <div class="error" v-if="profileForm.errors.nickname">{{ profileForm.errors.nickname }}</div>
                </div>

                <div class="form-group">
                    <label>E-pasts:</label>
                    <input
                        type="email"
                        v-model="profileForm.email"
                        placeholder="jūsu@epasts.lv"
                        required
                    >
                    <div class="error" v-if="profileForm.errors.email">{{ profileForm.errors.email }}</div>
                </div>

                <div class="form-group">
                    <label>Apraksts:</label>
                    <textarea
                        v-model="profileForm.bio"
                        rows="4"
                        placeholder="Rakstiet kaut ko par sevi..."
                    ></textarea>
                    <div class="error" v-if="profileForm.errors.bio">{{ profileForm.errors.bio }}</div>
                </div>

                <button type="submit" class="save-btn">Saglabāt</button>
            </form>
        </div>

        <!-- Paroles maiņa -->
        <div class="settings-section">
            <h2><i class="fa">&#xf023;</i> Paroles maiņa</h2>

            <form @submit.prevent="handlePasswordSubmit" class="settings-form">

                <div class="form-group">
                    <label>Pašreizējā parole:</label>
                    <input type="password" v-model="passwordForm.current" autocomplete="username">
                    <div class="error" v-if="passwordForm.errors.current">{{ passwordForm.errors.current }}</div>
                </div>

                <div class="form-group">
                    <label>Jaunā parole:</label>
                    <input type="password" v-model="passwordForm.new">
                    <div class="error" v-if="passwordForm.errors.new">{{ passwordForm.errors.new }}</div>
                </div>

                <div class="form-group">
                    <label>Atkārtojiet paroli:</label>
                    <input type="password" v-model="passwordForm.new_confirmation">
                    <div class="error" v-if="passwordForm.errors.new_confirmation">{{ passwordForm.errors.new_confirmation }}</div>
                </div>

                <!-- Saite "Aizmirsāt paroli?" tiek rādīta, ja ir kļūda autentifikācijā -->
                <div v-if="passwordForm.errors.current" class="forgot-password">
                    <a :href="route('forgot-password.page')">Aizmirsāt paroli?</a>
                </div>

                <button type="submit" class="edit-btn">Mainīt paroli</button>
            </form>
        </div>

        <!-- Konta darbības -->
        <div class="settings-section">
            <h2><i class="fa">&#xf071;</i> Darbības ar kontu</h2>

            <div class="form-group">
                <button @click.prevent="openDeleteModal" class="delete-btn">Dzēst kontu</button>
                <button @click.prevent="logout" class="exit-btn">Iziet</button>
            </div>
        </div>

    </div>

    <Footer/>
</template>

<style scoped>
    /* Modala loga stils */
    .modal-overlay {
        position: fixed; /* Fiksēta pozicija */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(19, 8, 0, 0.59);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000; /* Virs visiem elementiem */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
    }

    .modal {
        border-radius: 12px;
        padding: 15px;
        max-width: 400px;
        width: 90%;
        position: relative;
        background-color: #c58667; /* Fona krāsa */
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
    }

    .close{
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .success-container {
        text-align: center;
        padding: 15px;
    }

    .success-container h2 {
        margin-bottom: 15px;
        font-size:  1.3rem;
        font-weight: bold;
        color: rgba(26, 16, 8, 0.8);
    }

    .success-container p {
        margin-bottom: 15px;
        color: rgba(26, 16, 8, 0.8);

    }
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

    .avatar-wrapper {
        display: flex;
        justify-content: center; /* Центр горизонтально */
        margin-bottom: 50px;
    }

    .avatar {
        width: 120px; /* Avatar platums */
        height: 120px; /* Avatar augstums */
        border-radius: 50%; /* Padara avataru pilnīgu apli */
        border: 1px solid rgba(26, 16, 8, 0.8);
        background-color: #e4a27c;
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px;
        border-radius: 50%; /* Pilns aplis */
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        overflow: hidden; /* Paslēpj visu, kas pārsniedz aplīti */
        position: relative; /* Relatīvs pozicionējums */
    }

    .avatar img {
        width: 100%; /* Attēla platums pilnībā atbilst avataram */
        height: 100%; /* Attēla augstums pilnībā atbilst avataram */
        object-fit: cover; /* Attēls aptver visu aplīti, saglabājot proporcijas */
        border-radius: 50%;
    }

    .profile-icon {
        font-size: 90px;
        line-height: 1;
    }

    .avatar-overlay {
        position: absolute; /* Absolūtā pozīcija */
        top: 0; /* Novieto augšpusē */
        left: 0; /* Novieto kreisajā pusē */
        width: 100%;
        height: 100%;
        background-color: rgba(255, 200, 169, 0.56);
        display: flex;
        justify-content: center;
        align-items: center; /* Vertikāli centrē saturu */
        opacity: 0; /* Noklusējuma stāvoklī slēpts */
        transition: opacity 0.3s; /* Pāreja uz redzamību ar animāciju 0.3s */
    }

    .avatar:hover .avatar-overlay {
        opacity: 1;
    }

    .overlay-icon {
        color: rgba(26, 16, 8, 0.8);
        font-size: 3rem;
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

    label {
        font-weight: bold;
    }

    input::placeholder,
    textarea::placeholder {
        color: rgba(26, 16, 8, 0.42);
        font-size: 1.0rem;
    }

    /* Kļūdas zem ievades lauka */
    .error{
        color: rgb(110, 37, 37);
        font-size: 1rem;
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

    .close-btn{
        align-self: flex-start;
        margin-bottom: 5px;
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

        .modal{
            max-width: 300px;
        }

        .success-container h2{
            font-size: 1.1rem;
        }
        .avatar {
            width: 100px;
            height: 100px;
        }

        .profile-icon {
            font-size: 70px;
        }
    }
</style>
