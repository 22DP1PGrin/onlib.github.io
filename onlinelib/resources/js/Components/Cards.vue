<template>
    <!-- Grid izkārtojums ar trim blokiem -->
    <div class="grid-container">
        <div class="grid-item">
            <h4 class="headingGrid">Library</h4> <!-- Bloka virsraksts -->
            <img src="../../public/Images/Book.jpg" alt="Reading books" width="300" height="200"> <!-- Attēls ar grāmatu -->
            <p class = "paragraphGrid">Read your favorite books of any genre by any author!</p> <!-- Bloka apraksts -->
            <button class="button">Read!</button> <!-- Poga lasīšanai -->
        </div>
        <div class="grid-item">
            <h4 class="headingGrid">Published book </h4> <!-- Otrā bloka virsraksts -->
            <img src="../../public/Images/WriteBook.jpg" alt="Writing books" width="300" height="200"> <!-- Attēls ar grāmatas rakstīšanu -->
            <p class = "paragraphGrid">Write your stories and publish them on our site!</p> <!-- Apraksts -->
            <button class="button">Write!</button> <!-- Poga rakstīšanai -->
        </div>
        <div class="grid-item">
            <h4 class="headingGrid">Profile</h4> <!-- Trešā bloka virsraksts -->
            <img src="../../public/Images/ReadBook.jpg" alt="Saving books" width="300" height="200"> <!-- Attēls ar grāmatu saglabāšanu -->
            <p class = "paragraphGrid">Bookmark your favorite books on your profile!</p> <!-- Apraksts -->
            <button class="button">Profile!</button> <!-- Poga profila apskatei -->
        </div>  
    </div> 
</template>

<style scoped>
.grid-container {
    display: grid; /* Izmantojam grid izkārtojumu elementiem */
    column-gap: 10px; /* Kolonnu atstarpes */
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); /* Automātiski aizpilda kolonnu */
    margin: 0 auto; /* Centrējam gridu horizontāli */
    width: 70%; /* Iestatām platumu uz 70% */
    padding: 10px; /* Iekšējā atstarpe */
    max-width: 1200px; /* Maksimālais platums */
    justify-content: center; /* Centrējam saturu gridā */
}

.grid-item {
    /* background-color: rgba(255, 255, 255, 0.8); Fona krāsa */
    background-image: linear-gradient(#e9a787,rgba(245, 213, 176, 0.8),rgba(255, 255, 255, 0.8)); /* Fona krāsa ar gradientu */
    border: 3px solid rgba(73, 48, 24, 0.8); /* Robežas krāsa un biezums */
    padding: 15px; /* Iekšējā atstarpe */
    font-size: 30px; /* Fonta izmērs */
    text-align: center; /* Teksta centrēšana */
    transition: transform 0.2s; /* Animācija, kad tiek pārvietots elements */
    color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
}

.paragraphGrid{
    font-size: 20px; /* Fonta izmērs */
}

.button {
  background-color:#e6b49b; /* Pogas fona krāsa */
  border: solid rgba(73, 48, 24, 0.8);; /* Pogas apmale */
  color: rgba(26, 16, 8, 0.8); /* Teksta krāsa */
  padding: 10px 28px; /* Iekšējās atstarpes ap pogu */
  text-align: center; /* Teksta centrēšana */
  text-decoration: none; /* Bez teksta dekorācijas */
  font-size: 16px; /* Fonta izmērs */
  transition: all 0.3s ease; /* Pārejas efekts visiem stiliem */
}

.grid-item:hover {
  background-image: none; /* Gradientu noņemšana */
  background-color: rgba(245, 213, 176, 0.8); /* Fona krāsa, kad pele tiek pārvilkta pār elementu */
  transform: scale(1.13); /* Palielinām elementu, kad pele tiek pārvilkta */
}

.button:hover {
    font-size: 20px; /* Mainām fonta izmēru, kad pele tiek pārvilkta */
    color: rgba(20, 13, 7, 0.8); /* Teksta krāsa, kad pele tiek pārvilkta */
    background-color: #c58667; /* Fona krāsa, kad pele tiek pārvilkta */
    border: rgba(73, 48, 24, 0.8); /* Robeža, kad pele tiek pārvilkta */
    animation: pulse 1s infinite; /* Pārnesto efektu animācija, kad peles rādītājs atrodas uz pogas */
}

@keyframes pulse { /* Animācija */
    0% {
        transform: scale(1); /* Sākuma stāvoklis (normāls izmērs) */
        box-shadow: 0 0 5px rgba(155, 100, 51, 0.8); /* Sākotnējā ēna ap objektu */
    }
    50% {
        transform: scale(1.1); /* Mērogošana par 10% vidū animācijā */
        box-shadow: 0 0 15px rgba(155, 100, 51, 0.8); /* Lielāka ēna vidū animācijā */
    }
    100% {
        transform: scale(1); /* Beigu stāvoklis (normāls izmērs) */
        box-shadow: 0 0 5px rgba(155, 100, 51, 0.8); /* Beigu ēna ap objektu */
    }
}
</style>