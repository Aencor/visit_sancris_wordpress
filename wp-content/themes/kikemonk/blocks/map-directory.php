<?php
/**
 * Block Name: Map Directory
 */

$places_query = new WP_Query([
    'post_type' => ['lugares', 'eventos'],
    'posts_per_page' => -1,
]);

$places_data = [];
if ($places_query->have_posts()) {
    while ($places_query->have_posts()) {
        $places_query->the_post();
        $id = get_the_ID();
        $type = get_post_type();
        
        // Image logic
        $image = get_the_post_thumbnail_url($id, 'medium') ?: 'https://loremflickr.com/320/240/san-cristobal';
        
        // Location logic
        $lat = 0; $lng = 0;
        if ($type === 'lugares') {
            $loc = get_field('ubicacion');
            $lat = $loc['lat'] ?? 16.7371;
            $lng = $loc['lng'] ?? -92.6376;
            $cat = get_field('categoria') ?: 'lugar';
        } else {
            // Eventos uses 'ubicacion_mapa'
            $loc = get_field('ubicacion_mapa');
            if (!$loc && $type === 'eventos') {
                 // Fallback if defined differently or using text field? 
                 // Assuming ubicacion_mapa is the one.
                 // If not set, maybe use default
            }
            $lat = $loc['lat'] ?? 16.7371;
            $lng = $loc['lng'] ?? -92.6376;
            $cat = 'evento';
        }

        $places_data[] = [
            'id' => $id,
            'name' => get_the_title(),
            'description' => get_the_excerpt(),
            'image' => $image,
            'category' => $cat,
            'lat' => (float) $lat,
            'lng' => (float) $lng,
            'url' => get_permalink(),
            'type' => $type
        ];
    }
    wp_reset_postdata();
}

// Localize data for JS
wp_localize_script('theme-defer', 'wpData', [
    'places' => $places_data,
    'center' => ['lat' => 16.7371, 'lng' => -92.6376]
]);
?>

<section id="map-directory-block" class="relative w-full h-[600px] flex flex-col md:flex-row bg-slate-50 dark:bg-slate-900 overflow-hidden rounded-2xl shadow-xl mt-12">
    <!-- Sidebar -->
    <div class="w-full md:w-80 h-full flex flex-col z-10 bg-white/80 dark:bg-slate-800/80 backdrop-blur-md border-r border-slate-200 dark:border-slate-700">
        <div class="p-6">
            <h3 class="text-xl font-black text-brand-blue dark:text-white uppercase tracking-tight">Directorio</h3>
            <p class="text-xs text-slate-500 mt-1">Explora los mejores lugares de San Cris.</p>
        </div>
        <div id="cards-container-wp" class="flex-grow overflow-y-auto p-4 flex flex-col gap-4">
            <!-- Cards injected via JS -->
        </div>
    </div>

    <!-- Map -->
    <div class="flex-grow relative h-full">
        <div id="map-instance" class="w-full h-full"></div>
        
        <!-- Theme Toggle -->
        <button id="theme-toggle" class="absolute top-6 right-6 z-[1000] p-3 rounded-full bg-white/90 dark:bg-slate-800/90 shadow-xl border border-white/20 dark:border-slate-700/50 text-xl">
            <span class="dark:hidden">🌙</span>
            <span class="hidden dark:inline">☀️</span>
        </button>
    </div>
</section>

<style>
/* Leaflet Styles */
.custom-popup .leaflet-popup-content-wrapper { border-radius: 12px; padding: 0; overflow: hidden; }
.custom-popup .leaflet-popup-content { margin: 0; width: 250px !important; }
.dark .leaflet-popup-content-wrapper { background: #1e293b; color: white; }
.dark .leaflet-popup-tip { background: #1e293b; }
</style>
