<script setup>
    import BaseModal from './BaseModal.vue'

    // Komponenta ievaddati
    const props = defineProps({
        isOpen: {
            type: Boolean,
            default: false
        },
        title: {
            type: String,
            default: ''
        },
    })

    // Emit notikumi - lai paziņotu vecākkomponentam par aizvēršanu
    const emit = defineEmits(['close'])

    // Aizvēršanas pogas klikšķa apstrāde
    const handleClose = () => {
        emit('close')
        document.body.style.overflow = ""
    }
</script>

<template>
    <!-- Izmanto pamata modāli -->
    <BaseModal
        :is-open="isOpen"
        :title="title"
        modal-class="modal success"
        :show-close-button="false"
        @close="handleClose"
    >
        <!-- Ievieto vienu pogu -->
        <template #body>
            <div class="success-buttons">
                <button @click="handleClose" class="btn-close">Aizvērt</button>
            </div>
        </template>
    </BaseModal>
</template>

<style scoped>
    button {
        background-color: #c58667;
        border: 2px solid rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        font-family: Tahoma, Helvetica, sans-serif;
        font-size: 1rem;
        padding: 5px 30px;
        min-width: 80px; /* Minimālais platums */
    }

    .success-buttons {
        display: flex;
        justify-content: center;
    }

    .btn-close {
        align-self: center;
        margin-bottom: 5px;
    }

    button:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;
    }

    /* Responsīvs dizains maziem ekrāniem (līdz 500px) */
    @media (max-width:500px) {
        .modal-header h2 {
            font-size: 1.2rem;
        }

        button {
            font-size: 0.9rem;
            padding: 4px 10px !important;
        }
    }
</style>
