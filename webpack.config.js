const path = require('path');
const webpack = require('webpack');
const ManifestPlugin = require('webpack-manifest-plugin');
// const RemovePlugin = require('remove-files-webpack-plugin');

let ASSET_PATH = '';
module.exports = env => {

    let DOMAIN = JSON.stringify('http://localhost:8000/projecao');
    return {
        entry: {
            main: './view/src/js/init.js',
        },
        output: {
            filename: "[name].[contenthash].js",
            path: path.resolve(__dirname, 'view/src/dist'),
            publicPath: ASSET_PATH + '/view/src/dist/',
        },
        plugins: [
            new webpack.DefinePlugin({
                DOMAIN,
            }),

            new ManifestPlugin(),
        ],
        devtool: false,
        module: {
            rules: [
                {
                    test: /\.js$/,
                    exclude: /nodemodules/,
                    use: {
                        loader: 'babel-loader',
                        options: {
                            presets: ['@babel/preset-env']
                        }
                    }
                },
            ]
        },
        resolve: {
            extensions: ['.js'],
        },
        optimization: {
            minimize: true,
        },
    }
}