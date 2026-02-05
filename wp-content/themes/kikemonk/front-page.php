<?php get_header(); ?>

<!-- Landing Section -->
<div id="landing-section" class="min-h-screen flex flex-col items-center justify-center p-4 relative overflow-hidden bg-brand-blue/5 dark:bg-slate-900">
    
    <!-- Logo -->
    <div class="mb-8 w-48 md:w-64 relative z-20">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.jpg" alt="Visita San Cris Logo" class="w-full h-auto drop-shadow-xl rounded-full">
    </div>

    <!-- Search Container -->
    <div class="w-full max-w-2xl text-center z-50 px-4 relative flex flex-col items-center">
        <h1 class="text-3xl md:text-5xl font-bold mb-8 text-brand-blue dark:text-brand-gold tracking-tight">Descubre San Cristóbal</h1>
        <h4 class="text-xl md:text-2xl font-bold mb-8 text-brand-blue dark:text-brand-gold tracking-tight opacity-80">Discover San Cristóbal</h4>
        
        <button id="search-btn" class="group relative bg-brand-blue hover:bg-blue-800 text-white rounded-full px-16 py-6 shadow-2xl transition-all duration-300 hover:scale-105 active:scale-95 flex flex-col items-center justify-center border-4 border-white/20">
            <span class="text-2xl font-black tracking-wider drop-shadow-md">VAMOS</span>
            <span class="text-lg font-bold opacity-80 tracking-widest mt-1">GO!</span>
        </button>

        <p class="mt-8 text-slate-500 text-sm md:text-base font-medium relative z-50">Explora San Cristóbal de las Casas a tu alrededor.</p>
    </div>

    <!-- Decorative Background Elements -->
    <div class="absolute top-0 left-0 w-full h-full pointer-events-none z-0">
        <div class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-blue-200 dark:bg-blue-900 rounded-full mix-blend-multiply filter blur-3xl opacity-30 dark:opacity-20 animate-blob"></div>
        <div class="absolute top-[-10%] right-[-10%] w-96 h-96 bg-yellow-200 dark:bg-yellow-900 rounded-full mix-blend-multiply filter blur-3xl opacity-30 dark:opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-[-10%] left-[20%] w-96 h-96 bg-pink-200 dark:bg-pink-900 rounded-full mix-blend-multiply filter blur-3xl opacity-30 dark:opacity-20 animate-blob animation-delay-4000"></div>
    </div>
</div>

<!-- Map Section (Initially Hidden) -->
<div id="map-section" class="hidden w-full h-screen fixed inset-0 bg-slate-50 dark:bg-slate-900 z-[9999]">
    <!-- Header for Map View -->
    <?php get_template_part('template-parts/header-custom'); ?>

    <div class="flex-grow relative overflow-hidden flex flex-col md:flex-row h-full">
        
        <!-- Map Container -->
        <div class="absolute inset-0 md:relative md:flex-grow md:w-auto z-0 bg-slate-100 dark:bg-slate-800">
            <div id="map" class="h-full w-full"></div>
            
            <!-- Events List Overlay (Default Hidden) -->
            <div id="events-container" class="hidden absolute bottom-6 right-6 z-[1000] w-72 md:w-80 max-h-[60vh] md:max-h-[500px] overflow-y-auto pointer-events-auto flex flex-col gap-1 px-2 py-2 custom-scrollbar shadow-2xl rounded-2xl bg-white dark:bg-slate-800">
                <!-- Events will be injected here -->
            </div>

            <!-- Loading Overlay -->
            <div id="map-loader" class="absolute inset-0 bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm z-[1000] flex flex-col items-center justify-center hidden">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-brand-blue dark:border-brand-gold mb-4"></div>
                <p class="text-brand-blue dark:text-brand-gold font-medium animate-pulse">Obteniendo tu ubicación...</p>
            </div>

            <!-- Custom Map Controls -->
            <div class="absolute top-4 right-4 z-[999] flex flex-col gap-2">
                <div class="flex flex-col bg-white dark:bg-slate-800 rounded-lg shadow-lg overflow-hidden border border-slate-200 dark:border-slate-600">
                    <button id="zoom-in" class="p-3 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-colors border-b border-slate-100 dark:border-slate-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    </button>
                    <button id="zoom-out" class="p-3 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                    </button>
                </div>
                <button id="center-map" class="p-3 bg-white dark:bg-slate-800 rounded-lg shadow-lg border border-slate-200 dark:border-slate-600 text-brand-blue dark:text-brand-gold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </button>
            </div>
        </div>

        <!-- Cards List -->
        <div class="absolute bottom-0 left-0 right-0 md:relative md:w-96 md:h-full z-10 pointer-events-none md:pointer-events-auto flex flex-col md:bg-white md:dark:bg-slate-800 md:border-r md:border-slate-200 md:dark:border-slate-700 md:shadow-xl">
            
            <div class="hidden md:flex justify-between items-center p-4 bg-white dark:bg-slate-800 border-b border-slate-100 dark:border-slate-700">
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100">Resultados (<span id="result-count-desktop">0</span>)</h3>
                <span class="text-xs text-slate-400">Radio de 2km</span>
            </div>

            <div class="w-full md:flex-grow md:overflow-y-auto p-4 pointer-events-auto">
                <div id="cards-container" class="flex md:flex-col gap-4 overflow-x-auto md:overflow-visible snap-x snap-mandatory hide-scrollbar pb-12 md:pb-0">
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
