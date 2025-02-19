<template>

    <!-- Galvenais virsraksts un ievada teksts -->
    <h1 class="main_h">Hello. This is Onlib!</h1> <!-- Galvenais virsraksts -->
    <p class="main_paragraph"><br>&nbsp A community where you can study not only famous authors and their books, but also fan works in different directions. </p> <!-- Ievada paragrāfs -->
    <p class="main_paragraph">&nbsp Sharing your own stories and fantasies is an integral part of Onlib. </p> <!-- Ievada paragrāfs -->
</template>

<style scoped>
    /* Galvenais virsraksts un paragrāfi */
    .main_h{
        text-align: center; /* Centrējam galveno virsrakstu */
        font-size: 35px; /* Fonta izmērs galvenajam virsrakstam */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
    }

    .main_paragraph{
        text-align: center; /* Centrējam tekstu */
        font-size: 24px; /* Iestatām fonta izmēru paragrāfam */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        animation: gradientText 4s ease-in-out infinite; /* Gradienta teksta animācija */
    }

    @keyframes gradientText {
    0% {
        color: rgba(26, 16, 8, 0.8); /* Sākotnējā krāsa */
    }
    25% {
        color: #704733; /* Pirmā pāreja krāsā */
    }
    50% {
        color: #c58667; /* Otrā pāreja krāsā */
    }
    75% {
        color: #704733; /* Trešā pāreja krāsā */
    }
    100% {
        color: rgba(88, 35, 0, 0.8); /* Beigu krāsa */
    }
    }
</style>