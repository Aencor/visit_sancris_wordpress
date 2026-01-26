<?php
/**
 * Template Name: Events Landing
 */

get_header(); ?>

<!-- Hero Section -->
<div class="relative py-20 bg-brand-blue overflow-hidden">
    <div class="absolute inset-0 bg-[url('<?php echo get_template_directory_uri(); ?>/assets/img/pattern.svg')] opacity-10"></div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h1 class="text-4xl md:text-6xl font-black text-white mb-4">Próximos Eventos</h1>
        <p class="text-white/80 text-xl md:text-2xl max-w-2xl mx-auto">
            Descubre qué está pasando en San Cristóbal de las Casas. Arte, música, cultura y más.
        </p>
    </div>
    
    <!-- Decorative Blobs -->
    <div class="absolute top-[-50px] left-[-50px] w-64 h-64 bg-brand-gold rounded-full blur-[80px] opacity-30"></div>
    <div class="absolute bottom-[-50px] right-[-50px] w-64 h-64 bg-pink-500 rounded-full blur-[80px] opacity-30"></div>
</div>

<!-- Events Grid -->
<div class="bg-slate-50 dark:bg-slate-900 min-h-screen py-16">
    <div class="container mx-auto px-4">
        
        <?php
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args = array(
            'post_type' => 'eventos',
            'posts_per_page' => 9,
            'paged' => $paged,
            'meta_key' => 'fecha',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'post_status' => 'publish'
        );
        $events_query = new WP_Query($args);
        
        if ($events_query->have_posts()): ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while ($events_query->have_posts()): $events_query->the_post(); 
                    $fecha = get_field('fecha') ?: 'Fecha por confirmar';
                    $hora = get_field('hora');
                    $ubicacion = get_field('ubicacion');
                    $image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                    
                    if (!$image_url) {
                        // Fallback image based on category or random
                        $image_url = 'https://loremflickr.com/640/480/concert,party?random=' . get_the_ID();
                    }
                ?>
                
                <!-- Event Card -->
                <article class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 group border border-slate-100 dark:border-slate-700">
                    <div class="relative h-48 overflow-hidden">
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php the_title(); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        <div class="absolute top-4 left-4 bg-white/90 dark:bg-slate-900/90 backdrop-blur-sm px-3 py-1 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                            <span class="text-sm font-bold text-brand-blue dark:text-brand-gold">
                                <?php echo date_i18n('d M', strtotime($fecha)); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-slate-800 dark:text-white mb-2 leading-tight group-hover:text-brand-blue transition-colors">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h2>
                        
                        <div class="flex flex-col gap-2 text-sm text-slate-500 dark:text-slate-400 mb-4">
                            <?php if ($hora): ?>
                                <div class="flex items-center gap-2">
                                    <span>🕒</span> <?php echo esc_html($hora); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($ubicacion): ?>
                                <div class="flex items-center gap-2">
                                    <span>📍</span> <?php echo esc_html($ubicacion); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <p class="text-slate-600 dark:text-slate-300 mb-6 line-clamp-3">
                            <?php echo get_the_excerpt(); ?>
                        </p>
                        
                        <a href="<?php the_permalink(); ?>" class="inline-block w-full text-center py-3 rounded-xl bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold hover:bg-brand-blue hover:text-white dark:hover:bg-brand-gold dark:hover:text-slate-900 transition-all">
                            Ver Detalles
                        </a>
                    </div>
                </article>
                
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                <?php
                echo paginate_links(array(
                    'total' => $events_query->max_num_pages,
                    'prev_text' => '←',
                    'next_text' => '→',
                    'type' => 'list',
                    'class' => 'flex gap-2'
                ));
                ?>
            </div>
            
        <?php else: ?>
            <div class="text-center py-20">
                <div class="text-6xl mb-4">📅</div>
                <h3 class="text-2xl font-bold text-slate-400">No hay eventos próximos</h3>
                <p class="text-slate-500">Vuelve a checar pronto.</p>
            </div>
        <?php endif; wp_reset_postdata(); ?>
        
    </div>
</div>

<style>
    /* Pagination Styles */
    .page-numbers {
        display: flex;
        gap: 0.5rem;
    }
    .page-numbers li span, .page-numbers li a {
        display: block;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        background: white;
        color: #64748b;
        font-weight: bold;
        transition: all 0.3s;
    }
    .dark .page-numbers li span, .dark .page-numbers li a {
        background: #1e293b;
        color: #94a3b8;
    }
    .page-numbers li span.current {
        background: #1e3a8a;
        color: white;
    }
    .page-numbers li a:hover {
        background: #cbd5e1;
    }
</style>

<?php get_footer(); ?>
