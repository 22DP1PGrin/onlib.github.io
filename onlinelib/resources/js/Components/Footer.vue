<template>
    <!-- Kājene ar saitēm uz dažādām lapām -->
<footer id="footer">
    <div class="footer-container">
            <ul class="footer-links">
                <li><a href="#Terms of Service">Pakalpojuma noteikumi</a></li> <!-- Saite uz lietotāja līgumu -->
                <li><a href="/contentpolicy">Satura politika</a></li> <!-- Saite uz satura politiku -->
                <li><a href="#Content Policy">Privātuma politika</a></li> <!-- Saite uz privātuma politiku -->
                <li><a href="#DMCA Policy">DMCA politika</a></li> <!-- Saite uz DMCA politiku -->
            </ul>
            <ul class="footer-links">
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
        height: 170px; /* Augstums */
        box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px; /* Ēna navigācijas joslas apakšā */
    }


    .footer-container {
        display: flex; /* Flexbox izkārtojums */
        align-items: center; /* Elementu vertikāla izlīdzināšana */
        flex-direction: column; /* Kolonnas izkārtojums */
        justify-content: space-between; /* Horizontāla izlīdzināšana */
        ; /* Horizontāla izlīdzināšana */
        gap: 10px; /* Atstarpe starp saitēm */
        flex-grow: 1; /* Izplešas, lai aizpildītu vietu */
        margin-top: 15px; /* Atstarpe no augšas */
        flex-wrap: wrap; /* Wrap elements to the next line if needed */
    }

    .footer-links {
        list-style: none; /* Noņemam saraksta punktus */
        margin: 0;
        padding: 0px;
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap; /* Wrap elements to the next line if needed */
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
        font-size: 17px; /* Fonta izmērs */
        font-family: Tahoma, Helvetica, sans-serif; /* Fonta tips */
        margin-top: 35px; /* Atstarpe no augšas */
        text-align: center; /* Centrējam tekstu */
        cursor: context-menu; /* Peles formāts */
    }

    @media screen and (max-width: 1100px) {
        footer {
            padding: 20px; /* Adjust padding */
            height: 150px;
        }
        .footer-links a {
            font-size: 16px;
        }
        .info {
            font-size: 15px;
            margin-top: 20px;
        }
        .footer-container {
            gap: 5px; /* Adjust gap */
        }
    }

    @media screen and (max-width: 800px) {
        footer {
            padding:20px;
        }

        .info {
            font-size: 15px;
            margin-top: 15px;
        }

    }
    @media screen and (max-height: 573px){
        footer {
            padding: 20px;
            height: 170px;
        }
        .info {
            margin-top: 100px;
        }
    }
</style>
