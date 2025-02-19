<template>
    <Navbar />
  
    <div class="main-content">

  
      <h2>1. Ievads</h2>
      <p>
        Šī satura politika nosaka vadlīnijas un nosacījumus attiecībā uz materiāliem, kas tiek ievietoti mūsu tiešsaistes bibliotēkā. Mūsu bibliotēka ir vieta, kur lietotāji var gan lasīt, gan rakstīt dažādus žanru darbus, kā arī piekļūt klasiskajai literatūrai. Mēs atbalstām radošumu, izglītību un intelektuālo attīstību, vienlaikus ievērojot ētikas normas un likumus.
      </p>
  
      <h2>2. Satura pieņemšanas kritēriji</h2>
      <p>Mēs piedāvājam daudzveidīgu saturu, kas ietver gan literāros darbus, gan radošus rakstus. Visām publikācijām jāatbilst šādiem kritērijiem:</p>
      <ul>
        <li><strong>Radošums un oriģinalitāte</strong> – Mēs aicinām lietotājus dalīties ar oriģināliem darbiem, kas ietver dažādus žanrus, tostarp dzeju, prozu, esejas, stāstus un citus radošus rakstus.</li>
        <li><strong>Klasiskā literatūra</strong> – Mēs piedāvājam piekļuvi klasiskajiem literatūras darbiem, kas ir sabiedrības īpašums vai ir pieejami ar licencēm, kas ļauj tos brīvi izmantot.</li>
        <li><strong>Izglītojošs saturs</strong> – Papildus radošajiem darbiem mūsu bibliotēka piedāvā arī resursus, kas palīdz uzlabot rakstīšanas prasmes un izpratni par literatūru.</li>
      </ul>
  
      <h2>3. Aizliegti materiāli</h2>
      <p>Mēs nepieņemsim materiālus, kas ietver:</p>
      <ul>
        <li><strong>Nelikumīgu saturu</strong> – Saturs, kas pārkāpj autortiesības, tiek aizliegts. Mēs nepieņemam nelegāli iegūtus darbus.</li>
        <li><strong>Kaitinošu vai aizskarošu saturu</strong> – Saturs, kas veicina naida runu, diskrimināciju, vardarbību, vai kas ir aizskarošs, pazemojošs vai apmelojošs.</li>
        <li><strong>Maldi un dezinformāciju</strong> – Mēs nepieņemam materiālus, kas izplata nepatiesas vai maldinošas ziņas vai idejas.</li>
      </ul>
  
      <h2>4. Lietotāju atbildība</h2>
      <p>Lietotājiem ir jāievēro šādi noteikumi, izmantojot mūsu bibliotēku:</p>
      <ul>
        <li><strong>Autortiesību ievērošana</strong> – Lietotājiem jārespektē autortiesības, ievērojot noteikumus par materiālu kopēšanu, izplatīšanu un citēšanu.</li>
        <li><strong>Atbildīga rakstīšana un lasīšana</strong> – Lietotājiem jābūt atbildīgiem, publicējot savu darbu, lai tas atbilstu ētikas standartiem un cienītu citu cilvēku tiesības un viedokļus.</li>
        <li><strong>Citu lietotāju cieņa</strong> – Rakstot, lietotājiem ir jāievēro cieņa pret citu darbu un viedokļiem, lai veidotu atbalstošu un pozitīvu kopienu.</li>
      </ul>
  
      <h2>5. Satura atjauninājumi un dzēšana</h2>
      <p>
        Mēs regulāri atjauninām mūsu bibliotēkas saturu, lai nodrošinātu, ka tas ir aktuāls un atbilst mūsu kvalitātes standartiem.<br />
        Ja tiek konstatēts, ka kāds materiāls pārkāpj šo politiku, mēs paturam tiesības dzēst vai ierobežot piekļuvi šim materiālam.
      </p>
  
      <h2>6. Lietošanas nosacījumi un piekļuve</h2>
      <p>
        Mūsu bibliotēka ir pieejama visiem, kas piekrīt mūsu lietošanas nosacījumiem un privātuma politikai. Piekļuve materiāliem var tikt ierobežota vai atņemta, ja tiek pārkāpti noteikumi, un lietotājiem ir jāievēro mūsu ētikas standarti.
      </p>
  
      <h2>7. Autortiesības un intelektuālā īpašuma tiesības</h2>
      <p>
        Visi darbi, kas tiek publicēti mūsu bibliotēkā, ir aizsargāti ar autortiesībām. Lietotājiem ir jāievēro autortiesību likumi un jābūt piesardzīgiem, kopējot vai izplatot saturu ārpus platformas.<br />
        Mēs mudinām lietotājus publicēt tikai savus oriģinālos darbus vai darbus, kas ir atļauti saskaņā ar licencēm vai autoru piekrišanu.
      </p>
  
      <h2>8. Sūdzības un ierosinājumi</h2>
      <p> Ja uzskatāt, ka kāds materiāls mūsu bibliotēkā pārkāpj šo satura politiku, vai ja vēlaties ieteikt jauna materiāla pievienošanu, lūdzu, sazinieties ar mums, izmantojot <a href="#tEH.ATBALSTS">tehnisko atbalstu</a>.</p>
  
      <h2>9. Politikas izmaiņas</h2>
      <p class="end">
        Mēs paturam tiesības mainīt šo satura politiku jebkurā laikā. Visi izmaiņojumi tiks publicēti šajā lapā, un tie stāsies spēkā nekavējoties pēc publicēšanas.
      </p>
    </div>
  
    <Footer />
  </template>
  
  <script>
  import Navbar from "@/components/Navbar.vue";
  import Footer from "@/components/Footer.vue";
  
  export default {
    components: {
      Navbar,
      Footer
    }
  };
  </script>
  
  <style scoped>
  .PageName {
    text-align: center;
    margin-top: 0px;
  }
  
  h2{ 
    font-size: 1.1rem;
    }
  .main-content p {
    font-size: 1.0rem;
    margin: 10px 20px;

  }
  li{
    display: block;
    padding: 10px;
  }
  .main-content ul {
    font-size: 1.0rem;
    margin: 10px -10px;
  }
  
  .main-content {
    flex: 1;
    padding: 20px;
    margin-top: 55px;
    color: rgba(26, 16, 8, 0.8);
    font-family: Tahoma, Helvetica, sans-serif;
    margin-left: 20%;
    margin-right: 20%;
    margin-bottom: 55px;
  }
  a{
    text-decoration: none; /* Noņemam noklusēto teksta apakšsvītrojumu */
    color: rgba(107, 50, 0, 0.8); /*Teksta krāsa */
  }

  
  @media (max-width: 768px) {
    .main-content {
      margin-left: 10px;
      margin-right: 10px;
    }
  
    .PageName {
      font-size: 1.5rem;
    }
  
    .main-content p,
    .main-content ul {
      font-size: 1rem;
    }
  }
  </style>