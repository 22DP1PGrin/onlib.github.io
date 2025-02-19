<template>
    <div class="info-container">
        <h3 class="info">We have many genres that you can not only read but also work with!</h3> <!-- Informācija par žanriem -->
        <div class="list-row">
            <ul class="list-container">
                <li>Fantasy</li>
                <li>Mystery</li>
                <li>Horror</li>
                <li>Romance</li>
                <li>True Crime</li>
                <li>Humor</li>
            </ul>
            <ul class="list-container">
                <li>Science Fiction</li>
                <li>Action & Adventure</li>
                <li>Thriller</li>
                <li>Dystopian</li>
                <li>Detective</li>
                <li>Historical Fiction</li>
            </ul>
        </div>
    </div>  
</template>

<style scoped>
    /* Papildu informācija */
    .container {
        display: flex; /* Izmantojam flexbox konteineram */
        flex-direction: column; /* Novietojam elementus kolonnā */
        margin: 20px; /* Atstarpe visam konteineram */
    }

    .info-container {
        text-align: center; /* Centrējam tekstu */
        color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
        font-size: 20px; /* Fonta izmērs */
    }

    .list-row {
        display: flex; /* Izmantojam flexbox, lai izvietotu sarakstus vienā rindā */
        justify-content: center; /* Centrējam sarakstus */
        margin-top: 10px; /* Atstarpe virs saraksta */
    }

    .list-container {
        list-style-type: none; /* Uzdodam, lai nav saraksta marķējuma */
        padding: 0; /* Uzdodam, lai nebūtu iekšējā atstarpes */
        margin: 0 10px; /* Atstarpe starp sarakstiem */
    }

    .list-container li {
        margin: 5px 0; /* Atstarpe starp saraksta elementiem */
        font-size: 18px; /* Fonta izmērs saraksta elementiem */
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