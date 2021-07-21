const path = require('path')
const UglifyJsPlugin = require('uglifyjs-webpack-plugin')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin')
const VueLoaderPlugin = require('vue-loader/lib/plugin')
const autoprefixer = require('autoprefixer');
const webpack = require('webpack');




let env = process.env.NODE_ENV
let isDev = env === 'development'

const WEBPACK_CONFIG = {
    mode: env,
    entry: {
        'crm.statistic': './resources/ts/crm.statistic.ts',
        'crm.lid.list': './resources/ts/crm.lid.list.ts',
        'crm.lid': './resources/ts/crm.lid.ts',
        'crm.funnel.personal': './resources/ts/crm.funnel.personal.ts',
        'app': './resources/js/app.js',
    },
    output: {
        publicPath: './public',
        path: path.resolve(__dirname, 'public'),
        filename: 'ts/[name].js',
        chunkFilename: 'ts/chunks/[name].js'
    },
    module: {
        rules: [
            {
                test: /\.tsx?$/,
                loader: 'ts-loader',
                options: {
                    appendTsSuffixTo: [/\.vue$/]
                },
                exclude: /node_modules/,
            },
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },
            {
                test: /\.scss$/,
                use: [
                    'style-loader',
                    // MiniCssExtractPlugin.loader,
                    'css-loader',
                    'postcss-loader',
                    'sass-loader'
                ],
                exclude: /node_modules/,
            }
        ],
    },
    plugins: [
        // new MiniCssExtractPlugin({
        //     filename: 'css/app2.css'
        // }),
        new VueLoaderPlugin(),
        new webpack.LoaderOptionsPlugin({
            options: {
                postcss: [
                    autoprefixer()
                ]
            }
        })
    ],
    resolve: {
        extensions: ['.js', '.jsx', '.vue', '.ts', '.tsx'],
        alias: {
            vue$: 'vue/dist/vue.esm.js',
        },
    },
    optimization: {
        splitChunks: {
            chunks: 'async',
            minSize: 30000,
            maxSize: 0,
            minChunks: 1,
            maxAsyncRequests: 5,
            maxInitialRequests: 3,
            automaticNameDelimiter: '~',
            name: true,
            cacheGroups: {
                vendors: {
                    test: /[\\/]node_modules[\\/]/,
                    priority: -10
                },
                default: {
                    minChunks: 2,
                    priority: -20,
                    reuseExistingChunk: true
                }
            }
        }
    }
}

if (!isDev) {
    WEBPACK_CONFIG.optimization = {
        minimizer: [
            new UglifyJsPlugin({
                cache: true,
                parallel: true,
                sourceMap: true
            }),
            new OptimizeCSSAssetsPlugin({})
        ]
    }

    WEBPACK_CONFIG.plugins.push(
        new webpack.DefinePlugin({
            'process.env': {
                NODE_ENV: 'production'
            }
        })
    )
}

module.exports = WEBPACK_CONFIG
