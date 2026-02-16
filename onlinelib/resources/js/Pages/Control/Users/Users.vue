<script setup>
    import {computed, ref} from "vue";
    import {usePage, router, useForm} from "@inertiajs/vue3";
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";
    import {route} from "ziggy-js";

    // Saņem lietotāju sarakstu no servera
    const users = computed(() => usePage().props.users);
    const admins = computed(() => usePage().props.admins);
    const currentUser = computed(() => usePage().props.currentUser);

    const selectedUser = ref(null);

    const limit = 3; // Cik lietotāju rādīt sākumā

    const showAllUsers = ref(false);
    const showAllAdmins = ref(false);

    // Aprēķina redzamu parastu lietotāju atkarībā no showAllBooks
    const visibleUsers = computed(() =>
        showAllUsers.value ? users.value : users.value.slice(0, limit)
    );

    // Aprēķina redzamu administratoru atkarībā no showAllBooks
    const visibleAdmins = computed(() =>
        showAllAdmins.value ? admins.value : admins.value.slice(0, limit)
    );

    // Veidlapa dati bloķēšanai
    const form = useForm({
        subject: '',
        problem: '',
        duration: '',
    });

    // Modālo logu stāvokļi
    const showUserModal = ref(false);
    const showSuccessModal = ref(false);

    // Atver modāli lietotāja konta bloķēšanai
    const openUserBlockModal = (user) => {
        selectedUser.value = user;
        showUserModal.value = true;
        document.body.style.overflow = "hidden";
    };

    // Apstiprina lietotāja konta bloķēšanu
    const confirmUserBlock = () => {
        if (!selectedUser.value) return;
        form.post(
            route('user.toggleBlock', { user: selectedUser.value.id }),
            {
                preserveScroll: true,

                onSuccess: () => {
                    form.reset();
                    showUserModal.value = false;
                    showSuccessModal.value = true;
                }
            }
        );
    };

    // Aizver visus bloķēšanas modāļus
    const closeUserModals = () => {
        showUserModal.value = false;
        form.reset();
        document.body.style.overflow = "";
    };

    // Aizver veiksmīgas darbības modāli
    const closeSuccessModal = () => {
        showSuccessModal.value = false;
        router.visit(window.location.href);
        document.body.style.overflow = "";
    };

    // Lietotāju apskatīšana
    const GoToWatch = (userId) => {
        router.get(route('users.watch', { id: userId }));
    };
</script>

