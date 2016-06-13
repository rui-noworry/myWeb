define(function(require, exports, module){

	var obj = {};
	var listNum = 0;
	

	obj.init = function(){

		// 新闻向上滚动
		setInterval(this.newScroll, 2000);

		// 栏目点击显示不同的内容
		obj.category();

		// 页面加载的时候获取推荐的内容
		obj.ajaxFunc(1, 0);

		// 当点击更多的时候
		obj.moreCon();

		// 回到顶部
		obj.topFunc();

	};

	obj.topFunc = function() {

		// 回到顶部
		$(window).scroll(function(){
			$(".goTop").show();
		})

		$(".goTop").on('click', function(){

			$("html, body").animate({
				'scrollTop' : 0
			});
		});
	};

	obj.moreCon = function(){

		// 当点击更多的时候
		$(document).on('click', '.more-news', function(event) {

			// 获取当前栏目
			$("header nav a").each(function(index){

				var thisObj = $(this);

				if (thisObj.hasClass("active")) {
					
					if (index == 1) {
						listNum = $("#hundred").children("div").length;

					} else if (index == 3) {
						listNum = $(".pics").children("div").length;
					} else {
						listNum = $(".content > div").eq(index).find(".news-list").children("div").length;
					}
					
					
					obj.ajaxFunc(index+1, listNum);
				}
			});

		});
	}

	obj.category = function(){

		$("header nav a").each(function(index){

			var thisObj = $(this);

			// 默认显示推荐的内容
			$(".content > div").first().show();

			thisObj.on("click", function(event) {
				listNum = 0;
				thisObj.addClass('active').siblings('a').removeClass('active');

				$(".content > div").eq(index).show().siblings("div").hide();

				// 重复点击的时候不能连续追加内容  如果点击更多不执行这部分内容
				if (!listNum) {

					var listCon;

					if (index == 1) {
						listCon = $("#hundred").html();
					} else if (index == 3) {
						listCon = $(".pics").html();
					} else {
						listCon = $(".content > div").eq(index).find(".news-list").html();
					}
					
					
					obj.ajaxFunc(index+1, 0, 1);
					
				}
			});
		});
	};

	obj.getLocalTime = function(nS){
		 return new Date(parseInt(nS) * 1000).toLocaleString().replace(/:\d{1,2}$/,' ');   
	};



	// 获取网络数据
	obj.ajaxFunc = function(c_id, num, cate = 0){

		// ajax获取数据列表
		$.get('/category/' + c_id, '', function(data){

			// 如果没有数据
			if (data.slice(-4) == "null") {

				// 获取当前栏目
				$("header nav a").each(function(index){

					var thisObj = $(this);

					if (thisObj.hasClass("active")) {
						
						if (index == 1) {
							var spanDiv = $("#hundred").children("span");
						} else if (index == 3) {
							var spanDiv = $(".pics").children("span");
						} else {
							var spanDiv = $(".content > div").eq(index).find(".news-list").children("span");
						}

						spanDiv.text('没有更多显示内容')
					}
				});

				return false;
			}

			var arr = data;
			
			var html = "";
			

			for (var i = 0; i < arr.length; i++) {

				var img;
				if (arr[i]['n_pic']) {
					img = "<div class='img-wrap'><img src='uploads/"+arr[i]['n_pic']+"'></div>";
				} else {
					img = "";
				}

				if (arr[i]['n_date']) {
					arr[i]['n_date'] = obj.getLocalTime(arr[i]['n_date']);
				}
				

				if (c_id != 4) {
					html += "<div class='list'>"+img+"<div class='img-con'><h4>"+arr[i]['n_title']+"</h4><p class='desc'>"+arr[i]['n_desc']+"</p><time>"+arr[i]['n_date']+"</time></div></div>";
				} else {
					html += "<div class='pic-list'><div class='pic-img-wrap'><img src='uploads/"+arr[i]['n_pic']+"'></div><div class='pic-desc'><div class='pic-title'><h4>"+arr[i]['n_title']+"</h4></div><div class='pic-zan'><em></em><span>521</span></div></div></div>";
				}
			}

			// html += "<span class='more-news'>更多新闻</span>";

			if (cate == 1) {

				switch(true){
					case c_id == 1:
						$("#latest .news-list").children("span").remove();
						$("#latest .news-list").html(html);
						break;
					case c_id == 2:
						$("#hundred").children("span").remove();
						$("#hundred").html(html);
						break;
					case c_id == 3:
						$("#local .news-list").children("span").remove();
						$("#local .news-list").html(html);
						break;
					case c_id == 4:
						 $(".pics").children("span").remove();
						 $(".pics").html(html);
						 break;
					case c_id == 5:
						$("#enjoy .news-list").children("span").remove();
						$("#enjoy .news-list").html(html);
						break;
					case c_id == 6:
						$("#society .news-list").children("span").remove();
						$("#society .news-list").html(html);
						break;
				}

			} else {

				switch(true){
					case c_id == 1:
						$("#latest .news-list").children("span").remove();
						$("#latest .news-list").append(html);
						break;
					case c_id == 2:
						$("#hundred").children("span").remove();
						$("#hundred").append(html);
						break;
					case c_id == 3:
						$("#local .news-list").children("span").remove();
						$("#local .news-list").append(html);
						break;
					case c_id == 4:
						 $(".pics").children("span").remove();
						 $(".pics").append(html);
						 break;
					case c_id == 5:
						$("#enjoy .news-list").children("span").remove();
						$("#enjoy .news-list").append(html);
						break;
					case c_id == 6:
						$("#society .news-list").children("span").remove();
						$("#society .news-list").append(html);
						break;
				}

			}

		});
	};

	// 向上滚动新闻的函数
	obj.newScroll = function(){

		var offset = parseInt($(".news-scroll li").css("line-height"))* -1;

		$(".news-scroll").animate({

			top : offset+"px"

		},1000,function(){
			var firstLi = $(this).find("li").first();
			$(this).find("ul").append(firstLi);
			$(this).css("top","0px");
		});
	};



	module.exports = obj;
});