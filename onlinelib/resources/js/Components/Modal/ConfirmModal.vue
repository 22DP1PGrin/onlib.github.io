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
        confirmText: {
            type: String,
            default: ''
        },
    })

    // Emit notikumi - lai paziņotu vecākkomponentam par lietotāja darbībām
    const emit = defineEmits(['confirm', 'cancel'])

    // Apstiprināšanas pogas klikšķa apstrāde
    const handleConfirm = () => {
        emit('confirm')
    }

    // Atcelšanas pogas klikšķa apstrāde
    const handleCancel = () => {
        emit('cancel')
    }
</script>

<template>
    <!-- Izmanto pamata modāli -->
    <BaseModal
        :is-open="isOpen"
        :title="title"
        modal-class="modal warning"
        @close="handleCancel"
    >
        <!-- Ievieto divas pogas -->
        <template #body>
            <div class="button-group">
                <button @click="handleCancel" class="btn-cancel">Atcelt</button>
                <button @click="handleConfirm" class="btn-confirm">{{ confirmText }}</button>
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
        padding: 5px 25px;
        min-width: 80px; /* Minimālais platums */
    }

    .button-group {
        display: flex;
        gap: 140px; /* Atstarpe starp pogām */
        justify-content: center; /* Centrē pogas horizontāli */
    }

    .btn-cancel {
        align-self: flex-start;
        margin-bottom: 5px;
    }

    .btn-confirm {
        align-self: flex-start;
        margin-bottom: 5px;
        border: 2px solid rgba(35, 11, 11, 0.8);
        background-color: #714e3e;
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

        button{
            font-size: 0.9rem;
            padding: 4px 20px;
        }

        .button-group{
            gap:80px;
        }
    }
</style>
