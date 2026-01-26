<?php get_header(); ?>

<!-- Header for Events Archive (Copied from front-page.php for consistency) -->
<?php get_template_part('template-parts/header-custom'); ?>

<main class="container mx-auto px-4 py-8 min-h-screen">
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (have_posts()) : while (have_posts()) : the_post(); 
            $fecha = get_field('fecha') ?: 'Próximamente';
            $hora = get_field('hora') ?: '';
            $ubicacion = get_field('ubicacion') ?: '';
            $date_obj = date_create($fecha);
            $date_day = $date_obj ? date_format($date_obj, 'd') : '';
            $date_month = $date_obj ? date_i18n('M', strtotime($fecha)) : '';
        ?>
            <article class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-100 dark:border-slate-700 overflow-hidden hover:shadow-xl transition-all group">
                <a href="<?php the_permalink(); ?>" class="block relative h-48 overflow-hidden">
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('medium', ['class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform duration-700']); ?>
                    <?php else : ?>
                        <div class="w-full h-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                            <span class="text-4xl">📅</span>
                        </div>
                    <?php endif; ?>
                    <div class="absolute top-2 right-2 bg-white/90 dark:bg-slate-900/90 backdrop-blur-sm p-2 rounded-lg text-center shadow-md border border-slate-200 dark:border-slate-700">
                        <span class="block text-xs font-bold text-brand-gold uppercase"><?php echo $date_month; ?></span>
                        <span class="block text-xl font-black text-brand-blue dark:text-white"><?php echo $date_day; ?></span>
                    </div>
                </a>
                <div class="p-5">
                    <h2 class="text-xl font-bold text-brand-blue dark:text-brand-gold mb-2 leading-tight">
                        <a href="<?php the_permalink(); ?>" class="hover:underline"><?php the_title(); ?></a>
                    </h2>
                    <div class="text-sm text-slate-500 dark:text-slate-400 space-y-1 mb-4">
                        <?php if ($hora): ?>
                            <div class="flex items-center gap-2">
                                <span>🕒</span> <?php echo $hora; ?>
                            </div>
                        <?php endif; ?>
                        <?php if ($ubicacion): ?>
                            <div class="flex items-center gap-2">
                                <span>📍</span> <?php echo $ubicacion; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <a href="<?php the_permalink(); ?>" class="block w-full text-center bg-slate-50 dark:bg-slate-700 hover:bg-brand-blue hover:text-white dark:hover:bg-brand-gold dark:hover:text-slate-900 text-slate-600 dark:text-slate-300 font-bold py-2 rounded-lg transition-colors text-sm">
                        Ver Detalles
                    </a>
                </div>
            </article>
        <?php endwhile; else: ?>
            <div class="col-span-full text-center py-20">
                <p class="text-slate-400 text-lg">No hay eventos programados por el momento.</p>
            </div>
        <?php endif; ?>
    </div>

</main>

<?php get_footer(); ?>
