<?php get_header(); ?>
<?php get_template_part('template-parts/header-custom'); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); 
    // ACF Fields
    $fecha = get_field('fecha') ?: 'Fecha por confirmar';
    $hora = get_field('hora') ?: '';
    $ubicacion_texto = get_field('ubicacion') ?: 'Ubicación por confirmar';
    // We could add more explicit location fields like 'coordenadas' later if needed
    
    // Formatting date
    $date_obj = date_create($fecha);
    $date_day = $date_obj ? date_format($date_obj, 'd') : '';
    $date_month = $date_obj ? date_i18n('M', strtotime($fecha)) : '';
?>

<main class="bg-white dark:bg-slate-900 transition-colors duration-300">
    <!-- Hero Section -->
    <div class="relative h-[60vh] min-h-[500px] w-full overflow-hidden flex items-end">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('full', ['class' => 'absolute inset-0 w-full h-full object-cover']); ?>
        <?php else : ?>
            <img src="https://loremflickr.com/1200/800/party,concert" class="absolute inset-0 w-full h-full object-cover" alt="<?php the_title(); ?>">
        <?php endif; ?>
        
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/50 to-transparent"></div>
        
        <div class="container mx-auto p-6 md:p-12 relative z-10">
            <div class="flex flex-col md:flex-row gap-6 md:items-end">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 text-center min-w-[100px] shadow-2xl">
                    <span class="block text-xl font-medium text-brand-gold uppercase tracking-wider"><?php echo $date_month; ?></span>
                    <span class="block text-5xl font-black text-white"><?php echo $date_day; ?></span>
                </div>
                
                <div class="flex-grow">
                    <span class="inline-block px-3 py-1 rounded-full bg-brand-blue text-white text-xs font-bold uppercase tracking-widest mb-4 shadow-lg border border-white/10">
                        Evento
                    </span>
                    <div class="flex items-center justify-between gap-4">
                        <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white mb-4 leading-tight">
                            <?php the_title(); ?>
                        </h1>
                        <?php 
                        $is_fav = false;
                        if (is_user_logged_in()) {
                            $favs = get_user_meta(get_current_user_id(), 'user_favorites', true);
                            if (is_array($favs) && in_array(get_the_ID(), $favs)) {
                                $is_fav = true;
                            }
                        }
                        ?>
                        <button id="btn-favorite" data-id="<?php the_ID(); ?>" class="text-4xl md:text-5xl transition-transform hover:scale-110 focus:outline-none group relative <?php echo $is_fav ? 'text-red-500' : 'text-white opacity-50 hover:opacity-100'; ?>">
                            <?php echo $is_fav ? '❤️' : '🤍'; ?>
                            <div class="absolute bottom-full mb-3 left-1/2 -translate-x-1/2 px-3 py-1.5 bg-slate-900/90 backdrop-blur-sm text-white text-[10px] font-bold rounded-lg opacity-0 group-hover:opacity-100 transition-all pointer-events-none whitespace-nowrap z-50 shadow-xl">
                                <?php echo $is_fav ? 'Quitar de favoritos' : 'Añadir a favoritos'; ?>
                                <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-slate-900/90 rotate-45"></div>
                            </div>
                        </button>
                    </div>
                    <div class="flex flex-wrap gap-6 text-white/90 font-medium text-lg">
                        <?php if ($hora): ?>
                            <div class="flex items-center gap-2">
                                <span class="text-brand-gold">🕒</span> <?php echo $hora; ?>
                            </div>
                        <?php endif; ?>
                        <div class="flex items-center gap-2">
                            <span class="text-brand-gold">📍</span> <?php echo $ubicacion_texto; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="container mx-auto px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-12">
                <section>
                    <h2 class="text-2xl font-black text-brand-blue dark:text-brand-gold uppercase tracking-tight mb-6">Detalles del Evento</h2>
                    <div class="prose dark:prose-invert max-w-none text-slate-600 dark:text-slate-400 leading-relaxed text-lg">
                        <?php 
                        if ($desc_larga = get_field('descripcion_larga')) {
                            echo $desc_larga; 
                        } else {
                            // Fallback to standard content if custom field is empty
                            the_content(); 
                        }
                        ?>
                    </div>
                </section>
                
                <!-- Share -->
                <section class="border-t border-slate-100 dark:border-slate-800 pt-8">
                    <h3 class="text-sm font-bold text-slate-400 mb-4 uppercase">Compartir este evento</h3>
                    <div class="flex gap-4">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank" class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center hover:scale-110 transition-transform">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode(get_the_title()); ?>&url=<?php the_permalink(); ?>" target="_blank" class="w-10 h-10 rounded-full bg-black text-white flex items-center justify-center hover:scale-110 transition-transform">
                            <i class="fab fa-x-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text=<?php echo urlencode("¡Checa este evento! " . get_the_permalink()); ?>" target="_blank" class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center hover:scale-110 transition-transform">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </section>
            </div>

            <!-- Sidebar -->
            <aside class="lg:col-span-1">
                <div class="sticky top-24 space-y-6">
                    
                    <!-- QR Code -->
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl shadow-lg border border-slate-100 dark:border-slate-700 text-center">
                        <h3 class="text-lg font-bold text-slate-700 dark:text-white mb-4">Escanea para llevar</h3>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?php the_permalink(); ?>" alt="QR Code" class="mx-auto rounded-lg shadow-sm mb-2">
                        <p class="text-xs text-slate-400">Escanea con tu celular</p>
                    </div>

                    <div class="bg-brand-blue dark:bg-slate-800 p-8 rounded-3xl shadow-2xl text-white relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-32 bg-white/5 rounded-full blur-3xl pointer-events-none -mr-16 -mt-16"></div>
                        
                        <h3 class="text-2xl font-bold mb-6 relative z-10">¿Te interesa?</h3>
                        
                        <div class="space-y-4 relative z-10">
                            <button onclick="window.print()" class="w-full bg-white text-brand-blue font-bold py-4 px-2 rounded-xl hover:bg-slate-100 transition-all shadow-lg flex items-center justify-center gap-2">
                                <span>🎟️</span> Imprimir Info
                            </button>
                            
                            <a href="https://calendar.google.com/calendar/render?action=TEMPLATE&text=<?php echo urlencode(get_the_title()); ?>&dates=<?php echo date('Ymd', strtotime($fecha)); ?>T<?php echo str_replace(':', '', $hora); ?>00/<?php echo date('Ymd', strtotime($fecha)); ?>T235900&details=<?php echo urlencode(get_the_excerpt()); ?>&location=<?php echo urlencode($ubicacion_texto); ?>" 
                               target="_blank"
                               class="w-full bg-brand-gold hover:bg-amber-600 text-white font-bold py-4 px-2 rounded-xl transition-all shadow-lg flex items-center justify-center gap-2">
                                <span>📅</span> Agregar a Calendario
                            </a>
                        </div>
                    </div>

                    <!-- Related Events (Mock or Real) -->
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-xl">
                        <h3 class="font-bold text-slate-800 dark:text-white mb-4">Otros Eventos</h3>
                        <?php 
                        $related = new WP_Query(array(
                            'post_type' => 'eventos',
                            'posts_per_page' => 3,
                            'post__not_in' => array(get_the_ID()),
                            'orderby' => 'rand' // simple recommendation
                        ));
                        
                        if ($related->have_posts()):
                            echo '<ul class="space-y-4">';
                            while ($related->have_posts()): $related->the_post();
                            ?>
                            <li class="flex gap-4 group cursor-pointer" onclick="window.location='<?php the_permalink(); ?>'">
                                <?php if (has_post_thumbnail()): ?>
                                    <img src="<?php the_post_thumbnail_url('thumbnail'); ?>" class="w-16 h-16 rounded-lg object-cover">
                                <?php else: ?>
                                    <div class="w-16 h-16 rounded-lg bg-slate-100"></div>
                                <?php endif; ?>
                                <div>
                                    <h4 class="font-bold text-sm text-slate-700 dark:text-slate-300 group-hover:text-brand-blue transition-colors"><?php the_title(); ?></h4>
                                    <p class="text-xs text-slate-400 mt-1"><?php the_field('fecha'); ?></p>
                                </div>
                            </li>
                            <?php 
                            endwhile;
                            echo '</ul>';
                        else:
                            echo '<p class="text-slate-400 text-sm">No hay más eventos por ahora.</p>';
                        endif;
                        wp_reset_postdata();
                        ?>
                    </div>

                </div>
            </aside>
        </div>
    </div>
    
    <!-- Floating Back Button -->
    <a href="<?php echo home_url('/eventos'); ?>" class="fixed bottom-8 left-8 z-50 bg-white/90 dark:bg-slate-800/90 backdrop-blur-md p-4 rounded-full shadow-2xl border border-white/20 dark:border-slate-700/50 hover:scale-110 transition-all group">
        <span class="text-brand-blue dark:text-brand-gold font-bold flex items-center gap-2">
            <span class="group-hover:-translate-x-1 transition-transform">←</span> Eventos
        </span>
    </a>

</main>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
