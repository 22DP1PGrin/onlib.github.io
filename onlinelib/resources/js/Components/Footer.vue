<template>
    <!-- Kājene ar saitēm uz dažādām lapām -->
<footer id="footer">
    <div class="footer-container">
            <ul class="footer-links">
                <li><a href="#Terms of Service">Pakalpojuma noteikumi</a></li> <!-- Saite uz lietotāja līgumu -->
                <li><a href="/contentpolicy">Satura politika</a></li> <!-- Saite uz satura politiku -->
                <li><a href="#Privacy policy">Privātuma politika</a></li> <!-- Saite uz privātuma politiku -->
            </ul>
            <ul class="footer-links">
                <li><a href="#DMCA Policy">DMCA politika</a></li> <!-- Saite uz DMCA politiku -->
                <li><a href="#FAQ">FAQ</a></li> <!-- Saite uz bieži uzdotiem jautājumiem -->
                <li><a href="#PrivacyPolicy">Tehniskais atbalsts</a></li> <!-- Saite uz tehnisko atbalstu -->
            </ul>
    </div>
    <p class="info">© 2025 Onlib. Visas tiesības paturētas.</p>
</footer>

</template>

<style scoped>

    /* Kājenes stils */
    footer {
        background-color: #c58667; /* Fona krāsa */
        padding: 25px; /* Iekšējās atstarpes */
        display: flex; /* Flexbox izkārtojums */
        flex-direction: column; /* Kolonnas izkārtojums */
        height: 200px; /* Augstums */
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px; /* Ēna navigācijas joslas apakšā */
    }

    .footer-container {
        display: flex; /* Flexbox izkārtojums */
        align-items: center; /* Elementu vertikāla izlīdzināšana */
        flex-direction: column; /* Kolonnas izkārtojums */
        justify-content: space-between; /* Horizontāla izlīdzināšana */
        gap: 19px; /* Atstarpe starp saitēm */
        flex-grow: 1; /* Izplešas, lai aizpildītu vietu */
        margin-top: 20px; /* Atstarpe no augšas */
        flex-wrap: wrap; /* Nodrošina, ka saites pārlec uz nākamo rindu mazākos ekrānos */
    }

    .footer-links {
        list-style: none; /* Noņemam saraksta punktus */
        margin: 0; /* Noņem (margin) */
        padding: 0px; /* Noņem (padding) */
        display: flex; /* Izmanto flexbox izkārtojumu */
        gap: 15px; /* Atstarpe starp flex elementiem */
        justify-content: center; /* Centrē elementus horizontāli */
        flex-wrap: wrap; /* Nodrošina, ka saites pārlec uz nākamo rindu mazākos ekrānos */
    }

    .footer-links a {
        color: rgba(20, 13, 7, 0.8); /* Saites teksta krāsa */
        text-decoration: none; /* Noņemam apakšsvītrojumu */
        font-size: 17px; /* Teksta izmērs */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonta tips */

    }

    .footer-links a:hover {
        color: rgba(255, 187, 142, 0.8); /* Teksta krāsa saitēm, kad pele tiek pārvilkta */

    }
    .info{
        color: rgba(20, 13, 7, 0.8); /* Teksta krāsa */
        font-size: 16px; /* Fonta izmērs */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonta tips */
        margin-top: 40px; /* Atstarpe no augšas */
        text-align: center; /* Centrējam tekstu */
        cursor: context-menu; /* Peles formāts */
    }

    /*Responsivitāte*/

    @media screen and (max-width: 800px) {
        footer {
            padding: 20px; /* Iekšējās atstarpes */
        }
        .footer-links {
            gap: 10px; /* Atstarpes starp saitēm */
            font-size: 16px; /* Fonta lielums */
        }
        .info {
            margin-top: 18px; /* Atstarpe no augšas */
            font-size: 15px;
        }
    }
</style>
<script setup lang="ts">
</script>
