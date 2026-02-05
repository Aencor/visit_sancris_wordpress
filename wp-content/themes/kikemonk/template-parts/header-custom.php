<header class="bg-white dark:bg-slate-800 shadow-md p-2 md:px-4 sticky top-0 z-50 flex flex-row items-center gap-4 justify-between">
    
    <!-- Logo & Brand -->
    <a href="<?php echo home_url('/'); ?>" class="flex items-center gap-3 cursor-pointer hover:opacity-80 transition-opacity flex-shrink-0">
         <img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.jpg" alt="Logo Small" class="w-10 h-10 rounded-full border-2 border-slate-100 dark:border-slate-700 shadow-sm">
         <div class="leading-tight hidden lg:block">
             <h2 class="font-bold text-lg text-brand-blue dark:text-brand-gold">Visita San Cris</h2>
             <p class="text-[10px] text-slate-500 dark:text-slate-400">Directorio Turístico</p>
         </div>
    </a>

    <!-- Top Row Controls (Search + Actions) -->
    <div class="flex flex-grow items-center gap-2 md:gap-4 justify-end md:justify-between w-full md:w-auto">
        
        <!-- Search Bar -->
        <?php if (is_front_page()): ?>
        <!-- Mobile Search Toggle -->
        <button id="mobile-search-toggle" type="button" class="md:hidden p-2 rounded-full bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>

        <!-- Search Input Container -->
        <div id="header-search-container" class="absolute top-[100%] left-0 w-full bg-white dark:bg-slate-800 p-3 shadow-lg border-t border-slate-100 dark:border-slate-700 hidden md:relative md:top-auto md:left-auto md:w-auto md:bg-transparent md:p-0 md:shadow-none md:border-none md:flex md:flex-grow md:max-w-md md:min-w-[150px] z-[2000] md:z-auto">
             <input type="text" id="header-search" placeholder="Buscar..." 
                class="w-full py-2 px-4 rounded-full border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 text-sm focus:border-brand-blue dark:focus:border-brand-gold focus:ring-2 focus:ring-brand-blue/20 dark:text-white focus:outline-none shadow-sm transition-all text-slate-800">
             <div class="absolute right-6 md:right-3 top-1/2 transform -translate-y-1/2 text-slate-400 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
             </div>
        </div>
        <?php else: ?>
        <div class="flex-grow"></div>
        <?php endif; ?>

        <!-- Right Side Actions (Events, Profile, Theme) -->
        <div class="flex items-center gap-2 flex-shrink-0">
            <?php if (is_front_page()): ?>
            
            <!-- Categories Dropdown -->
            <div class="relative relative-dropdown">
                <button id="cat-dropdown-btn" class="px-3 py-1.5 rounded-full bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 hover:border-brand-blue transition-all shadow-sm text-xs font-medium flex items-center gap-1.5">
                    <span>🏷️</span> <span>Categorías</span> <span class="text-[10px]">▼</span>
                </button>
                
                <!-- Dropdown Menu -->
                <div id="cat-dropdown-menu" class="hidden absolute top-full right-0 mt-2 w-64 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 p-2 z-[100] max-h-[60vh] overflow-y-auto">
                    <button class="category-filter w-full text-left px-3 py-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-medium flex items-center gap-2 mb-1" data-cat="all">
                        <span>🔄</span> Todo / Reiniciar
                    </button>
                    <div class="h-px bg-slate-100 dark:bg-slate-700 my-1"></div>
                    <?php
                    $terms = get_terms([
                        'taxonomy' => 'tipo_lugar',
                        'hide_empty' => false,
                    ]);
                    if (!is_wp_error($terms) && !empty($terms)) {
                        foreach ($terms as $term) {
                             // Check for active class can be done in JS, here we just output markup
                             echo '<button class="category-filter w-full text-left px-3 py-2 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-medium flex items-center gap-2 transition-colors" data-cat="' . esc_attr($term->slug) . '">
                                    <span class="w-4 h-4 rounded border border-slate-300 dark:border-slate-500 flex items-center justify-center text-[10px] check-icon opacity-0">✓</span>
                                    ' . esc_html($term->name) . '
                                  </button>';
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="w-px h-6 bg-slate-200 dark:bg-slate-700 mx-1 hidden md:block"></div>

            <!-- Events Toggle -->
             <button class="event-toggle px-3 py-1.5 rounded-full bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-600 hover:border-brand-gold transition-all shadow-sm text-xs font-medium flex items-center gap-1.5 group" id="toggle-events-btn">
                <span class="grayscale group-hover:grayscale-0 transition-all">📅</span> <span>Eventos</span>
            </button>
            <?php endif; ?>

            <!-- Profile Link -->
            <a href="<?php echo is_user_logged_in() ? home_url('/mi-perfil') : home_url('/registro'); ?>" class="p-0.5 rounded-full bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors flex items-center justify-center w-10 h-10 group relative" title="Mi Perfil">
                <div class="w-full h-full rounded-full overflow-hidden relative">
                    <?php 
                    if (is_user_logged_in()) {
                        $avatar_id = get_user_meta(get_current_user_id(), 'custom_avatar', true);
                        if ($avatar_id) {
                            echo wp_get_attachment_image($avatar_id, 'thumbnail', false, ['class' => 'w-full h-full object-cover']);
                        } else {
                            echo '<div class="w-full h-full flex items-center justify-center text-lg"><span>👤</span></div>';
                        }
                    } else {
                        echo '<div class="w-full h-full flex items-center justify-center text-lg"><span>👤</span></div>';
                    }
                    ?>
                </div>
            </a>

            <!-- Theme Toggle -->
            <button class="theme-toggle-btn p-2 rounded-full bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors text-lg">
                <span class="dark:hidden">🌙</span>
                <span class="hidden dark:inline">☀️</span>
            </button>
        </div>
    </div>
</header>
