<?php get_header(); ?>
<?php get_template_part('template-parts/header-custom'); ?>

<?php if (have_posts()) : while (have_posts()) : the_post(); 
    // ACF Fields
    $category = get_field('categoria') ?: 'General';
    $rating = get_field('puntuacion') ?: '4.8';
    $horario = get_field('horario') ?: 'Abierto 09:00 - 21:00';
    $ubicacion = get_field('ubicacion');
    $direccion = $ubicacion['address'] ?? 'Barrio de Guadalupe, San Cristóbal';
    $gallery = get_field('galeria'); // Array of images
    
    // New Fields
    $desc_personalizada = get_field('descripcion_personalizada');
    $msg_whatsapp = get_field('mensaje_whatsapp') ?: "Hola, vi su lugar en Visita San Cris y quisiera más información.";
    $msg_email = get_field('mensaje_email') ?: "Hola, solicito información sobre sus servicios.";
?>

<main class="bg-white dark:bg-slate-900 transition-colors duration-300">
    <!-- Hero Section -->
    <?php 
    // Image Logic: Featured > Gallery[0] > Logo
    $hero_image = get_template_directory_uri() . '/assets/img/logo.jpg'; // Default
    if (has_post_thumbnail()) {
        $hero_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
    } elseif ($gallery && is_array($gallery) && !empty($gallery)) {
        $hero_image = $gallery[0]['url'];
    }
    ?>
    <div class="relative h-[60vh] min-h-[500px] w-full overflow-hidden flex items-end">
        <img src="<?php echo esc_url($hero_image); ?>" class="absolute inset-0 w-full h-full object-cover" alt="<?php the_title(); ?>">
        
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
        <div class="absolute bottom-0 left-0 w-full p-8 md:p-16">
            <div class="container mx-auto">
                <span class="inline-block px-3 py-1 rounded-full bg-brand-gold text-white text-xs font-bold uppercase tracking-widest mb-4 shadow-lg">
                    <?php echo $category; ?>
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
                    <div class="flex items-center gap-2">
                        <span class="text-brand-gold text-xl">★</span>
                        <span class="font-bold"><?php echo $rating; ?></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        <span class="text-sm font-medium">Abierto ahora</span>
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
                    <h2 class="text-2xl font-black text-brand-blue dark:text-brand-gold uppercase tracking-tight mb-6 mt-4">Sobre el lugar</h2>
                    <div class="prose dark:prose-invert max-w-none text-slate-600 dark:text-slate-400 leading-relaxed">
                        <?php if ($desc_personalizada): ?>
                            <p class="font-medium text-lg text-slate-700 dark:text-slate-200 mb-4">
                                <?php echo nl2br(esc_html($desc_personalizada)); ?>
                            </p>
                        <?php endif; ?>
                        <?php the_content(); ?>
                    </div>
                </section>
                
                <!-- Tags & Tips Section -->
                <?php 
                $post_tags = get_the_tags();
                $tips = get_field('tips');
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

                <section class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-slate-50 dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700">
                        <h3 class="text-2xl font-black text-brand-blue dark:text-white mb-4 flex items-center gap-2">
                            <span>🕒</span> Horarios
                        </h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 whitespace-pre-line"><?php echo $horario; ?></p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700">
                        <h3 class="text-2xl font-black text-brand-blue dark:text-white mb-4 flex items-center gap-2">
                            <span>📍</span> Ubicación
                        </h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mb-4"><?php echo $direccion; ?></p>
                        <?php if ($ubicacion && isset($ubicacion['lat']) && isset($ubicacion['lng'])): ?>
                        <button onclick="window.open('https://www.google.com/maps/dir/?api=1&destination=<?php echo $ubicacion['lat']; ?>,<?php echo $ubicacion['lng']; ?>', '_blank')" 
                                class="text-brand-blue dark:text-brand-gold font-bold text-sm hover:underline">
                        <?php else: ?>
                        <button onclick="window.open('https://www.google.com/maps/search/?api=1&query=<?php echo urlencode(get_the_title() . ' San Cristóbal'); ?>', '_blank')" 
                                class="text-brand-blue dark:text-brand-gold font-bold text-sm hover:underline">
                        <?php endif; ?>
                            Ver en Google Maps →
                        </button>
                    </div>
                </section>

                <!-- Gallery Placeholder -->
                <section>
                    <h2 class="text-2xl font-black text-brand-blue dark:text-brand-gold uppercase tracking-tight mb-6">Galería</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <?php if ($gallery) : foreach ($gallery as $img) : ?>
                            <a href="<?php echo $img['url']; ?>" data-fancybox="gallery" class="aspect-square rounded-xl overflow-hidden shadow-lg hover:scale-105 transition-transform duration-500 block">
                                <img src="<?php echo $img['url']; ?>" class="w-full h-full object-cover">
                            </a>
                        <?php endforeach; else : ?>
                            <div class="aspect-square rounded-xl bg-slate-100 dark:bg-slate-800 animate-pulse"></div>
                            <div class="aspect-square rounded-xl bg-slate-100 dark:bg-slate-800 animate-pulse delay-75"></div>
                            <div class="aspect-square rounded-xl bg-slate-100 dark:bg-slate-800 animate-pulse delay-150"></div>
                        <?php endif; ?>
                    </div>
                </section>
            </div>

                <!-- Sidebar -->
                <aside class="lg:col-span-1">
                    <div class="sticky top-24 space-y-6">
                        <?php 
                        $whatsapp = get_field('whatsapp');
                        $email = get_field('email');
                        $telefono = get_field('telefono');
                        
                        if ($whatsapp || $email || $telefono): 
                        ?>
                        <div class="bg-brand-blue dark:bg-slate-800 p-6 md:p-8 rounded-3xl shadow-2xl text-white">
                            <h3 class="text-2xl font-black mb-6 text-white">¿Quieres visitarnos?</h3>
                            <div class="flex flex-col gap-4">
                                <?php if ($ubicacion && isset($ubicacion['lat']) && isset($ubicacion['lng'])): ?>
                                <button onclick="window.open('https://www.google.com/maps/dir/?api=1&destination=<?php echo $ubicacion['lat']; ?>,<?php echo $ubicacion['lng']; ?>', '_blank')" 
                                        class="w-full bg-brand-gold hover:bg-amber-600 text-white font-bold py-4 px-2 rounded-xl transition-all shadow-lg flex items-center justify-center gap-2 text-sm md:text-base">
                                <?php else: ?>
                                <button onclick="window.open('https://www.google.com/maps/search/?api=1&query=<?php echo urlencode(get_the_title() . ' San Cristóbal'); ?>', '_blank')" 
                                        class="w-full bg-brand-gold hover:bg-amber-600 text-white font-bold py-4 px-2 rounded-xl transition-all shadow-lg flex items-center justify-center gap-2 text-sm md:text-base">
                                <?php endif; ?>
                                    <span>🚗</span> Cómo llegar
                                </button>
                                
                                <?php if ($whatsapp): 
                                    $wa_link = "https://wa.me/" . esc_attr($whatsapp) . "?text=" . urlencode($msg_whatsapp);
                                ?>
                                <a href="<?php echo $wa_link; ?>" target="_blank" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-2 rounded-xl transition-all shadow-lg flex items-center justify-center gap-2 text-sm md:text-base">
                                    <span>💬</span> WhatsApp
                                </a>
                                <?php endif; ?>

                                <?php if ($email): 
                                    $mailto_link = "mailto:" . esc_attr($email) . "?subject=" . urlencode("Información - Visita San Cris") . "&body=" . urlencode($msg_email);
                                ?>
                                <a href="<?php echo $mailto_link; ?>" class="w-full bg-white/10 hover:bg-white/20 text-white font-bold py-4 px-2 rounded-xl border border-white/20 transition-all flex items-center justify-center gap-2 text-sm md:text-base">
                                    <span>📧</span> Email
                                </a>
                                <?php endif; ?>

                                <?php if ($telefono): ?>
                                <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9]/', '', $telefono)); ?>" class="w-full bg-white/10 hover:bg-white/20 text-white font-bold py-4 px-2 rounded-xl border border-white/20 transition-all flex items-center justify-center gap-2 text-sm md:text-base">
                                    <span>📞</span> Llamar ahora (<?php echo esc_html($telefono); ?>)
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Stats Card -->
                        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-xl">
                            <ul class="space-y-4">
                                <?php if ($puntuacion = get_field('puntuacion')): ?>
                                <li class="flex justify-between items-center text-lg">
                                    <span class="text-slate-500 dark:text-slate-400 font-bold">Puntuación</span>
                                    <span class="text-xl font-black text-brand-gold">⭐ <?php echo esc_html($puntuacion); ?></span>
                                </li>
                                <?php endif; ?>

                                <?php if ($horario = get_field('horario')): ?>
                                <li class="text-lg">
                                    <span class="text-slate-500 dark:text-slate-400 block mb-2 font-bold">Horario</span>
                                    <span class="font-medium text-slate-800 dark:text-slate-200"><?php echo nl2br(esc_html($horario)); ?></span>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <!-- QR Code Card -->
                        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-xl text-center">
                            <h3 class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-4">Comparte este lugar</h3>
                            <?php 
                            $current_url = urlencode(get_permalink());
                            $qr_api_url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . $current_url;
                            ?>
                            <div class="w-48 h-48 mx-auto bg-white p-3 rounded-2xl shadow-inner">
                                <img src="<?php echo $qr_api_url; ?>" 
                                     alt="QR Code" 
                                     class="w-full h-full object-contain">
                            </div>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-4">Escanea para guardar el contacto directo.</p>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <!-- Floating Back Button -->
    <a href="<?php echo home_url('/'); ?>" class="fixed bottom-8 left-8 z-50 bg-white/90 dark:bg-slate-800/90 backdrop-blur-md p-4 rounded-full shadow-2xl border border-white/20 dark:border-slate-700/50 hover:scale-110 transition-all group">
        <span class="text-brand-blue dark:text-brand-gold font-bold flex items-center gap-2">
            <span class="group-hover:-translate-x-1 transition-transform">←</span> Regresar
        </span>
    </a>
</main>

<?php endwhile; endif; ?>

<?php get_footer(); ?>
