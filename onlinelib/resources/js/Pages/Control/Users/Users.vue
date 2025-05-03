<script>
import { computed } from "vue";
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

        const GoToWatch = (userId) => {
            router.get(route('users.watch', { id: userId }));
        };

        return {
            users,
            deleteUser,
            GoToWatch
        };
    },
};
</script>

<template>

    <Navbar/>

    <div class="main-content">
        <!-- Lietotāju pārvaldības forma -->
        <div class="story-form">

            <h1>Lietotāji</h1>

            <div class="container">
                <!-- Lietotāju saraksts -->
                <div class="list">
                    <!-- Paziņojums, ja nav lietotāju -->
                    <div v-if="users.length === 0" class="item">
                        <span class="title">Šeit vēl nav lietotāju.</span>
                    </div>

                    <!-- Atkārtojas katram lietotājam -->
                    <div v-for="user in users" :key="user.id" class="item">
                        <!-- Lietotājvārda attēlošana -->
                        <span class="title">{{ user.nickname }}</span>


                        <div class="buttons-container">
                            <!-- Lietotāja dzēšanas poga -->
                            <button class="watch-btn" @click="GoToWatch(user.id)">Apskatīt</button>
                            <button class="delete-btn" @click="deleteUser(user.id)">
                                Dzēst
                            </button>
                        </div>
                    </div>
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
