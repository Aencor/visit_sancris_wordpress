const mix = require('laravel-mix');
const webpack = require('webpack');

require('dotenv').config();

// The theme name should be located in your .env file
const THEME_NAME = process.env.THEME_NAME || 'kikemonk',
	PROXY = process.env.PROXY,
	THEME_DIRECTORY = `wp-content/themes/${THEME_NAME}`,
	ASSETS_DIR = `${THEME_DIRECTORY}/assets`,
	BUILD_DIR = `${ASSETS_DIR}/build`,
	// Add into the array the vendor libraries you're using:
	VENDOR_LIBRARIES = ['jquery'],
	BUILD_MESSAGE = `We are building the ${THEME_NAME} theme`;

mix
	.webpackConfig({
		devtool: 'source-map',
		output: {
			chunkFilename: 'chunks/[name].js',
		},
		stats: {
			children: true,
		},
	})
	.setPublicPath(BUILD_DIR)
	.js(`${ASSETS_DIR}/js/main.js`, BUILD_DIR)
	.js(`${ASSETS_DIR}/js/map-directory.js`, BUILD_DIR)
	.sass(`${ASSETS_DIR}/scss/style.scss`, BUILD_DIR)
	.options({
		processCssUrls: false,
		postCss: [require('tailwindcss'), require('autoprefixer')],
	})
	.sourceMaps(true, 'source-map')
	.browserSync(PROXY);

mix.browserSync({
	proxy: PROXY,
	files: [
		`${BUILD_DIR}/master.css`,
		`${BUILD_DIR}/main.js`,
		`${THEME_DIRECTORY}/**/*.+(html|php)`,
	],
	notify: {
		styles: {
			top: 'auto',
			bottom: '0',
		},
	},
});

mix.webpackConfig({
	watchOptions: {
		ignored: /build/, // Ignora los archivos en la carpeta build
	},
	plugins: [
		new webpack.ProvidePlugin({
			$: 'jquery',
			jQuery: 'jquery',
		}),
	],
});

if (mix.inProduction()) {
	console.log(BUILD_MESSAGE);
	mix
		.minify([
			`${BUILD_DIR}/master.css`,
			`${BUILD_DIR}/manifest.js`,
			`${BUILD_DIR}/vendor.js`,
			`${BUILD_DIR}/main.js`,
		])
		.version([]);
}
