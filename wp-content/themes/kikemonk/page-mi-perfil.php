<?php
/**
 * Template Name: Mi Perfil
 */

if (!is_user_logged_in()) {
    wp_redirect(home_url('/login'));
    exit;
}

get_header();
get_template_part('template-parts/header-custom');

$current_user = wp_get_current_user();
$updated = isset($_GET['profile_updated']) && $_GET['profile_updated'] == 'true';
$error = isset($_GET['profile_error']);

// Get Avatar
$avatar_id = get_user_meta($current_user->ID, 'custom_avatar', true);
$avatar_url = $avatar_id ? wp_get_attachment_image_url($avatar_id, 'thumbnail') : '';
?>

<div class="min-h-screen bg-slate-50 dark:bg-slate-900 py-12 px-4 relative overflow-hidden">
    
    <!-- Background Decor -->
    <div class="absolute top-0 left-0 w-full h-[300px] bg-brand-blue/90 z-0"></div>
    <div class="absolute top-[10%] left-[10%] w-64 h-64 bg-white/10 rounded-full blur-3xl z-0"></div>
    <div class="absolute top-[20%] right-[10%] w-48 h-48 bg-brand-gold/20 rounded-full blur-3xl z-0"></div>

    <div class="container mx-auto relative z-10">
        
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar / Profile Card -->
            <div class="lg:w-1/3">
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-xl p-8 border border-slate-100 dark:border-slate-700 sticky top-24">
                    <div class="text-center">
                        <div class="w-32 h-32 mx-auto bg-slate-200 dark:bg-slate-700 rounded-full flex items-center justify-center text-4xl mb-4 border-4 border-white dark:border-slate-600 shadow-lg overflow-hidden relative group">
                            <?php if ($avatar_url): ?>
                                <img src="<?php echo $avatar_url; ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                                <?php echo strtoupper(substr($current_user->user_login, 0, 1)); ?>
                            <?php endif; ?>
                            
                            <!-- Overlay for upload hint (optional) -->
                            <div class="absolute inset-0 bg-black/50 hidden group-hover:flex items-center justify-center text-white text-xs font-bold pointer-events-none">
                                Cambiar foto
                            </div>
                        </div>
                        <h1 class="text-2xl font-bold text-slate-800 dark:text-white mb-1" id="profile-display-name"><?php echo esc_html($current_user->display_name); ?></h1>
                        <?php if($current_user->first_name || $current_user->last_name): ?>
                        <p class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-1" id="profile-full-name"><?php echo esc_html($current_user->first_name . ' ' . $current_user->last_name); ?></p>
                        <?php endif; ?>
                        <p class="text-slate-500 dark:text-slate-400 text-sm mb-6"><?php echo esc_html($current_user->user_email); ?></p>
                    </div>

                    <div class="border-t border-slate-100 dark:border-slate-700 pt-6">
                        <h3 class="font-bold text-slate-400 text-xs uppercase mb-4">Mis Datos</h3>
                        
                        <?php if ($updated): ?>
                            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm text-center">
                                ✅ Perfil actualizado correctamente.
                            </div>
                        <?php endif; ?>

                        <div id="profile-message" class="hidden mb-4 p-3 rounded-lg text-sm text-center"></div>

                        <form id="profile-update-form" class="space-y-4">
                            <input type="hidden" name="action" value="update_profile">
                            <?php wp_nonce_field('profile_update_action', 'profile_update_nonce'); ?>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Foto de Perfil</label>
                                <input type="file" name="profile_photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-brand-blue/10 file:text-brand-blue hover:file:bg-brand-blue/20">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Nombre</label>
                                <input type="text" name="first_name" value="<?php echo esc_attr($current_user->first_name); ?>" class="w-full p-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Apellido</label>
                                <input type="text" name="last_name" value="<?php echo esc_attr($current_user->last_name); ?>" class="w-full p-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 text-sm">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 mb-1">Email</label>
                                <input type="email" name="email" value="<?php echo esc_attr($current_user->user_email); ?>" class="w-full p-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 text-sm">
                            </div>

                            <button type="submit" class="w-full py-3 bg-brand-blue hover:bg-blue-700 text-white font-bold rounded-xl transition-colors text-sm">
                                Guardar Cambios
                            </button>
                        </form>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-700">
                        <a href="<?php echo wp_logout_url(home_url()); ?>" class="text-red-500 hover:text-red-700 text-sm font-bold flex items-center justify-center gap-2">
                            <span>🚪</span> Cerrar Sesión
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:w-2/3 space-y-8">
                
                <!-- Favorites Places -->
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md rounded-3xl p-8 border border-white/20 shadow-xl">
                    <h2 class="text-3xl font-black text-brand-blue dark:text-white mb-8 flex items-center gap-3">
                        <span>❤️</span> Mis Lugares Favoritos
                    </h2>

                    <!-- Favorites Grid -->
                    <div id="favorites-grid" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php 
                        $favorites = get_user_meta($current_user->ID, 'user_favorites', true);
                        $has_favs = false;
                        
                        // Filter places only
                        if ($favorites && is_array($favorites) && !empty($favorites)):
                            $args = array(
                                'post_type' => 'lugares',
                                'post__in' => $favorites, 
                                'posts_per_page' => -1
                            );
                            $query = new WP_Query($args);
                            if ($query->have_posts()):
                                $has_favs = true;
                                while ($query->have_posts()): $query->the_post();
                                $category = get_field('categoria') ?: 'General';
                        ?>
                                <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all group relative border border-slate-100 dark:border-slate-700">
                                    <div class="aspect-video rounded-xl overflow-hidden mb-4">
                                        <?php if (has_post_thumbnail()): ?>
                                            <?php the_post_thumbnail('medium', ['class' => 'w-full h-full object-cover group-hover:scale-110 transition-transform']); ?>
                                        <?php else: ?>
                                            <div class="w-full h-full bg-slate-200 dark:bg-slate-700 animate-pulse"></div>
                                        <?php endif; ?>
                                    </div>
                                    <span class="absolute top-6 left-6 px-2 py-1 rounded bg-brand-gold text-white text-[10px] font-bold uppercase shadow-sm">
                                        <?php echo $category; ?>
                                    </span>
                                    <h3 class="font-bold text-lg text-brand-blue dark:text-brand-gold mb-1">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-3"><?php echo wp_trim_words(get_the_excerpt(), 10); ?></p>
                                    <a href="<?php the_permalink(); ?>" class="text-brand-blue dark:text-white font-bold text-xs hover:underline">Ver detalles →</a>
                                </div>
                        <?php 
                                endwhile;
                            endif;
                            wp_reset_postdata();
                        endif;
                        
                        if (!$has_favs):
                            echo '<div class="col-span-full text-center text-slate-500 dark:text-white/70 py-10">No has guardado lugares favoritos aún.</div>';
                        endif;
                        ?>
                    </div>
                </div>

                <!-- Favorite Events -->
                <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-md rounded-3xl p-8 border border-white/20 shadow-xl">
                    <h2 class="text-3xl font-black text-brand-blue dark:text-white mb-8 flex items-center gap-3">
                        <span>📅</span> Mis Eventos
                    </h2>

                    <!-- Events Grid -->
                    <div id="events-grid" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php 
                        // Assuming we share the same meta 'user_favorites' for both types.
                        // Ideally separate them, but if IDs are unique across post types (which they are), we can use same meta.
                        // Just need to query 'post_type' => 'eventos'
                        
                        $has_events = false;
                        if ($favorites && is_array($favorites) && !empty($favorites)):
                            $args_events = array(
                                'post_type' => 'eventos',
                                'post__in' => $favorites, 
                                'posts_per_page' => -1
                            );
                            $query_events = new WP_Query($args_events);
                            if ($query_events->have_posts()):
                                $has_events = true;
                                while ($query_events->have_posts()): $query_events->the_post();
                                $fecha = get_field('fecha') ?: 'Próx';
                                $date_obj = date_create($fecha);
                                $date_day = $date_obj ? date_format($date_obj, 'd') : '??';
                                $date_month = $date_obj ? date_i18n('M', strtotime($fecha)) : '???';
                        ?>
                                <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 shadow-lg hover:shadow-xl transition-all group flex gap-4">
                                    <!-- Date Badge -->
                                    <div class="flex-shrink-0 w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-xl flex flex-col items-center justify-center border border-slate-200 dark:border-slate-600">
                                        <span class="text-xs font-bold text-brand-gold uppercase leading-none mb-1"><?php echo $date_month; ?></span>
                                        <span class="text-2xl font-black text-slate-800 dark:text-white leading-none"><?php echo $date_day; ?></span>
                                    </div>
                                    
                                    <div class="flex-grow min-w-0">
                                        <h3 class="font-bold text-lg text-brand-blue dark:text-brand-gold mb-1 leading-tight">
                                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        </h3>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mb-2 truncate"><?php echo get_field('ubicacion') ?: 'San Cristóbal'; ?></p>
                                        <a href="<?php the_permalink(); ?>" class="text-brand-blue dark:text-white font-bold text-xs hover:underline">Ver evento →</a>
                                    </div>
                                </div>
                        <?php 
                                endwhile;
                            endif;
                            wp_reset_postdata();
                        endif;

                        if (!$has_events):
                            echo '<div class="col-span-full text-center text-white/70 py-10">No has guardado eventos aún.</div>';
                        endif;
                        ?>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#profile-update-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.text();
        const messageBox = $('#profile-message');
        
        // Show loading state
        submitBtn.prop('disabled', true).text('Guardando...');
        messageBox.addClass('hidden').removeClass('bg-green-100 text-green-700 bg-red-100 text-red-700');

        const formData = new FormData(this);
        // Ensure action is correct
        if (!formData.has('action')) {
            formData.append('action', 'update_profile');
        }

        $.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    messageBox.text(response.data.message).addClass('bg-green-100 text-green-700').removeClass('hidden');
                    
                    // Update Avatar if changed
                    if (response.data.avatar_url) {
                        $('.w-32.h-32 img').attr('src', response.data.avatar_url);
                    }

                    // Update Names
                    if (response.data.full_name) {
                        let nameEl = $('#profile-full-name');
                        if (nameEl.length) {
                             nameEl.text(response.data.full_name);
                        } else {
                             // Create if not exists (rare case if started empty)
                             $('#profile-display-name').after('<p class="text-sm font-bold text-slate-700 dark:text-slate-300 mb-1" id="profile-full-name">' + response.data.full_name + '</p>');
                        }
                    }
                    if (response.data.display_name) {
                        $('#profile-display-name').text(response.data.display_name);
                    }

                    // Optional: Reset file input
                    form.find('input[type="file"]').val('');

                } else {
                    messageBox.text(response.data.message || 'Error al actualizar').addClass('bg-red-100 text-red-700').removeClass('hidden');
                }
            },
            error: function() {
                messageBox.text('Error de conexión').addClass('bg-red-100 text-red-700').removeClass('hidden');
            },
            complete: function() {
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });
});
</script>

<?php get_footer(); ?>
