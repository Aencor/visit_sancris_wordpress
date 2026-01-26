<?php get_header(); ?>
<?php get_template_part('template-parts/header-custom'); ?>

<main class="bg-slate-50 dark:bg-slate-900 min-h-screen py-12 px-4 transition-colors duration-300">
    <!-- Background Decor (Optional consistency match) -->
    <div class="absolute top-0 left-0 w-full h-[300px] bg-brand-blue/5 dark:bg-slate-800/50 z-0 pointer-events-none"></div>

    <div class="container mx-auto max-w-4xl relative z-10">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            
            <article class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl p-8 md:p-12 border border-slate-100 dark:border-slate-700">
                <header class="mb-8 border-b border-slate-100 dark:border-slate-700 pb-8">
                    <h1 class="text-3xl md:text-5xl font-black text-brand-blue dark:text-brand-gold mb-4"><?php the_title(); ?></h1>
                    
                    <div class="flex items-center gap-2 text-sm text-slate-400">
                        <a href="<?php echo home_url(); ?>" class="hover:text-brand-blue transition-colors">Inicio</a>
                        <span>/</span>
                        <span class="text-slate-600 dark:text-slate-300"><?php the_title(); ?></span>
                    </div>
                </header>

                <div class="prose dark:prose-invert prose-lg max-w-none text-slate-600 dark:text-slate-300">
                    <?php the_content(); ?>
                </div>
            </article>

        <?php endwhile; endif; ?>
    </div>
</main>

<?php get_footer(); ?>
