<script setup>
    import {useForm, usePage} from '@inertiajs/vue3';
    import Navbar from "@/Components/Navbar.vue";
    import Footer from "@/Components/Footer.vue";
    import {computed, ref} from "vue";
    import {route} from "ziggy-js";
    import SuccessModal from "@/Components/Modal/SuccessModal.vue";

    // Komponenta ievaddati
    const props = defineProps({
        users: Object, // Objekts
    });

    // Iegūst pašreizējā pieslēgušos lietotāja datus
    const currentUser = computed(() => usePage().props.auth.user)

    // Modālo logu stāvokļi
    const showModal = ref(false);

    // Izveido veidlapas datus, sākotnēji aizpildītus ar esošo stāsta informāciju
    const form = useForm({
        id: props.users.id,
        nickname: props.users.nickname,
        email: props.users.email,
        bio: props.users.bio,
        avatar: props.users.avatar,
        role: props.users.role,
        blocked_until: props.users.blocked_until,
        is_blocked: props.users.is_blocked

    });

    // Maina lietotāju lomu
    const updateRole = () => {
        form.put(route('users.updateRole', form.id), {
            preserveScroll: true,
            onSuccess: () => {
                showModal.value = true;
                document.body.style.overflow = "hidden";
            }
        });
    };
</script>

<template>
    <!-- Navigācijas josla -->
    <Navbar />

    <!-- Galvenais satura bloks -->
    <div class="main-content">

        <!-- Veiksmīgas atjaunināšanas modālais logs -->
        <SuccessModal
            :is-open="showModal"
            title="Lietotāja loma ir atjaunināta!"
            @close="showModal = false"
        />

        <!-- Lietotāja informācija-->
        <div class="user-form">

            <h1>Lietotāja apskate</h1>

            <!-- Lietotāja avatars-->
            <div class="avatar-wrapper">
                <div class="avatar">
                    <i v-if="!form.avatar" class="fa profile-icon">&#xf2be;</i>
                    <img v-else :src="`/storage/${form.avatar}`" alt="avatar" />
                </div>
            </div>

            <!-- Informācija par bloķēšānu -->
            <div v-if="form.is_blocked" class="block">
                <p>
                    Šis lietotājs ir bloķēts līdz
                    {{ form.blocked_until ? new Date(form.blocked_until).toLocaleString('lv-LV', {
                        timeZone: 'Europe/Riga',
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    })
                    : 'uz nenoteiktu laiku'
                    }}
                    .
                </p>
            </div>

            <!-- Veidlapas sadaļa -->
            <div class="form">
                <!-- Lietotājvārds -->
                <div class="form-group">
                    <h2 class="title">Lietotājsvārds</h2>
                    <div class="form-info">{{form.nickname}}</div>
                </div>

                <!-- Lietotāja e-pasts-->
                <div class="form-group">
                    <h2 class="title">E-pasts</h2>
                    <div class="form-info">{{form.email}}</div>
                </div>

                <!-- Lietotāja apraksts-->
                <div class="form-group">
                    <h2 class="title">Apraksts</h2>
                    <div class="form-info">{{form.bio || 'Nav informācijas par lietotāju.'}}</div>
                </div>

                <!-- Lietotāja loma. Tikai superadmins var izmainīt -->
                <div class="form-group" v-if="currentUser.role === 'superadmin'">
                    <h2 class="title">Loma</h2>

                    <select v-model="form.role">
                        <option value="user">Parasts lietotājs</option>
                        <option value="admin">Administrators</option>
                    </select>
                </div>

                <!-- Formas iesniegšanas poga -->
                <div class="form-actions">
                    <button @click="updateRole" class="btn">Saglabāt</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Kājene -->
    <Footer/>
</template>

<style scoped>
    .main-content {
        padding-bottom: 45px; /* Atstarpe apakšā */
    }

    .user-form {
        max-width: 500px; /* Maksimālais platums formai */
        margin: 0 auto; /* Centrēšana */
        padding: 20px; /* Iekšējā atstarpe */
    }

    .form{
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmales krāsa */
        background-color: #e4a27c; /* Fona krāsa */
        border-radius: 10px; /* Apmales noapaļojums */
        padding: 30px; /* Iekšējā atstarpe */
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px; /* Ēna */
    }
    h1 {
        font-size: 1.7rem;
        margin-top: 32px;
        margin-bottom: 20px;
        text-align: center; /* Centrēts teksts */
        color: rgba(26, 16, 8, 0.8);
        font-family: Tahoma, Helvetica, sans-serif;
        font-weight: bold; /* Treknraksts */
    }

    .form-group {
        margin-bottom: 20px; /* Atstarpe starp laukiem */
        word-wrap: break-word;
    }

    .avatar-wrapper {
        display: flex;
        justify-content: center; /* Horizontāli centrēts */
        margin-bottom: 20px;
    }

    .avatar {
        width: 120px; /* Avatar platums */
        height: 120px; /* Avatar augstums */
        border-radius: 50%; /* Padara avataru pilnīgu apli */
        border: 1px solid rgba(26, 16, 8, 0.8);
        background-color: #e4a27c;
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .block{
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
        color: rgba(26, 16, 8, 0.8);
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
    }

    .block p{
        font-weight: bold;
        font-size: 1.1rem;
    }

    .avatar img {
        width: 100%; /* Attēla platums pilnībā atbilst avataram */
        height: 100%; /* Attēla augstums pilnībā atbilst avataram */
        object-fit: cover; /* Attēls aptver visu aplīti, saglabājot proporcijas */
        border-radius: 50%;
    }

    .profile-icon {
        font-size: 90px; /* Ikonas lielums, ja nav bildes */
        color: rgba(26, 16, 8, 0.8);
    }

    .title {
        display: block; /* zem viena bloka */
        font-size: 1.1rem;
        margin-bottom: 8px; /* Atstarpe no ievadlaukuma */
        font-weight: bold;
    }

    .form-info,
    select{
        width: 100%; /* Aizņem visu pieejamo platumu */
        padding: 10px; /* Iekšējā atstarpe */
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmale */
        border-radius: 4px; /* Noapaļoti stūri */
        background-color: white; /* Fona krāsa */
        font-size: 1rem;
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonts */
    }

    select:focus{
        outline: none; /* Noņem noklusēto fokusu */
        box-shadow: none; /* Noņem ēnu */
        background-color: #ffd9c6; /* Fona krāsa */
        border-color: rgba(26, 16, 8, 0.8); /* Apmales krāsa */
    }

    button {
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 1.0rem;
        background-color: #c58667; /* Fona krāsa */
        border: 2px solid rgba(26, 16, 8, 0.8);
        color: rgba(26, 16, 8, 0.8);
    }
    .form-actions {
        display: flex;
        justify-content: flex-end; /* Pogas labajā pusē */
        gap: 15px;
        margin-top: 30px;
    }
    .btn {
        border: 2px solid rgba(35, 11, 11, 0.8);
        color: rgba(26, 16, 8, 0.8);
        padding: 5px 20px;
    }

    button:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;
    }


    @media (max-width: 500px) {
        h1 {
            font-size: 1.5rem;
        }
        .title,
        .block {
            font-size: 1rem;
        }

        .form-info {
            font-size: 0.9rem;
        }

        .btn{
            font-size: 0.9rem;
            padding: 5px 15px;
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

