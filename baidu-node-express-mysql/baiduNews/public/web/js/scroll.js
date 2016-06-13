define(function(require, exports, module){

	var scrollPic = {};

	scrollPic.bannerObj = function(){


		setInterval(function(){
			// 推荐的图片轮换
			scrollPic.nextscroll("#latest");

			// 本地的图片轮换
			scrollPic.nextscroll("#local");

			// 娱乐的图片轮换
			scrollPic.nextscroll("#enjoy");

			// 社会的图片轮换
			scrollPic.nextscroll("#society");
			
		}, 2000);

	};

	// 图片轮换公用方法
	scrollPic.nextscroll = function(thisImg){
		// 下一个
	             var vcon = $(thisImg).find(".img-scroll-wrap");
                            var offset = (vcon.find("li").width() * -1);

                            vcon.animate({
                                left: offset
                            }, "slow", function() {

                                var firstItem = vcon.find("li").first();
                                vcon.find("ul").append(firstItem);
                                $(this).css("left", "0px");

                               scrollPic.circle($(this))

                            });
	};

	scrollPic.circle = function(category){

                            var currentItem = category.find("li").first();
                            var currentIndex = currentItem.attr("index");
                            var thisI = category.next().find("i");
                           thisI.removeClass("cur-bar");
                           thisI.eq(currentIndex).addClass("cur-bar");
            };

            module.exports = scrollPic;


});