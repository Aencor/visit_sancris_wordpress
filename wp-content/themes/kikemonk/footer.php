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
                 <a href="#" class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center hover:border-brand-blue dark:hover:border-brand-gold hover:text-brand-blue dark:hover:text-brand-gold transition-all text-lg"><i class="fab fa-facebook-f"></i></a>
                 <a href="#" class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center hover:border-brand-blue dark:hover:border-brand-gold hover:text-brand-blue dark:hover:text-brand-gold transition-all text-lg"><i class="fab fa-instagram"></i></a>
                 <a href="#" class="w-10 h-10 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center hover:border-brand-blue dark:hover:border-brand-gold hover:text-brand-blue dark:hover:text-brand-gold transition-all text-lg"><i class="fab fa-x-twitter"></i></a>
            </div>
            <p class="mt-4">© <?php echo date('Y'); ?> Visita San Cris. Directorio Turístico.</p>
        </div>
    </footer>
  
    <?php wp_footer(); ?>
  </body>
</html>
