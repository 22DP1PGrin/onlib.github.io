<template >
  <Navbar/>

  <div class="main-content">
      <main class="content">
        <h2 class="header">Do what you've been wanting for a long time!</h2> <!-- Aicinājums rīkoties -->
      </main>
    </div>

  <Footer/>


</template>

<script>

import Navbar from '../Components/Navbar.vue';
import Footer from '../Components/Footer.vue';

export default{
  components: {
    Navbar,
    Footer,
  }
}
</script>

<style scoped>



.main-content {
    flex: 1; /* Kontents izstiepjas, lai kājene būtu apakšā */
    padding: 20px;
    margin-top: 55px; /* Adjust this depending on the navbar's height */
    display: flex;
    flex-direction: column;
    min-height: 100vh; /* Растягиваем на всю высоту экрана */
}


.content {
    flex: 1; /* Контент займет всю доступную высоту */
}
</style>
