const config = require('./webpack.config');
const mix = require('laravel-mix');
require('laravel-mix-eslint');

function resolve(dir) {
    return path.join(
        __dirname,
        '/resources/js',
        dir
    );
}

Mix.listen('configReady', webpackConfig => {
    // Add "svg" to image loader test
    const imageLoaderConfig = webpackConfig.module.rules.find(
        rule =>
            String(rule.test) ===
            String(/(\.(png|jpe?g|gif|webp)$|^((?!font).)*\.svg$)/)
    );
    imageLoaderConfig.exclude = resolve('icons');
});

mix.webpackConfig(config);

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */
mix.js('resources/js/app.js', 'public/js');

const tailwindcss = require('tailwindcss')

mix
    .js('resources/js/app.js', 'public/dist/js')
    .extract([
        'vue',
        'axios',
        'vuex',
        'vue-router',
        'vue-i18n',
        'element-ui',
        'echarts',
        'highlight.js',
        'sortablejs',
        'dropzone',
        'xlsx',
        'tui-editor',
        'codemirror',
    ])
    .options({
        processCssUrls: false,
        postCss: [ tailwindcss('tailwind.config.js') ],
    })
    .sass('resources/js/styles/index.scss', 'public/dist/css/app.css', {
        implementation: require('node-sass'),
    });

if (mix.inProduction()) {
    // mix.version();
    require('laravel-mix-versionhash');
    mix.versionHash();
} else {
    if (process.env.VUE_USE_ESLINT === 'true') {
        mix.eslint();
    }
    // Development settings
    mix
        .sourceMaps()
        .webpackConfig({
            devtool: 'cheap-eval-source-map', // Fastest for development
        });
}
