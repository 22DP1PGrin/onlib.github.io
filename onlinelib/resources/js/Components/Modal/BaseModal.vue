<script setup>
    import { watch } from 'vue'

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
        modalClass: {
            type: String,
            default: ''
        },
        showCloseButton: {
            type: Boolean,
            default: true
        },
        closeOnOverlay: {
            type: Boolean,
            default: true
        }
    })

    // Emit notikumi - lai paziņotu vecākkomponentam par aizvēršanu
    const emit = defineEmits(['close'])

    // Funkcija modālā loga aizvēršanai
    const handleClose = () => {
        if (props.closeOnOverlay) {
            emit('close')
            document.body.style.overflow = "" // Atbloķē lapas ritināšanu
        }
    }

    // Bloķē vai atbloķē lapas ritināšanu, kad modālis tiek atvērts/aizvērts
    watch(() => props.isOpen, (newVal) => {
        if (newVal) {
            document.body.style.overflow = "hidden" // Bloķē ritināšanu
        } else {
            document.body.style.overflow = "" // Atbloķē ritināšanu
        }
    }, { immediate: true }) // Palaiž uzreiz komponenta ielādes laikā
</script>

<template>
    <!-- Modālā loga pārklājums -->
    <div v-if="isOpen" class="modal-overlay" @click.self="handleClose">
        <div class="modal" :class="modalClass">
            <!-- Modālā loga galvene -->
            <div class="modal-header" v-if="$slots.header || title">
                <h2 v-if="title">{{ title }}</h2>
                <div v-else><slot name="header" /></div>
            </div>

            <!-- Modālā loga saturs -->
            <div class="modal-body">
                <p><slot name="body" /></p>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .modal-overlay {
        position: fixed; /* Fiksēta pozīcija, neatkarīga no ritināšanas */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(19, 8, 0, 0.59);
        display: flex;
        justify-content: center; /* Horizontāli centrē */
        align-items: center; /* Vertikāli centrē */
        z-index: 1000; /* Novieto virs visa cita satura */
        font-family: Tahoma, Helvetica, sans-serif;
    }

    .modal {
        border-radius: 12px; /* Noapaļoti stūri */
        padding: 15px;
        max-width: 400px; /* Maksimālais platums */
        width: 90%; /* Responsīvs platums */
        position: relative;
        background-color: #e4a27c; /* Fona krāsa */
        border: 1px solid rgba(26, 16, 8, 0.8); /* Apmale */
        font-family: Tahoma, Helvetica, sans-serif;
    }

    .modal-header {
        text-align: center; /* Centrē tekstu */
        padding: 15px;
    }

    .modal-header h2 {
        margin-bottom: 15px;
        font-size: 1.3rem;
        font-weight: bold;
        color: rgba(26, 16, 8, 0.8);
    }

    .modal-body p {
        margin-bottom: 15px;
        color: rgba(26, 16, 8, 0.8);
    }

    /* Responsīvs dizains maziem ekrāniem (līdz 500px) */
    @media (max-width:500px) {
        .modal-header h2 {
            font-size: 1.2rem;
        }
    }
</style>
