<template>
    <Navbar />

    <div class="main-content">
        <div class="content">

            <div class="profile-header">
                <h1><strong>{{ user.nickname }}</strong></h1>

                <div class="bio-section">
                        <p class="bio-text"> Šeit pagaidām tukšs</p>
                        <button class="edit-btn">Rediģēt profilu</button>
                </div>
            </div>

            <div class="content-panel">
                <div class="stats-section">
                    <h2> Manā aktivitāte</h2>
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-number">{{ stats.booksRead }}</div>
                            <div class="stat-label">Izlasītās grāmatas</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ stats.favorites }}</div>
                            <div class="stat-label">Uzrakstītie darbi</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ stats.inProgress }}</div>
                            <div class="stat-label">Novērtētie darbi</div>
                        </div>

                    </div>
                </div>


                <div class="bookmarks-links">
                    <h2>Manas grāmatzīmes </h2>
                    <div class="links-grid">
                        <div class="bookmark-link read">
                            <span class="fa">&#xf02d;</span>
                            <span class="link-text">Izlasītās grāmatas</span>
                        </div>

                        <div class="bookmark-link ">
                            <span class="fa">&#xf046;</span>
                            <span class="link-text">Plānotās grāmatas</span>
                        </div>

                        <div class="bookmark-link ">
                            <span class="fa">&#xf02e;</span>
                            <span class="link-text">Lasu tagad</span>
                        </div>

                        <div class="bookmark-link ">
                            <span class="fa">&#xf1f8;</span>
                            <span class="link-text">Pamestās grāmatas</span>
                        </div>

                    </div>
                </div>

                <h2>Mani literārie darbi</h2>
                <div class="links-grid">
                    <div class="bookmark-link ">
                        <span class="fa">&#xf067;</span>
                        <span class="link-text">Izveidot jaunu darbu</span>
                    </div>

                    <div class="bookmark-link ">
                        <span class="fa">&#xf040;</span>
                        <span class="link-text">Rediģēt esošos darbus</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Footer/>
</template>

<style scoped>
    .main-content {
        padding: 20px;
        margin-top: 55px;
        color: rgba(26, 16, 8, 0.8);
        font-family: Tahoma, Helvetica, sans-serif;
        padding-bottom: 60px;
    }

    .content {
        max-width: 1000px;
        margin: 0 auto;
    }

    .profile-header {
        text-align: center;
        margin-bottom: 30px;
        padding: 0 20px;
    }

    .profile-header h1 {
        font-size: 2.2rem;
        margin-bottom: 10px;
    }

    .bio-text {
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 15px;
        color: rgba(26, 16, 8, 0.8);
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    .content-panel {
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
        background-color: #c58667;;
        border-radius: 10px;
        padding: 30px;
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px; /* Ēna */
    }

    .stats-section {
        margin-bottom: 30px;
    }

     h2 {
         text-align: center;
         margin-bottom: 25px;
         font-size: 1.6rem;
         font-weight: bold;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 20px;
        max-width: 800px;
        margin: 0 auto;
    }

    .stat-item {
        background-color: #ffd9c6;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 10px; /* Ēna */
    }


    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 1rem;
        letter-spacing: 1px;
    }

    .bookmarks-links {
        margin-bottom: 40px;
    }

    .links-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .bookmark-link {
        background-color: #ffd9c6;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 20px 15px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s;
        text-align: center;
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 10px; /* Ēna */
    }

    .bookmark-link:hover {
        transform: translateY(-3px);
        background-color: #ffc8a9;
    }

    .fa {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .link-text {
        font-size: 0.95rem;
    }


    .my-works-section h3 {
        color: rgba(106, 51, 0, 0.9);
        text-align: center;
        margin-bottom: 20px;
        font-size: 1.4rem;
    }


    /* Pogas stils, kad pele ir virs tās */
    button:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;;
    }

    .edit-btn {
        background-color: #c58667; /* Fona krāsa */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        border: 2px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
        border-radius: 4px; /* Noapaļotie stūri */
        font-size: 1.0rem; /* Teksta izmērs */
        transition: background-color 0.3s, border-color 0.3s;
        padding: 8px 15px;
        cursor: pointer;
    }


@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .links-grid {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 500px) {
    .profile-header h1 {
        font-size: 1.8rem;
    }

    .stats-grid,
    .links-grid {
        grid-template-columns: 1fr;
    }

    .content-panel {
        padding: 20px 15px;
    }
}
</style>

<script>
import Navbar from "@/components/Navbar.vue";
import Footer from "@/components/Footer.vue";
import { computed, ref } from "vue";
import { router, usePage } from "@inertiajs/vue3";

export default {
    setup() {
        const user = computed(() => usePage().props.auth.user);
        const bio = ref(user.value.bio || '');
        const isEditing = ref(false);

        const stats = ref({
            booksRead: 24,
            favorites: 8,
            inProgress: 5,
            abandoned: 3
        });

        const saveBio = () => {
            router.patch(route('profile.update'), {
                bio: bio.value
            }, {
                onSuccess: () => {
                    isEditing.value = false;
                }
            });
        };

        const publishWork = (workId) => {
            console.log('Darba publikācija', workId);
        };

        const deleteWork = (workId) => {
            console.log('Darba dzēšana', workId);
        };

        return {
            user,
            bio,
            isEditing,
            stats,
            saveBio,
            publishWork,
            deleteWork
        };
    },
    components: {
        Navbar,
        Footer
    }
};
</script>
