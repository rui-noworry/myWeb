var webpack = require('webpack');
var path = require('path');
var ExtractTextPlugin = require('extract-text-webpack-plugin');
var HtmlWebpackPlugin = require('html-webpack-plugin');

module.exports = {
    entry: {
        app: './app.js',
        app2: './app2.js'
    },
    // entry: './app.js',
    output:{
        path:'./assets',
        filename:'[name].[hash].bundle.js',
        // publicPath:'http://rui-noworry.github.com/assets'
    },
    module:{
        loaders :[
            {
                test:/\.js|jsx$/,
                loader:'babel',
                query:{
                    presets:['es2015']
                }
            },
            // {test:/\.css$/, loader:"style!css"}
            {test:/\.css$/,loader:ExtractTextPlugin.extract("style-loader", "css-loader")}
        ]
    },
    plugins:[
        new webpack.optimize.CommonsChunkPlugin('common.js'),
        new ExtractTextPlugin("style.css",{
            allChunks:true
        }),
        new HtmlWebpackPlugin({
            filename:'./index-release.html',
            template: './index.html',
            // template: path.resolve('index.template'),
            inject: 'body'
        })
    ]
};