define(function(require, exports, module){

	// 引入无缝切换插件
	require("marquee");

	// 引入animate插件
	require("transit");

	var funcArr = {};

	funcArr.init = function(){

		// 头部搜索框点击事件
		funcArr.searchFunc();

		// 导航panel显示
		funcArr.navBoxPanel();

		// 侧边导航浮动显示效果图
		funcArr.lefNavFunc();

		// 第一屏图片切换按钮显示与隐藏
		$("#slidePic").hover(function() {
			$(".slidePicBtn").fadeIn(500);
		}, function() {
			$(".slidePicBtn").fadeOut(500);
		});

		// 图片切换执行函数
		funcArr.slidePic();

		// 动态加载推荐icon图标
		funcArr.recomendFunc();

		// 最新公开课执行动画
		$(".lesson-item").hover(function(){
			$(this).find(".lesson-pos").fadeToggle(500);
		});

		// 职业路径
		$(".work-road-item").hover(function(){
			$(this).toggleClass('active');
		});

		// 知识体系图动画效果

		funcArr.systemFunc();


		// 合作院校，媒体报道，企业合作等箭头显示隐藏的公共样式
		this.showArrow($("#school"), $("#school .bannerBtn"));
		this.showArrow($("#company"), $("#company .bannerBtn"));
		this.showArrow($("#media"), $("#media .bannerBtn"));

		// 标题问好显示动画效果
		$(".head .ask").hover(function(){
			$(this).find('span').fadeToggle();
		});

		// 精品课程动画效果
		$(".import-item").hover(function(){

			var thisObj = $(this);
			thisObj.toggleClass("import-border");
			thisObj.find('.import-pos').fadeToggle();
		});

		// wiki动画效果
		funcArr.wikiFunc();

		// 热门推荐部分的动画以及tab切换
		funcArr.hotFunc();

		// 回到顶部
		funcArr.rollBack();

		// 当窗口发生变化时重新定位回到顶部元素
		$(window).resize(function(){
			funcArr.rollBack();
		});



		$(window).scroll(function(){

			if($(this).scrollTop() > 0) {
				$("#goTop").find(".go").css("display", "block");
			}
		});
	};

	funcArr.hotFunc = function(){

		// 当页面加载的时候，热门推荐第一部分内容显示
		$(".all-contents").children().first().show().siblings("div").hide();

		// 热门推荐
		$(".all-head ul li").each(function(index){

			var thisObj = $(this);

			thisObj.hover(function(){

				thisObj.addClass('this-select').siblings('li').removeClass('this-select');
				$(".all-contents").children().eq(index).show().siblings().hide();

			});
		});

		// 当鼠标浮动到每一项上的动画效果
		$(".all-les-con .all-les-item").each(function(index){

			var thisObj = $(this);

			thisObj.hover(function(){
				
				$(this).find(".all-pos").fadeIn();
				$(this).find(".all-pos").find(".all-intro").slideDown();

			}, function(){

				
				$(this).find(".all-pos").fadeOut();
				$(this).find(".all-pos").find(".all-intro").slideUp();
				
			});
		});
	};

	funcArr.wikiFunc = function(){

		// 鼠标浮动到书上面动画效果
		$(".wiki-img-wrap").hover(function(){

			$(this).parents(".wiki-book").find("i").show();

			$(this).transition({
				rotateY:-25
			}, 1000);

		},function(){

			$(this).parents(".wiki-book").find("i").hide();

			$(this).transition({
				rotateY:0
			}, 1000);
		});
	};

	funcArr.systemFunc = function(){

		// 知识体系图动画效果
		$(".system-item").hover(function(){

		   	$(this).find(".sys-desc").transition({
		   		rotateY: -270,
		   	});

		   	$(this).find(".system-pos").transition({
	   			rotateY:-360,
	   			zIndex:9
	   		});

		   	
		}, function(){

			$(this).find(".sys-desc").transition({
		   		rotateY: -360,
		   	});

		   	$(this).find(".system-pos").transition({
	   			rotateY:-270,
	   			zIndex:-1
	   		});
		});
	};

	funcArr.showArrow = function(thisObj, thisBtn){

		thisObj.hover(function(){
			thisBtn.fadeToggle();
		});
	};

	funcArr.searchFunc = function(){

		$("#search form .searchInput").on('focus', function(event) {
			
			var thisObj = $(this);
			thisObj.siblings('.hotSearch').hide();
			thisObj.siblings('.subBtn').addClass('searchBtnHover');
		});

		$("#search form .searchInput").on('blur', function(event) {
			
			var thisObj = $(this);
			thisObj.siblings('.hotSearch').show();
			thisObj.siblings('.subBtn').removeClass('searchBtnHover');
		});
	};

	funcArr.navBoxPanel = function(){

		var panelBox = $(".navBoxPanel");
		var panelDiv = $(".navBoxPanel div");

		// 鼠标浮上导航的时候显示面板
		$(".navBox a").each(function(index, el) {
			
			var thisObj = $(this);

			var num = index - 1;
			if (num < 0 ) {
				num = 0;
			}

			var panel = panelDiv.eq(num);
			

			thisObj.hover(function() {

				panelBox.show();
				panel.addClass("navBoxPanelBg");
				panel.siblings("div").removeClass("navBoxPanelBg");
				panel.find("i").show();
				panel.siblings('div').find("i").hide();

			}, function() {
				
				panelBox.hide();
			});
		});

		panelBox.hover(function(){

			$(this).show();

		},function(){

			$(this).hide();
		});

		// 鼠标在导航每个模块移动的时候面板显示设置
		panelDiv.each(function(index, el) {

			var thisObj = $(this);

			thisObj.hover(function(){

				thisObj.addClass("navBoxPanelBg");
				thisObj.siblings("div").removeClass("navBoxPanelBg");
				thisObj.find("i").show();
				thisObj.siblings('div').find("i").hide();

			});

		});
	};

	funcArr.lefNavFunc = function(){

		var navListDiv = $(".navList > div");

		$(".leftNav ul li").each(function(index){

			var thisObj = $(this);
			var curNavList = navListDiv.eq(index);

			funcArr.publicLeftNav(thisObj, curNavList);
		});

		navListDiv.each(function(index, el) {
			
			var thisObj = $(this);

			funcArr.publicLeftNav(thisObj, thisObj);
		});
	};

	funcArr.publicLeftNav = function(thisObj, curNavList){

		// 左侧导航公共函数
		var navListDiv = $(".navList > div");

		thisObj.hover(function() {
				
			curNavList.show();
			curNavList.siblings("div").hide();
			thisObj.find("a").addClass("aHover");

		}, function() {
			thisObj.find("a").removeClass("aHover");
			navListDiv.hide();
		});
	};

	funcArr.slidePic = function(){

		// 图片切换
		$('#slidePic').jCarouselLite({

	                    auto: 0,

	                    speed: 500,

	                    btnPrev: '.slide-left',

	                    btnNext: '.slide-right',

	                    visible: 3

	                });

		// 企业合作 图片切换
		$('#company').jCarouselLite({

	                    auto: 0,

	                    speed: 500,

	                    btnPrev: '#company-left',

	                    btnNext: '#company-right',

	                    visible: 6

	                });

		// 院校合作图片切换
		$('#school').jCarouselLite({

	                    auto: 0,

	                    speed: 500,

	                    btnPrev: '#school-left',

	                    btnNext: '#school-right',

	                    visible: 7

	                });

		// 媒体合作图片切换
		$('#media').jCarouselLite({

	                    auto: 0,

	                    speed: 500,

	                    btnPrev: '#media-left',

	                    btnNext: '#media-right',

	                    visible: 6

	                });

	
	};

	funcArr.recomendFunc = function(){

		// 动态加载推荐icon图标
		$(".first-recommend li").each(function(index){

			var thisObj = $(this);
			var away = 29*index * -1 +1;
			var position = "-3px "+away+"px";

			thisObj.find(".recom-icon").css("background-position", position);

			thisObj.hover(function(){
				thisObj.find(".recom-icon").css("background-position", "-40px "+away+"px");
			},function(){
				thisObj.find(".recom-icon").css("background-position", position);
			});

		});

		// 动态显示层
		$(".first-recommend > ul li").each(function(index){

			if (index > 3) {
				return false;
			}

			var thisObj = $(this);

			thisObj.hover(function(){
				$(".first-recommend-pos").show();
				$(".first-recommend-pos > ul > li").eq(index).addClass("this-pos").siblings("li").removeClass('this-pos');
				$(".first-recommend-pos > div.pos-show > div").eq(index).show().siblings("div").hide();

			});
		});

		$(".first-recommend-pos").hover(function(){
			$(this).show();
		},function(){
			$(this).hide();
		});

		$(".first-recommend-pos").find(".pos-head >li").each(function(index){
			var thisObj = $(this);
			thisObj.hover(function(){
				$(".first-recommend-pos > div.pos-show > div").eq(index).show().siblings("div").hide();
				thisObj.addClass("this-pos").siblings("li").removeClass('this-pos');
			})
		});
	};

	// 回到顶部
	funcArr.rollBack = function(){

		// 给元素定位
		var content = $(".content");
		var goTop = $("#goTop");
		var  marginLeft  = content.offset().left + content.width() + 10;
		goTop.css("left", marginLeft + "px");

		goTop.on('click', function(event) {
			$("html, body").animate({
				scrollTop:0
			})
		});
	};

	module.exports = funcArr;


});