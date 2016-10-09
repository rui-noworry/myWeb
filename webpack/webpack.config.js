module.exports = {
	entry: "./entry.js",
	output: {
		path: __dirname, // 输出文件所在文件夹
		filename: "bundle.js" // 输出的文件名
	},
	module: {
		loaders: [
			{
				test: /\.css$/,
				loader: "style!css" // style 和 css 加载器
			}
		]

	}
};
