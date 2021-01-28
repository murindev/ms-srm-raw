const mix = require('laravel-mix');

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
const path = require('path');
mix.webpackConfig({
    module: {
        rules: [
            {
                test: /\.less$/,
                use: [
                    'vue-style-loader',
                    'css-loader',
                    'less-loader'
                ]
            }
        ]}
});

mix.autoload({
    // 'jquery': ['$', 'window.jQuery', 'jQuery'],
    // 'vue': ['Vue','window.Vue'],
    // 'moment': ['moment','window.moment'],
});
// mix.js('resources/_js/statistic.lid.js', 'public/ts')

// mix.ts('resources/ts/crm.statistic.ts', 'public/ts');
// mix.js('resources/js/messenger.js', 'public/js');
// mix.js('resources/js/statistic.lid.js', 'public/js');




// mix.js('resources/newjs/_manipulation.js', 'public/js/new');

// mix.js('resources/js/chat.js', 'public/js');

// mix.js('resources/newjs/lids.list.js', 'public/js/new');
// mix.js('resources/newjs/crew.list.js', 'public/js/new');
// mix.js('resources/newjs/lid.js', 'public/js/new');
// mix.js('resources/newjs/logistic.map.js', 'public/js/new');
// mix.js('resources/newjs/map.js', 'public/js/new');
// mix.js('resources/newjs/metrika.js', 'public/js/new');


// mix.js('resources/js/main.logistic.map.js', 'public/js');
// mix.js('resources/js/implement.map.js', 'public/js');
// mix.js('resources/js/get.address.data.js', 'public/js');
// mix.js('resources/js/logistic.routes.map.js', 'public/js');
// mix.js('resources/js/lid.js', 'public/js');

// mix.js('resources/newjs/helpers/implement.day.js', 'public/js/new/helpers');
// mix.js('resources/newjs/Components/utils/mask/index.js', 'public/js/new');
// mix.js('resources/js/calc.pest.js', 'public/js');
// mix.js('resources/js/new.acts.js', 'public/js');
// mix.js('resources/js/logistic.routes.js', 'public/js');
// mix.js('resources/js/pusher.js', 'public/js');
    // .sass('resources/sass/app.scss', 'public/css');

