/**
 * Created by Administrator on 2016/10/14 0014.
 */
var webpack = require("webpack");
//import webpack from "webpack"; 注意配置文件中不能用import，其他文件可以
var HtmlWebpackPlugin = require('html-webpack-plugin');

module.exports = {
    devtools:'eval-source-map',// 配置生成source-map，选择合适的选项，
    entry: __dirname + "/app/main.js", // 唯一入口文件
    output: {
        path: __dirname + "/build", // 打包后文件输出目录
        filename: "bundle.js" // 打包后，输出的文件名
    },
    module:{ // 注意这是module而不是modules
        loaders:[
            {
                test:/\.json$/,
                loader: "json"
            },
            {
                test:/\.js$/,
                loader:"babel",
                exclude: '/node_modules/',
                query: {
                    presets: ['es2015','react']
                }
            },
            {
               test:/\.css$/,
                loader:"style!css!postcss" // 添加对样式表的处理
            }
        ]
    },
    postcss:[
          require("autoprefixer") // 调用自动添加前缀的插件
    ],
    plugins:[
        new HtmlWebpackPlugin({
            template: __dirname+"/app/index.tmpl.html"
        })
    ],
    devServer: {
        contentBase:"./public", // 启动public下的文件
        colors:true,// 终端输出结果为彩色
        historyApiFallback:true,// 不跳转
        inline:true// 实时刷新
    }
}