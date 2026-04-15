<script setup>
    import { ref, watch } from 'vue';

    // Komponenta ievaddati
    const props = defineProps({
        placeholder: {
            type: String,
            default: 'Meklēt...'
        },
        modelValue: {
            type: String,
            default: ''
        }
    });

    // Emit notikumi - lai paziņotu par meklēšanas notikumu
    const emit = defineEmits(['update:modelValue', 'search']);

    // Lokālais meklēšanas ievades lauka stāvoklis
    const searchQuery = ref(props.modelValue);

    // Seko līdzi izmaiņām no vecāka
    // Kad vecāks maina vērtību, atjaunina lokālo stāvokli
    watch(() => props.modelValue, (newValue) => {
        searchQuery.value = newValue;
    });

    // Seko līdzi izmaiņām ievades laukā
    // Kad lietotājs ievada tekstu, paziņo vecākkomponentam par izmaiņām
    watch(searchQuery, (newValue) => {
        emit('update:modelValue', newValue);
    });

    // Meklēšanas darbība, kad tiek nospiesta poga
    const handleSearch = () => {
        emit('search', searchQuery.value);
    };

    // Tastatūras notikumu apstrāde
    const handleKeyPress = (e) => {
        if (e.key === 'Enter') performSearch() // Ja nospiests Enter, izpilda meklēšanu
    }
</script>

<template>
    <div class="search">
        <!-- Meklēšanas ievades lauks -->
        <input
            v-model="searchQuery"
            type="text"
            class="input"
            @keyup.enter="handleSearch"
            :placeholder="placeholder"
        >

        <!-- Meklēšanas poga -->
        <button class="btn" @click="handleSearch">
            <i class="fa bar">&#xf002;</i>
        </button>
    </div>
</template>
<style scoped>

    /* Meklēšanas josla */
    .search {
        display: flex;  /* Flexbox izkārtojums konta sadaļai */
        justify-content: center;
        align-items: center;  /* Elementu vertikāla izlīdzināšana */
        margin: 60px auto;
        max-width: 800px;
        margin-bottom: 30px;
    }

    .search:hover {
        transform: none; /*noņemam transformāciju, kad pele tiek pārvilkta */
    }

    .search .input {
        background-color: #ffffff; /*Krasa fona */
        border: 0; /* Noņemam apmales */
        border-radius: 20px; /* Noapaļo apmalas*/
        border-color: rgba(26, 16, 8, 0.8); /* Mainam apmales krāsu */
        font-size: 1rem; /* Fonta izmērs */
        padding: 10px; /* Iekšējās atstarpes */
        height: 15%;
        width: 90%; /* Sakam ar nulles platumu */
    }

    /* Poga meklēšanai */
    .search .btn {
        background-color: #c58667;
        border: 2px solid rgba(26, 16, 8, 0.8); /*apmales vērtības */
        border-radius: 20px;
        cursor: pointer; /* Peles formāts */
        outline: none; /* Noņemam noklusēto apmales stāvokli */
        margin-left: 7px; /* Atstarpe no labās puses */
        width: 41px;
        height: 40px;
        transition: border-color 0.3s;
    }

    .btn .fa{
        font-size: 20px;
        text-align: center;
        transition: color 0.3s !important;
    }

    .input{
        color: rgba(26, 16, 8, 0.8);
        font-family: Tahoma, Helvetica, sans-serif; /* Fonta tips */
    }
    .search input::placeholder {
        color: rgba(26, 16, 8, 0.42); /* Krāsa */
    }

    .input:focus {
        outline: none !important; /* Noņemam noklusēto apmales stāvokli */
        box-shadow: none !important;
        background-color: #ffd9c6; /* Fona krāsa */
    }
    .btn:hover {
        border-color: rgba(255, 187, 142, 0.8); /* Mainam apmales krāsu, kad pele tiek pārvilkta */
    }
    .btn:hover .fa {
        color: rgba(255, 187, 142, 0.8); /* Mainam ikonas krāsu, kad pele tiek pārvilkta */
    }
    .fa{
        color: rgba(26, 16, 8, 0.8);  /* Fonta krāsa */
    }

    @media (max-width: 500px) {
        .search .input {
            font-size: 0.9rem; /* Fonta izmērs */
            height: 30px;
            width: 75%;
        }

        /* Poga meklēšanai */
        .search .btn {
            padding: 0;
            width: 34px;
            height: 34px;
        }

        .btn .fa{
            font-size: 18px;
        }
        input::placeholder,
        button
        {
            font-size: 0.9rem;
        }
    }
</style>
