<?php
/**
 * Block Name: Events List
 */

$events_query = new WP_Query([
    'post_type' => 'eventos',
    'posts_per_page' => 3,
    'meta_key' => 'fecha_evento',
    'orderby' => 'meta_value',
    'order' => 'ASC'
]);

?>

<div id="events-list-block" class="fixed bottom-6 right-6 z-[1000] w-72 md:w-80 flex flex-col gap-3 pointer-events-auto">
    <div class="mb-1 pointer-events-none">
        <h3 class="text-3xl font-black text-brand-gold drop-shadow-md italic uppercase tracking-tighter">HOY</h3>
    </div>

    <?php if ($events_query->have_posts()) : while ($events_query->have_posts()) : $events_query->the_post(); 
        $date = get_field('fecha_evento') ?: date('d M, H:i');
        $place = get_field('lugar_evento') ?: 'Centro Histórico';
        $date_parts = explode(' ', $date);
    ?>
        <div class="bg-white/95 dark:bg-slate-800/95 backdrop-blur-md p-3 rounded-xl shadow-xl border border-white/20 dark:border-slate-700/50 transform transition-all duration-300 hover:translate-x-1 cursor-pointer group">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-brand-gold/10 dark:bg-brand-gold/20 flex flex-col items-center justify-center text-brand-gold">
                    <span class="text-[10px] font-bold uppercase leading-none"><?php echo $date_parts[1] ?? ''; ?></span>
                    <span class="text-lg font-black leading-none"><?php echo $date_parts[0] ?? ''; ?></span>
                </div>
                <div class="flex-grow min-w-0">
                    <h4 class="font-bold text-brand-blue dark:text-white text-sm truncate uppercase tracking-tight"><?php the_title(); ?></h4>
                    <div class="flex items-center gap-2 text-[10px] text-slate-500 dark:text-slate-400 font-medium">
                        <span class="opacity-70">🕒 <?php echo explode(', ', $date)[1] ?? $date; ?></span>
                        <span class="opacity-30">•</span>
                        <span class="truncate">📍 <?php echo $place; ?></span>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; wp_reset_postdata(); else : ?>
        <p class="text-xs text-slate-400">No hay eventos para hoy.</p>
    <?php endif; ?>
</div>
