<?php
  $widgets = get_field('widgets', 'option');
?>
   
    <!-- Footer Section -->
    <footer class="bg-slate-50 dark:bg-slate-900 p-8 border-t border-slate-200 dark:border-slate-700 text-xs text-slate-500">
        <div class="container mx-auto flex flex-col gap-4 text-center">
            <div class="flex justify-center gap-6">
                <a href="#" class="hover:text-brand-blue dark:hover:text-brand-gold transition-colors">Política de Privacidad</a>
                <span class="text-slate-300 dark:text-slate-600">|</span>
                <a href="#" class="hover:text-brand-blue dark:hover:text-brand-gold transition-colors">Términos de Uso</a>
            </div>
            <div class="flex justify-center gap-4 mt-4">
                <?php 
                $socials = array(
                    'facebook' => array('url' => get_field('social_facebook', 'option'), 'icon' => 'fab fa-facebook-f'),
                    'instagram' => array('url' => get_field('social_instagram', 'option'), 'icon' => 'fab fa-instagram'),
                    'tiktok' => array('url' => get_field('social_tiktok', 'option'), 'icon' => 'fab fa-tiktok'),
                    'twitter' => array('url' => get_field('social_twitter', 'option'), 'icon' => 'fab fa-x-twitter')
                );

                foreach($socials as $key => $social): 
                    if($social['url']):
                ?>
                 <a href="<?php echo esc_url($social['url']); ?>" target="_blank" class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center hover:border-brand-blue dark:hover:border-brand-gold hover:text-brand-blue dark:hover:text-brand-gold transition-all text-lg">
                    <i class="<?php echo $social['icon']; ?>"></i>
                 </a>
                <?php 
                    endif; 
                endforeach; 
                ?>
            </div>
            <p class="mt-4">© <?php echo date('Y'); ?> Visita San Cris. Directorio Turístico.</p>
        </div>
    </footer>
  
    <?php wp_footer(); ?>
  </body>
</html>
