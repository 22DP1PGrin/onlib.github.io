<script setup>
    import {computed, ref} from "vue";
    import {usePage, router, useForm} from "@inertiajs/vue3";
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";
    import {route} from "ziggy-js";
    import SuccessModal from "@/Components/Modal/SuccessModal.vue";
    import ConfirmModal from "@/Components/Modal/ConfirmModal.vue";
    import SearchComponent from "@/Components/SearchComponent.vue";

    // Saņem lietotāju sarakstu no servera
    const users = computed(() => usePage().props.users);
    const admins = computed(() => usePage().props.admins);
    const currentUser = computed(() => usePage().props.currentUser);

    // Meklēšanas vaicājums - inicilizē ar esošo filtru vai tukšu virkni
    const search = ref(usePage().props.filters?.search || '');

    // Uzglabā izvēlēto lietotāju atbloķēšanai
    const selectedUser = ref(null);
    // Uzglabā izvēlēto lietotāju dzešanai
    const selectedUserForDelete = ref(null);

    const limit = 3; // Cik lietotāju rādīt sākumā

    // Kontrolē, vai rādīt visus lietotājus vai tikai limit skaitu
    const showAllUsers = ref(false);
    const showAllAdmins = ref(false);

    // Aprēķina redzamu parastu lietotāju atkarībā no showAllUsers
    const visibleUsers = computed(() =>
        showAllUsers.value ? users.value : users.value.slice(0, limit)
    );

    // Aprēķina redzamu administratoru atkarībā no showAllAdmins
    const visibleAdmins = computed(() =>
        showAllAdmins.value ? admins.value : admins.value.slice(0, limit)
    );

    // Veidlapa dati atbloķēšanai(tukša)
    const form = useForm({
        subject: '',
        problem: '',
        duration: '',
    });

    // Modālo logu stāvokļi
    const showUserModal = ref(false);
    const showSuccessModal = ref(false);
    const showDeleteModal = ref(false);
    const showSuccessDeleteModal = ref(false);

    // Atver modāli lietotāja konta atbloķēšanai
    const openUserBlockModal = (user) => {
        selectedUser.value = user;
        showUserModal.value = true;
        document.body.style.overflow = "hidden";
    };

    // Atver modāli dzēšanai
    const openDeleteModal = (user) => {
        selectedUserForDelete.value = user;
        showDeleteModal.value = true;
        document.body.style.overflow = "hidden";
    };

    // Meklēt lietotāju pēc lietotājvārda
    const searchUsers = () => {
        router.get(route('block.users'),
            { search: search.value }, // Meklēšanas parametrs
            {
                preserveState: true,
                replace: true,
            }
        );
    };

    // Apstiprina lietotāja konta atbloķēšanu
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

    // Apstiprina lietotāja konta dzēšanu
    const confirmDelete = () => {
        if (!selectedUserForDelete.value) return;

        router.delete(
            route('users.destroy', { user: selectedUserForDelete.value.id }),
            {
                preserveScroll: true,
                onSuccess: () => {
                    showDeleteModal.value = false;
                    showSuccessDeleteModal.value = true;
                }
            }
        );
    };

    // Lietotāju apskatīšana
    const GoToWatch = (userId) => {
        router.get(route('users.watch', { id: userId }));
    };
</script>

<template>
    <!-- Navigācijas josla -->
    <Navbar/>

    <div class="main-content">
        <!-- Dzēšanas apstiprinājuma modālais logs -->
        <ConfirmModal
            :is-open="showDeleteModal"
            title="Vai tiešām vēlaties dzēst šo lietotāju kontu?"
            confirm-text="Dzēst"
            @confirm="confirmDelete"
            @cancel="showDeleteModal = false"
        />

        <!-- Veiksmīgas atbloķēšanas modālais logs -->
        <SuccessModal
            :is-open="showSuccessDeleteModal"
            title="Lietotājs veiksmīgi dzēsts!"
            @close="showSuccessDeleteModal = false"
        />

        <!-- Atbloķēšanas apstiprinājuma modālais logs -->
        <ConfirmModal
            :is-open="showUserModal"
            title="Vai tiešām vēlaties atbloķēt šo lietotāju kontu?"
            confirm-text="Atbloķēt"
            @confirm="confirmUserBlock"
            @cancel="showUserModal = false"
        />

        <!-- Veiksmīgas atbloķēšanas modālais logs -->
        <SuccessModal
            :is-open="showSuccessModal"
            title="Konts veiksmīgi atbloķēts!"
            @close="showSuccessModal = false"
        />

        <!-- Meklēšanas josla -->
        <SearchComponent
            v-model="search"
            placeholder="Meklēt lietotāju..."
            @search="searchUsers"
        />

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

                            <!-- Dzēšana, apskatīšana, atbloķēšana -->
                            <div class="buttons-container">
                                <button class="delete-btn" @click="openDeleteModal(admin)" v-if="currentUser.role === 'superadmin'">
                                    Dzēst
                                </button>
                                <button class="watch-btn" @click="GoToWatch(admin.id)">Apskatīt</button>
                                <button class="watch-btn" @click="openUserBlockModal(admin)">
                                    Atbloķēt
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Skatīt vairāk -->
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

                        <!-- Dzēšana, apskatīšana, atbloķēšana -->
                        <div class="buttons-container">
                            <button class="delete-btn" @click="openDeleteModal(user)" v-if="currentUser.role === 'superadmin'">
                                Dzēst
                            </button>
                            <button class="watch-btn" @click="GoToWatch(user.id)">Apskatīt</button>
                            <button class="watch-btn" @click="openUserBlockModal(user)">
                                Atbloķēt
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Skatīt vairāk -->
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
    <!-- Kājene -->
    <Footer/>
</template>

<style scoped>
    .main-content {
        padding-bottom: 45px; /* Apakšējais atstatums */
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

    label {
        font-weight: bold;
        text-align: left;
        color: rgba(26, 16, 8, 0.8); /* Krāsa */
    }

    textarea::placeholder {
        color: rgba(26, 16, 8, 0.42);
        font-size: 1.0rem;
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
        select,
        option,
        option::placeholder,
        input::placeholder,
        textarea::placeholder
        {
            font-size: 0.9rem;
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
    }
</style>
