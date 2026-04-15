<script setup>
    import { reactive, watch } from 'vue'

    // Komponenta ievaddati
    const props = defineProps({
        isOpen: {
            type: Boolean,
            default: false
        },
        title: {
            type: String,
            required: true
        },
        message : {
            type: String,
            required: true
        },
        fields: {
            type: Array,
            required: true
        },
        submitText: {
            type: String,
            default: 'Nosūtīt'
        },
        processing: {
            type: Boolean,
            default: false
        },
        initialData: {
            type: Object,
            default: () => ({})
        },
        errors: {
            type: Object,
            default: () => ({})
        }
    })

    // Definē komponenta emitus: datu nosūtīšana un aizvēršana
    const emit = defineEmits(['submit', 'close'])

    // Reaktīvs objekts formas datu glabāšanai
    const formData = reactive({})

    // Inicializē formu
    watch(() => props.isOpen, (isOpen) => {
        if (isOpen) {
            document.body.style.overflow = "hidden"

            // Notīra iepriekšējos formas datus
            Object.keys(formData).forEach(key => {
                delete formData[key]
            })

            // Ielādē sākotnējos datus
            props.fields.forEach(field => {
                formData[field.name] = props.initialData[field.name] || ''
            })
        }
    }, { immediate: true })

    // Apstrādā formas iesniegšanu
    const handleSubmit = () => {
        emit('submit', { ...formData })
    }

    // Apstrādā aizvēršanu
    const handleClose = () => {
        emit('close')
        document.body.style.overflow = ""
    }

    // Funkcija, kas automātiski pielāgo textarea augstumu atkarībā no teksta daudzuma
    const resizeTextarea = (el) => {
        el.style.height = "auto"
        el.style.height = el.scrollHeight + "px"

        // Ja teksts pārsniedz 200px, tiek parādīts vertikālais scroll
        el.style.overflowY = el.scrollHeight > 200 ? "auto" : "hidden"
    }
</script>
<template>
    <div v-if="isOpen" class="modal-overlay" @click.self="handleClose">
        <div class="modal">
            <div class="success-container">
                <!-- Modāla virsraksts un paziņojuma teksts -->
                <h2>{{ title }}</h2>
                <p>{{ message }}</p>

                <!-- Dinamiska forma, kas ģenerē laukus no iveddatus -->
                <form @submit.prevent="handleSubmit">
                    <div class="form-group" v-for="field in fields" :key="field.name">
                        <label :for="field.name">{{ field.label }}</label>

                        <!-- Atlases lauks -->
                        <select v-if="field.type === 'select'"
                                :id="field.name"
                                v-model="formData[field.name]"
                                :required="field.required">
                            <option value="" disabled>{{ field.placeholder || 'Izvēlieties tēmu' }}</option>
                            <option v-for="option in field.options" :key="option.value" :value="option.value">
                                {{ option.label }}
                            </option>
                        </select>

                        <!-- Teksta laukums -->
                        <textarea v-else-if="field.type === 'textarea'"
                                  :id="field.name"
                                  v-model="formData[field.name]"
                                  :rows="field.rows || 4"
                                  :placeholder="field.placeholder"
                                  @input="resizeTextarea($event.target)"
                                  :required="field.required">
                        </textarea>

                        <!-- Ievades lauks -->
                        <input v-else
                               :id="field.name"
                               :type="field.type === 'input' ? 'text' : field.type"
                               v-model="formData[field.name]"
                               :placeholder="field.placeholder"
                               :required="field.required">

                        <!-- Kļūdas ziņojums konkrētajam laukam -->
                        <div v-if="errors[field.name]" class="error">
                            {{ errors[field.name] }}
                        </div>
                    </div>

                    <!-- Formas darbību pogas -->
                    <div class="close">
                        <button type="button" @click="handleClose" class="close-btn">Atcelt</button>
                        <button type="submit" class="danger-btn">{{ submitText }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
        padding: 3px 20px;
        align-items: center;
    }

    button:hover {
        background-color: #ffc8a9;
        border-color: #ffc8a9;
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

    .danger-btn{
        align-self: flex-start;
        margin-bottom: 5px;
        border: 2px solid rgba(35, 11, 11, 0.8);
        background-color: #714e3e;
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
        font-size: 0.9rem;
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
        text-align: left;
        margin-bottom: 5px;
    }

    .form-group select,
    .form-group textarea {
        resize: none;
        overflow: hidden;
        padding: 10px;
        border: 1px solid rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        font-size: 1rem;
    }

    option{
        font-size: 1rem;
    }

    textarea {
        resize: none;
        overflow: hidden;
        min-height: 40px;
        max-height: 200px;
        width: 100%;
        box-sizing: border-box;
        padding: 10px;
        border: 1px solid rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        font-size: 1rem;
    }

    .form-group input{
        padding: 10px;
        border: 1px solid rgba(26, 16, 8, 0.8);
        border-radius: 4px;
        font-size: 1rem;
    }

    textarea:focus,
    .form-group input:focus {
        outline: none; /* Noņem apmales fokusa režīmā */
        box-shadow: none; /* Noņem nokrāsu ap laukiem */
        background-color: #ffc8a9; /* Fona krāsa, kad lauks ir fokusēts */
    }

    /* Responsīvs dizains maziem ekrāniem (līdz 500px) */
    @media (max-width:500px) {
        .modal-header h2 {
            font-size: 1.2rem;
        }

        button {
            font-size: 0.9rem;
            padding: 4px 25px;
        }

        select,
        .error,
        textarea,
        input,
        p,
        option,
        label{
            font-size: 0.9rem;
        }
    }
</style>
