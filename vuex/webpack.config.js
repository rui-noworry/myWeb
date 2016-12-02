/**
 * Created by Administrator on 2016/10/24 0024.
 */
module.exports = {
    entry:'./main.js',
    output:{
        path:__dirname,
        filename:'build.js'
    },
    module:{
        loaders:[
            {
                test:/\.vue$/,
                loader:'vue'
            },
            {
                test:/\.js$/,
                loader:'babel',
                exclude:/node_modules/
            }
        ]
    },
    babel:{
        presets:['es2015'],
        plugins:['transform-runtime']
    }
}