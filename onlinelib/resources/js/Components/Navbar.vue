<script>
import { usePage, router } from '@inertiajs/vue3'
import {computed, ref} from 'vue'
import {route} from "ziggy-js";

export default {
    setup() {
        // Aprēķina lietotāja datus no lapas propieniem
        const user = computed(() => usePage().props.auth.user)

        // Pārbauda, vai lietotājs ir pieslēdzies (pārvērš vērtību par boolean)
        const isLoggedIn = computed(() => !!user.value)

        const searchQuery = ref('')


        const handleWritingClick = (e) => {
            if (!isLoggedIn.value) {
                router.get('/login');
            } else {
                router.get('/StoryList');
            }
        };

        // Atgriež reakrīvās vērtības un metodes
        return {
            user,
            isLoggedIn,
            searchQuery,
            handleWritingClick,
        };
    },


    mounted() {
        // Izsauc funkcijas, lai iestatītu hamburger izvēlni un meklēšanas funkcionalitāti
        this.setupHamburgerMenu();
        this.setupSearch();
    },
    methods: {
        // Funkcija, kas iestata hamburger izvēlni
        setupHamburgerMenu() {
            // Atrod pogu, kas atver/slēpj izvēlni
            const toggleButton = this.$el.querySelector('.toggle-button');
            // Atrod navigācijas sašu konteineru
            const navbarLinks = this.$el.querySelector('.navbar-links');

            // Pārbauda, vai elementi eksistē
            if (toggleButton && navbarLinks) {
                // Pievieno klikšķa notikumu pogai
                toggleButton.addEventListener('click', () => {
                    // Pievieno vai noņem klasi "active", lai parādītu vai paslēptu izvēlni
                    navbarLinks.classList.toggle('active');
                });
            }

        },

        performSearch() {
            if (this.searchQuery.trim()) {
                router.get(route('search.books'), {
                    query: this.searchQuery
                }, {
                    preserveState: true,
                    replace: true
                })
            }
        },

        handleKeyPress(e) {
            if (e.key === 'Enter') this.performSearch()
        },

        // Funkcija, kas iestata meklēšanas funkcionalitāti
        setupSearch() {
            const search = this.$el.querySelector('.search')
            const btn = this.$el.querySelector('.btn')
            const input = this.$el.querySelector('.input')

            if (btn) {
                btn.addEventListener('click', () => {
                    if (input.value.trim() === '') {
                        search.classList.toggle('active')
                        if (search.classList.contains('active')) {
                            input.focus()
                        }
                    } else {
                        this.performSearch()
                    }
                })

                input.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        this.performSearch()
                    }
                })
            }
        }
    }
}
</script>
<template>

    <nav class="navbar">

        <a href="/"class="BigLogo"><img src="../../../public/images/Logo.jpg" ></a> <!-- Saite uz sākumlapu un lielo logo -->
        <a href="/" class="SmallLogo"><img src="../../../public/images/Logo2.0.jpg" ></a> <!-- Saite uz sākumlapu un mazo logo -->

        <ul class="marks">
            <li><a class="pressed1nav" href="/Library"><i class="fa">&#xf02d;</i> Bibliotēka</a></li> <!-- Saite uz lapu "Bibliotēka" -->
            <li><a class="pressed1nav" href="/bookmarks/read"><i class="fa">&#xf02e;</i> Grāmatzīmes</a></li> <!-- Saite uz lapu "Grāmatzīmes" -->
            <li>
                <a
                    class="pressed1nav"
                    @click.prevent="handleWritingClick"
                >
                    <i class="fa">&#xf040;</i> Rakstīšana
                </a>
            </li><!-- Saite uz lapu "Rakstīšana" -->
        </ul>

        <div class="account"> <!-- Bloks konta pārvaldīšanai -->
            <div class="search">
                <input
                    type="text"
                    class="input"
                    placeholder="Meklēt grāmatu..."
                    v-model="searchQuery"
                    @keypress="handleKeyPress"
                >
                <button class="btn" @click="performSearch">
                    <i class="fa bar">&#xf002;</i>
                </button>
            </div>


            <template v-if="!isLoggedIn">
                <a class="pressed2" href="/register"><i class="fa">&#xf2bd;</i> Reģistrācija</a>
                <a class="pressed2" href="/login"> <i class="fa">&#xf2be;</i> Pieteikšanās</a>
                <a class="pressed3" href="/login"> <i class="fa">&#xf2be; <br></i></a>
            </template>

            <template v-else>
                <a class="pressed2" href="/profile"><i class="fa profile-icon">&#xf2be;</i> {{ user.nickname }}</a>
                <a class="pressed3" href="/profile"> <i class="fa">&#xf2be; <br></i></a>
            </template>
        </div>

        <a href="#" class="toggle-button"> <!-- Pogas izvēlne -->
           <span class="bar"></span>
           <span class="bar"></span>
           <span class="bar"></span>
        </a>

        <!-- Hamburger menu -->
        <div class="navbar-links">
            <ul>
                <li><a class="pressed1" href="/Library"><i style="font-size:16px" class="fa">&#xf02d;</i> Bibliotēka</a></li> <!-- Saite uz lapu "Bibliotēka" -->
                <li><a class="pressed1" href="/bookmarks/read"><i style="font-size:16px;" class="fa">&#xf02e;</i> Grāmatzīmes</a></li> <!-- Saite uz lapu "Grāmatzīmes" -->
                <li>
                    <a
                        class="pressed1nav"
                        @click.prevent="handleWritingClick"
                    >
                        <i class="fa">&#xf040;</i> Rakstīšana
                    </a>
                </li><!-- Saite uz lapu "Rakstīšana" -->
            </ul>
        </div>
    </nav>

   </template>

   <style>


   /* Navigācijas josla */
   .navbar {
       display: flex;
       align-items: center; /* Vertikāla izlīdzināšana */
       z-index: 1000; /* Nodrošina, ka navigācija ir virs citiem elementiem */
       height: 55px !important;
       margin: 0;  /* Noņem arpusejo atstarpi */
       padding: 0;  /* Noņem iekšējo atstarpi */
       box-shadow: rgba(63, 31, 4, 0.8) 0px 0px 15px; /* Ēna navigācijas joslas apakšā */
       background-color: #c58667; /* Fona krāsa */
       flex-grow: 1; /* Izplešas, lai aizpildītu vietu */
   }
   .navbar-links {
       display: none; /* Paslēpjam navigācijas saites */
       box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2); /* Ēna */
       border-top: 1px solid rgba(26, 16, 8, 0.8); /*augšējā apmales vērtības */
       border-bottom: 1px solid rgba(26, 16, 8, 0.8); /*apakšējā apmales vērtības */

   }

   .navbar-links ul {
       margin: 0; /* Iekšpusē starp elementiem nav atstarpes */
       padding: 0; /* Arpusē starp elementiem nav atstarpes */
       display: flex;
   }

   li {
       display: inline; /* Lai saites būtu vienā rindā */
    }

   .navbar-links li {
       list-style: none; /* Noņemam saraksta punktus */
   }

   .navbar-links a {
       text-decoration: none; /* Noņemam noklusēto teksta apakšsvītrojumu */
       color: rgba(26, 16, 8, 0.8); /*Teksta krāsa */
       padding: 0.5rem; /* Iekšējās atstarpes */
       display: block; /* Bloka elementi */
   }

   .navbar-links a:hover {
       color: rgba(255, 187, 142, 0.8); /*Teksts, kad pele tiek pārvilkta */
   }
   .navbar-links a:hover .fa{
       color: rgba(255, 187, 142, 0.8); /*Teksts, kad pele tiek pārvilkta */
   }

   /*Saites navigacijas joslā*/
   .marks{
       margin-left:-45px; /*Atsarpes no kreisa puse*/
       padding: 9px; /*Atstarpes starp elementiem iekšā*/
   }
   .pressed1nav{
       color: rgba(26, 16, 8, 0.8); /*Teksta krāsa */
       text-align: center; /* Centrējam tekstu */
       text-decoration: none; /* Noņemam noklusēto teksta apakšsvītrojumu */
       font-size: 17px; /* Fonta izmērs navigācijas tekstam */
       cursor: pointer; /* Mainam peles formu uz rādītāju */
       font-family: Tahoma, Helvetica, sans-serif; /* Fonta tips */
       margin: 7px; /* Atstarpe ap elementiem */
       transition: color 0.3s;
   }

   .pressed1nav:hover{
       color: rgba(255, 187, 142, 0.8); /*Teksts, kad pele tiek pārvilkta */
   }

   .pressed1nav:hover .fa{
       color: rgba(255, 187, 142, 0.8); /*Teksts, kad pele tiek pārvilkta */
   }

   .account{
       display: flex; /* Flexbox for layout */
       margin-left: auto; /* Novietojam konta bloku pa labi */
       text-align: center; /* Centrējam tekstu */
   }
   .account .fa{
       transition: color 0.3s;
   }

   .pressed2{
       color: rgba(26, 16, 8, 0.8); /*Teksta krāsa */
       margin: 9px;
       text-decoration: none; /* Noņemam noklusēto teksta apakšsvītrojumu */
       font-size: 17px; /* Fonta izmērs navigācijas tekstam */
       font-family: Tahoma, Helvetica, sans-serif; /* Fonta tips */
       transition: color 0.3s;
   }

   .pressed3{
       display:none;
       text-decoration: none; /* Noņemam noklusēto teksta apakšsvītrojumu */
       margin-top: 13px;
       margin-right:15px ;
       transition: color 0.3s;
   }

   .pressed3:hover .fa{
       color: rgba(255, 187, 142, 0.8); /*Teksts, kad pele tiek pārvilkta */
   }

   .pressed2:hover .fa{
       color: rgba(255, 187, 142, 0.8); /*Teksts, kad pele tiek pārvilkta */
   }

   .pressed2:hover {
       color: rgba(255, 187, 142, 0.8); /*Teksts, kad pele tiek pārvilkta */
   }

   /* Pogas izvēlne un Hamburger menu*/
   .toggle-button {
       position: absolute; /* Absolūta pozīcija */
       margin-left: 60px; /* Atstarpe no kreisās puses */
       top: 1.2rem; /* Atstarpe no augšas */
       display: none; /* Paslēpjam pogu */
       flex-direction: column; /* Kolonnas izkārtojums */
       justify-content: space-between; /* Horizontāla izlīdzināšana */
       width: 30px; /* Platums */
       height: 19px; /* Augstums */
       transition: color 0.3s;
   }
   .toggle-button:hover .bar {
       background-color: rgba(255, 187, 142, 0.8); /* Mainam krāsu, kad pele tiek pārvilkta */
   }

   .toggle-button .bar {
       height: 3px;
       width: 80%;
       background-color: rgba(26, 16, 8, 0.8);
       border-radius: 10px; /* Noapaļo apmalas */
   }

   .pressed1{
       color: rgba(26, 16, 8, 0.8); /*Teksta krāsa */
       text-align: center; /* Centrējam tekstu */
       text-decoration: none; /* Noņemam noklusēto teksta apakšsvītrojumu */
       font-size: 17px; /* Fonta izmērs navigācijas tekstam */
       font-family: Tahoma, Helvetica, sans-serif; /* Fonta tips */
       right: 200px;
       display: none;

   }
   .pressed1 .fa{
       transition: color 0.3s;
       font-size: 16px;
   }

   .pressed2 .fa{
       transition: color 0.3s;
       font-size: 16px;
   }
   .pressed1nav .fa{
       transition: color 0.3s;
       font-size: 16px;
   }
   .pressed3 .fa{
       transition: color 0.3s;
       font-size: 27px;
   }

    /* Meklēšanas josla */
   .search {
        display: flex;  /* Flexbox izkārtojums konta sadaļai */
        align-items: center;  /* Elementu vertikāla izlīdzināšana */
   }

   .search:hover {
        transform: none; /*noņemam transformāciju, kad pele tiek pārvilkta */
   }

   .search .input {
        background-color: #ffffff; /*Krasa fona */
        border: 0; /* Noņemam apmales */
        border-radius: 20px; /* Noapaļo apmalas*/
        border-color: rgba(26, 16, 8, 0.8); /* Mainam apmales krāsu */
        font-size: 15px; /* Fonta izmērs */
        padding: 10px; /* Iekšējās atstarpes */
        height: 10px;
        width: 0; /* Sakam ar nulles platumu */
        overflow: hidden; /* Paslēpjam pārplūdes elementus */
        opacity: 0; /* Paslēpjam elementu */
   }

   /* Poga meklēšanai */
   .search .btn {
        background-color: #c58667;
        border: 2px solid rgba(26, 16, 8, 0.8); /*apmales vērtības */
        border-radius: 20px;
        padding: 1px; /* Iekšējās atstarpes */
        cursor: pointer; /* Peles formāts */
        outline: none; /* Noņemam noklusēto apmales stāvokli */
        margin-right: 7px; /* Atstarpe no labās puses */
        margin-top: 1px;
        width: 30px;
        height: 30px;
        transition: border-color 0.3s;
    }
   .btn .fa{
       font-size: 15px;
       transition: color 0.3s !important;
   }

   /* Meklēšanas joslas aktīvā stāvoklis */
   .search.active .input {
        width: 180px;
        height: 23px;
        opacity: 1; /* Parādam elementu */
        margin: 10px;
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

   /*Logotipi*/
   .SmallLogo{
       display:none;
       position: absolute;
       margin-top: 2px;
       margin-left:7px;
   }

   .BigLogo{
        margin-top: 2px;
        margin-right: 35px;
   }


   /* Responsive Design */
    @media screen and (max-width: 1164px) {
        .search {
            margin-top: -67px; /* Atstarpe no augšas */
        }

        .pressed2 {
            margin-top: -46px; /* Atstarpe no augšas */
        }

        .marks {
            display: none; /* Paslēpj navigācijas saites */
        }

        .toggle-button {
            display: flex; /* Parādam pogu */
            margin-left: 160px; /* Atstarpe no kreisās puses */
        }

        .BigLogo {
            margin-top: -3px; /* Atstarpe no augšas */
        }

        .navbar-links {
            width: 100%; /* Pilns platums */
            background-color: #c58667; /* Fona krāsa */
            margin: 0; /* Bez ārējām atstarpēm */
            padding: 0; /* Bez iekšējām atstarpēm */
            margin-top: -15px; /* Atstarpe no augšas */
        }

        .navbar {
            flex-direction: column; /* Kolonnas izkārtojums */
            align-items: flex-start; /* Horizontāla izlīdzināšana pa kreisi */
            transition: height 0.3s ease; /* Animācija augstuma maiņai */
        }

        .navbar {
            position: relative; /* Relatīvā pozīcija */
            height: auto; /* Augstums automātisks */
            z-index: 1000 !important; /* Z-indekss, lai būtu virs citiem elementiem */
        }

        .navbar-links ul {
            flex-direction: column; /* Kolonnas izkārtojums */
            width: 100%; /* Pilns platums */
            margin-top: 1rem; /* Atstarpe no augšas */
            list-style: none; /* Noņem saraksta punktus */
            padding: 0; /* Bez iekšējām atstarpēm */
            margin: 0; /* Bez ārējām atstarpēm */
            width: 100%; /* Pilns platums */
        }

        .navbar-links li {
            text-align: center; /* Teksta centrēšana */
        }

        .navbar-links.active {
            display: flex; /* Flex izkārtojums */
            background-color: #c58667; /* Fona krāsa */
            flex-direction: column; /* Kolonnas izkārtojums */
            margin-bottom: 100px; /* Atstarpe no apakšas */
        }

        @media (max-width: 800px) {
            .BigLogo {
                display: none; /* Paslēpj lielo logo */
            }

            .SmallLogo {
                display: flex; /* Parāda mazo logo */
                margin-bottom: 697px; /* Atstarpe no apakšas */
            }

            .toggle-button {
                margin-left: 60px; /* Atstarpe no kreisās puses */
            }

            .pressed3 {
                display: flex; /* Flex izkārtojums */
                margin-left: 5px; /* Atstarpe no kreisās puses */
                margin-top: 15px; /* Atstarpe no augšas */
            }

            .search {
                margin-top: 0; /* Bez atstarpes no augšas */
            }

            .search .btn {
                width: 28px; /* Pogas platums */
                height: 28px; /* Pogas augstums */
                margin-top: 13px; /* Atstarpe no augšas */
            }

            .search.active .input {
                width: 160px; /* Ievades lauka platums */
                height: 20px; /* Ievades lauka augstums */
                margin-bottom: 0; /* Bez atstarpes no apakšas */
                margin-top: 13px; /* Atstarpe no augšas */
            }

            .pressed2 {
                display: none; /* Paslēpj elementu */
            }

            .navbar-links {
                margin-top: 12px; /* Atstarpe no augšas */
            }

            .pressed1 {
                font-size: 16px; /* Teksta izmērs */
            }
        }

        @media (max-width: 350px) {
            .SmallLogo {
                display: flex; /* Parāda mazo logo */
            }

            .search .input {
                padding: 8px; /* Iekšējās atstarpes */
                width: 120px !important; /* Ievades lauka platums */
            }

            .search .btn {
                margin-top: 15px; /* Atstarpe no augšas */
                padding: -5px; /* Iekšējās atstarpes */
            }

            .search.active .input {
                width: 160px; /* Ievades lauka platums */
                height: 20px; /* Ievades lauka augstums */
                margin-bottom: 0; /* Bez atstarpes no apakšas */
                margin-top: 16px; /* Atstarpe no augšas */
            }

            .pressed3 {
                margin-top: 15px; /* Atstarpe no augšas */
            }

            .toggle-button .bar {
                height: 3px; /* Svītras augstums */
                width: 70%; /* Svītras platums */
                margin: 2px; /* Atstarpe apkārt */
                background-color: rgba(26, 16, 8, 0.8); /* Svītras krāsa */
                border-radius: 10px; /* Noapaļotie stūri */
            }

            .toggle-button {
                margin-left: 50px; /* Atstarpe no kreisās puses */
            }

            .navbar-links {
                margin-top: 10px; /* Atstarpe no augšas */
            }

            .pressed1 {
                font-size: 15px; /* Teksta izmērs */
            }
        }
    }
   </style>
