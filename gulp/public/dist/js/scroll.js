define(function(require, exports, module){

	var scrollPic = {};

	scrollPic.bannerObj = function(){

		$(".banner-left").click(function() {
	                    scrollPic.prescroll();
	                });

		 $(".banner-right").click(function() {
	                    scrollPic.nextscroll();
	                });

		 $(".bannerWrap").hover(function() {
		 	$(".bannerBtn").fadeIn(500);
		 }, function() {
		 	$(".bannerBtn").fadeOut(500);
		 });

		 setInterval(this.nextscroll, 2000);


		 // 下面小按钮点击
		  /*======btn====circle======*/
	                var animateEnd = 1;

	                $(".bannerSwitch li").click(function() {
	                	console.log(1);
	                    if (animateEnd == 0) {
	                        return;
	                    }

	                    $(this).addClass("bannerCur").siblings().removeClass("bannerCur");

	                    var nextindex = $(this).index();
	                    var currentindex = $(".bannerCon li").first().attr("index");
	                    var curr = $(".bannerCon li").first().clone();

	                    if (nextindex > currentindex) {

	                        for (var i = 0; i < nextindex - currentindex; i++) {

	                            var firstItem = $(".bannerCon li").first();
	                            $(".bannerCon ul").append(firstItem);

	                        }

	                        $(".bannerCon ul").prepend(curr);

	                        var offset = ($(".bannerCon li").width()) * -1;

	                        if (animateEnd == 1) {

	                            animateEnd = 0;
	                            $(".bannerCon").stop().animate({
	                                left: offset
	                            }, "slow", function() {

	                                $(".bannerCon ul li").first().remove();
	                                $(".bannerCon").css("left", "0px");
	                                animateEnd = 1;

	                            });

	                        }

	                    } else {

	                        var curt = $(".bannerCon li").last().clone();

	                        for (var i = 0; i < currentindex - nextindex; i++) {
	                            var lastItem = $(".bannerCon li").last();
	                            $(".bannerCon ul").prepend(lastItem);
	                        }

	                        $(".bannerCon ul").append(curt);

	                        var offset = ($(".bannerCon li").width()) * -1;

	                        $(".bannerCon").css("left", offset);


	                        if (animateEnd == 1) {

	                            animateEnd = 0;
	                            $(".bannerCon").stop().animate({
	                                left: "0px"
	                            }, "slow", function() {

	                                $(".bannerCon ul li").last().remove();
	                                animateEnd = 1;

	                            });

	                        }

	                    }

	                });
	};

	scrollPic.nextscroll = function(){
		// 下一个
	             var vcon = $(".bannerCon");
                            var offset = ($(".bannerCon li").width() * -1);

                            vcon.animate({
                                left: offset
                            }, "slow", function() {

                                var firstItem = $(".bannerCon ul li").first();
                                vcon.find("ul").append(firstItem);
                                $(this).css("left", "0px");

                               scrollPic.circle()

                            });
	};

	scrollPic.prescroll = function(){

		    // 前一个
		     var vcon = $(".bannerCon ");
	                    var offset = ($(".bannerCon li").width() * -1);

	                    var lastItem = $(".bannerCon ul li").last();

	                    vcon.find("ul").prepend(lastItem);
	                    vcon.css("left", offset);

	                    vcon.animate({
	                        left: "0px"
	                    }, "slow", function() {
	                        scrollPic.circle();
	                    })
	};

	scrollPic.circle = function(){

                            var currentItem = $(".bannerCon ul li").first();
                            var currentIndex = currentItem.attr("index");

                            $(".bannerSwitch li").removeClass("bannerCur");
                            $(".bannerSwitch li").eq(currentIndex).addClass("bannerCur");
            };

            module.exports = scrollPic;


});