<?php get_header(); ?>
<?php get_template_part('template-parts/header-custom'); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); 
    // ACF Fields
    $fecha = get_field('fecha') ?: 'Fecha por confirmar';
    $hora = get_field('hora') ?: '';
    $ubicacion_texto = get_field('ubicacion') ?: 'Ubicación por confirmar';
    
    // Formatting date
    $date_obj = date_create($fecha);
    $date_day = $date_obj ? date_format($date_obj, 'd') : '';
    $date_month = $date_obj ? date_i18n('M', strtotime($fecha)) : '';

    // New Fields
    $costo_tipo = get_field('costo_tipo'); // free/paid
    $costo_valor = get_field('costo_valor'); 
    $whatsapp = get_field('whatsapp');
    $tips = get_field('tips');
    $gallery = get_field('galeria');

    // Image Logic: Featured > Gallery[0] > Logo
    $hero_image = get_template_directory_uri() . '/assets/img/logo.jpg';
    if (has_post_thumbnail()) {
        $hero_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
    } elseif ($gallery && is_array($gallery) && !empty($gallery)) {
        $hero_image = $gallery[0]['url'];
    }
?>

<main class="bg-white dark:bg-slate-900 transition-colors duration-300">
    <!-- Hero Section -->
    <div class="relative h-[60vh] min-h-[500px] w-full overflow-hidden flex items-end">
        <img src="<?php echo esc_url($hero_image); ?>" class="absolute inset-0 w-full h-full object-cover" alt="<?php the_title(); ?>">
        
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
        
        <div class="absolute bottom-0 left-0 w-full p-8 md:p-16">
            <div class="container mx-auto relative z-10">
                <div class="flex flex-col md:flex-row gap-6 md:items-end">
                    <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 text-center min-w-[100px] shadow-2xl hidden md:block">
                        <span class="block text-xl font-medium text-brand-gold uppercase tracking-wider"><?php echo $date_month; ?></span>
                        <span class="block text-5xl font-black text-white"><?php echo $date_day; ?></span>
                    </div>
                    
                    <div class="flex-grow">
                        <span class="inline-block px-3 py-1 rounded-full bg-brand-gold text-white text-xs font-bold uppercase tracking-widest mb-4 shadow-lg">
                            Evento
                        </span>
                        <div class="flex items-center justify-between">
                            <h1 class="text-4xl md:text-6xl font-black text-white mb-4 uppercase tracking-tighter">
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
                        <div class="flex items-center gap-6 text-white/90">
                            <?php if ($hora): ?>
                                <div class="flex items-center gap-2">
                                    <span class="text-brand-gold text-xl">🕒</span> 
                                    <span class="font-bold text-lg"><?php echo $hora; ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="flex items-center gap-2">
                                <span class="text-brand-gold text-xl">📍</span>
                                <span class="font-bold text-lg"><?php echo $ubicacion_texto; ?></span>
                            </div>
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
                    <h2 class="text-2xl font-black text-brand-blue dark:text-brand-gold uppercase tracking-tight mb-6 mt-4">Detalles del Evento</h2>
                    <div class="prose dark:prose-invert max-w-none text-slate-600 dark:text-slate-400 leading-relaxed text-lg">
                        <?php 
                        if ($desc_larga = get_field('descripcion_larga')) {
                            echo $desc_larga; 
                        } else {
                            the_content(); 
                        }
                        ?>
                    </div>
                </section>
                
                <!-- Tags & Tips Section -->
                <?php 
                $post_tags = get_the_tags();
                if ($post_tags || $tips): 
                ?>
                <section class="space-y-8">
                    <?php if ($post_tags): ?>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach($post_tags as $tag): ?>
                        <span class="inline-block bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 px-3 py-1 rounded-full text-sm font-bold">
                            #<?php echo $tag->name; ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($tips): ?>
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-6 rounded-2xl border border-yellow-100 dark:border-yellow-700/50">
                        <h3 class="text-xl font-black text-brand-gold mb-3 flex items-center gap-2">
                            <span>💡</span> Tips:
                        </h3>
                        <div class="prose dark:prose-invert text-slate-700 dark:text-slate-300 text-sm">
                            <?php echo nl2br(esc_html($tips)); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </section>
                <?php endif; ?>

                <!-- Gallery -->
                <?php if ($gallery): ?>
                <section>
                    <h2 class="text-2xl font-black text-brand-blue dark:text-brand-gold uppercase tracking-tight mb-6">Galería</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <?php foreach ($gallery as $img) : ?>
                            <div class="aspect-square rounded-xl overflow-hidden shadow-lg hover:scale-105 transition-transform duration-500">
                                <img src="<?php echo $img['url']; ?>" class="w-full h-full object-cover">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                <?php endif; ?>
                
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

                            <?php if ($whatsapp): 
                                $wa_link = "https://wa.me/" . esc_attr($whatsapp) . "?text=" . urlencode("Hola, quiero reservar para el evento: " . get_the_title());
                            ?>
                            <a href="<?php echo $wa_link; ?>" target="_blank" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-2 rounded-xl transition-all shadow-lg flex items-center justify-center gap-2">
                                <span>💬</span> Reservar por WhatsApp
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Cost Info -->
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-xl">
                        <h3 class="font-bold text-slate-800 dark:text-white mb-4 flex items-center gap-2"><span>🎟️</span> Entrada</h3>
                        <?php if ($costo_tipo === 'paid'): ?>
                            <div class="text-2xl font-black text-brand-blue dark:text-brand-gold"><?php echo esc_html($costo_valor); ?></div>
                            <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Con Costo</span>
                        <?php else: ?>
                            <div class="text-2xl font-black text-green-500">GRATIS</div>
                            <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">Entrada Libre</span>
                        <?php endif; ?>
                    </div>

                    <!-- Related Events -->
                    <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-xl">
                        <h3 class="font-bold text-slate-800 dark:text-white mb-4">Otros Eventos</h3>
                        <?php 
                        $related = new WP_Query(array(
                            'post_type' => 'eventos',
                            'posts_per_page' => 3,
                            'post__not_in' => array(get_the_ID()),
                            'orderby' => 'rand'
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
