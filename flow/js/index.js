$(function() {

    // 模拟动态数据
    var dynamicData = { data: ["1.jpg", "2.jpg", "3.jpg", "4.jpg", "5.jpg", "6.jpg", "7.jpg", "8.jpg", "9.jpg", "10.jpg", "11.jpg", "12.jpg", "13.jpg", "14.jpg", "15.jpg", "16.jpg", "17.jpg", "18.jpg", "19.jpg", "20.jpg"] }

    // 页面加载的时候执行
    list();

    // 当窗口滚动时触发事件
    $(window).scroll(function() {

        // 获取列表中最后一个元素距离顶部的距离+最后元素高度的一半
        var lastHeight = $("#wrapper li:last").offset().top + $("#wrapper li:last").height() / 2;

        // 获取文档的高度+滚动条的高度
        var allHeight = $(this).height() + $(this).scrollTop();

        if (allHeight > lastHeight) {

            // 动态加载数据
            $(dynamicData.data).each(function(index, value) {
                var li = $("<li>");
                $("<a href='#'><img src='images/" + value + "' /></a><p><a href='#'>萌萌宠~</a></p>").appendTo(li);
                li.appendTo($("#wrapper ul"));
            });

        }

        list();

    });

    // 当窗口大小发生变化时重新加载数据
    $(window).resize(function() {
        list();
    });

});

function list() {

    // 声明一个数组存储第一行每个li的高度
    var heightArr = [];

    // 遍历瀑布流中的每一个li
    $("#wrapper li").each(function(index, value) {

        // 获取每行图片的个数
        var num = Math.floor($("#wrapper").width() / $(this).find('img').width());

        if (index < num) {
            // 将第一排的每个高度存放到数组
            heightArr[index] = $(this).height();

            // 当窗口由小变大的时候，第一排图片需要重新定位
            if (index > 0) {

                $(this).css({
                    position: "absolute",
                    top: "70px",
                    left: $("#wrapper li").eq(index - 1).position().left + $(this).width() + 10 + "px"
                });
            }

        } else {
            // 获取数组中最小高度
            var minHeight = Math.min.apply(null, heightArr);

            // 获取最小高度图片的位置
            var minIndex = $.inArray(minHeight, heightArr);

            $(this).css({
                position: "absolute",
                top: $("#wrapper li").eq(minIndex).position().top + minHeight + 5 + "px",
                left: $("#wrapper li").eq(minIndex).position().left + "px"
            });

            // 高度重置
            heightArr[minIndex] += $(this).height() + 5;
        }

    });

    // 设置背景层的高度
    $("#wrapper").css("height", Math.max.apply(null, heightArr));
}