<template>

    <Navbar/>

    <div class="main-content">
        <!-- Bloķēšanas apstiprinājuma modālais logs stāstam -->
        <div v-if="showUserModal" class="modal-overlay">
            <div class="modal">
                <div class="success-container">
                    <p class="h2">Vai tiešām vēlaties bloķēt šo lietotāju kontu?</p>
                    <p>Lūdzu, norādiet bloķēšanas iemeslu, lai apstiprinātu.</p>

                    <div class="form-group">
                        <!-- Tēmas izvēle -->
                        <label for="subject">Tēma:</label>
                        <select v-model="form.subject" required>
                            <option value="" disabled>Izvēlieties tēmu</option>
                            <option value="Krāpnieciska vai maldinoša darbība">Krāpnieciska vai maldinoša darbība</option>
                            <option value="Noteikumu pārkāpums">Noteikumu pārkāpums</option>
                            <option value="Naida runa vai diskriminējoša uzvedība"> Naida runa vai diskriminējoša uzvedība</option>
                            <option value="Citu lietotāju aizskaršana">Citu lietotāju aizskaršana</option>
                            <option value="Sūdzības no lietotājiem">Sūdzības no lietotājiem</option>
                        </select>

                        <div v-if="form.errors.subject" class="error">
                            {{ form.errors.subject }}
                        </div>
                    </div>

                    <!-- Pamatojuma ievades lauks -->
                    <div class="form-group">
                        <label for="problem">Pamatojums:</label>
                        <textarea v-model="form.problem" required></textarea>

                        <!-- Validācijas kļūdas paziņojums pamatojumam -->
                        <div v-if="form.errors.problem" class="error">
                            {{ form.errors.problem }}
                        </div>
                    </div>

                    <!-- Ilgums izvēle -->
                    <div class="form-group">
                        <label>Bloķēšanas ilgums:</label>

                        <select v-model="form.duration" required>
                            <option value="" disabled selected>Izvēlieties periodu</option>
                            <option :value="1">1 nedēļa</option>
                            <option :value="2">2 nedēļas</option>
                            <option :value="3">3 nedēļas</option>
                            <option :value="4">4 nedēļas</option>
                            <option :value="null" selected>Neierobežots</option>
                        </select>

                        <div v-if="form.errors.duration" class="error">
                            {{ form.errors.duration }}
                        </div>
                    </div>

                    <div class="close">
                        <button @click="closeUserModals" class="close-btn">Atcelt</button>
                        <button @click="confirmUserBlock" class="block">Bloķēt</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Veiksmīgas bloķēšanas modālais logs -->
        <div v-if="showSuccessModal" class="modal-overlay">
            <div class="modal">
                <div class="success-container">
                    <h2>Konts veiksmīgi bloķēts!</h2>
                    <button @click="closeSuccessModal" class="close-btn">Aizvērt</button>
                </div>
            </div>
        </div>

        <!-- Administartoru pārvaldības forma (tikai superadminiem) -->
        <div v-if="currentUser.role === 'superadmin'">
            <div class="story-form">
                <h1>Administratori</h1>
                <div class="container">
                    <div class="list">
                        <!-- Paziņojums, ja nav administratoru -->
                        <div v-if="admins.length === 0" class="item">
                            <span class="title">Šeit vēl nav administratora.</span>
                        </div>

                        <!-- Atkārtojas katram lietotājam -->
                        <div v-for="admin in visibleAdmins" :key="admin.id" class="item">
                            <!-- Lietotājvārda attēlošana -->
                            <span class="title">{{ admin.nickname }}</span>


                            <div class="buttons-container">
                                <!-- Apskatīšana, bloķēšana -->
                                <button class="delete-btn" @click="openUserBlockModal(admin)">
                                    Bloķēt
                                </button>
                                <button class="watch-btn" @click="GoToWatch(admin.id)">Apskatīt</button>
                            </div>
                        </div>
                    </div>

                    <!-- Skatī vairāk -->
                    <div class="toggle-container">
                        <button
                            v-if="admins.length > limit"
                            class="toggle-btn"
                            @click="showAllAdmins = !showAllAdmins"
                        >
                            {{ showAllAdmins ? 'Paslēpt' : 'Skatīt vairāk' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lietotāju pārvaldības forma -->
        <div class="story-form">
            <h1>Parastie lietotāji</h1>

            <div class="container">
                <!-- Lietotāju saraksts -->
                <div class="list">
                    <!-- Paziņojums, ja nav lietotāju -->
                    <div v-if="users.length === 0" class="item">
                        <span class="title">Šeit vēl nav lietotāju.</span>
                    </div>

                    <!-- Atkārtojas katram lietotājam -->
                    <div v-for="user in visibleUsers" :key="user.id" class="item">
                        <!-- Lietotājvārda attēlošana -->
                        <span class="title">{{ user.nickname }}</span>


                        <div class="buttons-container">
                            <!-- Apskatīšana, bloķēšana -->
                            <button class="delete-btn" @click="openUserBlockModal(user)">
                                Bloķēt
                            </button>
                            <button class="watch-btn" @click="GoToWatch(user.id)">Apskatīt</button>
                        </div>
                    </div>
                </div>

                <!-- Skatī vairāk -->
                <div class="toggle-container">
                    <button
                        v-if="users.length > limit"
                        class="toggle-btn"
                        @click="showAllUsers = !showAllUsers"
                    >
                        {{ showAllUsers ? 'Paslēpt' : 'Skatīt vairāk' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <Footer/>
</template>

<style scoped>

    .main-content {
        padding-bottom: 45px; /* Apakšējais atstatums */
    }

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
        background-color: #e4a27c; /* Fona krāsa */
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
        max-height: 75vh;
        overflow-y: auto;
        scrollbar-width: thin; /* Plāna ritjosla */
    }

    .close{
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .close-btn{
        align-self: flex-start;
        margin-bottom: 5px;
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

    .story-form {
        max-width: 800px; /* Maksimālais platums */
        margin: 0 auto; /* Centrē forma horizontāli */
        padding: 20px; /* Piepildījums ap saturu */
    }

    h1 {
        font-size: 1.7rem; /* Fonta lielums */
        margin-top: 32px;
        margin-bottom: 40px;
        text-align: center; /* Centrēts teksts */
        color: rgba(26, 16, 8, 0.8); /* Krāsa */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
        font-weight: bold; /* Trekns fonts */
    }

    .h2{
        font-size: 1.1rem; /* Fonta lielums */
        text-align: center; /* Centrēts teksts */
        color: rgba(26, 16, 8, 0.8); /* Krāsa */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
        font-weight: bold; /* Trekns fonts */
    }

    .list {
        margin-bottom: 20px;
    }

    .item {
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
        background-color: #e4a27c;
        border-radius: 8px; /* Noapaļotas malas */
        text-align: center;
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 10px; /* Ēna*/
        font-family: Tahoma, Helvetica, sans-serif;
        display: flex; /* Izmanto flexbox režīmu */
        justify-content: space-between; /* Izlīdzina saturu horizontāli */
        align-items: center;
        padding: 10px 15px; /* Piepildījums ap saturu */
        margin-bottom: 10px;
    }

    .title {
        color: rgba(26, 16, 8, 0.8);
        font-family: Tahoma, Helvetica, sans-serif;
        font-size: 1.1rem;
        font-weight: bold;
    }

    .buttons-container {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .toggle-container {
        display: flex;
        justify-content: center;
    }

    .chapter-item button {
        margin-left: 3px;
    }

    button {
        border: 2px solid rgba(26, 16, 8, 0.8);
        background-color: #c58667; /* Fona krāsa */
        color: rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        font-size: 1.0rem;
        font-family: Tahoma, Helvetica, sans-serif;
        transition: background-color 0.3s, border-color 0.3s; /* Pārejas efekts, lai uzlabotu interaktivitāti */
        cursor: pointer; /* Kursora izmaiņas pie pogas */
        padding: 5px 10px;
    }

    .block {
        align-self: flex-start;
        margin-bottom: 5px;
        border: 2px solid rgba(35, 11, 11, 0.8);
        background-color: #714e3e;
    }

    .delete-btn {
        padding: 3px 15px;
        border: 2px solid rgba(35, 11, 11, 0.8);
        background-color: #714e3e;
        border-radius: 4px;
    }

    .watch-btn{
        background-color: #c58667;
        border: 2px solid rgba(26, 16, 8, 0.8);
        padding: 3px 12px;
        border-radius: 4px;
        transition: all 0.3s; /* Pāreja uz mainīgām īpašībām */
    }

    .toggle-btn{
        font-family: Tahoma, Helvetica, sans-serif;
        background-color: #c58667;
        border: 2px solid rgba(26, 16, 8, 0.8);
        align-self: center;
        padding: 6px 20px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s; /* Pāreja uz mainīgām īpašībām */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
    }

    button:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin-bottom: 10px;
    }

    label {
        font-weight: bold;
        text-align: left;
        color: rgba(26, 16, 8, 0.8); /* Krāsa */
    }

    textarea::placeholder {
        color: rgba(26, 16, 8, 0.42);
        font-size: 1.0rem;
    }

    /* Kļūdas zem ievades lauka */
    .error{
        color: rgb(110, 37, 37);
        font-size: 1rem;
        text-align: left;
        margin-bottom: 5px;
    }

    .form-group select,
    .form-group textarea {
        padding: 10px;
        border: 1px solid rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        font-size: 1rem;
    }

    option{
        font-size: 1rem;
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

    @media (max-width: 500px) {
        h1 {
            font-size: 1.5rem;
        }
        .title{
            font-size: 1rem;
        }

        p,
        label,
        .error,
        select,
        option,
        option::placeholder,
        input::placeholder,
        textarea::placeholder
        {
            font-size: 0.9rem;
        }

        .h2{
            font-size: 1.1rem;
        }

        .buttons-container {
            gap: 2px;
        }

        .delete-btn {
            padding: 2px 8px;
            font-size: 0.9rem;
        }
        .watch-btn{
            padding: 2px 8px;
            font-size: 0.9rem;
        }

        .modal{
            max-width: 300px;
        }
    }
</style>
