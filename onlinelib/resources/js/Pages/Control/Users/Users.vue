<script>
import {computed, ref} from "vue";
import { usePage, router } from "@inertiajs/vue3";
import Navbar from "@/Components/Navbar.vue";
import Footer from "@/Components/Footer.vue";
import {route} from "ziggy-js";

export default {
    components: {
        Navbar,
        Footer,
    },
    setup() {
        const users = computed(() => usePage().props.users);
        const admins = computed(() => usePage().props.admins);
        const currentUser = computed(() => usePage().props.currentUser);

        const limit = 3;

        const showAllUsers = ref(false);
        const showAllAdmins = ref(false);

        const visibleUsers = computed(() =>
            showAllUsers.value ? users.value : users.value.slice(0, limit)
        );

        const visibleAdmins = computed(() =>
            showAllAdmins.value ? admins.value : admins.value.slice(0, limit)
        );

        const GoToWatch = (userId) => {
            router.get(route('users.watch', { id: userId }));
        };

        return {
            users,
            admins,
            currentUser,
            GoToWatch,
            visibleUsers,
            visibleAdmins,
            showAllUsers,
            showAllAdmins,
            limit
        };
    },
};
</script>

<template>

    <Navbar/>

    <div class="main-content">

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
                                <!-- Lietotāja dzēšanas poga -->
                                <button class="watch-btn" @click="GoToWatch(admin.id)">Apskatīt</button>
                                <button class="delete-btn" @click="deleteUser(admin.id)">
                                    Bloķēt
                                </button>
                            </div>
                        </div>
                    </div>

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
                            <!-- Lietotāja dzēšanas poga -->
                            <button class="watch-btn" @click="GoToWatch(user.id)">Apskatīt</button>
                            <button class="delete-btn" @click="deleteUser(user.id)">
                                Bloķēt
                            </button>
                        </div>
                    </div>
                </div>
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

    .delete-btn {
        padding: 3px 15px;
        border: 2px solid rgba(35, 11, 11, 0.8);
        background-color: #714e3e;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s; /* Pāreja uz mainīgām īpašībām */
    }
    .watch-btn{
        background-color: #c58667;
        border: 2px solid rgba(26, 16, 8, 0.8);
        padding: 3px 12px;
        border-radius: 4px;
        cursor: pointer;
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

    @media (max-width: 500px) {
        h1 {
            font-size: 1.5rem;
        }
        .title{
            font-size: 1rem;
        }

        .buttons-container {
            gap: 3px;
        }

        .delete-btn {
            padding: 3px 12px;
            font-size: 0.9rem;
        }
        .watch-btn{
            padding: 3px 9px;
            font-size: 0.9rem;
        }

    }
</style>
