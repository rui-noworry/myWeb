define(function(require, exports, moudle){

	require("jquery");

	var index = require("index");
	index.init();

	// 图片自动轮播
	var scrollPic = require("scroll");

	scrollPic.bannerObj();

});