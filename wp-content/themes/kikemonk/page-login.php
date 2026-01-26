<?php
/**
 * Template Name: Login / Register
 */

get_header();

// Check if user is already logged in
if (is_user_logged_in()) {
    wp_redirect(home_url());
    exit;
}

// Check for errors
$login_error = isset($_GET['login_error']) ? $_GET['login_error'] : '';
$register_error = isset($_GET['register_error']) ? $_GET['register_error'] : '';
?>

<div class="min-h-screen flex items-center justify-center p-4 bg-slate-50 dark:bg-slate-900 relative overflow-hidden">

    <!-- Blooms -->
    <div class="absolute top-[-20%] left-[-10%] w-[500px] h-[500px] bg-brand-blue/20 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-20%] right-[-10%] w-[500px] h-[500px] bg-brand-gold/20 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-md bg-white dark:bg-slate-800 rounded-3xl shadow-2xl p-8 relative z-10 border border-slate-100 dark:border-slate-700">
        
        <!-- Logo -->
        <div class="flex justify-center mb-8">
            <a href="<?php echo home_url(); ?>">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.jpg" alt="Logo" class="w-20 h-20 rounded-full shadow-lg border-2 border-white dark:border-slate-700">
            </a>
        </div>

        <!-- Tabs -->
        <div class="flex mb-8 bg-slate-100 dark:bg-slate-700 p-1 rounded-xl" id="auth-tabs">
            <button class="flex-1 py-3 rounded-lg text-sm font-bold transition-all text-slate-600 dark:text-slate-300 hover:text-brand-blue active-tab" onclick="switchTab('login')">
                Iniciar Sesión
            </button>
            <button class="flex-1 py-3 rounded-lg text-sm font-bold transition-all text-slate-600 dark:text-slate-300 hover:text-brand-blue" onclick="switchTab('register')">
                Registrarse
            </button>
        </div>

        <!-- Login Form -->
        <div id="login-form" class="auth-section">
            <?php if ($login_error): ?>
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm">
                    ⚠️ Error: Credenciales incorrectas.
                </div>
            <?php endif; ?>

            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="flex flex-col gap-4">
                <input type="hidden" name="action" value="custom_login">
                <?php wp_nonce_field('custom_login_action', 'custom_login_nonce'); ?>
                
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nombre de usuario o Email</label>
                    <input type="text" name="log" class="w-full p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 focus:border-brand-blue focus:ring-0 outline-none transition-all" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Contraseña</label>
                    <input type="password" name="pwd" class="w-full p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 focus:border-brand-blue focus:ring-0 outline-none transition-all" required>
                </div>

                <div class="flex justify-between items-center text-sm">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="rememberme" class="rounded border-slate-300 text-brand-blue focus:ring-0">
                        <span class="text-slate-600 dark:text-slate-400">Recordarme</span>
                    </label>
                    <a href="<?php echo wp_lostpassword_url(); ?>" class="text-brand-blue hover:underline">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="w-full py-4 bg-brand-blue hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                    Entrar
                </button>
            </form>
        </div>

        <!-- Register Form -->
        <div id="register-form" class="auth-section hidden">
            <?php if ($register_error): ?>
                <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg text-sm">
                    <?php 
                        if ($register_error == 'password_mismatch') echo '⚠️ Las contraseñas no coinciden.';
                        elseif ($register_error == 'user_exists') echo '⚠️ El usuario o email ya existe.';
                        else echo '⚠️ Ocurrió un error inesperado.';
                    ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" class="flex flex-col gap-4">
                <input type="hidden" name="action" value="custom_register">
                <?php wp_nonce_field('custom_register_action', 'custom_register_nonce'); ?>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Nombre de usuario</label>
                    <input type="text" name="username" class="w-full p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 focus:border-brand-blue focus:ring-0 outline-none transition-all" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Email</label>
                    <input type="email" name="email" class="w-full p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 focus:border-brand-blue focus:ring-0 outline-none transition-all" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Contraseña</label>
                    <input type="password" name="password" class="w-full p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 focus:border-brand-blue focus:ring-0 outline-none transition-all" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Confirmar Contraseña</label>
                    <input type="password" name="confirm_password" class="w-full p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 focus:border-brand-blue focus:ring-0 outline-none transition-all" required>
                </div>

                <div class="text-xs text-slate-500 text-center">
                    Al registrarte aceptas nuestros <a href="#" class="underline">Términos y condiciones</a>.
                </div>

                <button type="submit" class="w-full py-4 bg-brand-gold hover:bg-yellow-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                    Crear Cuenta
                </button>
            </form>
        </div>

    </div>
</div>

<style>
    .active-tab {
        background-color: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        color: #1e3a8a; /* brand-blue */
    }
    .dark .active-tab {
        background-color: #1e293b; /* slate-800 */
        color: white;
    }
</style>

<script>
    function switchTab(tab) {
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        const buttons = document.querySelectorAll('#auth-tabs button');

        if (tab === 'login') {
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
            buttons[0].classList.add('active-tab');
            buttons[1].classList.remove('active-tab');
        } else {
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
            buttons[0].classList.remove('active-tab');
            buttons[1].classList.add('active-tab');
        }
    }

    // Auto switch if error present
    <?php if ($register_error): ?>
        switchTab('register');
    <?php endif; ?>
</script>

<?php get_footer(); ?>
