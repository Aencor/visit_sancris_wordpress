<?php get_header(); ?>

<!-- Landing Section -->
<div id="landing-section" class="min-h-screen flex flex-col items-center justify-center p-4 relative overflow-hidden bg-brand-blue/5 dark:bg-slate-900">
    
    <!-- Logo -->
    <div class="mb-8 w-48 md:w-64 relative z-20">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.jpg" alt="Visita San Cris Logo" class="w-full h-auto drop-shadow-xl rounded-full">
    </div>

    <!-- Search Container -->
    <div class="w-full max-w-2xl text-center z-50 px-4 relative">
        <h1 class="text-3xl md:text-5xl font-bold mb-8 text-brand-blue dark:text-brand-gold tracking-tight">Descubre San Cristóbal</h1>
        <h4 class="text-xl md:text-2xl font-bold mb-8 text-brand-blue dark:text-brand-gold tracking-tight opacity-80">Discover San Cristóbal</h4>
        
        <div class="relative group">
            <input type="text" id="main-search" placeholder="Restaurantes, Parques, Museos..." 
                class="w-full p-4 md:p-6 rounded-full text-lg shadow-2xl border-2 border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-white focus:border-brand-gold focus:outline-none transition-all duration-300 pr-16 bg-white text-slate-800 placeholder-slate-400 relative z-50">
            
            <button id="search-btn" class="absolute right-2 top-2 bottom-2 bg-brand-blue text-white p-3 md:p-4 rounded-full hover:bg-blue-800 transition-colors duration-300 flex items-center justify-center aspect-square shadow-md z-50">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </div>
        <p class="mt-4 text-slate-500 text-sm md:text-base font-medium relative z-50">Explora San Cristóbal de las Casas a tu alrededor.</p>
    </div>

    <!-- Decorative Background Elements -->
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none z-0">
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-blue-200 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-3xl opacity-30 dark:opacity-20 animate-blob"></div>
        <div class="absolute top-[-10%] right-[-10%] w-96 h-96 bg-yellow-200 dark:bg-yellow-900 rounded-full mix-blend-multiply filter blur-3xl opacity-30 dark:opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-96 h-96 bg-pink-200 dark:bg-pink-900 rounded-full mix-blend-multiply filter blur-3xl opacity-30 dark:opacity-20 animate-blob animation-delay-4000"></div>
    </div>
</div>

<!-- Map Section (Initially Hidden) -->
<div id="map-section" class="hidden-section w-full min-h-screen relative bg-slate-50 dark:bg-slate-900 flex flex-col">
    <!-- Header for Map View -->
    <header class="bg-white dark:bg-slate-800 shadow-md p-4 sticky top-0 z-50 flex flex-col gap-4">
        
        <!-- Top Row: Logo & Brand -->
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3 cursor-pointer hover:opacity-80 transition-opacity" onclick="location.reload()">
                 <img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.jpg" alt="Logo Small" class="w-12 h-12 rounded-full border-2 border-slate-100 dark:border-slate-700 shadow-sm">
                 <div class="leading-tight">
                     <h2 class="font-bold text-xl text-brand-blue dark:text-brand-gold">Visita San Cris</h2>
                     <p class="text-xs text-slate-500 dark:text-slate-400">Directorio Turístico</p>
                 </div>
            </div>
        </div>

        <!-- Middle Row: Search Bar -->
        <div class="w-full relative">
             <input type="text" id="header-search" placeholder="Busca restaurantes, parques, museos..." 
                class="w-full py-3 px-5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-base focus:border-brand-blue dark:focus:border-brand-gold focus:ring-2 focus:ring-brand-blue/20 dark:text-white focus:outline-none shadow-sm transition-all">
             <div class="absolute right-4 top-1/2 transform -translate-y-1/2 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
             </div>
        </div>

        <!-- Bottom Row: Categories -->
        <div class="w-full overflow-x-auto hide-scrollbar pb-1">
            <div class="flex gap-2 min-w-max">
                <button class="category-filter px-5 py-2 rounded-full bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 hover:border-brand-blue transition-all shadow-sm text-sm font-medium flex items-center gap-2 group" data-cat="all">
                    <span>🏷️</span> Todo
                </button>
                <button class="category-filter px-5 py-2 rounded-full bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 hover:border-brand-blue transition-all shadow-sm text-sm font-medium flex items-center gap-2 group" data-cat="restaurant">
                    <span>🍽️</span> Restaurantes
                </button>
                <button class="category-filter px-5 py-2 rounded-full bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 hover:border-brand-blue transition-all shadow-sm text-sm font-medium flex items-center gap-2 group" data-cat="park">
                    <span>🌳</span> Parques
                </button>
                <button class="category-filter px-5 py-2 rounded-full bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 hover:border-brand-blue transition-all shadow-sm text-sm font-medium flex items-center gap-2 group" data-cat="museum">
                    <span>🏛️</span> Museos
                </button>
                 <button class="category-filter px-5 py-2 rounded-full bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 hover:border-brand-blue transition-all shadow-sm text-sm font-medium flex items-center gap-2 group" data-cat="shop">
                    <span>🛍️</span> Tiendas
                </button>
            </div>
        </div>
    </header>

    <div class="flex-grow relative overflow-hidden flex flex-col md:flex-row h-[calc(100vh-140px)] md:h-[calc(100vh-80px)]">
        
        <!-- Map Container -->
        <div class="absolute inset-0 md:relative md:flex-grow md:w-auto z-0 bg-slate-100 dark:bg-slate-800">
            <div id="map" class="h-full w-full"></div>
            
            <!-- Events List Overlay -->
            <div id="events-container" class="absolute bottom-6 right-6 z-[1000] w-72 md:w-80 pointer-events-auto flex flex-col gap-3">
                <!-- Events will be injected here -->
            </div>

            <!-- Loading Overlay -->
            <div id="map-loader" class="absolute inset-0 bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm z-[1000] flex flex-col items-center justify-center hidden">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-brand-blue dark:border-brand-gold mb-4"></div>
                <p class="text-brand-blue dark:text-brand-gold font-medium animate-pulse">Obteniendo tu ubicación...</p>
            </div>
        </div>

        <!-- Cards List -->
        <div class="absolute bottom-0 left-0 right-0 md:relative md:w-96 md:h-full z-10 pointer-events-none md:pointer-events-auto flex flex-col md:bg-white md:dark:bg-slate-800 md:border-r md:border-slate-200 md:dark:border-slate-700 md:shadow-xl">
            
            <div class="hidden md:flex justify-between items-center p-4 bg-white dark:bg-slate-800 border-b border-slate-100 dark:border-slate-700">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Resultados (<span id="result-count-desktop">0</span>)</h3>
                <span class="text-xs text-slate-400">Radio de 2km</span>
            </div>

            <div class="w-full md:flex-grow md:overflow-y-auto p-4 pointer-events-auto">
                <div id="cards-container" class="flex md:flex-col gap-4 overflow-x-auto md:overflow-visible snap-x snap-mandatory hide-scrollbar pb-4 md:pb-0">
                    <div class="hidden md:block p-8 text-center text-slate-400">
                        <div class="animate-pulse mb-2">🔍</div>
                         Realiza una búsqueda...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
